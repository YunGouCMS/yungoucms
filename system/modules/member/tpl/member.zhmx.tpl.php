<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
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
<style>
        tbody tr {line-height: 30px;height: 30px;}
        .con_a_btn {border: 0px solid #0c0}
        .con_a_btn a {display: inline-block;padding: 3px 6px;margin-right: 5px;background: #6CF;color: #fff;font-weight: bold;text-decoration: none;cursor: pointer}
    </style>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>

    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>

    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_PLUGIN_PATH; ?>/messageModal/message.js"></script>

    <script src="<?php echo G_PLUGIN_PATH; ?>/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>

    <script type="text/javascript" src="<?php echo G_TEMPLATES_JS; ?>/layui/layui.js"></script>
</head>
<body>
<div class="header lr10"> <?php echo $this->headerment(); ?> </div>

<div class="bk10"></div>

<div class="header-data lr10">

    <form action="#" method="post" class="posttimeform">

        <?php $this->lan("Search_time");?>&nbsp;&nbsp;
		<div class="posttime">
			<input name="posttime1" type="text" id="posttime1" class="input-text" placeholder="<?php $this->lan("Search_time1");?>" readonly="readonly" /> 
      -
			<input name="posttime2" type="text" id="posttime2" class="input-text" placeholder="<?php $this->lan("Search_time2");?>" readonly="readonly" />
		</div>

        <script type="text/javascript">

            date = new Date();

            Calendar.setup({
                inputField     :    "posttime1",
                ifFormat       :    "%Y-%m-%d %H:%M:%S",
                showsTime      :    true,
                timeFormat     :    "24"
            });

            Calendar.setup({
                inputField     :    "posttime2",
                ifFormat       :    "%Y-%m-%d %H:%M:%S",
                showsTime      :    true,
                timeFormat     :    "24"
            });



        </script>

        <script type="text/javascript">

            function r_submit(){
                window.location.href="<?php echo WEB_PATH; ?>/member/member/zhmx/<?php echo $info[0]['uid'] ?>"
            }
        </script>
        <!--<br><span style="display: block;height: 10px;width: 10px;"></span>-->

        <input class="button" type="submit" name="sososubmit" value="<?php $this->lan("Search1");?>">
        <input class="button" type="button"  value="<?php $this->lan("Reset");?>" onclick="r_submit()"><?php $this->lan("tips7");?>

    </form>



</div>

<div class="bk10"></div>

<div class="header-data lr10">

    <b><?php $this->lan("tips5");?></b> <?php echo $zhmx['account_cz'];?>


    <b style="margin-left: 100px"><?php $this->lan("tips6");?></b> <?php echo $zhmx['chashout']; ?>



</div>
<div class="bk10"></div>
<div class="table-list lr10">
  <table width="100%" cellspacing="0">
    <thead>
      <tr>
        <th width="100px" align="center"><?php $this->lan("ID");?></th>
          <th width="100px" align="center"><?php $this->lan("Merchant_order_number");?></th>
        <th width="100px" align="center"><?php $this->lan("Operation_notes");?></th>
        <th width="100px" align="center"><?php $this->lan("Operation_time");?></th>
        <th width="100px" align="center"><?php $this->lan("Funding_changes");?></th>
        <th width="100px" align="center"><?php $this->lan("Remark");?></th>
        <th width="100px" align="center"><?php $this->lan("operation");?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($info as $v) { ?>
      <tr>
        <td align="center"><?php echo $v['uid']; ?></td>
          <td align="center"><?php echo $v['code']; ?></td>
        <td align="center"><?php echo $v['content']; ?></td>
        <td align="center"><?php echo $v['time']; ?></td>
        <td align="center">
          <?php
           
            if($v['pay'] == '账户'){
              if($v['type'] == 1){
               echo '+ '.$v['money']." 元";
              }else{
               echo '- '.$v['money']." 元";
              }
            }else{
              echo '0 元';
            } 
           ?>
        </td>
          <td align="center"><?php echo $v['message']; ?></td>
          <td align="center">
              <a href="javascript:;" onClick="userListOpen('添加备注','<?php echo G_MODULE_PATH; ?>/member/massage/<?php echo $v['id']; ?>/<?php echo $v['status']; ?>')"><?php $this->lan("Add_notes");?></a><span class="span_fenge lr5"></span>
          </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<!--table-list end-->

<script>

    function userListOpen(title,url){
        var index = layer.open({
            type: 2,
            title: title,
            shadeClose: false,
            shade: false,
            maxmin: false, //开启最大化最小化按钮
            area: ['600px', '400px'],
            content: url,
            end:function() {
                location.reload();
            }
        });
        // layer.full(index);
    }
</script>
</body>
</html>
