<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Registration Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $designation_id
 * @property \Cake\I18n\FrozenDate $date_of_birth
 * @property string $organization
 * @property string $shortname
 * @property string $name
 * @property int $gender
 * @property string $email
 * @property string $mobile_number
 * @property string $phone
 * @property string $fax
 * @property int $state_id
 * @property int $district_id
 * @property int $city_id
 * @property string $address
 * @property int $pincode
 * @property int $star
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Designation $designation
 * @property \App\Model\Entity\State $state
 * @property \App\Model\Entity\District $district
 * @property \App\Model\Entity\City $city
 */
class Registration extends Entity
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
        '*' => true,
    ];
}
