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
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
  <script src="/statics/templates/mlmuban/js/layui/lay/modules/form.js"></script>
  <style>
    .on {
      color: #fff;
      background: #0c0;
      padding: 3px 6px;
    }

    .off {
      color: #f00;
      background: #eee;
      padding: 3px 6px;
    }

    .msgs {
      float: left;
      overflow: hidden;
      width: 100%;
    }

    table.meg_table {
      border: 10px solid #d5dfe8;
      width: 480px;
      height: 330px;
      float: left;
      display: inline-block;
      padding: 10px;
    }

    table.meg_table:hover {
      background: #f1f1f1
    }

    table.meg_table tr {
      display: block;
    }

    form {
      float: left;
      height: 400px;
      padding: 10px;
    }

    table tr td:nth-of-type(1) {
      text-align: right;
    }

    /*  */

    .rightgray {
      display: flex;
      align-items: center;
    }

    .rightgray div {
      width: 5px;
      background-color: #f5f5f5;
      border: 1px solid #B4B4B4;
      border-left-width: 0px;
      height: 27px;
      padding: 2px 15px;
      border-top-right-radius: 3px;
      border-bottom-right-radius: 3px;
      line-height: 28px;
    }

    .rightgray input {
      margin: 0;
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
      width: 42px;
      min-width: 65px;
    }

    /*  */
    .abtn {
      background-color: #ffffff;
      display: inline-block;
      padding: 0px 20px;
      margin: 0 5px 0 0;
      cursor: pointer;
      overflow: visible;
      text-decoration: none;
      white-space: nowrap;
      color: #444;
      transition: background-color 0.2s ease-out 0s;
      background-clip: padding-box;
      border-radius: 5px;
      height: 34px;
    }
    
    .layui-form .layui-unselect {
        padding-left: 20px;
    }

    .layui-form-switch {
      margin-top: 0;
    }

    #toggleButton {
      position: absolute !important;
      top: 15px !important;
      right: 7px !important;
    }

    #toggleButton2 {
      position: absolute !important;
      top: 15px !important;
      right: 7px !important;
    }

    #toggleButton3 {
      position: absolute !important;
      top: 15px !important;
      right: 7px !important;
    }
    .toggleButton {
      position: absolute !important;
      top: 0px !important;
      right: 7px !important;
    }
  </style>
  </style>
</head>

<body>
  <div class="header lr10">
    <h3 class="nav_icon"> <?php $this->lan("title"); ?></h3><span class="span_fenge lr10"></span>
    <a href="<?php echo G_MODULE_PATH; ?>/setting/mobile"> <?php $this->lan("title"); ?></a>
    <span class="lr10"></span>
    <a href="<?php echo G_MODULE_PATH; ?>/template/mobile_temp"> <?php $this->lan("SMS_template_setting"); ?></a>
    <span class="lr10"></span>
    <a href="javascript:;" onClick="mobile_check();"> <?php $this->lan("Send_test_SMS"); ?></a>
    <span class="lr10"></span>
    <!--    <a href="javascript:;" onclick="openAk('发送短信','/member/member/SendSmS')">发送短信</a>-->
    <!--    <span class="lr10"> | </span>-->
  </div>
  <div class="bk10"></div>
  <div class="lr10" id="div_top_block" style="display:block; width:100px; height:0px;background:#d5dfe8;">

  </div>

  <!--  -->
  <form name="myform" action="#" method="post" style="height: 30px;">

    <table width="100%" cellspacing="0" style="border-style: none;">

      <tr style="display: flex;align-items: center;">
        <td width="80" align="left" style="padding-right: 10px"> <?php $this->lan("Recharge_amount"); ?></td>
        <td class="rightgray" style="max-height: 40px;"><input type="text" style="text-align: center;border: 0 !important;padding: 0!important" name="rcmoney" value="<?php echo $web['rcmoney']; ?>" placeholder="<?php $this->lan("amount"); ?>" class="input-text">
          <div style="align-items: center;display: flex;"><?php echo $symbol; ?></div>
        </td>

        <td width="80" align="right" style="padding-right: 10px"> <?php $this->lan("Recharge_notification"); ?></td>
        <td class="layui-form">
          <input type="checkbox" class="is_state sta" name="rcnotice" value="1" <?php if ($web['rcnotice'] == 1) {
                                                                                  echo 'checked=""';
                                                                                } ?> lay-skin="switch" lay-filter="is_state" lay-text="<?php $this->lan("open"); ?>|<?php $this->lan("close"); ?>">
        </td>
        <td>
          <div class="template" style="display: flex;white-space: nowrap;align-items: center;padding-left: 10px;" <?php if (!$web['rcnotice']) { ?>style="display: none;" <?php } ?>>
            &nbsp;<?php $this->lan("phone"); ?> &nbsp;<input type="text" name="rcmobile1" maxlength="11" value="<?php echo substr_replace($web['rcmobile1'], '****', 3, 4); ?>" placeholder="<?php $this->lan("phone"); ?>" class="input-text" style='width:128px;'>
          </div>
        </td>
        <td>
          <div style="margin-left:10px!important">
            <input type="submit" class="button" name="dosubmit" value="  <?php $this->lan("Submit"); ?> " style="max-height: 40px !important;">
          </div>
        </td>
      </tr>

      <!-- <tr>
