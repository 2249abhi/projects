<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MenuTranslations Model
 *
 * @property \App\Model\Table\MenusTable|\Cake\ORM\Association\BelongsTo $Menus
 * @property \App\Model\Table\LanguagesTable|\Cake\ORM\Association\BelongsTo $Languages
 *
 * @method \App\Model\Entity\MenuTranslation get($primaryKey, $options = [])
 * @method \App\Model\Entity\MenuTranslation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MenuTranslation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MenuTranslation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MenuTranslation|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MenuTranslation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MenuTranslation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MenuTranslation findOrCreate($search, callable $callback = null, $options = [])
 */
class MenuTranslationsTable extends Table
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

        $this->setTable('menu_translations');
        $this->setDisplayField('menu_title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Menus', [
            'foreignKey' => 'menu_id',
            'joinType' => 'INNER',
            'className' => 'Menus'
        ]);
        $this->belongsTo('Languages', [
            'foreignKey' => 'language_id',
            'joinType' => 'INNER',
            'className' => 'Languages'
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
            ->scalar('culture')
            ->maxLength('culture', 10)
            ->requirePresence('culture', 'create')
            ->notEmpty('culture');

        $validator
            ->scalar('menu_title')
            ->maxLength('menu_title', 255)
            ->allowEmpty('menu_title');

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
        $rules->add($rules->existsIn(['menu_id'], 'Menus'));
        $rules->add($rules->existsIn(['language_id'], 'Languages'));

        return $rules;
    }
}
