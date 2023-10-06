<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * News Entity
 *
 * @property int $id
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $content
 * @property string|null $news_url
 * @property \Cake\I18n\FrozenTime $display_date
 * @property int $user_id
 * @property int|null $sort_order
 * @property bool $status
 * @property string|null $custom_link
 * @property string|null $upload_document_1
 * @property string|null $upload_document_2
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\NewsTranslation $news_translation
 * @property \App\Model\Entity\NewsTranslation[] $news_translations
 */
class News extends Entity
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
        'title' => true,
        'excerpt' => true,
        'content' => true,
        'cloud_tags' => true,
        'meta_title' => true,
        'meta_keywords' => true,
        'meta_description' => true,
        'news_url' => true,
        'display_date' => true,
        'user_id' => true,
        'sort_order' => true,
        'status' => true,
        'custom_link' => true,
        'upload_document_1' => true,
        'upload_document_2' => true,
        'created' => true,
        'updated' => true,
        'user' => true,
        'header_image' => true,
        'news_translation' => true,
        'news_translations' => true
    ];
}
