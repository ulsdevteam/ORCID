<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Mailer\Mailer;

/**
 * OrcidBatchTriggers Controller
 *
 * @property \App\Model\Table\OrcidBatchTriggersTable $OrcidBatchTriggers
 * @method \App\Model\Entity\OrcidBatchTrigger[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrcidBatchTriggersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['OrcidStatusTypes', 'OrcidBatches', 'OrcidBatchGroups'],
        ];
        $orcidBatchTriggers = $this->paginate($this->OrcidBatchTriggers);

        $this->set(compact('orcidBatchTriggers'));
    }

    /**
     * View method
     *
     * @param string|null $id Orcid Batch Trigger id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $orcidBatchTrigger = $this->OrcidBatchTriggers->get($id, [
            'contain' => ['OrcidStatusTypes', 'OrcidBatches', 'OrcidBatchGroups'],
        ]);

        $this->set(compact('orcidBatchTrigger'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $orcidBatchTrigger = $this->OrcidBatchTriggers->newEmptyEntity();
        if ($this->request->is('post')) {
            $orcidBatchTrigger = $this->OrcidBatchTriggers->patchEntity($orcidBatchTrigger, $this->request->getData());
            if ($this->OrcidBatchTriggers->save($orcidBatchTrigger)) {
                $this->Flash->success(__('The orcid batch trigger has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid batch trigger could not be saved. Please, try again.'));
        }
        $orcidStatusTypes = $this->OrcidBatchTriggers->OrcidStatusTypes->find('list', ['limit' => 200])->all();
        $orcidBatches = $this->OrcidBatchTriggers->OrcidBatches->find('list', ['limit' => 200])->all();
        $reqBatches = [0 => __('No Requirement'), -1 => __('Require any prior Email')];
        $orcidBatchGroups = $this->OrcidBatchTriggers->OrcidBatchGroups->find('list', ['limit' => 200])->all();
        $this->set(compact('orcidBatchTrigger', 'orcidStatusTypes', 'orcidBatches', 'orcidBatchGroups', 'reqBatches'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Orcid Batch Trigger id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $orcidBatchTrigger = $this->OrcidBatchTriggers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $orcidBatchTrigger = $this->OrcidBatchTriggers->patchEntity($orcidBatchTrigger, $this->request->getData());
            if ($this->OrcidBatchTriggers->save($orcidBatchTrigger)) {
                $this->Flash->success(__('The orcid batch trigger has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid batch trigger could not be saved. Please, try again.'));
        }
        $reqBatches = [0 => __('No Requirement'), -1 => __('Require any prior Email')];
        $orcidStatusTypes = $this->OrcidBatchTriggers->OrcidStatusTypes->find('list', ['limit' => 200])->all();
        $orcidBatches = $this->OrcidBatchTriggers->OrcidBatches->find('list', ['limit' => 200])->all();
        $orcidBatchGroups = $this->OrcidBatchTriggers->OrcidBatchGroups->find('list', ['limit' => 200])->all();
        $this->set(compact('orcidBatchTrigger', 'orcidStatusTypes', 'orcidBatches', 'orcidBatchGroups', 'reqBatches'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Orcid Batch Trigger id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $orcidBatchTrigger = $this->OrcidBatchTriggers->get($id);
        if ($this->OrcidBatchTriggers->delete($orcidBatchTrigger)) {
            $this->Flash->success(__('The orcid batch trigger has been deleted.'));
        } else {
            $this->Flash->error(__('The orcid batch trigger could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * execute method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function execute($id = null) {
        $trigger = $this->OrcidBatchTriggers->get($id);
        $this->request->allowMethod('post');
        $Mailer = new Mailer();
        xdebug_break();
        if ($trigger->begin_date && $trigger->begin_date > time()) {
            $this->Session->setFlash(__('The Trigger has a future Begin Date.'));
        } else if ($this->executeTrigger($trigger)) {
            $this->Session->setFlash(__('The Trigger has run.'), 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash(__('The Trigger could not be run. Please, try again.'));
        }
        return $this->redirect(array('action' => 'view', $id));
    }

    /**
    * executeAll method
    *
    * @return void
    */
    public function executeAll() {
        $this->request->allowMethod('post');
        $Mailer = new Mailer();
        // must not be already sent or cancelled
        $triggers = $this->OrcidBatchTrigger->find('all', array('conditions' => array('or' => array(array('begin_date <=' => 'today'), array('begin_date' => NULL))), 'order' => array('require_batch_id DESC')));
        $success = 0;
        $failed = 0;
        xdebug_break();
        foreach ($triggers as $trigger) {
            if ($this->executeTrigger($trigger)) {
                $success++;
            } else {
                $failed++;
            }
        }
        if ($success) {
            $this->Session->setFlash(__('Successfully ran '.$success.' trigger'.($success > 1 ? 's' : '')), 'default', array('class' => 'success'));
        }
        if ($failed) {
            $this->Session->setFlash(__('Failed '.$failed.' trigger run'.($failed > 1 ? 's' : '')));
        }
        if (!$success && !$failed) {
            $this->Session->setFlash(__('No triggers to run.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function executeTrigger($trigger) {
        // Abort if OrcidTrigger does not contain expected information
		if (!isset($trigger->OrcidBatchTrigger) || !isset($trigger->OrcidStatusType)) {
			return false;
		}
		// Trigger may not run prior to begin_date
		if (isset($trigger['OrcidBatchTrigger']['begin_date']) && strtotime($trigger['OrcidBatchTrigger']['begin_date']) > time() ) {
			return false;
		}
		$failures = 0;
		// We'll use OrcidEmail to create new emails
		$this->OrcidEmail = ClassRegistry::init('OrcidEmail');
		// We'll use OrcidStatus to ensure the user is at the trigger criteria
		$this->CurrentOrcidStatus = ClassRegistry::init('CurrentOrcidStatus');
		// We'll use OrcidBatchGroup to collect relevant users
		$this->OrcidBatchGroup = ClassRegistry::init('OrcidBatchGroup');
		// If sequence is 0 a group is required.  We can't initialize everyone.
		if ($trigger['OrcidStatusType']['seq'] == 0 && !isset($trigger['OrcidBatchGroup'])) {
			return false;
		}
		// Process each user at the status for the trigger_delay days
		$options = array('conditions' => array('CurrentOrcidStatus.orcid_status_type_id' => $trigger['OrcidStatusType']['id'], 'TRUNC(CurrentOrcidStatus.status_timestamp + '.$trigger['OrcidBatchTrigger']['trigger_delay'].') <=' => date('Y-m-d')));
		// This will be our selection of users
		$users = array();
		if (isset($trigger['OrcidBatchGroup']['id'])) {
			$users = $this->OrcidBatchGroup->getAssociatedUsers( $trigger['OrcidBatchGroup']['id'], 'CurrentOrcidStatus.orcid_user_id' );
			$options['conditions'][] = $users;
		}
		$userStatuses = $this->CurrentOrcidStatus->find('all', $options);
		foreach ($userStatuses as $userStatus) {
			// If a prior email is required, check for it
			if ($trigger['OrcidBatchTrigger']['require_batch_id']) {
				$options = array('recursive' => -1, 'conditions' => array('OrcidEmail.orcid_user_id' => $userStatus['CurrentOrcidStatus']['orcid_user_id']));
				if ($trigger['OrcidBatchTrigger']['require_batch_id'] !== -1) {
					$options['conditions']['OrcidEmail.orcid_batch_id'] = $trigger['OrcidBatchTrigger']['require_batch_id'];
				}
				if (!$this->OrcidEmail->find('first', $options)) {
					// if the prior email was not found, skip
					continue;
				}
			}
			// Create unless the email already exists
			$options = array('recursive' => -1, 'conditions' => array('OrcidEmail.orcid_user_id' => $userStatus['CurrentOrcidStatus']['orcid_user_id'], 'OrcidEmail.orcid_batch_id' => $trigger['OrcidBatch']['id']));
			// If a maximum repeat is set, count the number of times sent
			if ($trigger['OrcidBatchTrigger']['maximum_repeat']) {
				if ($this->OrcidEmail->find('count', $options) >= $trigger['OrcidBatchTrigger']['maximum_repeat']) {
					// if already at or past the limit, skip
					continue;
				}
			}
			// If this email is repeating, also check the last sent date
			if ($trigger['OrcidBatchTrigger']['repeat']) {
				$options['conditions']['TRUNC(NVL(OrcidEmail.sent, SYSDATE) + '.$trigger['OrcidBatchTrigger']['repeat'].') >'] = date('Y-m-d');
			}
			if (!$this->OrcidEmail->find('first', $options)) {
				$this->OrcidEmail->create();
				$newEmail['OrcidEmail'] = array('orcid_user_id' => $userStatus['CurrentOrcidStatus']['orcid_user_id'], 'orcid_batch_id' => $trigger['OrcidBatch']['id'], 'queued' => date('Y-m-d H:i:s'));
				if (!$this->OrcidEmail->save($newEmail)) {
					$failures++;
				}
			}
		}
		return !$failures;
    }

}
