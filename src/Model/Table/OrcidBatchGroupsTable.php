<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * OrcidBatchGroups Model
 *
 * @property \App\Model\Table\OrcidBatchGroupCachesTable&\Cake\ORM\Association\HasMany $OrcidBatchGroupCaches
 * @property \App\Model\Table\OrcidBatchTriggersTable&\Cake\ORM\Association\HasMany $OrcidBatchTriggers
 *
 * @method \App\Model\Entity\OrcidBatchGroup newEmptyEntity()
 * @method \App\Model\Entity\OrcidBatchGroup newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchGroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchGroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroup findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchGroup[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchGroup|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroup saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroup[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroup[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroup[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroup[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class OrcidBatchGroupsTable extends Table
{

    public function getAssociatedUsers($groupId, $key) {
        $this->updateCache($groupId);
		$db = $this->OrcidBatchGroupCache->getDataSource();
		$subQuery = $db->buildStatement(
			array(
				'fields'     => array('cache.orcid_user_id'),
				'table'      => $db->fullTableName($this->OrcidBatchGroupCache),
				'alias'      => 'cache',
				'limit'      => null,
				'offset'     => null,
				'joins'      => array(),
				'conditions' => $groupId == -1 ? null :array('cache.orcid_batch_group_id' => $groupId),
				'order'      => null,
				'group'      => null
			),
			$this->OrcidBatchGroupCache
		);
		$subQuery = ' '.$key.' '.($groupId == -1 ? 'NOT ' : '').'IN (' . $subQuery . ') ';
		return $db->expression($subQuery);
    }

    /**
     * Ensure the OrcidBatchGroup.id has an updated cache, creating the OrcidUser(s) if necessary
     *
     * @param int
     * @return boolean
     */
	public function updateCache( $groupId ) {
		// Does this group exist?
        xdebug_break();
		$group = $this->find()
            ->where(['id' => $groupId])
            ->first();
		if (!$group) {
			$this->OrcidBatchGroupCache->deleteAll(['orcid_batch_group_id' => $groupId], false);
			return true;
		}
		// No action needed if the cache was updated in the last 30 minutes
		if ($group->cache_creation_date && $group->cache_creation_date->wasWithinLast('30 minutes')) {
			return true;
		}
		// Indicate that this cache update is in-progress (blocks simultaneous cache refreshes)
		//$this->save($group);
		// mark all current cache entries as needing validation or removal
		// quoting the value separately (only when conditions are used) is some sort of ridiculous backwards compatibility thing with DboSource's update() implementation
		// $db = $this->getDataSource();
		/* $deprecated = $db->value(date('Y-m-d H:i:s'), 'date');
		$this->OrcidBatchGroupCache->updateAll(array('deprecated' => $deprecated), array('orcid_batch_group_id' => $groupId));
		if ($group['OrcidBatchGroup']['group_definition']) {
			$this->Person = ClassRegistry::init('Person');
		} */

		$groupMembers = array();
		if ($group->student_definition || $group->employee_definition) {
			// CDS defintion(s) is (are) the base query
			if ($group->student_definition) {
				$this->OrcidStudent = TableRegistry::getTableLocator()->get('OrcidUsers');
				$options = array('recursive' => -1, 'conditions' => $group->student_definition);
				$students = $this->OrcidStudent->find('all', $options);
				if (!$students) {
					$students = array();
				}
				foreach ($students as $student) {
					// skip if a group defintion is provided and the user does not match the definition
					if ($group->group_definition) {
						// TODO: warning: hardcoded foreign key relationship
						$options = array('conditions' => '(&(cn='.$student->username.')'.$group->group_definition.')');
						if (!$this->Person->find('first', $options)) {
							continue;
						}
					}
					$groupMembers[$student->username] = $student->username;
				}
			}
			if ($group->employee_definition) {
				$this->OrcidEmployee = TableRegistry::getTableLocator()->get('OrcidUsers');
				$options = array('recursive' => -1, 'conditions' => $group->employee_definition);
				$employees = $this->OrcidEmployee->find('all', $options);
				if (!$employees) {
					$employees = array();
				}
				foreach ($employees as $employee) {
					// skip if a group defintion is provided and the user does not match the definition
					if ($group->group_definition) {
						// TODO: warning: hardcoded foreign key relationship
						$options = array('conditions' => '(&(cn='.$employee->username.')'.$group->group_definition.')');
						if (!$this->Person->find('first', $options)) {
							continue;
						}
					}
					$groupMembers[$employee->username] = $employee->username;
				}
			}
		} else if ($group->group_definition) {
			// group_defintion is the base query
			// TODO: risky because Person is LDAP and may not support paging
			$options = array('recurisve' => -1, 'conditions' => $group->group_definition);
			$people = $this->Person->find('all', $options);
			if (!$people) {
				$people = array();
			}
			$this->OrcidUser = TableRegistry::getTableLocator()->get('OrcidUsers');
			foreach ($people as $person) {
				$groupMembers[$person['Person']['cn']] = $person['Person']['cn'];
			}
		}

		$this->OrcidUser = TableRegistry::getTableLocator()->get('OrcidUsers');
		// Refresh the cache
		foreach ($groupMembers as $groupMember) {
			$options = array('recursive' => -1, 'conditions' => array('OrcidUser.username' => $groupMember));
			$user = $this->OrcidUser->find('first', $options);
			if (!$user) {
				$this->OrcidUser->create();
				$user = array('username' => $groupMember);
				if (!$this->OrcidUser->save($user)) {
					continue;
				} else {
					$user = $this->OrcidUser->find('first', $options);
				}
			}
			// create or update the user in the cache
			if ($user->id) {
				$options = array('conditions' => array('orcid_user_id' => $user->id, 'orcid_batch_group_id' => $groupId));
				$cache = $this->OrcidBatchGroupCache->find('first', $options);
				if (!$cache) {
					$this->OrcidBatchGroupCache->create();
					$cache->orcid_user_id = $user->id;
					$cache->orcid_batch_group_id = $groupId;
				} else {
					$cache->deprecated = NULL;
				}
				$this->OrcidBatchGroupCache->save($cache);
			}
		}
		// If the cache entry wasn't updated, delete it
		$this->OrcidBatchGroupCache->deleteAll(array('orcid_batch_group_id' => $groupId, 'NOT' => array('deprecated' => NULL)));
		// Indicate that this cache update is complete
		$group->cache_creation_date = date('Y-m-d H:i:s');
		$this->save($group);

		return true;
	}

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('orcid_batch_groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('OrcidBatchGroupCaches', [
            'foreignKey' => 'orcid_batch_group_id',
        ]);
        $this->hasMany('OrcidBatchTriggers', [
            'foreignKey' => 'orcid_batch_group_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 512)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('group_definition')
            ->maxLength('group_definition', 2048)
            ->allowEmptyString('group_definition');

        $validator
            ->scalar('employee_definition')
            ->maxLength('employee_definition', 2048)
            ->allowEmptyString('employee_definition');

        $validator
            ->scalar('student_definition')
            ->maxLength('student_definition', 2048)
            ->allowEmptyString('student_definition');

        $validator
            ->date('cache_creation_date')
            ->allowEmptyDate('cache_creation_date');

        return $validator;
    }
}
