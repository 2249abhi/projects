<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NatureOfStartup Entity
 *
 * @property int $id
 * @property string $nature
 * @property bool $status
 *
 * @property \App\Model\Entity\StartupApplication[] $startup_applications
 */
class NatureOfStartup extends Entity
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
        'nature' => true,
        'status' => true,
        'startup_applications' => true
    ];
}
