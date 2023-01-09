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
 * OrcidEmployees Model
 *
 * @method \App\Model\Entity\OrcidEmployee newEmptyEntity()
 * @method \App\Model\Entity\OrcidEmployee newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidEmployee[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrcidEmployee get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrcidEmployee findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrcidEmployee patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidEmployee[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrcidEmployee|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidEmployee saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrcidEmployee[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidEmployee[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidEmployee[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrcidEmployee[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class OrcidEmployeesTable extends Table
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

        $this->setTable('ORCID_EMPLOYEE');
        $this->setDisplayField('USERNAME');
        $this->setPrimaryKey('EMPLOYEE_NBR');
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
            ->maxLength('FULL_NAME', 240)
            ->allowEmptyString('FULL_NAME');

        $validator
            ->scalar('FIRST_NAME')
            ->maxLength('FIRST_NAME', 150)
            ->allowEmptyString('FIRST_NAME');

        $validator
            ->scalar('LAST_NAME')
            ->maxLength('LAST_NAME', 150)
            ->allowEmptyString('LAST_NAME');

        $validator
            ->scalar('EMAIL_ADDRESS')
            ->maxLength('EMAIL_ADDRESS', 240)
            ->allowEmptyString('EMAIL_ADDRESS');

        $validator
            ->scalar('RG_PREFERENCE')
            ->maxLength('RG_PREFERENCE', 30)
            ->allowEmptyString('RG_PREFERENCE');

        $validator
            ->scalar('BUILDING_NAME')
            ->maxLength('BUILDING_NAME', 64)
            ->allowEmptyString('BUILDING_NAME');

        $validator
            ->scalar('BUILDING_ABBRV')
            ->maxLength('BUILDING_ABBRV', 12)
            ->allowEmptyString('BUILDING_ABBRV');

        $validator
            ->scalar('ROOM_NBR')
            ->maxLength('ROOM_NBR', 20)
            ->allowEmptyString('ROOM_NBR');

        $validator
            ->scalar('HOME_ADDRESS_LINE1')
            ->maxLength('HOME_ADDRESS_LINE1', 240)
            ->allowEmptyString('HOME_ADDRESS_LINE1');

        $validator
            ->scalar('HOME_ADDRESS_LINE2')
            ->maxLength('HOME_ADDRESS_LINE2', 240)
            ->allowEmptyString('HOME_ADDRESS_LINE2');

        $validator
            ->scalar('HOME_ADDRESS_LINE3')
            ->maxLength('HOME_ADDRESS_LINE3', 240)
            ->allowEmptyString('HOME_ADDRESS_LINE3');

        $validator
            ->scalar('HOME_CITY')
            ->maxLength('HOME_CITY', 30)
            ->allowEmptyString('HOME_CITY');

        $validator
            ->scalar('HOME_STATE')
            ->maxLength('HOME_STATE', 120)
            ->allowEmptyString('HOME_STATE');

        $validator
            ->scalar('HOME_POSTAL_CD')
            ->maxLength('HOME_POSTAL_CD', 30)
            ->allowEmptyString('HOME_POSTAL_CD');

        $validator
            ->scalar('ASSIGNMENT_STATUS')
            ->maxLength('ASSIGNMENT_STATUS', 60)
            ->allowEmptyString('ASSIGNMENT_STATUS');

        $validator
            ->scalar('EMPLOYMENT_CATEGORY_CD')
            ->maxLength('EMPLOYMENT_CATEGORY_CD', 3)
            ->allowEmptyString('EMPLOYMENT_CATEGORY_CD');

        $validator
            ->scalar('JOB_TYPE')
            ->maxLength('JOB_TYPE', 60)
            ->allowEmptyString('JOB_TYPE');

        $validator
            ->scalar('JOB_FAMILY')
            ->maxLength('JOB_FAMILY', 60)
            ->allowEmptyString('JOB_FAMILY');

        $validator
            ->scalar('JOB_CLASS')
            ->maxLength('JOB_CLASS', 60)
            ->allowEmptyString('JOB_CLASS');

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
            ->maxLength('ETHNIC_GROUP_CD', 5)
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
            ->scalar('CAMPUS_CD')
            ->maxLength('CAMPUS_CD', 3)
            ->allowEmptyString('CAMPUS_CD');

        $validator
            ->scalar('RESPONSIBILITY_CENTER_CD')
            ->maxLength('RESPONSIBILITY_CENTER_CD', 10)
            ->allowEmptyString('RESPONSIBILITY_CENTER_CD');

        $validator
            ->scalar('RESPONSIBILITY_CENTER_DESCR')
            ->maxLength('RESPONSIBILITY_CENTER_DESCR', 255)
            ->allowEmptyString('RESPONSIBILITY_CENTER_DESCR');

        $validator
            ->scalar('DEPARTMENT_CD')
            ->maxLength('DEPARTMENT_CD', 10)
            ->allowEmptyString('DEPARTMENT_CD');

        $validator
            ->scalar('DEPARTMENT_DESCR')
            ->maxLength('DEPARTMENT_DESCR', 255)
            ->allowEmptyString('DEPARTMENT_DESCR');

        $validator
            ->scalar('FACULTY_EMERITUS_FLG')
            ->maxLength('FACULTY_EMERITUS_FLG', 3)
            ->allowEmptyString('FACULTY_EMERITUS_FLG');

        $validator
            ->scalar('UPP_CD')
            ->maxLength('UPP_CD', 5)
            ->allowEmptyString('UPP_CD');

        $validator
            ->integer('AGE')
            ->allowEmptyString('AGE');

        return $validator;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return (Configure::read('debug')) ? 'default' : 'production-cds';
    }

    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $query->whereNotNull('USERNAME');
    }
}
