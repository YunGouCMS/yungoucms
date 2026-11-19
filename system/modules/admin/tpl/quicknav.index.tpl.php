<!DOCTYPE html>
<html>

<head>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">

    <meta charset="utf-8">
    <title><?php echo $this->getPageTitle('后台页面'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <style>
        .layui-btn {
            background-color: #080808;
        }

        th {
            border: 0px solid #000;
        }

        .butaoin {
            color: #ffffff;
            background: #3c8dbc;
            padding: 3px 5px;
            border-radius: 5px;
            white-space: nowrap;
            cursor: pointer;
        }
        .layui-form-label {
            height: 50px!important;
            line-height: 30px!important;
        }
    </style>
</head>

<body>

    <div class="header lr10">
        <?php echo $this->headerment(); ?>
    </div>
    <div style="margin: 20px">

        <form class="layui-form layui-form-pane" action="#">
            <div class="layui-form-item">
                <label class="layui-form-label">KEY</label>
                <div class="layui-input-inline">
                    <input type="password" name="password" placeholder="<?php $this->lan("enter_p"); ?>" autocomplete="off" class="layui-input">
                </div>
                <button type="submit" class="layui-btn dosubmit" lay-submit="" lay-filter="demo1"><?php $this->lan("see"); ?></button>
                <button type="button" id="password" class="layui-btn repassword"><?php $this->lan("Change_password"); ?></button>
            </div>
        </form>
        <div id="nav_tab" style="display: none">
            <table width="100%" class="layui-table" cellspacing="0">
                <thead>
                    <tr>
                        <th style="text-align: center" width="90"><?php $this->lan("Shortcut_name"); ?></th>
                        <th style="text-align: center" align='center'><?php $this->lan("Link"); ?></th>
                        <th style="text-align: center" align='center'><?php $this->lan("operation"); ?></th>
                    </tr>

                </thead>
                <tbody id="tab_body">
                </tbody>
            </table>
        </div>

    </div>

    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <!-- 注意：如果你直接复制所有代码到本地，上述 JS 路径需要改成你本地的 -->
    <script>
        var nav = 0;
        $(".dosubmit").click(function() {
            console.log(1)
            if(nav==1){return false;}
            var password = $("input[name='password']").val()
            $.post('<?php echo G_MODULE_PATH; ?>/quicknav/getnav', {
                    value: password
                }, function(res) {
                    const result = JSON.parse(res)
                    if (result.code == 200) {
                        getNav()
                    } else {
                        layer.msg('密码错误')
                        $('#nav_tab').css('display', 'none')
                        $("#password").css('opacity', 0.01)
                        $('#tab_body').html('')
                    }
                    return false;
            })
        })
        layui.use(['form', 'layedit', 'laydate'], function() {
            var form = layui.form,
                layer = layui.layer,
                layedit = layui.layedit,
                laydate = layui.laydate;
            //监听提交
            form.on('submit(demo1)', function(data) {
                console.log(2)
                if(nav==1){return false;}
                var value = data.field.password
                $.post('<?php echo G_MODULE_PATH; ?>/quicknav/getnav', {
                    value: value
                }, function(res) {
                    const result = JSON.parse(res)
                    if (result.code == 200) {
                        getNav()
                    } else {
                        layer.msg('密码错误')
                        $('#nav_tab').css('display', 'none')
                        $("#password").css('opacity', 0.01)
                        $('#tab_body').html('')
                    }
                    return false;
                })
                return false;
            });
        });
    </script>
    <script>
        getNav()

        function getNav() {
            $.get('<?php echo G_MODULE_PATH; ?>/quicknav/jsNav', function(res) {
                const result = JSON.parse(res)
                if (result.code == 1) {
                    $("#password").css('opacity', 1)
                }
                if (result.data.length < 1) {
                    return false
                }
                $('#nav_tab').css('display', 'block')
                for (var i = 0; i < result.data.length; i++) {

                    var html = `
                        <tr>
                        <td align='center'> ${result.data[i].name}</td>
                        <td align='center'><a href="${result.data[i].domian}/?${result.data[i].d}" target="_blank">${result.data[i].domian}/?${result.data[i].d}</a></td>
                        <td align='center'>
                            <a class="butaoin" href="${result.data[i].domian}/?${result.data[i].d}" target="_blank"><?php $this->lan("access"); ?></a><span class="span_fenge lr5"></span>
                            <a class="butaoin" href="javascript:;"  onclick="openview('${result.data[i].domian}/?${result.data[i].d}')"><?php $this->lan("copy"); ?></a>
                        </td>
                    </tr>
                `
                    $('#tab_body').append(html)
                }
                nav = 1;
            })
        }

        function openview(url) {
            var input = document.createElement("input");
            document.body.appendChild(input);
            input.setAttribute("value", url);
            input.select();
            document.execCommand("copy");
            document.body.removeChild(input);
            layer.msg("复制成功");
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
    </script>
    <script>
        $('.repassword').click(function() {

            layer.open({
                type: 2,
                title: '<?php $this->lan('Change_password'); ?>',
                shadeClose: true,
                shade: 0.8,
                area: ['460px', '310px'],
                content: '<?php echo G_MODULE_PATH; ?>/quicknav/repassword'
            });
        })
    </script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>