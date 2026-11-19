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
  <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
  <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>

  <style>
    .page-container {
      display: block;
    }

    .country-panel,
    .addr-panel {
      background: #fff;
      border-radius: 4px;
      padding: 15px 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.04);
      width: 100%;
      box-sizing: border-box;
      margin-bottom: 15px;
    }

    .country-title,
    .addr-title {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .country-list {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .country-item {
      padding: 4px 10px;
      border-radius: 16px;
      border: 1px solid #e2e2e2;
      cursor: pointer;
      font-size: 13px;
      background: #f8f8f8;
      color: #333;
      text-decoration: none;
    }

    .country-item.active {
      background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);
      color: #fff;
      border-color: transparent;
    }

    .addr-list {
      margin-top: 10px;
    }

    .addr-header,
    .addr-row {
      display: flex;
      align-items: center;
      padding: 6px 8px;
      border-bottom: 1px solid #f0f0f0;
      font-size: 13px;
    }

    .addr-header {
      background: #fafafa;
      font-weight: 600;
    }

    .addr-cell-toggle {
      width: 32px;
    }

    .addr-cell-id {
      width: 60px;
    }

    .addr-cell-name {
      width: 180px;
    }

    .addr-cell-code {
      width: 140px;
    }

    .addr-cell-desc {
      flex: 1;
    }

    .addr-cell-op {
      width: 420px; /* 仅本页操作列加宽 */
      text-align: center;
    }

    .addr-row.level-0 {
      background: #fffdf6;
    }

    .addr-row.level-1 {
      background: #fff;
    }

    .addr-row.level-2 {
      background: #f7fbff;
    }

    .addr-row.level-3 {
      background: #f5f5ff;
    }

    /* 不同层级左边缩进，保证市、区展开后不与上级左侧对齐 */
    .addr-row.level-0 .addr-cell-name {
      padding-left: 0;
    }
    .addr-row.level-1 .addr-cell-name {
      padding-left: 20px;
    }
    .addr-row.level-2 .addr-cell-name {
      padding-left: 40px;
    }
    .addr-row.level-3 .addr-cell-name {
      padding-left: 60px;
    }

    .addr-toggle {
      cursor: pointer;
      font-size: 14px;
      user-select: none;
      display: inline-block;
      transform: translateY(-1px);
    }

    .addr-children {
      display: none;
    }

    .addr-children.open {
      display: block;
    }

    .butaoin {
      height: 16px!important;
      min-width: auto!important;
      margin: 0 2px!important;
    }

    .import-tools {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-left: auto;
    }
  </style>
</head>

<body>
  <div class="header lr10">
    <?php echo $this->headerment(); ?>
    <span class="span_fenge lr5"></span>
    <!-- <div class="import-tools"> -->
      <div class="exportBtn" style="padding:0;margin-top:4px;">
        <input type="button" value='<?php $this->lan("import","导入模版下载"); ?>' name="excel_submit" style="float: right;margin-top: 5px;margin-right: 5px;background: rgba(0, 0, 0, 0) !important;height: 16px !important;min-width: auto;" onclick="javascript:window.location.href='<?php echo WEB_PATH . '/' . G_ADMIN_DIR; ?>/wap/set_addrchild_import_down'">
      </div>
      <div class="exportBtn" style="margin-top:4px;background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);padding: 0 20px;line-height: 32px;font-size: 14px;" onclick="window.location.href = '<?php echo G_ADMIN_PATH; ?>/import/index/1'"><?php $this->lan("collection","采集"); ?></div>
    <!-- </div> -->
  </div>
  <div class="bk10"></div>

  <div class="table-list lr10">
    <div class="page-container">
      <!-- 国家模块 -->
      <div class="country-panel">
        <div class="country-title"><?php $this->lan("country","国家"); ?></div>
        <div class="country-list">
          <?php if(!empty($areas)){ ?>
            <?php foreach($areas as $area){ ?>
              <a
                class="country-item <?php echo isset($current_id) && $current_id == $area['id'] ? 'active' : ''; ?>"
                href="javascript:;"
                data-id="<?php echo $area['id']; ?>"
              >
                <?php echo $area['name']; ?>
              </a>
            <?php } ?>
          <?php } else { ?>
            <span style="font-size:12px;color:#999;"><?php $this->lan("no_country_data","暂无国家数据"); ?></span>
          <?php } ?>
        </div>
      </div>

      <!-- 省 / 市 / 区 模块（市、区默认折叠） -->
      <div class="addr-panel" style="margin-top: 10px;">
        <div class="addr-title">
          <?php $this->lan("province_city_area","省 / 市 / 区（市、区默认折叠）"); ?>
          <?php if(!empty($current_area)){ ?>
            <span style="font-size:12px;color:#999;margin-left:8px;">
              (<?php $this->lan("current_country","当前国家"); ?>：<?php echo $current_area['name']; ?>)
            </span>
          <?php } ?>
        </div>

        <?php if(!empty($lists)){ ?>
          <div class="addr-list">
            <div class="addr-header">
              <div class="addr-cell-toggle"></div>
              <div class="addr-cell-id"><?php $this->lan("ID","ID");?></div>
              <div class="addr-cell-name"><?php $this->lan("Province","省/市");?></div>
              <div class="addr-cell-code"><?php $this->lan("code","代码");?></div>
              <div class="addr-cell-desc"><?php $this->lan("remarks","备注");?></div>
              <div class="addr-cell-op"><?php $this->lan("operation","操作");?></div>
            </div>

            <ul class="addr-root">
              <?php foreach($lists as $v){ ?>
                <li class="addr-item">
                  <div class="addr-row level-0" data-id="<?php echo $v['id']; ?>" data-level="0">
                    <div class="addr-cell-toggle">
                      <span class="addr-toggle" data-id="<?php echo $v['id']; ?>">▶</span>
                    </div>
                    <div class="addr-cell-id"><?php echo $v['id']; ?></div>
                    <div class="addr-cell-name">
                      <!-- 定位 SVG 图标 -->
                      <span class="addr-icon-location" aria-hidden="true" style="margin-right:4px;display:inline-flex;align-items:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M12 2.75C8.824 2.75 6.25 5.324 6.25 8.5C6.25 11.418 8.92 15.145 11.063 17.77C11.56 18.379 12.44 18.379 12.937 17.77C15.08 15.145 17.75 11.418 17.75 8.5C17.75 5.324 15.176 2.75 12 2.75Z" stroke="#666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M12 10.25C10.895 10.25 10 9.355 10 8.25C10 7.145 10.895 6.25 12 6.25C13.105 6.25 14 7.145 14 8.25C14 9.355 13.105 10.25 12 10.25Z" stroke="#666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      </span>
                      <span class="addr-name-text"><?php echo $v['name']; ?></span>
                    </div>
                    <div class="addr-cell-code"><?php echo $v['code']; ?></div>
                    <div class="addr-cell-desc"><?php echo $v['desc']; ?></div>
                    <div class="addr-cell-op">
                      <a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/set_addrchild_add/<?php echo $v['id'];?>"><?php $this->lan("add_pack_value",'添加下级');?></a>
                      <a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/set_addrchild_edit/<?php echo $v['id'];?>"><?php $this->lan("modify","修改");?></a>
                      <a class="butaoin butaish" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/set_addrchild_del/<?php echo $v['id'];?>" onClick="return confirm('是否真的删除！');"><?php $this->lan("delete","删除");?></a>
                    </div>
                  </div>
                  <div class="addr-children" data-parent="<?php echo $v['id']; ?>"></div>
                </li>
              <?php } ?>
            </ul>
          </div>
        <?php } else { ?>
          <div style="font-size:12px;color:#999;margin-top:10px;">
            <?php $this->lan("no_addr_data","当前国家暂未配置省 / 市 / 区数据"); ?>
          </div>
        <?php } ?>
      </div>
    </div>

    <div class="import-data" style="display: none;">
      <form action="none" method="post" enctype="multipart/form-data">
          <input type="file" name="file" id="file" value="" />
          <button type="button " class="btn btn-primary import-submit" ><?php $this->lan("submit")?></button>
      </form>
    </div>
  </div><!--table-list end-->

  <script>
    // 国家切换：点击国家按钮刷新到对应国家
    $(".country-list").on("click",".country-item",function(e){
      e.preventDefault();
      var id = $(this).attr("data-id");
      if(!id){ return; }
      window.location.href = "<?php echo G_WEB_PATH .'/'. G_ADMIN_DIR; ?>/wap/set_addr?id="+id;
    });

    // 导入模板上传
    var importUrl = "";
    $(".import").click(function(e){
      e.preventDefault();
      $(".import-data").show();
      importUrl = $(this).attr("href");
    });
    $(".import-submit").click(function(){
      var formData = new FormData();
      var fileInput = $('#file')[0];
      var file = fileInput.files[0];
      formData.append('file', file);
      $.ajax({
        url: importUrl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          $(".import-data").hide();
          layer.msg('导入成功', {icon: 1, time: 2000}, function(){
            window.location.reload();
          });
        },
        error: function() {
          $(".import-data").hide();
          layer.msg('文件上传失败',{icon:2});
        }
      });
    });

    // 省 / 市 / 区 级联展开（市、区默认折叠）
    var levelNames = ["<?php $this->lan("Province","省/市");?>","<?php $this->lan("District","区/县");?>","<?php $this->lan("Street","街道/乡镇");?>","<?php $this->lan("Community","村/社区");?>"];

    $(".addr-list").on("click",".addr-row",function(e){
      // 避免点击操作按钮触发展开
      if($(e.target).is("a") || $(e.target).closest("a").length){
        return;
      }
      var row   = $(this);
      // 已标记为叶子节点的行，直接不处理
      if(row.hasClass("addr-leaf")){
        return;
      }
      var id    = row.attr("data-id");
      var level = parseInt(row.attr("data-level"),10) || 0;
      var toggle = row.find(".addr-toggle");
      var childrenBox = row.next(".addr-children[data-parent='"+id+"']");

      if(childrenBox.hasClass("open")){
        childrenBox.removeClass("open").slideUp(120);
        toggle.text("▶");
        return;
      }

      $.ajax({
        url: "<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/get_addrchild/" + id,
        type: "post",
        data: {},
        success: function(data){
          var datas = JSON.parse(data);
          var lists = datas.data || [];
          if(lists.length === 0){
            // 没有下级时，当前行视为最下一级（市或区等），彻底移除箭头并标记为叶子节点
            toggle.closest(".addr-cell-toggle").empty();
            row.addClass("addr-leaf");
            return;
          }
          var nextLevel = level + 1;
          var html = '<ul>';
          for(var i=0;i<lists.length;i++){
            var el = lists[i];
            html += '<li>' +
                      '<div class="addr-row level-'+nextLevel+'" data-id="'+el.id+'" data-level="'+nextLevel+'">' +
                        '<div class="addr-cell-toggle"><span class="addr-toggle" data-id="'+el.id+'">▶</span></div>' +
                        '<div class="addr-cell-id">'+el.id+'</div>' +
                        '<div class="addr-cell-name">' +
                          '<span class="addr-icon-location" aria-hidden="true" style="margin-right:4px;display:inline-flex;align-items:center;">' +
                            '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                              '<path d="M12 2.75C8.824 2.75 6.25 5.324 6.25 8.5C6.25 11.418 8.92 15.145 11.063 17.77C11.56 18.379 12.44 18.379 12.937 17.77C15.08 15.145 17.75 11.418 17.75 8.5C17.75 5.324 15.176 2.75 12 2.75Z" stroke="#666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>' +
                              '<path d="M12 10.25C10.895 10.25 10 9.355 10 8.25C10 7.145 10.895 6.25 12 6.25C13.105 6.25 14 7.145 14 8.25C14 9.355 13.105 10.25 12 10.25Z" stroke="#666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>' +
                            '</svg>' +
                          '</span>' +
                          '<span class="addr-name-text">'+el.name+'</span>' +
                        '</div>' +
                        '<div class="addr-cell-code">'+(el.code || "")+'</div>' +
                        '<div class="addr-cell-desc">'+(el.desc || "")+'</div>' +
                        '<div class="addr-cell-op">' +
                          '<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/set_addrchild_add/'+el.id+'"><?php $this->lan("add_pack_value","添加下级");?></a>' +
                          '<a class="butaoin" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/set_addrchild_edit/'+el.id+'"><?php $this->lan("modify","修改");?></a>' +
                          '<a class="butaoin butaish" href="<?php echo WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/wap/set_addrchild_del/'+el.id+'" onClick="return confirm(\'是否真的删除！\');"><?php $this->lan("delete","删除");?></a>' +
                        '</div>' +
                      '</div>' +
                      '<div class="addr-children" data-parent="'+el.id+'"></div>' +
                    '</li>';
          }
          html += '</ul>';
          childrenBox.html(html).addClass("open").slideDown(120);
          toggle.text("▼");
        }
      });
    });
  </script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>