<?php
$this->set('title', 'demo');
$this->addCssFile([
    '/lib/fex-webuploader/dist/webuploader.css',
]);
$this->addJsFile([
    '/common/js/webupload.js',
    '/lib/fex-webuploader/dist/webuploader.js',
]);
?>
<div class="row">
        <div class="col-lg-12">
            <div class="panel">
                <div class="panel-heading top">
                    <h3 class="panel-title">上传</h3>
                </div>
                <div class="panel-body">
                    <form id="role_group_attr" class="form-horizontal" onsubmit="return false;">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">前台logo <span class="text-xs"> </span></label>
                            <div class="col-sm-6 input-group">
                                <span style="width: 93.5px;height: 32px;line-height: 32px;" id="selectwap" class="btn btn-primary btn-file mar-rgt">
                                    <i class="fa fa-upload"></i>上传图片
                                </span>
                                <input type="hidden" name="wap_logo" id="wap_logo_url" v-model="info.wap_logo">
                            </div>
                            <div id="cover_preview" class="col-sm-6 col-sm-offset-3" v-show="true">
                                <div style="width: 201px;height:97px; margin-top:10px;background: #eee;border:1px solid #ddd; ">
                                    <img id="upload"  src="" alt="前台logo" style="width: 200px;height:96px;">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="demo-nifty-btn">
                            <button class="btn btn-primary " type="submit" >保存</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<div class="row" id="app">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Order Status</h3>
            </div>
            <div class="panel-body">
                <div class="pad-btm form-inline">
                    <div class="row">
                        <div class="col-sm-6 table-toolbar-left">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search" v-model="keyword" id="search">
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-default" @click="search()">搜索</button>
                            </div>
                        </div>
                        <div class="col-sm-6 table-toolbar-right">
                            <button class="btn btn-purple"><i class="demo-pli-add icon-fw"></i>Add</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>User</th>
                            <th>Order date</th>
                            <th>Amount</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in lists">
                            <td><a href="javascript:;" class="btn-link">{{item.invoice}}</a></td>
                            <td>{{item.user}}</td>
                            <td><span class="text-muted"><i class="fa fa-clock-o"></i>{{item.date}}</span></td>
                            <td>${{item.amount}}</td>
                            <td class="text-center">
                                <a class="label label-table label-success" @click="remove($index)">删除</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="pull-right">
                    <?php echo Yii::$app->page;?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('jsText');?>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            keyword: '',
            lists: <?php echo json_encode($list);?>,
        },
        methods: {
            search: function () {
                $.ajax({
                    url: "/backend/demo/search",
                    data: {kw:this.keyword},
                    dataType: 'json',
                    context: this,
                    success: function (obj) {
                        if (obj.e) {
                            nsalert(obj.m, "fail");
                        } else {
                            this.lists = obj.d.list;
                        }
                    }
                });
            },
            remove: function(idx) {
                bootbox.confirm('确认删除？', function(ok) {
                    if (ok) {
                        vm.lists.splice(idx, 1);
                    }
                });
            }
        }
    })
</script>
<?php $this->endBlock();?>
<?php $this->beginBlock('jsText','append'); ?>
<script>
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
        optionwap.pick = {id:"#selectwap",multiple:false};
        optionwap.func = {
            uploadSuccess: function (file, args) {
                if (args.state == 'SUCCESS') {
                    $('#upload').attr('src','<?php echo $this->imghost;?>'+ '/' + args.url);
                } else {
                    nsalert('上传前台logo失败', "fail");
                }
            }
        };
        webuploadUtil.init(optionwap);

    });
</script>
<?php $this->endBlock(); ?>
