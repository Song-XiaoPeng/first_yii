<?php
echo $this->set('title','设置');
$url_pre = Yii::$app->controller->module->appkey.'/'.Yii::$app->controller->id;
?>

<?php $this->beginBlock('cssText','append')?>
<style>
.mar-btm .check_div {
    margin-top: 5px
}

.help {
    display: block;
    color: #42a5f5;
    font-size: 12px;
    margin-top: 5px;
}

.form-group .col-md-8 .table {
    margin-bottom: 0px !important;
}

.card_content {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
}
.card_content > .preview {
  -webkit-flex-shrink: 0;
      -ms-flex-negative: 0;
          flex-shrink: 0;
}
.card_content > .preview .preview_inner {
  width: 330px;
  height: 578px;
  padding: 10px 5px;
  overflow: auto;
  background: #ebebeb;
  border-radius: 5px;
}
.card_content > .preview .preview_inner .card_prev {
  height: 203px;
  background: rgba(0, 0, 0, 0.3);
  border-radius: 12px;
  position: relative;
}
.card_content > .preview .preview_inner .card_prev .card_prev_tit {
  color: #fff;
  font-size: 16px;
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  padding: 10px;
  padding-left: 10px;
  -webkit-box-align: center;
  -webkit-align-items: center;
      -ms-flex-align: center;
          align-items: center;
  font-weight: 600;
}
.card_content > .preview .preview_inner .card_prev .card_prev_tit img {
  width: 35px;
  height: 35px;
  margin-right: 5px;
  -webkit-flex-shrink: 0;
      -ms-flex-negative: 0;
          flex-shrink: 0;
}
.card_content > .preview .preview_inner .card_prev .card_prev_tit span {
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
  -webkit-flex-shrink: 1;
      -ms-flex-negative: 1;
          flex-shrink: 1;
  -webkit-box-flex: 1;
  -ms-flex: 1;
  -webkit-flex: 1;
          flex: 1;
}
.card_content > .preview .preview_inner .card_prev .card_prev_item {
  height: 110px;
  padding-left: 20px;
  color: #fff;
  font-size: 14px;
  font-weight: 400;
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
      -ms-flex-direction: column;
          flex-direction: column;
  -webkit-box-pack: center;
  -webkit-justify-content: center;
      -ms-flex-pack: center;
          justify-content: center;
}
.card_content > .preview .preview_inner .card_prev .card_prev_photo {
  position: absolute;
  right: 20px;
  top: 59px;
  width: 80px;
  height: 110px;
  background: #fff;
}
.card_content > .preview .preview_inner .card_prev .card_prev_photo img {
  width: 100%;
  height: 100%;
  display: block;
}
.card_content > .preview .preview_inner .card_list {
  background: #fff;
  border-radius: 12px;
  min-height: 354px;
  border: 1px solid #e9e9ea;
  position: relative;
  padding-top: 60px;
  padding-left: 15px;
  padding-right: 15px;
}
.card_content > .preview .preview_inner .card_list .card_scan {
  position: absolute;
  top: -15px;
  left: 50%;
  -webkit-transform: translate(-50%, 0);
      -ms-transform: translate(-50%, 0);
          transform: translate(-50%, 0);
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: #fff;
  z-index: 3;
}
.card_content > .preview .preview_inner .card_list .card_scan > div {
  width: 60%;
  height: 60%;
  margin: 20%;
  margin-top: 17%;
  background: #2d2d2d url(./scan.png) no-repeat center center;
  background-size: 60% 60%;
  border-radius: 50%;
}
.card_content > .preview .preview_inner .card_list .card_list_across {
  width: 100%;
  height: 40px;
  border: 1px solid #9ebbdb;
  border-radius: 6px;
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
      -ms-flex-align: center;
          align-items: center;
  z-index: 9;
  position: relative;
}
.card_content > .preview .preview_inner .card_list .card_list_across span {
  -webkit-flex-shrink: 1;
      -ms-flex-negative: 1;
          flex-shrink: 1;
  -webkit-box-flex: 1;
  -ms-flex: 1;
  -webkit-flex: 1;
          flex: 1;
  font-size: 16px;
  font-weight: 500;
  color: #9ebbdb;
  line-height: 20px;
  text-align: center;
  border-right: 1px solid #9ebbdb;
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
}
.card_content > .preview .preview_inner .card_list .card_list_across span:last-child {
  border-right: 0;
}
.card_content > .preview .preview_inner .card_list .card_list_vertical {
  list-style-type: none;
  width: 100%;
  padding: 0;
  margin-top: 30px;
}
.card_content > .preview .preview_inner .card_list .card_list_vertical li {
  width: 100%;
  line-height: 42px;
  color: #2d2d2d;
  font-size: 16px;
  padding-left: 4px;
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  border-bottom: 1px solid #f0f0f0;
}
.card_content > .preview .preview_inner .card_list .card_list_vertical li img {
  -webkit-flex-shrink: 0;
      -ms-flex-negative: 0;
          flex-shrink: 0;
  float: right;
  display: block;
  width: 15px;
  height: 15px;
  margin-top: 14px;
}
.card_content > .preview .preview_inner .card_list .card_list_vertical li span {
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
  -webkit-flex-shrink: 1;
      -ms-flex-negative: 1;
          flex-shrink: 1;
  -webkit-box-flex: 1;
  -ms-flex: 1;
  -webkit-flex: 1;
          flex: 1;
}
.card_content > .preview .preview_inner .card_list .card_list_vertical li:first-child {
  border-top: 1px solid #f0f0f0;
}

