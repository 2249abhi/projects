<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NewsTranslation Entity
 *
 * @property int $id
 * @property int $news_id
 * @property int $language_id
 * @property string $culture
 * @property string|null $title
 * @property string|null $excerpt
 * @property string $content
 * @property string $news_url
 *
 * @property \App\Model\Entity\News $news
 * @property \App\Model\Entity\Language $language
 */
class NewsTranslation extends Entity
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
        'news_id' => true,
        'language_id' => true,
        'culture' => true,
        'title' => true,
        'excerpt' => true,
        'content' => true,
        'news_url' => true,
        'news' => true,
        'language' => true
    ];
}
