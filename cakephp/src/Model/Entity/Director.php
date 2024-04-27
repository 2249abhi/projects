<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Director Entity
 *
 * @property int $id
 * @property string $title
 * @property string|null $excerpt
 * @property string|null $content
 * @property string|null $url
 * @property int $user_id
 * @property string|null $images
 * @property string|null $header_image
 * @property bool $is_main
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\DirectorTranslation[] $director_translations
 */
class Director extends Entity
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
        'excerpt' => true,
        'content' => true,
        'education' => true,
        'cloud_tags'=>true,
        'url' => true,
        'user_id' => true,
        'images' => true,
        'header_image' => true,
        'is_main' => true,
        'status' => true,
        'created' => true,
        'updated' => true,
        'user' => true,
		'sort_order' => true,
        'director_translations' => true
    ];
}
