<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
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
</head>
<style>
  * {
    font-size: 12px;
    font-weight: normal;
    border-style: none;
  }

  #toggleButton {
    position: absolute !important;
    top: 15px !important;
    right: 7px !important;
  }
  .table_form table.lr10 tbody tr td:first-child {padding-right:10px!important;text-align:right!important;}
</style>

<body>
  <div class="header lr10">
      <?php echo $this->headerment(); ?>
  </div>
  <div class="bk10"></div>
  <div class="table_form lr10">
    <form action="#" method="post" id="myform">
      <table width="100%" class="lr10" style="border-style: none;">
        <tr>
          <td width="150" align="right"><?php $this->lan("Email_sending_mode"); ?></td>
          <td style="display: flex;gap: 10px;">
            <input checkbox="mail_type" value="1" onClick="showsmtp(this)" type="radio" checked style="margin: 0 !important;"> <?php $this->lan("Function_sending"); ?>
          </td>
        </tr>
        <tr>
          <td align="right"><?php $this->lan("mail_server"); ?></td>
          <td width="300"><input type="text" class="input-text" name="server" size="30" value="<?php echo $info['stmp_host']; ?>" /></td>
          <td>
            <div data-title="<?php $this->lan("tips"); ?>" class="tip">
              <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin: 0 !important;">

            </div>
          </td>
        </tr>
        <tr>
          <td align="right"><?php $this->lan("Senders_email"); ?></td>
          <td><input type="text" class="input-text" name="email" size="30" value="<?php echo $info['from']; ?>" /></td>
        </tr>
        <tr>
          <td align="right"><?php $this->lan("Senders_name"); ?></td>
          <td><input type="text" class="input-text wid80" name="name" size="30" value="<?php echo $info['fromName']; ?>"/></td>
        </tr>
        <tr>
          <td align="right"><?php $this->lan("Send_encoding"); ?></td>
          <td><input type="text" class="input-text wid80" name="big" value="<?php echo $info['big']; ?>" disabled/></td>
        </tr>
        <tr>
          <td align="right"><?php $this->lan("Senders_email"); ?></td>
          <td><input type="text" class="input-text" name="user" size="30" value="<?php echo $info['user']; ?>" /></td>
        </tr>
        <tr>
          <td align="right"><?php $this->lan("Authorization_assword"); ?></td>
          <td style="position:relative" width="300"><input type="password" id="mpass" class="input-text" name="pass" size="30" value="<?php echo $info['pass']; ?>" />
            <div id="toggleButton">
              <image src="../../statics/uploads/photo/mimayin.png"  style="margin-top:8px;margin-left:4px" id="i_close" />
              <image src="../../statics/uploads/photo/mimaxian.png"  style="margin-top:8px;margin-left:4px" id="i_open" />
            </div>
          <td>
            <div data-title="<?php $this->lan("tips1"); ?>" class="tip">
              <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin: 0 !important;">

            </div>
          </td>

          </td>
        </tr>

        <tr>
          <td width="100"></td>
          <td style="display:flex;"> <input type="submit" value="<?php $this->lan("Submit"); ?> " name="dosubmit" class="button" style="margin:0!important;"></td>
        </tr>
        <tr>
          <td style="position: relative; top: -15px;" align="right"><?php $this->lan("Test_email"); ?></td>
          <td style="transform: translateY(10px);">
            <input type="text" id="ceshi" class="input-text" size="30" placeholder="<?php $this->lan("tips2"); ?>" />
            <input type="button" value="<?php $this->lan("test"); ?>" style="position: relative; top: -50px; left: 215px;" onClick="sendemail();" class="button">
          </td>
        </tr>
      </table>

    </form>
  </div><!--table-list end-->
  <script>
    function sendemail() {
      var dizhi = document.getElementById('ceshi');
      var email = dizhi.value;
      var xinemail = email.replace('.', "|");
      $.post("<?php echo G_MODULE_PATH; ?>/setting/email/cesi/" + xinemail, function(data) {
        alert(data);
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      const passwordInput = document.getElementById('mpass');
      const toggleButton = document.getElementById('toggleButton');

      let isVisible = false; // 初始状态设为false，表示密码不可见
      document.getElementById("i_open").style.display = "none";
      toggleButton.addEventListener('click', function() {
        if (isVisible) {
          // 如果密码当前是可见的，则切换为隐藏状态
          passwordInput.type = 'password';
          document.getElementById("i_open").style.display = "none";
          document.getElementById("i_close").style.display = "block";
          // toggleButton.textContent = 'Show'; // 更改按钮文本为“Show”
        } else {
          // 如果密码当前是隐藏的，则切换为可见状态
          passwordInput.type = 'text'; // 修改type为text以显示密码
          document.getElementById("i_open").style.display = "block";
          document.getElementById("i_close").style.display = "none";
        }
        isVisible = !isVisible; // 切换isVisible的状态
      });

    });
  </script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>