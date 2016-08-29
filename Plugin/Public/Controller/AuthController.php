<?php
App::uses('PublicAppController', 'Public.Controller');

class AuthException extends Exception {}
class AuthController extends PublicAppController 
{
    public $name = 'Auth';
    public $uses = array('Public.User', 'Public.SystemEvent');
    public $allowedActions = array('login', 'logout', 'password_recovery', 'password_change', 'authorized');
    public $authRedirectActions = array('login', 'password_recovery', 'password_change');
    
    public function login()
    {        
        try
        {
            if (!$this->request->is('post'))
            {
                throw new Exception('no data submitted');
            }
            
            if (empty($this->request->data['User']['email']) || empty($this->request->data['User']['password']))
            {
                throw new AuthException('invalid credentials');
            }
            
            $user = $this->User->find('first',
                array(
                    'conditions' => array(
                        'User.email LIKE' => $this->request->data['User']['email'],
                        'User.password' => Security::hash($this->request->data['User']['password']),
                        'NOT' => array(
                            'User.status' => array('disabled', 'deleted'),
                            'Group.status' => array('disabled', 'deleted')
                        )
                    )
                )
            );
            
            if (empty($user))
            {
                throw new AuthException('user not found');
            }
            
            $loginSystemEvent = $this->logSystemEvent('login', $user['User']['id']);
            
            $this->Auth->login(array('id' => $user['User']['id']));
            $this->Cookie->write('Auth.SessionKey', $loginSystemEvent['SystemEvent']['hash']);
            
            $this->redirectAuthUser($user);
        }
        catch (AuthException $e)
        {
            unset($this->request->data['User']['password']);
            
            $this->Session->setFlash(__d('public', 'Controller.Auth.login.flash.error'), 'Public.flash' . DS . 'error');
            
            $this->User->invalidate('email', __d('public', 'Controller.Auth.login.invalidate.email'));
            $this->User->invalidate('password', __d('public', 'Controller.Auth.login.invalidate.password'));
        }
        catch (Exception $e)
        {
        }
    }
    
    public function logout()
    {
        try
        {
            if (empty($this->authUser))
            {
                throw new Exception('user not authenticated');
            }
            
            $loginSystemEvent = $this->SystemEvent->find('first',
                array(
                    'conditions' => array(
                        'SystemEvent.user_id' => $this->authUser['User']['id'],
                        'SystemEvent.event' => 'login',
                        'SystemEvent.hash' => $this->Cookie->read('Auth.SessionKey'),
                        'SystemEvent.status <>' => array('disabled', 'deleted')
                    )
                )
            );
            
            if (empty($loginSystemEvent))
            {
                throw new Exception('invalid authentication key');
            }
            
            $this->SystemEvent->id = $loginSystemEvent['SystemEvent']['id'];
            $this->SystemEvent->saveField('status', 'disabled');
            
            $this->logSystemEvent('logout', $this->authUser['User']['id']);
        }
        catch (Exception $e)
        {
        }
        
        $this->Auth->logout();
        $this->Cookie->delete('Auth.SessionKey');
        
        $this->redirect('/');
    }
    
    public function password_recovery()
    {
        try
        {
            if (!$this->request->is('post'))
            {
                throw new Exception('no data submitted');
            }
            
            if (empty($this->request->data['User']['email']))
            {
                throw new AuthException('invalid email');
            }
            
            $user = $this->User->find('first',
                array(
                    'conditions' => array(
                        'User.email LIKE' => $this->request->data['User']['email'],
                        'NOT' => array(
                            'User.status' => array('disabled', 'deleted')
                        )
                    )
                )
            );
            
            if (empty($user))
            {
                throw new AuthException('user not found');
            }
            
            $this->logSystemEvent('password recovery', $user['User']['id']);
            $this->Session->setFlash(__d('public', 'Controller.Auth.password_recovery.flash.success'), 'Public.flash' . DS . 'success');
            
            /**
                Password recovery email logic
            **/
            
            $this->redirect(array('action' => 'login'));
        }
        catch (AuthException $e)
        {
            $this->Session->setFlash(__d('public', 'Controller.Auth.password_recovery.flash.success'), 'Public.flash' . DS . 'success');
            
            $this->redirect(array('action' => 'login'));
        }
        catch (Exception $e)
        {
        }
    }
    
    public function password_change($passwordRecoveryUID)
    {
        try
        {
            $passwordRecoveryEvent = $this->SystemEvent->find('first',
                array(
                    'conditions' => array(
                        'SystemEvent.event' => 'password recovery',
                        'SystemEvent.hash' => $passwordRecoveryUID,
                        'NOT' => array(
                            'SystemEvent.status' => array('disabled', 'deleted')
                        )
                    )
                )
            );
            
            if (empty($passwordRecoveryEvent))
            {
                throw new AuthException('invalid password recovery');
            }
            
            if (!$this->request->is('post'))
            {
                throw new Exception('no data submitted');
            }
            
            $dataToSave = array(
                'User' => array(
                    'id' => $passwordRecoveryEvent['SystemEvent']['user_id'],
                    'password' => $this->request->data['User']['password']
                )
            );
            
            if (!$this->User->save($dataToSave))
            {
                unset($this->request->data['User']);
                
                $this->Session->setFlash(__d('public', 'Controller.Auth.password_change.flash.error'), 'Public.flash' . DS . 'error');
                
                throw new Exception('could not change the password');
            }
            
            $this->SystemEvent->id = $passwordRecoveryEvent['SystemEvent']['id'];
            $this->SystemEvent->saveField('status', 'disabled');
            
            $this->logSystemEvent('password change', $passwordRecoveryEvent['SystemEvent']['user_id'], 'User', $passwordRecoveryEvent['SystemEvent']['user_id']);
            $this->Session->setFlash(__d('public', 'Controller.Auth.password_change.flash.success'), 'Public.flash' . DS . 'success');
            
            $this->redirect(array('action' => 'login'));
        }
        catch (AuthException $e)
        {
            $this->redirect(array('action' => 'password_recovery'));
        }
        catch (Exception $e)
        {
        }
    }
    
    public function authorized($aco)
    {
        try
        {
            if (empty($this->authUser))
            {
                throw new Exception('user not authenticated');
            }
            
            if (!$this->Acl->check(array('User' => array('id' => $this->authUser['User']['id'])), $aco))
            {
                throw new Exception('permission denied');
            }
            
            return true;
        }
        catch (Exception $e)
        {
        }
        
        return false;
    }
}
