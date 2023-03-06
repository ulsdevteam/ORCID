<?php

declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @since         2.2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Utility;

use ArrayAccess;
use InvalidArgumentException;
use RuntimeException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Mailer\Mailer;
use Exception;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;

class Emailer
{

	use LocatorAwareTrait;

	/**
	 * Verify that all connections are available
	 * 
	 * @return boolean Success on all connections
	 */
	public function connected()
	{
		$manager = new ConnectionManager();
		$connections = $manager->configured();
		$oracleConnectionToRemove = (Configure::read('debug')) ? 'production-default' : 'default';
		$cdsConnectionToRemove = (Configure::read('debug')) ? 'production-cds' : 'default-cds';
		$connections = array_flip($connections);
		unset($connections[$oracleConnectionToRemove]);
		unset($connections[$cdsConnectionToRemove]);
		$connections = array_flip($connections);
		foreach ($connections as $name) {
			$connection = $manager->get($name);
			$driver = $connection->getDriver();
			$connected = $driver->connect();
			if (!$connected) {
				return false;
			}
		}
		return true;
	}

	/**
	 * List any connection that is failing
	 * 
	 * @return array Names of failed connections
	 */
	public function getFailedConnections()
	{
		$manager = new ConnectionManager();
		$problems = [];
		$connections = $manager->configured();
		$oracleConnectionToRemove = (Configure::read('debug')) ? 'production-default' : 'default';
		$cdsConnectionToRemove = (Configure::read('debug')) ? 'production-cds' : 'default-cds';
		$connections = array_flip($connections);
		unset($connections[$oracleConnectionToRemove]);
		unset($connections[$cdsConnectionToRemove]);
		$connections = array_flip($connections);
		foreach ($connections as $name) {
			$connection = $manager->get($name);
			$driver = $connection->getDriver();
			$connected = $driver->isConnected();
			if (!$connected) {
				$problems[] = $connection->configName();
			}
		}
		return $problems;
	}


	/**
	 * Send an email, updates SENT if sendBatch is successful
	 * 
	 * @param \App\Model\Entity\OrcidUser $user The orcid user to send the mail to
	 * @param \App\Model\Entity\OrcidEmail $orcidEmail The email that contains a batch
	 * @return boolean successful send
	 */
	public function sendEmail($user, $orcidEmail)
	{
		if ($this->sendBatch($user->email, $orcidEmail->orcid_batch, $user->displayname)) {
			$orcidEmail->SENT = FrozenTime::now();
			$orcidEmailsTable = $this->fetchTable('OrcidEmails');
			return $orcidEmailsTable->save($orcidEmail);
		}
		return false;
	}

