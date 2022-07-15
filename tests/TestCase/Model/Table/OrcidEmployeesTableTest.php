<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrcidEmployeesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrcidEmployeesTable Test Case
 */
class OrcidEmployeesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrcidEmployeesTable
     */
    protected $OrcidEmployees;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.OrcidEmployees',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('OrcidEmployees') ? [] : ['className' => OrcidEmployeesTable::class];
        $this->OrcidEmployees = $this->getTableLocator()->get('OrcidEmployees', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->OrcidEmployees);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\OrcidEmployeesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test defaultConnectionName method
     *
     * @return void
     * @uses \App\Model\Table\OrcidEmployeesTable::defaultConnectionName()
     */
    public function testDefaultConnectionName(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
