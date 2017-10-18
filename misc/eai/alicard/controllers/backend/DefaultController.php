<?php
namespace apps\alicard\controllers\backend;

use apps\alicard\ars\Alicard;
use portal\models\ManagerModel;
use Yii;
use portal\models\LogModel;
use apps\BackendController;
use portal\helpers\ErrorMessage;

class DefaultController extends BackendController {
    
    public function highLightRules()
    {
        return [
            [['index'], 'list'],
            [['delivery'], 'delivery'],
        ];
    }

    public function actionIndex()
    {
        $pages = Yii::$app->page->getLimit();
        $card = Alicard::find()->orderBy('id desc');
        Yii::$app->page->setTotal($card->count());
        $lists=$card->offset($pages[0])->limit($pages[1])->asArray()->all();
        static $admin_user = [];
        foreach($lists as &$one){
            if (!isset($admin_user[$one['admin_uid']])) {
                $user = ManagerModel::info($one['admin_uid']);
                $admin_user[$one['admin_uid']] = empty($user) ? '系统管理员' : ($user['realname'] ?: $user['username']);
            }
            $one['username'] = $admin_user[$one['admin_uid']];
            $tmp = json_decode($one['template_benefit_info'], true);
            $one['card_show_name'] = $tmp['card_show_name'];
        }
        return $this->render('index', ['data'=>$lists]);
    }

    public function actionAdd()
    {
        $id = intval(Yii::$app->request->get('id'));
        $info = [];
        if (!empty($id)) {
            $info = Alicard::find()->where(['id'=>$id])->asArray()->one();
        }
        if (!empty($info)) {
            $info['template_style_info'] = json_decode($info['template_style_info'], true);
            $info['template_benefit_info'] = json_decode($info['template_benefit_info'], true);
            $info['column_info_list'] = json_decode($info['column_info_list'], true);
            $info['field_rule_list'] = json_decode($info['field_rule_list'], true);
        }
        return $this->render('add', ['info'=>$info]);
    }

