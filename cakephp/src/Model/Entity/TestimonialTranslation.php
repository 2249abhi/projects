<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TestimonialTranslation Entity
 *
 * @property int $id
 * @property int $testimonial_id
 * @property int $language_id
 * @property string $culture
 * @property string|null $title
 * @property string|null $description
 * @property string|null $excerpt
 * @property string|null $client_designation
 * @property string|null $client_name
 * @property string|null $company_name
 *
 * @property \App\Model\Entity\Testimonial $testimonial
 * @property \App\Model\Entity\Language $language
 */
class TestimonialTranslation extends Entity
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
        'testimonial_id' => true,
        'language_id' => true,
        'culture' => true,
        'title' => true,
        'description' => true,
        'excerpt' => true,
        'client_designation' => true,
        'client_name' => true,
        'company_name' => true,
        'testimonial' => true,
        'language' => true
    ];
}
