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
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
            <th width="100px" align="center"><?php $this->lan("id");?></th>
            <th width="100px" align="center"><?php $this->lan("name");?></th>
            <th width="100px" align="center"><?php $this->lan("time");?></th>
            <th width="100px" align="center"><?php $this->lan("commission");?></th>
            <th width="100px" align="center"><?php $this->lan("source");?></th>

		</tr>
    </thead>
    <tbody>
    	<?php
            $arr = ["1"=>"<?php $this->lan('record_state1');?>","2"=>"<?php $this->lan('record_state2');?>","3"=>"<?php $this->lan('record_state3');?>"];
             foreach($list as $key=>$v){ ?>
		<tr>
			<td align="center"><?php echo $v['id']; ?></td>
			<td align="center"><?php echo $v['uid'].'/'.$v['username'];echo $v['uid'] == $uid?"(".$this->lgs['Personal'].")":'('.$this->lgs['Subordinate'].')'; ?></td>	
			<td align="center"><?php echo $v['time']; ?></td>
			<td align="center"><?php echo $v['type']==-3?"-":"+";echo $v['money']; ?></td>
			<td align="center"><?php echo $v['content']=='提现'?$this->lgs['Withdrawal']:$v['content']; ?></td>

		    </td>            	
		</tr>
       <?php } ?>
       <?php  if (!$list ): ?>
            <tr>
                <td colspan="13" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php  endif; ?>
	
  	</tbody>
	
</table>
    <div id="pages"><ul><li> <?php $this->lan("common");?><?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('one','li'); ?></ul></div>

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