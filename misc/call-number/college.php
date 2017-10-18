<?php $this->beginBlock('header'); ?>
    <div class="head_my">
        <div class="collage_head">
            <span class="scan change_span"></span>
            <p class="collage_p">学院预约</p>
            <a class="search" href="javascript:void(0);"></a>
            <span class="user"></span>
        </div>
    </div>
<?php $this->endBlock(); ?>
    <div class="content">
        <div class="banner">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php if (!empty($data)) foreach ($data as $v): ?>
                        <div class="swiper-slide" data-id="<?php echo $v['id'] ?>">
                            <a href="javascript:;" class="banner-box">
                                <div class="fl">
                                    <img src="<?php echo $this->imghost . '/' . $v['imgurl'] ?>" alt="">
                                    <p>
                                        <?php if(!empty($v['avg'])) for($i=1;$i<=$v['avg'];$i++):?>
                                            <i></i>
                                        <?php endfor;?>
                                    </p>
                                </div>
                                <div class="fr">
                                    <p class="name"><?php echo $v['name']; ?></p>
                                    <p class="details">
                                        <?php echo $v['desc']; ?>
                                    </p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="lists">
            <ul class="list clearfix">
                <div class="pulling_down">
                    <i class="loading_icon"></i>
                    <span class="loading">下拉刷新中...</span>
                </div>
                <!-- <li>
                    <a href="javascript:;">
                        <p class="num">等待人数: <span>8</span></p>
                        <div class="name">
                            校园卡与网络
                        </div>
                    </a>
                </li> -->
            </ul>
        </div>
    </div>

<?php $this->beginBlock('footer'); ?>
<?php
echo \apps\frontend\widgets\Navigate::widget();
?>
<?php $this->endBlock(); ?>

<?php $this->beginBlock('modal'); ?>
    <div class="model"></div>
<?php
echo \apps\frontend\widgets\UserCenter::widget();
?>
    <div class="alert_model"></div>
    <div class="alert_box">
        <div class="details" style="display: none;">
            <div class="alert_content">
                <p class="title">详情</p>
                <p></p>
            </div>
            <div class="close_btn"></div>
        </div>
        <div class="order_box" style="display: none;">
            <p class="title">校园卡与网络</p>
            <p>国际交流合作处是学校涉外及涉港澳台事务工作管理的职能部门。</p>
            <p class="desc">当前有<span class="mark">15</span>人等待</p>
            <div class="btn_group">
                <div class="btn">
                    <a href="javascript:void(0)" class="cancel">取消</a>
                </div>
                <div class="btn">
                    <a href="javascript:void(0)" class="sure">预约</a>
                </div>
            </div>
        </div>
    </div>

<?php $this->endBlock(); ?>