    public function actionSave()
    {
        /**
         * request_id	String	必选	32	请求ID，由开发者生成并保证唯一性	2016072600000000000000001
         * card_type	String	必选	32	卡类型为固定枚举类型，可选类型如下： OUT_MEMBER_CARD：外部权益卡	OUT_MEMBER_CARD
         * biz_no_prefix	String	可选	10	业务卡号前缀，由商户指定 支付宝业务卡号生成规则：biz_no_prefix(商户指定)卡号前缀 + biz_no_suffix(实时生成）卡号后缀	prex
         * biz_no_suffix_len	String	必选	2	业务卡号后缀的长度 
            * 支付宝业务卡号生成规则：biz_no_prefix(商户指定)卡号前缀 + biz_no_suffix(实时生成）卡号后缀 
            * 由于业务卡号最长不超过32位，所以biz_no_suffix_len <= 32 - biz_no_prefix的位数	10
         * write_off_type	String	必选	32	卡包详情页面中展现出的卡码（可用于扫码核销） 
             * (1) 静态码 
            * qrcode: 二维码，扫码得商户开卡传入的external_card_no 
            * barcode: 条形码，扫码得商户开卡传入的external_card_no 
            * text: 当前不再推荐使用，text的展示效果目前等价于barcode+qrcode，同时出现条形码和二维码 

            * (2) 动态码-支付宝生成码值(动态码会在2分钟左右后过期) 
            * dqrcode: 动态二维码，扫码得到的码值可配合会员卡查询接口使用 
            * dbarcode: 动态条形码，扫码得到的码值可配合会员卡查询接口使用 

            * (3) 动态码-商家自主生成码值（码值、时效性都由商户控制） 
            * mdqrcode: 商户动态二维码，扫码得商户自主传入的码值 
            * mdbarcode: 商户动态条码，扫码得商户自主传入的码值	qrcode
         * template_style_info	TemplateStyleInfoDTO	必选		模板样式信息	
         * └ card_show_name	String	必填	10	钱包端显示名称（字符串长度）	花呗联名卡
         * └ logo_id	String	必填	1000	logo的图片ID，通过接口（alipay.offline.material.image.upload）上传图片 
            * 图片说明：1M以内，格式bmp、png、jpeg、jpg、gif； 
            * 尺寸不小于500*500px的正方形； 
            * 请优先使用商家LOGO；	1T8Pp00AT7eo9NoAJkMR3AAAACMAAQEC
         * └ color	String	可选	64	注意：此字段已废弃。 卡片颜色	rgb(55,112,179)
         * └ background_id	String	必填	1000	背景图片Id，通过接口（alipay.offline.material.image.upload）上传图片 
            * 图片说明：2M以内，格式：bmp、png、jpeg、jpg、gif； 
            * 尺寸不小于1020*643px； 
            * 图片不得有圆角，不得拉伸变形	1T8Pp00AT7eo9NoAJkMR3AAAACMAAQEC
         * └ bg_color	String	必填	32	背景色	rgb(55,112,179)
         * └ front_text_list_enable	Boolean	可选	5	设置是否在卡面展示文案信息，默认不展示； 
            * 文案信息分行展示，最多展示3行文案，每行文案分为label和value两部分； 
            * 文案实际内容随创建卡/更新卡时传入； 
            * 详见会员卡产品说明文档。	false
         * └ front_image_enable	Boolean	可选	5	设置是否在卡面展示（个人头像）图片信息，默认不展示； 
            * 当前仅用于身份验证信息类型的个人头像图片； 
            * 图片id随创建卡/更新卡时传入； 
            * 详见会员卡产品文档。	false
         * └ feature_descriptions	String[]	可选	4000	特色信息，用于领卡预览	使用花呗卡可享受免费分期
         * └ slogan	String	可选	100	标语	会员权益享不停
         * └ slogan_img_id	String	可选	100	标语图片， 通过接口（alipay.offline.material.image.upload）上传图片	1T8Pp00AT7eo9NoAJkMR3AAAACMAAQEC
         * └ brand_name	String	可选	100	品牌商名称	可乐 
         * template_benefit_info	TemplateBenefitInfoDTO[]	可选		权益信息， 
            * 1、在卡包的卡详情页面会自动添加权益栏位，展现会员卡特权， 
            * 2、如果添加门店渠道，则可在门店页展现会员卡的权益	
         * └ title	String	必填	16	权益描述	消费即折扣
         * └ benefit_desc	String[]	必填	1000	权益描述信息	消费即折扣
         * └ start_date	Date	必填	64	开始时间	2016-07-18 15:17:23
         * └ end_date	Date	必填	64	权益结束时间	2016-07-34 12:12:12
         * column_info_list	TemplateColumnInfoDTO[]	必选		栏位信息	
         * └ code	String	必填	32	
            * 标准栏位：行为由支付宝统一定，同时已经分配标准Code 
            * BALANCE：会员卡余额 
            * POINT：积分 
            * LEVEL：等级 
            * TELEPHONE：联系方式 
            * 自定义栏位：行为由商户定义，自定义Code码（只要无重复）	BENEFIT_INFO
         * └ operate_type	String	可选	32	
            * 1、openNative：打开二级页面，展现 more中descs 
            * 2、openWeb：打开URL 
            * 3、staticinfo：静态信息 
            * 注意： 
            * 不填则默认staticinfo； 
            * 标准code尽量使用staticinfo，例如TELEPHONE商家电话栏位就只支持staticinfo；	openWeb
         * └ title	String	必填	16	栏目的标题	会员专享
         * └ value	String	可选	16	卡包详情页面，卡栏位右边展现的值 TELEPHONE栏位的商家联系电话号码由此value字段传入	80
         * more_info	MoreInfoDTO	可选	2048	扩展信息	
         * └ title	String	必填	16	二级页面标题	会员专享权益
         * └ url	String	可选	256	超链接(选择openweb的时候必须填写url参数内容)	http://www.baidu.com
         * └ params	String	可选	1024	扩展参数，需要URL地址回带的值，JSON格式(openweb时填)	{}
         * └ descs	String[]	可选	1000	选择opennative的时候必须填写descs的内容	会员生日7折
         * field_rule_list	TemplateFieldRuleDTO[]	必选		
            * 字段规则列表，会员卡开卡过程中，会员卡信息的生成规则， 
            * 例如：卡有效期为开卡后两年内有效，则设置为：DATE_IN_FUTURE	
         * └ field_name	String	必填	64	
            * 字段名称，现在支持如下几个Key（暂不支持自定义） 
            * Balance：金额 
            * Point：整数 
            * Level：任意字符串 
            * OpenDate：开卡日期 
            * ValidDate：过期日期	Balance
         * └ rule_name	String	必填	64	
            * 规则名 
            * 1、ASSIGN_FROM_REQUEST: 以rule_value为key值，表示该栏位的值从会员卡开卡接口中获取，会员卡开卡接口的card_info中获取对应参数值 
            * 2、DATE_IN_FUTURE: 生成一个未来的日期（格式YYYY-MM-DD)，当选择DATE_IN_FUTURE的时候，field_name 必须是OpenDate或ValidDate， 值为(10m或10d 分别表示10个月或10天) 
            * 3、CONST: 常量，会员卡开卡接口进行开卡的时候使用模板创建时候设置的值，即取rule_value的值	ASSIGN_FROM_REQUEST
         * └ rule_value	String	必填	512	
            * 根据rule_name，采取相应取值策略 
            * CONST：直接取rule_value作为卡属性值 
            * DATE_IN_FUTURE：10m或10d 分别表示10个月或10天 
            * ASSIGN_FROM_REQUEST：在开卡Reuqest请求中按rule_value取值，现在和field_name对应的为（OpenDate、ValidDate、Level、Point、Balance）	Balance
            * card_action_list	TemplateActionInfoDTO[]	可选		卡行动点配置； 
            * 行动点，即用户可点击跳转的区块，类似按钮控件的交互； 
            * 单张卡最多定制4个行动点。	
         * └ code	String	必填	32	行动点业务CODE，商户自定义	TO_CLOCK_IN
         * └ text	String	必填	6	行动点展示文案	打卡
         * └ url	String	必填	1024	行动点跳转链接	https://merchant.ali.com/ee/clock_in.do
         * open_card_conf	TemplateOpenCardConfDTO	可选		会员卡用户领卡配置，在门店等渠道露出领卡入口时，需要部署的商户领卡H5页面地址	
         * └ open_card_source_type	String	必填	20	
            * ISV：外部系统 
            * MER：直连商户	ISV
         * └ source_app_id	String	必填	32	渠道APPID，提供领卡页面的服务提供方	201609191111111
         * └ open_card_url	String	必填	256	开卡连接，必须http、https开头	https://www.alipay.com
         * └ conf	String	可选	4000	配置，预留字段，暂时不用	""
         * service_label_list	String	可选	1024	服务Code 
         * HUABEI_FUWU：花呗服务（只有需要花呗服务时，才需要加入该标识）	HUABEI_FUWU
         * shop_ids	String	可选	1024	会员卡上架门店id（支付宝门店id），既发放会员卡的商家门店id	2015122900077000000002409504
         * pub_channels	PubChannelDTO[]	可选		卡模板投放渠道	
         * └ pub_channel	String	必填	32	
            * 1、SHOP_DETAIL:店铺详情页 
            * 2、PAYMENT_RESULT: 支付成功页（支付成功页暂不支持）	SHOP_DETAIL
         *  └ ext_info	String	必填	1024	扩展信息，无需配置	"key":"value"
         *  card_level_conf	TemplateCardLevelConfDTO[]	可选		卡级别配置	
         *  └ level	String	必填	64	会员级别 该级别和开卡接口中的levle要一致	VIP1
         *  └ level_show_name	String	必填	64	会员级别显示名称	黄金会员
         * └ level_icon	String	必填	64	会员级别对应icon， 通过接口（alipay.offline.material.image.upload）上传图片	1T8Pp00AT7eo9NoAJkMR3AAAACMAAQEC
         * └ level_desc	String	必填	4000	会员级别描述	黄金会员享受免费停车
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
         */
    }

