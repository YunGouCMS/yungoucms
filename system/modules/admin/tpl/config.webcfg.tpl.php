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
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/admin.css" type="text/css">
	<!-- 后台各页面详细UI升级样式 -->
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/backend-pages-ui.css" type="text/css">
	<!-- 通知消息系统样式 -->
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
	<!--<script src="--><?php //echo G_GLOBAL_STYLE; 
						?><!--/global/layui/css/layui.css"></script>-->
	<!--script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script-->
	<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/upload.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
	<!-- 表单提交处理脚本 -->
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/form.js"></script>
	<!-- 通知消息系统脚本（必须在layer之后加载） -->
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
	<style>
		tr {
			height: 40px;
			line-height: 40px
		}

		.rightgray {
			display: flex;
			align-items: center;
		}

		.rightgray div {
			/*width: 5px;*/
			text-align: center;
			background-color: #fff !important;
			border: none !important;
			border-left-width: 0px;
			height: auto;
			padding: 0;
			border-radius: 0;
			line-height: 38px;
		}

		.rightgray input {
			margin: 0;
			border-top-right-radius: 0;
			border-bottom-right-radius: 0;
			width: 42px;
			min-width: 65px;
		}

		.biqoqian {
			display: flex;
			align-items: center;
			color: #777;
			border: 1px solid #e2e2e2;
			width: max-content;
			border-radius: 2px;
			padding: 7px;
			min-width: 500px;
		}

		.biqoqian.shuruon {
			border-color: #007cba;
			box-shadow: 0 0 0 .5px #007cba;
			outline: 2px solid #0000;
		}

		.biqoqian .sjiap {
			display: inline-block;
			height: 30px !important;
			display: flex !important;
			align-items: center !important;
		}

		.biqoqian .sinop {
			border-radius: 5px;
			background-color: #fff;
			padding-left: 5px;
			height: 25px;
			line-height: 25px;
			display: flex;
			align-items: center;
			margin-right: 7px;
			background: #dddddd;
		}

		.sjiap input {
			height: 30px !important;
			width: 90px;
			border: 0;
			line-height: 30px !important;
			background: rgba(0, 0, 0, 0);
			font-size: 12px;
		}

		.biqoqian .sinop input {
			border: 0;
			width: max-content;
			width: 50px;
			background: #dddddd;

		}

		.biqoqian .sinop span {
			font-size: 15px;
			padding: 0 10px;
			cursor: pointer;
		}

		.biqoqian .sinop div {
			color: #fff;
		}

		.resouci {
			display: flex;
			margin-bottom: 10px;
		}

		.resouci:last-child {
			margin-bottom: 0;
		}

		table {
			border: none !important;
			/* 去除整个表格的边框 */
			border-collapse: collapse !important;
			/* 防止表格单元格之间出现重复的边框 */
		}

		td,
		th {
			border: none !important;
			/* 去除表格单元格和表头的边框 */
		}
		
		/* 美化全局设置页面 - 优化对齐和排版 */
		.table-list {
			background: #fff;
			border-radius: 12px;
			padding: 30px 40px;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		}
		
		.table-list table {
			width: 100%;
			border-collapse: separate;
			border-spacing: 0;
		}
		
		.table-list table tr {
			transition: background-color 0.2s ease;
			border-bottom: 1px solid #f3f4f6;
		}
		
		.table-list table tr:last-child {
			border-bottom: none;
		}
		
		.table-list table tr:hover {
			background-color: transparent;
		}
		
		/* 统一表格列宽度和对齐 */
		.table-list table td {
			padding: 18px 15px;
			vertical-align: middle;
		}
		
		/* 标签列统一样式 - 白色背景 */
		.table-list table td[align="right"] {
			width: 200px !important;
			min-width: 200px !important;
			font-weight: 600;
			color: #374151;
			background: #fff !important;
			border-right: none !important; /* 强制移除蓝色竖线 */
			padding-right: 25px !important;
			text-align: right !important;
			white-space: nowrap;
		}
		
		/* 输入框列统一宽度 - 居左排列，无前方间隙 */
		.table-list table td:not([align="right"]) {
			width: auto;
			text-align: left !important;
			padding-left: 0 !important;
		}
		
		/* 统一输入框样式和宽度 - 高度50px，居左排列，无前方间隙 */
		.input-text, .wid200, .wid300, input[type="text"]:not(.rightgray input):not(.resouci input):not(input[name="margin_bottom"]):not(input[name="margin_top"]):not(input[name="proportion"]):not(input[name="goods_end_time"]):not(input[name="admindir"]):not(input[name="order_number_prefix_dp"]):not(input[name="web_name"]):not(input[name="web_path"]):not(input[name="web_xxczts"]):not(input[name="web_copyright"]):not(input[name="wx"]):not(input[name="domain_k"]):not(input[name="web_key"]) {
			border: 2px solid #e5e7eb !important;
			border-radius: 8px !important;
			padding: 0 16px !important;
			height: 50px !important;
			line-height: 50px !important;
			transition: all 0.3s ease !important;
			font-size: 14px !important;
			width: 100% !important;
			max-width: 400px !important;
			box-sizing: border-box !important;
			text-align: left !important;
			margin-left: 0 !important;
			margin-right: 0 !important;
			padding-left: 16px !important;
		}
		
		/* 特定输入框样式 - 优先级最高，覆盖上面的通用样式 */
		input[name="margin_bottom"][style*="width:200px"],
		input[name="margin_top"][style*="width:200px"],
		input[name="proportion"][style*="width: 200px"],
		input[name="goods_end_time"][style*="width:200px"],
		input[name="admindir"][style*="width:200px"],
		input[name="order_number_prefix_dp"][style*="width:200px"] {
			width: 200px !important;
			max-width: 200px !important;
			min-width: 200px !important;
			text-align: center !important;
		}
		
		.wid200 {
			max-width: 250px !important;
		}
		
		.wid300 {
			max-width: 400px !important;
		}
		
		/* 特殊宽度的输入框 */
		input[name="admindir"] {
			max-width: 500px !important;
		}
		
		input[name="web_des"], input[name="banner"], input[name="web_copyright"] {
			max-width: 500px !important;
		}
		
		/* 覆盖内联样式中的高度设置，统一为50px */
		input[style*="height:30px"]:not(.sjiap input), input[style*="height: 30px"]:not(.sjiap input) {
			height: 50px !important;
			line-height: 50px !important;
			padding: 0 16px !important;
		}
		
		.input-text:focus, .wid200:focus, .wid300:focus, input[type="text"]:focus:not(.rightgray input) {
			border-color: #667eea !important;
			box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
			outline: none !important;
		}
		
		/* 提交按钮样式 - 高度50px，文字完全垂直居中 */
		.button {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
			border: none !important;
			border-radius: 10px !important;
			padding: 0 40px !important;
			height: 50px !important;
			color: #fff !important;
			font-size: 16px !important;
			font-weight: 600 !important;
			cursor: pointer !important;
			transition: all 0.3s ease !important;
			box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
			margin-top: 20px;
			display: inline-flex !important;
			align-items: center !important;
			justify-content: center !important;
			vertical-align: middle !important;
			min-width: 120px;
			text-align: center !important;
			line-height: 1 !important;
		}
		
		/* 确保input按钮文字垂直居中 */
		input.button[type="submit"] {
			display: inline-flex !important;
			align-items: center !important;
			justify-content: center !important;
			line-height: 1 !important;
			padding: 0 40px !important;
		}
		
		.button:hover {
			transform: translateY(-2px) !important;
			box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4) !important;
		}
		
		.button:active {
			transform: translateY(0) !important;
		}
		
		/* 右侧输入框组 - 白色背景，高度50px，内部输入框38px，居左排列 */
		.rightgray {
			background: #fff !important;
			border: 2px solid #e5e7eb !important;
			border-radius: 8px !important;
			padding: 0 14px !important;
		}
		
		/* 前端语言模块的.rightgray容器 - 无外边框 */
		td.rightgray:has(select[name="module"]) {
			border: none !important;
			box-shadow: none !important;
			background: transparent !important;
			padding: 0 !important;
		}
		
		/* 兼容性：如果浏览器不支持:has()，使用更具体的选择器 */
		td.rightgray select[name="module"] {
			border: none !important;
		}
		
		.rightgray:not(:has(select[name="module"])) {
			height: 50px !important;
			display: inline-flex !important;
			align-items: center !important;
			gap: 8px !important;
			width: auto !important;
			max-width: 200px !important;
			margin-left: 0 !important;
			text-align: left !important;
		}
		
		/* 内部输入框高度38px */
		.rightgray input {
			border: none !important;
			background: transparent !important;
			padding: 0 !important;
			height: 38px !important;
			line-height: 38px !important;
			width: auto !important;
			max-width: 80px !important;
			text-align: center !important;
			font-weight: 600 !important;
		}
		
		.rightgray div {
			background: #fff !important;
			border: none !important;
			color: #6b7280 !important;
			font-weight: 500 !important;
			padding: 0 !important;
			margin: 0 !important;
			line-height: 38px !important;
		}
		
		/* 倒计时分钟数模块样式优化 - 无外框 */
		.countdown-time {
			display: inline-flex !important;
			align-items: center !important;
			gap: 6px !important;
		}
		
		.countdown-time input {
			width: 50px !important;
			min-width: 50px !important;
			max-width: 50px !important;
			flex-shrink: 0 !important;
		}
		
		.countdown-time .time-unit {
			color: #6b7280 !important;
			font-size: 14px !important;
			font-weight: 500 !important;
			white-space: nowrap !important;
			flex-shrink: 0 !important;
		}
		
		/* 间距和比例输入框样式 - 无外框，宽度200px，居中 */
		table input[name="margin_bottom"],
		table input[name="margin_top"],
		table input[name="proportion"],
		table input[name="goods_end_time"],
		table input[name="admindir"],
		table input[name="order_number_prefix_dp"],
		input.input-text[name="margin_bottom"],
		input.input-text[name="margin_top"],
		input.input-text[name="proportion"],
		input.input-text[name="goods_end_time"],
		input.input-text[name="admindir"],
		input.input-text[name="order_number_prefix_dp"] {
			width: 200px !important;
			max-width: 200px !important;
			min-width: 200px !important;
			text-align: center !important;
			border: 2px solid #e5e7eb !important;
			border-radius: 8px !important;
			padding: 0 16px !important;
			height: 50px !important;
			line-height: 50px !important;
		}
		
		/* 前端语言模块下拉框 - 无外边框 */
		select[name="module"] {
			border: none !important;
			background: transparent !important;
			box-shadow: none !important;
			outline: none !important;
			-webkit-appearance: none !important;
			-moz-appearance: none !important;
			appearance: none !important;
		}
		
		select[name="module"]:focus {
			border: none !important;
			box-shadow: none !important;
			outline: none !important;
		}
		
		select[name="module"]:hover {
			border: none !important;
			box-shadow: none !important;
		}
		
		/* 确保前端语言模块下拉框容器也没有边框 */
		td.rightgray:has(select[name="module"]),
		.rightgray:has(select[name="module"]) {
			border: none !important;
			box-shadow: none !important;
			background: transparent !important;
		}
		
		/* 兼容性：如果浏览器不支持:has()，使用更具体的选择器 */
		td.rightgray select[name="module"] {
			border: none !important;
		}
		
		/* 搜索标签输入框 */
		.biqoqian {
			border: 2px solid #e5e7eb !important;
			border-radius: 8px !important;
			transition: all 0.3s ease !important;
			min-width: 300px !important;
			display: flex !important;
			align-items: center !important;
			padding: 6px 12px !important;
			height: auto !important;
		}
		
		.biqoqian.shuruon {
			border-color: #667eea !important;
			box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
		}
		
		.biqoqian .sinop {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
			color: #fff !important;
			border-radius: 6px !important;
			padding: 6px 12px !important;
			margin-right: 8px !important;
		}
		
		.biqoqian .sinop span {
			cursor: pointer !important;
			transition: transform 0.2s ease !important;
		}
		
		.biqoqian .sinop span:hover {
			transform: scale(1.2) !important;
		}
		
		/* 下拉选择框 - 高度50px，居左排列，无前方间隙 */
		select {
			border: 2px solid #e5e7eb !important;
			border-radius: 8px !important;
			padding: 0 16px !important;
			height: 50px !important;
			line-height: 50px !important;
			transition: all 0.3s ease !important;
			font-size: 14px !important;
			width: 100% !important;
			max-width: 200px !important;
			box-sizing: border-box !important;
			text-align: left !important;
			margin-left: 0 !important;
			margin-right: 0 !important;
			padding-left: 16px !important;
		}
		
		select:focus {
			border-color: #667eea !important;
			box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
			outline: none !important;
		}
		
		/* 开奖方式下拉框样式 */
		select[name="winning_method"] {
			width: 200px !important;
			max-width: 200px !important;
			min-width: 200px !important;
			height: 50px !important;
			padding: 0 16px 0 45px !important;
			border: 2px solid #e5e7eb !important;
			border-radius: 8px !important;
			font-size: 14px !important;
			background-color: #fff !important;
			cursor: pointer !important;
			appearance: none !important;
			-webkit-appearance: none !important;
			-moz-appearance: none !important;
		}
		
		select[name="winning_method"]:focus {
			border-color: #667eea !important;
			box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
		}
		
		/* 后端默认语言自定义下拉框样式 */
		.custom-language-select-backend {
			position: relative;
			display: inline-block;
			width: 200px;
			z-index: 1000;
		}
		
		.custom-select-selected-backend {
			background-color: #fff;
			border: 2px solid #e5e7eb;
			color: #333;
			padding: 0 28px 0 32px;
			height: 50px;
			line-height: 30px;
			font-size: 13px;
			cursor: pointer;
			display: flex;
			align-items: center;
			position: relative;
			border-radius: 4px;
			transition: background-color 0.2s ease;
		}
		
		.custom-select-selected-backend:hover {
			background-color: #f5f5f5;
		}
		
		.custom-select-selected-backend .flag-icon {
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
		
		.custom-select-selected-backend .flag-icon svg {
			width: 20px;
			height: 15px;
			display: block;
		}
		
		.custom-select-selected-backend .flag-icon img {
			width: 20px;
			height: 15px;
			display: block;
		}
		
		.custom-select-selected-backend .lang-name {
			flex: 1;
			color: #333;
			padding-left: 12px;
			white-space: nowrap;
			text-align: left;
		}
		
		.custom-select-selected-backend .custom-select-arrow {
			position: absolute;
			right: 8px;
			color: #666;
			font-size: 10px;
			transition: transform 0.2s ease;
		}
		
		.custom-select-options-backend {
			display: none;
			position: absolute;
			top: 100%;
			left: 0;
			right: 0;
			background-color: #fff;
			min-width: 200px;
			box-shadow: 0 4px 12px rgba(0,0,0,0.15), 0 2px 4px rgba(0,0,0,0.1);
			z-index: 1001;
			max-height: 300px;
			overflow-y: auto;
			overflow-x: hidden;
			border: 1px solid #e0e0e0;
			border-radius: 6px;
			margin-top: 4px;
			padding: 4px 0;
		}
		
		.custom-select-options-backend.show {
			display: block;
		}
		
		.custom-select-option-backend {
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
		
		.custom-select-option-backend:hover {
			background-color: #f5f5f5;
		}
		
		.custom-select-option-backend.selected {
			background-color: #3C8DBC;
			color: #fff;
			font-weight: 500;
		}
		
		.custom-select-option-backend.selected:hover {
			background-color: #357ca5;
		}
		
		.custom-select-option-backend .flag-icon {
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
		
		.custom-select-option-backend .flag-icon svg {
			width: 20px;
			height: 15px;
			display: block;
		}
		
		.custom-select-option-backend .flag-icon img {
			width: 20px;
			height: 15px;
			display: block;
		}
		
		.custom-select-option-backend .lang-name {
			flex: 1;
			padding-left: 12px;
			white-space: nowrap;
			text-align: left;
		}
		
		/* 美化滚动条 */
		.custom-select-options-backend::-webkit-scrollbar {
			width: 6px;
		}
		
		.custom-select-options-backend::-webkit-scrollbar-track {
			background: #f1f1f1;
			border-radius: 3px;
		}
		
		.custom-select-options-backend::-webkit-scrollbar-thumb {
			background: #c1c1c1;
			border-radius: 3px;
		}
		
		.custom-select-options-backend::-webkit-scrollbar-thumb:hover {
			background: #a8a8a8;
		}
		
		/* 前端默认语言自定义下拉框样式 */
		.custom-language-select-frontend {
			position: relative;
			display: inline-block;
			width: 200px;
			z-index: 1000;
		}
		
		.custom-select-selected-frontend {
			background-color: #fff;
			border: 2px solid #e5e7eb;
			color: #333;
			padding: 0 28px 0 32px;
			height: 50px;
			line-height: 30px;
			font-size: 13px;
			cursor: pointer;
			display: flex;
			align-items: center;
			position: relative;
			border-radius: 4px;
			transition: background-color 0.2s ease;
		}
		
		.custom-select-selected-frontend:hover {
			background-color: #f5f5f5;
		}
		
		.custom-select-selected-frontend .flag-icon {
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
		
		.custom-select-selected-frontend .flag-icon svg {
			width: 20px;
			height: 15px;
			display: block;
		}
		
		.custom-select-selected-frontend .flag-icon img {
			width: 20px;
			height: 15px;
			display: block;
		}
		
		.custom-select-selected-frontend .lang-name {
			flex: 1;
			color: #333;
			padding-left: 12px;
			white-space: nowrap;
			text-align: left;
		}
		
		.custom-select-selected-frontend .custom-select-arrow {
			position: absolute;
			right: 8px;
			color: #666;
			font-size: 10px;
			transition: transform 0.2s ease;
		}
		
		.custom-select-options-frontend {
			display: none;
			position: absolute;
			top: 100%;
			left: 0;
			right: 0;
			background-color: #fff;
			min-width: 200px;
			box-shadow: 0 4px 12px rgba(0,0,0,0.15), 0 2px 4px rgba(0,0,0,0.1);
			z-index: 1001;
			max-height: 300px;
			overflow-y: auto;
			overflow-x: hidden;
			border: 1px solid #e0e0e0;
			border-radius: 6px;
			margin-top: 4px;
			padding: 4px 0;
		}
		
		.custom-select-options-frontend.show {
			display: block;
		}
		
		.custom-select-option-frontend {
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
		
		.custom-select-option-frontend:hover {
			background-color: #f5f5f5;
		}
		
		.custom-select-option-frontend.selected {
			background-color: #3C8DBC;
			color: #fff;
			font-weight: 500;
		}
		
		.custom-select-option-frontend.selected:hover {
			background-color: #357ca5;
		}
		
		.custom-select-option-frontend .flag-icon {
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
		
		.custom-select-option-frontend .flag-icon svg {
			width: 20px;
			height: 15px;
			display: block;
		}
		
		.custom-select-option-frontend .flag-icon img {
			width: 20px;
			height: 15px;
			display: block;
		}
		
		.custom-select-option-frontend .lang-name {
			flex: 1;
			padding-left: 12px;
			white-space: nowrap;
			text-align: left;
		}
		
		/* 美化滚动条 */
		.custom-select-options-frontend::-webkit-scrollbar {
			width: 6px;
		}
		
		.custom-select-options-frontend::-webkit-scrollbar-track {
			background: #f1f1f1;
			border-radius: 3px;
		}
		
		.custom-select-options-frontend::-webkit-scrollbar-thumb {
			background: #c1c1c1;
			border-radius: 3px;
		}
		
		.custom-select-options-frontend::-webkit-scrollbar-thumb:hover {
			background: #a8a8a8;
		}
		
		/* 中奖方式自定义下拉框样式 */
		.custom-winning-method-select {
			position: relative;
			display: inline-block;
			width: 200px;
			z-index: 1000;
		}
		
		.custom-select-selected-winning {
			background-color: #fff;
			border: 2px solid #e5e7eb;
			color: #333;
			padding: 0 28px 0 32px;
			height: 50px;
			line-height: 30px;
			font-size: 13px;
			cursor: pointer;
			display: flex;
			align-items: center;
			position: relative;
			border-radius: 4px;
			transition: background-color 0.2s ease;
		}
		
		.custom-select-selected-winning:hover {
			background-color: #f5f5f5;
		}
		
		.custom-select-selected-winning .method-icon {
			position: absolute;
			left: 6px;
			top: 50%;
			transform: translateY(-50%);
			width: 20px;
			height: 20px;
			display: inline-flex !important;
			align-items: center !important;
			justify-content: center !important;
			visibility: visible !important;
			opacity: 1 !important;
			flex-shrink: 0;
		}
		
		.custom-select-selected-winning .method-icon svg {
			width: 20px;
			height: 20px;
			display: block;
		}
		
		.custom-select-selected-winning .method-name {
			flex: 1;
			color: #333;
			padding-left: 12px;
			white-space: nowrap;
			text-align: left;
		}
		
		.custom-select-selected-winning .custom-select-arrow {
			position: absolute;
			right: 8px;
			color: #666;
			font-size: 10px;
			transition: transform 0.2s ease;
		}
		
		.custom-select-options-winning {
			display: none;
			position: absolute;
			top: 100%;
			left: 0;
			right: 0;
			background-color: #fff;
			min-width: 200px;
			box-shadow: 0 4px 12px rgba(0,0,0,0.15), 0 2px 4px rgba(0,0,0,0.1);
			z-index: 1001;
			max-height: 300px;
			overflow-y: auto;
			overflow-x: hidden;
			border: 1px solid #e0e0e0;
			border-radius: 6px;
			margin-top: 4px;
			padding: 4px 0;
		}
		
		.custom-select-options-winning.show {
			display: block;
		}
		
		.custom-select-option-winning {
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
		
		.custom-select-option-winning:hover {
			background-color: #f5f5f5;
		}
		
		.custom-select-option-winning.selected {
			background-color: #3C8DBC;
			color: #fff;
			font-weight: 500;
		}
		
		.custom-select-option-winning.selected:hover {
			background-color: #357ca5;
		}
		
		.custom-select-option-winning .method-icon {
			position: absolute;
			left: 6px;
			top: 50%;
			transform: translateY(-50%);
			width: 20px;
			height: 20px;
			display: inline-flex !important;
			align-items: center !important;
			justify-content: center !important;
			visibility: visible !important;
			opacity: 1 !important;
			flex-shrink: 0;
		}
		
		.custom-select-option-winning .method-icon svg {
			width: 20px;
			height: 20px;
			display: block;
		}
		
		.custom-select-option-winning .method-name {
			flex: 1;
			padding-left: 12px;
			white-space: nowrap;
			text-align: left;
		}
		
		/* 美化滚动条 */
		.custom-select-options-winning::-webkit-scrollbar {
			width: 6px;
		}
		
		.custom-select-options-winning::-webkit-scrollbar-track {
			background: #f1f1f1;
			border-radius: 3px;
		}
		
		.custom-select-options-winning::-webkit-scrollbar-thumb {
			background: #c1c1c1;
			border-radius: 3px;
		}
		
		.custom-select-options-winning::-webkit-scrollbar-thumb:hover {
			background: #a8a8a8;
		}
		
		/* 后端默认语言国旗图标容器样式 */
		#flag_backend {
			position: absolute;
			left: 10px;
			top: 50%;
			transform: translateY(-50%);
			pointer-events: none;
			display: inline-block;
			vertical-align: middle;
			z-index: 10;
			width: 20px;
			height: 15px;
		}
		
		#flag_backend svg {
			width: 20px !important;
			height: 15px !important;
			display: block !important;
			vertical-align: middle;
		}
		
		#flag_backend img {
			width: 20px !important;
			height: 15px !important;
			display: block !important;
			vertical-align: middle;
		}
		
		/* 单选框样式 */
		input[type="radio"] {
			width: 18px !important;
			height: 18px !important;
			cursor: pointer !important;
			margin-right: 8px !important;
			margin-left: 0 !important;
			vertical-align: middle !important;
		}
		
		/* 单选框标签 */
		label, font {
			vertical-align: middle !important;
			margin-right: 20px !important;
		}
		
		/* 时间格式和搜索词列表 */
		.resouci {
			margin-bottom: 10px !important;
			display: flex !important;
			align-items: center !important;
			gap: 15px !important;
			height: 50px;
		}
		
		/* 热门搜索词模块 - 每两行输入框之间的间距调整为10px */
		.resouci + .resouci {
			margin-top: 10px !important;
		}
		
		.resouci > div:first-child {
			flex: 1;
		}
		
		.resouci > span:last-child {
			min-width: 80px;
			text-align: left;
		}
		
		/* 时间格式选择器 */
		select[name*="web_time_format"] {
			margin-bottom: 10px !important;
		}
		
		/* 禁用输入框样式 - 白色背景 */
		input[disabled] {
			background-color: #fff !important;
			border-color: #d1d5db !important;
			color: #9ca3af !important;
			cursor: not-allowed !important;
		}
		
		/* 表单布局优化 - 居左排列，无间隙 */
		.layui-input-block {
			margin-left: 0 !important;
			text-align: left !important;
		}
		
		/* 所有输入框和下拉框容器居左 */
		.table-list table td:not([align="right"]) > *,
		.table-list table td:not([align="right"]) .layui-input-block,
		.table-list table td:not([align="right"]) .layui-input-inline {
			text-align: left !important;
			margin-left: 0 !important;
		}
		
		/* 提交按钮容器 */
		div[style*="text-align: center"] {
			text-align: center !important;
		}
		
		/* 确保页面背景为白色 */
		body {
			background: #fff !important;
		}
		
		/* 响应式优化 */
		@media (max-width: 1400px) {
			.table-list table td[align="right"] {
				width: 180px !important;
				min-width: 180px !important;
			}
			
			.input-text, .wid200, .wid300 {
				max-width: 300px !important;
			}
		}
		.gjdys {
		    display: flex;
		    gap: 40px;
		    align-items: center;
		}
		.gjdys span {
		    transform: translateY(-5px);
		}
		table tbody tr:hover:not(.calendar tr) {
            transform: none!important;
        }
	</style>
</head>

<body>
	<?php
	// 语言到国旗SVG图标的映射函数
	function getFlagSVG($lg_value) {
		// 将语言值转换为小写以便匹配
		$lg_lower = strtolower($lg_value);
		
		// 国旗SVG图标映射（使用内联SVG，确保在所有浏览器中正常显示）
		$flagSVGMap = [
			'chinese' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="15" fill="#de2910"/><path d="M3,2 L5,4 L4.5,1 L6.5,1 L6,4 L8,2 L7,5 L9,5 L5.5,7 L9,9 L7,9 L8,12 L6,10 L6.5,13 L4.5,13 L5,10 L3,12 L4,9 L2,9 L5.5,7 L2,5 L4,5 Z" fill="#ffde00"/></svg>',
			'english' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="15" fill="#012169"/><path d="M0,0 L20,0 L20,2 L0,2 Z M0,6 L20,6 L20,8 L0,8 Z M0,13 L20,13 L20,15 L0,15 Z" fill="#fff"/><path d="M0,0 L0,15 L8,15 L8,0 Z" fill="#fff"/><path d="M0,0 L20,15 M20,0 L0,15" stroke="#c8102e" stroke-width="2"/><path d="M0,0 L20,15 M20,0 L0,15" stroke="#fff" stroke-width="1.2"/></svg>',
			'portuguese' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="15" fill="#006600"/><rect width="10" height="15" fill="#ff0000"/><circle cx="5" cy="7.5" r="3" fill="#ffd700"/><circle cx="5" cy="7.5" r="2" fill="#006600"/></svg>',
			'spanish' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="5" fill="#aa151b"/><rect y="5" width="20" height="5" fill="#f1bf00"/><rect y="10" width="20" height="5" fill="#aa151b"/></svg>',
			'french' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="6.67" height="15" fill="#002654"/><rect x="6.67" width="6.66" height="15" fill="#fff"/><rect x="13.33" width="6.67" height="15" fill="#ed2939"/></svg>',
			'german' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="5" fill="#000"/><rect y="5" width="20" height="5" fill="#dd0000"/><rect y="10" width="20" height="5" fill="#ffce00"/></svg>',
			'italian' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="6.67" height="15" fill="#009246"/><rect x="6.67" width="6.66" height="15" fill="#fff"/><rect x="13.33" width="6.67" height="15" fill="#ce2b37"/></svg>',
			'japanese' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="15" fill="#fff"/><circle cx="10" cy="7.5" r="4.5" fill="#bc002d"/></svg>',
			'korean' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="15" fill="#fff"/><circle cx="10" cy="7.5" r="4.5" fill="#c60c30"/><path d="M10,3 L10,12 M6,7.5 L14,7.5" stroke="#000" stroke-width="0.5"/></svg>',
			'russian' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="5" fill="#fff"/><rect y="5" width="20" height="5" fill="#0039a6"/><rect y="10" width="20" height="5" fill="#d52b1e"/></svg>',
			'arabic' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="15" fill="#006c35"/><text x="10" y="11" font-size="10" fill="#fff" text-anchor="middle" font-weight="bold">☪</text></svg>',
			'thai' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="3" fill="#ed1c24"/><rect y="3" width="20" height="9" fill="#fff"/><rect y="12" width="20" height="3" fill="#ed1c24"/><rect y="5" width="20" height="5" fill="#241d4f"/></svg>',
			'vietnamese' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="15" fill="#da020e"/><path d="M10,2 L11,6 L15,6 L11.5,8.5 L12.5,12.5 L10,10 L7.5,12.5 L8.5,8.5 L5,6 L9,6 Z" fill="#ff0"/></svg>',
			'indonesian' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="7.5" fill="#ce1126"/><rect y="7.5" width="20" height="7.5" fill="#fff"/></svg>',
			'malay' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="3" fill="#000"/><rect y="3" width="20" height="3" fill="#fff"/><rect y="6" width="20" height="3" fill="#000"/><rect y="9" width="20" height="3" fill="#fff"/><rect y="12" width="20" height="3" fill="#000"/><rect width="10" height="15" fill="#006"/></svg>',
			'hindi' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="5" fill="#ff9933"/><rect y="5" width="20" height="5" fill="#fff"/><rect y="10" width="20" height="5" fill="#138808"/><circle cx="10" cy="7.5" r="2" fill="#000080"/></svg>',
			'turkish' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="15" fill="#e30a17"/><circle cx="10" cy="7.5" r="4" fill="#fff"/><circle cx="10" cy="7.5" r="2.5" fill="#e30a17"/></svg>',
			'dutch' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="5" fill="#ae1c28"/><rect y="5" width="20" height="5" fill="#fff"/><rect y="10" width="20" height="5" fill="#21468b"/></svg>',
			'polish' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="7.5" fill="#fff"/><rect y="7.5" width="20" height="7.5" fill="#dc143c"/></svg>',
			'czech' => '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="7.5" fill="#fff"/><rect y="7.5" width="20" height="7.5" fill="#d7141a"/><path d="M0,0 L10,7.5 L0,15 Z" fill="#11457e"/></svg>',
		];
		
		// 尝试精确匹配
		if (isset($flagSVGMap[$lg_lower])) {
			return $flagSVGMap[$lg_lower];
		}
		
		// 尝试部分匹配（处理类似 "Chinese（中文）" 的情况）
		foreach ($flagSVGMap as $key => $svg) {
			if (strpos($lg_lower, $key) !== false) {
				return $svg;
			}
		}
		
		// 默认返回通用旗帜
		return '<svg width="20" height="15" viewBox="0 0 20 15" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="15" fill="#ccc"/><text x="10" y="11" font-size="8" fill="#666" text-anchor="middle">?</text></svg>';
	}
	
	// 兼容旧函数名（保留emoji版本作为备用）
	function getFlagEmoji($lg_value) {
		$flagMap = [
			'Chinese' => '🇨🇳',
			'English' => '🇬🇧',
			'Portuguese' => '🇵🇹',
			'Spanish' => '🇪🇸',
			'French' => '🇫🇷',
			'German' => '🇩🇪',
			'Italian' => '🇮🇹',
			'Japanese' => '🇯🇵',
			'Korean' => '🇰🇷',
			'Russian' => '🇷🇺',
			'Arabic' => '🇸🇦',
			'Thai' => '🇹🇭',
			'Vietnamese' => '🇻🇳',
			'Indonesian' => '🇮🇩',
			'Malay' => '🇲🇾',
			'Hindi' => '🇮🇳',
			'Turkish' => '🇹🇷',
			'Dutch' => '🇳🇱',
			'Polish' => '🇵🇱',
			'Czech' => '🇨🇿',
		];
		return isset($flagMap[$lg_value]) ? $flagMap[$lg_value] : '🏳️';
	}
	?>
	<div class="header lr10">
		<?php echo $this->headerment(); ?>
	</div>
	<div class="bk10"></div>
	<div class="table-list lr10">
		<!--start-->
		<form name="myform" action="#" method="post" enctype="multipart/form-data">
			<table width="100%" cellspacing="0">
				<tr>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"><?php $this->lan("Platform_Name");?> </td>
					<td style="display: flex;"><input type="text" value="<?php echo $web['web_name']; ?>" name="web_name" placeholder="<?php $this->lan("Platform_Name");?>" class="input-text wid200" style="width:200px !important; max-width:200px !important; min-width:200px !important;"></td>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("description");?></td>
					<td style="display: flex;"><input name="web_des" class="wid300" placeholder=" <?php $this->lan("description");?>" style="height:30px;width:500px;border: 1px solid #B4B4B4;padding:2px 0px 2px 5px" value="<?php echo $web['web_des']; ?>">
					</td>
				</tr>

				<tr>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Domain_name");?></td>
					<td style="display: flex;"><input type="text" name="web_path" value="<?php echo $web['web_path']; ?>" placeholder="<?php $this->lan("tips2");?>" class="input-text wid200" style="width:200px !important; max-width:200px !important; min-width:200px !important;"></td>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Backend_path");?></td>
					<td style="display: flex;">
						<input type="text" name="admindir"  value="<?php echo $web['admindir']; ?>" style="width:200px !important; max-width:200px !important; min-width:200px !important; text-align:center !important;" placeholder=" <?php $this->lan("Backend_path");?>" class="input-text">
					</td>
					<!-- <td width="220" align="right" style="padding-right: 10px">Favicon 图标</td>
		<td>
			<div class="layui-row">
			    <div class="layui-col-md1 width56" style="margin-right: -20px;border:1px solid #fff">
			        <div class="layui-upload-list height56" style="width: 20px;height: 20px; padding: 0; margin: 0;border: 1px solid #fff;">
						<div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;"<?php endif; ?>>
							<span>+</span>
							<text>点击上传</text>
						</div>
						<img id="imgIcoPhoto" class="layui-upload-img" src="<?php echo G_WEB_PATH . "/favicon.ico"; ?>" style="width: 20px; height: 20px;" alt="" />
			            <p id="demoText"></p>
						<input class="bottoms" type="file" name="web_ico"  onchange="PreviewImage(this,'imgIcoPhoto','divPreview');" style="width: 69px;" size="20" accept="image/png/ico"/>
			        </div>
			    </div>
			    <div style="marign-bottom 10px;">
			        64*64Px，支持png
			    </div>
			</div>
		</td> -->
				</tr>
				<!-- 	<tr>
		<td width="220" align="right" style="padding-right: 10px">平台logo</td>
		<td >
	        <div class="layui-row">
	            <div class="layui-col-md1 width130" style="border:1px solid #fff">
	                <div class="layui-upload-list height130">
						<div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;"<?php endif; ?>>
							<span>+</span>
							<text>点击上传</text>
						</div>
	                    <img class="layui-upload-img" width="120px" src="<?php echo G_UPLOAD_PATH . '/' . $web['web_logo']; ?>" id="web_logoimg-img">
	                    <input type="hidden" id="web_logoimg-lack" name="web_logo" value="<?php echo $web['web_logo']; ?>">
	                    <p id="demoText"></p>
						<button type="button" onclick="uploadimg('上传平台logo','#web_logoimg','image','banner')"></button>
	                </div>
	            </div>
	            <div class="layui-col-md9">
	                200*200Px，支持jpg、jpeg
	            </div>
	        </div>
	    </td>
		<td width="220" align="right" style="padding-right: 10px">用户头像</td>
		<td>
            <div class="layui-row">
                <div class="layui-col-md1 width130" style="border:1px solid #fff">
					<div class="layui-upload-list height130">
						<div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;"<?php endif; ?>>
							<span>+</span>
							<text>点击上传</text>
						</div>
					    <img class="layui-upload-img" width="120px" src="<?php echo  G_UPLOAD_PATH . '/' . $web['photo']; ?>" id="photoimg-img">
					    <input type="hidden" id="photoimg-lack" name="photo" value="<?php echo $web['photo']; ?>">
					    <p id="demoText"></p>
						<button type="button" onclick="uploadimg('用户头像','#photoimg','image','touimg')"></button>
					</div>
                </div>
                <div class="layui-col-md9">
                    200*200Px，支持jpg、jpeg
                </div>
            </div>
        </td>
	</tr> -->

				<tr style="display:none">
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("WhatsApp");?>
						<br> <?php $this->lan("service_code");?>
					</td>
					<td colspan="6" style="display:flex;flex-wrap: wrap;">
						<?php foreach ($languages as $k => $v) { ?>
							<div class="layui-row" style="text-align:center">
								<div class="layui-col-md1 width130" style="border:1px solid #fff">
									<div class="layui-col-md-2"><?php echo $v['country']; ?></div>
									<div class="layui-upload-list " style="padding: 30px 0;">
										<div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;" <?php endif; ?>>
											<span>+</span>
											<text>点击上传</text>
										</div>
										<img class="layui-upload-img" width="120px" src="<?php echo G_UPLOAD_PATH . '/' . $web['qqkfewm'][$v['lg_value']]; ?>" id="<?php echo $v['lg_value'] . 'kfimg-img'; ?>">
										<input type="hidden" id="<?php echo $v['lg_value'] . 'kfimg-lack'; ?>" name="qqkfewm[<?php echo $v['lg_value']; ?>]" value="<?php echo $web['qqkfewm'][$v['lg_value']]; ?>">
										<p id="demoText"></p>
										<button type="button" onclick="uploadimg('上传QQ客服二维码','#<?php echo $v['lg_value']; ?>kfimg','image','banner')"></button>
									</div>
									<!-- <p>446*800Px，支持jpg</p> -->
								</div>
								<!--<div class="layui-col-md7">-->
								<!--    446*800Px，支持jpg-->
								<!--</div>-->
							</div>
						<?php } ?>
					</td>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Telegram");?>
						<br> <?php $this->lan("User_center");?>
					</td>
					<td colspan="6" style="display:flex;flex-wrap: wrap;">
						<?php foreach ($languages as $k => $v) { ?>
							<div class="layui-row" style="text-align:center">
								<div class="layui-col-md1 width130" style="border:1px solid #fff">
									<div class="layui-col-md-2"><?php echo $v['country']; ?></div>
									<div class="layui-upload-list" style="padding: 30px 0;">
										<div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;" <?php endif; ?>>
											<span>+</span>
											<text>点击上传</text>
										</div>
										<img class="layui-upload-img" width="100px" src="<?php echo G_UPLOAD_PATH . '/' . $web['wxkfewm'][$v['lg_value']]; ?>" id="<?php echo $v['lg_value'] . 'wxkfewmimg-img'; ?>">
										<input type="hidden" id="<?php echo $v['lg_value'] . 'wxkfewmimg-lack'; ?>" name="wxkfewm[<?php echo $v['lg_value']; ?>]" value="<?php echo $web['wxkfewm'][$v['lg_value']]; ?>">
										<p id="demoText"></p>
										<button type="button" onclick="uploadimg('上传微信客服二维码','#<?php echo $v['lg_value']; ?>wxkfewmimg','image','banner')"></button>
									</div>
									<!-- <p>358*485Px，支持jpg</p> -->
								</div>
								<!--<div class="layui-col-md9">-->
								<!--    358*485Px，支持jpg-->
								<!--</div>-->
							</div>
						<?php } ?>
					</td>
				</tr>

				<!--    <tr>-->
				<!--		<td width="220" align="right" style="padding-right: 10px">IOS下载地址</td>-->
				<!--		<td><input type="text" name="web_ios" value="--><?php //echo $web['web_ios']; 
																			?><!--" placeholder="域名前需带上http://或https://" class="input-text wid200"></td>-->
				<!--	</tr>-->
				<!--  	<tr>-->
				<!--		<td width="220" align="right" style="padding-right: 10px">Android下载地址</td>-->
				<!--		<td><input type="text" name="web_android" value="--><?php //echo $web['web_android']; 
																				?><!--" placeholder="域名前需带上http://或https://" class="input-text wid200"></td>-->
				<!--	</tr>-->

				<!--	<tr>-->
				<!--			<td width="220" align="right" style="padding-right: 10px">网站短名称</td>-->
				<!--			<td><input type="text" value="--><?php //echo $web['web_name_two']; 
																	?><!--" name="web_name_two" class="input-text wid200"></td>-->
				<!--	</tr>-->
				<!--     <tr>-->
				<!--			<td width="220" align="right" style="padding-right: 10px">网站关键字</td>-->
				<!--			<td><input type="text" value="--><?php //echo $web['web_key']; 
																	?><!--" name="web_key" class="input-text wid300"></td>-->
				<!--	</tr>-->

				<tr>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Winning_method");?></td>
					<td align="left" style="padding-right: 10px;display: flex;">
						<div class="custom-winning-method-select" style="position: relative; display: inline-block; width: 200px;z-index: 9999;margin:0!important;">
							<!-- 隐藏的select，用于表单提交 -->
							<select name="winning_method" id="winning_method" style="display: none;">
								<option value="1" <?php echo (isset($web['winning_method']) && $web['winning_method'] == '1') ? 'selected' : ''; ?>>余数开奖方式</option>
								<option value="2" <?php echo (isset($web['winning_method']) && $web['winning_method'] == '2') ? 'selected' : ''; ?>>比特币开奖方式</option>
							</select>
							<!-- 自定义下拉框 -->
							<?php 
							$current_method = isset($web['winning_method']) ? $web['winning_method'] : '1';
							// 余数开奖方式图标 - 更美观的设计（计算器/数学符号）
							$icon1 = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="4" y="4" width="16" height="16" rx="3" stroke="#667eea" stroke-width="2" fill="none"/><path d="M8 8h8M8 12h8M8 16h5" stroke="#667eea" stroke-width="2" stroke-linecap="round"/><circle cx="15" cy="16" r="1.5" fill="#667eea"/></svg>';
							// 比特币开奖方式图标
							$icon2 = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke="#f59e0b" stroke-width="2"/><path d="M12 6V12L16 14" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="12" r="2" fill="#f59e0b"/></svg>';
							$currentIcon = ($current_method == '1') ? $icon1 : $icon2;
							$currentText = ($current_method == '1') ? '余数开奖方式' : '比特币开奖方式';
							?>
							<div class="custom-select-selected-winning" id="winning_method_selected">
								<span class="method-icon"><?php echo $currentIcon; ?></span>
								<span class="method-name"><?php echo $currentText; ?></span>
								<span class="custom-select-arrow">▼</span>
							</div>
							<div class="custom-select-options-winning" id="winning_method_options">
								<div class="custom-select-option-winning <?php echo ($current_method == '1') ? 'selected' : ''; ?>" data-value="1" data-icon="<?php echo htmlspecialchars($icon1, ENT_QUOTES); ?>">
									<span class="method-icon"><?php echo $icon1; ?></span>
									<span class="method-name">余数开奖方式</span>
								</div>
								<!--<div class="custom-select-option-winning <?php echo ($current_method == '2') ? 'selected' : ''; ?>" data-value="2" data-icon="<?php echo htmlspecialchars($icon2, ENT_QUOTES); ?>">-->
								<!--	<span class="method-icon"><?php echo $icon2; ?></span>-->
								<!--	<span class="method-name">比特币开奖方式</span>-->
								<!--</div>-->
							</div>
						</div>
					</td>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("time");?></td>
					<td class="countdown-time" style="margin-top:6px; display: inline-flex; align-items: center; gap: 6px;">
						<input type="text" readonly style="width:200px !important; max-width:200px !important; min-width:200px !important; text-align: center !important;" name="goods_end_time" value="<?php echo $web['goods_end_time']?:30; ?>" placeholder=" <?php $this->lan("tips13");?>" class="input-text">
						<span class="time-unit" style="color: #6b7280; font-size: 14px; font-weight: 500; white-space: nowrap;"> <?php $this->lan("second");?></span>
					</td>
					<!-- <td width="220" align="right" style="padding-right: 10px">中奖恭喜图</td>
		  <td colspan="6" style="display:flex;flex-wrap: wrap;">
		  <?php foreach ($languages as $k => $v) { ?>
            <div class="layui-row" style="text-align:center">
                <div class="layui-col-md1 width130" style="border:1px solid #fff">
                    <div class="layui-col-md-2"><?php echo $v['country']; ?></div>
                    <div class="layui-upload-list" style="padding: 30px 0;">
						<div class="layui-bottom-img" <?php if (!$img): ?>style="opacity: 0;"<?php endif; ?>>
							<span>+</span>
							<text>点击上传</text>
						</div>
                        <img class="layui-upload-img" width="100px" src="<?php echo G_UPLOAD_PATH . '/' . $web['celebrate_img'][$v['lg_value']]; ?>" id="<?php echo $v['lg_value'] . 'celebrate_img-img'; ?>">
                        <input type="hidden" id="<?php echo $v['lg_value'] . 'celebrate_img-lack'; ?>" name="celebrate_img[<?php echo $v['lg_value']; ?>]" value="<?php echo $web['celebrate_img'][$v['lg_value']]; ?>">
                        <p id="demoText"></p>
						<button type="button" onclick="uploadimg('上传中奖恭喜图','#<?php echo $v['lg_value']; ?>celebrate_img','image','banner')"></button>
                    </div>
                </div>
            </div>
			<?php } ?>
		  </td> -->
				</tr>
				<tr>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Discounted_Purchase1");?></td>
					<td style="display: flex;align-items: center;">
						<input type="radio" name="is_preferential" value="1" style="margin:0!important;" title="是" <?php if (!isset($web['is_preferential']) || $web['is_preferential'] == 1) {
																							echo 'checked';
																						} ?>>&nbsp; <?php $this->lan("open");?>&nbsp;&nbsp;&nbsp;&nbsp;
						<input style="margin:0!important;" type="radio" name="is_preferential" value="2" title="否" <?php if (isset($web['is_preferential']) && $web['is_preferential'] == 2) {
																														echo 'checked';
																													} ?>>&nbsp; <?php $this->lan("close");?>
					</td>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Group_buying");?></td>
					<td style="display: flex;align-items: center;">
						<input type="radio" name="is_group" value="1" style="margin:0!important;" title="是" <?php if (!isset($web['is_group']) || $web['is_group'] == 1) {
																					echo 'checked';
																				} ?>>&nbsp; <?php $this->lan("open");?>&nbsp;&nbsp;&nbsp;&nbsp;
						<input style="margin:0!important;" type="radio" name="is_group" value="2" title="否" <?php if (isset($web['is_group']) && $web['is_group'] == 2) {
																												echo 'checked';
																											} ?>>&nbsp; <?php $this->lan("close");?>
					</td>
				</tr>

				<tr>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("home_Pop");?></td>
					<td>
						<div class="layui-input-block" style="margin-left: 0px!important;padding-top:6px">
							<input type="radio" id="is_popup_banner_1" name="is_popup_banner" value="1" title="是" <?php echo $web['is_popup_banner'] != 2 ? "checked='checked'" : ''; ?>><label for="is_popup_banner_1">&nbsp; <?php $this->lan("show");?></label>
							<input type="radio" id="is_popup_banner_2" name="is_popup_banner" style="margin-left: 13px !important;" value="2" title="否" <?php echo $web['is_popup_banner'] == 2 ? "checked" : ''; ?>><label for="is_popup_banner_2">&nbsp; <?php $this->lan("hide");?></label>
						</div>
					</td>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Home_wheel");?></td>
					<td>
						<div class="layui-input-block" style="margin-left: 0px!important;padding-top:6px">
							<input type="radio" id="is_turntable_1" name="is_turntable" value="1" title="是" <?php echo $web['is_turntable'] != 2 ? "checked" : ''; ?>><label for="is_turntable_1">&nbsp; <?php $this->lan("show");?></label>
							<input type="radio" id="is_turntable_2" name="is_turntable" value="2" title="否" style="margin-left: 13px !important;" <?php echo $web['is_turntable'] == 2 ? "checked" : ''; ?>><label for="is_turntable_2">&nbsp; <?php $this->lan("hide");?></label>
						</div>
					</td>
				</tr>
				<tr>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Notice");?></td>
					<td style="display: flex;align-items: center;">
							<input class="checked_show" type="radio" name="is_shownotice" style="margin: 0!important;" value="1" title="是" <?php echo $web['is_shownotice'] == 1 ? "checked" : ''; ?>>&nbsp; <?php $this->lan("show");?>&nbsp;&nbsp;&nbsp;&nbsp;
							<input class="checked_show" type="radio" style="margin: 0!important;" name="is_shownotice" value="2" title="否" <?php echo $web['is_shownotice'] == 2 ? "checked" : ''; ?>>&nbsp; <?php $this->lan("hide");?>
					</td>
					<td data-show="is_shownotice" width="220" align="right" style="text-align:right!important;padding-right: 10px;<?php echo $web['is_shownotice']=='2'?'display:none;':''?>"> <?php $this->lan("Notice_content");?></td>
					<td data-show="is_shownotice" style="display: flex;"><input name="banner" class="wid300" style="height:30px;width:500px;border: 1px solid #B4B4B4;padding:2px 0px 2px 5px" value="<?php echo $web['banner']; ?>" placeholder=" <?php $this->lan("tips10");?>">
					</td>
				</tr>

				<tr>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Offline_recharge");?></td>
					<td style="display: flex;"><input type="text" value="<?php echo $web['web_xxczts']; ?>" name="web_xxczts" placeholder=" <?php $this->lan("Offline_recharge");?>" class="input-text wid200" style="width:200px !important; max-width:200px !important; min-width:200px !important;"></td>
					<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Copyright_Info");?></td>
					<td style="display: flex;"><input name="web_copyright" class="wid300" placeholder=" <?php $this->lan("Copyright_Info");?>" style="height:30px;width:200px !important; max-width:200px !important; min-width:200px !important; border: 1px solid #B4B4B4;padding:2px 0px 2px 5px" value="<?php echo $web['web_copyright']; ?>">
					</td>
				</tr>
				<tr>
					<td align="right" width="220" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Navigation_spacing");?></td>
					<td style="display: inline-flex; align-items: center; gap: 6px;">
						<input type="text" id="margin_bottom" name="margin_bottom" onKeyUp="value=value.replace(/\D/g,'')" maxlength="2" style="width:200px !important; max-width:200px !important; min-width:200px !important; text-align:center !important;" value="<?php echo $web['margin_bottom']; ?>" class="input-text">
						<span style="color: #6b7280; font-size: 14px; font-weight: 500; white-space: nowrap;">px</span>
					</td>
					<td align="right" width="220" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Navigation_top");?></td>
					<td style="display: inline-flex; align-items: center; gap: 6px;">
						<input type="text" id="margin_top" name="margin_top" onKeyUp="value=value.replace(/\D/g,'')" maxlength="2" style="width:200px !important; max-width:200px !important; min-width:200px !important; text-align:center !important;" value="<?php echo $web['margin_top']; ?>" class="input-text">
						<span style="color: #6b7280; font-size: 14px; font-weight: 500; white-space: nowrap;">px</span>
					</td>

				</tr>

				<!-- <td><input type="submit" class="button" name="dosubmit"  value=" 提交 " ></td> -->
				<?php foreach ($domain_cfg as $key => $do) { ?>
					<tr>
						<td align="right" width="" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Bind_domain");?></td>
						<td style="display: flex;">
							<input type="text" value="<?php echo $key; ?>" name="domain_k" placeholder="请输入模块" class="input-text wid200" style="width:200px !important; max-width:200px !important; min-width:200px !important;">
						</td>
						<td align="right" width="" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Language_modules");?></td>
						<td class="rightgray" style="border: none !important; box-shadow: none !important; background: transparent !important; padding: 0 !important;">
							<select name="module" id="module" style="width: 130px;text-align:center; border: none !important; background: transparent !important; box-shadow: none !important; outline: none !important;">
								<option value="vue" <?php echo $do['m'] == "vue" ? "selected" : ""; ?>>Uniapp前端</option>
								<option value="mobile" <?php echo $do['m'] == "mobile" ? "selected" : ""; ?>>Html前端</option>
							</select>
							<input type="hidden" value="<?php echo $do['m']; ?>" name="domain_m" id="domain_m" class="input-text ">
							<input type="hidden" value="<?php echo $do['c']; ?>" name="domain_c" id="domain_c" class="input-text ">

							<!-- <input type="text" value="<?php echo $do['m']; ?>" name="domain_m" placeholder="请输入模块" class="input-text "> -->
							<!-- <select name="domain_m">
				<option value="vue" <?php if ($do['c'] == "vue") {
										echo "selected";
									} ?>>vue</option>
				<option value="mobile" <?php if ($do['c'] == "vue") {
											echo "selected";
										} ?>>mobile</option>
			</select> -->
						</td>
						<!-- <td align="right" width="" style="padding-right: 10px">绑定控制器</td> -->
						<!-- <td class="rightgray"> -->
						<!-- <select name="domain_c"> -->
						<!-- <option value="index" <?php if ($do['c'] == "index") {
														echo "selected";
													} ?>>index</option> -->
						<!-- <option value="mobile" <?php if ($do['c'] == "mobile") {
														echo "selected";
													} ?>>mobile</option> -->
						<!-- </select> -->
						<!-- <input type="text" value="<?php echo $do['c']; ?>" name="domain_c" placeholder="请输入控制器" class="input-text "> -->
						<!-- </td> -->
					</tr>

					<tr>

						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("qq_tel");?></td>
						<td style="display: flex;"><input type="text" name="qq" style="width:300px" value="<?php echo $web['qq']; ?>" placeholder=" <?php $this->lan("qq_tel");?>" class="input-text"></td>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Default_language");?></td>
						<td align="left" style="padding-right: 10px;display: flex;">
							<div class="custom-language-select-frontend" style="position: relative; display: inline-block; width: 200px;z-index: 9999;margin: 0!important;">
								<!-- 隐藏的select，用于表单提交 -->
								<select name="web_language" id="web_language" style="display: none;">
									<?php foreach ($languages as $key => $v) { ?>
										<option value="<?php echo $v['lg_value']; ?>" <?php if ($web['web_language'] == $v['lg_value']) {
																						echo 'selected';
																					} ?>><?php echo $v['country']; ?>(<?php echo $v['lg_value']; ?>)</option>
									<?php } ?>
								</select>
								<!-- 自定义下拉框 -->
								<div class="custom-select-selected-frontend" id="web_language_selected">
									<?php 
									$selected_frontend = $web['web_language'];
									$currentFlagSvg = '';
									$currentFlagUrl = '';
									$currentLangText = '';
									foreach ($languages as $key => $v) {
										if ($v['lg_value'] == $selected_frontend) {
											$currentFlagSvg = isset($v['flag_svg']) ? $v['flag_svg'] : '';
											$currentFlagUrl = isset($v['flag_url']) ? $v['flag_url'] : '';
											$currentLangText = $v['country'].'('.$v['lg_value'].')';
											break;
										}
									}
									if (empty($currentLangText) && count($languages) > 0) {
										$first = $languages[0];
										$currentFlagSvg = isset($first['flag_svg']) ? $first['flag_svg'] : '';
										$currentFlagUrl = isset($first['flag_url']) ? $first['flag_url'] : '';
										$currentLangText = $first['country'].'('.$first['lg_value'].')';
									}
									if(!empty($currentFlagSvg)): 
										echo '<span class="flag-icon">' . $currentFlagSvg . '</span>';
									elseif(!empty($currentFlagUrl)): 
										echo '<img src="'.htmlspecialchars($currentFlagUrl).'" class="flag-icon" alt="flag" onerror="this.style.display=\'none\';" />';
									else: 
										echo '<span class="flag-icon" style="background: #ccc; border-radius: 2px;"></span>';
									endif; 
									?>
									<span class="lang-name"><?php echo htmlspecialchars($currentLangText); ?></span>
									<span class="custom-select-arrow">▼</span>
								</div>
								<div class="custom-select-options-frontend" id="web_language_options">
									<?php 
									foreach ($languages as $key => $v) {
										$isSelected = ($web['web_language'] == $v['lg_value']) ? 'selected' : '';
										$flagUrl = isset($v['flag_url']) ? $v['flag_url'] : '';
										$flagSvg = isset($v['flag_svg']) ? $v['flag_svg'] : '';
										$langText = $v['country'].'('.$v['lg_value'].')';
										echo '<div class="custom-select-option-frontend '.$isSelected.'" data-value="'.htmlspecialchars($v['lg_value'], ENT_QUOTES).'" data-flag="'.htmlspecialchars($flagUrl, ENT_QUOTES).'" data-flag-svg="'.htmlspecialchars($flagSvg, ENT_QUOTES).'">';
										if(!empty($flagSvg)){
											echo '<span class="flag-icon">' . $flagSvg . '</span>';
										} elseif(!empty($flagUrl)){
											echo '<img src="'.htmlspecialchars($flagUrl).'" class="flag-icon" alt="flag" onerror="this.style.display=\'none\';" />';
										} else {
											echo '<span class="flag-icon" style="background: #ccc; border-radius: 2px;"></span>';
										}
										echo '<span class="lang-name">'.htmlspecialchars($langText).'</span>';
										echo '</div>';
									}
									?>
								</div>
							</div>
						</td>
					</tr>
				
					<tr>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("wx_tel","微信官方号");?></td>
						<td style="width:460px;display: flex;">
							<input type="text" name="wx" style="width:200px !important; max-width:200px !important; min-width:200px !important;" value="<?php echo $web['wx']; ?>" placeholder=" <?php $this->lan("wx_tel");?>" class="input-text">
						</td>
						<td width="220" align="right" style="text-align:right!important;"> <?php $this->lan("currency1","后端默认语言");?></td>
						<td style="display: flex;">
							<div class="custom-language-select-backend" style="position: relative; display: inline-block; width: 200px;margin: 0!important;">
								<!-- 隐藏的select，用于表单提交 -->
								<select name="web_language_backend" id="web_language_backend" style="display: none;">
									<?php foreach ($language_backend as $key => $v) { ?>
										<option value="<?php echo $v['lg_value']; ?>" <?php if ($web['web_language_backend'] == $v['lg_value']) {
																							echo 'selected';
																						} ?>><?php echo $v['lg_name']; ?></option>
									<?php } ?>
								</select>
								<!-- 自定义下拉框 -->
								<div class="custom-select-selected-backend" id="web_language_backend_selected">
									<?php 
									$selected_backend = $web['web_language_backend'];
									$currentFlagSvg = '';
									$currentFlagUrl = '';
									$currentLangText = '';
									foreach ($language_backend as $key => $v) {
										if ($v['lg_value'] == $selected_backend) {
											$currentFlagSvg = isset($v['flag_svg']) ? $v['flag_svg'] : '';
											$currentFlagUrl = isset($v['flag_url']) ? $v['flag_url'] : '';
											$currentLangText = $v['lg_name'];
											break;
										}
									}
									if (empty($currentLangText) && count($language_backend) > 0) {
										$first = $language_backend[0];
										$currentFlagSvg = isset($first['flag_svg']) ? $first['flag_svg'] : '';
										$currentFlagUrl = isset($first['flag_url']) ? $first['flag_url'] : '';
										$currentLangText = $first['lg_name'];
									}
									if(!empty($currentFlagSvg)): 
										echo '<span class="flag-icon">' . $currentFlagSvg . '</span>';
									elseif(!empty($currentFlagUrl)): 
										echo '<img src="'.htmlspecialchars($currentFlagUrl).'" class="flag-icon" alt="flag" onerror="this.style.display=\'none\';" />';
									else: 
										echo '<span class="flag-icon" style="background: #ccc; border-radius: 2px;"></span>';
									endif; 
									?>
									<span class="lang-name"><?php echo htmlspecialchars($currentLangText); ?></span>
									<span class="custom-select-arrow">▼</span>
								</div>
								<div class="custom-select-options-backend" id="web_language_backend_options">
									<?php 
									foreach ($language_backend as $key => $v) {
										$isSelected = ($web['web_language_backend'] == $v['lg_value']) ? 'selected' : '';
										$flagUrl = isset($v['flag_url']) ? $v['flag_url'] : '';
										$flagSvg = isset($v['flag_svg']) ? $v['flag_svg'] : '';
										$langText = $v['lg_name'];
										echo '<div class="custom-select-option-backend '.$isSelected.'" data-value="'.htmlspecialchars($v['lg_value'], ENT_QUOTES).'" data-flag="'.htmlspecialchars($flagUrl, ENT_QUOTES).'" data-flag-svg="'.htmlspecialchars($flagSvg, ENT_QUOTES).'">';
										if(!empty($flagSvg)){
											echo '<span class="flag-icon">' . $flagSvg . '</span>';
										} elseif(!empty($flagUrl)){
											echo '<img src="'.htmlspecialchars($flagUrl).'" class="flag-icon" alt="flag" onerror="this.style.display=\'none\';" />';
										} else {
											echo '<span class="flag-icon" style="background: #ccc; border-radius: 2px;"></span>';
										}
										echo '<span class="lang-name">'.htmlspecialchars($langText).'</span>';
										echo '</div>';
									}
									?>
								</div>
							</div>
						</td>
					</tr>
						<tr>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("orderlist_conver");?></td>
						<td style="width:460px;display: flex;align-items: center;">
							<input class="checked_show" type="radio" name="online_exchange" style="margin: 0!important;" value="1" <?php if ($web['online_exchange'] != 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("open");?>&nbsp;
							<input class="checked_show" type="radio" name="online_exchange" style="margin: 0 0 0 13px!important;" value="2" <?php if ($web['online_exchange'] == 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("close");?>
						</td>

						<td align="right" width="220" data-show="online_exchange" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Exchange_rate");?></td>
						<td data-show="online_exchange" style="display: inline-flex; align-items: center; gap: 6px;">
							<input type='text' name='proportion' placeholder=" <?php $this->lan("tips7");?>" maxlength="5" value="<?php echo $proportion; ?>" class="input-text" style="width: 200px !important; max-width:200px !important; min-width:200px !important; text-align: center !important;">
							<span style="color: #6b7280; font-size: 14px; font-weight: 500; white-space: nowrap;">%</span>
						</td>
					</tr>
					<tr>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Offline_recharge");?></td>
						<td  style="width:460px;display: flex;align-items: center;">
							<input type="radio" name="is_offline_recharge" style="margin: 0!important;" value="1" <?php if ($web['is_offline_recharge'] != 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("open");?>&nbsp;
							<input type="radio" name="is_offline_recharge" style="margin: 0 0 0 13px!important;" value="2" <?php if ($web['is_offline_recharge'] == 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("close");?>
						</td>

						<td align="right" width="220" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Charlie_recharge");?></td>
						<td style="width:460px;display: flex;align-items: center;">
							<input type="radio" name="is_charlie_recharge" style="margin: 0!important;"  value="1" <?php if ($web['is_charlie_recharge'] != 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("open");?>&nbsp;
							<input type="radio" name="is_charlie_recharge" style="margin: 0 0 0 13px!important;" value="2" <?php if ($web['is_charlie_recharge'] == 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("close");?>
						</td>

					</tr>
					<tr>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Bank_card_recharge");?></td>
						<td style="width:460px;display: flex;align-items: center;">
							<input type="radio" name="is_bank_card_recharge" style="margin: 0!important;"  value="1" <?php if ($web['is_bank_card_recharge'] != 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("open");?>&nbsp;
							<input type="radio" name="is_bank_card_recharge" style="margin: 0 0 0 13px!important;" value="2" <?php if ($web['is_bank_card_recharge'] == 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("close");?>
						</td>

						<td align="right" width="220" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("usdt_recharge");?></td>
						<td style="width:460px;display: flex;align-items: center;">
							<input type="radio" name="is_usdt_recharge" style="margin: 0!important;"  value="1" <?php if ($web['is_usdt_recharge'] != 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("open");?>&nbsp;
							<input type="radio" name="is_usdt_recharge" style="margin: 0 0 0 13px!important;" value="2" <?php if ($web['is_usdt_recharge'] == 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("close");?>
						</td>

					</tr>
					<tr>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Image_upload");?></td>
						<td id="is_upload_auth_pau" style="width:460px;display: flex;align-items: center;">
							<input type="radio" name="is_upload" value="1" style="margin: 0!important;" <?php if ($web['is_upload'] == 1) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("open");?>&nbsp;
							<input type="radio" name="is_upload" value="2" style="margin: 0 0 0 13px!important;" <?php if ($web['is_upload'] == 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("close");?>
						</td>
						<td width="220" align="right" style="text-align:right!important;"> <?php $this->lan("Winning_notice");?></td>
						<td style="display: flex;align-items: center;gap:10px;">
							<input type="radio" name="s_type" value="2" style="margin: 0!important;" <?php echo ($s_type == 2 ? 'checked' : ''); ?> />
							<?php $this->lan("open");?>
							<input type="radio" name="s_type" value="0" style="margin: 0 0 0 13px!important;" <?php echo ($s_type == 0 ? 'checked' : ''); ?> />
							<?php $this->lan("close");?>

						</td>

					</tr>
					<tr>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Backend_copyright");?></td>
						<td style="width:460px;display: flex;align-items: center;padding: 25px 0 !important;">
							<input type="radio" name="backend_copyright" value="1" style="margin: 0!important;" <?php if ($web['backend_copyright'] != 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("open");?>&nbsp;
							<input type="radio" name="backend_copyright" style="margin: 0 0 0 13px!important;" value="2" <?php if ($web['backend_copyright'] == 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("close");?>
						</td>
						<td width="220" align="right" style="text-align:right!important;"> <?php $this->lan("currency");?></td>
						<td style="display: flex;">
							<select name="web_language_currency" style="width:130px;text-align:center;margin:0!important;text-align: center !important;">
								<?php foreach ($languages as $key => $v) { ?>
									<option value="<?php echo $v['lg_value']; ?>" <?php if ($web['web_language_currency'] == $v['lg_value']) {
																						echo 'selected';
																					} ?>><?php echo $v['currency_unit']; ?></option>
								<?php } ?>
							</select>

						</td>

					</tr>
					<tr>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("User_order");?></td>
						<td style="width:460px;display: flex;align-items: center;padding: 25px 0 !important;">
							<input type="radio" name="is_shaidan" value="1" style="margin: 0!important;" <?php if ($web['is_shaidan'] != 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("open");?>&nbsp;
							<input type="radio" name="is_shaidan" value="2" style="margin: 0 0 0 13px!important;" <?php if ($web['is_shaidan'] == 2) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("close");?>
						</td>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Order_number_prefix_dp","直购订单号前缀");?></td>
						<td style="width:460px;display:flex;">
							<input type="text" class="input-text" name="order_number_prefix_dp" value="<?php echo $web['order_number_prefix_dp']; ?>" oninput="validateInput(this)" maxlength="6" style="width:200px !important; max-width:200px !important; min-width:200px !important; text-align:center !important;" />
						</td>
						<!-- <td width="220" align="right" style="padding-right: 10px"> <?php $this->lan("Backend_copyright");?></td>
						<td style="width:460px"></td> -->
					</tr>
					<tr>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"><?php $this->lan("Maintenance");?></td>
						<td style="display: flex;align-items: center;padding: 25px 0 !important;">
							<input class="checked_show" type="radio" style="margin: 0!important;" name="web_off" value="0" <?php if ($web['web_off'] == 0) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("open");?>
							<input class="checked_show" type="radio" style="margin: 0 0 0 13px!important;" name="web_off" value="1" <?php if ($web['web_off'] == 1) { ?> checked="checked" <?php } ?> />&nbsp; <?php $this->lan("close");?>&nbsp;
						</td>
						<td data-show="web_off" width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Maintenance_reasons");?></td>
						<td data-show="web_off" style="display: flex;"><input name="web_off_text" placeholder=" <?php $this->lan("Maintenance_reasons");?>" style="min-width:407px;width:510px" class="input-text" type="text" value="<?php echo $web['web_off_text']; ?>"></input></td>
					</tr>
					<tr>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("time_format");?></td>
						<td>
							<?php
							foreach ($languages as $k => $v) { ?>
								<div class="gjdys">

									<select name="web_time_format[<?php echo $v['lg_value']; ?>]" style="margin: 0 0 10px 0!important;">
										<option value="Y/m/d" <?php if ($web['web_time_format'][$v['lg_value']] == "Y/m/d") {
																	echo "selected";
																} ?>> <?php echo date("Y/m/d") ?></option>
										<option value="Y-m-d" <?php if ($web['web_time_format'][$v['lg_value']] == "Y-m-d") {
																	echo "selected";
																} ?>> <?php echo date("Y-m-d") ?></option>
										<option value="m/d/Y" <?php if ($web['web_time_format'][$v['lg_value']] == "m/d/Y") {
																	echo "selected";
																} ?>> <?php echo date("m/d/Y") ?></option>
										<option value="d/m/y" <?php if ($web['web_time_format'][$v['lg_value']] == "d/m/Y") {
																	echo "selected";
																} ?>> <?php echo date("d/m/Y") ?></option>
									</select>
									<span style="width: 50px; display:inline-block;text-align: left;margin: 0!important;"><?php echo $v['country']; ?></span>
								</div>
							<?php } ?>
							<span>
								<font></font>
							</span>
						</td>
						<td width="220" align="right" style="padding-right: 10px;text-align:right!important;"> <?php $this->lan("Search_terms");?></td>
						<td>
							<?php
							foreach ($languages as $k => $v) { ?>
								<div class="resouci">

									<div class="biqoqian">
										<span class="sjiap" style="margin: 0!important;"><input type="text" style="border: 0!important;" placeholder=" <?php $this->lan("tips11");?>"></span>
										<input type="hidden" name="web_search[<?php echo $v['lg_value']; ?>]" value="<?php if (isset($web['web_search'][$v['lg_value']])) {
																															echo $web['web_search'][$v['lg_value']];
																														} ?>" placeholder=" <?php $this->lan("tips12");?>" class="dohafngesr">
									</div>
									&nbsp;&nbsp;<span style="width: 50px; display:inline-block;    text-align: left;"><?php echo $v['country']; ?></span>
								</div>
							<?php } ?>
							<span>
								<font></font>
							</span>
						</td>
					</tr>

				<?php } ?>
			</table>
			<div style="text-align: center; margin-top: 30px; padding-top: 20px;">
				<input type="submit" class="button" name="dosubmit" value="<?php $this->lan("Submit");?>">
			</div>
		</form>

	</div><!--table-list end-->
	<script>
		// 更新语言选择框的国旗图标
		function updateFlagIcon(selectId, flagSpanId) {
			var select = document.getElementById(selectId);
			var flagSpan = document.getElementById(flagSpanId);
			if (select && flagSpan) {
				var selectedOption = select.options[select.selectedIndex];
				if (selectedOption) {
					// 优先使用SVG
					var flagSvg = selectedOption.getAttribute('data-flag-svg');
					var flagUrl = selectedOption.getAttribute('data-flag');
					
					if (flagSvg && flagSvg.trim() !== '') {
						// 使用内联SVG
						flagSpan.innerHTML = flagSvg;
					} else if (flagUrl && flagUrl.trim() !== '') {
						// 使用图片URL
						flagSpan.innerHTML = '<img src="' + flagUrl.replace(/"/g, '&quot;') + '" alt="" style="width:20px;height:15px;display:block;" onerror="this.style.display=\'none\';" />';
					} else {
						flagSpan.innerHTML = '';
					}
				} else {
					flagSpan.innerHTML = '';
				}
			}
		}
		function validateInput(input) {
          input.value = input.value.replace(/[^A-Za-z0-9]/g, '');
        }
		// 前端默认语言自定义下拉框功能
		$(document).ready(function() {
			// 点击前端默认语言下拉框
			$("#web_language_selected").on('click', function(e){
				e.stopPropagation();
				var $options = $("#web_language_options");
				$options.toggleClass('show');
				
				// 旋转箭头
				if($options.hasClass('show')){
					$(this).find('.custom-select-arrow').css('transform', 'rotate(180deg)');
				} else {
					$(this).find('.custom-select-arrow').css('transform', 'rotate(0deg)');
				}
			});
			
			// 点击前端默认语言选项
			$(document).on('click', '.custom-select-option-frontend', function(e){
				e.stopPropagation();
				var value = $(this).data('value');
				var flagUrl = $(this).data('flag');
				var flagSvg = $(this).data('flag-svg');
				var langText = $(this).find('.lang-name').text();
				
				// 更新选中状态
				$(".custom-select-option-frontend").removeClass('selected');
				$(this).addClass('selected');
				
				// 更新显示区域
				var $selected = $("#web_language_selected");
				$selected.find('.flag-icon').remove();
				
				if(flagSvg){
					$selected.prepend('<span class="flag-icon">' + flagSvg + '</span>');
				} else if(flagUrl){
					$selected.prepend('<img src="'+flagUrl+'" class="flag-icon" alt="flag" onerror="this.style.display=\'none\';" />');
				} else {
					$selected.prepend('<span class="flag-icon" style="background: #ccc; border-radius: 2px;"></span>');
				}
				$selected.find('.lang-name').text(langText);
				
				// 更新隐藏的select
				$("#web_language").val(value);
				
				// 隐藏下拉选项
				$("#web_language_options").removeClass('show');
				$("#web_language_selected").find('.custom-select-arrow').css('transform', 'rotate(0deg)');
			});
			
			// 点击页面其他地方，隐藏下拉选项
			$(document).on('click', function(e){
				if(!$(e.target).closest('.custom-language-select-frontend').length){
					$("#web_language_options").removeClass('show');
					$("#web_language_selected").find('.custom-select-arrow').css('transform', 'rotate(0deg)');
				}
			});
			
			// 中奖方式自定义下拉框功能
			// 点击中奖方式下拉框
			$("#winning_method_selected").on('click', function(e){
				e.stopPropagation();
				var $options = $("#winning_method_options");
				$options.toggleClass('show');
				
				// 旋转箭头
				if($options.hasClass('show')){
					$(this).find('.custom-select-arrow').css('transform', 'rotate(180deg)');
				} else {
					$(this).find('.custom-select-arrow').css('transform', 'rotate(0deg)');
				}
			});
			
			// 点击中奖方式选项
			$(document).on('click', '.custom-select-option-winning', function(e){
				e.stopPropagation();
				var value = $(this).data('value');
				var icon = $(this).data('icon');
				var methodText = $(this).find('.method-name').text();
				
				// 更新选中状态
				$(".custom-select-option-winning").removeClass('selected');
				$(this).addClass('selected');
				
				// 更新显示区域
				var $selected = $("#winning_method_selected");
				$selected.find('.method-icon').html(icon);
				$selected.find('.method-name').text(methodText);
				
				// 更新隐藏的select
				$("#winning_method").val(value);
				
				// 隐藏下拉选项
				$("#winning_method_options").removeClass('show');
				$("#winning_method_selected").find('.custom-select-arrow').css('transform', 'rotate(0deg)');
			});
			
			// 点击页面其他地方，隐藏下拉选项
			$(document).on('click', function(e){
				if(!$(e.target).closest('.custom-winning-method-select').length){
					$("#winning_method_options").removeClass('show');
					$("#winning_method_selected").find('.custom-select-arrow').css('transform', 'rotate(0deg)');
				}
			});
			
			// 后端默认语言自定义下拉框功能
			// 点击后端默认语言下拉框
			$("#web_language_backend_selected").on('click', function(e){
				e.stopPropagation();
				var $options = $("#web_language_backend_options");
				$options.toggleClass('show');
				
				// 旋转箭头
				if($options.hasClass('show')){
					$(this).find('.custom-select-arrow').css('transform', 'rotate(180deg)');
				} else {
					$(this).find('.custom-select-arrow').css('transform', 'rotate(0deg)');
				}
			});
			
			// 点击后端默认语言选项
			$(document).on('click', '.custom-select-option-backend', function(e){
				e.stopPropagation();
				var value = $(this).data('value');
				var flagUrl = $(this).data('flag');
				var flagSvg = $(this).data('flag-svg');
				var langText = $(this).find('.lang-name').text();
				
				// 更新选中状态
				$(".custom-select-option-backend").removeClass('selected');
				$(this).addClass('selected');
				
				// 更新显示区域
				var $selected = $("#web_language_backend_selected");
				$selected.find('.flag-icon').remove();
				
				if(flagSvg){
					$selected.prepend('<span class="flag-icon">' + flagSvg + '</span>');
				} else if(flagUrl){
					$selected.prepend('<img src="'+flagUrl+'" class="flag-icon" alt="flag" onerror="this.style.display=\'none\';" />');
				} else {
					$selected.prepend('<span class="flag-icon" style="background: #ccc; border-radius: 2px;"></span>');
				}
				$selected.find('.lang-name').text(langText);
				
				// 更新隐藏的select
				$("#web_language_backend").val(value);
				
				// 隐藏下拉选项
				$("#web_language_backend_options").removeClass('show');
				$("#web_language_backend_selected").find('.custom-select-arrow').css('transform', 'rotate(0deg)');
			});
			
			// 点击页面其他地方，隐藏后端默认语言下拉选项
			$(document).on('click', function(e){
				if(!$(e.target).closest('.custom-language-select-backend').length){
					$("#web_language_backend_options").removeClass('show');
					$("#web_language_backend_selected").find('.custom-select-arrow').css('transform', 'rotate(0deg)');
				}
			});
			
			// 更新开奖方式图标
			function updateWinningMethodIcon() {
				var select = document.getElementById('winning_method');
				var iconSpan = document.getElementById('winning_method_icon');
				if (select && iconSpan) {
					var selectedValue = select.value;
					var iconHtml = '';
					if (selectedValue == '1') {
						// 余数开奖方式图标
						iconHtml = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 17L12 22L22 17" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 12L12 17L22 12" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
					} else if (selectedValue == '2') {
						// 比特币开奖方式图标
						iconHtml = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke="#f59e0b" stroke-width="2"/><path d="M12 6V12L16 14" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="12" r="2" fill="#f59e0b"/></svg>';
					}
					iconSpan.innerHTML = iconHtml;
				}
			}
			
			// 初始化开奖方式图标
			updateWinningMethodIcon();
			
			// 监听开奖方式选择变化
			$('#winning_method').on('change', function() {
				updateWinningMethodIcon();
			});
		});
		
		// 域名
		$("input[name='domain_k']").change(function(){
			var domain_k = $(this).val();
			domain_k = '<?php echo G_HTTP; ?>' + domain_k;
            $('input[name="web_path"]').val(domain_k);
		})
		var imglist = $("img")
		var host = <?php echo "'" . G_UPLOAD_PATH . "'"; ?>

		$("img").on("error", function() {
			$(this).attr("src", host + "/photo/ontfound.png")
		})
		$("#module").change(function() {
			if ($(this).val() == "vue") {
				$("#domain_m").val('vue');
				$("#domain_c").val('index');
			} else {
				$("#domain_m").val('mobile');
				$("#domain_c").val('mobile');
			}
		})
		document.getElementById("is_upload_auth_pau").addEventListener("click", function(e) {
			if (e.target.tagName == "INPUT") {
				var isup = $('#is_upload_auth_pau_input').val()
				var value = e.target.value
				if (isup == 2 && value == 1) {
					gouge()
				}

			}
		})
		$('.biqoqian').click(function() {
			$(this).addClass('shuruon')
			$(this).find('.sjiap input').focus();
		})
		$('.sjiap input').on('keypress', function(e) {
			console.log(e)
			var keyCode = e.which;
			// 如果按下的是逗号或回车键，则触发事件
			if (keyCode === 44 || keyCode === 13) {
				if ($(this).val()=='') return
                $(this).parent().before('<div class="sinop" style="margin: 0 5px 0 0!important;"><div>' + $(this).val() + '</div><span class="inpsha">×</span></div>')
                var grandParent = $(this).parent().parent().find('.dohafngesr');
    		        var tiselistval = grandParent.val()
    		        if (tiselistval == '') {
    		            grandParent.val($(this).val())
    		        } else {
    		            grandParent.val(tiselistval+','+$(this).val())
    		        }
                $(this).val('')
                cliinpchama()
                e.preventDefault();
			}
		})
		$('.sjiap input').blur(function() {
			// 在这里编写失去焦点时想要执行的代码
			$(this).parent().parent().removeClass('shuruon')
		});

		function cliinpchama() {
			$('.inpsha').click(function() {
				var tpdiv = $(this).parent()
				tpdiv.find('input').val('')
				var trsel = tpdiv.parent().find('.sinop div')
				var thufg = ''
				trsel.map(function(index) {
					if (thufg == '') {
						thufg = $(this).val()
					} else if ($(this).val() != '') {
						thufg += ',' + $(this).val()
					}
				})
				tpdiv.parent().find('.dohafngesr').val(thufg)
				tpdiv.remove()
			})
		}
		$('.dohafngesr').parent().each(function(index) {
			var tjddiv = $(this).find('.sjiap')
			var czdval = $(this).find('.dohafngesr').val()
			if (czdval == '') return
			$.each(czdval.split(','), function(ind, ele) {
				tjddiv.before('<div class="sinop" style="margin: 0 5px 0 0!important;"><div>' + ele + '</div><span class="inpsha">×</span></div>')
			})
		});
		cliinpchama()

		// //线下兑换
		// $("input[name='online_exchange']").click(function() {
		// 	chagneStatus(".online_exchange", $(this).val())
		// })
		// $("input[name='online_exchange']").click()

		// function chagneStatus(that, status) {
		// 	if (status == 1) {
		// 		$(that).show()
		// 	} else {
		// 		$(that).hide()
		// 	}
		// }

	</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>