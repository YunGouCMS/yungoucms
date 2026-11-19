<!doctype html>
<html lang="en">
<head>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>

    <style>
        #box{
            height: 150px;
            padding: 20px;
        }
        .layui-form-label{
            width: 40px;
            padding-left: 10px;
        }
    </style>
</head>
<body>

<script>
    function opensss() {
        var openhtml = `
             <div id="box">
                <form class="layui-form" action="<?php echo $url;?>" method="post">
                    <div class="layui-form-item">
                        <label class="layui-form-label">验证码</label>
                        <div class="layui-input-inline">
                            <input type="text" id="password" name="password" placeholder="谷歌验证码" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                        <div class="layui-input-inline">
                             <button type="submit" class="layui-btn" style="background-color: #161e22;" lay-submit="" lay-filter="demo1">确定</button>
                        </div>
                    </div>
                </form>
            </div>
        `
        Win.layer.open({
            type: 1,
            title:'请输入谷歌验证码才能进行下载',
            content:openhtml,
            cancel: function(index, layero){
                delCookie('download_url')
                window.location.href = "<?php echo $back;?>"
                return false;
            },
            end:function (){
                delCookie('download_url')
                return false;
            }
        })
    }
    //window.location.href = 'http://upos.yungou.com/admin/dingdan/output_excel/'
    setTimeout(()=>{
        opensss()
    },500)
    function delCookie(name)
    {
        var exp = new Date();
        exp.setTime(exp.getTime() - 1);
        var cval=getCookie(name);
        if(cval!=null)
            document.cookie= name + "="+cval+";expires="+exp.toGMTString();
    }
    function getCookie(name)
    {
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg))
            return unescape(arr[2]);
        else
            return null;
    }

    // 默认密码123456
    // form.on('submit(demo1)', function(data){
    //         var value = data.field.password
    //         $.post('<?php echo G_MODULE_PATH; ?>/quicknav/getnav',{value:value},function (res) {
    //             const result = JSON.parse(res)
    //             if(result.code == 1){
    //                 getNav()
    //             }else {
    //                 layer.msg('密码错误')
    //                 $('#nav_tab').css('display','none')
    //                 $("#password").css('opacity',0.01)
    //                 $('#tab_body').html('')
    //             }
    //         })
    //     return false;
    // });
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>