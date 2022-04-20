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
                'id' => 1,
                'orcid_user_id' => 1,
                'orcid_status_type_id' => 1,
                'status_timestamp' => '2022-04-20',
            ],
        ];
        parent::init();
    }
}
