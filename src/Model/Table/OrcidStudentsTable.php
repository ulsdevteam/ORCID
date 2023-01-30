<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\Event\Event;
use ArrayObject;

/**
 * OrcidStudents Model
 *
 * @method \App\Model\Entity\OrcidStudent newEmptyEntity()
 * @method \App\Model\Entity\OrcidStudent newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidStudent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidStudent get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrcidStudent findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrcidStudent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidStudent[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidStudent|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidStudent saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidStudent[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidStudent[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidStudent[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidStudent[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class OrcidStudentsTable extends Table
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

        $this->setTable('ORCID_STUDENT');
        $this->setDisplayField('USERNAME');
        $this->setPrimaryKey(['EMPLID', 'TERM_CD', 'CAREER_LEVEL_CD']);
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
            ->scalar('USERNAME')
            ->maxLength('USERNAME', 8)
            ->allowEmptyString('USERNAME');

        $validator
            ->scalar('FULL_NAME')
            ->maxLength('FULL_NAME', 50)
            ->allowEmptyString('FULL_NAME');

        $validator
            ->scalar('FIRST_NAME')
            ->maxLength('FIRST_NAME', 30)
            ->allowEmptyString('FIRST_NAME');

        $validator
            ->scalar('LAST_NAME')
            ->maxLength('LAST_NAME', 30)
            ->allowEmptyString('LAST_NAME');

        $validator
            ->scalar('EMAIL_ADDRESS')
            ->maxLength('EMAIL_ADDRESS', 50)
            ->allowEmptyString('EMAIL_ADDRESS');

        $validator
            ->scalar('HOME_ADDRESS1')
            ->maxLength('HOME_ADDRESS1', 55)
            ->allowEmptyString('HOME_ADDRESS1');

        $validator
            ->scalar('HOME_ADDRESS2')
            ->maxLength('HOME_ADDRESS2', 55)
            ->allowEmptyString('HOME_ADDRESS2');

        $validator
            ->scalar('HOME_ADDRESS3')
            ->maxLength('HOME_ADDRESS3', 55)
            ->allowEmptyString('HOME_ADDRESS3');

        $validator
            ->scalar('HOME_CITY')
            ->maxLength('HOME_CITY', 30)
            ->allowEmptyString('HOME_CITY');

        $validator
            ->scalar('HOME_STATE')
            ->maxLength('HOME_STATE', 6)
            ->allowEmptyString('HOME_STATE');

        $validator
            ->scalar('HOME_POSTAL_CD')
            ->maxLength('HOME_POSTAL_CD', 12)
            ->allowEmptyString('HOME_POSTAL_CD');

        $validator
            ->scalar('HOME_COUNTRY_DESCR')
            ->maxLength('HOME_COUNTRY_DESCR', 30)
            ->allowEmptyString('HOME_COUNTRY_DESCR');

        $validator
            ->scalar('DORM_ADDRESS1')
            ->maxLength('DORM_ADDRESS1', 55)
            ->allowEmptyString('DORM_ADDRESS1');

        $validator
            ->scalar('DORM_ADDRESS2')
            ->maxLength('DORM_ADDRESS2', 55)
            ->allowEmptyString('DORM_ADDRESS2');

        $validator
            ->scalar('DORM_ADDRESS3')
            ->maxLength('DORM_ADDRESS3', 55)
            ->allowEmptyString('DORM_ADDRESS3');

        $validator
            ->scalar('DORM_CITY')
            ->maxLength('DORM_CITY', 30)
            ->allowEmptyString('DORM_CITY');

        $validator
            ->scalar('DORM_STATE')
            ->maxLength('DORM_STATE', 6)
            ->allowEmptyString('DORM_STATE');

        $validator
            ->scalar('DORM_POSTAL_CD')
            ->maxLength('DORM_POSTAL_CD', 12)
            ->allowEmptyString('DORM_POSTAL_CD');

        $validator
            ->scalar('MAIL_ADDRESS1')
            ->maxLength('MAIL_ADDRESS1', 55)
            ->allowEmptyString('MAIL_ADDRESS1');

        $validator
            ->scalar('MAIL_ADDRESS2')
            ->maxLength('MAIL_ADDRESS2', 55)
            ->allowEmptyString('MAIL_ADDRESS2');

        $validator
            ->scalar('MAIL_ADDRESS3')
            ->maxLength('MAIL_ADDRESS3', 55)
            ->allowEmptyString('MAIL_ADDRESS3');

        $validator
            ->scalar('MAIL_CITY')
            ->maxLength('MAIL_CITY', 30)
            ->allowEmptyString('MAIL_CITY');

        $validator
            ->scalar('MAIL_STATE')
            ->maxLength('MAIL_STATE', 6)
            ->allowEmptyString('MAIL_STATE');

        $validator
            ->scalar('MAIL_POSTAL_CD')
            ->maxLength('MAIL_POSTAL_CD', 12)
            ->allowEmptyString('MAIL_POSTAL_CD');

        $validator
            ->scalar('MAIL_COUNTRY_DESCR')
            ->maxLength('MAIL_COUNTRY_DESCR', 30)
            ->allowEmptyString('MAIL_COUNTRY_DESCR');

        $validator
            ->scalar('GENDER_CD')
            ->maxLength('GENDER_CD', 2)
            ->allowEmptyString('GENDER_CD');

        $validator
            ->scalar('GENDER_DESCR')
            ->maxLength('GENDER_DESCR', 30)
            ->allowEmptyString('GENDER_DESCR');

        $validator
            ->scalar('ETHNIC_GROUP_CD')
            ->maxLength('ETHNIC_GROUP_CD', 8)
            ->allowEmptyString('ETHNIC_GROUP_CD');

        $validator
            ->scalar('ETHNIC_AMIND_FLG')
            ->maxLength('ETHNIC_AMIND_FLG', 1)
            ->allowEmptyString('ETHNIC_AMIND_FLG');

        $validator
            ->scalar('ETHNIC_ASIAN_FLG')
            ->maxLength('ETHNIC_ASIAN_FLG', 1)
            ->allowEmptyString('ETHNIC_ASIAN_FLG');

        $validator
            ->scalar('ETHNIC_BLACK_FLG')
            ->maxLength('ETHNIC_BLACK_FLG', 1)
            ->allowEmptyString('ETHNIC_BLACK_FLG');

        $validator
            ->scalar('ETHNIC_HISPA_FLG')
            ->maxLength('ETHNIC_HISPA_FLG', 1)
            ->allowEmptyString('ETHNIC_HISPA_FLG');

        $validator
            ->scalar('ETHNIC_PACIF_FLG')
            ->maxLength('ETHNIC_PACIF_FLG', 1)
            ->allowEmptyString('ETHNIC_PACIF_FLG');

        $validator
            ->scalar('ETHNIC_WHITE_FLG')
            ->maxLength('ETHNIC_WHITE_FLG', 1)
            ->allowEmptyString('ETHNIC_WHITE_FLG');

        $validator
            ->integer('AGE')
            ->allowEmptyString('AGE');

        $validator
            ->scalar('FERPA_FLG')
            ->maxLength('FERPA_FLG', 1)
            ->allowEmptyString('FERPA_FLG');

        $validator
            ->scalar('ENROLLED_IN_TERM_FLG')
            ->maxLength('ENROLLED_IN_TERM_FLG', 1)
            ->allowEmptyString('ENROLLED_IN_TERM_FLG');

        $validator
            ->scalar('DEGREE_AWARDED_IN_TERM_FLG')
            ->maxLength('DEGREE_AWARDED_IN_TERM_FLG', 1)
            ->allowEmptyString('DEGREE_AWARDED_IN_TERM_FLG');

        $validator
            ->scalar('INCOMING_IN_TERM_FLG')
            ->maxLength('INCOMING_IN_TERM_FLG', 1)
            ->allowEmptyString('INCOMING_IN_TERM_FLG');

        $validator
            ->scalar('ADMIT_TYPE_CD')
            ->maxLength('ADMIT_TYPE_CD', 3)
            ->allowEmptyString('ADMIT_TYPE_CD');

        $validator
            ->scalar('ACADEMIC_LEVEL_CD')
            ->maxLength('ACADEMIC_LEVEL_CD', 2)
            ->allowEmptyString('ACADEMIC_LEVEL_CD');

        $validator
            ->scalar('ACADEMIC_LEVEL_DESCR')
            ->maxLength('ACADEMIC_LEVEL_DESCR', 30)
            ->allowEmptyString('ACADEMIC_LEVEL_DESCR');

        $validator
            ->scalar('CAMPUS_CD')
            ->maxLength('CAMPUS_CD', 5)
            ->allowEmptyString('CAMPUS_CD');

        $validator
            ->scalar('CAREER_LEVEL_DESCR')
            ->maxLength('CAREER_LEVEL_DESCR', 30)
            ->allowEmptyString('CAREER_LEVEL_DESCR');

        $validator
            ->scalar('ACADEMIC_GROUP_CD')
            ->maxLength('ACADEMIC_GROUP_CD', 5)
            ->allowEmptyString('ACADEMIC_GROUP_CD');

        $validator
            ->scalar('ACADEMIC_GROUP_DESCR')
            ->maxLength('ACADEMIC_GROUP_DESCR', 30)
            ->allowEmptyString('ACADEMIC_GROUP_DESCR');

        $validator
            ->scalar('DEGREE_CD')
            ->maxLength('DEGREE_CD', 8)
            ->allowEmptyString('DEGREE_CD');

        $validator
            ->scalar('DEGREE_DESCR')
            ->maxLength('DEGREE_DESCR', 30)
            ->allowEmptyString('DEGREE_DESCR');

        $validator
            ->scalar('DEGREE_CHECKOUT_STATUS')
            ->maxLength('DEGREE_CHECKOUT_STATUS', 30)
            ->allowEmptyString('DEGREE_CHECKOUT_STATUS');

        $validator
            ->decimal('TOTAL_CUMULATIVE_CREDITS')
            ->allowEmptyString('TOTAL_CUMULATIVE_CREDITS');

        $validator
            ->decimal('CUMULATIVE_GPA')
            ->allowEmptyString('CUMULATIVE_GPA');

        return $validator;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return (Configure::read('debug')) ? 'default-cds' : 'production-cds';
    }

    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $query->whereNotNull('USERNAME');
    } 
}
