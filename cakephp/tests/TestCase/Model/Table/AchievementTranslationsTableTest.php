<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AchievementTranslationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AchievementTranslationsTable Test Case
 */
class AchievementTranslationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AchievementTranslationsTable
     */
    public $AchievementTranslations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.achievement_translations',
        'app.achievements',
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
        $config = TableRegistry::getTableLocator()->exists('AchievementTranslations') ? [] : ['className' => AchievementTranslationsTable::class];
        $this->AchievementTranslations = TableRegistry::getTableLocator()->get('AchievementTranslations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AchievementTranslations);

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
