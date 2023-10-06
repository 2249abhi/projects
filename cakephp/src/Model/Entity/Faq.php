<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Faq Entity
 *
 * @property int $id
 * @property string $title
 * @property string|null $subtitle
 * @property string $content
 * @property string $cloud_tags
 * @property string $faqs_image
 * @property int|null $faq_cat
 * @property int $user_id
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $updated
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\FaqTranslation $faq_translation
 * @property \App\Model\Entity\FaqTranslation[] $faq_translations
 */
class Faq extends Entity
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
        'subtitle' => true,
        'content' => true,
        'cloud_tags' => true,
        'faqs_image' => true,
        'faq_cat' => true,
        'user_id' => true,
        'status' => true,
        'updated' => true,
        'created' => true,
        'user' => true,
        'faq_translation' => true,
        'faq_translations' => true
    ];
}
