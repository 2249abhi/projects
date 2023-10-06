<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\Cache\Cache;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Menus Model
 *
 * @property \App\Model\Table\MenuRegionsTable|\Cake\ORM\Association\BelongsTo $MenuRegions
 * @property \App\Model\Table\ObjectsTable|\Cake\ORM\Association\BelongsTo $Objects
 * @property \App\Model\Table\ModulesTable|\Cake\ORM\Association\BelongsTo $Modules
 * @property \App\Model\Table\MenuTranslationsTable|\Cake\ORM\Association\HasMany $MenuTranslations
 *
 * @method \App\Model\Entity\Menu get($primaryKey, $options = [])
 * @method \App\Model\Entity\Menu newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Menu[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Menu|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Menu|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Menu patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Menu[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Menu findOrCreate($search, callable $callback = null, $options = [])
 */
class MenusTable extends Table
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

        $this->setTable('menus');
        $this->setDisplayField('menu_title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree', [
            'parent' => 'parent_id',
            'left'   => 'lft',
            'right'  => 'rght',
        ]);

        $this->belongsTo('MenuRegions', [
            'foreignKey' => 'menu_region_id',
            'joinType'   => 'INNER',
            'className'  => 'MenuRegions',
        ]);

        $this->belongsTo('Articles', [
            'foreignKey' => 'object_id',
            'joinType'   => 'LEFT',
            'className'  => 'Articles',
        ]);

        $this->belongsTo('MenuTranslation', [
            'foreignKey' => 'id',
            'bindingKey' => 'menu_id',
            'joinType'   => 'LEFT',
            'className'  => 'MenuTranslations',
        ]);

        $this->hasMany('MenuTranslations', [
            'foreignKey' => 'menu_id',
            'className'  => 'MenuTranslations',
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
            ->scalar('menu_title')
            ->maxLength('menu_title', 255)
            ->requirePresence('menu_title', 'create')
            ->notEmpty('menu_title');

        $validator
            ->scalar('menu_type')
            ->requirePresence('menu_type', 'create')
            ->notEmpty('menu_type');

        $validator
            ->scalar('custom_link')
            ->allowEmpty('custom_link');

        $validator
            ->scalar('internal_link')
            ->allowEmpty('internal_link');
        
        $validator
            ->scalar('object_type')
            ->maxLength('object_type', 30)
            ->allowEmpty('object_type');

        $validator
            ->scalar('redirection')
            ->requirePresence('redirection', 'create')
            ->notEmpty('redirection');

        $validator
            ->integer('sort_order')
            ->requirePresence('sort_order', 'create')
            ->notEmpty('sort_order');

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
        $rules->add($rules->existsIn(['menu_region_id'], 'MenuRegions'));

        return $rules;
    }

    public function afterSave($event, $entity, $options = [])
    {
        Cache::clearGroup('silver-menu', 'silvermenu');
    }

    public function afterDelete($event, $entity, $options = [])
    {
        Cache::clearGroup('silver-menu', 'silvermenu');
    }
}
