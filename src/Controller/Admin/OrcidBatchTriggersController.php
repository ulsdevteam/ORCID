<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

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
            'contain' => ['OrcidStatusTypes', 'OrcidBatches', 'OrcidBatchGroups', 'RequireBatches'],
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
            'contain' => ['OrcidStatusTypes', 'OrcidBatches', 'OrcidBatchGroups', 'RequireBatches'],
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
        $orcidBatchGroups = $this->OrcidBatchTriggers->OrcidBatchGroups->find('list', ['limit' => 200])->all();
        $requireBatches = $this->OrcidBatchTriggers->RequireBatches->find('list', ['limit' => 200])->all();
        $this->set(compact('orcidBatchTrigger', 'orcidStatusTypes', 'orcidBatches', 'orcidBatchGroups', 'requireBatches'));
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
        $orcidStatusTypes = $this->OrcidBatchTriggers->OrcidStatusTypes->find('list', ['limit' => 200])->all();
        $orcidBatches = $this->OrcidBatchTriggers->OrcidBatches->find('list', ['limit' => 200])->all();
        $orcidBatchGroups = $this->OrcidBatchTriggers->OrcidBatchGroups->find('list', ['limit' => 200])->all();
        $requireBatches = $this->OrcidBatchTriggers->RequireBatches->find('list', ['limit' => 200])->all();
        $this->set(compact('orcidBatchTrigger', 'orcidStatusTypes', 'orcidBatches', 'orcidBatchGroups', 'requireBatches'));
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
}
