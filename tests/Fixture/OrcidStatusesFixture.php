<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrcidStatusesFixture
 */
class OrcidStatusesFixture extends TestFixture
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
                'ORCID_USER_ID' => 1,
                'ORCID_STATUS_TYPE_ID' => 1,
                'STATUS_TIMESTAMP' => '2022-07-15 19:15:26',
            ],
        ];
        parent::init();
    }
}
