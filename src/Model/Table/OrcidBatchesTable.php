<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OrcidBatches Model
 *
 * @property \App\Model\Table\OrcidBatchCreatorsTable&\Cake\ORM\Association\BelongsTo $OrcidBatchCreators
 * @property \App\Model\Table\OrcidBatchTriggersTable&\Cake\ORM\Association\HasMany $OrcidBatchTriggers
 * @property \App\Model\Table\OrcidEmailsTable&\Cake\ORM\Association\HasMany $OrcidEmails
 *
 * @method \App\Model\Entity\OrcidBatch newEmptyEntity()
 * @method \App\Model\Entity\OrcidBatch newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatch[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatch get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrcidBatch findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrcidBatch patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatch[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatch|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidBatch saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidBatch[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatch[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatch[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatch[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class OrcidBatchesTable extends Table
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

        $this->setTable('orcid_batches');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('OrcidBatchCreators', [
            'foreignKey' => 'orcid_batch_creator_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('OrcidBatchTriggers', [
            'foreignKey' => 'orcid_batch_id',
        ]);
        $this->hasMany('OrcidEmails', [
            'foreignKey' => 'orcid_batch_id',
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

        /* $validator
            ->scalar('subject')
            ->maxLength('subject', 512)
            ->requirePresence('subject', 'create')
            ->notEmptyString('subject'); */

        $validator
            ->scalar('body')
            ->maxLength('body', 4294967295)
            ->requirePresence('body', 'create')
            ->notEmptyString('body');

        $validator
            ->scalar('from_name')
            ->maxLength('from_name', 64)
            ->requirePresence('from_name', 'create')
            ->notEmptyString('from_name');

        $validator
            ->scalar('from_addr')
            ->maxLength('from_addr', 64)
            ->requirePresence('from_addr', 'create')
            ->notEmptyString('from_addr');

        $validator
            ->scalar('reply_to')
            ->maxLength('reply_to', 64)
            ->allowEmptyString('reply_to');

        $validator
            ->requirePresence('orcid_batch_creator_id', 'create')
            ->notEmptyString('orcid_batch_creator_id');

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
        $rules->add($rules->existsIn('orcid_batch_creator_id', 'OrcidBatchCreators'), ['errorField' => 'orcid_batch_creator_id']);

        return $rules;
    }
}
