<!doctype html>
<html lang="en">
<head>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/to-update.css" type="text/css">
	<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
<!--	<script type="text/javascript" charset="utf-8" src="--><?php //echo G_PLUGIN_PATH; ?><!--/calendar/calendar.js"></script>-->
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
	<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/circleChart.min.js"></script>
    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/messageModal/font/iconfont.css" type="text/css">
    <script src="<?php echo G_PLUGIN_PATH; ?>/messageModal/message.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/progress.js"></script>
<!--    <script src="--><?php //echo G_PLUGIN_PATH; ?><!--/laydate/laydate.js"></script>-->
    <title>数据优化</title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <style>
        .rw_title{
            display: inline-block;
            font-size: 20px;
        }
        .rw_title span{
            margin: 0 5px;
            color: red;
        }
        .rw_title :hover{
            cursor:pointer;
        }
        .layui-btn-danger {
            background-color: red;
        }
    </style>
</head>

<body>
    <div class="header lr10">
    	<!--<h3 class="nav_icon"><?php $this->lan("database_optimization");?> </h3>-->
     <!--   <span class="span_fenge lr10"></span><a href="#"><?php $this->lan("database_optimization");?></a><span class="span_fenge lr5"></span>-->
     <?php echo $this->headerment(); ?>
    </div>
	<div class="bk10"></div>
	<div style="margin:5px ;display: flex;">
      <form action='/member/member/clear_r' method='post' style='margin-top:-2px;margin-left:5px' class='posttimeform'>
        <?php $this->lan("list_clear"); ?>
        <div class='posttime' style="margin-left:5px">
          <input name="search_time" type="text" id="search_time" class="input-text" readonly="readonly" value="" />
        </div>
        <script type="text/javascript">
          date = new Date();
          Calendar.setup({
            inputField: "search_time",
            ifFormat: "%Y-%m-%d",
            showsTime: true,
            timeFormat: "24"
          });
        </script>
        <input class="button" type="submit" name="searchsubmit" value="<?php $this->lan("clear"); ?>" style="height: 32px;margin-top: -2px;" />
        <!--<span style="color:red;font-weight:bold;"> 如删除选择3月10日，则会删除10号以前的记录！</span>-->
        <div data-title="<?php $this->lan("tips10"); ?>" style="width:200px" class="tip">
          <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
        </div>
        <!--<span style="color:red;font-weight:bold;"> <?php $this->lan("tips"); ?></span>-->
      </form>
    </div>
	<div class="baset">
		<div class="dataform">
			<form action="#" method="post">
				<span class="size16"><?php $this->lan("Select_date");?> </span>
			    <input  type="text" id="posttime1"  value="" style="padding-left: 5px" />
				<span class="size14"> <?php $this->lan("tips");?><span class="colorD9001B" id="dayb">0</span> <?php $this->lan("Data");?></span>
                <input type="hidden" id="deltiem" value="">
                <p style="color: #aa1111"><?php $this->lan("tips1");?></p>
			</form>

		</div>
		<div class="posirela">
			<div class="circlewidth">
				<div class="chartimg">
                    <canvas id="css"  width="230" height="230"></canvas>
				</div>
<!--				<div class="circleChart" id="circle1"></div>-->

			</div>

			<div class="allnumber">
                <div class="layui-container" style="width: 830px;margin: 1px auto">
                    <div class="layui-row">
                        <div class="layui-col-md3">
                            <div class="rw_title" style="font-size: 12px"> <?php $this->lan("All");?><span><?php  echo count($sqldata);?></span> <?php $this->lan("Tasks");?><span style="font-size: 12px"><?php $this->lan("see");?></span> </div>
                        </div>
                        <div class="layui-col-md3">
                            <?php $this->lan("Site_disk_size");?><span id="_Z">0%</span>
                        </div>
                        <div class="layui-col-md3">
                            <?php $this->lan("Site_disk_used");?><span id="_Y">0%</span>
                        </div>
                        <div class="layui-col-md3">
                            <?php $this->lan("Accumulated_deletion");?> <span style="color:red;"><?php echo $delnum?:0;?></span> <?php $this->lan("Article_data");?>
                        </div>
                    </div>
                </div>

            </div>
			<div class="checkarr">
                <div id="tablist"></div>
				<button class="bottomchec layui-btn layui-btn-danger"><?php $this->lan("clean");?></button>

			</div>
		</div>
	</div>

	<div class="poping" style="display: none;">
		
	</div>
