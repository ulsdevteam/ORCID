<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrcidEmployeesFixture
 */
class OrcidEmployeesFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ORCID_EMPLOYEE';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'EMPLOYEE_NBR' => 'a67c4990-2623-4440-a3e6-77bf8b0d6e82',
                'USERNAME' => 'Lorem ',
                'FULL_NAME' => 'Lorem ipsum dolor sit amet',
                'FIRST_NAME' => 'Lorem ipsum dolor sit amet',
                'LAST_NAME' => 'Lorem ipsum dolor sit amet',
                'EMAIL_ADDRESS' => 'Lorem ipsum dolor sit amet',
                'RG_PREFERENCE' => 'Lorem ipsum dolor sit amet',
                'BUILDING_NAME' => 'Lorem ipsum dolor sit amet',
                'BUILDING_ABBRV' => 'Lorem ipsu',
                'ROOM_NBR' => 'Lorem ipsum dolor ',
                'HOME_ADDRESS_LINE1' => 'Lorem ipsum dolor sit amet',
                'HOME_ADDRESS_LINE2' => 'Lorem ipsum dolor sit amet',
                'HOME_ADDRESS_LINE3' => 'Lorem ipsum dolor sit amet',
                'HOME_CITY' => 'Lorem ipsum dolor sit amet',
                'HOME_STATE' => 'Lorem ipsum dolor sit amet',
                'HOME_POSTAL_CD' => 'Lorem ipsum dolor sit amet',
                'ASSIGNMENT_STATUS' => 'Lorem ipsum dolor sit amet',
                'EMPLOYMENT_CATEGORY_CD' => 'L',
                'JOB_TYPE' => 'Lorem ipsum dolor sit amet',
                'JOB_FAMILY' => 'Lorem ipsum dolor sit amet',
                'JOB_CLASS' => 'Lorem ipsum dolor sit amet',
                'GENDER_CD' => 'Lo',
                'GENDER_DESCR' => 'Lorem ipsum dolor sit amet',
                'ETHNIC_GROUP_CD' => 'Lor',
                'ETHNIC_AMIND_FLG' => 'L',
                'ETHNIC_ASIAN_FLG' => 'L',
                'ETHNIC_BLACK_FLG' => 'L',
                'ETHNIC_HISPA_FLG' => 'L',
                'ETHNIC_PACIF_FLG' => 'L',
                'ETHNIC_WHITE_FLG' => 'L',
                'CAMPUS_CD' => 'L',
                'RESPONSIBILITY_CENTER_CD' => 'Lorem ip',
                'RESPONSIBILITY_CENTER_DESCR' => 'Lorem ipsum dolor sit amet',
                'DEPARTMENT_CD' => 'Lorem ip',
                'DEPARTMENT_DESCR' => 'Lorem ipsum dolor sit amet',
                'FACULTY_EMERITUS_FLG' => 'L',
                'UPP_CD' => 'Lor',
                'AGE' => 1,
            ],
        ];
        parent::init();
    }
}
