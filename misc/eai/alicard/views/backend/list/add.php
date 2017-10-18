<?php
echo $this->set('title','新增临时卡');
$url_pre = Yii::$app->controller->module->appkey.'/'.Yii::$app->controller->id;
?>
<div class="panel">
    <div class="panel-heading top">
        <a href="javascript:history.go(-1);" class="back"></a>
        <h3 class="panel-title"><?php echo !empty($info['id']) ? '修改' : '新增';?></h3>
    </div>
    <form class="form-horizontal" id="lostForm" action="" onsubmit="return addUtil.save();">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-3 control-label"><span class="text-danger">*</span> 临时卡卡号</label>
                <div class="col-md-5">
                    <div class="input-group mar-btm">
                        <span class="input-group-addon text-right"><?php echo $ls_prefix;?></span>
                        <input <?php if(!empty($info['cardid'])) echo "disabled";?> name="cardid" class="form-control" placeholder="请输入临时卡卡号" value="<?php if (!empty($info['cardid'])) echo $info['cardid']; ?>"/>
                    </div>
                    <small class="help-block">领取临时卡时，输入的临时卡卡号请带上前缀 "<?php echo $ls_prefix?>"</small>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label"><span class="text-danger">*</span> 有效时间</label>
                <div class="col-md-5">
                    <input name="time_d" readonly="" class="form-control date-input" value="<?php echo empty($info['valid_date'])? date("Y-m-d") :date('Y-m-d',$info['valid_date']); ?>"/>
                    <input name="time_t" readonly="" class="form-control time-input" value="<?php echo empty($info['valid_date'])? "" :date('H:i',$info['valid_date']); ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label"><span class="text-danger">*</span> 领卡密码</label>
                <div class="col-md-5">
                    <input name="password" type="password" class="form-control" placeholder="请输入领卡密码" value=""/>
                </div>
            </div>
        </div>
        <input type="hidden" name="id" value="<?php echo !empty($info['id']) ? $info['id'] : 0; ?>">
        <div class="panel-footer clearfix">
            <div class="demo-nifty-btn">
                <button type="submit" class="btn btn-sure jsBtnSave">保存</button>
                <button type="reset" class="btn btn-cancel btn-back">取消</button>
            </div>
        </div>
    </form>
</div>
<?php $this->beginBlock('jsText','append')?>
<script type="text/javascript">
$(function() {
    $("input.date-input").datepicker({
        format: "yyyy-mm-dd",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true,
        language: "zh-CN"
    });
    $('input.time-input').timepicker({
        showSeconds: true,
        minuteStep: 1,
        disableFocus: true,
        showMeridian: false,//24小时制
        autoclose: true,
    });
});
var locked = false;
var addUtil={
    addUrl:'<?php echo Yii::$app->urlManager->createUrl([$url_pre.'/save']);?>',
    save:function()
    {
        var checkCardId = checkUtil.checkEmpty('cardid','临时卡卡号');
        var checkPassword = checkUtil.checkEmpty('password','领卡密码');
        var checkDate = checkUtil.checkEmpty('time_d','有效期');

        if( !(checkCardId && checkPassword && checkDate) )
        {
            return false;
        }

        var id = $("#lostForm input[name=id]").val();
        var data = {
            id: id > 0 ? id : undefined,
            cardid: $("#lostForm input[name=cardid]").val(),
            password: $("#lostForm input[name=password]").val(),
            valid_date: $("#lostForm input[name=time_d]").val()+" "+$("input[name=time_t]").val()
        };
        if(locked)
            return false;
        locked = true;
        $.ajax({
            type:"POST",
            cache:false,
            url:this.addUrl,
            data:data,
            dataType:'json',
            success: function(obj){
                if ( obj.e == '0')
                {
                    var url = "<?php echo Yii::$app->urlManager->createUrl([$url_pre.'/index', 'type' => 1]);?>";
                    window.location.href = url;
                }
                else
                {
                    nsalert(obj.m, 'fail', 2000);
                    locked = false;
                }
            },
            error:function(){
                locked = false;
                nsalert("系统繁忙，请稍后再试！");
            }
        });
        return false;
    }
};

var warnUtil={
    setWrong:function(obj,msg)
    {
        var form_group = $(obj).closest('.form-group');
        if (form_group.size())
        {
            var span_parent = form_group.children('div:first');
            form_group.addClass('has-error');
            if( span_parent.children('span').length > 0)
                span_parent.children('span').html(msg);
            else
                span_parent.append('<span class="help-block">'+msg+'</span>');
        }
        else
        {
            var prt = $(obj).parent();
            if( prt.children('span').length > 0)
                prt.children('span').html(msg);
            else
                prt.append('<span class="help-block" style="color:#cc3f44">'+msg+'</span>');
        }
    },
    setRight:function(obj)
    {
        var form_group = $(obj).closest('.form-group');
        if (form_group.size())
        {
            var span_parent = form_group.children('div:first');
            form_group.removeClass('has-error');
            span_parent.children('span').html('');
        }
        else
        {
            var prt = $(obj).parent();
            prt.removeClass('has-error');
            prt.children('span').html('');
        }
    },
    setInit:function(obj)
    {
        var form_group = $(obj).closest('.form-group');
        var span_parent = form_group.children('div:first');
        form_group.removeClass('has-error');
        //form_group.removeClass('has-success');
        span_parent.children('span').html('');
    }
}
</script>
<?php $this->endBlock()?>
