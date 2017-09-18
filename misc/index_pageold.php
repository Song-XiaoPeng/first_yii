<?php $this->beginblock('cssFile'); ?>
    <link rel="stylesheet" href="/pc/css/bootstrap.min.css">
    <link rel="stylesheet" href="/pc/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="/pc/css/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="/pc/css/cropper.css">
    <link rel="stylesheet" href="/pc/css/base.css">
    <link rel="stylesheet" href="/pc/css/book.css">
<?php $this->endBlock(); ?>
    <div class="wrap index">
        <div class="header">
            <div class="pic">
                <img src="./images/com.png" alt="">
            </div>
            <div class="right clearfix">
                <a href="javascript:void(0)" class="choose">选择工作窗口</a>
                <a href="javascript:void(0)" class="look">查看已办理事项</a>
                <a href="javascript:void(0)" class="person_name"><?php echo Yii::$app->manager->info['name']?></a>
                <a href="javascript:void(0)" class="person_pic">
                    <img src="<?php echo empty(Yii::$app->manager->info['headimg']) ? '':$this->imghost.'/'.Yii::$app->manager->info['headimg']?>" alt="">
                </a>
                <a href="javascript:void(0)" id="logout">
                    <img src="./images/close.png" alt="">
                </a>
            </div>
        </div>
        <div class="content">
            <ul class="left_ul">
                <li class="call_num">
                    <a href="javascript:void(0)">
                        <p>叫号</p>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="done">
                        <p>办结</p>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <p>过号</p>
                    </a>
                </li>
            </ul>
            <div class="content_right">
                <div class="top">
                    <span>正在办理</span>
                    <span><?php echo empty($data) ? '无' : $data['businessInfo']['name']?></span>
                </div>
                <div class="main">
                    <ul>
                        <li>
                            <div class="rotate_div">
                                <img src="images/rotate.png" alt="">
                                <div class="word">
                                    <p><?php echo empty($data) ? '无' : $data['id']?></p>
                                    <p>当前预约号</p>
                                </div>
                            </div>
                            <div class="main_bottom">
                                <div>
                                    <span>用户评分</span>
                                    <i class="active"></i>
                                    <i class="active"></i>
                                    <i class=""></i>
                                    <i class=""></i>
                                    <i class=""></i>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div>
                                <span>下一个等待</span>
                                <span id="next_business"></span>
                            </div>
                            <p>预约号:<span id="next_id"></span></p>
                            <p class="last"><span id="waiting_count"></span>人正在等待<i></i></p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="stop">
                <span>暂停</span>
            </div>
        </div>
        <div class="footer">
            <p>北京航空航天大学·瑞雷森科技</p>
        </div>
    </div>
    <div class="float_right">
        <ul>
        </ul>
    </div>
    <div class="model"></div>
    <!-- 评论 begin -->
    <div class="alert_box">
        <p class="alert_title">
            请填写评价
        </p>
        <div class="main_bottom">
            <div>
                <span>用户评分</span>
                <i class="active"></i>
                <i class="active"></i>
                <i class=""></i>
                <i class=""></i>
                <i class=""></i>
            </div>
        </div>
        <textarea name="desc" id="" cols="30" rows="10"></textarea>
        <div class="butn_group">
            <div class="butn">
                <a href="javascript:void(0)">取消</a>
            </div>
            <div class="butn">
                <a href="javascript:void(0)" id="eva-commit">确定</a>
            </div>
        </div>
    </div>
    <!--  选择窗口begin  -->
    <div class="choose_box">
        <p class="alert_title">
            请选择工作窗口
        </p>
        <div class="select">
            <p>请选择窗口<i></i></p>
            <ul>
                <li>1号窗口</li>
                <li>2号窗口</li>
                <li>3号窗口</li>
            </ul>
        </div>
        <div class="butn">
            <a href="javascript:void(0)">确定</a>
        </div>
    </div>
    <!-- 个人中心 begin  -->
    <div class="personal">
        <span class="close"></span>
        <p class="title">个人中心</p>
        <ul class="tab">
            <li class="active">基本资料</li>
            <li>密码修改</li>
            <li>手机号修改</li>
        </ul>
        <form id="edit_profile">
            <div class="person_box">
                <div class="box">
                    <div><span>职工编号：</span><span><?php echo Yii::$app->manager->info['number']?></span></div>
                    <div><span>星级：</span>
                        <div class="star"><i class="active"></i><i></i><i></i><i></i><i></i></div>
                    </div>
                    <div><span>办公室电话：</span>
                        <input type="text" name="tel" value="010-83425678">
                    </div>
                    <div><span>办公室地址：</span>
                        <input type="text" name="address" value="2号窗口">
                    </div>
                </div>
                <div class="box pass" style="display: none;">
                    <div><span>原密码</span>
                        <div>
                            <input type="password" name="password" placeholder="请输入当前密码">
                        </div>
                    </div>
                    <div><span>新密码</span>
                        <div>
                            <input type="password" name="new_password" placeholder="请输入新的密码6-12位数字或字母">
                            <!--                        <span class="notice" style="display:none">必填</span>-->
                        </div>
                    </div>
                    <div><span>确认新密码</span>
                        <div>
                            <input type="password" name="re_password" placeholder=" 请再次输入新密码 ">
                            <!--                        <span class="notice" style="display:none">两次输入不一致</span>-->
                        </div>
                    </div>
                </div>
                <div class="box phone" style="display: none; ">
                    <div><span>手机号码</span>
                        <div>
                            <input type="text" name="phone" placeholder="请输入新的手机号码">
                        </div>
                    </div>
                    <div><span>验证码</span>
                        <div>
                            <input type="text" name="verify_code" placeholder="请输入6位验证码">
                            <a id="send-vcode" href="javascript:void(0)">获取验证码</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="butn_group ">
                <div class="butn ">
                    <a href="javascript:void(0) ">取消</a>
                </div>
                <div class="butn ">
                    <a id="edit-personalinfo" href="javascript:void(0) ">确定</a>
                </div>
            </div>
        </form>
    </div>
    <!-- 修改头像 -->
    <div class="change_pic">
        <span class="close"></span>
        <p class="title">修改头像</p>
        <div class="pic_content">
            <div class="pic_top">
                <label class="btn btn-primary btn-upload" for="inputImage" title="Upload image file">
                    <input type="file" class="sr-only" id="inputImage" name="file"
                           accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false"
                          title="Import image with Blob URLs">
                        <span class="fa fa-upload">本地上传</span>
                    </span>
                </label>
                <p>图片大小不能超过5M，图片类型必须是jpg，gif，png，jpeg中的一种</p>
            </div>
            <div class="pic_main docs-buttons">
                <div class="big_pic">
                    <div class="big">
                        <img src="" alt="" id="image">
                    </div>
                    <div class="butns">
                        <button type="button" class="btn butn" data-method="rotate" data-option="-45"
                                title="Rotate Left">
                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false"
                                  title="$().cropper(&quot;rotate&quot;, -45)">
                            <span class="fa fa-rotate-left">左转</span>
                            </span>
                        </button>
                        <button type="button" class="btn butn" data-method="rotate" data-option="45"
                                title="Rotate Right">
                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false"
                                  title="$().cropper(&quot;rotate&quot;, 45)">
                            <span class="fa fa-rotate-right">右转</span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="small">
                    <div>
                        <div class="show docs-preview">
                            <div class="img-preview"></div>
                        </div>
                        <p>预览效果</p>
                    </div>
                    <button type="button" class="btn butn" data-method="getCroppedCanvas">
                        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false"
                              title="$().cropper(&quot;getCroppedCanvas&quot;)">
                          确认修改
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- 查看已办理事项 -->
    <div class="have_done">
        <span class="close"></span>
        <p class="title">已办理预约记录</p>
        <div class="search">
            <span>查询时间</span>
            <input type="text" id="birth" placeholder="请选择开始时间">
            <span>到</span>
            <input type="text" id="birth1" placeholder="请选择结束时间">
            <div class="butn"><span style="cursor: pointer"><i></i>查询</span></div>
        </div>
        <div class="list">
            <ul>
                <li>
                    <div>业务名称</div>
                    <div>用户名</div>
                    <div>预约号码</div>
                    <div>预约时间</div>
                    <div>办理（过号）时间</div>
                    <div>办理状态</div>
                </li>
            </ul>
        </div>
        <nav aria-label="Page navigation" class="page">
            <ul class="pagination" id="have_done">
                    <li class="left">
                        <a href="#" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    <ul>
                    </ul>
                    <li class="right">
                        <a href="#" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
        </nav>
    </div>
    <!-- 帮办 -->
    <div class="help_done">
        <span class="close"></span>
        <p class="title">已预约记录</p>
        <div class="list">
            <ul>
                <li>
                    <div>业务名称</div>
                    <div>用户名</div>
                    <div>预约号码</div>
                    <div>预约时间</div>
                    <div>办理（过号）时间</div>
                    <div>办理状态</div>
                </li>
            </ul>
        </div>
        <nav aria-label="Page navigation" class="page">
            <ul class="pagination" id="help">
                    <li class="left">
                        <a href="#" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    <ul>
                    </ul>
                    <li class="right">
                        <a href="#" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
        </nav>
    </div>
