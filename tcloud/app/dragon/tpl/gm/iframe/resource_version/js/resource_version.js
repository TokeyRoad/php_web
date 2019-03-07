var channel_req_url = "/dragon/gm/resource_version";

$(function(){
    var i = 1;
    $('#addRow').click(function() {
        $('#query_info').append('<tr id="Row"><td><input class="form-control" id="resource_version_key_' + i + '" placeholder=""></td><td><input class="form-control" id="resource_version_value_' + i + '" placeholder=""></td></tr>');
        i++;
    });
    
    $('#postpointbtn').click(function() {
        // alert(2);
        var resource_version_key = new Array();
        var resource_version_value = new Array();
        
        // channel_name[0] = $('#channel_name_0').val();
        // channel_id[0] = $('#channel_id_0').val();
        
        for(var j = 0;j < i;j++){
            resource_version_key[j] = $('#resource_version_key_' + j).val();
            if(resource_version_key[j] == null){
                alert("资源号不能为空!");
                return;
            }
            for(var l = 0;l < j;l++){
                if(resource_version_key[j] == resource_version_key[l]){
                    alert("资源号重复!");
                    return; 
                }
            }
        }
        
        for(var j = 0;j < i;j++){
            resource_version_value[j] = $('#resource_version_value_' + j).val();
            if(resource_version_value[j] == null){
                alert("资源版本不能为空!");
                return;
            }
        }
        
        var msg = '{"info":[{"name":"' + resource_version_key[0] + '","version":' + resource_version_value[0] + '}'
        for(var j = 1;j < i;j++){
            msg = msg + ',{"name":"' + resource_version_key[j] +'","version":' + resource_version_value[j] + '}';
        }
        msg = msg + ']}'
        // alert(msg);
        $.ajax({
            type:"post",
            url: channel_req_url,
            // dataType: "jsonp",
            data: {
                msg:msg
            },
            success: function (data) {
                // alert(data);
                var json = eval('(' + data + ')');
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