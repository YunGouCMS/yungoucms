<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>查看</title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-3.4.1.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/vue/vue.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
	<link type="text/css" href="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/sample/css/sample.css" rel="stylesheet" media="screen" />
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/ckeditor.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/translations/zh-cn.js"></script>
</head>
<style>
    #app{margin: 50px auto;padding-right: 50px;;}
	main .document-editor {max-height: 281px;}
	.poreab {position: relative;}
	.poreab>div {position: absolute;width: 100%;height: 100%;top: 0;left: 0;z-index: 100;}
</style>
<body>
<div id="app" class="container">
    <div class="layui-form">

        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("message_title");?></label>
            <div class="layui-input-block">
                <input type="text" name="title" required  v-model="send.data.titles" lay-verify="required" placeholder="<?php $this->lan("message_title");?>" autocomplete="off" class="layui-input layui-disabled">
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label"><?php $this->lan("content");?></label>
            <div class="layui-input-block poreab">
                <!-- <textarea name="desc" placeholder="请输入内容" class="layui-textarea"></textarea> -->
				<div></div>
				<main>
					<!-- <div class="document-editor">
						<div class="toolbar-container"></div>
						<div name="editorValue" ref="editor" id="editor">
                            <?php echo $data['content'];?>
						</div>
					</div> -->
                    <div name="editorValue" ref="editor" id="editor">
                            <?php echo $data['content'];?>
                    </div>
                    <!-- <input type="text" name="title" required  v-model="send.data.content" lay-verify="required" placeholder="请输入内容" autocomplete="off" class="layui-input layui-disabled"> -->
				</main>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("send_member");?></label>
            <div class="layui-input-block">
            <table class="layui-table">
                <colgroup>
                    <col width="150">
                    <col width="200">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th><?php $this->lan("Member_id");?></th>
                    <th><?php $this->lan("nickname");?></th>
                    <th><?php $this->lan("status");?></th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($userData as $v){?>
                        <tr>
                            <td><?php echo $v['uid'];?></td>
                            <td><?php echo $v['username'];?></td>
                            <td><?php echo $v['statusName'];?></td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
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
/*     let vm = new Vue({
        el:'#app',
        data:{
            send:{
                code:1700,
                data:{
                    titles:"<?php echo $data['titles'];?>",
                    content:"<?php echo $data['content'];?>",
                    userIds:'',
                }
            },
            websock:null
        },

    }) */
</script>
</html>