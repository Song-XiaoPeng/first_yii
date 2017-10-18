<?php echo $this->set('title', '校园卡设置'); ?>
<?php $this->beginBlock('cssFile', 'append'); ?>
    <link rel="stylesheet" type="text/css" href="/backend/alicard/card.css">
    <style type="text/css">
        i.del {
            background: url(/backend/activity/img/delete-1.png) no-repeat;
            background-position: center;
            background-color: #efefef;
            background-size: 60%;
            position: absolute;
            right: 10px;
            top: 0px;
            height: 18px;
            width: 18px;
        }
    </style>
<?php $this->endBlock(); ?>
    <div class="row" id="appcon">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading top">
                    <h3 class="panel-title">设置</h3>
                </div>
                <form class="form-horizontal" onsubmit="return false;" id="main-form">
                    <div class="panel-body card_content">
                        <div class='preview'>
                            <div class='preview_inner'>
                                <div class='card_prev' :style="getBackStyle(info.background_url)">
                                    <!-- <div class='card_prev'> -->
                                    <p class='card_prev_tit'>
                                        <img class='logo' :src="GetImg(info.logo_url)" alt=""
                                             style="border-radius: 50%;"> <span v-text="info.title"></span>
                                    </p>
                                    <div class='card_prev_item'>
                                        <p v-show="showColumns('name')">姓名</p>
                                        <p v-show="showColumns('xgh')">学工号</p>
                                        <p v-show="showColumns('dept')">部门/学院</p>
                                        <p v-show="showColumns('type')">类别</p>
                                    </div>
                                    <div class='card_prev_photo' style="display:none;">
                                        <img src="/backend/alicard/avatar.png" alt="">
                                    </div>
                                </div>
                                <div class='card_list'>
                                    <div class='card_scan'>
                                        <div></div>
                                    </div>
                                    <div class='card_list_across' v-show="objLen(codeList) > 0">
                                        <span v-for="item in codeList" v-text="item.title"></span>
                                    </div>
                                    <ul class='card_list_vertical'>
                                        <li v-for="item in columnList">
                                            <span v-text="item.title"></span>
                                            <img src="/backend/alicard/go.png" alt="">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class='operation'>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><span class="text-danger"> *</span>名称：</label>
                                <div class="col-md-8">
                                    <input name="title" class="form-control" placeholder="请输入校园卡名称"
                                           v-model="info.title"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><span class="text-danger"> *</span>卡面：</label>
                                <div class="col-md-8">
                                <span class="pull-left btn btn-primary btn-file">
                                    上传图片<div id="backgroundPic"></div>
                                </span>
                                    <small class="help-block mar-lft pull-left">
                                        尺寸1020*643px,2M以内，格式：bmp、png、jpeg、jpg、gif
                                    </small>
                                </div>
                                <div style="margin-top: 10px;" class="col-md-3 col-md-offset-3">
                                    <!-- <div class="media"> -->
                                    <div class="media-body">
                                        <div class="media-block">
                                            <div style="display: none;" v-show="info.background_url!=''"
                                                 class="media-left pos-rel">
                                                <img style="height: 161px;width: 255px;" class="dz-img"
                                                     v-bind:src="GetImg(info.background_url)">
                                                <i class="del" @click="info.background_url=''"></i>
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
                                    上传图片<div id="logoPic"></div>
                                </span>
                                    <small class="help-block mar-lft pull-left">
                                        尺寸500*500px,1M以内，格式bmp、png、jpeg、jpg、gif
                                    </small>
                                </div>
                                <div style="margin-top: 10px;" class="col-md-3 col-md-offset-3">
                                    <!-- <div class="media"> -->
                                    <div class="media-body">
                                        <div class="media-block">
                                            <div style="display: none;" v-show="info.logo_url!=''"
                                                 class="media-left pos-rel">
                                                <img style="height:120px;width:120px;" class="dz-img"
                                                     v-bind:src="GetImg(info.logo_url)">
                                                <i class="del" @click="info.logo_url=''"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">显示字段：</label>
                                <div class="col-md-7">
                                    <?php foreach ($columns as $k => $v): ?>
                                        <div class="checkbox">
                                            <input @change="setColumns($event)" id="field<?php echo $k; ?>"
                                                   class="form-control magic-checkbox" type="checkbox"
                                                   value="<?php echo $k; ?>"
                                                   name="columns[]" <?php echo in_array($k, !empty($info['columns']) ? $info['columns'] : []) ? 'checked' : ''; ?>>
                                            <label onmouseover="$(this).find('span').show()"
                                                   onmouseout="$(this).find('span').hide()"
                                                   for="field<?php echo $k; ?>"><?php echo $v; ?><?php echo isset($tips[$k]) ? ' <span style="display:none;color:#CCC;">（' . $tips[$k] . '）</span>' : ''; ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <p class="col-sm-offset-3 col-md-5 text-warning">勾选的字段将会显示在校园卡上</p>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="section">横向栏位信息：</label>
                                <div class="col-md-8">
                                    <table class="table table-bordered" cellspacing="0" width="100%"
                                           style="margin-bottom: 5px">
                                        <thead v-show="objLen(codeList) > 0">
                                        <tr>
                                            <th width="30%">标题</th>
                                            <th width="10%">code</th>
                                            <th>链接</th>
                                            <th width="10%">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(k,item) in codeList">
                                            <td v-text="item.title" style="word-break: break-all;"></td>
                                            <td v-text="item.code" style="word-break: break-all;"></td>
                                            <td v-text="item.url" style="word-break: break-all;"></td>
                                            <td class="operate">
                                                <i class="ion-edit" style="margin-right: 10px"
                                                   @click="openModal('codeList', item)"></i>
                                                <i class="ion-trash-a" @click="delLine('codeList',k)"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="cursor: pointer;" colspan="4" @click="openModal('codeList')"><i
                                                        class="ion-plus"></i> 添加
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="extraList" v-model="extraList">
                                    <!-- <a class="text-primary">查看图示</a> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="section">纵向栏位信息：</label>
                                <div class="col-md-8">
                                    <table class="table table-bordered" cellspacing="0" width="100%"
                                           style="margin-bottom: 5px">
                                        <thead v-show="objLen(columnList) > 0">
                                        <tr>
                                            <th width="10%">code</th>
                                            <th width="30%">标题</th>
                                            <th>链接</th>
                                            <th width="10%">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(k,item) in columnList">
                                            <td v-text="item.code"></td>
                                            <td v-text="item.title"></td>
                                            <td v-text="item.url"></td>
                                            <td class="operate">
                                                <i class="ion-edit" style="margin-right: 10px"
                                                   @click="openModal('columnList', item)"></i>
                                                <i class="ion-trash-a" @click="delLine('columnList',k)"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="cursor: pointer;" colspan="4" @click="openModal('columnList')"><i
                                                        class="ion-plus"></i> 添加
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="extraList" v-model="extraList">
                                    <!-- <a class="text-primary">查看图示</a> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">领卡说明：</label>
                                <div class="col-md-8">
                                    <textarea rows="10" v-model="info.description" class="form-control"></textarea>
                                    <span class="help-block">该说明将显示在领卡页面顶部
                                        <!-- <a class="text-primary">查看图示</a> --></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer clearfix">
                        <div class="demo-nifty-btn">
                            <button type="submit" class="btn btn-sure" v-on:click="save()">保存</button>
                            <button type="reset" class="btn btn-cancel btn-back">取消</button>
                        </div>
                    </div>
                </form>
            </div>
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
                                            <input name="title" class="form-control" maxlength="6"
                                                   placeholder="请输入标题，至多6字" v-model="current.title"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">CODE：</label>
                                        <div class="col-md-8">
                                            <input name="code" class="form-control" placeholder="请输入CODE,英文唯一标识,不能重复"
                                                   v-model="current.code"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">链接：</label>
                                        <div class="col-md-8">
                                            <input name="url" class="form-control" placeholder="请输入链接"
                                                   v-model="current.url"/>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!--Modal footer-->
                    <div class="modal-footer">
                        <a data-bb-handler="confirm" class="btn-sure" @click="saveModal()">确 &nbsp;&nbsp; 认</a>
                        <a data-dismiss="modal" class="btn-cancel">取 &nbsp;&nbsp; 消</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->beginBlock('jsText', 'append'); ?>
    <script type="text/javascript">

        var option = {
            pick: {id: "#backgroundPic", multiple: false},
            formData: {'category': 'image', plugins: ["alipay"]}, //category:image 图片 document 文档  media 媒体
            fileSingleSizeLimit: 2 * 1024 * 1024,
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png'
            },
            func: {
                uploadSuccess: function (file, args) {
                    if (args.state == 'SUCCESS' && args.alipay.code == '10000') {
                        infoVue.info.background_id = args.alipay.image_id;
                        Vue.set(infoVue.info, 'background_url', args.url);
                    } else {
                        nsalert('上传失败', 'fail');
                    }
                }
            }
        };
        var option1 = {
            pick: {id: "#logoPic", multiple: false},
            formData: {'category': 'image', plugins: ["alipay"]}, //category:image 图片 document 文档  media 媒体
            fileSingleSizeLimit: 1 * 1024 * 1024,
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png'
            },
            func: {
                uploadSuccess: function (file, args) {
                    if (args.state == 'SUCCESS' && args.alipay.code == '10000') {
                        infoVue.info.logo_id = args.alipay.image_id;
                        Vue.set(infoVue.info, 'logo_url', args.url);
                    } else {
                        nsalert('上传失败', 'fail');
                    }
                }
            }
        };
        webuploadUtil.init(option);
        webuploadUtil.init(option1);
        var infoVue = new Vue({
            el: '#appcon',
            data: {
                info: <?php echo empty($info) ? '{logo_url:"",background_url:""}' : json_encode($info); ?>,
                ajaxLock: false,
                columnList: <?php echo empty($columnList) ? "{}" : json_encode($columnList); ?>,
                codeList: <?php echo empty($codeList) ? "{}" : json_encode($codeList); ?>,
                current: {},
                current_type: null,
                columns: <?php echo !empty($info['columns']) ? json_encode($info['columns']) : '[]'; ?>,
            },
            methods: {
                getBackStyle: function (url) {
                    return "background: url(" + this.GetImg(this.info.background_url) + ") no-repeat center center;background-size: 100% 100%;";
                },
                setColumns: function (event) {
                    if ($(event.target).is(':checked')) {
                        this.columns.push($(event.target).val());
                    }
                    else {
                        this.columns.splice($.inArray($(event.target).val(), this.columns), 1);
                    }
                },
                showColumns: function (index) {
                    var flag = false;
                    for (var i in this.columns) {
                        if (index == this.columns[i]) {
                            flag = true;
                        }
                    }
                    return flag;
                },
                GetImg: function (url) {
                    if (url == "")return "";
                    return '<?php echo $this->imghost . '/'; ?>' + url;
                },
                openModal: function (type, data) {
                    if (typeof data != 'undefined') {
                        this.current = $.extend(true, {}, data);
                    } else {
                        this.current = $.extend(true, {}, {title: '', code: '', url: ''});
                    }
                    this.current_type = type;
                    $('#modal-form').modal('show');
                },
                delLine: function (type, key) {
                    bootbox.confirm('<span class=\'text-danger\'>确认删除？</span>', function () {
                        Vue.delete(infoVue[type], key);
                    });
                    return true;
                },
                objLen: function (obj) {
                    return Object.keys(obj).length;
                },
                saveModal: function () {
                    if (this.current.title == "") {
                        nsalert('请输入标题', 'warn');
                        return;
                    }
                    if (this.current.code == "") {
                        nsalert('请输入code', 'warn');
                        return;
                    }
                    if (this.current.url == "") {
                        nsalert('请输入链接', 'warn');
                        return;
                    }
                    Vue.set(infoVue[infoVue.current_type], this.current.code, this.current);
                    $('#modal-form').modal('hide');
                },
                save: function () {
                    if ($.isEmptyObject(this.info.title)) {
                        nsalert("请填写名称", 'warn');
                        return false;
                    }
                    if ($.isEmptyObject(this.info.background_id)) {
                        nsalert("请上传卡面", 'warn');
                        return false;
                    }
                    if ($.isEmptyObject(this.info.logo_id)) {
                        nsalert("请上传logo", 'warn');
                        return false;
                    }
                    if (this.ajaxLock) {
                        return false;
                    }
                    this.ajaxLock = true;
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo Yii::$app->urlManager->createUrl(['/alicard/backend/setting/save']); ?>',
                        dataType: 'json',
                        data: $.extend(true, {}, {
                            columnList: this.columnList,
                            info: this.info,
                            codeList: this.codeList,
                            columns: infoVue.columns
                        }),
                        success: function (resp) {
                            infoVue.ajaxLock = false;
                            if (resp.e == '0') {
                                nsalert(resp.m);
                            } else {
                                nsalert(resp.m, 'fail');
                            }
                        },
                        error: function () {
                            nsalert("网络繁忙，请稍后再试！", 'fail');
                            infoVue.ajaxLock = false;
                        }
                    });
                }
            }
        });
    </script>
<?php $this->endBlock(); ?>