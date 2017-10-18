<?php 
use apps\base\widgets\TableNoData;
use apps\base\widgets\PreviewBox;

echo PreviewBox::widget();
echo $this->set('title','支付宝会员卡');
?>
<div class="panel">
    <div class="panel-heading top">
        <h3 class="panel-title">列表</h3>
    </div>

    <div class="panel-body">
        <?php 
        $url_pre = Yii::$app->controller->module->appkey.'/'.Yii::$app->controller->id;
        $addurl = Yii::$app->urlManager->createUrl([$url_pre . '/add']);
        ?>
        <div class="table-toolbar-left">
            <a class="btn btn-primary" href="<?php echo $addurl;?>"><i class="demo-pli-plus"></i> 新建卡模板</a>
        </div>
        <table class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="7%">ID</th>
                    <th width="10%">LOGO</th>
                    <th width="">名称</th>
                    <th width="10%">创建人</th>
                    <th width="10%">创建时间</th>
                    <th width="10%">修改时间</th>
                    <th width="160">操作</th>
                </tr>
            </thead>
            <tbody>
            <?php if(!empty($lists)): ?>
                <?php foreach ($lists as $row): ?>
                <tr id="row<?php echo $row['id']; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><img style="width:150px;height: 53px" src="<?php echo empty($row['logo'])?'/wap/topic/images/default.png':$this->imghost.'/'.$row['logo'];?>"/></td>
                    <td><?php echo $row['card_show_name']; ?></td>
                    <td><?php if(!empty($row['manager']['realname']) && !empty($row['manager']['username']))echo $row['manager']['realname'].'('.$row['manager']['username'].')'; ?></td>
                    <td><?php echo date('Y-m-d H:i:s',$row['mtime']); ?></td>
                    <td class="btn-single">
                        <a href="<?php echo Yii::$app->urlManager->createUrl([$url_pre . '/add','id'=>$row['id']]); ?>" class="btn btn-primary">修改信息</a>
                        <a onclick="previewBox('<?php echo Yii::$app->urlManager->createAbsoluteUrl([Yii::$app->controller->module->appkey.'/wap/default','id'=>$row['id']]); ?>')" class="btn btn-default btn-h">预览</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else:?>
                <?php echo TableNoData::widget(['colspan'=>7,'url'=>$addurl]); ?>
            <?php endif; ?>
            </tbody>
        </table>
        <!-- 分页 -->
        <?php echo Yii::$app->page;?>
        <!-- 分页 -->
    </div>
</div>
<?php $this->beginBlock('jsText','append');?>

<?php $this->endBlock(); ?>