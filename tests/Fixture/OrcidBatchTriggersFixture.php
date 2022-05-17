<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrcidBatchTriggersFixture
 */
class OrcidBatchTriggersFixture extends TestFixture
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
                'orcid_status_type_id' => 1,
                'orcid_batch_id' => 1,
                'trigger_delay' => 1.5,
                'orcid_batch_group_id' => 1,
                'begin_date' => '2022-05-17',
                'repeat_value' => 1.5,
                'maximum_repeat' => 1.5,
            ],
        ];
        parent::init();
    }
}
