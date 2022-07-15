<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrcidUsersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrcidUsersTable Test Case
 */
class OrcidUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrcidUsersTable
     */
    protected $OrcidUsers;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.OrcidUsers',
        'app.AllOrcidStatuses',
        'app.CurrentOrcidStatus',
        'app.OrcidBatchGroupCaches',
        'app.OrcidEmails',
        'app.OrcidStatuses',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('OrcidUsers') ? [] : ['className' => OrcidUsersTable::class];
        $this->OrcidUsers = $this->getTableLocator()->get('OrcidUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->OrcidUsers);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\OrcidUsersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\OrcidUsersTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test unknownFind method
     *
     * @return void
     * @uses \App\Model\Table\OrcidUsersTable::unknownFind()
     */
    public function testUnknownFind(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
