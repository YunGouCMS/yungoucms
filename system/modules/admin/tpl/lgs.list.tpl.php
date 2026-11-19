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
	.pagesli{
		float: left;
		display: block;
		line-height: 25px;
		padding: 0px 10px;
		background-color: #eef3f7;
		margin-left: 1px;
	}
	li{
		border-top: 1px solid;
		div:nth-child(-n + 2){
			width: 40px;
		}
		div:nth-child(3){
			width: 150px;
			word-wrap: break-word;
		}

		div:nth-child(4){
			width: calc(100% - 810px);
		}
		div:nth-child(5){
			width: 200px;
		}
		div:nth-child(6){
			width: 300px;
		}
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
	.search {
	    display: flex;
	    white-space: nowrap;
        align-items: center;
        margin-right: 20px;
	}
	.utoiwadiv {
	    transform: translateY(-4px);
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
 <input class="button" type="submit" name="sososubmit" value="<?php $this->lan("search");?>">
</form>

<form name="myform" id="myform" action="#" meliod="post">

	<ul>	
		<li>
			<div ></div>
			<div >ID</div>
			<div >key</div>
			<div ><?php $this->lan("value");?></div>
			<div ><?php $this->lan("remarks");?></div>
			<div ><?php $this->lan("operation");?></div>
		</li>
		
		<?php foreach($treelist as $v){ ?>
		<li class="parent" data-id="<?php echo $v['id']; ?>">
			<div style="cursor: pointer;"><span class="parent-s parent-<?php echo $v['id']; ?>" data-id="<?php echo $v['id']; ?>">+</span></div>
			<div ><?php echo $v['id']; ?></div>
			<div ><?php echo $v['key']; ?></div>
			<div ><?php foreach($v['value'] as $k=>$vl){echo $k.":".$vl." ";}?></div>
			<div ><?php echo $v['desc'];?></div>
			<div class="utoiwadiv">
				<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/lgsAdd/<?php echo $v['id'].'/'.$type;?>"><?php $this->lan("add_pack_value");?></a>
				<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/lgsEdit/<?php echo $v['id'].'/'.$type;?>"><?php $this->lan("modify");?></a>
				<a class="butaoin butaish" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/lgsDel/<?php echo $v['id'].'/'.$type;?>" onClick="return confirm('是否真的删除！');"><?php $this->lan("delete");?></a>
			</div>
			
			<ul></ul>
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
		var ul = $(this).find("ul")
		if(show == '1'){
			$(this).attr("data-show",0)
			$("."+cla).css("display","none")
			$(".parent-"+id).html("+")
			$(this).find("ul").css("display","none")
		}else{
			$(".parent-"+id).html("-")
			$(this).attr("data-show",1)
			if(!$(ul).html()){
				//等待异步请求完成
				getChildren(id).then(response => {
							// 处理返回的数据
							$(this).find("ul").append(response.html)
							$(this).find("ul").append(response.page)
							// 后续操作
						})
						.catch(error => {
							console.error('请求失败:', error);
						});
			}
			$("."+cla).css("display","block")
			$(this).find("ul").css("display","block")
		}
		return
		var key = $(".posttimeform").find("input[name='key']").val()
		var val  = $(".posttimeform").find("input[name='val']").val()
		var desc = $(".posttimeform").find("input[name='desc']").val()
		
	})
	function getChildren(id,p=1){
		return new Promise((resolve, reject) => {
			$.post("<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/getLanuageChildren",{"id":id,"p":p,"type":"<?php echo $type;?>"},function(res){
				res = JSON.parse(res)
				var data = res.data
				var list = data.list
				var type = '<?php echo $type;?>'
				var html = ""
				for (const key in list) {
					const element = list[key];
					html += '<li class="children children-'+element.pid+'" data-pid="'+element.pid+'" data-show="0">';
					html += '<div></div>';
					html += '<div>'+element.id+'</div>';
					html += '<div>'+element.key+'</div>';
					html += '<div>';
					for (const k in element.value) {
						const el = element.value[k];
						html += el.name+":"+el.value+"|";
					}
					html += '</div>';
					html += '<div>'+element.desc+'</div>';
					html += '<div class="utoiwadiv">';
					html += '<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/lgsEdit/'+element.id+'/'+type+'"><?php $this->lan("modify");?></a>';
					html += '<a class="butaoin butaish" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/language/lgsDel/'+element.id+'/'+type+'" onClick="return confirm(\'是否真的删除！\');"><?php $this->lan("delete");?></a>';
					html += '</div></li>';
				}
				var par_page = data.p - 1; //上一页
				var next_page = data.p + 1; //下一页
				var page = '<li class="children"><ul id="pages">'
				page += "<li class='pagesli' data-p='"+par_page+"'>上一页</li>"
				page += "<li class='pagesli'>"+data.p+"/"+data.pagecount+"</li>";
				page += "<li class='pagesli' data-p='"+next_page+"'>下一页</li>"
				page += "<li class='pagesli' data-p='1'>首页</li>"
				page += "<li class='pagesli' data-p='"+data.pagecount+"'>尾页</li>"
				page += '</ul></li>'
				resolve({"html":html,"page":page});
			}).fail(function(error){
				reject(error);
			})
		})
	}
	$(".parent").on("click",".pagesli",function(e){
		e.stopPropagation();
		var p = $(this).attr("data-p")
		var parent = $(this).parent().parent().parent().parent()
		var id = $(parent).attr("data-id")
		if(p){
			getChildren(id,p).then(response => {
								// 处理返回的数据
								$(parent).find("ul").html("")
								$(parent).find("ul").append(response.html)
								$(parent).find("ul").append(response.page)
								// 后续操作
							})
							.catch(error => {
								console.error('请求失败:', error);
							});
		}
	})
	$(".parent a").on("click",function(e){
		e.stopPropagation();
	})
</script>
</html> 
