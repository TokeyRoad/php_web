var cdk_req_url = "/dragon/gm/uploadxlsx";

$(function(){
    $("#input_file").fileinput({  
    allowedFileExtensions: ["xlsx"],  
    resizePreference: 'width',  
    maxFileCount: 1,  
    language: 'zh',  
    overwriteInitial: false,  
    resizeImage: true,  
    });
    
    $("#btnAjaxSubmit").click(function(){
        $.ajaxFileUpload({
            url:cdk_req_url, //处理图片的脚本路径
            type: 'post', //提交的方式
            secureuri :false, //是否启用安全提交
            fileElementId :'input_file', //file控件ID
            dataType : 'JSON', //服务器返回的数据类型 
            success : function (data, status){ //提交成功后自动执行的处理函数
                var json = eval('(' + data + ')');
                //alert(data);
                if(json['code'] == 0) {
                    alert("邮件发送成功");
                } else {
                    alert("邮件发送失败");
                }
            },
            error: function(){
                alert("无法连接");
            }
        });
    });
});
