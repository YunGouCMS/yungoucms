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
  <script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/common.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
  <link rel="stylesheet" href="<?php echo G_TEMPLATES_JS; ?>/layui/css/layui.css" type="text/css">

  <style>
    tbody tr {
      line-height: 30px;
      height: 30px;
    }

    /* 模态窗口背景 */
    .modal {
      display: none;
      /* 默认隐藏 */
      position: fixed;
      /* 固定定位 */
      z-index: 1;
      /* 置于顶层 */
      left: 0;
      top: 0;
      width: 100%;
      /* 全屏宽度 */
      height: 100%;
      /* 全屏高度 */
      overflow: auto;
      /* 允许滚动 */
      background-color: rgba(0, 0, 0, 0.4);
      /* 半透明黑色 */
    }

    /* 模态窗口内容 */
    .modalcontent {
      background-color: #fefefe;
      margin: 15% auto;
      /* 居中显示 */
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      /* 宽度 */
    }

    /* 关闭按钮 */
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div class="header lr10"> <?php echo $this->headerment(); ?>
    <div class="">
      <input type="button" value="  <?php $this->lan("export", "导出"); ?>" name="excel_submit" style="float: right;transform: translateY(-30px);height: 30px !important;" onclick="javascript:window.location.href='<?php echo WEB_PATH . '/' . G_ADMIN_DIR; ?>/dingdan/rech_export/<?php echo $where; ?>'">
      <img src="/statics/plugin/style/global/image/daochu.png" style="float: right;transform: translate(42px, -26px);width: 20px;">
    </div>
  </div>
  <div class="bk10"></div>
  <div class="header-data lr10">
    <div style="margin-bottom:5px;"><?php $this->lan("total_amount"); ?>&nbsp;&nbsp;<span style="color:red;font-weight:bold;"><?php echo $summoeny['sum_money']; ?></span></div>
    <div style="display:flex">
      <form action="#" method="post" class="posttimeform" style="width: 100%;">
        <select name="day" id="">
          <option value="" selected="selected" disabled="disabled"><?php $this->lan("Search_Days"); ?></option>
          <option value="1" <?php if ($day == '1') echo 'selected'; ?>><?php $this->lan("today"); ?></option>
          <option value="2" <?php if ($day == '2') echo 'selected'; ?>><?php $this->lan("yesterday"); ?></option>
          <option value="3" <?php if ($day == '3') echo 'selected'; ?>><?php $this->lan("before_yesterday"); ?></option>
          <option value="7" <?php if ($day == '7') echo 'selected'; ?>><?php $this->lan("days_7", '7天内'); ?></option>
        </select>
        <div class="posttime" style="height: 48px;">
          <input name="posttime1" type="text" id="posttime1" class="input-text" placeholder="<?php $this->lan("Tips8"); ?>" readonly="readonly" value="<?php echo !empty($posttime1) ? date("Y-m-d H:i:s", $posttime1) : '' ?>" />
          -
          <input name="posttime2" type="text" id="posttime2" class="input-text" placeholder="<?php $this->lan("Tips9"); ?>" readonly="readonly" value="<?php echo !empty($posttime2) ? date("Y-m-d H:i:s", $posttime2) : '' ?>" />
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
        <select name="chongzhi">
          <option value="<?php $this->lan("Recharge_source"); ?>"
            <?php echo ($chongzhi == $this->lgs["Recharge_source"]) ? 'selected' : '';?>><?php $this->lan("Recharge_source"); ?></option>
          <option value="<?php $this->lan("top_up"); ?>"
            <?php echo ($chongzhi == $this->lgs["top_up"]) ? 'selected' : '';?>><?php $this->lan("Online_recharge"); ?></option>
          <option value="<?php $this->lan("Commission_balance"); ?>"
            <?php echo ($chongzhi == $this->lgs["Commission_balance"]) ? 'selected' : '';?>><?php $this->lan("Commission_balance"); ?></option>
        </select>
        <select name="sel_channel" class="soucsuo">
            <option value=""><?php $this->lan("select"); ?></option>
            <?php foreach($channel?:[] as $vl){
            $select = ($sel_channel == 'channel_'.$vl['uid']) ? 'selected' : '';
            $channel = $this->lgs['Channel']?:"Channel";
            echo "<option value='channel_{$vl['uid']}' $select >{$channel}-{$vl['username']}</option>";
          }?>
        </select>
        <select name="yonghu" class="soucsuo">
          <option value="<?php $this->lan("user_type"); ?>" content="<?php $this->lan("Search_content"); ?>"
            <?php
            if (isset($yonghu)) {
              echo ($yonghu == '<?php $this->lan("user_type");?>') ? 'selected' : '';
            }
            ?>><?php $this->lan("user_type"); ?></option>
          <option value="<?php $this->lan("id"); ?>" content="<?php $this->lan("id"); ?>"
            <?php
            if (isset($yonghu)) {
              echo ($yonghu == '<?php $this->lan("id");?>') ? 'selected' : '';
            }
            ?>><?php $this->lan("id"); ?></option>
          <option value="<?php $this->lan("name"); ?>" content="<?php $this->lan("name"); ?>"
            <?php
            if (isset($yonghu)) {
              echo ($yonghu == '<?php $this->lan("name");?>') ? 'selected' : '';
            }
            ?>><?php $this->lan("name"); ?></option>
          <!--option value="用户邮箱" 
			<?php
      if (isset($yonghu)) {
        echo ($yonghu == '<?php $this->lan("user_ems");?>') ? 'selected' : '';
      }
      ?>
			>用户邮箱</option-->
          <option value="<?php $this->lan("phone"); ?>" content="<?php $this->lan("phone"); ?>"
            <?php
            if (isset($yonghu)) {
              echo ($yonghu == '<?php $this->lan("phone");?>') ? 'selected' : '';
            }
            ?>><?php $this->lan("phone"); ?></option>
        </select>
        <input type="text" name="yonghuzhi" placeholder="<?php $this->lan("Search_content"); ?>" class="input-text soutsuo" style="width: 160px!important;" value="<?php echo !empty($yonghuzhi) ? $yonghuzhi : ''; ?>" />
        <input class="button" type="submit" name="sososubmit" value="<?php $this->lan("search"); ?>" style="height: 32px;margin-top: -2px;">
        <input class="button" type="submit" id="replay" value="<?php $this->lan("reset"); ?>" style=" width: auto;min-width: 0;margin: -2px 0 0 3px;">
      </form>
    </div>
  </div>
  <div class="table-list lr10">
    <table width="100%" cellspacing="0" style="font-size: 12px;">
      <thead>
        <tr>
          <th width="100px" align="center"><?php $this->lan("user_name"); ?></th>
          <th width="100px" align="center"><?php $this->lan("Recharge_amount"); ?></th>
          <th width="100px" align="center"><?php $this->lan("Recharge_source"); ?></th>
          <th width="100px" align="center"><?php $this->lan("Merchant_ID"); ?></th>
          <th width="100px" align="center"><?php $this->lan("order_number"); ?></th>
          <th width="100px" align="center"><?php $this->lan("time"); ?></th>
          <th width="100px" align="center"><?php $this->lan("status"); ?></th>
          <th width="100px" align="center"><?php $this->lan("operation"); ?></th>
          <th width="100px" align="center"><?php $this->lan("Approver"); ?></th>
          <th width="100px" align="center"><?php $this->lan("Approval_remarks"); ?></th>
          <th width="100px" align="center"><?php $this->lan("Approval_time"); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $es = ["1" => "<?php $this->lan('record_state1');?>", "2" => "<?php $this->lan('record_state2');?>", "3" => "<?php $this->lan('record_state3');?>"];
        for ($j = 0; $j < count($recharge); $j++) {
        ?>
          <tr>
            <td align="center">
              <a href="<?php echo G_MODULE_PATH; ?>/member/daiguan/<?php echo $recharge[$j]['uid']; ?>" target="_blank"><span style="color:red;font-weight:bold;"><?php echo $recharge[$j]['uid']; ?></span>/
                <?php echo $members[$j]; ?></a>
                <?echo $recharge[$j]['ym_uid']?"({$recharge[$j]['ym_uid']}/{$recharge[$j]['ym_username']})":"";?>
            </td>
            <td align="center"><?php echo $recharge[$j]['money']; ?></td>
            <td align="center"><?php echo $recharge[$j]['content'] == '充值' ? '通过网络充值' : $recharge[$j]['content']; ?></td>
            <td align="center"><?php echo $recharge[$j]['pay_type']; ?></td>
            <td align="center"><?php echo $recharge[$j]['code']; ?></td>
            <td align="center"><?php echo date("Y/m/d H:i:s", $recharge[$j]['time']); ?></td>
            <td align="center"><?php echo $recharge[$j]['rechange_status']; ?></td>
            <td align="center"><?php echo $recharge[$j]['operat_name']; ?></td>
            <td align="center"><?php echo $recharge[$j]['manage_name']; ?></td>
            <td align="center"><?php echo $recharge[$j]['manage_desc']; ?></td>
            <td align="center"><?php echo $recharge[$j]['manage_time']?date("Y/m/d H:i:s", $recharge[$j]['manage_time']):""; ?></td>
          </tr>
        <?php }  ?>
      </tbody>
    </table>
    <div id="pages">
      <ul>
        <li><?php $this->lan("common"); ?> <?php echo $total; ?> <?php $this->lan("strip"); ?></li>
        <?php echo $page->show('one', 'li'); ?>
      </ul>
    </div>
  </div>
  <div id="myModal" class="modal">
    <div class="modalcontent">
      <span class="close">&times;</span>
      <form class="layui-form layui-form-pane" action="#" id="operation" method="post">
        <input type="hidden" name="m-id">
        <div class="layui-form-item">
          <label class="layui-form-label"><?php $this->lan("Select_action"); ?></label>
          <div class="layui-input-block">
            <select name="status" lay-filter="aihao" id='status' style="display: block;">
              <option value="2"><?php $this->lan("adopt"); ?></option>
              <option value="3"><?php $this->lan("Not_passed"); ?></option>
            </select>
          </div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label"><?php $this->lan("remarks"); ?></label>
          <div class="layui-input-block">
            <input type="text" name="desc" placeholder="<?php $this->lan("remarks"); ?>" class="layui-input">
          </div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label"><?php $this->lan("Secure_password"); ?></label>
          <div class="layui-input-block">
            <input type="password" name="secure_password" placeholder="<?php $this->lan("Secure_password"); ?>" class="layui-input" required>
          </div>
        </div>
        <div class="layui-form-item">
          <div class="layui-input-block">
            <button type="button" class="layui-btn" id="m-submit"><?php $this->lan("Submit"); ?></button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script>
    $(function() {
      $("[data-toggle='tooltip']").tooltip();
    });

    $(document).ready(function() {
      $("#replay").click(function() {
        $.get("/member/member/replay1", function(data, status) {
          window.location.href = window.location.href;
        });
      });
    });
    $('.soucsuo').change(function() {
      var ptext = $(this).find("option:selected").attr("content")
      $('.soutsuo').attr('placeholder', ptext)
    })
    // 获取模态窗口元素
    var modal = document.getElementById("myModal");
    // 获取触发模态窗口的按钮元素
    var btn = document.getElementById("myBtn");
    // 获取关闭按钮元素
    var span = document.getElementsByClassName("close")[0];
    // 当用户点击按钮时，显示模态窗口
    btn.onclick = function() {
      var id = $(this).attr("data-id");
      $("input[name=m-id]").val(id);
      modal.style.display = "block";
    }
    $("#m-submit").click(function() {
      var id = $("input[name=m-id]").val();
      var status = $("select[name=status]").val();
      var desc = $("input[name=desc]").val();
      var secure_password = $("input[name=secure_password]").val();
      $.post("<?php echo WEB_PATH . "/member/member/rechargeExamine"; ?>", {
        id: id,
        status: status,
        desc: desc,
        secure_password: secure_password
      }, function(res) {
        res = JSON.parse(res)
        alert(res.msg)
        location.reload();
      })
    })

    // 当用户点击关闭按钮时，关闭模态窗口
    span.onclick = function() {
      modal.style.display = "none";
    }
    // 当用户点击模态窗口之外的区域时，关闭模态窗口
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
  <script>
    let myDate = new Date();
    let today = myDate.toISOString().slice(0, 10);
    // let arr = new Date().toLocaleDateString(); // 2022/2/25
    // let today = new Date().toLocaleDateString().split('/').join('-'); // 2022-2-25
    console.log(today, "当天日期"); // 当天日期 2022-2-25
    let lw = new Date(myDate - 1000 * 60 * 60 * 24 * 15); // 最后一个数字15可改，15天前
    let lastY = lw.getFullYear();
    let lastM = lw.getMonth() + 1;
    let lastD = lw.getDate();
    let startdate =
      lastY +
      "-" +
      (lastM < 10 ? "0" + lastM : lastM) +
      "-" +
      (lastD < 10 ? "0" + lastD : lastD); // 十五天前日期
    console.log("十五天前日期", startdate);
    $("#search_time").val(startdate);
  </script>

</body>

</html>