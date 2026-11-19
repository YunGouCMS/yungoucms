<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $this->getPageTitle('后台页面'); ?></title>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <script src="<?php echo G_TEMPLATES_JS; ?>/mobile/jquery190.js" language="javascript" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <style>
        tr{height:40px;line-height:40px}
        .table-list tbody td {padding: 10px 0 10px 10px;}
        .formborcol {
            border-radius: 5px;
            display: flex;
            overflow: hidden;
            width: 70px;
            margin-left: 10px;
            position: relative;
            padding-bottom: 28px!important;
            border: 2px solid #aaaaaa;
        }
        .noborcol {border-radius: 5px;width: 67px;padding: 2px;}
        .noborcol .actvicol:first-child {margin-right:5px;}
        .formborcol:first-child {margin-left: 0;}
        .formborcol .conborbor {
            width: 35px;height: 35px;
        }
        .formborcol .dagou {
            position: absolute;
            background: #aaaaaa;
            border-radius: 50%;
            width: 15px;
            height: 15px;
            bottom: 6px;
            left: 50%;
            transform: translateX(-50%);
            align-items: center;
            justify-content: center;
            display: flex;
        }
        .formborcol.active .dagou {
            background: #3C8DBC;
        }
        .formborcol .dagou img {
            width: 10px;
        }
        .actvicol {width: 31px;height: 31px;}
        .layui-colorpicker {width: 19px;height: 19px;}
        .active {border: 2px solid #3C8DBC;}
        .users,.index,.glist {
            margin-right: 15px;
            width: 340px;
            height: 640px;
            overflow: hidden;
            border-radius: 5px;
            border: 1px solid #eee;
            background-color: #f6f6f6;
            position: relative;
        }
        .users .qb-hed p {
            display: flex;
            align-items: center;
            padding: 25px 25px 20px;
            height: 48px;
            padding-top: 25px;
        }
        .users .qb-hed p img {
            width: 48px;
            max-height: 48px;
            border: 2px solid #eee;
            border-radius: 50%;
            margin-right: 10px;
        }
        .users .qb-hed p span {
            color: #fff;
            font-size: 18px;
        }
        .users .qb-hed div {
            display: flex;
            justify-content: space-around;
            padding-bottom: 35px;
        }
        .users .qb-hed div span {
            color: #fff;
            font-size: 13px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .users .qb-hed div span img {
            width: 25px;
        }
        .users .more-con {
            padding: 15px;
            margin-top: -20px;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            background-color: #f6f6f6;
        }
        .users .more-con > div {
            background-color: #fff;
            border-radius: 8px;
            padding: 10px 10px 0;
            margin-bottom: 15px;
        }
        .users .more-con > span {
            position: relative;
            display: inherit;
            margin-bottom: 12px;
        }
        .users .more-con > div > p {
            font-weight: 700;
            border-bottom: 1px solid #f6f6f6;
            padding: 10px;
            font-size: 15px;
        }
        .users .zhanghu {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #f6f6f6;
        }
        .users .zhanghu div {
            width: 50%;
            text-align: center;
            position: relative;
        }
        .users .zhanghu div span {
            font-weight: 700;
            font-size: 18px;
        }
        .users .zhanghu div p {
            font-size: 14px;
            width: auto;
            line-height: 16px;
        }
        .users .zhanghu div:first-child:after {
            content: "";
            right: 0;
            width: 2px;
            height: 20px;
            background: #ddd;
            top: 50%;
            margin-top: -10px;
            position: absolute;
        }
        .users .zichan {
            display: flex;
            padding: 3px 0;
            justify-content: space-around;
        }
        .users .zichan div {
            width: 50%;
            justify-content: center;
            font-size: 12px;
            align-items: center;
            display: flex;
        }
        .users .zichan div img {
            width: 15px;
            margin-right: 5px;
        }
        .users .zichan div span {
            display: inline-block;
            margin-left: 5px;
            border-width: 4px;
            border-color: transparent transparent transparent #000;
            border-style: dotted dotted dotted solid;
        }
        .users .more-con > span div {
            position: absolute;
            top: 50%;
            right: 50%;
            -webkit-transform: translate(50%, -50%);
            transform: translate(50%, -50%);
            z-index: 10;
            font-size: 24px;
            color: #fff;
            font-weight: 700;
            white-space: nowrap;
        }
        .users .more-con > span img {
            width: 100%;
        }
        .users .gengduo {
            padding-top: 10px;
        }
        .users .gengduo > div {
            padding-bottom: 20px;
            justify-content: space-between;
        }
        .users .gengduo div {
            display: flex;
            align-items: center;
        }
        .users .gengduo div img {
            width: 22px;
            margin: 0 13px 0 8px;
        }
        .users .gengduo > div > img {
            width: 12px;
        }
        .footer {
            position: absolute;
            bottom: 0;
            background-color: #fff;
            z-index: 91;
            padding-top: 10px;
            max-width: 640px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            padding-bottom: 10px;
            align-items: center;
            display: flex;
        }
        .footerflex {
            display: flex;
            align-items: center;
            flex-direction: column;
            width: 20%;
            justify-content: center;
            height: 100%;
            padding-bottom: 1px;
            position: relative;
        }
        .footer img {
            width: 24px;
            height: 24px;
        }
        .footer .footerflex span {
            font-size: 12px;
            padding: 3px 0 0;
            color: #888;
            line-height: 16px;
        }
        .glist .header2 {
            height: 45px;
            width: 100%;
            box-sizing: border-box;
            align-items: center;
            justify-content: space-between;
            padding: 0 10px;
            max-width: 640px;
            margin: 0 auto;
            display: flex;
        }
        .glist .leftback, .glist .rightcon {
            width: 15%;
        }
        .glist .centit {
            font-size: 17px;
            color: #fff;
        }
        .glist .rightcon img {
            width: 20px;
            height: 20px;
            margin-left: 13px;
        }
        .glist .rightcon {
            display: flex;
            justify-content: flex-end;
        }
        .glist .segment,.glist .segview {
            border-top-right-radius: 18px;
            border-top-left-radius: 18px;
            width: 100%;
        }
        .glist .segview {
            display: flex;
            justify-content: space-around;
            background-color: #f6f6f6;
            overflow: hidden;
        }
        .glist .segview span {
            box-sizing: border-box;
            color: #888;
            white-space: nowrap;
            display: flex;
            align-items: center;
            line-height: 42px;
            font-size: 14px;
        }
        .glist .segment > div span img {
            width: 10px;
        }
        .glist .mescroll {
            background-color: #f6f6f6;
        }
        .glist .mescroll > div {
            display: flex;
            background-color: #fff;
            box-sizing: border-box;
            margin: 0 9px 5px;
            padding: 4px 4px 4px 9px;
            width: 93%;
            align-items: center;
            border-radius: 7px;
            position: relative;
            justify-content: space-between;
        }
        .glist .mescroll > div > img {
            width: 72px;
            max-height: 72px;
        }
        .glist .mescroll > div > div {
            width: calc(100% - 82px);
            padding: 4px 0 0;
        }
        .glist .mescdpr {
            display: flex;
            justify-content: space-between;
        }
        .glist .mescdpr img {
            width: 30px;
            max-height: 30px;
            margin-top: 9px;
        }
        .glist .mescdpr > div {
            width: calc(100% - 41px);
            padding: 5px 0 0;
        }
        .glist .pfontbew {
            padding: 0 1px;
            display: flex;
        }
        .glist .pfontbew div {
            padding-top: 1px;
            display: inline-block;
            width: calc(100% / 3);
            text-align: center;
            color: #777;
            display: flex;
            flex-direction: column;
            line-height: 16px;
        }
        .glist .mescdpr > div > span {
            height: 3px;
            border-radius: 1px;
            background-color: #ddd;
            display: block;
            overflow: hidden;
            position: relative;
        }
        .glist .mescdpr > div > span font {
            position: absolute;
            left: 0;
            bottom: 0;
            top: 0;
        }
        .mui-ellipsis-2 {
            line-height: 16px;
            text-align: left;
        }
        .glist .pfontbew div:first-child {
            text-align: left;
        }
        .glist .pfontbew div:last-child {
            text-align: right;
        }
        .glist .pfontbew div span {
            font-size: 11px;
            font-weight: 400;
            color: #777;
        }
        .index .jjjxnnbox {
            margin: 13px 13px 0;
            background: url(/index/static/images/img_lucky_home_soon_bg.png) no-repeat;
            background-size: 100% 100%;
            height: 150px;
        }
        .index .jjjxnnbox .xnnboxtit {
            width: 95%;
            margin: 0 auto;
            height: 36px;
            line-height: 36px;
            color: #777;
        }
        .index .jjjxnnbox .xnnboxtit span:first-child {
            color: #333;
            font-size: 16px;
            font-weight: 700;
            padding-right: 4px;
        }
        .index .jjjxnnbox .xnnboxtit div {
            float: right;
            font-size: 14px;
        }
        .index .jjjxnnbox .xnnboxtit div img {
            width: 10px;
            max-height: 10px;
        }
        .index .jjjxnnbox .xnnboxcon {
            overflow: scroll;
            display: flex;
        }
        .index .jjjxnnbox .xnnboxcon .nnhone {
            display: flex;
            margin-right: 10px;
            background-color: #fff;
            padding: 4px;
            border-radius: 9px;
        }
        .index .jjjxnnbox .xnnboxcon .nnhone img {
            width: 80px;
            max-height: 80px;
        }
        .index .jjjxnnbox .xnnboxcon .nnhone > div {
            width: 104px;
            padding: 4px 0 4px 4px;
            font-size: 11px;
        }
        .index .jjjxnnbox .xnnboxcon::-webkit-scrollbar {
            display: none;
        }
        .index .new-open {
            margin: 0 13px;
            height: 136px;
            padding: 7px 0;
            background: url(/index/static/images/img_lucky_home_newest_bg.png);
            background-size: 100%;
            background-repeat: no-repeat;
            display: flex;
            box-sizing: border-box;
        }
        .index .new-open .openleft {
            display: flex;
            align-items: center;
            flex-direction: column;
            height: 100%;
            width: 30%;
            justify-content: flex-end;
        }
        .index .new-open .openleft span {
            padding: 2px;
            margin: 13px 0 9px;
            font-size: 10px;
            color: #fff;
            border-radius: 45px;
            line-height: 14px;
        }
        .index .new-open .openleft p {
            line-height: 16px;
        }
        .index .scrollview {
            width: 70%;
            display: flex;
            height: 114px;
            overflow: hidden;
            gap: 5px;
        }
        .index .view_Hcon {
            min-width: 84px;
            padding-top: 9px;
            position: relative;
            margin-right: 9px;
            border-radius: 4px;
            box-sizing: border-box;
            height: 100%;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            display: flex;
        }
        .index .view_Hcon img {
            width: 57px;
            max-height: 57px;
            min-height: 57px;
        }
        .index .view_Hcon > span {
            top: 48px;
            padding: 1px 7px;
            border-top-right-radius: 27px;
            border-bottom-right-radius: 27px;
            left: 0;
            position: absolute;
            color: #fff;
            font-size: 12px;
            line-height: 16px;
        }
        .index .view_Hcon .lastime {
            padding: 4px 0;
            text-align: center;
            width: 100%;
            line-height: normal;
        }
        .index .lastime > span {
            color: #999;
        }
        .index .lastime p {
            padding: 2px 9px 0;
            line-height: 16px;
        }
        .index .menuml {
            display: flex;
            margin: 0 9px;
            justify-content: space-around;
            padding: 16px 0;
        }
        .index .menuml div {
            display: flex;
            align-items: center;
            font-size: 12px;
            color: #333;
            flex-direction: column;
            width: calc(100% / 5);
        }
        .index .menuml div img {
            width: 41px;
            margin-bottom: 4px;
            max-height: 41px;
        }
        .index .menuml div span {
            line-height: 16px;
        }
        .index .notice-wrapper {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            margin: 13px auto 0;
            background-color: #fffcda;
            font-size: 13px;
            padding: 3px 10px 3px 0;
            width: calc(100% - 54px);
            border-radius: 4px;
            display: flex;
        }
        .index .notice-wrapper .notice-img {
            font-size: 0;
            position: relative;
            z-index: 10;
            padding: 6px 9px 0 10px;
            background-color: #fffcda;
        }
        .index .notice-wrapper .notice-img img {
            width: 18px;
        }
        .index .banner_swiper {
            height: 146px;
            margin: 13px 13px 0;
            position: relative;
        }
        .index .banner_swiper img {
            border-radius: 9px;
            width: 100%;
        }
        .index .banner_swiper div {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            position: absolute;
            left: 50%;
            bottom: 10px;
            transform: translateX(-50%);
        }
        .index .center-trip > div {
            padding: 13px 13px 0;
            justify-content: space-around;
            background-color: #fff;
            display: flex;
            line-height: 15px;
            border-radius: 30px;
        }
        .center-trip {
            margin-top: -15px;
        }
        .center-trip div span {
            color: #333;
        }
        .center-trip img {
            width: 18px;
            max-height: 18px;
            margin-right: 3px;
        }
        .header1 {
            height: 56px;
            box-sizing: border-box;
            padding: 0 9px 15px 9px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logopng img{
            width: 22px;
            max-height: 22px;
            border-radius: 50%;
        }
        .header1 .logopng:first-child img {
            background-color: #fff;
        }
        .logopng img.radionone {
            border-radius: initial;
        }
        .inputsou {
            background-color: #fff;
            border-radius: 31px;
            border: 1px solid #fff;
            height: calc(100% - 16px);
            width: calc(100% - 77px);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .inputsou img {
            width: 18px;
            margin-left: 9px;
            height: 18px;
        }
        .inputsou div {
            color: #fff;
            font-size: 14px;
            padding: 0 13px;
            height: 100%;
            white-space: nowrap;
            display: flex;
            align-items: center;
            border-radius: 18px;
        }
        .inputsou span {
            height: 100%;
            font-size: 14px;
            color: grey;
            line-height: 25px;
        }
        .bgcolor {background-color: #d32d25;}
        .fscolor {color: #d32d25;}
        .fscolor path {fill: #d32d25;}
        .bg-jb3 {
            background: #d32d25;
            background: -webkit-linear-gradient(left, #f86025, #d32d25);
            background: -o-linear-gradient(left,#f86025,#d32d25);
            background: -moz-linear-gradient(left,#f86025,#d32d25);
            background: linear-gradient(left,#f86025,#d32d25);
        }
        .mescroll .mescdpr .pfontbew .fscolor span {margin: 0!important;}
    </style>
</head>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
    <!--start-->
    <form name="myform" action="<?php echo G_MODULE_PATH;?>/codes/uploadpost" method="post">
        <table width="100%" cellspacing="0">
            <tr>
                <td style="display: flex;align-items: center;">
                    <div class="formborcol active">
                        <div class="conborbor" style="background-color: #d32d25;"></div>
                        <div class="conborbor" style="background-color: #f86025;"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                    <div class="formborcol">
                        <div class="conborbor" style="background-color: #1fb481;"></div>
                        <div class="conborbor" style="background-color: #9ade97;"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                    <div class="formborcol">
                        <div class="conborbor" style="background-color: #25c377;"></div>
                        <div class="conborbor" style="background-color: #f86c53;"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                    <div class="formborcol">
                        <div class="conborbor" style="background-color: #a40035;"></div>
                        <div class="conborbor" style="background-color: #f1c06b;"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                    <div class="formborcol">
                        <div class="conborbor" style="background-color: #db4e4e;"></div>
                        <div class="conborbor" style="background-color: #e28e60;"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                    <div class="formborcol">
                        <div class="conborbor" style="background-color: #dd6631;"></div>
                        <div class="conborbor" style="background-color: #e49b32;"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                    <div class="formborcol">
                        <div class="conborbor" style="background-color: #ebcc3b;"></div>
                        <div class="conborbor" style="background-color: #20252d;"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                    <div class="formborcol">
                        <div class="conborbor" style="background-color: #8ac5ab;"></div>
                        <div class="conborbor" style="background-color: #232826;"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                    <div class="formborcol">
                        <div class="conborbor" style="background-color: #8328f9;"></div>
                        <div class="conborbor" style="background-color: #b49fe2;"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                    <div class="formborcol">
                        <div class="conborbor" style="background-color: #ca2432;"></div>
                        <div class="conborbor" style="background-color: #efd645;"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                    <div class="formborcol">
                        <div class="conborbor" style="background-color: #db4e4e;"></div>
                        <div class="conborbor" style="background-color: #555555;"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                    <div class="formborcol">
                        <div class="conborbor" style="background-color: #bbaa70;"></div>
                        <div class="conborbor" style="background-color: #dcd6a9;"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                    
                    <div class="formborcol noborcol">
                        <div id="test-all<?php echo $key; ?>" class="testall actvicol"></div>
                        <div id="test-all2<?php echo $key; ?>" class="testall2 actvicol"></div>
                        <div class="dagou"><img src="/index/static/images/gg.png"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="display: flex;">
                    <div class="index">
                        <div class="header1 bgcolor">
                            <div class="logopng"><img src="/index/static/images/ico17.png"></div>
                            <div class="inputsou">
                                <span style="margin: 0!important;display: flex;align-items: center;padding-left: 15px;gap: 10px;"><img src="/index/static/images/sousuo.png">apple</span><div class="bgcolor" style="margin: 0!important;">搜索</div>
                            </div>
                            <div class="logopng"><img src="/index/static/images/icon_lucky_home_fenx.png" class="radionone"></div>
                        </div>
                        <div class="center-trip">
                            <div>
                                <div><img src="/index/static/images/icon_lucky_home_justice_tag1.png"><span>100%公开</span></div>
                                <div><img src="/index/static/images/icon_lucky_home_quality_tag2.png"><span>100%正品</span></div>
                                <div><img src="/index/static/images/icon_lucky_home_exemption_tag3.png"><span>免费配送</span></div>
                            </div>
                        </div>
                        <div class="banner_swiper">
                            <img src="/statics/uploads/banner/20241221/43831046755155.png"><div class="bgcolor"></div>
                        </div>
                        <div class="notice-wrapper fscolor">
                            <div class="notice-img"><img src="/index/static/images/gonggao.png"></div>
                            <div class="notice-content">YunGouCMS：Provide everyone with a high-quality shopping experience!</div>
                        </div>
                        <div class="menuml">
                            <div><img src="/statics/uploads/banner/20241221/76373779756014.png"><span>获得积分</span></div>
                            <div><img src="/statics/uploads/banner/20241221/87582736756036.png"><span>去充值</span></div>
                            <div><img src="/statics/uploads/banner/20241221/58907937756044.png"><span>客服</span></div>
                            <div><img src="/statics/uploads/banner/20241221/74523988756052.png"><span>邀好友</span></div>
                            <div><img src="/statics/uploads/banner/20241221/74047559756061.png"><span>App下载</span></div>
                        </div>
                        <div class="new-open">
                            <div class="openleft">
                                <p>快来围观</p>
                                <p>一起见证幸运儿</p>
                                <span class="bgcolor">查看更多</span>
                            </div>
                            <div class="scrollview">
                                <div class="view_Hcon">
                                    <img src="/statics/uploads/shopimg/20250614/87943448913775.jpg">
                                    <span class="bg-jb3">第1989期</span>
                                    <div class="lastime"><span>获奖者</span><p>烂</p></div>
                                </div>
                                <div class="view_Hcon">
                                    <img src="/statics/uploads/shopimg/20250614/20257571918584.jpg">
                                    <span class="bg-jb3">第2187期</span>
                                    <div class="lastime"><span>获奖者</span><p>再不中投降</p></div>
                                </div>
                                <div class="view_Hcon">
                                    <img src="/statics/uploads/shopimg/20250614/26630192915433.jpg">
                                    <span class="bg-jb3">第1647期</span>
                                    <div class="lastime"><span>获奖者</span><p>中个车呗谢</p></div>
                                </div>
                            </div>
                        </div>
                        <div class="jjjxnnbox">
                            <div class="xnnboxtit">
                                <span>即将揭晓</span>
                                <div>查看更多<img src="/index/static/images/j-right.png"></div>
                            </div>
                            <div class="xnnboxcon">
                                <div class="nnhone">
                                    <img src="/statics/uploads/shopimg/20250614/93336937914693.jpg">
                                    <div>
                                        <p>(第2026期)苏泊尔 SY-25YC10电压力锅迷你高压锅智能小饭煲 </p>
                                    </div>
                                </div>
                                <div class="nnhone">
                                    <img src="/statics/uploads/shopimg/20250614/20257571918584.jpg">
                                    <div>
                                        <p>(第2026期)苏泊尔 SY-25YC10电压力锅迷你高压锅智能小饭煲 </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <div class="footerflex"><svg class="fscolor" t="1773126897428" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="9011" width="24" height="24"><path d="M885.76 584.517c-5.912-5.52-14.232-8.56-23.429-8.56-18.744 0-32.35 12.304-32.35 29.255v241.44c0 19.081-18.867 37.12-38.824 37.12H629.481c-18.708 0-32.286 12.302-32.286 29.255 0 16.952 13.578 29.256 32.286 29.256h161.676c57.014 0 103.398-42.9 103.398-95.63V605.213c0-8.167-3.04-15.325-8.795-20.695z" p-id="9012" fill="#888888"></path><path d="M1007.076 510.629L536.555 71.455c-3.809-3.565-13.593-8.567-23.47-8.567-9.844 0-19.654 5.016-23.476 8.576L19.085 510.61c-4.022 7.544-9.86 18.657-10.365 22.348 0.073 8.17 4.868 17.648 10.218 27.698 4.474 3.383 13.23 7.372 22.068 7.372 9.877 0 19.66-5.002 23.453-8.553l60.656-56.606v342.743c0 52.827 46.399 95.804 103.43 95.804h168.15c18.689 0 32.255-12.302 32.255-29.254V718.969h200.533c18.708 0 32.289-12.33 32.289-29.317 0-16.915-13.58-29.193-32.289-29.193h-232.79c-18.708 0-32.286 12.278-32.286 29.193v188.06h-129.42c-19.975 0-38.859-18.04-38.859-37.121V436.564l316.894-295.758L961.63 553.399c4.542 4.19 14.327 8.63 23.538 8.63 9.841 0 19.656-5.014 23.48-8.576 4.292-4 8.735-12.734 8.804-20.495-0.506-3.77-6.464-15.053-10.377-22.33z" p-id="9013" fill="#888888"></path></svg><span class="fscolor">首页</span></div>
                            <div class="footerflex"><svg t="1773128006926" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="11239" width="24" height="24"><path d="M390.140093 66.514903 132.242372 66.514903c-38.551014 0-69.799716 31.248701-69.799716 69.787436l0 257.921257c0 38.539758 31.248701 69.789483 69.799716 69.789483l257.897721 0c38.549991 0 69.798692-31.249725 69.798692-69.789483L459.938786 136.302339C459.938786 97.763604 428.691107 66.514903 390.140093 66.514903zM410.252153 361.982288c0 28.906353-23.435759 52.343135-52.355415 52.343135L164.484703 414.325423c-28.919656 0-52.355415-23.436782-52.355415-52.343135l0-193.435571c0-28.9084 23.435759-52.343135 52.355415-52.343135l193.412035 0c28.919656 0 52.355415 23.435759 52.355415 52.343135L410.252153 361.982288z" p-id="11240" fill="#888888"></path><path d="M887.022791 66.514903 629.10051 66.514903c-38.537711 0-69.78539 31.248701-69.78539 69.787436l0 257.921257c0 38.539758 31.247678 69.789483 69.78539 69.789483l257.922281 0c38.539758 0 69.788459-31.249725 69.788459-69.789483L956.81125 136.302339C956.812273 97.763604 925.562549 66.514903 887.022791 66.514903zM907.123594 361.982288c0 28.906353-23.434735 52.343135-52.355415 52.343135L661.357168 414.325423c-28.92068 0-52.355415-23.436782-52.355415-52.343135l0-193.435571c0-28.9084 23.434735-52.343135 52.355415-52.343135l193.411011 0c28.92068 0 52.355415 23.435759 52.355415 52.343135L907.123594 361.982288z" p-id="11241" fill="#888888"></path><path d="M887.022791 563.386344 629.10051 563.386344c-38.537711 0-69.78539 31.248701-69.78539 69.787436l0 257.922281c0 38.539758 31.247678 69.788459 69.78539 69.788459l257.922281 0c38.539758 0 69.788459-31.248701 69.788459-69.788459L956.81125 633.17378C956.812273 594.635045 925.562549 563.386344 887.022791 563.386344zM907.123594 858.853729c0 28.906353-23.434735 52.343135-52.355415 52.343135L661.357168 911.196865c-28.92068 0-52.355415-23.436782-52.355415-52.343135L609.001753 665.418158c0-28.907377 23.434735-52.344159 52.355415-52.344159l193.411011 0c28.92068 0 52.355415 23.436782 52.355415 52.344159L907.123594 858.853729z" p-id="11242" fill="#888888"></path><path d="M391.086651 563.386344 131.295813 563.386344c-38.029128 0-68.853158 30.825053-68.853158 68.841901l0 259.814374c0 38.016849 30.825053 68.840878 68.853158 68.840878l259.790838 0c38.030152 0 68.853158-30.824029 68.853158-68.840878L459.939809 632.228245C459.938786 594.211397 429.11578 563.386344 391.086651 563.386344zM410.252153 858.853729c0 28.906353-23.435759 52.343135-52.355415 52.343135L164.484703 911.196865c-28.919656 0-52.355415-23.436782-52.355415-52.343135L112.129288 665.418158c0-28.907377 23.435759-52.344159 52.355415-52.344159l193.412035 0c28.919656 0 52.355415 23.436782 52.355415 52.344159L410.252153 858.853729z" p-id="11243" fill="#888888"></path></svg><span>所有商品</span></div>
                            <div class="footerflex"><img src="/index/static/images/3-002.png"><span>购物车</span></div>
                            <div class="footerflex"><img src="/index/static/images/4-002.png"><span>最新揭晓</span></div>
                            <div class="footerflex"><svg t="1773128195540" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="33851" width="24" height="24"><path d="M512 85.333333c129.607111 0 234.666667 105.059556 234.666667 234.666667 0 84.252444-44.387556 158.136889-111.075556 199.523556a425.272889 425.272889 0 0 1 152.860445 83.456 32 32 0 0 1-41.500445 48.739555A361.045333 361.045333 0 0 0 512 565.333333c-188.672 0-345.429333 144.668444-361.344 331.420445a32 32 0 0 1-63.758222-5.432889c15.104-177.322667 138.040889-322.346667 301.539555-371.797333C321.763556 478.165333 277.333333 404.266667 277.333333 320 277.333333 190.392889 382.407111 85.333333 512 85.333333z m0 64c-94.264889 0-170.666667 76.401778-170.666667 170.666667s76.401778 170.666667 170.666667 170.666667 170.666667-76.401778 170.666667-170.666667-76.401778-170.666667-170.666667-170.666667z m143.36 587.946667a30.72 30.72 0 0 1 30.72-30.72H921.6a30.72 30.72 0 0 1 0 61.44H686.08a30.72 30.72 0 0 1-30.72-30.72z m30.72 71.68a30.72 30.72 0 0 0 0 61.44h174.08a30.72 30.72 0 0 0 0-61.44H686.08z" p-id="33852" fill="#888888"></path></svg><span>我的</span></div>
                        </div>
                    </div>
                    <div class="glist">
                        <div class="header2 bgcolor">
                            <div class="leftback"></div>
                            <div class="centit">所有商品</div>
                            <div class="rightcon"><img src="/index/static/images/sousuo1.png"></div>
                        </div>
                        <div class="bgcolor">
                            <div class="segment">
                                <div class="segview">
                                    <span class="fscolor">综合</span>
                                    <span>销量</span>
                                    <span>最新</span>
                                    <span>价格<img src="/index/static/images/down1.png"></span>
                                    <span>所有商品<img src="/index/static/images/down0.png"></span>
                                </div>
                                <div class="mescroll">
                                    <div><img src="/statics/uploads/shopimg/20250614/20257571918584.jpg">
                                        <div><p class="mui-ellipsis-2">(第1643期)绿源电动车72V20Ah超长续航电动摩托车</p>
                                            <div class="mescdpr">
                                                <div><span><font class="bg-jb3" style="width: 87.41%;"></font></span>
                                                    <div class="pfontbew">
                                                        <div class="fscolor">4895<span>已参与</span></div>
                                                        <div>5886<span>总需</span></div>
                                                        <div class="fscolor">991<span>剩余</span></div>
                                                    </div>
                                                </div>
                                                <img src="/index/static/images/icon_lucky_add_to_cart.png">
                                            </div>
                                        </div>
                                    </div>
                                    <div><img src="/statics/uploads/shopimg/20250614/75236298914524.jpg">
                                        <div><p class="mui-ellipsis-2">(第506期)外星人（Alienware）畅玩黑神话 m16 R2 16英寸游戏本酷睿Ultra 7...</p>
                                            <div class="mescdpr">
                                                <div><span><font class="bg-jb3" style="width: 78.55%;"></font></span>
                                                    <div class="pfontbew">
                                                        <div class="fscolor">13613<span>已参与</span></div>
                                                        <div>16898<span>总需</span></div>
                                                        <div class="fscolor">3285<span>剩余</span></div>
                                                    </div>
                                                </div>
                                                <img src="/index/static/images/icon_lucky_add_to_cart.png">
                                            </div>
                                        </div>
                                    </div>
                                    <div><img src="/statics/uploads/shopimg/20250614/23690720916007.jpg">
                                        <div><p class="mui-ellipsis-2">(第126期)元気森林 元气森林无糖元气水气泡水苏打水元气水汽水饮料组合 白桃...</p>
                                            <div class="mescdpr">
                                                <div><span><font class="bg-jb3" style="width: 62.17%;"></font></span>
                                                    <div class="pfontbew">
                                                        <div class="fscolor">179<span>已参与</span></div>
                                                        <div>248<span>总需</span></div>
                                                        <div class="fscolor">69<span>剩余</span></div>
                                                    </div>
                                                </div>
                                                <img src="/index/static/images/icon_lucky_add_to_cart.png">
                                            </div>
                                        </div>
                                    </div>
                                    <div><img src="/statics/uploads/shopimg/20250614/25296610913887.png">
                                        <div><p class="mui-ellipsis-2">(第1932期)中国石化 1000元汽油面值换购券 </p>
                                            <div class="mescdpr">
                                                <div><span><font class="bg-jb3" style="width: 52.2%;"></font></span>
                                                    <div class="pfontbew">
                                                        <div class="fscolor">622<span>已参与</span></div>
                                                        <div>1000<span>总需</span></div>
                                                        <div class="fscolor">378<span>剩余</span></div>
                                                    </div>
                                                </div>
                                                <img src="/index/static/images/icon_lucky_add_to_cart.png">
                                            </div>
                                        </div>
                                    </div>
                                    <div><img src="/statics/uploads/shopimg/20250614/87943448913775.jpg">
                                        <div><p class="mui-ellipsis-2">(第1948期)京东商城 福气卡换购卷500面值 </p>
                                            <div class="mescdpr">
                                                <div><span><font class="bg-jb3" style="width: 39.4%;"></font></span>
                                                    <div class="pfontbew">
                                                        <div class="fscolor">297<span>已参与</span></div>
                                                        <div>500<span>总需</span></div>
                                                        <div class="fscolor">203<span>剩余</span></div>
                                                    </div>
                                                </div>
                                                <img src="/index/static/images/icon_lucky_add_to_cart.png">
                                            </div>
                                        </div>
                                    </div>
                                    <div><img src="/statics/uploads/shopimg/20250614/99723724914944.jpg">
                                        <div><p class="mui-ellipsis-2">(第499期)小米电视 100英寸巨幕 240Hz高刷 澎湃OS 4+64GB 会议电视 Redm...</p>
                                            <div class="mescdpr">
                                                <div><span><font class="bg-jb3" style="width: 26.04%;"></font></span>
                                                    <div class="pfontbew">
                                                        <div class="fscolor">5783<span>已参与</span></div>
                                                        <div>10318<span>总需</span></div>
                                                        <div class="fscolor">4535<span>剩余</span></div>
                                                    </div>
                                                </div>
                                                <img src="/index/static/images/icon_lucky_add_to_cart.png">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <div class="footerflex"><svg t="1773126897428" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="9011" width="24" height="24"><path d="M885.76 584.517c-5.912-5.52-14.232-8.56-23.429-8.56-18.744 0-32.35 12.304-32.35 29.255v241.44c0 19.081-18.867 37.12-38.824 37.12H629.481c-18.708 0-32.286 12.302-32.286 29.255 0 16.952 13.578 29.256 32.286 29.256h161.676c57.014 0 103.398-42.9 103.398-95.63V605.213c0-8.167-3.04-15.325-8.795-20.695z" p-id="9012" fill="#888888"></path><path d="M1007.076 510.629L536.555 71.455c-3.809-3.565-13.593-8.567-23.47-8.567-9.844 0-19.654 5.016-23.476 8.576L19.085 510.61c-4.022 7.544-9.86 18.657-10.365 22.348 0.073 8.17 4.868 17.648 10.218 27.698 4.474 3.383 13.23 7.372 22.068 7.372 9.877 0 19.66-5.002 23.453-8.553l60.656-56.606v342.743c0 52.827 46.399 95.804 103.43 95.804h168.15c18.689 0 32.255-12.302 32.255-29.254V718.969h200.533c18.708 0 32.289-12.33 32.289-29.317 0-16.915-13.58-29.193-32.289-29.193h-232.79c-18.708 0-32.286 12.278-32.286 29.193v188.06h-129.42c-19.975 0-38.859-18.04-38.859-37.121V436.564l316.894-295.758L961.63 553.399c4.542 4.19 14.327 8.63 23.538 8.63 9.841 0 19.656-5.014 23.48-8.576 4.292-4 8.735-12.734 8.804-20.495-0.506-3.77-6.464-15.053-10.377-22.33z" p-id="9013" fill="#888888"></path></svg><span>首页</span></div>
                            <div class="footerflex"><svg class="fscolor" t="1773128006926" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="11239" width="24" height="24"><path d="M390.140093 66.514903 132.242372 66.514903c-38.551014 0-69.799716 31.248701-69.799716 69.787436l0 257.921257c0 38.539758 31.248701 69.789483 69.799716 69.789483l257.897721 0c38.549991 0 69.798692-31.249725 69.798692-69.789483L459.938786 136.302339C459.938786 97.763604 428.691107 66.514903 390.140093 66.514903zM410.252153 361.982288c0 28.906353-23.435759 52.343135-52.355415 52.343135L164.484703 414.325423c-28.919656 0-52.355415-23.436782-52.355415-52.343135l0-193.435571c0-28.9084 23.435759-52.343135 52.355415-52.343135l193.412035 0c28.919656 0 52.355415 23.435759 52.355415 52.343135L410.252153 361.982288z" p-id="11240" fill="#888888"></path><path d="M887.022791 66.514903 629.10051 66.514903c-38.537711 0-69.78539 31.248701-69.78539 69.787436l0 257.921257c0 38.539758 31.247678 69.789483 69.78539 69.789483l257.922281 0c38.539758 0 69.788459-31.249725 69.788459-69.789483L956.81125 136.302339C956.812273 97.763604 925.562549 66.514903 887.022791 66.514903zM907.123594 361.982288c0 28.906353-23.434735 52.343135-52.355415 52.343135L661.357168 414.325423c-28.92068 0-52.355415-23.436782-52.355415-52.343135l0-193.435571c0-28.9084 23.434735-52.343135 52.355415-52.343135l193.411011 0c28.92068 0 52.355415 23.435759 52.355415 52.343135L907.123594 361.982288z" p-id="11241" fill="#888888"></path><path d="M887.022791 563.386344 629.10051 563.386344c-38.537711 0-69.78539 31.248701-69.78539 69.787436l0 257.922281c0 38.539758 31.247678 69.788459 69.78539 69.788459l257.922281 0c38.539758 0 69.788459-31.248701 69.788459-69.788459L956.81125 633.17378C956.812273 594.635045 925.562549 563.386344 887.022791 563.386344zM907.123594 858.853729c0 28.906353-23.434735 52.343135-52.355415 52.343135L661.357168 911.196865c-28.92068 0-52.355415-23.436782-52.355415-52.343135L609.001753 665.418158c0-28.907377 23.434735-52.344159 52.355415-52.344159l193.411011 0c28.92068 0 52.355415 23.436782 52.355415 52.344159L907.123594 858.853729z" p-id="11242" fill="#888888"></path><path d="M391.086651 563.386344 131.295813 563.386344c-38.029128 0-68.853158 30.825053-68.853158 68.841901l0 259.814374c0 38.016849 30.825053 68.840878 68.853158 68.840878l259.790838 0c38.030152 0 68.853158-30.824029 68.853158-68.840878L459.939809 632.228245C459.938786 594.211397 429.11578 563.386344 391.086651 563.386344zM410.252153 858.853729c0 28.906353-23.435759 52.343135-52.355415 52.343135L164.484703 911.196865c-28.919656 0-52.355415-23.436782-52.355415-52.343135L112.129288 665.418158c0-28.907377 23.435759-52.344159 52.355415-52.344159l193.412035 0c28.919656 0 52.355415 23.436782 52.355415 52.344159L410.252153 858.853729z" p-id="11243" fill="#888888"></path></svg><span class="fscolor">所有商品</span></div>
                            <div class="footerflex"><img src="/index/static/images/3-002.png"><span>购物车</span></div>
                            <div class="footerflex"><img src="/index/static/images/4-002.png"><span>最新揭晓</span></div>
                            <div class="footerflex"><svg t="1773128195540" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="33851" width="24" height="24"><path d="M512 85.333333c129.607111 0 234.666667 105.059556 234.666667 234.666667 0 84.252444-44.387556 158.136889-111.075556 199.523556a425.272889 425.272889 0 0 1 152.860445 83.456 32 32 0 0 1-41.500445 48.739555A361.045333 361.045333 0 0 0 512 565.333333c-188.672 0-345.429333 144.668444-361.344 331.420445a32 32 0 0 1-63.758222-5.432889c15.104-177.322667 138.040889-322.346667 301.539555-371.797333C321.763556 478.165333 277.333333 404.266667 277.333333 320 277.333333 190.392889 382.407111 85.333333 512 85.333333z m0 64c-94.264889 0-170.666667 76.401778-170.666667 170.666667s76.401778 170.666667 170.666667 170.666667 170.666667-76.401778 170.666667-170.666667-76.401778-170.666667-170.666667-170.666667z m143.36 587.946667a30.72 30.72 0 0 1 30.72-30.72H921.6a30.72 30.72 0 0 1 0 61.44H686.08a30.72 30.72 0 0 1-30.72-30.72z m30.72 71.68a30.72 30.72 0 0 0 0 61.44h174.08a30.72 30.72 0 0 0 0-61.44H686.08z" p-id="33852" fill="#888888"></path></svg><span>我的</span></div>
                        </div>
                    </div>
                    <div class="users">
                        <div class="qb-hed bgcolor">
                            <p>
                                <img src="/statics/uploads/banner/20250326/96010175960503.png" style="margin: 0 15px 0 0 !important;">
                                <span style="margin: 0 !important;">登录/注册</span>
                            </p>
                            <div>
                                <span><img src="/index/static/images/icon_lucky_mine_collect.png">我的收藏</span>
                                <span><img src="/index/static/images/icon_lucky_mine_lucky_history.png">获得商品</span>
                                <span><img src="/index/static/images/icon_lucky_mine_my_goods.png">参与记录</span>
                            </div>
                        </div>
                        <div class="more-con">
                            <div>
                                <p>账户资产</p>
                                <div class="zhanghu">
                                    <div><span>—</span><p>佣金</p></div>
                                    <div><span>—</span><p>余额</p></div>
                                </div>
                                <div class="zichan">
                                    <div><img src="/index/static/images/icon_lucky_mine_transfer.png">返现转账<span></span></div>
                                    <div><img src="/index/static/images/icon_lucky_mine_credits_exchange.png">兑换余额<span></span></div>
                                </div>
                            </div>
                            <span>
                                <div>邀请返现</div>
                                <img src="/index/static/images/img_lucky_mine_invite.png">
                            </span>
                            <div>
                                <div class="gengduo">
                                    <div>
                                        <div style="margin: 0 !important;"><img src="/index/static/images/icon_lucky_mine_address_location.png">收货地址</div>
                                        <img src="/index/static/images/j-right.png" style="margin: 0 !important;">
                                    </div>
                                    <div>
                                        <div style="margin: 0 !important;"><img src="/index/static/images/icon_lucky_mine_earn_money.png">邀请返现</div>
                                        <img src="/index/static/images/j-right.png" style="margin: 0 !important;">
                                    </div>
                                    <div>
                                        <div style="margin: 0 !important;"><img src="/index/static/images/icon_lucky_mine_help.png">充值中心</div>
                                        <img src="/index/static/images/j-right.png" style="margin: 0 !important;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <div class="footerflex"><svg t="1773126897428" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="9011" width="24" height="24"><path d="M885.76 584.517c-5.912-5.52-14.232-8.56-23.429-8.56-18.744 0-32.35 12.304-32.35 29.255v241.44c0 19.081-18.867 37.12-38.824 37.12H629.481c-18.708 0-32.286 12.302-32.286 29.255 0 16.952 13.578 29.256 32.286 29.256h161.676c57.014 0 103.398-42.9 103.398-95.63V605.213c0-8.167-3.04-15.325-8.795-20.695z" p-id="9012" fill="#888888"></path><path d="M1007.076 510.629L536.555 71.455c-3.809-3.565-13.593-8.567-23.47-8.567-9.844 0-19.654 5.016-23.476 8.576L19.085 510.61c-4.022 7.544-9.86 18.657-10.365 22.348 0.073 8.17 4.868 17.648 10.218 27.698 4.474 3.383 13.23 7.372 22.068 7.372 9.877 0 19.66-5.002 23.453-8.553l60.656-56.606v342.743c0 52.827 46.399 95.804 103.43 95.804h168.15c18.689 0 32.255-12.302 32.255-29.254V718.969h200.533c18.708 0 32.289-12.33 32.289-29.317 0-16.915-13.58-29.193-32.289-29.193h-232.79c-18.708 0-32.286 12.278-32.286 29.193v188.06h-129.42c-19.975 0-38.859-18.04-38.859-37.121V436.564l316.894-295.758L961.63 553.399c4.542 4.19 14.327 8.63 23.538 8.63 9.841 0 19.656-5.014 23.48-8.576 4.292-4 8.735-12.734 8.804-20.495-0.506-3.77-6.464-15.053-10.377-22.33z" p-id="9013" fill="#888888"></path></svg><span>首页</span></div>
                            <div class="footerflex"><svg t="1773128006926" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="11239" width="24" height="24"><path d="M390.140093 66.514903 132.242372 66.514903c-38.551014 0-69.799716 31.248701-69.799716 69.787436l0 257.921257c0 38.539758 31.248701 69.789483 69.799716 69.789483l257.897721 0c38.549991 0 69.798692-31.249725 69.798692-69.789483L459.938786 136.302339C459.938786 97.763604 428.691107 66.514903 390.140093 66.514903zM410.252153 361.982288c0 28.906353-23.435759 52.343135-52.355415 52.343135L164.484703 414.325423c-28.919656 0-52.355415-23.436782-52.355415-52.343135l0-193.435571c0-28.9084 23.435759-52.343135 52.355415-52.343135l193.412035 0c28.919656 0 52.355415 23.435759 52.355415 52.343135L410.252153 361.982288z" p-id="11240" fill="#888888"></path><path d="M887.022791 66.514903 629.10051 66.514903c-38.537711 0-69.78539 31.248701-69.78539 69.787436l0 257.921257c0 38.539758 31.247678 69.789483 69.78539 69.789483l257.922281 0c38.539758 0 69.788459-31.249725 69.788459-69.789483L956.81125 136.302339C956.812273 97.763604 925.562549 66.514903 887.022791 66.514903zM907.123594 361.982288c0 28.906353-23.434735 52.343135-52.355415 52.343135L661.357168 414.325423c-28.92068 0-52.355415-23.436782-52.355415-52.343135l0-193.435571c0-28.9084 23.434735-52.343135 52.355415-52.343135l193.411011 0c28.92068 0 52.355415 23.435759 52.355415 52.343135L907.123594 361.982288z" p-id="11241" fill="#888888"></path><path d="M887.022791 563.386344 629.10051 563.386344c-38.537711 0-69.78539 31.248701-69.78539 69.787436l0 257.922281c0 38.539758 31.247678 69.788459 69.78539 69.788459l257.922281 0c38.539758 0 69.788459-31.248701 69.788459-69.788459L956.81125 633.17378C956.812273 594.635045 925.562549 563.386344 887.022791 563.386344zM907.123594 858.853729c0 28.906353-23.434735 52.343135-52.355415 52.343135L661.357168 911.196865c-28.92068 0-52.355415-23.436782-52.355415-52.343135L609.001753 665.418158c0-28.907377 23.434735-52.344159 52.355415-52.344159l193.411011 0c28.92068 0 52.355415 23.436782 52.355415 52.344159L907.123594 858.853729z" p-id="11242" fill="#888888"></path><path d="M391.086651 563.386344 131.295813 563.386344c-38.029128 0-68.853158 30.825053-68.853158 68.841901l0 259.814374c0 38.016849 30.825053 68.840878 68.853158 68.840878l259.790838 0c38.030152 0 68.853158-30.824029 68.853158-68.840878L459.939809 632.228245C459.938786 594.211397 429.11578 563.386344 391.086651 563.386344zM410.252153 858.853729c0 28.906353-23.435759 52.343135-52.355415 52.343135L164.484703 911.196865c-28.919656 0-52.355415-23.436782-52.355415-52.343135L112.129288 665.418158c0-28.907377 23.435759-52.344159 52.355415-52.344159l193.412035 0c28.919656 0 52.355415 23.436782 52.355415 52.344159L410.252153 858.853729z" p-id="11243" fill="#888888"></path></svg><span>所有商品</span></div>
                            <div class="footerflex"><img src="/index/static/images/3-002.png"><span>购物车</span></div>
                            <div class="footerflex"><img src="/index/static/images/4-002.png"><span>最新揭晓</span></div>
                            <div class="footerflex"><svg class="fscolor" t="1773128195540" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="33851" width="24" height="24"><path d="M512 85.333333c129.607111 0 234.666667 105.059556 234.666667 234.666667 0 84.252444-44.387556 158.136889-111.075556 199.523556a425.272889 425.272889 0 0 1 152.860445 83.456 32 32 0 0 1-41.500445 48.739555A361.045333 361.045333 0 0 0 512 565.333333c-188.672 0-345.429333 144.668444-361.344 331.420445a32 32 0 0 1-63.758222-5.432889c15.104-177.322667 138.040889-322.346667 301.539555-371.797333C321.763556 478.165333 277.333333 404.266667 277.333333 320 277.333333 190.392889 382.407111 85.333333 512 85.333333z m0 64c-94.264889 0-170.666667 76.401778-170.666667 170.666667s76.401778 170.666667 170.666667 170.666667 170.666667-76.401778 170.666667-170.666667-76.401778-170.666667-170.666667-170.666667z m143.36 587.946667a30.72 30.72 0 0 1 30.72-30.72H921.6a30.72 30.72 0 0 1 0 61.44H686.08a30.72 30.72 0 0 1-30.72-30.72z m30.72 71.68a30.72 30.72 0 0 0 0 61.44h174.08a30.72 30.72 0 0 0 0-61.44H686.08z" p-id="33852" fill="#888888"></path></svg><span class="fscolor">我的</span></div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><input type="submit" class="button" name="dosubmit"  value=" <?php $this->lan("Submit");?> " ></td>
            </tr>
        </table>
    </form>
</div><!--table-list end-->

<script>
    $(".formborcol").click(function(){
        $('.formborcol').removeClass('active')
        $(this).addClass('active')
        var colorbg1 = $(this).find('div:nth-child(1)').css('backgroundColor');
        var colorbg2 = $(this).find('div:nth-child(2)').css('backgroundColor');
        var colorbg3 = $(".bg-jb3").css('background');
        $(".bgcolor").css("backgroundColor", colorbg1);
        $(".fscolor").css("color", colorbg1);
        $(".fscolor path").css("fill", colorbg1);
        $(".bg-jb3").css("background", `-webkit-linear-gradient(left, ${colorbg2}, ${colorbg1})`);
        var colorbg4 = $(".bg-jb3").css('background');
        if (colorbg4==colorbg3){
            $(".bg-jb3").css("background", `-o-linear-gradient(left, ${colorbg2}, ${colorbg1})`);
            colorbg4 = $(".bg-jb3").css('background');
            if (colorbg4==colorbg3){
                $(".bg-jb3").css("background", `-moz-linear-gradient(left, ${colorbg2}, ${colorbg1})`);
                colorbg4 = $(".bg-jb3").css('background');
                if (colorbg4==colorbg3){
                    $(".bg-jb3").css("background", `linear-gradient(left, ${colorbg2}, ${colorbg1})`);
                    colorbg4 = $(".bg-jb3").css('background');
                }
            }
        }
    })
    $("#addbanner").on('click',function (){
        var id = diu_Randomize(1000,100000)
        console.log(id)
        var html = `
             <div style="display: inline-block;margin-right: 5px;">
                        <img  onclick="uploadimg('上传商品图片','#imgHeadPhoto${id}','image','banner')" id="imgHeadPhoto${id}-img" width="130px" height="130px" src="/statics/uploads/admanage/mr.jpg">
                         <input type="hidden" name="banner[]" id="imgHeadPhoto${id}-lack" value="">
              </div>
        `
		var bb = $("#lunbo>div:nth-last-child(1)")
		bb.before(html)
        // $("#lunbo").append(html)

        setTimeout(()=>{
            uploadimg('上传商品图片','#imgHeadPhoto'+id,'image','banner')
        },100)
    })
    function  diu_Randomize(b,e){
        if (!b && b!=0 || !e){ return  "?" ;}
        return  Math.floor( ( Math.random() * e ) + b );
    }
    $('.radioval').click(function(){
        var tval = $(this).attr('value');
        if(tval==0) {
            $('.sign').css('display','none')
            $('.sign_phone').css('display','none')
        } else {
            if($("#web_verify_mode option:selected").val() == 2) {
                $('.sign_phone').css('display','table-row')
            }
            $('.sign').css('display','table-row')

        }
    })
    $('#web_verify_mode').on("change",function(){
        var tval = $(this).attr('value');
        if ($("option:selected",this).val() == 2) {
            $('.sign_phone').css('display','table-row')
        } else {
            $('.sign_phone').css('display','none')
        }
    })
    if($("#web_verify_mode option:selected").val()!=2) {
        $('.sign_phone').css('display','none')
    }
</script>
<script>
chushicolor();
setTimeout(function(){
    chushicolor();
},4000);
function chushicolor() {
    var noborcol1 = 'rgb(255,255,255)'
    var noborcol2 = 'rgb(255,255,255)'
    layui.use(['colorpicker'], function(){
        var olorpicker = layui.colorpicker;
        olorpicker.render({
            elem: "#test-all<?php echo $key; ?>"
            ,color: "<?php echo $temp['color']; ?>"
            ,format: 'hex'
            ,done: function(color){
                noborcol1 = color
                changecolor();
                // $.post('<?php echo G_MODULE_PATH; ?>/template/editColor/<?php echo $key; ?>',{color:color,dosubmit:"true",type:1},function (res) {
                //     var result = JSON.parse(res)
                //     if (result.code ==1){
                //         $('.testall').removeClass('actvicol')
                //         $('#test-all<?php echo $key; ?>').addClass('actvicol')
                //         layer.msg('修改成功')
                //         return
                //     }
                //     layer.msg('修改失败')
                // })
            }
        });
    });
    layui.use(['colorpicker'], function(){
        var olorpicker1 = layui.colorpicker;
        olorpicker1.render({
            elem: "#test-all2<?php echo $key; ?>"
            ,color: "<?php echo $temp['color1']; ?>"
            ,format: 'hex'
            ,done: function(color){
                noborcol2 = color
                changecolor();
                // $.post('<?php echo G_MODULE_PATH; ?>/template/editColor/<?php echo $key; ?>',{color:color,dosubmit:"true",type:2},function (res) {
                //     var result = JSON.parse(res)
                //     if (result.code ==1){
                //         $('.testall2').removeClass('actvicol')
                //         $('#test-all2<?php echo $key; ?>').addClass('actvicol')
                //         layer.msg('修改成功')
                //         return
                //     }
                //     layer.msg('修改失败')
                // })
            }
        });
    });
    function changecolor() {
        var colorbg3 = $(".bg-jb3").css('background');
        $(".bgcolor").css("backgroundColor", noborcol1);
        $(".fscolor").css("color", noborcol1);
        $(".bg-jb3").css("background", `-webkit-linear-gradient(left, ${noborcol2}, ${noborcol1})`);
        var colorbg4 = $(".bg-jb3").css('background');
        if (colorbg4==colorbg3){
            $(".bg-jb3").css("background", `-o-linear-gradient(left, ${noborcol2}, ${noborcol1})`);
            colorbg4 = $(".bg-jb3").css('background');
            if (colorbg4==colorbg3){
                $(".bg-jb3").css("background", `-moz-linear-gradient(left, ${noborcol2}, ${noborcol1})`);
                colorbg4 = $(".bg-jb3").css('background');
                if (colorbg4==colorbg3){
                    $(".bg-jb3").css("background", `linear-gradient(left, ${noborcol2}, ${noborcol1})`);
                    colorbg4 = $(".bg-jb3").css('background');
                }
            }
        }
    }
}
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>