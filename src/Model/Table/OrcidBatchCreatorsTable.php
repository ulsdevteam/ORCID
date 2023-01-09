<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

/**
 * OrcidBatchCreators Model
 * 
 *@property \App\Model\Table\OrcidBatchesTable&\Cake\ORM\Association\HasMany $ORCIDBATCHES
 * 
 * @method \App\Model\Entity\OrcidBatchCreator newEmptyEntity()
 * @method \App\Model\Entity\OrcidBatchCreator newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchCreator[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchCreator get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrcidBatchCreator findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrcidBatchCreator patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchCreator[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchCreator|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidBatchCreator saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidBatchCreator[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatchCreator[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatchCreator[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatchCreator[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class OrcidBatchCreatorsTable extends Table
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
        $config["alias"] = strtoupper($config["alias"]);
        $this->setAlias($config["alias"]);

        $this->setTable('ULS.ORCID_BATCH_CREATORS');
        $this->setDisplayField('NAME');
        $this->setPrimaryKey('ID');

        $this->hasMany('OrcidBatches', [
            'foreignKey' => 'ORCID_BATCH_CREATOR_ID',
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
            ->maxLength('NAME', 8)
            ->requirePresence('NAME', 'create')
            ->notEmptyString('NAME', 'Creator name must be provided.');

        $validator
            ->integer('FLAGS')
            ->notEmptyString('FLAGS');

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
