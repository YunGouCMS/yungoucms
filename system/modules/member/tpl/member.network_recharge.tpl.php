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
    <style>
        tbody tr{ line-height:30px; height:30px;} 
        table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; width:200px;}
        table td{ padding-left:10px;background: #fff;color: #444;
    font-size: 12px;
    font-weight: normal;}
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
		.butaoin {
			color: #ffffff;
			background: #3c8dbc;
			padding: 3px 5px;
			border-radius: 5px;
		}
		.butaoin.butaish {
			background: #ef2916;
		}
		.butaoin.butaish:hover {
			background: #f44336;
		}
    </style>
</head>
<body>
<div class="header lr10">
    <h3 class="nav_icon"><?php $this->lan("title");?></h3>
</div>
<div class="bk10"></div>
<div class="table_form table-list lr10">
    <!--start-->
    <form name="myform" action="#" method="post">
        <table width="100%" cellspacing="0" style="table-layout: fixed;">

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
            if($addmoney_record['0']){
            ?>
            <tr>
                <th width="60px" style="max-width: 60px;" align="center"><input id="checkAll" type="checkbox"></th>
                <th width="70px" style="max-width: 70px;" align="center"><?php $this->lan("ID");?></th>
                <th align="center"><?php $this->lan("uid_nick");?></th>
                <th align="center"><?php $this->lan("amount");?></th>
                <th align="center"><?php $this->lan("order");?></th>
                <th align="center"><?php $this->lan("status");?></th>
                <th align="center"><?php $this->lan("time");?></th>
                <!--th width="20%" align="center"><div class="operation"><span class="chosen" id='1'>线下充值</span><span id='2'>线下扣钱</span></div></th-->
                <th align="center"><div class="operation"><?php $this->lan("operation");?></th>    
            </tr>

            </thead>
            <tbody>
            <?php
            foreach($addmoney_record as $v1){
                ?>
                <tr>
                    <td align="center" style="padding-left:5px;"><input class="checkOne" type="checkbox"   data-id="<?php echo $v1['id']; ?>"></td>
                    <td align="center"><?php echo $v1['id']; ?></td>
                    <td align="center"><?php echo $v1['uid']; ?></td>
                    <td align="center"><?php echo $v1['money']; ?></td>

                    <td align="center" class='usermoney ' ><?php echo $v1['code']; ?></td>
                    <td align="center" class='usermoney ' ><?php echo $v1['status']; ?></td>
                    <!-- <?php echo date("Y/m/d H:i:s",$v['logintime']); ?> -->
                    <td align="center" class='usermoney ' ><?php echo $v1['time']; ?></td>
                   <td align="center">
                       <!--class="button batchDel"-->
						  <input type="button" class="butaoin batchDel butaish" data-id="<?php echo $v1['id']; ?>" value="<?php $this->lan('del');?>">
                        <!--input type="number" value='' name='money' id='money' cid="<?php echo $v1['uid']; ?>" style='width: 109px;height: 28px;'>
					 <input type="button" class="button" onclick='upmoney();' value="修改" -->

                        <!--<input type="button" class="button buttons<?php echo $v1['uid']; ?>" value="已到账" id="<?php echo $v1['id']; ?>" status="2" >-->
                        <!--<input type="button" class="button buttons<?php echo $v1['uid']; ?>" value="未到账" id="<?php echo $v1['id']; ?>" status="3" >-->
                   </td>
                </tr>
                <?php
            }
            }else{
                echo "<?php $this->lan('No_data');?>";
            }
            ?>
            </tbody>
        </table>
    </form>
    <div class="btn_paixu" style="margin-left: -10px;"><input type="button" value=" <?php $this->lan("Batch_deletion");?> " class="batchDel button"></div>
    <div id="pages">
        <ul>
            <li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li>
            <?php echo $page->show('one', 'li'); ?>
        </ul>
    </div>
</div><!--table-list end-->

<div class="" id="window" style="margin:30px;display:none;">
    <form class="layui-form layui-form-pane" action="#" id="operation" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-block">
                <input type="tel" name="phone" lay-verify="phone" autocomplete="off"  class="layui-input" value="" readonly>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">账户金额</label>
            <div class="layui-input-block">
                <input type="number" name="money" lay-verify="money" autocomplete="off" class="layui-input" value="" readonly>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">金额</label>
            <div class="layui-input-block">
                <input type="number" name="addmoney" lay-verify="title" autocomplete="off" placeholder="请输入金额" class="layui-input" required onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">选择操作</label>
            <div class="layui-input-block">
                <select name="interest" lay-filter="aihao" id='interest'>
                    <option value=""></option>
                    <option value="1">线下充值</option>
                    <option value="2">线下扣钱</option>
                </select>
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
    layui.use('form', function(){
        $ = layui.jquery,
            form = layui.form;
        //监听提交
        form.on('submit(demo)', function(data){
            return false;
        });

    });
    function upImage(){
        return document.getElementById('imgfield').click();
    }
    $(function(){
        $('.operation span').click(function(){
            $(this).addClass('chosen').siblings().removeClass('chosen');

        });

        $("#checkAll").on('change', function () {
            if ($(this).is(":checked")) { // 全选
                $(".checkOne").prop("checked",true);
            } else { // 反选
                $(".checkOne").prop("checked",false);
            }
        });

        $('.batchDel').click(function(){
            // if(!confirm("<?php $this->lan('Confirm_delete');?> ")){
            //     return;
            // }
            var ids = $(this).attr('data-id')
            if(!ids){
                //获取所有选中的CheckBox的id
                var  ids="";
                $(".checkOne").each(function(i) {
                    if($(this).prop('checked')){
                        if(ids){ids += ',';}
                        var id= $(this).attr("data-id");
                        ids+=+id;
                    }
                // ajax批量删除
                });
            }
            if(!ids){
                layer.msg("请选择需要删除的记录!");
                return;
            }
            var param = {ids:ids};
            if(!confirm("<?php $this->lan('Confirm_delete');?> ",'<?echo G_WEB_PATH;?>/member/member/patch_del_recharge',param)){
                return;
            }
            return false;
            $.ajax({
                    type: 'POST',
                    url: '<?echo G_WEB_PATH;?>/member/member/patch_del_recharge',
                    data: {ids: ids},
                    dataType: 'json',
                    success: function (data) {
                            if (data == 0) {
                                layer.msg("删除成功!");
                                setTimeout(function(){
                                    location.reload(false);
                                },1500);
                            }else {
                                layer.msg("删除失败!");
                            }
                        }
            });
            ids="";
        }); 

        $(".down_money").click(function(){
            var id=$(this).attr('id')
            var status=$(this).attr('status')

            $.ajax({
                type: "post",
                url: "<?php echo WEB_PATH."/member/member/down_money_post"; ?>",
                cache: false,
                async: false,
                dataType: "json",
                data: {id:id,status:status},
                success: function(data) {
                    setTimeout(function(){
                        issucces = false
                    },2000)
                    if(data.code==1){
                        layer.msg(data.msg);
                        location.reload();

                    }else{
                        layer.msg(data.msg);
                    }
                }
            });
        })
    });

</script>
</body>
</html>