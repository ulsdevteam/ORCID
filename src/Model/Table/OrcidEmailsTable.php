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

        $this->setTable('orcid_emails');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('OrcidUsers', [
            'foreignKey' => 'orcid_user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('OrcidBatches', [
            'foreignKey' => 'orcid_batch_id',
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
            ->requirePresence('orcid_user_id', 'create')
            ->notEmptyString('orcid_user_id');

        $validator
            ->requirePresence('orcid_batch_id', 'create')
            ->notEmptyString('orcid_batch_id');

        $validator
            ->date('queued')
            ->allowEmptyDate('queued');

        $validator
            ->date('sent')
            ->allowEmptyDate('sent');

        $validator
            ->date('cancelled')
            ->allowEmptyDate('cancelled');

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
        $rules->add($rules->existsIn('orcid_user_id', 'OrcidUsers'), ['errorField' => 'orcid_user_id']);
        $rules->add($rules->existsIn('orcid_batch_id', 'OrcidBatches'), ['errorField' => 'orcid_batch_id']);

        return $rules;
    }
}
