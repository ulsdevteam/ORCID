<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrcidStatusesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrcidStatusesTable Test Case
 */
class OrcidStatusesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrcidStatusesTable
     */
    protected $OrcidStatuses;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.OrcidStatuses',
        'app.ORCIDUSERS',
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
        $config = $this->getTableLocator()->exists('OrcidStatuses') ? [] : ['className' => OrcidStatusesTable::class];
        $this->OrcidStatuses = $this->getTableLocator()->get('OrcidStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->OrcidStatuses);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\OrcidStatusesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\OrcidStatusesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
