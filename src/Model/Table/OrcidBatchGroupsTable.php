<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

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

		$saveFailed = false;

		// Have not touched GroupCache
		if (!$group) {
			$this->OrcidBatchGroupCaches->deleteAll(['ORCID_BATCH_GROUP_ID' => $groupId], false);
			return true;
		}

		// No action needed if the cache was updated in the last 30 minutes
		if ($group->CACHE_CREATION_DATE && $group->CACHE_CREATION_DATE->wasWithinLast('30 minutes')) {
			return true;
		}

		$deprecated = FrozenTime::now();
		$this->OrcidBatchGroupCaches->updateAll(['DEPRECATED' => $deprecated], ['ORCID_BATCH_GROUP_ID' => $groupId]);
		
		$OrcidUser = TableRegistry::getTableLocator()->get('OrcidUsers');
		$groupMembers = [];
		if ($group->STUDENT_DEFINITION || $group->EMPLOYEE_DEFINITION) {
			// CDS definition(s) is (are) the base query
			if ($group->STUDENT_DEFINITION) {
				$OrcidStudent = TableRegistry::getTableLocator()->get('OrcidStudents');
				$options = ['conditions' => $group->STUDENT_DEFINITION];
				$students = $OrcidStudent->find('all', $options)->all();
				if (!$students) {
					$students = [];
				}
				foreach ($students as $student) {
					// skip if a group definition is provided and the user does not match the definition
					if ($group->GROUP_DEFINITION) {
						// TODO: warning: hardcoded foreign key relationship
						$options = '(cn=' . $student->USERNAME . ')' . $group->GROUP_DEFINITION;
						if (!$OrcidUser->definitionSearch($options)) {
							continue;
						}
					}
					$groupMembers[$student->USERNAME] = $student->USERNAME;
				}
			}
			if ($group->EMPLOYEE_DEFINITION) {
				$OrcidEmployee = TableRegistry::getTableLocator()->get('OrcidEmployees');
				$options = ['conditions' => $group->EMPLOYEE_DEFINITION];
				$employees = $OrcidEmployee->find('all', $options)->all();
				if (!$employees) {
					$employees = [];
				}
				foreach ($employees as $employee) {
					// skip if a group definition is provided and the user does not match the definition
					if ($group->GROUP_DEFINITION) {
						// TODO: warning: hardcoded foreign key relationship
						$options = '(cn=' . $employee->USERNAME . ')' . $group->GROUP_DEFINITION;
						if (!$OrcidUser->definitionSearch($options)) {
							continue;
						}
					}
					$groupMembers[$employee->USERNAME] = $employee->USERNAME;
				}
			}
			
		} elseif ($group->GROUP_DEFINITION) {
			// group_definition is the base query
			// TODO: risky because Person is LDAP and may not support paging
			$people = $OrcidUser->definitionSearch($group->GROUP_DEFINITION);
			foreach ($people as $person) {
				$groupMembers[$person] = $person;
			}
		}
		// Refresh the cache
		foreach ($groupMembers as $groupMember) {
			$options = ['conditions' => ['USERNAME' => $groupMember]];
			$user = $OrcidUser->find('all', $options)->first();
			if (!$user) {
				$user = $OrcidUser->newEntity(['USERNAME' => $groupMember]);
				$user = $OrcidUser->save($user);
				if ($user === false ) {
					continue;
				}
			}
			// create or update the user in the cache
			if ($user->ID) {
				$options = ['conditions' => ['ORCID_USER_ID' => $user->ID, 'ORCID_BATCH_GROUP_ID' => $groupId]];
				$cache = $this->OrcidBatchGroupCaches->find('all', $options)->first();
				if (!$cache) {
					$cache = $this->OrcidBatchGroupCaches->newEntity(['ORCID_USER_ID' => $user->ID, 'ORCID_BATCH_GROUP_ID' => $groupId]);
				} else {
					$cache->DEPRECATED = NULL;
				}
				if ($this->OrcidBatchGroupCaches->save($cache) === false) {
					$saveFailed = true;
				}
			}
		}

		if (!$saveFailed) {
			// If the cache entry wasn't updated, delete it
			$this->OrcidBatchGroupCaches->deleteAll(['ORCID_BATCH_GROUP_ID' => $groupId, ['DEPRECATED IS NOT' =>  NULL]]);
			// Indicate that this cache update is complete
			$group->CACHE_CREATION_DATE = FrozenTime::now();
			$this->save($group);
		}

		return !$saveFailed;
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
			->scalar('NAME')
			->maxLength('NAME', 512)
			->requirePresence('NAME')
			->notEmptyString('NAME', 'Group name must be provided.');

		$validator
			->scalar('GROUP_DEFINITION')
			->maxLength('GROUP_DEFINITION', 2048)
			->allowEmptyString('GROUP_DEFINITION', 'At least one criteria must be specified.', [$this, 'oneRequired']);

		$validator
			->scalar('EMPLOYEE_DEFINITION')
			->maxLength('EMPLOYEE_DEFINITION', 2048)
			->allowEmptyString('EMPLOYEE_DEFINITION', 'At least one criteria must be specified.', [$this, 'oneRequired']);

		$validator
			->scalar('STUDENT_DEFINITION')
			->maxLength('STUDENT_DEFINITION', 2048)
			->allowEmptyString('STUDENT_DEFINITION', 'At least one criteria must be specified.', [$this, 'oneRequired']);

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

	/**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return (Configure::read('debug')) ? 'default' : 'production-default';
    }

	public static function oneRequired($context) {
		$data = $context["data"];
		if (!empty($data) &&  (empty($data['GROUP_DEFINITION']) && empty($data['EMPLOYEE_DEFINITION']) && empty($data['STUDENT_DEFINITION']))) {
			return false;
		}
		return true;
	}
}
