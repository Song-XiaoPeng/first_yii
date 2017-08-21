<?php
$this->set('title', '添加');
 ?>
<div class="row" id="app">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading top">
                <a href="javascript:history.go(-1)" class="back"></a>
                <h3 class="panel-title">添加</h3>
            </div>
            <form id="manger_form" class="form-horizontal" onsubmit="return false;">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="demo-is-inputsmall">名称</label>
                        <div class="col-sm-6">
                            <input maxlength="64" type="text"  name="name" value="<?= empty($data['name']) ? '' : $data['name'];?>" placeholder="" class="form-control input-sm" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">所属分类</label>
                        <div class="col-sm-6">
                            <select name="pid" class="form-control"  >
                                <option value="0">请选择</option>
                                <?php if(!empty($category)): foreach ($category as $key => $val): ?>
                                    <option value="<?= $val['id'];?>" <?php if(!empty($data['pid'])&&$data['pid'] == $val['id']){ echo 'selected';} ?>><?= $val['name'];?></option>
                                <?php endforeach;endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <input name="id" type="hidden" value='<?= empty($data['id']) ? '' : $data['id'];?>'>
                        <div class="demo-nifty-btn">
                            <button class="btn btn-sure" onclick="obj.save()" type="submit">保存</button>
                            <button class="btn btn-cancel btn-back" onclick="history.go(-1)" type="reset">取消</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->beginBlock('jsText','append');?>
<script type="text/javascript">

    var obj = {
        ajaxLock:false,
        save:function(){
            if($("input[name=name]").val() == '') {
                alert('名称不能为空');
                return;
            }
            if(this.ajaxLock) return;
            this.ajaxLock = true;
            $.ajax({
                type:"POST",
                cache:false,
                url:'<?php echo Yii::$app->urlManager->createUrl(['/system/archive-classify/save']);?>',
                data:$('#manger_form').serialize(),
                dataType:'json',
                success:function(resp) {
                    if(resp.e == 0) {
                        alert(resp.m);
                        setTimeout(function(){
                            window.location.href="<?php echo Yii::$app->urlManager->createUrl(['/system/archive-classify/index']);?>";
                        },1500);
                    } else {
                        alert(resp.m);
                    }
                    obj.ajaxLock = false;
                },
                error:function(){
                    alert('网络错误...');
                    obj.ajaxLock = false;
                }
            });
        }
    };
</script>
<?php $this->endBlock();?>
