<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CircularTranslation Entity
 *
 * @property int $id
 * @property int $circular_id
 * @property int $language_id
 * @property string $culture
 * @property string $title
 * @property string $remarks
 * @property string $content
 * @property string $slug
 * @property string $url
 *
 * @property \App\Model\Entity\Circular $circular
 * @property \App\Model\Entity\Language $language
 */
class CircularTranslation extends Entity
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
        'circular_id' => true,
        'language_id' => true,
        'culture' => true,
        'title' => true,
        'remarks' => true,
        'content' => true,
        'slug' => true,
        'url' => true,
        'circular' => true,
        'language' => true
    ];
}
