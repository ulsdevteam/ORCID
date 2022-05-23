<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * OrcidBatches Controller
 *
 * @property \App\Model\Table\OrcidBatchesTable $OrcidBatches
 * @method \App\Model\Entity\OrcidBatch[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrcidBatchesController extends AppController
{


    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['OrcidBatchCreators'],
        ];
        $orcidBatches = $this->paginate($this->OrcidBatches);

        $this->set(compact('orcidBatches'));
    }

    /**
     * View method
     *
     * @param string|null $id Orcid Batch id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $orcidBatch = $this->OrcidBatches->get($id, [
            'contain' => ['OrcidBatchCreators', 'OrcidBatchTriggers', 'OrcidEmails'],
        ]);

        $this->set(compact('orcidBatch'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $orcidBatch = $this->OrcidBatches->newEmptyEntity();
        if ($this->request->is('post')) {
            $orcidBatch = $this->OrcidBatches->patchEntity($orcidBatch, $this->request->getData());
            if ($this->OrcidBatches->save($orcidBatch)) {
                $this->Flash->success(__('The orcid batch has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid batch could not be saved. Please, try again.'));
        }
        $orcidBatchCreators = $this->OrcidBatches->OrcidBatchCreators->find('list', ['limit' => 200])->all();
        $this->set(compact('orcidBatch', 'orcidBatchCreators'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Orcid Batch id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $orcidBatch = $this->OrcidBatches->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $orcidBatch = $this->OrcidBatches->patchEntity($orcidBatch, $this->request->getData());
            if ($this->OrcidBatches->save($orcidBatch)) {
                $this->Flash->success(__('The orcid batch has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid batch could not be saved. Please, try again.'));
        }
        $orcidBatchCreators = $this->OrcidBatches->OrcidBatchCreators->find('list', ['limit' => 200])->all();
        $this->set(compact('orcidBatch', 'orcidBatchCreators'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Orcid Batch id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $orcidBatch = $this->OrcidBatches->get($id);
        if ($this->OrcidBatches->delete($orcidBatch)) {
            $this->Flash->success(__('The orcid batch has been deleted.'));
        } else {
            $this->Flash->error(__('The orcid batch could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
