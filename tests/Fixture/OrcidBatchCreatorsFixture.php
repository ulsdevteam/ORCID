<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrcidBatchCreatorsFixture
 */
class OrcidBatchCreatorsFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ORCID_BATCH_CREATORS';
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
                'NAME' => 'Lorem ',
                'FLAGS' => 1,
            ],
        ];
        parent::init();
    }
}
