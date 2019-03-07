var channel_req_url = "/dragon/gm/channel";

$(function(){
    var i = 1;
    $('#addRow').click(function() {
        $('#query_info').append('<tr id="Row"><td><input class="form-control" id="channel_name_' + i + '" placeholder=""></td><td><input class="form-control" id="channel_id_' + i + '" placeholder=""></td></tr>');
        i++;
    });
    
    $('#postpointbtn').click(function() {
        // alert(2);
        var channel_name = new Array();
        var channel_id = new Array();
        
        // channel_name[0] = $('#channel_name_0').val();
        // channel_id[0] = $('#channel_id_0').val();
        
        for(var j = 0;j < i;j++){
            channel_name[j] = $('#channel_name_' + j).val();
            if(channel_name[j] == null){
                alert("不能为空!");
                return;
            }
            for(var l = 0;l < j;l++){
                if(channel_name[j] == channel_name[l]){
                    alert("渠道号重复!");
                    return; 
                }
            }
        }
        
        for(var j = 0;j < i;j++){
            channel_id[j] = $('#channel_id_' + j).val();
            if(channel_id[j] == null){
                alert("不能为空!");
                return;
            }
            for(var l = 0;l < j;l++){
                if(channel_id[j] == channel_id[l]){
                    alert("渠道id重复!");
                    return; 
                }
            }
        }

        var msg = '{"info":[{"' + channel_name[0] +'":"' + channel_id[0] + '"}'
        for(var j = 1;j < i;j++){
            msg = msg + ',{"' + channel_name[j] +'":"' + channel_id[j] + '"}';
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
                    alert("渠道配置成功");
                } else {
                    alert("渠道配置失败");
                }
            },
            error: function(){
                alert("无法连接");
            }
        });
    return false;
    });
});