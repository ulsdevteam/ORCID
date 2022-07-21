<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CurrentOrcidStatusesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CurrentOrcidStatusesTable Test Case
 */
class CurrentOrcidStatusesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CurrentOrcidStatusesTable
     */
    protected $CurrentOrcidStatuses;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.CurrentOrcidStatuses',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('CurrentOrcidStatuses') ? [] : ['className' => CurrentOrcidStatusesTable::class];
        $this->CurrentOrcidStatuses = $this->getTableLocator()->get('CurrentOrcidStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->CurrentOrcidStatuses);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\CurrentOrcidStatusesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
