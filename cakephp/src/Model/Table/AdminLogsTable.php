<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AdminLogs Model
 *
 * @method \App\Model\Entity\AdminLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdminLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AdminLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdminLog|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdminLog|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdminLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdminLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdminLog findOrCreate($search, callable $callback = null, $options = [])
 */
class AdminLogsTable extends Table
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

        $this->setTable('admin_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'uid',
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
            ->nonNegativeInteger('id')
            ->allowEmpty('id', 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->nonNegativeInteger('uid')
            ->requirePresence('uid', 'create')
            ->notEmpty('uid');

        $validator
            ->dateTime('logtime')
            ->requirePresence('logtime', 'create')
            ->notEmpty('logtime');

        $validator
            ->requirePresence('ipaddress', 'create')
            ->notEmpty('ipaddress');

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
        $rules->add($rules->isUnique(['id']));

        return $rules;
    }
}
