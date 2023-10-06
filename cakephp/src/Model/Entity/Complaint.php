<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Complaint Entity
 *
 * @property int $id
 * @property string $full_name
 * @property string $company_name
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $pincode
 * @property string $fixed_line_number
 * @property string $mobile_number
 * @property string $email_id
 * @property string $subject
 * @property string $comments
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\Email $email
 */
class Complaint extends Entity
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
        'full_name' => true,
        'company_name' => true,
        'address' => true,
        'city' => true,
        'state' => true,
        'country' => true,
        'pincode' => true,
        'fixed_line_number' => true,
        'mobile_number' => true,
        'email_id' => true,
        'subject' => true,
        'comments' => true,
        'created' => true,
        'updated' => true,
        'email' => true,
        'reference_number' => true
    ];
}
