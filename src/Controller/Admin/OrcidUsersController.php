<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\OrcidStatusType;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;
use Cake\Collection\Collection;
use Cake\Core\Configure;

use function PHPUnit\Framework\equalTo;

/**
 * OrcidUsers Controller
 *
 * @property \App\Model\Table\OrcidUsersTable $OrcidUsers
 * @method \App\Model\Entity\OrcidUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrcidUsersController extends AppController
{

    private const CONTAINS = '0';
    private const STARTS_WITH = '1';
    private const ENDS_WITH = '2';
    private const EXACTLY = '3';
    private const NULL_STRING_ID = '-1';
    private const NULL_ID = -1;

    private $ldapHandler;

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
            'contain' => ['OrcidEmails', 'CurrentOrcidStatus.OrcidStatusTypes', 'AllOrcidStatuses.OrcidStatusTypes'],
        ]);
        $ldapResult = $this->ldapHandler->find('search', [
            'baseDn' => 'ou=Accounts,dc=univ,dc=pitt,dc=edu',
            'filter' => 'cn='.$orcidUser->username,
            'attributes' => [
                'samaccountname', 
                'dn',
                'givenName',
                'sn',
                'mail',
                'displayName',
                'department',
                'PittEmployeeRC',
            ],
        ]);
        if($ldapResult['count'] > 0) {
            $result = $ldapResult[0];
            $orcidUser->set("name", $result['displayname'][0]);
            $orcidUser->set("email", $result['mail'][0]);
            if(!($result['pittemployeerc'])){
                $orcidUser->set("department", "RC ".$result['pittemployeerc'][0]." / ".$result['department'][0]);
            } else {
                $orcidUser->set("department", $result['department'][0]);
            }
        } else {
            $orcidUser->set("name", "");
            $orcidUser->set("email", "");
            $orcidUser->set("department", "");
        }
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
            $OrcidStatusTable = $this->fetchTable('OrcidStatuses');
            $OrcidStatusTypesTable = $this->fetchTable('OrcidStatusTypes');
            $orcidStatusTypeID = $OrcidStatusTypesTable->find()->where(['seq' => $OrcidStatusTypesTable::OPTOUT_SEQUENCE])->first()->id;
            $orcidStatuses = $OrcidStatusTable->find()->where(['orcid_user_id' => $id, 'orcid_status_type_id' =>  $orcidStatusTypeID])->first();

            if(isset($orcidStatuses)){
                var_dump("Opted out already");
                $this->Flash->error(__('The ORCID User has already opted out.'));
                return $this->redirect(['action' => 'index']);
            }
            $time = FrozenTime::now();
            $data = [
                'orcid_user_id' => $id,
                'orcid_status_type_id' => $orcidStatusTypeID,
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
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function find()
    {
        $orcidUsersTable = $this->fetchTable('OrcidUsers');

        $BatchGroups = $this->fetchTable('OrcidBatchGroups');
        $batchGroups = $BatchGroups->find('all')->all();
        $orcidUsers = $this->paginate($this->_parameterize($batchGroups));

        $findTypes = ['Containing', 'Starting With', 'Ending With', 'Matching Exactly'];
        $groups = [0 => ''];

        foreach ($batchGroups as $group) {
            $groups[$group->id] = $group->name;
        }

        $groups[$this::NULL_ID] = 'No Matching Group';

        $this->set('findTypes', $findTypes);
        $this->set('batchGroups', $groups);
        $this->set('ldapHandler', $this->ldapHandler);
        $this->set(compact('orcidUsers'));

    }

    private function _parameterize() {
        // User Entered Data
		$options = $this->request->getData();

        // split to easily reuse it
        $userQuery = $options['q'];
        $findType = $options['s'];
        $groupQuery = $options['g'];

        // container to hold condtions
        $conditions = [];

        // Starting point for query
        $orcidUsersTable = $this->fetchTable('OrcidUsers');
        $orcidUsersTable = $orcidUsersTable->find('all')->contain(['CurrentOrcidStatus', 'CurrentOrcidStatus.OrcidStatusTypes']);

        // query by string matching
        if (!empty($userQuery)){
            if ($findType === $this::EXACTLY) {
                $conditions = ['OR' => [['username' => $userQuery], ['orcid' => $userQuery]]];
            } else if ($findType === $this::ENDS_WITH) {
                $conditions = ['OR' => [['username LIKE' => '%'.$userQuery], ['orcid LIKE' => '%'.$userQuery]]];
            } else if ($findType === $this::STARTS_WITH) {
                $conditions = ['OR' => [['username LIKE' => '%'.$userQuery], ['orcid LIKE' => '%'.$userQuery]]];
            } else if ($findType === $this::CONTAINS) {
                $conditions = ['OR' => [['username LIKE' => '%'.$userQuery.'%'], ['orcid LIKE' => '%'.$userQuery.'%']]];
            }
        }

        // query by group
        if (!empty($groupQuery)) {
            if ($groupQuery === $this::NULL_STRING_ID) {
                // notMatching creates a left join
                $orcidUsersTable = $orcidUsersTable->notMatching('OrcidBatchGroupCaches', function($q) use($groupQuery) {
                    return $q->where(['OrcidBatchGroupCaches.orcid_batch_group_id IS NOT NULL']);
                });
            } else {
                // matching creates an inner join
                $orcidUsersTable = $orcidUsersTable->matching('OrcidBatchGroupCaches', function($q) use($groupQuery) {
                    return $q->where(['OrcidBatchGroupCaches.orcid_batch_group_id' => $groupQuery]);
                });
            }
        }

		// if no query specified, return nothing
        if (empty($userQuery) && empty($groupQuery)) {
            // no oricid id should be -1
            $conditions = ['orcid' => $this::NULL_ID];
        }
        
        // this is the final query after all conditions
        $orcidUsersQuery = $orcidUsersTable->where($conditions);

        return $orcidUsersQuery;
	}
}
