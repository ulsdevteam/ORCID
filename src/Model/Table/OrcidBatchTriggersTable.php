<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OrcidBatchTriggers Model
 *
 * @property \App\Model\Table\OrcidStatusTypesTable&\Cake\ORM\Association\BelongsTo $OrcidStatusTypes
 * @property \App\Model\Table\OrcidBatchesTable&\Cake\ORM\Association\BelongsTo $OrcidBatches
 * @property \App\Model\Table\OrcidBatchGroupsTable&\Cake\ORM\Association\BelongsTo $OrcidBatchGroups
 * @property \App\Model\Table\RequireBatchesTable&\Cake\ORM\Association\BelongsTo $RequireBatches
 *
 * @method \App\Model\Entity\OrcidBatchTrigger newEmptyEntity()
 * @method \App\Model\Entity\OrcidBatchTrigger newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchTrigger[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchTrigger get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrcidBatchTrigger findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrcidBatchTrigger patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchTrigger[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchTrigger|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidBatchTrigger saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidBatchTrigger[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatchTrigger[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatchTrigger[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatchTrigger[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class OrcidBatchTriggersTable extends Table
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

        $this->setTable('orcid_batch_triggers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('OrcidStatusTypes', [
            'foreignKey' => 'orcid_status_type_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('OrcidBatches', [
            'foreignKey' => 'orcid_batch_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('OrcidBatchGroups', [
            'foreignKey' => 'orcid_batch_group_id',
        ]);
        $this->belongsTo('RequireBatches', [
            'foreignKey' => 'require_batch_id',
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
            ->requirePresence('orcid_status_type_id', 'create')
            ->notEmptyString('orcid_status_type_id');

        $validator
            ->requirePresence('orcid_batch_id', 'create')
            ->notEmptyString('orcid_batch_id');

        $validator
            ->decimal('trigger_delay')
            ->requirePresence('trigger_delay', 'create')
            ->notEmptyString('trigger_delay');

        $validator
            ->allowEmptyString('orcid_batch_group_id');

        $validator
            ->date('begin_date')
            ->allowEmptyDate('begin_date');

        $validator
            ->allowEmptyString('require_batch_id');

        $validator
            ->decimal('repeat')
            ->notEmptyString('repeat');

        $validator
            ->decimal('maximum_repeat')
            ->notEmptyString('maximum_repeat');

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
        $rules->add($rules->existsIn('orcid_status_type_id', 'OrcidStatusTypes'), ['errorField' => 'orcid_status_type_id']);
        $rules->add($rules->existsIn('orcid_batch_id', 'OrcidBatches'), ['errorField' => 'orcid_batch_id']);
        $rules->add($rules->existsIn('orcid_batch_group_id', 'OrcidBatchGroups'), ['errorField' => 'orcid_batch_group_id']);
        $rules->add($rules->existsIn('require_batch_id', 'RequireBatches'), ['errorField' => 'require_batch_id']);

        return $rules;
    }
}
