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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<!-- 测试 -->
<link href="https://unpkg.com/layui@2.9.8/dist/css/layui.css" rel="stylesheet">
<script src="https://unpkg.com/layui@2.9.8/dist/layui.js"></script>
<style>
.layui-form-switch {
    margin-top: 0;
}
.layui-layer-msg {
    min-width: 100px;
}
.header,.butaoin {font-size:12px;}
table tbody td.layui-form .layui-form-switch, .table-list table tbody td.layui-form .layui-form-switch {
    padding-left: 25px !important;
}
</style>
</head>
<body>
<div class="header" style="display: flex;">
	<?php echo $this->headerment();?>
</div>

<div class="bk10"></div>

<div class="table-list">
<form name="myform" action="#" method="post">
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
		<th width="30"><?php $this->lan("id");?></th>
		<!-- <th width="100">序号</th> -->
		<th width="100" ><?php $this->lan("menu_name");?></th>
    <th width="100" ><?php $this->lan("level");?></th>
    <th width="100" ><?php $this->lan("status");?></th>
    <th width="100" ><?php $this->lan("sort");?></th>
		<th width="100"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
  	<tbody>
    	<?php foreach($data as $v){ ?>
        <tr>
        <td  align="center"><?php echo $v['id']; ?></td>
        <!-- <td  align="center"><?php echo $v['sort']; ?></td> -->
        <td  align="center"><?php echo $v['name']; ?></td>
        <td  align="center"><?php if($v['level'] == 0 ) { $this->lan("top");} elseif($v['level'] == 1) { $this->lan("one");} else {$this->lan("two");} ?></td>
        <td  align="center" class="layui-form">
            <input type="checkbox" class="is_state sta" name="is_state" <?php if($v['is_enable'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("open");?>|<?php $this->lan("close");?>">
        </td>
        <td  align="center"><?php echo $v['sort']; ?></td>
        <td align="center">
          <a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/user/edit_group/<?php echo $v['id'];?>"><?php $this->lan("edit");?></a>
          <button type="button" class="butaoin butaish myBtn" style="border: none;"  data-id="<?echo $v['id'];?>"><?php $this->lan("del","删除");?></button>
        </td>
        </tr>
        <?php } ?>
	</tbody>
</table>

</form>
</div><!--table-list end-->
<!-- 审核操作 -->
<div id="myModal" class="modal">
        <div class="modalcontent">
            <span class="close">&times;</span>
            <form class="layui-form layui-form-pane" action="#" id="operation" method="post">
                <input type="hidden" name="m-id">
                <div class="layui-form-item">
                    <label class="layui-form-label">安全密码</label>
                    <div class="layui-input-block">
                        <input type="password" name="secure_password" placeholder="请输入安全密码" class="layui-input" required>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="m-submit">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script>
layui.use(['form','jquery'],function () {
        var form = layui.form,
        $ = layui.jquery;
        form.render('checkbox');
        form.on('switch(is_state)',function (data) {
            var id = $(this).attr('data-state');
            var zhi = '';
            if (data.elem.checked == true) {
                zhi = 1;
            }else if(data.elem.checked == false){
                zhi = 2;
            }
            $.post("<?php echo WEB_PATH.'/'.ROUTE_M; ?>/user/chang_column",{id:id,zhi:zhi},function (data) {
                data = JSON.parse(data);
                if (data.code == 200){
                    layer.msg(data.msg, {icon: 1});
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            });
        });
        form.render();
      });
	function chang_status(obj){
        var id=$(obj).attr('id');
        var state=$(obj).attr('state');
    $.post("<?php echo WEB_PATH.'/'.ROUTE_M; ?>/user/chang_column",{"id":id,'state':state},function(data){
      if(data==1){
        $(obj).html('是');
        $(obj).attr('state','1');
        $(obj).attr('style','color:green;');
      }else{
         $(obj).html('否');
         $(obj).attr('state','0');
         $(obj).attr('style','');
      }
    }); 
    }

   
   // 获取模态窗口元素
   var modal = document.getElementById("myModal");
        // 获取触发模态窗口的按钮元素
        // 获取关闭按钮元素
        var span = document.getElementsByClassName("close")[0];

        // 当用户点击按钮时，显示模态窗口
        $(".myBtn").click(function() {
            var id = $(this).attr("data-id");
            $("input[name=m-id]").val(id);
            modal.style.display = "block";
        })
        $("#m-submit").click(function(){
            var id = $("input[name=m-id]").val();
            var status = $("select[name=status]").val();
            var desc = $("input[name=desc]").val();
            var secure_password = $("input[name=secure_password]").val();
            $.post("<?php echo G_MODULE_PATH . "/user/columnDel"; ?>",{id:id,secure_password:secure_password},function(res){
                res =JSON.parse(res)
                alert(res.msg)
                modal.style.display = "none";
                location.reload();
            })
        })

        // 当用户点击关闭按钮时，关闭模态窗口
        $(".close").click(function(){
            modal.style.display = "none";
            $("#detailModal").css("display","none")
        })
  /*       span.onclick = function() {
            modal.style.display = "none";
        } */
        // 当用户点击模态窗口之外的区域时，关闭模态窗口
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
            if(event.target == document.getElementById("detailModal")){
                 $("#detailModal").css("display","none")
            }
        }
</script>
      

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 