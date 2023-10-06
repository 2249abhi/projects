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
class DistrictCentralCooperativeBankTable extends Table
{

    /**audit_categories
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('district_central_cooperative_bank');
        $this->setDisplayField('dccb_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('dccb_name')
            ->maxLength('dccb_name', 255)
            ->requirePresence('dccb_name', 'create')
            ->notEmpty('dccb_name');
        
            $validator
            ->scalar('branch_code')
            ->maxLength('branch_code', 255)
            ->requirePresence('branch_code', 'create')
            ->notEmpty('branch_code');

            $validator
            ->scalar('ifsc')
            ->maxLength('ifsc', 50)
            ->requirePresence('ifsc', 'create')
            ->notEmpty('ifsc');

            $validator
            ->scalar('mobile')
            ->maxLength('mobile', 10)
            ->requirePresence('mobile', 'create')
            ->notEmpty('mobile');
            
            $validator
            ->scalar('email_id')
            ->maxLength('email_id', 100)
            ->requirePresence('email_id', 'create')
            ->notEmpty('email_id');

            $validator
            ->scalar('pincode')
            ->maxLength('pincode', 6)
            ->requirePresence('pincode', 'create')
            ->notEmpty('pincode');

            

        // $validator
        //     ->integer('status')
        //     ->requirePresence('status', 'create')
        //     ->notEmpty('status');

        return $validator;
    }
}
