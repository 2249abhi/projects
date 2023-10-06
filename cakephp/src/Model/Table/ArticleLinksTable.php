<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\Cache\Cache;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ArticleLinks Model
 *
 * @property \App\Model\Table\ArticlesTable|\Cake\ORM\Association\BelongsTo $Articles
 *
 * @method \App\Model\Entity\ArticleLink get($primaryKey, $options = [])
 * @method \App\Model\Entity\ArticleLink newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ArticleLink[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ArticleLink|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArticleLink|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArticleLink patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ArticleLink[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ArticleLink findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArticleLinksTable extends Table
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

        $this->setTable('article_links');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Articles', [
            'foreignKey' => 'article_id',
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
            ->scalar('link_type')
            ->maxLength('link_type', 250)
            ->allowEmpty('link_type');

        $validator
            ->scalar('custom_link')
            ->maxLength('custom_link', 255)
            ->allowEmpty('custom_link');
            
        $validator
            ->scalar('internal_link')
            ->maxLength('internal_link', 255)
            ->allowEmpty('internal_link');

        $validator
            ->scalar('link_title')
            ->maxLength('link_title', 255)
            ->allowEmpty('link_title');

        $validator
            ->scalar('redirection')
            ->maxLength('redirection', 250)
            ->requirePresence('redirection', 'create')
            ->notEmpty('redirection');

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

        return $rules;
    }
}
