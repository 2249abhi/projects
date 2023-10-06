<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Partnership Entity
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
 * @property string|null $url
 * @property string|null $slug
 * @property string $logo_image
 * @property int|null $sort_order
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\PartnershipTranslation[] $partnership_translations
 */
class Partnership extends Entity
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
        'url' => true,
        'slug' => true,
        'logo_image' => true,
        'sort_order' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'website_link' => true,
        'partnership_translations' => true
    ];
}
