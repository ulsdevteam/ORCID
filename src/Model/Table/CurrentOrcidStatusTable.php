<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CurrentOrcidStatus Model
 *
 * @property \App\Model\Table\OrcidUsersTable&\Cake\ORM\Association\BelongsTo $OrcidUsers
 * @property \App\Model\Table\OrcidStatusTypesTable&\Cake\ORM\Association\BelongsTo $OrcidStatusTypes
 *
 * @method \App\Model\Entity\CurrentOrcidStatus newEmptyEntity()
 * @method \App\Model\Entity\CurrentOrcidStatus newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CurrentOrcidStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CurrentOrcidStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\CurrentOrcidStatus findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CurrentOrcidStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CurrentOrcidStatus[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CurrentOrcidStatus|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CurrentOrcidStatus saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CurrentOrcidStatus[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CurrentOrcidStatus[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CurrentOrcidStatus[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CurrentOrcidStatus[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class CurrentOrcidStatusTable extends Table
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

        $this->setTable('current_orcid_status');

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
            ->notEmptyString('orcid_user_id');

        $validator
            ->notEmptyString('orcid_status_type_id');

        $validator
            ->date('status_timestamp')
            ->allowEmptyDate('status_timestamp');

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
