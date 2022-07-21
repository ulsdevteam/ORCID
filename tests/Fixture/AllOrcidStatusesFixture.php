<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AllOrcidStatusesFixture
 */
class AllOrcidStatusesFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ULS.all_orcid_statuses';
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
                'STATUS_TIMESTAMP' => '2022-07-18 14:51:17',
            ],
        ];
        parent::init();
    }
}
