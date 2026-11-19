<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>

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
    <!--Google_verification_code-->
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <script src="<?php echo G_TEMPLATES_JS; ?>/mobile/gt.js" language="javascript" type="text/javascript"></script>

    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>

    <style>
        tr {
            height: 40px;
            line-height: 40px
        }

        .input-text {
            width: 150px !important;
        }

        .layui-btn {
            background-color: #080808;
        }

        th {
            border: 0px solid #000;
        }

        .layui-form-switch {
            margin: 4px 0 4px;
        }

        .saomabangd {
            padding: 10px 0 0 10px;
        }

        .saomabangd .saobut {
            padding: 20px 0 0 160px;
        }

        .saomabangd .checkcode {
            display: flex;
            margin-left: 50px;
        }

        .saomabangd .checkcode .codeimg {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 14px;color: #444;
        }
        .codeimg div {
            position: relative;
            margin-top: 5px;
        }
        /* 谷歌验证码图片与加载占位同尺寸，避免布局抖动 */
        .codeimg .ggqr-container {
            width: 150px;
            height: 150px;
            position: relative;
            background: #f8fafc;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .codeimg .ggqr-container .ggqr-loading {
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            border-radius: 8px;
        }
        .codeimg .ggqr-container .ggqr-loading svg {
            width: 48px;
            height: 48px;
            display: block;
        }
        .codeimg .ggqr-container .ggqr-loading .ggqr-spinner {
            animation: ggqr-spin 0.8s linear infinite;
        }
        @keyframes ggqr-spin {
            to { transform: rotate(360deg); }
        }
        .codeimg #testImg {
            position: absolute;
            top: 0;
            left: 0;
            width: 150px;
            height: 150px;
            object-fit: contain;
        }
        /* 已绑定时二维码马赛克遮挡，防止他人扫码 */
        .codeimg .ggqr-container.ggqr-bound {
            overflow: hidden;
        }
        .codeimg .ggqr-container.ggqr-bound .ggimg {
            filter: blur(14px);
            transform: scale(1.08);
        }
        .codeimg .ggqr-container .ggqr-mask {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            z-index: 10;
            pointer-events: auto;
        }
        .codeimg .ggqr-container.ggqr-bound .ggqr-mask {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .codeimg .ggqr-container .ggqr-mask-mosaic {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' xmlns='http://www.w3.org/2000/svg'%3E%3Crect fill='%23333' width='8' height='8'/%3E%3Crect fill='%23555' x='8' y='0' width='8' height='8'/%3E%3Crect fill='%23555' x='0' y='8' width='8' height='8'/%3E%3Crect fill='%23333' x='8' y='8' width='8' height='8'/%3E%3C/svg%3E");
            background-size: 16px 16px;
            opacity: 0.9;
            border-radius: 8px;
            z-index: 11;
        }
        .codeimg .ggqr-container .ggqr-mask-text {
            position: relative;
            z-index: 12;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        }
        .saomabangd .checkcode img.ggimg {
            width: 150px;
            height: 150px;
        }
        .saomabangd .checkcode img:not(.ggimg) {
            width: 150px;
            height: 150px;
        }

        .saomabangd .checkcode .coderight {
            padding: 30px 0 0 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .saomabangd .checkcode .coderight p {
            padding-bottom: 5px;
        }
        .geetest_holder.geetest_wind {
            min-width: 200px!important;
        }
        .wid125{
           width: 125px!important;
        }
        /* 谷歌身份验证器扫码 文案美化 */
        .codeimg .gg-scan-title {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
            letter-spacing: 0.02em;
            margin-bottom: 8px;
            display: inline-block;
        }
        .codeimg .gg-scan-title:hover {
            color: #3c8dbc;
        }
        /* 谷歌验证码：输入框固定 150px，按钮与输入框同一行不换行 */
        .gg-verify-row {
            display: flex !important;
            align-items: center;
            gap: 10px;
            flex-wrap: nowrap !important;
        }
        .gg-verify-row input[name="gg_code"],
        #gg_code_input {
            width: 150px !important;
            min-width: 150px !important;
            max-width: 150px !important;
            flex: 0 0 auto;
            box-sizing: border-box !important;
        }
        img[src=""],img:not([src]){
            opacity:0;
        }
        table tbody td.layui-form .layui-form-switch, .table-list table tbody td.layui-form .layui-unselect.layui-form-switch.layui-form-onswitch {
            margin: 0!important;
        }
    </style>
</head>

<body>
    <div class="header lr10">
        <h3 class="nav_icon"> <?php $this->lan("Google_verification_code"); ?></h3>
        <span class="span_fenge lr10"></span><a href="/<?php echo ROUTE_M ?>/setting/setLogin"> <?php $this->lan("Google_verification_code"); ?></a><span class="span_fenge lr5"></span>
    </div>
    <div class="saomabangd">
        <?php $is_google_bound = !empty($google_bound); ?>
        <div class="checkcode">
            <div class="codeimg">
                <a href="https://apps.apple.com/sg/app/google-authenticator/id388497605?l=zh" target="_blank" class="gg-scan-title"><?php $this->lan("scan_code"); ?></a>
                <div class="ggqr-container<?php echo $is_google_bound ? ' ggqr-bound' : ''; ?>">
                    <div class="ggqr-loading" id="ggqrLoading">
                        <svg class="ggqr-spinner" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <circle cx="25" cy="25" r="20" fill="none" stroke="#cbd5e1" stroke-width="3"/>
                            <circle cx="25" cy="25" r="20" fill="none" stroke="#3c8dbc" stroke-width="3" stroke-dasharray="80 45" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <img src="<?php echo $qrCodeUrl; ?>" class="ggimg" id="testImg" loading="lazy"/>
                    <div class="ggqr-mask" aria-hidden="true">
                        <span class="ggqr-mask-mosaic"></span>
                        <span class="ggqr-mask-text">已绑定<br/>不可扫码</span>
                    </div>
                </div>
            </div>
            <div class="coderight">
                <div>
                    <p style="font-size: 14px;color: #444;"><?php $this->lan("valid_for"); ?><span id="countdown">30<?php $this->lan("second"); ?></span</p>
                    <p style="font-size: 14px;color: #444;"><?php $this->lan("multiple_mobile"); ?></p>
                </div>
                <div class="gg-verify-row" id="ggVerifyRow" style="font-size: 14px; color: #444;">
                    <span id="ggInputWrap" style="<?php echo $is_google_bound ? 'display:none;' : ''; ?>"><input name="gg_code" id="gg_code_input" class="input-text" maxlength="6" placeholder="<?php $this->lan("Google_verification_code"); ?>"></span>
                    <span id="ggResetWrap" style="<?php echo $is_google_bound ? '' : 'display: none;'; ?>"><button type="button" class="button" id="gg_reset_btn">重置验证码</button></span>
                    <button type="button" class="button" id="gg_submit" <?php echo $is_google_bound ? ' disabled="disabled" title="已绑定" ' : ''; ?>><?php echo $is_google_bound ? '已绑定' : $this->lan("verify_bind"); ?></button>
                </div>
            </div>
        </div>
        
    </div>
    <div class="header lr10">
        <h3 class="nav_icon"> <?php $this->lan("Backend_login_verification"); ?></h3>
    </div>
    <div class="bk10"></div>
    <div class="table-list lr10">
        <!--start-->
        <form name="myform" action="#" method="post">

            <table width="100%" cellspacing="0">

                <tr>
                    <td width="100" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Backend_login_verification"); ?></td>
                    <td class="layui-form" style="display: flex;
    height: inherit;">
                        <input type="checkbox" class="is_state sta" name="web_verify" <?php if ($switch == 1) {
                                                                                            echo 'checked=""';
                                                                                        } ?> lay-skin="switch" lay-filter="is_state" lay-text=" <?php $this->lan("open"); ?>| <?php $this->lan("close"); ?>">
                    </td>
                </tr>
                <tr>

                    <td width="100" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Backend_verification_method"); ?></td>

                    <td style="display: flex;">
                        <select name="web_verify_mode" id="" style="margin: 0!important;">

                            <?php foreach ($array as $k => $v) { ?>

                                <option value="<?php echo $v['id']; ?>" <?php echo $v['id'] == $config ? 'selected' : ''; ?>><?php echo $v['title']; ?></option>
                                <!--                        <option value="--><?php //echo $web['timezone'];
                                                                                ?><!--" >亚洲/上海</option>-->
                                <!--                                                <input type="text" name="timezone" value=" echo $web['timezone']; " placeholder="上海时间: Asia/Shanghai  重庆时间: Asia/Chongqing" class="input-text">-->
                            <?php } ?>
                        </select>
                        <span></span>

                    </td>

                </tr>

                <tr style="<?php if ($config != 1) {
                                echo 'display:none;';
                            } ?>" class="zf">
                    <td width="200" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Character_verification_code"); ?></td>
                    <td>

                        <div class="input_box lf wid260" style="display: flex;
    align-items: center;">
                            <div class="lf input_value">
                                <input type="text" id="input-c" class="input-text wid125" maxlength="6" name="code" value="" <?php if (!_cfg("web_verify")) { ?> disabled="disabled" <?php } ?> placeholder=" <?php $this->lan("tips11"); ?>">
                            </div>
                            <div class="lf input_value">
                                <img id="checkcode" class="vam" src="<?php echo G_MODULE_PATH; ?>/checkcode/image/80_27/" />
                            </div>
                            <div class="cl"></div>
                        </div>
                    </td>
                </tr>
                <tr style="<?php if ($config != 2) {
                                echo 'display:none;';
                            } ?>" class="sj">
                    <td width="280" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Mobile_verification_rendering"); ?></td>
                    <td>
                        <div class="input_box lf wid260">
                            <input type="hidden" id="phone" value="<?php echo $phone; ?>">
                            <div class="lf input_value">
                                <input type="text" id="input-c" class="input-text wid125" name="code" value="" placeholder=" <?php $this->lan("tips11"); ?>">
                            </div>

                            <button class="getAdminCode layui-btn layui-btn-sm" type="button"> <?php $this->lan("Obtain_verification_code"); ?></button>
                            <div class="cl"></div>
                        </div>
                    </td>
                </tr>
                <tr style="<?php if ($config != 3) {
                                echo 'display:none;';
                            } ?>" class="gg">
                    <td width="200" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Google_verification_rendering"); ?></td>
                    <td>
                        <div class="input_box lf wid320">
                            <div class="lf input_value">
                                <!-- <input type="text" id="input-c" class="input-text wid100" name="code" value="" placeholder=" <?php $this->lan("tips11"); ?>"> -->
                            </div>
                        </div>
                        <button type="button" class="layui-btn layui-btn-sm" id="updateggimg" onClick="getQr(1)">
                            重新生成谷歌二维码
                        </button>
                        <p>重新生成后需提交才可生效</p>
                        <input type="hidden" name="gg_is_new" id="gg_is_new" value="0">
                        <!-- <button type="button" class="layui-btn layui-btn-sm" id="updateimgbtnqq"> -->
                    </td>
                </tr>
                <tr style="<?php if (1||$config != 3) {
                                echo 'display:none;';
                            } ?>" class="gg1">
                    <td>
                        谷歌二维码
                    </td>
                    <td>
                    <img src="" class="ggimg" id="ggimg">

                    </td>
                </tr>
                <tr style="<?php if ($config != 4) {
                                echo 'display:none;';
                            } ?>" class="sf">
                    <td width="220" align="right" style="padding-right: 10px;"> <?php $this->lan("Algorithm_validation_rendering"); ?></td>
                    <td>

                        <div class="input_box lf wid260">
                            <div class="lf input_value">
                                <input type="text" id="input-c" class="input-text wid125" name="code" value="" <?php if (!_cfg("web_verify")) { ?> disabled="disabled" <?php } ?> placeholder=" <?php $this->lan("tips11"); ?>">
                            </div>
                            <div class="lf input_value">
                                <img id="checkcode" class="vam" src="<?php echo G_MODULE_PATH; ?>/checkcode/suanshuimg" style="height: 34px" />
                            </div>
                            <div class="cl"></div>
                        </div>
                    </td>
                </tr>
                <tr style="<?php if ($config != 5) {
                                echo 'display:none;';
                            } ?>" class="xw">
                    <td width="220" align="right" style="padding-right: 10px;"> <?php $this->lan("Behavior_verification_effect_diagram"); ?></td>
                    <td>
                        <div class='Lcodes'>
                            <div id="embed-captcha"></div>
                            <p id="wait" class="show"></p>
                        </div>
                    </td>
                </tr>
                <tr class="sign_phone">
                    <td width="100" align="right" style="padding-right: 10px"> <?php $this->lan("Backend_verification_phone"); ?></td>
                    <td>
                        <input type="text" name="code_phone" maxlength="11" value="<?php echo substr_replace($code_phone, '****', 3, 4); ?>" placeholder=" <?php $this->lan("tips10"); ?>" class="input-text wid125">
                    </td>
                </tr>

                <tr>
                    <td width="100" align="right"></td>
                    <input type="hidden" name="dosubmit" value="1">
                    <td style="display: flex"><input type="submit" class="button" name="dosubmit" style="margin: 0!important;" value=" <?php $this->lan("Submit"); ?> "></td>

                </tr>

            </table>
        </form>

    </div><!--table-list end-->

    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/global.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <script>
    (function () {
        var countdown = document.getElementById("countdown"), second = 30, timer = null;
        timer = setInterval(function () {
            if (second > 0) {
                second--;
                second = second<10 ? '0'+second :second
                countdown.innerHTML = second + "<?php $this->lan('second'); ?>" ;
            } else {
                countdown.innerHTML = "刷新中"
                second = 31
                // clearInterval(timer);
                // timer = null;
            }
        }, 1000);
    })()
        layui.use(['form', 'jquery'], function() {
            var form = layui.form,
                $ = layui.jquery;

            form.render('checkbox');
            form.on('switch(is_state)', function(data) {
                var zhi = '';
                if (data.elem.checked == true) {
                    zhi = 1;
                } else if (data.elem.checked == false) {
                    zhi = 0;
                }
                $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/setting/setLoginVerify', {
                    status: zhi
                }, function(data) {
                    data = JSON.parse(data);
                    if (data.code == 200) {
                        layer.msg('修改成功');
                    } else {
                        layer.msg('修改失败');
                    }
                });
            });
            form.render();

        });
        $("#updateimgbtnqq").on('click', function() {
            layer.open({
                type: 2,
                title: '谷歌二维码 <span style="color: red">扫码后请勿再次打开，会导致之前生成的失效</span>',
                shadeClose: true,
                shade: false,
                maxmin: false, //开启最大化最小化按钮
                area: ['578px', '380px'],
                content: "<?php echo G_MODULE_PATH; ?>/checkcode/code"
            });
        })
        var handlerEmbed = function(captchaObj) {
            $("#embed-submit").click(function(e) {
                var validate = captchaObj.getValidate();
                if (!validate) {
                    $("#notice")[0].className = "show";
                    setTimeout(function() {
                        $("#notice")[0].className = "hide";
                    }, 2000);
                    e.preventDefault();
                }
            });
            // 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
            captchaObj.appendTo("#embed-captcha");
            captchaObj.onReady(function() {
                $("#wait")[0].className = "hide";
            });
            // 更多接口参考：http://www.geetest.com/install/sections/idx-client-sdk.html
        };
        $.ajax({
            // 获取id，challenge，success（是否启用failback）
            url: "<?php echo WEB_PATH; ?>/mobile/ajax/StartCaptchaServlet/t=" + (new Date()).getTime(), // 加随机数防止缓存
            type: "get",
            dataType: "json",
            success: function(data) {
                // 使用initGeetest接口
                // 参数1：配置参数
                // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
                initGeetest({
                    gt: data.gt,
                    challenge: data.challenge,
                    new_captcha: data.new_captcha,
                    product: "embed", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                    offline: !data.success, // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                    width: '200px',
                    // lang: 'mr'
                    // 更多配置参数请参见：http://www.geetest.com/install/sections/idx-client-sdk.html#config
                }, handlerEmbed);
            }
        });
        $('.sign_phone').attr('style', 'display:none');
        $('select[name="web_verify_mode"]').change(function() {
            var status = $(this).val();
            switch (status) {
                case '1':
                    console.log(status);
                    $('.zf').attr('style', '');
                    $('.sign_phone').attr('style', 'display:none');
                    $('.sj').attr('style', 'display:none');
                    $('.gg').attr('style', 'display:none');
                    $('.sf').attr('style', 'display:none');
                    $('.xw').attr('style', 'display:none');
                    break;
                case '2':
                    $('.zf').attr('style', 'display:none');
                    $('.sign_phone').attr('style', '');
                    $('.sj').attr('style', '');
                    $('.gg').attr('style', 'display:none');
                    $('.sf').attr('style', 'display:none');
                    $('.xw').attr('style', 'display:none');
                    break;
                case '3':
                    $('.sign_phone').attr('style', 'display:none');
                    $('.zf').attr('style', 'display:none');
                    $('.sj').attr('style', 'display:none');
                    $('.gg').attr('style', '');
                    $('.sf').attr('style', 'display:none');
                    $('.xw').attr('style', 'display:none');
                    getQr()
                    break;
                case '4':
                    $('.sign_phone').attr('style', 'display:none');
                    $('.zf').attr('style', 'display:none');
                    $('.sj').attr('style', 'display:none');
                    $('.gg').attr('style', 'display:none');
                    $('.sf').attr('style', '');
                    $('.xw').attr('style', 'display:none');
                    break;
                case '5':
                    $('.sign_phone').attr('style', 'display:none');
                    $('.zf').attr('style', 'display:none');
                    $('.sj').attr('style', 'display:none');
                    $('.gg').attr('style', 'display:none');
                    $('.sf').attr('style', 'display:none');
                    $('.xw').attr('style', '');
                    break;
                default:
                    console.log(status);
            }
            console.log(status);
        })

        function getQr(type=0){
            if(type==1){
                $("#gg_is_new").val(1)
            }
            $.post("<?php echo WEB_PATH .'/'. G_ADMIN_DIR; ?>/checkcode/getQr",{type:type},function(res){
                res = JSON.parse(res)
                $("#ggqrLoading").show();
                $(".ggimg").attr("src",res.data.img)
                console.log(res.data.img)
            })
        }
        getQr()
        // 谷歌验证码图片加载完成后隐藏加载占位
        $("#testImg").on("load", function(){ $("#ggqrLoading").hide(); });
        $("#testImg").on("error", function(){ $("#ggqrLoading").show(); });
        if (document.getElementById("testImg") && document.getElementById("testImg").complete && document.getElementById("testImg").naturalWidth > 0) {
            $("#ggqrLoading").hide();
        }

        function textToImg(name, width, height, color) {
            //名字
            name = name || '';
            //图像大小
            width = width || 60;
            height = height || 60;
            //背景颜色
            var colours = [
                    "#1abc9c", "#2ecc71", "#3498db", "#9b59b6", "#34495e", "#16a085", "#27ae60", "#2980b9", "#8e44ad", "#2c3e50",
                    "#f1c40f", "#e67e22", "#e74c3c", "#00bcd4", "#95a5a6", "#f39c12", "#d35400", "#c0392b", "#bdc3c7", "#7f8c8d"
                ],
                nameSplit = String(name).split(' '),
                initials, charIndex, colourIndex, canvas, context, dataURI;
            if (nameSplit.length == 1) {
                initials = nameSplit[0] ? nameSplit[0].charAt(0) : '?';
            } else {
                initials = nameSplit[0].charAt(0) + nameSplit[1].charAt(0);
            }
            //上面对名字进行一系列处理，下面找到绘图元素
            var canvas = document.getElementById('headImg');
            charIndex = (initials == '?' ? 72 : initials.charCodeAt(0)) - 64;
            colourIndex = charIndex % 20;
            //图像大小
            canvas.width = width;
            canvas.height = height;
            context = canvas.getContext("2d");
            //图像背景颜色
            context.fillStyle = color ? color : colours[colourIndex - 1];
            context.fillRect(0, 0, canvas.width, canvas.height);
            //字体大小
            context.font = Math.round(canvas.width / 2) + "px 'Microsoft Yahei'";
            context.textAlign = "center";
            //字体颜色
            context.fillStyle = "#FFF";
            //绘制位置
            context.fillText(initials, width / 2, height / 1.5);
            //显示图像
            return canvas.toDataURL("image/png");
        };

        $("#gg_submit").click(function(){
            if ($(this).text().indexOf("已绑定") >= 0) return;
            var code = $.trim($("input[name='gg_code']").val());
            if (!code) {
                layer.msg('请输入谷歌验证码');
                return;
            }
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/checkcode/googleSign', {
                    code: code
                }, function(res) {
                    var result = JSON.parse(res);
                    if (result.code == 200) {
                        layer.msg(result.msg || '绑定成功');
                        $("#ggInputWrap").hide();
                        $("#ggResetWrap").show();
                        $("#gg_submit").text("已绑定").prop("disabled", true);
                        $(".ggqr-container").addClass("ggqr-bound");
                    } else {
                        layer.msg(result.msg || '验证码输入错误');
                    }
                }).fail(function(){
                    layer.msg('请求失败，请重试');
                });
        });
        $(document).on("click", "#gg_reset_btn", function(){
            var resetUrl = "<?php echo WEB_PATH . '/' . G_ADMIN_DIR; ?>/checkcode/resetGoogleBind";
            if (typeof layer !== 'undefined' && layer.msg) layer.msg('正在重置…', { icon: 16, shade: 0.3, time: 0 });
            $.post(resetUrl, {}, function(res){
                var result;
                try { result = typeof res === 'string' ? JSON.parse(res) : res; } catch(e) {
                    if (typeof layer !== 'undefined' && layer.msg) layer.msg('返回数据异常');
                    if (typeof layer !== 'undefined' && layer.closeAll) layer.closeAll();
                    return;
                }
                if (typeof layer !== 'undefined' && layer.closeAll) layer.closeAll();
                var ok = (result && (result.code == 200 || result.code === 200 || result.code == 1));
                if (ok) {
                    if (typeof layer !== 'undefined' && layer.msg) layer.msg(result.msg || '已重置', { time: 1200 });
                    setTimeout(function(){ location.reload(); }, 1300);
                } else {
                    if (typeof layer !== 'undefined' && layer.msg) layer.msg((result && result.msg) ? result.msg : '重置失败');
                }
            }).fail(function(xhr, status, err){
                if (typeof layer !== 'undefined' && layer.closeAll) layer.closeAll();
                if (typeof layer !== 'undefined' && layer.msg) layer.msg('请求失败，请重试');
            });
        });
        
         
        // const container = document.querySelector('.image-container');
        // const imageUrl = 'https://ygc.taolong.com/statics/uploads/photo/member.jpg';
        const img = new Image();
            img.src = imageUrl;
            img.onload = () => {
            $('#testImg').style.backgroundImage = `url('${img.src}')`;
        }; 
    </script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>