<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $this->getPageTitle('会员列表'); ?></title>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
  <!-- 高级骨架屏系统样式 -->
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
  <!-- 统一后台页面美化样式 -->
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
  <!-- 后台通用页面增强样式 -->
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
  <!-- 通知消息系统样式 -->
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
  <!-- 后台各页面详细UI升级样式 -->
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
  
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">

  <style>
    /* 会员列表页面美化样式 */
    body {
      background: #f5f7fa;
    }
    
    .header.lr10 {
      margin-bottom: 20px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .header-data.lr10 {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    .table-list.lr10 {
      background: #fff;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
      /*overflow-x: auto;*/
    }
    
    /* 表格美化 */
    .table-list table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }
    
    .table-list thead {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .table-list thead th {
      color: #fff;
      font-weight: 600;
      padding: 16px 12px;
      text-align: center;
      border: none;
      font-size: 14px;
      white-space: nowrap;
    }
    
    .table-list thead th:first-child {
      border-radius: 8px 0 0 0;
    }
    
    .table-list thead th:last-child {
      border-radius: 0 8px 0 0;
    }
    
    .table-list tbody tr {
      transition: all 0.3s ease;
      border-bottom: 1px solid #f0f0f0;
    }
    
    .table-list tbody tr:hover {
      background: #f8f9ff;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
    }
    
    .table-list tbody td {
      padding: 16px 12px;
      text-align: center;
      color: #333;
      font-size: 14px;
      vertical-align: middle;
    }
    
    .table-list tbody tr:last-child td {
      border-bottom: none;
    }
    
    /* 按钮美化 */
    .butaoin {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
      color: #fff !important;
      padding: 8px 16px !important;
      border-radius: 6px !important;
      font-size: 13px !important;
      font-weight: 500 !important;
      border: none !important;
      cursor: pointer !important;
      transition: all 0.3s ease !important;
      text-decoration: none !important;
      display: inline-block !important;
      margin: 0 4px !important;
      box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3) !important;
    }
    
    .butaoin:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
    }
    
    .butaish {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
      box-shadow: 0 2px 4px rgba(245, 87, 108, 0.3) !important;
    }
    
    .butaish:hover {
      box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4) !important;
    }
    
    .button {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
      color: #fff !important;
      padding: 10px 20px !important;
      border-radius: 6px !important;
      border: none !important;
      cursor: pointer !important;
      font-size: 14px !important;
      font-weight: 500 !important;
      transition: all 0.3s ease !important;
      box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3) !important;
    }
    
    .button:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
    }
    
    /* 输入框美化 */
    .input-text {
      border: 1px solid #e1e8ed !important;
      border-radius: 6px !important;
      padding: 10px 12px !important;
      font-size: 14px !important;
      transition: all 0.3s ease !important;
    }
    
    .input-text:focus {
      border-color: #667eea !important;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
      outline: none !important;
    }
    
    /* 选择框美化 */
    select {
      border: 1px solid #e1e8ed !important;
      border-radius: 6px !important;
      padding: 10px 12px !important;
      font-size: 14px !important;
      background: #fff !important;
      transition: all 0.3s ease !important;
    }
    
    select:focus {
      border-color: #667eea !important;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
      outline: none !important;
    }
    
    /* 复选框美化 */
    input[type="checkbox"] {
      width: 18px !important;
      height: 18px !important;
      cursor: pointer !important;
      accent-color: #667eea !important;
    }
    
    /* 头像美化 */
    .table-list tbody img[style*="width:30px"] {
      border-radius: 50% !important;
      border: 2px solid #e1e8ed !important;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }
    
    
    /* 分页美化 */
    #pages ul {
      display: flex;
      align-items: center;
      gap: 8px;
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    #pages ul li {
      padding: 8px 12px;
      border-radius: 6px;
      background: #f5f7fa;
      color: #333;
      font-size: 14px;
      transition: all 0.3s ease;
    }
    
    #pages ul li a {
      color: #667eea;
      text-decoration: none;
      font-weight: 500;
    }
    
    #pages ul li:hover {
      background: #f8f9ff;
    }
    
    /* 导出按钮美化 */
    .exportBtn {
      display: flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #fff;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
      text-decoration: none;
      margin-top: 0!important;
    }
    
    .exportBtn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .exportBtn img {
      width: 18px;
      height: 18px;
      filter: brightness(0) invert(1);
    }
    
    /* 批量操作区域美化 */
    .btn_paixu {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 16px 20px;
      margin-top: 20px;
    }
    
    /* 状态标签美化 */
    .table-list tbody td font[style*="color:red"] {
      background: #fff5f5;
      color: #e53e3e !important;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
    }
    
    /* 在线状态图标美化 */
    .table-list tbody td image[src*="zaixian"] {
      filter: drop-shadow(0 2px 4px rgba(34, 197, 94, 0.3));
    }
    
    .table-list tbody td image[src*="lixian"] {
      filter: drop-shadow(0 2px 4px rgba(156, 163, 175, 0.3));
    }
    
    /* 响应式设计 */
    @media (max-width: 768px) {
      .table-list {
        padding: 16px;
      }
      
      .table-list table {
        font-size: 12px;
      }
      
      .table-list thead th,
      .table-list tbody td {
        padding: 10px 6px;
      }
    }
    
    /* 原有样式保留 */
    tbody tr {
      line-height: 30px;
      height: 30px;
    }

    .con_a_btn {
      border: 0px solid #0c0
    }

    .con_a_btn a {
      display: inline-block;
      padding: 3px 6px;
      margin-right: 5px;
      background: #6CF;
      color: #fff;
      font-weight: bold;
      text-decoration: none;
      cursor: pointer
    }

    .remarks {
      margin: 10px 0;
    }

    .remarks p {
      line-height: 32px;
    }

    .remarks p a {
      background: #161E22;
      font-size: 12px;
      padding: 4px 6px;
      color: #fff;
      border-radius: 5px;
      margin-left: 20px;
    }
    
    /* 更多操作菜单美化 */
    .hoverdiva {
      display: inline-block;
      cursor: pointer;
    }
    
    .hoverdiva div {
      background: #fff !important;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
      padding: 8px 0 !important;
      min-width: 150px !important;
      border: 1px solid #e1e8ed !important;
      display: none;
      position: absolute;
      top: 30px;
      right: -15px;
      min-width: 70px;
      flex-wrap: wrap;
      justify-content: space-between;
      border-radius: 8px !important;
      z-index: 100;
      flex-direction: column;
    }
    
    .hoverdiva div a {
      display: block !important;
      padding: 10px 16px !important;
      color: #333 !important;
      text-decoration: none !important;
      transition: all 0.2s ease !important;
      font-size: 13px !important;
      border: none !important;
      background: transparent !important;
      margin: 0 !important;
      border-radius: 0 !important;
      box-shadow: none !important;
    }
    
    .hoverdiva div a:hover {
      background: #f8f9ff !important;
      color: #667eea !important;
      padding-left: 20px !important;
    }

    .hoverdiva:hover div {
      display: flex;
    }

    .table-list.lr10 {
      min-height: 75vh;
    }

    .layui-layer-setwin .layui-layer-min,
    .layui-layer-ico.layui-layer-max {
      display: none;
    }

    /*tbody tr:nth-last-child(-n+3) .hoverdiva div {*/
    /*	  top: 760px !important;*/
    /*bottom: 30px;*/
    /*}*/
    /*tbody tr:nth-last-child(-n+2) .hoverdiva div {*/
    /*	  top: 800px !important;*/
    /*bottom: 30px;*/
    /*}*/
    /*tbody tr:nth-last-child(-n+1) .hoverdiva div {*/
    /*	  top: 900px !important;*/
    /*bottom: 30px;*/
    /*}*/
    /*tbody tr:nth-child(-n+4) .hoverdiva div {*/
    /*	  top: 30px;*/
    /*	  bottom: auto;*/
    /*}*/
    .xsxxright {
      position: fixed;
      right: 15px;
      bottom: 50px;
    }

    .xsxxright div {
      height: 30px;
      line-height: 30px;
      border: 2px solid #3C8DBC;
      width: 30px;
      background: #C5D9EB;
    }

    .xsxxright div img {
      width: 30px;
    }

    .xsxxright div:last-child img {
      transform: rotate(180deg);
    }

    .xsxxright div:first-child {
      border-radius: 7px 7px 0 0;
    }

    .xsxxright div:last-child {
      border-radius: 0 0 7px 7px;
      margin-top: 5px;
    }

    .xsxxright div:hover {
      border: 2px solid #3C8DBC;
      background: #3C8DBC;
    }

    .xsxxright div:hover img {
      filter: brightness(100);
    }
    .layui-form-pane .layui-form-label{
        width: 165px!important;
    }
    .layui-input-block{
        margin-left: 185px!important;
    }
    .layui-input-block .layui-input {
        height: 38px!important;
    }
    .maw{
        width: 60px!important;
        max-width: 60px!important;
    }
    
    /* 保留原有样式 */
    tbody tr {
      line-height: 30px;
      height: auto;
    }

    .con_a_btn {
      border: 0px solid #0c0
    }

    .con_a_btn a {
      display: inline-block;
      padding: 3px 6px;
      margin-right: 5px;
      background: #6CF;
      color: #fff;
      font-weight: bold;
      text-decoration: none;
      cursor: pointer
    }

    .remarks {
      margin: 10px 0;
    }

    .remarks p {
      line-height: 32px;
    }

    .remarks p a {
      background: #161E22;
      font-size: 12px;
      padding: 4px 6px;
      color: #fff;
      border-radius: 5px;
      margin-left: 20px;
    }

    .table-list.lr10 {
      min-height: 75vh;
      overflow: auto;
    }

    .layui-layer-setwin .layui-layer-min,
    .layui-layer-ico.layui-layer-max {
      display: none;
    }
    
    .xsxxright {
      position: fixed;
      right: 15px;
      bottom: 50px;
      z-index: 999;
    }

    .xsxxright div {
      height: 30px;
      line-height: 30px;
      border: 2px solid #3C8DBC;
      width: 30px;
      background: #C5D9EB;
      transition: all 0.3s ease;
    }

    .xsxxright div img {
      width: 30px;
    }

    .xsxxright div:last-child img {
      transform: rotate(180deg);
    }

    .xsxxright div:first-child {
      border-radius: 7px 7px 0 0;
    }

    .xsxxright div:last-child {
      border-radius: 0 0 7px 7px;
      margin-top: 5px;
    }

    .xsxxright div:hover {
      border: 2px solid #3C8DBC;
      background: #3C8DBC;
      transform: scale(1.1);
    }

    .xsxxright div:hover img {
      filter: brightness(100);
    }
    
    .layui-form-pane .layui-form-label{
        width: 165px!important;
    }
    .exportBtn img {
        top: 6px;
    }
    .layui-select-title input.layui-input.layui-unselect[type="text"]:not(.rightgray input):not(.posttime input):not([readonly][id*="time"]):not([readonly][id*="Time"]):not([readonly][id*="date"]):not([readonly][id*="Date"]) {
        max-height: 38px;
    }
    .shjiandhover {
        position: relative;
    }
    .shjiandhover .shijiandxishi {
        display: none;
        background: #8d8d8d;
        border-radius: 20px;
        white-space: nowrap;
        padding: 3px 12px;
        color: #ffffff;
        position: absolute;
        bottom: 0px;
        left: 95px;
    }
    .shjiandhover:hover .shijiandxishi {
        display: block;
    }
    
    .fuzhilianjie {
        position: relative;
    }
    .fuzhilianjie:hover a {
        display: block!important;
    }
    .fuzhilianjie a {
        display: none!important;
        background: #8d8d8d;
        border-radius: 20px;
        padding: 3px 12px;
        color: #ffffff;
        position: absolute;
        bottom: 0px;
        left: 80px;
    }
  </style>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
  <script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/clipboard.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
