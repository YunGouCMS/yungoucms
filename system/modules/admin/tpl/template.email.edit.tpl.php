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
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
  <link type="text/css" href="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/sample/css/sample.css" rel="stylesheet" media="screen" />
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/ckeditor.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/ckeditor5-build-decoupled-document/translations/zh-cn.js"></script>
  <style>
    .box_content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      grid-template-rows: 1fr 1fr;

    }

    .document-editor {
      width: 600px;
    }
    .layui-form-item{
        display: flex;
        margin: 10px 0;
    }
    .layui-form-label{
        margin: 0 10px;
        line-height: 30px;
    }
    .layui-input-block {margin-left:0;}
  </style>

</head>

<body>
  <div class="lr10 header">
    <?php echo $this->headerment(); ?>
  </div>
  <div class="bk10"></div>
  <div class="box_content">
    <div class="table_form lr10">
      <form action="#" method="post" id="myform3">
        <table width="100%" class="lr10" style="border-style: none;">
          <div class="layui-form-item" style="<?php echo $id?"display: none;":"";?>">
            <label class="layui-form-label"><?php $this->lan("key","key"); ?></label>
            <div class="layui-input-block">
              <input type="text" style="width: 600px;height: 30px;text-indent:10px" name="key" value="<?php echo $record['key'];?>"  placeholder="<?php $this->lan("key"); ?>" class="layui-input">
            </div>
          </div>
          <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("Email_title","标题"); ?></label>
            <div class="layui-input-block">
              <input type="text" style="width: 600px;height: 30px;text-indent:10px" name="title" value="<?php echo $record['title'];?>"  placeholder="<?php $this->lan("message_title"); ?>" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item layui-form-text">
            <label class="layui-form-label"><?php $this->lan("content","内容"); ?></label>
            <div class="layui-input-block">
              <!-- <textarea name="desc" placeholder="请输入内容" class="layui-textarea"></textarea> -->
              <main>
                <div class="document-editor">
                  <div class="toolbar-container3"></div>
                  <div name="editorValue" ref="editor" id="editor">
                    <?php echo $record['value'];?>
                  </div>
                  <input type="hidden" name="value" id="editorValue" value="<?php echo $record['value'];?>" />
                </div>
              </main>
            </div>
          </div>
          <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("remark","说明"); ?></label>
            <div class="layui-input-block">
              <input type="text" style="width: 600px;height: 30px;text-indent:10px" name="remark" value="<?php echo $record['remark'];?>"  placeholder="<?php $this->lan("message_remark1"); ?>" class="layui-input">
            </div>
          </div>
          <div class="layui-form-item">
            <label class="layui-form-label"><?php $this->lan("desc","备注"); ?></label>
            <div class="layui-input-block">
              <input type="text" style="width: 600px;height: 30px;text-indent:10px" name="desc" value="<?php echo $record['desc'];?>"  placeholder="<?php $this->lan("message_remark2"); ?>" class="layui-input">
            </div>
          </div>
          <div class="btn_paixu" style="padding-left: 135px;">        		
            <input type ="submit" name="dosubmit" class='button' value="<?php $this->lan("save","保存"); ?>" style="margin-left:115px"/>
          </div>
        </table>
      </form>
    </div><!--table-list end-->
  </div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
<script>
  window.onload = function() {

    DecoupledEditor
      .create(document.querySelector('#editor'), {
        language: 'zh-cn',
        ckfinder: {
          uploadUrl: '/api/tjupload/upeditimage'
        }
      })
      .then(editor => {
        const toolbarContainer3 = document.querySelector('.toolbar-container3');
        toolbarContainer3.prepend(editor.ui.view.toolbar.element);
        window.editor = editor;
      })
      .catch(err => {
        console.error(err.stack);
      });

  }
  document.getElementById('editor').addEventListener('blur', function() {
    var htmlContent = document.getElementById("editor").innerHTML;
        document.getElementById("editorValue").value = htmlContent
  });
</script>
</html>