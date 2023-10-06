<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Complaints Model
 *
 * @property \App\Model\Table\EmailsTable|\Cake\ORM\Association\BelongsTo $Emails
 *
 * @method \App\Model\Entity\Complaint get($primaryKey, $options = [])
 * @method \App\Model\Entity\Complaint newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Complaint[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Complaint|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Complaint|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Complaint patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Complaint[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Complaint findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ComplaintsTable extends Table
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

        $this->setTable('complaints');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Emails', [
            'foreignKey' => 'email_id',
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
            ->scalar('full_name')
            ->maxLength('full_name', 255)
            ->requirePresence('full_name', 'create')
            ->notEmpty('full_name');

        $validator
            ->scalar('company_name')
            ->maxLength('company_name', 255)
            ->requirePresence('company_name', 'create')
            ->notEmpty('company_name');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->requirePresence('address', 'create')
            ->notEmpty('address');

        $validator
            ->scalar('city')
            ->maxLength('city', 255)
            ->requirePresence('city', 'create')
            ->notEmpty('city');

        $validator
            ->scalar('state')
            ->maxLength('state', 255)
            ->requirePresence('state', 'create')
            ->notEmpty('state');

        $validator
            ->scalar('country')
            ->maxLength('country', 100)
            ->requirePresence('country', 'create')
            ->notEmpty('country');

        $validator
            ->scalar('pincode')
            ->maxLength('pincode', 100)
            ->requirePresence('pincode', 'create')
            ->notEmpty('pincode');

        $validator
            ->scalar('fixed_line_number')
            ->maxLength('fixed_line_number', 255)
            ->requirePresence('fixed_line_number', 'create')
            ->notEmpty('fixed_line_number');

        $validator
            ->scalar('mobile_number')
            ->maxLength('mobile_number', 100)
            ->requirePresence('mobile_number', 'create')
            ->notEmpty('mobile_number');

        $validator
            ->scalar('email_id')
            ->maxLength('email_id', 255)
            ->requirePresence('email_id', 'create')
            ->notEmpty('email_id');

        $validator
            ->scalar('subject')
            ->maxLength('subject', 255)
            ->requirePresence('subject', 'create')
            ->notEmpty('subject');
        
        $validator
            ->scalar('reference_number')
            ->allowEmpty('reference_number');

        $validator
            ->scalar('comments')
            ->requirePresence('comments', 'create')
            ->notEmpty('comments');

        return $validator;
    }
}
