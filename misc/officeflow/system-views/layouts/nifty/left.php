<?php
use app\helpers\Menu;
?>
<nav id="mainnav-container">
    <div id="mainnav">
        <div id="mainnav-menu-wrap">
            <div class="nano">
                <div class="nano-content">
                    <div id="mainnav-profile" class="mainnav-profile">
                        <div class="profile-wrap">
                            <div class="pad-btm">
                                <img class="img-circle img-sm img-border" src='/framework/nifty/img/profile-photos/1.png' alt="Profile Picture">
                            </div>
                            <a href="#profile-nav" class="box-block" data-toggle="collapse" aria-expanded="false">
                                <span class="pull-right dropdown-toggle">
                                    <i class="dropdown-caret"></i>
                                </span>
                                <p class="mnp-name">张三</p>
                                <span class="mnp-desc">demo@demo.com</span>
                            </a>
                        </div>
                        <div id="profile-nav" class="collapse list-group bg-trans">
                            <a href="#" class="list-group-item">
                                <i class="demo-pli-male icon-lg icon-fw"></i> 个人信息
                            </a>
                            <a href="#" class="list-group-item">
                                <i class="demo-pli-gear icon-lg icon-fw"></i> 修改密码
                            </a>
                            <a href="#" class="list-group-item">
                                <i class="demo-pli-information icon-lg icon-fw"></i> 帮助
                            </a>
                            <a  onclick="logoutUtil.logout();" class="list-group-item">
                                <i class="ion-log-out icon-lg icon-fw"></i> 登出
                            </a>
                        </div>
                    </div>
                    <div id="mainnav-shortcut">
                        <ul class="list-unstyled">
                            <li class="col-xs-3" data-content="个人信息">
                                <a class="shortcut-grid" href="#">
                                    <i class="demo-psi-male"></i>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="修改密码">
                                <a class="shortcut-grid" href="#">
                                    <i class="demo-pli-gear"></i>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="帮助">
                                <a class="shortcut-grid" href="#">
                                    <i class="demo-pli-information"></i>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="登出">
                                <a class="shortcut-grid" onclick="logoutUtil.logout();">
                                    <i class="ion-log-out"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <ul id="mainnav-menu" class="list-group">
                        <li class="list-header">应用管理</li>
                        <li>
                            <a href="/system/apps/index" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>应用列表</strong></span>
                            </a>
                        </li>
                        <li>
                            <a href="/system/apps-tags/index" data-original-title="" title="">
                                <i class="fa fa-list-ul"></i><span class="menu-title"><strong>应用标签管理</strong></span>
                            </a>
                        </li>
                        <li>

                            <a href="/system/app-special/index?type=0" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>热门应用</strong></span>
                            </a>
                        </li>
                        <li>
                            <a href="/system/app-special/index?type=1" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>猜你想用</strong></span>
                            </a>
                        </li>
                    </ul>
                    <ul id="mainnav-menu" class="list-group">
                        <li class="list-header">网站管理</li>
                        <li>
                            <a href="/system/site-options/index" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>网站配置</strong></span>
                            </a>
                        </li>
                        <li class="list-header">部门管理</li>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/department/index']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>列表</strong></span>
                            </a>
                        </li>
                        <li class="list-header">职位管理</li>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/job/index']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>职位列表</strong></span>
                            </a>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/job/type']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>职位分类</strong></span>
                            </a>
                        </li>
                        <li class="list-header">职级管理</li>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/grade/index']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>职级列表</strong></span>
                            </a>
                        </li>
                        <li class="list-header">权限管理</li>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/auth-group/index']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>权限组</strong></span>
                            </a>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/user-auth/index']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>用户权限</strong></span>
                            </a>
                        </li>
                        <li class="list-header">归档管理</li>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/archive-classify/index']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>归档分类</strong></span>
                            </a>
                        </li>
                        <li class="list-header">表单管理</li>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/form-category/index']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>表单分类</strong></span>
                            </a>
                        </li>
                        <li class="list-header">用户管理</li>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/user/index']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>用户列表</strong></span>
                            </a>
                        </li>
                        <li class="list-header">相对角色管理</li>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/relation/index']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>相对角色列表</strong></span>
                            </a>
                        </li>
                        <li class="list-header">小组管理</li>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/team/index']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>小组列表</strong></span>
                            </a>
                        </li>
                        <li class="list-header">印章管理</li>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/seal/index']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>印章列表</strong></span>
                            </a>
                        </li>
                        <li class="list-header">轮播图管理</li>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/system/banner/index']); ?>" data-original-title="" title="">
                                <i class="fa fa-users"></i><span class="menu-title"><strong>轮播图列表</strong></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
