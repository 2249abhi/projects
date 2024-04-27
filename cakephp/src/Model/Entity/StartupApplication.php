<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StartupApplication Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $nature_of_startup_id
 * @property string $reference_no
 * @property \Cake\I18n\FrozenDate $registration_date
 * @property int $industry_id
 * @property int $sector_id
 * @property string $category_id
 * @property string $name_of_startup
 * @property string $pan_no
 * @property string $address_line_1
 * @property string $address_line_2
 * @property string|null $address_line_3
 * @property string $city
 * @property int $state_id
 * @property int $district_id
 * @property string $pincode
 * @property string $authorized_representative
 * @property string $representative_designation
 * @property string $representative_email
 * @property string $representative_mobile
 * @property int $no_of_directors
 * @property int $number_of_employees
 * @property int $startup_stage
 * @property string $applied_for_IPR
 * @property string $is_innovative_improving
 * @property string $is_scalable_business_model
 * @property string $awards_recognition
 * @property string $problem_startup_solving
 * @property string $how_problem_solving
 * @property string $uniqueness_of_solution
 * @property string $how_generate_revenue
 * @property string $registration_certificate
 * @property string $additional_documents
 * @property int $is_Declaration_checked
 * @property string $screening_committee_reject_reason
 * @property string $steering_committee_reject_reason
 * @property string $recognition_certificate_generated
 * @property int $application_status_id
 * @property string $recognition_certificate_no
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\NatureOfStartup $nature_of_startup
 * @property \App\Model\Entity\Industry $industry
 * @property \App\Model\Entity\Sector $sector
 * @property \App\Model\Entity\Category $category
 * @property \App\Model\Entity\State $state
 * @property \App\Model\Entity\District $district
 * @property \App\Model\Entity\ApplicationStatus $application_status
 */
class StartupApplication extends Entity
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
