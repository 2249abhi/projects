<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Tender Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $slug
 * @property string $content
 * @property string|null $url
 * @property \Cake\I18n\FrozenTime $tender_date
 * @property \Cake\I18n\FrozenTime $last_date
 * @property string $tender_document
 * @property string $corrigendum
 * @property string|null $remarks
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\TenderTranslation[] $tender_translations
 * @property \App\Model\Entity\TenderTranslation $tender_translation
 */
class Tender extends Entity
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
        'meta_title' => true,
        'meta_keywords' => true,
        'meta_description' => true,
        'cloud_tags' => true,
        'url' => true,
        'tender_date' => true,
        'last_date' => true,
        'tender_document' => true,
        'corrigendum' => true,
        'remarks' => true,
        'status' => true,
        'created' => true,
        'updated' => true,
        'user' => true,
        'is_archive' => true,
        'tender_translations' => true,
        'tender_translation' => true
    ];
}
