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
class StateCooperativeBanksTable extends Table
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

        $this->setTable('state_cooperative_banks');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        //$this->addBehavior('Timestamp');
		
		$this->belongsTo('States', [
            'foreignKey' => 'state_code',
			'bindingKey'=> 'state_code',
            'className'=>'States',
			'joinType' => 'inner'
        ]);
		
		$this->belongsTo('Districts', [
            'foreignKey' => 'district_code',
			'bindingKey'=> 'district_code',
            'className'=>'Districts',
			'joinType' => 'inner'
        ]);
		
        $this->hasMany('ScbImplementingSchemes', [
            'foreignKey' => 'state_cooperative_bank_id',
            'className'=>'ScbImplementingSchemes',
            'dependent' => true
        ]);

        $this->hasMany('ScbContactDetails', [
            'foreignKey' => 'state_cooperative_bank_id',
            'className'=>'ScbContactDetails',
            'dependent' => true
        ]);

        $this->hasMany('DcbScbOtherMembers', [
            'foreignKey' => 'state_cooperative_bank_id',
            'className'=>'DcbScbOtherMembers',
            'dependent' => true
        ]);
        $this->hasMany('ScardbDetails', [
            'foreignKey' => 'state_cooperative_bank_id',
            'className'=>'ScardbDetails',
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

        return $validator;
    }
}
