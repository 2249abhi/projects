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
class NationalFederationsTable extends Table
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

        $this->setTable('national_federations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('States',[
            'foreignKey' => 'state_code',
            'joinType' => 'INNER'
        ]);


        $this->hasMany('NationalFederationsContactDetails', [
            'foreignKey' => 'national_federations_id',
            'className'=>'NationalFederationsContactDetails',
            'dependent' => true
        ]);

        $this->hasMany('NationalFederationsMajorActivities', [
            'foreignKey' => 'national_federations_id',
            'className'=>'NationalFederationsMajorActivities',
            'dependent' => true
        ]);

        $this->hasMany('NationalFederationsMembers', [
            'foreignKey' => 'national_federations_id',
            'className'=>'NationalFederationsMembers',
            'dependent' => true
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

            $validator
                ->scalar('cooperative_id')
                ->maxLength('cooperative_id', 11)
                ->requirePresence('cooperative_id', 'create')
                ->notEmpty('cooperative_id');

            $validator
                ->scalar('reference_year')
                ->maxLength('reference_year', 5)
                ->requirePresence('reference_year', 'create')
                ->notEmpty('reference_year');

            $validator
                ->scalar('cooperative_society_name')
                ->maxLength('cooperative_society_name', 255)
                ->requirePresence('cooperative_society_name', 'create')
                ->notEmpty('cooperative_society_name');    

            $validator
                ->scalar('registration_number')
                ->maxLength('registration_number', 255)
                ->requirePresence('registration_number', 'create')
                ->notEmpty('registration_number');        

            
            $validator
                ->scalar('date_registration')
                ->requirePresence('date_registration', 'create')
                ->notEmpty('date_registration');            
            
            $validator
                ->scalar('state_code')
                ->maxLength('state_code', 11)
                ->requirePresence('state_code', 'create')
                ->notEmpty('state_code');

            if(@$dataPost['location_of_head_quarter']==1){
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
                ->scalar('locality_ward_code')
                ->maxLength('locality_ward_code', 11)
                ->requirePresence('locality_ward_code', 'create')
                ->notEmpty('locality_ward_code');
            }else{

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

                $validator
                ->scalar('district_code')
                ->maxLength('district_code', 11)
                ->requirePresence('district_code', 'create')
                ->notEmpty('district_code');



            }

            $validator
                ->scalar('pincode')
                ->maxLength('pincode', 11)
                ->requirePresence('pincode', 'create')
                ->notEmpty('pincode');
			// if(@$dataPost['functional_status'] !=3){
				// $validator
                // ->scalar('total_number_of_members')
                // ->maxLength('total_number_of_members',11)
                // ->requirePresence('total_number_of_members', 'create')
                // ->notEmpty('total_number_of_members');
			// }
              
        

        return $validator;
    }
}
