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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_TEMPLATES_JS; ?>/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_TEMPLATES_JS; ?>/layui/layui.js"></script>
    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
    <style>
        table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:center; width:200px;}
        table td{ padding-left:10px;}
        input.button{ display:inline-block}
        .operation{
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .operation span{
            margin: 0 10px;
            font-family: PingFang SC;
            font-weight: 400;
            cursor: pointer;
        }
        .operation .chosen{
            font-size: 16px;
            font-weight: 700;
            color: #2267fa;
            border-bottom: 5px solid #2267fa;
            padding-bottom: 5px;
            font-weight: 500;
        }
        .table_form thead td {
            padding-top: 8px;
            padding-bottom: 8px;
        }
        .hei10fff {
            height: 0!important;
            background: #fff;
        }
        .hei10fff td {
            padding: 5px 0!important;
        }
        tbody tr {background-color: #fff;}
        #pages {background-color: #fff;margin: 0;padding: 10px 0;}
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
            width: 600px;
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
        .header.lr10 a,.header-data.lr10 {font-size:12px;}
        .button.myBtn {height:auto;padding: 3px 5px;line-height: normal;}
         .layui-form-label{
        width: 145px!important;
    }
    .layui-form-pane .layui-input-block{
        margin-left: 145px!important;
    }
    </style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="header-data lr10">
    <form action="#" method="post" style="display: flex; align-items: center;">
        <?php $this->lan("time_search");?>&nbsp;&nbsp;&nbsp;
        <div class="posttime" style="height:48px;">
            <input name="posttime1" type="text" id="posttime1" placeholder="<?php $this->lan("Tips8");?>" class="input-text" readonly="readonly" value="<?php echo !empty($posttime1)?date("Y-m-d H:i:s",$posttime1):''?>" style="box-sizing: content-box;text-indent: 5px;"/>
        -&nbsp;&nbsp;
        <input name="posttime2" type="text" id="posttime2" placeholder="<?php $this->lan("Tips9");?>" class="input-text"  readonly="readonly" value="<?php echo !empty($posttime2)?date("Y-m-d H:i:s",$posttime2):''?>" style="box-sizing: content-box;text-indent: 5px;"/>
        </div>
        
        <script type="text/javascript">
            date = new Date();
            Calendar.setup({
                inputField     :    "posttime1",
                ifFormat       :    "%Y-%m-%d 0:0:0",
                showsTime      :    true,
                timeFormat     :    "24"
            });
            Calendar.setup({
                inputField     :    "posttime2",
                ifFormat       :    "%Y-%m-%d 0:0:0",
                showsTime      :    true,
                timeFormat     :    "24"
            });
        </script>
        <select name="chongzhi">
            <option value="<?php $this->lan("tips1");?>"
                <?php
                if(isset($chongzhi)){
                    echo ($chongzhi=='<?php $this->lan("tips1");?>')?'selected':'';
                }

                ?>
            ><?php $this->lan("tips1");?></option>
            <option value="2"
                <?php
                if(isset($chongzhi)){
                    echo ($chongzhi=='2')?'selected':'';
                }

                ?>
            ><?php $this->lan("Received");?></option>
            <option value="3"
                <?php
                if(isset($chongzhi)){
                    echo ($chongzhi=='3')?'selected':'';
                }

                ?>
            ><?php $this->lan("Not_received");?></option>
        </select>
        <input class="button" type="submit" name="sososubmit" value="<?php $this->lan("search");?>">
    </form>
</div>
<div class="table_form table-list lr10">
    <!--start-->
    <form name="myform" action="#" method="post">
        <table cellspacing="0" style="width:100%;font-size: 12px;">

            <thead>
            <!--<tr>-->
            <!--	<td width="220" align="right">搜索条件：-->
            <!--		<select name="sousuo">-->
            <!--			<option value="id">会员uid</option>-->
            <!--			<option value="nickname" >会员昵称</option>-->
            <!--			option value="email">会员邮箱</option-->
            <!--			<option value="mobile">会员手机</option>-->
            <!--		</select>-->
            <!--	</td>-->
            <!--	<td width="120"><input type="text" name="content" class="input-text"></td>-->
            <!--	<td>		-->
            <!--		<input type="submit" class="button" name="submit" value="确认搜索" >-->
            <!--    </td>-->
            <!--	<td></td>-->
            <!--	<td></td>-->
            <!--</tr>-->
            <!--<tr class="hei10fff">-->
            <!--	<td></td>-->
            <!--	<td></td>-->
            <!--	<td></td>-->
            <!--	<td></td>-->
            <!--	<td></td>-->
            <!--</tr>-->
            <?php
            if($members['0']){
            ?>
            <tr>
                <th width="70px" style="max-width: 70px;" align="center">ID</th>
                <th align="center"><?php $this->lan("uid_nick");?></th>
                <th align="center"><?php $this->lan("amount");?></th>
                <th align="center"><?php $this->lan("Bank");?></th>
                <th align="center"><?php $this->lan("Bank_number");?></th>
                <th align="center"><?php $this->lan("Transfer_voucher");?></th>
                <th align="center"><?php $this->lan("status");?></th>
                <th align="center"><?php $this->lan("time");?></th>
                <th align="center"><?php $this->lan("Audit_status");?></th>
                <th align="center"><?php $this->lan("Audit_remarks");?></th>
                <th align="center" colspan="4"><div class="operation"><?php $this->lan("operation");?></th>
            </tr>

            </thead>
            <tbody>
            <?php
                            $st = ["1"=>$this->lgs['Awaiting_review'],"2"=>$this->lgs['adopt'],"3"=>$this->lgs['Not_passed']];
            foreach($members as $v1){
                ?>
                <tr>
                    <td align="center"><?php echo $v1['id']; ?></td>
                    <td align="center"><?php echo $v1['uid']; ?></td>
                    <td align="center"><?php echo $v1['money']; ?></td>
                    <td align="center"><?php echo $v1['volume']['bank_name']; ?></td>
                    <td align="center"><?php echo $v1['volume']['bank_no']; ?></td>
			        <td align="center">
                        <?php
                            foreach($v1['picarr'] as $vl){
                                echo "<img src='$vl' height='80px' width='80px'> ";
                            }
                        ?>
                    </td>
                    <td align="center" class='usermoney ' ><?php echo $v1['status']; ?></td>
                    <td align="center" class='usermoney ' ><?php echo $v1['create_time']; ?></td>
                    <td align="center"><?php echo $st[$v1['manage_status']];?></td>
                    <td align="center"><?php echo $v1['manage_desc'];?></td>
                    <td align="center" colspan="4">
                        <input type="button" class="button myBtn" value="<?php $this->lan("handle","审核");?>" data-id="<?php echo $v1['id']; ?>">
                    </td>
                </tr>
                <?php
            }
        }
            ?>
        <?php  if (!$members ): ?>
        <tr>
                <th width="70px" style="max-width: 70px;" align="center">ID</th>
                <th align="center"><?php $this->lan("uid_nick");?></th>
                <th align="center"><?php $this->lan("amount");?></th>
                <th align="center"><?php $this->lan("Bank");?></th>
                <th align="center"><?php $this->lan("Bank_number");?></th>
                <th align="center"><?php $this->lan("Transfer_voucher");?></th>
                <th align="center"><?php $this->lan("status");?></th>
                <th align="center"><?php $this->lan("time");?></th>
                <th align="center"><?php $this->lan("Audit_status");?></th>
                <th align="center"><?php $this->lan("Audit_remarks");?></th>
                <th width="70px"><div class="operation"><?php $this->lan("operation");?></th>
            </tr>
            <tr>
                <td colspan="11" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php  endif; ?>
        </tbody>
        </table>
    </form>
    <div id="pages">
        <ul>
            <li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li>
            <?php echo $page->show('one', 'li'); ?>
        </ul>
    </div>
</div><!--table-list end-->
<div id="myModal" class="modal">
        <div class="modalcontent">
            <span class="close">&times;</span>
            <form class="layui-form layui-form-pane" action="#" id="operation" method="post">
                <input type="hidden" name="m-id">
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php $this->lan("Select_operation");?></label>
                    <div class="layui-input-block">
                        <select name="status" lay-filter="aihao" id='status' style="display: none;">
                            <option value="2"><?php $this->lan("Received");?></option>
                            <option value="3"><?php $this->lan("Not_received");?></option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php $this->lan("remarks");?></label>
                    <div class="layui-input-block">
                        <input type="text" name="desc" placeholder="<?php $this->lan("remarks");?>" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><?php $this->lan("Secure_password");?></label>
                    <div class="layui-input-block">
                        <input type="password" name="secure_password" placeholder="<?php $this->lan("Secure_password");?>" class="layui-input" required>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="m-submit"><?php $this->lan("Submit_now");?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script>
        // 获取模态窗口元素
        var modal = document.getElementById("myModal");
        // 获取触发模态窗口的按钮元素
        // 获取关闭按钮元素
        var span = document.getElementsByClassName("close")[0];
        // 当用户点击按钮时，显示模态窗口
        $(".myBtn").click(function() {
            var id = $(this).attr("data-id");
            $("input[name=m-id]").val(id);
            modal.style.display = "block";
        })
        	$("#m-submit").click(function(){
        		var id = $("input[name=m-id]").val();
        		var status = $("select[name=status]").val();
        		var desc = $("input[name=desc]").val();
        		var secure_password = $("input[name=secure_password]").val();
        		$.post("<?php echo WEB_PATH."/member/member/down_money_post"; ?>",{id:id,status:status,desc:desc,secure_password:secure_password},function(res){
        			res =JSON.parse(res)
                    console.log(res);
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
</body>
</html>