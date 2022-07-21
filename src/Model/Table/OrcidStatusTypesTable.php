<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OrcidStatusTypes Model
 *
 * @property \App\Model\Table\OrcidBatchTriggersTable&\Cake\ORM\Association\HasMany $OrcidBatchTriggers
 * @property \App\Model\Table\OrcidStatusesTable&\Cake\ORM\Association\HasMany $OrcidStatuses
 *
 * @method \App\Model\Entity\OrcidStatusType newEmptyEntity()
 * @method \App\Model\Entity\OrcidStatusType newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidStatusType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidStatusType get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrcidStatusType findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrcidStatusType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidStatusType[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidStatusType|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidStatusType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidStatusType[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidStatusType[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidStatusType[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidStatusType[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class OrcidStatusTypesTable extends Table
{

    public const OPTOUT_SEQUENCE = 6;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('ULS.ORCID_STATUS_TYPES');
        $this->setDisplayField('NAME');
        $this->setPrimaryKey('ID');

        $this->hasMany('AllOrcidStatuses', [
            'foreignKey' => 'ORCID_STATUS_TYPE_ID',
        ]);
        $this->hasMany('CurrentOrcidStatuses', [
            'foreignKey' => 'ORCID_STATUS_TYPE_ID',
        ]);
        $this->hasMany('OrcidBatchTriggers', [
            'foreignKey' => 'ORCID_STATUS_TYPE_ID',
        ]);
        $this->hasMany('OrcidStatuses', [
            'foreignKey' => 'ORCID_STATUS_TYPE_ID',
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
            ->notEmptyString('NAME');

        $validator
            ->integer('SEQ')
            ->allowEmptyString('SEQ')
            ->add('SEQ', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
        $rules->add($rules->isUnique(['SEQ'], ['allowMultipleNulls' => true]), ['errorField' => 'SEQ']);
        $rules->add($rules->isUnique(['ID']), ['errorField' => 'ID']);

        return $rules;
    }
}
