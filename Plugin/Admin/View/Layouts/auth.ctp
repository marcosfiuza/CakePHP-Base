<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <?php echo $this->Html->meta('icon'); ?>
    <?php echo $this->fetch('meta'); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo __d('admin', 'View.Layouts.auth.head.title'); ?>: <?php echo $this->fetch('title'); ?></title>
    <?php echo $this->Html->css('https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css'); ?>
    <?php echo $this->Html->css('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'); ?>
    <?php echo $this->Html->css('Admin./css/main.css'); ?>
    <?php echo $this->fetch('css'); ?>
</head>
<body class="layout-auth">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                    <span class="sr-only"><?php echo __d('admin', 'View.Layouts.auth.nav.toggle'); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php echo $this->Html->link(__d('admin', 'View.Layouts.auth.title'), array('plugin' => 'admin', 'controller' => 'dashboard', 'action' => 'index'), array('class' => 'navbar-brand')); ?>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-fw hidden-xs"></i> <?php echo $authUser['User']['name']; ?> <i class="fa fa-caret-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo Router::url(array('plugin' => 'public', 'controller' => 'auth', 'action' => 'logout')); ?>"><?php echo __d('admin', 'View.Layouts.auth.nav.logout'); ?></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="page-header">
            <?php echo $this->Html->getCrumbList(array('class' => 'breadcrumb')); ?>
            <?php if (!isset($showPageTitle) || (isset($showPageTitle) && $showPageTitle === true)) : ?>
                <h1><?php echo $this->fetch('title'); ?></h1>
            <?php endif; ?>
            <?php echo $this->Session->flash(); ?>
        </div>
        <?php echo $this->fetch('content'); ?>
    </div>
    <?php echo $this->Html->script('https://code.jquery.com/jquery-1.12.4.min.js'); ?>
    <?php echo $this->Html->script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'); ?>
    <?php echo $this->fetch('script') ?>
</body>
</html>