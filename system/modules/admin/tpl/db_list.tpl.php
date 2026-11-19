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
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>

</head>
<style>
.layui-form-switch {
    margin-top: 0;
}
.layui-form-onswitch em {margin-left:0px;}
tbody tr{ line-height:30px; height:30px;}

/* 列表数据居中排列 - 使用更强的选择器 */
#myform table tbody tr td,
table tbody tr td,
.table-list table tbody tr td,
.table-list #myform table tbody tr td {
    text-align: center !important;
    vertical-align: middle !important;
}

/* 排序列（第2列）居中 - 包括输入框 */
#myform table tbody tr td:nth-child(2),
table tbody tr td:nth-child(2),
.table-list table tbody tr td:nth-child(2) {
    text-align: center !important;
    vertical-align: middle !important;
}

/* ID列（第3列）居中 */
#myform table tbody tr td:nth-child(3),
table tbody tr td:nth-child(3),
.table-list table tbody tr td:nth-child(3) {
    text-align: center !important;
    vertical-align: middle !important;
}

/* 名称列（第4列）居中 */
#myform table tbody tr td:nth-child(4),
table tbody tr td:nth-child(4),
.table-list table tbody tr td:nth-child(4) {
    text-align: center !important;
    vertical-align: middle !important;
}

/* 是否购物车列（第8列）隐藏 */
#myform table thead tr th:nth-child(8),
#myform table tbody tr td:nth-child(8),
table thead tr th:nth-child(8),
table tbody tr td:nth-child(8) {
    display: none !important;
}

/* 国家列（第9列）居中 */
#myform table tbody tr td:nth-child(9),
table tbody tr td:nth-child(9),
.table-list table tbody tr td:nth-child(9) {
    text-align: center !important;
    vertical-align: middle !important;
}

/* 状态列（第10列）隐藏 */
#myform table thead tr th:nth-child(10),
#myform table tbody tr td:nth-child(10),
table thead tr th:nth-child(10),
table tbody tr td:nth-child(10),
.table-list table thead tr th:nth-child(10),
.table-list table tbody tr td:nth-child(10) {
    display: none !important;
}

/* 操作列（第11列）居中 - 包含按钮 */
#myform table tbody tr td:nth-child(11),
table tbody tr td:nth-child(11),
.table-list table tbody tr td:nth-child(11) {
    text-align: center !important;
    vertical-align: middle !important;
}

#myform table tbody tr td:nth-child(11) a,
table tbody tr td:nth-child(11) a,
.table-list table tbody tr td:nth-child(11) a {
    margin: 0 auto !important;
    display: inline-block !important;
}

#myform table tbody tr td input[type="checkbox"],
table tbody tr td input[type="checkbox"] {
    margin: 0 auto !important;
    display: block !important;
}

#myform table tbody tr td img,
table tbody tr td img {
    margin: 0 auto !important;
    display: block !important;
}

/* 切换前和切换后列的图片样式 - 宽度38px，高度自适应 */
#myform table tbody tr td:nth-child(6) img,
#myform table tbody tr td:nth-child(7) img,
table tbody tr td:nth-child(6) img,
table tbody tr td:nth-child(7) img {
    width: 38px !important;
    max-width: 38px !important;
    min-width: 38px !important;
    height: auto !important;
    max-height: none !important;
    object-fit: contain !important;
}

#myform table thead tr th,
table thead tr th {
    text-align: center !important;
}

#myform table thead tr th input[type="checkbox"],
table thead tr th input[type="checkbox"] {
    margin: 0 auto !important;
    display: block !important;
}

/* 排序输入框样式 */
#myform table tbody tr td input[name^="listorders"],
table tbody tr td input[name^="listorders"],
#myform table tbody tr td input.input-text-c,
table tbody tr td input.input-text-c {
    width: 80px !important;
    max-width: 80px !important;
    min-width: 80px !important;
    text-align: center !important;
    padding: 0 !important;
    margin: 0 auto !important;
    display: block !important;
}

