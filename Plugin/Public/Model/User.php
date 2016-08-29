<?php
App::uses('PublicAppModel', 'Public.Model');

class User extends PublicAppModel
{
    public $name = 'User';
    public $useTable = 'users';
    public $actsAs = array('Acl' => array('type' => 'requester'), 'Containable');
    
    public $belongsTo = array(
        'Group' => array(
            'className' => 'Public.Group',
            'foreignKey' => 'group_id'
        )
    );
    
    public $validate = array(
        'group_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Model.User.validate.group_id.required',
                'allowEmpty' => false,
                'required' => true,
                'on' => 'create'
            ),
            'groupExist' => array(
                'rule' => 'groupExist',
                'message' => 'Model.User.validate.group_id.groupExist',
                'allowEmpty' => false
            )
        ),
        'name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Model.User.validate.name.required',
                'allowEmpty' => false,
                'required' => true,
                'on' => 'create'
            ),
            'minLength' => array(
                'rule' => array('minLength', 3),
                'message' => 'Model.User.validate.name.minLength',
                'allowEmpty' => false
            )
        ),
        'email' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Model.User.validate.email.required',
                'allowEmpty' => false,
                'required' => true,
                'on' => 'create'
            ),
            'pattern' => array(
                'rule' => array('email', false),
                'message' => 'Model.User.validate.email.pattern',
                'allowEmpty' => false
            ),
            'unique' => array(
                'rule' => 'fieldContentUnique',
                'message' => 'Model.User.validate.email.unique',
                'allowEmpty' => false
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Model.User.validate.password.required',
                'allowEmpty' => false,
                'required' => true,
                'on' => 'create'
            ),
            'minLength' => array(
                'rule' => array('minLength', 6),
                'message' => 'Model.User.validate.password.minLength',
                'allowEmpty' => false
            )
        )
    );
    
    public function parentNode()
    {
        $groupId = null;
        
        if (!empty($this->data[$this->alias]['group_id']))
        {
            $groupId = $this->data[$this->alias]['group_id'];
        }
        else if (!empty($this->data[$this->alias]['id']))
        {
            $this->id = $this->data[$this->alias]['id'];
            $groupId = $this->field('group_id');
        }
        else if (!empty($this->id))
        {
            $groupId = $this->field('group_id');
        }
        
        if (is_numeric($groupId))
        {
            return $this->Group->findById($groupId);
        }
        
        return null;
    }
    
    public function beforeValidate($options = array())
    {
        if (!isset($this->data[$this->alias]['group_id']))
        {
            $defaultGroup = $this->Group->find('first',
                array(
                    'Group.is_default' => true,
                    'NOT' => array(
                        'Group.status' => array('disabled', 'deleted')
                    )
                )
            );
            
            if (!empty($defaultGroup))
            {
                $this->data[$this->alias]['group_id'] = $defaultGroup['Group']['id'];
            }
        }
        
        return parent::beforeValidate($options);
    }
    
    public function beforeSave($options = array())
    {
        if (!empty($this->data[$this->alias]['password']))
        {
            $this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password']);
        }
        
        return parent::beforeSave($options);
    }
    
    public function groupExist($check)
    {
        $group = $this->Group->find('count',
            array(
                'conditions' => array(
                    'Group.id' => $check['group_id'],
                    'NOT' => array(
                        'Group.status' => array('deleted')
                    )
                )
            )
        );
        
        return $group > 0;
    }
    
    public function generateRandomPassword($length = 6)
    {
        return $this->generateRandomString($length);
    }
}
