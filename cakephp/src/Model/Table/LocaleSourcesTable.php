<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LocaleSources Model
 *
 * @property \App\Model\Table\LocaleTargetsTable|\Cake\ORM\Association\HasMany $LocaleTargets
 *
 * @method \App\Model\Entity\LocaleSource get($primaryKey, $options = [])
 * @method \App\Model\Entity\LocaleSource newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LocaleSource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LocaleSource|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LocaleSource|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LocaleSource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LocaleSource[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LocaleSource findOrCreate($search, callable $callback = null, $options = [])
 */
class LocaleSourcesTable extends Table
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

        $this->setTable('locale_sources');
        $this->setDisplayField('source');
        $this->setPrimaryKey('id');

        $this->hasMany('LocaleTargets', [
            'foreignKey' => 'locale_source_id'
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
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('source')
            ->maxLength('source', 255)
            ->requirePresence('source', 'create')
            ->notEmpty('source');

        return $validator;
    }
}
