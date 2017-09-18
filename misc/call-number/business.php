<?php
$cdn = Misc::get('common.schema') . Misc::get('common.cdndomain');
?>
<?php $this->addJsFile([
    '/framework/nifty/plugins/select2/js/select2.js',
]) ?>
<?php $this->addCssFile([
    '/framework/nifty/plugins/select2/css/select2.min.css',
]) ?>

<?php $this->beginBlock('pageTitle') ?>
<div id="page-title">
    <h1 class="page-header text-overflow">业务管理</h1>
</div>
<?php $this->endBlock() ?>
<style type="text/css">
    .hide{
        display: none;
    }
    .selected{
        margin-left: 5px;
        margin-top: 3px;
        position:relative;
    }
    span{
        position:absolute;right:10px;cursor: pointer;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    业务列表
                    <button id="add" class="btn btn-primary" style="float: right;margin-top: 9px;">添加业务</button>
                </h3>
            </div>
            <div class="panel-body">
                <div class="bootstrap-table">
                    <div class="fixed-table-container">
                        <div class="fixed-table-body">
                            <table class="demo-add-niftyradio table table-hover">
                                <thead>
                                <tr>
                                    <th>业务id</th>
                                    <th>业务名称</th>
                                    <th>所需资料</th>
                                    <th>业务类型</th>
                                    <th>处室id</th>
                                    <th>学院id</th>
                                    <th>大厅id</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($data)) foreach ($data as $one): ?>
                                    <tr>
                                        <td><?php echo $one['id'] ?></td>
                                        <td><?php echo $one['name'] ?></td>
                                        <td width=""><?php echo $one['require'] ?></td>
                                        <td width=""><?php echo $one['type_name'] ?></td>
                                        <td width=""><?php echo $one['office_name'] ?></td>
                                        <td width=""><?php echo $one['college_name'] ?></td>
                                        <td width=""><?php echo $one['hall_name'] ?></td>
                                        <td width=""><?php echo date('Y-m-d,H:i:s', $one['createtime']) ?></td>
                                        <td>
                                            <div  class="btn-group mar-rgt">
                                                <a href="javascript:void(0)" class="btn btn-success"
                                                   onclick="edit(<?php echo $one["id"]; ?>,'<?php echo $one["name"] ?>','<?php echo $one["require"] ?>','<?php echo $one["type"] ?>','<?php echo $one["office_id"] ?>','<?php echo $one["hall_id"] ?>','<?php echo $one["college_id"] ?>','<?php echo $one["college_name"] ?>','<?php echo $one["office_name"] ?>','<?php echo $one["hall_name"] ?>')">修改</a>
                                                <a href="javascript:void(0)" class="btn btn-info"
                                                   onclick="">详情</a><a href="javascript:void(0)" class="btn btn-danger"
                                                   onclick="confirm('确认删除')?del(<?php echo $one['id'] ?>) : false">删除</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="fixed-table-footer">
                            <?php echo Yii::$app->page; ?>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('modal') ?>
