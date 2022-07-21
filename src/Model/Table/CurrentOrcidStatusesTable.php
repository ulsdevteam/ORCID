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
class CurrentOrcidStatusesTable extends Table
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

        $this->setTable('ULS.CURRENT_ORCID_STATUSES');

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
            ->notEmptyString('ORCID_USER_ID');

        $validator
            ->notEmptyString('ORCID_STATUS_TYPE_ID');

        $validator
            ->date('STATUS_TIMESTAMP')
            ->allowEmptyDate('STATUS_TIMESTAMP');

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
        $rules->add($rules->existsIn('ORCID_USER_ID', 'OrcidUsers'), ['errorField' => 'ORCID_USER_ID']);
        $rules->add($rules->existsIn('ORCID_STATUS_TYPE_ID', 'OrcidStatusTypes'), ['errorField' => 'ORCID_STATUS_TYPE_ID']);

        return $rules;
    }
}
