<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="Shortcut Icon" type="image/x-icon" href="/favicon.ico">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/index.css" type="text/css">
<!-- 后台体验优化样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin-enhancements.css" type="text/css">
<!-- 按钮和表单优化样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/form-button-enhancements.css" type="text/css">
<!-- 高级表单功能样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-form-features.css" type="text/css">
<!-- 骨架屏预加载样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/skeleton-loader.css" type="text/css">
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
<!-- 后台各页面详细UI升级样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/layer/layer.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/global.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
<!-- 后台体验优化脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin-enhancements.js"></script>
<!-- 按钮和表单优化脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/form-button-enhancements.js"></script>
<!-- 高级表单功能脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-form-features.js"></script>
<!-- 骨架屏预加载脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/skeleton-loader.js"></script>
<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载，以便重写layer方法） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
<title><?php echo _cfg("web_name");?>管理后台</title>
<script type="text/javascript">
var ready=1;
var kj_width;
var kj_height;
var header_height=100;
// var header_height=70;
var R_label;
var R_label_one = "<?php $this->lan("current_location");$this->lan("System_settings");?>>";

function left(init){
  var left = document.getElementById("left");
  if (!left) return;
  
  // 先隐藏所有二级菜单
  var leftlist = left.getElementsByTagName("ul");
  for (var k=0; k<leftlist.length; k++){
    leftlist[k].style.display="none";
  }
  
  // 检查是否已经恢复了URL，如果已经恢复则不执行默认行为（不自动加载第一个子菜单）
  if (window._urlRestored === true) {
    // URL已经恢复，只显示对应的菜单组，不自动加载第一个子菜单
    var parentElement = document.getElementById(init);
    if (parentElement) {
      parentElement.style.display="block";
    }
    return;
  }
  
  // 显示对应的菜单组
  var parentElement = document.getElementById(init);
  if (parentElement) {
    parentElement.style.display="block";
  }
  
  // 自动加载第一个子菜单（第二个li，第一个是head）
  var secondChild = parentElement.querySelector('li:nth-child(2)');
  if(secondChild){
    var a = secondChild.querySelector("a");
    var src = $(a).attr("src");
    var currentMenuName = $(a).text(); // 当前菜单项名称（已包含多语言）
    $("#iframe_src").attr("src", src);
    // 保存当前URL和菜单文本
    if (typeof saveCurrentUrl === 'function') {
      // 使用R_label_one（已在secBoard中设置）加上当前菜单项文本
      var menuText = R_label_one + ' ' + currentMenuName + ' >';
      saveCurrentUrl(src, menuText);
      // 同时更新R_label显示
      if (R_label) {
        R_label.text(menuText);
      }
      // 更新页面标题为：网站名称 + 当前菜单名称
      var webName = "<?php echo _cfg('web_name'); ?>";
      document.title = (webName ? webName : '') + (currentMenuName ? ' - ' + currentMenuName : '');
    }
  }
  lefttextnowrop(init,0)
}

function secBoard(elementID,n,init,r_lable) {
  // 用户主动点击菜单，重置URL恢复标志，执行完整的菜单切换逻辑
  window._urlRestored = false;
  
  var elem = document.getElementById(elementID);
  var elemlist = elem.getElementsByTagName("li");
  for (var i=0; i<elemlist.length; i++) {
    elemlist[i].className = "normal";   
  }
  elemlist[n].className = "current";
  R_label_one="<?php $this->lan('current_location'); ?>"+r_lable+" >";
  R_label.text(R_label_one);
  // 更新页面标题为：网站名称 + 顶级菜单名称
  var webName = "<?php echo _cfg('web_name'); ?>";
  document.title = (webName ? webName : '') + (r_lable ? ' - ' + r_lable : '');
  left(init);
  // 注意：left函数内部已经保存了URL和菜单文本，这里不需要重复保存
  // 但需要确保R_label_one已更新，以便left函数内部使用正确的顶级菜单名称
}

function set_div(){
    kj_width=$(window).width();
    kj_height=$(window).height();
    if(kj_width<1000){kj_width=1000;}
    if(kj_height<500){kj_height=500;}
    $("#header").css('width',kj_width); 
    $("#header").css('height',header_height);
    $("#left").css('height',kj_height-header_height); 
    $("#right").css('height',kj_height-header_height); 
    $("#left").css('top',header_height); 
    $("#right").css('top',header_height);
    $("#left").css('width',180);    
    $("#right").css('width',kj_width-180); 
    $("#right").css('left',180);
    $("#right_iframe").css('width',kj_width-206); 
    $("#right_iframe").css('height',kj_height-148);
    $("#iframe_src").css('width',kj_width-208); 
    $("#iframe_src").css('height',kj_height-150);
    $("#off_on").css('height',kj_height-180);
    var nav=$("#nav");    
    nav.css("left",(kj_width-nav.get(0).offsetWidth)/2);
    // nav.css("left",181);
    nav.css("top",61);
    // nav.css("top",31);
}

