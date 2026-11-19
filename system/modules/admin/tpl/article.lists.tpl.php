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
    <title><?php echo $this->getPageTitle('文章列表'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css"> 
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <!-- 后台各页面详细UI升级样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<style>
body{ background-color:#fff}
.header-data{
	border: 1px solid #FFBE7A;
	zoom: 1;
	background: #FFFCED;
	padding: 8px 10px;
	line-height: 20px;
}
.table-list {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.table-list  tr {
	text-align:center;
}
.ssgjdiv {padding:10px 0 0 10px;}
.ssgjdiv form {display: flex;align-items: center;}
.ssgjdiv .button {height:32px;}
.ssgjdiv select {margin-left:10px!important;}
.butaoin, .butaish {
    padding: 6px 16px !important;
    border-radius: 6px !important;
    font-size: 13px !important;
    transition: all 0.2s ease !important;
    text-decoration: none !important;
    display: inline-block !important;
    margin: 0 4px !important;
}
.butaoin {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: #fff !important;
}
.butaoin:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
}
.butaish {
    background: #fee2e2 !important;
    color: #991b1b !important;
}
.butaish:hover {
    background: #fecaca !important;
    transform: translateY(-2px) !important;
}
.button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: none !important;
    border-radius: 6px !important;
    color: #fff !important;
    padding: 8px 16px !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
}
.button:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="ssgjdiv">
    <form action="#" method="post">
        <!--名称：<input type="text" name="title">-->
         <?php $this->lan("Belonging_country");?>
        <select name="language">
            <option value="all" <?php echo ($lan == "all") ? "selected" : "";?>><?php $this->lan("all")?></option>
            <option value="general" <?php echo ($lan == "general") ? "selected" : "";?>><?php $this->lan("general")?></option>
            <?php 
                foreach($language as $vl){
                    $sel = ($lan == $vl['lg_value']) ? "selected" : "";
                    echo "<option value='{$vl['lg_value']}' {$sel}>{$vl['country']}</option>";
                }
            ?>
        </select>
        <input type="submit" value="<?php $this->lan("filter");?>" name="sososubmit" class="button">
    </form>
</div>
<div class="bk10"></div>
<!--<div class="header-data lr10">-->
<!--<form action="#" method="post">-->
<!-- 添加时间  <input name="posttime1" type="text" id="posttime1" class="input-text posttime" placeholder="请输入开启时间" readonly="readonly" /> --->
<!-- 		  <input name="posttime2" type="text" id="posttime2" class="input-text posttime" placeholder="请输入结束时间" readonly="readonly" />-->
<!--<script type="text/javascript">-->
<!--		date = new Date();-->
<!--		Calendar.setup({-->
<!--					inputField     :    "posttime1",-->
<!--					ifFormat       :    "%Y-%m-%d %H:%M:%S",-->
<!--					showsTime      :    true,-->
<!--					timeFormat     :    "24"-->
<!--		});-->
<!--		Calendar.setup({-->
<!--					inputField     :    "posttime2",-->
<!--					ifFormat       :    "%Y-%m-%d %H:%M:%S",-->
<!--					showsTime      :    true,-->
<!--					timeFormat     :    "24"-->
<!--		});-->
<!--				-->
<!--</script>-->
<!---->
<!--<select name="sotype">-->
<!--<option value="cateid">栏目id</option>-->
<!--<option value="catename">栏目名称</option>-->
<!--<option value="title">文章标题</option>-->
<!--<option value="id">文章ID</option>-->
<!--</select>-->
<!--<input type="text" name="sosotext" placeholder="请输入搜索内容" class="input-text wid100"/>-->
<!--<input class="button" type="submit" name="sososubmit" value="搜索">-->
<!--</form>-->
<!--</div>-->
<!-- <div class="bk10"></div> -->
<form action="#" method="post" name="myform">
<div class="table-list lr10">
	 <table width="100%" cellspacing="0" style="border-width: 1px 1px 0px 0px;">
     	<thead>
        		<tr>
                	<th width="5%"><?php $this->lan("sort");?></th>
                    <th width="4%"><?php $this->lan("id");?></th>        
                    <th width="15%"><?php $this->lan("Article_title");?></th>
                    <!--<th width="25%"><?php $this->lan("Jump_link");?></th>-->
                     <th width="10%"><?php $this->lan("Belonging_category");?></th>
                     <th width="10%"><?php $this->lan("country");?></th>
                     <th width="10%"><?php $this->lan("keys");?></th>
                    <th width="10%"><?php $this->lan("add_time");?></th>        
                    <th width="15%"><?php $this->lan("operation");?></th>
				</tr>
        </thead>
        <tbody>				
        	<?php foreach($articlelist AS $v) { ?>
            <tr style="border-left: 1px solid #E2E2E2;">
              <td align='center'><input name='listorders[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['order']; ?>' class='input-text-c' style="border: 0!important;text-align: center!important;"></td>
                <td><?php echo $v['id'];?></td>
                <td><a href="/mobile/mobile/gonggaoxq/<?php echo $v['id']; ?>" target="_blank"><?php echo _strcut($v['title'],0,25);?></a></td>
                <!--<td><a href="/mobile/mobile/gonggaoxq/<?php echo $v['id']; ?>" target="_blank"><?php echo WEB_PATH ?>/mobile/mobile/gonggaoxq/<?php echo $v['id']; ?></a></td>-->
                <td><a href="<?php echo G_ADMIN_PATH; ?>/content/article_list/<?php echo $v['cateid']; ?>"><?php echo $v['name']; ?></a></td>
                <td><?php echo $v['language'];?></td>
                <td><?php echo $v['keys'];?></td>
                <td><?php echo date("Y/m/d H:i:s",$v['posttime']);?></td>
                <td class="action">
                <a class="butaoin" href="<?php echo G_ADMIN_PATH; ?>/content/article_edit/<?php echo $v['id'];?>"><?php $this->lan("modify");?></a>
				<a class="butaoin butaish a_form_noparam" data-confirm="<?php $this->lan("confirm");?>" data-cancel="<?php $this->lan("cancel");?>" href="<?php echo G_ADMIN_PATH.'/content/article_del/'.$v['id'];?>"><?php $this->lan("delete");?></a>
				</td>
            </tr>
            <?php } ?>
			<tr>
				<td align='right' style="border-width: 0px;display:flex;padding-left:0!important;"><input type="button" class="button" value=" <?php $this->lan("sort");?> "
        onclick="myform.action='<?php echo G_MODULE_PATH; ?>/content/article_listorder/dosubmit';myform.submit();" style="margin:0!important;"/></td>
			</tr>
        </tbody>
     </table>
     </form>
<!--<div id="pages"><ul><li>共 --><?php //echo $total; ?><!-- 条</li>--><?php //echo $page->show('one','li'); ?><!--</ul></div>-->
</div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html> 