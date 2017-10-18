<?php 
use apps\base\widgets\ActivityBase;
$this->set('_vue_version_', '2.1.10');
$urlprefix = Yii::$app->controller->module->appkey.'/'.Yii::$app->controller->id;
$this->set('title',empty($info['id']) ? '创建模板' : '修改模板');
?>
<div class="row" id="app">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading top">
                <a href="javascript:history.go(-1);" class="back"></a>
                <h3 class="panel-title"><?php echo empty($info['id']) ? '创建模板' : '修改模板'; ?></h3>
            </div>
            <form class="form-horizontal mar-top" onsubmit="return false;" id="data-form">
                <div class="panel-body">
                    
                    <div id="baseApp">
<!-- --------基础信息-------- -->                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">卡号前缀</label>
                            <div class="col-md-7">
                                <input type="text" name="biz_no_prefix" class="form-control" placeholder="卡号固定前缀，选填"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">* 卡号后缀的长度</label>
                            <div class="col-md-7">
                                <input type="text" name="biz_no_suffix_len" class="form-control" placeholder="卡号为固定前缀+固定长度后缀，总长度不能超过32位"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">* 会员卡码</label>
                            <div class="col-md-7">
                                <select name="write_off_type" class="form-control">
                                    <option name="qrcode">静态二维码</option>
                                    <option name="barcode">静态条形码</option>
                                    <option name="dqrcode">动态二维码</option>
                                    <option name="dbarcode">动态条形码</option>
                                    <!--option name="mdqrcode">自定义二维码</option>
                                    <option name="mdbarcode">自定义条形码</option-->
                                </select>
                            </div>
                        </div>
                        
<!-- --------模板样式-------- -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">* 名称</label>
                            <div class="col-md-7">
                                <input type="text" name="template_style_info.card_show_name" class="form-control" placeholder="会员卡名称，例如 XX大学校园卡"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">* logo</label>
                            <div class="col-md-7">
                                <!--上传控件-->
                                <input type="hidden" name="template_style_info.logo_id" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">* 背景图</label>
                            <div class="col-md-7">
                                <!--上传控件-->
                                <input type="hidden" name="template_style_info.background_id" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">* 背景色</label>
                            <div class="col-md-7">
                                <!--取色器-->
                                <input type="hidden" name="template_style_info.bg_color" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">卡面展示文案信息</label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <input name="template_style_info.front_text_list_enable" id="front_text_list_enable_yes" class="magic-radio" type="radio"  value="0" checked>
                                    <label for="front_text_list_enable_yes">不显示</label>
                                </div>
                                <div class="radio">
                                    <input name="template_style_info.front_text_list_enable" id="front_text_list_enable_no" class="magic-radio" type="radio"  value="1" >
                                    <label for="front_text_list_enable_no">显示</label>
                                </div>
                            </div>
                            <div class="col-sm-7 col-sm-offset-3">
                            文案信息分行展示，最多展示3行文案，每行文案分为label和value两部分； <br />
                            文案实际内容随创建卡/更新卡时传入； 
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">卡面展示个人头像</label>
                            <div class="col-md-7">
                                <div class="radio">
                                    <input name="template_style_info.front_image_enable" id="front_image_enable_yes" class="magic-radio" type="radio" value="0" checked>
                                    <label for="front_image_enable_yes">不显示</label>
                                </div>
                                <div class="radio">
                                    <input name="template_style_info.front_image_enable" id="front_image_enable_no" class="magic-radio" type="radio"  value="1" >
                                    <label for="front_image_enable_no">显示</label>
                                </div>
                            </div>
                            <div class="col-sm-7 col-sm-offset-3">
                            当前仅用于身份验证信息类型的个人头像图片； <br />
                            图片id随创建卡/更新卡时传入；
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">特色信息，用于领卡预览</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="template_style_info.feature_descriptions" value="" placeholder="例如：使用花呗卡可享受免费分期"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">标语</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="template_style_info.slogan" value="" placeholder="可选，例如：会员权益享不停"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">标语图片</label>
                            <div class="col-md-7">
                                <!--上传控件-->
                                <input type="hidden" name="template_style_info.slogan_img_id"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">品牌名称</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="template_style_info.brand_name" value="" placeholder="例如：可口可乐"/>
                            </div>
                        </div>

<!-- --------权益信息 选填 可以填多行 template_benefit_info-------- -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">权益</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="template_benefit_info.title" value="" placeholder=""/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">权益描述信息</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="template_benefit_info.benefit_desc" value="" placeholder=""/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">开始时间</label>
                            <div class="col-md-7">
                                <!--时间选择控件-->
                                <input type="text" class="form-control" name="template_benefit_info.start_date" value="" placeholder=""/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">结束时间</label>
                            <div class="col-md-7">
                                <!--时间选择控件-->
                                <input type="text" class="form-control" name="template_benefit_info.end_date" value="" placeholder=""/>
                            </div>
                        </div>

