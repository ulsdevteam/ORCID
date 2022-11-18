<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\FrozenTime;
use App\Utility\Emailer;
use Cake\ORM\Locator\TableLocator;

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


    /**
	 * requeue method
	 *
	 * @throws NotFoundException0
	 * @param string $id
	 * @return void
	 */
	public function requeue($id = null) { 
		$orcidEmail = $this->OrcidEmails->get($id);
		$this->request->allowMethod(array('post', 'put'));
		if (!(empty($orcidEmail->SENT) || empty($orcidEmail->CANCELLED))){
			$orcidEmail->SENT = null;
			$orcidEmail->CANCELLED = null;
			$orcidEmail->QUEUED = new FrozenTime();
			if ($this->orcidEmails->save($orcidEmail)){
				$this->Flash->success(__('The Email has been requeued.'));
			} else {
				$this->Flash->error(__('The orcid email could not be requeued. Please, try again.'));
			}
		} else {
			$this->Flash->error(__('The orcid email could not be requeued.'));
		}
		return $this->redirect(array('action' => 'view', $id));
	}

/**
 * cancel method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function cancel($id = null) {
		$orcidEmail = $this->OrcidEmails->get($id);
		$this->request->allowMethod('post', 'delete');
		// must not be already sent or cancelled
		if (!(empty($orcidEmail->SENT) || empty($orcidEmail->CANCELLED))){
			$this->Flash->error(__('This Email cannot be cancelled.'));
		} else {
			$orcidEmail->CANCELLED = new FrozenTime();
			if ($this->OrcidEmail->save($orcidEmail)) {
				$this->Flash->success(__('The Email has been cancelled.'));
			} else {
				$this->Flash->error(__('The Email could not be cancelled. Please, try again.'));
			}
		}
		return $this->redirect(array('action' => 'view', $id));
	}
	
/**
 * send method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function send($id = null) {
		$orcidEmail = $this->OrcidEmails->get($id, ['contain' => 'OrcidUsers']);
		$this->Person = $this->fetchTable('OrcidUsers');
		$this->request->allowMethod('post');
		// must not be already sent or cancelled
		$this->Emailer = new Emailer();
		if (!(empty($orcidEmail->SENT) || !empty($orcidEmail->CANCELLED))){
			$this->Flash->error(__('This Email cannot be sent.'));
		} else {
			$orcidUser = $this->Person->get($orcidEmail->orcid_user->ID);
            $person = $orcidUser->get('email');
            if (!empty($person)) {
                if ($this->Emailer->sendBatch($person, $orcidEmail)) {
                    $this->Flash->success(__('The Email has been sent.'));
                } else {
                    $this->Flash->error(__('The Email could not be sent. Please, try again.'));
                }
            }
		}
		return $this->redirect(array('action' => 'view', $id));
	}

/**
 * sendAll method
 *
 * @return void
 */
	public function sendAll() {
		$this->request->allowMethod('post');
		// must not be already sent or cancelled
		$options = array('conditions' => array('OrcidEmail.SENT IS NOT' => NULL, 'OrcidEmail.CANCELLED IS NOT' => NULL));
		$orcidEmails = $this->OrcidEmail->find('all', $options);
		$success = 0;
		$failed = 0;
		$this->Person = $this->fetchTable('OrcidUsers');
		$this->Emailer = new Emailer();
        foreach ($orcidEmails as $orcidEmail){
            if (!(empty($orcidEmail->SENT) || !empty($orcidEmail->CANCELLED))){
                $this->Flash->error(__('This Email cannot be sent.'));
            } else {
                $orcidUser = $this->Person->get($orcidEmail->orcid_user->ID);
                $person = $orcidUser->get('email');
                if (!empty($person) && $this->Emailer->sendBatch($person, $orcidEmail)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
		}
		if ($success) {
            $this->Flash->success(__('Successfully sent '.$success.' email'.($success > 1 ? 's' : '')), 'default', array('class' => 'success'));
		}
		if ($failed) {
			$this->Flash->error(__('Failed to send '.$failed.' email'.($failed > 1 ? 's' : '')));
		}
		if (!$success && !$failed) {
			$this->Flash->error(__('No emails to send.'));
		}
		return $this->redirect(['action' => 'index']);
	}

}
