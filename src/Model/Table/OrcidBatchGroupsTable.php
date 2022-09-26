<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\FrozenTime;
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

	public function getAssociatedUsers($groupId, $key)
	{
		[$table, $field] = explode(".", $key);
		$this->OrcidBatchGroupCache = TableRegistry::getTableLocator()->get('OrcidBatchGroupCaches');
		$this->updateCache($groupId);

		return $this->OrcidBatchGroupCaches->find()->select(['ORCID_USER_ID'])->distinct()->where(['ORCID_BATCH_GROUP_ID' => ($groupId == -1 ? null : $groupId)]);

	}

	/**
	 * Ensure the OrcidBatchGroup.id has an updated cache, creating the OrcidUser(s) if necessary
	 *
	 * @param int
	 * @return boolean
	 */
	public function updateCache($groupId)
	{
		// Does this group exist?
		$group = $this->find()
			->where(['ID' => $groupId])
			->first();
		// Have not touched GroupCache
		if (!$group) {
			$this->OrcidBatchGroupCache->deleteAll(['ORCID_BATCH_GROUP_ID' => $groupId], false);
			return true;
		}
		// No action needed if the cache was updated in the last 30 minutes
		if ($group->CACHE_CREATION_DATE && $group->CACHE_CREATION_DATE->wasWithinLast('30 minutes')) {
			return true;
		}
		// Indicate that this cache update is in-progress (blocks simultaneous cache refreshes)
		//$this->save($group);
		// mark all current cache entries as needing validation or removal
		// quoting the value separately (only when conditions are used) is some sort of ridiculous backwards compatibility thing with DboSource's update() implementation
		// $db = $this->getDataSource();
		/* $deprecated = $db->value(date('Y-m-d H:i:s'), 'date');
		$this->OrcidBatchGroupCache->updateAll(['deprecated' => $deprecated), ['orcid_batch_group_id' => $groupId));
		if ($group['OrcidBatchGroup']['GROUP_DEFINITION']) {
			$this->Person = ClassRegistry::init('Person');
		} */

		$groupMembers = [];
		if ($group->STUDENT_DEFINITION || $group->EMPLOYEE_DEFINITION) {
			// CDS defintion(s) is (are) the base query
			if ($group->STUDENT_DEFINITION) {
				$this->OrcidStudent = TableRegistry::getTableLocator()->get('OrcidStudents');
				$options = ['conditions' => $group->STUDENT_DEFINITION];
				$students = $this->OrcidStudent->find('all', $options)->all();
				if (!$students) {
					$students = [];
				}
				foreach ($students as $student) {
					// skip if a group defintion is provided and the user does not match the definition
					if ($group->GROUP_DEFINITION) {
						// TODO: warning: hardcoded foreign key relationship
						$options = ['conditions' => '(&(cn=' . $student->username . ')' . $group->GROUP_DEFINITION . ')'];
						if (!$this->Person->find('first', $options)) {
							continue;
						}
					}
					$groupMembers[$student->username] = $student->username;
				}
			}
			if ($group->EMPLOYEE_DEFINITION) {
				$this->OrcidEmployee = TableRegistry::getTableLocator()->get('OrcidEmployees');
				$options = ['conditions' => $group->EMPLOYEE_DEFINITION];
				$employees = $this->OrcidEmployee->find('all', $options)->all();
				if (!$employees) {
					$employees = [];
				}
				foreach ($employees as $employee) {
					// skip if a group defintion is provided and the user does not match the definition
					if ($group->GROUP_DEFINITION) {
						// TODO: warning: hardcoded foreign key relationship
						$options = ['conditions' => '(&(cn=' . $employee->username . ')' . $group->GROUP_DEFINITION . ')'];
						if (!$this->Person->find('first', $options)) {
							continue;
						}
					}
					$groupMembers[$employee->USERNAME] = $employee->USERNAME;
				}
			}
		} else if ($group->GROUP_DEFINITION) {
			// group_defintion is the base query
			// TODO: risky because Person is LDAP and may not support paging
			$this->OrcidUser = TableRegistry::getTableLocator()->get('OrcidUsers');
			$people = $this->OrcidUser->defintionSearch($group->GROUP_DEFINITION);
			foreach ($people as $person) {
				$groupMembers[$person] = $person;
			}
		}

		$this->OrcidUser = TableRegistry::getTableLocator()->get('OrcidUsers');
		// Refresh the cache
		foreach ($groupMembers as $groupMember) {
			$options = ['conditions' => ['USERNAME' => $groupMember]];
			$user = $this->OrcidUser->find('all', $options)->first();
			if (!$user) {
				$user = $this->OrcidUser->newEntity(['USERNAME' => $groupMember]);
				if ($this->OrcidUser->save($user)) {
					continue;
				} else {
					$user = $this->OrcidUser->find('all', $options)->first();
				}
			}
			// create or update the user in the cache
			if ($user->ID) {
				$options = ['conditions' => ['ORCID_USER_ID' => $user->ID, 'ORCID_BATCH_GROUP_ID' => $groupId]];
				$cache = $this->OrcidBatchGroupCache->find('all', $options)->first();
				if (!$cache) {
					$cache = $this->OrcidBatchGroupCache->newEntity(['ORCID_USER_ID' => $user->ID, 'ORCID_BATCH_GROUP_ID' => $groupId]);
				} else {
					$cache->DEPRECATED = NULL;
				}
				$this->OrcidBatchGroupCache->save($cache);
			}
		}
		// If the cache entry wasn't updated, delete it
		$this->OrcidBatchGroupCache->deleteAll(['ORCID_BATCH_GROUP_ID' => $groupId, ['deprecated IS NOT NULL']]);
		// Indicate that this cache update is complete
		$group->CACHE_CREATION_DATE = FrozenTime::now();
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

		$this->setTable('ULS.ORCID_BATCH_GROUPS');
		$this->setDisplayField('NAME');
		$this->setPrimaryKey('ID');

		$this->hasMany('OrcidBatchGroupCaches', [
			'foreignKey' => 'ORCID_BATCH_GROUP_ID',
		]);
		$this->hasMany('OrcidBatchTriggers', [
			'foreignKey' => 'ORCID_BATCH_GROUP_ID',
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
			->integer('ID')
			->notEmptyString('ID')
			->add('ID', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

		$validator
			->scalar('name')
			->maxLength('name', 512)
			->requirePresence('name', 'create')
			->notEmptyString('name');

		$validator
			->scalar('GROUP_DEFINITION')
			->maxLength('GROUP_DEFINITION', 2048)
			->allowEmptyString('GROUP_DEFINITION');

		$validator
			->scalar('EMPLOYEE_DEFINITION')
			->maxLength('EMPLOYEE_DEFINITION', 2048)
			->allowEmptyString('EMPLOYEE_DEFINITION');

		$validator
			->scalar('STUDENT_DEFINITION')
			->maxLength('STUDENT_DEFINITION', 2048)
			->allowEmptyString('STUDENT_DEFINITION');

		$validator
			->dateTime('CACHE_CREATION_DATE')
			->allowEmptyDateTime('CACHE_CREATION_DATE');

		return $validator;
	}

	/**
	 * Returns a rules checker object that will be used for validating
	 * application integrity.
	 *
	 * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
	 * @return \Cake\ORM\RulesChecker
	 */
	public function buildRules(RulesChecker $rules): RulesChecker
	{
		$rules->add($rules->isUnique(['ID']), ['errorField' => 'ID']);

		return $rules;
	}
}
