<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrcidUsersFixture
 */
class OrcidUsersFixture extends TestFixture
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
                'username' => 'Lorem ',
                'orcid' => 'Lorem ipsum dolor',
                'token' => 'Lorem ipsum dolor sit amet',
                'created' => '2022-05-17',
                'modified' => '2022-05-17',
            ],
        ];
        parent::init();
    }
}
