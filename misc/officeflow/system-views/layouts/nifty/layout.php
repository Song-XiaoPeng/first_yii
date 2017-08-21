<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo Misc::get('common.schema') . Misc::get('common.cdndomain') . '/' . $this->get('favicon', 'favicon.ico'); ?>" type="image/x-icon" />
    <title><?php echo $this->get('title', '用户中心');?></title>
    <?php $this->block('cssFile');?>
    <?php $this->block('cssText');?>
    <?php $this->block('jsHeaderFile');?>
    <?php $this->block('jsHeaderText');?>
    <!--upload, datepicker, ueditor, baidutemplate, jquery-ui, -->
</head>
<body class="<?php echo $this->get('bodyinfo');?>">
<?php $this->beginBody() ?>
<div id="container" class="effect aside-float aside-bright mainnav-lg">
    <header id="navbar">
        <?php $this->block('header');?>
    </header>
    <div class="boxed">
        <div id="content-container">
            <?php $this->block('pageTitle');?>
            <div id="page-content">
                <?php echo $content;?>
            </div>
        </div>
        <?php echo $this->block('left');?>
    </div>
    <?php echo $this->block('footer');?>
</div>
<?php $this->block('modal');?>
<script>
var YII_DEBUG = <?php echo YII_DEBUG ? 'true' : 'false';?>;
</script>
<?php $this->block('jsFile');?>
<?php $this->block('jsText');?>
<script>
var logoutUtil = {
    logout: function() {
        $.ajax({
            type: 'post',
            url:  '<?php echo Yii::$app->urlManager->createUrl(['/site/login/logout']);?>',
            dateType: 'json',
            success: function(){
                window.location.reload();
            },
            error: function(){
                
            }
        });
    }
};
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
