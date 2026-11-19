<!DOCTYPE html>
<html>
<head>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">

    <meta charset="utf-8">
    <title>附件管理</title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo G_GLOBAL_STYLE; ?>/global/selectImg/css/selectImg.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo G_GLOBAL_STYLE; ?>/global/selectImg/css/iconfont.css"/>
</head>
<body>
<div>

    <blockquote class="layui-elem-quote">
        <button id="delimg" class="layui-btn layui-btn-radius  layui-btn-sm layui-btn-danger">删除选中</button>
        <button id="cancel"  class="layui-btn layui-btn-radius  layui-btn-sm ">取消选择</button>
    </blockquote>
</div>
<div class="selectImgDiv" id="selectItemDiv">
</div>

<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo G_GLOBAL_STYLE; ?>/global/selectImg/js/selectImg.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>

<script>
    $(function () {

        $('#delimg').on('click',function () {
            const dom = selectImgTake.getSelectImgs('selectItemDiv')
            if(dom.length<1){
                layer.msg('至少选中一张图片')
                return
            }
            var  ids = [];
            dom.map((k,v) =>{
               ids.push(v.dataset.id)
            })
            $.post('<?php echo G_MODULE_PATH; ?>/attachment/del2',{id:ids},function (result) {
                var result = JSON.parse(result)
                if(result.code== 1){
                    layer.msg(result.msg)
                   setTimeout(()=>{
                       location.reload();
                   },1500)
                }
                layer.msg(result.msg)
            })
        })
        $('#circle1').on('click',function () {
            selectImgTake.cancelInit('selectItemDiv')
            setTimeout(()=>{
                selectImgTake.init('selectItemDiv',20)
            },500)
        })
        var p = 1;
        init(p)
        $(window).scroll(function() {
            var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var windowHeight = $(this).height();
            if(scrollTop == 0){
                console.log('触顶');
            }
            if(scrollTop + windowHeight == scrollHeight  ){
                p=p+1;
                init(p)
            }
        })
        function init() {
            $.get('<?php echo G_MODULE_PATH; ?>/attachment/GetFile',function (result) {
                const results = JSON.parse(result)
                var imgdir = results.data.data

                f(imgdir)
                console.log(arrs)
                return
                for (var i=0;i<arrs.length;i++){
                    var html  = `
                             <div class="item" data-id="${arrs[i]}">
                                <div class="img_show">
                                    <img src="/statics/uploads/shopimg${arrs[i]}"/>
                                </div>
                                <div class="img_title">

                                </div>
                                <div class="img_isCheck">
                                    <i class="iconfont icon-xuanzhong"></i>
                                </div>
                            </div>
                          `;
                    $('#selectItemDiv').append(html)
                }
                setTimeout(()=>{
                    selectImgTake.init('selectItemDiv',20)
                },500)
            })
        }

        var arrs = [];
        var dir =''
        var is = 0
        function f(obj) {
            for( var i  in  obj ) {
                if  (typeof obj[i] === "object") {
                    dir+='/'+i
                    f (obj[i]);
                }else{
                    arrs.push(dir+'/'+obj[i])
                    console.log(Object.values(i))
                    if (obj[i].length == is){
                        console.log(Object.values(i).length)
                        dir =''
                        is =0
                    }
                }
                ++is
            }
        };
        function init2(p) {
            $.post('<?php echo G_MODULE_PATH; ?>/attachment/GetFile',{page:p},function (result) {
                const results = JSON.parse(result)
                if(results.data.length){
                    var  data = results.data
                     for (var i=0;i<data.length;i++){
                         var da = data[i]
                         var html  = `
                             <div class="item" data-id="${da.id}">
                                <div class="img_show">
                                    <img src="/statics/uploads/${da.topic}/${da.url}"/>
                                </div>
                                <div class="img_title">
                                   ${da.name}
                                </div>
                                <div class="img_isCheck">
                                    <i class="iconfont icon-xuanzhong"></i>
                                </div>
                            </div>
                          `;
                         // console.log(html)
                         $('#selectItemDiv').append(html)
                     }
                    setTimeout(()=>{
                        selectImgTake.init('selectItemDiv',20)
                    },500)
                }else {
                    layer.msg('没有更多了')
                }
            })
        }

    })
