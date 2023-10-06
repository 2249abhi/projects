<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GalleryCategories Model
 *
 * @property \App\Model\Table\ArticlesTable|\Cake\ORM\Association\BelongsTo $Articles
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\GalleriesTable|\Cake\ORM\Association\HasMany $Galleries
 *
 * @method \App\Model\Entity\GalleryCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\GalleryCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GalleryCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GalleryCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GalleryCategory|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GalleryCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GalleryCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GalleryCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GalleryCategoriesTable extends Table
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

        $this->setTable('gallery_categories');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Articles', [
            'foreignKey' => 'article_id'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Galleries', [
            'foreignKey' => 'gallery_category_id'
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
            ->scalar('title')
            ->maxLength('title', 250)
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->scalar('content')
            ->allowEmpty('content');

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
        $rules->add($rules->existsIn(['article_id'], 'Articles'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
