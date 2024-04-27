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
class CooperativeFederationDairyTable extends Table
{

    /**cooperative_registration_dairy
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('cooperative_federation_dairy');
       // $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

         $this->belongsTo('CooperativeFederations', [
            'foreignKey' => 'cooperative_federations_id',
          //  'joinType' => 'INNER'
            'className'  => 'CooperativeFederations',
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

      /*  $dataPost = @$_REQUEST;
        $validator
            ->nonNegativeInteger('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('milk_collection')
            ->maxLength('milk_collection',11)
            ->requirePresence('milk_collection', 'create')
            ->notEmpty('milk_collection');

        $validator
            ->scalar('credit_facility')
            ->maxLength('credit_facility', 1)
            ->requirePresence('credit_facility', 'create')
            ->notEmpty('credit_facility');

        $validator
            ->scalar('credit_provided')
            ->maxLength('credit_provided', 100)
            ->requirePresence('credit_provided', 'create')
            ->notEmpty('credit_provided');    

        $validator
            ->scalar('milk_collection_unit')
            ->maxLength('milk_collection_unit', 1)
            ->requirePresence('milk_collection_unit', 'create')
            ->notEmpty('milk_collection_unit');  

        $validator
            ->scalar('milk_collection_capicity')
            ->maxLength('milk_collection_capicity', 100)
            ->requirePresence('milk_collection_capicity', 'create')
            ->notEmpty('milk_collection_capicity');        

        
        $validator
            ->scalar('transport_milk')
            ->maxLength('transport_milk', 1)
            ->requirePresence('transport_milk', 'create')
            ->notEmpty('transport_milk');            
        
        $validator
            ->scalar('bulk_milk_unit')
            ->maxLength('bulk_milk_unit', 1)
            ->requirePresence('bulk_milk_unit', 'create')
            ->notEmpty('bulk_milk_unit');  

        $validator
            ->scalar('milk_testing')
            ->maxLength('milk_testing', 1)
            ->requirePresence('milk_testing', 'create')
            ->notEmpty('milk_testing');  
    
       $validator
            ->scalar('processing')
            ->maxLength('processing', 1)
            ->requirePresence('processing', 'create')
            ->notEmpty('processing'); 
         
         $validator
            ->scalar('other_facility')
            ->maxLength('other_facility', 100)
            ->requirePresence('other_facility', 'create')
            ->notEmpty('other_facility'); */

        return $validator;
    }
}
