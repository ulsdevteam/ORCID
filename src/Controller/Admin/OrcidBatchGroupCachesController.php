<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * OrcidBatchGroupCaches Controller
 *
 * @property \App\Model\Table\OrcidBatchGroupCachesTable $OrcidBatchGroupCaches
 * @method \App\Model\Entity\OrcidBatchGroupCache[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrcidBatchGroupCachesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['OrcidBatchGroups', 'OrcidUsers'],
        ];
        $orcidBatchGroupCaches = $this->paginate($this->OrcidBatchGroupCaches);

        $this->set(compact('orcidBatchGroupCaches'));
    }

    /**
     * View method
     *
     * @param string|null $id Orcid Batch Group Cache id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $orcidBatchGroupCache = $this->OrcidBatchGroupCaches->get($id, [
            'contain' => ['OrcidBatchGroups', 'OrcidUsers'],
        ]);

        $this->set(compact('orcidBatchGroupCache'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $orcidBatchGroupCache = $this->OrcidBatchGroupCaches->newEmptyEntity();
        if ($this->request->is('post')) {
            $orcidBatchGroupCache = $this->OrcidBatchGroupCaches->patchEntity($orcidBatchGroupCache, $this->request->getData());
            if ($this->OrcidBatchGroupCaches->save($orcidBatchGroupCache)) {
                $this->Flash->success(__('The orcid batch group cache has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid batch group cache could not be saved. Please, try again.'));
        }
        $orcidBatchGroups = $this->OrcidBatchGroupCaches->OrcidBatchGroups->find('list', ['limit' => 200])->all();
        $orcidUsers = $this->OrcidBatchGroupCaches->OrcidUsers->find('list', ['limit' => 200])->all();
        $this->set(compact('orcidBatchGroupCache', 'orcidBatchGroups', 'orcidUsers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Orcid Batch Group Cache id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $orcidBatchGroupCache = $this->OrcidBatchGroupCaches->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $orcidBatchGroupCache = $this->OrcidBatchGroupCaches->patchEntity($orcidBatchGroupCache, $this->request->getData());
            if ($this->OrcidBatchGroupCaches->save($orcidBatchGroupCache)) {
                $this->Flash->success(__('The orcid batch group cache has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid batch group cache could not be saved. Please, try again.'));
        }
        $orcidBatchGroups = $this->OrcidBatchGroupCaches->OrcidBatchGroups->find('list', ['limit' => 200])->all();
        $orcidUsers = $this->OrcidBatchGroupCaches->OrcidUsers->find('list', ['limit' => 200])->all();
        $this->set(compact('orcidBatchGroupCache', 'orcidBatchGroups', 'orcidUsers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Orcid Batch Group Cache id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $orcidBatchGroupCache = $this->OrcidBatchGroupCaches->get($id);
        if ($this->OrcidBatchGroupCaches->delete($orcidBatchGroupCache)) {
            $this->Flash->success(__('The orcid batch group cache has been deleted.'));
        } else {
            $this->Flash->error(__('The orcid batch group cache could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
