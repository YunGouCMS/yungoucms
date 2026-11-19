<?php if($type == "41,42" || $type == "41"){?>
<table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center"><?php $this->lan("id");?></th>
            <th align="center"><?php $this->lan("User_id");?></th>
            <th align="center"><?php $this->lan("Recharge_amount");?></th>
            <th align="center"><?php $this->lan("Purchaser_time");?></th>
            <th align="center"><?php $this->lan("Order_status");?></th>
            <th align="center"><?php $this->lan("operation");?></th>
            <th align="center"><?php $this->lan("Audit_status");?></th>
            <th align="center"><?php $this->lan("operation");?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $v) {   ?>
            <tr>
                <td align="center"><?php echo $v['id']; ?></td>
                <td align="center"><a href="<?php echo WEB_PATH; ?>/member/member/daiguan/<?php echo $v['uid']; ?>" target="_bank"><?php echo $v['username']; ?></a></br><?php echo $v['uid']; ?></td>
                <td align="center"><?php echo $v['money']; ?></td>	
                <td align="center">
                    <?php echo date("Y-m-d H:i:s", $v['operat_time']); ?>
                    </br>
                    <?php echo date("Y/m/d H:i:s", $v['time']); ?>
                </td>
                <td align="center"><?php echo $v['t_status_cn']; ?></td>
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
<?php }?>