<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $this->getPageTitle('后台页面'); ?></title>
    <!-- 高级骨架屏系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <!-- 统一后台页面美化样式（参考webcfg标准） -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/main_default.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <style>
        body {
            background-color: #fefeff;
            font: 12px/1.5 arial, 宋体b8b\4f53, sans-serif;
        }

        .width30 {
            width: 30%;
            font-size: 12px;
            border-radius: 5px 2px 20px 2px;
        }

        .title {
            font-size: 15px;
            font-weight: bold;
            color: #444;
            line-height: 30px;
            border-bottom: 1px solid #ccc;
        }
        /* 平台系统信息模块 - 三栏卡片美化 */
        .platform-system-wrap { margin-bottom: 24px; }
        .platform-system-wrap .main_title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
            letter-spacing: 0.02em;
        }
        .platform-system-wrap .platform-cols {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            overflow: hidden;
        }
        .platform-system-wrap .div-system,
        .platform-system-wrap .div-webinfo,
        .platform-system-wrap .div-about {
            background: #fff;
            border-radius: 12px;
            padding: 20px 24px;
            margin: 0;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            box-sizing: border-box;
            flex: 1;
            min-width: 280px;
            transition: box-shadow 0.25s ease, transform 0.25s ease;
        }
        .platform-system-wrap .div-system:hover,
        .platform-system-wrap .div-webinfo:hover,
        .platform-system-wrap .div-about:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .platform-system-wrap .div-system .title,
        .platform-system-wrap .div-webinfo .title,
        .platform-system-wrap .div-about .title {
            font-size: 15px;
            font-weight: 600;
            color: #374151;
            line-height: 32px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .platform-system-wrap .div-system ul,
        .platform-system-wrap .div-webinfo ul,
        .platform-system-wrap .div-about ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .platform-system-wrap .div-system ul li,
        .platform-system-wrap .div-about ul li {
            height: 34px;
            line-height: 34px;
            color: #4b5563;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }
        .platform-system-wrap .div-system ul li:last-child,
        .platform-system-wrap .div-about ul li:last-child {
            border-bottom: none;
        }
        .platform-system-wrap .div-system ul li i,
        .platform-system-wrap .div-about ul li i {
            display: inline-block;
            color: #6b7280;
            min-width: 90px;
            margin-right: 8px;
            font-style: normal;
            font-weight: 500;
        }
        .platform-system-wrap .div-webinfo ul li {
            height: auto;
            min-height: 32px;
            line-height: 1.5;
            color: #4b5563;
            border-bottom: 1px solid #f3f4f6;
            padding: 6px 0;
            font-size: 13px;
        }
        .platform-system-wrap .div-webinfo ul li:last-child { border-bottom: none; }
        .platform-system-wrap .div-webinfo ul li i {
            display: inline-block;
            color: #6b7280;
            margin-right: 6px;
            font-style: normal;
            font-weight: 500;
        }
        .platform-system-wrap .div-webinfo .job {
            border-bottom: none;
            padding-bottom: 0;
        }
        .platform-system-wrap .div-webinfo .job .right img {
            border-radius: 8px;
            max-width: 64px;
            height: auto;
        }
        .platform-system-wrap .div-about {
            max-height: none;
        }

        .div-news {
            height: 50px;
            background-color: #fff
        }

        .div-user span {
            display: block;
            font-size: 12px;
            font: 12px/1.5 arial, 宋体b8b\4f53, sans-serif;
            line-height: 20px;
            color: #999
        }

        .div-user {
            background-color: #fff;
            padding: 20px;
            width: 30%;
            float: left;
            border-bottom: 1px solid #eee
        }

        .div-system {
            background-color: #fff;
            min-width: 475px;
            padding: 20px;
            margin: 0 10px;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 5px #888;
            box-sizing: border-box;
        }

        .div-webinfo {
            background-color: #fff;
            min-width: 475px;
            padding: 20px;
            margin: 0 11px;
            width: 27%;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 5px #888;
            box-sizing: border-box;
        }

        .div-about {
            background-color: #fff;
            padding: 20px;
            margin: 0 10px;
            overflow: hidden;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 5px #888;
            box-sizing: border-box;
            min-width: calc(100% - 1015px);
            max-height: 336px;
        }

        li {
            font: 12px/1.5 arial, 宋体b8b\4f53, sans-serif;
        }

        .div-system ul li {
            height: 30px;
            line-height: 30px;
            color: #6b6b6b;
            border-bottom: 1px dashed #ddd;
        }

        .div-system ul li i {
            width: 100px;
            height: 30px;
            line-height: 30px;
            display: inline-block;
            color: #666;
            text-align: right;
            padding-right: 15px;
        }

        .div-about ul li {
            height: 30px;
            line-height: 30px;
            color: #6b6b6b;
            border-bottom: 1px dashed #ddd;
        }

        .div-about ul li i {
            height: 30px;
            line-height: 30px;
            display: inline-block;
            color: #666;
        }

        .div-webinfo ul li {
            height: 30px;
            line-height: 30px;
            color: #6b6b6b;
            border-bottom: 1px dashed #ddd;
        }

        .div-webinfo ul li i {
            height: 30px;
            line-height: 30px;
            display: inline-block;
            color: #666;
        }

        .job {
            width: 100%;
            float: left;
            padding-bottom: 0px;
            border-bottom: 1px dashed #DDD;
        }

        .job .left {
            width: 400px;
            float: left;
            color: #3C8DBC;
        }

        .job .right {
            width: 100px;
            float: left;
        }

        .job span {
            font-weight: 700;
            font-size: 14px;
        }

        /*.layui-carousel[lay-anim=updown]>[carousel-item]>.layui-this {*/
        /*    background: #8ba9c0;*/
        /*}*/

        /*.layui-carousel>[carousel-item] {*/
        /*    text-align: left;*/
        /*}*/

        /*.layui-carousel[lay-anim=updown]>[carousel-item]>*,*/
        /*.layui-carousel[lay-anim=fade]>[carousel-item]>* {*/
        /*    background: #8ba9c0;*/
        /*}*/

        /* 今日/近7日/近1月 与 余额/佣金/积分 同一行 */
        .stats-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin: 0 15px 12px 0;
            padding: 10px 0;
        }
        .stats-toolbar .totalBox {
            margin: 0;
            padding: 0;
        }
        .totalBox {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin: 0 15px 12px 0;
            padding: 10px 0;
        }
        .totalBox span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 13px;
            color: #475569;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            white-space: nowrap;
        }
        .totalBox span:first-child { border-left: 3px solid #3b82f6; }
        .totalBox span:nth-child(2) { border-left: 3px solid #10b981; }
        .totalBox span:nth-child(3) { border-left: 3px solid #f59e0b; }

        /* 云购官网/热线/QQ 客服文案美化 */
        .official-contact-bar {
            display: flex;
            align-items: center;
            gap: 0;
            flex-wrap: wrap;
            font-size: 13px;
            color: #64748b;
        }
        .official-contact-bar .contact-item {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-left: 10px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: color 0.2s, border-color 0.2s, box-shadow 0.2s;
        }
        .official-contact-bar .contact-item:first-child { margin-left: 0; }
        .official-contact-bar .contact-item:hover { box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15); border-color: #c7d2fe; }
        .official-contact-bar .contact-item .label {
            color: #64748b;
            margin-right: 6px;
            font-weight: 500;
        }
        .official-contact-bar .contact-item a {
            color: #3c8dbc;
            text-decoration: none;
            font-weight: 600;
        }
        .official-contact-bar .contact-item a:hover {
            color: #2563eb;
            text-decoration: underline;
        }
        .notify_box,.shijiesj,.crypto-section,.platform-system-wrap,.stats-toolbar {
            margin-left: 40px!important;
            width: calc(90% + 34px);
        }
        .notify_box {
            position: relative;
        }
        .shijiesj {
            display: flex;
            justify-content: center;
            justify-content: space-between;
            
        }

        .shijianlist {
            width: calc((100% - 67px) / 4);
            overflow: hidden;
            margin-left: 20px;
            background-color: #67809F;
            border-radius: 4px;
        }

        /* .shijianlist:first-child {
            background-color: #cb5a5e;
            margin-left: 0;
        } */

        /* .shijianlist:first-child .shilibo {
            background-color: #c64a4f;
        } */

        .shilito {
            color: #ffffff;
            text-align: right;
            padding: 25px 15px 10px 0;
            background: url('<?php echo G_WEB_PATH ?>/statics/plugin/style/global/image/sjshijiesj.png') -25px 15px no-repeat;
        }

        /* .shijianlist:first-child .shilito {
            background: url(<?php echo G_WEB_PATH ?>/statics/plugin/style/global/image/sjshizhong.png) -25px 15px no-repeat;
        } */
        .shijian-sel .shilito{
            background: url('<?php echo G_WEB_PATH ?>/statics/plugin/style/global/image/sjshizhong.png') -25px 15px no-repeat;
        }
        .shijian-sel {
            background-color: #cb5a5e;
            margin-left: 0;
        }
        .shijian-sel .shilibo{
            background-color: #c64a4f;
        }

        .litosu {
            font-size: 13px;
        }

        .litotu {
            font-size: 34px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .litotu div {
            font-size: 11px;
            padding: 1px 6px;
            border-radius: 12px;
            background-color: rgba(255, 255, 255, .7);
            color: #333;
            margin: 5px 0 0 8px;
        }

        .shilibo {
            display: flex;
            font-size: 13px;
            justify-content: space-between;
            padding: 6px 10px;
            background-color: #5e7694;
        }

        .libole {
            color: #333;
            background-color: rgba(255, 255, 255, .7);
            padding: 3px 6px;
            font-size: 12px;
            border-radius: 5px;
        }

        .libori img {
            width: 22px;
            cursor: pointer;
        }

        .qh3brq {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0;
            margin: 0;
        }

        .qh3brq .brqd {
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border: 1px solid #cbd5e1;
            cursor: pointer;
            transition: color 0.2s, background 0.2s, border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .qh3brq .brqd:hover {
            color: #334155;
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            border-color: #94a3b8;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        .qh3brq .brqd.xzdqdiv {
            color: #fff;
            background: linear-gradient(135deg, #3C8DBC 0%, #2980b9 100%);
            border-color: #2471a3;
            box-shadow: 0 2px 6px rgba(59, 141, 188, 0.35);
        }
        .loader::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 48.5%;
            width: 50px;
            height: 50px;
            transform: translate(-50%, -50%);
            border: 5px solid #f3f3f3;
            border-top: 5px solid #999;
            border-radius: 50%;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #test1 {
            width: auto !important;
            height: 30px;
            overflow: hidden;
        }
        
        
        /*测试1*/
         .carousel {
      overflow: hidden;
      /* 隐藏超出容器的部分 */
      width: 160px;
      height: 30px;
      /* 设置轮播图的宽度 */
      font-size: 12px;
      position: relative;
      color: red;
      z-index: 1000;
      color: #333;
    }

    .carousel-images div {
      width: 100%;
      /* 确保图片宽度是100% */
      display: none;
      /* 默认隐藏所有图片 */
    }

    .carousel-images div:first-child {
      display: block;
      /* 显示第一个图片 */
    }
    
    /*测试2*/
     #carousel {
      width: 50%;
      max-width: 100%;
      box-sizing: border-box;
    }

    /* 顶部公告/提示文案美化（含「强烈建议开启后台谷歌验证码！」等） */
    .notice-carousel-wrap {
      display: flex;
      align-items: center;
      min-height: 36px;
      width: 50%;
      max-width: 100%;
      box-sizing: border-box;
      padding: 0 14px;
      background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
      border: 1px solid #bfdbfe;
      border-radius: 10px;
      box-shadow: 0 1px 3px rgba(59, 130, 246, 0.08);
    }
    .notice-carousel-wrap [carousel-item] {
      display: flex;
      align-items: center;
      gap: 8px;
      min-height: 36px;
      padding: 6px 0;
      font-size: 13px;
      color: #fe8487;
      line-height: 1.5;
      font-weight: 500;
    }
    .notice-carousel-wrap [carousel-item] img {
      flex-shrink: 0;
      opacity: 0.9;
    }
    .notice-carousel-wrap .notice-text {
      letter-spacing: 0.02em;
      color: #fe8487;
    }

    /* 今日/本周/本月运营数据文案美化 */
    .notify_box table { width: 100%; border-collapse: separate; border-spacing: 14px 0; }
    .notify_box table > tr > td { padding: 0; width: 25%; vertical-align: top; }
    .notify_box table td.order_box table,
    .notify_box table td.user_box table,
    .notify_box table td.tuan_box table,
    .notify_box table td.version_box table {
        width: 100%;
        min-height: 160px;
        padding: 18px 16px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: none;
        box-sizing: border-box;
    }
    .notify_box table td.order_box table { background: linear-gradient(160deg, #f97316 0%, #ea580c 100%); color: #fff; }
    .notify_box table td.user_box table { background: linear-gradient(160deg, #22c55e 0%, #16a34a 100%); color: #fff; }
    .notify_box table td.tuan_box table { background: linear-gradient(160deg, #8b5cf6 0%, #7c3aed 100%); color: #fff; }
    .notify_box table td.version_box table { background: linear-gradient(160deg, #3b82f6 0%, #2563eb 100%); color: #fff; }
    .notify_box table td table td {
        vertical-align: middle;
        padding: 10px 0;
        line-height: 26px;
        font-size: 13px;
        color: rgba(255,255,255,0.95);
    }
    .notify_box table td table td span[class^="statistics_"] {
        font-weight: 700;
        font-size: 14px;
        color: #fff;
        margin-left: 4px;
    }
    .notify_box table td table a { color: #fff; text-decoration: none; }

    /* 近7日数据统计走势图 容器 */
    .trend-chart-wrap {
        background: linear-gradient(180deg, #fafbfc 0%, #f8fafc 100%);
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    </style>
</head>

<body>
    <div class="main">
        <div class="main_title" style="padding: 5px 0;">
            <!-- <div class="carousel" id="test1" style="background:#8ba9c0">-->
            <!--      <div class="carousel-images">-->
            <!--         <?php foreach ($notices as $notice) { ?>-->
            <!--            <div carousel-item><img src="./statics/uploads/imgs/false.png" width="12" height="12" alt=""> <?php echo $notice; ?></div>-->
            <!--        <?php } ?> -->
            <!--     </div>-->
            <!--</div>-->

             <div style="margin-left:40px; width: calc(90% + 34px);display: flex;justify-content: space-between;align-items: center;">
                 <div id="carousel" class="notice-carousel-wrap" style="overflow: hidden; height: 44px; position: relative; text-align: left;width: calc(100% / 4 - 13px);">
                 <div id="carousel-items" class="notice-carousel-items">
                      <?php if (!isset($notices) || !is_array($notices)) { $notices = []; } foreach ($notices as $notice) { ?>
                      <div carousel-item><img src="./statics/uploads/imgs/false.png" width="14" height="14" alt=""> <span class="notice-text"><?php echo $notice; ?></span></div>
                      <?php } ?>
                </div>
             </div>
            
          
            

            <div class="official-contact-bar" style="<?php if ($backend_copyright != 1) {
                            echo 'display: none;';
                        } ?>">
                <span class="contact-item"><span class="label"><?php $this->lan("Official_website"); ?></span><a href="https://www.yungoucms.com/" target="_blank" rel="noopener">www.yungoucms.com</a></span>
                <span class="contact-item"><span class="label"><?php $this->lan("tel"); ?></span><a href="tel:023-67898642">023-67898642</a></span>
                <span class="contact-item"><span class="label"><?php $this->lan("QQ_Customer_Service"); ?></span><a href="https://wpa.qq.com/msgrd?v=3&uin=1274117743&site=qq&menu=yes" target="_blank" rel="noopener">1274117743</a></span>
            </div>
             </div>
        </div>
        <div class="stats-toolbar">
            <div class="qh3brq">
                <div class="brqd xzdqdiv load" attr-id='1'><?php $this->lan("today"); ?></div>
                <div class="brqd" attr-id='7'><?php $this->lan("week"); ?></div>
                <div class="brqd" attr-id='30'><?php $this->lan("month"); ?></div>
            </div>
            <div class="totalBox">
                <span><?php $this->lan("Current_balance_remaining"); ?> <?php echo $statistics_now['now_money']; ?></span>
                <span><?php $this->lan("Current_commission_balance"); ?> <?php echo $statistics_now['now_commission']; ?></span>
                <span><?php $this->lan("Current_points_remaining"); ?> <?php echo $statistics_now['now_score']; ?></span>
            </div>
        </div>
        <div class="notify_box notibox1">
            <table>
                <tr>
                    <td class="order_box">
                        <table>
                            <tr>
                                <td>
                                    <?php $this->lan("Today_Register"); ?> <span class="statistics_zs"></span> <br />
                                    <?php $this->lan("Today_promotion"); ?> <span class="statistics_com_tg"></span> <br />
                                    <!--今日三级返佣 <?php echo $statistics[0]['com_sj']??0; ?> (定制)<br />-->
                                    <?php $this->lan("Today_Withdraw"); ?> <span class="statistics_ts"></span> <br />
                                    <!--今日股东分红 <?php echo $statistics[0]['fh']??0; ?> (定制)<br />-->
                                    <?php $this->lan("Today_points_add"); ?> <span class="statistics_score_add"></span> <br />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="user_box">
                        <table>
                            <tr>
                                <td>
                                    <?php $this->lan("Today_balance"); ?> <span class="statistics_use_money"></span> <br />
                                    <?php $this->lan("Today_Recharge"); ?> <span class="statistics_rechange"></span> <br />
                                    <?php $this->lan("Today_Exchange"); ?> <span class="statistics_exchange"></span> <br />
                                    <!--今日投资收入 <?php echo $statistics[0]['invest_income']??0; ?> (定制) <br />-->
                                    <?php $this->lan("Today_points"); ?> <span class="statistics_use_score"></span> <br />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="tuan_box">
                        <table>
                            <tr>
                                <td>
                                    <!--当前商品总数 <?php echo $statistics[0]['goods_total']; ?><br />-->
                                    <?php $this->lan("Today_order_add"); ?> <span class="statistics_orders_num"></span> <br />
                                    <?php $this->lan("Today_order_deliver"); ?> <span class="statistics_orders_fh_num"></span> <br />
                                    <?php $this->lan("Today_order_wait"); ?> <span class="statistics_orders_df_num"></span> <br />
                                    <?php $this->lan("Today_order_convert"); ?> <span class="statistics_orders_exc_num"></span> <br />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="version_box">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php $this->lan("Total_number_users"); ?> <span class="statistics_user_total"></span> <br />
                                        <?php $this->lan("New_additions_today/yesterday"); ?> <span class="statistics_user_num"></span> <br />
                                        <!--<?php echo $statistics[0]['yesterday_user_num']??0; ?>-->
                                        <?php $this->lan("Today_user_Recharge"); ?> <span class="statistics_user_rechange_num"></span> <br />
                                        <?php $this->lan("Today_user_convert"); ?> <span class="statistics_user_exc_num"></span> <br />
                                        <!--今日股东增加/退出 <?php echo $statistics[0]['user_invest_add']??0; ?>/<?php echo $statistics[0]['user_invest_quit']??0; ?> (定制) <br />-->
                                        <div id="version_tip"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div class="notify_box notibox7" style="display:none;">
            <table>
                <tr>
                    <td class="order_box">
                        <table>
                            <tr>
                                <td>
                                    <?php $this->lan("Week_register"); ?> <span class="statistics_zs"></span> <br />
                                    <?php $this->lan("Week_promotion"); ?> <span class="statistics_com_tg"></span> <br />
                                    <!--今日三级返佣 <?php echo $statistics[0]['com_sj']??0; ?> (定制)<br />-->
                                    <?php $this->lan("Week_Withdraw"); ?> <span class="statistics_ts"></span> <br />
                                    <!--今日股东分红 <?php echo $statistics[0]['fh']??0; ?> (定制)<br />-->
                                    <?php $this->lan("Week_points_add"); ?> <span class="statistics_score_add"></span> <br />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="user_box">
                        <table>
                            <tr>
                                <td>
                                    <?php $this->lan("Week_balance"); ?> <span class="statistics_use_money"></span> <br />
                                    <?php $this->lan("Week_Recharge"); ?> <span class="statistics_rechange"></span> <br />
                                    <?php $this->lan("Week_Exchange"); ?> <span class="statistics_exchange"></span> <br />
                                    <!--今日投资收入 <?php echo $statistics[0]['invest_income']??0;; ?> (定制) <br />-->
                                    <?php $this->lan("Week_points"); ?> <span class="statistics_use_score"></span> <br />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="tuan_box">
                        <table>
                            <tr>
                                <td>
                                    <!--当前商品总数 <?php echo $statistics[0]['goods_total']??0; ?><br />-->
                                    <?php $this->lan("Week_order_add"); ?> <span class="statistics_orders_num"></span> <br />
                                    <?php $this->lan("Week_order_deliver"); ?> <span class="statistics_orders_fh_num"></span> <br />
                                    <?php $this->lan("Week_order_wait"); ?> <span class="statistics_orders_df_num"></span> <br />
                                    <?php $this->lan("Week_order_convert"); ?> <span class="statistics_orders_exc_num"></span> <br />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="version_box">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php $this->lan("Total_number_users"); ?> <span class="statistics_user_total"></span> <br />
                                        <!--<?php echo $statistics[0]['yesterday_user_num']??0; ?>-->
                                        <?php $this->lan("Week_user_add"); ?> <span class="statistics_user_num"></span> <br />
                                        <?php $this->lan("Week_user_recharge"); ?> <span class="statistics_user_rechange_num"></span> <br />
                                        <?php $this->lan("Week_user_exchange"); ?> <span class="statistics_user_exc_num"></span> <br />
                                        <!--近7日股东增加/退出 <?php echo $statistics[1]['user_invest_add']??0; ?>/<?php echo $statistics[1]['user_invest_quit']??0; ?> (定制) <br />-->
                                        <div id="version_tip"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div class="notify_box notibox30" style="display:none;">
            <table>
                <tr>
                    <td class="order_box">
                        <table>
                            <tr>
                                <td>
                                    <?php $this->lan("Month_register"); ?> <span class="statistics_zs"></span> <br />
                                    <?php $this->lan("Month_promotion"); ?> <span class="statistics_com_tg"></span> <br />
                                    <!--今日三级返佣 <?php echo $statistics[0]['com_sj']??0; ?> (定制)<br />-->
                                    <?php $this->lan("Month_Withdraw"); ?> <span class="statistics_ts"></span> <br />
                                    <!--今日股东分红 <?php echo $statistics[0]['fh']??0; ?> (定制)<br />-->
                                    <?php $this->lan("Month_points_add"); ?> <span class="statistics_score_add"></span> <br />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="user_box">
                        <table>
                            <tr>
                                <td>
                                    <?php $this->lan("Month_balance"); ?> <span class="statistics_use_money"></span> <br />
                                    <?php $this->lan("Month_Recharge"); ?> <span class="statistics_rechange"></span> <br />
                                    <?php $this->lan("Month_Exchange"); ?> <span class="statistics_exchange"></span> <br />
                                    <!--今日投资收入 <?php echo $statistics[0]['invest_income']??0;; ?> (定制) <br />-->
                                    <?php $this->lan("Month_points"); ?> <span class="statistics_use_score"></span> <br />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="tuan_box">
                        <table>
                            <tr>
                                <td>
                                    <!--当前商品总数 <?php echo $statistics[0]['goods_total']??0; ?><br />-->
                                    <?php $this->lan("Month_order_add"); ?> <span class="statistics_orders_num"></span> <br />
                                    <?php $this->lan("Month_order_deliver"); ?> <span class="statistics_orders_fh_num"></span> <br />
                                    <?php $this->lan("Month_order_wait"); ?> <span class="statistics_orders_df_num"></span> <br />
                                    <?php $this->lan("Month_order_convert"); ?> <span class="statistics_orders_exc_num"></span> <br />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="version_box">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php $this->lan("Total_number_users"); ?> <span class="statistics_user_total"></span> <br />
                                        <!--<?php echo $statistics[0]['yesterday_user_num']??0; ?>-->
                                        <?php $this->lan("Month_user_add"); ?> <span class="statistics_user_num"></span> <br />
                                        <?php $this->lan("Month_user_recharge"); ?> <span class="statistics_user_rechange_num"></span> <br />
                                        <?php $this->lan("Month_user_exchange"); ?> <span class="statistics_user_exc_num"></span> <br />
                                        <!--近7日股东增加/退出 <?php echo $statistics[1]['user_invest_add']??0; ?>/<?php echo $statistics[1]['user_invest_quit']??0; ?> (定制) <br />-->
                                        <div id="version_tip"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div class="blank5"></div>
        <div class="blank5"></div>
        <div class="blank5"></div>
        <div class="blank5"></div>
        <!--<div class="main_title">-->
            <!-- <span style="color:red;">注意：如某天没有用户充值则不显示</span> -->
        <!--</div>-->
    </div>
    <div id='app' class="div-button trend-chart-wrap" style="width:90%;padding-top: 20px;margin-left:40px">
        <div id="myChart" :style="{width: '100%', height: '400px'}"></div>
    </div>
    <div class="bk10"></div>
    <!--<div class="main_title" style="text-align:center; margin-bottom:10px"> </div>-->
    <div class="shijiesj">
        <?php
        foreach ($zones as $key=>$vl) {
            $sel = $vl['time_zone']==$time_zone?1:0;
            $css = "";
            if($sel==1){
                $css = 'shijian-sel';
            }
            echo "
                    <div class='shijianlist $css' >
                        <div class='shilito'>
                            <div class='litotu' id='daojis{$vl['hi']}{$vl['s']}'><span>{$vl['hi']}</span><div>{$vl['s']}s</div></div>
                            <div class='litosu'>{$vl['md']} {$vl['n']}</div>
                        </div>
                        <div class='shilibo'>
                            <div class='libole'>{$vl['name']}</div>
                            <div class='libori edit_item_right' data-key='{$key}' data-time-zone='{$vl['time_zone']}' data-sel='{$sel}'><img src='" . G_WEB_PATH . "/statics/plugin/style/global/image/shright.png'></div>
                        </div>
                    </div>
                ";
        }
        ?>
    </div>
    <div class="bk10"></div>

<!-- 虚拟币实时价板块 -->
<div class="crypto-section" style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); margin: 20px 0;box-sizing: border-box; border-radius: 12px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
    <!--<div class="title" style="margin-bottom: 20px;"></div>-->
    <div id="cryptoContainer" style="display: flex; gap: 20px; flex-wrap: wrap;">
        <!-- BTC 价格 -->
        <div class="crypto-item" style="flex: 1; min-width: 200px; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; color: #fff; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)';">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <div>
                    <div style="font-size: 14px; opacity: 0.9;">BTC/USDT</div>
                    <div style="font-size: 24px; font-weight: bold; margin-top: 5px;" id="btcPrice"><?php $this->lan("Connecting","连接中"); ?>...</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 12px; opacity: 0.8;"><?php $this->lan("rise_fall","24h涨跌"); ?></div>
                    <div style="font-size: 16px; font-weight: bold; margin-top: 5px;" id="btcChange">-</div>
                </div>
            </div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 10px;">
                <span id="btcVolume"><?php $this->lan("Volume","成交量"); ?>: -</span>
            </div>
        </div>
        <!-- ETH 价格 -->
        <div class="crypto-item" style="flex: 1; min-width: 200px; padding: 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; color: #fff; box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 6px 20px rgba(245, 87, 108, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(245, 87, 108, 0.3)';">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <div>
                    <div style="font-size: 14px; opacity: 0.9;">ETH/USDT</div>
                    <div style="font-size: 24px; font-weight: bold; margin-top: 5px;" id="ethPrice"><?php $this->lan("Connecting","连接中"); ?>...</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 12px; opacity: 0.8;"><?php $this->lan("rise_fall","24h涨跌"); ?></div>
                    <div style="font-size: 16px; font-weight: bold; margin-top: 5px;" id="ethChange">-</div>
                </div>
            </div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 10px;">
                <span id="ethVolume"><?php $this->lan("Volume","成交量"); ?>: -</span>
            </div>
        </div>
    </div>
    <div style="margin-top: 15px; font-size: 12px; color: #999; text-align: center;">
        <?php $this->lan("Data_source","数据来源"); ?>：<a href="https://api.taolong.com" target="_blank" rel="noopener noreferrer">韬龙API数据集</a> | <span id="cryptoDataSource"><?php $this->lan("Connecting","连接中"); ?>...</span>
        <span id="cryptoApiTestIcons" style="display:none; margin-left: 10px; vertical-align: middle;">
            <a href="javascript:void(0)" id="cryptoTestTaoLong" title="<?php echo htmlspecialchars($this->lan("Test_taolong","测试韬龙API连接",2)); ?>" style="display:inline-flex;align-items:center;margin:0 4px;padding:2px 6px;border-radius:6px;background:#f0f4f8;color:#64748b;text-decoration:none;font-size:11px;border:1px solid #e2e8f0;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f0f4f8'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83M19.07 4.93l-2.83 2.83M7.76 16.24l-2.83 2.83"/><circle cx="12" cy="12" r="3"/></svg>
                <?php echo htmlspecialchars($this->lan("TaoLong","韬龙",2)); ?>
            </a>
            <a href="javascript:void(0)" id="cryptoTestBinance" title="<?php echo htmlspecialchars($this->lan("Test_binance","测试Binance API连接",2)); ?>" style="display:inline-flex;align-items:center;margin:0 4px;padding:2px 6px;border-radius:6px;background:#f0fdf4;color:#166534;text-decoration:none;font-size:11px;border:1px solid #bbf7d0;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83M19.07 4.93l-2.83 2.83M7.76 16.24l-2.83 2.83"/><circle cx="12" cy="12" r="3"/></svg>
                Binance
            </a>
            <a href="javascript:void(0)" id="cryptoShowTroubleshoot" title="<?php echo htmlspecialchars($this->lan("Troubleshoot_tip","查看排查步骤",2)); ?>" style="display:inline-flex;align-items:center;margin:0 4px;padding:2px 6px;border-radius:6px;background:#fef3c7;color:#92400e;text-decoration:none;font-size:11px;border:1px solid #fde68a;" onmouseover="this.style.background='#fde68a'" onmouseout="this.style.background='#fef3c7'"><?php echo htmlspecialchars($this->lan("How_to_fix","如何排查？",2)); ?></a>
        </span>
    </div>
    <div id="cryptoTroubleshootBox" style="display:none; margin-top: 12px; padding: 12px 16px; font-size: 12px; color: #64748b; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; text-align: left;">
        <div style="font-weight: 600; color: #475569; margin-bottom: 8px;"><?php echo htmlspecialchars($this->lan("Troubleshoot_title","故障排查步骤",2)); ?></div>
        <ol style="margin: 0; padding-left: 18px; line-height: 1.8;">
            <li><strong><?php echo htmlspecialchars($this->lan("Step1_browser","浏览器端",2)); ?></strong>：按 F12 打开开发者工具 → 切到「Network」→ 再点击一次「韬龙」或「Binance」测试 → 看该请求的状态码（200/404/500）和响应内容，可判断是<strong>请求超时</strong>还是<strong>服务器返回错误</strong>。</li>
            <li><strong><?php echo htmlspecialchars($this->lan("Step2_server_taolong","服务器到韬龙",2)); ?></strong>：在<strong>本机服务器</strong> SSH 或终端执行：<code style="background:#e2e8f0;padding:2px 6px;border-radius:4px;">curl -I --connect-timeout 5 https://api.taolong.com/api/crypto/ticker</code>。若返回 <strong>401</strong>，说明韬龙 API 需要鉴权，请在后台配置 <strong>韬龙 API Key</strong>（系统配置或 config 表中的 <code>taolong_crypto_api_key</code>）；若超时或连接失败，说明服务器访问韬龙异常（防火墙/DNS/网络）。</li>
            <li><strong><?php echo htmlspecialchars($this->lan("Step2_server_binance","服务器到 Binance",2)); ?></strong>：执行 <code style="background:#e2e8f0;padding:2px 6px;border-radius:4px;">curl -I --connect-timeout 5 https://api.binance.com/api/v3/ticker/24hr?symbol=BTCUSDT</code>，同样可判断服务器能否访问 Binance。</li>
            <li><strong><?php echo htmlspecialchars($this->lan("Step3_backend","后台接口",2)); ?></strong>：在浏览器新标签直接访问测试接口（需已登录后台），例如 <code style="background:#e2e8f0;padding:2px 6px;border-radius:4px;"><?php echo defined("G_MODULE_PATH") ? G_MODULE_PATH : "/admin"; ?>/index/testCryptoApi?source=taolong</code>，应返回 JSON。若 404/500 或空白，检查模块路由与 PHP 错误日志。</li>
        </ol>
    </div>
</div>

<script>
// 虚拟币实时价：优先 TaoLong/服务端 Binance，带超时与并行请求，避免 60+ 秒挂起
(function() {
    let ws = null;
    let reconnectTimer = null;
    let reconnectAttempts = 0;
    let connectionTimeout = null;
    let restApiInterval = null;
    let useRestApi = false;
    let useTaoLong = false;
    let taolongPollTimer = null;
    const maxReconnectAttempts = 3;
    const connectionTimeoutMs = 8000;
    const cryptoPriceUrl = '<?php echo defined("G_MODULE_PATH") ? G_MODULE_PATH : "/admin"; ?>/index/cryptoPrice';
    const cryptoTestApiUrl = '<?php echo defined("G_MODULE_PATH") ? G_MODULE_PATH : "/admin"; ?>/index/testCryptoApi';
    var connectionStatusSet = false;
    const connectingCountdownSeconds = 15;
    var connectingCountdownTimer = null;
    const fetchTimeoutMs = 10000;   // 前端请求超时，避免挂起 60+ 秒
    const restFetchTimeoutMs = 8000;
    
    function fetchWithTimeout(url, options, ms) {
        var ctrl = new AbortController();
        var t = setTimeout(function() { ctrl.abort(); }, ms);
        options = options || {};
        options.signal = ctrl.signal;
        return fetch(url, options).then(function(res) {
            clearTimeout(t);
            return res;
        }, function(err) {
            clearTimeout(t);
            throw err;
        });
    }
    
    function clearConnectingCountdown() {
        if (connectingCountdownTimer) {
            clearInterval(connectingCountdownTimer);
            connectingCountdownTimer = null;
        }
    }
    
    function setConnectionStatus(status, reason) {
        var el = document.getElementById('cryptoDataSource');
        if (!el) return;
        clearConnectingCountdown();
        var iconsEl = document.getElementById('cryptoApiTestIcons');
        var boxEl = document.getElementById('cryptoTroubleshootBox');
        if (status === 'success') {
            el.textContent = '<?php echo addslashes($this->lan("Connect_success","连接成功")); ?>';
            el.style.color = '#22c55e';
            if (iconsEl) iconsEl.style.display = 'none';
            if (boxEl) boxEl.style.display = 'none';
        } else if (status === 'fail') {
            el.textContent = '<?php echo addslashes($this->lan("Connect_fail","连接失败")); ?>' + (reason ? ': ' + reason : '');
            el.style.color = '#ef4444';
            if (iconsEl) iconsEl.style.display = 'inline';
        } else {
            if (iconsEl) iconsEl.style.display = 'none';
            if (boxEl) boxEl.style.display = 'none';
            el.style.color = '#999';
            var sec = connectingCountdownSeconds;
            el.textContent = '<?php echo addslashes($this->lan("Connecting","连接中")); ?>... ' + sec + 's';
            connectingCountdownTimer = setInterval(function() {
                if (!document.getElementById('cryptoDataSource')) return;
                sec--;
                if (sec > 0) {
                    el.textContent = '<?php echo addslashes($this->lan("Connecting","连接中")); ?>... ' + sec + 's';
                } else {
                    clearConnectingCountdown();
                    el.textContent = '<?php echo addslashes($this->lan("Connecting","连接中")); ?>...';
                }
            }, 1000);
        }
    }
    
    // 更新价格的通用函数（首次成功更新时标记为连接成功）
    function updatePrice(symbol, price, change, volume) {
        if (!connectionStatusSet) {
            connectionStatusSet = true;
            setConnectionStatus('success');
        }
        if (symbol === 'BTC') {
            const btcPriceEl = document.getElementById('btcPrice');
            if(btcPriceEl) {
                btcPriceEl.textContent = '$' + price.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
            const changeEl = document.getElementById('btcChange');
            if(changeEl) {
                changeEl.textContent = (change > 0 ? '+' : '') + change.toFixed(2) + '%';
                changeEl.style.color = change > 0 ? '#4ade80' : '#f87171';
            }
            const volumeEl = document.getElementById('btcVolume');
            if(volumeEl) {
                volumeEl.textContent = '成交量: ' + volume.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }
        } else if (symbol === 'ETH') {
            const ethPriceEl = document.getElementById('ethPrice');
            if(ethPriceEl) {
                ethPriceEl.textContent = '$' + price.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
            const changeEl = document.getElementById('ethChange');
            if(changeEl) {
                changeEl.textContent = (change > 0 ? '+' : '') + change.toFixed(2) + '%';
                changeEl.style.color = change > 0 ? '#4ade80' : '#f87171';
            }
            const volumeEl = document.getElementById('ethVolume');
            if(volumeEl) {
                volumeEl.textContent = '成交量: ' + volume.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }
        }
    }
    
    // 使用 TaoLong/服务端 Binance 获取价格（优先，带超时避免挂起 60+ 秒）
    function fetchTaoLongPrices() {
        fetchWithTimeout(cryptoPriceUrl, { credentials: 'same-origin' }, fetchTimeoutMs)
            .then(function(res) { return res.json(); })
            .then(function(ret) {
                if (ret && ret.code === 1 && ret.data) {
                    var b = ret.data.btc, e = ret.data.eth;
                    if (b && b.price > 0) updatePrice('BTC', b.price, b.change, b.volume);
                    if (e && e.price > 0) updatePrice('ETH', e.price, e.change, e.volume);
                    if (!useTaoLong) {
                        useTaoLong = true;
                        if (taolongPollTimer) clearInterval(taolongPollTimer);
                        taolongPollTimer = setInterval(fetchTaoLongPrices, 15000);
                    }
                }
            })
            .catch(function() {});
    }
    
    // REST 回退：轮询本机接口（走服务端 TaoLong/Binance），避免浏览器直连 Binance 被墙导致长时间挂起
    function fetchPricesViaRestApi() {
        fetchWithTimeout(cryptoPriceUrl, { credentials: 'same-origin' }, restFetchTimeoutMs)
            .then(function(res) { return res.json(); })
            .then(function(ret) {
                if (ret && ret.code === 1 && ret.data) {
                    var b = ret.data.btc, e = ret.data.eth;
                    if (b && b.price > 0) updatePrice('BTC', b.price, b.change, b.volume);
                    if (e && e.price > 0) updatePrice('ETH', e.price, e.change, e.volume);
                }
            })
            .catch(function() {});
    }
    
    // 启动 REST API 轮询（reason：若 REST 最终也无数据，则显示该失败原因）
    function startRestApiPolling(reason) {
        if (restApiInterval) clearInterval(restApiInterval);
        useRestApi = true;
        console.log('切换到 REST API 模式，开始轮询...');
        fetchPricesViaRestApi();
        restApiInterval = setInterval(fetchPricesViaRestApi, 3000);
        setTimeout(function() {
            if (!connectionStatusSet) setConnectionStatus('fail', reason || '<?php echo addslashes($this->lan("all_sources_fail","数据源不可用")); ?>');
        }, 20000);
    }
    
    function connectWebSocket() {
        try {
            if (ws) {
                try {
                    ws.close();
                } catch(e) {}
                ws = null;
            }
            if (connectionTimeout) {
                clearTimeout(connectionTimeout);
                connectionTimeout = null;
            }
            
            ws = new WebSocket('wss://stream.binance.com:9443/stream?streams=btcusdt@ticker&ethusdt@ticker');
            
            connectionTimeout = setTimeout(function() {
                if (ws && ws.readyState === WebSocket.CONNECTING) {
                    console.warn('WebSocket 连接超时，改用 REST 获取');
                    ws.close();
                    ws = null;
                    if(document.getElementById('btcPrice')) {
                        document.getElementById('btcPrice').textContent = '<?php $this->lan("Connecting","连接中"); ?>...';
                    }
                    if(document.getElementById('ethPrice')) {
                        document.getElementById('ethPrice').textContent = '<?php $this->lan("Connecting","连接中"); ?>...';
                    }
                    startRestApiPolling('<?php echo addslashes($this->lan("ws_timeout","WebSocket超时")); ?>');
                }
            }, connectionTimeoutMs);
            
            ws.onopen = function() {
                console.log('Binance WebSocket 连接已建立');
                if (connectionTimeout) {
                    clearTimeout(connectionTimeout);
                    connectionTimeout = null;
                }
                if (restApiInterval) {
                    clearInterval(restApiInterval);
                    restApiInterval = null;
                    useRestApi = false;
                    console.log('WebSocket 连接成功，停止 REST API 轮询');
                }
                reconnectAttempts = 0;
                if(document.getElementById('btcPrice')) {
                    document.getElementById('btcPrice').textContent = '<?php $this->lan('Connecting', '连接中'); ?>...';
                }
                if(document.getElementById('ethPrice')) {
                    document.getElementById('ethPrice').textContent = '<?php $this->lan('Connecting', '连接中'); ?>...';
                }
            };
            
            ws.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    if (data.stream && data.data) {
                        const stream = data.stream;
                        const ticker = data.data;
                        if (stream === 'btcusdt@ticker') {
                            const price = parseFloat(ticker.c);
                            const change = parseFloat(ticker.P);
                            const volume = parseFloat(ticker.v);
                            updatePrice('BTC', price, change, volume);
                        } else if (stream === 'ethusdt@ticker') {
                            const price = parseFloat(ticker.c);
                            const change = parseFloat(ticker.P);
                            const volume = parseFloat(ticker.v);
                            updatePrice('ETH', price, change, volume);
                        }
                    }
                } catch (e) {
                    console.error('解析 WebSocket 数据失败:', e);
                }
            };
            
            ws.onerror = function(error) {
                console.error('WebSocket 错误:', error);
            };
            
            ws.onclose = function(event) {
                console.log('WebSocket 连接已关闭', 'Code:', event.code);
                var failReason = (event.code === 1006 ? '<?php echo addslashes($this->lan("ws_abnormal","连接异常")); ?>' : '<?php echo addslashes($this->lan("ws_closed","连接已关闭")); ?>');
                if (event.code === 1006 || reconnectAttempts >= maxReconnectAttempts) {
                    console.log('WebSocket 连接失败，切换到 REST API 模式');
                    startRestApiPolling(failReason);
                    return;
                }
                if (reconnectAttempts < maxReconnectAttempts) {
                    reconnectAttempts++;
                    const delay = Math.min(1000 * Math.pow(2, reconnectAttempts), 30000);
                    console.log('将在 ' + (delay/1000).toFixed(1) + ' 秒后尝试重连...');
                    reconnectTimer = setTimeout(connectWebSocket, delay);
                } else {
                    console.error('达到最大重连次数，切换到 REST API 模式');
                    startRestApiPolling(failReason);
                }
            };
        } catch (e) {
            console.error('WebSocket 连接失败:', e);
            startRestApiPolling('<?php echo addslashes($this->lan("ws_error","WebSocket错误")); ?>');
        }
    }
    
    // 页面加载时：先试 TaoLong，失败再走 Binance
    function initWebSocket() {
        if (!document.getElementById('btcPrice') || !document.getElementById('ethPrice')) {
            console.warn('加密货币价格元素未找到，延迟连接...');
            setTimeout(initWebSocket, 100);
            return;
        }
        setConnectionStatus('connecting');
        fetchTaoLongPrices();
        setTimeout(function() {
            if (useTaoLong) return;
            console.log('TaoLong 未就绪，使用 Binance WebSocket...');
            connectWebSocket();
            setTimeout(function() {
                if (useTaoLong) return;
                if (!useRestApi && (!ws || ws.readyState !== WebSocket.OPEN)) {
                    console.log('WebSocket 未连接，切换到 REST API 模式');
                    if (ws) { try { ws.close(); } catch(e) {} ws = null; }
                    startRestApiPolling('<?php echo addslashes($this->lan("ws_not_ready","WebSocket未就绪")); ?>');
                }
            }, 12000);
        }, 4000);
    }
    
    function testCryptoApiClick(source) {
        var url = cryptoTestApiUrl + '?source=' + encodeURIComponent(source);
        var btn = source === 'taolong' ? document.getElementById('cryptoTestTaoLong') : document.getElementById('cryptoTestBinance');
        if (btn) { btn.style.pointerEvents = 'none'; btn.style.opacity = '0.7'; }
        fetchWithTimeout(url, { credentials: 'same-origin' }, 8000)
            .then(function(res) {
                if (!res.ok) {
                    var err = new Error('HTTP ' + res.status);
                    err.status = res.status;
                    throw err;
                }
                return res.json();
            })
            .then(function(ret) {
                var msg = (ret && ret.msg) ? ret.msg : (ret && ret.ok ? '<?php echo addslashes($this->lan("Connect_success","连接成功")); ?>' : '<?php echo addslashes($this->lan("Connect_fail","连接失败")); ?>');
                if (typeof layer !== 'undefined' && layer.msg) layer.msg(msg, { icon: ret && ret.ok ? 1 : 2 });
                else alert(msg);
            })
            .catch(function(err) {
                var isTimeout = (err && err.name === 'AbortError');
                var isHttp = (err && err.status);
                var msg = isTimeout ? '<?php echo addslashes($this->lan("Request_timeout_only","请求超时（8秒内未响应）")); ?>' : (isHttp ? '<?php echo addslashes($this->lan("Server_error","服务器返回错误")); ?> ' + (err.status || '') : '<?php echo addslashes($this->lan("Request_timeout","请求超时或网络异常")); ?>');
                if (typeof layer !== 'undefined' && layer.msg) layer.msg(msg + ' <?php echo addslashes($this->lan("Click_troubleshoot","可点击「如何排查？」查看步骤")); ?>', { icon: 2, time: 5000 });
                else alert(msg);
            })
            .then(function() {
                if (btn) { btn.style.pointerEvents = ''; btn.style.opacity = '1'; }
            });
    }
    
    function bindCryptoTestIcons() {
        var t = document.getElementById('cryptoTestTaoLong');
        var b = document.getElementById('cryptoTestBinance');
        var troubleshootLink = document.getElementById('cryptoShowTroubleshoot');
        var troubleshootBox = document.getElementById('cryptoTroubleshootBox');
        if (t) t.addEventListener('click', function(e) { e.preventDefault(); testCryptoApiClick('taolong'); });
        if (b) b.addEventListener('click', function(e) { e.preventDefault(); testCryptoApiClick('binance'); });
        if (troubleshootLink && troubleshootBox) {
            troubleshootLink.addEventListener('click', function(e) {
                e.preventDefault();
                troubleshootBox.style.display = troubleshootBox.style.display === 'none' ? 'block' : 'none';
            });
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initWebSocket, 100);
            bindCryptoTestIcons();
        });
    } else {
        setTimeout(initWebSocket, 100);
        setTimeout(bindCryptoTestIcons, 150);
    }
    
    // 页面卸载时关闭连接
    window.addEventListener('beforeunload', function() {
        if (taolongPollTimer) {
            clearInterval(taolongPollTimer);
            taolongPollTimer = null;
        }
        if (ws) {
            try { ws.close(); } catch(e) {}
            ws = null;
        }
        if (reconnectTimer) { clearTimeout(reconnectTimer); reconnectTimer = null; }
        if (connectionTimeout) { clearTimeout(connectionTimeout); connectionTimeout = null; }
        if (restApiInterval) { clearInterval(restApiInterval); restApiInterval = null; }
    });
})();
</script>

    <div class="platform-system-wrap">
    <!--<div class="main_title" style="text-align:center;"> </div>-->
    <div class="platform-cols">
        <div class="div-system">
            <div class="title"><?php $this->lan("System_information"); ?></div>
            <div class="bk10"></div>
            <ul>
                <li><i><?php $this->lan("Operating_system"); ?> </i><?php echo $SysInfo['os']; ?></li>
                <li><i><?php $this->lan("Server_version"); ?> </i><?php echo $SysInfo['web_server']; ?></li>
                <li> <i><?php $this->lan("Cancel_account"); ?> </i><?php echo date('Y-m-d H:i:s', time()); ?> <div style="float:right;width: 220px;text-align: left"></div>
                </li>

                <li><i><?php $this->lan("PHP_version"); ?> </i><?php echo $SysInfo['phpv']; ?> <div style="float: right;width: 220px;text-align: left"><i><?php $this->lan("MYSQL_version"); ?> </i><?php echo $SysInfo['MysqlVersion']; ?></div>
                </li>
                <li><i> <?php $this->lan("GD_library"); ?></i><?php echo showResult('imageline')?$this->lan("support"):$this->lan("not_support"); ?> <div style="float: right;width: 220px;text-align: left"><i><?php $this->lan("set_time_limit"); ?> </i><?php echo showResult('set_time_limit',2)?$this->lan("support"):$this->lan("not_support"); ?></div>
                </li>
                <li><i><?php $this->lan("restrictions"); ?> </i><?php echo get_cfg_var('post_max_size'); ?> <div style="float: right;width: 220px;text-align: left"><i><?php $this->lan("fsockopen"); ?> </i><?php echo showResult('fsockopen')?$this->lan("support"):$this->lan("not_support"); ?></div>
                </li>
                <li><i><?php $this->lan("Script_timeout"); ?> </i><?php echo ini_get('max_execution_time') ; ?><?php $this->lan("second"); ?> <div style="float: right;width: 220px;text-align: left"><i><?php $this->lan("ZEND_support"); ?> </i><?php echo showResult('zend_version')?$this->lan("support"):$this->lan("not_support"); ?></div>
                </li>
            </ul>
        </div>
        <div class="div-webinfo">
            <div class="title"><?php $this->lan("Administrator_Info"); ?></div>
            <div class="bk10"></div>
            <div class="job">
                <div class="right"> <img src="<?php echo G_WEB_PATH ?>/statics/plugin/style/global/image/u93.png"> </div>

                <ul class="">
                    <li><span><?php echo $info['username']; ?></span></li>
                    <li><i><?php $this->lan("nickname"); ?></i><span><?php echo $info['name']; ?></span></li>
                    <li><i><?php $this->lan("Belonging_role"); ?></i><span><?php $this->lan("Super_administrator"); ?></span></li>
                    <?php foreach ($infos as $k => $v) { ?>
                        <li>IP：<?php echo $v['ip']; ?>【<?php echo $v['region']; ?>·<?php echo $v['city']; ?>】&nbsp;&nbsp;<?php $this->lan("login_time"); ?><?php echo date('Y-m-d H:i:s', $v['login_time']); ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="div-about">
            <div class="title"><?php $this->lan("Website_info"); ?></div>
            <div class="bk15"></div>
            <ul>
                <li><i><?php $this->lan("column"); ?></i><?php echo $tj_category; ?></li>
                <li><i><?php $this->lan("brand"); ?></i><?php echo $tj_brand; ?></li>
                <li><i><?php $this->lan("article"); ?></i><?php echo $tj_article; ?></li>
                <li><i><?php $this->lan("goods_count"); ?></i><?php echo $tj_shoplist; ?></li>
                <li><i><?php $this->lan("membership"); ?></i><?php echo $tj_member; ?></li>
                <li><i><?php $this->lan("members_add"); ?></i><?php echo $tj_member_new; ?></li>
                <li><i><?php $this->lan("Goods_add"); ?></i><?php echo $tj_shoplist_new; ?></li>
                <li><i><?php $this->lan("Today_account_income"); ?></i><?php echo $today_money; ?></li>
            </ul>
        </div>
    </div>
    </div>
    </div>
    <div class="" id="window" style="margin:30px;display:none;">
        <form class="layui-form layui-form-pane" action="#" id="operation" method="post">

            <div class="layui-form-item">
                <label class="layui-form-label"><?php $this->lan("current"); ?></label>
                <div class="layui-input-block">
                    <select name="time_zone" lay-filter="aihao" id='time_zone'>
                        <?php foreach($zone_lists as $vl){?>
                        <option value="<?php echo $vl['lg_value'];?>" <?php if ($time_zone == $vl['time_zone']) echo "selected"; ?> datatime=""><?php echo $vl['country'];?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><?php $this->lan("服务器时间","服务器时间"); ?></label>
                <div class="layui-input-block layui-form">
                    <input type="checkbox"
                        id="is_state" 
                        class="is_state" 
                        lay-skin="switch" 
                        lay-filter="is_state"
                        data-state="<?php echo $v3['id'] ?>" 
                        lay-text="<?php $this->lan("设置"); ?>|<?php $this->lan("取消"); ?>">
                </div>
            </div>
            <div class="shiquval" style="padding-left: 121px;"></div>
            <input type="hidden" name="time_zone_sel" id="time_zone_sel" value="">
        </form>
    </div>
</body>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/vue/vue.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/map/data/echarts.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ichart.latest.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<script src="/statics/templates/mlmuban/js/layui/lay/modules/form.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    var images = document.querySelectorAll('.carousel-images div'); // 获取所有图片
    console.log(123,images);
    
    var currentIndex = 0; // 当前显示的图片索引
    var totalImages = images.length; // 总图片数
    var intervalTime = 3000; // 自动切换的时间间隔，单位毫秒
 
    function showNextImage() {
        images[currentIndex].style.display = 'none'; // 隐藏当前图片
        currentIndex = (currentIndex + 1) % totalImages; // 更新索引，循环显示图片
        images[currentIndex].style.display = 'block'; // 显示下一张图片
    }
 
    setInterval(showNextImage, intervalTime); // 设置定时器自动播放
    });
</script>
<script>
(function() {
  var carouselItems = document.getElementById('carousel-items');
  if (!carouselItems) return;
  var items = carouselItems.querySelectorAll('[carousel-item]');
  var count = items.length;
  if (count <= 1) return; // 只有一条或没有时不滚动
  var index = 0;
  var itemHeight = 36;
  var stayMs = 3200;  // 每条停留时间（含过渡），节奏统一
  function moveCarousel() {
    index = (index + 1) % count;
    carouselItems.style.transform = 'translateY(' + (-index * itemHeight) + 'px)';
  }
  setInterval(moveCarousel, stayMs);
})();
</script>

<script type="text/javascript">
    new Vue({
        el: '#app',
        data: {
            monedata: {},
            userdata: {},
            mu: {},
            vnum: [],
            unum: [],
            vday: [],
            znum: [],
        },
        computed: {

        },
        methods: {
            getdata() {
                var _this = this
                $.post('/<?php echo G_ADMIN_DIR; ?>/index/TdefaultData', {}, function(res) {
                    var result = JSON.parse(res)
                    _this.mu = result
                    _this.mu.m.map((v, k) => {
                        _this.vnum.push(v.num * 1)
                    })
                    _this.mu.u.map((v, k) => {
                        _this.unum.push(v.num * 1)
                    })
                    _this.mu.zu.map((v, k) => {
                        _this.znum.push(v.num * 1)
                    })
                    _this.mu.u.map((v, k) => {
                        _this.vday.push(v.day)
                    })
                    // return true
                    _this.drawLine()
                })
            },
            drawLine() {
                var _this = this
                // 基于准备好的dom，初始化echarts实例
                let myChart = echarts.init(document.getElementById('myChart'))
                // 绘制图表
                var chartOpt = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: { type: 'cross', label: { backgroundColor: '#00BDBE' } }
                    },
                    legend: {
                        x: '10px',
                        data: ["<?php $this->lan('Sales', '营业额'); ?>", "<?php $this->lan('new_people', '新增用户'); ?>", "<?php $this->lan('all_people', '总用户'); ?>"]
                    },
                    grid: {
                        left: '40px',
                        right: '40px',
                        bottom: '7%'
                    },
                    color: ['#00BDBE', '#2B97FB', '#FF9500'],
                    xAxis: {
                        axisLine: { lineStyle: { color: '#E8E8E8' } },
                        axisLabel: { color: '#494949' },
                        axisTick: { show: false },
                        data: _this.vday
                    },
                    yAxis: [{
                            name: '<?php $this->lan('amount', '金额'); ?>',
                            type: 'value',
                            nameTextStyle: { color: '#323232' },
                            axisLine: { show: true, lineStyle: { color: '#E8E8E8' } },
                            axisLabel: { color: '#494949' },
                            splitLine: { show: true, lineStyle: { color: '#E8E8E8' } },
                            axisTick: { show: false }
                        },
                        {
                            name: '<?php $this->lan('people', '人数'); ?>',
                            type: 'value',
                            nameTextStyle: { color: '#323232' },
                            axisLine: { show: true, lineStyle: { color: '#E8E8E8' } },
                            axisLabel: { color: '#494949' },
                            splitLine: { show: true, lineStyle: { color: '#E8E8E8' } },
                            axisTick: { show: false }
                        }
                    ],
                    series: [
                        { name: '<?php $this->lan('Sales', '营业额'); ?>', type: 'line', yAxisIndex: 0, data: _this.vnum },
                        { name: '<?php $this->lan('new_people', '新增用户'); ?>', type: 'line', yAxisIndex: 1, data: _this.unum },
                        { name: '<?php $this->lan('all_people', '总用户'); ?>', type: 'line', yAxisIndex: 1, data: _this.znum }
                    ]
                };
                try {
                    myChart.setOption(chartOpt);
                    setTimeout(function() { myChart.resize(); }, 100);
                } catch (e) {
                    if (typeof console !== 'undefined' && console.error) console.error('ECharts setOption error:', e);
                }
            },
        },
        beforeMount() {

        },
        mounted() {
            this.getdata()
        },
        watch: {
            'message'() {

            }
        }
    })
    $('.litotu div').map(function() {
        var tumiao = $(this).text().slice(0, 2) * 1
        var tushil = $(this).parent().find('span').text().split(':')
        setInterval(() => {
            if (tumiao == 59) {
                tumiao = '00'
                if (tushil[1] == '59') {
                    tushil[1] = '00'
                    if (tushil[0] == '23') {
                        tushil[0] = '00'
                    } else {
                        tushil[0] = tushil[0] * 1 + 1
                    }
                } else {
                    tushil[1] = tushil[1] * 1 + 1
                }
                $(this).parent().find('span').text(tushil[0] + ':' + tushil[1])
            } else {
                tumiao++
                if (tumiao < 10) tumiao = '0' + tumiao
            }
            $(this).text(tumiao + 's')
        }, 1000);
    })

    function _ROLL(obj) {
        this.ele = document.getElementById(obj);
        this.interval = false;
        this.currentNode = 0;
        this.passNode = 0;
        this.speed = 100;
        this.childs = _childs(this.ele);
        this.childHeight = parseInt(_style(this.childs[0])['height']);
        addEvent(this.ele, 'mouseover', function() {
            window._loveYR.pause();
        });
        addEvent(this.ele, 'mouseout', function() {
            window._loveYR.start(_loveYR.speed);
        });
    }

    function _style(obj) {
        return obj.currentStyle || document.defaultView.getComputedStyle(obj, null);
    }

    function _childs(obj) {
        var childs = [];
        for (var i = 0; i < obj.childNodes.length; i++) {
            var _this = obj.childNodes[i];
            if (_this.nodeType === 1) {
                childs.push(_this);
            }
        }
        return childs;
    }

    function addEvent(elem, evt, func) {
        if (-[1, ]) {
            elem.addEventListener(evt, func, false);
        } else {
            elem.attachEvent('on' + evt, func);
        };
    }

    function innerest(elem) {
        var c = elem;
        while (c.childNodes.item(0).nodeType == 1) {
            c = c.childNodes.item(0);
        }
        return c;
    }

    layui.use(['form'], function() {
        var form = layui.form;
        form.on('select(aihao)', function(data) {
            var customValue = data.elem.options[data.elem.selectedIndex].getAttribute('datatime'); // 获取自定义属性
            console.log(customValue); // 被选中的元素DOM对象
            $('.shiquval').html(customValue)
        });

        var $ = layui.jquery; // 注意这里的 $ 是 layui 封装的 jQuery，不是全局的 jQuery
        $(".edit_item_right").click(function() {
            console.log(1)
            var selected = $(this).attr("data-sel")
            if( selected == 1){
                $("#is_state").prop('checked', true)
            }else{
                $("#is_state").prop('checked', false)
            }
            form.render('checkbox'); // 或者使用 form.render(); 来重新渲染所有表单元素
        })
    });
     
    
    layui.use('carousel', function() {
        var carousel = layui.carousel;
        //建造实例 
        carousel.render({
            elem: '#test1',
            width: '20%', //设置容器宽度
            height: '30px', //设定轮播容器高度
            arrow: 'none', //始终显示箭头 always
            anim: 'updown', //切换动画方式
            interval: '1000', //自动切换的时间间隔，单位：ms（毫秒），不能低于800
            indicator: 'none', //指示器位置
            autoplay:'true'
            // arrow: 'none', //切换箭头默认显示状态
        })
    });
    $('.qh3brq .brqd').click(function() {
        $(".notify_box").addClass('loader')
        var thisid = $(this).attr('attr-id')
        $.post("<?php echo G_MODULE_PATH ?>/index/statistics", {
            times: thisid
        }, function(data) {
            data = JSON.parse(data)
            if (data.code == 200) {
                for (var element in data.data) {
                    $(".statistics_" + element).html(data.data[element])
                }
                $('.notify_box').removeClass('loader')
            }

        });
        // return false;
        $('.qh3brq .brqd').removeClass('xzdqdiv')
        $(this).addClass('xzdqdiv')
        $('.notify_box').hide()
        if (thisid == 1) {
            $('.notibox1').show()
        }
        if (thisid == 7) {
            $('.notibox7').show()
        }
        if (thisid == 30) {
            $('.notibox30').show()
        }
    })
    $(".xzdqdiv").click()
    $(".edit_item_right").click(function() {
        var key = $(this).attr("data-key")
        var time_zone = $(this).attr("data-time-zone")
        var selected = $(this).attr("data-sel")
        if( selected == 1){
            $("#time_zone_sel").val(1)
            // $("#is_state").prop('checked', 'checked')
        }else{
            // $("#is_state").prop('checked', '')
            $("#time_zone_sel").val(2)
        }
        layer.open({
            type: 1,
            title: "<?php $this->lan('Select_time_zone', '选择时区'); ?>",
            area: ['400px', '240px'],
            btn: ['<?php $this->lan('Submit', '确定'); ?>'],
            btnAlign: 'c',
            content: $("#window"),
            yes: function(index, layero) {
                var time_zone = $("#time_zone").val()
                var sel = $("#time_zone_sel").val()
                $.post("<?php echo G_MODULE_PATH . "/index/setTimeZone"; ?>", {
                    time_zone: time_zone,
                    key:key,
                    sel:sel
                }, function(data) {
                    data = JSON.parse(data)
                    if (data.code == 200) {
                        layer.msg(data.msg)
                        setTimeout(function() {
                            location.reload()
                        }, 1000)
                    }
                })
            },
            end: function() {
                //不做其他操作

            }
        });
    })

    // 修改过
    layui.use(['form', 'jquery'], function() {
            var form = layui.form,
                $ = layui.jquery;
            form.render('checkbox');
            form.on('switch(is_state)', function(data) {
                var id = $(this).attr('data-state');
                var name = $(this).attr('name');
                var zhi = '';
                if (data.elem.checked == true) {
                    zhi = 1;
                } else if (data.elem.checked == false) {
                    zhi = 2;
                }
                $("#time_zone_sel").val(zhi)
            })
        })
</script>


</html>