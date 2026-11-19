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
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <!-- 后台各页面详细UI升级样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.all.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <!-- 通知消息系统脚本（必须在layer之后加载） -->
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
    <style>
        img{
            width: 100px;
        }
        p{
            color:gray;
        }
        .layui-form {margin-top: 15px;}
        .layui-form-label {width: 185px;}
        .layui-input-inline {width: 300px!important;display: flex;}
        .layui-input-inline>input {width: 132px;padding-right: 10px;text-align: center;}
        .layui-form-item p{
            padding-top:22px;
        }
        .layui-input-inline .tip{
            width:100px;
        }
		.xianimage {
			position: absolute;
			top: 0;
			left: 600px;
			height: 594px;
			width: calc(100% - 620px);
			max-width: 450px;
			display: flex;
            align-items: center;
            justify-content: center;
		}
		.xianimage div.layui-bottom-img text {font-size: 25px;}
		.xianimage img {width: auto;height: auto;max-width: 90%;max-height: 90%;}
		.layui-form-item p {padding-left:10px;}
		.layui-col-md9 {color:#808080;}
		.layui-input-block {margin-left: 235px!important;}
        /* .layui-layer-shade{
            right: 0;
            width: 50%;
        } */
        .tip{
            margin-top: 30px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
</div>
<div style="position: relative;">
    <form class="layui-form ">
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("Navigation_code");?></label>
            <div class="layui-input-block">
				<div class="layui-row">
					<div class="layui-col-md1 width130">
						<div class="layui-upload-list height131">
							<div class="layui-bottom-img">
								<span>+</span>
								<text><?php $this->lan("Click_upload");?></text>
							</div>
							<img class="layui-upload-img" id="demo1" style="width: auto;height: 100%;">
							<p id="demoText"></p>
							<button type="button" class="layui-btn layui-btn-sm" id="test1" style="height: 100% !important;background: rgba(0, 0, 0, 0) !important;"></button>
						</div>
					</div>
					<!--<div class="layui-col-md9">-->
					<!--	414*512Px，支持Png格式-->
					<!--</div>-->
					<div data-title="<?php $this->lan("tips");?>" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" style="width:20px; height:20px">
                    </div>
				</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("Logo_image");?></label>
            <div class="layui-input-block">
				<div class="layui-row">
					<div class="layui-col-md1 width130">
						<div class="layui-upload-list height131">
							<div class="layui-bottom-img">
								<span>+</span>
								<text><?php $this->lan("Click_upload");?></text>
							</div>
							<img class="layui-upload-img" id="demo2" width="120px" src="<?php echo $icUrl;?>">
							<p id="demoText2"></p>
							<button type="button" class="layui-btn layui-btn-sm" id="test2" style="height: 100% !important;background: rgba(0, 0, 0, 0) !important;"></button>
						</div>
					</div>
                    <div class="layui-col-md9">
                        <!--（不上传时使用网站默认LOGO）-->
                    </div>
				</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("code_size");?></label>
            <div class="layui-input-inline">
                <input type="number" name="qr_code_size" lay-verify="number|required" value="5" placeholder="<?php $this->lan("code_size");?>" autocomplete="off" class="layui-input qr_code_size">
                <!--<p>建议1-10</p>-->
                <div data-title="<?php $this->lan("tips1");?>" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" style="width:20px; height:20px">
                    </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("Border_size");?></label>
            <div class="layui-input-inline">
                <input type="number" name="qr_code_border" lay-verify="number|required" value="1" placeholder="<?php $this->lan("Border_size");?>" autocomplete="off" class="layui-input qr_code_border">
                <!--<p>建议1-5</p>-->
                <div data-title="建议1-5<?php $this->lan("tips2");?>" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" style="width:20px; height:20px">
                    </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("left_side");?></label>
            <div class="layui-input-inline">
                <input type="number" name="left_margin" lay-verify="number|required" value="100" placeholder="<?php $this->lan("left_side");?>" autocomplete="off" class="layui-input left_margin">
                <!--<p>二维码相对于分享图左侧位置</p>-->
                <div data-title="<?php $this->lan("tips3");?>" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" style="width:20px; height:20px">
                    </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("top_side");?></label>
            <div class="layui-input-inline">
                <input type="number" name="top_margin" lay-verify="number|required" value="280" placeholder="<?php $this->lan("top_side");?>" autocomplete="off" class="layui-input top_margin">
                <!--<p>二维码相对于分享图顶部位置</p>-->
                 <div data-title="<?php $this->lan("tips4");?>" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" style="width:20px; height:20px">
                    </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("left_distance");?></label>
            <div class="layui-input-inline">
                <input type="number" name="internal_x" lay-verify="number|required"  value="0"  placeholder="<?php $this->lan("left_distance");?>" autocomplete="off" class="layui-input internal_x">
                <!--<p>建议0 或者 0-5</p>-->
                <div data-title="<?php $this->lan("tips6");?>" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" style="width:20px; height:20px">
                    </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("top_distance");?></label>
            <div class="layui-input-inline">
                <input type="number" name="internal_y" lay-verify="number|required" value="0"  placeholder="<?php $this->lan("top_distance");?>" autocomplete="off" class="layui-input internal_y">
                <!--<p>建议0 或者 0-5</p>-->
                 <div data-title="<?php $this->lan("tips6");?>" class="tip">
                            <img src="../../../../statics/uploads/photo/gan.png" style="width:20px; height:20px">
                    </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit type="button" id="dosubmit" style="background-color: #3c8dbc;"><?php $this->lan("preview");?></button>
                <button class="layui-btn  layui-btn-normal" lay-submit type="button" id="save" style="background: #000000"><?php $this->lan("add");?></button>
            </div>
        </div>
    </form>
	<div class="xianimage layui-col-md1">
	    <div class="layui-bottom-img">
			<text><?php $this->lan("preview_img");?></text>
		</div>
		<img src="">
	<div>
</div>
<script>
    layui.use(['upload', 'element', 'layer','form'], function(){
        var $ = layui.jquery
            ,upload = layui.upload
            ,element = layui.element
            ,layer = layui.layer
            ,form = layui.form;

        //判读是否查看图片
        let isShow = 0;

        let bgUrl = ''
        let icUrl = ''
        let backgrounds = ''
        let icons = ''

        //合成图片
        let syntheticImag = ''
        //多图片上传
        var uploadInst = upload.render({
            elem: '#test1'
            ,data:{url:'codes'}
            ,url: '<?php echo G_WEB_PATH;?>/<?php echo G_ADMIN_DIR;?>/content/upimage' //此处用的是第三方的 http 请求演示，实际使用时改成您自己的上传接口即可。
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#demo1').attr('src', result); //图片链接（base64）
                });

                element.progress('demo', '0%'); //进度条复位
                layer.msg('上传中', {icon: 16, time: 0});
            }
            ,done: function(res){
                //如果上传失败
                if(res.code <= 0){
                    return layer.msg('上传失败');
                }
                //上传成功的一些操作
                //……
                $('#demoText').html(''); //置空上传失败的状态
                bgUrl = ''
                backgrounds = ''
                bgUrl = res.data.url
                backgrounds = res.data.url2
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
            //进度条
            ,progress: function(n, elem, e){
                element.progress('demo', n + '%'); //可配合 layui 进度条元素使用
                if(n == 100){
                    layer.msg('上传完毕', {icon: 1});
                }
            }
        });

        //图标上传
        var uploadInst2 = upload.render({
            elem: '#test2'
            ,data:{url:'codes'}
            ,url: '<?php echo G_WEB_PATH;?>/<?php echo G_ADMIN_DIR;?>/content/upimage' //此处用的是第三方的 http 请求演示，实际使用时改成您自己的上传接口即可。
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#demo2').attr('src', result); //图片链接（base64）
                });

                element.progress('demo', '0%'); //进度条复位
                layer.msg('上传中', {icon: 16, time: 0});
            }
            ,done: function(res){
                //如果上传失败
                if(res.code <= 0){
                    return layer.msg('上传失败');
                }
                //上传成功的一些操作
                //……
                $('#demoText2').html(''); //置空上传失败的状态
                icUrl = '';
                icons = '';
                icUrl = res.data.url
                icons = res.data.url2
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText2');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst2.upload();
                });
            }
            //进度条
            ,progress: function(n, elem, e){
                element.progress('demo', n + '%'); //可配合 layui 进度条元素使用
                if(n == 100){
                    layer.msg('上传完毕', {icon: 1});
                }
            }
        });

        function getInfoData(){
            let qr_code_size = $('.qr_code_size').val();
            let qr_code_border = $('.qr_code_border').val();
            let left_margin = $('.left_margin').val();
            let top_margin = $('.top_margin').val();
            let internal_x = $('.internal_x').val();
            let code_img_url = $('.code_img_url').val();
            let internal_y = $('.internal_y').val();
            let is_selected = $('.is_selected').val();
            const formData = {
                backgrounds:backgrounds,
                bgUrl:bgUrl,
                icons:icons,
                icUrl:icUrl,
                qr_code_size:qr_code_size,
                qr_code_border:qr_code_border,
                left_margin:left_margin,
                top_margin:top_margin,
                internal_x:internal_x,
                code_img_url:code_img_url,
                internal_y:internal_y,
                is_selected:is_selected,
                syntheticImag:syntheticImag,
            }
            return formData;
        }

        $('#dosubmit').on('click', function () {
            var index = ''
            layer.msg('图片真正合成请耐心等待。。。',{time:1000},function () {
                // index = layer.load(1, {
                //     shade: [0.1,'#fff'] //0.1透明度的白色背景
                // });
            });
            formData = getInfoData();
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/add',formData,function (data) {
                data = JSON.parse(data);
                if (data.code == 1){
                    isShow = 1;
                    syntheticImag = data.data
                    
                    layer.msg(data.msg,{icon:1});
					$('.xianimage div').hide();
					 var timestamp = new Date().getTime();
					$('.xianimage img').attr('src', data.data+'?timestamp='+timestamp);
					return
                    layer.msg(data.msg, {icon: 1,time:1000},function(){
                        layer.close(index);
                        var json ={
                            "title": "", //相册标题
                            "id": 123, //相册id
                            "start": 0, //初始显示的图片序号，默认0
                            "data": [   //相册包含的图片，数组格式
                                {
                                    "alt": "分享图预览",
                                    "pid": 1, //图片id
                                    "src": data.data, //原图地址
                                    "thumb": "" //缩略图地址
                                }
                            ]
                        }
                        layer.photos({
                            photos: json
                            ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
                        });
                    });
                }else{
                    layer.close(index);
                    layer.msg(data.msg,{icon:2});
                }
            });
        });

        $('#save').on('click', function () {
            if (isShow <= 0){
                layer.msg('请检查分享图后在保存',{time:1000});
                return;
            }
            var indexs = ''
            layer.msg('拼命保存中。。。',{time:1000},function () {
                indexs = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
            });
            formData = getInfoData();
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/addinsert',formData,function (data) {
                data = JSON.parse(data);
                if (data.code == 1){
                    isShow = 1;
                    layer.msg(data.msg, {icon: 1,time:1000},function(){
                        // parent.location.reload();//刷新父页面，注意一定要在关闭当前iframe层之前执行刷新
                        // parent.layer.close(index); //再执行关闭
                        window.location.href = "<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/index";
                    });
                }else{
                    layer.close(indexs);
                    layer.msg(data.msg,{icon:2});
                }
            });
        });
    });
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html>