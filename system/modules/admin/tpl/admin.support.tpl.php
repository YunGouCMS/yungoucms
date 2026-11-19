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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<style>
	body{ background-color:#fefeff; font:12px/1.5 arial,宋体b8b\4f53,sans-serif; overflow:hidden}
	hr {width: 70px;height: 3px;background-color: #FFF;text-align: left;}
	.title {font-family: 黑体, 微软雅黑, Verdana;text-align: left;text-transform: uppercase;font-size: 18px;margin: 4% 0;line-height: normal;letter-spacing: 2px; color:#FFF}
	#contact {background-color:#233e5c;overflow:hidden;font-family:Knockout-light;position:relative;}
	#contact .text .follow a {color:#fff;}
	#contact .text .follow:hover a {color:#242739;} 
	#contact .content .text {width: 90%;}
	#contact .content {margin: auto;width: 80%;}
	#contact  .articles {width:auto; float:left; clear:left;}
	#contact  .articles img{ display:block;float: left;}
	#contact .content span.title {float:left;margin: 1% 0;}
	#contact ul{margin:0; margin-top:5px; padding:0; list-style:none; background-color:#FFF;}
	.posttitle{ background-color:#dce0e5;height:35px; font-size:12px; color:#666; margin-top:0px; padding-top:15px; padding-left:20px; overflow:hidden;}
	ul .posttitle strong {color:#233e5c; font-size:16px;}
	ul li{ clear:left; margin-top:10px; line-height:18px; color:#233e5c; padding-left:20px;}
	.partenaires{float:left; width:20%;; margin-left:10%;}
	*+html #contact .partenaires{float:left; width:50%; margin-left:40%; margin-top:6%; overflow:hidden;}
	#contact .partenaires ul{background:url('222.png') no-repeat; height:204px; width:640px; padding-top:52px;}
	.partenaires ul li{color:#FFF; font-size:14px; margin-left:20px; height:35px; margin-top:10px;}
	.partenaires img{margin-top:0px;}
</style>
</head>

<body>

<div id="contact" style="height: 889px; ">
                <div class="content" style="padding-top: 26.669999999999998px; ">
                    <hr>

                    <div class="text">
                        <span class="title">
                        版权所有：Copyright © 2011 - <?php echo date('Y')?>重庆韬龙网络科技有限公司</span>
                        <div class="articles clearfix">
                           <figure class="articles_f1"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/register.jpg" width="400" height="299" sizes="(max-width: 400px) 100vw, 299px"></figure>
                           <figure class="articles_f2"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/copyright.jpg" width="200" height="273" sizes="(max-width: 2008px) 100vw, 273px"></figure>
                           <figure class="articles_f3"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/ruanjian.jpg" width="200" height="273"></figure>
                        </div>
                        <div class="partenaires">
                            <ul>
                                <li>客服电话：<span style="color:#FFF">023-67898642</span></li>
                                <li>客服QQ：<span style="color:#FFF">1274117743</span></li>
                                <li>韬龙系统：<span><a href="https://taolong.com" target="_blank" style="color:#FFF">https://taolong.com</a></span></li>
								<li>云购系统：<span><a href="https://www.yungoucms.com" target="_blank" style="color:#FFF">https://www.yungoucms.com</a></span></li>
                                <li>公司地址：重庆市渝中区解放碑街道民生路232号9-1</li>
<!--								<li>开发团队：<span style="color:#FFF">司马懿 赵子龙 张飞 郭嘉 小乔 大乔 黄忠 步婉 姜维 巴蜀 甘宁 Mr.Li Mr.Liao Ms.Tang Ms.Cheng ...</span></li>-->
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html> 
