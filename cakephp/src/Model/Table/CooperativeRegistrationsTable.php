<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * States Model
 *
 * @property \App\Model\Table\DistrictsTable|\Cake\ORM\Association\HasMany $Districts
 * @property \App\Model\Table\RegistersTable|\Cake\ORM\Association\HasMany $Registers
 *
 * @method \App\Model\Entity\State get($primaryKey, $options = [])
 * @method \App\Model\Entity\State newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\State[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\State|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\State|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\State patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\State[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\State findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CooperativeRegistrationsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('cooperative_registrations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('CooperativeSocietyTypes', [
            'foreignKey' => 'cooperative_society_type_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('AreaOfOperations', [
            'foreignKey' => 'area_of_operation_id',
            'joinType' => 'LEFT'
        ]);

        $this->hasMany('AreaOfOperationLevelUrban', [
            'foreignKey' => 'cooperative_registrations_id',
            'className'=>'AreaOfOperationLevelUrban'
        ]);

        $this->hasOne('CooperativeRegistrationsLands', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsLands'
        ]);

        $this->hasMany('SocietyImplementingSchemes', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'SocietyImplementingSchemes',
            // 'dependent' => true
        ]);

        $this->hasMany('CooperativeRegistrationFishery', [
            'foreignKey' => 'cooperative_registrations_id',
            'className'=>'CooperativeRegistrationFishery'
        ]);

        $this->hasMany('CRMS', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsMultiStates'
        ]);
        
        $this->hasMany('CooperativeRegistrationsContactNumbers', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsContactNumbers'
        ]);

        $this->hasMany('CooperativeRegistrationsEmails', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsEmails'
        ]);

        $this->hasMany('AreaOfOperationLevel', [
            'foreignKey' => 'cooperative_registrations_id',
            'className'=>'AreaOfOperationLevel'
        ]);


         $this->hasMany('CooperativeRegistrationsContactNumbers', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsContactNumbers'
        ]);

        $this->hasMany('CooperativeRegistrationsEmails', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsEmails'
        ]);

         $this->hasMany('CooperativeRegistrationPacs', [
            'foreignKey' => 'cooperative_registrations_id',
            'className'=>'CooperativeRegistrationPacs'
        ]);

        $this->hasMany('CooperativeRegistrationDairy', [
            'foreignKey' => 'cooperative_registrations_id',
            'className'=>'CooperativeRegistrationDairy'
        ]);

         $this->hasMany('CooperativeRegistrationFishery', [
            'foreignKey' => 'cooperative_registrations_id',
            'className'=>'CooperativeRegistrationFishery'
        ]);
		
        $this->hasOne('CooperativeRegistrationsHousing', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsHousing',
        ]);

        $this->hasOne('CooperativeRegistrationsTransport', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsTransport',        
        ]);

        $this->hasOne('CooperativeRegistrationsHandloom', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsHandloom',
        ]);

        $this->hasOne('CooperativeRegistrationsAgriculture', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsAgriculture',
        ]);

        $this->hasOne('CooperativeRegistrationsTribal', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsTribal',
        ]);  

        $this->hasOne('CooperativeRegistrationsTourism', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsTourism',
        ]);

        $this->hasOne('CooperativeRegistrationsJute', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsJute',
        ]);

        $this->hasOne('CooperativeRegistrationsLivestock', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsLivestock',
        ]);

        $this->hasOne('CooperativeRegistrationsSericulture', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsSericulture',
        ]);

        $this->hasOne('CooperativeRegistrationsWocoop', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsWocoop',
        ]);

        $this->hasOne('CooperativeRegistrationsMulti', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsMulti',
        ]);

        $this->hasOne('CooperativeRegistrationsBee', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsBee',
        ]);

        $this->hasOne('CooperativeRegistrationsHandicraft', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsHandicraft',
        ]);

        
        $this->hasOne('CooperativeRegistrationsEducation', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsEducation',
        ]);

        $this->hasOne('CooperativeRegistrationsUcb', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsUcb',
        ]);


        $this->hasOne('CooperativeRegistrationsLabour', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsLabour',
        ]);

        $this->hasOne('CooperativeRegistrationsMarketing', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsMarketing',
        ]);

        $this->hasOne('CooperativeRegistrationsCreditThrift', [       
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsCreditThrift',
        ]);

        $this->hasOne('CooperativeRegistrationsConsumer', [       
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsConsumer',
        ]);

        $this->hasOne('MultiStateCooperatives', [
            'foreignKey' => 'cooperative_id',
            'className'=>'MultiStateCooperatives'
        ]);

        $this->hasMany('MultiStateCooperativeMemberDetails', [
            'foreignKey' => 'multi_state_cooperative_id',
            'className'=>'MultiStateCooperativeMemberDetails',
            'dependent' => true
        ]);  

        $this->hasMany('CooperativeReceivedBenefits', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeReceivedBenefits'
        ]);
        

        $this->hasOne('CooperativeRegistrationsSugar', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsSugar',
        ]);
		
		$this->belongsTo('States', [
            'foreignKey' => 'state_code',
            'joinType' => 'INNER'
        ]); 
        
        $this->belongsTo('Districts', [
            'foreignKey' => 'district_code',
            'joinType' => 'INNER'
        ]);  

        $this->belongsTo('Blocks', [
            'foreignKey' => 'block_code',
            'joinType' => 'INNER'
        ]);  

        $this->belongsTo('Panchayat', [
            'foreignKey' => 'gram_panchayat_code',
            'joinType' => 'INNER'
        ]);

		 $this->hasOne('CooperativeRegistrationsConsumer', [       
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsConsumer',
        ]);

		$this->hasOne('CooperativeRegistrationsLabour', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsLabour',
        ]);

        $this->hasOne('CooperativeRegistrationsProcessing', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsProcessing',
        ]);

        $this->belongsTo('PrimaryActivities', [
            'foreignKey' => 'sector_of_operation',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('CooperativeRegistrationsServices', [
            'foreignKey' => 'cooperative_registration_id',
            'className'=>'CooperativeRegistrationsServices'
        ]);
		
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {

        $dataPost = @$_REQUEST;
        $validator
            ->nonNegativeInteger('id')
            ->allowEmpty('id', 'create');

        if(isset($dataPost['is_draft']) && $dataPost['is_draft']==0){

            $validator
                ->scalar('cooperative_society_name')
                ->maxLength('cooperative_society_name', 255)
                ->requirePresence('cooperative_society_name', 'create')
                ->notEmpty('cooperative_society_name');

            $validator
                ->scalar('date_registration')
                ->maxLength('date_registration', 100)
                ->requirePresence('date_registration', 'create')
                ->notEmpty('date_registration');

            $validator
                ->scalar('registration_number')
                ->maxLength('registration_number', 255)
                ->requirePresence('registration_number', 'create')
                ->notEmpty('registration_number');    

            $validator
                ->scalar('cooperative_society_type_id')
                ->maxLength('cooperative_society_type_id', 11)
                ->requirePresence('cooperative_society_type_id', 'create')
                ->notEmpty('cooperative_society_type_id');  

            // $validator
            //     ->scalar('area_of_operation_id')
            //     ->maxLength('area_of_operation_id', 11)
            //     ->requirePresence('area_of_operation_id', 'create')
            //     ->notEmpty('area_of_operation_id');        

            
            $validator
                ->scalar('reference_year')
                ->maxLength('reference_year', 4)
                ->requirePresence('reference_year', 'create')
                ->notEmpty('reference_year');            
            
            $validator
                ->scalar('location_of_head_quarter')
                ->maxLength('location_of_head_quarter', 4)
                ->requirePresence('location_of_head_quarter', 'create')
                ->notEmpty('location_of_head_quarter');  

            $validator
                ->scalar('sector_of_operation_type')
                ->maxLength('sector_of_operation_type', 3)
                ->requirePresence('sector_of_operation_type', 'create')
                ->notEmpty('sector_of_operation_type');  
        
                /*
            $validator
                ->scalar('sector_of_operation')
                ->maxLength('sector_of_operation', 11)
                ->requirePresence('sector_of_operation', 'create')
                ->notEmpty('sector_of_operation');  
                

            if($dataPost['sector_of_operation_credit']==8 && $dataPost['sector_of_operation_type']==1){
                $validator
                ->scalar('sector_of_operation_other')
                ->maxLength('sector_of_operation_other', 255)
                ->requirePresence('sector_of_operation_other', 'create')
                ->notEmpty('sector_of_operation_other');  

            }
                */


            if(isset($dataPost['location_of_head_quarter']) && $dataPost['location_of_head_quarter']==2){
            
            $validator
                ->scalar('state_code')
                ->maxLength('state_code', 11)
                ->requirePresence('state_code', 'create')
                ->notEmpty('state_code');

            $validator
                ->scalar('state_code')
                ->maxLength('state_code', 11)
                ->requirePresence('state_code', 'create')
                ->notEmpty('state_code');
                
                $validator
                ->scalar('district_code')
                ->maxLength('district_code', 11)
                ->requirePresence('district_code', 'create')
                ->notEmpty('district_code');

                $validator
                ->scalar('block_code')
                ->maxLength('block_code', 11)
                ->requirePresence('block_code', 'create')
                ->notEmpty('block_code');

            $validator
                ->scalar('gram_panchayat_code')
                ->maxLength('gram_panchayat_code', 11)
                ->requirePresence('gram_panchayat_code', 'create')
                ->notEmpty('gram_panchayat_code');  

                $validator
                ->scalar('village_code')
                ->maxLength('village_code', 11)
                ->requirePresence('village_code', 'create')
                ->notEmpty('village_code');  

                
            }
            if(isset($dataPost['location_of_head_quarter']) && $dataPost['location_of_head_quarter']==1){
            $validator
                ->scalar('urban_local_body_type_code')
                ->maxLength('urban_local_body_type_code', 11)
                ->requirePresence('urban_local_body_type_code', 'create')
                ->notEmpty('urban_local_body_type_code');  
                

                $validator
                ->scalar('urban_local_body_code')
                ->maxLength('urban_local_body_code', 11)
                ->requirePresence('urban_local_body_code', 'create')
                ->notEmpty('urban_local_body_code');  

                $validator
                ->scalar('urban_local_body_code')
                ->maxLength('urban_local_body_code', 11)
                ->requirePresence('urban_local_body_code', 'create')
                ->notEmpty('urban_local_body_code');

                $validator
                ->scalar('locality_ward_code')
                ->maxLength('locality_ward_code', 11)
                ->requirePresence('locality_ward_code', 'create')
                ->notEmpty('locality_ward_code');
                
            }

            $validator
                ->scalar('pincode')
                ->maxLength('pincode', 6)
                ->requirePresence('pincode', 'create')
                ->notEmpty('pincode');  

                // $validator
                // ->scalar('pincode')
                // ->maxLength('pincode', 6)
                // ->requirePresence('pincode', 'create')
                // ->notEmpty('pincode');  

            $validator
                ->scalar('sector_of_operation')
                ->maxLength('sector_of_operation', 11)
                ->requirePresence('sector_of_operation', 'create')
                ->notEmpty('sector_of_operation');  
            /*
            $validator
                ->scalar('secondary_activity')
                ->maxLength('secondary_activity', 11)
                ->requirePresence('secondary_activity', 'create')
                ->notEmpty('secondary_activity');      
                if($dataPost['secondary_activity']==5){

                $validator
                ->scalar('secondary_activity_other')
                ->maxLength('secondary_activity_other', 255)
                ->requirePresence('secondary_activity_other', 'create')
                ->notEmpty('secondary_activity_other');
                }    */

                $validator
                ->scalar('contact_person')
                ->maxLength('contact_person', 200)
                ->requirePresence('contact_person', 'create')
                ->notEmpty('contact_person');  

            $validator
                ->scalar('mobile')
                ->maxLength('mobile', 10)
                ->requirePresence('mobile', 'create')
                ->notEmpty('mobile');

            $validator
                ->scalar('functional_status')
                ->maxLength('functional_status', 9)
                ->requirePresence('functional_status', 'create')
                ->notEmpty('functional_status');

        }

        return $validator;
    }
}
