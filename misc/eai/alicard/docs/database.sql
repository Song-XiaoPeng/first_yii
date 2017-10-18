create table alicard (
  id int unsigned auto_increment,
  write_off_type varchar(32) not null default '' comment '卡码信息，qrcode/barcode/dqrcode/dbarcode/mdqrcode/mdbarcode',
  template_style_info text not null comment '模板样式信息',
  template_benefit_info text not null comment '权益信息',
  column_info_list text not null comment '栏位信息',
  field_rule_list text  not null comment '字段规则列表',
  card_action_list text default null comment '卡行动点配置',
  open_card_conf text default null comment '会员卡用户领卡配置',
  service_label_list text default null comment '服务Code, HUABEI_FUWU：花呗服务',
  shop_ids text default null comment '会员卡上架门店id',
  pub_channels text default null comment '卡模板投放渠道',
  card_level_conf text default null comment '卡级别配置',
  mdcode_notify_conf text default null comment '商户动态码通知参数配置',
  template_id varchar(20) not null default '' comment '',
  created int not null default '0',
  modified int not null default '0',
  creator int not null default '0',
  primary key (id),
  unique key idx_template_id (template_id)
)engine=innodb default charset=utf8;
  
create table alicard_user (
  id int unsigned not null auto_increment,
  cardid int not null default '0',
  uid int not null default '0',
  template_id varchar(32) not null default '' comment '支付宝模板id',
  biz_card_no varchar(32) not null default '' comment '支付宝卡id',
  primary key (id),
  key idx_uid (uid),
  key idx_biz_card_no (biz_card_no)
)engine=innodb default charset=utf8;

## 卡类别
create table alicard_type (
  id int unsigned not null auto_increment,
  name varchar(32) not null default '' comment '卡类别名称',
  status tinyint not null default 0 comment '是否删除0否1是',
  primary key (id),
  key idx_name (name),
)engine=innodb default charset=utf8;

## 卡类别与组织架构关联
create table alicard_type_organize (
  id int unsigned not null auto_increment,
  type_id int not null default '' comment '卡类别id',
  organize_id int not null default '' comment '组织架构id',
  primary key (id),
)engine=innodb default charset=utf8;