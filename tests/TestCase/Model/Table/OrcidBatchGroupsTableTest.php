<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrcidBatchGroupsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrcidBatchGroupsTable Test Case
 */
class OrcidBatchGroupsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrcidBatchGroupsTable
     */
    protected $OrcidBatchGroups;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.OrcidBatchGroups',
        'app.OrcidBatchGroupCaches',
        'app.OrcidBatchTriggers',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('OrcidBatchGroups') ? [] : ['className' => OrcidBatchGroupsTable::class];
        $this->OrcidBatchGroups = $this->getTableLocator()->get('OrcidBatchGroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->OrcidBatchGroups);

        parent::tearDown();
    }

    /**
     * Test getAssociatedUsers method
     *
     * @return void
     * @uses \App\Model\Table\OrcidBatchGroupsTable::getAssociatedUsers()
     */
    public function testGetAssociatedUsers(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test updateCache method
     *
     * @return void
     * @uses \App\Model\Table\OrcidBatchGroupsTable::updateCache()
     */
    public function testUpdateCache(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\OrcidBatchGroupsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
