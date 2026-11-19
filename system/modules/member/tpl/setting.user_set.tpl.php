<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
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

    <script src="<?php echo G_TEMPLATES_JS; ?>/mobile/jquery190.js" language="javascript" type="text/javascript"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>

    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.all.js"></script>

    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/inputTag/inputTag.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/inputTag/inputTag.js"></script>
    <style>
        .layui-btn {
            background-color: #080808;
        }

        th {
            border: 0px solid #000;
        }

        .biqoqian {
            display: flex;
            align-items: center;
            color: #777;
            border: 1px solid #E3E5F1;
            width: max-content;
            border-radius: 2px;
            padding: 7px;
            min-width: 395px;
        }

        .biqoqian.shuruon {
            border-color: #007cba;
            box-shadow: 0 0 0 .5px #007cba;
            outline: 2px solid #0000;
        }

        .biqoqian .sjiap {
            display: inline-block;
            height: 20px;
            display: flex
        }

        .biqoqian .sinop {
            border-radius: 5px;
            background-color: #fff;
            padding-left: 5px;
            height: 25px;
            line-height: 25px;
            display: flex;
            align-items: center;
            margin-right: 7px;
            background: #dddddd;
        }

        .sjiap input {
            height: 100%;
            width: 395px;
            border: 0;
            background: rgba(0, 0, 0, 0);
            font-size: 12px;
        }

        .biqoqian .sinop input {
            border: 0;
            width: max-content;
            width: 50px;
            background: #dddddd;
        }

        .biqoqian .sinop span {
            font-size: 15px;
            padding: 0 10px;
            cursor: pointer;
        }

        .biqoqian .sinop div {
            color: #333;
        }

        table {
            border: none !important;
            /* 去除整个表格的边框 */
            border-collapse: collapse !important;
            /* 防止表格单元格之间出现重复的边框 */
        }

        td,
        th {
            border: none !important;
            /* 去除表格单元格和表头的边框 */
        }

        .layui-btn {
            height: 30px !important;
            line-height: 30px !important;
        }

        .Login_method_img img {
            margin: -5px 3px 0px 3px;

        }
        .tip:hover:after {
            bottom: 10px;
        }
        .tip:hover:after {
            bottom: -2px;
        }
    </style>
</head>

