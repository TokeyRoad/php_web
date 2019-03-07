var recharge_resouce_req = "/dragon/gm/save_recharge_resouce";
var recharge_resouce_file_upload = "/dragon/gm/upload_recharge_resouce";
var tmp_url = "";
$(function(){
    $("#input_file").fileinput({  
        allowedFileExtensions: ["png"],  
        resizePreference: 'width',  
        maxFileCount: 1,  
        language: 'zh',  
        overwriteInitial: false,  
        resizeImage: true,  
    });
    $('#filebtn').click(function(){
        $.ajaxFileUpload({
            url:recharge_resouce_file_upload, //处理图片的脚本路径
            type: 'post', //提交的方式
            secureuri :false, //是否启用安全提交
            fileElementId :'input_file', //file控件ID
            dataType : 'JSON', //服务器返回的数据类型 
            success : function (data, status){ //提交成功后自动执行的处理函数
                // alert(data);
                var json = eval('(' + data + ')');
                // var json = JSON.parse(data);
                tmp_url = json.value;
                if(json['code'] == 0) {
                    alert("上传成功");
                } else {
                    alert("上传失败");
                }
            },
            error: function(){
                alert("无法连接");
            }
        });
        return false;
    });
    $('#recharge_btn').click(function() {
        var type = $('#chosen').val();
        $.ajax({
            type:"post",
            url: recharge_resouce_req,
            //dataType: "jsonp",
            data: {
                version:type,
                url:tmp_url
            },
            success: function (data) {
                // alert(data);
                var json = eval('(' + data + ')');
                if(json['code'] == 0) {
                    alert("充值列表刷新成功");
                } else {
                    alert("充值列表刷新失败");
                }
            },
            error: function(){
                alert("无法连接");
            }
        });
        return false;
    });
});