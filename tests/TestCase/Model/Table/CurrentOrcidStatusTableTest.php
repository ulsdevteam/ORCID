<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CurrentOrcidStatusTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CurrentOrcidStatusTable Test Case
 */
class CurrentOrcidStatusTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CurrentOrcidStatusTable
     */
    protected $CurrentOrcidStatus;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.CurrentOrcidStatus',
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
        $config = $this->getTableLocator()->exists('CurrentOrcidStatus') ? [] : ['className' => CurrentOrcidStatusTable::class];
        $this->CurrentOrcidStatus = $this->getTableLocator()->get('CurrentOrcidStatus', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->CurrentOrcidStatus);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\CurrentOrcidStatusTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\CurrentOrcidStatusTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
