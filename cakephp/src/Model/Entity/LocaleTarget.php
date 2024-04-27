<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LocaleTarget Entity
 *
 * @property int $id
 * @property int $locale_source_id
 * @property string $translation
 * @property string $language
 *
 * @property \App\Model\Entity\LocaleSource $locale_source
 */
class LocaleTarget extends Entity
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
        'locale_source_id' => true,
        'translation' => true,
        'language' => true,
        'locale_source' => true
    ];
}
