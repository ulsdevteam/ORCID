<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\FrozenTime;
use App\Utility\Emailer;

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
	 * requeue method
	 *
	 * @throws NotFoundException0
	 * @param string $id
	 * @return void
	 */
	public function requeue($id = null) { 
		$orcidEmail = $this->OrcidEmails->get($id);
		$this->request->allowMethod(array('post', 'put'));
		if (!empty($orcidEmail->SENT) || !empty($orcidEmail->CANCELLED)){
			$orcidEmail->SENT = null;
			$orcidEmail->CANCELLED = null;
			$orcidEmail->QUEUED = new FrozenTime();
			if ($this->OrcidEmails->save($orcidEmail) !== false ){
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
			if ($this->OrcidEmail->save($orcidEmail) !== false ) {
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
		$orcidEmail = $this->OrcidEmails->get($id, ['contain' => ['OrcidUsers', 'OrcidBatches']]);
		$this->request->allowMethod('post');
		// must not be already sent or cancelled
		$this->Emailer = new Emailer();
		if (!(empty($orcidEmail->SENT) || !empty($orcidEmail->CANCELLED))){
			$this->Flash->error(__('This Email cannot be sent.'));
		} else {
            if (!empty($orcidUser)) {
                if ($this->Emailer->sendEmail($orcidEmail->orcid_user, $orcidEmail)) {
                    $this->Flash->success(__('The Email has been sent.'));
                } else {
                    $this->Flash->error(__('The Email could not be sent. Please, try again.'));
                }
            }
		}
		return $this->redirect(array('action' => 'view', $id));
	}

}
