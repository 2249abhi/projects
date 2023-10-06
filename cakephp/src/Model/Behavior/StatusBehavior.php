<?php
namespace App\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Utility\Text;

class StatusBehavior extends Behavior
{
	public function initialize(array $config)
	{
	    // Some initialization code here
	}

	protected $_defaultConfig = [
        'field' => 'status',
        'status' => 'status',
    ];

    public function status(Entity $entity)
    {
        $config = $this->config();        
        $value = $entity->get($config['field']);
        (int) $roleId = $_SESSION['Auth']['User']['role_id'];
        if ($roleId === 1 || $roleId === 2) {
            $status = 1;
        } else {
            $status = 0;
        }
        $entity->set($config['status'], $status);
    }

    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $this->status($entity);
    }

}