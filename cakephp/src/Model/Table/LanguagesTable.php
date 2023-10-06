<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\Cache\Cache;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Languages Model
 *
 * @property |\Cake\ORM\Association\HasMany $ArticleTranslations
 * @property |\Cake\ORM\Association\HasMany $MenuTranslations
 * @property \App\Model\Table\TenderTranslationsTable|\Cake\ORM\Association\HasMany $TenderTranslations
 *
 * @method \App\Model\Entity\Language get($primaryKey, $options = [])
 * @method \App\Model\Entity\Language newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Language[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Language|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Language|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Language patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Language[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Language findOrCreate($search, callable $callback = null, $options = [])
 */
class LanguagesTable extends Table
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

        $this->setTable('languages');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('ArticleTranslations', [
            'foreignKey' => 'language_id'
        ]);
        $this->hasMany('MenuTranslations', [
            'foreignKey' => 'language_id'
        ]);
        $this->hasMany('TenderTranslations', [
            'foreignKey' => 'language_id'
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
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('culture')
            ->maxLength('culture', 10)
            ->requirePresence('culture', 'create')
            ->notEmpty('culture')
            ->add('culture', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message'  => 'Culture already exist.',]);

        $validator
            ->scalar('direction')
            ->requirePresence('direction', 'create')
            ->notEmpty('direction');

        $validator
            ->boolean('is_default')
            ->requirePresence('is_default', 'create')
            ->notEmpty('is_default');

        $validator
            ->boolean('is_system')
            ->requirePresence('is_system', 'create')
            ->notEmpty('is_system');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->dateTime('created_at')
            ->allowEmpty('created_at');

        $validator
            ->dateTime('modified_at')
            ->allowEmpty('modified_at');

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
        $rules->add($rules->isUnique(['culture']));

        return $rules;
    }

    public function afterSave($event, $entity, $options = [])
    {
        $this->languageCache();
    }

    public function afterDelete($event, $entity, $options = [])
    {
        $this->languageCache();
    }

    public function languageCache()
    {
        Cache::clearGroup('silver-language', 'languages');
        $getLanguages = $this->find('all')
            ->select(["id", "name", "culture", "is_default", "direction", "is_system", "status"])
            ->order(['is_system' => 'DESC', 'is_default' => 'DESC'])
            ->enableHydration(false)
            ->toArray();

        Cache::write('site-language', $getLanguages, 'languages');
        return $getLanguages;
    }
}
