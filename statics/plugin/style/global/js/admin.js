const ScriptArr ={
    jQuery:'/statics/plugin/style/global/js/jquery-1.8.3.min.js',
    layui:'/statics/plugin/style/global/layui/layui.all.js',
    form:'/statics/plugin/style/global/js/form.js',
    sweetalert2:'/statics/plugin/style/global/hsycmsAalert/dist/sweetalert2.min.js',
}
const Win = window
const LinkStyleArr ={
    global:"/statics/plugin/style/global/css/global.css",
    // style:"/statics/plugin/style/global/css/style.css",
    layui: "/statics/plugin/style/global/layui/css/layui.css",
    admincss: "/statics/plugin/style/global/css/admin.css",
    sweetalert2: "/statics/plugin/style/global/hsycmsAalert/dist/sweetalert2.min.css",
}
function getDomain(){
    var url = document.location.toString();
    var arrUrl = url.split("//");
    return arrUrl[0]+"//"+document.domain
}
function addLink (src){
    var link  = document.createElement("link");
    link.type = "text/css";
    link.rel = "stylesheet";
    link.href = src;
    document.getElementsByTagName('head')[0].append(link);
}

function addScript(src){
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.async = true;
    script.src = src;
    document.getElementsByTagName('head')[0].append(script);
}
for (let key in LinkStyleArr){
    var src = getDomain()+LinkStyleArr[key]
    addLink(LinkStyleArr[key])
}
for (let key in ScriptArr){
    var src = getDomain()+ScriptArr[key]
    addScript(ScriptArr[key])
}

//判断字符是否为空的方法
function isEmpty(obj){
    if(typeof obj == "undefined" || obj == null || obj == ""){
        return true;
    }else{
        return false;
    }
}

/**
 * 返回范围随机数
 * @param b 最小值
 * @param e 最大值
 * @returns {string|number}
 */
function diu_Randomize(b,e){
    if (!b && b!=0 || !e){ return  "?" ;}
    return  Math.floor( ( Math.random() * e ) + b );
}
/**
 * 单个文件上传
 * @param dialog_title 上传对话框标题
 * @param input_selector 图片容器
 * @param filetype 文件类型，image,video,audio,file
 * @param extra_params 额外参数，'上传位置'
 * @param upload_size 允许文件大小 单位 MB 默认 10
 */
function uploadimgOpenBox(dialog_title, input_selector, filetype,extra_params,upload_size) {
    upload_size = !isEmpty(upload_size) ? upload_size : 10;
    var html = `
        <div class="layui-upload-drag" id="upload_box_aw" style="position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);">
          <i id="upload_icon" class="layui-icon"></i>
          <p id="upload_title_tip">点击上传，或将文件拖拽到此处</p>
          <div class="layui-hide"  id="uploadDemoView_aw">
            <hr>
          </div>
        </div>
        <div id="uploadimgarr">
         <img src="" id="uploadDemoView_aw_img" alt="" style="max-width: 130px">
        </div>
    `
    var updata = {}
    var uploadmodel = Win.layer.open({
        type: 1,
        title: !isEmpty(dialog_title) ? dialog_title : "上传图片",
        closeBtn: 1,
        btn: ['确定'],
        skin: 'layui-layer-rim', //加上边框
        area: ['520px', '340px'], //宽高
        shadeClose: false,
        content: html,
        yes: function () {
            layer.close(uploadmodel);
        }
    });

    var paramdata = {
        url: !isEmpty(extra_params) ? extra_params : 'shopimg'
    }
    filetype = !isEmpty(filetype) ? filetype : 'images';
    window.layui.upload.render({
        elem: '#upload_box_aw'
        , accept: filetype
        , size: upload_size * 1024
        , data: paramdata
        , url: getDomain() + "/api/tjupload/upimage"//上传接口。
        , done: function (res) {
            updata = res
            if (res.code == 1) {
                layui.$('#uploadDemoView_aw_img').attr('src', res.data.url);
                $(`${input_selector}-complete`).val(res.data.url)
                $(`${input_selector}-lack`).val(res.data.url2)
                $(`${input_selector}-img`).attr('src', res.data.url)
                $(`${input_selector}-img`).css('display', "block")
                console.log("aaaaaaa");
				$('.layui-bottom-img').css('opacity',0)
                return layer.msg('上传成功');
            }
            layer.msg(res.msg);
        },

    })
}