<?php $this->beginblock('jsFile'); ?>
    <script src="/pc/js/jquery-2.2.4.min.js"></script>
    <script src="/pc/js/popper.min.js"></script>
    <script src="/pc/js/bootstrap.min.js"></script>
    <script src="/pc/js/bootstrap-datepicker.min.js"></script>
    <script src="/pc/js/bootstrap-timepicker.min.js"></script>
    <script src="/pc/js/bootstrap-datepicker.zh-CN.min.js"></script>
    <script src="/pc/js/cropper.js"></script>
    <script src="/pc/js/main.js"></script>
<?php $this->endBlock(); ?>
<?php $this->beginblock('jsText'); ?>
    <script>
        function polling(){
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '<?php echo \Yii::$app->urlManager->createUrl(['/pc/default/next']); ?>',
                success: function (resp) {
                    if (resp.e == 0) {
                        var id = resp.d.count != 0 ? resp.d.data.id : '无';
                        var business = resp.d.count != 0 ? resp.d.data.businessInfo.name : '暂无业务';
                        var count = resp.d.count;
                        $('#next_id').html(id);
                        $('#next_business').html(business);
                        $('#waiting_count').html(count);
                    }
                },
                error:function(){
                    return;
                }
            });
        }
        $().ready(function(){
            polling();
            setInterval(polling,2000);
        });

        var queue_id = <?php echo empty($data) ? 'null' : $data['id'] ?>;//队列中的当前的预约号

        //评价 老师对学生
        $('#eva-commit').click(function(){
            var score = 0;
            $.each($('.alert_box .main_bottom i'),function(i,v){
                if($(v).hasClass('active')) score ++;
            })
            if(score <= 1 && $('.alert_box textarea').val() == ''){
                $('.alert_box textarea').css('border-color','#f00');
            }else{
                $('.alert_box textarea').css('border-color','#0083e2');
                $('.alert_box textarea').val('');
                $('.alert_box').hide();
                $('.model').hide()
            }
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {desc:$('.alert_box .desc').val(),score:score,deal_id:queue_id},
                url: '<?php echo \Yii::$app->urlManager->createUrl(['/pc/default/evaluate']); ?>',
                success: function (resp) {
                    if (resp.e == 0) {
                        pushLi(resp.m, textInit());
                    } else {
                        pushLi(resp.m);
                    }
                }
            })
        })


        //叫号
        $('.content .call_num a').click(function () {
            $.ajax({
                type: 'get',
                dataType: 'json',
                data: {},
                url: '<?php echo \Yii::$app->urlManager->createUrl(['/pc/default/call-number']); ?>',
                success: function (resp) {
                    if (resp.e == 0) {
                        queue_id = resp.d.id
                        $('.main .word p:eq(0)').text(resp.d.id);
                        $('.top span:eq(2)').text(resp.d.name);
                    } else {
                        pushLi('当前没有预约业务');
                    }
                }
            })
        })

        //办结
        $('.content .done').click(function () {
            $('.model').show();
            $('.alert_box').show();
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {id: queue_id},
                url: '<?php echo \Yii::$app->urlManager->createUrl(['/pc/default/finish']); ?>',
                success: function (resp) {
                    if (resp.e == 0) {
                        pushLi(resp.m, textInit());
                    } else {
                        pushLi(resp.m);
                    }
                }
            })
        })

        //过号
        $('.content li:eq(2) a').click(function () {
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {id: queue_id},
                url: '<?php echo \Yii::$app->urlManager->createUrl(['/pc/default/pass']); ?>',
                success: function (resp) {
                    if (resp.e == 0) {
                        pushLi(resp.m, textInit());
                    } else {
                        pushLi(resp.m);
                    }
                }
            })
        })

        //业务名称和当前预约号初始化
        function textInit() {
            $('.main .word p:eq(0)').text('无');
            $('.top span:eq(2)').text('无');
        }


        var i = 0;
        function pushLi(paramsStr, callback) {
            i++;
            var j = i;
            var html = '<li id="li_' + j + '" style="display:none"><i></i>' + paramsStr + '</li>'
            $('.float_right>ul').append(html);
            $('#li_' + j).fadeIn();
            setTimeout(function () {
                console.log($('#li_' + j));
                $('#li_' + j).fadeOut('normal', function () {
                    $(this).remove();
                    if (typeof callback == 'function') {
                        callback();
                    }
                });
            }, 1000);
        }
        //修改个人信息
        var lock = false;
        $('#edit-personalinfo').on('click', function () {
            //验证是否为空
            var pwd = checkPwd();
            var phone = checkPhone();
            //验证是否相等
            if (!pwd || !phone) return false;

            if (lock) {
                return;
            }
            lock = true;
            $.ajax({
                type: 'POST',
                data: $('#edit_profile').serialize(),
                url: '<?php echo Yii::$app->urlManager->createUrl(['/pc/default/edit']);?>',
                dataType: 'json',
                success: function (resp) {
                    if (resp.e == 0) {
                        $('.model').hide();
                        $('.personal').hide();
                        pushLi(resp.m);
                    } else {
                        pushLi(resp.m);
                    }
                    lock = false;
                }
            });
        });

        //手机验证码
        $('#send-vcode').click(function () {
            if ($(this).hasClass('haveclick')) {
                return;
            }
            if ($('input[name=phone]').val() == '') {
                return;
            }
            $(this).addClass('haveclick');
            num = 59;
            $('#send-vcode').html(num + 's');
            interval = setInterval(function () {
                num--;
                $('#send-vcode').html(num + 's');
                if (num == 0) {
                    clearInterval(interval);
                    $('#send-vcode').removeClass('haveclick');
                    $('#send-vcode').html('获取验证码');
                }
            }, 1000);

            $.ajax({
                type: 'POST',
                data: {phone: $('input[name=phone]').val()},
                url: '<?php echo Yii::$app->urlManager->createUrl(['/pc/default/verify-code']);?>',
                dataType: 'json',
                success: function (resp) {

                },
            });
        })


        //===================================================================================================
        // 验证
        //===================================================================================================
        function checkPhone(){
            var phone = $('input[name=phone]').val();
            if(!phone) return true;
            reg = /^1[34578]{1}\d{9}$/;
            if(!reg.test(phone)) {
                setWrong('input[name=phone]','<font color="red" size="1">请输入11位长度的有效手机号</font>');
                return false;
            }
            return true
        }

        function checkPwd() {
            if ($("input[name=password]").length > 0) {
                if ($("input[name=new_password]").val() == '' && $("input[name=password]").val() == '' && $("input[name=re_password]").val() == '') return true
                var ckopwd = checkEmpty("input[name=password]", "旧密码不能为空");
                var cknpwd = checkEmpty("input[name=new_password]", "新密码不能为空");
                var ckrpwd = checkEmpty("input[name=re_password]", "重复密码不能为空");
                if ($("input[name=new_password]").val() != $("input[name=re_password]").val()) {
                    setWrong("input[name=re_password]", "两次密码不一致");
                    ckrpwd = false;
                }
                return ckrpwd && ckopwd && cknpwd;
            }
            return true;
        }

        function checkEmpty(obj, text) {
            if ($.trim($(obj).val()) == "") {
                setWrong(obj, text);
                return false;
            } else {
                setRight(obj);
                return true;
            }
        }

        function setWrong(obj, text) {
            //var helpblock = $(obj).next();
            var helpblock = $(obj).closest('div').find('span');
            if (helpblock.length <= 0) {
                var text = '<span class="notice">' + text + '</span>';
                $(obj).addClass('notice').parent().append(text);
            } else {
                helpblock.html(text);
            }
        }

        function setRight(obj) {
            $(obj).removeClass('notice').parent('div').find('span').remove();
        }

        //退出登陆
        $('#logout').on('click', function () {
            $.ajax({
                type: 'post',
                url: '<?php echo Yii::$app->urlManager->createUrl(['/pc/login/logout/']);?>',
                dataType: 'json',
                success: function (args) {
                    if (args.e == 0) {
                        window.location.href = '<?php echo Yii::$app->urlManager->createUrl(['/pc/login/index']);?>';
                    } else {

                    }
                },
            });
        });

        //整个页面的高度
        if ($(window).height() > 780) {
            $('.content').height($(window).height() - $('.footer').height() - $('.header').height() - 20);
        }

        //选择窗口弹框
        $('.select>p').on('click', function () {
            $(this).siblings('ul').toggle()
            $(this).toggleClass('active');
        })
        $('.select>ul li').on('click', function () {
            var value = $(this).html();
            $('.select>p').removeClass('active');
            $('.select>p').html(value + '<i></i>');
            $('.select>ul').hide();
        })

        //叫号 旋转
        var deg = 0,
            rotateC,bool = true;
        $('.call_num').on('click', function () {
            if(bool){
                rotateC = setInterval(function () {
                    deg += 3;
                    $('.rotate_div>img').css('transform', "rotate( " + deg + "deg) ")
                }, 50);
            }
        })

        //办结旋转停止
        $('.done').on('click', function () {
            clearInterval(rotateC);
        })

        //个人信息tab切换
        $('.tab li').on('click', function () {
            $(this).addClass('active').siblings().removeClass('active');
            var index = $(this).index();
            $('.person_box>div').hide();
            $('.person_box>div').eq(index).show();
        })


        // 弹窗
        // 办结弹窗
        $('.alert_box .butn:first-child').click(function () {
            $('.model').hide();
            $('.alert_box').hide();
        })

        //选择窗口
        $('.choose').on('click', function () {
            $('.model').show();
            $('.choose_box').show();
        })

        $('.choose_box .butn').on('click', function () {
            $('.model').hide();
            $('.choose_box').hide();
        })

        //修改个人信息
        $('.person_name').on('click', function () {
            $('.model').show();
            $('.personal').show();
        })
        $('.personal .close').on('click', function () {
            $('.model').hide();
            $('.personal').hide();
        })

        //修改头像
        $('.person_pic').on('click', function () {
            $('.model').show();
            $('.change_pic').show();
        })
        $('.change_pic .close').on('click', function () {
            $('.model').hide();
            $('.change_pic').hide();
        })

        //已办理事项
        $('.look').on('click', function () {
            $('.model').show();
            $('.have_done').show();
//            page1.init()
        })
        $('.have_done .close').on('click', function () {
            $('.model').hide();
            $('.have_done').hide();
        })

        //日历
        $('#birth').datepicker({
            format: "yyyy-mm-dd",
            todaybutn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: "zh-CN"
        });
        $('#birth1').datepicker({
            format: "yyyy-mm-dd",
            todaybutn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: "zh-CN"
        });

        // 办结弹窗 评价星星
        $('.alert_box .main_bottom i').on('click', function() {
            var that = $(this)
            getIndex(that);
        })

        function getIndex(that) {
            $('.alert_box .main_bottom i').removeClass('active');
            var index = that.index();
            for (var i = 0; i < index; i++) {
                $('.alert_box .main_bottom i').eq(i).addClass('active');
            }
        }

        // 暂停/开始叫号
        $('.stop').on('click',function(){
            $(this).toggleClass('start');
            if($(this).hasClass('start')){
                $(this).find('span').html('开始');
            }else{
                $(this).find('span').html('暂停');
            }
        })

        $('p.last i').on('click',function(){
            $('.help_done').show();
            $('.model').show();
            page2.init()
        })
        $('.help_done .close').on('click', function () {
            $('.model').hide();
            $('.help_done').hide();
        })


        //===================================================================================================
        // 分页
        //===================================================================================================

        // 页码显示
        // 总页数，当前高亮显示的页码，最多显示分页数
        function showPager(totalPageNum, idx, maxPage) {
            if (totalPageNum <= 1) {
                $('.pagination .left,.pagination .right').hide();
            }
            //可以全部显示页码
            if (totalPageNum >= 0 && totalPageNum <= maxPage) {
                var str = '';
                for (var i = 1; i <= totalPageNum; i++) {
                    if (i == idx) {
                        str += '<li class="active" li_id=' + i + '><a href="#">' + i + '</a></li>';
                    } else {
                        str += '<li li_id=' + i + '><a href="#">' + i + '</a></li>';
                    }
                }
                $('.pagination ul').html(str);

                //不能全部显示页码
            } else {
                //当前高亮的在第一位的时候 1 2 3 4 5 ... totalPageNum
                if (idx == 1) {
                    var str = '';
                    for (var i = 1; i <= maxPage; i++) {
                        if (i == maxPage - 1) {
                            str += '<li><a href="#">...</a></li>';
                        } else if (i == maxPage) {
                            str += '<li li_id=' + totalPageNum + '><a href="#">' + totalPageNum + '</a></li>';
                        } else if (i == idx) {
                            str += '<li class="active" li_id=' + i + '><a href="#">' + i + '</a></li>';
                        } else {
                            str += '<li li_id=' + i + '><a href="#">' + i + '</a></li>';
                        }

                    }
                    $('.pagination ul').html(str);
                    //当前高亮显示的在后几位或者是最后一位的时候  1 ... totalPageNum-4 totalPageNum-3 totalPageNum-2 totalPageNum-1 totalPageNum
                } else if (idx > totalPageNum - 4 || idx == totalPageNum) {
                    var str = '';
                    for (var i = 1, j = totalPageNum - 6; i <= maxPage; i++, j++) {
                        if (i == 1) {
                            str += '<li li_id=' + 1 + '><a href="#">1</a></li>';
                        } else if (i == 2) {
                            str += '<li><a href="#">...</a></li>';
                        } else if (i >= 3) {
                            if (j == idx) {
                                str += '<li class="active" li_id=' + j + '><a href="#">' + j + '</a></li>';
                            } else {
                                str += '<li li_id=' + j + '><a href="#">' + j + '</a></li>';
                            }
                        }
                    }
                    $('.pagination ul').html(str);
                    //当前高亮显示的在第五位之后  1 ... idx-1 idx idx+1 ... totalPageNum
                } else if (idx > 5) {
                    var str = '';
                    for (var i = 1; i <= maxPage; i++) {
                        if (i == 1) {
                            str += '<li li_id="1"><a href="#">1</a></li>';
                        } else if (i == 2 || i == maxPage - 1) {
                            str += '<li><a href="#">...</a></li>';
                        } else if (i == 3) {
                            str += '<li li_id=' + (idx - 1) + '><a href="#">' + (idx - 1) + '</a></li>';
                        } else if (i == 4) {
                            str += '<li class="active" li_id=' + idx + '><a href="#">' + idx + '</a></li>';
                        } else if (i == 5) {
                            str += '<li li_id=' + (idx + 1) + '><a href="#">' + (idx + 1) + '</a></li>';
                        } else {
                            str += '<li li_id=' + totalPageNum + '><a href="#">' + totalPageNum + '</a></li>';
                        }
                    }
                    $('.pagination ul').html(str);
                    //其余情况
                } else {
                    var str = '';
                    for (var i = 1; i <= maxPage; i++) {
                        if (i == maxPage - 1) {
                            str += '<li><a href="#">...</a></li>';
                        } else if (i == maxPage) {
                            str += '<li  li_id=' + totalPageNum + '><a href="#">' + totalPageNum + '</a></li>';
                        } else if (i == idx) {
                            str += '<li class="active"  li_id=' + i + '><a href="#">' + i + '</a></li>';
                        } else {
                            str += '<li li_id=' + i + '><a href="#">' + i + '</a></li>';
                        }
                    }
                    $('.pagination ul').html(str);
                }
            }
        }

        // 翻页
        function pageChange(bool) {
            if (bool) { //上一页
                if (current <= 1) {
                    return;
                } else {
                    current -= 1;
                    $('.pagination>ul li').removeClass('active');
                    if ($('.pagination>ul li').attr('li_id') == current) {
                        var i = $('.pagination>ul li').index();
                        $('.pagination>ul li').eq(i).addClass('active')
                    }
                }
            } else { //下一页
                if (current >= pageNum) {
                    return;
                } else {
                    current += 1;
                    $('.pagination>ul li').removeClass('active');
                    if ($('.pagination>ul li').attr('li_id') == current) {
                        var i = $('.pagination>ul li').index();
                        $('.pagination>ul li').eq(i).addClass('active')
                    }
                }
            }
            showPager(pageNum, current, 7);
        }


        //点击页码
        function gotoPage(that) {
            var idx = that.attr('li_id');
            if (that.find('a').html() == '...') {
                return;
            } else {
                that.addClass('active').siblings().removeClass('active');
                current = parseInt(idx);
            }
        }

        // ajax 请求
        function pageajax(data, url) {
            if (typeof url == 'undefined') {
                console.log(data);
            } else {
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    data: data,
                    async: false,
                    //jsonp: "callback",
                    success: function (resp) {
                        change(resp);
                    }
                });
            }
        }

        //===================================================================================================
        // 分页----查看已办理记录数据显示
        //===================================================================================================
        var current = 1;//当前页
        var totalNum;//查询记录总数
        var pagePer = 10;//每页显示多少条
        var pageNum;//总页数
        var maxPage = 7;

        var data;//分页ajax传递的数据
        var url = '<?php echo \Yii::$app->urlManager->createUrl(['/pc/default/deal-logs']); ?>';//ajax分页请求的url

        //点击<<
        $('.pagination .left').on('click', function () {
            pageChange(true);
            data = {start: $('#birth').val(), end: $('#birth1').val(), p: current, page_size: pagePer};
            pageajax(data, url);
        })

        //点击>>
        $('.pagination .right').on('click', function () {
            pageChange(false);
            data = {start: $('#birth').val(), end: $('#birth1').val(), p: current, page_size: pagePer};
            pageajax(data, url);
        })

        //点击页码
        $(document).on('click', '.pagination ul li', function () {
            var that = $(this);
            gotoPage(that)
            data = {start: $('#birth').val(), end: $('#birth1').val(), p: current, page_size: pagePer};
            pageajax(data, url);
        })

        //点击查询按钮
        $('.search .butn span').click(function () {
            data = {start: $('#birth').val(), end: $('#birth1').val(), p: current, page_size: pagePer};
            pageajax(data, url);
            showPage();
        })

        //点击查看已办理事项
        $('.look').click(function () {
            data = {start: $('#birth').val(), end: $('#birth1').val(), p: current, page_size: pagePer};
            pageajax(data, url);
            showPage();
        });

        //显示分页条
        function showPage(){
            pageNum = Math.ceil(totalNum / pagePer);
            showPager(pageNum, current, maxPage);
        }

        //分页ajax回调函数
        function change(resp) {
            $('.have_done .list ul li:gt(0)').remove();
            var html = '';
            if (resp.e == 0) {
                totalNum = resp.d.count;
                if (resp.d.lists.length > 0)
                    $.each(resp.d.lists, function (i, v) {
                        html += '<li>';
                        html += '<div>' + v.business_name + '</div>';
                        html += '<div>' + v.user_name + '</div>';
                        html += '<div>' + v.id + '</div>';
                        html += '<div>' + v.start + '</div>';
                        html += '<div>' + v.end + '</div>';
                        html += '<div>' + v.status + '</div>';
                        html += '</li>';
                    })
                $('.have_done .list ul').append(html);
            }
        }

        //===================================================================================================
        // 分页----查看已预约记录
        //===================================================================================================
        /*function queueList(){
            $('.help_done .list ul li:gt(0)').remove();
            var html = '';
            if (resp.e == 0) {
                totalNum = resp.d.count;
                if (resp.d.lists.length > 0)
                    $.each(resp.d.lists, function (i, v) {
                        html += '<li>';
                        html += '<div>' + v.business_name + '</div>';
                        html += '<div>' + v.user_name + '</div>';
                        html += '<div>' + v.id + '</div>';
                        html += '<div>' + v.start + '</div>';
                        html += '<div>' + v.end + '</div>';
                        html += '<div>' + v.status + '</div>';
                        html += '</li>';
                    })
                $('.have_done .list ul').append(html);
            }
        }*/





        /*        function Pager(totalNum, pagePer,url) {
                    var obj = {};
                    obj.current = 1;
                    obj.totalNum = totalNum; //总页数
                    obj.pagePer = pagePer; //每页显示的数据条数
                    obj.pageNum = Math.ceil(obj.totalNum / obj.pagePer); //总页数
                    obj.init = function() {
                        obj.showPager(obj.pageNum, obj.current, 7);
                    };

                    // 页码显示
                    // 总页数，当前高亮显示的页码，最多显示分页数
                    obj.showPager = function(totalPageNum, idx, maxPage) {
                        if (totalPageNum <= 1) {
                            $('.left,.right').hide();
                        }
                        //可以全部显示页码
                        if (totalPageNum >= 1 && totalPageNum <= maxPage) {
                            var str = '';
                            for (var i = 1; i <= totalPageNum; i++) {
                                if (i == idx) {
                                    str += '<li class="active" li_id=' + i + '><a href="#">' + i + '</a></li>';
                                } else {
                                    str += '<li li_id=' + i + '><a href="#">' + i + '</a></li>';
                                }
                            }
                            $('.pagination ul').html(str);

                            //不能全部显示页码
                        } else {
                            //当前高亮的在第一位的时候 1 2 3 4 5 ... totalPageNum
                            if (idx == 1) {
                                var str = '';
                                for (var i = 1; i <= maxPage; i++) {
                                    if (i == maxPage - 1) {
                                        str += '<li><a href="#">...</a></li>';
                                    } else if (i == maxPage) {
                                        str += '<li li_id=' + totalPageNum + '><a href="#">' + totalPageNum + '</a></li>';
                                    } else if (i == idx) {
                                        str += '<li class="active" li_id=' + i + '><a href="#">' + i + '</a></li>';
                                    } else {
                                        str += '<li li_id=' + i + '><a href="#">' + i + '</a></li>';
                                    }

                                }
                                $('.pagination ul').html(str);
                                //当前高亮显示的在后几位或者是最后一位的时候  1 ... totalPageNum-4 totalPageNum-3 totalPageNum-2 totalPageNum-1 totalPageNum
                            } else if (idx > totalPageNum - 4 || idx == totalPageNum) {
                                var str = '';
                                for (var i = 1, j = totalPageNum - 6; i <= maxPage; i++, j++) {
                                    if (i == 1) {
                                        str += '<li li_id=' + 1 + '><a href="#">1</a></li>';
                                    } else if (i == 2) {
                                        str += '<li><a href="#">...</a></li>';
                                    } else if (i >= 3) {
                                        if (j == idx) {
                                            str += '<li class="active" li_id=' + j + '><a href="#">' + j + '</a></li>';
                                        } else {
                                            str += '<li li_id=' + j + '><a href="#">' + j + '</a></li>';
                                        }
                                    }
                                }
                                $('.pagination ul').html(str);
                                //当前高亮显示的在第五位之后  1 ... idx-1 idx idx+1 ... totalPageNum
                            } else if (idx > 5) {
                                var str = '';
                                for (var i = 1; i <= maxPage; i++) {
                                    if (i == 1) {
                                        str += '<li li_id="1"><a href="#">1</a></li>';
                                    } else if (i == 2 || i == maxPage - 1) {
                                        str += '<li><a href="#">...</a></li>';
                                    } else if (i == 3) {
                                        str += '<li li_id=' + (idx - 1) + '><a href="#">' + (idx - 1) + '</a></li>';
                                    } else if (i == 4) {
                                        str += '<li class="active" li_id=' + idx + '><a href="#">' + idx + '</a></li>';
                                    } else if (i == 5) {
                                        str += '<li li_id=' + (idx + 1) + '><a href="#">' + (idx + 1) + '</a></li>';
                                    } else {
                                        str += '<li li_id=' + totalPageNum + '><a href="#">' + totalPageNum + '</a></li>';
                                    }
                                }
                                $('.pagination ul').html(str);
                                //其余情况
                            } else {
                                var str = '';
                                for (var i = 1; i <= maxPage; i++) {
                                    if (i == maxPage - 1) {
                                        str += '<li><a href="#">...</a></li>';
                                    } else if (i == maxPage) {
                                        str += '<li  li_id=' + totalPageNum + '><a href="#">' + totalPageNum + '</a></li>';
                                    } else if (i == idx) {
                                        str += '<li class="active"  li_id=' + i + '><a href="#">' + i + '</a></li>';
                                    } else {
                                        str += '<li li_id=' + i + '><a href="#">' + i + '</a></li>';
                                    }
                                }
                                $('.pagination ul').html(str);
                            }
                        }
                    };
                    // 翻页
                    obj.pageChange = function(bool) {
                        if (bool) { //上一页
                            if (obj.current <= 1) {
                                return;
                            } else {
                                obj.current -= 1;
                                $('.pagination>ul li').removeClass('active');
                                if ($('.pagination>ul li').attr('li_id') == obj.current) {
                                    var i = $('.pagination>ul li').index();
                                    $('.pagination>ul li').eq(i).addClass('active')
                                }
                            }
                        } else { //下一页
                            if (obj.current >= obj.pageNum) {
                                return;
                            } else {
                                obj.current += 1;
                                $('.pagination>ul li').removeClass('active');
                                if ($('.pagination>ul li').attr('li_id') == obj.current) {
                                    var i = $('.pagination>ul li').index();
                                    $('.pagination>ul li').eq(i).addClass('active')
                                }
                            }
                        }
                        obj.showPager(obj.pageNum, obj.current, 7);
                    }
                    //点击页码
                    obj.gotoPage = function(that) {
                        var idx = that.attr('li_id');
                        if (that.find('a').html() == '...') {
                            return;
                        } else {
                            that.addClass('active').siblings().removeClass('active');
                            obj.current = parseInt(idx);
                        }
                    }
                    // ajax 请求
                    obj.pageajax = function(data, url) {
                        $.ajax({
                            url: url,
                            type: "GET",
                            dataType: "jsonp",
                            data: data,
                            jsonp: "callback",
                            success: function(resp) {}
                        });
                    }
                    return obj;

                }
                var page1 = new Pager(100, 20);
                var page2 = new Pager(100, 10);


                $('#have_done .left').on('click', function() {
                    page1.pageChange(true);
                })
                $('#have_done .right').on('click', function() {
                    page1.pageChange(false);
                })
                $('#help .left').on('click', function() {
                    page2.pageChange(true);
                })
                $('#help .right').on('click', function() {
                    page2.pageChange(false);
                })

                $(document).on('click', '#have_done ul li', function() {
                    var that = $(this);
                    page1.gotoPage(that);
                })
                $(document).on('click', '#help ul li', function() {
                    var that = $(this);
                    page2.gotoPage(that);
                })*/
    </script>
<?php $this->endBlock(); ?>