<?php $this->set('title', '推送中心') ?>
<div class="row queue_id">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title">
                    推送队列设置
                </div>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div id="se_notice" class="col-md-1" style="line-height: 32px;text-align: center;">推送队列设置：</div>
                    <div class="col-md-5">
                        <input type="text" name="queue_order_id" title="设置的数字表示：向当前排队人数中的第几位推送消息" class="form-control"
                               placeholder="请输入纯数字或者数字之间使用英文标点符号隔开(例如：1,2,3)"
                               value="<?php echo empty($data['queue_order_id']) ? '' : $data['queue_order_id']; ?>">
                    </div>
                    <div class="col-md-2">
                        <button id="btn-queue-id-submit" class="btn btn-primary" style="margin-right: 20px">保存</button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="row push-msg">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    消息模版设置
                </h3>
            </div>
            <div class="panel-body">
                <div class="row" style="margin-top:12px">
                    <div class="col-md-12">
                        <div id="se_notice" class="col-md-1" style="line-height: 32px;text-align: center;">自定义消息模版：
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <button class="btn btn-info btn-msg-wait"
                                        style="margin-right: 10px;margin-left:6px">等待人数
                                </button>
                                <!--                                <button id="btn-msg-teacher" class="btn btn-info" style="margin-right: 10px;margin-left:6px">办理老师</button>-->
                                <button class="btn btn-info btn-msg-user" style="margin-right: 10px">办理用户</button>
                                <!--                                <button id="btn-msg-address" class="btn btn-info" style="margin-right: 10px">业务办理地点</button>-->
                                <button class="btn btn-info btn-msg-require" style="margin-right: 10px">业务办理所需资料
                                </button>
                                <button class="btn btn-info btn-msg-business" style="margin-right: 10px">业务名称
                                </button>
                                <div style="float:right">
                                    <button id="btn-msg-submit" class="btn btn-primary" style="">保存</button>
                                    <button id="btn-msg-cancel" class="btn btn-danger" style="">重置</button>
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px">
                                <textarea cols="10" name="desc" type="text"
                                          placeholder="消息模版说明：{xxx}为可选内容且不可修改，不填写则使用默认消息模版"
                                          class="form-control input-md"
                                          style="resize: none;height: 100px;"><?php echo empty($data['template']) ? '' : $data['template']; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:12px">
                    <div class="col-md-12">

                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="row push-msg">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    二维码模版设置
                </h3>
            </div>
            <div class="panel-body">
                <div class="row" style="margin-top:12px">
                    <div class="col-md-12">
                        <div id="se_notice" class="col-md-1" style="line-height: 32px;text-align: center;">自定义消息模版：
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <button class="btn btn-info btn-qrcode-wait"
                                        style="margin-right: 10px;margin-left:6px">等待人数
                                </button>
                                <button class="btn btn-info btn-qrcode-queue_id" style="margin-right: 10px">预约号码
                                </button>

                                <button class="btn btn-info btn-qrcode-business" style="margin-right: 10px">业务名称
                                </button>
                                <div style="float:right">
                                    <button id="btn-qrcode-submit" class="btn btn-primary" style="">保存</button>
                                    <button id="btn-qrcode-cancel" class="btn btn-danger"
                                            style="">重置
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:12px">
                    <form id="qrcode_form">
                        <table id="table-images" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <td width="80%">描述</td>
                                <td width="10%">类型</td>
                                <td width="10%">操作</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($qrcode_msg)) foreach ($qrcode_msg as $v): ?>
                                <tr>
                                    <td class="text-right">
                                        <div class="form-group"
                                             style="display: <?php echo $v['type'] == 1 ? 'block' : 'none'; ?>">
                                            <input name="message[]"
                                                   placeholder="消息模版说明：{xxx}为可选内容且不可修改，不填写则使用默认消息模版"
                                                   class="form-control intro" type="text"
                                                   value="<?php echo $v['type'] == 1 ? $v['text'] : '' ?>">
                                        </div>
                                        <div class="form-group"
                                             style="display: <?php echo $v['type'] == 2 ? 'block' : 'none'; ?>">
                                            <div id="se_notice" class="col-md-1"
                                                 style="line-height: 32px;text-align: center;">宽：
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control" id="" name="width[]">
                                                    <option value="200" <?php echo $v['type'] == 2 && $v['width'] == 200 ? 'selected' : ''; ?>>
                                                        200
                                                    </option>
                                                    <option value="300" <?php echo $v['type'] == 2 && $v['width'] == 300 ? 'selected' : ''; ?>>
                                                        300
                                                    </option>
                                                    <option value="400" <?php echo $v['type'] == 2 && $v['width'] == 400 ? 'selected' : ''; ?>>
                                                        400
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-1"
                                                 style="line-height: 32px;text-align: center;">高：
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control" id="" name="height[]">
                                                    <option value="200" <?php echo $v['type'] == 2 && $v['height'] == 200 ? 'selected' : ''; ?>>
                                                        200
                                                    </option>
                                                    <option value="300" <?php echo $v['type'] == 2 && $v['height'] == 300 ? 'selected' : ''; ?>>
                                                        300
                                                    </option>
                                                    <option value="400" <?php echo $v['type'] == 2 && $v['height'] == 400 ? 'selected' : ''; ?>>
                                                        400
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-6"
                                                 style="line-height: 32px;text-align: center;">
                                                <input type="text" name="text[]" placeholder="请填写url地址"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <select name="type[]" id="" class="form-control">
                                            <option value="0">请选择类型</option>
                                            <option value="1" <?php echo $v['type'] == 1 ? 'selected' : '' ?>>二维码内容
                                            </option>
                                            <option value="2" <?php echo $v['type'] == 2 ? 'selected' : '' ?>>二维码尺寸
                                            </option>
                                        </select>
                                    </td>
                                    <td class="text-left">
                                        <button type="button" data-toggle="tooltip" title="移除"
                                                class="btn btn-danger remove"><i
                                                    class="fa fa-minus-circle"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr id="image-row-new" style="display:none;">
                                <td class="text-right">
                                    <div class="form-group" style="display: none">
                                        <input name="message[]" value=""
                                               placeholder="消息模版说明：{xxx}为可选内容且不可修改，不填写则使用默认消息模版"
                                               class="form-control intro" type="text">
                                    </div>
                                    <div class="form-group" style="display: none">
                                        <div id="se_notice" class="col-md-1"
                                             style="line-height: 32px;text-align: center;">宽：
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="" name="width[]">
                                                <option value="200">200</option>
                                                <option value="300">300</option>
                                                <option value="400">400</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1"
                                             style="line-height: 32px;text-align: center;">高：
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="" name="height[]">
                                                <option value="200">200</option>
                                                <option value="300">300</option>
                                                <option value="400">400</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6"
                                             style="line-height: 32px;text-align: center;">
                                            <input type="text" name="text[]" placeholder="请填写url地址"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <select name="type[]" id="" class="form-control">
                                        <option value="0">请选择类型</option>
                                        <option value="1">二维码内容</option>
                                        <option value="2">二维码尺寸</option>
                                    </select>
                                </td>
                                <td class="text-left">
                                    <button type="button" data-toggle="tooltip" title="移除"
                                            class="btn btn-danger remove"><i
                                                class="fa fa-minus-circle"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-left">
                                    <button type="button" data-toggle="tooltip" title="添加图片" class="btn btn-primary"
                                            id="button-add"><i class="fa fa-plus-circle"></i></button>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="row push-msg">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    二维码尺寸设置
                </h3>
            </div>
            <div class="panel-body">
                <div class="row col-md-7" style="margin-top:12px">
                    <form id="qrcode_size_form">
                        <div class="form-group" id="qrcode_width">
                            <div id="se_notice" class="col-md-1" style="line-height: 32px;text-align: center;">宽：</div>
                            <div class="col-md-2">
                                <select class="form-control" id="" name="width">
                                    <option value="200">200</option>
                                    <option value="300">300</option>
                                    <option value="400">400</option>
                                </select>
                            </div>
                            <div id="se_notice" class="col-md-1" style="line-height: 32px;text-align: center;">px</div>
                        </div>
                        <div class="form-group">
                            <div id="se_notice" class="col-md-1" style="line-height: 32px;text-align: center;">高：</div>
                            <div class="col-md-2">
                                <select class="form-control" id="" name="height">
                                    <option value="200">200</option>
                                    <option value="300">300</option>
                                    <option value="400">400</option>
                                </select>
                            </div>
                            <div id="se_notice" class="col-md-1" style="line-height: 32px;text-align: center;">px</div>
                        </div>
                    </form>

                    <div class="form-group col-lg-4">
                        <div style="float:right">
                            <button id="btn-qrcode_size-submit" class="btn btn-primary" style="">保存</button>
                            <button id="btn-qrcode_size-cancel" class="btn btn-danger" style="">重置
                            </button>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('jsText') ?>
