<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
  <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
  <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
  <link rel="stylesheet" href="<?php echo G_TEMPLATES_JS; ?>/layui/css/layui.css" type="text/css">
  <script src="<?php echo G_TEMPLATES_JS; ?>/layui/layui.js"></script>

  <style>
    table th {
      border-bottom: 1px solid #eee;
      font-size: 12px;
      font-weight: 100;
      text-align: right;
      width: 200px;
    }

    table td {
      padding-left: 10px;
    }

    input.button {
      display: inline-block
    }

    .operation {
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .operation span {
      margin: 0 10px;
      font-family: PingFang SC;
      font-weight: 400;
      cursor: pointer;
    }

    .operation .chosen {
      font-size: 16px;
      font-weight: 700;
      color: #2267fa;
      border-bottom: 5px solid #2267fa;
      padding-bottom: 5px;
      font-weight: 500;
    }

    .table-list thead th {
      text-align: center;
    }

    .layui-layer-btn1 {
      display: none;
    }

    .layui-layer-btn.layui-layer-btn-c {
      text-align: left;
      margin-left: 190px;
    }
    .posttime {margin-left: 5px;}
    
    .layui-form-label{
        width: 160px!important;
    }
    .layui-form-pane .layui-input-block{
        margin-left: 160px!important;
    }
    #window #money {
        height: 38px!important;
    }
  </style>
</head>

