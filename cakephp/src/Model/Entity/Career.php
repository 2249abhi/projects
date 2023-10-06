<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Career Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $slug
 * @property string|null $content
 * @property int|null $excerpt
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $expiry_date
 * @property string|null $url
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\CareerDocument[] $career_documents
 * @property \App\Model\Entity\CareerTranslation[] $career_translations
 */
class Career extends Entity
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
        'excerpt' => true,
        'cloud_tags'=>true,
        'meta_title'=>true,
        'meta_keywords'=>true,
        'meta_description'=>true,
        'start_date' => true,
        'expiry_date' => true,
        'url' => true,
        'status' => true,
        'is_archive' => true,
        'created' => true,
        'updated' => true,
        'user' => true,
        'career_documents' => true,
        'career_translations' => true
    ];
}
