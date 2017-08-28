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
                                            <div>
                                                <a href="javascript:void(0)" class="btn btn-success"
                                                   onclick="edit(<?php echo $one["id"]; ?>,'<?php echo $one["name"] ?>','<?php echo $one["require"] ?>','<?php echo $one["type"] ?>','<?php echo $one["office_id"] ?>','<?php echo $one["hall_id"] ?>','<?php echo $one["college_id"] ?>','<?php echo $one["college_name"] ?>','<?php echo $one["office_name"] ?>','<?php echo $one["hall_name"] ?>')">修改</a>
                                                <a href="javascript:void(0)" class="btn btn-success"
                                                   onclick="edit(<?php echo $one["id"]; ?>)">指派业务</a>
                                                <a href="javascript:void(0)" class="btn btn-danger"
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
                        <div class="col-md-12">
                            <form class="form-horizontal" id="add-business">
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
                                <div class="form-group">
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
                                <!-- 处室 -->
                                <div class="form-group" style="display:none" id="business_type_office">
                                    <label class="col-md-4 control-label" for="office_id">
                                        <font>
                                            <font>所属处室</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <select name="office_id" id="" class="form-control">
                                            <option value="0">请选择处室</option>
                                            <?php if (!empty($office)) foreach ($office as $one): ?>
                                                <option value="<?php echo $one['id']; ?>"><?php echo $one['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- 大厅 -->
                                <div class="form-group" style="display:none" id="business_type_hall">
                                    <label class="col-md-4 control-label" for="hall_id">
                                        <font>
                                            <font>所属大厅</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <select name="hall_id" id="hall_business" class="form-control">
                                            <option value="0">请选择大厅</option>
                                            <?php if (!empty($hall)) foreach ($hall as $one): ?>
                                                <option value="<?php echo $one['id']; ?>"><?php echo $one['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- 处室 -->
                                <div class="form-group" style="display:none" id="business_type_office">
                                    <label class="col-md-4 control-label" for="hall_id">
                                        <font>
                                            <font>所属处室：</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <select name="office_id" id="hall_business_1" class="form-control">
                                            <option value="0">请选择处室</option>
                                            <?php if (!empty($office)) foreach ($office as $one): ?>
                                                <option value="<?php echo $one['id']; ?>"><?php echo $one['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- 学院 -->
                                <div class="form-group" style="display:none" id="business_type_college">
                                    <label class="col-md-4 control-label" for="college_id">
                                        <font>
                                            <font>所属学院</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <select name="college_id" id="" class="form-control">
                                            <option value="0">请选择学院</option>
                                            <?php if (!empty($college)) foreach ($college as $one): ?>
                                                <option value="<?php echo $one['id']; ?>"><?php echo $one['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- 办理老师 -->
                                <div class="form-group" style="display:none" id="business_manager">
                                    <label class="col-md-4 control-label" for="manager_id">
                                        <font>
                                            <font>办理老师</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <select name="manager_id" id="sel_tea" class="form-control">
                                            <option value="0" disabled>请选择老师</option>
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
                                        <input type="text" name="window">
                                    </div>
                                </div>
                                <!-- 已选办理老师 -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="demo-hor-inputemail">已选办理老师:</label>
                                    <div class="col-md-6">
                                        <div style="height: 32px;" type="text" name="realname" class="form-control" id="selecteds">
                                            <a type="button" class="btn btn-info btn-xs hide selected" name="selected">名字<span>×</span></a>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="bak_hidden" >
                                <input type="hidden" name="businessid" value="0">
                            </form>
                        </div>
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
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="typ">
                                        <font>
                                            <font>业务类型</font>
                                        </font>
                                    </label>
                                    <div class="col-md-6">
                                        <input name="type" readonly type="text" placeholder="业务类型"
                                               class="form-control input-md">
                                    </div>
                                </div>

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
        $('input[name=type]').val(type);
        $('input[name=office_id]').val(office);
        $('input[name=hall_id]').val(hall);
        $('input[name=college_id]').val(college);

        var position = $('#bus_position').clone();
        if(college_name != ''){
            text = '所属学院'
            addPosition(position,i,college_name);
        }
        if(hall_name != ''){
            text = '所属大厅'
            addPosition(position,i,hall_name);
        }
        if(office_name != ''){
            text = '所属处室'
            addPosition(position,i,office_name);
        }
        $('#modal_edit').modal('show');
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
        var type_idx = $(this).val();
//        $("div[id^='business_type_']").hide();
        $(this).closest('div.form-group').nextAll().hide();

        $("#business_type_" + type_idx).show();

        if (type_idx != 2) {//不是大厅业务
            $("#business_type_" + type_idx).change(function () {

                var dependence_idx = $(this).find('select').val();

                if (type_idx == 1) {
                    var idx = 3
                } else if (type_idx == 3) {
                    var idx = 2;
                } else {
                    var idx = type_idx;
                }

                if (lock) {
                    return;
                }
                lock = true;
                $.ajax({
                    type: 'get',
                    data: {dependence_id: dependence_idx, type: idx},
                    url: '<?php echo Yii::$app->urlManager->createUrl(['/backend/business/managers']);?>',
                    cache: false,
                    dataType: 'json',
                    success: function (args) {
                        if (args.e == 0) {
                            var html = '<option value=0>请选择老师</option>';
                            if (args.d != '') {
                                $.each(args.d, function (k, v) {
                                    html += '<option value="' + v.id + '">' + v.name + '</option>'
                                })
                            }
                            $('#business_manager').find('select[name=manager_id]').html(html);
                        } else {

                        }
                        lock = false;
                    },
                    error: function () {
                        lock = false;
                    }
                })

                $('#business_manager').show();
            });
        } else {//大厅业务
            $("#hall_business").change(function () {
                $("#business_type_" + type_idx).show();
                var idx = 3;
                $("#business_type_4").show();
                $("#hall_business_1").change(function () {
                    var dependence_idx = $(this).val();
                    if (lock) {
                        return;
                    }
                    lock = true;
                    $.ajax({
                        type: 'get',
                        data: {type: idx, dependence_id: dependence_idx},
                        url: '<?php echo Yii::$app->urlManager->createUrl(['/backend/business/managers']);?>',
                        cache: false,
                        dataType: 'json',
                        success: function (args) {
                            if (args.e == 0) {
                                var html = '<option value=0>请选择老师</option>';
                                $.each(args.d, function (k, v) {
                                    html += '<option value="' + v.id + '">' + v.name + '</option>'
                                })
                                $('#business_manager').find('select[name=manager_id]').html(html);
                            } else {

                            }
                            lock = false;
                        },
                        error: function () {
                            lock = false;
                        }
                    })
                    $('#business_manager').show();
                    $('#business_window').show();
                });
            })
        }
    });

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
    $('#sel_tea').change(function(){

    });
</script>
<?php $this->endBlock() ?>