<!-- --------栏位信息 必选 可以填多行 column_info_list-------- -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">标准栏位</label>
                            <div class="col-md-7">
                                <select name="column_info_list.code" class="form-control">
                                    <option value="BALANCE">余额</option>
                                    <option value="POIN">积分</option>
                                    <option value="LEVEL">等级</option>
                                    <option value="TELEPHONE">联系方式</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">自定义栏位</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="column_info_list.code" value="" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">点击响应操作</label>
                            <!--不填则默认staticinfo； 标准code尽量使用staticinfo，例如TELEPHONE商家电话栏位就只支持staticinfo；-->
                            <div class="col-md-7">
                                <select name="column_info_list.operate_type" class="form-control">
                                    <option value="openNative">打开二级页面，展现 more中descs </option>
                                    <option value="openWeb">打开URL</option>
                                    <option value="staticinfo">静态信息</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">标题</label>
                            <div class="col-md-7">
                                <input type="text" name="column_info_list.title" class="form-control" value="" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">内容</label>
                            <div class="col-md-7">
                                <input type="text" name="column_info_list.value" class="form-control" value="" placeholder=""/>
                            </div>
                        </div>
                        <!--more_info 二级页面扩展信息-->
                        <div class="form-group">
                            <label class="col-md-3 control-label">二级页面标题</label>
                            <div class="col-md-7">
                                <input type="text" name="column_info_list.more_info.title" class="form-control" value=""  placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">超链接(选择openweb的时候必须填写url参数内容)</label>
                            <div class="col-md-7">
                                <input type="text" name="column_info_list.more_info.url" class="form-control" value="" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">扩展参数，需要URL地址回带的值，JSON格式(openweb时填)</label>
                            <div class="col-md-7">
                                <input type="text" name="column_info_list.more_info.params" class="form-control" value="" placeholder=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">选择opennative的时候必须填写descs的内容</label>
                            <div class="col-md-7">
                                <input type="text" name="column_info_list.more_info.descs" class="form-control" value="" placeholder=""/>
                            </div>
                        </div>
                        
<!-- --------字段规则列表 必选 可以填多行 field_rule_list-------- -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">字段名称</label>
                            <div class="col-md-7">
                                <select name="field_rule_list.field_name" class="form-control">
                                    <option value="Balance">金额</option>
                                    <option value="Point">整数</option>
                                    <option value="Level">任意字符串</option>
                                    <option value="OpenDate">开卡日期</option>
                                    <option value="ValidDate">过期日期</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">规则名</label>
                            <div class="col-md-7">
                                <select name="field_rule_list.rule_name" class="form-control">
                                    <option value="ASSIGN_FROM_REQUEST">以rule_value为key值，表示该栏位的值从会员卡开卡接口中获取，会员卡开卡接口的card_info中获取对应参数值 </option>
                                    <option value="DATE_IN_FUTURE">生成一个未来的日期（格式YYYY-MM-DD)，当选择DATE_IN_FUTURE的时候，field_name 必须是OpenDate或ValidDate， 值为(10m或10d 分别表示10个月或10天) </option>
                                    <option value="CONST">常量，会员卡开卡接口进行开卡的时候使用模板创建时候设置的值，即取rule_value的值	ASSIGN_FROM_REQUEST</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <!-- * 根据rule_name，采取相应取值策略 
                            * CONST：直接取rule_value作为卡属性值 
                            * DATE_IN_FUTURE：10m或10d 分别表示10个月或10天 
                            * ASSIGN_FROM_REQUEST：在开卡Reuqest请求中按rule_value取值，现在和field_name对应的为（OpenDate、ValidDate、Level、Point、Balance）	Balance
                            -->
                            <label class="col-md-3 control-label">选择opennative的时候必须填写descs的内容</label>
                            <div class="col-md-7">
                                <input type="text" name="column_info_list.rule_value" class="form-control" value="" placeholder=""/>
                            </div>
                        </div>
<!-- -----行动点，即用户可点击跳转的区块 可以填多行 ，类似按钮控件的交互，单张卡最多定制4个行动点。 ----- -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">行动点业务CODE</label>
                            <div class="col-md-7">
                                <input type="text" name="card_action_list.code" class="form-control" value="" placeholder="自定义，不超过32位" maxlength=32/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">行动点展示文案</label>
                            <div class="col-md-7">
                                <input type="text" name="card_action_list.text" class="form-control" value="" placeholder="例如：打卡" maxlength="6"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">行动点跳转链接</label>
                            <div class="col-md-7">
                                <input type="text" name="card_action_list.url" class="form-control" value="" placeholder="行动点跳转链接"/>
                            </div>
                        </div>
