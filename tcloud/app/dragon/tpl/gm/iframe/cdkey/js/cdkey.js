var cdk_req_url = "/dragon/gm/uploadcdkey";

$(function(){
    // $("#input_file").fileinput({  
    // allowedFileExtensions: ["xlsx"],  
    // resizePreference: 'width',  
    // maxFileCount: 1,  
    // language: 'zh',  
    // overwriteInitial: false,  
    // resizeImage: true,  
    // });
    
    $(function () {
        //$('#load_file').show();
        $('#show_cdkey').hide();
        $('#query_info').html("");
    })
    
    function showCDKeyList(json){
        //alert(json['value'][0][0]['cdkey']);
        //$('#load_file').hide();
        $('#show_cdkey').show();
        $('#query_info').html("");
        if(json['value'] == null) {
            return;
        }
        for(var i = 0; i <json['value'][0].length; ++i) {
            $('#query_info').append("<tr><th>" + json['value'][0][i]['cdkey'] + "</th><td>" + json['value'][0][i]['isuse'] + "</td></tr>");
        }

        $('#query_info').append("<tr><th>重复cdkey</th></tr>");

        for(var i = 0; i <json['value']['error'].length; ++i) {
                $('#query_info').append("<tr><th>" + json['value']['error'][i] + "</th></tr>");
        }
    }
    
    $("#btnAjaxSubmit").click(function(){
        $.ajaxFileUpload({
            url:cdk_req_url, //处理图片的脚本路径
            type: 'post', //提交的方式
            secureuri :false, //是否启用安全提交
            //fileElementId :'input_file', //file控件ID
            dataType : 'JSON', //服务器返回的数据类型 
            success : function (data, status){ //提交成功后自动执行的处理函数
                // alert(data);
                var json = eval('(' + data + ')');
                //alert(data);
                showCDKeyList(json);
            },
            error: function(){
                alert("无法连接");
            }
        });
    });
});
