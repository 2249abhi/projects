<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Circular Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string|null $url
 * @property \Cake\I18n\FrozenTime $circular_date
 * @property \Cake\I18n\FrozenTime $last_date
 * @property string|null $remarks
 * @property bool $status
 * @property bool $is_archive
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\CircularDocument[] $circular_documents
 * @property \App\Model\Entity\CircularTranslation[] $circular_translations
 */
class Circular extends Entity
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
        'user_id' => true,
        'title' => true,
        'slug' => true,
        'content' => true,
        'cloud_tags'=>true,
        'url' => true,
        'circular_date' => true,
        'last_date' => true,
        'remarks' => true,
        'status' => true,
        'is_archive' => true,
        'created' => true,
        'updated' => true,
        'user' => true,
        'circular_documents' => true,
        'circular_translations' => true
    ];
}
