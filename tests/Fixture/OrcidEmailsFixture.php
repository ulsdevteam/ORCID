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
                'id' => 1,
                'orcid_user_id' => 1,
                'orcid_batch_id' => 1,
                'queued' => '2022-04-20',
                'sent' => '2022-04-20',
                'cancelled' => '2022-04-20',
            ],
        ];
        parent::init();
    }
}
