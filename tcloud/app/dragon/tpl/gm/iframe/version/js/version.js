var vsrsion_req_url = "/dragon/gm/version";
var version_read_url = "/app/dragon/tpl/static/version/version.json";
var url = "/app/dragon/tpl/static/version/version.json";
//119.23.175.202
var server_list = '"server":[{"name":"公网测试服", "ip":"180.76.137.45", "port":"36200"}, {"name":"慧姐的服务器", "ip":"172.17.1.33", "port":"36200"}, {"name":"肥仔的服务器", "ip":"172.17.1.245", "port":"36200"}, {"name":"帅哥的服务器", "ip":"192.168.1.33", "port":"36200"}]';
var server_list_debug = '"server":[{"name":"公网测试服", "ip":"180.76.137.45", "port":"36200"}, {"name":"慧姐的服务器", "ip":"172.17.1.33", "port":"36200"}, {"name":"肥仔的服务器", "ip":"172.17.1.245", "port":"36200"}, {"name":"帅哥的服务器", "ip":"192.168.1.33", "port":"36200"}]';
var file_name;
var host;
var host_debug;
var mainsite_debug;
var update_switch;
var update_switch_debug;
var version_debug;
var notice_switch;
var notice_content;
$(function(){
    $.ajax({
        type:"get",
        url: version_read_url,
        //dataType: "jsonp",
        success: function (data) {
            //alert(data);
            var obj = eval(data);
            // if(obj.)
            arr = obj.ver.split(".");
            document.getElementById('version1').value = arr[0];
            document.getElementById('version2').value = arr[1];
            document.getElementById('version3').value = arr[2];
            document.getElementById('load_way').value = obj.mainsite;
            document.getElementById('load_host').value = obj.host;
            
            arr_debug = obj.debug.ver.split(".");

            document.getElementById('version1_debug').value = arr_debug[0];
            document.getElementById('version2_debug').value = arr_debug[1];
            document.getElementById('version3_debug').value = arr_debug[2];
            document.getElementById('load_way_debug').value = obj.debug.mainsite;
            document.getElementById('load_host_debug').value = obj.debug.host;
        },
        error: function(){
            alert("无法连接");
        }
    });
    $('#input_file').on('change', function( e ){
        //e.currentTarget.files 是一个数组，如果支持多个文件，则需要遍历
        var name = pre_url + e.currentTarget.files[0].name;
        tmp = tmp + e.currentTarget.files[0].name;
        document.getElementById('load_way').value = name;
    });
    $('#filebtn').click(function() {
        $('#versionbtn').hide();
        $.ajaxFileUpload({
            url:file_req_url, //处理图片的脚本路径
            type: 'post', //提交的方式
            secureuri :false, //是否启用安全提交
            fileElementId :'input_file', //file控件ID
            dataType : 'JSON', //服务器返回的数据类型 
            success : function (data, status){ //提交成功后自动执行的处理函数
                // alert(data);
                var json = eval('(' + data + ')');
                if(json['code'] == 0) {
                    alert("上传成功");
                } else {
                    alert("上传失败");
                }
                $('#versionbtn').show();
            },
            error: function(){
                alert("无法连接");
                $('#versionbtn').show();
            }
        });
        return false;
    });
    $('#versionbtn').click(function() {
        version = $('#version1').val() + '.' + $('#version2').val() + '.' + $('#version3').val();
        if(version == ""){
            alert("version不能为空!");
            return;
        }
        if($('#update_on').is(':checked')) {
            update_switch="true";
        }else if($('#update_off').is(':checked')) {
            update_switch="false";
        }
        if($('#notice_true').is(':checked')) {
            notice_switch="true";
        }else if($('#notice_false').is(':checked')) {
            notice_switch="false";
        }
        notice_content = $('#notice_content').val();
        alert("update_switch : " + update_switch);
        alert("notice_switch : " + notice_switch);
        mainsite = document.getElementById('load_way').value;
        host = document.getElementById('load_host').value;
        //debug
        version_debug = $('#version1_debug').val() + '.' + $('#version2_debug').val() + '.' + $('#version3_debug').val();
        if(version_debug == ""){
            alert("version_debug不能为空!");
            return;
        }
        if($('#update_on_debug').is(':checked')) {
            update_switch_debug="true";
        }else if($('#update_off_debug').is(':checked')) {
            update_switch_debug="false";
        }
        alert("update_switch_debug : " + update_switch_debug);
        mainsite_debug = document.getElementById('load_way_debug').value;
        host_debug = document.getElementById('load_host_debug').value;
        var debug_msg = '{"update_switch":"' + update_switch_debug + '","ver":"' + version_debug +'", "mainsite":"' + mainsite_debug + '","host":"' + host_debug + '",' + server_list_debug +'}';
        var msg = '{"update_switch":"' + update_switch + '","ver":"' + version +'", "mainsite":"' + mainsite + '","host":"' + host + '",' + server_list + ',"notice_switch":"' + notice_switch + '","notice_content":"' + notice_content +'","debug":' + debug_msg +'}';
        alert(msg);
        $.ajax({
            type:"post",
            url: vsrsion_req_url,
            data: {
                msg:msg
            },
            success: function (data) {
               alert(data);
                var json = eval("(" + data + ")");
                if(json['code'] == 0) {
                    alert("资源配置成功");
                } else {
                    alert("资源配置失败");
                }
            },
            error: function(){
                alert("无法连接");
            }
        });
        return false;
    });
});