<!-- ------open_card_conf 选填 会员卡用户领卡配置，在门店等渠道露出领卡入口时，需要部署的商户领卡H5页面地址 ------ -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">* 行动点跳转链接</label>
                            <div class="col-md-7">
                                <select class="" name="open_card_conf.open_card_source_type">
                                    <option value="ISV">外部系统</option>
                                    <option value="MER">直连商户</option>
                                </select>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">渠道APPID</label>
                            <div class="col-md-7">
                                <input type="text" name="open_card_conf.source_app_id" class="form-control" value="" placeholder="提供领卡页面的服务提供方"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">开卡连接</label>
                            <div class="col-md-7">
                                <input type="text" name="open_card_conf.open_card_url" class="form-control" value="" placeholder="必须http、https开头"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">配置</label>
                            <div class="col-md-7">
                                <input type="text" name="open_card_conf.conf" class="form-control" value="" placeholder="预留字段，暂时不用"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">服务Code</label>
                            <div class="col-md-7">
                                <input type="text" name="service_label_list" class="form-control" value="" placeholder="HUABEI_FUWU 花呗服务（只有需要花呗服务时，才需要加入该标识"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">会员卡上架门店id</label>
                            <div class="col-md-7">
                                <input type="text" name="shop_ids" class="form-control" value="" placeholder="会员卡上架门店id（支付宝门店id），既发放会员卡的商家门店id"/>
                            </div>
                        </div>
<!--pub_channels 卡模板投放渠道 可选 可重复 -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">卡模板投放渠道</label>
                            <div class="col-md-7">
                                <select name="pub_channels.pub_channel" class="form-control">
                                    <option value="SHOP_DETAIL">店铺详情页 </option>
                                    <option value="PAYMENT_RESULT">支付成功页（支付成功页暂不支持）</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">扩展信息，无需配置</label>
                            <div class="col-md-7">
                                <input type="text" name="pub_channels.ext_info" class="form-control" value="" placeholder="会员卡上架门店id（支付宝门店id），既发放会员卡的商家门店id"/>
                            </div>
                        </div>
<!--card_level_conf 卡级别配置 可选 可重复 -->                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">会员级别 </label>
                            <div class="col-md-7">
                                <input type="text" name="card_level_conf.level" class="form-control" value="" placeholder="该级别和开卡接口中的level要一致, 例如：VIP1"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">会员级别显示名称 </label>
                            <div class="col-md-7">
                                <input type="text" name="card_level_conf.level_show_name" class="form-control" value="" placeholder="例如：黄金会员"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">会员级别对应icon </label>
                            <!-- 需要上传 -->
                                <input type="hidden" name="card_level_conf.level_icon"/>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">会员级别描述 </label>
                            <div class="col-md-7">
                                <input type="text" name="card_level_conf.level_desc" class="form-control" value="" placeholder="黄金会员享受免费停车"/>
                            </div>
                        </div>
<!-- mdcode_notify_conf 可选-->
<!--
商户动态码通知参数配置： 
当write_off_type指定为商户动态码mdbarcode或mdqrcode时必填； 
在此字段配置用户打开会员卡时支付宝通知商户生成动态码（发码）的通知参数，如接收通知地址等。
-->
                        <div class="form-group">
                            <label class="col-md-3 control-label">商户接收发码通知的地址链接 </label>
                            <div class="col-md-7">
                                <input type="text" name="mdcode_notify_conf.url" class="form-control" value="" placeholder="只支持https地址"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">扩展参数信息,键值对，json </label>
                            <div class="col-md-7">
                                <input type="text" name="mdcode_notify_conf.ext_params" class="form-control" value="" placeholder=""/>
                            </div>
                        </div>

<!--
         * mdcode_notify_conf	TemplateMdcodeNotifyConfDTO	可选		
            * 商户动态码通知参数配置： 
            * 当write_off_type指定为商户动态码mdbarcode或mdqrcode时必填； 
            * 在此字段配置用户打开会员卡时支付宝通知商户生成动态码（发码）的通知参数，如接收通知地址等。	
         * └ url	String	必填	1024	
            * 商户接收发码通知的地址链接； 
            * 只支持https地址； 
            * 用户打开会员卡时，支付宝提交POST请求此url地址，通知商户发码。	https://www.ali123.com/ant/mdcode
         * └ ext_params	String	可选	1024	
            * 扩展参数信息； 
            * 格式为key-value键值对； 
            * 支付宝POST请求指定url时，除BizCardNo等固定参数外，将带上ext_params中配置的所有key-value参数。	{"param1":"value1","param2":"value2"}
         -->
                    </div>
                </div>

                <div class="panel-footer">
                    <div class="row">
                        <div class="demo-nifty-btn">
                            <button class="btn btn-sure" id="finish-btn" type="button">保存</button>
                            <button class="btn btn-cancel btn-back" type="reset">取消</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->beginBlock('jsText','append');?>
<script>
new Vue({
    el: "#app",
    data: {

    }
});
</script>
<?php $this->endBlock(); ?>