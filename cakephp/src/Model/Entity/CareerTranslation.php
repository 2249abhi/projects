<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CareerTranslation Entity
 *
 * @property int $id
 * @property int $career_id
 * @property int $language_id
 * @property string $culture
 * @property string $title
 * @property string|null $excerpt
 * @property string|null $content
 * @property string $url
 *
 * @property \App\Model\Entity\Career $career
 * @property \App\Model\Entity\Language $language
 */
class CareerTranslation extends Entity
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
        'career_id' => true,
        'language_id' => true,
        'culture' => true,
        'title' => true,
        'excerpt' => true,
        'content' => true,
        'url' => true,
        'career' => true,
        'language' => true
    ];
}