<div class="bootbox modal fade in" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="pci-cross pci-circle"></i>
                </button>
                <h4 class="modal-title">
                    <font>
                        <font>添加业务</font>
                    </font>
                </h4>
            </div>
            <div class="modal-body">
                <div class="bootbox-body">
                    <div class="row">
                        <form class="form-horizontal" id="add-business">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="name">
                                        <font>
                                            <font>业务名称</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <input name="name" type="text" placeholder="业务名称" class="form-control input-md">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="require">
                                        <font>
                                            <font>所需资料</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <textarea rows="5" name="require" type="text" placeholder="所需资料"
                                                  class="form-control input-md"></textarea>
                                    </div>
                                </div>

                                <div class="form-group" id="business_type">
                                    <label class="col-md-4 control-label" for="type">
                                        <font>
                                            <font>业务类型</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <select name="type" id="add_type" class="form-control">
                                            <option value="0">请选择业务类型</option>
                                            <?php if (!empty($type)) foreach ($type as $one): ?>
                                                <option value="<?php echo $one['id']; ?>"><?php echo $one['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- 大厅 -->
                                <div class="form-group" style="display:none" id="business_type_2">
                                    <label class="col-md-4 control-label" for="hall_id">
                                        <font>
                                            <font>所属大厅</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <select name="hall_id" id="selHall" class="form-control">
                                            <option value="0">请选择大厅</option>
                                            <?php if (!empty($hall)) foreach ($hall as $one): ?>
                                                <option value="<?php echo $one['id']; ?>"><?php echo $one['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- 处室 -->
                                <div class="form-group" style="display:none" id="business_type_1">
                                    <label class="col-md-4 control-label" for="office_id">
                                        <font>
                                            <font>所属处室</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <select name="office_id" id="sel_office" class="form-control">
                                            <option value="0">请选择处室</option>
                                            <?php if (!empty($office)) foreach ($office as $one): ?>
                                                <option value="<?php echo $one['id']; ?>"><?php echo $one['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- 学院 -->
                                <div class="form-group" style="display:none" id="business_type_3">
                                    <label class="col-md-4 control-label" for="college_id">
                                        <font>
                                            <font>所属学院</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <select name="college_id" id="sel_college" class="form-control">
                                            <option value="0">请选择学院</option>
                                            <?php if (!empty($college)) foreach ($college as $one): ?>
                                                <option value="<?php echo $one['id']; ?>"><?php echo $one['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- 办理窗口 -->
                                <div class="form-group" style="display:none" id="business_window">
                                    <label class="col-md-4 control-label" for="window">
                                        <font>
                                            <font>办理窗口</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="window">
                                    </div>
                                </div>
                                <!-- 办理老师 -->
                                <div class="form-group"  id="business_manager">
                                    <label class="col-md-4 control-label" for="teacher">
                                        <font>
                                            <font>办理老师</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <select name="teacher" id="sel_teacher" class="form-control">
                                            <option disabled  selected  value="0">请选择老师</option>
                                        </select>
                                    </div>
                                </div>


                                <!-- 已选办理老师 -->
                                <div class="form-group"  id="manager">
                                    <label class="col-md-4 control-label" for="demo-hor-inputemail">已选办理老师:</label>
                                    <div class="col-md-6" id="sel_selecteds"></div>

                                    <div type="button" class="hide selected form-control" name="selected">名字 <span>x</span></div>
                                </div>


                                <input type="hidden" name="bak_hidden" >
                                <input type="hidden" name="businessid" value="0">

                            </div>
                            <div class="col-md-4"></div>
                        </form>


                    </div>

                    <script></script>
                </div>
            </div>
            <div class="modal-footer">
                <button data-bb-handler="success" type="button" class="btn btn-primary" id="business-save">
                    <font>
                        <font>保存</font>
                    </font>
                </button>
                <button data-bb-handler="success" type="button" class="btn btn-danger" id="cancel">
                    <font>
                        <font>取消</font>
                    </font>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="bootbox modal fade in" id="modal_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="pci-cross pci-circle"></i>
                </button>
                <h4 class="modal-title">
                    <font>
                        <font>修改业务</font>
                    </font>
                </h4>
            </div>
            <div class="modal-body">
                <div class="bootbox-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal" id="edit-business">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="name">
                                        <font>
                                            <font>业务名称</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <input name="name" type="text" placeholder="业务名称" class="form-control input-md">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="require">
                                        <font>
                                            <font>所需资料</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <textarea rows="5" cols="10" name="require" type="text" placeholder="所需资料"
                                                  class="form-control input-md"></textarea>
                                    </div>
                                </div>
                                <!--<div class="form-group">
                                    <label class="col-md-4 control-label" for="typ">
                                        <font>
                                            <font>业务类型</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <input name="type" readonly type="text" placeholder="业务类型"
                                               class="form-control input-md">
                                    </div>
                                </div>-->

                                <div class="form-group" style="display:none" id="bus_position">
                                    <label class="col-md-4 control-label" for="position">
                                        <font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <input name="position" disabled type="text" placeholder=""
                                               class="form-control input-md">
                                    </div>
                                </div>

                                <input type="hidden" name="businessid" value="0">
                                <input type="hidden" name="office_id" value="0">
                                <input type="hidden" name="college_id" value="0">
                                <input type="hidden" name="hall_id" value="0">
                            </form>
                        </div>
                    </div>
                    <script></script>
                </div>
            </div>
            <div class="modal-footer">
                <button data-bb-handler="success" type="button" class="btn btn-primary" id="save_edit">
                    <font>
                        <font>保存</font>
                    </font>
                </button>
            </div>
        </div>
    </div>
</div>
<?php $this->endBlock() ?>
<?php $this->beginBlock('jsText') ?>
<script type="text/javascript">
    var lock = false;
    var i = 0;

    //修改
    function edit(id, name, require, type, office, hall, college, college_name, office_name, hall_name) {
        $('div[id^=bus_position_]').remove();
        $('input[name=businessid]').val(id);
        $('input[name=name]').val(name);
        $('textarea[name=require]').val(require);
//        $('input[name=type]').val(type);
        $('input[name=office_id]').val(office);
        $('input[name=hall_id]').val(hall);
        $('input[name=college_id]').val(college);

//        $('textarea[name=require]').closest('.form-group').nextAll().remove();
        $('.modal-header .modal-title').find('font').text('修改业务');
        $

        if(college_name != ''){
            text = '所属学院';

        }
        if(hall_name != ''){
            text = '所属大厅';
        }
        if(office_name != ''){
            text = '所属处室';
        }


        $('#modal').modal('show');
    }

    function addPosition(position,i,name){
        position.attr('id','bus_position_'+i);
        position.find('font').text(text);
        position.find('div input').val(name);
        position.show();
        $('#edit-business').append(position);
        i++;
    }

    $('#save_edit').on('click', function () {
        if (lock) {
            return;
        }
        lock = true;
        $.ajax({
            type: 'POST',
            data: $('#edit-business').serialize(),
            url: '<?php echo Yii::$app->urlManager->createUrl(['/backend/business/business-save']);?>',
            cache: false,
            dataType: 'json',
            success: function (args) {
                if (args.e == 0) {
                    $('#modal').modal('hide');
                    alertUtil.alert('保存成功', function () {
                        window.location.reload();
                    });
                } else {
                    alertUtil.alert(args.m);
                }
                lock = false;
            },
            error: function () {
                lock = false;
            }
        });
    });

    //删除
    function del(id) {
        if (lock) {
            return;
        }
        lock = true;
        $.ajax({
            type: 'POST',
            data: {id: id},
            url: '<?php echo Yii::$app->urlManager->createUrl(['/backend/business/business-del']);?>',
            cache: false,
            dataType: 'json',
            success: function (args) {
                if (args.e == 0) {
                    alertUtil.alert('删除成功', function () {
                        window.location.reload();
                    });
                } else {
                    alertUtil.alert(args.m);
                }
                lock = false;
            },
            error: function () {
                lock = false;
            }
        });
    }

    //添加
    $('#add').on('click', function () {
        $('.modal-header .modal-title').find('font').text('添加业务');
        $('#add_type').val(0).closest('div.form-group').nextAll().hide();
        $('input[name=businessid]').val(0);
        $('input[name=name]').val('');
        $('input[name=require]').val('');
        $('input[name=type]').val(0);
        $('input[name=office_id]').val(0);
        $('textarea[name=college_id]').val(0);
        $('textarea[name=hall_id]').val(0);
        $('#modal').modal('show');
    });

    $("#add_type").change(function () {
        changeType("#add_type");
    });

    function changeType(exp){
        $('#selHall').val(0);
        $('#sel_office').val(0);
        $('#sel_college').val(0);
        $('#sel_teacher').html('<option disabled selected value="0">请选择老师</option>');
        hallInit();
        $(exp).closest('div.form-group').nextAll().hide();
        var type = $(exp).val();
        if(type == 0) return;
        //根据type 显示
        $('#business_type_'+type).show();
        if(type == 2) {
            $('#business_type_1').show();
            $('#business_window').show();
        }
        //办理老师下拉列表
        $('#business_manager').show();
        //已选老师
        $('#manager').show();
    }


    $('#selHall').change(function(){
        hallInit();
        $('#sel_teacher').find('option[value=0]').attr('selected','selected');
    });

    //添加大厅业务时，将已选择的管理员删除
    function hallInit(){
        $('input[name=window]').val(null);
        $('#sel_selecteds').children().remove();
        $('input[name^=manager_id]').remove();

    }

    //根据处室/学院 获得老师
    $('div[id^=business_type]').find('select[id^=sel_]').change(function(){
        hallInit();
        var dependence_idx = $(this).val();
        var type = $("#add_type").val();
        if (lock) {
            return;
        }
        lock = true;
        $.ajax({
            type: 'get',
            data: {dependence_id: dependence_idx, type: type},
            url: '<?php echo Yii::$app->urlManager->createUrl(['/backend/business/managers']);?>',
            cache: false,
            dataType: 'json',
            success: function (args) {
                if (args.e == 0) {
                    var html = '<option disabled selected value=0>请选择老师</option>';
                    if (args.d != '') {
                        $.each(args.d, function (k, v) {
                            html += '<option value="' + v.id + '">' + v.name + '</option>'
                        })
                    }
                    $('#business_manager').find('select[name=teacher]').html(html);
                } else {

                }
                lock = false;
            },
            error: function () {
                lock = false;
            }
        })

    })

    //显示已选老师
    $('#sel_teacher').change(function(){
        var window = $('input[name=window]').val();
        var uid = $(this).val();
        var html = $(this).find('option[value='+uid+']').html();
        if (!html) return false;

        var send_html = html;
        if(window) {
            send_html = html + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;窗口号：" + window;
            html += '窗口号：'+ window;
            uid = uid + '_' + window;
        }
        var span_demo = '<span>×</span>';
        if($('div[value='+html+']').length < 1 && $('input[value='+uid+']').length <1) {
            $('input[name=bak_hidden]').clone(true).attr('name','manager_id[]').val(uid).appendTo('#add-business');
            $('div[name=selected]').clone(true).removeClass('hide').removeAttr('name').attr('value',html).html(send_html+span_demo).appendTo('#sel_selecteds').find('span').click(function(){
                $(this).closest('div').remove();
                $('input[value='+uid+']').remove();
            });
        }
    });


    //======================================================
    //======================================================
    //======================================================
    $('#business-save').on('click', function () {
        $('#business_type').hide();
        if (lock) {
            return;
        }
        lock = true;
        $.ajax({
            type: 'POST',
            data: $('#add-business').serialize(),
            url: '<?php echo Yii::$app->urlManager->createUrl(['/backend/business/business-save']);?>',
            cache: false,
            dataType: 'json',
            success: function (args) {
                if (args.e == 0) {
//                    $('#modal').modal('hide');
                    alertUtil.alert('保存成功', function () {
//                        window.location.reload();
                    });
                } else {
                    alertUtil.alert(args.m);
                }
                lock = false;
            },
            error: function () {
                lock = false;
            }
        });
    });
    $('#cancel').click(function(){
        window.location.reload();
    });
</script>
<?php $this->endBlock() ?>

