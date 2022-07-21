<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\OrcidStatusType;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;
use Cake\Collection\Collection;

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
            'contain' => ['OrcidEmails','CurrentOrcidStatuses', 'CurrentOrcidStatuses.OrcidStatusTypes', 'AllOrcidStatuses.OrcidStatusTypes'],
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
            $OrcidStatusTable = $this->fetchTable('OrcidStatuses');
            $OrcidStatusTypesTable = $this->fetchTable('OrcidStatusTypes');
            $orcidStatusTypeID = $OrcidStatusTypesTable->find()->where(['SEQ' => $OrcidStatusTypesTable::OPTOUT_SEQUENCE])->first()->id;
            $orcidStatuses = $OrcidStatusTable->find()->where(['ORCID_USER_ID' => $id, 'ORCID_STATUS_TYPE_ID' =>  $orcidStatusTypeID])->first();

            if(isset($orcidStatuses)){
                var_dump("Opted out already");
                $this->Flash->error(__('The ORCID User has already opted out.'));
                return $this->redirect(['action' => 'index']);
            }
            $time = FrozenTime::now();
            $data = [
                'ORCID_USER_ID' => $id,
                'ORCID_STATUS_TYPE_ID' => $orcidStatusTypeID,
                'STATUS_TIMESTAMP' => $time
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
        $options = $this->request->getQueryParams();

        if ($options) {
            $userQuery = $options['q']??'';
            $findType = $options['s']??'';
            $groupQuery = $options['g']??'';
        } else {
            $userQuery = '';
            $findType = '';
            $groupQuery = '';
        }

        $BatchGroups = $this->fetchTable('OrcidBatchGroups');
        $batchGroups = $BatchGroups->find('all')->all();
        $orcidUsers = $this->paginate($this->_parameterize($userQuery, $findType, $groupQuery));

        $findTypes = ['Containing', 'Starting With', 'Ending With', 'Matching Exactly'];
        $groups = [0 => ''];

        foreach ($batchGroups as $group) { 
            $groups[$group->ID] = $group->NAME;
        }

        $groups[$this::NULL_ID] = 'No Matching Group';

        $this->set('findTypes', $findTypes);
        $this->set('userQuery', $userQuery);
        $this->set('selectedType', $findType);
        $this->set('selectedGroup', $groupQuery);
        $this->set('batchGroups', $groups);
        $this->set(compact('orcidUsers'));

    }

    private function _parameterize($userQuery, $findType, $groupQuery) {

        // container to hold condtions
        $conditions = [];

        // Starting point for query
        $orcidUsersTable = $this->fetchTable('OrcidUsers');
        $orcidUsersTable = $orcidUsersTable->find('all')->contain(['CurrentOrcidStatuses', 'CurrentOrcidStatuses.OrcidStatusTypes']);

        // query by string matching
        if (!empty($userQuery)){
            if ($findType === $this::EXACTLY) {
                $conditions = ['OR' => [['USERNAME' => strtoupper($userQuery)], ['ORCID' => $userQuery]]];
            } else if ($findType === $this::ENDS_WITH) {
                $conditions = ['OR' => [['USERNAME LIKE' => '%'.strtoupper($userQuery)], ['ORCID LIKE' => '%'.$userQuery]]];
            } else if ($findType === $this::STARTS_WITH) {
                $conditions = ['OR' => [['USERNAME LIKE' => strtoupper($userQuery).'%'], ['ORCID LIKE' => $userQuery.'%']]];
            } else if ($findType === $this::CONTAINS) {
                $conditions = ['OR' => [['USERNAME LIKE' => '%'.strtoupper($userQuery).'%'], ['ORCID LIKE' => '%'.$userQuery.'%']]];
            }
        }

        // query by group
        if (!empty($groupQuery)) {
            if ($groupQuery === $this::NULL_STRING_ID) {
                // notMatching creates a left join
                $orcidUsersTable = $orcidUsersTable->notMatching('OrcidBatchGroupCaches', function($q) use($groupQuery) {
                    return $q->where(['OrcidBatchGroupCaches.ORCID_BATCH_GROUP_ID IS NOT NULL']);
                });
            } else {
                // matching creates an inner join
                $orcidUsersTable = $orcidUsersTable->matching('OrcidBatchGroupCaches', function($q) use($groupQuery) {
                    return $q->where(['OrcidBatchGroupCaches.ORCID_BATCH_GROUP_ID' => $groupQuery]);
                });
            }
        }

		// if no query specified, return nothing
        if (empty($userQuery) && empty($groupQuery)) {
            // no oricid id should be -1
            $conditions = ['ORCID' => $this::NULL_ID];
        }
        
        // this is the final query after all conditions
        $orcidUsersQuery = $orcidUsersTable->where($conditions);

        return $orcidUsersQuery;
	}
}