/**
 * 多个文件上传
 * @param dialog_title 上传对话框标题
 * @param input_selector 图片容器
 * @param imgagebox 图片容器
 * @param filetype 文件类型，image,video,audio,file
 * @param extra_params 额外参数，'上传位置'
 * @param upload_size 允许文件大小 单位 MB 默认 10
 */
function uploadFiles(dialog_title, input_selector,imgagebox, filetype,extra_params,upload_size) {
    upload_size = !isEmpty(upload_size) ? upload_size : 10;
    var html = `
        <div class="layui-upload-drag" id="uploads_box_aw" style="position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);">
          <i id="upload_icon" class="layui-icon"></i>
          <p id="upload_title_tip">点击上传，或将文件拖拽到此处</p>
          <div class="layui-hide"  id="uploadDemoView_aw">
            <hr>
          </div>
        </div>
        <div id="uploadimgarr">
         <img src="" id="uploadDemoViews_aw_img" alt="" style="max-width: 130px">
        </div>
    `
    var updata = {}
    var uploadmodel1 = Win.layer.open({
        type: 1,
        title: !isEmpty(dialog_title) ? dialog_title : "上传图片",
        closeBtn: 1,
        btn: ['确定'],
        skin: 'layui-layer-rim', //加上边框
        area: ['520px', '340px'], //宽高
        shadeClose: false,
        content: html,
        yes: function () {
            layer.close(uploadmodel1);
        }
    });
    extra_params = !isEmpty(extra_params) ? extra_params : 'shopimg'
    var paramdata = {
        url: extra_params
    }
    filetype = !isEmpty(filetype) ? filetype : 'images';
    window.layui.upload.render({
        elem: '#uploads_box_aw'
        , accept: filetype
        , size: upload_size * 1024
        ,multiple:true
        , data: paramdata
        , url: getDomain() + "/api/tjupload/upimage"//上传接口。
        , done: function (res) {
            updata = res
            console.log(res)
            if (res.code == 1) {
                layui.$('#uploadDemoViews_aw_img').attr('src', res.data.url);
                var id ='uploadedig_arr_file'+diu_Randomize(1000,1000000)
                var input =`
                    <div class="uploadarr_box">
                        <i class="layui-icon layui-icon-close" onclick="deletefile(this)"></i>
                        <img src="${res.data.url}" onclick="uploadimg('修改图片测试123','#${id}','${filetype}','${extra_params}','${upload_size}')" id="${id}-img" alt="" style="width:130px;height:130px">
                        <input type="hidden" id="${id}-complete" name="${input_selector}_complete[]" value="${res.data.url}">
                        <input type="hidden" id="${id}-lack" name="${input_selector}[]" value="${res.data.url2}">
                    </div>
                `
                $(imgagebox).append(input)
                // $(`${input_selector}-complete`).val(res.data.url)
                // $(`${input_selector}-lack`).val(res.data.url2)
                // $(`${input_selector}-img`).attr('src', res.data.url)
                return layer.msg('上传成功');
            }
            layer.msg(res.msg);
        },
    })
}

/**
 * 删除父级标签内容
 * @param obj
 */
function deletefile(obj){
    obj.parentNode.remove()
}



/**
 * 单个文件上传
 * @param dialog_title 上传对话框标题
 * @param input_selector 图片容器
 * @param filetype 文件类型，image,video,audio,file
 * @param extra_params 额外参数，'上传位置'
 * @param upload_size 允许文件大小 单位 MB 默认 10
 */
function uploadimg(dialog_title, input_selector, filetype,extra_params,upload_size) {
    var id ='uploade_button_'+diu_Randomize(1000,1000000)
    var scbutt = `
        <div id="${id}" class="layui-upload-file">文件上传</div>
    `
    document.getElementsByTagName('body')[0].insertAdjacentHTML("beforeEnd",scbutt);
    upload_size = !isEmpty(upload_size) ? upload_size : 10;
    var updata = {}
    var paramdata = {
        url: !isEmpty(extra_params) ? extra_params : 'shopimg'
    }
    filetype = !isEmpty(filetype) ? filetype : 'images';
    Win.layui.upload.render({
        elem: '#'+id
        , accept: filetype
        , size: upload_size * 1024
        , data: paramdata
        , url: getDomain() + "/api/tjupload/upimage"//上传接口。
        , done: function (res) {
            console.log(res)
            updata = res
            if (res.code == 1) {
                $(`${input_selector}-complete`).val(res.data.url)
                $(`${input_selector}-lack`).val(res.data.url2)
                $(`${input_selector}-img`).attr('src', res.data.url)
                $(`${input_selector}-img`).css('display', "block")
                return layer.msg('上传成功');
            }
          return  layer.msg(res.msg);
        },
    })
    $("#"+id).click()
}

