<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?php echo $this->getPageTitle('会员管理'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
<!-- 后台各页面详细UI升级样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">

<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">

<style>
    /* 站内信列表页面美化样式 */
    body {
        background: #f5f7fa;
    }
    
    .header.lr10 {
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    .table-list.lr10 {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow-x: auto;
    }
    
    /* 表格美化 */
    .table-list table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table-list thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .table-list thead th {
        color: #fff;
        font-weight: 600;
        padding: 16px 12px;
        text-align: center;
        border: none;
        font-size: 14px;
        white-space: nowrap;
    }
    
    .table-list thead th:first-child {
        border-radius: 8px 0 0 0;
    }
    
    .table-list thead th:last-child {
        border-radius: 0 8px 0 0;
    }
    
    .table-list tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .table-list tbody tr:hover {
        background: #f8f9ff;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
    }
    
    .table-list tbody td {
        padding: 16px 12px;
        text-align: center;
        color: #333;
        font-size: 14px;
        vertical-align: middle;
    }
    
    .table-list tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* 按钮美化 */
    .butaoin {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: #fff !important;
        padding: 8px 16px !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        border: none !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        text-decoration: none !important;
        display: inline-block !important;
        margin: 0 4px !important;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3) !important;
    }
    
    .butaoin:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
    }
    
    .butaish {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        box-shadow: 0 2px 4px rgba(245, 87, 108, 0.3) !important;
    }
    
    .butaish:hover {
        box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4) !important;
    }
    
    .button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: #fff !important;
        padding: 10px 20px !important;
        border-radius: 6px !important;
        border: none !important;
        cursor: pointer !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3) !important;
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
    }
    
    /* 复选框美化 */
    input[type="checkbox"] {
        width: 18px !important;
        height: 18px !important;
        cursor: pointer !important;
        accent-color: #667eea !important;
    }
    
    /* 单选框美化 */
    input[type="radio"] {
        width: 18px !important;
        height: 18px !important;
        cursor: pointer !important;
        accent-color: #667eea !important;
        margin-top: 0px !important;
        margin-right: 6px !important;
    }
    
    /* 表单区域美化 */
    .table-list form table {
        width: 100%;
    }
    
    .table-list form table td {
        padding: 16px 12px;
        vertical-align: middle;
    }
    
    .table-list form table td:first-child {
        font-weight: 600;
        color: #333;
        background: #f8f9fa;
        border-radius: 8px 0 0 8px;
    }
    
    /* layui switch 美化 */
    .layui-form-switch {
        margin-top: 0;
    }
    
    /* 模板区域美化 */
    .template {
        display: flex;
        justify-content: start;
        align-items: center;
        gap: 8px;
        margin-left: 20px;
    }
    
    .show {
        margin-left: 10px;
        margin-right: 10px;
        font-weight: 500;
        color: #333;
    }
    
    /* 分页美化 */
    #pages ul {
        display: flex;
        align-items: center;
        gap: 8px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    #pages ul li {
        padding: 8px 12px;
        border-radius: 6px;
        background: #f5f7fa;
        color: #333;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    #pages ul li a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
    }
    
    #pages ul li:hover {
        background: #f8f9ff;
    }
    
    /* 批量操作区域美化 */
    .btn_paixu {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 16px 20px;
        margin-top: 20px;
    }
    
    /* 已读/未读数据美化 */
    .table-list tbody td p {
        margin: 4px 0;
        padding: 4px 8px;
        background: #f0f0f0;
        border-radius: 4px;
        display: inline-block;
        font-size: 13px;
    }
    
    /* 响应式设计 */
    @media (max-width: 768px) {
        .table-list {
            padding: 16px;
        }
        
        .table-list table {
            font-size: 12px;
        }
        
        .table-list thead th,
        .table-list tbody td {
            padding: 10px 6px;
        }
    }
    
    /* 原有样式保留并优化 */
    tbody tr {
        line-height: 30px;
        height: auto;
    }
    
    .con_a_btn {
        border: 0px solid #0c0
    }
    
    .con_a_btn a {
        display: inline-block;
        padding: 3px 6px;
        margin-right: 5px;
        background: #6CF;
        color: #fff;
        font-weight: bold;
        text-decoration: none;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .con_a_btn a:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .remarks {
        margin: 10px 0;
    }
    
    .remarks p {
        line-height: 32px;
    }
    
    .remarks p a {
        background: #161E22;
        font-size: 12px;
        padding: 4px 6px;
        color: #fff;
        border-radius: 5px;
        margin-left: 20px;
        transition: all 0.3s ease;
    }
    
    .remarks p a:hover {
        background: #2a3a4a;
        transform: translateY(-1px);
    }

  .template{
    display:flex;justify-content: start;align-items: center;
}
  .show{
    margin-left: 10px;
    margin-right: 10px;
}
input[type="radio"] {
    margin-top: 0px !important;
}
.layui-form-switch {
    margin-top: 0;
}
table tbody td.layui-form .layui-form-switch.layui-form-onswitch, .table-list table tbody td.layui-form .layui-form-switch.layui-form-onswitch {margin: 10px 20px !important;}
    </style>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js"
type="text/javascript"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/clipboard.js"></script>
  
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script type="text/javascript" src="<?php echo G_TEMPLATES_JS; ?>/layui/layui.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <script src="/statics/templates/mlmuban/js/layui/lay/modules/form.js"></script>
</head>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
</div>

<div class="table-list lr10">
    <form name="myform" action="#" method="post">
        <table width="100%" cellspacing="0">
            <tr>
                <td width="188" align="center" style="padding: 0!important;"><?php $this->lan("message_notice");?></td>
                <td style="display:flex;justify-content: start;align-items: center;margin-bottom: -1px;padding: 0!important;" class="layui-form">
                    <input  type="checkbox"  class="is_state sta" name="is_state" <?php if($switch==1){ echo 'checked="checked"';} ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v['id'] ?>" lay-text="<?php $this->lan("open");?>|<?php $this->lan("close");?>">
                    <input type="hidden" value="<?php echo $v['id'] ?>" name="template_verify">
                    <!--<input type="radio" name="template_verify" value="1" <?php if($switch==1){ ?> checked="checked" <?php } ?> />&nbsp;开启&nbsp;-->
                    <!--<input type="radio" name="template_verify" value="0" <?php if($switch==0){ ?> checked="checked" <?php } ?>/>&nbsp;关闭-->

                    <div class="template" style="margin:0!important;padding: 0 5px;">
                        <p class="show"><?php $this->lan("Display_location");?></p>
                        <div>
                            <input name="" value="1" type="checkbox" checked/>&nbsp;<?php $this->lan("cneter");?>&nbsp;&nbsp;
                        </div>
                    </div>
                    <input style="height: 30px !important;margin: 0 !important;" type="submit" value=" <?php $this->lan("Submit");?> " name="dosubmit" class="button">
                </td>  
            </tr>
        </table>
    </form>
</div>
<div class="table-list lr10">
  <table width="100%" cellspacing="0">
    <thead>
      <tr>
        <th width="50px" align="center"><input id="checkAll" type="checkbox"></th>
        <th width="70px" align="center"><?php $this->lan("id");?></th>
        <th width="200px" align="center"><?php $this->lan("message_title");?></th>
       <!-- <th width="100px" align="center">接受用户</th> -->
<!--        <th width="100px" align="center">状态</th>-->
        <th width="300px" align="center"><?php $this->lan("time");?></th>
        <th align="center"><?php $this->lan("Read");?></th>
        <th align="center"><?php $this->lan("Unread");?></th>
        <th align="center"><?php $this->lan("operation");?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($members as $v) { ?>
      <tr>
        <td align="center"><input class="checkOne" type="checkbox"   data-id="<?php echo $v['id']; ?>"></td>
        <td align="center"><?php echo $v['id']; ?></td>
        <td align="center"><?php echo $v['titles']; ?></td>
<!--        <td align="center">--><?php //echo $v['username']; ?><!--</td>-->
<!--        <td align="center">--><?php //echo $v['statusName']; ?><!--</td>-->
          <td align="center"><?php echo $v['send_time']; ?> </td>
          <td align="center"><?php foreach ($v['read_data'] as $read) { ?>
              <?php echo $read['username'] .'('. $read['uid'].')'; ?>、
            <?php } ?> 
          </td>
          <td align="center"><?php foreach ($v['unread_data'] as $unread) { ?>
              <p><?php echo $unread['username'] .'('. $unread['uid'] .')'; ?></p>
            <?php } ?> 
          </td>
          <td align="center">
			<a class="butaoin" href="javascript:;"   class="charShow" onclick="openAk('<?php $this->lan('message_detail');?>','<?php echo G_MODULE_PATH; ?>/member/chartShow/<?php echo $v["id"]; ?>')" ><?php $this->lan("see");?></a>
			 <a class="butaoin butaish batchDel" style="cursor: pointer" data-id="<?php echo $v["id"]; ?>"><?php $this->lan("delete");?></a>
		  </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <div class="btn_paixu" style="padding: 6px 12px 0px 0px;margin-bottom: 5px;display:flex;align-items: center;justify-content: space-between;height: 50px;"><input type="button" value=" <?php $this->lan("Batch_deletion");?> " class="batchDels button" style="margin-bottom: 10px;">
    <div id="pages">
        <ul>
            <li>共 <?php echo $total; ?> 条</li>
            <?php echo $page->show('one', 'li'); ?>
        </ul>
    </div>
  </div>
    
</div>

  <script>
    layui.use(['form','jquery'],function () {
        var form = layui.form,$ = layui.jquery;
        form.on('switch(is_state)',function (data) {
            var zhi = data.elem.checked?1:0;
            $('input[name=template_verify]').val(zhi)
        });
    });
      function openAk(title,url) {
          console.log(title)
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

      $('.batchDel').click(function(){
            let id = $(this).data('id');
        //   console.log(id);
          $.ajax({
              type: 'POST',
              url: "<?php echo G_MODULE_PATH; ?>/member/chartDel/",
              data: {id: id},
              dataType: 'json',
              success: function (data) {
                  console.log(data);
                  if (data['code'] == 1) {
                      alert(data['msg']);
                      window.history.go(0)
                      //$(window).attr('location','<?php //echo G_MODULE_PATH; ?>///member/lists');
                      // window.location.href='http://www.yungoucms.cn/member/member/lists';
                  }else {
                      alert(data['msg']);

                  }
              }
          });
          id="";
      });
 $('.template').attr('style','display:none');
 $('input[type=radio][name=template_verify]').change(function () {
        var status = $(this).val();
        switch (status) {
            case '1':
                $('.template').attr('style','');
                break
            case '0':
                $('.template').attr('style','display:none');
                break
            default:
                console.log(status);
            }
        }
 )
 $("#checkAll").on('change', function () {
    if ($(this).is(":checked")) { // 全选
        $(".checkOne").prop("checked",true);
    } else { // 反选
        $(".checkOne").prop("checked",false);
    }
});
$('.batchDels').click(function(){
    //获取所有选中的CheckBox的id
     var  ids="";
     $(".checkOne").each(function(i) {
       if($(this).prop('checked')){
       var id= $(this).attr("data-id");
            if(ids){
                ids += ','
            }
		    ids+=+id;	
       }
	// ajax批量删除
    });
      $.ajax({
        	type: 'POST',
        	url: '/member/member/chartDels',
        	data: {ids: ids},
        	dataType: 'json',
        	success: function (data) {
           			 if (data.code == '1') {
                      alert("删除成功!");
                      window.history.go(0)
            		}else {
                      alert("删除失败!");
                     
                    }
        		}
    		});
      	ids="";
  	});
</script>
</body>

</html>
