<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Utility\Emailer;

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
        if ($this->request->is(['patch', 'put', 'post'])) {
            $orcidBatch = $this->OrcidBatches->patchEntity($orcidBatch, $this->request->getData());
            if ($this->OrcidBatches->save($orcidBatch) !== false ) {
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
            if ($this->OrcidBatches->save($orcidBatch) !== false ) {
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

    public function preview($id = null)
    {
        $orcidBatch = $this->OrcidBatches->get($id);
        if ($this->request->is(array('post', 'put'))) {
            $toRecipient = $this->request->getdata('recipient');
            if (!empty($toRecipient)) {
                $Emailer = new Emailer();
                if ($Emailer->sendBatch($toRecipient, $orcidBatch)) {
                    $this->Flash->success(__('A preview of the Batch Email Template has been sent.'));
                } else {
                    $this->Flash->error(__('The Batch Email Template could not be previewed. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('The Batch Email Template cannot be previewed without a preview recipient. Please, try again.'));
            }
            return $this->redirect(array('action' => 'view', $id));
        } else {
            $this->viewBuilder()
                ->setTemplatePath('email/html')
                ->setTemplate('rendered')
                ->setLayout('email/html/default');
            $this->set('body', $orcidBatch->BODY);
            $this->set('title', $orcidBatch->SUBJECT);
        }
    }
}
