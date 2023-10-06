<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Scheme Entity
 *
 * @property int $id
 * @property string $name
 * @property string $short_name
 * @property string $short_code
 * @property string $agreement_with
 * @property int $type
 * @property bool $flag
 * @property \Cake\I18n\FrozenTime $updated
 * @property \Cake\I18n\FrozenTime $created
 */
class Scheme extends Entity
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
        'short_name' => true,
        'short_code' => true,
        'agreement_with' => true,
        'type' => true,
        'flag' => true,
        'updated' => true,
        'created' => true
    ];
}
