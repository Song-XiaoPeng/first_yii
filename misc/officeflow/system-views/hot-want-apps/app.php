<?php $this->set('title', $title);
?>
<div id="page-content">
    <div class="panel">
        <div class="panel-heading top">
            <h3 class="panel-title"><?=$title;?></h3>
        </div>
        <div class="panel-body" id="app">
            <div class="table-head table-toolbar-left">
                <div class="panel-options mt20 no-pl no-pr">
                    <a href="/system/hot-want-apps/add" class="btn btn-primary">
                        <i class="demo-pli-plus"></i>
                        <span><?=$title;?></span>
                    </a>
                </div>
            </div>
            <!-- 表格主体 begin -->
            <table class="table table-hover table-striped table-bordered table-advanced">
                <thead>
                <tr align="center">
                    <th>ID</th>
                    <th>应用名称</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($data)):
                    foreach($data as $v):
                        ?>
                        <tr id="user_<?php echo $v['id']; ?>">
                            <td><?php echo empty($v['id']) ? '' : $v['id'];?></td>
                            <td><?php echo empty($v['name']) ? '' : $v['name'];?></td>
                            <td class="btn-double">
                                <a href="/system/hot-want-apps/add?id=<?php echo $v['id']; ?>" class="btn btn-primary btn-sm">修改信息</a>
                                <a class="btn btn-danger btn-sm" onclick="deleteapp(<?php echo $v['id']; ?>)">删除</a>
                            </td>
                        </tr>
                    <?php endforeach;endif; ?>
                </tbody>
            </table>
            <!-- 表格主体 end -->
            <div class="pull-right"><?php echo Yii::$app->page;?></div>
        </div>
    </div>
</div>
<?php $this->beginBlock('jsText');?>
<script type="text/javascript">
    function deleteapp(id)
    {
        bootbox.confirm("<span class='text-danger'>确定删除该应用？</span>", function(resp){
            $.ajax({
                type: 'POST',
                url: "<?php echo Yii::$app->urlManager->createUrl('/system/hot-want-apps/delete');?>",
                dataType: 'json',
                data: {id: id},
                success: function(resp) {
                    if(resp.e === 0){
                        nsalert("操作成功");
                        $("#user_"+id).remove();
                    }else{
                        nsalert(resp.m, "fail");
                    }
                },
                error: function() {
                    nsalert("系统繁忙", "fail");
                }
            });
        });
    }
</script>
<?php $this->endBlock();?>
