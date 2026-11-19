<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!--<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">-->
    <title><?php echo $this->getPageTitle('发送系统消息站内信'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-3.4.1.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/vue/vue.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
	<!-- 高级骨架屏系统样式 -->
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
	<!-- 统一后台页面美化样式 -->
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
	<!-- 后台通用页面增强样式 -->
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
	<!-- 通知消息系统样式 -->
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
	<!-- 后台各页面详细UI升级样式 -->
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
	<link type="text/css" href="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/sample/css/sample.css" rel="stylesheet" media="screen" />
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/ckeditor.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/translations/zh-cn.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>

<style>
    /* 发送消息页面美化样式 */
    body {
        background: #f5f7fa;
    }
    
    .header.lr10 {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    #app {
        margin: 10px auto;
        padding: 0 20px;
        max-width: 1200px;
    }
    
    .container {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    /* layui 表单美化 */
    .layui-form {
        width: 100%;
    }
    
    .layui-form-item {
        margin-bottom: 24px;
    }
    
    .layui-form-label {
        width: 120px !important;
        font-weight: 600;
        color: #333;
        padding: 10px 15px;
    }
    
    .layui-input-block {
        margin-left: 120px !important;
    }
    
    /* 输入框美化 */
    .layui-input {
        border: 1px solid #e1e8ed !important;
        border-radius: 6px !important;
        padding: 10px 12px !important;
        font-size: 14px !important;
        transition: all 0.3s ease !important;
        background: #fff !important;
    }
    
    .layui-input:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
        outline: none !important;
    }
    
    /* 按钮美化 */
    .button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: #fff !important;
        padding: 12px 24px !important;
        border-radius: 6px !important;
        border: none !important;
        cursor: pointer !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3) !important;
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
    }
    
    /* CKEditor 编辑器美化 */
    main {
        margin-top: 10px;
    }
    
    .document-editor {
        border: 1px solid #e1e8ed;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .document-editor:focus-within {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .toolbar-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 1px solid #e1e8ed;
        padding: 8px 12px;
        border-radius: 8px 8px 0 0;
    }
    
    .toolbar-container .ck-toolbar {
        border: none !important;
        background: transparent !important;
    }
    
    .toolbar-container .ck-toolbar__separator {
        background: #e1e8ed !important;
    }
    
    #editor {
        min-height: 400px;
        padding: 20px;
        background: #fff;
        border-radius: 0 0 8px 8px;
    }
    
    #editor:focus {
        outline: none;
    }
    
    /* 编辑器内容区域美化 */
    .ck-editor {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .ck-editor__editable {
        min-height: 400px !important;
        border: none !important;
        box-shadow: none !important;
        padding: 20px !important;
        font-size: 14px !important;
        line-height: 1.6 !important;
    }
    
    .ck-editor__editable:focus {
        box-shadow: none !important;
    }
    
    .ck-editor__editable.ck-focused {
        box-shadow: none !important;
    }
    
    /* 编辑器工具栏按钮美化 */
    .ck-toolbar__items .ck-button {
        border-radius: 4px !important;
        transition: all 0.2s ease !important;
    }
    
    .ck-toolbar__items .ck-button:hover {
        background: #f0f0f0 !important;
    }
    
    .ck-toolbar__items .ck-button.ck-on {
        background: #e8f0fe !important;
        color: #667eea !important;
    }
    
    /* 提示文字美化 */
    span[style*="color: red"] {
        display: block;
        margin-top: 8px;
        font-size: 13px;
        color: #e53e3e !important;
    }
    
    /* 响应式设计 */
    @media (max-width: 768px) {
        #app {
            padding: 0 10px;
        }
        
        .container {
            padding: 20px;
        }
        
        .layui-form-label {
            width: 100% !important;
            text-align: left !important;
        }
        
        .layui-input-block {
            margin-left: 0 !important;
        }
        
        .layui-input {
            width: 100% !important;
        }
    }
