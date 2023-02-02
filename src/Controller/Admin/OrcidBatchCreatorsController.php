<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * OrcidBatchCreators Controller
 *
 * @property \App\Model\Table\OrcidBatchCreatorsTable $OrcidBatchCreators
 * @method \App\Model\Entity\OrcidBatchCreator[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrcidBatchCreatorsController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->ldapHandler = new \LdapUtility\Ldap(Configure::read('ldapUtility.ldap'));
        $this->ldapHandler->bindUsingCommonCredentials();
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $orcidBatchCreators = $this->paginate($this->OrcidBatchCreators);
        $this->set(compact('orcidBatchCreators'));
    }

    /**
     * View method
     *
     * @param string|null $id Orcid Batch Creator id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $orcidBatchCreator = $this->OrcidBatchCreators->get($id, [
            'contain' => ['OrcidBatches'],
        ]);

        $this->set(compact('orcidBatchCreator'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $orcidBatchCreator = $this->OrcidBatchCreators->newEmptyEntity();
        if ($this->request->is('post')) {
            $orcidBatchCreator = $this->OrcidBatchCreators->patchEntity($orcidBatchCreator, $this->request->getData());
            if ($this->OrcidBatchCreators->save($orcidBatchCreator) !== false ) {
                $this->Flash->success(__('The Administrator has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Administrator could not be saved. Please, try again.'));
        }
        $this->set(compact('orcidBatchCreator'));
    }

    /**
     * Enable method
     *
     * @param string|null $id Orcid Batch Creator id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function enable($id = null)
    {
        $orcidBatchCreator = $this->OrcidBatchCreators->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $orcidBatchCreator = $this->OrcidBatchCreators->patchEntity($orcidBatchCreator, $this->request->getData());
            $orcidBatchCreator->FLAGS = $orcidBatchCreator->FLAGS & ~1;
            if ($this->OrcidBatchCreators->save($orcidBatchCreator) !== false ) {
                $this->Flash->success(__('The Administrator has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Administrator could not be saved. Please, try again.'));
        }
        $this->set(compact('orcidBatchCreator'));
    }

    /**
     * Disable Method
     */
    public function disable($id = null)
    {
        $orcidBatchCreator = $this->OrcidBatchCreators->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $orcidBatchCreator = $this->OrcidBatchCreators->patchEntity($orcidBatchCreator, $this->request->getData());
            $orcidBatchCreator->FLAGS = $orcidBatchCreator->FLAGS | 1;
            if ($this->OrcidBatchCreators->save($orcidBatchCreator) !== false ) {
                $this->Flash->success(__('The Administrator has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Administrator could not be saved. Please, try again.'));
        }
        $this->set(compact('orcidBatchCreator'));
    }
}
