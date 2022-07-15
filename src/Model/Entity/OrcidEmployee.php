<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcidEmployee Entity
 *
 * @property string $EMPLOYEE_NBR
 * @property string|null $USERNAME
 * @property string|null $FULL_NAME
 * @property string|null $FIRST_NAME
 * @property string|null $LAST_NAME
 * @property string|null $EMAIL_ADDRESS
 * @property string|null $RG_PREFERENCE
 * @property string|null $BUILDING_NAME
 * @property string|null $BUILDING_ABBRV
 * @property string|null $ROOM_NBR
 * @property string|null $HOME_ADDRESS_LINE1
 * @property string|null $HOME_ADDRESS_LINE2
 * @property string|null $HOME_ADDRESS_LINE3
 * @property string|null $HOME_CITY
 * @property string|null $HOME_STATE
 * @property string|null $HOME_POSTAL_CD
 * @property string|null $ASSIGNMENT_STATUS
 * @property string|null $EMPLOYMENT_CATEGORY_CD
 * @property string|null $JOB_TYPE
 * @property string|null $JOB_FAMILY
 * @property string|null $JOB_CLASS
 * @property string|null $GENDER_CD
 * @property string|null $GENDER_DESCR
 * @property string|null $ETHNIC_GROUP_CD
 * @property string|null $ETHNIC_AMIND_FLG
 * @property string|null $ETHNIC_ASIAN_FLG
 * @property string|null $ETHNIC_BLACK_FLG
 * @property string|null $ETHNIC_HISPA_FLG
 * @property string|null $ETHNIC_PACIF_FLG
 * @property string|null $ETHNIC_WHITE_FLG
 * @property string|null $CAMPUS_CD
 * @property string|null $RESPONSIBILITY_CENTER_CD
 * @property string|null $RESPONSIBILITY_CENTER_DESCR
 * @property string|null $DEPARTMENT_CD
 * @property string|null $DEPARTMENT_DESCR
 * @property string|null $FACULTY_EMERITUS_FLG
 * @property string|null $UPP_CD
 * @property int|null $AGE
 */
class OrcidEmployee extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'USERNAME' => true,
        'FULL_NAME' => true,
        'FIRST_NAME' => true,
        'LAST_NAME' => true,
        'EMAIL_ADDRESS' => true,
        'RG_PREFERENCE' => true,
        'BUILDING_NAME' => true,
        'BUILDING_ABBRV' => true,
        'ROOM_NBR' => true,
        'HOME_ADDRESS_LINE1' => true,
        'HOME_ADDRESS_LINE2' => true,
        'HOME_ADDRESS_LINE3' => true,
        'HOME_CITY' => true,
        'HOME_STATE' => true,
        'HOME_POSTAL_CD' => true,
        'ASSIGNMENT_STATUS' => true,
        'EMPLOYMENT_CATEGORY_CD' => true,
        'JOB_TYPE' => true,
        'JOB_FAMILY' => true,
        'JOB_CLASS' => true,
        'GENDER_CD' => true,
        'GENDER_DESCR' => true,
        'ETHNIC_GROUP_CD' => true,
        'ETHNIC_AMIND_FLG' => true,
        'ETHNIC_ASIAN_FLG' => true,
        'ETHNIC_BLACK_FLG' => true,
        'ETHNIC_HISPA_FLG' => true,
        'ETHNIC_PACIF_FLG' => true,
        'ETHNIC_WHITE_FLG' => true,
        'CAMPUS_CD' => true,
        'RESPONSIBILITY_CENTER_CD' => true,
        'RESPONSIBILITY_CENTER_DESCR' => true,
        'DEPARTMENT_CD' => true,
        'DEPARTMENT_DESCR' => true,
        'FACULTY_EMERITUS_FLG' => true,
        'UPP_CD' => true,
        'AGE' => true,
    ];
}
