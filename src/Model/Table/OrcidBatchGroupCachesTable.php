<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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

        $this->setTable('orcid_batch_group_caches');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('OrcidBatchGroups', [
            'foreignKey' => 'orcid_batch_group_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('OrcidUsers', [
            'foreignKey' => 'orcid_user_id',
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
            ->requirePresence('orcid_batch_group_id', 'create')
            ->notEmptyString('orcid_batch_group_id');

        $validator
            ->requirePresence('orcid_user_id', 'create')
            ->notEmptyString('orcid_user_id');

        $validator
            ->date('deprecated')
            ->allowEmptyDate('deprecated');

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
        $rules->add($rules->existsIn('orcid_batch_group_id', 'OrcidBatchGroups'), ['errorField' => 'orcid_batch_group_id']);
        $rules->add($rules->existsIn('orcid_user_id', 'OrcidUsers'), ['errorField' => 'orcid_user_id']);

        return $rules;
    }
}
