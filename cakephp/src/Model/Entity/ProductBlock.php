<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductBlock Entity
 *
 * @property int $id
 * @property int $product_id
 * @property string $block_label
 * @property string $block_icon
 * @property string $block_content
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\ProductBlockTranslation[] $product_block_translations
 */
class ProductBlock extends Entity
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
        'block_label' => true,
        'block_icon' => true,
        'block_content' => true,
        'product' => true,
        'product_block_translations' => true
    ];
}
