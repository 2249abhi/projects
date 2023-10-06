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
class CooperativeRegistrationsTribalTable extends Table
{

    /** cooperative_registration_pacs
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('cooperative_registrations_tribal');
       // $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');  

           $this->belongsTo('CooperativeRegistrations', [
            'foreignKey' => 'cooperative_registration_id',
            'joinType' => 'INNER'
            
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

    //     $dataPost = @$_REQUEST;
    //     $validator
    //         ->nonNegativeInteger('id')
    //         ->allowEmpty('id', 'create');

    //     $validator
    //         ->scalar('has_building')
    //         ->maxLength('has_building',1)
    //         ->requirePresence('has_building', 'create')
    //         ->notEmpty('has_building');

    //     $validator
    //         ->scalar('building_type')
    //         ->maxLength('building_type', 1)
    //         ->requirePresence('building_type', 'create')
    //         ->notEmpty('building_type');

    //     $validator
    //         ->scalar('available_land')
    //         ->maxLength('available_land', 11)
    //         ->requirePresence('available_land', 'create')
    //         ->notEmpty('available_land');    

    //     $validator
    //         ->scalar('credit_facility')
    //         ->maxLength('credit_facility', 1)
    //         ->requirePresence('credit_facility', 'create')
    //         ->notEmpty('credit_facility');  

    //     $validator
    //         ->scalar('total_credit')
    //         ->maxLength('total_credit', 100)
    //         ->requirePresence('total_credit', 'create')
    //         ->notEmpty('total_credit');        

        
    //     $validator
    //         ->scalar('fertilizer_distribution')
    //         ->maxLength('fertilizer_distribution', 1)
    //         ->requirePresence('fertilizer_distribution', 'create')
    //         ->notEmpty('fertilizer_distribution');            
        
    //     $validator
    //         ->scalar('pesticide_distribution')
    //         ->maxLength('pesticide_distribution', 1)
    //         ->requirePresence('pesticide_distribution', 'create')
    //         ->notEmpty('pesticide_distribution');  

    //      $validator
    //         ->scalar('seed_distribution')
    //         ->maxLength('seed_distribution', 1)
    //         ->requirePresence('seed_distribution', 'create')
    //         ->notEmpty('seed_distribution');  

    //     $validator
    //         ->scalar('is_foodgrains')
    //         ->maxLength('is_foodgrains', 1)
    //         ->requirePresence('is_foodgrains', 'create')
    //         ->notEmpty('is_foodgrains'); 

    //      $validator
    //         ->scalar('agricultural_implements')
    //         ->maxLength('agricultural_implements', 1)
    //         ->requirePresence('agricultural_implements', 'create')
    //         ->notEmpty('agricultural_implements'); 

    //          $validator
    //         ->scalar('dry_storage')
    //         ->maxLength('dry_storage', 1)
    //         ->requirePresence('dry_storage', 'create')
    //         ->notEmpty('dry_storage'); 

    //          $validator
    //         ->scalar('dry_storage_capicity')
    //         ->maxLength('dry_storage_capicity', 200)
    //         ->requirePresence('dry_storage_capicity', 'create')
    //         ->notEmpty('dry_storage_capicity');

    //          $validator
    //         ->scalar('cold_storage')
    //         ->maxLength('cold_storage', 1)
    //         ->requirePresence('cold_storage', 'create')
    //         ->notEmpty('cold_storage');

    //     $validator
    //         ->scalar('cold_storage_capicity')
    //         ->maxLength('cold_storage_capicity', 100)
    //         ->requirePresence('cold_storage_capicity', 'create')
    //         ->notEmpty('cold_storage_capicity');

    //     $validator
    //         ->scalar('milk_unit')
    //         ->maxLength('milk_unit', 1)
    //         ->requirePresence('milk_unit', 'create')
    //         ->notEmpty('milk_unit');

    //     $validator
    //         ->scalar('milk_capicity_unit')
    //         ->maxLength('milk_capicity_unit', 200)
    //         ->requirePresence('milk_capicity_unit', 'create')
    //         ->notEmpty('milk_capicity_unit');

    //     $validator
    //         ->scalar('food_processing')
    //         ->maxLength('food_processing', 1)
    //         ->requirePresence('food_processing', 'create')
    //         ->notEmpty('food_processing');

    //  $validator
    //         ->scalar('food_processing_type')
    //         ->maxLength('food_processing_type', 100)
    //         ->requirePresence('food_processing_type', 'create')
    //         ->notEmpty('food_processing_type');

    // $validator
    //         ->scalar('other_facility')
    //         ->maxLength('other_facility', 100)
    //         ->requirePresence('other_facility', 'create')
    //         ->notEmpty('other_facility');     

        return $validator;
    }
}