let dataurl={}
function uploadimg1(dialog_title, input_selector, filetype,extra_params,upload_size) {
    var id ='uploade_button_'+diu_Randomize(1000,1000000)
    var scbutt = `
        <div id="${id}" class="layui-upload-file">文件上传</div>
    `
    document.getElementsByTagName('body')[0].insertAdjacentHTML("beforeEnd",scbutt);
    upload_size = !isEmpty(upload_size) ? upload_size : 10;
    var updata = {}
    var paramdata = {
        url: !isEmpty(extra_params) ? extra_params : 'shopimg'
    }
    filetype = !isEmpty(filetype) ? filetype : 'images';
    Win.layui.upload.render({
        elem: '#'+id
        , accept: filetype
        , size: upload_size * 1024
        , data: paramdata
        , url: getDomain() + "/member/uploadimgs/upimage"//上传接口。
        , done: function (res) {
            
            dataurl=res.data.url2
            updata = res
            console.log(456,dataurl)
            if (res.code == 1) {
                $(`${input_selector}-complete`).val(res.data.url)
                $(`${input_selector}-lack`).val(res.data.url2)
                $(`${input_selector}-img`).attr('src', res.data.url)
                $(`${input_selector}-img`).css('display', "block")
                
                return layer.msg('Upload successful');
            }
          return  layer.msg(res.msg);
        },
    })
    $("#"+id).click()
}

window.onload = function () {
    var imgs = $(".layui-upload-img")
    // var imgs = document.getElementsByClassName("layui-upload-img")
    for(var i=0;i<imgs.length;i++){
        $(imgs[i]).on("load",function(){
            this.style.display = 'block'; // 图片加载完成后显示图片
        })
        $(imgs[i]).onerror = function(){
            this.style.display = 'none'; // 图片加载完成后显示图片
        }
    }


    //控制是否显示
    var show = $(".checked_show")
    var n = "";
    var v = "";
    for(var i =0;i<show.length;i++){
        var item = show[i]
        if(n == $(item).attr("name")){
            var vd = $("input[name='"+n+"']:checked").val()
            if((v==0 && vd == 1) || (v==1 && vd == 2)){
                $("[data-show='"+n+"']").css("display","none")
            }else{
                var css = $(this).parent().css("display")
                $("[data-show='"+n+"']").css("display",css)
            }
            continue;
        }
        n =$(item).attr("name")
        v =$(item).val()
    }
    $(".checked_show").click(function(){
        var n =$(this).attr("name")
        var v = $("input[name='"+n+"']").eq(0).val()
        var vd = $("input[name='"+n+"']:checked").val()
        if((v==0 && vd == 1) || (v==1 && vd == 2)){
            $("[data-show='"+n+"']").css("display","none")
        }else{
            var css = $(this).parent().css("display")
            $("[data-show='"+n+"']").css("display",css)
            var d = $("[data-show='"+n+"']")
            d.each(function(e,el){
                if($(el).attr("data-display")){
                    $(el).css("display",$(el).attr("data-display"))
                }
            })
        }
    })
}

