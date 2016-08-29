<?php
App::uses('PublicAppController', 'Public.Controller');

class UsersController extends PublicAppController 
{
    public $name = 'Users';
    public $uses = array('Public.User', 'Public.SystemEvent');
    public $allowedActions = array('add');
    public $authRedirectActions = array('add');
    
    public function add()
    {
        try
        {
            if (!$this->request->is('post'))
            {
                throw new Exception('no data submitted');
            }
            
            $dataToSave = array(
                'User' => array(
                    'name' => $this->request->data['User']['name'],
                    'email' => $this->request->data['User']['email'],
                    'password' => $this->request->data['User']['password']
                )
            );
            
            if (!$this->User->save($dataToSave))
            {
                unset($this->request->data['User']['password']);
                
                $this->Session->setFlash(__d('public', 'Controller.Users.add.flash.error'), 'Public.flash' . DS . 'error');
                
                throw new Exception('could not register the user');
            }
            
            $this->logSystemEvent('register', $this->User->getLastInsertId(), 'User', $this->User->getLastInsertId());
            $this->Session->setFlash(__d('public', 'Controller.Users.add.flash.success'), 'Public.flash' . DS . 'success');
            
            $this->redirect(array('controller' => 'auth', 'action' => 'login'));
        }
        catch (Exception $e)
        {
        }
    }
}
