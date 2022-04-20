<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * OrcidStatusTypes Controller
 *
 * @property \App\Model\Table\OrcidStatusTypesTable $OrcidStatusTypes
 * @method \App\Model\Entity\OrcidStatusType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrcidStatusTypesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $orcidStatusTypes = $this->paginate($this->OrcidStatusTypes);

        $this->set(compact('orcidStatusTypes'));
    }

    /**
     * View method
     *
     * @param string|null $id Orcid Status Type id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $orcidStatusType = $this->OrcidStatusTypes->get($id, [
            'contain' => ['OrcidBatchTriggers', 'OrcidStatuses'],
        ]);

        $this->set(compact('orcidStatusType'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $orcidStatusType = $this->OrcidStatusTypes->newEmptyEntity();
        if ($this->request->is('post')) {
            $orcidStatusType = $this->OrcidStatusTypes->patchEntity($orcidStatusType, $this->request->getData());
            if ($this->OrcidStatusTypes->save($orcidStatusType)) {
                $this->Flash->success(__('The orcid status type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid status type could not be saved. Please, try again.'));
        }
        $this->set(compact('orcidStatusType'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Orcid Status Type id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $orcidStatusType = $this->OrcidStatusTypes->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $orcidStatusType = $this->OrcidStatusTypes->patchEntity($orcidStatusType, $this->request->getData());
            if ($this->OrcidStatusTypes->save($orcidStatusType)) {
                $this->Flash->success(__('The orcid status type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid status type could not be saved. Please, try again.'));
        }
        $this->set(compact('orcidStatusType'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Orcid Status Type id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $orcidStatusType = $this->OrcidStatusTypes->get($id);
        if ($this->OrcidStatusTypes->delete($orcidStatusType)) {
            $this->Flash->success(__('The orcid status type has been deleted.'));
        } else {
            $this->Flash->error(__('The orcid status type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