/* $(document).ready(function() {
    var imgs = $(".layui-upload-img")
    for(var i=0;i<imgs.length;i++){
        $(imgs[i]).on("load",function(){
            this.style.display = 'block'; // 图片加载完成后显示图片
        })
        $(imgs[i]).onerror = function() {
        this.style.display = 'none'; // 如果图片加载失败，则隐藏图片
      };
    }

    
    //控制是否显示
    var show = $(".checked_show")
    var n = "";
    var v = "";
    for(var i =0;i<show.length;i++){
        var item = show[i]
        if(n == $(item).attr("name")){
            var vd = $("input[name='"+n+"']:checked").val()
            if((v==0 && vd == 1) || (v==1 && vd == 2)){
                $("[data-show='"+n+"']").css("display","none")
            }else{
                var css = $(this).parent().css("display")
                $("[data-show='"+n+"']").css("display",css)
            }
            continue;
        }
        n =$(item).attr("name")
        v =$(item).val()
    }
    $(".checked_show").click(function(){
        var n =$(this).attr("name")
        var v = $("input[name='"+n+"']").eq(0).val()
        var vd = $("input[name='"+n+"']:checked").val()
        if((v==0 && vd == 1) || (v==1 && vd == 2)){
            $("[data-show='"+n+"']").css("display","none")
        }else{
            var css = $(this).parent().css("display")
            $("[data-show='"+n+"']").css("display",css)
            var d = $("[data-show='"+n+"']")
            d.each(function(e,el){
                if($(el).attr("data-display")){
                    $(el).css("display",$(el).attr("data-display"))
                }
            })
        }
    })
}) */
//处理表单提交回调 - 如果form.js已定义，则不重复定义
if(typeof window.return_handle === 'undefined'){
    window.return_handle = function(data){
        if(data){
            // 验证必要的数据字段
            if(!data.string){
                data.string = data.message || data.msg || '操作完成';
            }
            if(!data.iserror){
                data.iserror = data.status === 'error' ? 'error' : 'success';
            }
            
            // 使用NotificationSystem直接显示，避免通过layer.msg导致重复
            if(typeof NotificationSystem !== 'undefined' && NotificationSystem.show){
                var notificationType = data.iserror === 'error' ? 'error' : 'success';
                NotificationSystem.show(data.string, notificationType, {
                    duration: 2000,
                    onClose: function(){
                        if(data.defurl && data.defurl !== ''){
                            setTimeout(()=>{
                                if(window.location.href !== data.defurl){
                                    location.href = data.defurl;
                                }
                            }, 100);
                        }
                    }
                });
            } else if(typeof layer !== 'undefined' && typeof layer.msg === 'function'){
                // 降级到layer.msg
                var icon = data.iserror === 'error' ? 2 : 1;
                layer.msg(data.string, {
                    icon: icon,
                    time: 2000,
                    end: function(){
                        if(data.defurl && data.defurl !== ''){
                            setTimeout(()=>{
                                if(window.location.href !== data.defurl){
                                    location.href = data.defurl;
                                }
                            }, 100);
                        }
                    }
                });
            } else {
                setTimeout(()=>{
                    swal({
                        title: data.string,
                        type: data.iserror,
                        timer: 1500
                    }).then(function(isConfirm) {
                        if (isConfirm === true) {
                            if(data.defurl && data.defurl !== ''){
                                location.href = data.defurl;
                            }
                        } else if (isConfirm === false) {

                        } else {
                            // Esc, close button or outside click
                            // isConfirm is undefined
                        }
                    });
                    
                    // 只有在有defurl时才执行跳转
                    if(data.defurl && data.defurl !== ''){
                        setTimeout(()=>{
                            location.href = data.defurl;
                        }, 1500);
                    }
                }, 100);
            }
        } else {
            // 处理空数据情况
            if(typeof layer !== 'undefined' && typeof layer.msg === 'function'){
                layer.msg("操作完成", {icon: 1, time: 2000});
            } else {
                swal({
                    title: "操作完成",
                    type: "success",
                    timer: 1500
                });
            }
        }
    };
}
function form_submit(url,data){
    if(!url){
        console.log("url为空")
        console.log(url)
        console.log(data)
        setTimeout(()=>{
            swal({
                title: "错误",
                text: "url为空",
                type: "error",
                timer: 3000
            }).then(function() {
               window.location.reload();
            });
        }, 100);
        return;
    }
    $.post(url,data,function(data){
        try {
            // 如果返回的是字符串，尝试解析为JSON
            if(typeof data === 'string'){
                data = JSON.parse(data);
            }
            return_handle(data);
        } catch(e) {
            console.error('JSON解析错误:', e);
            swal({
                title: "响应解析失败",
                text: "服务器返回的数据格式不正确",
                type: "error",
                timer: 3000
            });
        }
    },'json').fail(function(xhr, status, error){
        console.error('表单提交失败:', error);
        swal({
            title: "提交失败",
            text: "网络错误或服务器异常: " + error,
            type: "error",
            timer: 3000
        });
    });
}

/**
 * 提示跳转弹窗
 * @param title 提示内容
 * @param url 跳转地址
 */
function confirm(title,url,data={}){
    var confirmindex =layer.confirm(title?title:'确认删除', {
        btn: ['确认','取消'] //按钮
    }, function(){
        if(url){
            // Win.location.href = url
            // 引用form.js的提交
            form_submit(url,data)
        }
        layer.close(confirmindex)
        return true
    }, function(){
        layer.close(confirmindex)
    });
    console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaa")
    return confirmindex
}

