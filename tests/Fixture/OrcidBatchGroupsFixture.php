<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrcidBatchGroupsFixture
 */
class OrcidBatchGroupsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'ID' => 1,
                'NAME' => 'Lorem ipsum dolor sit amet',
                'GROUP_DEFINITION' => 'Lorem ipsum dolor sit amet',
                'EMPLOYEE_DEFINITION' => 'Lorem ipsum dolor sit amet',
                'STUDENT_DEFINITION' => 'Lorem ipsum dolor sit amet',
                'CACHE_CREATION_DATE' => '2022-07-15 14:44:24',
            ],
        ];
        parent::init();
    }
}
