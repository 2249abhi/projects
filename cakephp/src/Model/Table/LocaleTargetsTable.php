<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LocaleTargets Model
 *
 * @property \App\Model\Table\LocaleSourcesTable|\Cake\ORM\Association\BelongsTo $LocaleSources
 *
 * @method \App\Model\Entity\LocaleTarget get($primaryKey, $options = [])
 * @method \App\Model\Entity\LocaleTarget newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LocaleTarget[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LocaleTarget|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LocaleTarget|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LocaleTarget patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LocaleTarget[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LocaleTarget findOrCreate($search, callable $callback = null, $options = [])
 */
class LocaleTargetsTable extends Table
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

        $this->setTable('locale_targets');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('LocaleSources', [
            'foreignKey' => 'locale_source_id',
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
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('translation')
            ->allowEmpty('translation');

        $validator
            ->scalar('language')
            ->maxLength('language', 11)
            ->requirePresence('language', 'create')
            ->notEmpty('language');

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
        $rules->add($rules->existsIn(['locale_source_id'], 'LocaleSources'));

        return $rules;
    }
}
