<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrcidEmailsFixture
 */
class OrcidEmailsFixture extends TestFixture
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
                'ORCID_BATCH_ID' => 1,
                'QUEUED' => '2022-07-15 14:56:02',
                'SENT' => '2022-07-15 14:56:02',
                'CANCELLED' => '2022-07-15 14:56:02',
            ],
        ];
        parent::init();
    }
}
