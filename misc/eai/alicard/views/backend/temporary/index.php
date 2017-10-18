<?php
echo $this->set('title','临时卡列表');
$url_pre = Yii::$app->controller->module->appkey.'/'.Yii::$app->controller->id;
?>

<?php $this->beginBlock('cssText','append')?>
<style>
.row.mbt10 {
    margin-bottom: 12px !important;
}

.table td button{
    border-radius: 5px;
}

.btns .btn {
    padding: 0 40px;
    line-height: 30px;
    margin: 5px 30px 5px 0;
}

</style>
<?php $this->endBlock()?>
<div class="panel">
    <div class="panel-heading top">
        <h3 class="panel-title">临时卡列表</h3>
    </div>
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="tab-base">
                    <div class="tab-content" style="padding: 15px 20px 45px 20px; min-height: 426px; display: block;">
                        <div class="tab-pane active">
                            <div class="row mbt10">
                                <div class="col-xs-1" style="width: auto;">
                                    <span class="car-line-text" style="line-height: 32px">卡号:&nbsp;</span>
                                </div>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" value="">
                                </div>
                            </div>
                            <div class="row mbt10">
                                <div class="col-xs-1" style="width: auto;">
                                    <span class="car-line-text" style="line-height: 32px">有效期:&nbsp;</span>
                                </div>
                                <div class="col-xs-6">
                                    <div class="input-daterange input-group">
                                        <div class="clear" style="clear: both"></div>
                                        <input type="text" class="form-control" name="start_deal" readonly="" placeholder="开始时间" value="">
                                        <span class="input-group-addon">到</span>
                                        <input type="text" class="form-control" name="end_deal" readonly="" placeholder="结束时间" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row mbt10">
                                <div class="col-xs-1" style="width: auto;">
                                    <span class="car-line-text" style="line-height: 32px">领取日:&nbsp;</span>
                                </div>
                                <div class="col-xs-6">
                                    <div class="input-daterange input-group">
                                        <div class="clear" style="clear: both"></div>
                                        <input type="text" class="form-control" name="start_deal" readonly="" placeholder="领取时间" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row mbt10">
                                <div class="col-md-offset-1 btns">
                                    <button type="button" class="btn btn-primary">搜索</button>
                                    <button type="button" class="btn btn-default">导出</button>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="20%">卡号</th>
                                        <th width="30%">有效期</th>
                                        <th width="30%">领取时间</th>
                                        <th width="20%">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="rowwuchengjun">
                                        <td>wuchengjun</td>
                                        <td>2021-10-12 16:49:34</td>
                                        <td>2017-10-13 16:49:34</td>
                                        <td>
                                            <button type="button" class="btn btn-primary">编辑</button>
                                            <button type="button" class="btn btn-default">删除</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- 分页 -->
                            <div class="pull-right">
                                <ul class="pagination text-nowrap mar-no">
                                    <li class="page-pre disabled"><a href="javascript:;"><i class="demo-psi-arrow-left"></i></a></li>
                                    <li class="page-number active"><span>1</span></li>
                                    <li class="page-next disabled"><a href="javascript:;"><i class="demo-psi-arrow-right"></i></a></li>
                                </ul>
                                <div class="page-info">
                                    <span>共 4 条， 到第 
                                    <form class="input-inline" onsubmit="jumpPage();return false;">
                                        <input class="form-control input-mini input-inline" type="text" id="page-num" value="1"> 页 
                                        <button class="btn btn-default ver-align-top" type="submit">确定</button>
                                    </form>
                                    </span>
                                </div>
                            </div>
                            <script type="text/javascript">
                            function jumpPage() {
                                var toPage = parseInt($.trim($("#page-num").val()));
                                if (isNaN(toPage) || toPage <= 0 || toPage > 1 || toPage == 1) {
                                    $("#page-num").focus();
                                    return;
                                } else {
                                    window.location.href = "/alicard/backend/list?p=" + toPage + "&app=alicard";
                                }
                            }
                            </script>
                            <!-- 分页 -->
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
