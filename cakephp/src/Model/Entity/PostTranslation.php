<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PostTranslation Entity
 *
 * @property int $id
 * @property int $post_id
 * @property int $language_id
 * @property string $culture
 * @property string|null $title
 * @property string|null $subtitle
 * @property string|null $content
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Post $post
 * @property \App\Model\Entity\Language $language
 */
class PostTranslation extends Entity
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
        'id' => true,
        'post_id' => true,
        'language_id' => true,
        'culture' => true,
        'title' => true,
        'subtitle' => true,
        'content' => true,
        'created' => true,
        'post' => true,
        'language' => true
    ];
}