</style>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
</div>
<div id="app" class="container">
    <div class="layui-form">

        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("message_title");?></label>
            <div class="layui-input-block">
                <input type="text" style="width:300px" name="title" required  v-model="send.data.titles" lay-verify="required" placeholder="<?php $this->lan("message_title");?>" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label"><?php $this->lan("content");?></label>
            <div class="layui-input-block">
                <!-- <textarea name="desc" placeholder="请输入内容" class="layui-textarea"></textarea> -->
				<main>
					<div class="document-editor">
						<div class="toolbar-container" style="margin:0!important;"></div>
						<div name="editorValue" ref="editor" id="editor" style="margin:0!important;">
							<?php echo $shopinfo['content'];?>
						</div>
					</div>
				</main>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("Member_id");?></label>
            <div class="layui-input-block">
                <input type="text" name="userIds" required  v-model="send.data.userIds" lay-verify="required" @keyup="keyupUser" placeholder="<?php $this->lan("tips");?>" autocomplete="off" class="layui-input">
                <span style="color: red; size: 15px;"></span>
            </div>
            
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <!-- <button class="layui-btn" @click="gugecode">发送</button> -->
                <input type="submit" name="dosubmit" class="button dosubmit" @click="gugecode" value="<?php $this->lan("send");?>" />
                <!--<input type="button" class="button" @click="sendmsgs" value="发送" >-->
            </div>
        </div>
    </div>
</div>

</body>
<script>
	window.onload=function(){
		
		DecoupledEditor
			.create( document.querySelector( '#editor' ), {
				language: 'zh-cn',
				ckfinder: {
					uploadUrl: '/api/tjupload/upeditimage'
				}
			} )
			.then( editor => {
				const toolbarContainer = document.querySelector( '.toolbar-container' );
				toolbarContainer.prepend( editor.ui.view.toolbar.element );
				window.editor = editor;
			} )
			.catch( err => {
				console.error( err.stack );
			} );
			
	} 
    let vm = new Vue({
        el:'#app',
        data:{
            send:{
                code:1700,
                data:{
                    titles:'',
                    content:'',
                    userIds:'',
                }
            },
            websock:null
        },
        created() {
            // this.initWebSocket();
        },
        destroyed() {
            this.websock.close() //离开路由之后断开websocket连接
        },
        methods: {
            initWebSocket(){ //初始化weosocket
                const wsuri = "ws://"+document.domain+":9504/chatpush";
                // const wsuri = "ws://118.123.237.130:9504/chatpush";
                this.websock = new WebSocket(wsuri);
                this.websock.onmessage = this.websocketonmessage;
                this.websock.onopen = this.websocketonopen;
                this.websock.onerror = this.websocketonerror;
                this.websock.onclose = this.websocketclose;
            },
            websocketonopen(){ //连接建立之后执行send方法发送数据
                console.log('连接成功')
            },
            websocketonerror(){//连接建立失败重连
                layer.msg("服务连接失败")
                // this.initWebSocket();
            },
            websocketonmessage(e){ //数据接收
                const redata = JSON.parse(e.data);
                if (redata.code==1701){
                    layer.msg('发送成功')
                }
                console.log(redata)
            },
            websocketsend(Data){//数据发送
                this.websock.send(Data);
            },
            websocketclose(e){  //关闭
               layer.msg('服务断开连接');
            },
            sendmsg(){

                // return

                this.websocketsend(JSON.stringify(this.send))
            },
            
            sendmsgs(){
                 this.send.data.content= this.$refs.editor.innerHTML
                let updata = this.send.data;
                $.post('/member/member/tuisong',updata,function (res) {
                        var result = JSON.parse(res)
                        if (result.code==1){
                            _this.sendmsg()
                            _this.initWebSocket()
                            layer.close(index);
                            return true
                        }
                        layer.msg(result.msg)
                    })
            },
            gugecode(){ 
                this.send.data.content= this.$refs.editor.innerHTML
                if (this.send.data.content=='' || this.send.data.titles==''){
                    return layer.msg("发送内容不能为空")
                }
                this.sendmsgs()
                return  //取消谷歌验证码
                var _this = this
                var config ={
                    title: '请输入谷歌验证码',
                    formType: 0,
                }
                layer.prompt(config, function(pass, index){
                    $.post('/<?php echo G_ADMIN_DIR; ?>/setting/isuploadauth',{code:pass},function (res) {
                        var result = JSON.parse(res)
                        if (result.code==1){
                            // _this.sendmsg()
                            // _this.initWebSocket()
                            _this.sendmsgs()
                            layer.close(index);
                            return true
                        }
                        layer.msg(result.msg)
                    })

                });
            },
            keyupUser(e){
                console.log("站内信发送",e);
                console.log("站内信发送2",this.send.data.userIds);
            }
        },
    })
</script>
</html>