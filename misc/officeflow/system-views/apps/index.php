<?php
$this->set('title', '应用列表');
?>
    <div id="page-content">
        <div class="panel">
            <div class="panel-heading top">
                <a class="back" href="javascript:history.go(-1)"></a>
                <h3 class="panel-title"><?=$this->get('title');?></h3>
            </div>
            <div class="panel-body" id="app">
                <form id="search" role="form" class="form-horizontal" action="/system/apps/index" method="get">
                    <label class="col-sm-1  control-label no-pl" style="width:auto" for="field-1">  </label>
                    <div class="col-sm-2 no-pl">

                        <input type="text" class="form-control" v-if="kw.length>0" :value="kw" name="kw" placeholder="请输入活动名称">
                        <input type="text" class="form-control" v-else name="kw" placeholder="请输入应用名称">

                    </div>
                    <div class="col-sm-1 no-pl" style="width:auto">
                        <button type="submit" class="btn btn-success">搜索</button>
                    </div>
                </form>
                <div class="table-head table-toolbar-right">
                    <div class="panel-options mt20 no-pl no-pr">
                        <a style="float:left;margin-right:3px" href="<?php echo Yii::$app->urlManager->createUrl('system/apps/create');?>"  class="btn btn-primary">
                            <i class="demo-pli-plus"></i>
                            <span>添加应用</span>
                        </a>
                        <a  href="javascript:void(0);" class="btn btn-primary" @click="addspecial(0)">
                            <i class="demo-pli-plus"></i>
                            <span>添加热门应用</span>
                        </a>
                        <a href="javascript:void(0);"  class="btn btn-primary" @click="addspecial(1)">
                            <i class="demo-pli-plus"></i>
                            <span>添加猜你想用</span>
                        </a>
                        <a href="javascript:void(0);" @click="del()" class="btn btn-danger multiple">
                            <i class="demo-pli-plus"></i>
                            <span>批量删除</span>
                        </a>
                    </div>
                </div>
                <!-- 表格主体 begin -->
                <table class="table table-hover table-striped table-bordered table-advanced">
                    <thead>
                    <tr align="center">
                        <th><input type="checkbox" id="select"></th>
                        <th>ID</th>
                        <th>应用名称</th>
                        <th>应用描述</th>
                        <th>标签</th>
                        <th>可用人群</th>
                        <th>是否可见</th>
                        <th>应用有效时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($lists)): ?>
                        <tr v-for="list in lists">
                            <td><input type="checkbox" name="ids[]" :value="list.id"></td>
                            <td>{{list.id}}</td>
                            <td>{{list.name}}</td>
                            <td>{{list.detail}}</td>
                            <td><span v-for="tag in list.tag">{{tag.name}} </span></td>
                            <td><span v-for="usable in list.usable">{{usable}} </span></td>
                            <td>{{list.visible}}</td>
                            <td style="width:550px;">
                                <div class="col-md-5">
                                    <input  v-model="list.during.date_start" type="text" class="form-control date-input" readonly>
                                    <input  v-model="list.during.time_start" type="text" class="form-control time-input" readonly>
                                </div>
                                <label class="col-md-1 control-label word-one">至</label>
                                <div class="col-md-5">
                                    <input v-model="list.during.date_end" type="text" class="form-control date-input" readonly>
                                    <input v-model="list.during.time_end" type="text" class="form-control time-input" readonly>
                                </div>
                            </td>
                            <td class="btn-double">
                                <a :href="'/system/apps/create?id=' + list.id" class="btn btn-primary btn-sm">修改信息</a>
                                <a class="btn btn-danger btn-sm" href="javascript:void(0);" @click="del(list.id)">删除</a>
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
                lists:<?php echo empty($lists)?'{id:"",name:"",detail:"",visible:"",tag:"",usable:"",during:""}':json_encode($lists);?>,
                kw:<?php echo empty($kw)?"{}":json_encode($kw);?>,
            },
            methods:{
                addspecial:function(type){
                    var ids = [];
                    $("input:checked[name='ids[]']").each(function(i,d){
                        ids.push($(this).val());
                    });
                    if(ids.length<=0){
                         alert('请选择应用');
                         return false;
                    };
                    $.ajax({
                        type:'post',
                        url:"<?php echo Yii::$app->urlManager->createUrl('system/app-special/save');?>",
                        dataType:'json',
                        data:{
                            ids:ids,
                            type:type
                        },
                        success:function(resp){
                            if(resp.e == 0){
                                alert(resp.m);
                                if(type==0){
                                    windows.location.href = '<?php echo Yii::$app->urlManager->createUrl('system/appSpecial/index')?>'
                                }
                            }else{
                                alert(resp.m);
                            }
                        }
                    })
                },
                del:function(id=''){
                    if(id == ''){
                        id = [];
                        $("input:checked[name='ids[]']").each(function(){
                            id.push($(this).val());
                        });
                        if(id.length<=0){
                            bootbox.alert("<span class='text-danger'>请选择要删除的应用</span>");
                            return false;
                        }
                    };
                    var url = "<?php echo Yii::$app->urlManager->createUrl('/system/apps/delete'); ?>";
                    bootbox.confirm("<span class='text-danger'>确定删除该应用？</span>", function(resp){
                        if(resp){
                            $.ajax({
                                type:'post',
                                url:url,
                                dataType:'json',
                                data:{
                                    id:id,
                                },
                                success:function(resp){
                                    if(resp.e == 0){
                                        alert(resp.m);
                                        window.location.reload();
                                    }else{
                                        alert(resp.m);
                                        window.location.reload();
                                    }
                                },
                                error: function(){
                                    alert('系统繁忙');
                                }
                            })
                        }
                    })
                }
            }
        })
        //批量选择
        $('#select').click(function(){
            $(':checkbox[name="ids[]"]').prop('checked',$(this).prop('checked'));
        })
    </script>

<?php $this->endBlock();?>