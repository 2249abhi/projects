<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RolesPermission Entity
 *
 * @property int $id
 * @property int $role_id
 * @property int $mid
 * @property int $navigationshow
 * @property string $module
 * @property string $moduletask
 * @property \Cake\I18n\FrozenTime $updated
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Child $child
 * @property \App\Model\Entity\Userrole[] $userroles
 */
class RolesPermission extends Entity
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
        'role_id' => true,
        'mid' => true,
        'navigationshow' => true,
        'module' => true,
        'moduletask' => true,
        'updated' => true,
        'created' => true,
        'child' => true,
        'userroles' => true
    ];
}
