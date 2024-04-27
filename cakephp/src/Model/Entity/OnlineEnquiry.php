<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OnlineEnquiry Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $emailid
 * @property string|null $location
 * @property string|null $qoas
 * @property string|null $mobile_number
 * @property string|null $need
 * @property string|null $other_need
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime $created
 */
class OnlineEnquiry extends Entity
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
        'emailid' => true,
        'location' => true,
        'qoas' => true,
        'mobile_number' => true,
        'need' => true,
        'other_need' => true,
        'description' => true,
        'created_at' => true,
        'reference_number' => true
    ];
}
