<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrcidBatchesFixture
 */
class OrcidBatchesFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ORCID_BATCHES';
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
                'SUBJECT' => 'Lorem ipsum dolor sit amet',
                'BODY' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'FROM_NAME' => 'Lorem ipsum dolor sit amet',
                'FROM_ADDR' => 'Lorem ipsum dolor sit amet',
                'REPLY_TO' => 'Lorem ipsum dolor sit amet',
                'ORCID_BATCH_CREATOR_ID' => 1,
            ],
        ];
        parent::init();
    }
}
