<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RolesPermissions Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Roles
 *
 * @method \App\Model\Entity\RolesPermission get($primaryKey, $options = [])
 * @method \App\Model\Entity\RolesPermission newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RolesPermission[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RolesPermission|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RolesPermission|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RolesPermission patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RolesPermission[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RolesPermission findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RolesPermissionsTable extends Table
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

        $this->setTable('roles_permissions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Child', [
            'className' => 'Modules',
            'foreignKey' => 'mid',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
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
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->integer('mid')
            ->requirePresence('mid', 'create')
            ->notEmpty('mid');

        $validator
            ->integer('navigationshow')
            ->allowEmpty('navigationshow');

        $validator
            ->scalar('module')
            ->maxLength('module', 200)
            ->requirePresence('module', 'create')
            ->notEmpty('module');

        $validator
            ->scalar('moduletask')
            ->maxLength('moduletask', 200)
            ->requirePresence('moduletask', 'create')
            ->notEmpty('moduletask');

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
        $rules->add($rules->existsIn(['role_id'], 'Roles'));

        return $rules;
    }
}
