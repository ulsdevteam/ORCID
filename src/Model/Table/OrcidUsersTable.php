<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OrcidUsers Model
 *
 * @property \App\Model\Table\OrcidBatchGroupCachesTable&\Cake\ORM\Association\HasMany $OrcidBatchGroupCaches
 * @property \App\Model\Table\OrcidEmailsTable&\Cake\ORM\Association\HasMany $OrcidEmails
 * @property \App\Model\Table\OrcidStatusesTable&\Cake\ORM\Association\HasMany $OrcidStatuses
 *
 * @method \App\Model\Entity\OrcidUser newEmptyEntity()
 * @method \App\Model\Entity\OrcidUser newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrcidUser findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrcidUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidUser[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidUser|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidUser saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidUser[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidUser[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidUser[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidUser[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrcidUsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('orcid_users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('AllOrcidStatuses', [
            'foreignKey' => 'orcid_user_id',
        ]);
        $this->hasMany('CurrentOrcidStatus', [
            'foreignKey' => 'orcid_user_id',
        ]);
        $this->hasMany('OrcidBatchGroupCaches', [
            'foreignKey' => 'orcid_user_id',
        ]);
        $this->hasMany('OrcidEmails', [
            'foreignKey' => 'orcid_user_id',
        ]);
        $this->hasMany('OrcidStatuses', [
            'foreignKey' => 'orcid_user_id',
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
            ->scalar('username')
            ->maxLength('username', 8)
            ->requirePresence('username', 'create')
            ->notEmptyString('username');

        $validator
            ->scalar('orcid')
            ->maxLength('orcid', 19)
            ->allowEmptyString('orcid');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->allowEmptyString('token');

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
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username']);

        return $rules;
    }
}