</script>
<script>
    layui.use(['table','util'], function(){
        var table = layui.table
            ,util = layui.util;
        var tableIns = table.render({
            elem: '#test'
            ,url:'<?php echo G_MODULE_PATH; ?>/attachment/getDelData'
            ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
            ,defaultToolbar: ['filter', 'exports', 'print', { //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
                title: '提示'
                ,layEvent: 'LAYTABLE_TIPS'
                ,icon: 'layui-icon-tips'
            }]
            ,title: '附件管理'
            ,cols: [[
                {type: 'checkbox', fixed: 'left'}
                ,{field:'id', title:'ID', width:80, fixed: 'left', unresize: true, sort: true}
                ,{field:'user_id', title:'用户ID', width:120}
                ,{field:'topic', title:'分组', width:120}
                ,{field:'url', title:'图片', width:100,templet: function(d){
                    var imgurl  = '/statics/uploads/'+d.topic+'/'+d.url
                    return '<img width="80px" src='+imgurl+'>'
                }}
                ,{field:'width', title:'宽度', width:80, edit: 'text', sort: true}
                ,{field:'height', title:'高度', width:100}
                ,{field:'name', title:'文件名',edit:"text"}
                ,{field:'size', title:'文件大小', width:100, sort: true,templet:function(d){
                    var s = d.size/1000/1000
                    return s.toFixed(2)+" MB"
                }}
                ,{field:'createtime', title:'上传时间', width:200}
                ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:150}
            ]]
            ,page: true
        });

        //头工具栏事件
        table.on('toolbar(test)', function(obj){
            var checkStatus = table.checkStatus(obj.config.id);
            switch(obj.event){
                case 'getCheckData':
                    var data = checkStatus.data;
                    var idss = data.map(v=> {
                        return v.id
                    })
                    if(idss.length<1){
                        return layer.msg('没有选择')
                    }

                    layer.confirm('真的删除行么', function(index){
                        layer.confirm('删除同时会删除'+data.length+' 个相应文件', function(index){
                            $.post('<?php echo G_MODULE_PATH; ?>/attachment/del',{id:idss.toString()},function (result) {
                                var result = JSON.parse(result)
                                if(result.code== 1){
                                    layer.close(index);
                                    tableIns.reload();
                                }
                                layer.msg(result.msg)
                            })
                        });
                    });

                    break;
                case 'getCheckLength':
                    var data = checkStatus.data;
                    layer.msg('选中了：'+ data.length + ' 个');
                    break;
                case 'isAll':
                    layer.msg(checkStatus.isAll ? '全选': '未全选');
                    break;

                //自定义头工具栏右侧图标 - 提示
                case 'LAYTABLE_TIPS':
                    layer.alert('进行删除操作同时会删除对应的文件');
                    break;
            };
        });
        //监听单元格编辑
        table.on('edit(test)', function(obj){
            var value = obj.value //得到修改后的值
                ,data = obj.data //得到所在行所有键值
                ,field = obj.field //得到字段
                ,v = util.escape(value);
            console.log('[ID: '+ data.id +'] ' + field + ' 字段更改值为：');//
            $.post('<?php echo G_MODULE_PATH; ?>/attachment/edit',{id:data.id,name:field,value:v},function (result) {
                var result = JSON.parse(result)
                if(result.code== 1){
                    tableIns.reload();
                }
                layer.msg(result.msg)
            })
        });

        //监听行工具事件
        table.on('tool(test)', function(obj){
            var data = obj.data;
            if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    layer.confirm('删除同时会删除相应文件', function(index){
                        $.post('<?php echo G_MODULE_PATH; ?>/attachment/del',{id:data.id},function (result) {
                            var result = JSON.parse(result)
                            if(result.code== 1){
                                obj.del();
                                layer.close(index);
                                tableIns.reload();
                            }
                            layer.msg(result.msg)
                        })

                    });
                });
            } else if(obj.event === 'edit'){
                console.log(data)
                layer.prompt({
                    formType: 2
                    ,value: data.email
                }, function(value, index){
                    obj.update({
                        email: value
                    });
                    layer.close(index);
                });
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