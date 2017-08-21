<?php
$this->set('title', '应用');
?>
<div class="panel jsVueCon">
    <div class="panel-heading top">
        <a class="back" href="javascript:history.go(-1)"></a>
        <h3 class="panel-title">添加/编辑</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal jsSaveForm" onsubmit="return false;">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="demo-hor-inputemail"><span class="text-danger">* </span>应用名称:</label>
                <div class="col-sm-9">
                    <input type="text" name="name" value="<?php echo isset($data['name'])?$data['name']:'' ;?>" placeholder="应用名称" id="demo-hor-inputemail" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="demo-hor-inputpass"><span class="text-danger">* </span>是否为热门应用:</label>
                <div class="col-sm-9">
                    <input type="radio" checked="checked" value='yes' name="is_hot" <?php echo isset($data['is_hot'])&&$data['is_hot']=='yes'?'checked':'' ;?>>是&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="is_hot" value="no" <?php echo isset($data['is_hot'])&&$data['is_hot']=='no'?'checked':'' ;?>>否
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="demo-hor-inputpass"><span class="text-danger">* </span>是否为猜猜你想用的应用:</label>
                <div class="col-sm-9">
                    <input type="radio" checked="checked" value='yes' name="is_want" <?php echo isset($data['is_want'])&&$data['is_want']=='yes'?'checked':'' ;?>>是&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="is_want" value="no" <?php echo isset($data['is_want'])&&$data['is_want']=='no'?'checked':'' ;?>>否
                </div>
            </div>
            <div class="form-group savegroup demo-nifty-btn">
                <button type="submit" class="btn btn-sure" @click="save()">保存</button>
                <button type="reset" class="btn btn-cancel" onclick="Javascript:window.history.go(-1)">取消</button>
            </div>
            <input type="hidden" name="id" value="<?php echo isset($data['id'])?$data['id']:0 ;?>">
        </form>
    </div>
</div>
<?php $this->beginBlock("jsText");?>
<script type="text/javascript">
    var vm = new Vue({
        el: '.jsVueCon',
        data: {
            ajaxLock: false,
            saveUrl: "<?php echo Yii::$app->urlManager->createUrl(['/system/hot-want-apps/save']);?>",
        },
        methods: {
            save: function() {
                var ckun = this.checkEmpty("input[name=name]", "应用名称不能为空");
                if(!ckun){
                    return false;
                }
                if(this.ajaxLock)return;
                this.ajaxLock = true;
                $.ajax({
                    type: 'POST',
                    url: this.saveUrl,
                    dataType: 'json',
                    data: $(".jsSaveForm").serialize(),
                    success: function(resp) {
                        if(resp.e == 0){
                            nsalert("操作成功");
                            setTimeout(function() {
                                window.location.href = "<?php echo Yii::$app->urlManager->createUrl(['/system/hot-want-apps/index']);?>";
                            }, 1500);
                        }else{
                            nsalert(resp.m, "fail");
                            vm.ajaxLock = false;
                        }
                    },
                    error: function() {
                        nsalert("系统繁忙，请稍后再试！", "fail");
                        vm.ajaxLock = false;
                    },
                    complete: function() {

                    }
                });
            },
            checkEmpty: function(obj, text) {
                if($.trim($(obj).val()) == "") {
                    this.setWrong(obj, text);
                    return false;
                }else{
                    this.setRight(obj);
                    return true;
                }
            },
            setWrong: function(obj, text) {
                var helpBlock = $(obj).closest(".form-group").find(".help-block");
                if(helpBlock.length <= 0) {
                    var text = '<small class="help-block">'+text+'</small>';
                    $(obj).after(text);
                }else{
                    helpBlock.text(text);
                }
                $(obj).closest(".form-group").addClass("has-error");
            },
            setRight: function(obj) {
                $(obj).closest(".form-group").find(".help-block").remove();
                $(obj).closest(".form-group").removeClass("has-error");
            }
        }
    });
</script>
<?php $this->endBlock();?>


