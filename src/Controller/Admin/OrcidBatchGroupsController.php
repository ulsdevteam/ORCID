<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * OrcidBatchGroups Controller
 *
 * @property \App\Model\Table\OrcidBatchGroupsTable $OrcidBatchGroups
 * @method \App\Model\Entity\OrcidBatchGroup[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrcidBatchGroupsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $orcidBatchGroups = $this->paginate($this->OrcidBatchGroups);

        $this->set(compact('orcidBatchGroups'));
    }

    /**
     * View method
     *
     * @param string|null $id Orcid Batch Group id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $orcidBatchGroup = $this->OrcidBatchGroups->get($id, [
            'contain' => ['OrcidBatchGroupCaches', 'OrcidBatchTriggers'],
        ]);

        $this->set(compact('orcidBatchGroup'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $orcidBatchGroup = $this->OrcidBatchGroups->newEmptyEntity();
        if ($this->request->is('post')) {
            $orcidBatchGroup = $this->OrcidBatchGroups->patchEntity($orcidBatchGroup, $this->request->getData());
            if ($this->OrcidBatchGroups->save($orcidBatchGroup)) {
                $this->Flash->success(__('The orcid batch group has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid batch group could not be saved. Please, try again.'));
        }
        $this->set(compact('orcidBatchGroup'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Orcid Batch Group id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $orcidBatchGroup = $this->OrcidBatchGroups->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $orcidBatchGroup = $this->OrcidBatchGroups->patchEntity($orcidBatchGroup, $this->request->getData());
            if ($this->OrcidBatchGroups->save($orcidBatchGroup)) {
                $this->Flash->success(__('The orcid batch group has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid batch group could not be saved. Please, try again.'));
        }
        $this->set(compact('orcidBatchGroup'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Orcid Batch Group id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $orcidBatchGroup = $this->OrcidBatchGroups->get($id);
        if ($this->OrcidBatchGroups->delete($orcidBatchGroup)) {
            $this->Flash->success(__('The orcid batch group has been deleted.'));
        } else {
            $this->Flash->error(__('The orcid batch group could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * recache method
     *
     * @param string|null $id Orcid Batch Group id.
     * @return \Cake\Http\Response|null|void Redirects to view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function recache($id = null)
    {
        $orcidBatchGroup = $this->OrcidBatchGroups->get($id);
        $orcidBatchGroup->set('cache_creation_date', null);
        if ($this->OrcidBatchGroups->save($orcidBatchGroup)) {
            $this->Flash->success(__('The Group cache has been expired.'));
        } else {
            $this->Flash->error(__('The Group cache could not be expired. Please, try again.'));
        }
        return $this->redirect(['action' => 'view', $id]);
    }
}
