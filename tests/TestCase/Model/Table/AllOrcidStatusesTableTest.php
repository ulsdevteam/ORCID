<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AllOrcidStatusesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AllOrcidStatusesTable Test Case
 */
class AllOrcidStatusesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AllOrcidStatusesTable
     */
    protected $AllOrcidStatuses;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.AllOrcidStatuses',
        'app.OrcidUsers',
        'app.OrcidStatusTypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AllOrcidStatuses') ? [] : ['className' => AllOrcidStatusesTable::class];
        $this->AllOrcidStatuses = $this->getTableLocator()->get('AllOrcidStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AllOrcidStatuses);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AllOrcidStatusesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\AllOrcidStatusesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
