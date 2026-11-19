<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<style>

tbody tr{ line-height:30px; height:30px;}

</style>
</head>
<body>
<div class="header lr10"> <?php echo $this->headerment();?> </div>
<div class="bk10"></div>
<div class="header-data lr10">
<div style="margin-bottom:5px;"><?php $this->lan("tips6");?>&nbsp;<span style="color:red;font-weight:bold;"><?php echo $summoeny['sum_money'];?></span></div>
<form action="#" method="post" class="posttimeform">
    <?php $this->lan("Time_search");?>&nbsp;&nbsp;
    <div class="posttime" style="height:48px">
        <input name="posttime1" type="text" id="posttime1" class="input-text" placeholder="<?php $this->lan("Tips8");?>" readonly="readonly" value="<?php echo !empty($posttime1)?date("Y-m-d H:i:s",$posttime1):''?>"/>
        -
        <input name="posttime2" type="text" id="posttime2" class="input-text" placeholder="<?php $this->lan("Tips9");?>" readonly="readonly" value="<?php echo !empty($posttime2)?date("Y-m-d H:i:s",$posttime2):''?>"/>
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
        <select name="auto_user">
          <option value="0" <?php echo ($auto_user == 0) ? 'selected' : '';?>><?php $this->lan("user","用户")?></option>
          <option value="1" <?php echo ($auto_user == 1) ? 'selected' : '';?>><?php $this->lan("robot","机器人")?></option>
        </select>
    <select name="yonghu" class="soucsuo">
    <option value="<?php $this->lan("tips5");?>" content="<?php $this->lan("Search_content");?>"

            <?php

                if(isset($yonghu)){

                    echo ($yonghu=='<?php $this->lan("tips5");?>')?'selected':'';

                }

            ?>

            ><?php $this->lan("tips5");?></option>
    <option value="<?php $this->lan("user_id");?>" content="<?php $this->lan("tips2");?>"

            <?php

                if(isset($yonghu)){

                    echo ($yonghu=='<?php $this->lan("user_id");?>')?'selected':'';

                }

            ?>

            ><?php $this->lan("user_id");?></option>
    <option value="<?php $this->lan("user_n");?>" content="<?php $this->lan("tips4");?>"

            <?php

                if(isset($yonghu)){

                    echo ($yonghu=='<?php $this->lan("user_n");?>')?'selected':'';

                }

            ?>

            ><?php $this->lan("user_n");?></option>
    <!--option value="用户邮箱"

            <?php

                if(isset($yonghu)){

                    echo ($yonghu=='<?php $this->lan("user_ems");?>')?'selected':'';

                }

            ?>

            >用户邮箱</option-->
    <option value="<?php $this->lan("phone");?>" content="<?php $this->lan("tips3");?>"

            <?php

                if(isset($yonghu)){

                    echo ($yonghu=='<?php $this->lan("phone");?>')?'selected':'';

                }

            ?>

            ><?php $this->lan("phone");?></option>
    </select>
    <input type="text" name="yonghuzhi" placeholder="<?php $this->lan("Search_content");?>" class="input-text wid100 soutsuo" value="<?php echo !empty($yonghuzhi)?$yonghuzhi:''; ?>"/>
    <input class="button" type="submit" name="sososubmit" value="<?php $this->lan("search");?>">
    <input class="button" type="submit" id="replay" value="<?php $this->lan("reset");?>" style=" width: 70px;min-width: 0;margin: -2px 0 0 3px;">
</form>
</div>
<div class="table-list lr10">
    <!--start-->
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="30px" align="center"><input id="checkAll" type="checkbox"></th>
                <th width="70px" align="center"><?php $this->lan("name");?></th>
                <th width="70px" align="center"><?php $this->lan("Channel");?></th>
                <th width="300px" align="center"><?php $this->lan("goods_name");?></th>
                <th width="60px" align="center"><?php $this->lan("Product_issues");?></th>
                <th width="100px" align="center"><?php $this->lan("Number");?></th>
                <th width="120px" align="center"><?php $this->lan("Consumption_amount");?></th>
                <th width="70px" align="center"><?php $this->lan("Total_price");?></th>
                <th width="110px" align="center"><?php $this->lan("time1");?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            for($j=0;$j<count($pay_list);$j++){
        ?>
        <tr>
            <td align="center"><input class="checkOne" type="checkbox" data-id="<?php echo $pay_list[$j]['id']; ?>"></td>
            <td align="center">
            <a href="<?php echo G_MODULE_PATH; ?>/member/daiguan/<?php echo $pay_list[$j]['uid']; ?>" target="_blank">
                <span style="color:red;font-weight:bold"><?php echo $pay_list[$j]['uid']; ?></span>/<?php  echo $pay_list[$j]['username'];?>
            </a>
            </td>
            <td align="center">
                <?php if($pay_list[$j]['ym_uid'])echo "{$pay_list[$j]['ym_uid']}/{$pay_list[$j]['ym_username']}"?>
            </td>
            <td align="left" style=" padding-left:20px">
            <a href="<?php echo G_MODULE_PATH; ?>/member/daiguan/<?php echo $pay_list[$j]['uid']; ?>"  target="_blank"><?php echo $pay_list[$j]['shopname']; ?></a>
            </td>
            <td align="center"><?php echo $pay_list[$j]['shopqishu']; ?>/<?php echo $pay_list[$j]['maxqishu']; ?></td>
            <td align="center"><?php echo $pay_list[$j]['gonumber']; ?></td>
            <td align="center"><?php $this->lan("integral");?>：<?php echo $pay_list[$j]['point']; ?> + <?php $this->lan("Amount");?>:<?php echo $pay_list[$j]['money']; ?></td>
            <td align="center"><?php echo $pay_list[$j]['moneycount']; ?></td>
            <td align="center"><?php echo date("Y/m/d H:i:s",intval($pay_list[$j]['time'])); ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="btn_paixu" style="padding-left: 10px;"><input type="button" value="<?php $this->lan("Batch_deletion");?> " class="batchDel button" style="margin: 10px 0;"></div>
    <div id="pages">
        <ul>
            <li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li>
            <?php echo $page->show('one','li'); ?>
        </ul>
    </div>
</div>
<!--table-list end-->

<script>
$(document).ready(function(){
$("#replay").click(function(){
    $.get("/member/member/replay",function(data,status){
window.location.href=window.location.href;
    });
});
});
$('.soucsuo').change(function(){
		var ptext = $(this).find("option:selected").attr("content")
		$('.soutsuo').attr('placeholder',ptext)
	})
// 消费记录批量删除
$("#checkAll").on('change', function () {
    if ($(this).is(":checked")) { // 全选
        $(".checkOne").prop("checked",true);
    } else { // 反选
        $(".checkOne").prop("checked",false);
    }
    });

    $('.batchDel').click(function(){
        //获取所有选中的CheckBox的id
    var  ids="";
    $(".checkOne").each(function(i) {
    if($(this).prop('checked')){
    var id= $(this).attr("data-id");
        ids+=+id+',';
    }
    // ajax批量删除
    });
    console.log(ids);
    $.ajax({
            type: 'POST',
            url: '/member/member/xf_del',
            data: {ids: ids},
            dataType: 'json',
            success: function (data) {
                    if (data == 0) {
                        layer.msg("删除成功!");
                        setTimeout(function(){
                            location.reload(false);
                        },1500);
                        // $(window).attr('location','http://bata.yungoucms.cn/admin/content/goods_list');
                    }else {
                        layer.msg("删除失败!");
                    
                    }
                }
            });
        ids="";
    }); 

</script>
</body>
</html>
