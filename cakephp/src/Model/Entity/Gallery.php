<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Gallery Entity
 *
 * @property int $id
 * @property int $gallery_category_id
 * @property string $filename
 * @property string $filemime
 * @property string $filesize
 * @property int $sort_order
 * @property string $description
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\GalleryCategory $gallery_category
 */
class Gallery extends Entity
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
        'press_category_id' => true,
        'filename' => true,
        'filemime' => true,
        'filesize' => true,
        'sort_order' => true,
        'description' => true,
        'cloud_tags'=>true,
        'status' => true,
        'created' => true,
        'press_category' => true,
        'is_home' => true
    ];
}