/* 修改、删除按钮样式 - 使用更具体的选择器确保优先级 */
#myform table tbody tr td a.butaoin,
#myform table tbody tr td a.butaish,
table tbody tr td a.butaoin,
table tbody tr td a.butaish,
.butaoin, .butaish,
a.butaoin, a.butaish {
    width: 80px !important;
    min-width: 80px !important;
    max-width: 80px !important;
    height: 50px !important;
    min-height: 50px !important;
    max-height: 50px !important;
    line-height: 50px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
    padding: 0 !important;
    margin: 0 5px !important;
    box-sizing: border-box !important;
    text-decoration: none !important;
    border-radius: 6px !important;
    transition: all 0.2s ease !important;
    font-size: 13px !important;
    white-space: nowrap !important;
}

#myform table tbody tr td a.butaoin,
table tbody tr td a.butaoin,
.butaoin,
a.butaoin {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: #fff !important;
    border: none !important;
}

#myform table tbody tr td a.butaoin:hover,
table tbody tr td a.butaoin:hover,
.butaoin:hover,
a.butaoin:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
}

#myform table tbody tr td a.butaish,
table tbody tr td a.butaish,
.butaish,
a.butaish {
    background: #fee2e2 !important;
    color: #991b1b !important;
    border: none !important;
}

#myform table tbody tr td a.butaish:hover,
table tbody tr td a.butaish:hover,
.butaish:hover,
a.butaish:hover {
    background: #fecaca !important;
    transform: translateY(-2px) !important;
}

.ssgjdiv {padding:10px 0 0 10px;}
.ssgjdiv form {display: flex;align-items: center;}
.ssgjdiv .button {height:32px;}
.ssgjdiv select {margin-left:10px;}
.btn_paixu {padding-left:0;}

/* 批量删除和排序按钮容器样式 - 确保在同一行 */
.btn_paixu > div,
.btn_paixu .btn-container {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    flex-wrap: nowrap !important;
    width: auto !important;
}

/* 批量删除按钮样式 - 确保文案居中 */
.batchDel.button,
input.batchDel.button,
input[type="button"].batchDel.button {
    width: 120px !important;
    min-width: 120px !important;
    max-width: 120px !important;
    flex-shrink: 0 !important;
    text-align: center !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    line-height: normal !important;
}

/* 排序按钮样式 - 确保与批量删除在同一行 */
.btn_paixu .button:not(.batchDel) {
    flex-shrink: 0 !important;
    white-space: nowrap !important;
}
input[type="text"]:not(.rightgray input):not(.posttime input):not([readonly][id*="time"]):not([readonly][id*="Time"]):not([readonly][id*="date"]):not([readonly][id*="Date"]) {
    text-align: center!important;
}
</style>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>

