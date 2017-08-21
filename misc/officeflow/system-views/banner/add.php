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
                <div class="form-group">
                    <label class="col-md-3 control-label">上传图片：</label>
                    <div class="col-md-9">
                    <span class="pull-left btn btn-primary btn-file">
                        上传
                        <div id="selectPic" style=""></div>
                    </span>
                        <input type="hidden" name="path" value="<?php echo empty($data['path'])?'':$data['path'];?>">
                    </div>
                    <div id="avatar_div" style="margin-top: 10px;" class="col-md-9 col-sm-offset-3">
                        <div class="media">
                            <div class="media-body">
                                <div class="media-block">
                                    <div class="media-left">
                                        <img style="max-height:100px;" class="dz-img" src="<?php echo empty($data['path']) ? '' : ($this->imghost . '/' . $data['path']);?>">
                                    </div>
                                </div>
                            </div>
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
            if($('input[name=attachement_id]').val() == '') {
                alert('图片不能为空');
                return;
            }
            if(this.ajaxLock) return;
            this.ajaxLock = true;
            $.ajax({
                type:"POST",
                cache:false,
                url:'<?php echo Yii::$app->urlManager->createUrl(['/system/banner/save']);?>',
                data:$('#manger_form').serialize(),
                dataType:'json',
                success:function(resp) {
                    if(resp.e == 0) {
                        alert(resp.m);
                        setTimeout(function(){
                            window.location.href="<?php echo Yii::$app->urlManager->createUrl(['/system/banner/index']);?>";
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
        },
        option:{
            pick: {id:"#selectPic"},
            formData: {'category': 'image'}, //category:image 图片 document 文档  media 媒体
            fileSingleSizeLimit: 6*1024*1024,
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/gif,image/jpeg,image/bmp,image/png'
            },
            func: {
                uploadSuccess: function (file, args) {
                    console.log(args);
                    if (args.state == 'SUCCESS') {
                        $('#avatar_div img').attr('src', '<?php echo $this->imghost;?>/' + args.url);
                        $('input[name=path]').val(args.url);
                        console.log(args.id);
                        $('#avatar_div').show();
                    } else {
                        nsalert('上传失败', "fail");
                    }
                }
            }
        }
    };
    webuploadUtil.init(obj.option);
</script>
<?php $this->endBlock();?>
