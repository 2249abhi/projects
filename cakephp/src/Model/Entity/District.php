<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * District Entity
 *
 * @property int $id
 * @property int $state_id
 * @property string $name
 * @property bool $flag
 *
 * @property \App\Model\Entity\State $state
 * @property \App\Model\Entity\Register[] $registers
 */
class District extends Entity
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
        'state_id' => true,
        'name' => true,
        'flag' => true,
        'state' => true,
        'registers' => true
    ];
}
