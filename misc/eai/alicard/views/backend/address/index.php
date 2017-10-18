<?php
echo $this->set('title','领卡地址');
$url_pre = Yii::$app->controller->module->appkey.'/'.Yii::$app->controller->id;
?>

<?php $this->beginBlock('cssText','append')?>
<style>
.get_card.panel-body {
    padding: 0;
}

.get_card .tab-base .nav-tabs {
    background: #ecf0f5
}

.get_card .tab-base .tab-content .tab-pane {
    min-height: 400px;
    display: none;
}

.get_card .tab-base .tab-content .tab-pane.active {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
}

.get_card .tab-base .tab-content .tab-pane img {
    width: 170px;
    height: 170px;
    display: block;
    margin: 5px 0;
}

.get_card .tab-base .tab-content .tab-pane button {
    width: 150px;
    margin: 10px auto;
}
</style>
<?php $this->endBlock()?>
<div class="panel">
    <div class="panel-heading top">
        <h3 class="panel-title">领卡地址</h3>
    </div>
    <div class="panel-body get_card">
        <div class="row">
            <div class="col-sm-12">
                <div class="tab-base">
                    <ul class="nav nav-tabs" style="">
                        <li class="active">
                            <a href="javascript:void(0)">统一入口</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">正式卡入口</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">临时卡入口</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <p>打开手机支付宝扫描下方二维码1</p>
                            <img src="/backend/alicard/scan_b.png" alt="">
                            <button class="btn btn-primary">点击复制链接</button>
                        </div>
                        <div class="tab-pane">
                            <p>打开手机支付宝扫描下方二维码2</p>
                            <img src="/backend/alicard/scan_b.png" alt="">
                            <button class="btn btn-primary">点击复制链接</button>
                        </div>
                        <div class="tab-pane">
                            <p>打开手机支付宝扫描下方二维码3</p>
                            <img src="/backend/alicard/scan_b.png" alt="">
                            <button class="btn btn-primary">点击复制链接</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('jsText','append')?>
<script type="text/javascript">
 $('.nav-tabs>li').click(function() {
    var ind = $(this).index();
    $(this).addClass('active').siblings().removeClass('active');
    $('.tab-content>div').removeClass('active').eq(ind).addClass('active');
})
</script>
<?php $this->endBlock()?>
