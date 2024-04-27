<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EventTranslation Entity
 *
 * @property int $id
 * @property int $event_id
 * @property int $language_id
 * @property string $culture
 * @property string|null $title
 * @property string|null $subtitle
 * @property string|null $excerpt
 * @property string|null $content
 * @property string|null $url
 * @property string|null $venue
 *
 * @property \App\Model\Entity\Event $event
 * @property \App\Model\Entity\Language $language
 */
class EventTranslation extends Entity
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
        'event_id' => true,
        'language_id' => true,
        'culture' => true,
        'title' => true,
        'subtitle' => true,
        'excerpt' => true,
        'content' => true,
        'url' => true,
        'venue' => true,
        'event' => true,
        'language' => true
    ];
}
