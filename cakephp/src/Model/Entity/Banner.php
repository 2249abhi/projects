<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Banner Entity
 *
 * @property int $id
 * @property string $title
 * @property int $banner_category_id
 * @property string $excerpt
 * @property string $banner_image
 * @property bool $status
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\BannerCategory $banner_category
 * @property \App\Model\Entity\User $user
 */
class Banner extends Entity
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
        'banner_category_id' => true,
        'excerpt' => true,
        'description' => true,
        'banner_image' => true,
        'banner_type' => true,
        'banner_video' => true,
        'status' => true,
        'user_id' => true,
        'created' => true,
        'updated' => true,
        'banner_category' => true,
        'user' => true
    ];
}
