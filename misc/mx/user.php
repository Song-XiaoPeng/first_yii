<?php
use common\components\JConfig;

?>
<?php $this->beginBlock('title'); ?>
<?php if ($type == 0) {
    echo '我的图集';
} elseif ($type == 1) {
    echo '我的音频专辑';
} elseif ($type == 3) {
    echo '我的写作';
} else {
    echo '我的视频';
} ?>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('cssFile', 'append'); ?>
<link rel="stylesheet" href="<?php echo JConfig::item('config.imgCdnDomain'); ?>/wap/activity/original/css/menu.css">
<link rel="stylesheet" href="<?php echo JConfig::item('config.imgCdnDomain'); ?>/wap/activity/original/css/style.css">
<?php $this->endBlock(); ?>
<?php $this->beginBlock('cssText', 'append'); ?>
<style>
    <?php if($type==0):?>
    .picture span {
        background: url(<?php echo JConfig::item('config.imgCdnDomain');?>/wap/activity/original/img/3.png) left center no-repeat;
        background-size: 100%;
    }

    .picture {
        background: url(<?php echo JConfig::item('config.imgCdnDomain');?>/wap/activity/original/img/bg.png) top center no-repeat;
        background-size: 100% 100%;
        padding: 3%;
    }

    .update {
        background: url(<?php echo JConfig::item('config.imgCdnDomain');?>/wap/activity/original/img/update.png) no-repeat center;
        background-size: 15px auto;
    }

    .picture:nth-of-type(2n+1) {
        margin-right: 2%;
    }

    <?php endif;?>
    .followed {
        background: #939191 !important;
    }

    .img {
        background: #f7f7f7;
        overflow: hidden;
        padding-top: 16px;
        box-sizing: border-box;
        position: relative;
    }

    .img:after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 1px;
        -webkit-transform: scaleY(0.5);
        transform: scaleY(0.5);
        background: #ccc;
    }

    body {
        background: #f7f7f7;
        height: 100%;
        overflow-y: auto;
    }

    .flex {
        width: 100%;
        z-index: 10;
        padding-bottom: 10px;
        background: #fff;
    }

    .user_mes {
        background: #fff url(<?php echo JConfig::item('config.imgCdnDomain');?>/wap/activity/original/img/111.png) center top no-repeat;
        background-size: 100%;
        -webkit-align-items: center;
        align-items: center;
        -webkit-justify-content: center;
        justify-content: flex-start;
        position: relative;
        z-index: 100;
    }

    .article-cic {
        position: absolute;
        top: -5px;
        right: 0;
        left: 0;
        bottom: 0;
/*        background: url(*/<?php //echo JConfig::item('config.imgCdnDomain');?>/*/wap/activity/original/img/bg_article.png) center no-repeat;*/
        background-size: 100%;
        z-index: 1;
    }

    .update {
        background: url(https://app.cnu.edu.cn/wap/activity/original/img/update.png) no-repeat center;
        background-size: 15px auto;
    }
</style>
<?php $this->endBlock(); ?>
<div class="main">
    <div class="user_mes flex">
        <div class="user_img">
            <img src="<?php echo $userinfo['avatar']; ?>">
        </div>
        <div class="user_name">
            <p><?php echo $userinfo['username']; ?></p>
        </div>
        <?php if ($userinfo['uid'] == Yii::$app->user->uid): ?>
            <a class="artics"
               href="<?php echo Yii::$app->urlManager->createUrl(['/activity/wap/original/add', 'type' => $type]); ?>"><?php echo $type == 3 ? "原创写作" : "上传作品" ?></a>
        <?php else: ?>
            <?php $cls = in_array($userinfo['uid'], $follow_list) ? 'followed' : ''; ?>
            <a id="follow" class="artics <?php echo $cls; ?>"
               data-id="<?php echo $userinfo['uid']; ?>"><?php echo empty($cls) ? '点击关注' : '取消关注'; ?></a>
        <?php endif; ?>
    </div>
    <div class="flex">
        <?php if ($userinfo['uid'] == Yii::$app->user->uid): ?>
            <div class="focus focus-light">
                <p class="num">
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['/activity/wap/original/user-followed']) ?>"><?php echo $follow_num; ?></a>
                </p>
                <p><a href="<?php echo Yii::$app->urlManager->createUrl(['/activity/wap/original/user-followed']) ?>"
                      style="color:#50c5d3 ;">关注</a></p>
            </div>
        <?php else: ?>
            <div class="focus focus-light">
                <p class="num">
                    <?php echo $follow_num; ?>
                </p>
                <p>关注</p>
            </div>
        <?php endif; ?>
        <div class="focus">
            <p class="num"><?php echo !empty($likes_num) ? $likes_num : 0; ?></p>
            <p>点赞</p>
        </div>
        <div class="focus">
            <p class="num"><?php echo $works_num; ?></p>
            <p><?php if ($type == 0) {
                    echo '图集';
                } elseif ($type == 1) {
                    echo '歌曲';
                } elseif ($type == 3) {
                    echo '写作';
                } else {
                    echo '视频';
                } ?></p>
        </div>
        <div class="arc"></div>
    </div>
    <?php if ($type == 0): ?>
        <div class="img">
            <?php if (!empty($list)): foreach ($list as $v): ?>
                <div class="picture">
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['/activity/wap/original/detail', 'id' => $v['id']]); ?>"
                       style="background:url(<?php echo JConfig::item('config.imgServer.domain'), '/', $v['cover_pic']; ?>) no-repeat center center; background-size:cover;">
                        <span><?php echo $v['works_num']; ?>张</span>
                        <p class="title"><?php echo $v['title']; ?></p>
                    </a>
                </div>
            <?php endforeach;endif; ?>
        </div>
    <?php elseif ($type == 1): ?>
        <div class="img music-mes">
            <?php if (!empty($list)): foreach ($list as $v): ?>
                <a href="<?php echo Yii::$app->urlManager->createUrl(['/activity/wap/original/detail', 'id' => $v['id'], 'type' => $type]); ?>">
                    <div class="flex">
                        <div class="music-mesimg">
                            <div class="music-topimg">
                                <img src="<?php echo JConfig::item('config.imgServer.domain'), '/', $v['cover_pic']; ?>"
                                     data-id="<?php echo $v['id']; ?>">
                            </div>
                            <div class="music-cic"></div>
                        </div>
                        <div class="music-exp">
                            <p class="music-name"><?php echo $v['title']; ?></p>
                            <p class="music-count"><?php echo $v['works_num']; ?>首歌曲</p>
                        </div>
                        <div class="music-zan">
                            <span class="zan"><?php echo $v['likes_num']; ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach;endif; ?>
        </div>
    <?php elseif ($type == 3): ?>
        <div class="img music-mes">
        <?php if (!empty($list)): foreach ($list as $v): ?>
            <a href="<?php echo Yii::$app->urlManager->createUrl(['/activity/wap/original/article-detail', 'album_id' => $v['id'], 'type' => $type]); ?>">
                <div class="flex">
                    <div class="music-mesimg">
                        <div class="music-topimg">
                            <img src="<?php echo $userinfo['avatar']; ?>"
                                 data-id="<?php echo $v['id']; ?>">
                        </div>
                        <div class="article-cic"></div>
                    </div>
                    <div class="music-exp">
                        <p class="music-name"><?php echo $v['title']; ?></p>
                        <p class="music-count"><?php echo $v['works_num']; ?>篇写作</p>
                    </div>
                    <div class="music-zan">
                        <span class="zan"><?php echo $v['hot_num']; ?></span>
                    </div>
                </div>
            </a>
        <?php endforeach;endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php if ($type == 0): ?>
    <div id="ss_menu" style="transform: rotate(-180deg);" class="three_menu">
        <div onclick="window.location.reload();">
            <i class="refresh"></i>
        </div>
        <div onclick="window.location='<?php echo Yii::$app->urlManager->createUrl(['/activity/wap/original/list', 'type' => $type]) ?>'">
            <i class="fa fa-home"></i>
        </div>
        <div onclick="window.location='<?php echo Yii::$app->urlManager->createUrl(['/activity/wap/original/add', 'type' => $type]) ?>'">
            <i class="update"></i>
        </div>
        <div class="menu">
            <div class="share" id="ss_toggle" data-rot="180">
                <div class="bar"></div>
            </div>
        </div>
        <div class="shine"></div>
    </div>
