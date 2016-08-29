<?php
Router::parseExtensions('json');

Router::connect('/authorized/**', array('plugin' => 'public', 'controller' => 'auth', 'action' => 'authorized'));
Router::connect('/register', array('plugin' => 'public', 'controller' => 'users', 'action' => 'add'));
Router::connect('/login', array('plugin' => 'public', 'controller' => 'auth', 'action' => 'login'));
Router::connect('/logout', array('plugin' => 'public', 'controller' => 'auth', 'action' => 'logout'));
Router::connect('/password-recovery', array('plugin' => 'public', 'controller' => 'auth', 'action' => 'password_recovery'));
Router::connect('/password-recovery/:passwordRecoveryUID', array('plugin' => 'public', 'controller' => 'auth', 'action' => 'password_change'), array('pass' => array('passwordRecoveryUID')));
