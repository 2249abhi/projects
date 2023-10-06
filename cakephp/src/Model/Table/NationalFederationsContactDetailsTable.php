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
class NationalFederationsContactDetailsTable extends Table
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

        $this->setTable('national_federations_contact_details');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('NationalFederations',[
            'foreignKey' => 'national_federations_id',
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

        $dataPost = @$_REQUEST;
        $validator
            ->nonNegativeInteger('id')
            ->allowEmpty('id', 'create');

            /*$validator
                ->scalar('national_federations_id')
                ->maxLength('national_federations_id', 11)
                ->requirePresence('national_federations_id', 'create')
                ->notEmpty('national_federations_id'); */   


            $validator
                ->scalar('phone_number')
                ->maxLength('phone_number', 20)
                ->requirePresence('phone_number', 'create')
                ->notEmpty('phone_number');  

             // $validator
                // ->scalar('email')
                // ->maxLength('email', 20)
                // ->requirePresence('email', 'create')
                // ->notEmpty('email'); 
                

             $validator
                ->scalar('contact_person')
                ->maxLength('contact_person', 100)
                ->requirePresence('contact_person', 'create')
                ->notEmpty('contact_person');         
                

             $validator
                ->scalar('designation')
                ->maxLength('designation', 11)
                ->requirePresence('designation', 'create')
                ->notEmpty('designation');         
                
                
            
            // $validator
                // ->scalar('contact_details_address')
                // ->requirePresence('contact_details_address', 'create')
                // ->notEmpty('contact_details_address');  
            
            /*$validator
                ->scalar('major_activities_id')
                ->requirePresence('major_activities_id', 'create')
                ->notEmpty('major_activities_id');*/      



        return $validator;
    }
}
