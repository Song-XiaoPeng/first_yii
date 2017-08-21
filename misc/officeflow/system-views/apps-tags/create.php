<?php
$this->set('title', '个人信息');
$this->addCssFile([
    '/framework/nifty/plugins/select2/css/select2.min.css',
    '/framework/nifty/plugins/bootstrap-select/bootstrap-select.min.css',
    '/lib/fex-webuploader/dist/webuploader.css',
]);
$this->addJsFile([
    '/framework/nifty/plugins/select2/js/select2.min.js',
    '/framework/nifty/plugins/bootstrap-validator/bootstrapValidator.min.js',
    '/common/js/webupload.js',
    '/lib/fex-webuploader/dist/webuploader.min.js',
]);
?>
<div class="panel" id="app">
   <div class="panel-heading top">
    <a href="javascript:history.go(-1)" class="back"></a>
    <h3 class="panel-title">添加/编辑</h3>
   </div>
   <div class="panel-body">
    <form id="tags_form" onsubmit="return false;" class="form-horizontal jsSaveForm">
     <div class="form-group">
      <label for="demo-hor-inputemail" class="col-sm-3 control-label"><span class="text-danger">* </span>标签名称:</label>
      <div class="col-sm-6">
       <input type="text" name="name" value="" v-model="name" id="demo-hor-inputemail" class="form-control" />
      </div>
     </div>
     <div class="form-group">
      <label for="demo-hor-inputemail" class="col-sm-3 control-label"><span class="text-danger">* </span>标签分类:</label>
      <div class="col-sm-6">
        <select v-model="type" class="form-control">
          <?php foreach($tagType as $k => $name): ?>
            <option value="<?php echo $k;?>"><?php echo $name;?></option>
          <?php endforeach;?>
        </select>
      </div>
     </div>
     <div class="form-group savegroup demo-nifty-btn">
      <button type="submit" class="btn btn-primary" v-on:click="create">保存</button>
      <button type="reset" onclick="Javascript:window.history.go(-1)" class="btn btn btn-default">取消</button>
    </div>
    </form>
   </div>
  </div>
<?php $this->beginBlock('jsText','append'); ?>
<script>
//$("select").select2({minimumResultsForSearch: -1});
$(function () {
  $('#tags_form').bootstrapValidator({
              excluded: [':disabled'],
              submitButtons: 'button[type="submit"]',
              feedbackIcons:  {
                  valid: 'fa fa-check-circle fa-lg text-success',
                  invalid: 'fa fa-times-circle fa-lg',
                  validating: 'fa fa-refresh'
              },
              fields: {
                  name: {
                      validators: {
                          notEmpty: {
                              message: '名称不能为空'
                          }
                      }
                  },
                  type: {
                      validators: {
                          notEmpty: {
                              message: '类型不能为空'
                          }
                      }
                  },

              }
  });
});
new Vue({
  el: '#app',
  data: {
    'name': '<?php echo isset($info['name']) ? $info['name'] :''; ?>',
    'type': '<?php echo isset($info['type']) ? $info['type'] : 0; ?>',
    'id' : <?php echo isset($info['id']) ? $info['id'] : 0; ?>
  },
  methods: {
    create: function () {
      //开启验证
      $('#tags_form').data('bootstrapValidator').validate();
      if (!$('#tags_form').data('bootstrapValidator').isValid() || this.ajax_lock) {
          return false;
      }
      this.ajax_lock = true;
      $.ajax({
                type:"POST",
                cache:false,
                url:'<?php echo Yii::$app->urlManager->createUrl(['/system/apps-tags/save']);?>',
                data:{
                  'name':this.name,
                  'type':this.type,
                  'id' : this.id,
                },
                dataType:'json',
                context: this,
                success:function(resp) {
                    if(resp.e == 0) {
                        nsalert(resp.m);
                        window.location.href = '<?php echo Yii::$app->urlManager->createUrl(['/system/apps-tags/index/']);?>';
                    } else {
                        nsalert(resp.m, "fail");
                        this.ajax_lock = false;
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
