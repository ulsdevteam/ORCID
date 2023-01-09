<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

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
        $config["alias"] = strtoupper($config["alias"]);
        parent::initialize($config);

        $this->setAlias($config["alias"]);

        $this->setTable('ULS.ORCID_BATCHES');
        $this->setDisplayField('NAME');
        $this->setPrimaryKey('ID');

        $this->belongsTo('OrcidBatchCreators', [
            'foreignKey' => 'ORCID_BATCH_CREATOR_ID',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('OrcidBatchTriggers', [
            'foreignKey' => 'ORCID_BATCH_ID',
        ]);
        $this->hasMany('OrcidEmails', [
            'foreignKey' => 'ORCID_BATCH_ID',
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
            ->requirePresence('NAME', 'create')
            ->notEmptyString('NAME', 'A name for this batch must be provided.');

        $validator
            ->scalar('SUBJECT')
            ->maxLength('SUBJECT', 512)
            ->requirePresence('SUBJECT', 'create')
            ->notEmptyString('SUBJECT', 'The email subject must be provided.');

        $validator
            ->scalar('BODY')
            ->requirePresence('BODY', 'create')
            ->notEmptyString('BODY', 'The email body must be provided.');

        $validator
            ->scalar('FROM_NAME')
            ->maxLength('FROM_NAME', 64)
            ->requirePresence('FROM_NAME', 'create')
            ->notEmptyString('FROM_NAME', 'The from display name must be provided.');

        $validator
            ->scalar('FROM_ADDR')
            ->maxLength('FROM_ADDR', 64)
            ->requirePresence('FROM_ADDR', 'create')
            ->notEmptyString('FROM_ADDR', 'The from address must be a valid email.');

        $validator
            ->scalar('REPLY_TO')
            ->maxLength('REPLY_TO', 64)
            ->allowEmptyString('REPLY_TO', 'The reply to address must be a valid email.');

        $validator
            ->integer('ORCID_BATCH_CREATOR_ID')
            ->requirePresence('ORCID_BATCH_CREATOR_ID', 'create')
            ->notEmptyString('ORCID_BATCH_CREATOR_ID', 'The batch creator must be provided.');

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
        $rules->add($rules->existsIn('ORCID_BATCH_CREATOR_ID', 'OrcidBatchCreators'), ['errorField' => 'ORCID_BATCH_CREATOR_ID', 'message' => 'The batch creator must be provided.']);
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
}
