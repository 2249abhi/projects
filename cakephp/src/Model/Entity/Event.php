<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;

/**
 * Event Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $excerpt
 * @property string|null $content
 * @property string|null $meta_title
 * @property string|null $meta_keywords
 * @property string|null $meta_description
 * @property string|null $venue
 * @property \Cake\I18n\FrozenDate $date
 * @property string|null $url
 * @property string $header_image
 * @property int|null $sort_order
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\EventTranslation[] $event_translations
 */
class Event extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'title' => true,
        'subtitle' => true,
        'excerpt' => true,
        'content' => true,
        'meta_title' => true,
        'meta_keywords' => true,
        'meta_description' => true,
        'venue' => true,
        'date' => true,
        'url' => true,
        'slug' => true,
        'header_image' => true,
        'sort_order' => true,
        'status' => true,
        'created' => true,
        'updated' => true,
        'user' => true,
        'event_translations' => true
    ];

    protected function _getUrl($url)
    {
        $url = trim($url);
        if (!empty($url)) {
            return strtolower(Text::slug($url));
        } else {
            return null;
        }
    }
    protected function _setUrl($url)
    {
        $url = trim($url);
        if (!empty($url)) {
            return strtolower(Text::slug($url));
        } else {
            return null;
        }
    }
}
