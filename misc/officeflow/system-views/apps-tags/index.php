<?php
$this->set('title', '应用管理');
?>
<div class="panel" id="app">
        <div class="panel-heading top">
            <h3 class="panel-title">标签管理</h3>
        </div>
        <div class="panel-body">
            <div class="table-head table-toolbar-right">
                <div class="panel-options mt20 no-pl no-pr">
                    <a href="/system/apps-tags/create" class="btn btn-primary">
                        <i class="demo-pli-plus"></i>
                        <span>添加标签</span>
                    </a>
                </div>
            </div>
            <!-- 表格主体 begin -->
            <table class="table table-hover table-striped table-bordered table-advanced">
                <thead>
                <tr align="center">
                    <th>ID</th>
                    <th>标签</th>
                    <th>类别</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                  <?php foreach($lists['data'] as $val): ?>
                    <tr id="tr_<?php echo $val['id'];?>">
                      <td><?php echo $val['id'];?></td>
                      <td><?php echo $val['name'];?></td>
                      <td><?php echo isset($tagType[$val['type']]) ? $tagType[$val['type']] : '' ;?></td>
                      <td><a class="btn btn-primary" href="<?php echo Yii::$app->urlManager->createUrl(['/system/apps-tags/create','id' => $val['id']]);?>">编辑</a>
                        <a href="javacript:void(0)" v-on:click="del('<?php echo $val['id'];?>');" class="btn btn-default">删除</a>
                      </td>
                    </tr>
                  <?php endforeach;?>
                </tbody>
            </table>
            <!-- 表格主体 end -->
            <div class="pull-right"> <?php echo Yii::$app->page;?></div>
        </div>
    </div>
    <?php $this->beginBlock('jsText','append'); ?>
    <script>
    new Vue({
      el: '#app',
      methods: {
        del:function(id) {
          //开启验证
          $.ajax({
                    type:"POST",
                    cache:false,
                    url:'<?php echo Yii::$app->urlManager->createUrl(['/system/apps-tags/delete']);?>',
                    data:{
                      'id':id
                    },
                    dataType:'json',
                    context: this,
                    success:function(resp) {
                        if(resp.e == 0) {
                            nsalert(resp.m);
                            $('#tr_'+ id).remove();
                        } else {
                            nsalert(resp.m, "fail");
                        }
                    },
                    error:function(){
                        this.ajax_lock = false;
                    }
            });

        }
      }
    })
    </script>
    <?php $this->endBlock(); ?>
