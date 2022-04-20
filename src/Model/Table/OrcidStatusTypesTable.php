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
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('orcid_status_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('OrcidBatchTriggers', [
            'foreignKey' => 'orcid_status_type_id',
        ]);
        $this->hasMany('OrcidStatuses', [
            'foreignKey' => 'orcid_status_type_id',
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
            ->decimal('seq')
            ->allowEmptyString('seq');

        return $validator;
    }
}