$(document).ready(function(){ 
    set_div();    
    $("#off_on").click(function(){
        if($(this).attr('val')=='open'){
          $(this).attr('val','exit');
          $("#right").css('width',kj_width);
          $("#right").css('left',1);
          $("#right_iframe").css('width',kj_width-25); 
          $("iframe").css('width',kj_width-27);
        }else{
          $(this).attr('val','open');
          $("#right").css('width',kj_width-182);
          $("#right").css('left',182);
          $("#right_iframe").css('width',kj_width-206); 
          $("iframe").css('width',kj_width-208);
        }
    });
    
    // 保存当前页面URL和菜单路径到localStorage
    function saveCurrentUrl(url, menuText) {
        if (url && url !== '') {
            try {
                localStorage.setItem('admin_current_url', url);
                if (menuText) {
                    localStorage.setItem('admin_current_menu_text', menuText);
                }
            } catch(e) {
                console.error('保存URL失败:', e);
            }
        }
    }

    // 获取保存的URL
    function getSavedUrl() {
        try {
            return localStorage.getItem('admin_current_url');
        } catch(e) {
            console.error('获取URL失败:', e);
            return null;
        }
    }

    // 获取保存的菜单文本
    function getSavedMenuText() {
        try {
            return localStorage.getItem('admin_current_menu_text');
        } catch(e) {
            console.error('获取菜单文本失败:', e);
            return null;
        }
    }

    // 恢复保存的URL或使用默认URL
    function restoreUrl() {
        var savedUrl = getSavedUrl();
        var defaultUrl = "<?php echo G_MODULE_PATH; ?>/index/Tdefault";
        var url = savedUrl || defaultUrl;
        // 显示骨架屏
        var iframeContainer = $('#iframe_src').parent();
        if(iframeContainer.length) {
            if(typeof showIframeSkeleton === 'function') {
                showIframeSkeleton(iframeContainer[0]);
            }
        }
        $("#iframe_src").attr("src", url);
        return url;
    }

    // 恢复菜单路径文案
    function restoreMenuText() {
        var savedMenuText = getSavedMenuText();
        if (savedMenuText && R_label && savedMenuText !== '') {
            R_label.text(savedMenuText);
            return true; // 成功恢复
        }
        return false; // 没有保存的文案
    }
    
    // 初始化R_label
    R_label=$("#R_label");
    
    // 恢复菜单文本（在R_label初始化后）
    var menuTextRestored = restoreMenuText();
    // 恢复URL（立即执行，确保在left()函数之前设置）
    var savedUrl = getSavedUrl();
    var restoredUrl = null;
    
    // 优先检查是否有保存的URL
    if (savedUrl && savedUrl !== '' && savedUrl !== "<?php echo G_MODULE_PATH; ?>/index/Tdefault") {
        // 有保存的URL，使用保存的URL
        restoredUrl = savedUrl;
        // 显示骨架屏
        var iframeContainer = $('#iframe_src').parent();
        if(iframeContainer.length) {
            if(typeof showIframeSkeleton === 'function') {
                showIframeSkeleton(iframeContainer[0]);
            }
        }
        // 立即设置iframe的src，防止left()函数覆盖
        $("#iframe_src").attr("src", restoredUrl);
        
        // 标记已经设置了URL，防止left()函数覆盖
        window._urlRestored = true;
    } else {
        // 如果没有保存的URL，标记为未恢复，允许left()函数执行默认行为
        window._urlRestored = false;
        restoredUrl = "<?php echo G_MODULE_PATH; ?>/index/Tdefault";
    }
    
    // 根据恢复的URL设置对应的菜单项为选中状态
    if (restoredUrl && restoredUrl !== "<?php echo G_MODULE_PATH; ?>/index/Tdefault") {
        // 延迟执行，确保菜单已经渲染完成
        setTimeout(function() {
            var foundMenu = false;
            // 查找匹配的菜单项 - 使用更精确的匹配
            $(".left_date a").each(function() {
                var menuSrc = $(this).attr("src");
                if (menuSrc) {
                    // 精确匹配或包含匹配
                    var urlMatch = false;
                    if (restoredUrl === menuSrc) {
                        urlMatch = true;
                    } else if (restoredUrl.indexOf(menuSrc) !== -1) {
                        urlMatch = true;
                    } else if (menuSrc.indexOf(restoredUrl) !== -1) {
                        urlMatch = true;
                    }
                    
                    if (urlMatch) {
                        foundMenu = true;
                        $(this).parent().addClass("set");
                        $(".left_date li").removeClass("set");
                        $(this).parent().addClass("set");
                        // 显示对应的菜单组
                        var menuGroup = $(this).closest("ul").attr("id");
                        if (menuGroup) {
                            $(".left_date").hide();
                            $("#" + menuGroup).show();
                        }
                        // 如果菜单文本没有成功恢复，则根据当前菜单项生成
                        if (!menuTextRestored && R_label) {
                            // 查找对应的顶级菜单
                            var topMenuName = '<?php $this->lan("System_settings");?>';
                            if (menuGroup) {
                                // 根据菜单组ID查找对应的顶级菜单
                                $('#nav li a').each(function() {
                                    var onClick = $(this).attr('onClick');
                                    if (onClick && onClick.indexOf("'"+menuGroup+"'") !== -1) {
                                        topMenuName = $(this).text();
                                        return false; // 跳出循环
                                    }
                                });
                            }
                            var currentMenuName = $(this).text(); // 当前菜单项名称（已包含多语言）
                            var menuText = "<?php $this->lan('current_location'); ?>" + topMenuName + " > " + currentMenuName + " >";
                            R_label.text(menuText);
                            // 更新页面标题为：网站名称 + 当前菜单名称
                            var webName = "<?php echo _cfg('web_name'); ?>";
                            document.title = (webName ? webName : '') + (currentMenuName ? ' - ' + currentMenuName : '');
                            // 保存生成的文案
                            saveCurrentUrl(restoredUrl, menuText);
                        }
                        return false; // 跳出循环
                    }
                }
            });
            // 如果没有找到匹配的菜单项，且菜单文本没有恢复，使用默认文案
            if (!foundMenu && !menuTextRestored && R_label) {
                var defaultMenuText = "<?php $this->lan('current_location'); ?><?php $this->lan('System_settings');?> > <?php $this->lan('homepage'); ?> >";
                R_label.text(defaultMenuText);
                // 更新页面标题为默认标题
                var webName = "<?php echo _cfg('web_name'); ?>";
                var homepageText = '<?php $this->lan("homepage"); ?>';
                document.title = (webName ? webName : '') + ' - ' + homepageText;
            }
        }, 200);
    }
    
    //left('setting');
    $(".left_date a").click(function(){
        $(".left_date li").removeClass("set");              
        $(this).parent().addClass("set");
        // 查找对应的顶级菜单名称
        var menuGroup = $(this).closest("ul").attr("id");
        var topMenuName = '<?php $this->lan("System_settings");?>';
        if (menuGroup) {
            // 根据菜单组ID查找对应的顶级菜单
            $('#nav li a').each(function() {
                var onClick = $(this).attr('onClick');
                if (onClick && onClick.indexOf("'"+menuGroup+"'") !== -1) {
                    topMenuName = $(this).text();
                    return false; // 跳出循环
                }
            });
        }
        var currentMenuName = $(this).text(); // 当前菜单项名称（已包含多语言）
        var menuText = "<?php $this->lan('current_location'); ?>" + topMenuName + " > " + currentMenuName + " >";
        R_label.text(menuText);
        var src = $(this).attr("src");
        // 显示骨架屏
        var iframeContainer = $('#iframe_src').parent();
        if(iframeContainer.length) {
            if(typeof showIframeSkeleton === 'function') {
                showIframeSkeleton(iframeContainer[0]);
            }
        }
        $("#iframe_src").attr("src", src);
        saveCurrentUrl(src, menuText); // 保存当前URL和菜单文本
        // 更新页面标题为：网站名称 + 当前菜单名称
        var webName = "<?php echo _cfg('web_name'); ?>";
        document.title = (webName ? webName : '') + (currentMenuName ? ' - ' + currentMenuName : '');
    });
    
    // 监听iframe加载完成事件，确保URL被正确保存
    $('#iframe_src').on('load', function() {
        var currentSrc = $(this).attr('src');
        if (currentSrc && currentSrc !== '') {
            // 延迟保存，确保iframe完全加载
            setTimeout(function() {
                var currentMenuText = R_label ? R_label.text() : '';
                if (currentMenuText && currentMenuText !== '') {
                    saveCurrentUrl(currentSrc, currentMenuText);
                } else {
                    // 如果没有菜单文本，至少保存URL
                    saveCurrentUrl(currentSrc, '');
                }
            }, 100);
        }
    });
    $(".header_case").on("click","a",function(){
      var src = $(this).attr("src");
      // 显示骨架屏
      var iframeContainer = $('#iframe_src').parent();
      if(iframeContainer.length) {
          if(typeof showIframeSkeleton === 'function') {
              showIframeSkeleton(iframeContainer[0]);
          }
      }
      $("#iframe_src").attr("src", src);
      // 更新页面标题（通知标题）
      var webName = "<?php echo _cfg('web_name'); ?>";
      var noticeText = $(this).text();
      document.title = (webName ? webName : '') + (noticeText ? ' - ' + noticeText : '');
      // 保存URL（通知URL）
      saveCurrentUrl(src, "<?php $this->lan('current_location'); ?>" + noticeText + " >");
    })
    // $(".header_case a").click(function(){
    //     $("#iframe_src").attr("src",$(this).attr("src"));
    // });
    $('body').bind('contextmenu',function(){return false;});
    $('body').bind("selectstart",function(){return false;});
});

