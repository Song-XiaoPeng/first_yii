<?php $this->addJsFile([
    '/lib/My97DatePicker/WdatePicker.js',
    '/framework/nifty/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js',
    '/framework/nifty/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
    '/framework/nifty/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js',
    '/framework/nifty/plugins/chosen/chosen.jquery.min.js',
    '/framework/nifty/plugins/select2/js/select2.min.js',
    '/framework/nifty/plugins/bootstrap-select/bootstrap-select.min.js',
])?>
<?php $this->addCssFile([
    '/lib/My97DatePicker/skin/WdatePicker.css',
    '/framework/nifty/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css',
    '/framework/nifty/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
     "/framework/nifty/plugins/bootstrap-select/bootstrap-select.min.css",
    "/framework/nifty/plugins/select2/css/select2.min.css",
    "/framework/nifty/plugins/chosen/chosen.min.css",
])?>
<?php $this->set('title','添加/修改应用');?>
<script src="/framework/nifty/js/jquery-2.2.4.min.js"></script>
<script src="/framework/nifty/js/demo/form-component.js"></script>
<div class="panel jsVueCon">
   <div class="panel-heading top">
        <a href="javascript:history.go(-1)" class="back"></a>
        <h3 class="panel-title">添加/编辑</h3>
   </div>
   <div class="panel-body">
    <form onsubmit="return false;" class="form-horizontal jsSaveForm" id="jsSaveForm1">
        <div class="form-group">
          <label for="demo-hor-inputemail" class="col-sm-3 control-label"><span class="text-danger">* </span>应用名称:</label>
          <div class="col-sm-6">
           <input type="text" name="name" value="" v-model="data.name" placeholder="应用名称" id="demo-hor-inputemail" class="form-control" />
          </div>
         </div>
        <div class="form-group">
          <label for="demo-hor-inputemail" class="col-sm-3 control-label"><span class="text-danger">* </span>应用描述:</label>
          <div class="col-sm-6">
           <input type="text" name="detail" value="" v-model="data.detail" placeholder="应用描述" id="demo-hor-inputemail" class="form-control" />
          </div>
         </div>
        <div class="form-group">
            <label for="demo-hor-inputemail" class="col-sm-3 control-label"><span class="text-danger">* </span>标签选择:</label>
            <div class="col-sm-6">
                <select name="tag[]" id="demo-cs-multiselect" data-placeholder="选择标签" multiple="" tabindex="-1" style="display: none;">

                    <option :value="tag.id" v-if="inArray(tag.name,data.tag)" v-for="tag in tags" selected>{{tag.name}}</option>
                    <option :value="tag.id" v-else>{{tag.name}}</option>
<!--                    <option  v-for="tag in tags" :value="tag.id">{{tag.name}}{{data.tag}}{{inArray(tag.name,data.tag)}}</option>-->
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="demo-hor-inputemail" class="col-sm-3 control-label"><span class="text-danger">* </span>流程应用:</label>
            <div class="col-sm-6">
                <select name="process_id" id="" class="form-control">
                    <option value="0" >请选择流程</option>
                    <option value="1" v-if="data.bpmn_id==1" selected>流程1</option>
                    <option value="1" v-else>流程1</option>
                    <option value="2" v-if="data.bpmn_id==2" selected>流程2</option>
                    <option value="2" v-else>流程2</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="demo-hor-inputemail" class="col-sm-3 control-label"><span class="text-danger">* </span>可用人群:</label>
            <div class="col-sm-6">
                <div v-for="(v,k) in usable">
                    <input type="checkbox" name="usable[]"  :value="k" v-if="data.usable.indexOf(k)>=0" checked>{{v}}
                    <input type="checkbox" name="usable[]"  :value="k" v-else>{{v}}
                </div>

                <!--<input type="checkbox" name="usable[]" value="1" v-if="data.usable[0]==1" checked>本科生
                <input type="checkbox" name="usable[]" value="1" v-else>本科生
                <input type="checkbox" name="usable[]" value="2" v-if="data.usable[1]==2" checked>研究生
                <input type="checkbox" name="usable[]" value="2" v-else>研究生
                <input type="checkbox" name="usable[]" value="3" v-if="data.usable[2]==3" checked>教师
                <input type="checkbox" name="usable[]" value="3" v-else>教师-->
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="text-danger">* </span>活动时间:</label>
            <div class="col-sm-3">
                <input name="date_start" v-model="data.during.date_start"  type="text" class="form-control date-input">
                <input name="time_start" v-model="data.during.time_start" type="text" class="form-control time-input">
            </div>
            <label class="col-sm-1 control-label word-one">至</label>
            <div class="col-sm-3">
                <input name="date_end" v-model="data.during.date_end"  type="text" class="form-control date-input" readonly>
                <input name="time_end" v-model="data.during.time_end"  type="text" class="form-control time-input" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">
                <span class="text-danger">* </span>
                前台logo</label>
            <div class="col-sm-6 input-group">
                 <span style="width: 93.5px;height: 32px;line-height: 32px;" id="select" class="btn btn-primary btn-file mar-rgt">
                     <i class="fa fa-upload"></i>上传图片
                 </span>
                <input type="hidden" name="logo" v-model="data.logo">
            </div>
            <div id="cover_preview" class="col-sm-6 col-sm-offset-3" v-show="true">
                <div style="width: 201px;height:97px; margin-top:10px;background: #eee;border:1px solid #ddd; ">
                    <img id="upload"  :src="'<?php echo $this->imghost.'/';?>'+data.url" alt="前台logo" style="width: 200px;height:96px;">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="demo-hor-inputemail" class="col-sm-3 control-label"><span class="text-danger">* </span>超管可见:</label>
            <div class="col-sm-9">
                <input type="radio" name="visible" value='yes' checked /> 是
                <input type="radio" name="visible" value='no' /> 否
            </div>
        </div>
        <div class="form-group savegroup demo-nifty-btn">
             <input type="hidden" name="id" :value="data.id" />
             <button type="submit" @click="save()" class="btn btn-primary">保存</button>
             <button type="reset" onclick="Javascript:window.history.go(-1)" class="btn btn btn-default">取消</button>
         </div>
    </form>
   </div>
  </div>