<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
    <form action="#" method="post" name="myform">

    <table width="100%" cellspacing="0">
    <thead>
		<tr>
            <th width="40px" align="center"><input id="checkAll" class="checkAll" type="checkbox"></th>
            <th width="80px"><?php $this->lan("sort");?></th>
            <th width="80px"><?php $this->lan("id");?></th>
            <th width="" align="center"><?php $this->lan("name","名称");?></th>
            <th align="center"><?php $this->lan("Jump_link");?></th>
            <th width="" align="center"><?php $this->lan("Before_switching");?></th>
            <th width="" align="center"><?php $this->lan("After_switching");?></th>
            <th width="" align="center" style="display:none;"><?php $this->lan("is_cart","是否购物车");?></th>
            <th width="" align="center"><?php $this->lan("country");?></th>
            <th width="" align="center" style="display:none;"><?php $this->lan("state");?></th>
		    <th width="330" align="center"><?php $this->lan("operation");?></th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($lists as $v){ ?>
		<tr>
            <td align="center" style="text-align: center !important;"><input class="checkOne" type="checkbox"  data-id="<?php echo $v['id']; ?>"></td>
			<td align="center" style="text-align: center !important;"><input name='listorders[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['sort']; ?>' class='input-text-c' style="border:0!important;"></td>
			<td align="center" style="text-align: center !important;"><?php echo $v['id']; ?></td>
			<td align="center" style="text-align: center !important;"><?php echo $v['title']; ?></td>
            <td align="center"><?php echo $v['link'];?></td>
			<td align="center"><img src="<?php echo G_UPLOAD_PATH; ?>/<?php echo $v['img']; ?>"/></td>
			<td align="center"><img src="<?php echo G_UPLOAD_PATH; ?>/<?php echo $v['picarr']; ?>"/></td>

			<td align="center" class="layui-form" style="display:none;">
                <input  type="checkbox"  class="is_state sta" name="type" <?php if($v['type'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="type" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("open");?>|<?php $this->lan("close");?>">
			</td>
			<td align="center" style="text-align: center !important;"><?php echo $v['language']?:$this->lgs['currency'];?></td>
            <td align="center" style="text-align: center !important; display:none;" class="layui-form">
                <input type="checkbox" class="is_state sta" name="is_state" <?php if($v['status'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("open");?>|<?php $this->lan("close");?>">
            </td>
			<td align="center" style="text-align: center !important;"> 
                <?php
                    if($model==1){
                        ?>
                <a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/wap/db_update/<?php echo $v['id'];?>"><?php $this->lan("modify");?></a>
                <a class="butaoin butaish" href="<?php echo G_MODULE_PATH; ?>/wap/db_delete/<?php echo $v['id'];?>"><?php $this->lan("delete");?></a>
                <?php
                    }else{
                ?>
                <a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/wap/jifen_db_update/<?php echo $v['id'];?>"><?php $this->lan("modify");?></a>
                <a class="butaoin butaish" href="<?php echo G_MODULE_PATH; ?>/wap/jifen_db_delete/<?php echo $v['id'];?>"><?php $this->lan("delete");?></a>
              <?php
                    }
                ?>
			</td>
		</tr>
		<?php } ?>
  	</tbody>
	</table>
</form>
  <div class="btn_paixu">
      <div class="btn-container">
        <input type="button" value=" <?php $this->lan("Batch_deletion");?> " class="batchDel button" >
        <input type="button" class="button" value=" <?php $this->lan("sort");?> " onclick="myform.action='<?php echo G_MODULE_PATH; ?>/wap/listorder/dosubmit';myform.submit();"/>
    </div>
  </div>
	<div class="btn_paixu"></div>
</div><!--table-list end-->

<script>
	// 强制应用按钮样式，确保宽度为80px
	$(document).ready(function() {
		// 强制设置按钮宽度和高度
		$('table tbody tr td a.butaoin, table tbody tr td a.butaish').each(function() {
			$(this).css({
				'width': '80px',
				'min-width': '80px',
				'max-width': '80px',
				'height': '50px',
				'min-height': '50px',
				'max-height': '50px',
				'line-height': '50px',
				'display': 'inline-flex',
				'align-items': 'center',
				'justify-content': 'center',
				'text-align': 'center',
				'padding': '0',
				'margin': '0 5px',
				'box-sizing': 'border-box',
				'white-space': 'nowrap'
			});
		});
		
		// 强制设置批量删除按钮宽度为120px，文案居中
		$('.batchDel.button, input.batchDel.button').css({
			'width': '120px',
			'min-width': '120px',
			'max-width': '120px',
			'text-align': 'center',
			'display': 'inline-flex',
			'align-items': 'center',
			'justify-content': 'center',
			'padding': '0',
			'line-height': 'normal'
		});
		
		// 确保批量删除和排序按钮在同一行
		$('.btn_paixu > div, .btn_paixu .btn-container').css({
			'display': 'flex',
			'align-items': 'center',
			'gap': '10px',
			'flex-wrap': 'nowrap',
			'width': 'auto'
		});
		
		$('.btn_paixu .button').css({
			'flex-shrink': '0',
			'white-space': 'nowrap'
		});
		
		// 强制设置排序输入框宽度为80px，内容居中
		
		// 强制设置切换前和切换后列的图片宽度为38px，高度自适应
		$('table tbody tr td:nth-child(6) img, table tbody tr td:nth-child(7) img').each(function() {
			$(this).css({
				'width': '38px',
				'max-width': '38px',
				'min-width': '38px',
				'height': 'auto',
				'max-height': 'none'
			});
		});
		
		// 隐藏是否购物车列（第8列）
		$('table thead tr th:nth-child(8), table tbody tr td:nth-child(8)').css('display', 'none');
		
		// 强制设置所有指定列的内容居中 - 覆盖内联样式
		// 排序列（第2列）
		$('table tbody tr td:nth-child(2)').each(function() {
			$(this).attr('style', 'text-align: center !important; vertical-align: middle !important;');
		});
		
		// ID列（第3列）
		$('table tbody tr td:nth-child(3)').each(function() {
			$(this).attr('style', 'text-align: center !important; vertical-align: middle !important;');
		});
		
		// 名称列（第4列）
		$('table tbody tr td:nth-child(4)').each(function() {
			$(this).attr('style', 'text-align: center !important; vertical-align: middle !important;');
		});
		
		// 国家列（第9列）
		$('table tbody tr td:nth-child(9)').each(function() {
			$(this).attr('style', 'text-align: center !important; vertical-align: middle !important;');
		});
		
		// 隐藏状态列（第10列）
		$('table thead tr th:nth-child(10), table tbody tr td:nth-child(10)').css('display', 'none');
		
		// 操作列（第11列）- 包含按钮
		$('table tbody tr td:nth-child(11)').each(function() {
			$(this).attr('style', 'text-align: center !important; vertical-align: middle !important;');
		});
		$('table tbody tr td:nth-child(11) a').each(function() {
			$(this).css({
				'margin': '0 auto',
				'display': 'inline-block'
			});
		});
	});
	
    layui.use(['form','jquery'],function () {
        var form = layui.form,
            $ = layui.jquery;

        form.render('checkbox');
        form.on('switch(type)',function (data) {
            var id = $(this).attr('data-state');
            var zhi = '';
            if (data.elem.checked == true) {
                zhi = 1;
            }else if(data.elem.checked == false){
                zhi = 2;
            }
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/wap/up_dbstatus',{id:id,type:zhi},function (data) {
                data = JSON.parse(data);
                if (data == 1){
                    layer.msg('修改成功');
                }else{
                    layer.msg('修改失败');
                }
            });
        });
        form.on('switch(is_state)',function (data) {
            var id = $(this).attr('data-state');
            var zhi = '';
            if (data.elem.checked == true) {
                zhi = 1;
            }else if(data.elem.checked == false){
                zhi = 0;
            }
            $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/wap/up_dbstatus',{id:id,status:zhi},function (data) {
                data = JSON.parse(data);
                if (data == 1){
                    layer.msg('修改成功');
                }else{
                    layer.msg('修改失败');
                }
            });
        });
        form.render();

    });

    $("#checkAll").on('change', function () {
        console.log("aaa")

    if ($(this).is(":checked")) { // 全选
        $(".checkOne").prop("checked",true);
    } else { // 反选
        $(".checkOne").prop("checked",false);
    }
	});
	$('.batchDel').click(function(){
         //获取所有选中的CheckBox的id
        var  ids="";
        $(".checkOne").each(function(i) {
            if($(this).prop('checked')){
            var id= $(this).attr("data-id");
            if(ids){
                ids = ids +","
            }
                ids = ids+id	
            }
        });
    // ajax批量删除
        $.post("<?php echo G_MODULE_PATH; ?>/wap/batch_del",{ids:ids},function(res){
            if (res == 0) {
                layer.msg("删除成功!");
                // alert("删除成功!");
                setTimeout(function(){
                    location.reload(false);
                },1000);
            }else {
                layer.msg("删除失败!");
            
            }
        })
        ids="";
  	});
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 