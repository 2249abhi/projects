<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Banners Model
 *
 * @property \App\Model\Table\BannerCategoriesTable|\Cake\ORM\Association\BelongsTo $BannerCategories
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Banner get($primaryKey, $options = [])
 * @method \App\Model\Entity\Banner newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Banner[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Banner|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Banner|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Banner patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Banner[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Banner findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BannersTable extends Table
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

        $this->setTable('banners');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('BannerCategories', [
            'foreignKey' => 'banner_category_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->allowEmpty('id', 'create');

        /* $validator
            ->scalar('title')
            ->maxLength('title', 250)
            ->requirePresence('title', 'create')
            ->notEmpty('title'); */

        /* $validator
            ->scalar('excerpt')
            ->requirePresence('excerpt', 'create')
            ->notEmpty('excerpt');
		$validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmpty('description');	 */ 

        $validator
            ->scalar('banner_type')
            ->requirePresence('banner_type', 'create')
            ->notEmpty('banner_type');

        /*$validator
            ->scalar('banner_image')
            ->allowEmpty('banner_image');

        $validator
            ->scalar('banner_video')
            ->allowEmpty('banner_video');*/

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
        $rules->add($rules->existsIn(['banner_category_id'], 'BannerCategories'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
