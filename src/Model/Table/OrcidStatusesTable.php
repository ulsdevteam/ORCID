<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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

        $this->setTable('orcid_statuses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('OrcidUsers', [
            'foreignKey' => 'orcid_user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('OrcidStatusTypes', [
            'foreignKey' => 'orcid_status_type_id',
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
            ->requirePresence('orcid_user_id', 'create')
            ->notEmptyString('orcid_user_id');

        $validator
            ->requirePresence('orcid_status_type_id', 'create')
            ->notEmptyString('orcid_status_type_id');

        $validator
            ->date('status_timestamp')
            ->requirePresence('status_timestamp', 'create')
            ->notEmptyDate('status_timestamp');

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
        $rules->add($rules->existsIn('orcid_user_id', 'OrcidUsers'), ['errorField' => 'orcid_user_id']);
        $rules->add($rules->existsIn('orcid_status_type_id', 'OrcidStatusTypes'), ['errorField' => 'orcid_status_type_id']);

        return $rules;
    }
}
