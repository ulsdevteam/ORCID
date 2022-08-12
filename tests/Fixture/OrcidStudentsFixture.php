<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrcidStudentsFixture
 */
class OrcidStudentsFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ORCID_STUDENT';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'EMPLID' => 'bee551bf-8bbc-4a3c-b243-bcdd9b6d36ac',
                'USERNAME' => 'Lorem ',
                'FULL_NAME' => 'Lorem ipsum dolor sit amet',
                'FIRST_NAME' => 'Lorem ipsum dolor sit amet',
                'LAST_NAME' => 'Lorem ipsum dolor sit amet',
                'EMAIL_ADDRESS' => 'Lorem ipsum dolor sit amet',
                'HOME_ADDRESS1' => 'Lorem ipsum dolor sit amet',
                'HOME_ADDRESS2' => 'Lorem ipsum dolor sit amet',
                'HOME_ADDRESS3' => 'Lorem ipsum dolor sit amet',
                'HOME_CITY' => 'Lorem ipsum dolor sit amet',
                'HOME_STATE' => 'Lore',
                'HOME_POSTAL_CD' => 'Lorem ipsu',
                'HOME_COUNTRY_DESCR' => 'Lorem ipsum dolor sit amet',
                'DORM_ADDRESS1' => 'Lorem ipsum dolor sit amet',
                'DORM_ADDRESS2' => 'Lorem ipsum dolor sit amet',
                'DORM_ADDRESS3' => 'Lorem ipsum dolor sit amet',
                'DORM_CITY' => 'Lorem ipsum dolor sit amet',
                'DORM_STATE' => 'Lore',
                'DORM_POSTAL_CD' => 'Lorem ipsu',
                'MAIL_ADDRESS1' => 'Lorem ipsum dolor sit amet',
                'MAIL_ADDRESS2' => 'Lorem ipsum dolor sit amet',
                'MAIL_ADDRESS3' => 'Lorem ipsum dolor sit amet',
                'MAIL_CITY' => 'Lorem ipsum dolor sit amet',
                'MAIL_STATE' => 'Lore',
                'MAIL_POSTAL_CD' => 'Lorem ipsu',
                'MAIL_COUNTRY_DESCR' => 'Lorem ipsum dolor sit amet',
                'GENDER_CD' => 'Lo',
                'GENDER_DESCR' => 'Lorem ipsum dolor sit amet',
                'ETHNIC_GROUP_CD' => 'Lorem ',
                'ETHNIC_AMIND_FLG' => 'L',
                'ETHNIC_ASIAN_FLG' => 'L',
                'ETHNIC_BLACK_FLG' => 'L',
                'ETHNIC_HISPA_FLG' => 'L',
                'ETHNIC_PACIF_FLG' => 'L',
                'ETHNIC_WHITE_FLG' => 'L',
                'AGE' => 1,
                'FERPA_FLG' => 'L',
                'TERM_CD' => '7f4b68d8-647a-4185-b10e-a6fa982ce039',
                'ENROLLED_IN_TERM_FLG' => 'L',
                'DEGREE_AWARDED_IN_TERM_FLG' => 'L',
                'INCOMING_IN_TERM_FLG' => 'L',
                'ADMIT_TYPE_CD' => 'L',
                'ACADEMIC_LEVEL_CD' => 'Lo',
                'ACADEMIC_LEVEL_DESCR' => 'Lorem ipsum dolor sit amet',
                'CAMPUS_CD' => 'Lor',
                'CAREER_LEVEL_CD' => '5d733c21-29fe-4c72-b300-c5e5ac3afa1a',
                'CAREER_LEVEL_DESCR' => 'Lorem ipsum dolor sit amet',
                'ACADEMIC_GROUP_CD' => 'Lor',
                'ACADEMIC_GROUP_DESCR' => 'Lorem ipsum dolor sit amet',
                'DEGREE_CD' => 'Lorem ',
                'DEGREE_DESCR' => 'Lorem ipsum dolor sit amet',
                'DEGREE_CHECKOUT_STATUS' => 'Lorem ipsum dolor sit amet',
                'TOTAL_CUMULATIVE_CREDITS' => 1.5,
                'CUMULATIVE_GPA' => 1.5,
            ],
        ];
        parent::init();
    }
}
