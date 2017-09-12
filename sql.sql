create database if not exists finjd;
create table jd_goods(
	id mediumint unsigned not null auto_increment comment 'id',
	logo varchar(150) not null default '' comment '商品logo',
	sm_logo varchar(150) not null default '' comment '商品小logo',
	goods_name varchar(100) not null comment '商品名称',
	market_price decimal(10,2) not null comment '商品市场价格',
	shop_price decimal(10,2) not null comment '商品本店价格',
	is_on_sale enum("是","否") not null default '是' comment '是否上架',
	goods_desc text  comment '商品描述',
	goods_number mediumint unsigned not null default '0' comment '库存量',
	primary key (id),
	key shop_price(shop_price),
	key is_on_sale(is_on_sale)
)engine=Innodb default charset=utf8;

	create table jd_goods_pics(
		id mediumint unsigned not null auto_increment,
		pic varchar(150) not null default '' comment '商品logo',
		sm_pic varchar(150) not null default '' comment '商品小logo',
		goods_id mediumint unsigned not null default '0' comment '对应的商品ID',
		primary key(id),
		key goods_id(goods_id)
	)engine=Myisam default charset=utf8 comment '商品图片表';

	
	
/*****************************RBAC****************************/
	
create table jd_pri(
		id mediumint unsigned not null auto_increment,
		pri_name varchar(30) not null  comment '权限名称',
		module_name varchar(30) not null  comment '模型名称',
		controller_name varchar(30) not null  comment '控制器名称',
		action_name  varchar(30) not null  comment '方法名称',
		sort_num tinyint unsigned not null default '100' comment '排序字段',
		parent_id mediumint unsigned not null default '0' comment '递归使用无限级，上级权限的ID，如果为0代表顶级权限' ,
		primary key(id)
	)engine=Myisam default charset=utf8 comment '权限/按钮表';
alter table jd_privilege add sort_num tinyint unsigned not null default '100' comment '排序字段';
	
create table jd_role_pri(
		role_id mediumint unsigned not null  comment '角色的ID',
		pri_id mediumint unsigned not null  comment '权限的ID',
		key role_id(role_id),
		key pri_id(pri_id)
	)engine=Myisam default charset=utf8 comment '角色所拥有的权限表';


create table jd_role(
		id mediumint unsigned not null auto_increment,
		role_name varchar(30) not null  comment '角色名称',
		primary key(id)
	)engine=Myisam default charset=utf8 comment '角色表';

create table jd_admin_role(
		admin_id mediumint unsigned not null ,
		role_id mediumint unsigned not null,
		key pri_id(admin_id),
		key role_id(role_id)
	)engine=Myisam default charset=utf8 comment '管理员角色表';


create table jd_admin(
		id mediumint unsigned not null auto_increment,
		username varchar(30) not null  comment '管理员账号',
		password char(32) not null  comment '密码',
		primary key(id)
	)engine=Myisam default charset=utf8 comment '管理员表';
insert into jd_admin values(1,'root', '5ac2be6bdb7f1eead87a76556c755b3e');


/*********************************商品类型、类型属性表**********************************/

create table jd_goods_type(
	id mediumint  unsigned not null auto_increment,
	type_name  varchar(55) not null  comment '商品类型名称',
	primary key(id)
)engine=Myisam default charset=utf8 comment '商品类型表';



DROP TABLE IF EXISTS jd_goods_attribute;
CREATE TABLE jd_attribute
(
	id mediumint unsigned not null auto_increment,
	attr_name varchar(30) not null comment '属性名称',
	attr_type enum("唯一","单选") not null comment '属性的类型',
	attr_option_values varchar(300) not null default '' comment '属性的可选值，多个可选用，隔开',
	type_id mediumint unsigned not null comment '类型id',
	primary key (id),
	key type_id(type_id)
)engine=MyISAM default charset=utf8 comment '属性表';

SELECT a.*,GROUP_CONCAT(c.role_name) role_name FROM jd_admin a LEFT JOIN 
jd_admin_role b ON a.id=b.role_id LEFT JOIN jd_role c ON b.role_id=c.id 
GROUP BY a.id LIMIT 8,4 

SELECT a.*,GROUP_CONCAT(c.role_name) as role_name 
FROM jd_admin as a LEFT JOIN jd_admin_role as b ON a.id=b.admin_id
LEFT JOIN jd_role as c ON b.role_id=c.id GROUP BY b.admin_id;

DROP TABLE IF EXISTS jd_member_level;
create table jd_member_level(
	id mediumint unsigned not null auto_increment ,
	level_name  varchar(32)  not null comment '属性名称',
	num_bottom mediumint unsigned not null comment '积分上限',
	num_top mediumint unsigned not null comment '积分下限',
	unique level_name(level_name),
	primary key(id)
)engine=Myisam default charset=utf8 comment '会员级别表';


DROP TABLE IF EXISTS jd_member;
create table jd_member(
	id mediumint unsigned not null auto_increment ,
	email  varchar(150)  not null comment 'Email',
	password  varchar(150)  not null comment '密码',
	username  varchar(32)  not null default '' comment '用户名',
	face varchar(154)  not null default '' comment '头像',
	sm_face varchar(154)  not null default '' comment '小头像  150*150',
	jifen mediumint unsigned not null default '0' comment '会员积分',
	jingyan mediumint unsigned not null default '0' comment '经验值',
	add_time datetime not null comment '注册时间',
	unique email(email),
	primary key(id)
)engine=Myisam default charset=utf8 comment '会员表';

DROP TABLE IF EXISTS jd_goods_attr;
create table jd_goods_attr(
	id mediumint unsigned not null auto_increment ,
	attr_id mediumint unsigned not null comment '属性id',
	attr_value  varchar(132)  not null default '' comment '属性值',
	goods_id mediumint unsigned not null  comment '商品id',
	key goods_id(goods_id),
	primary key(id)
)engine=Myisam default charset=utf8 comment '商品属性表';


alter table jd_goods add type_id mediumint unsigned not null default '0' comment '类型id';












