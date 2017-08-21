<?php
$this->set('title', '印章');
?>
<div class="panel">
    <div class="panel-heading top">
        <h3 class="panel-title">印章列表</h3>
    </div>
    <div class="panel-body">
        <div class="pad-btm form-inline">
            <div class="row">
                <div class="col-sm-6 table-toolbar-right" style="float:right">
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/seal/add']);?>" class="btn btn-success">
                        <i class="ion-plus"></i>添加印章
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
                    <th>印章</th>
                    <th>添加时间</th>
                    <th class="text-center">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($data)): ?>
                    <?php foreach($data as $k=>$v): ?>
                        <tr id="tr_<?= $v['id'];?>">
                            <td><?= $v['id'] ?></td>
                            <td><?= $v['name'] ?></td>
                            <td><img src="<?= $this->imghost.'/'.$v['pics']['file'] ?>" alt=""></td>
                            <td>
                                <?= $v['creator']?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/seal/add','id'=>$v['id']]);?>" class="btn btn-sm btn-info">编辑</a>
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
                        url:'<?php echo Yii::$app->urlManager->createUrl(['/system/seal/delete']);?>',
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
                                nsalert(resp.m, "fail");
                            }
                        }
                    });
                }
            });
        }
    };
</script>
<?php $this->endBlock()?>
