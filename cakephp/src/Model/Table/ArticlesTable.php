<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\Cache\Cache;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


/**
 * Articles Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ArticleTranslationsTable|\Cake\ORM\Association\HasMany $ArticleTranslations
 *
 * @method \App\Model\Entity\Article get($primaryKey, $options = [])
 * @method \App\Model\Entity\Article newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Article[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Article|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Article[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Article findOrCreate($search, callable $callback = null, $options = [])
 */
class ArticlesTable extends Table
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

        $this->setTable('articles');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
        $this->addBehavior('Status');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('ArticleTranslation', [
            'foreignKey' => 'id',
            'bindingKey' => 'article_id',
            'joinType'   => 'LEFT',
            'className'  => 'ArticleTranslations',
        ]);

        $this->hasMany('ArticleTranslations', [
            'foreignKey' => 'article_id',
            'className'  => 'ArticleTranslations',
        ]);

        $this->hasMany('ArticleLinks', [
            'foreignKey' => 'article_id',
            'className'  => 'ArticleLinks',
        ]);

        $this->hasMany('ArticleImages', [
            'foreignKey' => 'article_id',
            'className'  => 'ArticleImages',
        ]);

        $this->hasMany('Posts', [
            'foreignKey' => 'article_id',
            'className'  => 'Posts',
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
            ->scalar('title')
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->scalar('slug')
            ->allowEmpty('slug');

        $validator
            ->scalar('excerpt')
            ->allowEmpty('excerpt');

        $validator
            ->scalar('meta_title')
            ->allowEmpty('meta_title');

        $validator
            ->scalar('meta_keywords')
            ->allowEmpty('meta_keywords');

        $validator
            ->scalar('meta_description')
            ->allowEmpty('meta_description');

        $validator
            ->scalar('content')
            ->maxLength('content', 4294967295)
            ->requirePresence('content', 'create')
            ->notEmpty('content');

        $validator
            ->scalar('url')
            ->allowEmpty('url');

        $validator
            ->integer('sort_order')
            ->allowEmpty('sort_order');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->isUnique(['url']));

        return $rules;
    }

    public function afterSave($event, $entity, $options = [])
    {
        Cache::clearGroup('silver-menu', 'silvermenu');
        $this->articleCache();
    }

    public function afterDelete($event, $entity, $options = [])
    {
        Cache::clearGroup('silver-menu', 'silvermenu');
        $this->articleCache();
    }
    
    public function articleCache()
    {
        Cache::clearGroup('silver-article', 'articles');
        $rewriteRules = $this->find('all')
            ->select(["id", "title", "slug", "url"])
            ->contain(['ArticleTranslations' => function ($q) {
                $q->select(['article_id', 'language_id', 'culture', 'url']);
                $q->where(['url IS NOT NULL', 'url !=' => '']);
                return $q;
            }])
            ->where(['status' => 1])
            ->enableHydration(false)
            ->toArray();
        Cache::write('rewrite_rules', $rewriteRules, 'articles');
        return $rewriteRules;
    }
}
