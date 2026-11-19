<?php if($type=="21"){?>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center"><?php $this->lan("id");?></th>
            <th align="center"><?php $this->lan("User_id");?></th>
            <th align="center"><?php $this->lan("Bank");?></th>
            <th align="center"><?php $this->lan("Bank_number");?></th>
            <th align="center"><?php $this->lan("voucher");?></th>
            <th align="center"><?php $this->lan("Amount");?></th>
            <th align="center"><?php $this->lan("Purchaser_time1");?></th>
            <th align="center"><?php $this->lan("Order_status");?></th>
            <th align="center"><?php $this->lan("operation");?></th>
            <th align="center"><?php $this->lan("Audit_status");?></th>
            <th align="center"><?php $this->lan("operation");?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $v) { $v['volume'] = safe_unserialize($v['volume'] ?? ''); $v['picarr'] = safe_unserialize($v['picarr'] ?? '');   ?>
            <tr>
                <td align="center"><?php echo $v['id']; ?></td>
                <td align="center"><a href="<?php echo WEB_PATH; ?>/member/member/daiguan/<?php echo $v['uid']; ?>" target="_bank"><?php echo $v['username']; ?></a></br><?php echo $v['uid']; ?></td>
                <td align="center"><?php echo $v['volume']['bank_name']; ?></td>
                <td align="center"><?php echo $v['volume']['bank_no']; ?></td>
                <td align="center"><?php
                            foreach($v['picarr'] as $vl){
                                echo "<img src='$vl' height='80px' width='80px'> ";
                            }
                            ?></td>
                <td align="center"><?php echo $v['money']; ?></td>
                <td align="center">
                    <?php echo date("Y-m-d H:i:s", $v['operat_time']); ?>
                    </br>
                    <?php echo date("Y/m/d H:i:s", $v['create_time']); ?>
                </td>
                <td align="center"><?php echo $this->offline_status[$v['t_status']]; ?></td>
                <td align="center"><?php echo $v['operat_name']; ?></br><?php echo $v['operat_desc']; ?></td>
                <td align="center"><?php echo $status_text[$v['manage_status']]; ?></br><?php echo $v['manage_desc']; ?></td>
                <td align="center">
                    <?php if ($v['manage_status'] == 1) { ?>
                        <button type="button" class="butaoin myBtn" style="border:none" data-id="<?php echo $v['id']; ?>"><?php $this->lan("examine");?></button>
                    <?php } ?>
                    <button type="button" class="butaoin iframexq" style="border:none" data-href="<?php echo G_MODULE_PATH.'/'.ROUTE_C; ?>/details/<?php echo $v['id']; ?>"><?php $this->lan("details");?></button>
                </td>
            </tr>
        <?php } ?>
        <?php if (!$list): ?>
            <tr>
                <td colspan="8" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</script>
<?php }elseif($type == '22'){ ?>
    <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center"><?php $this->lan("id");?></th>
            <th align="center"><?php $this->lan("User_id");?></th>
            <th align="center"><?php $this->lan("voucher");?></th>
            <th align="center"><?php $this->lan("Amount");?></th>
            <th align="center"><?php $this->lan("exchange_rate");?></th>
            <th align="center"><?php $this->lan("Purchaser_time1");?></th>
            <th align="center"><?php $this->lan("Order_status");?></th>
            <th align="center"><?php $this->lan("operation");?></th>
            <th align="center"><?php $this->lan("Audit_status");?></th>
            <th align="center"><?php $this->lan("today");?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $v) { $v['volume'] = safe_unserialize($v['volume'] ?? '');$v['picarr'] = safe_unserialize($v['picarr'] ?? '');     ?>
            <tr>
                <td align="center"><?php echo $v['id']; ?></td>
                <td align="center"><a href="<?php echo WEB_PATH; ?>/member/member/daiguan/<?php echo $v['uid']; ?>" target="_bank"><?php echo $v['username']; ?></a></br><?php echo $v['uid']; ?></td>
                <td align="center">
                    <?php
                            foreach($v['picarr'] as $vl){
                                echo "<img src='$vl' height='80px' width='80px'> ";
                            }
                    ?></td>
                <td align="center"><?php echo $v['money']; ?></td>
                <td align="center"><?php echo number_format($v['real_money']?:$v['money'],2); ?></td>
                <td align="center">
                    <?php echo date("Y-m-d H:i:s", $v['operat_time']); ?>
                    </br>
                    <?php echo date("Y/m/d H:i:s", $v['create_time']); ?>
                </td>
                <td align="center"><?php echo $this->offline_status[$v['t_status']]; ?></td>
                <td align="center"><?php echo $v['operat_name']; ?></br><?php echo $v['operat_desc']; ?></td>
                <td align="center"><?php echo $status_text[$v['manage_status']]; ?></br><?php echo $v['manage_desc']; ?></td>
                <td align="center">
                    <?php if ($v['manage_status'] == 1) { ?>
                        <button type="button" class="butaoin myBtn" style="border:none" data-id="<?php echo $v['id']; ?>"><?php $this->lan("examine");?></button>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <?php if (!$list): ?>
            <tr>
                <td colspan="8" style="text-align: center;font-size: 14px;margin: 20px;color:#999"><?php $this->lan("No_data");?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php }elseif($type == '23'){ ?>

<?php }?>