/**
 * 弹窗
 * @param title 标题
 * @param url 路径
 * @param x 宽
 * @param y 高
 */
function open(title,url,x='893px',y='600px'){
    layer.open({
        type: 2,
        title: title,
        shadeClose: true,
        shade: false,
        maxmin: true, //开启最大化最小化按钮
        area: [x, y],
        content: url
    });
}

/**
 * 时间戳转换为日期
 * @param value 日期
 * @param type 转换格式
 * @returns {string}
 */
function formatDate(value, type = 0){
    var time = new Date(value * 1000);
    var year = time.getFullYear();
    var month = time.getMonth() + 1;
    var date = time.getDate();
    var hour = time.getHours();
    var minute = time.getMinutes();
    var second = time.getSeconds();
    month = month < 10 ? "0" + month : month;
    date = date < 10 ? "0" + date : date;
    hour = hour < 10 ? "0" + hour : hour;
    minute = minute < 10 ? "0" + minute : minute;
    second = second < 10 ? "0" + second : second;
    var arr = [
        year + "-" + month + "-" + date,
        year + "-" + month + "-" + date + " " + hour + ":" + minute + ":" + second,
        year + "年" + month + "月" + date,
        year + "年" + month + "月" + date + " " + hour + ":" + minute + ":" + second,
        hour + ":" + minute + ":" + second
    ]
    return arr[type];
}

/**
 * 传入时间搓多少分钟前
 * @param timestamp 时间戳
 * @returns {string}
 */
function timestampFormat( timestamp ) {
    function zeroize( num ) {
        return (String(num).length == 1 ? '0' : '') + num;
    }

    var curTimestamp = parseInt(new Date().getTime() / 1000); //当前时间戳
    var timestampDiff = curTimestamp - timestamp; // 参数时间戳与当前时间戳相差秒数

    var curDate = new Date( curTimestamp * 1000 ); // 当前时间日期对象
    var tmDate = new Date( timestamp * 1000 );  // 参数时间戳转换成的日期对象

    var Y = tmDate.getFullYear(), m = tmDate.getMonth() + 1, d = tmDate.getDate();
    var H = tmDate.getHours(), i = tmDate.getMinutes(), s = tmDate.getSeconds();

    if ( timestampDiff < 60 ) { // 一分钟以内
        return "刚刚";
    } else if( timestampDiff < 3600 ) { // 一小时前之内
        return Math.floor( timestampDiff / 60 ) + "分钟前";
    } else if ( curDate.getFullYear() == Y && curDate.getMonth()+1 == m && curDate.getDate() == d ) {
        return '今天' + zeroize(H) + ':' + zeroize(i);
    } else {
        var newDate = new Date( (curTimestamp - 86400) * 1000 ); // 参数中的时间戳加一天转换成的日期对象
        if ( newDate.getFullYear() == Y && newDate.getMonth()+1 == m && newDate.getDate() == d ) {
            return '昨天' + zeroize(H) + ':' + zeroize(i);
        } else if ( curDate.getFullYear() == Y ) {
            return  zeroize(m) + '月' + zeroize(d) + '日 ' + zeroize(H) + ':' + zeroize(i);
        } else {
            return  Y + '年' + zeroize(m) + '月' + zeroize(d) + '日 ' + zeroize(H) + ':' + zeroize(i);
        }
    }
}

/**
 * 获取两个时间之间的天数差
 * @param {date} startDate 
 * @param {date} endDate 
 * @returns 
 */
function getDateDiff(startDate, endDate) {
    var start = new Date(startDate);
    var end = new Date(endDate);
    var diff = end.getTime() - start.getTime();
    var days = Math.floor(diff / (24 * 60 * 60 * 1000));
    return days;
  }

function add_keys(par,chi){
	var s = $("#"+chi).clone();
	var id = "cli"+diu_Randomize(1000,1000000)
	s.attr("id",id)
    s.css("display","block")
    var childs = s.find(":input")
    for(var i=0;i<childs.length;i++){
        var name = $(childs[i]).attr("data-name")
        if(name){
            $(childs[i]).attr("name",name.replace('['+chi+']','['+id+']'))
        }
    }
	s.find('input').val('')
	s.children("button").attr("data-parent",id)
	s.children("button").css("display","inline-block")
    console.log(s.find(':input'))
	$("#"+par).append(s)
}
function remove_keys(e){
	var id = e.getAttribute("data-parent")
	$("#"+id).remove()
}

