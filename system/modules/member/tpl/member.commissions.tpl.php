<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->getPageTitle('会员管理'); ?></title>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>

<style>
tbody tr{ line-height:30px; height:30px;} 
.modal {
            display: none; /* 默认隐藏 */
            position: fixed; /* 固定定位 */
            z-index: 1; /* 置于顶层 */
            left: 0;
            top: 0;
            width: 100%; /* 全屏宽度 */
            height: 100%; /* 全屏高度 */
            overflow: auto; /* 允许滚动 */
            background-color: rgba(0, 0, 0, 0.4); /* 半透明黑色 */
        }
        /* 模态窗口内容 */
        .modalcontent {
            background-color: #fefefe;
            margin: 15% auto; /* 居中显示 */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* 宽度 */
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
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
    <div class="">
     <input type ="button" value="  <?php $this->lan("export","导出");?>" name="excel_submit" style="float: right;transform: translateY(-30px);height: 30px !important;" onclick="javascript:window.location.href='<?php echo WEB_PATH.'/'.G_ADMIN_DIR;?>/dingdan/comm_export/<?php echo $where; ?>'" >
      <img src="/statics/plugin/style/global/image/daochu.png" style="width: 20px;float: right;transform: translate(42px, -25px);">
 </div>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
            <th width="60px" align="center"><?php $this->lan("id");?></th>
            <!-- <th width="100px" align="center">唯一ID</th> -->
            <th width="100px" align="center"><?php $this->lan("name");?></th>
            <th width="100px" align="center"><?php $this->lan("time");?></th>
            <th width="100px" align="center"><?php $this->lan("Account_name");?></th>
            <th width="100px" align="center"><?php $this->lan("Search_Days");?></th> 
            <th width="100px" align="center"><?php $this->lan("Bank_info");?></th>
            <th width="100px" align="center"><?php $this->lan("Service_charge");?></th>
            <th width="100px" align="center"><?php $this->lan("status");?></th>       
            <th width="100px" align="center"><?php $this->lan("operator");?></th>       
            <th width="100px" align="center"><?php $this->lan("operator_status");?></th>       
            <th width="100px" align="center"><?php $this->lan("admin");?></th>       
            <th width="100px" align="center"><?php $this->lan("admin_status");?></th>       
            <th width="100px" align="center"><?php $this->lan("admin_remarks");?></th>       
            <th width="100px" align="center"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
    <tbody>
    	<?php
            $arr = ["1"=>$this->lgs['record_state1'],"2"=>$this->lgs['record_state2'],"3"=>$this->lgs['record_state3']];
             foreach($commissions as $key=>$v){ ?>
		<tr>
			<td align="center"><?php echo $v['id']; ?></td>
			<!-- <td align="center"><?php echo $v['uid']; ?></td> -->
			<td align="center"><?php echo $v['uid']; ?>/<?php if($user[$key]['username']){echo $user[$key]['username'];}else{echo $user[$key]['mobile'];}; ?></td>	
			<td align="center"><?php echo date('Y/m/d H:i:s',$v['time']); ?></td>
			<td align="center"><?php echo $v['username']; ?></td>	
			<td align="left"><?php echo $v['bankname']."".$v['branch']."".$v['banknumber']; ?></td>	
			<td align="center"><?php echo $v['money']; ?></td>	
			<td align="center"><?php echo $v['procefees']; ?></td>	
			<td align="center">
                <?php if($v['auditstatus']==1){echo $this->lgs['record_state2']; }else if($v['auditstatus'] == 2){echo $this->lgs['record_state3'];}else{echo $this->lgs['record_state1'];} ?>
            </td>
			<td align="center"><?php echo $v['operat_name']; ?></td>	
			<td align="center">
                <?php echo $arr[$v['operat_status'] + 1].'('.$v['operat_desc'].')';?>
            </td>
			<td align="center"><?php echo $v['manage_name']; ?></td>	
			<td align="center"><?php echo $arr[$v['manage_status']];?></td>	
			<td align="center"><?php echo $v['manage_desc']; ?></td>	
            <td align="center">
                <?php if(!$v['operat_status']){
                    echo '<a class="butaoin myBtn" data-id="'.$v['id'].'" data-m="0">审核</a>';
                }
                ?>
                <a class="butaoin" href="<?php echo G_WEB_PATH.'/member/member/commissionsDetail/'.$v['uid']; ?>"><?php $this->lan("commissions_details");?></a>
		    </td>            	
		</tr>
       <?php } ?>
       <?php  if (!$commissions ): ?>
            <tr>
                <td colspan="13" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php  endif; ?>
	
  	</tbody>
	
</table>
    <div id="pages"><ul><li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('one','li'); ?></ul></div>

</div><!--table-list end-->

<div id="myModal" class="modal">
    <div class="modalcontent">
	<span class="close">&times;</span>
    <form class="layui-form layui-form-pane" action="#" id="operation" method="post">
		<input type="hidden" name="m-id">
		<div class="layui-form-item">
			<label class="layui-form-label"><?php $this->lan("Select_operation");?></label>
			<div class="layui-input-block">
			<select name="status"  style="display: none;">
				<option value="1"><?php $this->lan("record_state2");?></option>
				<option value="2"><?php $this->lan("record_state3");?></option>
			</select>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label"><?php $this->lan("remarks");?></label>
			<div class="layui-input-block">
				<input type="text" name="desc"  placeholder="<?php $this->lan("remarks");?>" class="layui-input"  >
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label"><?php $this->lan("Secure_password");?></label>
			<div class="layui-input-block">
				<input type="password" name="secure_password"  placeholder="<?php $this->lan("Secure_password");?>" class="layui-input" required  >
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
<script>
function submit(id){
    layer.open({
        type: 2,
        title: '拒绝理由',
        shadeClose: true,
        shade: 0.8,
        area: ['600px', '510px'],
        content: '/member/member/reason/'+id
    });
}

// 获取模态窗口元素
var modal = document.getElementById("myModal");
    // 获取关闭按钮元素
    var span = document.getElementsByClassName("close")[0];
    // 当用户点击按钮时，显示模态窗口
    var m=0
    $(".myBtn").click(function(){
        console.log("sdfsd")
		var id = $(this).attr("data-id");
		m = $(this).attr("data-m");
		$("input[name=m-id]").val(id);
		modal.style.display = "block";
	})
    // 当用户点击关闭按钮时，关闭模态窗口
    span.onclick = function() {
        modal.style.display = "none";
    }
    // 当用户点击模态窗口之外的区域时，关闭模态窗口
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    $("#m-submit").click(function(){
		var id = $("input[name=m-id]").val();
		var status = $("select[name=status]").val();
		var desc = $("input[name=desc]").val();
        /* if(m=='1'){
            var url = "<?php echo G_MODULE_PATH; ?>/member/examineCommre/"+id
        }else{
            var url ="<?php echo G_MODULE_PATH; ?>/member/commreview/"+id
        } */
        var url ="<?php echo G_MODULE_PATH; ?>/member/commreview/"+id
		var secure_password = $("input[name=secure_password]").val();
		$.post(url,{id:id,status:status,desc:desc,secure_password:secure_password},function(res){
			res =JSON.parse(res)
            console.log(res)
            layer.msg(res.msg);
            setTimeout(()=>{
                    location.reload();
                },1500)
		})
	})
</script>
</body>
</html> 