<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Mailer\Mailer;

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

    public function preview($id = null) {
        $orcidBatch = $this->OrcidBatches->get($id);
        if ($this->request->is(array('post', 'put'))) {
            $Mailer = new Mailer();
			if ($this->request->getData()) {
				if ($this->Emailer->sendBatch($email, $person)) {
					$this->Session->setFlash(__('A preview of the Batch Email Template has been sent.'), 'default', array('class' => 'success'));
				} else {
					$this->Session->setFlash(__('The Batch Email Template could not be previewed. Please, try again.'));
				}
			} else {
				$this->Session->setFlash(__('The Batch Email Template cannot be previewed without a preview recipient. Please, try again.'));
			}
			return $this->redirect(array('action' => 'view', $id));
		} else {
			$this->viewBuilder()->setLayout('email/html/default')->setTemplate('email/html/batch');
			$options = array('conditions' => array('OrcidBatch.' . $this->OrcidBatch->primaryKey => $id));
			$email = $this->OrcidBatch->find('first', $options);
			$this->set('body', $email['OrcidBatch']['body']);
			$this->set('title', $email['OrcidBatch']['subject']);
		}
    }
}
