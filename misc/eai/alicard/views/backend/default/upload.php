<?php
$this->addCssFile([
    '/thirdparty/webuploader/webuploader.css',
]);
$this->addJsFile([
    '/js/webupload.js',
    '/thirdparty/webuploader/webuploader.min.js',
]);
?>
<div class="row" id="app">
    <div class="col-lg-12">
        <div class="panel">

            <form id="manger_form" class="form-horizontal" onsubmit="return false;">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">上传图片</label>
                        <div class="col-md-9">
                            <span style="width: 70px;height: 29px;line-height: 29px;" id="selectPic"class="pull-left btn btn-primary btn-file">
                                上传图片
                            </span>
                            <input type="hidden" name="avatar" value="<?php echo empty($info->avatar)?'':$info->avatar;?>">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->beginBlock('jsText','append');?>
<script type="text/javascript">
$(function () {
     var option = {
        pick: {id:"#selectPic",multiple:false},
        formData: {category:"image", plugins: ["alipay"]},
        fileSingleSizeLimit: 6 * 1024 * 1024,
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/gif,image/jpeg,image/bmp,image/png'
        },
        func: {
            uploadSuccess: function (file, args) {
                console.log(args);
                /* {
                    "url":"image/0/75.png",
                    "size":47023,
                    "title":"75.png",
                    "original":"0017029948225577_b.png",
                    "width":1024,
                    "height":573,
                    "alipay":{
                        "code":"10000",
                        "msg":"Success",
                        "image_id":"WO5C5p4NQjWccYVjih6TOAAAACMAAQED",
                        "image_url":"https://oalipay-dl-django.alicdn.com/rest/1.0/image?fileIds=WO5C5p4NQjWccYVjih6TOAAAACMAAQED&zoom=original"},
                        "state":"SUCCESS"
                    }
                */
            }
        }
    };
    webuploadUtil.init(option);
});
</script>
<?php $this->endBlock();?>
