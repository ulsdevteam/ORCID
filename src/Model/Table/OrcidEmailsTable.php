<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OrcidEmails Model
 *
 * @property \App\Model\Table\OrcidUsersTable&\Cake\ORM\Association\BelongsTo $OrcidUsers
 * @property \App\Model\Table\OrcidBatchesTable&\Cake\ORM\Association\BelongsTo $OrcidBatches
 *
 * @method \App\Model\Entity\OrcidEmail newEmptyEntity()
 * @method \App\Model\Entity\OrcidEmail newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidEmail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidEmail get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrcidEmail findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrcidEmail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidEmail[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidEmail|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidEmail saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidEmail[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidEmail[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidEmail[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidEmail[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class OrcidEmailsTable extends Table
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

        $this->setTable('ULS.ORCID_EMAILS');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->belongsTo('OrcidUsers', [
            'foreignKey' => 'ORCID_USER_ID',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('OrcidBatches', [
            'foreignKey' => 'ORCID_BATCH_ID',
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
            ->integer('ORCID_BATCH_ID')
            ->requirePresence('ORCID_BATCH_ID', 'create')
            ->notEmptyString('ORCID_BATCH_ID')
            ->add('ORCID_BATCH_ID', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->dateTime('QUEUED')
            ->allowEmptyDateTime('QUEUED');

        $validator
            ->dateTime('SENT')
            ->allowEmptyDateTime('SENT');

        $validator
            ->dateTime('CANCELLED')
            ->allowEmptyDateTime('CANCELLED');

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
        $rules->add($rules->isUnique(['ORCID_USER_ID']), ['errorField' => 'ORCID_USER_ID']);
        $rules->add($rules->isUnique(['CANCELLED'], ['allowMultipleNulls' => true]), ['errorField' => 'CANCELLED']);
        $rules->add($rules->isUnique(['QUEUED'], ['allowMultipleNulls' => true]), ['errorField' => 'QUEUED']);
        $rules->add($rules->isUnique(['SENT'], ['allowMultipleNulls' => true]), ['errorField' => 'SENT']);
        $rules->add($rules->isUnique(['ORCID_BATCH_ID']), ['errorField' => 'ORCID_BATCH_ID']);
        $rules->add($rules->existsIn('ORCID_USER_ID', 'OrcidUsers'), ['errorField' => 'ORCID_USER_ID']);
        $rules->add($rules->existsIn('ORCID_BATCH_ID', 'OrcidBatches'), ['errorField' => 'ORCID_BATCH_ID']);

        return $rules;
    }
}
