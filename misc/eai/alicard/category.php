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

    .pop-content {
        position: relative;
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
        z-index: 1;
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

    .pop-form-title div {
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

    .items .items {
        padding-left: 14px;
        display: none;
        list-style: none;
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
        background: url(/backend/alicard/checkbox.png) no-repeat center center;
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

    .pop-content .pop-list {
        width: 100%;
        border: 1px solid #ddd;
    }

    .pop-list-box {
        padding: 6px 0px;
        position: relative;
        display: inline-block;
        background: #eee;
        margin: 4px 6px;
    }

    .pop-list-box span {
        margin-left: 10px;
        color: #00c79c;
    }

    .pop-list-box .list-remove {
        width: 16px;
        height: 16px;
        background: #00c79c;
        background: url('/backend/alicard/close.png') no-repeat center center;
        background-size: 80%;
        display: inline-block;
        vertical-align: middle;
        margin: 0px 5px 2px 0px;
        cursor: pointer;
    }

    .pop-content .pop-list .pop-role {
        display: inline-block;
        width: 160px;
        line-height: 40px;
        font-size: 13px;
        margin-left: 10px;
        cursor: pointer;
    }

    .pop-content .pop-list .pop-role .search-box {
        width: 100%;
    }

    .pop-content .pop-list .pop-role .search-box input {
        border: none;
        width: 100%;
        outline: none;
    }

    .pop-content .search-list {
        position: absolute;
        width: 100%;
    }

    .pop-content .search-list .search-li {
        width: 100%;
        border: 1px solid #ddd;
        background-color: #ffffff;
        height: 42px;
        line-height: 42px;
        position: relative;
        z-index: 2;
        padding: 0 10px;
        box-sizing: border-box;
    }

    .pop-content .search-list .search-li:hover {
        background-color: #ddd;
    }


</style>
<?php $this->endBlock() ?>
<div class="panel">
    <div class="panel-heading top">
        <h3 class="panel-title">卡类别</h3>
        <a href="javascript:history.go(-1);" class="back"></a>

    </div>
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="tab-base">
                    <div class="tab-content" style="padding: 15px 20px 45px 20px; min-height: 426px; display: block;">
                        <div class="tab-pane active">
                            <div class="row mbt10">
                                <div style="margin-left: 7.5px">
                                    <button type="button" class="btn btn-primary"
                                            @click="showModal(0)"><i
                                                class="demo-pli-plus"></i> 添加卡类别
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
                                        <tr id="filelist_<?php echo $v['id'] ?>" title="鼠标上下拖动可排序" style="cursor: move;"
                                            draggable="true" @dragstart="drag('<?php echo $v['id'] ?>',$event)"
                                            @drop="drop('<?php echo $v['id'] ?>',$event)">
                                            <td><?php echo empty($v['name']) ? '' : $v['name'] ?></td>
                                            <td><?php echo empty($v['range']) ? '' : $v['range'] ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary"
                                                        @click="showModal(<?php echo $v['id'] ?>,'<?php echo $v['name'] ?>','<?php echo $v['sort'] ?>')">
                                                    修改
                                                </button>
                                                <button type="button" class="btn btn-danger"
                                                        onclick="bootbox.confirm('<span class=\'text-danger\'>删除后不可恢复，确定删除该信息？</span>',function(resp){vm.removeType(resp,<?php echo $v['id']; ?>);})">
                                                    删除
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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
                                            <ul class="chosen-choices" v-if="choosePerson.length != 0">
                                                <li class="search-choice" v-for="(key,item) in choosePerson"
                                                    track-by="$index"><span v-text="item.name"></span>
                                                    <a class="search-choice-close" data-option-array-index="key"
                                                       @click="remove(item,key)"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2" style="line-height: 32px ;text-align: left">排序:</div>
                                    <div class="col-md-8">
                                        <input type="text" name="sort" value="0" placeholder="请输入卡类别领取顺序"
                                               class="form-control">
                                    </div>
                                </div>
                                <p class="col-md-offset-2" style="text-align: left">请输入数字进行排序，说明：数字小的优先级高</p>
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
                        <div class="pop-list-box list-item" v-for="(key,item) in choosePerson" track-by="$index">
                            <span v-text="item.name"></span>
                            <i class="list-remove" @click="remove(item,key)"></i>
                        </div>
                        <div class="pop-role">
                            <div class="search-box">
                                <input @keyup="search" v-model="val" type="text" placeholder="请输入查询的组织架构名称">
                            </div>
                        </div>
                    </div>
                    <div class="search-list" v-show="searchList.length !=0 && showSearch">
                        <div class="search-li" v-for="item in searchList">
                            <span v-text="item.name"></span>
                            <div :class="{active:item.isCheck}" @click.stop="choose(item,$event)"
                                 class="pop-form-control"></div>
                        </div>
                    </div>
                    <div class="pop-person-box">
                        <div class="pop-form-person">
                            <tree-list :data="personList" @choose="choose"></tree-list>
                            <script type="text/template" id="treeView">
                                <ul class="items">
                                    <li v-for="(key,val) in data" track-by="$index">
                                        <div class="pop-form-title" @click="openList(val,$event)">
                                            <i class="pop-arrow-img"></i>
                                            <span v-text="val.name"></span>
                                            <div :class="{active:val.isCheck}" class="pop-form-control"
                                                 @click.stop="choose(val,$event)"></div>
                                        </div>
                                        <tree-list v-if="val.children" :data.sync="val.children"
                                                   @choose="choose"></tree-list>
                                    </li>
                                </ul>
                            </script>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="javaScript:void(0);" class="btn-sure" @click="saveOrg">确定</a>
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
            personList: [],  //组织架构数据
            choosePerson: [], //选中的组织
            val: '', //搜索框
            searchList: [],  //搜索数据
            ajaxList: {  //请求连接
                addUrl: '<?php echo Yii::$app->urlManager->createUrl([$url_api_pre . '/organize']);?>',
                searchUrl: '<?php echo Yii::$app->urlManager->createUrl([$url_api_pre . '/search-organize']);?>',
                saveUrl: '<?php echo Yii::$app->urlManager->createUrl([$url_pre . '/save-type']);?>',
            },
            locked: false,
            showSearch: true//控制显示隐藏搜索列表
        },
        methods: {
            showModal: function (id=0, name='', sort=0) { //添加卡类别弹窗
                vm.choosePerson = [];
                if (id > 0) {
                    $('.modal-title').text('修改卡类别');
                    $.ajax({
                        type: "get",
                        cache: false,
                        url: '<?php echo Yii::$app->urlManager->createUrl([$url_api_pre . '/organize-one'])?>',
                        dataType: 'json',
                        data: {
                            id: id
                        },
                        success: function (resp) {
                            var getList = resp.d;
                            for(var i=0;i<getList.length;i++){
                                vm.chooseItem(vm.personList,getList[i].organize_id)
                            }
                        },
                        error: function () {
                            nsalert("系统繁忙，请稍后再试！");
                        }
                    });
                }
                if(id == 0){
                    $('.modal-title').text('添加卡类别');
                }
                $('#lostForm input[name=id]').val(id);
                $('#lostForm input[name=name]').val(name);
                $('#lostForm input[name=sort]').val(sort);
                $('.num').text(name.length);

                $('#modal-add').modal('show');
            },
            chooseItem:function(item,id){
                if (item.length == 0) {
                    return false;
                }
                for (var i = 0; i < item.length; i++) {
                    if (item[i].id == id) {
                        Vue.set(item[i],'isCheck',true);
                        vm.choosePerson.push(item[i])
                    }else{
                        Vue.set(item[i],'isCheck',false);
                    }
                    if ('children' in item[i]) {
                        for (var j = 0; j < item[i].children.length; j++) {
                            if (item[i].children[j].id == id) {
                                Vue.set(item[i].children[j],'isCheck',true);
                                vm.choosePerson.push(item[i].children[j])
                            }else{
                                 Vue.set(item[i].children[j],'isCheck',false);
                            }
                            vm.chooseItem(item[i].children[j])
                        }
                    }
                }
                return true;
            },
            showChoose: function () {  //选择组织架构弹窗
                if (vm.locked) {
                    this.getMess();
                }
                if(vm.choosePerson.length == 0){
                    check(vm.personList)
                }
                function check(item){
                     if (item.length == 0) {
                        return false;
                    }
                    for (var i = 0; i < item.length; i++) {
                        Vue.set(item[i],'isCheck',false);
                        if ('children' in item[i]) {
                            for (var j = 0; j < item[i].children.length; j++) {
                                Vue.set(item[i].children[j],'isCheck',false);
                                vm.chooseItem(item[i].children[j])
                            }
                        }
                    }
                    return true;
                }
                $('#modal-choose').modal('show');
                $('#modal-add').modal('hide');
            },
            getMess:function(){
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: this.ajaxList.addUrl,
                    dataType: 'json',
                    success: function (resp) {
                        if (resp.e == '0') {
                            vm.personList = resp.d;
                            console.log(vm.choosePerson)
                            for(var i=0;i<vm.choosePerson.length;i++){
                                vm.getId(vm.personList,vm.choosePerson[i].id,true)
                            }
                        }
                        vm.locked = false;
                    },
                    error: function () {
                        vm.locked = true;
                        nsalert("系统繁忙，请稍后再试！");
                    }
                });
            },
            save: function () {  //添加卡类别弹窗保存
                var checkName = checkUtil.checkEmpty('name', '卡类型名称');
                //var checkOrganize = checkUtil.checkEmpty('organize', '组织架构');
                if (!(checkName)) {
                    return false;
                }
                var id = $('#lostForm input[name=id]').val();
                var data = {}, nameArr = [], idArr = [];
                for (var i = 0; i < vm.choosePerson.length; i++) {
                    idArr.push(vm.choosePerson[i].id);
                    nameArr.push(vm.choosePerson[i].name);
                }
                data.name = $.trim($('#lostForm input[name=name]').val());
                data.id = id > 0 ? id : 0;
                data.org = idArr;
                data.sort = $('#lostForm input[name=sort]').val();
                if (!this.checkRate(data.sort)) {
                    nsalert('排序请输入大于等于0的整数', 'fail');
                    return false;
                }
                if (idArr.length == 0) {
                    nsalert('组织架构不能为空', 'fail');
                    return false;
                }
                if (this.locked)
                    return false;
                this.locked = true;
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: '<?php echo Yii::$app->urlManager->createUrl([$url_pre . '/save-type']);?>',
                    data: data,
                    dataType: 'json',
                    success: function (obj) {
                        if (obj.e == '0') {
                            var url = "<?php echo Yii::$app->urlManager->createUrl([$url_pre . '/category']);?>";
                            window.location.reload();
                            locked = false
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
            },
            removeType: function (resp, id) {
                if (this.locked)return false;
                this.locked = true;
                $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::$app->urlManager->createUrl([$url_pre . '/delete-type']);?>',
                    dataType: 'json',
                    data: {id: id},
                    success: function (resp) {
                        if (resp.e == '0') {
                            $('#filelist_' + id).remove();
                            nsalert(resp.m);
                            vm.locked = false;
                        } else {
                            nsalert(resp.m, 'fail');
                            vm.locked = false;
                        }
                    },
                    error: function () {
                        nsalert("操作失败", 'fail');
                        vm.locked = false;
                    }
                });
            },
            saveOrg: function () {  //组织架构弹窗确定
                $('#modal-choose').modal('hide');
                $('#modal-add').modal('show');
            },
            search: function () { //搜索
                vm.searchList = [];
                vm.showSearch = true;
                $.ajax({
                    url: this.ajaxList.searchUrl,
                    type: 'get',
                    dataType: 'json',
                    async: false,
                    data: {
                        'key': vm.val
                    },
                    success: function (resp) {
                        if (resp.e === 0) {
                            vm.searchList = resp.d;
                        }
                    },
                    error: function () {
                        alert("请求失败，请稍后重试！");
                    }
                });
            },
            remove: function (item, key) {
                vm.choosePerson.splice(key, 1);
                var ind = item.id;
                vm.getId(vm.personList,ind,false);
            },
            getId:function(item,ind,bool){
                if (item.length == 0) {
                    return false;
                }
                for (var i = 0; i < item.length; i++) {
                    if (item[i].id == ind) {
                        Vue.set(item[i],'isCheck',bool);
                    }else{
                         Vue.set(item[i],'isCheck',!bool);
                    }
                    if ('children' in item[i]) {
                        for (var j = 0; j < item[i].children.length; j++) {
                            if (item[i].children[j].id == ind) {
                                Vue.set(item[i].children[j],'isCheck',bool);
                            }else{
                                Vue.set(item[i].children[j],'isCheck',!bool);
                            }
                            vm.getId(item[i].children[j])
                        }
                    }
                }
                return true;
            },
            choose: function (item, event) {  //选择组织
                var el = event.currentTarget;
                console.log($(el).hasClass('active'))
                if ($(el).hasClass('active')) {
                    //取消选中
                    Vue.set(item, 'isCheck', false);
                    var id = item.id; //获取点击的id
                    var pid = item.pid; //获取点击的pid

                    for (var i = 0; i < vm.choosePerson.length; i++) {
                        if (vm.choosePerson[i].id == id) {
                            vm.choosePerson.splice(i, 1);
                        }
                    }
                    // if(item.children){
                    //     for(var i = 0;i<item.children.length;i++){
                    //         this.choose(item.children[i],event)
                    //     }
                    // }

                    // for(var i = 0;i<vm.personList.length;i++){
                    //     if(vm.personList[i].id == pid){ //当点击取消时父亲取消选中
                    //         Vue.set(vm.personList[i],'isCheck',false);
                    //     }
                    // }
                } else {
                    //选中
                    for (var i = 0; i < vm.choosePerson.length; i++) {
                        if (vm.choosePerson[i].id == item.id) { //去重
                            return false;
                        }
                    }
                    Vue.set(item, 'isCheck', true);
                    vm.choosePerson.push(item);
                    // if(item.children){
                    //     for(var i = 0;i<item.children.length;i++){
                    //         this.choose(item.children[i],event)
                    //     }
                    // }
                }
            },
            checkRate: function (input) {
                var re = /^[\d]+$/;
                if (!re.test(input)) {
                    return false;
                }
                return true;
            },
            drag: function (ind, event) {
                event.dataTransfer.setData('id', ind);
            },
            drop: function (ind, event) {
                var e = event.currentTarget;
                var dragId = event.dataTransfer.getData('id');
                // var shan = vm.list.splice(dragId, 1);
                // vm.list.splice(ind, 0, shan[0]);

            },
        },
        components: {
            'tree-list': {
                name: 'tree-list',
                template: '#treeView',
                props: {
                    data: {}
                },
                methods: {
                    openList: function (item, event) {  //文件夹的开关
                        var el = event.currentTarget;
                        $(el).toggleClass('open');
                        if (item.children) {
                            $(el).siblings('.items').toggle()
                        }
                    },
                    choose: function (item, event) { //组件中选择组织
                        this.$emit('choose', item, event);
                    }
                }
            }
        }
    });
    vm.getMess();

    $('body').on('dragover', 'table tr', function (e) {
        e.preventDefault();
    })

    $('body').click(function () {
        vm.showSearch = false;
    })

    $('.pop-role').click(function (e) {
        e.stopPropagation();
    })
</script>
<?php $this->endBlock() ?>