function api_off_on_open(key){
	if(key=='open'){
		$("#off_on").attr('val','exit');
        $("#right").css('width',kj_width);
        $("#right").css('left',1);
        $("#right_iframe").css('width',kj_width-25); 
        $("iframe").css('width',kj_width-27);
  }else{
		$("#off_on").attr('val','open');
		$("#right").css('width',kj_width-182);
		$("#right").css('left',182);
		$("#right_iframe").css('width',kj_width-206); 
		$("iframe").css('width',kj_width-208);
  }
}
</script>
<style>
.header_case{  position:absolute; right: 10px; top:70px; color:#fff}
.header_case a{ padding-left:5px}
.header_case a:hover{ color:#fff; }
/* 左侧菜单栏：浅色风格，与后台主色 #3C8DBC 统一，毛玻璃点缀 */
#left {
  background: linear-gradient(180deg, #f5f7fa 0%, #eef2f7 100%);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border-right: 1px solid #dfe4eb;
  box-shadow: 2px 0 12px rgba(60, 141, 188, 0.06);
}
/* 左侧菜单栏文字样式：与后台整体风格统一 */
.left_date {
  overflow: hidden;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "PingFang SC", "Microsoft YaHei", sans-serif;
  font-size: 13px;
  -webkit-font-smoothing: antialiased;
}
.left_date a {
  color: #2c3e50;
  font-weight: 500;
  letter-spacing: 0.02em;
  text-decoration: none;
}
/* 左侧菜单箭头：内联 ">" 形状 SVG，与菜单名同色、同变（hover/选中） */
.left_date li span.left-menu-arrow {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: 35px;
  margin-left: 15px;
  width: 14px;
  vertical-align: top;
  color: #2c3e50;
  flex-shrink: 0;
  transition: color 0.2s ease;
}
.left_date li span.left-menu-arrow .left-menu-arrow-svg {
  display: block;
  width: 12px;
  height: 12px;
}
.left_date li:hover span.left-menu-arrow,
.left_date .set span.left-menu-arrow {
  color: #3c8dbc;
}
.left_date ul { margin: 0; padding: 0; }
.left_date li {
  line-height: 35px;
  height: 35px;
  overflow: hidden;
  transition: background 0.2s ease, color 0.2s ease;
}
.left_date li a {
  display: inline-block;
  text-indent: 1em;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  max-width: 100%;
}
.left_date li:hover {
  background-color: rgba(60, 141, 188, 0.1);
  border-left: 3px solid #3c8dbc;
  color: #3c8dbc;
  height: 34px;
  cursor: pointer;
  text-decoration: none;
}
.left_date li:hover a { color: #3c8dbc; }
.left_date .set {
  background-color: rgba(60, 141, 188, 0.14);
  font-weight: 600;
  color: #3c8dbc;
  border-left: 3px solid #3c8dbc;
}
.left_date .set a { color: #3c8dbc; font-weight: 600; }
.left_date .head {
  border-bottom: 1px solid #d0d9e2;
  color: #34495e;
  font-weight: 600;
  font-size: 12px;
  letter-spacing: 0.04em;
  text-transform: none;
  background: #e4eaf0;
  padding-left: 15px;
  height: 34px;
}

.iconfont{
  display: inline-block;
}

.remind-button{
  position: relative;
}

.notice-count{
  display: flex;
  position: absolute;
  top: -8px;
  left: 18px;
}

/*.icon-remind::before{*/
/*  content: "通知";*/
/*}*/
.notice-count{
  color: red;
}

/* 未读消息数徽章样式优化 */
.icon-a a p .badge-count {
  display: inline-block;
  position: absolute;
  top: -8px;
  right: -8px;
  min-width: 18px;
  height: 18px;
  line-height: 18px;
  padding: 0 6px;
  background: linear-gradient(135deg, #ff4757 0%, #ff3838 100%);
  color: #fff;
  font-size: 11px;
  font-weight: bold;
  text-align: center;
  border-radius: 9px;
  box-shadow: 0 2px 6px rgba(255, 71, 87, 0.4);
  transform: scale(0.9);
  transition: all 0.3s ease;
  z-index: 10;
  white-space: nowrap;
}

.icon-a a p:hover .badge-count {
  transform: scale(1);
  box-shadow: 0 3px 8px rgba(255, 71, 87, 0.6);
}
.icon-a{
  /*display: none;*/
    position: fixed;
    top: 18px;
    right: 50%;
    transform: translateX(51%);
    width: auto;
    text-align: center;
    display: flex !important;
    align-items: center !important;
    flex-wrap: nowrap !important;
    white-space: nowrap !important;
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
    flex-direction: row !important;
}
.icon-a a{
    display: inline-block !important;
    margin-right: 10px !important;
    vertical-align: middle !important;
    flex-shrink: 0 !important;
}
.icon-a p{
    display: inline-block;
    width: auto;
    height: 30px;
    text-align: center;
    background: #fff;
    margin-right: 0;
    color: #000;
    line-height: 30px;
    border-radius: 5px;
    cursor: pointer;
    padding: 0px 10px;
    position: relative;
}
.icon-a p:hover{
    background: rgb(207, 235, 195);
}

.iconfont:hover .icon-a{
  display: block;
}
.music_1{
float: left;
margin-left: 45%;
margin-top: 7px;
color: red;
font-size: 13px;
transform: translateX(-50%);
width: max-content;
}
.lacass {
    border: 0;background: #3c8dbc;color: #000 !important;
    height: 30px !important;
    line-height: 30px !important;
    padding: 0 30px 0 35px !important;
    font-size: 13px !important;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
.lacass option {
    background: #fff;
    color: #333 !important;
}
.lacass option:checked {
    background: #3C8DBC !important;
    color: #000 !important;
}
.lacass:focus {
    color: #000 !important;
}
/* 多语言切换按钮的国旗图标 */
.language-flag-container {
    position: relative;
    display: inline-block;
}
#language_flag_icon {
    position: absolute;
    left: 8px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 15px;
    pointer-events: none;
    z-index: 1;
    display: block;
}
/* 自定义下拉框样式 */
.custom-language-select {
    position: relative;
    display: inline-block;
    z-index: 2147483647 !important;
}
.custom-select-selected {
    background-color: #3c8dbc;
    color: #000;
    padding: 0 28px 0 32px;
    height: 30px;
    line-height: 30px;
    font-size: 13px;
    cursor: pointer;
    border: none;
    display: flex;
    align-items: center;
    position: relative;
    border-radius: 4px;
    transition: background-color 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 1px 4px rgba(0,0,0,0.12), 0 2px 6px rgba(0,0,0,0.08);
}
.custom-select-selected:hover {
    background-color: #357ca5;
    box-shadow: 0 2px 8px rgba(0,0,0,0.18), 0 4px 12px rgba(0,0,0,0.12);
}
.custom-select-selected .flag-icon {
    position: absolute;
    left: 6px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 15px;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    visibility: visible !important;
    opacity: 1 !important;
    flex-shrink: 0;
}
.custom-select-selected .flag-icon svg {
    width: 20px;
    height: 15px;
    display: block;
}
.custom-select-selected .lang-name {
    flex: 1;
    color: #000;
    padding-left: 12px;
    white-space: nowrap;
    text-align: left;
}
.custom-select-selected .custom-select-arrow {
    position: absolute;
    right: 8px;
    color: #000;
    font-size: 10px;
    transition: transform 0.2s ease;
}
.custom-select-options.show ~ .custom-select-selected .custom-select-arrow {
    transform: rotate(180deg);
}
.custom-select-options {
    display: none;
    position: fixed !important;
    background-color: #fff;
    min-width: 160px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15), 0 2px 4px rgba(0,0,0,0.1);
    z-index: 2147483647 !important; /* 使用最大z-index值，确保在所有元素之上 */
    max-height: 300px;
    overflow-y: auto;
    overflow-x: hidden;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    margin-top: 4px;
    padding: 4px 0;
}
/* 美化滚动条 */
.custom-select-options::-webkit-scrollbar {
    width: 6px;
}
.custom-select-options::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}
.custom-select-options::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}
.custom-select-options::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
.custom-language-select {
    position: relative;
    display: inline-block;
    z-index: 999999 !important;
}
.custom-select-options.show {
    display: block;
}
.custom-select-option {
    padding: 8px 28px 8px 32px;
    cursor: pointer;
    display: flex;
    align-items: center;
    color: #333;
    position: relative;
    transition: all 0.2s ease;
    min-height: 34px;
    margin: 2px 4px;
    border-radius: 4px;
}
.custom-select-option:hover {
    background-color: #f5f5f5;
}
.custom-select-option.selected {
    background-color: #3C8DBC;
    color: #fff;
    font-weight: 500;
}
.custom-select-option.selected:hover {
    background-color: #357ca5;
}
.custom-select-option .flag-icon {
    position: absolute;
    left: 6px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 15px;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    visibility: visible !important;
    opacity: 1 !important;
    flex-shrink: 0;
}
.custom-select-option .flag-icon svg {
    width: 20px;
    height: 15px;
    display: block;
}
.custom-select-option .lang-name {
    flex: 1;
    padding-left: 12px;
    white-space: nowrap;
    text-align: left;
}
#posileft {position:absolute;left:0;position: absolute;left: 180px;top: 0;background-color: #fff;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border: 1px solid #3C8DBC;z-index: 10;padding: 7px 10px 8px;border-left: 0;display:none;}
#header .logo {background: none;}
</style>
</head>
<body onResize="set_div();">
<div id="header" style="z-index: 99;">
  <div class="logo"><img style="height: 60px;margin: 20px 0 0 20px;" src="<?php echo $logo;?>"></div>
  <div class="header_case">
    <!-- 通知 -->
    <div class="iconfont">
      <ul class="icon-a">
        <?php
          foreach($notice['data']?:[] as $vl){
            echo "<a src='$vl[url]' style='padding-left:0px'><p>$vl[name]</p></a>";
          }
        ?>
        <!-- 铃声开关按钮 - 放在所有通知按钮后面，同一行 -->
        <a href="javascript:void(0);" id="bellToggleBtn" style="padding-left:0px; display: inline-block; margin-right: 10px;">
          <p class="bell-toggle-p" style="display: inline-block; width: auto; height: 30px; text-align: center; background: <?php echo (isset($info['bell_enabled']) && $info['bell_enabled'] == 1) ? '#4CAF50' : '#ccc'; ?>; color: #fff; margin-right: 0; line-height: 30px; border-radius: 5px; cursor: pointer; padding: 0px 10px; position: relative; transition: all 0.3s ease;">
            <span class="bell-icon">🔔</span>
            <span class="bell-text"><?php echo (isset($info['bell_enabled']) && $info['bell_enabled'] == 1) ? '铃声已开启' : '铃声已关闭'; ?></span>
          </p>
        </a>
      </ul>
    </div>
    
    <div class="custom-language-select" style="position: absolute;top: -48px;right: 0;">
        <?php 
          // 获取当前选中语言的国旗图标和名称
          $currentFlag = '';
          $currentLangName = '';
          foreach($languageBackend as $vl){
            if($vl['lg_value'] == $lan){
              $currentFlag = isset($vl['flag_url']) ? $vl['flag_url'] : '';
              $currentLangName = isset($vl['lg_name']) ? $vl['lg_name'] : '';
              break;
            }
          }
          // 如果没有找到，使用第一个语言的国旗
          if(empty($currentFlag) && count($languageBackend) > 0){
            $currentFlag = isset($languageBackend[0]['flag_url']) ? $languageBackend[0]['flag_url'] : '';
            $currentLangName = isset($languageBackend[0]['lg_name']) ? $languageBackend[0]['lg_name'] : '';
          }
        ?>
        <div class="custom-select-selected" id="languageSelectSelected">
            <?php 
              // 获取当前选中语言的国旗SVG或URL
              $currentFlagSvg = '';
              foreach($languageBackend as $vl){
                if($vl['lg_value'] == $lan){
                  $currentFlagSvg = isset($vl['flag_svg']) ? $vl['flag_svg'] : '';
                  $currentFlag = isset($vl['flag_url']) ? $vl['flag_url'] : '';
                  break;
                }
              }
              // 如果没有找到，使用第一个语言的国旗
              if(empty($currentFlagSvg) && empty($currentFlag) && count($languageBackend) > 0){
                $currentFlagSvg = isset($languageBackend[0]['flag_svg']) ? $languageBackend[0]['flag_svg'] : '';
                $currentFlag = isset($languageBackend[0]['flag_url']) ? $languageBackend[0]['flag_url'] : '';
              }
              
              if(!empty($currentFlagSvg)): 
                // 使用内联SVG
                echo '<span class="flag-icon">' . $currentFlagSvg . '</span>';
              elseif(!empty($currentFlag)): 
                // 使用图片URL
                echo '<img src="'.htmlspecialchars($currentFlag).'" class="flag-icon" alt="flag" onerror="this.style.display=\'none\';" />';
              else: 
                // 默认占位符
                echo '<span class="flag-icon" style="background: #ccc; border-radius: 2px;"></span>';
              endif; 
            ?>
            <span class="lang-name"><?php echo htmlspecialchars($currentLangName); ?></span>
            <span class="custom-select-arrow">▼</span>
        </div>
        <div class="custom-select-options" id="languageSelectOptions">
            <?php 
              foreach($languageBackend as $vl){
                $isSelected = ($vl['lg_value'] == $lan) ? 'selected' : '';
                $flagUrl = isset($vl['flag_url']) ? $vl['flag_url'] : '';
                $langName = isset($vl['lg_name']) ? $vl['lg_name'] : '';
                $flagSvg = isset($vl['flag_svg']) ? $vl['flag_svg'] : '';
                echo '<div class="custom-select-option '.$isSelected.'" data-value="'.htmlspecialchars($vl['lg_value'], ENT_QUOTES).'" data-flag="'.htmlspecialchars($flagUrl, ENT_QUOTES).'" data-flag-svg="'.htmlspecialchars($flagSvg, ENT_QUOTES).'">';
                if(!empty($flagSvg)){
                  // 使用内联SVG
                  echo '<span class="flag-icon" style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); width: 20px; height: 15px; display: inline-block; vertical-align: middle; z-index: 2;">' . $flagSvg . '</span>';
                } elseif(!empty($flagUrl)){
                  // 使用图片URL
                  echo '<img src="'.htmlspecialchars($flagUrl).'" class="flag-icon" alt="flag" style="display: block !important; visibility: visible !important; opacity: 1 !important; position: absolute; left: 8px; top: 50%; transform: translateY(-50%); width: 20px; height: 15px; object-fit: contain; z-index: 2;" onerror="this.style.display=\'none\';" />';
                } else {
                  // 默认占位符
                  echo '<span class="flag-icon" style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); width: 20px; height: 15px; background: #ccc; display: block; z-index: 2;"></span>';
                }
                echo '<span class="lang-name">'.htmlspecialchars($langName).'</span>';
                echo '</div>';
              } 
            ?>
        </div>
        <!-- 隐藏的select，用于表单提交 -->
        <select class="lacass" id="language_select" style="display: none;">
            <?php 
              $selected = '';
              foreach($languageBackend as $vl){
                if($vl['lg_value'] == $lan){$selected = "selected";}else{
                  $selected = '';
                }
                echo "<option value='{$vl['lg_value']}' $selected>{$vl['lg_name']}</option>";
              } 
            ?>
        </select>
    </div>
  <a href="javascript:;" style="color:#fff;">
      <?php echo $info['name']; ?>&nbsp;&nbsp;<?php echo $str;?>
  </a>
  <a style="color:#fff; cursor: pointer;" href="javascript:void(0);" id="logoutBtn" title="退出"><?php $this->lan('exit');?></a>
  <!--<a href="javascript:;" src="<?php echo G_MODULE_PATH; ?>/cache/init"><?php $this->lan('Genuine_version') ?></a>-->
    <a href="javascript:;" style="color:#fff;<?php echo $backend_copyright==2?"display:none;":"";?>" src="<?php echo G_MODULE_PATH; ?>/index/support" title="支持功能定制"><?php $this->lan("Technical_support");?></a>
    <a style="color:#fff;padding-left: 0;<?php echo $backend_copyright==2?"display:none;":"";?>" href="https://copy.taolong.com/submit.php?domain=<?php echo $domian;?>" target="_blank"><?php $this->lan("Authorized");?></a>
  <a style="color:#fff;" href="<?php echo G_WEB_PATH; ?>" target="_blank" title="前往本站首页"><?php $this->lan('Visit_homepage') ?></a>
  <a style="color:#fff;" href="<?php echo rtrim(_cfg('web_path'),'/'); ?>/index/pages/backstage/login/login" target="_blank" title="前往手机版后台"><?php $this->lan("Mobile_version_backend");?></a>
  </div>
    <a href="javascript:;" ></a>
  <div class="nav" id="nav">
    <ul>
    <?php
      $data=$this->db->GetList("select * from `@#_ments` where 1 order by `sort` desc, `id` asc");
//      p($data);exit;
      if($data){
         $i=0;
        foreach ($data as $key => $value) {
          $lg_name = safe_unserialize($value['language_name']);
          $data[$key]["name"] = $value['name'];
          if($lg_name && isset($lg_name[$this->language_backend])){
            $value["name"] = $lg_name[$this->language_backend]?:$value['name'];
            $data[$key]["name"] = $lg_name[$this->language_backend]?:$value['name'];
          }
        if(in_array($value['id'], $p_arr) || $p_data['permission'] == 'ALL'){
          if($value['pid'] == 0 && $value['is_enable'] == 1){
            if($i==0){
              echo '<script>$(document).ready(function(){  left(\''.$value['d'].'\') });</script>';
              echo '<li class="current"><a href="#" onClick="secBoard(\'nav\',\''.$i.'\',\''.$value['d'].'\',\''.$value['name'].'\');">'.$value['name'].'</a></li>';
            }else{
               echo '<li class="normal"><a href="#" onClick="secBoard(\'nav\',\''.$i.'\',\''.$value['d'].'\',\''.$value['name'].'\');">'.$value['name'].'</a></li>';
            }
            $i++;
          
            }
          }
        }
    ?>
    </ul>
  </div>
</div>
<?php
$left_arrow_svg = '<svg class="left-menu-arrow-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12" width="12" height="12" aria-hidden="true"><path d="M4 2.5 L9 6 L4 9.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<div id="posileft"></div>
<div id="left">
  <!--系统设置 -->
  <ul class="left_date" id="setting">
    <?php
     foreach ($data as $k => $v) {
      if(in_array($v['id'], $p_arr) || $p_data['permission'] == 'ALL'){
        if($v['d'] == 'head' && $v['pid'] == '1' && $v['is_enable'] == '1'){
          echo '<li class="head">'.$v['name'].'</li>';
           $nextdata=$this->db->GetList("select * from `@#_ments` where pid='".$v['id']."' AND is_enable =1 AND status = 1 order by `sort` desc, `id` asc");
//           var_dump("select * from `@#_ments` where pid='".$v['id']."' AND is_enable =1 AND status = 1 order by id asc");exit;
          foreach ($nextdata as $key => $value) {
            $lg_name = safe_unserialize($value['language_name']);
            if($lg_name && isset($lg_name[$this->language_backend])){
              $value["name"] = $lg_name[$this->language_backend]?:$value['name'];
              // $data[$key]["name"] = $lg_name[$this->language_backend]?:$value['name'];
            }
            // var_export($lg_name[$this->language_backend]);
            if(in_array($value['id'], $p_arr) || $p_data['permission'] == 'ALL'){
             if($value['a']){
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.WEB_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }else{
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.G_MODULE_PATH.($value['d']!="#"?$value['d']:"/index/developing").'">'.$value['name'].'</a></li>';
                }
            }
          }
        }
      }
      }
    ?>
  </ul>
  <!--内容管理 -->
  <ul class="left_date" id="content">
    <?php
     foreach ($data as $k => $v) {
      if(in_array($v['id'], $p_arr) || $p_data['permission'] == 'ALL'){
        if($v['d'] == 'head' && $v['pid'] == '2' && $v['is_enable'] == '1'){
          echo '<li class="head">'.$v['name'].'</li>';
           $nextdata=$this->db->GetList("select * from `@#_ments` where pid='".$v['id']."' AND is_enable =1 AND status = 1 order by `sort` desc, `id` asc");
          foreach ($nextdata as $key => $value) {
            $lg_name = safe_unserialize($value['language_name']);
            if($lg_name && isset($lg_name[$this->language_backend])){
              $value["name"] = $lg_name[$this->language_backend]?:$value['name'];
              // $data[$key]["name"] = $lg_name[$this->language_backend]?:$value['name'];
            }
            if(in_array($value['id'], $p_arr) || $p_data['permission'] == 'ALL'){
              if($value['a']){
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.WEB_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }else{
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.G_MODULE_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }
            }
          }
        }
      }
      }
    ?>  
  </ul>
  <!--商品管理 -->
  <ul class="left_date" id="shop">
    <?php
     foreach ($data as $k => $v) {
      if(in_array($v['id'], $p_arr) || $p_data['permission'] == 'ALL'){
        if($v['d'] == 'head' && $v['pid'] == '3' && $v['is_enable'] == '1'){
          echo '<li class="head">'.$v['name'].'</li>';
           $nextdata=$this->db->GetList("select * from `@#_ments` where pid='".$v['id']."' AND is_enable =1 AND status = 1 order by `sort` desc, `id` asc");
          foreach ($nextdata as $key => $value) {
            $lg_name = safe_unserialize($value['language_name']);
            if($lg_name && isset($lg_name[$this->language_backend])){
              $value["name"] = $lg_name[$this->language_backend]?:$value['name'];
              // $data[$key]["name"] = $lg_name[$this->language_backend]?:$value['name'];
            }
            if(in_array($value['id'], $p_arr) || $p_data['permission'] == 'ALL'){
              if($value['a']){
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.WEB_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }else{
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.G_MODULE_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }
            }
          }
        }
      }
      }
    ?>
  </ul>
  <!--用户管理 -->
  <ul class="left_date" id="user">
    <?php
     foreach ($data as $k => $v) {
      if(in_array($v['id'], $p_arr) || $p_data['permission'] == 'ALL'){
        if($v['d'] == 'head' && $v['pid'] == '4' && $v['is_enable'] == '1'){
          echo '<li class="head">'.$v['name'].'</li>';
           $nextdata=$this->db->GetList("select * from `@#_ments` where pid='".$v['id']."' AND is_enable =1 AND status = 1 and level < 3 order by `sort` desc, `id` asc");
          foreach ($nextdata as $key => $value) {
            $lg_name = safe_unserialize($value['language_name']);
            if($lg_name && isset($lg_name[$this->language_backend])){
              $value["name"] = $lg_name[$this->language_backend]?:$value['name'];
              // $data[$key]["name"] = $lg_name[$this->language_backend]?:$value['name'];
            }
            if(in_array($value['id'], $p_arr) || $p_data['permission'] == 'ALL'){
              if($value['a']){
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.WEB_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }else{
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.G_MODULE_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }
            }
          }
        }
      }
      }
    ?>
  </ul>
  <!--财务管理 -->
  <ul class="left_date" id="finance">
    <?php
     foreach ($data as $k => $v) {
      if(in_array($v['id'], $p_arr) || $p_data['permission'] == 'ALL'){
        if($v['d'] == 'head' && $v['pid'] == '1017' && $v['is_enable'] == '1'){
          echo '<li class="head">'.$v['name'].'</li>';
           $nextdata=$this->db->GetList("select * from `@#_ments` where pid='".$v['id']."' AND is_enable =1 AND status = 1 order by `sort` desc, `id` asc");
          foreach ($nextdata as $key => $value) {
            $lg_name = safe_unserialize($value['language_name']);
            if($lg_name && isset($lg_name[$this->language_backend])){
              $value["name"] = $lg_name[$this->language_backend]?:$value['name'];
              // $data[$key]["name"] = $lg_name[$this->language_backend]?:$value['name'];
            }
            if(in_array($value['id'], $p_arr) || $p_data['permission'] == 'ALL'){
              if($value['a']){
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.WEB_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }else{
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.G_MODULE_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }
            }
          }
        }
      }
      }
    ?>
  </ul>
  <!--界面管理 -->
  <ul class="left_date" id="template">
    <?php
     foreach ($data as $k => $v) {
      if(in_array($v['id'], $p_arr) || $p_data['permission'] == 'ALL'){
        if($v['d'] == 'head' && $v['pid'] == '5' && $v['is_enable'] == '1'){
          echo '<li class="head">'.$v['name'].'</li>';
           $nextdata=$this->db->GetList("select * from `@#_ments` where pid='".$v['id']."' AND is_enable =1 AND status = 1 order by id asc");
          foreach ($nextdata as $key => $value) {
            $lg_name = safe_unserialize($value['language_name']);
            if($lg_name && isset($lg_name[$this->language_backend])){
              $value["name"] = $lg_name[$this->language_backend]?:$value['name'];
              // $data[$key]["name"] = $lg_name[$this->language_backend]?:$value['name'];
            }
            if(in_array($value['id'], $p_arr) || $p_data['permission'] == 'ALL'){
             if($value['a']){
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.WEB_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }else{
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.G_MODULE_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }
            }
          }
        }
      }
      }
    ?>
  <!--云应用管理 -->
  </ul>
  <ul class="left_date" id="yunapp">
    <?php
     foreach ($data as $k => $v) {
      if(in_array($v['id'], $p_arr) || $p_data['permission'] == 'ALL'){
        if($v['d'] == 'head' && $v['pid'] == '66' && $v['is_enable'] == '1'){
          echo '<li class="head">'.$v['name'].'</li>';
           $nextdata=$this->db->GetList("select * from `@#_ments` where pid='".$v['id']."' AND is_enable =1 AND status = 1 order by id asc");
          foreach ($nextdata as $key => $value) {
            $lg_name = safe_unserialize($value['language_name']);
            if($lg_name && isset($lg_name[$this->language_backend])){
              $value["name"] = $lg_name[$this->language_backend]?:$value['name'];
              // $data[$key]["name"] = $lg_name[$this->language_backend]?:$value['name'];
            }
            if(in_array($value['id'], $p_arr) || $p_data['permission'] == 'ALL'){
             if($value['a']){
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.WEB_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }else{
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.G_MODULE_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }
            }
          }
        }
      }
      }
    ?>
  </ul>
  <!--微信管理 -->
  <ul class="left_date" id="wechat">
    <?php
     foreach ($data as $k => $v) {
      if(in_array($v['id'], $p_arr) || $p_data['permission'] == 'ALL'){
        if($v['d'] == 'head' && $v['pid'] == '67' && $v['is_enable'] == '1'){
          echo '<li class="head">'.$v['name'].'</li>';
           $nextdata=$this->db->GetList("select * from `@#_ments` where pid='".$v['id']."' AND is_enable =1 AND status = 1 order by id asc");
          foreach ($nextdata as $key => $value) {
            $lg_name = safe_unserialize($value['language_name']);
            if($lg_name && isset($lg_name[$this->language_backend])){
              $value["name"] = $lg_name[$this->language_backend]?:$value['name'];
              // $data[$key]["name"] = $lg_name[$this->language_backend]?:$value['name'];
            }
            if(in_array($value['id'], $p_arr) || $p_data['permission'] == 'ALL'){
              if($value['a']){
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.WEB_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }else{
                  echo ' <li><span class="left-menu-arrow">'.$left_arrow_svg.'</span><a href="javascript:void(0);" src="'.G_MODULE_PATH.$value['d'].'">'.$value['name'].'</a></li>';
                }
            }
          }
        }
      }
      }
    ?>
   </ul>
  <?php
    }
  ?>
</div>
<!--left end-->
<div id="right">
  <div class="right_top">
    <ul class="R_label" id="R_label">
      <?php $this->lan("current_location") ?><?php $this->lan("System_settings") ?> >  <?php $this->lan("homepage") ?> >
    </ul>
  </div>
  <div id="right_iframe" style="z-index: 1; position: relative;top: 0;">
    <iframe id="iframe_src" name="iframe" class="iframe"
         frameborder="no" border="1" marginwidth="0" marginheight="0" 
         src="" 
         scrolling="auto" allowtransparency="yes" style="width:100%; height:100%; z-index: 1; position: relative;"> </iframe>
  </div>
</div>
<!--right end-->
</body>
<script>
function lefttextnowrop(dwid,tnum) {
    $('#'+dwid+' li.head').map(function(){
        var headtext = $(this).text()
        $(this).html(headtext.slice(0,20))
        var heoffset = $(this).offset().top
        if (headtext.length > 20) {
            $(this).hover(function(){
                $('#posileft').text(headtext)
                $('#posileft').show()
                $('#posileft').css('top',heoffset+tnum)
            },function(){
                $('#posileft').hide()
            })
        }
    })
    $('#'+dwid+'.left_date li a').map(function(){
        var liatext = $(this).text()
        $(this).text(liatext.slice(0,17))
        var loffset = $(this).offset().top
        if (liatext.length > 17) {
            $(this).parent().hover(function(){
                $('#posileft').text(liatext)
                $('#posileft').show()
                $('#posileft').css('top',loffset+tnum)
            },function(){
                $('#posileft').hide()
            })
        }
    })
}
lefttextnowrop('setting',100)
function lafuncass(){
    var option = $("#language_select option:selected").val();
    $.post("<?php echo G_MODULE_PATH."/index/changeLan";?>",{lg_value:option},function(res){
      res =JSON.parse(res)
      if(res.code == 200){
        location.reload()
      }
    })
}
// 页面加载时初始化自定义下拉框
$(document).ready(function(){
  // 调试：输出语言数据
  console.log('语言数据:', <?php echo json_encode($languageBackend); ?>);
  console.log('当前语言:', '<?php echo $lan; ?>');
  console.log('当前国旗:', '<?php echo htmlspecialchars($currentFlag); ?>');
  
  // 点击选中区域，显示/隐藏下拉选项
  $("#languageSelectSelected").on('click', function(e){
    e.stopPropagation();
    e.preventDefault();
    var isShowing = $("#languageSelectOptions").hasClass('show');
    if(isShowing){
      $("#languageSelectOptions").removeClass('show');
    } else {
      // 计算下拉框的位置，使用fixed定位
      var $container = $(".custom-language-select");
      var $options = $("#languageSelectOptions");
      var containerOffset = $container.offset();
      var containerHeight = $container.outerHeight();
      var containerWidth = $container.outerWidth();
      
      // 使用fixed定位，相对于视口
      $options.css({
        'position': 'fixed',
        'top': (containerOffset.top + containerHeight) + 'px',
        'right': ($(window).width() - containerOffset.left - containerWidth) + 'px'
      });
      
      $options.addClass('show');
    }
  });
  
  // 点击选项 - 使用事件委托
  $(document).on('click', '.custom-select-option', function(e){
    e.stopPropagation();
    e.preventDefault();
    var value = $(this).data('value');
    var flagUrl = $(this).data('flag');
    var flagSvg = $(this).data('flag-svg');
    var langName = $(this).find('.lang-name').text();
    
    console.log('选择语言:', value, '国旗URL:', flagUrl, '国旗SVG:', flagSvg ? '存在' : '不存在');
    
    // 更新选中状态
    $(".custom-select-option").removeClass('selected');
    $(this).addClass('selected');
    
    // 更新显示区域
    var $selected = $("#languageSelectSelected");
    
    // 移除旧的图标
    $selected.find('.flag-icon').remove();
    
    if(flagSvg){
      // 使用内联SVG
      $selected.prepend('<span class="flag-icon">' + flagSvg + '</span>');
    } else if(flagUrl){
      // 使用图片URL
      $selected.prepend('<img src="'+flagUrl+'" class="flag-icon" alt="flag" onerror="this.style.display=\'none\';" />');
    } else {
      // 默认占位符
      $selected.prepend('<span class="flag-icon" style="background: #ccc; border-radius: 2px;"></span>');
    }
    $selected.find('.lang-name').text(langName);
    
    // 更新隐藏的select
    $("#language_select").val(value);
    
    // 隐藏下拉选项
    $("#languageSelectOptions").removeClass('show');
    
    // 调用切换语言函数
    lafuncass();
  });
  
  // 点击页面其他地方，隐藏下拉选项
  $(document).on('click', function(e){
    if(!$(e.target).closest('.custom-language-select').length){
      $("#languageSelectOptions").removeClass('show');
    }
  });
});
  $().ready(function(){
    var audio = new Audio('<?php echo G_PLUGIN_PATH; ?>/music/beauty_tip.wav');
    // 初始化铃声状态
    var bellEnabled = <?php echo (isset($info['bell_enabled']) && $info['bell_enabled'] == 1) ? 'true' : 'false'; ?>;
    audio.muted = !bellEnabled;
    
    // 使用事件委托，确保即使按钮被重新创建也能响应点击
    $(document).on('click', '#bellToggleBtn', function(e){
      e.preventDefault();
      e.stopPropagation();
      console.log('铃声按钮被点击，当前状态:', bellEnabled);
      bellEnabled = !bellEnabled;
      audio.muted = !bellEnabled;
      console.log('切换后状态:', bellEnabled);
      
      var $p = $(this).find('p');
      // 更新按钮样式和文字
      if(bellEnabled){
        $p.css('background', '#4CAF50');
        $p.find('.bell-text').text('铃声已开启');
      } else {
        $p.css('background', '#ccc');
        $p.find('.bell-text').text('铃声已关闭');
      }
      
      // 保存到数据库
      $.post("<?php echo G_MODULE_PATH."/index/toggleBell";?>", {
        bell_enabled: bellEnabled ? 1 : 0
      }, function(res){
        try {
          res = typeof res === 'string' ? JSON.parse(res) : res;
          console.log('服务器响应:', res);
          // 检查code是否为200（数字）或'200'（字符串）
          if(res.code == 200 || res.code === '200'){
            console.log('铃声状态保存成功:', bellEnabled ? '开启' : '关闭');
          } else {
            // 如果保存失败，恢复状态
            console.error('保存失败，响应code:', res.code, '响应内容:', res);
            bellEnabled = !bellEnabled;
            audio.muted = !bellEnabled;
            var $p = $('#bellToggleBtn').find('p');
            if(bellEnabled){
              $p.css('background', '#4CAF50').find('.bell-text').text('铃声已开启');
            } else {
              $p.css('background', '#ccc').find('.bell-text').text('铃声已关闭');
            }
          }
        } catch(e) {
          console.error('保存铃声状态失败:', e, '响应内容:', res);
          // 解析失败，恢复状态
          bellEnabled = !bellEnabled;
          audio.muted = !bellEnabled;
          var $p = $('#bellToggleBtn').find('p');
          if(bellEnabled){
            $p.css('background', '#4CAF50').find('.bell-text').text('铃声已开启');
          } else {
            $p.css('background', '#ccc').find('.bell-text').text('铃声已关闭');
          }
        }
      }).fail(function(xhr, status, error){
        console.error('网络错误:', error);
        // 网络错误，恢复状态
        bellEnabled = !bellEnabled;
        audio.muted = !bellEnabled;
        var $p = $('#bellToggleBtn').find('p');
        if(bellEnabled){
          $p.css('background', '#4CAF50').find('.bell-text').text('铃声已开启');
        } else {
          $p.css('background', '#ccc').find('.bell-text').text('铃声已关闭');
        }
      });
    });
    
    document.addEventListener('mousedown',function(){
      if(bellEnabled){
        audio.muted = false
      }
    })
    document.getElementById('iframe_src').onload = function() {
      var iframeDocument = this.contentDocument || this.contentWindow.document;
      iframeDocument.addEventListener('mousedown', function() {
        if(bellEnabled){
          audio.muted = false
        }
      });
    };
    function notice(){
      $.get("<?php echo G_MODULE_PATH."/index/getNotice";?>",function(res){
        res =JSON.parse(res)
        if(res.code==200){
          $(".notice-count").html(res.count)
          var html = ''
          res.data.forEach(element => {
            // 只有当未读消息数 > 0 时才显示徽章
            var badgeHtml = '';
            if(element.count && parseInt(element.count) > 0) {
              // 如果数量大于99，显示99+
              var countText = parseInt(element.count) > 99 ? '99+' : element.count;
              badgeHtml = '<span class="badge-count">' + countText + '</span>';
            }
            html += '<a src='+element.url+' style="padding-left:0px; display: inline-block; margin-right: 10px;"><p>'+element.name + badgeHtml + '</p></a>';
          });
          // 先保存当前铃声状态
          var currentBellState = bellEnabled;
          $(".icon-a").html(html)
          // 在更新后重新添加铃声按钮，确保它在最后，与其他按钮在同一行
          var bellHtml = '<a href="javascript:void(0);" id="bellToggleBtn" style="padding-left:0px; display: inline-block; margin-right: 10px;"><p class="bell-toggle-p" style="display: inline-block; width: auto; height: 30px; text-align: center; background: ' + (currentBellState ? '#4CAF50' : '#ccc') + '; color: #fff; margin-right: 0; line-height: 30px; border-radius: 5px; cursor: pointer; padding: 0px 10px; position: relative; transition: all 0.3s ease;"><span class="bell-icon">🔔</span><span class="bell-text">' + (currentBellState ? '铃声已开启' : '铃声已关闭') + '</span></p></a>';
          $(".icon-a").append(bellHtml);
          console.log('铃声按钮已重新添加，状态:', currentBellState);
          if(res.is_new && bellEnabled){
            //播放声音
            console.log("播放声音...")
            setTimeout(function() {
              audio.play();
            }, 700);
          }
        }
      })
    }
    notice();
    setInterval(notice, 30000);
  })
  
  // 退出功能 - 使用通知系统替代弹窗
  $(document).ready(function() {
    $('#logoutBtn').on('click', function(e) {
      e.preventDefault();
      
      // 使用 AJAX 方式退出
      $.ajax({
        url: '<?php echo G_MODULE_PATH; ?>/user/out',
        type: 'POST',
        dataType: 'json',
        data: {
          ajax: true
        },
        success: function(response) {
          // 显示退出成功通知
          if (typeof NotificationSystem !== 'undefined' && NotificationSystem.success) {
            NotificationSystem.success('退出成功', {
              duration: 1500,
              onClose: function() {
                // 延迟跳转到登录页面
                setTimeout(function() {
                  window.location.href = '<?php echo G_MODULE_PATH; ?>/user/login';
                }, 100);
              }
            });
          } else if (typeof layer !== 'undefined' && typeof layer.msg === 'function') {
            // 降级到 layer.msg
            layer.msg('退出成功', {
              icon: 1,
              time: 1500
            }, function() {
              window.location.href = '<?php echo G_MODULE_PATH; ?>/user/login';
            });
          } else {
            // 最后降级方案
            alert('退出成功');
            window.location.href = '<?php echo G_MODULE_PATH; ?>/user/login';
          }
        },
        error: function() {
          // 即使请求失败，也尝试跳转到登录页面
          if (typeof NotificationSystem !== 'undefined' && NotificationSystem.error) {
            NotificationSystem.error('退出失败，正在跳转...', {
              duration: 1000,
              onClose: function() {
                window.location.href = '<?php echo G_MODULE_PATH; ?>/user/login';
              }
            });
          } else {
            window.location.href = '<?php echo G_MODULE_PATH; ?>/user/login';
          }
        }
      });
    });
  });
</script>
</html>