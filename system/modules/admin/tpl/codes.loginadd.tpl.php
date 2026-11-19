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
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
<!--    <script src="--><?php //echo G_GLOBAL_STYLE; ?><!--/global/js/admin.js"></script>-->

    <style>
        p{
            color:gray;
        }

        .hiddens{
            display:none;
        }
		.layui-form {margin-top: 15px;}
		.layui-row:before {
		    clear: inherit;
		}
        .layui-anim .layui-icon{
            color: #3c8dbc;
        }
        .layui-form-radio>i:hover, .layui-form-radioed>i{
            color: #3c8dbc;
        }
        .butaoin{
            background-color: #3C8DBC;
    display: inline-block;
    padding: 0px 20px;
    margin: 0 5px 0 0;
    cursor: pointer;
    overflow: visible;
    text-decoration: none;
    white-space: nowrap;
    color: #fff;
    transition: background-color 0.2s ease-out 0s;
    background-clip: padding-box;
    height: 34px;
    border: 1px solid #3C8DBC;
    border-radius: 5px;
        }
        .layui-form-label{
            width: 140px!important;
        }
        .layui-input-block{
            margin-left: 170px!important;
        }
    </style>
</head>
<body>
    <div style="height:480px">
        <form class="layui-form ">
            <div class="layui-form-item">
                <label class="layui-form-label"><?php $this->lan("Type_selection");?></label>
                <div class="layui-input-block">
                    <input type="radio" name="is_bg" value="1" title="<?php $this->lan("Set_background_color");?>" lay-filter="beshared" checked="">
                    <input type="radio" name="is_bg" value="3" title="<?php $this->lan("Set_background_image");?>" lay-filter="beshared">
                    <input type="radio" name="is_bg" value="2" title="<?php $this->lan("Set_login_color");?>" lay-filter="beshared">
                </div>

            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><?php $this->lan("Theme_name");?></label>
                <div class="layui-input-block">
                    <div class="layui-input-inline">
                        <input type="text" name="title" placeholder="<?php $this->lan("Theme_name");?>" autocomplete="off" class="layui-input qr_code_border title">
                    </div>
                </div>
            </div>
            <div class="layui-form-item bgimg">
                <label class="layui-form-label"><?php $this->lan("Theme_img");?></label>
                <div class="layui-input-block">
					<div class="layui-row">
						<div class="layui-col-md1 width200">
							<div class="layui-upload-list height130">
								<div class="layui-bottom-img">
									<span>+</span>
									<text><?php $this->lan("Click_upload");?></text>
								</div>
								<img class="layui-upload-img" width="160px" src=""  id="demo1-img">
								<input type="hidden" id="demo1-lack" name="thumb" value="">
								<p id="demoText"></p>
								<button type="button" onclick="uploadimg('上传背景图片','#demo1','image','codes')"></button>
							</div>
						</div>
					</div>
					
                    <!-- <div style="width: 95px;">
                        <div class="layui-progress layui-progress-big" lay-showpercent="yes" lay-filter="demo">
                            <div class="layui-progress-bar" lay-percent=""></div>
                        </div>
                    </div> -->
                    <p id="demoText" >
                    </p>

                </div>

            </div>

            <div class="layui-form-item color1">
                <label class="layui-form-label"><?php $this->lan("background_color");?></label>
                <div class="layui-input-inline">
                    <input type="hidden" name="color1" value="" id="test-all-input">
                    <div id="test-all"></div>
                </div>
            </div>
            <div class="layui-form-item color2" style="display:flex">
                <label class="layui-form-label"><?php $this->lan("background_color");?></label>
                <div class="layui-input-inline">
                    <input type="hidden" name="color2" value="" id="test-all-input1">
                    <div id="test-all1"></div>
                </div>
                 <!--<p style="margin-top: 20px;">（选了这个页面是就是渐变色）</p>-->
                <div data-title="<?php $this->lan("tips");?>" class="tip" style="margin-top: 15px;margin-left:-150px">
                    <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
                </div>
            </div>
            <div class="layui-form-item color2">
                <label class="layui-form-label"><?php $this->lan("Gradient_direction");?></label>
                <div class="layui-input-block">
                    <input type="radio" name="gradient_direction" value="left" title="<?php $this->lan("right_left");?>" lay-filter="gradient_direction" checked="">
                    <input type="radio" name="gradient_direction" value="bottom" title="<?php $this->lan("top_bottom");?>" lay-filter="gradient_direction">
                    <input type="radio" name="gradient_direction" value="right" title="<?php $this->lan("left_right");?>" lay-filter="gradient_direction">
                </div>

            </div>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <!-- <button class="layui-btn  layui-btn-normal" lay-submit type="button" id="dosubmit">保存</button> -->
                    <input type="button" value="<?php $this->lan("save");?>" class="butaoin" name="dosubmit" id="dosubmit">
                </div>
            </div>
        </form>
    </div>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.all.js"></script>