<script type="text/javascript">
    $(function () {
        var width = <?php echo !empty($size['width']) ? $size['width'] : 200 ?>;
        var height = <?php echo !empty($size['height']) ? $size['height'] : 200 ?>;
        $('select[name=width]').find('option[value="' + width + '"]').prop('selected', true);

        $('select[name=height]').find('option[value="' + height + '"]').prop('selected', true);

        //二维码模版设置
        $('#table-images>tbody').delegate('.select_type', 'change', function (evt) {
            var type = Number($(this).val());
            switch (type) {
                case 1:
                    $(this).closest('td').prev().find('.form-group:eq(0)').show().siblings().hide();
                    break;
                case 2:
                    $(this).closest('td').prev().find('.form-group:eq(1)').show().siblings().hide();
                    break;
            }
        })

        // 绑定上传元素的上传方式
        $('#table-images>tbody').delegate('.remove', 'click', function (evt) {
            // 删除所在行
            $(this).parents('tr').remove();
        });

        $('select[name=type]').change(function () {
            var type = $(this).val();
            if (type == 1) {
                $(this).closest('td').prev().find('.form-group:eq(0)').show()
            } else if (type == 2) {
                $(this).closest('td').prev().find('.form-group:eq(1)').show()
            }
        })

        $('tbody').on('change', 'select[name^=type]', function () {
            var type = $(this).val();
            if (type == 1) {
                $(this).closest('td').prev().find('.form-group:eq(0)').show().siblings().hide().find('select').val(200);
            } else if (type == 2) {
                $(this).closest('td').prev().find('.form-group:eq(1)').show().siblings().hide().find('input').val('');
            } else {
                $(this).closest('td').prev().find('.form-group').hide().find('input').val('').closest('.form-group').find('select').val(200);
            }
        })


        var i = 1;
        $('#button-add').click(function (evt) {
            var newTr = $('#image-row-new').clone().removeAttr('id');
//            newTr.removeAttr('id').find('td:eq(0) input').attr('name', 'message[' + i + '][]');
            $('#table-images>tbody').append(newTr);
            newTr.show();
        });

        //保存推送队列
        $('#btn-queue-id-submit').click(function () {
            if (lock) return;
            lock = true;
            $.ajax({
                data: {id: $('.queue_id input').val(), template: $('.push-msg textarea').val()},
                type: 'post',
                dataType: 'json',
                url: '<?php echo Yii::$app->urlManager->createUrl(['/backend/push/push-msg-save']);?>',
                success: function (resp) {
                    if (resp.e == 0) {
                        alertUtil.alert('保存成功', function () {
                            window.location.reload();
                        });
                    } else {
                        alertUtil.alert(resp.m);
                    }
                    lock = false;
                },
                error: function () {
                    lock = false;
                }
            })
        })

        var lock = false;
        $('.btn-msg-wait').click(function () {
            diyMsg('{wait}');
        })

//        $('#btn-msg-teacher').click(function () {
//            diyMsg('{teacher}');
//        })

        $('.btn-msg-user').click(function () {
            diyMsg('{user}');
        })

//        $('#btn-msg-address').click(function () {
//            diyMsg('{address}')
//        })

        $('.btn-msg-require').click(function () {
            diyMsg('{require}')
        })

        $('.btn-msg-business').click(function () {
            diyMsg('{business}')
        })

        //取消推送消息
        $('#btn-msg-cancel').click(function () {
            resetDiyMsg('');
        })
        //保存推送消息
        $('#btn-msg-submit').click(function () {
            if (lock) return;
            lock = true;
            $.ajax({
                data: {template: $('.push-msg textarea').val(), id: $('.queue_id input').val()},
                type: 'post',
                dataType: 'json',
                url: '<?php echo Yii::$app->urlManager->createUrl(['/backend/push/push-msg-save'])?>',
                success: function (resp) {
                    if (resp.e == 0) {
                        alertUtil.alert('保存成功', function () {
                            window.location.reload();
                        });
                    } else {
                        alertUtil.alert(resp.m);
                    }
                    lock = false;
                },
                error: function () {
                    lock = false;
                }
            })
        })

        $('#btn-qrcode-submit').click(function () {
            if (lock) return;
            lock = true;
            $.ajax({
                data: $('#qrcode_form').serialize(),
                type: 'post',
                dataType: 'json',
                url: '<?php echo Yii::$app->urlManager->createUrl(['/backend/push/qrcode-msg-save'])?>',
                success: function (resp) {
                    if (resp.e == 0) {
                        alertUtil.alert('保存成功', function () {
                            window.location.reload();
                        });
                    } else {
                        alertUtil.alert(resp.m);
                    }
                    lock = false;
                },
                error: function () {
                    lock = false;
                }
            })
        })

        $('#btn-qrcode_size-submit').click(function () {
            if (lock) return;
            lock = true;
            $.ajax({
                data: $('#qrcode_size_form').serialize(),
                type: 'post',
                dataType: 'json',
                url: '<?php echo Yii::$app->urlManager->createUrl(['/backend/push/qrcode-size-save'])?>',
                success: function (resp) {
                    if (resp.e == 0) {
                        alertUtil.alert(resp.m, function () {
                            window.location.reload();
                        });
                    } else {
                        alertUtil.alert(resp.m);
                    }
                    lock = false;
                },
                error: function () {
                    lock = false;
                }
            })
        })

        function templateReset(type) {
            if (lock) return;
            lock = true;
            $.ajax({
                data: {type: type},
                type: 'post',
                dataType: 'json',
                url: '<?php echo Yii::$app->urlManager->createUrl(['/backend/push/reset'])?>',
                success: function (resp) {
                    if (resp.e == 0) {
                        alertUtil.alert('操作成功', function () {
                            window.location.reload();
                        });
                    } else {
                        alertUtil.alert(resp.m);
                    }
                    lock = false;
                },
                error: function () {
                    lock = false;
                }
            })
        }

        $('#btn-qrcode-cancel').click(function () {
            templateReset('qrcode_msg')
        });
        $('#btn-qrcode_size-cancel').click(function () {
            templateReset('qrcode_size')
        });

        function diyMsg(msg) {
            var txt = $('.push-msg textarea').val();
            var position = $('.push-msg textarea').get(0).selectionStart; //获取光标所在位置
            var newFront = txt.substr(0, position) + msg; //光标处添加msg
            var newOld = txt.substr(position, txt.length - 1);
            var msgLen = msg.length;  //获取传入字符串的长度
            txt = newFront + newOld;
            $('.push-msg textarea').val(txt);
            $('.push-msg textarea').get(0).focus();
            $('.push-msg textarea').get(0).setSelectionRange(position + msgLen, position + msgLen)  //设置光标所在位置
        }

        $('.btn-qrcode-wait').click(function () {
                diyMsg1('{wait}');
            }
        )
        $('.btn-qrcode-business').click(function () {
                diyMsg1('{business}');
            }
        )
        $('.btn-qrcode-queue_id').click(function () {
                diyMsg1('{queue_id}');
            }
        )

        var object;
        $('tbody').on('focus', 'input', function () {
            $(this).addClass('yes').siblings().removeClass('yes');
        })

        function diyMsg1(msg) {
            var object = 'tbody input';
            var length = $(object).length;
            if (length <= 0) {
                return false;
            } else if (length > 1) {
                var ys_len = $('.yes').length;
                if (Number(ys_len) == 1) {
                    object = '.yes';
                } else {
                    object = 'tbody input:last';
                }
            }

            var txt = $(object).val();
            var position = $(object).get(0).selectionStart; //获取光标所在位置
            var newFront = txt.substr(0, position) + msg; //光标处添加msg
            var newOld = txt.substr(position, txt.length - 1);
            var msgLen = msg.length;  //获取传入字符串的长度
            txt = newFront + newOld;
            $(object).val(txt);
            $(object).get(0).focus();
            $(object).get(0).setSelectionRange(position + msgLen, position + msgLen)  //设置光标所在位置
        }


        function resetDiyMsg(msg) {
            $('.push-msg textarea').val(msg);
        }
    })
</script>
<?php $this->endBlock() ?>

