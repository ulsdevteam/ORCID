<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * OrcidEmails Controller
 *
 * @property \App\Model\Table\OrcidEmailsTable $OrcidEmails
 * @method \App\Model\Entity\OrcidEmail[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrcidEmailsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['OrcidUsers', 'OrcidBatches'],
        ];
        $orcidEmails = $this->paginate($this->OrcidEmails);

        $this->set(compact('orcidEmails'));
    }

    /**
     * View method
     *
     * @param string|null $id Orcid Email id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $orcidEmail = $this->OrcidEmails->get($id, [
            'contain' => ['OrcidUsers', 'OrcidBatches'],
        ]);

        $this->set(compact('orcidEmail'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $orcidEmail = $this->OrcidEmails->newEmptyEntity();
        if ($this->request->is('post')) {
            $orcidEmail = $this->OrcidEmails->patchEntity($orcidEmail, $this->request->getData());
            if ($this->OrcidEmails->save($orcidEmail)) {
                $this->Flash->success(__('The orcid email has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid email could not be saved. Please, try again.'));
        }
        $orcidUsers = $this->OrcidEmails->OrcidUsers->find('list', ['limit' => 200])->all();
        $orcidBatches = $this->OrcidEmails->OrcidBatches->find('list', ['limit' => 200])->all();
        $this->set(compact('orcidEmail', 'orcidUsers', 'orcidBatches'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Orcid Email id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $orcidEmail = $this->OrcidEmails->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $orcidEmail = $this->OrcidEmails->patchEntity($orcidEmail, $this->request->getData());
            if ($this->OrcidEmails->save($orcidEmail)) {
                $this->Flash->success(__('The orcid email has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid email could not be saved. Please, try again.'));
        }
        $orcidUsers = $this->OrcidEmails->OrcidUsers->find('list', ['limit' => 200])->all();
        $orcidBatches = $this->OrcidEmails->OrcidBatches->find('list', ['limit' => 200])->all();
        $this->set(compact('orcidEmail', 'orcidUsers', 'orcidBatches'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Orcid Email id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $orcidEmail = $this->OrcidEmails->get($id);
        if ($this->OrcidEmails->delete($orcidEmail)) {
            $this->Flash->success(__('The orcid email has been deleted.'));
        } else {
            $this->Flash->error(__('The orcid email could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
