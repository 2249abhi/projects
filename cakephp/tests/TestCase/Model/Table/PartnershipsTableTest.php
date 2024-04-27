<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PartnershipsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PartnershipsTable Test Case
 */
class PartnershipsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PartnershipsTable
     */
    public $Partnerships;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.partnerships',
        'app.users',
        'app.partnership_translations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Partnerships') ? [] : ['className' => PartnershipsTable::class];
        $this->Partnerships = TableRegistry::getTableLocator()->get('Partnerships', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Partnerships);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
