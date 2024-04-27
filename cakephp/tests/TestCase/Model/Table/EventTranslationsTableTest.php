<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EventTranslationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EventTranslationsTable Test Case
 */
class EventTranslationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EventTranslationsTable
     */
    public $EventTranslations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.event_translations',
        'app.events',
        'app.languages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EventTranslations') ? [] : ['className' => EventTranslationsTable::class];
        $this->EventTranslations = TableRegistry::getTableLocator()->get('EventTranslations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EventTranslations);

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
