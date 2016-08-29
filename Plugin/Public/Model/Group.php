<?php
App::uses('PublicAppModel', 'Public.Model');

class Group extends PublicAppModel
{
    public $name = 'Group';
    public $useTable = 'groups';
    public $actsAs = array('Acl' => array('type' => 'requester'), 'Containable', 'Tree');
    
    public $hasMany = array(
        'User' => array(
            'className' => 'Public.User',
            'foreignKey' => 'group_id'
        )
    );
    
    public function parentNode()
    {
        $parentId = null;
        
        if (!empty($this->data[$this->alias]['parent_id']))
        {
            $parentId = $this->data[$this->alias]['parent_id'];
        }
        else if (!empty($this->id))
        {
            $parentId = $this->field('parent_id');
        }
        
        if (is_numeric($parentId))
        {
            return $this->findById($parentId);
        }
        
        return null;
    }
}
