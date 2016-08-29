<?php $this->assign('title', __d('public', 'View.Users.add.page_title')); ?>
<?php echo $this->Form->create('User', array('class' => 'form-signin', 'role' => 'form', 'autocomplete' => 'off')); ?>
    <h2><?php echo __d('public', 'View.Users.add.page_title'); ?></h2>
    <?php echo $this->Session->flash(); ?>
    <fieldset>
        <?php echo $this->Form->input('User.name', array('autofocus' => true, 'type' => 'text', 'placeholder' => __d('public', 'View.Users.add.form.user.name.placeholder'))); ?>
        <?php echo $this->Form->input('User.email', array('type' => 'email', 'placeholder' => __d('public', 'View.Users.add.form.user.email.placeholder'))); ?>
        <?php echo $this->Form->input('User.password', array('type' => 'password', 'placeholder' => __d('public', 'View.Users.add.form.user.password.placeholder'))); ?>        
    </fieldset>
    <?php echo $this->Form->button(__d('public', 'View.Users.add.form.submit'), array('class' => 'btn btn-lg btn-primary btn-block')); ?><br />
    <div class="pull-left"><a href="<?php echo Router::url(array('controller' => 'auth', 'action' => 'login')); ?>"><span class="glyphicon glyphicon-user"></span> <?php echo __d('public', 'View.Users.add.login'); ?></a></div>
<?php echo $this->Form->end(); ?>