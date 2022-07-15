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
                'ID' => 1,
                'NAME' => 'Lorem ipsum dolor sit amet',
                'ORCID_STATUS_TYPE_ID' => 1,
                'ORCID_BATCH_ID' => 1,
                'TRIGGER_DELAY' => 1,
                'ORCID_BATCH_GROUP_ID' => 1,
                'BEGIN_DATE' => '2022-07-15 14:51:28',
                'REQUIRE_BATCH_ID' => 1,
                'REPEAT' => 1,
                'MAXIMUM_REPEAT' => 1,
            ],
        ];
        parent::init();
    }
}