<?php $this->beginBlock('jsText','append'); ?>
<script>
    var vm = new Vue({
        el:'#jsSaveForm1',
        data:{
            ajaxLock:false,
            tags:<?php echo json_encode($tags);?>,
            save_url:"<?php echo Yii::$app->urlManager->createUrl('/system/apps/save');?>",
            data:<?php echo empty($data)?"{name:'',during:'',id:'',detail:'',visible:'',tag:'',usable:'',logo:'',process_id:'',url:''}":json_encode($data);?>,
            usable:<?php echo json_encode($usable);?>,
        },
        methods:{
            save:function(){
                var ckun = this.checkEmpty("input[name=name]", "应用名称不能为空");
                var cklo = this.checkEmpty("input[name='logo']", "上传图片为空");
                if(!ckun || !cklo){
                    return false;
                };
                if(this.ajaxLock){return}
                this.ajaxLock = true;
                $.ajax({
                    type:'post',
                    url:this.save_url,
                    data:$('.jsSaveForm').serialize(),
                    dataType:'json',
                    success:function(resp){
                        if(resp.e == 0){
                            alert(resp.m);
                            window.location.href = "<?php echo Yii::$app->urlManager->createUrl(['/system/apps/index']);?>";
                        }else{
                            alert(resp.m);
                            vm.ajaxLock = false;
                        }
                    },
                    error:function(resp){
                        nsalert("系统繁忙，请稍后再试！", "fail");
                        vm.ajaxLock = false;
                    }
                })
            },
            checkEmpty: function(obj, text) {
                if($.trim($(obj).val()) == "") {
                    this.setWrong(obj, text);
                    return false;
                }else{
                    this.setRight(obj);
                    return true;
                }
            },
            setWrong: function(obj, text) {
                var helpBlock = $(obj).closest(".form-group").find(".help-block");
                if(helpBlock.length <= 0) {
                    var text = '<small class="help-block">'+text+'</small>';
                    $(obj).after(text);
                }else{
                    helpBlock.text(text);
                }
                $(obj).closest(".form-group").addClass("has-error");
            },
            setRight: function(obj) {
                $(obj).closest(".form-group").find(".help-block").remove();
                $(obj).closest(".form-group").removeClass("has-error");
            },
            inArray: function(val,arr){
                var new_arr = [];
                //'a' '[{name:''},{name:''}]'
                for(v in arr){
                    new_arr.push(arr[v].name);
                }
                if(new_arr.indexOf(val)!=-1){
                    return true;
                }else{
                    return false;
                }
            }
        }
    });
    // 活动日期
    $('.date-input').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true,
        language : "zh-CN"
    });
    // 活动时间
    $('.time-input').timepicker({
        minuteStep: 5,
        showInputs: false,
        disableFocus: true,
        showMeridian:false,//24小时制
        showSeconds:false,
        autoclose: true,
    });
    //var ue = UE.getEditor('editor');
    $(function(){
        function base(){
            this.formData = {
                'category': 'image',
                'thumb':'1',
                'twidth':'100',
            };
            this.fileSingleSizeLimit = 2*1024*1024;
            this.accept = {
                title: 'Images',
                extensions: 'jpg,jpeg,png',
                mimeTypes: 'image/jpeg,image/png'
            };
        }
        var optionwap = new base();
        optionwap.pick = {
          id:"#select",
          multiple:false
        };
        optionwap.func = {
            uploadSuccess: function (file, args) {
                if (args.state == 'SUCCESS') {
                    $('#upload').attr('src','<?php echo $this->imghost;?>'+ '/' + args.url);
                    $(':input[name=logo]').val(args.id);
                } else {
                    nsalert('上传前台logo失败', "fail");
                }
            }
        };
        webuploadUtil.init(optionwap);

    });
</script>
<?php $this->endBlock(); ?>

