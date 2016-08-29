<?php
App::uses('String', 'Utility');
App::uses('CakeTime', 'Utility');
App::uses('AppController', 'Controller');

class PublicAppController extends AppController
{
    public $helpers = array('Number', 'Form' => array('className' => 'Public.BootstrapForm'), 'Text' => array('className' => 'Public.CustomText'), 'Time' => array('className' => 'Public.CustomTime'));
    public $components = array('Acl', 'Auth', 'Cookie', 'RequestHandler', 'Session');
    public $uses = array('Public.User', 'Public.SystemEvent');
    public $allowedActions = array();
    public $authUser = array();
    public $authRedirectActions = array();
    public $selectOptions = array();
    public $layoutDefault = 'Public.default';
    public $layoutAuth = 'Public.default';
    public $timezone = 'UTC';
    
    public function beforeFilter()
    {
        $this->timezone = Configure::read('Config.timezone');
        $this->Cookie->name = Configure::read('Cookie.name');
		
		$this->Auth->userModel = 'User';
		$this->Auth->authorize = array('Actions' => array('actionPath' => 'controllers'));
		$this->Auth->loginAction = Router::url(array('plugin' => 'public', 'controller' => 'auth', 'action' => 'login', '?' => array('redirect' => Router::url(null, true))), true);
		$this->Auth->authError = __d('public', 'Controller.App.beforeFilter.permisson_denied');
		$this->Auth->allowedActions = $this->allowedActions;
		
		if ($this->Auth->loggedIn())
		{
            try
            {
                $authUserId = $this->Auth->user('id');
                
                $authUser = $this->User->find('first',
                    array(
                        'conditions' => array(
                            'User.id' => $authUserId,
                            'NOT' => array(
                                'User.status' => array('deleted')
                            )
                        )
                    )
                );
                
                if (empty($authUser))
                {
                    throw new Exception('user not found');
                }
                
                $loginSystemEvent = $this->SystemEvent->find('first',
                    array(
                        'conditions' => array(
                            'SystemEvent.user_id' => $authUserId,
                            'SystemEvent.event' => 'login',
                            'SystemEvent.hash' => $this->Cookie->read('Auth.SessionKey'),
                            
                        )
                    )
                );
                
                if (empty($loginSystemEvent))
                {
                    throw new Exception('invalid authentication key');
                }
                
                $this->authUser = $authUser;
                $this->SystemEvent->id = $loginSystemEvent['SystemEvent']['id'];
                $this->SystemEvent->save('updated_at', date('Y-m-d H:i:s'));
            }
            catch (Exception $e)
            {
                $this->Auth->logout();
                $this->Cookie->delete('Auth.SessionKey');
            }
		}
        else
        {
            try
            {
                $loginSystemEvent = $this->SystemEvent->find('first',
                    array(
                        'conditions' => array(
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
                
                $authUser = $this->User->find('first',
                    array(
                        'conditions' => array(
                            'User.id' => $loginSystemEvent['SystemEvent']['user_id'],
                            'NOT' => array(
                                'User.status' => array('deleted')
                            )
                        )
                    )
                );
                
                if (empty($authUser))
                {
                    throw new Exception('user not found');
                }
                
                if (!$this->Auth->login(array('id' => $authUser['User']['id'])))
                {
                    throw new Exception('authentication failed');
                }
                
                $this->authUser = $authUser;
                $this->SystemEvent->id = $loginSystemEvent['SystemEvent']['id'];
                $this->SystemEvent->save('updated_at', date('Y-m-d H:i:s'));
            }
            catch (Exception $e)
            {
                $this->Cookie->delete('Auth.SessionKey');
            }
        }
		
        $this->layout = $this->layoutDefault;
        
		if (!empty($this->authUser))
		{
            $this->layout = $this->layoutAuth;
		}
        
        if (!empty($this->authUser) && in_array($this->request->params['action'], $this->authRedirectActions))
        {
            $this->redirectAuthUser();
        }       
        
		parent::beforeFilter();
	}
    
    public function beforeRender()
    {
        $this->set('selectOptions', $this->selectOptions);
        $this->set('timezone', $this->timezone);
        $this->set('authUser', $this->authUser);
        
        parent::beforeRender();
    }
    
    protected function redirectAuthUser($authUser = null)
    {
        try
        {
            if (empty($this->authUser))
            {
                throw new Exception('user not authenticated');
            }
            
            /**
                Redirect logic
            **/
        }
        catch (Exception $e)
        {
        }
        
        $this->redirect('/');
    }
    
    protected function logSystemEvent($event, $authUserId = null, $model = null, $foreignKey = null, $data = array(), $status = '')
    {
        if (is_nan($authUserId))
        {
            $authUserId = $this->Auth->user('id');
        
            if (is_nan($authUserId) && !empty($this->authUser))
            {
                $authUserId = $this->authUser['User']['id'];
            }
        }
        
        $dataToSave = array(
            'SystemEvent' => array(
                'user_id' => $authUserId,
                'model' => $model,
                'foreign_key' => $foreignKey,
                'event' => $event,
                'data' => json_encode($data),
                'status' => $status
            )
        );
        
        $this->SystemEvent->create();
        
        if ($this->SystemEvent->save($dataToSave))
        {
            return $this->SystemEvent->find('first',
                array(
                    'conditions' => array(
                        'SystemEvent.id' => $this->SystemEvent->getLastInsertId()
                    )
                )
            );
        }
        
        return false;
    }
    
    protected function dateInputToServer($date, $timezone = null, $format = null, $serverFormat = 'Y-m-d')
    {
        if (is_null($format))
        {
            $format = __d('public', 'Settings.backend.date.input.format');
        }
        
        return $this->datetimeInputToServer($date, $timezone, $format, $serverFormat);
    }
    
    protected function datetimeInputToServer($datetime, $timezone = null, $format = null, $serverFormat = 'Y-m-d H:i:s')
    {
        if (is_null($timezone))
        {
            $timezone = $this->timezone;
        }
        
        if (is_null($format))
        {
            $format = __d('public', 'Settings.backend.datetime.input.format');
        }
        
        $datetime = DateTime::createFromFormat($format, $datetime);
        
        if (!$datetime)
        {
            return '';
        }
        
        return CakeTime::toServer($datetime->format('Y-m-d H:i:s'), $timezone, $serverFormat);
    }
    
    protected function dateServerToInput($date, $timezone = null, $format = null)
    {
        if (is_null($format))
        {
            $format = __d('public', 'Settings.backend.date.input.format');
        }
        
        return $this->datetimeServerToInput($date, $timezone, $format);
    }
    
    protected function datetimeServerToInput($datetime, $timezone = null, $format = null)
    {
        if (is_null($timezone))
        {
            $timezone = $this->timezone;
        }
        
        if (is_null($format))
        {
            $format = __d('public', 'Settings.backend.datetime.input.format');
        }
        
        $datetime = new DateTime($datetime);
        $datetime->setTimezone(new DateTimeZone($timezone));
        
        if (!$datetime)
        {
            return '';
        }
        
        return $datetime->format($format);
    }
}
