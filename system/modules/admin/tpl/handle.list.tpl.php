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
    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_TEMPLATES_JS; ?>/layui/css/layui.css" type="text/css">
    <style>
        body {
            font-size: 12px;
        }
        td {line-height: 20px;}
        /* 模态窗口背景 */
        .modal {
            display: none;
            /* 默认隐藏 */
            position: fixed;
            /* 固定定位 */
            z-index: 1;
            /* 置于顶层 */
            left: 0;
            top: 0;
            width: 100%;
            /* 全屏宽度 */
            height: 100%;
            /* 全屏高度 */
            overflow: auto;
            /* 允许滚动 */
            background-color: rgba(0, 0, 0, 0.4);
            /* 半透明黑色 */
        }

        /* 模态窗口内容 */
        .modalcontent {
            background-color: #fefefe;
            margin: 15% auto;
            /* 居中显示 */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            /* 宽度 */
        }

        /* 关闭按钮 */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        tbody tr {
            line-height: 30px;
            height: 30px;
        }
    </style>
</head>

<body>
    <div class="header lr10">
        <?php echo $this->headerment(); ?>
        <span class="lr10"> </span><span class="lr10"> </span>

    </div>
    <div class="bk10"></div>
    <div class="header-data lr10">
    <div class="table_form ">
    <form action="#" method="post" style="display:inline-block;margin-top:3px ">
        <div style="display:flex;white-space: nowrap;">
            <div class="posttime">
            <input name="posttime1" type="text" id="posttime1" class="input-text" placeholder="<?php $this->lan("start_time"); ?>" readonly="readonly" value="<?php echo !empty($posttime1) ? date("Y-m-d H:i:s", $posttime1) : '' ?>" />
            -
            <input name="posttime2" type="text" id="posttime2" class="input-text" placeholder="<?php $this->lan("end_time"); ?>" readonly="readonly" value="<?php echo !empty($posttime2) ? date("Y-m-d H:i:s", $posttime2) : '' ?>" />
            </div>
            <script type="text/javascript">
            date = new Date();
            Calendar.setup({
                inputField: "posttime1",
                ifFormat: "%Y-%m-%d %H:%M:%S",
                showsTime: true,
                timeFormat: "24"
            });
            Calendar.setup({
                inputField: "posttime2",
                ifFormat: "%Y-%m-%d %H:%M:%S",
                showsTime: true,
                timeFormat: "24"
            });
            </script>
            <select name="type">
                <?php
                foreach ($state as $key => $vl) {
                    if ($key == $type) {
                        $sel = "selected";
                    } else {
                        $sel = '';
                    }
                    echo "<option value='$key' $sel>$vl</option>";
                }
                ?>
            </select>
            <select name="sel_status">
                <option value=""><?php $this->lan("select","请选择"); ?></option>
                <?php
                foreach ($status_text as $key => $vl) {
                    if ($key == $sel_status) {
                        $sel = "selected";
                    } else {
                        $sel = '';
                    }
                    echo "<option value='$key' $sel>$vl</option>";
                }
                ?>
            </select>
            <?php if($type==31){?>
            
            <!--只有待提现订单出现 选择渠道商-->
            <select name="chongzhi">
                <option value=""><?php $this->lan("select","选择渠道商"); ?></option>
              <?php foreach($channels?:[] as $vl){
              $select = ($chongzhi == 'channel_'.$vl['uid']) ? 'selected' : '';
              $channel = $this->lgs['Channel']?:"Channel";
              echo "<option value='channel_{$vl['uid']}' $select >{$channel}-{$vl['username']}</option>";
              }?>
            </select>
            <?}?>
            <input type="submit" value="确定" name="paixu_submit" class="button" />
            <?php if($type==31){?>
            <!--已经出款的总金额-->
            <span style="color:#444;display: inline-block;margin-top: 10px;"><?php $this->lan("Total_amount_already_disbursed");?>: <?php echo $cashot['total']?:0;?>₫</span>
            <?}?>
        </div>
        </form>
    </div>
    </div>

    <div class="table-list lr10">
        <!--start-->
        <?php include $handle; ?>
        <div id="pages"><ul><li><?php $this->lan("common");?> <?php echo $total; ?> <?php $this->lan("strip");?></li><?php echo $page->show('three', 'li'); ?></ul></div>
    </div>
    <!-- 详情 -->
    <div id="detailModal" class="modal">
        <div class="modalcontent">
            <span class="close">&times;</span>
            <div class="contentText" id="contentText"></div>
        </div>
    </div>
    <!-- 审核操作 -->
    <div id="myModal" class="modal">
        <div class="modalcontent">
            <span class="close">&times;</span>
            <form class="layui-form layui-form-pane" action="#" id="operation" method="post">
                <input type="hidden" name="m-id">
                <div class="layui-form-item">
                    <label class="layui-form-label">选择操作</label>
                    <div class="layui-input-block">
                        <select name="status" lay-filter="aihao" id='status' style="display: block;">
                            <option value="2">通过</option>
                            <option value="3">不通过</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">备注</label>
                    <div class="layui-input-block">
                        <input type="text" name="desc" placeholder="请输入备注" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">安全密码</label>
                    <div class="layui-input-block">
                        <input type="password" name="secure_password" placeholder="请输入安全密码" class="layui-input" required>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="m-submit">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
    <script>
        // 获取模态窗口元素
        var modal = document.getElementById("myModal");
        // 获取触发模态窗口的按钮元素
        // 获取关闭按钮元素
        var span = document.getElementsByClassName("close")[0];

        // 当用户点击按钮时，显示模态窗口
        $(".myBtn").click(function() {
            var id = $(this).attr("data-id");
            $("input[name=m-id]").val(id);
            modal.style.display = "block";
        })
        $("#m-submit").click(function(){
            var id = $("input[name=m-id]").val();
            var status = $("select[name=status]").val();
            var desc = $("input[name=desc]").val();
            var secure_password = $("input[name=secure_password]").val();
            $.post("<?php echo G_MODULE_PATH . "/handle/examine"; ?>",{id:id,status:status,desc:desc,secure_password:secure_password},function(res){
                res =JSON.parse(res)
                alert(res.msg)
                modal.style.display = "none";
                location.reload();
            })
        })

        // 当用户点击关闭按钮时，关闭模态窗口
        $(".close").click(function(){
            modal.style.display = "none";
            $("#detailModal").css("display","none")
        })
  /*       span.onclick = function() {
            modal.style.display = "none";
        } */
        // 当用户点击模态窗口之外的区域时，关闭模态窗口
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
            if(event.target == document.getElementById("detailModal")){
                 $("#detailModal").css("display","none")
            }
        }

        //详情窗口显示
        $(".detailShow").click(function(){
            var url = $(this).attr("data-url");
            $.post(url,function(res){
                res =JSON.parse(res)
                if(res.code == 200){
                    $(".contentText").html(res.data)
                }else{
                    $(".contentText").html(res.msg)
                }
            })
            $("#detailModal").css("display","block")
        })
    </script>
<style>
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
</script>
</html>