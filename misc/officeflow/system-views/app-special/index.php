<?php
$title = $type==1?'猜你想用':'热门应用';
$this->set('title', $title);
?>
<div id="page-content">
    <div class="panel">
        <div class="panel-heading top">
            <h3 class="panel-title"><?=$title;?></h3>
        </div>
        <div class="panel-body" id="app">
            <div>
                <form id="search" role="form" class="form-horizontal" action="/system/app-special/index" method="get">
                    <label class="col-sm-1  control-label no-pl" style="width:auto" for="field-1">  </label>
                    <div class="col-sm-2 no-pl">

                        <input type="text" class="form-control" v-if="kw.length>0" :value="kw" name="kw" placeholder="请输入活动名称">
                        <input type="text" class="form-control" v-else name="kw" placeholder="请输入应用名称">

                    </div>
                    <div class="col-sm-1 no-pl" style="width:auto">
                        <button type="submit" class="btn btn-success">搜索</button>
                        <input type="hidden" name="type" :value="type">
                    </div>
                </form>
            </div>
            <div class="table-head table-toolbar-right">
                <div class="panel-options mt20 no-pl no-pr">
                    <a href="javascript:void(0);" class="btn btn-danger" @click="deleteapp()">
                        <i></i>
                        <span>批量删除</span>
                    </a>
                </div>
            </div>
            <!-- 表格主体 begin -->
            <table class="table table-hover table-striped table-bordered table-advanced">
                <thead>
                <tr align="center">
                    <th><input type="checkbox" id="selectapp" @click="selectapp()"></th>
                    <th>ID</th>
                    <th>应用名称</th>
                    <th>应用描述</th>
                    <th>标签</th>
                    <th>是否可见</th>
                    <th>可用人群</th>
                    <th>应用有效时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($list)): ?>
                <tr v-for="list in lists">
                    <td><input type="checkbox" name="ids[]" :value="list.id"></td>
                    <td>{{list.id}}</td>
                    <td>{{list.name}}</td>
                    <td>{{list.detail}}</td>
                    <td><span v-for="tag in list.tags">{{tag.name}} </span></td>
                    <td>{{list.visible}}</td>
                    <td><span v-for="usable in list.usable">{{usable}} </span></td>
                    <td style="width:500px">
                        <div class="col-md-5">
                            <input  v-model="list.during_detail.date_start" type="text" class="form-control date-input" readonly>
                            <input  v-model="list.during_detail.time_start" type="text" class="form-control time-input" readonly>
                        </div>
                        <label class="col-md-1 control-label word-one">至</label>
                        <div class="col-md-5">
                            <input v-model="list.during_detail.date_end" type="text" class="form-control date-input" readonly>
                            <input v-model="list.during_detail.time_end" type="text" class="form-control time-input" readonly>
                        </div>
                    </td>
                    <td class="btn-double">
                        <a :href="'/system/apps/create?id=' + list.id" class="btn btn-primary btn-sm">修改信息</a>
                        <a class="btn btn-danger btn-sm" @click="deleteapp(list.id)">删除</a>
                    </td>
                </tr>
                <?php endif;?>
                </tbody>
            </table>
            <!-- 表格主体 end -->
            <div class="pull-right"><?php echo Yii::$app->page;?></div>
        </div>
    </div>
</div>
<?php $this->beginBlock('jsText');?>
<script type="text/javascript">
    new Vue({
        el:'#page-content',
        data:{
            lists:<?php echo empty($list)?'{id:"",name:"",detail:"",visible:"",tag:"",usable:"",during:""}':json_encode($list);?>,
            type:<?php echo $type;?>,
            kw:<?php echo empty($kw)?"{}":json_encode($kw);?>,
        },
        methods:{
            selectapp:function(){
                $(":checkbox[name='ids[]']").prop('checked',$("#selectapp").prop('checked'));
            },
            deleteapp:function(id=''){
                if(id == ''){
                    var ids = [];
                    $(":checked[name='ids[]']").each(function(){
                        ids.push($(this).val());
                    });
                    if(ids.length<=0){
                        alert('情选择应用');
                        return false;
                    }
                    id = ids;
                };
                type = "<?php echo $type;?>";
                bootbox.confirm("<span class='text-danger'>确定删除该应用？</span>", function(resp){
                    if(resp){
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo Yii::$app->urlManager->createUrl('/system/app-special/delete');?>",
                            dataType: 'json',
                            data: {
                                ids: id,
                                type:type,
                            },
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
                    }
                });
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
        }
    })
</script>
<?php $this->endBlock();?>