<?php $this->beginBlock('jsText'); ?>

    <script>
        var now_id;//当前页面的业务类型
        $(function () {
            FastClick.attach(document.body);
            $('.userInfo').height($(window).height());
        });

        $('.footer a').on('click', function () {
            $(this).addClass('active').siblings().removeClass('active');
        })
        $('body').on('click', '.scan', function () {
            $('.wrap').addClass('active');
            $('.model').height($(window).height());
            noscroll(true)
        })
        $('body').on('click', '.model', function () {
            $('.wrap').removeClass('active');
            noscroll(false)
        })
        var mySwiper = new Swiper('.swiper-container', {
            // autoplay: 1000,//可选选项，自动滑动
            slidesPerView: 1.5,
            loop: true,
            onSlideChangeStart: function (swiper) {
                var index;
                if (swiper.activeIndex == <?php echo count($data);?>) {
                    index = 0;
                } else {
                    index = swiper.activeIndex;
                }
                var id = $('.swiper-slide[data-swiper-slide-index=' + index + ']').data('id');
                now_id = id;
                //===========================================================================
                //获得大厅下的所有业务
                //===========================================================================
                //初始化
                refreshBusiness(id);
                getDesc();
            }
        })
        if(<?php echo count($data);?>  <= 2){
            mySwiper.disableTouchControl();
        }else{
            mySwiper.enableTouchControl();
        }

        function noscroll(bool) {
            if (bool) {
                $(window).on('touchmove', function (e) {//禁止滑动
                    e.preventDefault();
                    e.stopPropagation();
                })
            } else {
                $(window).unbind("touchmove");//可以滑动
            }
        }

        // 刷新页面业务列表
        function refreshBusiness(id) {
            $('.content ul.list li').remove();
            var lock = false;
            if (lock) {
                return;
            }
            lock = true;
            $.ajax({
                type: 'get',
                data: {id: id},
                url: '<?php echo Yii::$app->urlManager->createUrl(['/frontend/college/lists']);?>',
                cache: false,
                dataType: 'json',
                success: function (args) {
                    if (args.e == 0) {
                        if (args.d != '') {
                            var html = '';
                            $.each(args.d.lists, function (i, v) {
                                html += '<li class="';
                                if (v.exist == 1 || v.is_start == false) {
                                    html += 'dis ';
                                }
                                var total = parseInt(v.count);
                                if (total < 5) {
                                    html += 'green"';
                                } else if (total >= 10) {
                                    html += 'red"';
                                } else {
                                    html += 'yellow"';
                                }
                                html += ' data-require="' + v.require + '"';
                                html += ' data-name="' + v.name + '"';
                                html += ' data-count="' + v.count + '"';
                                html += ' data-business_id="' + v.id + '"';
                                html += '><a href="javascript:;"><p class="num';
                                html += '"><span>' + v.count +
                                    '</span>等待人数</p><div class="name">' + v.name + '</div></a></li>';
                            });
                            $('.content ul.list li').remove();

                            if (now_id == args.d.type_id) {
                                $('.content ul.list').append(html);
                            }
                        }
                    } else {

                    }
                    lock = false;
                },
                error: function () {
                    lock = false;
                }
            });
        }
        var business_id;
        //为页面li绑定点击事件
        $('.content ul.list').on('click', 'li', function () {
            if ($(this).hasClass('dis')) return;

            var name = $(this).data('name');
            var require = $(this).data('require');
            var count = $(this).data('count');
            business_id = $(this).data('business_id');
            $('.order_box p:eq(0)').text(name);
            $('.order_box p:eq(1)').text(require);
            $('.order_box p:eq(2) span').text(count);
            $('.order_box .sure').attr('data-business_id', business_id);
            showModel();
        })

        //绑定预约事件
        var lock = false;
        $('.order_box .sure').on('click', function () {
            sendBusinessId(business_id);
        })

        //向queue发送business_id
        function sendBusinessId(id) {
            if (lock) {
                return;
            }
            lock = true;
            $.ajax({
                type: 'post',
                data: {business_id: id},
                url: '<?php echo Yii::$app->urlManager->createUrl(['/frontend/business/take-number']);?>',
                cache: false,
                dataType: 'json',
                success: function (args) {
                    lock = false
                    if (args.e == 0) {
                        alert_box('预约成功', 1, function () {
                            refreshBusiness(now_id);
                            hideModel();
                        })
                    } else {
                        alert_box(args.m, 2, function () {
                            if(!args.d.flag) refreshBusiness(now_id);
                            hideModel();
                        })
                    }
                },
                error: function () {
                    lock = false
                }
            });
        }

        //取消预约事件
        $('.order_box .cancel').on('click', function () {
            hideModel();
        })

        //弹窗显示
        function showModel() {
            $('.alert_model').show();
            setTimeout(function () {
                $('.alert_box').show();
                $('.alert_box .order_box').show();
            }, 15)
        }

        //取消弹窗显示
        function hideModel() {
            $('.order_box').hide();
            $('.alert_box .alert_box').hide();
            $('.alert_model').hide();
            $('.alert_box .details').hide();
        }

        //获取当前大厅介绍
        function getDesc() {
            //通过hall_id获得业务详情
            var lock = false;
            if (lock) {
                return;
            }
            lock = true;
            $.ajax({
                type: 'get',
                data: {id: now_id},
                url: '<?php echo Yii::$app->urlManager->createUrl(['/frontend/college/one']);?>',
                cache: false,
                dataType: 'json',
                success: function (args) {
                    if (args.e == 0) {
                        if (args.d != '') {
                            $('.details .alert_content p:eq(1)').html(args.d.desc);
                        }
                    } else {

                    }
                    lock = false;
                },
                error: function () {
                    lock = false;
                }
            });
        }

        //banner
        $('.banner .swiper-slide').click(function () {
            showBannerModel();
        })

        //轮播图弹窗显示
        function showBannerModel() {
            $('.alert_model').show();
            setTimeout(function () {
                $('.alert_box').show();
                $('.details').show();
            }, 15)
        }

        $('.details .close_btn').click(function () {
            hideModel();
        })

        $('.lists').height($(window).height() - $('.list').offset().top - 60);
        var scroll = document.querySelector('.list');
        var outerScroller = document.querySelector('.lists');
        var touchStart = 0;
        var touchDis = 0;
        var scrollList = 0;
        outerScroller.addEventListener('touchstart', function (event) {
            var touch = event.targetTouches[0];
            // 把元素放在手指所在的位置
            touchStart = touch.pageY;
        }, false);
        outerScroller.addEventListener('touchmove', function (event) {
            var touch = event.targetTouches[0];
            touchDis = touch.pageY - touchStart;
            if (touchDis > 0 && touchDis < 60) {
                $('.list').css('transform', 'translateY(' + touchDis + 'px)');
            }
        }, false);
        outerScroller.addEventListener('touchend', function (event) {
            if (touchDis > 10 && scrollList <= 0) {
//                 console.log(1)
                refreshBusiness(now_id)
            }
            noscroll(false);
            $('.list').css('transform', 'translateY(0px)')
            touchDis = 0;
        }, false);

        $('.lists').on('scroll', function () {
            scrollList = $(this).scrollTop();
            noscroll(true);
        })
    </script>
<?php $this->endBlock(); ?>