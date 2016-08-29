<?php $this->assign('title', __d('public', 'View.Auth.password_recovery.page_title')); ?>
<?php echo $this->Form->create('User', array('class' => 'form-signin', 'role' => 'form', 'autocomplete' => 'off')); ?>
    <h2><?php echo __d('public', 'View.Auth.password_recovery.page_title'); ?></h2>
    <?php echo $this->Session->flash(); ?>
    <fieldset>
        <?php echo $this->Form->input('User.email', array('autofocus' => true, 'required' => true, 'type' => 'text', 'placeholder' => __d('public', 'View.Auth.password_recovery.form.user.email.placeholder'))); ?>
    </fieldset>
    <?php echo $this->Form->button(__d('public', 'View.Auth.password_recovery.form.submit'), array('class' => 'btn btn-lg btn-primary btn-block')); ?><br />
    <div class="pull-left"><a href="<?php echo Router::url(array('controller' => 'auth', 'action' => 'login')); ?>"><span class="glyphicon glyphicon-user"></span> <?php echo __d('public', 'View.Auth.password_recovery.login'); ?></a></div>
<?php echo $this->Form->end(); ?>