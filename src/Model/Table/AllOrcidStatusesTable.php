<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AllOrcidStatuses Model
 *
 * @property \App\Model\Table\OrcidUsersTable&\Cake\ORM\Association\BelongsTo $OrcidUsers
 * @property \App\Model\Table\OrcidStatusTypesTable&\Cake\ORM\Association\BelongsTo $OrcidStatusTypes
 * 
 * @method \App\Model\Entity\AllOrcidStatus newEmptyEntity()
 * @method \App\Model\Entity\AllOrcidStatus newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AllOrcidStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AllOrcidStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\AllOrcidStatus findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AllOrcidStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AllOrcidStatus[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AllOrcidStatus|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AllOrcidStatus saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AllOrcidStatus[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AllOrcidStatus[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AllOrcidStatus[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AllOrcidStatus[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AllOrcidStatusesTable extends Table
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

        $this->setTable('ULS.ALL_ORCID_STATUSES');

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
            ->integer('ORCID_USER_ID')
            ->allowEmptyString('ORCID_USER_ID');

        $validator
            ->integer('ORCID_STATUS_TYPE_ID')
            ->allowEmptyString('ORCID_STATUS_TYPE_ID');

        $validator
            ->dateTime('STATUS_TIMESTAMP')
            ->allowEmptyDateTime('STATUS_TIMESTAMP');

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
