<?php
App::uses('PublicAppModel', 'Public.Model');

class SystemEvent extends PublicAppModel
{
    public $name = 'SystemEvent';
    public $useTable = 'system_events';
    public $actsAs = array('Containable');
    
    public $belongsTo = array(
        'User' => array(
            'className' => 'Public.User',
            'foreignKey' => 'user_id'
        )
    );
}
