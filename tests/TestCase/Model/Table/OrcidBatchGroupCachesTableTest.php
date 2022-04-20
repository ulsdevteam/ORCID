<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrcidBatchGroupCachesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrcidBatchGroupCachesTable Test Case
 */
class OrcidBatchGroupCachesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrcidBatchGroupCachesTable
     */
    protected $OrcidBatchGroupCaches;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.OrcidBatchGroupCaches',
        'app.OrcidBatchGroups',
        'app.OrcidUsers',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('OrcidBatchGroupCaches') ? [] : ['className' => OrcidBatchGroupCachesTable::class];
        $this->OrcidBatchGroupCaches = $this->getTableLocator()->get('OrcidBatchGroupCaches', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->OrcidBatchGroupCaches);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\OrcidBatchGroupCachesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\OrcidBatchGroupCachesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
