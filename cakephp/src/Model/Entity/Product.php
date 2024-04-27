<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $excerpt
 * @property string|null $content
 * @property string|null $url
 * @property string|null $icon
 * @property string|null $slug
 * @property string|null $header_image
 * @property int $sort_order
 * @property bool $status
 * @property \Cake\I18n\FrozenDate $display_date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ProductTranslation $product_translation
 * @property \App\Model\Entity\ProductTranslation[] $product_translations
 */
class Product extends Entity
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
        'category_id' => true,
        'title' => true,
        'excerpt' => true,
        'sub_title' => true,
        'excerpt' => true,
        'content' => true,
        'meta_title' => true,
        'meta_keywords' => true,
        'meta_description' => true,
        'cloud_tags' => true,
		'feature_title' => true,
        'features' => true,
        'url' => true,
        'icon' => true,
        'slug' => true,
        'header_image' => true,
        'sort_order' => true,
        'status' => true,
        'display_date' => true,
        'created' => true,
        'updated' => true,
        'user' => true,
        'product_translation' => true,
        'product_translations' => true
    ];
}
