<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>发送短信消息</title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-3.4.1.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/vue/vue.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <!-- 高级骨架屏系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <!-- 统一后台页面美化样式（参考webcfg标准） -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
</head>
<style>
    #app{
        margin: 50px auto;
        padding-right: 50px;;
    }
</style>
<body>
<div id="app" class="container">
    <div class="layui-form">
<!--        <div class="layui-form-item">-->
<!--            <label class="layui-form-label">标题</label>-->
<!--            <div class="layui-input-block">-->
<!--                <input type="text" name="title" required  v-model="send.titles" lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">-->
<!--            </div>-->
<!--        </div>-->
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">用户 ID</label>
            <div class="layui-input-block">
               <textarea v-model="send.userIds" placeholder="选填 可填入多个ID用英文逗号分开 1,2,3,4" class="layui-textarea"></textarea>
            </div>
        </div>
<!--        <div class="layui-form-item">-->
<!--            <label class="layui-form-label">用户ID</label>-->
<!--            <div class="layui-input-block">-->
<!--                <input type="text" name="userIds" required  v-model="send.userIds" lay-verify="required" placeholder="请输入用户ID，次处为空则发全部" autocomplete="off" class="layui-input">-->
<!--                <span style="color: red; size: 15px;">（选填 可填入多个ID用英文逗号分开 1,2,3,4）</span>-->
<!--            </div>-->
<!--        </div>-->
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
<!--                <button class="layui-btn" @click="gugecode">发送</button>-->
                <input type="button" class="button" @click="sendmsg" value="发送" >
            </div>
        </div>
    </div>
</div>
</body>
<script>
    let vm = new Vue({
        el:'#app',
        data:{
            send:{
                content:'',
                titles:'',
                userIds:''
            },
        },
        created() {

        },
        destroyed() {

        },
        methods: {
            gugecode(){
                if (this.send.content=='' || this.send.title==''){
                    return layer.msg("发送内容不能为空")
                }
                var _this = this
                var config ={
                    title: '请输入谷歌验证码',
                    formType: 0,
                }
                layer.prompt(config, function(pass, index){
                    $.post('/<?php echo G_ADMIN_DIR; ?>/setting/isuploadauth',{code:pass},function (res) {
                        var result = JSON.parse(res)
                        if (result.code==1){
                            _this.sendmsg()
                            _this.initWebSocket()
                            layer.close(index);
                            return true
                        }
                        layer.msg(result.msg)
                    })

                });
            },
            sendmsg(){

                $.post('/member/member/sendMessage',this.send,function (res) {
                    var result = JSON.parse(res)
                    if (result.code==1){
                        return true
                    }
                    layer.msg(result.msg)
                })
            }
        },
    })
</script>
</html>