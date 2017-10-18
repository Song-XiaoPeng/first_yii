<?php
$this->set('title', '电子校园卡');
$this->addCssFile(['/wap/alicard/css/base.css']);
$config = Yii::$app->config->item("field.columns");
?>
<div class="wrap">
    <div class="top">
        <div class="pic">
            <img src="<?php echo $data['background_url'] ?$this->imghost.'/'.$data['background_url']: '/wap/alicard/img/card.png';?>" alt="">
        </div>
        <div class="school">
            <div class="school_top">
                <img src="<?php echo $data['logo_url'] ?$this->imghost.'/'.$data['logo_url']: '/wap/alicard/img/school.png';?>" alt="">
                <p><?php echo $data['title'] ?: '校园卡';?></p>
            </div>
            <div class="school_main">
                <div class="left">
                    <?php foreach($config as $key => $v):
                        if(!in_array($key, $data['columns']))continue;
                        ?>
                    <p><span><?php echo $v;?></span><span>&nbsp;</span></p>
                    <?php endforeach;?>
                </div>
                <div class="right">
                    <div class="pic">
                        <?php if(in_array('avatar', $data['columns'])):?>
                        <img src="/wap/alicard/img/school1.png" alt="">
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="bg">校园卡说明</div>
        <div class="notice">
            <?php echo empty($data['description']) ? '' : preg_replace("@\s@", "&nbsp;",preg_replace("@\n@", "<br/>",$data['description']));?>
        </div>
        <form action="javascript:void(0)">
            <?php if(!$hasCard):?>
                <input type="text" placeholder="请输入您的账号" id="username">
                <input type="password" placeholder="请输入您的密码" id="password">
                <div class="btn">
                    <a onclick="vmUtil.Commit();" class="jsGetBtn">领卡</a>
                </div>
            <?php else:?>
                <div class="btn dis">
                    <a>已领卡</a>
                </div>
            <?php endif;?>
        </form>
    </div>
</div>
<script>
    var vmUtil = {
        lock: false,
        <?php if(!$hasCard):?>
        Commit: function () {
            var username = $('#username').val();
            var password = $('#password').val();
            if(username == '' || username == undefined || password == '' || password == undefined){
                return false;
            }
            if(this.lock){
                return false;
            }
            this.lock = true;
            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::$app->urlManager->createUrl(['/alicard/wap/get/commit']); ?>',
                dataType: 'json',
                data: {'username':username,'password':password},
                success: function (resp) {
                    if (resp.e == '0') {
                        $('#username').remove();
                        $('#password').remove();
                        $('.jsGetBtn').unbind("onclick").html("已领卡").parent().addClass("dis");
                        wapalert('领卡成功');
                    } else if(resp.e == '50000') {
                        //window.location.reload();
                    } else{
                        vmUtil.lock = false;
                        wapalert(resp.m);
                    }
                },
                error: function () {
                    vmUtil.lock = false;
                    wapalert("领卡失败，请稍后再试！");
                }
            });
        },
        <?php endif;?>
    };
</script>