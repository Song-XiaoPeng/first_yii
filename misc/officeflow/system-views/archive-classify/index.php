<?php
$this->set('title', '归档分类');
?>
        <div class="panel">
            <div class="panel-heading top">
                <h3 class="panel-title">分类列表</h3>
            </div>
            <div class="panel-body">
                <div class="pad-btm form-inline">
                    <div class="row">
                        <div class="col-sm-6 table-toolbar-right" style="float:right">
                                <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/archive-classify/add']);?>" class="btn btn-success">
                                    <i class="ion-plus"></i> 添加分类
                                </a>
<!--                            <a href="" class="btn btn-info">-->
<!--                                <i class="ion-eye"></i>-->
<!--                            </a>-->
<!--                            <a href="" class="btn btn-warning">-->
<!--                                <i class="ion-eye"></i>-->
<!--                            </a>-->
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-vcenter table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>PID</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php if(!empty($data)): ?>
                            <?php foreach($data as $k=>$v): ?>
                                <tr id="tr_<?= $v['id'];?>">
                                    <td><?= $v['id'] ?></td>
                                    <td><?= $v['name'] ?></td>
                                    <td><?= $v['pid'] ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/archive-classify/add','id'=>$v['id']]);?>" class="btn btn-sm btn-info">编辑</a>
                                        <a onclick="obj.del(<?= $v['id']?>)" class="btn btn-sm btn-info">删除</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="pull-right">
                    <?php //echo Yii::$app->page;?>
                </div>
            </div>
        </div>
<?php $this->beginBlock('jsText','append') ?>
<script>
    var obj = {
        del:function (id) {

            bootbox.confirm('确认删除？删除后数据不能恢复！', function(ok) {
                if(ok) {
                    $.ajax({
                        type:"POST",
                        cache:false,
                        url:'<?php echo Yii::$app->urlManager->createUrl(['/system/archive-classify/delete']);?>',
                        data:{
                            id:id
                        },
                        dataType:'json',
                        success:function(resp)
                        {
                            if(resp.e == 0)
                            {
                                $("#tr_"+id).remove();
                            }else{
                                alert(resp.m, "fail");
                            }
                        }
                    });
                }
            });


        }
    };
</script>
<?php $this->endBlock()?>
