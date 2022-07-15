<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrcidStatusTypesFixture
 */
class OrcidStatusTypesFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ULS.ORCID_STATUS_TYPES';
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
                'SEQ' => 1,
            ],
        ];
        parent::init();
    }
}
