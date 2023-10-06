<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\Cache\Cache;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * News Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\NewsTranslationsTable|\Cake\ORM\Association\HasMany $NewsTranslations
 *
 * @method \App\Model\Entity\News get($primaryKey, $options = [])
 * @method \App\Model\Entity\News newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\News[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\News|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\News|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\News patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\News[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\News findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NewsTable extends Table
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

        $this->setTable('news');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('NewsTranslation', [
            'foreignKey' => 'id',
            'bindingKey' => 'news_id',
            'joinType'   => 'LEFT',
            'className'  => 'NewsTranslations',
        ]);
        $this->hasMany('NewsTranslations', [
            'foreignKey' => 'news_id',
            'className'  => 'NewsTranslations',
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
            ->scalar('excerpt')
            ->allowEmpty('excerpt');

        $validator
            ->scalar('meta_title')
            ->maxLength('meta_title', 250)
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
            ->allowEmpty('content');

        $validator
            ->scalar('news_url')
            ->maxLength('news_url', 250)
            ->allowEmpty('news_url');

        $validator
            ->date('display_date')
            ->requirePresence('display_date', 'create')
            ->notEmpty('display_date');

        $validator
            ->integer('sort_order')
            ->allowEmpty('sort_order');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->scalar('custom_link')
            ->maxLength('custom_link', 250)
            ->allowEmpty('custom_link');

        /*$validator
            ->scalar('upload_document_1')
            ->maxLength('upload_document_1', 250)
            ->allowEmpty('upload_document_1');

        $validator
            ->scalar('upload_document_2')
            ->maxLength('upload_document_2', 250)
            ->allowEmpty('upload_document_2');*/

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
        $rules->add($rules->isUnique(['news_url']));

        return $rules;
    }

    public function afterSave($event, $entity, $options = [])
    {
        Cache::clearGroup('silver-menu', 'silvermenu');
        $this->newsCache();
    }

    public function afterDelete($event, $entity, $options = [])
    {
        Cache::clearGroup('silver-menu', 'silvermenu');
        $this->newsCache();
    }
    
    public function newsCache()
    {
        Cache::clearGroup('silver-news', 'news');
        $rewriteRules = $this->find('all')
            ->select(["id", "title", "news_url"])
            ->contain(['NewsTranslations' => function ($q) {
                $q->select(['news_id', 'language_id', 'culture', 'news_url']);
                $q->where(['news_url IS NOT NULL', 'news_url !=' => '']);
                return $q;
            }])
            ->where(['status' => 1])
            ->enableHydration(false)
            ->toArray();
        Cache::write('rewrite_rules', $rewriteRules, 'news');
        return $rewriteRules;
    }
}