<td width="220" align="right" style="padding-right: 10px">充值通知2</td>
<td>
<?php if ($web['rcnotice2']) { ?>

<input type="radio" name="rcnotice2" value="1" checked="checked" />&nbsp;开启&nbsp;&nbsp;

<input type="radio" name="rcnotice2" value="0" />&nbsp;关闭

<?php } else { ?>

<input type="radio" name="rcnotice2" value="1"/>&nbsp;开启&nbsp;&nbsp;

<input type="radio" name="rcnotice2" value="0" checked="checked" />&nbsp;关闭

<?php } ?>

&nbsp;手机号 &nbsp;<input type="text" name="rcmobile2" value="<?php echo substr_replace($web['rcmobile2'], '****', 3, 4); ?>" placeholder="请输入手机号" class="input-text" style='width:128px;'>
</td>
</tr> -->

    </table>

  </form>
  <div class="table_form lr10 msgs">
    <!-- <div style="padding-left: 52px;">短信开户请前往taolong.cc或联系客服QQ:1274117743</div> -->
    <div class="bk10"></div>
    <!--form action="#" method="post" id="myform">
    <table width="100%" class="meg_table">
      <tr>
        <td width="100">
          <b>SMS短信平台</b>
          <em style="color:red;">
      
          </em>
        </td>
        <td key="4" class="mobile_on_off"></td>
      </tr>
      <tr>
        <td width="100">短信接口用户名：</td>
        <td>
          <input type="text" name="mid" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_4']['mid']; ?>">
        </td>

      </tr>
      <tr>
        <td width="100">短信接口密码：</td>
        <td>
          <input type="password" name="mpass" class="input-text wid150"  value="******"/>
        </td>
      </tr>
      <tr>
        <td width="100">优质专用id：</td>
        <td>
          <input type="text" name="productid" class="input-text wid150"  value="<?php echo $mobiles['cfg_mobile_4']['productid']; ?>"/>
        </td>
      </tr>
    <tr>
      <td width="100">短信接口签名：</td> 
      <td><input type="mqianming" name="mqianming" class="input-text wid150"  value="<?php echo $mobiles['cfg_mobile_4']['mqianming']; ?>"></td>
    </tr>
      <tr>
        <td width="100">短信信息：</td>
        <td>
          <span>
            <?php echo $mobiles['cfg_mobile_4']['mobile_text']; ?>
          </span>
        </td>
      </tr>

      <tr>
        <td width="100" height="50">
          <input type="hidden" name="interface" value="4" />
        </td>
        <td>
          <input type="submit" value=" 提交并启用该接口 " name="dosubmit" class="button">
        </td>
      </tr>
      
    </table>
  </form-->

    <form action="#" method="post" id="myform">
      <table width="100%" class="meg_table">
        <tr>
          <td width="100"><b> <?php $this->lan("beijing"); ?></b><em style="color:red;"> <!--[new]--></em></td>
          <td key="1" class="mobile_on_off"></td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_ID"); ?></td>
          <td><input type="text" name="mid" placeholder=" <?php $this->lan("SMS_ID"); ?>" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_1']['mid']; ?>"></td>

        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_name"); ?></td>
          <td><input type="text" name="mname" placeholder=" <?php $this->lan("SMS_name"); ?>" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_1']['mname']; ?>"></td>

        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_password"); ?></td>
          <td style="position:relative"><input type="password" id="mpass" name="mpass" placeholder=" <?php $this->lan("SMS_password"); ?>" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_1']['mpass']; ?>">
            <div id="toggleButton">
              <image src="../../statics/uploads/photo/mimayin.png"  style="margin-top:8px;margin-left:4px" id="i_close" />
              <image src="../../statics/uploads/photo/mimaxian.png"  style="margin-top:8px;margin-left:4px" id="i_open" />
            </div>
          </td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_signature"); ?></td>
          <td><input type="mqianming" name="mqianming" placeholder=" <?php $this->lan("SMS_signature"); ?>" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_1']['mqianming']; ?>"></td>
        </tr>
        <!--<tr>-->
        <!--<td width="100"> <?php $this->lan("Remaining_quantity"); ?></td>-->
        <!--<td>-->
        <!--  <span><?php echo $mobiles['cfg_mobile_1']['mobile_text'] ?: ' <?php $this->lan("unknown");?>'; ?></span>-->
        <!--</td>-->
        <!--<td>-->
        <!--<?php $this->lan("unknown"); ?>-->
        <!--                    <span>--><?php //echo $mobiles['cfg_mobile_8']['mobile_text']; 
                                          ?><!--</span>-->
        <!--</td>-->
        <!--</tr>-->

        <tr>
          <td width="100" height="50"><input type="hidden" name="interface" value="1" /></td>
          <td> <input type="submit" value=" <?php $this->lan("Submit"); ?>" name="dosubmit" class="button" style="transform: translate(195px, -66px)!important;"></td>
         

        </tr>

      </table>
    </form>

    <!--<form action="#" method="post" id="myform">-->
    <!--<table width="100%" class="meg_table">-->
    <!--  <tr>-->
    <!--      <td width="100"><b>享宇短信</b><em style="color:red;"> </em></td> -->
    <!--      <td key="7" class="mobile_on_off"></td>-->
    <!--    </tr> -->
    <!--    <tr>-->
    <!--      <td width="100">短信接口ID</td>-->
    <!--      <td><input type="text" name="mid" placeholder="请输入短信接口ID" class="input-text wid150" value="--><?php //echo $mobiles['cfg_mobile_7']['mid']; 
                                                                                                            ?><!--"></td>-->
    <!---->
    <!--    </tr>	-->
    <!--  -->
    <!--    <tr>-->
    <!--      <td width="100">短信接口密码</td>-->
    <!--      <td><input type="password" name="mpass" placeholder="请输入短信接口密码" class="input-text wid150"  value="******"></td>-->
    <!--    </tr>-->
    <!--      <tr>-->
    <!--      <td width="100">短信接口用户名</td>-->
    <!--      <td><input type="text" name="mname" placeholder="请输入短信接口用户名" class="input-text wid150" value="--><?php //echo $mobiles['cfg_mobile_7']['mname']; 
                                                                                                                ?><!--"></td>-->
    <!---->
    <!--    </tr>-->
    <!--    <tr>-->
    <!--      <td width="100">短信接口签名</td>-->
    <!--      <td><input type="mqianming" name="mqianming" class="input-text wid150" placeholder="请输入短信接口签名" value="--><?php //echo $mobiles['cfg_mobile_7']['mqianming']; 
                                                                                                                        ?><!--"></td>-->
    <!--    </tr>-->
    <!--   <tr>-->
    <!--   <td width="100">短信信息</td>-->
    <!--   <td>-->
    <!--     <span>--><?php //echo $mobiles['cfg_mobile_7']['mobile_text']; 
                      ?><!--</span>-->
    <!--     </td>-->
    <!--     </tr>-->
    <!--     -->
    <!--    <tr>-->
    <!--      <td width="100" height="50"><input type="hidden" name="interface" value="7" /></td> -->
    <!--      <td> <input type="submit" value=" 提交并启用该接口 " name="dosubmit" class="button"></td>-->
    <!--    </tr>-->
    <!--     -->
    <!--    </table> -->
    <!--  </form>-->

    <form action="#" method="post" id="myform">
      <table width="100%" class="meg_table">
        <tr>
          <td width="100"><b> <?php $this->lan("Enterprise_Credit"); ?></b><em style="color:red;"> <!--[new]--></em></td>
          <td key="8" class="mobile_on_off"></td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_ID"); ?></td>
          <td><input type="text" name="mid" placeholder=" <?php $this->lan("SMS_ID"); ?>" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_8']['mid']; ?>"></td>

        </tr>

        <tr>
          <td width="100"> <?php $this->lan("SMS_password"); ?></td>
          <td style="position:relative"><input type="password" id="mpass2" name="mpass" placeholder="<?php $this->lan("SMS_password"); ?> " class="input-text wid150"
              value="<?php echo $mobiles['cfg_mobile_8']['mpass']; ?>">
            <div id="toggleButton2">
              <image src="../../statics/uploads/photo/mimayin.png"  style="margin-top:8px;margin-left:4px" id="i_close2" />
              <image src="../../statics/uploads/photo/mimaxian.png"  style="margin-top:8px;margin-left:4px" id="i_open2" />
            </div>
          </td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_name"); ?></td>
          <td><input type="text" name="mname" placeholder="<?php $this->lan("SMS_name"); ?>" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_8']['mname']; ?>"></td>

        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_signature"); ?></td>
          <td><input type="mqianming" name="mqianming" class="input-text wid150" placeholder="<?php $this->lan("SMS_signature"); ?> " value="<?php echo $mobiles['cfg_mobile_8']['mqianming']; ?>"></td>
        </tr>
        <!--<tr>-->
        <!--<td width="100"> <?php $this->lan("Remaining_quantity"); ?></td>-->
        <!--<td>-->
        <!--<?php $this->lan("unknown"); ?>-->
        <!--                    <span>--><?php //echo $mobiles['cfg_mobile_8']['mobile_text']; 
                                          ?><!--</span>-->
        <!--</td>-->
        <!--</tr>-->

        <tr>
          <td width="100" height="50"><input type="hidden" name="interface" value="8" /></td>
          <td> <input type="submit" value=" <?php $this->lan("Submit"); ?>" name="dosubmit" class="button" style="transform: translate(195px, -66px)!important;"></td>
          <!--<td><a href="https://www.taolong.cc/" class="adds" target="_blank"><input type="button" value="申请开户 " name="dosubmit" class="button"></a></td>-->
        </tr>

      </table>
    </form>

    <form action="#" method="post" id="myform">
      <table width="100%" class="meg_table">
        <tr>
          <td width="100"><b> <?php $this->lan("Enterprise_Credit"); ?></b><em style="color:red;"> <!--[new]--></em></td>
          <td key="9" class="mobile_on_off"></td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_ID"); ?></td>
          <td><input type="text" name="mid" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_9']['mid']; ?>"></td>

        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_name"); ?></td>
          <td><input type="text" name="mname" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_9']['mname']; ?>"></td>

        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_password"); ?></td>
          <td style="position:relative"><input type="password" id="mpass3" name="mpass" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_9']['mpass']; ?>">
            <div id="toggleButton3">
              <image src="../../statics/uploads/photo/mimayin.png"  style="margin-top:8px;margin-left:4px" id="i_close3" />
              <image src="../../statics/uploads/photo/mimaxian.png"  style="margin-top:8px;margin-left:4px" id="i_open3" />
            </div>
          </td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_signature"); ?></td>
          <td><input type="mqianming" name="mqianming" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_9']['mqianming']; ?>"></td>
        </tr>
        <!--<tr>-->
        <!--  <td width="100"> <?php $this->lan("SMS_message"); ?></td>-->
        <!--  <td>-->
        <!--    <span><?php echo $mobiles['cfg_mobile_9']['mobile_text']; ?></span>-->
        <!--  </td>-->
        <!--</tr>-->

        <tr>
          <td width="100" height="50"><input type="hidden" name="interface" value="9" /></td>
          <td> <input type="submit" value=" <?php $this->lan("Submit"); ?>" name="dosubmit" class="button" style="transform: translate(195px, -66px)!important;"></td>
          <!--<td><a href="https://www.taolong.cc/" class="adds" target="_blank"><input type="button" value="申请开户 " name="dosubmit" class="button"></a></td>-->
        </tr>

      </table>
    </form>

    <form action="#" method="post" id="myform">
      <table width="100%" class="meg_table" style="height:420px">
        <tr>
          <td width="100"><b> <?php $this->lan("message"); ?></b><em style="color:red;"> </em></td>
          <td key="5" class="mobile_on_off"></td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("country_code"); ?></td>
          <td>
          <select name="code" id="">
              <?php foreach($languages as $vl){
                  $sel = '';
                  if($vl['area_code'] == $mobiles['cfg_mobile_5']['cid']){
                    $sel = "selected";
                  }
                  echo "<option value='{$vl['area_code']}' $sel >{$vl['area_code']}</option>";
                }
              ?>
            </select>
        </td>

        </tr>
        <tr>
          <td width="100"> <?php $this->lan("International_ID"); ?></td>
          <td><input type="text" name="mid" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_5']['mid']; ?>"></td>

        </tr>
        <tr>
          <td width="100"> <?php $this->lan("International_p"); ?></td>
          <td style="position:relative"><input type="password" name="mpass" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_5']['mpass']; ?>">
            <div class="toggleButton">
              <image src="../../statics/uploads/photo/mimayin.png"  style="margin-top:15px!important;margin-left:4px!important" id="i_close4" />
              <image src="../../statics/uploads/photo/mimaxian.png"  style="margin-top:15px!important;margin-left:4px!important;display:none" id="i_open4" />
            </div>
          </td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("Marketing_ID"); ?></td>
          <td><input type="text" name="mid1" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_5']['mid1']; ?>"></td>

        </tr>
        <tr>
          <td width="100"> <?php $this->lan("Marketing_p"); ?></td>
          <td style="position:relative"><input type="password" name="mpass1" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_5']['mpass1']; ?>">
          <div class="toggleButton">
              <image src="../../statics/uploads/photo/mimayin.png"  style="margin-top:15px!important;margin-left:4px!important" id="i_close4" />
              <image src="../../statics/uploads/photo/mimaxian.png"  style="margin-top:15px!important;margin-left:4px!important;display:none" id="i_open4" />
            </div>
          </td>
        </tr>
        <!--<tr>-->
        <!--  <td width="100"> <?php $this->lan("SMS_message"); ?></td>-->
        <!--  <td>-->
        <!--    <span><?php echo $mobiles['cfg_mobile_5']['mobile_text']; ?></span>-->
        <!--  </td>-->
        <!--</tr>-->

        <tr>
          <td width="100" height="50"><input type="hidden" name="interface" value="5" /></td>
          <td> <input type="submit" value=" <?php $this->lan("Submit"); ?>" name="dosubmit" class="button" style="transform: translate(195px, -66px)!important;"></td>
          <!--<td><a href="https://www.taolong.cc/" class="adds" target="_blank"><input type="button" value="申请开户 " name="dosubmit" class="button"></a></td>-->
        </tr>

      </table>
    </form>
    <form action="#" method="post" id="myform">
      <table width="100%" class="meg_table" style="height:420px">
        <tr>
          <td width="100"><b> <?php $this->lan("sms_fan", "短信帆"); ?></b><em style="color:red;"> </em></td>
          <td key="21" class="mobile_on_off"></td>
        </tr>
        <tr style="display: none;">
          <td width="100"> <?php $this->lan("country_code", "国家编码"); ?></td>
          <td>
            <select name="code" id="">
              <?php foreach($languages as $vl){
                  $sel = '';
                  if($vl['area_code'] == $mobiles['cfg_mobile_21']['code']){
                    $sel = "selected";
                  }
                  echo "<option value='{$vl['area_code']}' $sel >{$vl['area_code']}</option>";
                }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("client_id", "客户端ID"); ?></td>
          <td><input type="text" name="cid" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_21']['cid']; ?>"></td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("api_id", "发信人ID"); ?></td>
          <td><input type="text" name="mid" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_21']['mid']; ?>"></td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("api_key", "API密钥"); ?></td>
          <td style="position:relative;width: 190px;"><input type="password" name="mpass" class="input-text wid150" style="padding-right: 45px !important;" value="<?php echo $mobiles['cfg_mobile_21']['mpass']; ?>">
          <div class="toggleButton">
              <image src="../../statics/uploads/photo/mimayin.png"  style="margin-top:15px!important;margin-left:4px!important" id="i_close4" />
              <image src="../../statics/uploads/photo/mimaxian.png"  style="margin-top:15px!important;margin-left:4px!important;display:none" id="i_open4" />
            </div>
          </td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_signature", "短信接口签名"); ?></td>
          <td><input type="text" name="mqianming" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_21']['mqianming']; ?>"></td>
        </tr>
        <!--<tr>-->
        <!--  <td width="100"> <?php $this->lan("Remaining_quantity", "短信剩余条数"); ?></td>-->
        <!--  <td width="300">-->
        <!--    <span id='show21'></span>-->
        <!--    <button type="button" class="button sms_total" style="float:right" data-key="21"><?php $this->lan("show", "查看"); ?></button>-->
        <!--  </td>-->
        <!--</tr>-->

        <tr>
          <td width="100" height="50"><input type="hidden" name="interface" value="21" /></td>
          <td> <input type="submit" value=" <?php $this->lan("Submit"); ?>" name="dosubmit" class="button" style="transform: translate(195px, -66px)!important;"></td>
          <td><a href="https://www.taolong.cc/" class="adds" target="_blank"><input type="button" value="<?php $this->lan("open_account"); ?> " name="dosubmit" class="button"></a></td>
        </tr>

      </table>
    </form>
    <form action="#" method="post" id="myform" style="display: none;">
      <table width="100%" class="meg_table" style="height:420px">
        <tr>
          <td width="100"><b> <?php $this->lan("OPT", "OPT"); ?></b><em style="color:red;"> </em></td>
          <td key="22" class="mobile_on_off"></td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("country_code", "国家编码"); ?></td>
          <td>
            <select name="code" id="">
              <?php foreach($languages as $vl){
                  $sel = '';
                  if($vl['area_code'] == $mobiles['cfg_mobile_22']['code']){
                    $sel = "selected";
                  }
                  echo "<option value='{$vl['area_code']}' $sel >{$vl['area_code']}</option>";
                }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("client_id", "sid"); ?></td>
          <td><input type="text" name="cid" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_22']['cid']; ?>"></td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("twilio", "twilio号码"); ?></td>
          <td><input type="text" name="mid" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_22']['mid']; ?>"></td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("api_key", "token"); ?></td>
          <td style="position:relative"><input type="password" name="mpass" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_22']['mpass']; ?>">
          <div class="toggleButton">
              <image src="../../statics/uploads/photo/mimayin.png"  style="margin-top:8px;margin-left:4px" id="i_close4" />
              <image src="../../statics/uploads/photo/mimaxian.png"  style="margin-top:8px;margin-left:4px" id="i_open4" />
            </div>
          </td>
        </tr>
        <tr>
          <td width="100"> <?php $this->lan("SMS_signature", "短信接口签名"); ?></td>
          <td><input type="text" name="mqianming" class="input-text wid150" value="<?php echo $mobiles['cfg_mobile_22']['mqianming']; ?>"></td>
        </tr>
        <tr>
          <td width="100" height="50"><input type="hidden" name="interface" value="22" /></td>
          <td> <input type="submit" value=" <?php $this->lan("Submit"); ?>" name="dosubmit" class="button" style="transform: translate(195px, -66px)!important;"></td>
          <td><a href="https://www.taolong.cc/" class="adds" target="_blank"><input type="button" value="<?php $this->lan("open_account"); ?> " name="dosubmit" class="button"></a></td>
        </tr>

      </table>
    </form>
    <?php foreach($mobiles as $k => $vl) { 
      if(!isset($vl['input'])){continue;}
      ?>
      <form action="#" method="post" id="myform" >
      <table width="100%" class="meg_table" style="height:420px">
        <tr>
          <td width="100"><b> <?php $this->lan("OPT", $vl['title']); ?></b><em style="color:red;"> </em></td>
          <td key="<?php echo $vl['no'];?>" class="mobile_on_off"></td>
        </tr>
        <?php foreach($vl['input'] as $v){
          echo "<tr><td width='100'>{$v['name']}</td><td><input type='text' name='{$v['key']}' class='input-text wid150' value='{$vl[$v['key']]}'></td></tr>";
        }?>
        <tr>
          <td width="100"> <?php $this->lan("SMS_signature", "短信接口签名"); ?></td>
          <td><input type="text" name="mqianming" class="input-text wid150" value="<?php echo $vl['mqianming']; ?>"></td>
        </tr>
        <tr>
          <td width="100" height="50"><input type="hidden" name="interface" value="<?echo $vl['no'];?>" /></td>
          <td> <input type="submit" value=" <?php $this->lan("Submit"); ?>" name="dosubmit" class="button"></td>
        </tr>
          </table>
        </form>
      <?}?>

  </div><!--table-list end-->

  <!--支付弹出框-->
  <style>
    #paywindow {
      position: absolute;
      z-index: 999;
      width: 392px;
      height: 375px;
      display: none;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    #paywindow_b {
      width: 392px;
      height: 375px;
      background: #2a8aba;
      filter: alpha(opacity=60);
      opacity: 0.6;
      position: absolute;
      left: 0px;
      top: 0px;
      display: block
    }

    #paywindow_c {
      width: 380px;
      height: 360px;
      background: #fff;
      display: block;
      position: absolute;
      left: 6px;
      top: 6px;
    }

    .p_win_title {
      line-height: 40px;
      height: 40px;
      background: #f8f8f8;
    }

    .p_win_title b {
      float: left
    }

    .p_win_title a {
      float: right;
      padding: 0px 10px;
      color: #f60
    }

    .p_win_content h1 {
      font-size: 25px;
      font-weight: bold;
    }

    .p_win_but,
    .p_win_mes,
    .p_win_ctitle,
    .p_win_text {
      margin: 20px 20px;
    }

    .p_win_ctitle {
      overflow: hidden;
    }

    .p_win_x_b {
      float: left;
      width: 73px;
      height: 68px;
      background-repeat: no-repeat;
    }

    .p_win_x_t {
      font-size: 18px;
      font-weight: bold;
      font-family: "Helvetica Neue", \5FAE\8F6F\96C5\9ED1, Tohoma;
      color: #f00;
      text-align: center
    }

    .p_win_but {
      height: 40px;
      line-height: 40px;
    }

    .p_win_but a {
      padding: 8px 15px;
      background: #f60;
      color: #fff;
      border: 1px solid #f50;
      margin: 0px 15px;
      font-family: "Helvetica Neue", \5FAE\8F6F\96C5\9ED1, Tohoma;
      font-size: 15px;
    }

    .p_win_but a:hover {
      background: #f50
    }

    .p_win_text a {
      font-size: 13px;
      color: #f60
    }

    .pay_window_quit:hover {
      color: #f00;
    }
  </style>
  <div id="paywindow">
    <div id="paywindow_b"></div>
    <div id="paywindow_c">
      <div class="p_win_title p_win_x_t"><span style="text-align: center;margin-left: 60px;"> <?php $this->lan("Send_test_SMS"); ?></span><a href="javascript:void();" class="pay_window_quit">[关闭]</a><b></b></div>
      <div class="p_win_content">
        <div class="p_win_ctitle">
          <li class="p_win_x_t"></li>
        </div>
        <div class="p_win_mes" style="display: flex;">
          <select name="area_code" id="area_code">
            <?php foreach($languages as $vl){
                  $sel = '';
                  if($vl['area_code'] == $mobiles['cfg_mobile_5']['cid']){
                    $sel = "selected";
                  }
                  echo "<option value='{$vl['area_code']}' $sel >{$vl['area_code']}</option>";
                }
              ?>
          </select>
          <input type="text" id="ceshi_haoma" class="input-text" maxlength="11" style="width:210px;" placeholder="<?php $this->lan("tips1"); ?>" />
        </div>
        <div class="p_win_mes">
          <textarea id="ceshi_con" style="width:280px; height:150px;text-indent:10px;line-height: 20px;" placeholder="<?php $this->lan("tips2"); ?>"></textarea><br />
          <input type="button" value="  <?php $this->lan("Submit"); ?>" class="button" id="ceshi_form" style="display:block;margin: 10px auto">
        </div>
      </div>
    </div>
  </div>

  <script>
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
    document.addEventListener('DOMContentLoaded', function() {
      const passwordInput = document.getElementById('mpass2');
      const toggleButton = document.getElementById('toggleButton2');

      let isVisible = false; // 初始状态设为false，表示密码不可见
      document.getElementById("i_open2").style.display = "none";
      toggleButton.addEventListener('click', function() {
        if (isVisible) {
          // 如果密码当前是可见的，则切换为隐藏状态
          passwordInput.type = 'password';
          document.getElementById("i_open2").style.display = "none";
          document.getElementById("i_close2").style.display = "block";
          // toggleButton.textContent = 'Show'; // 更改按钮文本为“Show”
        } else {
          // 如果密码当前是隐藏的，则切换为可见状态
          passwordInput.type = 'text'; // 修改type为text以显示密码
          document.getElementById("i_open2").style.display = "block";
          document.getElementById("i_close2").style.display = "none";
        }
        isVisible = !isVisible; // 切换isVisible的状态
      });

    });
    document.addEventListener('DOMContentLoaded', function() {
      const passwordInput = document.getElementById('mpass3');
      const toggleButton = document.getElementById('toggleButton3');

      let isVisible = false; // 初始状态设为false，表示密码不可见
      document.getElementById("i_open3").style.display = "none";
      toggleButton.addEventListener('click', function() {
        if (isVisible) {
          // 如果密码当前是可见的，则切换为隐藏状态
          passwordInput.type = 'password';
          document.getElementById("i_open3").style.display = "none";
          document.getElementById("i_close3").style.display = "block";
          // toggleButton.textContent = 'Show'; // 更改按钮文本为“Show”
        } else {
          // 如果密码当前是隐藏的，则切换为可见状态
          passwordInput.type = 'text'; // 修改type为text以显示密码
          document.getElementById("i_open3").style.display = "block";
          document.getElementById("i_close3").style.display = "none";
        }
        isVisible = !isVisible; // 切换isVisible的状态
      });

    });

    $(".toggleButton").click(function(){
      var a1 = $(this).children().eq(0)
      var a2 = $(this).children().eq(1)
      var input = $(this).prev()[0];
      if($(a2)[0].style.display=="none"){
        input.type = "text"
        $(a1)[0].style.display = "none"
        $(a2)[0].style.display = "block"
      }else{
        input.type = "password"
        $(a1)[0].style.display = "block"
        $(a2)[0].style.display = "none"
      }
    })
    layui.use(['form', 'jquery'], function() {
      var form = layui.form,
        $ = layui.jquery;

      form.render('checkbox');
      form.on('switch(is_state)', function(data) {
        var zhi = '';
        if (data.elem.checked == true) {
          $('.template').attr('style', '');
          zhi = 1;
        } else if (data.elem.checked == false) {
          $('.template').attr('style', 'display:none');
          zhi = 0;
        }
        $.post('<?php echo WEB_PATH . G_ADMIN_DIR; ?>/setting/rechargeNoticStatus', {
          status: zhi
        }, function(data) {
          data = JSON.parse(data);
          if (data.code == 200) {
            layer.msg('修改成功');
          } else {
            layer.msg('修改失败');
          }
        });
      });
      form.render();

    });

    function openAk(title, url) {
      layer.open({
        type: 2,
        title: title,
        shadeClose: true,
        shade: false,
        maxmin: true, //开启最大化最小化按钮
        area: ['893px', '600px'],
        content: url
      });
    }

    $(function() {
      // var width = ($(window).width()-372)/2;
      // var height = ($(window).height()-442)/2;
      // $("#paywindow").css("left",width);
      // $("#paywindow").css("top",height);
      //
      $(".pay_window_quit").click(function() {
        $("#paywindow").hide();
      });

      var mobile = '<?php echo $mobiles['cfg_mobile_on']; ?>';

      $("td.mobile_on_off").each(function(i) {
        if ($(this).attr("key") == mobile) {
          $(this).html("<span class=\"on\"><?php $this->lan("open");?></span>");
        } else {
          $(this).html("<span class=\"off\"><?php $this->lan("close");?></span>");
        }
      });

    });
  </script>
  <!--支付弹出框-->

  <script type="text/javascript">
    function mobile_check() {
      $("#paywindow").show();
      return true;
    }

    $("#ceshi_form").click(function() {
      $.ajaxSetup({
        async: false
      });
      var area_code = document.getElementById('area_code').value;
      var ceshi_haoma = document.getElementById('ceshi_haoma').value;
      var ceshi_con = document.getElementById('ceshi_con').value;
      if (ceshi_con == '') {
        window.parent.message("内容不能为空!", 8, 2);
        return;
      }
      $.post("<?php echo WEB_PATH . '/' . ROUTE_M; ?>/setting/mobile", {
        "area_code": area_code,
        "ceshi_haoma": ceshi_haoma,
        "ceshi_con": ceshi_con,
        "ceshi_submit": true
      }, function(data) {

        data = jQuery.parseJSON(data);
        if (data[0] == -1) {
          window.parent.message(data[1], 8, 3);
        } else {
          window.parent.message(data[1], 1, 2);
        }
      });
    });
    $(".sms_total").click(function() {
      var key = $(this).attr("data-key")
      $.get("<?php echo G_MODULE_PATH . "/" . ROUTE_C . "/" . "get_sms_balance/"; ?>" + key, function(res) {
        res = JSON.parse(res)
        console.log(res)
        $("#show" + key).html(res.data.total);
      })
    })
  </script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>