</style>
<?php $this->endBlock()?>
<div class="panel">
    <div class="panel-heading top">
        <h3 class="panel-title">设置</h3>
    </div>
    <form class="form-horizontal" onsubmit="return false;" id="main-form">
        <div class="panel-body card_content">
            <div class="preview">
                <div class="preview_inner">
                    <div class="card_prev" style="background: url(&quot;http://eaiimg.datamorality.com/image/1/2690.png&quot;) center center / 100% 100% no-repeat;">
                        <!-- <div class='card_prev'> -->
                        <p class="card_prev_tit">
                            <img class="logo" alt="" style="border-radius: 50%;" src="./校园卡设置_files/2654.png"> <span>校园卡测试</span>
                        </p>
                        <div class="card_prev_item">
                            <p>姓名</p>
                            <p>学工号</p>
                            <p style="display: none;">部门/学院</p>
                            <p>类别</p>
                        </div>
                        <div class="card_prev_photo" style="display:none;">
                            <img src="./校园卡设置_files/avatar.png" alt="">
                        </div>
                    </div>
                    <div class="card_list">
                        <div class="card_scan">
                            <div></div>
                        </div>
                        <div class="card_list_across">
                            <span>缴费大厅</span><span>门禁扫码</span>
                        </div>
                        <ul class="card_list_vertical">
                            <li>
                                <span>竖着的竖着的竖着的竖着的竖着的1</span>
                                <img src="./校园卡设置_files/go.png" alt="">
                            </li>
                            <li>
                                <span>竖着的2</span>
                                <img src="./校园卡设置_files/go.png" alt="">
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="operation">
                <div class="form-group">
                    <label class="col-md-3 control-label"><span class="text-danger"> *</span>名称：</label>
                    <div class="col-md-8">
                        <input name="title" class="form-control" placeholder="请输入校园卡名称">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><span class="text-danger"> *</span>卡面：</label>
                    <div class="col-md-8">
                        <span class="pull-left btn btn-primary btn-file">
        上传图片<div id="backgroundPic" class="webuploader-container"><div class="webuploader-pick"></div><div id="rt_rt_1bshdat7i11n11nbg5mu1gjr1too1" style="position: absolute; top: 0px; left: 0px; width: 76px; height: 30px; overflow: hidden; bottom: auto; right: auto;"><input type="file" name="file" class="webuploader-element-invisible" accept="image/gif,image/jpg,image/jpeg,image/bmp,image/png"><label style="opacity: 0; width: 100%; height: 100%; display: block; cursor: pointer; background: rgb(255, 255, 255);"></label></div></div>
    </span>
                        <small class="help-block mar-lft pull-left">尺寸1020*643px,2M以内，格式：bmp、png、jpeg、jpg、gif</small>
                    </div>
                    <div style="margin-top: 10px;" class="col-md-3 col-md-offset-3">
                        <!-- <div class="media"> -->
                        <div class="media-body">
                            <div class="media-block">
                                <div class="media-left pos-rel">
                                    <img style="height: 161px;width: 255px;" class="dz-img" src="./校园卡设置_files/2690.png">
                                    <i class="del"></i>
                                </div>
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><span class="text-danger"> *</span>logo：</label>
                    <div class="col-md-8">
                        <span class="pull-left btn btn-primary btn-file">
        上传图片<div id="logoPic" class="webuploader-container"><div class="webuploader-pick"></div><div id="rt_rt_1bshdat7jc9m1cga58cu4cup54" style="position: absolute; top: 0px; left: 0px; width: 76px; height: 30px; overflow: hidden; bottom: auto; right: auto;"><input type="file" name="file" class="webuploader-element-invisible" accept="image/gif,image/jpg,image/jpeg,image/bmp,image/png"><label style="opacity: 0; width: 100%; height: 100%; display: block; cursor: pointer; background: rgb(255, 255, 255);"></label></div></div>
    </span>
                        <small class="help-block mar-lft pull-left">尺寸500*500px,1M以内，格式bmp、png、jpeg、jpg、gif</small>
                    </div>
                    <div style="margin-top: 10px;" class="col-md-3 col-md-offset-3">
                        <!-- <div class="media"> -->
                        <div class="media-body">
                            <div class="media-block">
                                <div class="media-left pos-rel">
                                    <img style="height:120px;width:120px;" class="dz-img" src="./校园卡设置_files/2654.png">
                                    <i class="del"></i>
                                </div>
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">显示字段：</label>
                    <div class="col-md-8 input-group mar-btm">
                        <div class="check_div">
                            <input type="checkbox" class="magic-checkbox" value="姓名" id="input_uid">
                            <label style="width:150px" for="input_uid">姓名</label>
                        </div>
                        <div class="check_div">
                            <input type="checkbox" class="magic-checkbox" value="学工号" id="input_num">
                            <label style="width:150px" for="input_num">学工号</label>
                        </div>
                        <div class="check_div">
                            <input type="checkbox" class="magic-checkbox" value="部门/学院" id="input_colid">
                            <label style="width:150px" for="input_colid">部门/学院</label>
                        </div>
                        <div class="check_div">
                            <input type="checkbox" class="magic-checkbox" value="卡类别" checked id="input_typeid">
                            <label style="width:150px" for="input_typeid">卡类别</label>
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="col-sm-offset-3 col-md-5 text-warning">勾选的字段将会显示在校园卡上</a>
                    <div class="col-sm-offset-3 col-md-5"><a href="javascript:void(0)" class="help">帮助</a></div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="section">标准栏位信息：</label>
                    <div class="col-md-8">
                        <table class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="30%">标题</th>
                                    <th width="10%">code</th>
                                    <th>链接</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="word-break: break-all;">缴费大厅</td>
                                    <td style="word-break: break-all;">jfdt01</td>
                                    <td style="word-break: break-all;">http://eai.datamorality.com/paymenthall/wap/default</td>
                                </tr>
                                <tr>
                                    <td style="word-break: break-all;">门禁扫码</td>
                                    <td style="word-break: break-all;">mjsm001</td>
                                    <td style="word-break: break-all;">https://www.baidu.com</td>
                                </tr>
                                <tr>
                                    <td style="cursor: pointer;" colspan="4"><i class="ion-plus"></i> 编辑</td>
                                </tr>
                            </tbody>
                        </table>
                        <input type="hidden" name="extraList">
                    </div>
                    <div class="col-sm-offset-3 col-md-5"><a href="javascript:void(0)" class="help">帮助</a></div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" for="section">栏位信息：</label>
                    <div class="col-md-8">
                        <table class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="10%">code</th>
                                    <th width="30%">标题</th>
                                    <th>链接</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>s1</td>
                                    <td>竖着的竖着的竖着的竖着的竖着的1</td>
                                    <td>https://www.baidu.com/</td>
                                </tr>
                                <tr>
                                    <td>s2</td>
                                    <td>竖着的2</td>
                                    <td>https://www.baidu.com/</td>
                                </tr>
                                <tr>
                                    <td style="cursor: pointer;" colspan="4"><i class="ion-plus"></i> 编辑</td>
                                </tr>
                            </tbody>
                        </table>
                        <input type="hidden" name="extraList">
                    </div>
                    <div class="col-sm-offset-3 col-md-5"><a href="javascript:void(0)" class="help">帮助</a></div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">领卡说明：</label>
                    <div class="col-md-8">
                        <textarea rows="10" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer clearfix">
            <div class="demo-nifty-btn">
                <button type="submit" class="btn btn-sure">保存</button>
                <button type="reset" class="btn btn-cancel btn-back">取消</button>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="modal-form" role="dialog" tabindex="-1" aria-labelledby="modal-form">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="ion-close"></i></button>
                <h4 class="modal-title">设置</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-3 control-label">标题：</label>
                                <div class="col-md-8">
                                    <input name="title" class="form-control" placeholder="请输入标题">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">CODE：</label>
                                <div class="col-md-8">
                                    <input name="code" class="form-control" placeholder="请输入CODE,英文唯一标识,不能重复">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">链接：</label>
                                <div class="col-md-8">
                                    <input name="url" class="form-control" placeholder="请输入链接">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--Modal footer-->
            <div class="modal-footer">
                <a data-bb-handler="confirm" class="btn-sure">确 &nbsp;&nbsp; 认</a>
                <a data-dismiss="modal" class="btn-cancel">取 &nbsp;&nbsp; 消</a>
            </div>
        </div>
    </div>
</div>