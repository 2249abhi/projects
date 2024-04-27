<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductTranslation Entity
 *
 * @property int $id
 * @property int $product_id
 * @property int $language_id
 * @property string|null $culture
 * @property string|null $title
 * @property string|null $excerpt
 * @property string|null $content
 * @property string|null $url
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Language $language
 */
class ProductTranslation extends Entity
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
        'product_id' => true,
        'language_id' => true,
        'culture' => true,
        'title' => true,
		'sub_title' => true, 
        'excerpt' => true,
        'content' => true,
		'feature_title' => true,
        'features' => true,
        'url' => true,
        'created' => true,
        'product' => true,
        'language' => true
    ];
}
