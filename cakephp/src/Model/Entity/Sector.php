<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Sector Entity
 *
 * @property int $id
 * @property string $name
 * @property int $industry_id
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\Industry $industry
 * @property \App\Model\Entity\StartupApplication[] $startup_applications
 */
class Sector extends Entity
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
        'industry_id' => true,
        'status' => true,
        'created' => true,
        'updated' => true,
        'industry' => true,
        'startup_applications' => true
    ];
}
