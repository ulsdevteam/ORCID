<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrcidStudent Entity
 *
 * @property string $EMPLID
 * @property string|null $USERNAME
 * @property string|null $FULL_NAME
 * @property string|null $FIRST_NAME
 * @property string|null $LAST_NAME
 * @property string|null $EMAIL_ADDRESS
 * @property string|null $HOME_ADDRESS1
 * @property string|null $HOME_ADDRESS2
 * @property string|null $HOME_ADDRESS3
 * @property string|null $HOME_CITY
 * @property string|null $HOME_STATE
 * @property string|null $HOME_POSTAL_CD
 * @property string|null $HOME_COUNTRY_DESCR
 * @property string|null $DORM_ADDRESS1
 * @property string|null $DORM_ADDRESS2
 * @property string|null $DORM_ADDRESS3
 * @property string|null $DORM_CITY
 * @property string|null $DORM_STATE
 * @property string|null $DORM_POSTAL_CD
 * @property string|null $MAIL_ADDRESS1
 * @property string|null $MAIL_ADDRESS2
 * @property string|null $MAIL_ADDRESS3
 * @property string|null $MAIL_CITY
 * @property string|null $MAIL_STATE
 * @property string|null $MAIL_POSTAL_CD
 * @property string|null $MAIL_COUNTRY_DESCR
 * @property string|null $GENDER_CD
 * @property string|null $GENDER_DESCR
 * @property string|null $ETHNIC_GROUP_CD
 * @property string|null $ETHNIC_AMIND_FLG
 * @property string|null $ETHNIC_ASIAN_FLG
 * @property string|null $ETHNIC_BLACK_FLG
 * @property string|null $ETHNIC_HISPA_FLG
 * @property string|null $ETHNIC_PACIF_FLG
 * @property string|null $ETHNIC_WHITE_FLG
 * @property int|null $AGE
 * @property string|null $FERPA_FLG
 * @property string $TERM_CD
 * @property string|null $ENROLLED_IN_TERM_FLG
 * @property string|null $DEGREE_AWARDED_IN_TERM_FLG
 * @property string|null $INCOMING_IN_TERM_FLG
 * @property string|null $ADMIT_TYPE_CD
 * @property string|null $ACADEMIC_LEVEL_CD
 * @property string|null $ACADEMIC_LEVEL_DESCR
 * @property string|null $CAMPUS_CD
 * @property string $CAREER_LEVEL_CD
 * @property string|null $CAREER_LEVEL_DESCR
 * @property string|null $ACADEMIC_GROUP_CD
 * @property string|null $ACADEMIC_GROUP_DESCR
 * @property string|null $DEGREE_CD
 * @property string|null $DEGREE_DESCR
 * @property string|null $DEGREE_CHECKOUT_STATUS
 * @property string|null $TOTAL_CUMULATIVE_CREDITS
 * @property string|null $CUMULATIVE_GPA
 */
class OrcidStudent extends Entity
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
        'HOME_ADDRESS1' => true,
        'HOME_ADDRESS2' => true,
        'HOME_ADDRESS3' => true,
        'HOME_CITY' => true,
        'HOME_STATE' => true,
        'HOME_POSTAL_CD' => true,
        'HOME_COUNTRY_DESCR' => true,
        'DORM_ADDRESS1' => true,
        'DORM_ADDRESS2' => true,
        'DORM_ADDRESS3' => true,
        'DORM_CITY' => true,
        'DORM_STATE' => true,
        'DORM_POSTAL_CD' => true,
        'MAIL_ADDRESS1' => true,
        'MAIL_ADDRESS2' => true,
        'MAIL_ADDRESS3' => true,
        'MAIL_CITY' => true,
        'MAIL_STATE' => true,
        'MAIL_POSTAL_CD' => true,
        'MAIL_COUNTRY_DESCR' => true,
        'GENDER_CD' => true,
        'GENDER_DESCR' => true,
        'ETHNIC_GROUP_CD' => true,
        'ETHNIC_AMIND_FLG' => true,
        'ETHNIC_ASIAN_FLG' => true,
        'ETHNIC_BLACK_FLG' => true,
        'ETHNIC_HISPA_FLG' => true,
        'ETHNIC_PACIF_FLG' => true,
        'ETHNIC_WHITE_FLG' => true,
        'AGE' => true,
        'FERPA_FLG' => true,
        'ENROLLED_IN_TERM_FLG' => true,
        'DEGREE_AWARDED_IN_TERM_FLG' => true,
        'INCOMING_IN_TERM_FLG' => true,
        'ADMIT_TYPE_CD' => true,
        'ACADEMIC_LEVEL_CD' => true,
        'ACADEMIC_LEVEL_DESCR' => true,
        'CAMPUS_CD' => true,
        'CAREER_LEVEL_DESCR' => true,
        'ACADEMIC_GROUP_CD' => true,
        'ACADEMIC_GROUP_DESCR' => true,
        'DEGREE_CD' => true,
        'DEGREE_DESCR' => true,
        'DEGREE_CHECKOUT_STATUS' => true,
        'TOTAL_CUMULATIVE_CREDITS' => true,
        'CUMULATIVE_GPA' => true,
    ];
}
