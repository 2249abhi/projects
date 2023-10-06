<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Industry Entity
 *
 * @property int $id
 * @property string $name
 * @property bool|null $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\Sector[] $sectors
 * @property \App\Model\Entity\StartupApplication[] $startup_applications
 */
class Industry extends Entity
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
        'status' => true,
        'created' => true,
        'updated' => true,
        'sectors' => true,
        'startup_applications' => true
    ];
}
