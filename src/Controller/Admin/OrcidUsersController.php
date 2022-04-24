<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\OrcidStatusType;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;

use function PHPUnit\Framework\equalTo;

/**
 * OrcidUsers Controller
 *
 * @property \App\Model\Table\OrcidUsersTable $OrcidUsers
 * @method \App\Model\Entity\OrcidUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrcidUsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $orcidUsers = $this->paginate($this->OrcidUsers);

        $this->set(compact('orcidUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id Orcid User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $orcidUser = $this->OrcidUsers->get($id, [
            'contain' => ['OrcidEmails', 'OrcidStatuses', 'OrcidStatuses.OrcidStatusTypes'],
        ]);

        $this->set(compact('orcidUser'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $orcidUser = $this->OrcidUsers->newEmptyEntity();
        if ($this->request->is('post')) {
            $orcidUser = $this->OrcidUsers->patchEntity($orcidUser, $this->request->getData());
            if ($this->OrcidUsers->save($orcidUser)) {
                $this->Flash->success(__('The orcid user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid user could not be saved. Please, try again.'));
        }
        $this->set(compact('orcidUser'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Orcid User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $orcidUser = $this->OrcidUsers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $orcidUser = $this->OrcidUsers->patchEntity($orcidUser, $this->request->getData());
            if ($this->OrcidUsers->save($orcidUser)) {
                $this->Flash->success(__('The orcid user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The orcid user could not be saved. Please, try again.'));
        }
        $this->set(compact('orcidUser'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Orcid User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $orcidUser = $this->OrcidUsers->get($id);
        if ($this->OrcidUsers->delete($orcidUser)) {
            $this->Flash->success(__('The orcid user has been deleted.'));
        } else {
            $this->Flash->error(__('The orcid user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * optout method
     *
     * @param string|null $id Orcid User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException
     */
    public function optout($id = null)
    {
        if (!$this->OrcidUsers->exists($id)){
            throw new NotFoundException(__('Invalid ORCID User'));
        }
        if ($this->request->is(['post', 'put'])) {         
            $associated = ['OrcidStatuses', 'OrcidStatuses.OrcidStatusTypes'];
            $OrcidStatusTable = $this->fetchTable('OrcidStatuses');
            $OrcidStatusTypesTable = $this->fetchTable('OrcidStatusTypes');
            $orcidStatusType = $OrcidStatusTypesTable->find()->where(['seq' => 6])->first();
            // $orcidStatuses = $OrcidStatusTable->find()->where(['orcid_user_id' => $id, 'orcid_status_type_id' => 6])->first();
            $orcidUsers = $this->getTableLocator()->get('OrcidUsers');
            $orcidUser = $orcidUsers->get($id, ['contain' => $associated]);
            /**
             * Possible improvement here, instead of comparing the two objects we can just check if 
             * OrcidStatuses exists. Unsure which is more readable. The second option also allows
             * The Orcid User, Orcid Status Type, and Orcid Status Type Table to be not needed.
             */
            if(end($orcidUser->orcid_statuses)->get('orcid_status_type') == $orcidStatusType){
                var_dump("Opted out already");
                $this->Flash->error(__('The ORCID User has already opted out.'));
                return $this->redirect(['action' => 'index']);
            }
            $time = FrozenTime::now();
            $data = [
                'orcid_user_id' => $id,
                'orcid_status_type_id' => $orcidStatusType->id,
                'status_timestamp' => $time
            ];
            $OptOutStatus = $OrcidStatusTable->newEntity($data);
            if ($OrcidStatusTable->save($OptOutStatus)) {
				$this->Flash->success(__('The ORCID Opt-out has been saved.'));
			} else {
				$this->Flash->error(__('The ORCID Opt-out could not be saved. Please, try again.'));
			}
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * find method
     *
     * @param string|null $id Orcid User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function search()
    {
        $orcidUsers = $this->fetchTable('OrcidUsers');
        $query = $orcidUsers->find('all');
        var_dump($query);
    }

    private function _parameterize() {
		$options = array();
		// query by string matching
		if ($this->request->query('q')) {
			$options = array('OR' => array(
				'username'.($this->request->query('s') == 3 ? '' : ' LIKE') => strtoupper($this->request->query('q')) ? ($this->request->query('s') == 2 || $this->request->query('s') == 0 ? '%' : '').strtoupper($this->request->query('q')).($this->request->query('s') == 1 || $this->request->query('s') == 0 ? '%' : '') : '',
				'orcid'.($this->request->query('s') == 3 ? '' : ' LIKE') => $this->request->query('q') ? ($this->request->query('s') == 2 || $this->request->query('s') == 0 ? '%' : '').$this->request->query('q').($this->request->query('s') == 1 || $this->request->query('s') == 0 ? '%' : '') : ''
				)
			);
		}
		// query by group
		if (!$this->OrcidBathGroup) {
			$this->OrcidBatchGroup = ClassRegistry::init('OrcidBatchGroup');
		}
		if ($this->request->query('g')) {
			$members = $this->OrcidBatchGroup->getAssociatedUsers( intval($this->request->query('g')), 'OrcidUser.'.$this->OrcidUser->primaryKey );
			$options[] = $members;
		}
		// if no query specified, return nothing
		if (!$options) {
			$options = array('id' => -1);
		}
		return $options;
	}
}
