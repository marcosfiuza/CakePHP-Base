<?php $this->assign('title', __d('public', 'View.Auth.login.page_title')); ?>
<?php echo $this->Form->create('User', array('class' => 'form-signin', 'role' => 'form', 'autocomplete' => 'off')); ?>
    <h2><?php echo __d('public', 'View.Auth.login.page_title'); ?></h2>
    <?php echo $this->Session->flash(); ?>
    <fieldset>
        <?php echo $this->Form->input('User.email', array('autofocus' => true, 'required' => true, 'type' => 'text', 'placeholder' => __d('public', 'View.Auth.login.form.user.email.placeholder'))); ?>
        <?php echo $this->Form->input('User.password', array('required' => true, 'type' => 'password', 'placeholder' => __d('public', 'View.Auth.login.form.user.password.placeholder'))); ?>
    </fieldset>
    <?php echo $this->Form->button(__d('public', 'View.Auth.login.form.submit'), array('class' => 'btn btn-lg btn-primary btn-block')); ?><br />
    <div class="pull-left"><a href="<?php echo Router::url(array('controller' => 'auth', 'action' => 'password_recovery')); ?>"><span class="glyphicon glyphicon-question-sign"></span> <?php echo __d('public', 'View.Auth.login.password_recovery'); ?></a></div>
    <div class="pull-right"><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'add')); ?>"><span class="glyphicon glyphicon-pencil"></span> <?php echo __d('public', 'View.Auth.login.register'); ?></a></div>
<?php echo $this->Form->end(); ?>