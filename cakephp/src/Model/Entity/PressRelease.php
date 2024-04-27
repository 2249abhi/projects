<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PressRelease Entity
 *
 * @property int $id
 * @property string $title
 * @property string|null $excerpt
 * @property string $content
 * @property string|null $url
 * @property int $user_id
 * @property int $sort_order
 * @property string|null $custom_link
 * @property string|null $upload_document_1
 * @property string|null $upload_document_2
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\PressReleaseTranslation[] $press_release_translations
 */
class PressRelease extends Entity
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
        'meta_title' => true,
        'meta_keywords' => true,
        'meta_description' => true,
        'cloud_tags' => true,
        'url' => true,
        'user_id' => true,
        'sort_order' => true,
        'custom_link' => true,
        'is_archive' => true,
        'upload_document_1' => true,
        'upload_document_2' => true,
        'status' => true,
        'release_date' => true,
        'created' => true,
        'updated' => true,
        'user' => true,
        'press_release_translations' => true
    ];
}
