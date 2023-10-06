<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Testimonial Entity
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $testimonial_image
 * @property string|null $client_name
 * @property string|null $client_designation
 * @property string|null $company_name
 * @property bool $status
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime|null $updated
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\User $user
 */
class Testimonial extends Entity
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
        'title' => true,
        'description' => true,
        'excerpt' => true,
        'testimonial_image' => true,
        'client_name' => true,
        'client_designation' => true,
        'company_name' => true,
        'cloud_tags'=>true,
        'status' => true,
        'user_id' => true,
        'updated' => true,
        'created' => true,
        'user' => true
    ];
}
