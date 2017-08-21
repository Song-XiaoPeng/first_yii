<?php 
use app\modules\manager\models\ManagerModel;
$this->set("title", "用户管理");
$url_prefix = "";
?>
<div class="panel jsVueCon">
    <div class="panel-heading top">
        <a class="back" href="javascript:history.go(-1)"></a>
        <h3 class="panel-title">添加管理员</h3>
    </div>

    <div class="panel-body">
        <form action="" onsubmit="return false;" class="form-horizontal jsSaveForm">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="demo-is-inputsmall"><span class="text-danger">*</span>用户名：</label>
                <div class="col-sm-6">
                    <input maxlength="64" type="text"  name="username" value="<?php echo empty($info['username']) ? '' : $info['username'];?>" placeholder="用户名" class="form-control input-sm" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><span class="text-danger">*</span>姓名：</label>
                <div class="col-sm-6">
                    <input maxlength="20" type="text" name="realname" value="<?php echo empty($info['realname']) ? '' : $info['realname'];?>" placeholder="真实姓名" class="form-control" >
                </div>
            </div>
            <?php if(empty($info['uid'])):?>
            <div class="form-group">
                <label class="col-sm-3 control-label">密码：</label>
                <div class="col-sm-6">
                    <input type="password" name="password" value="" placeholder="请输入密码" class="form-control" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">重复密码：</label>
                <div class="col-sm-6">
                    <input type="password" name="repassword" value="" placeholder="请再次输入密码" class="form-control" >
                </div>
            </div>
            <?php endif;?>
            <div class="form-group">
                <label class="col-sm-3 control-label"><span class="text-danger">*</span>绑定邮箱：</label>
                <div class="col-sm-6">
                    <input type="email" value="<?php echo empty($info['email']) ? '' : $info['email'];?>" name="email" placeholder="绑定邮箱" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">是否启用：</label>
                <div class="col-sm-6">
                    <div class="radio">
                        <input id="status_yes" name="status" <?php echo (empty($info['status']) || $info['status'] =='1' ) ? 'checked' : '';?>  value="1"   class="magic-radio" type="radio" >
                        <label for="status_yes" >是</label>
                        <input id="status_no" name="status" <?php echo !empty($info['status']) && $info['status'] == '2' ? 'checked' : '';?> value="2"   class="magic-radio" type="radio" >
                        <label for="status_no" >否</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">上传头像：</label>
                <div class="col-md-9">
                    <span class="pull-left btn btn-primary btn-file">
                        上传图片
                        <div id="selectPic"></div>
                    </span>
                    <input type="hidden" name="avatar" value="<?php echo empty($info['avatar'])?'':$info['avatar'];?>">
                </div>
                <div id="avatar_div" style="margin-top: 10px;" class="col-md-9 col-sm-offset-3">
                    <div class="media">
                        <div class="media-body">
                            <div class="media-block">
                                <div class="media-left">
                                    <img style="max-height:100px;" class="dz-img" src="<?php echo empty($info['avatar']) ? ManagerModel::DEF_AVATAR : ($this->imghost . '/' . $info['avatar']);?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">权限：</label>
                <div class="col-sm-6">
                    <?php if(empty($info) || $info['role'] != "developer"):?>
                        <select class="form-control" name="role">
                            <option value="">请选择权限...</option>
                            <option <?php echo isset($info['role']) && $info['role'] == 'root' ? 'selected' : '';?> value='root'>ROOT</option>
                            <option <?php echo isset($info['role']) && $info['role'] == 'normal' ? 'selected' : '';?> value='normal'>普通管理员</option>
                        </select>
                    <?php else:?>
                        <select disabled="" class="form-control" name="role">
                            <option value="developer">第三方开发者</option>
                        </select>
                    <?php endif;?>
                </div>
            </div>
            <?php if(!empty($info) && $info['role'] == 'developer'):?>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><span class="text-danger">*</span>开发者签名：</label>
                    <div class="col-sm-6">
                        <input type="text" value="<?php echo $info['develop_name'];?>" name="develop_name" placeholder="开发者签名" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><span class="text-danger">*</span>联系方式：</label>
                    <div class="col-sm-6">
                        <input type="text" value="<?php echo $info['develop_contact'];?>" name="develop_contact" placeholder="开发者联系方式" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><span class="text-danger">*</span>签名英文简称：</label>
                    <div class="col-sm-6">
                        <input type="text" value="<?php echo $info['develop_prefix'];?>" name="develop_prefix" placeholder="开发者签名英文简称" class="form-control" />
                    </div>
                </div>
            <?php endif;?>
            <input name="uid" type="hidden" value='<?php echo $info['uid'];?>'>
        </form>
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="demo-nifty-btn">
                <button class="btn btn-sure jsBtnSure" @click="save()" type="submit">保存</button>
                <button class="btn btn-cancel btn-back default" type="reset">取消</button>
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
            saveUrl: "<?php echo Yii::$app->urlManager->createUrl(['/manager/backend/default/save']);?>",
            info: <?php echo json_encode($info, JSON_UNESCAPED_UNICODE) ?: '{username:"", realname:"", email: "", password: "", repassword: "", }';?>,
        },
        methods: {
            save: function() {
                var ckun = this.checkEmpty("input[name=username]", "用户名不能为空");
                var ckrn = this.checkEmpty("input[name=realname]", "姓名不能为空");
                var ckmail = this.checkEmpty("input[name=email]", "邮箱不能为空");
                var ckpwd = this.checkPwd();
                var checkDev = true;
                <?php if(!empty($info) && $info['role'] == 'developer'):?>
                var ckDevName = this.checkEmpty("input[name=develop_name]", "开发者签名不能为空");
                var ckDevCon = this.checkEmpty("input[name=develop_contact]", "联系方式不能为空");
                var ckDevPre = this.checkPrefix();
                checkDev = ckDevName && ckDevCon && ckDevPre;
                <?php endif;?>

                if(!ckun || !ckrn || !ckmail || !ckpwd || !checkDev){
                    return false;
                }
                if(this.ajaxLock)return;
                this.ajaxLock = true;
                btnLoading($(".jsBtnSure"));
                $.ajax({
                    type: 'POST',
                    url: this.saveUrl,
                    dataType: 'json',
                    data: $(".jsSaveForm").serialize(),
                    success: function(resp) {
                        if(resp.e == 0){
                            nsalert("操作成功");
                            setTimeout(function() {
                                window.location.href = "<?php echo Yii::$app->urlManager->createUrl(['/manager/backend/default/index']);?>";
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
                        removeLock($(".jsBtnSure"));
                    }
                });
            },
            checkPwd: function() {
                if($("input[name=password]").length > 0){
                    var ckpwd = this.checkEmpty("input[name=password]", "密码不能为空");
                    var ckrpwd = true;
                    if($("input[name=password]").val() != $("input[name=repassword]").val()){
                        this.setWrong("input[name=repassword]", "两次密码不一致");
                        ckrpwd = false;
                    }else{
                        this.setRight("input[name=repassword]");
                    }

                    return ckrpwd && ckpwd;
                }
                return true;
            },
            checkPrefix: function() {
                var ckEpt = this.checkEmpty("input[name=develop_prefix]", "签名英文简称不能为空");
                if(!ckEpt)return ckEpt;
                if(!/^[a-zA-Z]+$/g.test($("input[name=develop_prefix]").val())) {
                    this.setWrong("input[name=develop_prefix]", "签名只能为英文字母");
                    return false;
                }else{
                    this.setRight("input[name=develop_prefix]");
                    return true;
                }
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
                    $('#avatar_div img').attr('src', '<?php echo $this->imghost;?>/' + args.url);
                    $('input[name=avatar]').val(args.url);
                    $('#avatar_div').show();
                } else {
                    nsalert('上传失败', "fail");
                }
            }
        }
    };
    webuploadUtil.init(option);
</script>
<?php $this->endBlock();?>