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
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.all.js"></script>
<!-- <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css"> -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <style>
        p{
            color:gray;
        }

        .hiddens{
            display:none;
        }
		.layui-row:before {
		    clear: inherit;
		}
        .layui-form-item .layui-input-inline{
            display: inline-block;
            margin: 0px;
        }
        .layui-anim .layui-icon{
            color: #3c8dbc;
        }
        .layui-form-radio>i:hover, .layui-form-radioed>i{
            color: #3c8dbc;
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
    <div>
        <form class="layui-form ">
            <div class="layui-form-item hiddens">
                <label class="layui-form-label"> <?php $this->lan("Type_selection");?></label>
                <div class="layui-input-block">
                    <input type="radio" name="is_bg" value="1" title="<?php $this->lan("Set_background_color");?>" lay-filter="beshared" checked="">
                    <input type="radio" name="is_bg" value="3" title="<?php $this->lan("Set_background_image");?>" lay-filter="beshared">
                    <input type="radio" name="is_bg" value="2" title="<?php $this->lan("Set_login_color");?>" lay-filter="beshared">
                </div>

            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><?php $this->lan("Theme_name");?></label>
                <div class="layui-input-block" style="margin-top: 15px;">
                    <div class="layui-input-inline" style="margin: 0;width: 190px;">
                        <input type="text" name="title" placeholder="<?php $this->lan("Theme_name");?>" value="<?php echo $record['title'];?>" autocomplete="off" class="layui-input qr_code_border title">
                    </div>
                </div>
            </div>
            <div class="layui-form-item bgimg">
                <label class="layui-form-label"><?php $this->lan("Theme_img");?></label>
                <div class="layui-input-block">
					<div class="layui-row">
						<div class="layui-col-md1 width200">
							<div class="layui-upload-list height130">
								<div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;"<?php endif;?>>
									<span>+</span>
									<text><?php $this->lan("Click_upload");?></text>
								</div>
								<img class="layui-upload-img" width="160px" src="<?php echo $record['bgimg']?>"  id="demo1-img">
								<input type="hidden" id="demo1-lack" name="thumb" value="">
								<p id="demoText"></p>
								<button type="button" onclick="uploadimg('上传背景图片','#demo1','image','codes')"></button>
							</div>
						</div>
					</div>
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
                <div data-title="<?php $this->lan("tips");?>" class="tip" style="margin-top: 20px;">
                               <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
                          </div>
            </div>
            <div class="layui-form-item color2">
                <label class="layui-form-label"><?php $this->lan("Gradient_direction");?></label>
                <div class="layui-input-block">
                    <input type="radio" name="gradient_direction" value="left" title="<?php $this->lan("right_left");?>" lay-filter="gradient_direction" <?php if ($data['gradient_direction'] == "left"){?>checked=""<?php }?>>
                    <input type="radio" name="gradient_direction" value="bottom" title="<?php $this->lan("top_bottom");?>" lay-filter="gradient_direction" <?php if ($data['gradient_direction'] == "bottom"){?>checked=""<?php }?>>
                    <input type="radio" name="gradient_direction" value="right" title="<?php $this->lan("left_right");?>" lay-filter="gradient_direction" <?php if ($data['gradient_direction'] == "right"){?>checked=""<?php }?>>
                </div>

            </div>

            <div class="layui-form-item">
                <div class="layui-input-block">
<!--                    <button class="layui-btn " lay-submit type="button" id="returnLast">返回</button>-->
                    <!-- <button class="layui-btn  layui-btn-normal" style="background: #000000;" lay-submit type="button" id="dosubmit">保存</button> -->
                    <input type="button" value=" <?php $this->lan("save");?> " name="dosubmit" class="button" id="dosubmit">
                </div>
            </div>
        </form>
    </div>
<script>
        layui.use(['upload', 'element', 'colorpicker','layer','form'], function(){
            var $ = layui.jquery
                ,upload = layui.upload
                ,element = layui.element
                ,colorpicker = layui.colorpicker
                ,layer = layui.layer
                ,form = layui.form;
        //多图片上传
        let bgimg = '<?php echo $record["bgimg"]?>';
        let gradient_direction = '<?php echo $data["gradient_direction"];?>';
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

        let color1 = '<?php echo $data["color1"];?>';
        let color2 = '<?php echo $data["color2"];?>';
        let is_bg = <?php echo $data['is_bg']?>;
        //开启全功能
        colorpicker.render({
            elem: '#test-all'
            ,color: color1
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
            ,color: color2
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
                id:<?php echo $record['id'];?>,

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
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/codes/loginEditor',formData,function (data) {
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
        })

        form.on('radio(gradient_direction)', function (data) {
            gradient_direction = data.value
        })

        //初始化选项
        setStyle("<?php echo $data['is_bg'];?>")

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

        //返回上一页
        $('#returnLast').on("click",function () {
            parent.location.reload();//刷新父页面，注意一定要在关闭当前iframe层之前执行刷新
            parent.layer.closeAll(); //再执行关闭
        });
    });

</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html>