</head>

<body>
  <div class="header lr10">

    <div>
        <?php echo $this->headerment(); ?>
    <!-- 发送站内信 -->
    <?php if (isset($tem) && $tem == 'tem') { ?>
      <a href="javascript:;" onclick="openAk('<?php $this->lan('end'); ?>','/member/member/SendMsg')"><?php $this->lan("send"); ?></a>
    <?php } ?>
    </div>
    <!-- 导出会员 -->
    <span class="span_fenge lr5"></span>
    <div>
        <div class="exportBtn">
          <input type="button" value="  <?php $this->lan("export"); ?>" name="excel_submit" style="float: right;
        margin-top: 1px !important;
        margin-right: 5px;
        height: 24px !important;
        background: rgba(0, 0, 0, 0) !important;" onclick="javascript:window.location.href='<?php echo WEB_PATH . '/' . G_ADMIN_DIR; ?>/dingdan/user_outputexcel'">
          <img src="/statics/plugin/style/global/image/daochu.png">
        </div>
        <?php if($level == 2){ ?>
        <a class="exportBtn" style="font-size: 15px;height: 32px;margin-top: 6px;" href="<?php echo WEB_PATH; ?>/channel_admin/login" target="_blank"><?php $this->lan("channel_manag","渠道商管理");?></a>
        <?php }?>
    </div>

    <!--<input type ="button" value=" <?php $this->lan("extension"); ?> " name="" class="exportBtn" style="float: right;margin-top: 5px;margin-right: 5px;" onclick="javascript:window.location.href='jacascript:;'" />-->
    <!--<a href="https://www.yungoudaohang.com/"  target="_blank" class="exportBtn" style="float: right;margin-top: 5px;margin-right: 5px;background:red;margin-top: 6px;-->
    <!--   height: 32px;" ><?php $this->lan("extension"); ?></a>-->
  </div>
  <div class="bk10"></div>
  <!-- 演示站不需要 -->
  <?if($level ==-1){?>
<div class="header-data lr10">
<div class="table_form ">
	<form name="myform" action="#" method="post">
    <div style="display:flex">
        <div class="posttime">
        <input name="posttime1" type="text" id="posttime1" class="input-text" placeholder="<?php $this->lan("start_time"); ?>" readonly="readonly" value="<?php echo !empty($posttime1) ? date("Y-m-d H:i:s", $posttime1) : '' ?>" />
        -
        <input name="posttime2" type="text" id="posttime2" class="input-text" placeholder="<?php $this->lan("end_time"); ?>" readonly="readonly" value="<?php echo !empty($posttime2) ? date("Y-m-d H:i:s", $posttime2) : '' ?>" />
        </div>
        <script type="text/javascript">
        date = new Date();
        Calendar.setup({
            inputField: "posttime1",
            ifFormat: "%Y-%m-%d %H:%M:%S",
            showsTime: true,
            timeFormat: "24"
        });
        Calendar.setup({
            inputField: "posttime2",
            ifFormat: "%Y-%m-%d %H:%M:%S",
            showsTime: true,
            timeFormat: "24"
        });
        </script>
	    <input type="submit" class="button" name="sososubmit" value="<?php $this->lan("search");?>" >
    </div>
	</form>
</div>
</div>
<?}?>
<!--销售员-->
  <?if($level ==1){?>
<div class="header-data lr10">
<div class="table_form ">
	<form name="myform" action="#" method="post">
    <div style="display:flex">
        <select name="level">
            <?php foreach($member_levels??[] as $vl){
              if($vl['groupid'] == $level_id){$sel = 'selected="selected"';}else{$sel = '';}
            echo "<option value='{$vl['groupid']}' {$sel}>{$vl['name']}</option>";
            } ?>
        </select>
	    <input type="submit" class="button" name="sososubmit" value="<?php $this->lan("search");?>" >
    </div>
	</form>
</div>
</div>
<?}?>
  <div class="table-list lr10">
    <!-- 未成功会员 -->
    <?php if (ROUTE_A == 'noregmember') { ?>
      <table width="100%" cellspacing="0">
        <thead>
          <tr>

            <th width="100px" align="center"><?php $this->lan("Member_uid"); ?></th>
            <th width="100px" align="center"><?php $this->lan("name"); ?></th>
            <th width="100px" align="center"><?php $this->lan("Member_id"); ?></th>
            <th width="100px" align="center"><?php $this->lan("Member_head"); ?></th>
            <!--th width="100px" align="center">邮箱</th-->
            <th width="100px" align="center"><?php $this->lan("Member_tel"); ?></th>
            <th width="100px" align="center"><?php $this->lan("superior"); ?></th>
            <th width="100px" align="center"><?php $this->lan("Operation_time"); ?></th>
            <th width="100px" align="center"><?php $this->lan("operation"); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($members as $v) { ?>
            <tr>
              <td align="center"><?php echo $v['uid']; ?></td>
              <td align="center"><?php echo $v['username']; ?></td>
              <td align="center"><?php echo $v['v_id']; ?></td>
              <td align="center"><img src="<?php echo G_UPLOAD_PATH . '/' . $v['img']; ?>" style="width:30px;"></td>
              <!--td align="center"><?php echo $v['email']; ?></td-->
              <td align="center"><?php echo $v['mobile']; ?></td>
              <td align="center">
                <?php
                if ($v['yaoqing']) {
                  echo $v['yaoqing'] . " / " . $v['y_name'];
                } else {
                  echo $this->lgs['no_superior'];;
                }
                ?>
              </td>
              <td align="center"><?php echo date("Y-m-d H:i:s", $v['time']); ?></td>
              <td align="center"><a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/member/modify/<?php echo $v['uid']; ?>"><?php $this->lan("modify"); ?></a>
                <a class="butaoin butaish a_form_noparam" href="<?php echo G_MODULE_PATH; ?>/member/del/<?php echo $v['uid']; ?>"><?php $this->lan("delete"); ?></a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <!-- 未成功会员end -->
    <?php } elseif (ROUTE_A == 'otherregmember') { ?>
      <!-- 其他会员 -->
      <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <th width="100px" align="center"><?php $this->lan("ID"); ?></th>
            <th width="100px" align="center"><?php $this->lan("name"); ?></th>
            <th width="100px" align="center"><?php $this->lan("Current_avatar"); ?></th>
            <th width="100px" align="center"><?php $this->lan("Registration_time"); ?></th>
            <th width="100px" align="center"><?php $this->lan("Registration_source"); ?></th>
            <th width="100px" align="center"><?php $this->lan("operation"); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($members as $v) { ?>
            <tr>
              <td align="center"><?php echo $v['uid']; ?></td>
              <td align="center"><?php echo $v['username']; ?></td>
              <td align="center"><img src="<?php echo G_UPLOAD_PATH . '/' . $v['img']; ?>" style="width:30px;"></td>
              <td align="center"><?php echo date("Y-m-d H:i:s", $v['time']); ?></td>
              <td align="center"><?php if (substr($v['username'], 0, 6) != 'weixin') {
                                    echo '<font style="color:red">快捷登录注册</font>';
                                  } else {
                                    echo '关注公众号注册';
                                  } ?></td>
              <td align="center">
                <a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/member/modify/<?php echo $v['uid']; ?>"><?php $this->lan("modify"); ?></a>
                <a class="butaoin butaish a_form_noparam" href="<?php echo G_MODULE_PATH; ?>/member/del/<?php echo $v['uid']; ?>" ><?php $this->lan("delete"); ?></a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <!-- 通用会员列表 -->
      <!-- <div class="bk10"></div> -->
      <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <th width="5%" align="center"><input id="checkAll" type="checkbox"></th>
            <th align="center" width="5%"><?php $this->lan("Member_head"); ?></br><?php $this->lan("Member_uid"); ?></th>
            <th width="5%" align="center"><?php $this->lan("name"); ?></th>
            <!--<th align="center" width="70"><?php $this->lan("Member_id"); ?></th>-->
            <?if($level==0){?>
            <!-- 普通会员 -->
            <th align="center"><?php $this->lan("Channel");?></th>
            <?}?>
            <!--<th width="5%" align="center"><?php $this->lan("Member_head"); ?></th>-->
            <th width="5%" align="center"><?php $this->lan("Member_tel"); ?></th>
            <th align="center" width="5%"><?php $this->lan("Account_amount"); ?></th>
            <th align="center" width="5%"><?php $this->lan("Account_points"); ?></th>
            <th align="center" width="5%"><?php $this->lan("Commission_balance"); ?></th>
            <th align="center" width="5%"><?php $this->lan("superior"); ?></th>
            <?php if ($level == 2) { ?>
              <th width="5%" align="center"><?php $this->lan("invitation_link"); ?></th>
              <th width="5%"><?php $this->lan('invite_recharge_total'); ?></th>
              <!--<th width="100"><?php $this->lan('invite_cashout_total'); ?></th>-->
              <th width="5%"><?php $this->lan('invite_recharge_days_7'); ?></th>
              <!--<th width="120"><?php $this->lan('invite_cashout_days_7'); ?></th>-->
            <?php } else { ?>
              <th align="center"><?php $this->lan("time"); ?></th>
            <?php } ?>
            <th width="5%" align="center"><?php $this->lan("member_level","会员等级");?></th>
            <th width="5%" align="center"><?php $this->lan("online"); ?></th>
            <th  width="5%"  align="center"><?php $this->lan("Online_duration"); ?></th>
            <th width="25%" align="center"><?php $this->lan("operation"); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($members as $v) { ?>
            <tr class="mententlist">
              <td align="center"><input class="checkOne" type="checkbox" data-id="<?php echo $v['uid']; ?>"></td>
              <td align="center"><img src="<?php echo $v['img']; ?>" style="width:30px;"><?php echo $v['uid']; ?></td>
              <td align="center">
                <?php
                if ($v['userStatus'] > 0) {
                  echo '<img src="../../../../statics/uploads/imgs/black.png" style="width: 15px;height: 15px; margin-bottom: 3px" alt="">';
                }
                ?>
                <?php echo $v['username']; ?><br /><?php echo $v['v_id']; ?></td>
              <!--<td align="center"><?php echo $v['v_id']; ?></td>-->
              <?if($level==0){?>
              <!-- 普通会员 -->
              <td align="center"><?php echo $v['ym_username']; ?></td>
              <?}?>
              <!--<td align="center">-->
              <!--  <img src="<?php echo $v['img']; ?>" style="width:30px;">-->
              <!--</td>-->
              <td align="center"><?php echo $v['mobile']; ?></td>
              <td align="center"><?php echo $v['money']; ?></td>
              <td align="center"><?php echo $v['score']; ?></td>
              <td align="center"><?php echo $v['other_money']; ?></td>
              <td align="center">
                <?php
                if ($v['yaoqing']) {
                  echo $v['yaoqing'] . " / " . $v['y_name'];
                } else {
                  echo $this->lgs['no_superior'];;
                }
                ?>
              </td>
              <?php if ($level == 2) { ?>
                <td align="center" class="fuzhilianjie" onclick="fuzhiurl('<?php echo G_WEB_PATH; ?>/index/pages/users/register/index?code=<?php echo $v['uid'] . "&" . $v['channel_yaoqing']; ?>')">
                    <svg t="1772437690737" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5666" width="32" height="32"><path d="M752.535797 273.701662l-2.230808-2.227738c-51.299363-51.300386-135.227868-51.300386-186.526207 0l-118.846782 118.883621c-51.299363 51.281967-51.299363 135.222751 0 186.544627l2.192945 2.156106c4.27742 4.267187 8.833179 8.116865 13.485129 11.728112l43.49563-43.544749c-5.086855-2.956332-9.885138-6.591115-14.223956-10.92891l-2.192945-2.156106c-27.855418-27.866674-27.855418-73.180719 0-101.048417L606.609263 314.26859c27.782763-27.855418 73.084529-27.855418 100.951203 0l2.218528 2.180666c27.855418 27.867698 27.855418 73.194022 0 101.012601l-53.736878 53.765531c9.304923 23.067368 13.740956 47.64002 13.304004 72.114434l83.152838-83.117023C803.83516 408.918273 803.83516 325.004095 752.535797 273.701662L752.535797 273.701662 752.535797 273.701662 752.535797 273.701662zM576.877101 444.959118c-4.266164-4.264117-8.820899-8.118911-13.521968-11.680017l-43.472094 43.496653c5.088902 3.00545 9.888208 6.615675 14.249539 10.952446l2.215458 2.227738c27.855418 27.820626 27.855418 73.135694 0 101.002368L417.465438 709.790762c-27.854395 27.821649-73.15616 27.821649-101.010555 0l-2.229784-2.204202c-27.854395-27.864628-27.854395-73.204256 0-100.999298l53.771671-53.745065c-9.317203-23.070438-13.763468-47.665603-13.340843-72.140017l-83.176374 83.068927c-51.312666 51.349505-51.312666 135.288243 0 186.563046l2.216481 2.228761c51.299363 51.251268 135.227868 51.251268 186.526207 0l118.835526-118.883621c51.250244-51.299363 51.250244-135.263683 0-186.513928L576.877101 444.959118 576.877101 444.959118 576.877101 444.959118 576.877101 444.959118zM576.877101 444.959118" fill="#040000" p-id="5667"></path></svg>
                    <a href="<?php echo G_WEB_PATH; ?>/index/pages/users/register/index?code=<?php echo $v['uid'] . "&" . $v['channel_yaoqing']; ?>" target="_blank">
                    <?php echo G_WEB_PATH; ?>/index/pages/users/register/index?code=<?php echo $v['uid'] . "&" . $v['channel_yaoqing']; ?>
                  </a>
                  <!-- <a class="yaoqing_set" href="<?php echo G_MODULE_PATH; ?>/member/channel_yaoqing_set/<?php echo $v['uid']; ?>"><?php $this->lan("modify"); ?></a> -->
                </td>
                <td align="center"><?= $v['rechange_total'] ?><br /> <?= $v['cashout_total'] ?></td>
                <!--<td align="center"><?= $v['cashout_total'] ?></td>-->
                <td align="center"><?= $v['rechange_days_7'] ?><br /><?= $v['cashout_days_7'] ?></td>
                <!--<td align="center"><?= $v['cashout_days_7'] ?></td>-->
              <?php } else { ?>
                <td align="center" style="padding-left: 20px;cursor:pointer" class="shjiandhover" onclick="userLoginLog(<?php echo $v['uid']; ?>,'<?php echo $v['username']; ?>','<?php echo $v['ip']; ?>')">
                  <?php echo date("Y/m/d H:i:s", $v['time']); ?>
                  <div class="shijiandxishi"><?php if (!empty($v['login_time'])) echo date("Y/m/d H:i:s", $v['login_time']); ?></div>
                </td>
              <?php } ?>
              <td align="center"><?php echo $level_text[$v['groupid']]; ?></td>
              <td align="center">
                <?php if ($v['online'] == '是') {
                  echo '<image  src="../../statics/uploads/imgs/zaixian.png" width="20" height="20">';
                } else {
                  echo '<image  src="../../statics/uploads/imgs/lixian.png" width="20" height="20">';
                }
                ?>
              </td>
              <td align="center"><?php echo $v['online_time']; ?></td>
              <td align="center"><?php if ($table == '@#_member_del'): ?>
                  <div class="hoverdiva butaoin" style="display: math;"><?php $this->lan("more"); ?>
                  
                  
                  <a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/member/huifu/<?php echo $v['uid']; ?>"><?php $this->lan("recover"); ?></a>
                  <a class="butaoin butaish a_form_noparam" href="<?php echo G_MODULE_PATH; ?>/member/del_true/<?php echo $v['uid']; ?>" ><?php $this->lan("delete"); ?></a>
                <?php else: ?>
                  <?php if ($this->dg) { ?>
                    <a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/member/daiguan/<?php echo $v['uid']; ?>" target="_blank"><?php $this->lan("Custody"); ?></a>
                  <?php } ?>
                  <!--href="<?php echo G_MODULE_PATH; ?>/member/zhmx/<?php echo $v['uid']; ?>"-->
                  <a class="butaoin edit_user_monery buttons<?php echo $v['uid']; ?>" uid="<?php echo $v['uid']; ?>" mobile="<?php echo $v['mobile']; ?>" usermoney="<?php echo $v['money']; ?>"><?php $this->lan("Recharge"); ?></a>
                  <a class="butaoin butaish a_form_noparam" href="<?php echo G_MODULE_PATH; ?>/member/del/<?php echo $v['uid']; ?>" ><?php $this->lan("delete"); ?></a>
                  <div class="hoverdiva butaoin" style="z-index:0;"><?php $this->lan("more"); ?>
                    <div>
                      <a href="<?php echo G_MODULE_PATH; ?>/member/modify/<?php echo $v['uid']; ?>"><?php $this->lan("modify"); ?></a>
                      <?php if($level == 1){ ?>
                        <!-- 回收商 -->
                      <a class="yaoqing_set" href="<?php echo G_MODULE_PATH; ?>/member/recover_commission_set/<?php echo $v['uid']; ?>"><?php $this->lan("modify_commission","修改佣金设置"); ?></a>
                      <?php } ?>
                      <?php if($level == 2){ ?>
                        <!-- 渠道商 -->
                      <a class="yaoqing_set" href="<?php echo G_MODULE_PATH; ?>/member/channel_yaoqing_set/<?php echo $v['uid']; ?>"><?php $this->lan("modify_invitation_link","修改邀请链接"); ?></a>
                      <?php } ?>
                      <a href="javascript:;" class="mycode" data-id="<?php echo $v['uid']; ?>"><?php $this->lan("Invitation_code"); ?></a>
                      <a href="<?php echo G_MODULE_PATH; ?>/member/yaoqing/<?php echo $v['uid']; ?>"><?php $this->lan("View_subordinates"); ?></a>
                      <a href="javascript:;" onclick="channel_change(<?php echo $v['uid'] ?>,<?php echo $v['yaoqing'] ?>)">
                          <?php $this->lan("Change_yaoqing"); ?></a>
                      <a href="<?php echo G_MODULE_PATH; ?>/record/get_money_details/<?php echo $v['uid']; ?>" data-id="<?php echo $v['uid']; ?>"><?php $this->lan("title_account_detail"); ?></a>
                      <a href="<?php echo G_MODULE_PATH; ?>/record/scoreRecord/<?php echo $v['uid']; ?>"><?php $this->lan("Source_points"); ?></a>
                      <a href="<?php echo G_MODULE_PATH; ?>/record/commissionRecord/<?php echo $v['uid']; ?>"><?php $this->lan("Commission_source"); ?></a>
                      <a href="javascript:;" data-id="<?php echo $v['mobilenoss']; ?>" class="banunblock"><?php $this->lan("Ban_unban"); ?></a>
                      <a href="javascript:;" data-name="<?php echo $v['username']; ?>" data-id="<?php echo $v['mobilenoss']; ?>" data-uid="<?php echo $v['uid']; ?>" class="blackuserid"><?php $this->lan("block"); ?></a>
                    </div>
                  </div>
                  <!--<a href="javascript:;" onClick="userListOpen('<?php echo $v["username"]; ?>聊天记录','<?php echo G_MODULE_PATH; ?>/member/cartindex/<?php echo $v['uid']; ?>')">聊天记录</a><span class="span_fenge lr5">|</span>-->
                <?php endif; ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

      <div class="btn_paixu" style="padding: 6px 12px 0px 0px;margin-bottom: 5px;display: flex;justify-content: space-between;align-items: center;height: 45px;"><input type="button" value="<?php $this->lan("Batch_deletion"); ?> " class="batchDel button" style="margin-bottom: 10px;">
          <?php if ($where != 'recoverlist') { ?>
            <div id="pages">
              <ul>
                <li> <?php $this->lan("common"); ?> <?php echo $total; ?> <?php $this->lan("strip"); ?></li><?php echo $page->show('one', 'li'); ?>
              </ul>
            </div>
          <?php } ?>
        </div>
    <?php } ?>
  </div>

  <div class="" id="window" style="margin:30px;display:none;">
    <form class="layui-form layui-form-pane" action="#" id="operation" method="post">
      <div class="layui-form-item">
        <label class="layui-form-label"><?php $this->lan("Cell"); ?></label>
        <div class="layui-input-block">
          <input type="tel" name="phone" lay-verify="phone" autocomplete="off" disabled="disabled" class="layui-input" value="" readonly>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label"><?php $this->lan("Account_amount"); ?></label>
        <div class="layui-input-block">
          <input type="text" name="money" lay-verify="money" autocomplete="off" disabled="disabled" class="layui-input" style="height: 38px!important;" value="" readonly>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">
          <spna style="color:#3C8DBC"><?php $this->lan("Recharge"); ?></spna>/<spna style="color:#ef2916"><?php $this->lan("Impairment", "扣款"); ?></spna>
        </label>
        <div class="layui-input-block">
          <input type="checkbox" id="type" name="type" value="1" class="is_state sta" lay-value="1|2" checked lay-skin="switch" lay-filter="type" lay-text="<?php $this->lan("Recharge"); ?>|<?php $this->lan("Impairment", "扣款"); ?>">
          <input type="hidden" name="interest" id="interest" value="1">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label"><span class="desc"></span><?php $this->lan("notes", "备注"); ?></label>
        <div class="layui-input-block">
          <select name="message" lay-filter="aihao" id='message'>
            <option value="充值返利"><?php $this->lan("Recharge_rebate"); ?></option>
            <option value="无货兑换"><?php $this->lan("goods_exchange"); ?></option>
            <option value="活动补偿"><?php $this->lan("Activity_compensation"); ?></option>
            <option value="系统扣款"><?php $this->lan("System_deduction"); ?></option>
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label"><span class="desc"></span><?php $this->lan("Amount"); ?></label>
        <div class="layui-input-block">
          <input type="number" name="addmoney" id="money" style="height: 38px!important;" lay-verify="title" autocomplete="off" maxlength="11" placeholder="<?php $this->lan("Amount"); ?>" class="layui-input" required onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}">
        </div>
        <p class="ChineseText" style="margin-left: 120px;"></p>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label"><?php $this->lan("Security_password"); ?></label>
        <div class="layui-input-block">
          <input type="password" name="secure_password" style="height: 38px!important;" placeholder="<?php $this->lan("Security_password"); ?>" class="layui-input" required>
        </div>
      </div>
    </form>
  </div>
  <div class="channel_change" id="channel_change" style="display:none;">
    <select id="channel_sel" style="width: 80%;margin-left: 40px!important;
    margin-top: 10px!important;">

    </select>
  </div>
</body>
<!--table-list end-->
<script>
 function fuzhiurl(textToCopy) {
    // 使用 navigator.clipboard.writeText() 复制文本
    navigator.clipboard.writeText(textToCopy).then(function() {
        console.log('文本已成功复制到剪贴板');
        layer.msg(textToCopy)
    }).catch(function(error) {
        console.error('复制文本到剪贴板失败:', error);
    });

 }
  function channel_init(uid) {
    $.post("/member/channel/channel_lists", {}, function(data) {
      var data = JSON.parse(data);
      data = data.data
      var html = '<option value="">暂无渠道商</option>';
      for (var i in data) {
        html += '<option value="' + data[i].uid + '">' + data[i].uid + '/' + data[i].username + '</option>';
      }
      $('.channel_change select').html(html);
    })
  }
  channel_init();

  function channel_change(uid, yaoqing) {
    $("#channel_sel option").each(function() {
      console.log($(this))
      if ($(this).val() == yaoqing) {
        $(this).prop("selected", true)
      } else {
        $(this).prop("selected", false)
      }
    })
    var desc = '切换渠道商';
    var op = layer.open({
      type: 1,
      title: desc,
      area: ['400px', '170px'],
      btn: ['<?php $this->lan("Confirm", "确认"); ?>', '<?php $this->lan("Cancel", "取消") ?>'],
      btnAlign: 'c',
      content: $("#channel_change"),
      yes: function(index, layero) {
        var yaoqing = $('#channel_sel').val();
        console.log(yaoqing)
        $.post("/member/channel/channel_change", {
          "uid": uid,
          "yaoqing": yaoqing,
          "dosubmit": "1"
        }, function(data) {
          var data = JSON.parse(data);
          if (data.code == 200) {
            layer.close(op)
            layer.msg(data.msg)
            setTimeout(function() {
              window.location.reload();
            }, 1000)
          } else {
            layer.msg(data.msg)
          }
        })
      }
    })
  }

  $('.xsxxright div:first-child').click(function() {
    window.scrollTo({
      top: 0,
      left: 0,
      behavior: 'smooth'
    });
  })
  $('.xsxxright div:last-child').click(function() {
    const totalHeight = document.body.scrollHeight;
    window.scrollTo({
      top: totalHeight,
      left: 0,
      behavior: 'smooth'
    });
  })
  $(function() {
    $(".edit_user_monery").click(function() {
      var uid = $(this).attr('uid'),
        mobile = $(this).attr('mobile'),
        usermoney = $(this).attr('usermoney');
      $("input[name=phone]").val(mobile);
      $("input[name=money]").val(usermoney);
      var desc = '';
      layui.use(['form', 'jquery'], function() {
        var form = layui.form,
          $ = layui.jquery;
        form.render('checkbox');
        desc = $("input[name=type]").next().find("em").html()
        $(".layui-layer-title").html(desc);
        $(".desc").html(desc);

        form.on('switch(type)', function(data) {
          desc = $(data.elem).next().find("em").html()
          $(".layui-layer-title").html(desc);
          $(".layui-layer-btn0").html(desc );
          $(".desc").html(desc);
          if (data.elem.checked == true) {
            $('#interest').val('1');
          } else if (data.elem.checked == false) {
            $('#interest').val('2');
          }
        });
      })
      layer.open({
        type: 1,
        title: desc,
        area: ['600px', '470px'],
        btn: [desc ],
        btnAlign: 'c',
        content: $("#window"),
        yes: function(index, layero) {
          var money = $("input[name=addmoney]").val();
          // var type= $("#interest").find("option:selected").attr("value");
          var type = $("input[name=interest]").val();
          var message = $("select[name='message']").val();
          var secure_password = $("input[name=secure_password]").val();
          $.getJSON("<?php echo WEB_PATH . "/member/member/upmoney"; ?>", {
            money: money,
            type: type,
            uid: uid,
            message: message,
            secure_password: secure_password
          }, function(res) {
            //根据后台返回的参数，来进行判断
            var data = res.code
            if (data == -1) {
              layer.msg('线下充值未开启');
            } else if (data == 1) {
              layer.msg('金额不能为0');
            } else if (data == 2) {
              layer.msg('输入金额大于用户余额');
            } else if (data == 4) {
              layer.msg('提交失败');
            } else if (data == 5) {
              layer.msg('安全密码错误');
            } else {
              layer.msg('提交成功，等待审批')
              layer.close(index); //关闭弹出层
              $(".usermoneys" + uid).html(data + '.00');
              $(".buttons" + uid).attr('usermoney', data + '.00');
              $("#operation")[0].reset() //重置fo
            }
          });
        },
        end: function() {
          $("#operation")[0].reset();
        }
      });
    })
  });
  var copyBtn = new ClipboardJS('.ecopy', {
    text: function(e) {
      return e.previousSibling.innerHTML;
      console.log(e.previousSibling.innerHTML);
    }
  });

  copyBtn.on("success", function(e) {
    alert("复制成功");
    e.clearSelection();

  });
  copyBtn.on("error", function(e) {
    alert("复制失败");
  });

  //会员列表删除
  $("#checkAll").on('change', function() {
    if ($(this).is(":checked")) { // 全选
      $(".checkOne").prop("checked", true);
    } else { // 反选
      $(".checkOne").prop("checked", false);
    }
  });

  $('.batchDel').click(function() {
    //获取所有选中的CheckBox的id
    var ids = "";
    $(".checkOne").each(function(i) {
      if ($(this).prop('checked')) {
        var id = $(this).attr("data-id");
        ids += +id + ',';
      }
      // ajax批量删除
    });
    $.ajax({
      type: 'POST',
      url: '/member/member/del_pl',
      data: {
        ids: ids
      },
      dataType: 'json',
      success: function(data) {
        if (data == 0) {
          alert("删除成功!");
          window.history.go(0)
          //$(window).attr('location','<?php //echo G_MODULE_PATH; 
                                        ?>///member/lists');
          // window.location.href='http://www.yungoucms.cn/member/member/lists';
        } else {
          alert("删除失败!");

        }
      }
    });
    ids = "";
  });

  function userListOpen(title, url, width = '80%', height = '80%') {
    var index = layer.open({
      type: 1,
      title: title,
      shadeClose: true,
      shade: false,
      maxmin: true, //开启最大化最小化按钮
      area: [width, height],
      content: url
    });
    layer.full(index);
  }
  //查看二维码
  //删除
  $(".mycode").on("click", function() {
    let id = $(this).data('id');
    var index = ''
    layer.msg('正在加载中，请耐心等待！', {
      time: 1000
    }, function() {
      index = layer.load(1, {
        shade: [0.1, '#fff'] //0.1透明度的白色背景
      });
    });
    $.post('<?php echo WEB_PATH . "/member/member/mycode"; ?>', {
      id: id
    }, function(res) {
      res = JSON.parse(res);
      if (res.code == 1) {
        layer.msg(res.msg, {
          icon: 1,
          time: 1500
        }, function() {
          layer.close(index);
          layer.open({
            type: 1,
            title: '<?php $this->lan("Invitation_code"); ?>',
            skin: 'layui-layer-rim', //加上边框
            area: ['300px', '500px'], //宽高
            content: '<img style="width:100%;height:100%" src="' + res.data + '">'
          });
        });

      } else {
        layer.close(index);
        layer.msg(res.msg, {
          icon: 2,
          time: 1500
        });
      }
    })

  });

  function userLoginLog(id, name, ip) {
    if (ip == '') {
      layer.msg('暂无登录日志');
      return false
    }
    layer.open({
      type: 2,
      title: '【' + name + '】 登录日志',
      shadeClose: true,
      shade: false,
      maxmin: true, //开启最大化最小化按钮
      area: ['893px', '600px'],
      content: '/?member/member/userloginlog/' + id
    });
  }

  function openAk(title, url, width = '893px', height = '600px') {
    layer.open({
      type: 2,
      title: title,
      shadeClose: true,
      shade: false,
      maxmin: true, //开启最大化最小化按钮
      area: [width, height],
      content: url,
      cancel: function() {
        location.reload();
      }
    });
    return layer;
  }
  layui.use(['form', 'layedit', 'laydate'], function() {
    var form = layui.form,
      layer = layui.layer,
      layedit = layui.layedit,
      laydate = layui.laydate;

    $('.blackuserid').on('click', function() {
      var mobile = $(this).attr('data-id')
      var uid = $(this).attr('data-uid')
      var name = $(this).attr('data-name')
      var title = name ? name : uid
      var index = layer.open({
        type: 2,
        title: '【' + title + '】 <?php $this->lan("management"); ?>',
        shadeClose: true,
        shade: false,
        maxmin: true, //开启最大化最小化按钮
        area: ['750px', '260px'],
        content: '/?member/member/blacktype/' + uid
      });
    })
    $('.banunblock').on('click', function() {
      var uid = $(this).attr('data-id')
      var index = layer.open({
        type: 2,
        title: '<?php $this->lan("user_status"); ?>',
        shadeClose: true,
        shade: false,
        maxmin: true, //开启最大化最小化按钮
        area: ['470px', '300px'],
        content: '/?member/member/banunblock/' + uid
      });
    })
  });
  var ishovcli = 1
  $('.hoverdiva').hover(function() {
    if (ishovcli == 1) {
      $(this).css({
        'z-index': 1
      });
      $(this).children().css({
        top: '22px',
        right: '-15px',
        left: 'auto',
        display: 'flex'
      });
    }
  }, function() {

    if (ishovcli == 1) {
      $('.hoverdiva').css({
        'z-index': 0
      });
      $('.hoverdiva>div').hide();
    }

  })
//   $('.mententlist').on('contextmenu', function(event) {
//     ishovcli = 2
//     $('.hoverdiva div').hide()
//     event.preventDefault(); // 阻止默认的右键菜单

//     $(this).find('.hoverdiva div').css({
//       left: event.pageX,
//       top: event.pageY,
//       right: 'auto',
//       display: 'flex'
//     });
//   });

  $(document).click(function() {
    ishovcli = 1
    $('.hoverdiva div').hide()
  });

  // 如果点击自定义菜单，则不隐藏
  // document.getElementByName('custom-menu').addEventListener('click', function(event) {
  //   event.stopPropagation();
  // });

  $(".yaoqing_set").click(function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var ak = openAk("<?php $this->lan("Invitation_settings"); ?>", url, '800px', '300px');
  })
</script>

</html>