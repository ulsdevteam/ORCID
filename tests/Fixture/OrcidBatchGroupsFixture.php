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
                'id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'group_definition' => 'Lorem ipsum dolor sit amet',
                'employee_definition' => 'Lorem ipsum dolor sit amet',
                'student_definition' => 'Lorem ipsum dolor sit amet',
                'cache_creation_date' => '2022-05-17',
            ],
        ];
        parent::init();
    }
}