<?php elseif ($type == 3): ?>
    <div id="ss_menu" style="transform: rotate(-180deg);" class="three_menu">
        <div onclick="window.location.reload();">
            <i class="refresh"></i>
        </div>
        <div onclick="window.location='<?php echo Yii::$app->urlManager->createUrl(['/activity/wap/original/list', 'type' => $type]) ?>'">
            <i class="fa fa-home"></i>
        </div>
        <div onclick="window.location='<?php echo Yii::$app->urlManager->createUrl(['/activity/wap/original/add', 'type' => $type]) ?>'">
            <i class="update"></i>
        </div>
        <div class="menu">
            <div class="share" id="ss_toggle" data-rot="180">
                <div class="bar"></div>
            </div>
        </div>
        <div class="shine"></div>
    </div>
<?php endif; ?>
<?php $this->beginBlock('footerJstext', 'append'); ?>
<script>
    var locked = false;
    var flag = true;
    $(".picture a").outerHeight($(".picture a").outerWidth() * 0.75);
    var userUtil = {
        follow: function (obj) {
            var fid = obj.attr('data-id');
            if (locked) return false;
            locked = true;
            $.ajax({
                url: "<?php echo Yii::$app->urlManager->createUrl(['/activity/wap/original/follow'])?>",
                cache: false,
                type: 'POST',
                dataType: 'JSON',
                data: {"fid[]": fid},
                success: function (response) {
                    if (response.e == '9999') {
                        if (obj.hasClass('followed')) {
                            obj.removeClass('followed').text('点击关注');
                        } else {
                            obj.addClass('followed').text('取消关注');
                        }
                        locked = false;
                    } else {
                        locked = false;
                    }
                },
                error: function () {
                    locked = false;
                }
            });
            return false;
        },
        play: function (obj, src, idx) {
            if (player.src != src) player.src = src;
            if (obj.children('.play').hasClass('play-no')) {
                if (playlist.indexOf(idx) == -1) {
                    playlist.push(idx);
                    userUtil.increment(idx);
                }
                player.play();
                $('.music').show();
            } else {
                player.pause();
                $('.music').hide();
            }
        },
        increment: function (idx) {
            if (locked) return false;
            locked = true;
            $.ajax({
                url: "<?php echo Yii::$app->urlManager->createUrl(['/activity/wap/original/views']);?>",
                type: 'POST',
                cache: false,
                data: {id: idx},
                dataType: 'JSON',
                success: function (response) {
                    locked = false;
                },
                error: function () {
                    wapalert('出错了');
                    locked = false;
                }
            })
        }
    };
    <?php if($userinfo['uid'] != Yii::$app->user->uid):?>
    $(function () {
        $('#follow').click(function () {
            userUtil.follow($(this));
        });
    });
    <?php endif;?>
    $(document).ready(function (ev) {
        var toggle = $('#ss_toggle');
        var menu = $('#ss_menu');
        var rot;
        $('#ss_toggle').on('click', function (ev) {
            rot = parseInt($(this).data('rot')) - 180;
            // console.log($(this).data('rot'));
            menu.css('transform', 'rotate(' + rot + 'deg)');
            menu.css('webkitTransform', 'rotate(' + rot + 'deg)');
            if (rot / 180 % 2 == 0) {
                toggle.parent().addClass('ss_active');
                toggle.addClass('close');
                $(".shine").addClass('ss_active');
            } else {
                toggle.parent().removeClass('ss_active');
                toggle.removeClass('close');
                $(".shine").removeClass('ss_active');
            }
            $(this).data('rot', rot);
        });
        menu.on('transitionend webkitTransitionEnd oTransitionEnd', function () {
            if (rot / 180 % 2 == 0) {
                $('#ss_menu div i').addClass('ss_animate');
            } else {
                $('#ss_menu div i').removeClass('ss_animate');
            }
        });
    });
</script>
<?php $this->endBlock(); ?>
