<?php
echo $this->set('title', '卡类别');
$url_pre = Yii::$app->controller->module->appkey . '/' . Yii::$app->controller->id;
$url_api_pre = Yii::$app->controller->module->appkey . '/api/api';
?>

<?php $this->beginBlock('cssText', 'append') ?>
<style>
    .form-group .col-md-8 .table {
        margin-bottom: 0px !important;
    }

    .row.mbt10 {
        margin-bottom: 12px !important;
    }

    .table td button.btn-default {
        border-radius: 5px;
    }

    .btns .btn {
        padding: 0 40px;
        line-height: 30px;
        margin: 5px 30px 5px 0;
    }

    .modal-body {
        text-align: left;
    }

    .modal-body .col-md-8 {
        position: relative;
    }

    .modal-body .col-md-8 > input {
        padding-right: 35px;
    }

    .modal-body .col-md-8 > span {
        position: absolute;
        top: 50%;
        -webkit-transform: translate3d(0, -50%, 0);
        transform: translate3d(0, -50%, 0);
        right: 18px;
    }

    .chosen-container-multi .chosen-choices {
        min-height: 40px
    }

    .chosen-container-multi .chosen-choices li.search-choice {
        position: relative;
        margin: 3px 5px 3px 0;
        padding: 5px 35px 5px 6px;
        max-width: 100%;
        background-color: #eee;
        color: #00c79c;
        line-height: 23px;
        cursor: default;
    }

    .chosen-container-multi .chosen-choices li.search-choice .search-choice-close {
        color: #bfc4ca
    }

    .chosen-container-multi .chosen-choices li.search-choice .search-choice-close {
        display: block;
        width: 1.7em;
        height: 1.7em;
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    .chosen-container-multi .chosen-choices li.search-choice .search-choice-close:before {
        width: 1.3em;
        height: 2px;
    }

    .chosen-container-multi .chosen-choices li.search-choice .search-choice-close:after {
        height: 1.3em;
        width: 2px;
    }

    .pop-content .pop-header {
        text-align: center;
        border-bottom: 1px solid #ddd;
        position: relative;
        padding: 6px 0;
    }

    .pop-content .pop-list input {
        height: 35px;
    }

    .pop-content .pop-person-box {
        font-size: 0;
        width: 100%;
        height: 220px;
        background: #f5f5fe;
        box-sizing: border-box;
        margin-top: 16px;
    }

    .pop-content .pop-person-box .pop-form-person {
        position: relative;
        font-size: 14px;
        width: 100%;
        display: inline-block;
        background: #fff;
        height: 100%;
        overflow: auto;
    }

    .items {
        font-size: 14px;
        display: inline-block;
        background: #fff;
        width: 100%;
        min-height: 100%;
        position: relative;
        box-sizing: border-box;
        padding-left: 0;
        list-style: none;
        margin-bottom: 0;
    }

    .pop-form-person .pop-form-title {
        height: 36px;
        line-height: 36px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        padding: 0 5px;
        cursor: pointer;
        margin-right: 20px;
    }

    .pop-form-person .pop-form-title span,
    .pop-form-person .pop-form-title i {
        cursor: pointer;
        position: relative;
        z-index: 2;
    }

    .pop-arrow-img {
        display: inline-block;
        width: 14px;
        height: 14px;
        background: url('/backend/alicard/arrow.png') no-repeat center center;
        background-size: 100%;
    }

    .pop-form-title.open span {
        color: #00c79c;
    }

    .pop-form-title.open .pop-arrow-img {
        background: url('/backend/alicard/arrow-active.png') no-repeat center center;
        background-size: 100%;
    }

    .pop-form-title divv {
        width: 20px;
        height: 20px;
        display: inline-block;
        background: url('/backend/alicard/checkbox.png') no-repeat center center;
        background-size: 100%;
        cursor: pointer;
    }

    .pop-form-title div.active {
        background: url('/backend/alicard/checkbox-active.png') no-repeat center center;
        background-size: 100%;
    }

    .items .item {
        padding-left: 14px;
        display: none;
        list-style: none;
    }

    .pop-form-title div {
        width: 20px;
        height: 20px;
        display: inline-block;
        background: url('../checkbox.png') no-repeat center center;
        background-size: 100%;
        cursor: pointer;
    }

    .pop-form-control {
        float: right;
        margin: 8px;
        position: absolute;
        right: 0;
        z-index: 2;
        width: 20px;
        height: 20px;
        display: inline-block;
        background: url(../checkbox.png) no-repeat center center;
        background-size: 100%;
        cursor: pointer;
    }

    .pop-content .pop-person-box .pop-form-role {
        font-size: 14px;
        width: 51%;
        display: inline-block;
        height: 100%;
        vertical-align: top;
        background: #fff;
        overflow: auto;
        overflow-y: auto;
        padding-top: 4px;
        box-sizing: border-box;
    }

    .pop-content .pop-person-box .pop-form-role .pop-form-title {
        height: 36px;
        line-height: 36px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        padding: 0 5px;
        cursor: pointer;
        padding-right: 20px;
        position: relative;
    }

    .pop-content .pop-person-box .pop-form-role .pop-form-title img {
        width: 30px;
        height: 30px;
        margin-top: -3px;
    }

    .pop-content .pop-person-box .pop-form-role .pop-form-title p {
        display: inline-block;
        line-height: 36px;
        vertical-align: top;
    }

    .pop-form-role .pop-form-title div {
        margin: 8px;
    }

    .pop-form-title div {
        width: 20px;
        height: 20px;
        display: inline-block;
        background: url('../checkbox.png') no-repeat center center;
        background-size: 100%;
        cursor: pointer;
    }

    .pop-form-title div.active,
    {
        background: url('../checkbox-active.png') no-repeat center center;
        background-size: 100%;
    }

    .pop-form-person .pop-form-title span,
    .pop-form-person .pop-form-title i {
        cursor: pointer;
        position: relative;
        z-index: 2;
    }

    .pop-form-person .pop-form-title.active::before {
        content: '';
        position: absolute;
        left: 0;
        width: 100%;
        height: 36px;
        background-color: #D5F8F1;
        opacity: 0.5;
    }

    .pop-form-person .pop-form-title.active .arrows {
        position: absolute;
        right: -1px;
        margin-top: 11px;
        border: 7px solid rgba(255, 255, 255, 0);
        border-right: 7px solid #fff;
    }

    .pop-content .search-list {
        width: 94%;
        margin-left: 3%;
    }

    .pop-content .search-list .search-li {
        width: 100%;
        border: 1px solid #ddd;
        background-color: #ffffff;
        height: 42px;
        line-height: 42px;
        position: relative;
        z-index: 2;
    }

    .pop-form-control.active {
        background: url('/backend/alicard/checkbox-active.png') no-repeat center center;
        background-size: 100%;
    }

    .pop-content .pop-btn-box {
        padding: 2% 0px;
        border-top: 1px solid #ddd;
        margin-top: 16px;
        background: #f5f5fe;
    }
</style>
<?php $this->endBlock() ?>
<div class="panel">
    <div class="panel-heading top">
        <h3 class="panel-title">卡类别</h3>
    </div>
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="tab-base">
                    <div class="tab-content" style="padding: 15px 20px 45px 20px; min-height: 426px; display: block;">
                        <div class="tab-pane active">
                            <div class="row mbt10">
                                <div style="margin-left: 7.5px">
                                    <button type="button" class="btn btn-primary" style="border-radius: 5px"
                                            @click="showModal">添加卡类别
                                    </button>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th width="25%">卡类别</th>
                                    <th width="50%">领取人员(部门/学院)</th>
                                    <th width="25%">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($data)): ?>
                                    <?php foreach ($data as $v): ?>
                                        <tr id="data_id_"<?php echo $v['id'] ?>>
                                            <td><?php echo $v['name'] ?></td>
                                            <td><?php echo $v['range'] ?></td>
                                            <td>
                                                <button type="button" class="btn btn-default">修改</button>
                                                <button type="button" class="btn btn-default">删除</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <!--<tr id="">
                                    <td>学生卡</td>
                                    <td>本科生丶研究生</td>
                                    <td>
                                        <button type="button" class="btn btn-default">修改</button>
                                        <button type="button" class="btn btn-default">删除</button>
                                    </td>
                                </tr>-->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-add" role="dialog" tabindex="-1" aria-labelledby="modal-add">
    <div class="modal-dialog modal-lg" style="max-width: 750px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="ion-close"></i></button>
                <h4 class="modal-title">添加卡类别</h4>
            </div>
            <div class="modal-body" style="max-height:350px;overflow-y:auto;padding-top:2%;">
                <div class="row">
                    <form id="lostForm">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2" style="line-height: 32px ;text-align: left">卡类别:</div>
                                    <div class="col-md-8">
                                        <input type="text" name="name" maxlength="6"
                                               oninput="$('.num').text($(this).val().length)"
                                               placeholder="请输入卡类别名称,做多6字"
                                               class="form-control">
                                        <span><span class="num">0</span>/6</span>
                                    </div>
                                </div>
                                <p class="col-md-offset-2" style="text-align: left">请设置卡类别名称,如:教师卡丶学生卡</p>
                            </div>
                            <div class="form-group">
                                <div class="row range">
                                    <div class="col-md-2" style="line-height: 32px ;text-align: left">领取范围:</div>
                                    <div class="col-md-8">
                                        <button type="button" class="btn btn-primary" style="margin-bottom: 10px"
                                                @click="showChoose">选择组织架构
                                        </button>
                                        <div class="chosen-container chosen-container-multi" style="width: 100%;"
                                             title=""
                                             id="columns_chosen">
                                            <ul class="chosen-choices">
                                                <li class="search-choice"><span>姓名</span>
                                                    <a class="search-choice-close" data-option-array-index="0"></a>
                                                </li>
                                                <li class="search-choice"><span>学工号</span>
                                                    <a class="search-choice-close" data-option-array-index="1"></a>
                                                </li>
                                                <li class="search-choice"><span>卡类别</span>
                                                    <a class="search-choice-close" data-option-array-index="3"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id">
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javaScript:void(0);" class="btn-sure" @click="save">确定</a>
                <a href="javaScript:void(0);" data-dismiss="modal" class="btn-cancel">取消</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-choose" role="dialog" tabindex="-1" aria-labelledby="modal-choose">
    <div class="modal-dialog modal-lg" style="max-width: 750px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="ion-close"></i></button>
                <h4 class="modal-title">选择组织架构</h4>
            </div>
            <div class="modal-body" style="max-height:350px;overflow-y:auto;padding-top:2%;">
                <div class="pop-content">
                    <div class="pop-list">
                        <input type="text" placeholder="请输入查询的组织架构名称" class="form-control">
                    </div>
                    <div class="pop-person-box">
                        <div class="pop-form-person">
                            <tree-list :data.sync="data" :permit="permit" :auth="auth"></tree-list>
                            <script type="text/template" id="treeView">
                                <ul class="items">
                                    <li v-for="(key,val) in data">
                                        <div class="pop-form-title open">
                                            <i class="pop-arrow-img"></i>
                                            <span>瑞雷森移动校园</span>
                                            <div class="pop-form-control"></div>
                                        </div>
                                        <tree-list v-if="val.list" :data.sync="val.list" :permit="permit"
                                                   :auth="auth"></tree-list>
                                    </li>
                                </ul>
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javaScript:void(0);" class="btn-sure" @click="save">确定</a>
                <a href="javaScript:void(0);" data-dismiss="modal" class="btn-cancel">取消</a>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('jsText', 'append') ?>
