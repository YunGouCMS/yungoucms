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
<style>
.layui-form-switch {
    margin-top: 0;
}
.layui-form-onswitch em {margin-left:0px;}
#myform tr td img {width:auto;height:36px;}

/* 语言列表数据居中排列 */
#myform table tbody tr td {
    text-align: center !important;
    vertical-align: middle !important;
}

#myform table thead tr th {
    text-align: center !important;
}

/* 排序输入框样式 */
#myform table tbody tr td input[name^="listorders"] {
    width: 80px !important;
    max-width: 80px !important;
    min-width: 80px !important;
    text-align: center !important;
    padding: 0 !important;
    margin: 0 auto !important;
    display: block !important;
}

/* 导入、修改、删除按钮样式 - 使用更具体的选择器确保优先级 */
#myform table tbody tr td a.butaoin,
#myform table tbody tr td a.butaish,
#myform table tbody tr td a.import.butaoin,
table tbody tr td a.butaoin,
table tbody tr td a.butaish,
table tbody tr td a.import.butaoin,
.butaoin, .butaish, .import.butaoin,
a.butaoin, a.butaish, a.import.butaoin {
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
</style>
</head>
<body>
<div class="header lr10">

<?php echo $this->headerment();?>

</div>

<div class="bk10"></div>

<div class="table-list lr10">

<!--start-->

<form name="myform" id="myform" action="#" method="post">

<table width="100%" cellspacing="0">	
<thead>
		<tr>
			<th width="60px"> <?php $this->lan("id");?></th>
			<th width=""> <?php $this->lan("sort");?></th>
            <th width="" align="center"> <?php $this->lan("Pack_code");?></th>
		    <th width="" align="center"> <?php $this->lan("country");?></th>
		    <th width="" align="center"> <?php $this->lan("national_flag");?></th>
		    <th width="" align="center"> <?php $this->lan("national_flag_status");?></th>
		    <th width="" align="center"> <?php $this->lan("area_code");?></th>
			<th width="" align="center"> <?php $this->lan("currency");?></th>
			<th width="" align="center"> <?php $this->lan("time_zone");?></th>
            <th align="center"> <?php $this->lan("state");?></th>
			<!-- <th align="center">是否缺失</th> -->
			<th width="350" align="center"> <?php $this->lan("operation");?>
				<!-- <a href="">导入</a> -->
			</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($languages as $key => $v){ ?>
		<tr>
			<td align="center"><?php echo $v['id']; ?></td>
			<td align='center'><input name='listorders[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['sort'];?>' class='input-text-c' style="border: 0!important;"></td>
            <td align="center"><?php echo $v['lg_value']; ?></td>
            <td align="center"><?php echo $v['country'];?></td>
            <td align="center"><img src="<?php echo $v['national_flag'];?>" alt=""></td>
            <td align="center">
				 <?php echo $v['flag_status'] == 1?$this->lan("open","open"):$this->lan("close");?>
				<!-- <input type="checkbox" class="flag_status sta" name="flag_status" <?php if($v['flag_status'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="flag_status" data-state="<?php echo $v['id'] ?>" lay-text="开启|关闭"> -->
			</td>
            <td align="center"><?php echo $v['area_code'];?></td>
            <td align="center"><?php echo $v['currency_unit'];?></td>
			<td align="center"><?php echo $v['time_zone'];?></td>
			<td align="center" class="layui-form">
			    <input type="checkbox" class="is_state sta" name="is_state" <?php if($v['status'] == 1){ echo 'checked=""';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("open");?>|<?php $this->lan("close");?>">
			</td>
			<!-- <td align="center"><?php echo $v['deletion']==1?'<?php $this->lan("abnormal");?>':'<?php $this->lan("normal");?>' ;?></td> -->
			<td align="center">
				<a class="import butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/setting/languageImport/<?php echo $v['lg_value'].'/'.$type;?>"> <?php $this->lan("import");?></a>
				<!--<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/setting/languageExport/<?php echo $v['id'].'/'.$type;?>">导出</a>-->
				<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/setting/languageUpdate/<?php echo $v['id'];?>/<?php echo $type?>"> <?php $this->lan("modify");?></a>
				<a class="butaoin butaish" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/setting/languageDelete/<?php echo $v['id'].'/'.$type;?>" onClick="return confirm('是否真的删除！');"> <?php $this->lan("delete");?></a>
			</td>	
		</tr>
		<?php } ?>
		<tr>
			<td colspan="3" style="border-width: 0px;padding-left: 15px!important;display: flex;" align='left'>
				<!-- <input type="button" value=" 批量删除 " class="batchDel button"> -->
				<input type="button" class="button" value=" <?php $this->lan("sort");?> " onclick="myform.action='<?php echo G_MODULE_PATH; ?>/setting/languageSort/dosubmit';myform.submit();"/>
			</td>	
		</tr>
  	</tbody>
</table>
</form>
</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
<script>
	$(".import").click(function(e){
		if(confirm('确定导入?')==false){
			return false;
		}else{
			layer.msg('正在导入中,请耐心等待..')
		}
	})
	// 强制应用按钮样式，确保宽度为80px
	$(document).ready(function() {
		// 强制设置按钮宽度和高度
		$('#myform table tbody tr td a.butaoin, #myform table tbody tr td a.butaish, #myform table tbody tr td a.import.butaoin').each(function() {
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
	});
	
	layui.use(['form','jquery'],function () {
        var form = layui.form,
        $ = layui.jquery;
        form.render('checkbox');
        form.on('switch(is_state)',function (data) {
            var id = $(this).attr('data-state');
            var status = '';
            if (data.elem.checked == true) {
                status = 1;
            }else if(data.elem.checked == false){
                status = 0;
            }
            $.post("<?php echo WEB_PATH.'/'.ROUTE_M; ?>/setting/languageSetStatus",{id:id,status:status},function (data) {
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
</script>
</html> 
