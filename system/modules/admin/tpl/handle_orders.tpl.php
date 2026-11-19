<?php if($type=="1"){?>
<table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center"><?php $this->lan("id");?></th>
            <th align="center"><?php $this->lan("Order_number");?></th>
            <th align="center" width="20%"><?php $this->lan("Product_title");?></th>
            <th align="center"><?php $this->lan("Purchase_users");?></th>
            <th align="center"><?php $this->lan("Purchase_frequency");?></th>
            <th align="center"><?php $this->lan("Total_purchase");?></th>
            <th align="center"><?php $this->lan("Purchaser_time");?></th>
            <th align="center"><?php $this->lan("Winner_order_status");?></th>
            <th align="center"><?php $this->lan("operator");?></th>
            <th align="center"><?php $this->lan("Audit_status");?></th>
            <th align="center"><?php $this->lan("operation");?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $v) {    ?>
            <tr>
                <td align="center"><?php echo $v['id']; ?></td>
                <td align="center">
                    <a href="<?php echo G_MODULE_PATH; ?>/dingdan/get_dingdan/<?php echo $v['id']; ?>">
                        <?php echo $v['code']; ?><?php if ($v['code_tmp']) echo " <font color='#ff0000'>[多]</font>"; ?></a>
                </td>
                <td align="left" style=" padding-left:20px">
                    <a href="/mobile/mobile/item/<?php echo $v['shopids']; ?>" target="_bank">
                        第(<?php echo $v['shopqishu']; ?>)期<?php echo _strcut($v['shopname'], 0, 25); ?></a>
                </td>
                <td align="center"><a href="<?php echo WEB_PATH; ?>/member/member/daiguan/<?php echo $v['uid']; ?>" target="_bank"><?php echo $v['username']; ?></a></br><?php echo $v['uid']; ?></td>
                <td align="center"><?php echo $v['gonumber']; ?>人次</td>
                <td align="center"><?php echo $v['moneycount']; ?></td>
                <td align="center">
                    <?php echo date("Y-m-d H:i:s", $v['operat_time']); ?>
                    </br>
                    <?php echo date("Y/m/d H:i:s", $v['time']); ?>
                </td>
                <td align="center"><?php echo $v['huode'] ? "中奖" : '未中奖'; ?></br><?php echo $v['t_status']; ?></td>
                <td align="center"><?php echo $v['operat_name']; ?></br><?php echo $v['operat_desc']; ?></td>
                <td align="center"><?php echo $status_text[$v['manage_status']]; ?></br><?php echo $v['manage_desc']; ?></td>
                <td align="center">
                    <?php if ($v['manage_status'] == 1) { ?>
                        <button type="button" class="butaoin myBtn" style="border:none" data-id="<?php echo $v['id']; ?>"><?php $this->lan("examine");?></button>
                    <?php } ?>
                    <a class="butaoin iframexq" data-href="<?php echo G_MODULE_PATH; ?>/dingdan/get_dingdan/<?php echo $v['to_id']; ?>"><?php $this->lan("details");?></a>
                    <!-- <a class="butaoin detailShow" data-url="<?php echo G_MODULE_PATH; ?>/dingdan/get_dingdan/<?php echo $v['id']; ?>"><?php $this->lan("details");?></a> -->
                </td>
            </tr>
        <?php } ?>
        <?php if (!$list): ?>
            <tr>
                <td colspan="11" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php }elseif($type == '2'){ ?>
<table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center"><?php $this->lan("id");?></th>
            <th align="center"><?php $this->lan("Order_number");?></th>
            <th align="center" width="20%"><?php $this->lan("Product_title");?></th>
            <th align="center"><?php $this->lan("Purchase_users");?></th>
            <th align="center"><?php $this->lan("Purchase_frequency");?></th>
            <th align="center"><?php $this->lan("Total_purchase");?></th>
            <th align="center"><?php $this->lan("Purchaser_time");?></th>
            <th align="center"><?php $this->lan("Order_status");?></th>
            <th align="center"><?php $this->lan("operator");?></th>
            <th align="center"><?php $this->lan("Audit_status");?></th>
            <th align="center"><?php $this->lan("operation");?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $v) {    ?>
            <tr>
                <td align="center"><?php echo $v['id']; ?></td>
                <td align="center">
                    <a href="<?php echo G_MODULE_PATH; ?>/dingdan/get_dingdan/<?php echo $v['id']; ?>"><?php echo $v['code']; ?></a>
                </td>
                <td align="left" style=" padding-left:20px">
                    <a href="/mobile/mobile/item/<?php echo $v['shopids']; ?>" target="_bank"><?php echo _strcut($v['shopname'], 0, 25); ?></a>
                </td>
                <td align="center"><a href="<?php echo WEB_PATH; ?>/member/member/daiguan/<?php echo $v['uid']; ?>" target="_bank"><?php echo $v['username']; ?></a></br><?php echo $v['uid']; ?></td>
                <td align="center"><?php echo $v['gonumber']; ?></td>
                <td align="center"><?php echo $v['moneycount']; ?></td>
                <td align="center">
                    <?php echo date("Y-m-d H:i:s", $v['operat_time']); ?>
                    </br>
                    <?php echo date("Y/m/d H:i:s", $v['time']); ?>
                </td>
                <td align="center"><?php echo $this->yh_status[$v['t_status']]; ?></td>
                <td align="center"><?php echo $v['operat_name']; ?></br><?php echo $v['operat_desc']; ?></td>
                <td align="center"><?php echo $status_text[$v['manage_status']]; ?></br><?php echo $v['manage_desc']; ?></td>
                <td align="center">
                    <?php if ($v['manage_status'] == 1) { ?>
                        <button type="button" class="butaoin myBtn" style="border:none" data-id="<?php echo $v['id']; ?>"><?php $this->lan("examine");?></button>
                    <?php } ?>
                    <a class="butaoin iframexq" data-href="<?php echo G_MODULE_PATH; ?>/dingdan/get_yhdingdan/<?php echo $v['to_id']; ?>"><?php $this->lan("details");?></a>
                </td>
            </tr>
        <?php } ?>
        <!-- 修改 -->
        <?php if (!$list): ?>
            <tr>
                <td colspan="11" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php }elseif($type == '3'){ ?>
    <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center"><?php $this->lan("id");?></th>
            <th align="center"><?php $this->lan("Order_number");?></th>
            <th align="center" width="20%"><?php $this->lan("Product_title");?></th>
            <th align="center"><?php $this->lan("Purchase_users");?></th>
            <th align="center"><?php $this->lan("Purchase_frequency");?></th>
            <th align="center"><?php $this->lan("Total_purchase");?></th>
            <th align="center"><?php $this->lan("Purchaser_time");?></th>
            <th align="center"><?php $this->lan("Winner_order_status");?></th>
            <th align="center"><?php $this->lan("operator");?></th>
            <th align="center"><?php $this->lan("Audit_status");?></th>
            <th align="center"><?php $this->lan("operation");?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $v) {    ?>
            <tr>
                <td align="center"><?php echo $v['id']; ?></td>
                <td align="center">
                    <a href="<?php echo G_MODULE_PATH; ?>/dingdan/get_dingdan/<?php echo $v['id']; ?>"><?php echo $v['code']; ?></a>
                </td>
                <td align="left" style=" padding-left:20px">
                    <a href="/mobile/mobile/item/<?php echo $v['shopids']; ?>" target="_bank"><?php echo _strcut($v['shopname'], 0, 25); ?></a>
                </td>
                <td align="center"><a href="<?php echo WEB_PATH; ?>/member/member/daiguan/<?php echo $v['uid']; ?>" target="_bank"><?php echo $v['username']; ?></a></br><?php echo $v['uid']; ?></td>
                <td align="center"><?php echo $v['gonumber']; ?>人次</td>
                <td align="center"><?php echo $v['moneycount']; ?></td>
                <td align="center">
                    <?php echo date("Y-m-d H:i:s", $v['operat_time']); ?>
                    </br>
                    <?php echo date("Y/m/d H:i:s", $v['time']); ?>
                </td>
                <td align="center"><?php echo $v['huode'] ? "中奖" : '未中奖'; ?></br><?php echo $this->group_status[$v['t_status']];; ?></td>
                <td align="center"><?php echo $v['operat_name']; ?></br><?php echo $v['operat_desc']; ?></td>
                <td align="center"><?php echo $status_text[$v['manage_status']]; ?></br><?php echo $v['manage_desc']; ?></td>
                <td align="center">
                    <?php if ($v['manage_status'] == 1) { ?>
                        <button type="button" class="butaoin myBtn" style="border:none" data-id="<?php echo $v['id']; ?>"><?php $this->lan("examine");?></button>
                    <?php } ?>
                    <a class="butaoin" href="<?php echo G_MODULE_PATH; ?>/dingdan/get_dingdan/<?php echo $v['to_id']; ?>"><?php $this->lan("details");?></a>
                </td>
            </tr>
        <?php } ?>
        <!-- 修改 -->
        <?php if (!$list): ?>
            <tr>
                <td colspan="11" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php }?>
<!-- <style>
    .ifmodal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }
    #iframediv {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80vw;
        height: 80vh;
        overflow: hidden;
    }
    #iframediv iframe {
        border: 0;
        margin-top: -1px;
    }
    .closexq {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        width: 100%;
        background: #ffffff;
        height: 35px;
    }
    .closexq div {
        float: right;
        padding: 2px 12px 0;
        cursor: pointer;
    }
</style>
<div class="ifmodal">
    <div id="iframediv">
        <div class="closexq"><div>×</div></div>
        <iframe src="" width="100%" height="100%"></iframe>
    </div>
</div>
<script>
    $('.iframexq').click(function(){
        var xqurl = $(this).attr('data-href')
        $('.ifmodal').show()
        $('#iframediv iframe').attr('src',xqurl)
    })
    $('.closexq div').click(function(){
        $('.ifmodal').hide()
    })
</script> -->