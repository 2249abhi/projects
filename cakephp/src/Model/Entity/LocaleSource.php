<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LocaleSource Entity
 *
 * @property int $id
 * @property string $source
 *
 * @property \App\Model\Entity\LocaleTarget[] $locale_targets
 */
class LocaleSource extends Entity
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
        'source' => true,
        'locale_targets' => true
    ];
}
