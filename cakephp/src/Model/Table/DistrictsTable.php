<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Districts Model
 *
 * @property \App\Model\Table\StatesTable|\Cake\ORM\Association\BelongsTo $States
 * @property \App\Model\Table\RegistersTable|\Cake\ORM\Association\HasMany $Registers
 *
 * @method \App\Model\Entity\District get($primaryKey, $options = [])
 * @method \App\Model\Entity\District newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\District[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\District|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\District|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\District patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\District[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\District findOrCreate($search, callable $callback = null, $options = [])
 */
class DistrictsTable extends Table
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

        $this->setTable('districts');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('States', [
            'foreignKey' => 'state_code',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Registers', [
            'foreignKey' => 'district_id'
        ]);

        $this->hasMany('CooperativeRegistrations', [
            'foreignKey' => 'district_code',
            'className'=>'CooperativeRegistrations',
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
        $validator
            ->nonNegativeInteger('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->boolean('flag')
            ->requirePresence('flag', 'create')
            ->notEmpty('flag');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        // $rules->add($rules->existsIn(['state_id'], 'States'));

        // return $rules;
    }
}
