//处理表单提交
$(function(){
    
    // 检查layedit模块是否存在（静默检查，不输出日志）
    // layedit是layui的富文本编辑器模块，用于后台表单中的文本编辑功能
    if(typeof layui === 'undefined' || !layui.layedit){
        // 只在模块缺失时警告，正常情况不输出日志
        console.warn('layedit模块未找到，请确保layui已正确加载');
    }

    //处理表单提交回调 - 确保全局唯一
    window.return_handle = function return_handle(data){
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
                // 降级到swal
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
            } else if(typeof NotificationSystem !== 'undefined' && NotificationSystem.success){
                NotificationSystem.success("操作完成", {duration: 2000});
            } else {
                swal({
                    title: "操作完成",
                    type: "success",
                    timer: 1500
                });
            }
        }
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
                // 使用全局函数，避免重复调用
                if(typeof window.return_handle === 'function'){
                    window.return_handle(data);
                } else {
                    return_handle(data);
                }
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

    // 文件验证函数
    function validateFiles(fileInputs){
        var maxSize = 20 * 1024 * 1024; // 10MB
        // var allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        
        var isValid = true;
        var errorMessage = '';
        
        fileInputs.each(function(){
            var files = $(this)[0].files;
            for(var i = 0; i < files.length; i++){
                var file = files[i];
                
                // 检查文件大小
                if(file.size > maxSize){
                    errorMessage = '文件 "' + file.name + '" 大小超过限制 (最大20MB)';
                    isValid = false;
                    break;
                }
                
                // 检查文件类型
                // if(allowedTypes.indexOf(file.type) === -1){
                //     errorMessage = '文件 "' + file.name + '" 类型不支持';
                //     isValid = false;
                //     break;
                // }
            }
            
            if(!isValid){
                return false; // 跳出循环
            }
        });
        
        if(!isValid){
            swal({
                title: "文件验证失败",
                text: errorMessage,
                type: "error",
                timer: 3000
            });
        }
        
        return isValid;
    }

    // 文件上传提交函数
    function form_submit_with_file(url, formData){
        // 显示上传进度提示
        var progressHtml = '<div id="upload-progress" style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(0,0,0,0.8);color:white;padding:20px;border-radius:10px;z-index:9999;">' +
                          '<div style="text-align:center;margin-bottom:10px;">文件上传中...</div>' +
                          '<div style="width:300px;height:20px;background:#333;border-radius:10px;overflow:hidden;">' +
                          '<div id="progress-bar" style="width:0%;height:100%;background:#4CAF50;transition:width 0.3s;"></div>' +
                          '</div>' +
                          '<div id="progress-text" style="text-align:center;margin-top:10px;font-size:12px;">0%</div>' +
                          '</div>';
        $('body').append(progressHtml);
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                // 上传进度
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round(evt.loaded / evt.total * 100);
                        $('#progress-bar').css('width', percentComplete + '%');
                        $('#progress-text').text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(data){
                $('#upload-progress').remove();
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
            },
            error: function(xhr, status, error){
                $('#upload-progress').remove();
                swal({
                    title: "上传失败",
                    text: "文件上传过程中发生错误: " + error,
                    type: "error",
                    timer: 3000
                });
            }
        });
    }

    $("form").on("submit",function(){
        //搜索框
        var name = $(this).find("input[type='submit']").attr("name")
        var data_attr = $(this).find("input[type='submit']").attr("data-direct_submit")
        if( name== "sososubmit" || name == "paixu_submit" || data_attr == "direct_submit"){
            return true;
        }
        
        var url = $(this).attr('action');
        if(url == 'none'){
            return false;
        }
        
        // 检查是否有文件输入
        var fileInputs = $(this).find("input[type='file']");
        var hasFiles = false;
        
        fileInputs.each(function(){
            if($(this)[0].files && $(this)[0].files.length > 0){
                hasFiles = true;
                return false; // 跳出循环
            }
        });
        
        if(hasFiles){
            // 验证文件
            var isValid = validateFiles(fileInputs);
            if(!isValid){
                return false;
            }
            
            // 有文件时使用FormData
            var formData = new FormData(this);
            formData.append('submit', '1');
            formData.append('dosubmit', '1');
            formData.append('ajax_from', '1');
            form_submit_with_file(url, formData);
        } else {
            // 无文件时使用普通提交
            var data = $(this).serialize() + '&submit=1&dosubmit=1&ajax_from=1';
            form_submit(url,data);
        }
        
        return false;
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