<script>
$(function() {
    $.get('<?php echo G_MODULE_PATH; ?>/database/getBbInfo', function(res){
        var ress = JSON.parse(res);
        var datas = ress.data;
        if(datas.http == 0){
            layer.alert('服务异常请联系服务商 此时不能进行数据删除')
        }
        progress(datas.dir.BFB)
        // $('.chartimg span').text(datas.dir.BFB+'%');
        $('#_Y').text(datas.dir.Y_);
        $('#_Z').text(datas.dir.Z_);
        var div = ''
        var fordata = datas.tbsize
        for( var i in  fordata) {
            var ii = i*1+1;
            var tbname =fordata[i]['tab'];
            // console.log(tbname)
            var sqlT =datas.tab[tbname]

            var t = sqlT?sqlT.time:'暂无记录'
            // var t = '暂无记录'
            div += '<div class="textchec">' +
                '<input type="checkbox" name="tablb" value="'+fordata[i]['tabname']+','+fordata[i]['tab']+'">' +
				// '<input type="hidden" name="data" value="'+fordata[i]['tabname']+'">'+
                '<span class="namemargin">'+fordata[i]['tabname']+'</span>' +
                '<div class="lengover"><span style="width:'+datas.tbsize[i].percent+'%"></span></div>' +
                '<span class="color666">'+datas.tbsize[i].size  +'【'+t+'】'+'</span>' +

                '</div>'

            // $('.checkarr>div:nth-child('+ii+')>.lengover>span').css('width',datas.tbsize[i].percent+'%');
        }
        $('#tablist').append(div)

    });

    $(".bottomchec").click(function(){
        var table = []
        $('input[name="tablb"]:checked').each(function () {
            table.push($(this).val());
        })
        var time = $('#deltiem').val();
        $(".poping").css('display','block');
        var data  = {time:time,table:table}
        if(time==''){
            toast('请选择需要清理的日期');
            return;
        }
        if(table==[]){
            toast('请选择需要清理的数据');
            return;
        }
        confirm({
            title: '温馨提示',
            content: '此操作会永久删除数据！！',
            doneText: '确认',
            cancelText: '取消'
        }).then(() => {
            $.post('<?php echo G_MODULE_PATH; ?>/database/sbmitclean',data,function (result) {
                var res = JSON.parse(result)
                toast(res.msg)
            })
        }).catch(() => {
            console.log('已取消')
            isclick = false
        })

    });

    function progress(num){
        var p2 = new Progress({
            el:'css',//canvas元素id
            deg:num,//绘制角度
            timer:5,//绘制时间
            lineWidth:20,//线宽
            lineBgColor:'#e2e2e2',//底圆颜色
            lineColor:'#00d63c',//动态圆颜色
            textColor:'#000',//文本颜色
            fontSize:40,//字体大小
            circleRadius:100//圆半径
        });
    }
    layui.use(['layer','laydate'], function(){
        var laydate = layui.laydate;

        var date7 = getDateTime('');
        var day7 = getDateTime(-7)
        $('#deltiem').val(day7)
        var days77  = DateDiff(date7,day7)
        $('#dayb').html(7)

        laydate.render({
            elem: '#posttime1'
            ,theme: '#393D49'
            ,value: new Date(new Date() - 86400 * 7 * 1000)
            ,type: 'datetime'
            ,max:0
            ,btns: ['confirm']
            ,done:function (value, date) {
                if(value == ''){
                    lay("#dayb").html(0)
                    return
                }
                $('#deltiem').val(value);
                var date = getDateTime('');
                var day  = DateDiff(date,value)
                lay("#dayb").html(day)
            }
            ,change: function(value, date){ //监听日期被切换
                if(value == ''){
                    lay("#dayb").html(0)
                    return
                }
                $('#deltiem').val(value);
                var date = getDateTime('');
                var day  = DateDiff(date,value)
               lay("#dayb").html(day)
            }
        });
        laydate.render({
            elem: '#search_time'
            ,theme: '#393D49'
            ,value: new Date(new Date() - 86400 * 14 * 1000)
            ,type: 'datetime'
            ,max:0
            ,btns: ['confirm']
            ,done:function (value, date) {
                if(value == ''){
                    lay("#dayb").html(0)
                    return
                }
                // $('#deltiem').val(value);
                var date = getDateTime('');
                var day  = DateDiff(date,value)
                // lay("#dayb").html(day)
            }
            ,change: function(value, date){ //监听日期被切换
                if(value == ''){
                    lay("#dayb").html(0)
                    return
                }
                // $('#deltiem').val(value);
                var date = getDateTime('');
                var day  = DateDiff(date,value)
            //   lay("#dayb").html(day)
            }
        });
        /**
         * 计算天数差的函数，通用
         * @param sDate1
         * @param sDate2
         * @returns {Number}天数
         */
        function DateDiff(sDate1,  sDate2){ //sDate1和sDate2是2006-12-18格式
            var  aDate,  oDate1,  oDate2,  iDays
            aDate  =  sDate1.split("-")
            oDate1  =  new  Date(aDate[1]  +  '-'  +  aDate[2]  +  '-'  +  aDate[0])    //转换为12-18-2006格式
            aDate  =  sDate2.split("-")
            oDate2  =  new  Date(aDate[1]  +  '-'  +  aDate[2]  +  '-'  +  aDate[0])
            iDays  =  parseInt(Math.abs(oDate1  -  oDate2)  /  1000  /  60  /  60  /24)    //把相差的毫秒数转换为天数
            return  iDays
        }

        /**
         * 获取日期
         * @param type
         * @returns {string|number}
         */
        function getDateTime (type) {
            var date = new Date();
            var hengGang = "-";
            var maoHao = ":";
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var curDate = date.getDate();
            var curHours = date.getHours();
            var curMinutes = date.getMinutes();
            var curSeconds = date.getSeconds();

            if (month >= 1 && month <= 9) {
                month = "0" + month;
            }
            if (curDate >= 0 && curDate <= 9) {
                curDate = "0" + curDate;
            }
            if (curHours >= 0 && curHours <= 9) {
                curHours = "0" + curHours;
            }
            if (curMinutes >= 0 && curMinutes <= 9) {
                curMinutes = "0" + curMinutes;
            }
            if (curSeconds >= 0 && curSeconds <= 9) {
                curSeconds = "0" + curSeconds;
            }
            var currentdate = "";
            if (type == "year") {
                currentdate = year;
                return currentdate;
            } else if (type == "month") {
                currentdate = year + hengGang + month;
                return currentdate;
            } else {
                currentdate = year + hengGang + month + hengGang + curDate + " " + curHours + maoHao + curMinutes + maoHao + curSeconds;
                return currentdate;
            }
        }
        $(".rw_title").on('click',function () {
			var layer = layui.layer;
			var tit = `
			<span style="color: red"> 如有任务一直在执行 请先停止再重启<span/>
			`
    		layer.open({
    			type: 2,

    			title:['任务中心 '+tit],
    			area: ['1000px', '450px'],
    			fixed: true, //不固定
    			maxmin: true,
    			content: '<?php echo G_MODULE_PATH; ?>/database/Datalist'
    		});
    	})
    });
	
})
</script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>
</html>