<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CurrentOrcidStatusFixture
 */
class CurrentOrcidStatusFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'current_orcid_status';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'orcid_user_id' => 1,
                'orcid_status_type_id' => 1,
                'status_timestamp' => '2022-05-17',
            ],
        ];
        parent::init();
    }
}
