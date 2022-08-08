<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * OrcidStatuses Controller
 *
 * @property \App\Model\Table\OrcidStatusesTable $OrcidStatuses
 * @method \App\Model\Entity\OrcidStatus[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrcidStatusesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['OrcidUsers', 'OrcidStatusTypes'],
        ];
        $orcidStatuses = $this->paginate($this->OrcidStatuses);

        $this->set(compact('orcidStatuses'));
    }

    /**
     * View method
     *
     * @param string|null $id Orcid Status id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $orcidStatus = $this->OrcidStatuses->get($id, [
            'contain' => ['OrcidUsers', 'OrcidStatusTypes'],
        ]);

        $this->set(compact('orcidStatus'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $orcidStatus = $this->OrcidStatuses->newEmptyEntity();
        if ($this->request->is('post')) {
            $orcidStatus = $this->OrcidStatuses->patchEntity($orcidStatus, $this->request->getData());
            if ($this->OrcidStatuses->save($orcidStatus)) {
                $this->Flash->success(__('The orcid status has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid status could not be saved. Please, try again.'));
        }
        $orcidUsers = $this->OrcidStatuses->OrcidUsers->find('list', ['limit' => 200])->all();
        $orcidStatusTypes = $this->OrcidStatuses->OrcidStatusTypes->find('list', ['limit' => 200])->all();
        $this->set(compact('orcidStatus', 'orcidUsers', 'orcidStatusTypes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Orcid Status id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $orcidStatus = $this->OrcidStatuses->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $orcidStatus = $this->OrcidStatuses->patchEntity($orcidStatus, $this->request->getData());
            if ($this->OrcidStatuses->save($orcidStatus)) {
                $this->Flash->success(__('The orcid status has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid status could not be saved. Please, try again.'));
        }
        $orcidUsers = $this->OrcidStatuses->OrcidUsers->find('list', ['limit' => 200])->all();
        $orcidStatusTypes = $this->OrcidStatuses->OrcidStatusTypes->find('list', ['limit' => 200])->all();
        $this->set(compact('orcidStatus', 'orcidUsers', 'orcidStatusTypes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Orcid Status id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $orcidStatus = $this->OrcidStatuses->get($id);
        if ($this->OrcidStatuses->delete($orcidStatus)) {
            $this->Flash->success(__('The orcid status has been deleted.'));
        } else {
            $this->Flash->error(__('The orcid status could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
