<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrcidBatchGroupCachesFixture
 */
class OrcidBatchGroupCachesFixture extends TestFixture
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
                'orcid_batch_group_id' => 1,
                'orcid_user_id' => 1,
                'deprecated' => '2022-04-20',
            ],
        ];
        parent::init();
    }
}
