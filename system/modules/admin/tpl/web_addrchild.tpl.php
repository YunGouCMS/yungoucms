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
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>

<style>
	.parent-s{
		font-size: 20px;
	}
	.parent{
		background-color: antiquewhite;
	}
	.children{
		/* display: none; */
		background-color: mintcream;
	}
	.title,.parent{
		border-top: 1px solid;
		div:nth-child(-n + 2){
			width: 40px;
		}
		div:nth-child(3){
			width: 150px;
		}

		div:nth-child(4){
			width: 45%;
		}
		div:nth-child(5){
			width: 200px;
		}
		/*div:nth-child(6){*/
		/*	width: 200px;*/
		/*}*/
	}
    .butaoin {
        height: 15px!important;
        min-width: auto!important;
    }
    .utoiwadiv {
        transform: translateY(-5px);
    }
	.title > div,.parent > div{
		/* float: left; */
		display: inline-block;
		margin-left: 10px;
		height: 30px;
		font-size: 16px;
	}
	.search{
		float: left;
	}
		.posttimeform{
			height: 50px;
		
		}
	.utoiwadiv{
	   	font-size: 12px;
	   	text-align: center;
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
<!-- <form action="#" method="post" class="posttimeform">
 <div class="search">
	<label>key</label>
	<input name="key" type="text" class="input-text" placeholder="<?php $this->lan("enter_key");?>"  value="<?php echo $keys;?>"/>
 </div>
 <div class="search">
	<label><?php $this->lan("remarks");?></label>
	<input name="desc" type="text" class="input-text" placeholder="<?php $this->lan("enter_comment");?>"  value="<?php echo $desc;?>"/>
 </div>
 <input class="button" type="submit" name="sososubmit" value="<?php $this->lan("Submit");?>">
</form> -->

<form name="myform" id="myform" action="#" meliod="post">

	<ul class="list">	
		<li class="title">
			<div ></div>
			<div >ID</div>
			<div ><?php $this->lan("Province","省/市");?></div>
			<div ><?php $this->lan("code","代码");?></div>
			<div ><?php $this->lan("remarks","备注");?></div>
			<div style="text-align:center"><?php $this->lan("operation","操作");?></div>
		</li>
		
		<?php foreach($lists as $v){ ?>
		<li >
			<div class="parent" data-id="<?php echo $v['id']; ?>" data-level='0'>
				<div style="cursor: pointer;"><span class="parent-s parent-<?php echo $v['id']; ?>" data-id="<?php echo $v['id']; ?>">+</span></div>
				<div ><?php echo $v['id']; ?></div>
				<div ><?php echo $v['name']; ?></div>
				<div ><?php echo $v['code'];?></div>
				<div ><?php echo $v['desc'];?></div>
				<div class="utoiwadiv">
					<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/set_addrchild_add/<?php echo $v['id'];?>"><?php $this->lan("add_pack_value",'添加下级');?></a>
					<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/set_addrchild_edit/<?php echo $v['id'];?>"><?php $this->lan("modify","修改");?></a>
					<a class="butaoin butaish" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/set_addrchild_del/<?php echo $v['id'];?>" onClick="return confirm('是否真的删除！');"><?php $this->lan("delete","删除");?></a>
				</div>
			</div>
			<div class="children">

			</div>
		</li>
		<?php } ?>
  	</ul>
	  <div id="pages"><ul><li><?php $this->lan("total");?> <?php echo $total; ?> <?php $this->lan("item");?></li><?php echo $page->show('one','li'); ?></ul></div>
</u>

</form>
</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
<script>
	var arr = ["省/市","区/县","街道/乡镇","村/社区"]
	var bgcorol = ["省/市","darkorange","darkgoldenrod","sienna"]
	$(".list").on("click",".children-hidden",function(e){
		$(this).parent().hide()
	})
	$(".list").on("click",".parent",function(e){
	    var id = $(this).attr("data-id")
		var show = $(this).attr("data-show")
		var level = $(this).attr("data-level")
		level = parseInt(level) + 1 
		var that = this
		$.ajax({
			url: "<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/get_addrchild/"+id,
			type: "post",
			data: {},
			success: function(data){
				var datas = JSON.parse(data)
				var lists = datas.data
				if(lists.length == 0){
					//提示没有了
					layer.msg("没有下级了!");
					return
				}
				var str = `<ul class="children children-<?php echo $v['id']; ?>">
								<li class="title children-hidden">
									<div ></div>
									<div ><?php $this->lan("ID");?></div>
									<div >`+arr[level]+`</div>
									<div ><?php $this->lan("code","代码");?></div>
									<div ><?php $this->lan("remarks","备注");?></div>
									<div style="text-align:center"><?php $this->lan("operation","操作");?></div>
								</li>`
				for(var i in lists){
					var el = lists[i]
					str += `<li >
								<div class="parent" data-id="`+el.id+`" data-level='`+level+`' style="background-color:`+bgcorol[level]+`">
									<div style="cursor: pointer;"><span class="parent-s parent-`+el.id+`" data-id="`+el.id+`">+</span></div>
									<div >`+el.id+`</div>
									<div >`+el.name+`</div>
									<div >`+el.code+`</div>
									<div >`+el.desc+`</div>
									<div class="utoiwadiv">
										<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/set_addrchild_add/`+el.id+`"><?php $this->lan("add_pack_value","添加下级");?></a>
										<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/set_addrchild_edit/`+el.id+`"><?php $this->lan("modify","修改");?></a>
										<a class="butaoin butaish" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/set_addrchild_del/`+el.id+`" onClick="return confirm('是否真的删除！');"><?php $this->lan("delete","删除");?></a>
									</div>
								</div>
								<div class="children">

								</div>
							</li>`
				}
				str += `</ul>`
				$(that).next().html('')
				$(that).next().append(str)
				$(that).next().show()
			}
		})
		// return datas
	})
	$(".parent a").on("click",function(e){
		e.stopPropagation();
	})
</script>
</html> 
