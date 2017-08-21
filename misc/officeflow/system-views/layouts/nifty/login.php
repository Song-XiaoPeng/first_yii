<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo Misc::get('common.cdndomain').$this->get('favicon', 'favicon.ico'); ?>" type="image/x-icon" />
    <title><?php echo $this->get('title', '登录');?></title>
    <link href='/framework/googlefonts/OpenSans.css' rel='stylesheet' type='text/css'>
    <link href="/framework/nifty/css/bootstrap.min.css" rel="stylesheet">
    <link href="/framework/nifty/css/nifty.min.css" rel="stylesheet">
    <link href="/framework/nifty/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="/framework/nifty/css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="/framework/nifty/plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link href="/framework/nifty/plugins/pace/pace.min.css" rel="stylesheet">
    <?php $this->block('cssFile');?>
    <?php $this->block('cssText');?>
    <script src="/framework/nifty/plugins/pace/pace.min.js"></script>
    <script src="/framework/nifty/js/jquery-2.2.4.min.js"></script>
    <script src="/framework/nifty/js/bootstrap.min.js"></script>
    <script src="/framework/nifty/js/nifty.min.js"></script>
    <script src="/framework/nifty/js/demo/bg-images.js"></script>
    <?php $this->block('jsHeaderFile');?>
    <?php $this->block('jsHeaderText');?>
</head>
<body class="<?php echo $this->get('bodyinfo');?>">
<?php $this->beginBody() ?>
<?php echo $content;?>
<script>
var YII_DEBUG = <?php echo YII_DEBUG ? 'true' : 'false';?>;
</script>
<?php $this->block('jsFile');?>
<?php $this->block('jsText');?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
