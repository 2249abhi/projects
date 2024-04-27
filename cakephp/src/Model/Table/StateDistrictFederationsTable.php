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
class StateDistrictFederationsTable extends Table
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

        $this->setTable('state_district_federations');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        // $this->setDisplayField('name');
        // $this->setPrimaryKey('id');

        // $this->addBehavior('Timestamp');

        // $this->hasMany('SdFederationMembers', [
        //     'foreignKey' => 'state_district_federation_id',
        //     'className'=>'SdFederationMembers',
        // ]);
		
        $this->hasMany('SdFederationOtherMembers', [
            'foreignKey' => 'state_district_federation_id',
            'className'=>'SdFederationOtherMembers',
            'dependent' => true
        ]);


        $this->hasMany('SdFederationRurals', [
            'foreignKey' => 'state_district_federation_id',
            'className'=>'SdFederationRurals',
            'dependent' => true
        ]);

        $this->hasMany('SdFederationUrbans', [
            'foreignKey' => 'state_district_federation_id',
            'className'=>'SdFederationUrbans',
            'dependent' => true
        ]);


        $this->hasMany('SdFederationPacs', [
            'foreignKey' => 'state_district_federation_id',
            'className'=>'SdFederationPacs'
        ]);

        $this->hasMany('SdFederationAgriculture', [
            'foreignKey' => 'state_district_federation_id',
            'className'=>'SdFederationAgriculture'
        ]);
    }


}
