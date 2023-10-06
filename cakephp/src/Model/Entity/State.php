<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * State Entity
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property \Cake\I18n\FrozenTime $created
 * @property int $flag
 *
 * @property \App\Model\Entity\District[] $districts
 * @property \App\Model\Entity\Register[] $registers
 */
class State extends Entity
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
        'code' => true,
        'name' => true,
        'created' => true,
        'flag' => true,
        'districts' => true,
        'registers' => true
    ];
}
