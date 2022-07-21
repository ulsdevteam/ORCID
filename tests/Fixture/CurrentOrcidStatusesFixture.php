<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CurrentOrcidStatusesFixture
 */
class CurrentOrcidStatusesFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'CURRENT_ORCID_STATUSES';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'ORCID_USER_ID' => 1,
                'ORCID_STATUS_TYPE_ID' => 1,
                'STATUS_TIMESTAMP' => '2022-07-18 20:47:51',
            ],
        ];
        parent::init();
    }
}
