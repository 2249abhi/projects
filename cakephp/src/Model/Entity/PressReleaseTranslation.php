<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PressReleaseTranslation Entity
 *
 * @property int $id
 * @property int $press_release_id
 * @property int $language_id
 * @property string $culture
 * @property string|null $title
 * @property string|null $excerpt
 * @property string|null $content
 * @property string|null $url
 *
 * @property \App\Model\Entity\PressRelease $press_release
 * @property \App\Model\Entity\Language $language
 */
class PressReleaseTranslation extends Entity
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
        'press_release_id' => true,
        'language_id' => true,
        'culture' => true,
        'title' => true,
        'excerpt' => true,
        'content' => true,
        'url' => true,
        'press_release' => true,
        'language' => true
    ];
}
