<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <!-- 高级骨架屏系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <!-- 统一后台页面美化样式（参考webcfg标准） -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <style>
        .layui-btn-normal {
            background-color: #000000;
        }
        #black{
            margin: 50px;
            padding-right: 50px;
        }
    </style>
</head>
<body>
<div >
    <form id="black"  class="layui-form" action="#" lay-filter="example">
        <div class="layui-form-item">
            <label class="layui-form-label">手机号码</label>
            <div class="layui-input-block">
                <input type="text" name="mobile" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">选项</label>
            <div class="layui-input-block" id="blacklist">

                <?php foreach ($bal as $k=>$v):?>
                    <input type="checkbox" name="arr[]" value="<?php echo $k;?>" lay-skin="primary" lay-skin="primary" title="<?php echo $v;?>">
                <?php endforeach;?>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <input type="text" name="remarks"  placeholder="备注" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="button" class="layui-btn layui-btn-normal" id="LAY-component-form-getval">提交</button>
            </div>
        </div>
    </form>
</div>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<script>
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit
            ,laydate = layui.laydate;

        layui.$('#LAY-component-form-getval').on('click', function(){
            var data = form.val('example');

            $.post('<?php echo G_MODULE_PATH; ?>/member/addbalckinputpost',data,function (res) {
                var result = JSON.parse(res)
                if (result.code==1){
                    layer.msg('添加黑名单成功')
                    setTimeout(()=>{
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭窗口
                        parent.layer.close(index);
                    },1500)
                }else {
                    layer.msg(result.msg?result.msg:'添加失败')
                }
            })
        });
    });
</script>
</body>
</html>
