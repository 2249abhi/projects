<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MenuRegions Model
 *
 * @property \App\Model\Table\MenusTable|\Cake\ORM\Association\HasMany $Menus
 *
 * @method \App\Model\Entity\MenuRegion get($primaryKey, $options = [])
 * @method \App\Model\Entity\MenuRegion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MenuRegion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MenuRegion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MenuRegion|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MenuRegion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MenuRegion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MenuRegion findOrCreate($search, callable $callback = null, $options = [])
 */
class MenuRegionsTable extends Table
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

        $this->setTable('menu_regions');
        $this->setDisplayField('region');
        $this->setPrimaryKey('id');

        $this->hasMany('Menus', [
            'foreignKey' => 'menu_region_id'
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('region')
            ->maxLength('region', 35)
            ->requirePresence('region', 'create')
            ->notEmpty('region');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 50)
            ->requirePresence('slug', 'create')
            ->notEmpty('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

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
        $rules->add($rules->isUnique(['slug']));

        return $rules;
    }
}
