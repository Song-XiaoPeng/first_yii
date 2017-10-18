<?php
use apps\base\widgets\TableNoData;
use apps\base\widgets\PreviewBox;

echo PreviewBox::widget();
echo $this->set('title', '支付宝校园卡');
?>
<?php
$url_pre = Yii::$app->controller->module->appkey . '/' . Yii::$app->controller->id;
$addurl = Yii::$app->urlManager->createUrl([$url_pre . '/add']);
$type = Yii::$app->request->get("type", 0);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="tab-base">
            <ul class="nav nav-tabs" style="">
                <li <?php if ($type == 0) echo "class='active'"; ?>>
                    <a href="<?php echo Yii::$app->urlManager->createUrl([$url_pre . "/index", 'type' => 0, 'app' => Yii::$app->controller->module->appkey]); ?>">
                        正式卡
                    </a>
                </li>
                <li <?php if ($type == 1) echo "class='active'"; ?>>
                    <a href="<?php echo Yii::$app->urlManager->createUrl([$url_pre . "/index", 'type' => 1, 'app' => Yii::$app->controller->module->appkey]); ?>">
                        临时卡
                    </a>
                </li>
            </ul>
            <div class="tab-content" style="padding: 15px 20px 45px 20px; min-height: 426px; display: block;">
                <div class="tab-pane active">
                    <div class="table-toolbar-left">
                        <a onclick="previewBox('<?php echo Yii::$app->urlManager->createAbsoluteUrl([Yii::$app->controller->module->appkey . '/wap/get']); ?>')"
                           class="btn btn-success"><i class="fa fa-search"></i> 预览及复制领卡链接</a>
                        <?php if (!empty($type)): ?><a class="btn btn-primary" href="<?php echo $addurl; ?>"><i
                                    class="demo-pli-plus"></i> 新增临时卡</a><?php endif; ?>
                    </div>
                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th width="20%">卡号</th>
                            <?php if (empty($type)): ?>
                                <th width="10%">姓名</th>
                                <th width="10%">部门</th>
                            <?php endif; ?>
                            <th width="10%">类别</th>
                            <th width="20%">有效期</th>
                            <th width="20%">领取时间</th>
                            <?php if (!empty($type)): ?>
                                <th width="10%">操作</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($lists)): ?>
                            <?php foreach ($lists as $row): ?>
                                <tr id="row<?php echo $row['cardid']; ?>">
                                    <td><?php echo $row['cardid']; ?></td>
                                    <?php if (empty($type)): ?>
                                        <td><?php echo $row['realname']; ?></td>
                                        <td><?php echo $row['depart_name']; ?></td>
                                    <?php endif; ?>
                                    <td><?php echo $row['type'] == '1' ? '临时卡' : '正式卡'; ?></td>
                                    <td><?php echo empty($row['valid_date']) ? '' : date('Y-m-d H:i:s', $row['valid_date']); ?></td>
                                    <td><?php echo empty($row['created']) ? '' : date('Y-m-d H:i:s', $row['created']); ?></td>
                                    <?php if (!empty($type)): ?>
                                        <td>
                                            <?php if (empty($row['created'])): ?><a
                                                href="<?php echo Yii::$app->urlManager->createUrl([$url_pre . "/add", 'id' => $row['id']]); ?>"
                                                class="btn btn-primary btn-sm">编辑</a><?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php echo TableNoData::widget(['colspan' => 40]); ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    <!-- 分页 -->
                    <?php echo Yii::$app->page; ?>
                    <!-- 分页 -->

                </div>

            </div>
        </div>
    </div>
</div>