<script>

    /**
     * 单个文件上传
     * @param dialog_title 上传对话框标题
     * @param input_selector 图片容器
     * @param filetype 文件类型，image,video,audio,file
     * @param extra_params 额外参数，'上传位置'
     * @param upload_size 允许文件大小 单位 MB 默认 10
     */
    function uploadimg(dialog_title, input_selector, filetype,extra_params,upload_size) {
        upload_size = !isEmpty(upload_size) ? upload_size : 10;
        var html = `
        <div class="layui-upload-drag" id="upload_box_aw" style="position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);">
          <i id="upload_icon" class="layui-icon"></i>
          <p id="upload_title_tip">点击上传，或将文件拖拽到此处</p>
          <div class="layui-hide"  id="uploadDemoView_aw">
            <hr>
          </div>
        </div>
        <div id="uploadimgarr">
         <img src="" id="uploadDemoView_aw_img" alt="" style="max-width: 86px">
        </div>
    `
        var updata = {}
        var uploadmodel = layer.open({
            type: 1,
            title: !isEmpty(dialog_title) ? dialog_title : "上传图片",
            closeBtn: 1,
            btn: ['确定'],
            skin: 'layui-layer-rim', //加上边框
            area: ['520px', '480px'], //宽高
            shadeClose: false,
            content: html,
            yes: function () {
                layer.close(uploadmodel);
            }
        });

        var paramdata = {
            url: !isEmpty(extra_params) ? extra_params : 'shopimg'
        }
        filetype = !isEmpty(filetype) ? filetype : 'images';
        layui.upload.render({
            elem: '#upload_box_aw'
            , accept: filetype
            , size: upload_size * 1024
            , data: paramdata
            , url: getDomain() + "/api/tjupload/upimage"//上传接口。
            , done: function (res) {
                updata = res
                if (res.code == 1) {
                    layui.$('#uploadDemoView_aw_img').attr('src', res.data.url);
                    $(`${input_selector}-complete`).val(res.data.url)
                    $(`${input_selector}-lack`).val(res.data.url2)
                    $(`${input_selector}-img`).attr('src', res.data.url)
                    $('.layui-bottom-img').css('opacity',0)
                    return layer.msg('上传成功');
                }
                layer.msg(res.msg);
            },

        })
    }
    function getDomain(){
        var url = document.location.toString();
        var arrUrl = url.split("//");
        return arrUrl[0]+"//"+document.domain
    }

    //判断字符是否为空的方法
    function isEmpty(obj){
        if(typeof obj == "undefined" || obj == null || obj == ""){
            return true;
        }else{
            return false;
        }
    }

    layui.use(['upload', 'element', 'colorpicker','layer','form'], function(){
        var $ = layui.jquery
            ,upload = layui.upload
            ,element = layui.element
            ,colorpicker = layui.colorpicker
            ,layer = layui.layer
            ,form = layui.form;
        //多图片上传
        let bgimg = '';
        let gradient_direction = '';
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
                bgimg = res.data.url
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

        let color1 = '';
        let color2 = '';
        let is_bg = 1;
        //开启全功能
        colorpicker.render({
            elem: '#test-all'
            ,color: 'rgba(7, 155, 140, 1)'
            ,format: 'rgb'
            ,predefine: true
            ,alpha: true
            ,done: function(color){
                $('#test-all-input').val(color); //向隐藏域赋值
                layer.tips('给指定隐藏域设置了颜色值：'+ color, this.elem);

                color || this.change(color); //清空时执行 change
            }
            ,change: function(color){
                //给当前页面头部和左侧设置主题色
                $('.header-demo,.layui-side .layui-nav').css('background-color', color);
                color1 = color;
            }
        });
        //开启全功能
        colorpicker.render({
            elem: '#test-all1'
            ,color: 'rgba(7, 155, 140, 1)'
            ,format: 'rgb'
            ,predefine: true
            ,alpha: true
            ,done: function(color){
                $('#test-all-input').val(color); //向隐藏域赋值
                layer.tips('给指定隐藏域设置了颜色值：'+ color, this.elem);

                color || this.change(color); //清空时执行 change
            }
            ,change: function(color){
                //给当前页面头部和左侧设置主题色
                $('.header-demo,.layui-side .layui-nav').css('background-color', color);
                color2 = color;

            }
        });

        function getInfoData(){
            let title = $('.title').val();
            bgimg = $('#demo1-lack').val();
            const formData = {
                is_bg:is_bg,
                title:title,
                bgimg:bgimg,
                color1:color1,
                color2:color2,
                gradient_direction:gradient_direction,

            }
            return formData;
        }

        $('#dosubmit').on('click', function () {
            var index = ''
            layer.msg('努力保存中',{time:1000},function () {
                index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
            });
            formData = getInfoData();
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/loginadd',formData,function (data) {
                data = JSON.parse(data);
                if (data.code == 1){
                    layer.msg(data.msg, {icon: 1,time:1000},function(){
                        parent.location.reload();//刷新父页面，注意一定要在关闭当前iframe层之前执行刷新
                        parent.layer.close(index); //再执行关闭
                    });
                }else{
                    layer.close(index);
                    layer.msg(data.msg,{icon:2});
                }
            });
        });

        //监听单选框
        form.on('radio(beshared)', function (data) {
            setStyle(data.value);
            form.render();
        })

        form.on('radio(gradient_direction)', function (data) {
            gradient_direction = data.value
        })

        //初始化选项
        setStyle("1")

        //单选框选择调整样式 (方便之后的拓展)
        function setStyle(num){
            is_bg = num
            switch (num) {
                case ("1"):
                    setOne();
                    break;
                case ("2"):
                    setOne();
                    break;
                case ("3"):
                    setTow();
                    break;
            }
        }

        //选择一的时候渲染的页面
        function setOne(){
            $('.bgimg').addClass("hiddens");
            $('.color1').removeClass("hiddens");
            $('.color2').removeClass("hiddens");
        }

        function setTow(){
            $('.bgimg').removeClass("hiddens");
            $('.color1').addClass("hiddens");
            $('.color2').addClass("hiddens");
        }
        form.render();
    });

</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html>