<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrcidBatchCreatorsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrcidBatchCreatorsTable Test Case
 */
class OrcidBatchCreatorsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrcidBatchCreatorsTable
     */
    protected $OrcidBatchCreators;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.OrcidBatchCreators',
        'app.ORCIDBATCHES',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('OrcidBatchCreators') ? [] : ['className' => OrcidBatchCreatorsTable::class];
        $this->OrcidBatchCreators = $this->getTableLocator()->get('OrcidBatchCreators', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->OrcidBatchCreators);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\OrcidBatchCreatorsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\OrcidBatchCreatorsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
