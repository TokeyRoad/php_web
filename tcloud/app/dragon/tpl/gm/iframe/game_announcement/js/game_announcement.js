var mail_req_url = "/dragon/gm/game_announcement";
var mail_show_url = "/dragon/gm/view_announcement";
var file_upload_url = "/dragon/gm/announcement_file";

$(function(){
    // $("#input_file").fileinput({  
    // allowedFileExtensions: ["xlsx"],  
    // resizePreference: 'width',  
    // maxFileCount: 1,  
    // language: 'zh',  
    // overwriteInitial: false,  
    // resizeImage: true,  
    // });
    $.ajax({
        type:"get",
        url: mail_show_url,
        //dataType: "jsonp",
        success: function (data) {
            //alert(data);
            $('#summernote').summernote('code', data);
        },
        error: function(){
            alert("无法连接");
        }
    });
    $('#btnAjaxSubmit').click(function(){
        $.ajaxFileUpload({
            url:file_upload_url, //处理图片的脚本路径
            type: 'post', //提交的方式
            secureuri :false, //是否启用安全提交
            fileElementId :'input_file', //file控件ID
            dataType : 'JSON', //服务器返回的数据类型 
            success : function (data, status){ //提交成功后自动执行的处理函数
                // alert(data);
                var json = eval('(' + data + ')');
                document.getElementById('load_way').value = json.value;
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
    $('#post_button').click(function() {
        var markupStr = $('#summernote').summernote('code');
        $.ajax({
            type:"post",
            url: mail_req_url,
            //dataType: "jsonp",
            data: {
                content:markupStr
            },
            success: function (data) {
                var json = eval('(' + data + ')');
                if(json['code'] == 0) {
                    alert("公告创建成功");
                } else {
                    alert("公告创建失败");
                }
            },
            error: function(){
                alert("无法连接");
            }
        });
        return false;
    });
});