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
class CooperativeRegistrationFisheryTable extends Table
{

    /**cooperative_registration_fishery
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('cooperative_registration_fishery');
       // $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');  

        
         $this->belongsTo('CooperativeRegistrations', [
            'foreignKey' => 'cooperative_registrations_id',
          //  'joinType' => 'INNER'
            'className'  => 'CooperativeRegistrations',
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

       /* $dataPost = @$_REQUEST;
        $validator
            ->nonNegativeInteger('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('annual_fish_catch')
            ->maxLength('annual_fish_catch',100)
            ->requirePresence('annual_fish_catch', 'create')
            ->notEmpty('annual_fish_catch');

        $validator
            ->scalar('credit_facility')
            ->maxLength('credit_facility', 1)
            ->requirePresence('credit_facility', 'create')
            ->notEmpty('credit_facility');

        $validator
            ->scalar('total_credit_provided')
            ->maxLength('total_credit_provided', 100)
            ->requirePresence('total_credit_provided', 'create')
            ->notEmpty('total_credit_provided');    

        $validator
            ->scalar('fuel_distribution')
            ->maxLength('fuel_distribution', 1)
            ->requirePresence('fuel_distribution', 'create')
            ->notEmpty('fuel_distribution');  

        $validator
            ->scalar('marketing')
            ->maxLength('marketing', 1)
            ->requirePresence('marketing', 'create')
            ->notEmpty('marketing');        

        
        $validator
            ->scalar('cold_storage')
            ->maxLength('cold_storage', 1)
            ->requirePresence('cold_storage', 'create')
            ->notEmpty('cold_storage');            
        
        $validator
            ->scalar('transportation')
            ->maxLength('transportation', 1)
            ->requirePresence('transportation', 'create')
            ->notEmpty('transportation');  

        $validator
            ->scalar('other_facility')
            ->maxLength('other_facility', 100)
            ->requirePresence('other_facility', 'create')
            ->notEmpty('other_facility'); */   
     

        return $validator;
    }
}