	/** 
	 * Send a batch message to a person
	 * 
	 * @param string $toRecipient The email address to send the mail to
	 * @param \App\Model\Entity\OrcidBatch $orcidBatch The batch to send, may contain an OrcidEmail to mark sent
	 * @param string? $displayName The name to refer to the subject as in the set To method
	 * @return boolean Successful send
	 */
	public function sendBatch($toRecipient, $orcidBatch, $displayName = null)
	{
		$Mailer = new Mailer();
		if (Configure::read('debug')) {
			$toRecipientHold = str_replace('@', '.', $toRecipient) . '@mailinator.com';
			$toRecipient = "Trl75.pitt.edu@mailinator.com";
		}
		$Mailer
			->setFrom($orcidBatch->FROM_ADDR, $orcidBatch->FROM_NAME)
			->setReturnPath($orcidBatch->REPLY_TO ? $orcidBatch->REPLY_TO : $orcidBatch->FROM_ADDR)
			->setReplyTo($orcidBatch->REPLY_TO ? $orcidBatch->REPLY_TO : $orcidBatch->FROM_ADDR)
			->setTo($toRecipient, $displayName)
			->setSubject($orcidBatch->SUBJECT);
		$Mailer
			->setEmailFormat('html')
			->viewBuilder()
			->setTemplate('rendered')
			->setLayout('default')
			->setVar('body', $orcidBatch->BODY);
		try {
			$Mailer->send();
			return true;
		} catch (Exception $e) {
			Log::write('error', 'ORCID@PITT: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Execute a trigger to queue emails
	 * 
	 * @param \App\Model\Entity\OrcidBatchTrigger $trigger with at least one recursion
	 * @return boolean
	 */
	public function executeTrigger($trigger)
	{
		// Abort if OrcidTrigger does not contain expected information
		if (!isset($trigger) || !isset($trigger->orcid_status_type)) {
			return false;
		}

		// Trigger may not run prior to begin_date
		if (isset($trigger->BEGIN_DATE) && $trigger->BEGIN_DATE->timestamp > time()) {
			return false;
		}

		$failures = 0;

		// We'll use OrcidEmailTable to create new emails
		$OrcidEmailTable = $this->fetchTable('OrcidEmails');

		// We'll use OrcidStatusTable to ensure the user is at the trigger criteria
		$CurrentOrcidStatusTable = $this->fetchTable('CurrentOrcidStatuses');

		// We'll use OrcidBatchGroupTable to collect relevant users
		$OrcidBatchGroupTable = $this->fetchTable('OrcidBatchGroups');

		// If sequence is 0 a group is required.  We can't initialize everyone.
		if ($trigger->orcid_status_type->SEQ == 0 && !isset($trigger->orcid_batch_group)) {
			return false;
		}
		
		$userStatuses = $CurrentOrcidStatusTable->find('all')->where(['CurrentOrcidStatuses.ORCID_STATUS_TYPE_ID' => $trigger->ORCID_STATUS_TYPE_ID]);

		// This will be our selection of users
		$users = [];

		if (isset($trigger->orcid_batch_group->ID)) {
			$groupId = $trigger->orcid_batch_group->ID;

			if (!$OrcidBatchGroupTable->updateCache($groupId)) {
				return false;
			}

			$users = $OrcidBatchGroupTable->OrcidBatchGroupCaches->find('all')->select(['ORCID_USER_ID'])->where(['ORCID_BATCH_GROUP_ID' => $groupId]);
			$userStatuses->where(['ORCID_USER_ID IN' => $users]);
		}
		
		$triggerDate = new FrozenTime($trigger->TRIGGER_DELAY . ' days ago');
		$userStatuses->where(['CurrentOrcidStatuses.STATUS_TIMESTAMP <=' => $triggerDate]);
		
		foreach ($userStatuses as $userStatus) {
			// If a prior email is required, check for it
			
			if (isset($trigger->REQUIRE_BATCH_ID) || $trigger->REQUIRE_BATCH_ID === 0) {
				$emailQuery = $OrcidEmailTable->find('all')->where(['OrcidEmails.ORCID_USER_ID' => $userStatus->ORCID_USER_ID]);
				if ($trigger->REQUIRE_BATCH_ID !== -1) {
					$emailQuery->where(['OrcidEmails.ORCID_BATCH_ID' => $trigger->REQUIRE_BATCH_ID]);
				}
				if (!$emailQuery->first()) {
					// if the prior email was not found, skip
					continue;
				}
			}
			// Create unless the email already exists
			$emailQuery = $OrcidEmailTable->find('all')->where(['OrcidEmails.ORCID_USER_ID' => $userStatus->ORCID_USER_ID, 'OrcidEmails.ORCID_BATCH_ID' => $trigger->ORCID_BATCH_ID]);
			// If a maximum repeat is set, count the number of times sent
			if ($trigger->MAXIMUM_REPEAT) {
				if ($emailQuery->count() >= $trigger->maximum_repeat) {
					// if already at or past the limit, skip
					continue;
				}
			}
			// If this email is repeating, also check the last sent date
			if ($trigger->REPEAT) {
				$repeatDate = new FrozenTime($trigger->REPEAT . " days ago");
				$emailQuery->where(['OR' => [
					'OrcidEmails.SENT IS' => NULL,
					'OrcidEmails.SENT >' => $repeatDate,
				]]);
			}
			if (!$emailQuery->first()) {
				$newEmail = $OrcidEmailTable->newEntity(['ORCID_USER_ID' => $userStatus->ORCID_USER_ID, 'ORCID_BATCH_ID' => $trigger->ORCID_BATCH_ID, 'QUEUED' => FrozenTime::now()]);
				if ($OrcidEmailTable->save($newEmail) !== false ) {
					$failures++;
				}
			}
		}
		return !$failures;
	}
}
