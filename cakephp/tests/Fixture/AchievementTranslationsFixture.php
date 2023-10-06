<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AchievementTranslationsFixture
 *
 */
class AchievementTranslationsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'achievement_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'language_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'culture' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => null, 'collate' => 'utf8_general_mysql500_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'title' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'utf8_general_mysql500_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'subtitle' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'utf8_general_mysql500_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'excerpt' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_mysql500_ci', 'comment' => '', 'precision' => null],
        'content' => ['type' => 'text', 'length' => 4294967295, 'null' => true, 'default' => null, 'collate' => 'utf8_general_mysql500_ci', 'comment' => '', 'precision' => null],
        'url' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_mysql500_ci', 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'achievement_id' => 1,
                'language_id' => 1,
                'culture' => 'Lorem ip',
                'title' => 'Lorem ipsum dolor sit amet',
                'subtitle' => 'Lorem ipsum dolor sit amet',
                'excerpt' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'url' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
            ],
        ];
        parent::init();
    }
}
