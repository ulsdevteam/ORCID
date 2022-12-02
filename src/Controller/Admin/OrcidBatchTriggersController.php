<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Utility\Emailer;
use Cake\I18n\FrozenTime;

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
            if ($this->OrcidBatchTriggers->save($orcidBatchTrigger) !== false ) {
                $this->Flash->success(__('The Trigger has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Trigger could not be saved. Please, try again.'));
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
            if ($this->OrcidBatchTriggers->save($orcidBatchTrigger) !== false ) {
                $this->Flash->success(__('The Trigger has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Trigger could not be saved. Please, try again.'));
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
            $this->Flash->success(__('The Trigger has been deleted.'));
        } else {
            $this->Flash->error(__('The Trigger could not be deleted. Please, try again.'));
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
    public function execute($id = null)
    {
        $trigger = $this->OrcidBatchTriggers->get($id, ['contain' => ['OrcidStatusTypes', 'OrcidBatchGroups']]);
        $this->request->allowMethod('post');
        $this->Emailer = new Emailer();
        if (isset($trigger->BEGIN_DATE) && $trigger->BEGIN_DATE->greaterThan(FrozenTime::now())) {
            $this->Flash->error(__('The Trigger has a future Begin Date.'));
        } elseif ($this->Emailer->executeTrigger($trigger)) {
            $this->Flash->success(__('The Trigger has run.'));
        } else {
            $this->Flash->error(__('The Trigger could not be run. Please, try again.'));
        }
        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * executeAll method
     *
     * @return void
     */
    public function executeAll()
    {
        $this->request->allowMethod('post');
        // must not be already sent or cancelled
        $triggers = $this->OrcidBatchTrigger->find('all', ['conditions' => ['or' => [['begin_date <=' => 'today'], ['begin_date' => NULL]], 'order' => ['require_batch_id DESC']]]);
        $success = 0;
        $failed = 0;
        $this->Emailer = new Emailer();
        foreach ($triggers as $trigger) {
            if ($this->Emailer->executeTrigger($trigger)) {
                $success++;
            } else {
                $failed++;
            }
        }
        if ($success) {
            $this->Flash->success(__('Successfully ran ' . $success . ' trigger' . ($success > 1 ? 's' : '')), 'default', ['class' => 'success']);
        }
        if ($failed) {
            $this->Flash->error(__('Failed ' . $failed . ' trigger run' . ($failed > 1 ? 's' : '')));
        }
        if (!$success && !$failed) {
            $this->Flash->error(__('No triggers to run.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
