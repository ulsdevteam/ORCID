<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use App\Utility\Emailer;
use Cake\I18n\FrozenTime;
use Cake\Console\ConsoleOptionParser;

class EmailCommand extends Command
{

	private $_emailStormCounter;
	private $_emailStormStopper;
	private $_hoursBetweenRetry;
	private $_maxErrorCount;

	public function __construct()
    {
		$this->_emailStormCounter = TMP . 'email_storm_count';
		$this->_emailStormStopper = TMP . 'email_storm_blocked';
		$this->_hoursBetweenRetry = 4;
		$this->_maxErrorCount = 10;
		parent::__construct();
	}

	protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addArgument('function', [
            'help' => 'What is your name'
        ]);
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
		$function = $args->getArgument('function');
		if (isset($function)) {
			switch ($function) {
				case "sendAll":
					$this->sendAll($io);
					break;
				case "queueAll":
					$this->queueAll($io);
					break;
				default:
					$io->abort("Incorrect command");
			}
			
			return static::CODE_SUCCESS;
		}
		$io->out(__d('cake_console', 'Interactive ORCID Email Shell'));
		$io->hr();
		$io->out(__d('cake_console', '[Q]ueue Emails'));
		$io->out(__d('cake_console', '[S]end Emails'));
		$io->out(__d('cake_console', '[E]xit Shell'));
		$in = strtoupper($io->askChoice(__d('cake_console', 'What would you like to do?'), ['Q','S','E']));
        switch ($in) {
			case 'Q':
				$this->queueAll($io);
				break;
			case 'S':
				$this->sendAll($io);
				break;
			default:
			case 'E':
				return static::CODE_SUCCESS;
			}

        return static::CODE_SUCCESS;
    }

	/** 
	 * Send all queued batch emails
	 */
	public function sendAll(ConsoleIo $io) {
		if ($this->_preventEmailStorm()) {
			return;
		}
		$this->Emailer = new Emailer();
		if (!$this->Emailer->connected()) {
			$failed = $this->Emailer->getFailedConnections();
			$this->_forecastEmailStorm($io);
			$io->abort('Some resource connectivity ('.implode(',', $failed).') is unavailable.  Aborting.');
		}
		$orcidEmailTable = $this->fetchTable('OrcidEmails');
		$usersTable = $this->fetchTable('OrcidUsers');
		// must not be already sent or cancelled
		$options = ['conditions' => 
			['OrcidEmails.SENT IS' => NULL,
			 'OrcidEmails.CANCELLED IS' => NULL]
		];
		$emails = $orcidEmailTable->find('all', $options)->contain(["OrcidUsers", "OrcidBatches"]);
		$success = 0;
		$failed = 0;
		foreach ($emails as $email) {
			// TODO: warning: hardcoded foreign key relationship
			// These need changed to use OrcidUsers since there is no Person table
			$condition = '(cn='.$email->orcid_user->USERNAME.')';
			$person = $usersTable->definitionSearch($condition, ['mail']);
			if (!isset($person) || !isset($person['mail'])) {
				$io->out('No email address for '.$email->orcid_user->USERNAME.'.');
			} elseif ($this->Emailer->sendEmail($person['mail'], $email)) {
				$success++;
			} else {
				$failed++;
			}
		}
		if ($success) {
			$io->out('Sent '.$success.' email'.($success > 1 ? 's' : '').'.');
		} elseif (!$failed) {
			$io->out('No email scheduled to send.');
		}
		if ($failed) {
			$this->_forecastEmailStorm($io);
			$io->error('Failed to send '.$failed.' email'.($failed > 1 ? 's' : ''));
		}
		$this->_forgetEmailStorm();
	}

	/** 
	 * Queued any batch emails
	 */
	public function queueAll(ConsoleIo $io) {
		if ($this->_preventEmailStorm()) {
			return;
		}
		$this->Emailer = new Emailer();
		if (!$this->Emailer->connected()) {
			$failed = $this->Emailer->getFailedConnections();
			$this->_forecastEmailStorm($io);
			$io->abort('Some resource connectivity ('.implode(',', $failed).') is unavailable.  Aborting.');
		}
		$orcidBatchTriggerTable = $this->fetchTable('OrcidBatchTriggers');
		$triggers = $orcidBatchTriggerTable->find('all', 
		['conditions' => 
			[
				'or' => [
					['BEGIN_DATE <=' => FrozenTime::now()], 
					['BEGIN_DATE IS' => NULL]
				]
			],
		'contain' => ['OrcidStatusTypes', 'OrcidBatches', 'OrcidBatchGroups']
		])->all();
		$success = 0;
		$failed = 0;
		foreach ($triggers as $trigger) {
			if ($this->Emailer->executeTrigger($trigger)) {
				$success++;
			} else {
				$failed++;
			}
		}
		if ($success) {
			$io->out('Successfully ran '.$success.' trigger'.($success > 1 ? 's' : ''));
		} elseif (!$failed) {
			$io->out('No triggers to run.');
		}
		if ($failed) {
			$this->_forecastEmailStorm($io);
			$io->error('Failed '.$failed.' trigger run'.($failed > 1 ? 's' : ''));
		}
		$this->_forgetEmailStorm();
	}

	/**
	 * Return true if the stop_email_processing file exists, unless it has aged out
	 */
	private function _preventEmailStorm() {
		if (file_exists($this->_emailStormStopper)) {
			clearstatcache();
			if (time() - filemtime($this->_emailStormStopper) > 60 * 60 * $this->_hoursBetweenRetry) {
				unlink($this->_emailStormStopper);
			} else {
				return true;
			}
		}
		return false;
	}

	/**
	 * Create a semaphore indicating that an error condition exists, and that error emails should be prevented
	 */
	private function _forecastEmailStorm(ConsoleIO $io) {
		$count = intval(file_get_contents($this->_emailStormCounter));
		$count++;
		file_put_contents($this->_emailStormCounter, $count);
		if ($count >= $this->_maxErrorCount) {
			touch($this->_emailStormStopper);
			unlink($this->_emailStormCounter);
			$io->abort('There have been '.$this->_maxErrorCount.' consecutive errors reported.  Discontinuing error emails for '.$this->_hoursBetweenRetry.' hours, unless manual intervention is taken.');
		}
		return false;
	}

	/**
	 * Clear a pending email storm count
	 */
	private function _forgetEmailStorm() {
		if (file_exists($this->_emailStormCounter)) {
			unlink($this->_emailStormCounter);
		}
		if (file_exists($this->_emailStormStopper)) {
			unlink($this->_emailStormStopper);
		}
	}
}