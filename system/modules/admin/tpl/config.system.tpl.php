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

    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">

    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <!-- 后台各页面详细UI升级样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">

    <script src="<?php echo G_TEMPLATES_JS; ?>/mobile/jquery190.js" language="javascript" type="text/javascript"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js?v=1"></script>

    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.all.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <!-- 通知消息系统脚本（必须在layer之后加载） -->
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>

    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/inputTag/inputTag.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/inputTag/inputTag.js"></script>
    <!--https中请求http接口或引入http资源都会被直接blocked（阻止），浏览器默认此行为不安全，会拦截。 所以加入该标签的原理是使用META标签强制将http请求转成https(SSL协议)请求 -->
    <!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />  -->
    <style>
        tr {
            height: 40px;
            line-height: 40px
        }

        .rightgray {
            display: flex;
            align-items: center;
        }

        .rightgray div {
            width: 5px;
            background-color: #f5f5f5;
            border: 1px solid #B4B4B4;
            border-left-width: 0px;
            height: 27px;
            padding: 2px 15px;
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
            line-height: 28px;
        }

        .rightgray input {
            margin: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            width: 42px;
            min-width: 65px;
        }

        .audio-upload .input-text {
            min-width: 407px;
            width: 407px
        }

        .audio-upload input[type="file"] {
            display: none;
        }

        .audio-upload label {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .audio-upload label span {
            background-color: #3C8DBC;
            color: #ffffff;
            padding: 4px 15px;
            margin: 0 5px 0 0;
            border-radius: 5px;
            cursor: pointer;
        }

        .resouci {
            display: flex;
            margin-bottom: 10px;
        }

        .resouci:last-child {
            margin-bottom: 0;
        }
        .qiniu{
            display: none;
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
		.width80{
		    width: 80px;
		}
    </style>

</head>

<body>

    <div class="header lr10">

        <?php echo $this->headerment(); ?>

    </div>

    <div class="bk10"></div>

    <div class="table-list lr10">

        <!--start-->

        <form name="myform" id="myform" action="#" method="post">

            <table width="100%" cellspacing="0">
                <tr>
                    <td width="220" align="right" style="text-align:right!important;"><?php $this->lan("Image_storage_method");?></td>
                    <td style="display: flex;">
                        <select name="upload_type" id="upload_type" style="width: 128px;margin: 0 !important;">
                            <option value="1" <?php if ($web['upload_type'] == 1) {
                                                    echo "selected";
                                                } ?>><?php $this->lan("Local_storage");?></option>
                            <option value="2" <?php if ($web['upload_type'] == 2) {
                                                    echo "selected";
                                                } ?>><?php $this->lan("Qiniu_storage");?></option>
                        </select>
                    </td>
                    
                </tr>
                <tr class="<?php if ($web['upload_type'] == 1) {echo "qiniu";}?>">
                    <td width="220" align="right" style="text-align:right!important;">七牛云AK</td>
                    <td style="display: flex;">
                        <input type="text" value="<?php echo $web['qiniu_AccessKey'] ?>" name="qiniu_AccessKey" placeholder="请输入AccessKey" class="input-text wid200">
                    </td>
                    <td></td>
                    <td></td>
                    
                </tr>
                <tr  class="<?php if ($web['upload_type'] == 1) {echo "qiniu";}?>">
                    <td width="220" align="right" style="text-align:right!important;">七牛云SK</td>
                    <td style="display: flex;">
                        <input type="text" value="<?php echo $web['qiniu_SecretKey'] ?>" name="qiniu_SecretKey" placeholder="请输入SecretKey" class="input-text wid200">
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="<?php if ($web['upload_type'] == 1) {echo "qiniu";}?>">
                    <td width="220" align="right" style="text-align:right!important;">七牛云仓库</td>
                    <td style="display: flex;">
                        <input type="text" value="<?php echo $web['qiniu_warehouse'] ?>" name="qiniu_warehouse" placeholder="请输入七牛云仓库" class="input-text wid200">
                    </td>
                  
                    <td></td>
                    <td></td>
                </tr>
                <tr  class="<?php if ($web['upload_type'] == 1) {echo "qiniu";}?>">
                      <td width="220" align="right" style="text-align:right!important;">七牛云域名</td>
                    <td style="display: flex;">
                        <input type="text" value="<?php echo $web['qiniu_domain'] ?>" name="qiniu_domain" placeholder="请输入七牛云域名" class="input-text wid200">
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="display: table-row;">
                    <td width="220" align="right" style="text-align:right!important;"><?php $this->lan("Favicon");?></td>
                    <td>
                        <div class="layui-row">
                            <div class="layui-col-md1" style="width: auto;border:1px solid #fff;margin: 0 !important;">
                                <div class="layui-upload-list height56" style="width: 20px;height: 20px; padding: 0; margin: 0;border: 1px solid #fff;">
                                    <div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;" <?php endif; ?>>
                                        <span>+</span>
                                        <text><?php $this->lan("Click_upload");?></text>
                                    </div>
                                    <img id="imgIcoPhoto" class="layui-upload-img" src="<?php echo G_WEB_PATH . "/favicon.ico"; ?>" style="width: 20px; height: 20px;" alt="" />
                                    <p id="demoText"></p>
                                    <input class="bottoms" type="file" name="web_ico" onchange="PreviewImage(this,'imgIcoPhoto','divPreview');" style="width: 69px;" size="20" accept="image/png/ico" />
                                </div>
                            </div>
                           <div style="z-index:100;display:flex;margin: 0 0 0 10px !important;transform: translateY(-6px);"  data-title="<?php $this->lan("tips");?>" class="tip" >
                                <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
                            </div>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="220" align="right" style="text-align:right!important;"><?php $this->lan("logo");?></td>
                    <td width="620">
                        <div class="layui-row">
                            <div class="layui-col-md1 width130" style="border:1px solid #fff;margin: 0 !important;width: 125px;">
                                <div class="layui-upload-list height130">
                                    <div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;" <?php endif; ?>>
                                        <span>+</span>
                                        <text><?php $this->lan("Click_upload");?></text>
                                    </div>
                                    <img class="layui-upload-img" width="120px" src="<?php echo G_UPLOAD_PATH . '/' . $web['web_logo']; ?>" id="web_logoimg-img">
                                    <input type="hidden" id="web_logoimg-lack" name="web_logo" value="<?php echo $web['web_logo']; ?>">
                                    <p id="demoText"></p>
                                    <button type="button" onclick="uploadimg('上传平台logo','#web_logoimg','image','banner')"></button>
                                </div>
                            </div>
                            <div class="layui-col-md9">
                                <!--<?php $this->lan("tips1");?>-->
                                <div style="z-index:100;display:flex;"  data-title="<?php $this->lan("tips1");?>" class="tip" >
                                <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin: 0 !important">
                            </div>
                            </div>
                             
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                    
                </tr>
                <tr>
                    <td width="220" align="right" style="text-align:right!important;"><?php $this->lan("Home_service_code");?>
                        <!-- <br>（前台首页客服码） -->
                    </td>
                    <td colspan="12" style="display:flex;flex-wrap: wrap;width: 850px;">
                        <?php foreach ($languages as $k => $v) { ?>
                            <div class="layui-row" style="text-align:center;margin: 0 10px 0 0 !important;">
                                <div class="layui-col-md1 width80" style="border:1px solid #fff">
                                   
                                    <div class="layui-upload-list " style="padding: 30px 0;">
                                        <div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;" <?php endif; ?>>
                                            <span>+</span>
                                            <text><?php $this->lan("Click_upload");?></text>
                                        </div>
                                        <img class="layui-upload-img" width="80px" src="<?php echo G_UPLOAD_PATH . '/' . $web['qqkfewm'][$v['lg_value']]; ?>" id="<?php echo $v['lg_value'] . 'kfimg-img'; ?>">
                                        <input type="hidden" id="<?php echo $v['lg_value'] . 'kfimg-lack'; ?>" name="qqkfewm[<?php echo $v['lg_value']; ?>]" value="<?php echo $web['qqkfewm'][$v['lg_value']]; ?>">
                                        <p id="demoText"></p>
                                        <button type="button" onclick="uploadimg('上传QQ客服二维码','#<?php echo $v['lg_value']; ?>kfimg','image','banner')"></button>
                                    </div>
                                     <div class="layui-col-md-2"><?php echo $v['country']; ?></div>
                                    <!-- <p>446*800Px，支持jpg</p> -->
                                </div>
                                <!--<div class="layui-col-md7">-->
                                <!--    446*800Px，支持jpg-->
                                <!--</div>-->
                            </div>
                        <?php } ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="220" align="right" style="text-align:right!important;"><?php $this->lan("User_center_service_code");?>
                        <!-- <br>（用户中心客服码） -->
                    </td>
                    <td colspan="6" style="display:flex;flex-wrap: wrap;">
                        <?php foreach ($languages as $k => $v) { ?>
                            <div class="layui-row" style="text-align:center;margin: 0 10px 0 0 !important;">
                                <div class="layui-col-md1 width80" style="border:1px solid #fff">
                                    <!--<div class="layui-col-md-2"><?php echo $v['country']; ?></div>-->
                                    <div class="layui-upload-list" style="padding: 30px 0;">
                                        <div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;" <?php endif; ?>>
                                            <span>+</span>
                                            <text><?php $this->lan("Click_upload");?></text>
                                        </div>
                                        <img class="layui-upload-img" width="80px" src="<?php echo G_UPLOAD_PATH . '/' . $web['wxkfewm'][$v['lg_value']]; ?>" id="<?php echo $v['lg_value'] . 'wxkfewmimg-img'; ?>">
                                        <input type="hidden" id="<?php echo $v['lg_value'] . 'wxkfewmimg-lack'; ?>" name="wxkfewm[<?php echo $v['lg_value']; ?>]" value="<?php echo $web['wxkfewm'][$v['lg_value']]; ?>">
                                        <p id="demoText"></p>
                                        <button type="button" onclick="uploadimg('上传微信客服二维码','#<?php echo $v['lg_value']; ?>wxkfewmimg','image','banner')"></button>
                                    </div>
                                     <!-- <p>358*485Px，支持jpg</p>  -->
                                     <div class="layui-col-md-2"><?php echo $v['country']; ?></div>
                                </div>
                                <!-- <div class="layui-col-md9">
                                    358*485Px，支持jpg
                                </div> -->
                            </div>
                        <?php } ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
               <!--  <tr>
                    <td width="220" align="right" style="padding-right: 10px"><?php $this->lan("User_center_service_code");?>
                    </td>
                    <td colspan="6" style="display:flex;flex-wrap: wrap;">
                        <?php foreach ($languages as $k => $v) { ?>
                            <div class="layui-row" style="text-align:center">
                                <div class="layui-col-md1 width80" style="border:1px solid #fff">
                                    <div class="layui-upload-list" style="padding: 30px 0;">
                                        <div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;" <?php endif; ?>>
                                            <span>+</span>
                                            <text><?php $this->lan("Click_upload");?></text>
                                        </div>
                                        <img class="layui-upload-img" width="80px" src="<?php echo G_UPLOAD_PATH . '/' . $web['wxkfewm'][$v['lg_value']]; ?>" id="<?php echo $v['lg_value'] . 'wxkfewmimg-img'; ?>">
                                        <input type="hidden" id="<?php echo $v['lg_value'] . 'wxkfewmimg-lack'; ?>" name="wxkfewm[<?php echo $v['lg_value']; ?>]" value="<?php echo $web['wxkfewm'][$v['lg_value']]; ?>">
                                        <p id="demoText"></p>
                                        <button type="button" onclick="uploadimg('上传微信客服二维码','#<?php echo $v['lg_value']; ?>wxkfewmimg','image','banner')"></button>
                                    </div>
                                     <div class="layui-col-md-2"><?php echo $v['country']; ?></div>
                                </div>
               
                            </div>
                        <?php } ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr> -->
                <tr>
                    <td width="220" align="right" style="text-align:right!important;"><?php $this->lan("Winning_picture");?></td>
                    <td colspan="6" style="display:flex;flex-wrap: wrap;">
                        <?php foreach ($languages as $k => $v) { ?>
                            <div class="layui-row" style="text-align:center;margin: 0 10px 0 0 !important;">
                                <div class="layui-col-md1 width80" style="border:1px solid #fff">
                                    <!--<div class="layui-col-md-2"><?php echo $v['country']; ?></div>-->
                                    <div class="layui-upload-list" style="padding: 30px 0;">
                                        <div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;" <?php endif; ?>>
                                            <span>+</span>
                                            <text><?php $this->lan("Click_upload");?></text>
                                        </div>
                                        <img class="layui-upload-img" width="80px" src="<?php echo G_UPLOAD_PATH . '/' . $web['celebrate_img'][$v['lg_value']]; ?>" id="<?php echo $v['lg_value'] . 'celebrate_img-img'; ?>">
                                        <input type="hidden" id="<?php echo $v['lg_value'] . 'celebrate_img-lack'; ?>" name="celebrate_img[<?php echo $v['lg_value']; ?>]" value="<?php echo $web['celebrate_img'][$v['lg_value']]; ?>">
                                        <p id="demoText"></p>
                                        <button type="button" onclick="uploadimg('上传中奖恭喜图','#<?php echo $v['lg_value']; ?>celebrate_img','image','banner')"></button>
                                    </div>
                                    <div class="layui-col-md-2"><?php echo $v['country']; ?></div>
                                </div>
                            </div>
                        <?php } ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <div style="margin-top: 10px;margin-left: 240px;">
                <input type="submit" class="button" name="dosubmit" value="<?php $this->lan("Submit");?>">
            </div>
        </form>

    </div><!--table-list end-->

    <script>
	var host = <?php echo "'".G_UPLOAD_PATH."'"; ?>

    $("img").on("error",function(){
		$(this).attr("src",host+"/photo/ontfound.png")
	})
        $("#upload_type").change(function() {
            if ($(this).val() == "1"){
                $(".qiniu").css("display","none");
            }else{
                $(".qiniu").css("display","table-row");
            }
        })
        $('.radioval').click(function() {
            var tval = $(this).attr('value');
            if (tval == 0) {
                $('.sign').css('display', 'none')
                $('.sign_phone').css('display', 'none')
            } else {
                if ($("#web_verify_mode option:selected").val() == 2) {
                    $('.sign_phone').css('display', 'table-row')
                }
                $('.sign').css('display', 'table-row')

            }
        })
        $('#web_verify_mode').on("change", function() {
            var tval = $(this).attr('value');
            if ($("option:selected", this).val() == 2) {
                $('.sign_phone').css('display', 'table-row')
            } else {
                $('.sign_phone').css('display', 'none')
            }
        })
        if ($("#web_verify_mode option:selected").val() != 2) {
            $('.sign_phone').css('display', 'none')
        }

        function gouge() {
            var config = {
                title: '请输入谷歌验证码',
                formType: 0,
                btn2: function() {
                    //取消验证回调
                    $("input[name='is_upload'][value='1']").removeAttr("checked");
                    $("input[name='is_upload'][value='2']").attr("checked", true);
                },
                cancel: function() {
                    $("input[name='is_upload'][value='1']").removeAttr("checked");
                    $("input[name='is_upload'][value='2']").attr("checked", true);
                }
            }
            layer.prompt(config, function(pass, index) {
                $.post('<?php echo G_MODULE_PATH; ?>/setting/isuploadauth', {
                    code: pass
                }, function(res) {
                    var result = JSON.parse(res)
                    if (result.code == 1) {
                        $("input[name='is_upload'][value='2']").removeAttr("checked");
                        $("input[name='is_upload'][value='1']").attr("checked", true);
                        layer.close(index);
                        return true
                    }
                    layer.msg(result.msg)
                })

            });
        }

        $('input[type=radio][name=web_off]').change(function() {
            // 获取input radio选中值，方法二
            console.log($(this));
            var myvalue = $(this).val();
            console.log(myvalue, 123);
            if (myvalue == 0) {
                $('.off_reason').css('display', 'none')
            } else {
                $('.off_reason').css('display', '')
            }
        });

        function upImage() {
            return document.getElementById('imgfield').click();
        }
    </script>

    <!-- 昵称限制 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/layui/2.6.8/layui.js" integrity="sha512-lH7rGfsFWwehkeyJYllBq73IsiR7RH2+wuOVjr06q8NKwHp5xVnkdSvUm8RNt31QCROqtPrjAAd1VuNH0ISxqQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // 假设你的表单有一个ID为'myform'
        document.getElementById('myform').addEventListener('keypress', function(event) {
            // 检查按下的键是否是回车键
            if (event.key === 'Enter') {
                // 阻止事件的默认行为
                event.preventDefault();
            }
        });

        layui.config({
            base: './'
        }).use(['inputTag', 'jquery'], function() {
            var $ = layui.jquery,
                inputTag = layui.inputTag;
            inputTag.render({
                elem: '.tag2',
                data: ['你好', '世界', '汤姆', '杰瑞'],
                permanentData: ['世界'],
                onChange: function(data, value, type) {
                    console.log(arguments);
                    $('#tag2').text(JSON.stringify(data));
                }
            });
        })

        function Previewaudio(fileObj) {
            console.log(fileObj.files, fileObj.files[0].name)
            $(this).addClass('s213')
            $(this).next().text(fileObj.files[0].name)
        }
        $('#audupl1').change(function(obj) {
            console.log(obj, obj.files, obj.files[0].name)
        })
        $(document).ready(function(){
            $("img").css("display","block")
        })
    </script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>