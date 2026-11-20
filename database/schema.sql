-- Schema only; no production records or credentials are included.
CREATE TABLE `go_ad_area`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `width` smallint(6) UNSIGNED NULL DEFAULT NULL,
  `height` smallint(6) UNSIGNED NULL DEFAULT NULL,
  `des` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `checked` tinyint(1) NULL DEFAULT 0 COMMENT '1表示通过',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `checked`(`checked`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '广告位' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_ad_data`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `aid` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'code,text,img',
  `content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `checked` tinyint(1) NULL DEFAULT 0 COMMENT '1表示通过',
  `addtime` int(10) UNSIGNED NOT NULL,
  `endtime` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `type`(`type`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '广告' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_admin_handle_notice`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `type` int(2) NULL DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `time` int(11) NULL DEFAULT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_view` int(1) NULL DEFAULT 1,
  `view_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_admin_message`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NULL DEFAULT NULL COMMENT '类别',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '内容',
  `time` int(11) NULL DEFAULT NULL COMMENT '时间',
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '数据',
  `admin_id` int(11) NULL DEFAULT NULL,
  `check_time` int(11) NULL DEFAULT NULL,
  `is_check` tinyint(1) NULL DEFAULT 1 COMMENT '1:待处理，2完成',
  `admin_type` tinyint(1) NULL DEFAULT 1 COMMENT '1操作员，2超级管理员，3所有',
  `check_admin_id` int(11) NULL DEFAULT NULL COMMENT '处理人id',
  `check_admin_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '处理人',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_admin_usergroup`  (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '分组名',
  `permission` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'ALL' COMMENT '权限范围',
  `time` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`mid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员分组及权限表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_appcart`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `num` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '购物车数量',
  `price` decimal(10, 2) UNSIGNED NOT NULL COMMENT '单价',
  `shopid` int(11) UNSIGNED NOT NULL COMMENT '商品id',
  `shopsid` int(11) UNSIGNED NOT NULL COMMENT '商品sid',
  `shopinfo` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '商品信息',
  `status` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '是否过期 0否 1是',
  `time` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '加入时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'app购物车' ROW_FORMAT = COMPACT;

CREATE TABLE `go_appoint`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `shopid` int(10) UNSIGNED NOT NULL COMMENT '商品期数id',
  `userid` int(10) UNSIGNED NOT NULL COMMENT '中奖用户',
  `time` int(10) UNSIGNED NOT NULL COMMENT '添加时间',
  `status` int(10) UNSIGNED NOT NULL COMMENT '订单状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_apprechangeunique`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `UniqueString` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '唯一字符串',
  `time` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'app充值用户唯一标识' ROW_FORMAT = COMPACT;

CREATE TABLE `go_area`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `create_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  `status` tinyint(4) NULL DEFAULT 1,
  `language` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '地区' ROW_FORMAT = Dynamic;

CREATE TABLE `go_area_details`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT 1,
  `create_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  `sort` int(5) NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '编码',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '地区详细' ROW_FORMAT = Dynamic;

CREATE TABLE `go_article`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `cateid` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '文章父ID',
  `author` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `title` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `title_style` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `thumb` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `picarr` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `keywords` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '内容',
  `hit` int(10) UNSIGNED NULL DEFAULT 0,
  `order` tinyint(3) UNSIGNED NULL DEFAULT NULL,
  `posttime` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '添加时间',
  `language` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '语言',
  `keys` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '\"1\"=>\"用户服务条款\",\"2\"=>\"隐私政策\"',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cateid`(`cateid`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_attachment`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `topic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '细目',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上传管理员ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上传用户ID',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '物理路径',
  `width` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '宽度',
  `height` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '高度',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '原始名称',
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '大小',
  `mimetype` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'mime类型',
  `quote` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上传(引用)次数',
  `storage` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '存储方式',
  `sha1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'sha1编码',
  `createtime` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '上传时间',
  `lastuploadtime` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '最后上传时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '附件表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_bank`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'logo',
  `bg_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '背景颜色',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) NULL DEFAULT 2 COMMENT '状态1开启，2关闭',
  `language` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '语言',
  `sort` int(11) NULL DEFAULT 1 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '银行' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_banner`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图片标题',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图片',
  `img_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图片跳转链接',
  `status` tinyint(2) NULL DEFAULT 1 COMMENT '状态：-1=禁用，1=启用',
  `create_time` bigint(20) NULL DEFAULT NULL COMMENT '添加时间',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `language` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '语言',
  `bg_color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '背景色',
  `type` int(2) NULL DEFAULT 1 COMMENT '类型：1首页弹窗，2转盘弹窗',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '首页弹窗' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_brand`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cateid` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '所属栏目ID',
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'Y' COMMENT '显示隐藏',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `order` int(11) NULL DEFAULT 1,
  `model` int(2) NULL DEFAULT 1 COMMENT '模型：1云购，2直购',
  `languages` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `order`(`order`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '品牌表' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_card_pwd`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pwd` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pwd`(`pwd`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '卡密密码表' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_card_recharge`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '用户id',
  `money` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '卡密金额',
  `code` varchar(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '卡号',
  `codepwd` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '卡号密码',
  `isrepeat` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'N' COMMENT '是否一次性',
  `rechargecount` int(10) NULL DEFAULT 0 COMMENT '充值次数',
  `maxrechargecout` int(10) NULL DEFAULT 0 COMMENT '多最可重复多少次',
  `rechargetime` int(10) NULL DEFAULT NULL COMMENT '充值期限',
  `time` int(10) NULL DEFAULT NULL COMMENT '充值时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '卡密表' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_cart`  (
  `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `shopsid` int(11) NOT NULL COMMENT '商品sid',
  `num` int(5) UNSIGNED NOT NULL COMMENT '购买数量',
  `price` decimal(10, 2) UNSIGNED NOT NULL COMMENT '商品单价',
  `cmoney` decimal(11, 2) NOT NULL COMMENT '小计金额',
  `xg` int(3) NOT NULL DEFAULT 10 COMMENT '限购',
  `addtime` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '添加时间',
  `status` int(10) NOT NULL COMMENT '1为普通商品2为积分商品',
  `sid` int(10) NULL DEFAULT NULL COMMENT '积分商品ID',
  `mode` int(2) NULL DEFAULT 1 COMMENT '购买类型：1云购，2优惠购,3积分',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '购物车表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_channel_yaoqing`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `data` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '渠道商邀请信息' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_cloud_chat`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_id` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '发起id',
  `to_id` int(10) UNSIGNED NOT NULL COMMENT '接受id',
  `types` tinyint(3) UNSIGNED NOT NULL COMMENT '消息类型：1用户消息 2系统推送消息 ',
  `msg_type` tinyint(3) UNSIGNED NOT NULL COMMENT '用户消息子类型 1 文字 2.表情 3.图片 4.语音',
  `send_status` tinyint(3) UNSIGNED NOT NULL COMMENT '发送状态 1发送成功 2发送失败',
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '消息的状态：0未读消息；1：已读消息，2消息已撤销',
  `delect_data` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '删除json数据 {[\'uid\'：a，\'time\'：xx],[\'uid\'：b，\'time\'：xx]}',
  `view_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '接收者查看时间',
  `send_time` int(10) UNSIGNED NOT NULL COMMENT '发起时间',
  `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '消息类容  json_encode',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '聊天表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_community`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sort` int(5) NULL DEFAULT 0,
  `status` tinyint(1) NULL DEFAULT 1,
  `language` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_country_phone`  (
  `id` int(11) NOT NULL COMMENT '主键',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '区域名称',
  `phone_code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL COMMENT '区域电话前缀',
  `status` tinyint(2) NULL DEFAULT 1 COMMENT '区域状态 1 启用 0 禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '国家电话区号表' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_coupon`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '优惠卷名称',
  `money` decimal(10, 2) NULL DEFAULT NULL COMMENT '抵扣金额',
  `discount` decimal(10, 2) NULL DEFAULT NULL COMMENT '折扣',
  `type` int(1) NULL DEFAULT NULL COMMENT '优惠券类型：1金额，2折扣',
  `day` int(5) NULL DEFAULT NULL COMMENT '有效天数',
  `time` int(5) NULL DEFAULT NULL COMMENT '有效小时',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '优惠券说明',
  `model` int(2) NULL DEFAULT NULL COMMENT '优惠券类型：1充值，2商品',
  `expire_type` tinyint(1) NULL DEFAULT 1 COMMENT '到期方式，1天数，2指定日期',
  `receive_start` int(11) NULL DEFAULT 0 COMMENT '领取时间',
  `receive_end` int(11) NULL DEFAULT 0 COMMENT '领取截止时间',
  `expire_start` int(11) NULL DEFAULT 0 COMMENT '有效使用开始时间',
  `expire_end` int(11) NULL DEFAULT 0 COMMENT '有效使用截止时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '优惠券' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_czk`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `czknum` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '充值卡号码',
  `password` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '充值卡密码',
  `mianzhi` int(11) NOT NULL COMMENT '面值',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '使用状态',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '充值类型 1一次性 2永久',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_fund`  (
  `id` int(10) UNSIGNED NOT NULL,
  `fund_off` tinyint(4) UNSIGNED NOT NULL DEFAULT 1,
  `fund_money` decimal(10, 2) UNSIGNED NOT NULL,
  `fund_count_money` decimal(12, 2) NULL DEFAULT NULL COMMENT '公益基金',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_google_verify`  (
  `int` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `keys` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`int`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_handle`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(2) NULL DEFAULT NULL COMMENT '类型',
  `to_id` int(11) NULL DEFAULT NULL COMMENT '关联id',
  `operat_id` int(11) NULL DEFAULT NULL COMMENT '操作员id',
  `operat_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '操作员姓名',
  `operat_status` tinyint(1) NULL DEFAULT 1 COMMENT '操作员审核状态',
  `operat_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  `operat_time` int(11) NULL DEFAULT NULL COMMENT '操作时间',
  `manage_id` int(11) NULL DEFAULT NULL COMMENT '管理员',
  `manage_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `manage_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `manage_time` int(11) NULL DEFAULT NULL,
  `manage_status` tinyint(1) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_invite_zp`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `invite_id` int(11) NULL DEFAULT NULL COMMENT '邀请id(子级)',
  `time` int(11) NULL DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_kefu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '客服名',
  `img` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '客服二维码地址',
  `status` tinyint(2) NOT NULL DEFAULT -1 COMMENT '1 离线 1在线',
  `time` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `bank_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '银行名称',
  `bank_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '银行卡号',
  `bank_user` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '银行卡持有人名称',
  `type` int(2) NULL DEFAULT NULL COMMENT '类型：1客服，2银行卡，3usdt',
  `pay_monerange` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '金额范围',
  `exchange_rate` float(10, 3) NULL DEFAULT 1.000 COMMENT '汇率',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pay_class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '线下充值客服表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_language`  (
  `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lg_value` char(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言英文 (注意建议手动输入语言英文名称,以免创建文件夹出错)',
  `lg_name` char(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言名称',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '语言后台显示状态  1.显示 2.关闭',
  `notes` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `time_zone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '时区',
  `country` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '国家',
  `national_flag` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '国旗',
  `currency_unit` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '货币单位',
  `area_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '手机区号',
  `sort` int(5) NULL DEFAULT 0 COMMENT '排序',
  `flag_status` tinyint(1) NULL DEFAULT 0 COMMENT '1显示0不显示',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1前端，2PC端',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_language_backend`  (
  `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lg_value` char(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言英文 (注意建议手动输入语言英文名称,以免创建文件夹出错)',
  `lg_name` char(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言名称',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '语言后台显示状态  1.显示 2.关闭',
  `notes` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `time_zone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '时区',
  `country` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `national_flag` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '国旗',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '后台语言包表' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_language_value`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'key',
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '值',
  `pid` int(11) NULL DEFAULT 0 COMMENT '上级id',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1前端，2PC端',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_language_value_backend`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'key',
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '值',
  `pid` int(11) NULL DEFAULT 0 COMMENT '上级id',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '后台语言包值' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_link`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '友情链接ID',
  `type` tinyint(1) UNSIGNED NOT NULL COMMENT '链接类型',
  `name` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `logo` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '图片',
  `url` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '地址',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `type`(`type` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_logistics`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '物流公司名称',
  `create_time` int(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL COMMENT '更新时间',
  `is_state` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '默认为1,1为开启,2为禁用',
  `language` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '语言',
  `is_free` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否包邮1包邮，2不包邮',
  `money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '固定运费',
  `ratio` float(5, 2) NULL DEFAULT 0.00 COMMENT '按商品比例',
  `type` int(1) NULL DEFAULT 2 COMMENT '运费类型1固定，2比例',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '物流管理表' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_lottery_records`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `time` int(11) NULL DEFAULT NULL COMMENT '抽奖时间',
  `huode` tinyint(1) NULL DEFAULT 0 COMMENT '是否中奖',
  `zp_id` int(11) NULL DEFAULT NULL COMMENT '奖品id',
  `zp_data` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '奖品内容',
  `money` decimal(10, 5) NULL DEFAULT 0.00000 COMMENT '奖金',
  `total_money` decimal(10, 5) NULL DEFAULT NULL COMMENT '累计奖金',
  `probability` decimal(10, 5) NULL DEFAULT 0.00000 COMMENT '中奖几率',
  `period` int(5) NULL DEFAULT 1 COMMENT '期数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '抽奖记录' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_map_member`  (
  `lng` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lat` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_member`  (
  `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `mobile` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户手机',
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码',
  `user_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'photo/member.jpg' COMMENT '用户头像',
  `qianming` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户签名',
  `groupid` tinyint(4) UNSIGNED NULL DEFAULT 1 COMMENT '用户权限组',
  `addgroup` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户加入的圈子组1|2|3',
  `money` decimal(15, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '账户金额',
  `emailcode` char(21) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '-1' COMMENT '邮箱认证码',
  `mobilecode` char(21) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '-1' COMMENT '手机认证码',
  `othercode` tinyint(1) NOT NULL DEFAULT 0 COMMENT '其他认证方式 1为认证通过',
  `passcode` char(21) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '-1' COMMENT '找会密码认证码-1,1,码',
  `reg_key` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '注册参数',
  `score` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `jingyan` int(10) UNSIGNED NULL DEFAULT 0,
  `yaoqing` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '邀请人',
  `band` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `time` int(10) NULL DEFAULT NULL,
  `headimg` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户头像，快捷登陆时候的头像',
  `wxid` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '微信id',
  `typeid` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '注册来源 0电脑端  1 手机端 2微信关注 3快捷登陆QQ 4 微信快捷',
  `level` tinyint(4) NULL DEFAULT 0 COMMENT '用户类别：0普通，1回收，2渠道，3入驻',
  `auto_user` int(1) NOT NULL DEFAULT 0,
  `shop_ permission` tinyint(4) NOT NULL DEFAULT 4 COMMENT '买购记录是否公开 0否 4 是',
  `owinshop_permission` tinyint(4) NOT NULL DEFAULT 4 COMMENT '获得商品是否公开 0 否 4 是',
  `shoping` int(2) NOT NULL DEFAULT 0 COMMENT '是否已消费',
  `chongzhi` int(2) NOT NULL DEFAULT 0 COMMENT '邀请是否充值',
  `every` int(10) NOT NULL DEFAULT 0 COMMENT '充值多少钱转一次',
  `consumption` int(10) NOT NULL DEFAULT 0 COMMENT '消费多少钱获得转盘次数一次',
  `fbid` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'facebook 授权id',
  `language` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '语言',
  `v_id` int(11) NULL DEFAULT NULL COMMENT '虚拟id',
  `edit_pwd` int(1) NULL DEFAULT 0,
  `host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '注册域名',
  `area_code` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '手机区号',
  `top_uid` int(11) NULL DEFAULT NULL COMMENT '顶级邀请uid',
  `wallet_address` varchar(42) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '钱包地址',
  PRIMARY KEY (`uid`) USING BTREE,
  INDEX `Auto_user`(`auto_user` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_account`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '充值订单号',
  `type` tinyint(1) NULL DEFAULT NULL COMMENT '充值1/消费-1',
  `pay` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '详情',
  `money` mediumint(8) NOT NULL DEFAULT 0 COMMENT '金额',
  `time` int(11) NOT NULL,
  `status` int(2) NULL DEFAULT 1 COMMENT '1',
  `message` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE,
  INDEX `type`(`type` ASC) USING BTREE,
  INDEX `idx_pay_type_time`(`pay` ASC, `type` ASC, `time` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员账户明细' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_addmoney_record`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `money` decimal(10, 2) UNSIGNED NOT NULL,
  `pay_type` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `time` int(10) NOT NULL,
  `score` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `scookies` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '购物车cookie',
  `discount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '折扣',
  `real_money` decimal(10, 2) NULL DEFAULT NULL COMMENT '实际支付金额',
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '充值订单记录' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_auth`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '绑定用户id',
  `type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '绑定类型 1=facebook; 2=google,3其他',
  `auth_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '授权账号唯一id',
  `auth_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '授权账号头像',
  `auth_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '授权账号昵称',
  `auth_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '授权账号邮箱',
  `auth_mobile` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '授权账号手机',
  `auth_time` bigint(20) UNSIGNED NOT NULL COMMENT '授权时间',
  `auth_status` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '授权状态 0=未完成授权 1=已完成授权',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `type_auth_id`(`type` ASC, `auth_id` ASC) USING BTREE COMMENT '类型加账号唯一'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '账号授权关联表' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_member_ban`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `start_time` int(11) NULL DEFAULT NULL,
  `end_time` int(11) NULL DEFAULT NULL,
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户封禁表' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_member_band`  (
  `b_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `b_uid` int(10) NULL DEFAULT NULL COMMENT '用户ID',
  `b_type` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '绑定登陆类型',
  `b_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '返回数据1',
  `b_data` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '返回数据2',
  `b_time` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`b_id`) USING BTREE,
  INDEX `b_uid`(`b_uid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_bank_card`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `user_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '开户姓名',
  `bank_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '银行名称',
  `sub_bank` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '开户省支行名',
  `bank_no` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '银行账号',
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '联系方式',
  `default` tinyint(1) NULL DEFAULT 0 COMMENT '是否默认',
  `time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_member_bind_code_record`  (
  `phone` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `updatetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_member_bind_phone`  (
  `uid` bigint(20) NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `intime` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '回收商绑定手机号' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_member_blacklist`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `addtime` int(11) NULL DEFAULT NULL COMMENT '添加时间',
  `type` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '类型 \'1\'\'禁止聊天\',\'2\'\'禁止发送短信\',\'4\'\'禁止登录\',\'8\' \'禁止购买\',\'16\' \'禁止充值\',',
  `remarks` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `update` int(11) NULL DEFAULT NULL COMMENT '修改时间',
  `mobile` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '黑名单' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_cashout`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '开户人',
  `bankname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '银行名称',
  `branch` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '支行',
  `money` decimal(8, 0) NOT NULL DEFAULT 0 COMMENT '申请提现金额',
  `time` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '申请时间',
  `banknumber` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '银行帐号',
  `linkphone` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '联系电话',
  `auditstatus` tinyint(4) NOT NULL COMMENT '1审核通过,2拒绝，3等待，4报错',
  `procefees` decimal(8, 2) NOT NULL COMMENT '手续费',
  `reviewtime` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '审核通过时间',
  `status_massage` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '审核说明',
  `admin_id` int(11) NULL DEFAULT NULL,
  `admin_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `examine_id` int(11) NULL DEFAULT NULL,
  `examine_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `examine_status` int(2) NULL DEFAULT NULL,
  `examine_time` datetime NULL DEFAULT NULL,
  `examine_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE,
  INDEX `type`(`username` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '佣金提现明细' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_commission`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户UID',
  `total_money` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '总佣金',
  `zhichu_money` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '支出金额',
  `shouru_money` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '收入金额',
  `dongjie_money` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '冻结资金',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员佣金' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_commission_config`  (
  `uid` int(11) NOT NULL,
  `yaoqing` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '邀请积分设置：{\"number\":50,}',
  `commission` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '佣金比例设置：{\"fufen_yongjin\":10,\"fufen_yongjin_1\":5}',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户佣金配置' ROW_FORMAT = Dynamic;

CREATE TABLE `go_member_coupon`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `c_id` int(11) NULL DEFAULT NULL COMMENT '优惠券id',
  `start_time` int(11) NULL DEFAULT NULL COMMENT '开始时间',
  `end_time` int(11) NULL DEFAULT NULL COMMENT '过期时间',
  `receive_time` int(11) NULL DEFAULT NULL COMMENT '领取时间',
  `type` int(1) NULL DEFAULT NULL COMMENT '类型1金额，2折扣',
  `money` decimal(10, 2) NULL DEFAULT NULL COMMENT '金额',
  `discount` decimal(10, 2) NULL DEFAULT NULL COMMENT '折扣',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '说明',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '优惠券名称',
  `model` int(2) NULL DEFAULT NULL COMMENT '优惠券类型 1充值，2商品',
  `used` tinyint(1) NULL DEFAULT 1 COMMENT '1未使用，2已使用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_member_del`  (
  `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `mobile` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户手机',
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码',
  `user_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'photo/member.jpg' COMMENT '用户头像',
  `qianming` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户签名',
  `groupid` tinyint(4) UNSIGNED NULL DEFAULT 1 COMMENT '用户权限组',
  `addgroup` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户加入的圈子组1|2|3',
  `money` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '账户金额',
  `emailcode` char(21) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '-1' COMMENT '邮箱认证码',
  `mobilecode` char(21) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '-1' COMMENT '手机认证码',
  `othercode` tinyint(1) NOT NULL DEFAULT 0 COMMENT '其他认证方式 1为认证通过',
  `passcode` char(21) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '-1' COMMENT '找会密码认证码-1,1,码',
  `reg_key` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '注册参数',
  `score` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `jingyan` int(10) UNSIGNED NULL DEFAULT 0,
  `yaoqing` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '邀请人数',
  `band` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `time` int(10) NULL DEFAULT NULL,
  `headimg` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户头像，快捷登陆时候的头像',
  `wxid` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '微信id',
  `typeid` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '注册来源 0电脑端  1 手机端 2微信关注 3快捷登陆QQ 4 微信快捷',
  `level` tinyint(4) NULL DEFAULT 0,
  `auto_user` int(1) NULL DEFAULT 0,
  `shop_ permission` tinyint(4) NOT NULL,
  `owinshop_permission` tinyint(4) NOT NULL,
  `shoping` int(2) NOT NULL COMMENT '是否已消费',
  `chongzhi` int(2) NOT NULL DEFAULT 0 COMMENT '邀请是否充值',
  `every` int(10) NOT NULL COMMENT '充值多少钱转一次',
  `consumption` int(10) NOT NULL COMMENT '消费多少钱获得转盘次数一次',
  `fbid` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'facebook 授权id',
  `language` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '语言',
  `v_id` int(11) NULL DEFAULT NULL COMMENT '虚拟id',
  `edit_pwd` int(1) NULL DEFAULT 0,
  `host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '注册域名',
  `area_code` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '手机区号',
  `top_uid` int(11) NULL DEFAULT NULL COMMENT '顶级邀请uid',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员回收站表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_dizhi`  (
  `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `guo` int(10) NOT NULL COMMENT '国家',
  `sheng` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '省',
  `shi` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '市',
  `xian` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '县',
  `jiedao` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '街道地址',
  `youbian` mediumint(8) NULL DEFAULT NULL COMMENT '邮编',
  `shouhuoren` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收货人',
  `mobile` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机',
  `tell` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '座机号',
  `default` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' COMMENT '是否默认',
  `time` int(10) UNSIGNED NOT NULL,
  `qq` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'QQ 号码',
  `shifoufahuo` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否发货！',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员地址表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_go_record`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `code_tmp` tinyint(3) UNSIGNED NULL DEFAULT NULL COMMENT '相同订单',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `uphoto` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `uid` int(10) UNSIGNED NOT NULL COMMENT '会员id',
  `shopid` int(6) UNSIGNED NOT NULL COMMENT '商品id',
  `shopname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品名',
  `shopqishu` smallint(6) NOT NULL DEFAULT 0 COMMENT '期数',
  `goucode` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '云购码',
  `gonumber` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '购买次数',
  `ordercode_table` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '云购码订单表名',
  `moneycount` decimal(10, 2) NOT NULL,
  `huode` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '中奖码',
  `pay_type` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '付款方式',
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单状态',
  `time` int(11) NOT NULL COMMENT '购买时间',
  `company` char(18) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '快递单位',
  `company_code` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '快递单号',
  `company_money` int(10) UNSIGNED NOT NULL,
  `address` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `order_phone` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `confirm_address` tinyint(4) NOT NULL DEFAULT 0,
  `money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '实际支付金额',
  `point` int(11) NULL DEFAULT 0 COMMENT '实际消耗积分',
  `ip_region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `state` int(2) NULL DEFAULT 0 COMMENT '订单状态代码',
  `data` json NULL COMMENT '其他数据',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE,
  INDEX `shopid`(`shopid` ASC) USING BTREE,
  INDEX `time`(`time` ASC) USING BTREE,
  INDEX `ip_region`(`ip_region` ASC) USING BTREE,
  INDEX `huode`(`huode` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '拼购订单记录' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_goods_item`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `oid` int(10) NOT NULL COMMENT '订单id',
  `sid` int(10) NOT NULL COMMENT '商品id',
  `price` decimal(10, 2) NOT NULL COMMENT '商品单价',
  `score` int(10) NOT NULL COMMENT '赠送积分',
  `snum` int(10) NOT NULL COMMENT '商品数量',
  `total` decimal(10, 2) NOT NULL COMMENT '总金额',
  `cretime` int(11) NOT NULL COMMENT '订单建立时间',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `time`(`cretime` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '购买普通商品记录表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_goods_order`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `oid` int(10) NOT NULL COMMENT '订单id',
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '订单编号',
  `paycode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `typenum` int(6) NOT NULL COMMENT '商品类别数量',
  `money` decimal(10, 2) NOT NULL COMMENT '订单总价格',
  `remoney` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '实际支付金额',
  `score` int(10) NOT NULL COMMENT '赠送总积分',
  `paytype` enum('0') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
  `aid` int(10) UNSIGNED NOT NULL COMMENT '送货地址id',
  `remin` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
  `logistics_way` enum('0','1','2','3','4','5','6','7','8','9','10') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '快递公司(1中国邮政,2申通快递,3圆通速递,4顺丰速运,5韵达快递,6天天快递,7中通快递,8增益快递,9京东快递,10商城物流)',
  `logistics_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '订单状态(0取消,1待付款,5已付款,金额异常,6已付款,订单异常)',
  `message` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '用户留言',
  `cretime` int(11) NOT NULL COMMENT '创建时间',
  `suctime` int(11) NULL DEFAULT 0,
  `sendtime` int(11) NULL DEFAULT 0,
  `endtime` int(11) NULL DEFAULT 0,
  `uptime` int(11) NOT NULL DEFAULT 0 COMMENT '变更时间',
  `status_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '1为html2为app',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE,
  INDEX `shopid`(`typenum` ASC) USING BTREE,
  INDEX `time`(`cretime` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '购买记录表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_group`  (
  `groupid` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '会员组名',
  `jingyan_start` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '需要的经验值',
  `jingyan_end` int(10) NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图标',
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'N' COMMENT '是否是系统组',
  `rake_back` float(5, 2) NULL DEFAULT 0.00 COMMENT '返佣比例',
  `rake_back_1` float(5, 2) NULL DEFAULT 0.00 COMMENT '返佣比例二级',
  PRIMARY KEY (`groupid`) USING BTREE,
  INDEX `jingyan`(`jingyan_start` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员权限组' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_ip`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ip_region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `last_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `last_ip_region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_member_login_log`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NULL DEFAULT NULL COMMENT '用户ID',
  `ip` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'IP',
  `login_time` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '登陆时间',
  `country` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '国家',
  `region` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '省份',
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '市',
  `district` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '县、区',
  `lat` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '经度',
  `lng` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '纬度',
  `isp` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '运营商',
  `type` int(2) NULL DEFAULT 1 COMMENT '是否是管理员1否2是',
  `login_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录方式',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_login_user`(`user_id` ASC, `type` ASC, `id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户登陆日志' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_message`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `type` tinyint(1) NULL DEFAULT 0 COMMENT '消息来源,0系统,1私信',
  `sendid` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '发送人ID',
  `sendname` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发送人名',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发送内容',
  `time` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员消息表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_online`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `start_time` int(11) NULL DEFAULT NULL COMMENT '开始时间',
  `end_time` int(11) NULL DEFAULT NULL COMMENT '最后时间',
  `online_number` int(11) NULL DEFAULT NULL COMMENT '在线时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_online_user`(`uid` ASC, `id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户在线时间' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_member_orderaddress_brand`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NULL DEFAULT NULL COMMENT '订单id',
  `order_code` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `uid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `address_id` int(11) NULL DEFAULT NULL COMMENT '用户地址id',
  `time` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `mode` tinyint(1) NULL DEFAULT NULL COMMENT '订单类型：1云购，2优惠购，3拼团',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '订单收货地址绑定' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_pay_order`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `oid` int(10) NOT NULL COMMENT '订单id(用于查询订单分项)',
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '订单编号',
  `paycode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '微信/支付宝交易号',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `typenum` int(6) NOT NULL COMMENT '商品类别数量',
  `money` decimal(10, 2) NOT NULL COMMENT '订单总价格',
  `remoney` decimal(10, 2) NOT NULL COMMENT '实际收费金额',
  `score` int(10) NOT NULL COMMENT '赠送总积分',
  `paytype` enum('1','2','3') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '付款方式(1微信,2支付宝,3余额)',
  `aid` int(10) UNSIGNED NOT NULL COMMENT '送货地址id',
  `remin` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '是否提醒',
  `logistics_way` enum('0','1','2','3','4','5','6','7','8','9','10') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '快递公司(1中国邮政,2申通快递,3圆通速递,4顺丰速运,5韵达快递,6天天快递,7中通快递,8增益快递,9京东快递,10商城物流)',
  `logistics_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '快递单号',
  `status` enum('2','3','4') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '2' COMMENT '订单状态(2待发货,3待收货,4已收货)',
  `message` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户留言',
  `cretime` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `suctime` int(11) NOT NULL DEFAULT 0 COMMENT '成交时间',
  `sendtime` int(11) NOT NULL DEFAULT 0 COMMENT '发货时间',
  `endtime` int(11) NOT NULL DEFAULT 0 COMMENT '收货时间',
  `uptime` int(11) NOT NULL DEFAULT 0 COMMENT '变更时间',
  `status_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE,
  INDEX `shopid`(`typenum` ASC) USING BTREE,
  INDEX `time`(`cretime` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '购买成功记录表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_rechange`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '充值订单号',
  `type` tinyint(1) NULL DEFAULT NULL COMMENT '充值1/消费-1',
  `pay` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '详情',
  `money` decimal(15, 0) NOT NULL DEFAULT 0 COMMENT '金额',
  `time` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` int(2) NULL DEFAULT 2 COMMENT '2',
  `message` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `examine_status` int(2) NULL DEFAULT NULL COMMENT '审批状态',
  `examine_id` int(11) NULL DEFAULT NULL COMMENT '审批人id',
  `examine_time` datetime NULL DEFAULT NULL COMMENT '审批时间',
  `examine_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '审核备注',
  `examine_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `admin_id` int(11) NULL DEFAULT NULL COMMENT '操作员id',
  `admin_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `mode` int(2) NULL DEFAULT NULL COMMENT '类型：1注册赠送',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE,
  INDEX `type`(`type` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '充值赠送明细' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_recodes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `type` tinyint(1) NOT NULL COMMENT '收取1//充值-2/提现-3',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '详情',
  `shopid` int(11) NULL DEFAULT NULL COMMENT '商品id',
  `money` decimal(9, 2) NOT NULL DEFAULT 0.00 COMMENT '佣金',
  `time` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ygmoney` decimal(8, 2) NOT NULL COMMENT '云购金额',
  `cashoutid` int(11) NULL DEFAULT NULL COMMENT '申请提现记录表id',
  `unqsid` int(11) NULL DEFAULT NULL,
  `yaoqing` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unqsid`(`unqsid` ASC) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE,
  INDEX `type`(`type` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员佣金明细' ROW_FORMAT = COMPACT;

CREATE TABLE `go_member_transfer`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '转赠人',
  `to_uid` int(11) NOT NULL COMMENT '接受人',
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '订单号',
  `mode` tinyint(1) NOT NULL COMMENT '订单类型1云购，2优惠购，3拼团，4直购',
  `create_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '订单转赠记录' ROW_FORMAT = Dynamic;

CREATE TABLE `go_member_zp`  (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '转盘商品id',
  `name` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品名称',
  `money` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品金额',
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品图片',
  `Grade` tinyint(20) UNSIGNED NOT NULL COMMENT '奖品等级 (一等奖)',
  `probability` tinyint(10) NOT NULL COMMENT '获奖的几率',
  `type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '0实物/1积分/余额',
  `ren` tinyint(3) NULL DEFAULT NULL COMMENT '邀请的人数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_mobile_verify`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mobile` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `verify` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `time` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '手机登录验证码表' ROW_FORMAT = FIXED;

CREATE TABLE `go_model`  (
  `modelid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `table` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`modelid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '模型表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_navigation`  (
  `cid` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parentid` smallint(6) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `type` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Y' COMMENT '显示/隐藏',
  `order` smallint(6) UNSIGNED NULL DEFAULT 1,
  `language` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '语言',
  PRIMARY KEY (`cid`) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  INDEX `order`(`order` ASC) USING BTREE,
  INDEX `type`(`type` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_offline_money`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL,
  `bank_id` int(10) UNSIGNED NOT NULL COMMENT '线下充值id	',
  `update_time` int(10) UNSIGNED NULL DEFAULT NULL,
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL,
  `status` tinyint(2) UNSIGNED NULL DEFAULT 1 COMMENT '状态 1 提交 2 同意 3 拒绝',
  `volume` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '	类容',
  `money` decimal(10, 2) NULL DEFAULT NULL COMMENT '金额',
  `picarr` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '图片',
  `type` tinyint(1) NULL DEFAULT NULL COMMENT '类型',
  `exchange_rate` float(10, 3) NULL DEFAULT 1.000 COMMENT '汇率',
  `real_money` decimal(12, 3) NULL DEFAULT NULL COMMENT '真实到账',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户银行卡操作列表' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_ordercodes_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `code` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '订单单号',
  `shop_codes` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '云购码',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `shopid` int(11) NOT NULL COMMENT '商品id',
  `shopqishu` smallint(6) NOT NULL COMMENT '商品期数',
  `time` char(21) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '购买时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '订单云购码表' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_orders`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `uphoto` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户头像',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '会员id',
  `shopid` int(6) UNSIGNED NOT NULL COMMENT '商品id',
  `shopname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品名',
  `gonumber` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '购买数量',
  `moneycount` decimal(10, 2) NOT NULL COMMENT '订单总价',
  `pay_type` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '付款方式',
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单状态',
  `time` char(21) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '购买时间',
  `company` char(18) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '快递单位',
  `company_code` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '快递单号',
  `company_money` int(10) UNSIGNED NOT NULL COMMENT '快递公司',
  `address` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '收货地址',
  `order_phone` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '收货人电话',
  `confirm_address` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否收货',
  `money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '实际支付金额',
  `point` int(11) NULL DEFAULT 0 COMMENT '实际消耗积分',
  `ip_region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `price` decimal(15, 2) NULL DEFAULT NULL COMMENT '单价',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE,
  INDEX `shopid`(`shopid` ASC) USING BTREE,
  INDEX `time`(`time` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '优惠购订单' ROW_FORMAT = COMPACT;

CREATE TABLE `go_orders_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名称',
  `uphoto` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户头像',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '会员id',
  `shopid` int(6) UNSIGNED NOT NULL COMMENT '商品id',
  `shopname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品名',
  `goucode_len` int(10) NULL DEFAULT NULL COMMENT '云购码长度',
  `goucode` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '云购码',
  `gonumber` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '购买次数',
  `ordercode_table` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '云购码订单表名',
  `moneycount` decimal(10, 2) NOT NULL COMMENT '订单总价',
  `huode` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '中奖码',
  `q_end_time` datetime NULL DEFAULT NULL COMMENT '中奖时间',
  `pay_type` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '付款方式',
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ip',
  `ip_region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'ip归属地',
  `status` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单状态',
  `time` char(21) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '购买时间',
  `company` char(18) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '快递单位',
  `company_code` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '快递单号',
  `company_money` int(10) UNSIGNED NOT NULL,
  `address` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '地址',
  `order_phone` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `confirm_address` tinyint(4) NOT NULL DEFAULT 0 COMMENT '确认收货',
  `money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '实际支付金额',
  `point` int(11) NULL DEFAULT 0 COMMENT '实际消耗积分',
  `group_num` int(3) NULL DEFAULT NULL COMMENT '拼团总人数',
  `join_num` int(3) UNSIGNED NULL DEFAULT 1 COMMENT '已加入人数',
  `end_time` datetime NULL DEFAULT NULL COMMENT '拼团结束时间',
  `pid` int(11) NULL DEFAULT 0 COMMENT '主订单id',
  `group_status` tinyint(1) NULL DEFAULT NULL COMMENT '拼团状态：1进行中，2完成，3失败',
  `is_end` tinyint(1) NULL DEFAULT 0 COMMENT '是否开奖，结束',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid` ASC) USING BTREE,
  INDEX `shopid`(`shopid` ASC) USING BTREE,
  INDEX `time`(`time` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '拼团订单' ROW_FORMAT = COMPACT;

CREATE TABLE `go_oucld_friends`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `a_uid` int(10) NOT NULL COMMENT '发起用户id',
  `a_name` char(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户别名',
  `a_unread_num` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发起用户未读消息',
  `a_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `b_uid` int(10) NOT NULL COMMENT '接受用户id',
  `b_name` char(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户别名',
  `b_unread_num` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发起用户未读消息',
  `b_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `rename` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `status` tinyint(3) NOT NULL COMMENT '状态 1.正常，2.申请中 3.删除好友 4.拉黑，5拒绝申请',
  `delete_uid` int(10) NULL DEFAULT NULL COMMENT '主动删除用户id',
  `delete_time` int(10) NULL DEFAULT NULL COMMENT '软件删除时间',
  `shielding_uid` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '主动拉黑用户json  {1:1,2:2}',
  `update_time` int(10) NULL DEFAULT NULL COMMENT '操作时间  同意时间 拒绝时间',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  `mes_sending_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '消息发送时间 根据这个字段来排序(预留)',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `friendsData`(`a_uid` ASC, `b_uid` ASC, `status` ASC) USING BTREE COMMENT '好友关系索引'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = ' 好友表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_position`  (
  `pos_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pos_model` tinyint(3) UNSIGNED NOT NULL,
  `pos_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pos_num` tinyint(3) UNSIGNED NOT NULL,
  `pos_maxnum` tinyint(3) UNSIGNED NOT NULL,
  `pos_this_num` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `pos_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`pos_id`) USING BTREE,
  INDEX `pos_id`(`pos_id` ASC) USING BTREE,
  INDEX `pos_model`(`pos_model` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_position_data`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `con_id` int(10) UNSIGNED NOT NULL,
  `mod_id` tinyint(3) UNSIGNED NOT NULL,
  `mod_name` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pos_id` int(10) UNSIGNED NOT NULL,
  `pos_data` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pos_order` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `pos_time` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_promotion_code`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '域名',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '值',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '预留字段',
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '渠道推广' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_qiandao`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `lianxu` int(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '连续签到',
  `sum` int(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '签到天数',
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '签到时间',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '签到' ROW_FORMAT = COMPACT;

CREATE TABLE `go_qqset`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `qq` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `qqurl` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `full` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `province` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `city` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `county` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `subtime` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_qr_codes`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `backgrounds` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '背景图片',
  `icons` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '图标',
  `qr_code_size` decimal(5, 2) NULL DEFAULT NULL COMMENT '二维码大小',
  `qr_code_border` decimal(5, 2) NULL DEFAULT NULL COMMENT '二维码边框',
  `left_margin` decimal(6, 2) NULL DEFAULT NULL COMMENT '二维码距离左边距',
  `top_margin` decimal(6, 2) NULL DEFAULT NULL COMMENT '二维码距离顶部距离',
  `internal_x` decimal(6, 2) NULL DEFAULT NULL COMMENT '二维码距离左边内部距离',
  `code_img_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '二维码路径',
  `internal_y` decimal(6, 2) NULL DEFAULT NULL COMMENT '二维码距离上边内部距离',
  `is_selected` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '是否被选中1选中 2 未选中',
  `update_time` int(11) UNSIGNED NULL DEFAULT NULL,
  `create_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分享二维码' ROW_FORMAT = COMPACT;

CREATE TABLE `go_quanzi`  (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标题',
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图片地址',
  `chengyuan` mediumint(8) UNSIGNED NULL DEFAULT 0 COMMENT '成员数',
  `tiezi` mediumint(8) UNSIGNED NULL DEFAULT 0 COMMENT '帖子数',
  `guanli` mediumint(8) UNSIGNED NOT NULL COMMENT '管理员',
  `jinhua` smallint(5) UNSIGNED NULL DEFAULT NULL COMMENT '精华帖',
  `jianjie` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '暂无介绍' COMMENT '简介',
  `gongao` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '暂无' COMMENT '公告',
  `jiaru` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Y' COMMENT '申请加入',
  `glfatie` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' COMMENT '发帖权限',
  `time` int(11) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_quanzi_hueifu`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `tzid` int(11) NULL DEFAULT NULL COMMENT '帖子ID匹配',
  `hueifu` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '回复内容',
  `hueiyuan` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '会员',
  `hftime` int(11) NULL DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_quanzi_tiezi`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `qzid` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '圈子ID匹配',
  `hueiyuan` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '会员信息',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `neirong` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '内容',
  `hueifu` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '回复',
  `dianji` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '点击量',
  `zhiding` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' COMMENT '置顶',
  `jinghua` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' COMMENT '精华',
  `zuihou` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '最后回复',
  `time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_recharge_discount`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `discount` decimal(10, 2) NULL DEFAULT NULL COMMENT '折扣',
  `day` int(5) NULL DEFAULT NULL COMMENT '累计充值天数',
  `last_time` int(11) NULL DEFAULT NULL COMMENT '最后充值时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户充值折扣' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_recharge_money`  (
  `id` tinyint(5) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `time` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '充值时间',
  `dindancode` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `status` int(2) NULL DEFAULT 0 COMMENT '0已做废 1生效',
  `day` int(10) NOT NULL DEFAULT 0 COMMENT '计累领奖天数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '充值奖励时间表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_recom`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '推荐位id',
  `img` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '推荐位图片',
  `title` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '推荐位标题',
  `link` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '链接地址',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_reg_money`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `money` decimal(12, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户注册赠送奖励金' ROW_FORMAT = FIXED;

CREATE TABLE `go_send`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(11) UNSIGNED NULL DEFAULT 0,
  `gid` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '商品ID',
  `username` char(50) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT '' COMMENT '用户名',
  `shoptitle` char(120) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT '' COMMENT '商品名称',
  `send_type` tinyint(4) UNSIGNED NULL DEFAULT 0 COMMENT '发送类型',
  `send_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '发送时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '中奖信息发送列表' ROW_FORMAT = FIXED;

CREATE TABLE `go_shaidan`  (
  `sd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '晒单id',
  `sd_userid` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '用户ID',
  `sd_shopid` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '商品ID',
  `sd_shoptitle` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '晒单商品标题',
  `sd_shopsid` int(11) NULL DEFAULT NULL COMMENT '商品sid',
  `sd_qishu` int(10) NULL DEFAULT NULL COMMENT '商品期数',
  `sd_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sd_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '晒单标题',
  `sd_thumbs` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '缩略图',
  `sd_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '晒单内容',
  `sd_photolist` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '晒单图片',
  `sd_zhan` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '点赞',
  `sd_ping` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '评论',
  `sd_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '晒单时间',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态',
  `examine_status` tinyint(1) NULL DEFAULT 1 COMMENT '审核状态',
  `examine_id` int(11) NULL DEFAULT NULL,
  `examine_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `examine_time` int(11) NULL DEFAULT NULL,
  `examine_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`sd_id`) USING BTREE,
  INDEX `sd_userid`(`sd_userid` ASC) USING BTREE,
  INDEX `sd_shopid`(`sd_shopid` ASC) USING BTREE,
  INDEX `sd_zhan`(`sd_zhan` ASC) USING BTREE,
  INDEX `sd_ping`(`sd_ping` ASC) USING BTREE,
  INDEX `sd_time`(`sd_time` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '晒单' ROW_FORMAT = COMPACT;

CREATE TABLE `go_shaidan_hueifu`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sdhf_id` int(11) NOT NULL COMMENT '晒单ID',
  `sdhf_userid` int(11) NULL DEFAULT NULL COMMENT '晒单回复会员ID',
  `sdhf_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '晒单回复内容',
  `sdhf_time` int(11) NULL DEFAULT NULL,
  `sdhf_username` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sdhf_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_shaidan_like`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sd_id` int(11) NOT NULL COMMENT '晒单ID',
  `uid` int(11) NULL DEFAULT NULL COMMENT '会员ID',
  `time` int(11) NULL DEFAULT NULL COMMENT '点赞时间',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_share`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL,
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `name`(`uid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

CREATE TABLE `go_shopcodes_1`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `s_id` int(10) UNSIGNED NOT NULL,
  `s_cid` smallint(5) UNSIGNED NOT NULL,
  `s_len` smallint(5) NULL DEFAULT NULL,
  `s_codes` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `s_codes_tmp` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `s_id`(`s_id` ASC) USING BTREE,
  INDEX `s_cid`(`s_cid` ASC) USING BTREE,
  INDEX `s_len`(`s_len` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_shopcollect`  (
  `shopid` int(11) NULL DEFAULT NULL,
  `uid` int(11) NULL DEFAULT NULL,
  `time` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商品收藏表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_shoplist`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `sid` int(10) UNSIGNED NOT NULL COMMENT '同一个商品',
  `cateid` smallint(6) UNSIGNED NULL DEFAULT NULL COMMENT '所属栏目ID',
  `brandid` smallint(6) UNSIGNED NULL DEFAULT NULL COMMENT '所属品牌ID',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品标题',
  `title_style` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `title2` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '副标题',
  `keywords` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '金额',
  `yunjiage` decimal(10, 2) UNSIGNED NULL DEFAULT 1.00 COMMENT '云购人次价格',
  `zongrenshu` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '总需人数',
  `canyurenshu` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '已参与人数',
  `shenyurenshu` int(10) UNSIGNED NULL DEFAULT NULL,
  `def_renshu` int(10) UNSIGNED NULL DEFAULT 0,
  `qishu` smallint(6) UNSIGNED NULL DEFAULT 0 COMMENT '期数',
  `maxqishu` smallint(5) UNSIGNED NULL DEFAULT 1 COMMENT ' 最大期数',
  `thumb` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `picarr` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品图片',
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品内容详情',
  `codes_table` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `xsjx_time` int(10) UNSIGNED NULL DEFAULT NULL,
  `pos` tinyint(4) UNSIGNED NULL DEFAULT NULL COMMENT '是否推荐',
  `renqi` tinyint(4) UNSIGNED NULL DEFAULT 0 COMMENT '是否人气商品0否1是',
  `time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '时间',
  `order` int(10) UNSIGNED NULL DEFAULT 1,
  `q_uid` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '中奖人ID',
  `q_user` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '中奖人信息',
  `q_user_code` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '中奖码',
  `q_content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '揭晓内容',
  `q_counttime` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '总时间相加',
  `q_end_time` decimal(20, 3) NULL DEFAULT NULL,
  `q_showtime` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' COMMENT 'Y/N揭晓动画',
  `zhiding` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '指定人中奖id',
  `not_prize_id` int(11) NOT NULL DEFAULT 0 COMMENT '不中奖人id',
  `goucount` int(32) NULL DEFAULT NULL,
  `buycount` int(32) NULL DEFAULT NULL,
  `ispk` int(2) NULL DEFAULT 0 COMMENT '否是为pk商品 1是 0否',
  `q_external_code` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '时彩时开奖号码',
  `q_external_content` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '时彩时开奖内容',
  `q_end_counttime` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '奖开时间总和',
  `ssc` int(2) NULL DEFAULT 0 COMMENT '否是为时时彩商品 0否1是',
  `send` int(2) NULL DEFAULT 0,
  `xg` int(5) NULL DEFAULT 0,
  `dh` int(2) NULL DEFAULT 0,
  `dhbl` int(3) NULL DEFAULT 0,
  `language` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Chinese' COMMENT '所属语言',
  `is_show` tinyint(2) UNSIGNED NULL DEFAULT 1 COMMENT '是否云商品库显示 1显示 2不显示',
  `preferential_price` decimal(20, 2) NULL DEFAULT NULL COMMENT '优惠购价格',
  `groups` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '拼团人数',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `renqi`(`renqi` ASC) USING BTREE,
  INDEX `order`(`yunjiage` ASC) USING BTREE,
  INDEX `q_uid`(`q_uid` ASC) USING BTREE,
  INDEX `sid`(`sid` ASC) USING BTREE,
  INDEX `shenyurenshu`(`shenyurenshu` ASC) USING BTREE,
  INDEX `q_showtime`(`q_showtime` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商品表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_shoplist_add`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `shopid` int(11) NULL DEFAULT NULL COMMENT '商品id',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '添加时间',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态1待审核，2通过，3拒绝',
  `is_money` tinyint(1) NULL DEFAULT 0 COMMENT '是否修改价格',
  `data` json NULL COMMENT '修改数据',
  `content_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '详情链接',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '入驻商添加商品审核表' ROW_FORMAT = Dynamic;

CREATE TABLE `go_shoplist_copy`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `sid` int(10) UNSIGNED NOT NULL COMMENT '同一个商品',
  `cateid` smallint(6) UNSIGNED NULL DEFAULT NULL COMMENT '所属栏目ID',
  `brandid` smallint(6) UNSIGNED NULL DEFAULT NULL COMMENT '所属品牌ID',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品标题',
  `title_style` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `title2` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '副标题',
  `keywords` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '金额',
  `yunjiage` decimal(10, 2) UNSIGNED NULL DEFAULT 1.00 COMMENT '云购人次价格',
  `zongrenshu` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '总需人数',
  `canyurenshu` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '已参与人数',
  `shenyurenshu` int(10) UNSIGNED NULL DEFAULT NULL,
  `def_renshu` int(10) UNSIGNED NULL DEFAULT 0,
  `qishu` smallint(6) UNSIGNED NULL DEFAULT 0 COMMENT '期数',
  `maxqishu` smallint(5) UNSIGNED NULL DEFAULT 1 COMMENT ' 最大期数',
  `thumb` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `picarr` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品图片',
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品内容详情',
  `codes_table` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `xsjx_time` int(10) UNSIGNED NULL DEFAULT NULL,
  `pos` tinyint(4) UNSIGNED NULL DEFAULT NULL COMMENT '是否推荐',
  `renqi` tinyint(4) UNSIGNED NULL DEFAULT 0 COMMENT '是否人气商品0否1是',
  `time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '时间',
  `order` int(10) UNSIGNED NULL DEFAULT 1,
  `q_uid` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '中奖人ID',
  `q_user` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '中奖人信息',
  `q_user_code` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '中奖码',
  `q_content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '揭晓内容',
  `q_counttime` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '总时间相加',
  `q_end_time` decimal(20, 3) NULL DEFAULT NULL,
  `q_showtime` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' COMMENT 'Y/N揭晓动画',
  `zhiding` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '指定人中奖id',
  `not_prize_id` int(11) NOT NULL DEFAULT 0 COMMENT '不中奖人id',
  `goucount` int(32) NULL DEFAULT NULL,
  `buycount` int(32) NULL DEFAULT NULL,
  `ispk` int(2) NULL DEFAULT 0 COMMENT '否是为pk商品 1是 0否',
  `q_external_code` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '时彩时开奖号码',
  `q_external_content` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '时彩时开奖内容',
  `q_end_counttime` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '奖开时间总和',
  `ssc` int(2) NULL DEFAULT 0 COMMENT '否是为时时彩商品 0否1是',
  `send` int(2) NULL DEFAULT 0,
  `xg` int(5) NULL DEFAULT 0,
  `dh` int(2) NULL DEFAULT 0,
  `dhbl` int(3) NULL DEFAULT 0,
  `language` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Chinese' COMMENT '所属语言',
  `is_show` tinyint(2) UNSIGNED NULL DEFAULT 1 COMMENT '是否云商品库显示 1显示 2不显示',
  `preferential_price` decimal(20, 2) NULL DEFAULT NULL COMMENT '优惠购价格',
  `groups` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '拼团人数',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `renqi`(`renqi` ASC) USING BTREE,
  INDEX `order`(`yunjiage` ASC) USING BTREE,
  INDEX `q_uid`(`q_uid` ASC) USING BTREE,
  INDEX `sid`(`sid` ASC) USING BTREE,
  INDEX `shenyurenshu`(`shenyurenshu` ASC) USING BTREE,
  INDEX `q_showtime`(`q_showtime` ASC) USING BTREE,
  INDEX `q_end_time`(`q_end_time` ASC) USING BTREE,
  INDEX `idx_q_uid_q_showtime_ssc`(`q_uid` ASC, `q_showtime` ASC, `ssc` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商品表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_shoplist_del`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `sid` int(10) UNSIGNED NOT NULL COMMENT '同一个商品',
  `cateid` smallint(6) UNSIGNED NULL DEFAULT NULL COMMENT '所属栏目ID',
  `brandid` smallint(6) UNSIGNED NULL DEFAULT NULL COMMENT '所属品牌ID',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品标题',
  `title_style` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `title2` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '副标题',
  `keywords` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '金额',
  `yunjiage` decimal(10, 2) UNSIGNED NULL DEFAULT 1.00 COMMENT '云购人次价格',
  `zongrenshu` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '总需人数',
  `canyurenshu` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '已参与人数',
  `shenyurenshu` int(10) UNSIGNED NULL DEFAULT NULL,
  `def_renshu` int(10) UNSIGNED NULL DEFAULT 0,
  `qishu` smallint(6) UNSIGNED NULL DEFAULT 0 COMMENT '期数',
  `maxqishu` smallint(5) UNSIGNED NULL DEFAULT 1 COMMENT ' 最大期数',
  `thumb` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `picarr` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品图片',
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品内容详情',
  `codes_table` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `xsjx_time` int(10) UNSIGNED NULL DEFAULT NULL,
  `pos` tinyint(4) UNSIGNED NULL DEFAULT NULL COMMENT '是否推荐',
  `renqi` tinyint(4) UNSIGNED NULL DEFAULT 0 COMMENT '是否人气商品0否1是',
  `time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '时间',
  `order` int(10) UNSIGNED NULL DEFAULT 1,
  `q_uid` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '中奖人ID',
  `q_user` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '中奖人信息',
  `q_user_code` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '中奖码',
  `q_content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '揭晓内容',
  `q_counttime` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '总时间相加',
  `q_end_time` decimal(20, 3) NULL DEFAULT NULL,
  `q_showtime` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' COMMENT 'Y/N揭晓动画',
  `zhiding` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `not_prize_id` int(11) NOT NULL,
  `goucount` int(32) NULL DEFAULT NULL,
  `buycount` int(32) NULL DEFAULT NULL,
  `ispk` int(2) NULL DEFAULT 0 COMMENT '否是为pk商品 1是 0否',
  `q_external_code` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `q_external_content` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `q_end_counttime` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ssc` int(2) NULL DEFAULT NULL,
  `send` int(2) NULL DEFAULT 0,
  `xg` int(5) NULL DEFAULT NULL,
  `dh` int(2) NULL DEFAULT NULL,
  `dhbl` int(3) NULL DEFAULT NULL,
  `language` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Chinese' COMMENT '所属语言',
  `is_show` tinyint(2) UNSIGNED NULL DEFAULT 1 COMMENT '是否云商品库显示 1显示 2不显示',
  `preferential_price` decimal(10, 2) NULL DEFAULT NULL COMMENT '优惠购价格',
  `groups` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '拼团人数',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `renqi`(`renqi` ASC) USING BTREE,
  INDEX `order`(`yunjiage` ASC) USING BTREE,
  INDEX `q_uid`(`q_uid` ASC) USING BTREE,
  INDEX `sid`(`sid` ASC) USING BTREE,
  INDEX `shenyurenshu`(`shenyurenshu` ASC) USING BTREE,
  INDEX `q_showtime`(`q_showtime` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商品回收站表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_shoplist_jifen`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `sid` int(10) UNSIGNED NOT NULL COMMENT '同一个商品',
  `cateid` smallint(6) UNSIGNED NOT NULL COMMENT '所属分类ID',
  `brandid` smallint(6) UNSIGNED NULL DEFAULT NULL COMMENT '所属品牌ID',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品标题',
  `title2` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '副标题',
  `keywords` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '关键字',
  `title_style` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题样式',
  `money` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '总价格',
  `disc_price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '扣折价',
  `s_score` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '积分',
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品内容详情',
  `sales` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '销量',
  `thumb` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图片',
  `picarr` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '展示图片',
  `online` int(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否上架0否1是',
  `pos` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否推荐0否1是',
  `renqi` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否人气商品0否1是',
  `order` int(2) NULL DEFAULT 1 COMMENT '时间顺序',
  `quality` int(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0为购享积分1为购享品质',
  `newhot` int(1) NOT NULL DEFAULT 0 COMMENT '新品或热销',
  `tejiachaozhi` int(1) NOT NULL DEFAULT 0 COMMENT '特价超值',
  `codes_table` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '添加时间',
  `updatime` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `fictitious` int(10) NOT NULL DEFAULT 0 COMMENT '虚拟销量',
  `language` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '语言',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '删除时间',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态1上架，2下降',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `renqi`(`renqi` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '普通商品表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_slide`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `img` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '幻灯片',
  `title` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `way` int(1) UNSIGNED NULL DEFAULT 0 COMMENT '手机端的轮播图',
  `language` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '多语言',
  `status` int(11) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `img`(`img` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '幻灯片表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_sound_effects`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '地址、路径',
  `status` int(1) NULL DEFAULT 1 COMMENT '状态1:开启，2关闭',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `srot` int(5) NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '音效表' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_sqldel`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `isaddtime` int(11) NULL DEFAULT NULL COMMENT '添加时间',
  `deltime` int(11) NULL DEFAULT NULL COMMENT '需要删除时间',
  `oktime` int(11) NULL DEFAULT NULL COMMENT '完成时间',
  `cont` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'sql',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `deltit` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '删除名称',
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '类型',
  `state` enum('1','2','3') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '1' COMMENT '状态1= 停止 ，2=进行中，3= 已完成',
  `uuid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '编号',
  `sort` int(3) NULL DEFAULT NULL COMMENT '分组排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_system_message`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `titles` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `to_id` int(10) UNSIGNED NOT NULL COMMENT '接受id',
  `msg_type` tinyint(3) UNSIGNED NOT NULL COMMENT '用户消息子类型 1 文字 2.表情 3.图片 4.语音',
  `send_status` tinyint(3) UNSIGNED NOT NULL COMMENT '发送状态 1发送成功 2发送失败',
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '消息的状态：0未读消息；1：已读消息，2消息已撤销',
  `view_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '接收者查看时间',
  `send_time` int(10) UNSIGNED NOT NULL COMMENT '发起时间',
  `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '消息类容  json_encode',
  `del` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 正常 2 删除',
  `title_id` int(10) NULL DEFAULT NULL COMMENT '标题id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统推送表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_system_title`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `titles` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `send_time` int(10) UNSIGNED NOT NULL COMMENT '发起时间',
  `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '消息类容  json_encode',
  `del` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 正常 2 删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统推送表' ROW_FORMAT = COMPACT;

CREATE TABLE `go_upload_config`  (
  `upload_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '下载页的logo',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `apk_upload` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '安卓下载链接',
  `ios_upload` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '苹果下载链接',
  `banner` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'banner图',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '描述',
  `edition` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '版本',
  `update_time` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '更新时间'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '下载页面配置' ROW_FORMAT = COMPACT;

CREATE TABLE `go_wap`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '标题',
  `link` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '超链接',
  `img` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '图片地址',
  `status` int(2) NULL DEFAULT 0 COMMENT '是否显示',
  `type` int(2) NULL DEFAULT 0 COMMENT '1 H5导航 0 幻灯片 2 APP导航',
  `sort` int(2) NOT NULL COMMENT '排列顺序',
  `pop_window` tinyint(2) UNSIGNED NULL DEFAULT 2 COMMENT '是否弹窗 1 弹窗  2 不弹窗',
  `language` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '所属语言',
  `picarr` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '图片列表',
  `data` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '数据',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `model` int(2) NULL DEFAULT 1 COMMENT '类型：1云购，2直购',
  `module` int(2) NULL DEFAULT 1 COMMENT '1导航，2底部',
  `bg_color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'banner设置' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_winning_probability`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `probability` decimal(10, 5) NULL DEFAULT 0.00000 COMMENT '中奖几率',
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `num` int(5) NULL DEFAULT 0 COMMENT '抽奖次数',
  `money` decimal(10, 5) NULL DEFAULT 0.00000 COMMENT '奖金',
  `add_time` int(11) NULL DEFAULT NULL COMMENT '添加抽奖次数时间',
  `rule_data` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '规则完成情况',
  `target_money` decimal(10, 5) NULL DEFAULT NULL COMMENT '目标金额',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '中奖概率表' ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_wxch_cfg`  (
  `cfg_id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cfg_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `cfg_value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '参数值',
  `autoload` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`cfg_id`) USING BTREE,
  UNIQUE INDEX `cfg_name`(`cfg_name`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `go_zp_number`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `number` int(11) NULL DEFAULT 0 COMMENT '抽奖次数',
  `time` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `has_num` int(2) NOT NULL DEFAULT 0 COMMENT '今日已次数抽奖',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = FIXED;

CREATE TABLE `go_zp_zj`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `Grade` int(4) NULL DEFAULT NULL COMMENT '中奖商品',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '中奖内容',
  `type` int(2) NOT NULL DEFAULT 0 COMMENT '是否领取/0未领取/1领取待发货/2已发货/3放弃\'',
  `time` int(50) NULL DEFAULT NULL COMMENT '时间',
  `ren` int(11) NULL DEFAULT 0 COMMENT '邀请好友人数',
  `yqren` int(11) NOT NULL DEFAULT 0 COMMENT '当日邀请人数',
  `state` int(2) NULL DEFAULT NULL COMMENT '奖品类型',
  `addrid` int(11) NULL DEFAULT NULL COMMENT '地址id',
  `kd` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '快递公司',
  `kdd` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '快递单号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;