<script type="text/javascript">
    var vm = new Vue({
        el: '#container',
        data: {
            personList: [],
            organizeUrl: '<?php echo Yii::$app->urlManager->createUrl([$url_api_pre . '/organize']);?>?>'

        },
        methods: {
            showModal: function () {
                $('#modal-add').modal('show');
            },
            showChoose: function () {
                $('#modal-add').modal('hide');
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: this.organizeUrl,
                    dataType: 'json',
                    success: function (resp) {
                        if (resp.e == '0') {
                            console.log(resp.d)
                            vm.personList = resp.d;
                        }
                    },
                    error: function () {
                        locked = false;
                        nsalert("系统繁忙，请稍后再试！");
                    }
                });
                $('#modal-choose').modal('show');
            },
            save: function () {
                var checkName = checkUtil.checkEmpty('name', '卡类型名称');
                var checkOrganize = checkUtil.checkEmpty('organize', '组织架构');

                if (!(checkName && checkOrganize)) {
                    return false;
                }

                var id = $("#lostForm input[name=id]").val();
                var data = {
                    id: id > 0 ? id : undefined,
                    name: $("#lostForm input[name=name]").val(),
                    organize: $("#lostForm input[name=organize]").val(),
                };
                if (locked)
                    return false;
                locked = true;
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: this.addUrl,
                    data: data,
                    dataType: 'json',
                    success: function (obj) {
                        if (obj.e == '0') {
                            var url = "<?php echo Yii::$app->urlManager->createUrl([$url_pre . '/category']);?>";
                            window.location.href = url;
                        }
                        else {
                            nsalert(obj.m, 'fail', 2000);
                            locked = false;
                        }
                    },
                    error: function () {
                        locked = false;
                        nsalert("系统繁忙，请稍后再试！");
                    }
                });
                return false;
            }
        }
    });
</script>
<?php $this->endBlock() ?>
