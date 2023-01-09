<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

/**
 * OrcidBatchGroupCaches Model
 *
 * @property \App\Model\Table\OrcidBatchGroupsTable&\Cake\ORM\Association\BelongsTo $OrcidBatchGroups
 * @property \App\Model\Table\OrcidUsersTable&\Cake\ORM\Association\BelongsTo $OrcidUsers
 * 
 * @method \App\Model\Entity\OrcidBatchGroupCache newEmptyEntity()
 * @method \App\Model\Entity\OrcidBatchGroupCache newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchGroupCache[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchGroupCache get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroupCache findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroupCache patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchGroupCache[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidBatchGroupCache|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroupCache saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroupCache[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroupCache[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroupCache[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidBatchGroupCache[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class OrcidBatchGroupCachesTable extends Table
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

        $this->setTable('ULS.ORCID_BATCH_GROUP_CACHES');
        $this->setDisplayField('id');
        $this->setPrimaryKey('ID');

        $this->belongsTo('OrcidBatchGroups', [
            'foreignKey' => 'ORCID_BATCH_GROUP_ID',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('OrcidUsers', [
            'foreignKey' => 'ORCID_USER_ID',
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
            ->integer('ORCID_BATCH_GROUP_ID')
            ->requirePresence('ORCID_BATCH_GROUP_ID', 'create')
            ->notEmptyString('ORCID_BATCH_GROUP_ID');

        $validator
            ->integer('ORCID_USER_ID')
            ->requirePresence('ORCID_USER_ID', 'create')
            ->notEmptyString('ORCID_USER_ID');

        $validator
            ->dateTime('DEPRECATED')
            ->allowEmptyDateTime('DEPRECATED');

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
        $rules->add($rules->existsIn('ORCID_BATCH_GROUP_ID', 'OrcidBatchGroups'), ['errorField' => 'ORCID_BATCH_GROUP_ID']);
        $rules->add($rules->existsIn('ORCID_USER_ID', 'OrcidUsers'), ['errorField' => 'ORCID_USER_ID']);

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
