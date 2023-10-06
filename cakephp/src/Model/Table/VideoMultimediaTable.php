<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Departments Model
 *
 * @property \App\Model\Table\DesignationsTable|\Cake\ORM\Association\HasMany $Designations
 *
 * @method \App\Model\Entity\Department get($primaryKey, $options = [])
 * @method \App\Model\Entity\Department newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Department[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Department|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Department|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Department patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Department[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Department findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VideoMultimediaTable extends Table
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

        $this->setTable('video_multimedia');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('MultimediaCategories', [
            'foreignKey' => 'multimedia_category_id'
        ]);

        /*$this->belongsTo('MultimediaSubcategories', [
            'foreignKey' => 'multimedia_subcategory_id'
        ]);*/
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    /*public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        return $validator;
    }*/
}
