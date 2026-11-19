<?php if($type=="31"){?>
<table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center"><?php $this->lan("id");?></th>
            <th align="center"><?php $this->lan("User_id");?></th>
            <th align="center"><?php $this->lan("Channel");?></th>
            <th align="center"><?php $this->lan("Account_name");?></th>
            <th align="center"><?php $this->lan("Bank_info");?></th> 
            <th align="center"><?php $this->lan("Withdrawal_amount");?></th>
            <th align="center"><?php $this->lan("commission");?></th>
            <th align="center"><?php $this->lan("Amount");?></th>
            <th align="center"><?php $this->lan("Order_status");?></th>
            <th align="center"><?php $this->lan("operation");?></th>
            <th align="center"><?php $this->lan("examine_time");?></th>
            <th align="center"><?php $this->lan("Audit_status");?></th>
            <th align="center"><?php $this->lan("operation");?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $v) {   ?>
            <tr>
                <td align="center"><?php echo $v['id']; ?></td>
                <td align="center"><a href="<?php echo WEB_PATH; ?>/member/member/daiguan/<?php echo $v['uid']; ?>" target="_bank"><?php echo $v['username']; ?></a></br><?php echo $v['uid']; ?></td>
                <td align="center"><?php echo $channels[$v['top_uid']]['username']; ?></td>
                <td align="center"><?php echo $v['k_username']; ?></td>
                <td align="center"><?php echo $v['bankname']." ".$v['branch']." ".$v['banknumber']; ?></td>	
                <td align="center"><?php echo $v['money']; ?></td>	
                <td align="center"><?php echo $fufen['fufen_yongjintx']; ?></td>	
                <td align="center"><?php echo $v['username']; ?></td>	
                <td align="center"> <?php echo $this->cashout_status[$v['t_status']]; ?></td>
                <td align="center"><?php echo $v['operat_name']; ?></br><?php echo $v['operat_desc']; ?></td>
                <td align="center"><?php echo $v['manage_time']; ?></td>
                <td align="center"><?php echo $status_text[$v['manage_status']]; ?></br><?php echo $v['manage_desc']; ?></td>
                <td align="center">
                    <?php if ($v['manage_status'] == 1 || $v['t_status'] == 4) { ?>
                        <button type="button" class="butaoin myBtn" style="border:none" data-id="<?php echo $v['id']; ?>"><?php $this->lan("examine");?></button>
                    <?php } 
                    if ($v['t_status'] == 4) { ?>
                        <a class="butaoin" href="<?php echo G_MODULE_PATH.'/handle/handle_cashout/'.$v['to_id'];?>" ><?php $this->lan("finish","已手动打款");?></a>
                    <?php } ?>
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
<?php }?>