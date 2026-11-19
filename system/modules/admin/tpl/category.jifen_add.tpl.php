<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $this->getPageTitle('后台页面'); ?></title>
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
  <script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
</head>

<body>
  <div class="header lr10">
    <?php echo $this->headerment(); ?>
  </div>
  <style>
    iframe {
      font-size: 36px;
    }

    .con-tab {
      margin: 10px;
      color: #444
    }

    .con-tab #tab-i {
      margin-left: 20px;
      overflow: hidden;
      height: 27px;
      _height: 28px;
    }

    .con-tab #tab-i li {
      float: left;
      background: #eaeef4;
      padding: 0 8px;
      border: 1px solid #dce3ed;
      height: 25px;
      _height: 26px;
      line-height: 26px;
      margin-right: 5px;
      cursor: pointer;
      position: relative;
      z-index: 0;
    }

    .con-tab div.con-tabv {
      clear: both;
      border: 1px solid #dce3ed;
      width: 100%;
      margin-top: -1px;
      padding-top: 30px;
      background-color: #fff;
      position: relative;
      z-index: 1;
    }

    #tab-i li.on {
      background-color: #fff;
      color: #444;
      font-weight: bold;
      border-bottom: 1px solid #fff;
      position: relative;
      z-index: 2;
    }

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

    .con-tabv tr {
      text-align: left
    }

    input.button {
      display: inline-block
    }
    .table-button {background: none;}
    .table-list table tbody td,table th {
        border-bottom: 0!important;
    }
  </style>
  <div class="bk10"></div>
  <div class="table-list con-tab lr10" id="con-tab">
    <form action="#" id="form" method="post" enctype="multipart/form-data">
      <table width="100%" class="table_form">
        <tbody>
          <tr>
            <th width="200" style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Parent_category");?></th>
            <td style="display:flex;">
              <select name="info[parentid]" class="wid150" style="margin:0!important;">
                <option value="">≡ <?php $this->lan("First_level");?> ≡</option>
                <?php echo $categoryshtml; ?>
              </select>
            </td>
          </tr>
          <tr>
            <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Column_name");?></th>
            <td style="display:flex;"><input type="text" name="info[name]" value="<?php echo $res['name'];?>" class="input-text wid140" onKeyUp="value=value.replace(/[^\a-\z\A-\Z0-9\u4E00-\u9FA5\_]/g,'')">
              <span style="margin:0!important;">
                <!--<font color="#0c0">※ </font>-->
                <div data-title="<?php $this->lan("tips1");?>" style="display: inline-block;width:200px;" class="tip">
          <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin:14px 0 0 10px!important;">
    </div>
              </span>
            </td>
          </tr>
          <tr>
            <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("eng_name");?></th>
            <td style="display:flex;"><input type="text" name="info[catdir]" value="<?php echo $res['catdir'];?>" onKeyUp="value=value.replace(/[^\w]/ig,'')" class="input-text wid140">
              <span style="margin:0!important;">
                <!--<font color="#0c0">※ </font>-->
                <div data-title="<?php $this->lan("tips2");?>" style="display: inline-block;width:200px" class="tip">
          <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20" style="margin:14px 0 0 10px!important;">
    </div>
              </span>
          </tr>
          <tr>
            <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("exhibit");?></th>
            <td style="display: flex;flex-direction: column;width: max-content;gap: 15px;border-right: 0;">
              <?php
                    foreach($languages as $k=>$v){?>
                <div style="display:flex;">
                   
                    <input  type="text" 
                            name="languages[<?php echo $v['lg_value']; ?>]"
                            value="<?php echo $res["languages"][$v['lg_value']]?>" 
                            style="min-width:407px;width:407px" placeholder="" class="input-text">
                             <span style="width: 300px; display:inline-block;    text-align: left;margin: 10px 0 0 10px!important;"><?php echo $v['lg_value']."-".$v['lg_name']; ?></span>
                </div>
                <?php } ?>
            </td>
          </tr>
          <tr>
            <th style="text-align: right !important;padding-right: 10px;"><?php $this->lan("Column_images");?></th>
            <td style="margin-left: 0px;display: flex;">
              <div class="layui-upload-list" style="border: 1px dashed #B4B4B4;width:50px;height:50px;padding:0;margin:0!important;">
                <div class="layui-bottom-img" <?php if ($res["info"]['thumb']): ?>style="opacity: 0;" <?php endif; ?>>
                  <span>+</span>
                  <!--<text>点击上传</text>-->
                </div>
                <img class="layui-upload-img"  width="50px" src="<?php echo $res["info"]['thumb']?G_UPLOAD_PATH . '/' . $res["info"]['thumb']:""; ?>" id="cate-img">
                <input type="hidden" id="cate-lack" name="pic_url" value="<?php echo $res["info"]['thumb'];?>">
                <p id="demoText"></p>
                <button type="button" onclick="uploadimg('上传分类图片','#cate','image','cate')"></button>
              </div>
            </td>
          </tr>
          <tr style="display:none">
            <th style="border-bottom:0px;">描述：</th>
            <td style="border-bottom:0px;">
              <textarea name="info[description]" maxlength="255" class="overflow" style="width:300px;height:60px;"><?php echo $res["info"]['des'];?></textarea>
            </td>
          </tr>
        </tbody>
      </table>
  </div>
  <!--table-list end-->

  <div class="table-button lr10" style="padding-left: 220px;">
    <input type="submit" name="dosubmit" value="<?php $this->lan("Submit");?>" class="button" style="margin:0!important;">
    </form>
  </div>
  <script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/js/GgTab.js"></script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
<script>
</script>

</html>