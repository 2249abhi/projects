<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LogoSlider Entity
 *
 * @property int $id
 * @property string $title
 * @property string $logo_image
 * @property int $logo_cat_id
 * @property int $user_id
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 */
class LogoSlider extends Entity
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
        'logo_image' => true,
        'logo_cat_id' => true,
        'user_id' => true,
        'website' => true,
        'status' => true,
        'created' => true
    ];
}