    // 设置领卡表单
    public function actionTemplate()
    {
        $rs = Yii::$app->aop->call('alipay.marketing.card.formtemplate.set', [
            'template_id' => '20170914000000000524008000300414',
            'fields' => [
                'required' => [
                    'common_fields' => ['OPEN_FORM_FIELD_NAME']
                ]
            ]
        ]);
        var_dump($rs);exit;
        if ($rs->alipay_marketing_card_formtemplate_set_response->code === 10000) {
            // success
        }
    }

    // 卡投放列表
    public function actionDelivery()
    {

    }

    public function actionCreate()
    {
        $rs = Yii::$app->aop->call('alipay.marketing.card.template.create', [
            'request_id' => '201709141207000007',
            'card_type' => 'OUT_MEMBER_CARD',
            'biz_no_prefix' => 'rlstech',
            'biz_no_suffix_len' => '10',
            'write_off_type'=> 'qrcode',
            'template_style_info' => [
                'card_show_name' => '瑞雷森科技大学',
                'logo_id' => 'fLYFvlL0RAea2BMkE2yyvQAAACMAAQED',
                'background_id' => 'SphieBmFTDeg7-SgL0YQsgAAACMAAQED',
                'bg_color' => 'rgb(55,112,179)',
                'front_text_list_enable' => true,//是否在卡面展示文案信息
                'front_image_enable' => true,//是否在卡面展示（个人头像）图片信息
                //不起作用'slogan' => '标语',
                //不起作用'slogan_img_id' => 'pSghWWrIQeSU6C3wwSCaCQAAACMAAQED', //标语图片,
                //不起作用'brand_name' => '品牌商名称',
            ],
            'column_info_list' =>[
                /*无意义,显示效果不好。[
                    'code' => 'LEVEL',
                    'operate_type' => 'staticinfo',
                    'title' => '类别',
                ],
                [
                    'code' => 'BALANCE',
                    'operate_type' => 'staticinfo',
                    'title' => '当前余额',
                ],*/
                [
                    'code' => 'LEVEL',
                    'operate_type' => 'staticinfo',
                    'title' => '领卡日期',
                ],
                [
                    'code' => 'BALANCE',
                    'operate_type' => 'staticinfo',
                    'title' => '当前余额',
                ],
                [
                    'code' => 'LOST_FOUND',
                    'operate_type' => 'openWeb',
                    'title' => '一卡通招领',
                    'more_info' => [
                        'title' => '一卡通招领',
                        'url' => 'http://eai.datamorality.com/cardlost/wap/default',
                    ]
                ],
            ],
            'field_rule_list' => [
                [
                    'field_name' => 'OpenDate',
                    'rule_name' => 'DATE_IN_FUTURE',
                    'rule_value' => '0d'
                ]
            ],
            'card_action_list' => [
                [
                    'code' => 'GZ',
                    'text' => '我的工资',
                    'url' => 'http://eai.datamorality.com/gzv1/wap/default/index'
                ],
                [
                    'code' => 'CARD_CHARGE',
                    'text' => '一卡通充值',
                    'url' => 'http://eai.datamorality.com/chargecard/wap/default/index'
                ]
            ],
            /*不起作用'card_level_conf' => [
                [
                    'level' => 'jzg',
                    'level_show_name' => '教工卡',
                    'level_icon' => 'BZcRBtXQQ6aD9HFxC9CSZgAAACMAAQED',
                    'level_desc' => '教工卡权益'
                ],
                [
                    'level' => 'xsk',
                    'level_show_name' => '学生卡',
                    'level_icon' => 'eq567DthSTiEHTryYhrvTgAAACMAAQED',
                    'level_desc' => '学生卡权益'
                ],
            ],*/
        ]);
        $response = $rs->alipay_marketing_card_template_create_response;
        if ($response->code === "10000") {
            $template_id = $response->template_id;
        } else {
            throw new \Exception($response->msg . ' ' . $response->sub_msg);
        }
        var_dump($template_id);
        //template_id = 20170911000000000515841000300417--测试校园卡
        //template_id = 20170913000000000531741000300411--瑞雷森科技大学
    }
    
