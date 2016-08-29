<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <?php echo $this->Html->meta('icon'); ?>
    <?php echo $this->fetch('meta'); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo __d('admin', 'View.Layouts.default.head.title'); ?>: <?php echo $this->fetch('title'); ?></title>
    <?php echo $this->Html->css('https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css'); ?>
    <?php echo $this->Html->css('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'); ?>
    <?php echo $this->Html->css('Public./css/main.css'); ?>
    <?php echo $this->fetch('css'); ?>
</head>
<body class="layout-default">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->fetch('content'); ?>
            </div>
        </div>
    </div>
    <?php echo $this->Html->script('https://code.jquery.com/jquery-1.12.4.min.js'); ?>
    <?php echo $this->Html->script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'); ?>
    <?php echo $this->fetch('script') ?>
</body>
</html>