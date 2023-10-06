<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Language Entity
 *
 * @property int $id
 * @property string $name
 * @property string $culture
 * @property string $direction
 * @property bool $is_default
 * @property bool $is_system
 * @property bool $status
 * @property \Cake\I18n\FrozenTime|null $created_at
 * @property \Cake\I18n\FrozenTime|null $modified_at
 *
 * @property \App\Model\Entity\TenderTranslation[] $tender_translations
 */
class Language extends Entity
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
        'name' => true,
        'culture' => true,
        'direction' => true,
        'is_default' => true,
        'is_system' => true,
        'status' => true,
        'created_at' => true,
        'modified_at' => true,
        'tender_translations' => true
    ];
}