<body>
    <div class="header lr10">

        <?php echo $this->headerment(); ?>

    </div>
    <div class="bk10"></div>
    <div class="table-list lr10">
        <form name="myform" id="myform" action="#" method="post">
            <table width="100%" cellspacing="0">
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"> <?php $this->lan("Nickname_restrictions"); ?></td>
                    <td>
                        <div class="biqoqian" style="margin: 0!important;">
                            <span class="sjiap">
                                <input type="text" placeholder=" <?php $this->lan("tips1"); ?>" style="transform: translate(-15px, -15px);border: 0 !important;">
                            </span>
                            <input type="hidden" name="nickname" placeholder="<?php $this->lan("Nickname_restrictions"); ?>" value="<?php echo $nickname; ?>" class="dohafngesr">
                        </div>

                        <span></span>
                    </td>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"> <?php $this->lan("Avatar_settings"); ?></td>
                    <td style="display: flex;align-items: center;padding: 20px 0 !important;gap: 10px;">
                        <input type="radio" name="is_photo" value="1" style="margin: 0 !important;" <?php echo $web['is_photo'] != "2" ? "checked" : ""; ?> />&nbsp;<?php $this->lan("default"); ?>&nbsp;
                        <input type="radio" name="is_photo" value="2" style="margin: 0 !important;" <?php echo $web['is_photo'] == "2" ? "checked" : ""; ?> />&nbsp;<?php $this->lan("customize"); ?>
                    </td>
                </tr>
                <tr class="img_head_defalut">
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("User_avatar"); ?></td>
                    <td>
                        <div class="layui-row">
                            <div class="layui-col-md1 width130" style="border:1px solid #fff">
                                <div class="layui-upload-list height130">
                                    <div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;" <?php endif; ?>>
                                        <span>+</span>
                                        <text><?php $this->lan("Click_upload"); ?></text>
                                    </div>
                                    <img class="layui-upload-img" width="120px" src="<?php echo  G_UPLOAD_PATH . '/' . $web['photo']; ?>" id="photoimg-img">
                                    <input type="hidden" id="photoimg-lack" name="photo" value="<?php echo $web['photo']; ?>">
                                    <p id="demoText"></p>
                                    <button type="button" onclick="uploadimg('用户头像','#photoimg','image','touimg')"></button>
                                </div>
                            </div>
                            <div style="z-index:100;width:200px;display:flex;" data-title="<?php $this->lan("tips"); ?>" class="tip">
                                <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
                            </div>

                        </div>
                    </td>

                </tr>
                <tr class="img_head_list">
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Qiniu_storage"); ?></td>
                    <td colspan="12">
                        <div id="lunbo" style="display: flex;flex-wrap: wrap;align-items: flex-start;max-width: 1400px;margin:0!important;">
                            <?php if ($list) foreach ($list as $k => $vl): ?>
                                <div class='imgbox' id="parent-<?php echo $k; ?>" style="position:relative">
                                    <input type="hidden" id="imgHeadPhoto<?php echo $k ?>-lack" name="imgarrList[]" value="<?php echo $vl['path']; ?>" />
                                    <img id="imgHeadPhoto<?php echo $k ?>-img" src="<?php echo $vl['url']; ?>" onclick="uploadimg('上传预设头像','#imgHeadPhoto<?php echo $k ?>')" style="width:130px;height:130px;" />
                                    <i style="position: absolute;right:0;color:#3C8DBC;font-size: 20px;cursor: pointer;padding-right: 10px;top: 0;" onClick="remove_keys(this)" data-parent="parent-<?php echo $k; ?>">x</i>
                                </div>
                            <?php endforeach; ?>
                            <div class="layui-row" style="display: inline-flex;padding: 0;">
                                <div class="layui-col-md1 width130">
                                    <div class="layui-upload-list height130">
                                        <div class="layui-bottom-img " id="addbanner">
                                            <span>+</span>
                                            <text><?php $this->lan("Favicon"); ?></text>
                                        </div>
                                        <p id="demoText"></p>
                                    </div>
                                </div>
                            </div>
                            <div style="z-index:100;width:200px;display:flex;margin-top: 105px;" data-title="<?php $this->lan("tips"); ?>" class="tip">
                                <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin: 5px 0 0 5px !important;">
                            </div>
                        </div>

                    </td>

                </tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("User_login_method"); ?></td>
                    <td style="display: flex;align-items: center;gap: 10px;">
                        <input type="checkbox" id="login_type1" style="margin: 0 !important;" name="login_type[]" <?php if (in_array(1, $web['login_type'])) {
                                                                                        echo "checked";
                                                                                    } ?> value="1"><label style="margin: 0 !important;" for="login_type1">&nbsp;<?php $this->lan("Code_login"); ?></label>
                        <input type="checkbox" id="login_type2" style="margin: 0 !important;" name="login_type[]" <?php if (in_array(2, $web['login_type'])) {
                                                                                        echo "checked";
                                                                                    } ?> value="2"><label style="margin: 0 !important;" for="login_type2">&nbsp;<?php $this->lan("Password_login"); ?></label>
                        <input type="checkbox" id="login_type3" style="margin: 0 !important;" name="login_type[]" <?php if (in_array(3, $web['login_type'])) {
                                                                                        echo "checked";
                                                                                    } ?> value="3"><label style="margin: 0 !important;" for="login_type3">&nbsp;<?php $this->lan("Email_login"); ?></label>
                        <input type="checkbox" id="login_type4" style="margin: 0 !important;" name="login_type[]" <?php if (in_array(4, $web['login_type'])) {
                                                                                        echo "checked";
                                                                                    } ?> value="4"><label style="margin: 0 !important;" for="login_type4">&nbsp;<?php $this->lan("Quick_login"); ?></label>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <!--<tr>-->
                    <!--    <td></td>-->
                    <!--    <td width="220" align="right">-->
                    <!--        <div class="layui-btn-group">-->
                    <!--            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>-->
                    <!--            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>-->
                    <!--            <input type="hidden" name="login_facebook_status" value="<?php echo $web['login_facebook_status']; ?>">-->
                    <!--        </div>-->
                    <!--    </td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                    <!--    <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("Facebook_id"); ?></td>-->
                    <!--    <td style="display:flex">-->
                    <!--        <input type="text" name="login_facebook_account" value="<?php echo $web['login_facebook_account']; ?>" placeholder="<?php $this->lan("Facebook_id"); ?>" style="min-width:407px;width:407px" class="input-text">-->
                    <!--        <div style="z-index:100;width:200px;display:inline-block" data-title="https://business.facebook.com/business/loginpage/?next=https%3A%2F%2Fdevelopers.facebook.com%2Fapps%2F%3Fshow_reminder%3Dtrue#" class="tip">-->
                    <!--            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">-->
                    <!--        </div>-->
                    <!--    </td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                    <!--    <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("Facebook_key"); ?></td>-->
                    <!--    <td>-->
                    <!--        <input type="text" name="login_facebook_key" value="<?php echo $web['login_facebook_key']; ?>" placeholder="<?php $this->lan("Facebook_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>-->
                    <!--    </td>-->
                    <!--</tr>-->

                </tr>

                <tr style="position:relative">
                    <p style="position:absolute;top:430px;left:252px;color:#444">回调域名Callback URL：<?php echo $web['web_path']; ?>/index/pages/users/index  </p>
                    <td width="220" align="right" style="padding-right: 10px;text-align:right!important;"><?php $this->lan("Google_id"); ?></td>
                    <td style="display:flex">
                        <input type="text" name="login_google_account" value="<?php echo $web['login_google_account']; ?>" placeholder="<?php $this->lan("Google_id"); ?>" style="min-width:407px;width:407px" class="input-text">
                        <div style="z-index:200;width:200px;display:inline-block;margin: 15px 0 0 0 !important;padding-left: 10px;" data-title="https://console.cloud.google.com/auth/overview?hl=zh-cn&pli=1" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin:0!important;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Google_key"); ?></td>
                    <td style="min-width:320px;display: flex;align-items: center;padding-left: 10px;gap: 10px;">
                        <input type="text" name="login_google_key" value="<?php echo $web['login_google_key']; ?>" placeholder="<?php $this->lan("Google_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>
                        <div class="layui-btn-group" style="margin:0!important;gap: 10px;display: flex;">
                            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>
                            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>
                            <input type="hidden" name="login_google_status" value="<?php echo $web['login_google_status']; ?>">
                        </div>
                    </td>
                </tr>

                </tr>
                <tr>

                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("WhatsApp_id"); ?></td>
                    <td style="display:flex">
                        <input type="text" name="login_WhatsApp_account" value="<?php echo $web['login_WhatsApp_account']; ?>" placeholder="<?php $this->lan("WhatsApp_id"); ?>" style="min-width:407px;width:407px" class="input-text">
                        <div style="z-index:100;width:200px;display:inline-block;margin: 15px 0 0 0 !important;padding-left: 10px;" data-title="https://business.whatsapp.com/developers/developer-hub" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin:0!important;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("WhatsApp_key"); ?></td>
                    <td style="display: flex;">
                        <input type="text" name="login_WhatsApp_key" value="<?php echo $web['login_WhatsApp_key']; ?>" placeholder="<?php $this->lan("WhatsApp_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>
                        <div class="layui-btn-group" style="margin:0!important;display:flex;align-items:center;padding:10px;gap:10px;">
                            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>
                            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>
                            <input type="hidden" name="login_WhatsApp_status" value="<?php echo $web['login_WhatsApp_status']; ?>">
                        </div>
                    </td>

                </tr>

                </tr>

                <!-- Line -->
                <tr>

                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Line_id"); ?></td>
                    <td style="display:flex">
                        <input type="text" name="login_Line_account" value="<?php echo $web['login_Line_account']; ?>" placeholder="<?php $this->lan("Line_id"); ?>" style="min-width:407px;width:407px" class="input-text">
                        <div style="z-index:100;width:200px;display:inline-block;margin: 15px 0 0 0 !important;padding-left: 10px" data-title="https://developers.line.biz/zh-hant" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin:0!important;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Line_key"); ?></td>
                    <td style="display:flex">
                        <input type="text" name="login_Line_key" value="<?php echo $web['login_Line_key']; ?>" placeholder="<?php $this->lan("Line_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>
                        <div class="layui-btn-group" style="margin:0!important;display:flex;align-items:center;padding:10px;gap:10px;">
                            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>
                            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>
                            <input type="hidden" name="login_Line_status" value="<?php echo $web['login_Line_status']; ?>">
                        </div>
                    </td>
                </tr>
                </tr>
                <!-- Outlook -->
                    <!--<tr>-->
                    <!--    <td></td>-->
                    <!--    <td width="220" align="right">-->
                    <!--        <div class="layui-btn-group">-->
                    <!--            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>-->
                    <!--            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>-->
                    <!--            <input type="hidden" name="login_Outlook_status" value="<?php echo $web['login_Outlook_status']; ?>">-->
                    <!--        </div>-->
                    <!--    </td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                    <!--    <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("Outlook_id"); ?></td>-->
                    <!--    <td style="display:flex">-->
                    <!--        <input type="text" name="login_Outlook_account" value="<?php echo $web['login_Outlook_account']; ?>" placeholder="<?php $this->lan("Outlook_id"); ?>" style="min-width:407px;width:407px" class="input-text">-->
                    <!--        <div style="z-index:100;width:200px;display:inline-block" data-title="<?php $this->lan("tips"); ?>" class="tip">-->
                    <!--            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">-->
                    <!--        </div>-->
                    <!--    </td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                    <!--    <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("Outlook_key"); ?></td>-->
                    <!--    <td>-->
                    <!--        <input type="text" name="login_Outlook_key" value="<?php echo $web['login_Outlook_key']; ?>" placeholder="<?php $this->lan("Outlook_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>-->
                    <!--    </td>-->
                    <!--</tr>-->
                <tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("TikTok_id"); ?></td>
                    <td style="display:flex">
                        <input type="text" name="login_TikTok_account" value="<?php echo $web['login_TikTok_account']; ?>" placeholder="<?php $this->lan("TikTok_id"); ?>" style="min-width:407px;width:407px" class="input-text">
                        <div style="z-index:100;width:200px;display:inline-block;margin: 15px 0 0 0 !important;padding-left: 10px" data-title="https://developers.tiktok.com" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin:0!important;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("TikTok_key"); ?></td>
                    <td style="display:flex">
                        <input type="text" name="login_TikTok_key" value="<?php echo $web['login_TikTok_key']; ?>" placeholder="<?php $this->lan("TikTok_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>
                        <div class="layui-btn-group" style="margin:0!important;display:flex;align-items:center;padding:10px;gap:10px;">
                            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>
                            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>
                            <input type="hidden" name="login_TikTok_status" value="<?php echo $web['login_TikTok_status']; ?>">
                        </div>
                    </td>
                    
                </tr>
                </tr>
                    <!--<tr>-->
                    <!--    <td></td>-->
                    <!--    <td width="220" align="right">-->
                    <!--        <div class="layui-btn-group">-->
                    <!--            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>-->
                    <!--            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>-->
                    <!--            <input type="hidden" name="login_Discord_status" value="<?php echo $web['login_Discord_status']; ?>">-->
                    <!--        </div>-->
                    <!--    </td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                    <!--    <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("Discord_id"); ?></td>-->
                    <!--    <td style="display:flex">-->
                    <!--        <input type="text" name="login_Discord_account" value="<?php echo $web['login_Discord_account']; ?>" placeholder="<?php $this->lan("Discord_id"); ?>" style="min-width:407px;width:407px" class="input-text">-->
                    <!--        <div style="z-index:100;width:200px;display:inline-block" data-title="<?php $this->lan("tips"); ?>" class="tip">-->
                    <!--            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">-->
                    <!--        </div>-->
                    <!--    </td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                    <!--    <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("Discord_key"); ?></td>-->
                    <!--    <td>-->
                    <!--        <input type="text" name="login_Discord_key" value="<?php echo $web['login_Discord_key']; ?>" placeholder="<?php $this->lan("Discord_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>-->
                    <!--    </td>-->
                    <!--</tr>-->
                <tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Telegram_id"); ?></td>
                    <td style="display:flex">
                        <input type="text" name="login_Telegram_account" value="<?php echo $web['login_Telegram_account']; ?>" placeholder="<?php $this->lan("Telegram_id"); ?>" style="min-width:407px;width:407px" class="input-text">
                        <div style="z-index:100;width:200px;display:inline-block;margin: 15px 0 0 0 !important;padding-left: 10px" data-title="https://core.telegram.org" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin:0!important;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Telegram_key"); ?></td>
                    <td style="display:flex;">
                        <input type="text" name="login_Telegram_key" value="<?php echo $web['login_Telegram_key']; ?>" placeholder="<?php $this->lan("Telegram_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>
                        <div class="layui-btn-group" style="margin:0!important;display:flex;align-items:center;padding:10px;gap:10px;">
                            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>
                            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>
                            <input type="hidden" name="login_Telegram_status" value="<?php echo $web['login_Telegram_status']; ?>">
                        </div>
                    </td>
                    
                </tr>
                </tr>
                    <!--<tr>-->
                    <!--    <td></td>-->
                    <!--    <td width="220" align="right">-->
                    <!--        <div class="layui-btn-group">-->
                    <!--            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>-->
                    <!--            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>-->
                    <!--            <input type="hidden" name="login_Wechat_status" value="<?php echo $web['login_Wechat_status']; ?>">-->
                    <!--        </div>-->
                    <!--    </td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                    <!--    <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("Wechat_id"); ?></td>-->
                    <!--    <td style="display:flex">-->
                    <!--        <input type="text" name="login_Wechat_account" value="<?php echo $web['login_Wechat_account']; ?>" placeholder="<?php $this->lan("Wechat_id"); ?>" style="min-width:407px;width:407px" class="input-text">-->
                    <!--        <div style="z-index:100;width:200px;display:inline-block" data-title="<?php $this->lan("tips"); ?>" class="tip">-->
                    <!--            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">-->
                    <!--        </div>-->
                    <!--    </td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                    <!--    <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("Wechat_key"); ?></td>-->
                    <!--    <td>-->
                    <!--        <input type="text" name="login_Wechat_key" value="<?php echo $web['login_Wechat_key']; ?>" placeholder="<?php $this->lan("Wechat_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>-->
                    <!--    </td>-->
                    <!--</tr>-->
                     <tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Zalo_id"); ?></td>
                    <td style="display:flex">
                        <input type="text" name="login_Zalo_account" value="<?php echo $web['login_Zalo_account']; ?>" placeholder="<?php $this->lan("Zalo_id"); ?>" style="min-width:407px;width:407px" class="input-text">
                        <div style="z-index:100;width:200px;display:inline-block;margin: 15px 0 0 0 !important;padding-left: 10px" data-title="https://developers.zalo.me" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin:0!important;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Zalo_key"); ?></td>
                    <td style="display:flex;">
                        <input type="text" name="login_Zalo_key" value="<?php echo $web['login_Zalo_key']; ?>" placeholder="<?php $this->lan("Zalo_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>
                        <div class="layui-btn-group" style="margin:0!important;display:flex;align-items:center;padding:10px;gap:10px;">
                            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>
                            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>
                            <input type="hidden" name="login_Zalo_status" value="<?php echo $web['login_Zalo_status']; ?>">
                        </div>
                    </td>

                </tr>
                </tr>
                <tr>

                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("lan_id"); ?></td>
                    <td style="display:flex">
                        <input type="text" name="cl_sy_app_id" value="<?php echo $web['cl_sy_app_id']; ?>"
                            placeholder="<?php $this->lan("lan_id"); ?>" style="min-width:407px;width:407px" class="input-text" />
                        <div style="z-index:100;width:200px;display:inline-block;margin: 15px 0 0 0 !important;padding-left: 10px" data-title="https://www.chuanglan.com" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin:0!important;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("lan_key"); ?></td>
                    <td style="display:flex;">
                        <input type="text" name="cl_sy_app_key" value="<?php echo $web['cl_sy_app_key']; ?>"
                            placeholder="<?php $this->lan("lan_key"); ?>" style="min-width:407px;width:407px" class="input-text" />
                        <div class="layui-btn-group" style="margin:0!important;display:flex;align-items:center;padding:10px;gap:10px;">
                            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>
                            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>
                            <input type="hidden" name="cl_sy_status" value="<?php echo $web['cl_sy_status']; ?>">
                        </div>
                    </td>
                   
                </tr>
                </tr>
                
                    <!--<tr>-->
                    <!--    <td></td>-->
                    <!--    <td width="220" align="right">-->
                    <!--        <div class="layui-btn-group">-->
                    <!--            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>-->
                    <!--            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>-->
                    <!--            <input type="hidden" name="ks_app_status" value="<?php echo $web['ks_app_status']; ?>">-->
                    <!--        </div>-->
                    <!--    </td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                    <!--    <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("kuai_id"); ?></td>-->
                    <!--    <td style="display:flex">-->
                    <!--        <input type="text" name="ks_app_id" value="<?php if (isset($web['ks_app_id'])) {
                                                                                echo $web['ks_app_id'];
                                                                            } ?>" placeholder="<?php $this->lan("kuai_id"); ?>" style="min-width:407px;width:407px" class="input-text">-->
                    <!--        <div style="z-index:100;width:200px;display:inline-block" data-title="<?php $this->lan("tips"); ?>" class="tip">-->
                    <!--            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">-->
                    <!--        </div>-->
                    <!--    </td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                    <!--    <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("kuai_key"); ?></td>-->
                    <!--    <td>-->
                    <!--        <input type="text" name="ks_app_secret" value="<?php if (isset($web['ks_app_secret'])) {
                                                                                    echo $web['ks_app_secret'];
                                                                                } ?>" placeholder="<?php $this->lan("kuai_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>-->
                    <!--    </td>-->
                    <!--</tr>-->
                <tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("beichen_id"); ?></td>
                    <td style="display:flex">
                        <input type="text" name="beichen_app_id" value="<?php if (isset($web['beichen_app_id'])) {
                                                                            echo $web['beichen_app_id'];
                                                                        } ?>" placeholder="<?php $this->lan("beichen_id"); ?>" style="min-width:407px;width:407px" class="input-text">
                        <div style="z-index:100;width:200px;display:inline-block;margin: 15px 0 0 0 !important;padding-left: 10px" data-title="https://u.beichenwl.cn" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin:0!important;">
                        </div>
                    </td>
                   
                </tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("beichen_key"); ?></td>
                    <td style="display:flex;">
                        <input type="text" name="beichen_app_key" value="<?php if (isset($web['beichen_app_key'])) {
                                                                                echo $web['beichen_app_key'];
                                                                            } ?>" placeholder="<?php $this->lan("beichen_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>
                        <div class="layui-btn-group" style="margin:0!important;display:flex;align-items:center;padding:10px;gap:10px;">
                            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>
                            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>
                            <input type="hidden" name="beichen_app_status" value="<?php echo $web['beichen_app_status']; ?>">
                        </div>
                    </td>

                </tr>

                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Login_method"); ?></td>
                    <td colspan="3">
                        <?php
                            foreach ($languages as $k => $v) { ?>
                                <div class="Login_method_img" style="display:flex;align-items: center;">
                                    <?php foreach($agglist as $key => $val) {
                                        if($v['lg_value'] == 'Chinese' && in_array($key,$zh)) {
                                    ?>
                                        <input type="checkbox" style="margin:0!important;"
                                                id="<?php echo $v['lg_value'].'beichen'.$key; ?>" 
                                                name="beichen_login_type[<?php echo $v['lg_value']; ?>][<?php echo $key;?>][value]" 
                                                <?php if (isset($web['beichen_login_type'][$v['lg_value']][$key]['value'])) {echo "checked";} ?> 
                                                value="<?php echo $key;?>">
                                        <label style="display: flex;align-items: center;margin: 0 15px 0 10px !important;gap: 10px;" for="<?php echo $v['lg_value'].'beichen'.$key; ?>"> <img width="20" height="20" src="../../statics/uploads/photo/login/<?php echo $val['img'];?>"><?php echo $val['name'];?></label>
                                    <?}elseif($v['lg_value'] != 'Chinese' && !in_array($key,$zh)){?>
                                        <input type="checkbox" style="margin:0!important;"
                                                id="<?php echo $v['lg_value'].'beichen'.$key; ?>" 
                                                name="beichen_login_type[<?php echo $v['lg_value']; ?>][<?php echo $key;?>][value]" 
                                                <?php if (isset($web['beichen_login_type'][$v['lg_value']][$key]['value'])) {echo "checked";} ?> 
                                                value="<?php echo $key;?>">
                                        <label style="display: flex;align-items: center;margin: 0 15px 0 10px !important;gap: 10px;" for="<?php echo $v['lg_value'].'beichen'.$key; ?>"> <img width="20" height="20" src="../../statics/uploads/photo/login/<?php echo $val['img'];?>"><?php echo $val['name'];?></label>
                                    <?}}?>
                                【<span style="display:inline-block;text-align: right;margin:0!important;"><?php echo $v['country']; ?></span>】
                                </div>
                        <?php } ?>   
                    </td>
                </tr>

                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Yi_wei_id","一为ID"); ?></td>
                    <td style="display:flex">
                        <input type="text" name="yiwei_app_id" value="<?php if (isset($web['yiwei_app_id'])) {
                                                                            echo $web['yiwei_app_id'];
                                                                        } ?>" placeholder="<?php $this->lan("Yi_wei_id"); ?>" style="min-width:407px;width:407px" class="input-text">
                        <div style="z-index:100;width:200px;display:inline-block;margin: 15px 0 0 0 !important;padding-left: 10px" data-title="https://iologin.cc" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin:0!important;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Yi_wei_key","一为KEY"); ?></td>
                    <td style="display:flex;">
                        <input type="text" name="yiwei_app_key" value="<?php if (isset($web['yiwei_app_key'])) {
                                                                                echo $web['yiwei_app_key'];
                                                                            } ?>" placeholder="<?php $this->lan("Yi_wei_key"); ?>" style="min-width:407px;width:407px" class="input-text"></input>
                        <div class="layui-btn-group" style="margin:0!important;display:flex;align-items:center;padding:10px;gap:10px;">
                            <button type="button" class="layui-btn open" style="background: #dc4f5d;"><?php $this->lan("Enable"); ?></button>
                            <button type="button" class="layui-btn close" style="background:#E3E5F1"><?php $this->lan("Disable"); ?></button>
                            <input type="hidden" name="yiwei_app_status" value="<?php echo $web['yiwei_app_status']; ?>">
                        </div>
                    </td>
                </tr>
                </tr>

                <tr>
                    <td width="220" align="right" style="padding-right: 10px;text-align: right !important;"><?php $this->lan("Login_method"); ?></td>
                    <td colspan="3">
                        <?php
                            foreach ($languages as $k => $v) { ?>
                                <div class="Login_method_img" style="display:flex;align-items: center;">
                                    <?php foreach($yiweilist as $key => $val) {
                                        if($v['lg_value'] == 'Chinese' && in_array($key,$zh)) {
                                    ?>
                                        <input type="checkbox" style="margin:0!important;"
                                                id="<?php echo $v['lg_value'].'yiwei'.$key; ?>" 
                                                name="yiwei_login_type[<?php echo $v['lg_value']; ?>][<?php echo $key;?>][value]" 
                                                <?php if (isset($web['yiwei_login_type'][$v['lg_value']][$key]['value'])) {echo "checked";} ?> 
                                                value="<?php echo $key;?>">
                                        <label style="display: flex;align-items: center;margin: 0 15px 0 10px !important;gap: 10px;" for="<?php echo $v['lg_value'].'yiwei'.$key; ?>"> <img width="20" height="20" src="../../statics/uploads/photo/login/<?php echo $val['img'];?>"><?php echo $val['name'];?></label>
                                    <?}elseif($v['lg_value'] != 'Chinese' && !in_array($key,$zh)){?>
                                        <input type="checkbox" style="margin:0!important;"
                                                id="<?php echo $v['lg_value'].'yiwei'.$key; ?>" 
                                                name="yiwei_login_type[<?php echo $v['lg_value']; ?>][<?php echo $key;?>][value]" 
                                                <?php if (isset($web['yiwei_login_type'][$v['lg_value']][$key]['value'])) {echo "checked";} ?> 
                                                value="<?php echo $key;?>">
                                        <label style="display: flex;align-items: center;margin: 0 15px 0 10px !important;gap: 10px;" for="<?php echo $v['lg_value'].'yiwei'.$key; ?>"> <img width="20" height="20" src="../../statics/uploads/photo/login/<?php echo $val['img'];?>"><?php echo $val['name'];?></label>
                                    <?}}?>
                                【<span style="display:inline-block;text-align: right;margin:0!important;"><?php echo $v['country']; ?></span>】
                                </div>
                        <?php } ?> 
                    </td>
                </tr>
            </table>
            <div style="margin-top: 10px;margin-left: 237px;">
                <input type="submit" class="button" name="dosubmit" value="<?php $this->lan("Submit"); ?>">
            </div>
        </form>
    </div>
