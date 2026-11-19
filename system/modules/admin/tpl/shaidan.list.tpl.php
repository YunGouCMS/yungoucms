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
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<style>

body {
            font-size: 12px;
        }
        td {line-height: 20px;}
        /* 模态窗口背景 */
        .modal {
            display: none;
            /* 默认隐藏 */
            position: fixed;
            /* 固定定位 */
            z-index: 1;
            /* 置于顶层 */
            left: 0;
            top: 0;
            width: 100%;
            /* 全屏宽度 */
            height: 100%;
            /* 全屏高度 */
            overflow: auto;
            /* 允许滚动 */
            background-color: rgba(0, 0, 0, 0.4);
            /* 半透明黑色 */
        }

        /* 模态窗口内容 */
        .modalcontent {
            background-color: #fefefe;
            margin: 15% auto;
            /* 居中显示 */
            padding: 20px;
            border: 1px solid #888;
            width: 600px;
            /* 宽度 */
        }

        /* 关闭按钮 */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        tbody tr {
            line-height: 30px;
            height: 30px;
        }
        .layui-input-block{
            margin-left: 170px!important;
        }
        .layui-form-label{
            width: 150px!important;
        }
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<!-- 修改表格样式 -->
<div class="table-list lr10">
<!--start-->
<form action="#" method="post" id="myform" class="layui-form" >
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
		<th ><?php $this->lan("id","id");?></th>
		<th ><?php $this->lan("img_title","标题");?></th>
		<th ><?php $this->lan("img","图片");?></th>
		<th ><?php $this->lan("Jump_address","用户id");?></th>
		<th ><?php $this->lan("country","内容");?></th>
		<th ><?php $this->lan("time","时间");?></th>
		<th ><?php $this->lan("state","状态");?></th>
		<th ><?php $this->lan("operation");?></th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($list as $v){ ?>
		<tr>
			<td align="center"><?php echo $v['sd_id']; ?></td>
			<td align="center"><?php echo $v['sd_title'];?></td>
			<td align="center">
                <?php
                    foreach($v['sd_photolist'] as $img){
                        echo '<img src="'.$img.'" width="50" height="50" alt="'.$img.'">';
                    }
                ?>
            </td>
			<td align="center"><?php echo $v['sd_userid'];?></td>
			<td align="center"><?php echo $v['sd_content'];?></td>
			<td align="center"><?php echo $v['sd_time'];?></td>
			<td align="center"><?php echo $v['examine_state'];?></td>
			<td align="center">
            <button type="button" class="butaoin myBtn" style="border:none" data-id="<?php echo $v['sd_id']; ?>"><?php $this->lan("examine","审核");?></button>
				<!--<a class="butaoin " target="_blank" href="<?php echo WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C; ?>/shaidan_huifu/<?php echo $v['sd_id'];?>" ><?php $this->lan("huifu","回复");?></a>-->
				<a class="butaoin butaish" href="<?php echo WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C; ?>/del/<?php echo $v['sd_id'];?>" onClick="return confirm('是否真的删除！');"><?php $this->lan("delete","删除");?></a>
			</td>	
		</tr>
		<?php } ?>
  	</tbody>
</table>
</form>
<!-- 审核操作 -->
<div id="myModal" class="modal">
        <div class="modalcontent">
            <span class="close">&times;</span>
            <form class="layui-form layui-form-pane" action="#" id="operation" method="post">
                <input type="hidden" name="m-id">
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php $this->lan("Select_operation");?></label>
                    <div class="layui-input-block">
                        <select name="status" lay-filter="aihao" id='status' style="display: none;">
                            <option value="2"><?php $this->lan("adopt");?></option>
                            <option value="3"><?php $this->lan("Not_passed");?></option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php $this->lan("Audit_remarks");?></label>
                    <div class="layui-input-block">
                        <input type="text" name="desc" placeholder="<?php $this->lan("Audit_remarks");?>" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php $this->lan("Secure_password");?></label>
                    <div class="layui-input-block">
                        <input type="password" name="secure_password" placeholder="<?php $this->lan("Secure_password");?>" class="layui-input" required>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="m-submit"><?php $this->lan("Submit_now");?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div><!--table-list end-->

<script type="text/javascript">
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
            $.post("<?php echo G_MODULE_PATH . "/shaidan/examine"; ?>",{id:id,status:status,desc:desc,secure_password:secure_password},function(res){
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