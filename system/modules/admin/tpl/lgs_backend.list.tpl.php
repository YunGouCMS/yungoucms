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
		display: none;
		background-color: mintcream;
	}
	li{
		border-top: 1px solid;
		div:nth-child(-n + 2){
			width: 40px;
		}
		div:nth-child(3){
			width: 150px;
		}

		div:nth-child(4){
			width: 50%;
		}
		div:nth-child(5){
			width: 200px;
		}
		/*div:nth-child(6){*/
		/*	width: 200px;*/
		/*}*/
	}

	li > div{
		/* float: left; */
		display: inline-block;
		margin-left: 10px;
		height: 30px;
		font-size: 16px;
	}
	.search{
		float: left;
		display: flex;
		align-items: center;
        margin-left: 20px;
        white-space: nowrap;
	}
		.posttimeform{
			height: 50px;
			margin-bottom: 20px;
		}
	.utoiwadiv{
	   	font-size: 12px;
	   	text-align: center;
	}
	.utoiwadiv a {
	    display: inline!important;
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
<form action="#" method="post" class="posttimeform">
 <div class="search">
	<label>key</label>
	<input name="key" type="text" class="input-text" placeholder="<?php $this->lan("enter_key");?>"  value="<?php echo $keys;?>"/>
 </div>
 <div class="search">
	<label>value</label>
	<input name="val" type="text" class="input-text" placeholder="请输入value"  value="<?php echo $val;?>"/>
 </div>
 <div class="search">
	<label><?php $this->lan("remarks");?></label>
	<input name="desc" type="text" class="input-text" placeholder="<?php $this->lan("enter_comment");?>"  value="<?php echo $desc;?>"/>
 </div>
 <input class="button" type="submit" name="sososubmit" value="<?php $this->lan("Submit");?>">
</form>

<form name="myform" id="myform" action="#" meliod="post">

	<ul>	
		<li>
			<div ></div>
			<div >ID</div>
			<div >key</div>
			<div ><?php $this->lan("value");?></div>
			<div ><?php $this->lan("remarks");?></div>
			<div style="text-align:center"><?php $this->lan("operation");?></div>
		</li>
		
		<?php foreach($treelist as $v){ ?>
		<li class="parent" data-id="<?php echo $v['id']; ?>">
			<div style="cursor: pointer;"><span class="parent-s parent-<?php echo $v['id']; ?>" data-id="<?php echo $v['id']; ?>">+</span></div>
			<div ><?php echo $v['id']; ?></div>
			<div ><?php echo $v['key']; ?></div>
			<div ><?php foreach($v['value']?:[] as $k=>$vl){echo $k.":".$vl." ";}?></div>
			<div ><?php echo $v['desc'];?></div>
			<div class="utoiwadiv">
				<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/lgsBackendAdd/<?php echo $v['id'];?>"><?php $this->lan("add_pack_value");?></a>
				<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/lgsBackendEdit/<?php echo $v['id'];?>"><?php $this->lan("modify");?></a>
				<a class="butaoin butaish" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/lgsBackendDel/<?php echo $v['id'];?>" onClick="return confirm('是否真的删除！');"><?php $this->lan("delete");?></a>
			</div>
			
			<ul>
				<?php foreach($v['children']?:[] as $vl){ ?>
				<li class="children children-<?php echo $vl['pid']; ?>" data-pid="<?php echo $vl['pid']; ?>" data-show="0">
					<div></div>
					<div><?php echo $vl['id']; ?></div>
					<div><?php echo $vl['key']; ?></div>
					<div><?php foreach($vl['value']?:[] as $index=>$val){echo $val['name'].":".$val['value']."|";}?></div>
					<div><?php echo $vl['desc'];?></div>
					<div class="utoiwadiv">
						<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/lgsBackendEdit/<?php echo $vl['id'].'/'.$vl['pid'];?>"><?php $this->lan("modify");?></a>
						<a class="butaoin butaish" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/lgsBackendDel/<?php echo $vl['id'];?>" onClick="return confirm('是否真的删除！');"><?php $this->lan("delete");?></a>
					</div>	
				</li>
				<?php } ?>
			</ul>
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
	$(".parent").click(function(e){
		var id = $(this).attr("data-id")
		var show = $(this).attr("data-show")
		var cla = "children-"+id
		if(show == '1'){
			$(this).attr("data-show",0)
			$("."+cla).css("display","none")
			$(".parent-"+id).html("+")
		}else{
			$(".parent-"+id).html("-")
			$(this).attr("data-show",1)
			$("."+cla).css("display","block")
		}
	})
	$(".parent a").on("click",function(e){
		e.stopPropagation();
	})
</script>
</html> 
