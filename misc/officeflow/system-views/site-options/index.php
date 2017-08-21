<?php
$this->set("title", "网站配置");
$url_prefix = "";
?>
    <div class="panel jsVueCon">
        <div class="panel-heading top">
            <a class="back" href="javascript:history.go(-1)"></a>
            <h3 class="panel-title">网站配置</h3>
        </div>
        <div class="panel-body">
            <form action="" onsubmit="return false;" class="form-horizontal jsSaveForm">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="demo-is-inputsmall"><span class="text-danger">*</span>网站名称：</label>
                    <div class="col-sm-6">
                        <input maxlength="64" type="text"  name="site_name" value="<?php echo empty($data['site_name']) ? '' : $data['site_name'];?>" placeholder="网站名称" class="form-control input-sm" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><span class="text-danger">*</span>网站版权：</label>
                    <div class="col-sm-6">
                        <input maxlength="20" type="text" name="site_copyright" value="<?php echo empty($data['site_copyright']) ? '' : $data['site_copyright'];?>" placeholder="网站版权" class="form-control" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><span class="text-danger">*</span>网站地址：</label>
                    <div class="col-sm-6">
                        <input maxlength="20" type="text" name="site_address" value="<?php echo empty($data['site_address']) ? '' : $data['site_address'];?>" placeholder="网站地址" class="form-control" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><span class="text-danger">*</span>网站邮箱：</label>
                    <div class="col-sm-6">
                        <input maxlength="20" type="text" name="site_email" value="<?php echo empty($data['site_email']) ? '' : $data['site_email'];?>" placeholder="网站邮箱" class="form-control" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><span class="text-danger">*</span>网站邮编：</label>
                    <div class="col-sm-6">
                        <input maxlength="20" type="text" name="site_postcode" value="<?php echo empty($data['site_postcode']) ? '' : $data['site_postcode'];?>" placeholder="网站邮编" class="form-control" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><span class="text-danger">*</span>域名备案信息：</label>
                    <div class="col-sm-6">
                        <input maxlength="20" type="text" name="site_archival" value="<?php echo empty($data['site_archival']) ? '' : $data['site_archival'];?>" placeholder="域名备案信息" class="form-control" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">上传网站Logo：</label>
                    <div class="col-md-9">
                    <span class="pull-left btn btn-primary btn-file">
                        上传图片
                        <div id="selectPic" style=""></div>
                    </span>
                        <input type="hidden" name="site_logo" value="<?php echo empty($data['site_logo'])?'':$data['site_logo'];?>">
                    </div>
                    <div id="logo_div" style="margin-top: 10px;" class="col-md-9 col-sm-offset-3">
                        <div class="media">
                            <div class="media-body">
                                <div class="media-block">
                                    <div class="media-left">
                                        <img style="max-height:100px;" class="dz-img" src="<?php echo empty($attach['file']) ? '' : ($this->imghost . '/' . $attach['file']);?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input name="id" type="hidden" value='<?php echo !empty($data['id']) ? $data['id'] : 0;?>'>
            </form>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="demo-nifty-btn">
                    <button class="btn btn-sure jsBtnSure" @click="save()" type="submit">保存</button>
                    <button class="btn btn-cancel btn-back default" @click="reset()" type="reset">取消</button>
                </div>
            </div>
        </div>
    </div>
<?php $this->beginBlock("jsText");?>
    <script type="text/javascript">
        var vm = new Vue({
            el: '.jsVueCon',
            data: {
                ajaxLock: false,
                saveUrl: "<?php echo Yii::$app->urlManager->createUrl(['/system/site-options/save']);?>",
                info: <?php echo json_encode($data, JSON_UNESCAPED_UNICODE) ?: '{name:"", copyright:"", logo:""}';?>,
            },
            methods: {
                save: function() {
                    var cksn = this.checkEmpty("input[name=site_name]", "网站名称不能为空");
                    var cksc = this.checkEmpty("input[name=site_copyright]", "网站版权不能为空");
                    var cksl = this.checkEmpty("input[name=site_logo]","网站logo不能为空");
                    var cksa = this.checkEmpty("input[name=site_address]","网站地址不能为空");
                    var ckse = this.checkEmpty("input[name=site_email]","网站邮箱不能为空");
                    var cksp = this.checkEmpty("input[name=site_postcode]","邮编不能为空");
                    var cksem = this.checkEmail("input[name=site_email]","邮箱有误");
                    if(!cksn || !cksc || !cksl || !cksa || !ckse || !cksp || !cksem ){
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
                                alert(resp.m);
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
                checkEmail: function(obj ,text){
                    var reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                    if(!reg.test($.trim($(obj).val()))){
                        this.setWrong(obj,text);
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
                },
                reset: function(evt){
                    window.location.reload();
                }
            }
        });
        var option = {
            pick: {id:"#selectPic",multiple:false},
            formData: {'category': 'image'}, //category:image 图片 document 文档  media 媒体
            fileSingleSizeLimit: 6*1024*1024,
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/gif,image/jpeg,image/bmp,image/png'
            },
            func: {
                uploadSuccess: function (file, args) {
                    if (args.state == 'SUCCESS') {
                        $('#logo_div img').attr('src', '<?php echo $this->imghost;?>/' + args.url);
                        $('input[name=site_logo]').val(args.id);
                        $('#logo_div').show();
                    } else {
                        nsalert('上传失败', "fail");
                    }
                }
            }
        };
        webuploadUtil.init(option);
    </script>
<?php $this->endBlock();?>