<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

/**
 * OrcidStatuses Model
 *
 * @property \App\Model\Table\OrcidUsersTable&\Cake\ORM\Association\BelongsTo $OrcidUsers
 * @property \App\Model\Table\OrcidStatusTypesTable&\Cake\ORM\Association\BelongsTo $OrcidStatusTypes
 *
 * @method \App\Model\Entity\OrcidStatus newEmptyEntity()
 * @method \App\Model\Entity\OrcidStatus newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrcidStatus findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrcidStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidStatus[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidStatus|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidStatus saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidStatus[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidStatus[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidStatus[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidStatus[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class OrcidStatusesTable extends Table
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

        $this->setTable('ULS.ORCID_STATUSES');
        $this->setDisplayField('id');
        $this->setPrimaryKey('ID');

        $this->belongsTo('OrcidUsers', [
            'foreignKey' => 'ORCID_USER_ID',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('OrcidStatusTypes', [
            'foreignKey' => 'ORCID_STATUS_TYPE_ID',
            'joinType' => 'INNER',
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
            ->integer('ORCID_USER_ID')
            ->requirePresence('ORCID_USER_ID', 'create')
            ->notEmptyString('ORCID_USER_ID')
            ->add('ORCID_USER_ID', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->integer('ORCID_STATUS_TYPE_ID')
            ->requirePresence('ORCID_STATUS_TYPE_ID', 'create')
            ->notEmptyString('ORCID_STATUS_TYPE_ID');

        $validator
            ->dateTime('STATUS_TIMESTAMP')
            ->requirePresence('STATUS_TIMESTAMP', 'create')
            ->notEmptyDateTime('STATUS_TIMESTAMP');

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
        $rules->add($rules->isUnique(['ORCID_USER_ID', 'ORCID_STATUS_TYPE_ID']), ['errorField' => 'ORCID_USER_ID']);
        $rules->add($rules->existsIn('ORCID_USER_ID', 'OrcidUsers'), ['errorField' => 'ORCID_USER_ID']);
        $rules->add($rules->existsIn('ORCID_STATUS_TYPE_ID', 'OrcidStatusTypes'), ['errorField' => 'ORCID_STATUS_TYPE_ID']);

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
}