</body>
<script>
    $(function() {
        img_head()
    })

    function img_head() {
        var t = $("input[name='is_photo']:checked").val()
        if (t == 1) {
            $(".img_head_defalut").css("display", "contents")
            $(".img_head_list").css("display", "none")
        } else {
            $(".img_head_list").css("display", "contents")
            $(".img_head_defalut").css("display", "none")
        }
    }
    $("input[name='is_photo']").click(function() {
        img_head()
    })
    $("#addbanner").on('click', function() {
        var id = diu_Randomize(1000, 100000)
        console.log(id)
        var html = `
			<div style="display:inline-block;margin-right: 5px;position: relative;" id="parent-${id}">
                        <img  onclick="uploadimg('上传预设头像','#imgHeadPhoto${id}','image','PresetsHeadPhoto')" id="imgHeadPhoto${id}-img" width="130px" height="130px">
                         <input type="hidden" name="imgarrList[]" id="imgHeadPhoto${id}-lack" value="">
                <i style="position: absolute;right:0;color:#3C8DBC;font-size: 20px;cursor: pointer;padding-right: 10px;" onClick="remove_keys(this)" data-parent="parent-${id}">x</i>
              </div>
        `
        var bb = $("#lunbo>div:nth-last-child(1)")
        bb.before(html)
        setTimeout(() => {
            uploadimg('上传预设头像', '#imgHeadPhoto' + id, 'image', 'PresetsHeadPhoto')
        }, 100)
    })

    function diu_Randomize(b, e) {
        if (!b && b != 0 || !e) {
            return "?";
        }
        return Math.floor((Math.random() * e) + b);
    }

    $('.biqoqian').click(function() {
        $(this).addClass('shuruon')
        $(this).find('.sjiap input').focus();
    })
    $('.sjiap input').on('keypress', function(e) {
        var keyCode = e.which;
        // 如果按下的是逗号或回车键，则触发事件
        if (keyCode === 44 || keyCode === 13) {
            if ($(this).val() == '') return
            $(this).parent().before('<div class="sinop"><div>' + $(this).val() + '</div><span class="inpsha">×</span></div>')
            var grandParent = $(this).parent().parent().find('.dohafngesr');
            var tiselistval = grandParent.val()
            if (tiselistval == '') {
                grandParent.val($(this).val())
            } else {
                grandParent.val(tiselistval + ',' + $(this).val())
            }
            $(this).val('')
            cliinpchama()
            e.preventDefault();
        }
    })
    $('.sjiap input').blur(function() {
        // 在这里编写失去焦点时想要执行的代码
        $(this).parent().parent().removeClass('shuruon')
    });

    function cliinpchama() {
        $('.inpsha').click(function() {
            var tpdiv = $(this).parent()
            tpdiv.find('div').text('')
            var trsel = tpdiv.parent().find('.sinop div')
            var thufg = ''
            trsel.map(function(index) {
                if (thufg == '') {
                    thufg = $(this).text()
                } else if ($(this).text() != '') {
                    thufg += ',' + $(this).text()
                }
            })
            tpdiv.parent().find('.dohafngesr').val(thufg)
            tpdiv.remove()
        })
    }
    $('.dohafngesr').parent().each(function(index) {
        var tjddiv = $(this).find('.sjiap')
        var czdval = $(this).find('.dohafngesr').val()
        if (czdval == '') return
        $.each(czdval.split(','), function(ind, ele) {
            tjddiv.before('<div class="sinop"><div>' + ele + '</div><span class="inpsha">×</span></div>')
        })
    });
    cliinpchama()

    //登录方式
    $('input[name="login_type[]"]').change(function() {
        var lg = $('input[name="login_type[]"]')
        var t = true
        lg.each(function(index, ele) {
            if ($(ele).prop('checked')) {
                t = false
                return
            }
        })
        if (t) {
            layer.msg("至少选择一种登录方式")
            $(this).prop("checked", true)
        }
    })
    $(".open").click(function() {
        if ($("#login_type4").prop("checked")) {
            $(this).parent().children('input').val(1)
            $(this).parent().children('.open').css("background", "#dc4f5d")
            $(this).parent().children('.close').css("background", "#E3E5F1")
        } else {
            layer.msg("请先开启第三方登录")
        }
    })

    $(".close").click(function() {
        $(this).parent().children('input').val(2)
        $(this).parent().children('.open').css("background", "#E3E5F1")
        $(this).parent().children('.close').css("background", "#dc4f5d")
    })
    $(".close").parent().children('input').each(function(index, ele) {
        if ($(ele).val() != 2) {
            $(ele).parent().children('.open').css("background", "#dc4f5d")
            $(ele).parent().children('.close').css("background", "#E3E5F1")
        } else {
            $(ele).parent().children('.open').css("background", "#E3E5F1")
            $(ele).parent().children('.close').css("background", "#dc4f5d")
        }
    })
</script>

</html>