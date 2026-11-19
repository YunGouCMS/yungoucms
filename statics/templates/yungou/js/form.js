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

function getDomain(){
    var url = document.location.toString();
    var arrUrl = url.split("//");
    return arrUrl[0]+"//"+document.domain
}

var src = getDomain()+'/statics/plugin/style/global/hsycmsAalert/dist/sweetalert2.min.js'
var link = getDomain()+'/statics/plugin/style/global/hsycmsAalert/dist/sweetalert2.min.css'
addLink(link);
addScript(src);


//处理表单提交
$(function(){

    // 统一使用右上角 toast 作为提交后提示（与登录、退出等一致）
    function return_handle(data){
        if(!data) return;
        var msg = (data.string || data.msg || '').trim();
        var isError = data.iserror === 'error';
        var url = data.defurl || '';
        setTimeout(function(){
            if (typeof window.toast === 'function') {
                if (msg) window.toast(msg, isError ? 'error' : 'success');
                if (url) setTimeout(function(){ location.href = url; }, msg ? 1800 : 0);
            } else {
                swal({
                    title: msg || (isError ? '操作失败' : '操作成功'),
                    type: data.iserror,
                    timer: 1500
                }).then(function(isConfirm) {
                    if ((isConfirm === true || isConfirm === undefined) && url) location.href = url;
                });
                if (url) setTimeout(function(){ location.href = url; }, 1500);
            }
        }, 100);
    }

    function form_submit(url,data){
        $.post(url,data,function(data){
            return_handle(data);
        },'json').fail(function(){
            if (typeof window.toast === 'function') {
                window.toast('请求失败，请重试', 'error');
            } else if (typeof swal !== 'undefined') {
                swal({ title: '请求失败，请重试', type: 'error', timer: 2000 });
            }
        });
    }

    /* $("form").on("submit",function(){
        //搜索框
        var name = $(this).find("input[type='submit']").attr("name")
        if( name== "sososubmit" || name == "paixu_submit"){
            return true;
        }
        //表单提交
        var data = $(this).serialize() + '&submit=1&dosubmit=1&ajax_from=1';
        var url = $(this).attr('action');
        form_submit(url,data);
        return false;
    }) */

    $(".a_form_noparam_noconfirm").click(function(e){
        e.preventDefault();
        var url = $(this).attr("href");
        form_submit(url,'');
    })
    
    $(".a_form_noparam").click(function(e){
        e.preventDefault();
        var url = $(this).attr("href");
        var text = $(this).html();
        var confirm_text = $(this).attr("data-confirm");
        var cancel_text = $(this).attr("data-cancel");
        if(!confirm_text){
            confirm_text = "确定";
        }
        if(!cancel_text){
            cancel_text = "取消";
        }
        swal({
            title: text ,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirm_text,
            cancelButtonText: cancel_text,
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(function(isConfirm) {
            if (isConfirm === true){
                form_submit(url,'');
            }
        });

    })
});