    public function actionUpdate()
    {
        $rs = Yii::$app->aop->call('alipay.marketing.card.template.modify', [
            'request_id' => strval(time()),
            'template_id' => '20170914000000000533022000300418',
            'biz_no_prefix' => 'rlstech',
            'write_off_type'=> 'qrcode',
            'template_style_info' => [
                'card_show_name' => '瑞雷森科技大学',
                'logo_id' => 'fLYFvlL0RAea2BMkE2yyvQAAACMAAQED',
                'background_id' => 'a7hCNda3QauoWTZAgACqFgAAACMAAQED',
                'bg_color' => 'rgb(55,112,179)',
                'front_text_list_enable' => true,//是否在卡面展示文案信息
                'front_image_enable' => true,//是否在卡面展示（个人头像）图片信息
                //不起作用'slogan' => '标语',
                //不起作用'slogan_img_id' => 'pSghWWrIQeSU6C3wwSCaCQAAACMAAQED', //标语图片,
                //不起作用'brand_name' => '品牌商名称',
            ],
            'column_info_list' =>[
                /*无意义,显示效果不好。[
                    'code' => 'LEVEL',
                    'operate_type' => 'staticinfo',
                    'title' => '类别',
                ],
                [
                    'code' => 'BALANCE',
                    'operate_type' => 'staticinfo',
                    'title' => '当前余额',
                ],*/
                [
                    'code' => 'LOST_FOUND',
                    'operate_type' => 'openWeb',
                    'title' => '失物招领',
                    'more_info' => [
                        'title' => '失物招领',
                        'url' => 'http://eai.datamorality.com/lost/wap/default',
                    ]
                ],
                [
                    'code' => 'ACTIVITY',
                    'operate_type' => 'openWeb',
                    'title' => '校园互动',
                    'value' => '快来参与吧',
                    'more_info' => [
                        'title' => '校园互动',
                        'url' => 'http://eai.datamorality.com/base/wap/activity/list?service=answer',
                    ]
                ],
                [
                    'code' => 'WESITE',
                    'operate_type' => 'openWeb',
                    'title' => '校园官网',
                    'more_info' => [
                        'title' => '校园官网',
                        'url' => 'http://eai.datamorality.com/microsite/wap/default/index?wid=6',
                    ]
                ],
                [
                    'code' => 'ONLINE',
                    'operate_type' => 'openWeb',
                    'title' => '业务办理',
                    'more_info' => [
                        'title' => '业务办理',
                        'url' => 'http://eai.datamorality.com/microsite/wap/default/index?wid=6',
                    ]
                ],
                [
                    'code' => 'ONLINE',
                    'operate_type' => 'openWeb',
                    'title' => '办公电话',
                    'more_info' => [
                        'title' => '办公电话',
                        'url' => 'http://eai.datamorality.com/microsite/wap/default/index?wid=6',
                    ]
                ],
                [
                    'code' => 'HOME',
                    'operate_type' => 'openWeb',
                    'title' => '个人中心',
                    'more_info' => [
                        'title' => '个人中心',
                        'url' => 'http://eai.datamorality.com/microsite/wap/default/index?wid=6',
                    ]
                ],
            ],
            'field_rule_list' => [
                [
                    'field_name' => 'OpenDate',
                    'rule_name' => 'DATE_IN_FUTURE',
                    'rule_value' => '0d'
                ]
            ],
            'card_action_list' => [
                [
                    'code' => 'CARD_CHARGE',
                    'text' => '一卡通充值',
                    'url' => 'http://eai.datamorality.com/chargecard/wap/default/index'
                ],
                [
                    'code' => 'NETWORD_CHARGE',
                    'text' => '网费充值',
                    'url' => 'http://eai.datamorality.com/chargefee/wap/default/index'
                ],
                [
                    'code' => 'ONLINE',
                    'text' => '扫码上网',
                    'url' => 'http://eai.datamorality.com/microsite/wap/default/index?wid=6'
                ],
            ],
        ]);
        $response = $rs->alipay_marketing_card_template_modify_response;
        if ($response->code === "10000") {
            $template_id = $response->template_id;
        } else {
            throw new \Exception($response->msg . ' ' . $response->sub_msg);
        }
        var_dump($template_id);
        //template_id = 20170913000000000531741000300411--瑞雷森校园卡
        //template_id = 20170914000000000533022000300418--瑞雷森科技大学
    }

    // 查询模板
    public function actionQuery()
    {
        $rs = Yii::$app->aop->call('alipay.marketing.card.template.query', [
            'template_id' => '20170914000000000533022000300418'
        ]);
        var_dump($rs);
    }

    // 测试支付宝上传
    public function actionUpload()
    {
        return $this->render('upload');
    }

}