<body>
  <div class="header lr10">
    <?php echo $this->headerment(); ?>
  </div>
  <div class="bk10"></div>
  <div class="header-data lr10">
    <form name="myform" action="#" method="post" style="display: flex; align-items: center;">
      <span style="margin-left: 30px"><?php $this->lan("condition"); ?></span>&nbsp;&nbsp;

      <script type="text/javascript">
        function r_submit() {
          window.location.href = "/member/member/down_reg"
        }
      </script>
      <select name="sousuo" class="soucsuo">
        <option value="id" content="<?php $this->lan("tips1"); ?>"><?php $this->lan("Member_uid"); ?></option>
        <option value="vid" content="<?php $this->lan("tips2"); ?>"><?php $this->lan("Member_id"); ?></option>
        <option value="nickname" content="<?php $this->lan("tips3"); ?>"><?php $this->lan("Member_nick"); ?></option>
        <!--option value="email">会员邮箱</option-->
        <option value="mobile" content="<?php $this->lan("tips4"); ?>"><?php $this->lan("Member_phone"); ?></option>
      </select>
      <input type="text" name="content" placeholder="<?php $this->lan("tips1"); ?>" class="input-text soutsuo" style="max-width: 160px!important;">
      <span style="margin-left: 30px"><?php $this->lan("time"); ?></span>&nbsp;&nbsp;
       <div class="posttime" style="height: 48px!important;"> 
      <input name="posttime1" type="text" id="posttime1" class="input-text" placeholder="<?php $this->lan("Tips8"); ?>" readonly="readonly" value="<?php echo !empty($posttime1) ? date("Y-m-d H:i:s", $posttime1) : '' ?>" style="box-sizing: content-box;text-indent: 5px;" />
      -
      <input name="posttime2" type="text" id="posttime2" class="input-text" placeholder="<?php $this->lan("Tips9"); ?>" readonly="readonly" value="<?php echo !empty($posttime2) ? date("Y-m-d H:i:s", $posttime2) : '' ?>" style="box-sizing: content-box;text-indent: 5px;" />
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
      <input type="submit" class="button" name="sososubmit" value="<?php $this->lan("search"); ?>">
      <input class="button" type="button" value="<?php $this->lan("reset"); ?>" onclick="r_submit()">
      <!--<span style="font-size:12px"><?php $this->lan("tips"); ?></span>-->
      <div data-title="<?php $this->lan("tips"); ?>" style="display:inline-block;width:auto" class="tip">
        <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
      </div>
    </form>
  </div>
  <div class="bk10"></div>
  <div class="table_form lr10 table-list">
    <!--start-->

    <table width="100%" cellspacing="0" style="font-size: 12px;">
      <?php
      if (!empty($members)) {
      ?>
        <thead>
          <tr>
            <th width="10%" align="center"><?php $this->lan("Member_uid"); ?></th>
            <th width="10%" align="center"><?php $this->lan("Member_id"); ?></th>
            <th width="10%" align="center"><?php $this->lan("name"); ?></th>
            <th width="20%" align="center"><?php $this->lan("phone"); ?></th>
            <th width="20%" align="center"><?php $this->lan("Recharge_time"); ?></th>
            <th width="20%" align="center"><?php $this->lan("Recharge_amount"); ?></th>
            <th width="20%" align="center"><?php $this->lan("Yesterday_recharge", "昨日充值"); ?></th>
            <th width="20%" align="center"><?php $this->lan("Before_recharge", "前日充值"); ?></th>
            <th width="20%" align="center"><?php $this->lan("Seven_recharge", "七日内充值"); ?></th>
            <th width="20%" align="center"><?php $this->lan("Custom_recharge", "自定义时间充值"); ?></th>
            <th width="20%" align="center"><?php $this->lan("Account_balance"); ?></th>
            <th width="20%" align="center"><?php $this->lan("Account_points"); ?></th>
            <th width="20%" align="center" colspan="4">
              <div class="operation"><?php $this->lan("operation"); ?>
            </th>
          </tr>
        </thead>
        <?php
        foreach ($members as $v) {
          if (!$v) {
            continue;
          }
        ?>
          <tr>
            <td align="center"><?php echo $v['uid']; ?></td>
            <td align="center"><?php echo $v['v_id']; ?></td>
            <td align="center"><?php echo $v['username']; ?></td>
            <td align="center"><?php echo substr_replace($v['mobile'], '****', 3, 4); ?></td>
            <td align="center"><?php echo $v['time']; ?></td>
            <td align="center"><?php echo $v['day_money']; ?></td>
            <td align="center"><?php echo $v['day_money_1']; ?></td>
            <td align="center"><?php echo $v['day_money_2']; ?></td>
            <td align="center"><?php echo $v['day_money_7']; ?></td>
            <td align="center"><?php echo $v['day_money_diy']; ?></td>
            <td align="center" class='usermoney usermoneys<?php echo $v['uid']; ?>'><?php echo $v['money']; ?></td>
            <td align="center" class='usermoney usermoneys<?php echo $v['uid']; ?>'><?php echo $v['score']; ?></td>
            <td align="center" class="utoiwadiv" colspan="4">
              <!--input type="number" value='' name='money' id='money' cid="<?php echo $v['uid']; ?>" style='width: 109px;height: 28px;'>
					<input type="button" class="button" onclick='upmoney();' value="修改" -->

              <input type="button" style="line-height: normal;height: auto;" class="button edit_user_monery buttons<?php echo $v['uid']; ?> butaoin" value="<?php $this->lan("Recharge"); ?>" uid="<?php echo $v['uid']; ?>" mobile="<?php echo $v['mobile']; ?>" usermoney="<?php echo $v['money']; ?>">
              <!--<span class="span_fenge lr5">|</span>-->
              <!--<a href="javascript:;" onClick="userListOpen('统计','<?php echo G_MODULE_PATH; ?>/member/statistics/<?php echo $v['uid']; ?>')">统计</a><span class="span_fenge lr5">|</span>-->
            </td>
          </tr>
      <?php
        }
      }
      ?>
    </table>

    <table width="100%" cellspacing="0">
      <?php
      if (empty($members)) {
      ?>
        <thead>
          <tr>
            <th width="10%" style="max-width:70px;" align="center"><?php $this->lan("Member_uid"); ?></th>
            <th width="10%" align="center"><?php $this->lan("name"); ?></th>
            <th width="20%" align="center"><?php $this->lan("phone"); ?></th>
            <th width="20%" align="center"><?php $this->lan("Recharge_time"); ?></th>
            <th width="20%" align="center"><?php $this->lan("Recharge_amount"); ?></th>
            <th width="20%" align="center"><?php $this->lan("Account_balance"); ?></th>
            <th width="20%" align="center"><?php $this->lan("Account_points"); ?></th>
            <th width="20%" align="center" colspan="4">
              <div class="operation"><?php $this->lan("operation"); ?>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($members as $v) {
          ?>
            <tr>
              <td align="center"><?php echo $v['uid']; ?></td>
              <td align="center"><?php echo $v['username']; ?></td>
              <td align="center"><?php echo substr_replace($v['mobile'], '****', 3, 4); ?></td>
              <td align="center">
                <?php echo $v['time']; ?>
              </td>
              <td align="center">
                <?php echo $v['day_money']; ?>
              </td>
              <td align="center" class='usermoney usermoneys<?php echo $v['uid']; ?>'><?php echo $v['money']; ?></td>
              <td align="center" class='usermoney usermoneys<?php echo $v['uid']; ?>'><?php echo $v['score']; ?></td>
              <td align="center" colspan="4">
                <!--input type="number" value='' name='money' id='money' cid="<?php echo $v['uid']; ?>" style='width: 109px;height: 28px;'>
					<input type="button" class="button" onclick='upmoney();' value="修改" -->

                <input type="button" style="line-height: normal;height: auto;" 
                  class="button edit_user_monery buttons<?php echo $v['uid']; ?> butaoin" 
                  value="<?php $this->lan("Recharge"); ?>" 
                  uid="<?php echo $v['uid']; ?>" 
                  mobile="<?php echo $v['mobile']; ?>" 
                  usermoney="<?php echo $v['money']; ?>"
                  >
                <!--<span class="span_fenge lr5">|</span>-->
                <!--<a href="javascript:;" onClick="userListOpen('统计','<?php echo G_MODULE_PATH; ?>/member/statistics/<?php echo $v['uid']; ?>')">统计</a><span class="span_fenge lr5">|</span>-->
              </td>
            </tr>
        <?php
          }
        }
        ?>
        <?php if (!$members): ?>
          <tr>
            <td colspan="8" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data"); ?></td>
          </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div id="pages">
      <ul>
        <li><?php $this->lan("common"); ?> <?php echo $total; ?> <?php $this->lan("strip"); ?></li>
        <?php echo $page->show('one', 'li'); ?>
      </ul>
    </div>
  </div><!--table-list end-->
  <div class="" id="window" style="margin:30px;display:none;">
    <form class="layui-form layui-form-pane" action="#" id="operation" method="post">
      <div class="layui-form-item" style="display: flex;">
        <label class="layui-form-label"><?php $this->lan("mobile_phone_no"); ?></label>
        <div class="layui-input-block" style="margin-left: 0 !important;flex: 1;">
          <input type="tel" name="phone" lay-verify="phone" autocomplete="off" disabled="disabled" class="layui-input" value="" readonly style="height: 38px !important;">
        </div>
      </div>
      <div class="layui-form-item" style="display: flex;">
        <label class="layui-form-label"><?php $this->lan("Account_amount"); ?></label>
        <div class="layui-input-block" style="margin-left: 0 !important;flex: 1;">
          <input type="text" name="money" lay-verify="money" autocomplete="off" disabled="disabled" class="layui-input" value="" readonly style="height: 38px !important;">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">
          <spna style="color:#3C8DBC"><?php $this->lan("Recharge"); ?></spna>/<spna style="color:#ef2916"><?php $this->lan("Impairment","扣款"); ?></spna>
        </label>
        <div class="layui-input-block">
          <input type="checkbox" id="type" name="type" value="1" class="is_state sta" lay-value="1|2" checked lay-skin="switch" lay-filter="type" lay-text="<?php $this->lan("Recharge"); ?>|<?php $this->lan("Impairment","扣款"); ?>">
          <input type="hidden" name="interest" id="interest" value="1">
        </div>
      </div>

      <div class="layui-form-item">
      <label class="layui-form-label"><span class="desc"></span><?php $this->lan("notes","备注");?></label>
        <div class="layui-input-block">
          <select name="message" lay-filter="aihao" id='message'>
            <option value="<?php $this->lan("Recharge_rebate"); ?>"><?php $this->lan("Recharge_rebate"); ?></option>
            <option value="<?php $this->lan("goods_exchange"); ?>"><?php $this->lan("goods_exchange"); ?></option>
            <option value="<?php $this->lan("Activity_compensation"); ?>"><?php $this->lan("Activity_compensation"); ?></option>
            <option value="<?php $this->lan("System_deduction"); ?>"><?php $this->lan("System_deduction"); ?></option>
          </select>
        </div>
      </div>
      <div class="layui-form-item" style="display: flex;">
        <label class="layui-form-label"><span class="desc"></span><?php $this->lan("Amount");?></label>
        <div class="layui-input-block" style="margin-left: 0 !important;flex: 1;">
          <input type="number" name="addmoney" value="" id="money" lay-verify="title" autocomplete="off" maxlength="11" placeholder="<?php $this->lan("Amount"); ?>" class="layui-input" required onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}">
        </div>
        <p class="ChineseText" style="margin-left: 10px!important;color:#d2d2d2;margin-top:5px"></p>
      </div>
      <div class="layui-form-item" style="display: flex;">
        <label class="layui-form-label"><?php $this->lan("Security_password"); ?></label>
        <div class="layui-input-block" style="margin-left: 0 !important;flex: 1;">
          <input type="password" name="secure_password" placeholder="<?php $this->lan("Security_password"); ?>" class="layui-input" required style="height: 38px !important;">
        </div>
      </div>
      <!--div class="layui-form-item">
    <div class="layui-input-block">
      <button type="submit" class="layui-btn" lay-submit lay-filter="demo">立即提交</button>
      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
  </div-->
    </form>
  </div>
  <script>
    // 百亿中文
    function Arabia_To_SimplifiedChinese(Num) {
      for (var i = Num.length - 1; i >= 0; i--) {
        Num = Num.replace(",", ""); //替换Num中的“,”
        Num = Num.replace(" ", ""); //替换Num中的空格
      }
      if (isNaN(Num)) {
        //验证输入的字符是否为数字
        //alert("请检查小写金额是否正确");
        return;
      }
      //字符处理完毕后开始转换，采用前后两部分分别转换
      var part = String(Num).split(".");
      var newchar = "";
      //小数点前进行转化
      for (var i = part[0].length - 1; i >= 0; i--) {
        if (part[0].length > 11) {
          //alert("位数过大，无法计算");
          return "";
        } //若数量超过拾亿单位，提示
        var tmpnewchar = "";
        var perchar = part[0].charAt(i);

        switch (perchar) {
          case "0":
            tmpnewchar = "零" + tmpnewchar;
            break;
          case "1":
            tmpnewchar = "一" + tmpnewchar;
            break;
          case "2":
            tmpnewchar = "二" + tmpnewchar;
            break;
          case "3":
            tmpnewchar = "三" + tmpnewchar;
            break;
          case "4":
            tmpnewchar = "四" + tmpnewchar;
            break;
          case "5":
            tmpnewchar = "五" + tmpnewchar;
            break;
          case "6":
            tmpnewchar = "六" + tmpnewchar;
            break;
          case "7":
            tmpnewchar = "七" + tmpnewchar;
            break;
          case "8":
            tmpnewchar = "八" + tmpnewchar;
            break;
          case "9":
            tmpnewchar = "九" + tmpnewchar;
            break;
        }

        switch (part[0].length - i - 1) {
          case 0:
            tmpnewchar = tmpnewchar;
            break;
          case 1:
            if (perchar != 0) tmpnewchar = tmpnewchar + "十";
            break;
          case 2:
            if (perchar != 0) tmpnewchar = tmpnewchar + "百";
            break;
          case 3:
            if (perchar != 0) tmpnewchar = tmpnewchar + "千";
            break;
          case 4:
            tmpnewchar = tmpnewchar + "万";
            break;
          case 5:
            if (perchar != 0) tmpnewchar = tmpnewchar + "十";
            break;
          case 6:
            if (perchar != 0) tmpnewchar = tmpnewchar + "百";
            break;
          case 7:
            if (perchar != 0) tmpnewchar = tmpnewchar + "千";
            break;
          case 8:
            tmpnewchar = tmpnewchar + "亿";
            break;
          case 9:
            if (perchar != 0) tmpnewchar = tmpnewchar + "十";
            break;
          case 10:
            tmpnewchar = tmpnewchar + "百";
            break;
        }
        newchar = tmpnewchar + newchar;
      }
      //替换所有无用汉字，直到没有此类无用的数字为止
      while (
        newchar.search("零零") != -1 ||
        newchar.search("零亿") != -1 ||
        newchar.search("亿万") != -1 ||
        newchar.search("零万") != -1
      ) {
        newchar = newchar.replace("零亿", "亿");
        newchar = newchar.replace("亿万", "亿");
        newchar = newchar.replace("零万", "万");
        newchar = newchar.replace("零零", "零");
      }
      //替换以“一十”开头的，为“十”
      if (newchar.indexOf("一十") == 0) {
        newchar = newchar.substr(1);
      }
      //替换以“零”结尾的，为“”
      if (newchar.lastIndexOf("零") == newchar.length - 1) {
        newchar = newchar.substr(0, newchar.length - 1);
      }
      return newchar;
    }
    $("#money").on("change", function() {
      var moneyVal = $("#money").val();
      if (moneyVal === "") {
        $(".ChineseText").val("")
      } else if (moneyVal > 10000000000) {
        $("#money").val(10000000000)
      } else {
        $(".ChineseText").text(Arabia_To_SimplifiedChinese(moneyVal))
      }
    })
    var desc = '';
    layui.use(['form','jquery'],function () {
            var form = layui.form,
                $ = layui.jquery;
            form.render('checkbox');
            desc = $("input[name=type]").next().find("em").html()
            $(".layui-layer-title").html(desc);
            $(".desc").html(desc);

            form.on('switch(type)',function (data) {
              desc = $(data.elem).next().find("em").html()
              $(".layui-layer-title").html(desc);
              $(".layui-layer-btn0").html(desc+'<?php $this->lan("Confirm","确认");?>');
              $(".desc").html(desc);
                if (data.elem.checked == true) {
                    $('#interest').val('1');
                }else if(data.elem.checked == false){
                    $('#interest').val('2');
                }
            });
    })
   /*  layui.use('form', function() {
      $ = layui.jquery,
        form = layui.form;
      //监听提交
      form.on('submit(demo)', function(data) {
        return false;
      });
      form.on('switch(interests)', function(data) {
        var isChecked = data.elem.checked; // 获取开关状态，true或false
        var value = isChecked ? '1' : '0'; // 根据开关状态设定value值
        $("input[name=interest]").val(value);
        if (value == '0') {
          $('.layui-layer-btn0').text('确认扣款')
          $('.layui-layer-btn0').text('确认减值')
          $('.layui-layer-btn0').css('border-color', '#ef2916')
          $('.layui-layer-btn0').css('background-color', '#ef2916')
        } else {
          $('.layui-layer-btn0').text('确认充值')
          $('.layui-layer-btn0').css('border-color', '#3C8DBC')
          $('.layui-layer-btn0').css('background-color', '#3C8DBC')
        }
        console.log($("input[name=interest]").val()); // 输出value值
      });
    }); */

    function upImage() {
      return document.getElementById('imgfield').click();
    }
    $(function() {
      $('.operation span').click(function() {
        $(this).addClass('chosen').siblings().removeClass('chosen');
      });
      $(".edit_user_monery").click(function() {
        var uid = $(this).attr('uid'),
          mobile = $(this).attr('mobile'),
          usermoney = $(this).attr('usermoney');
        $("input[name=phone]").val(mobile);
        $("input[name=money]").val(usermoney);
        layer.open({
          type: 1,
          title: "充值", 
          area: ['600px', '500px'],
          btn: ["<?php $this->lan('Confirm_recharge', '确认充值'); ?>"],
          btnAlign: 'c',
          content: $("#window"),
          yes: function(index, layero) {
            var money = $("input[name=addmoney]").val();
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
              // res = JSON.parse(res);
              var data = res.code
              console.log(data == -1)
              //根据后台返回的参数，来进行判断
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
                $(".usermoneys" + uid).html(res.money + '.00');
                $(".buttons" + uid).attr('usermoney', res.money + '.00');
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

    function upmoney() {
      var v = $('#money').val();
      var id = $('#money').attr('cid');
      var com = confirm('确定修改？');
      var money = $('.chosen').attr('id');

      if (com) {
        $.post("<?php echo WEB_PATH . "/member/member/upmoney"; ?>", {
            money: v,
            id: id,
            type: money,
            submit: 'submit',

          },
          function(data) {
            if (data == 1) {
              alert('金额不能为0');
            } else if (data == 2) {
              alert('输入金额大于用户余额');
            } else if (data == 4) {
              alert('修改失败');
            } else {
              $('.usermoney').html(data);
              alert('修改成功');
            }
          });
      }
    }
  </script>
  <script>
    function userListOpen(title, url) {
      var index = layer.open({
        type: 2,
        title: title,
        shadeClose: false,
        shade: false,
        maxmin: false, //开启最大化最小化按钮
        area: ['600px', '400px'],
        content: url,
        end: function() {
          location.reload();
        }
      });
      // layer.full(index);
    }
    $('.soucsuo').change(function() {
      var ptext = $(this).find("option:selected").attr("content")
      $('.soutsuo').attr('placeholder', ptext)
    })
  </script>
</body>

</html>