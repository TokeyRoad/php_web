var points_req_url = "/dragon/gm/points";

$(function(){
    
    $('#postpointbtn').click(function() {
        // alert(1);
        var chance=new Array();
        var chosen_points=new Array();
        // var gmActivityType=new Array();
        chance[0] = $('#chance_0').val();
        chance[1] = $('#chance_1').val();
        chance[2] = $('#chance_2').val();
        chance[3] = $('#chance_3').val();
        chance[4] = $('#chance_4').val();
        chance[5] = $('#chance_5').val();
        chance[6] = $('#chance_6').val();
        
        chosen_points[0] = $('#chosen_0').val();
        chosen_points[1] = $('#chosen_1').val();
        chosen_points[2] = $('#chosen_2').val();
        chosen_points[3] = $('#chosen_3').val();
        chosen_points[4] = $('#chosen_4').val();
        chosen_points[5] = $('#chosen_5').val();
        chosen_points[6] = $('#chosen_6').val();
        
        // gmActivityType[0] = $('#gmActivityType_0').val();
        // gmActivityType[1] = $('#gmActivityType_1').val();
        // gmActivityType[2] = $('#gmActivityType_2').val();
        // gmActivityType[3] = $('#gmActivityType_3').val();
        // gmActivityType[4] = $('#gmActivityType_4').val();
        // gmActivityType[5] = $('#gmActivityType_5').val();
        // gmActivityType[6] = $('#gmActivityType_6').val();
        
        for(var i = 0; i < 5; i++ ){
            if(chance[i] == ""){
                alert('请输入概率!');
                return;
            }
            else if(chance[i] >100 || chance[i] <0 ){
                alert('请输入正确的概率!');
                return;
            }
        }
        
        var msg='{"info":[{"activityType":"' + chosen_points[0] + '","jump_id":"enterGame","chance":"' + chance[0]/100 + '"},{"activityType":"' + chosen_points[1] + '","jump_id":"activity","chance":"' + chance[1]/100 + '"},{"activityType":"' + chosen_points[2] + '","jump_id":"replacement","chance":"' + chance[2]/100 + '"},{"activityType":"' + chosen_points[3] + '","jump_id":"gift","chance":"' + chance[3]/100 + '"},{"activityType":"' + chosen_points[4] + '","jump_id":"backpack","chance":"' + chance[4]/100 + '"},{"activityType":"' + chosen_points[5] + '","jump_id":"shop","chance":"' + chance[5]/100 + '"},{"activityType":"' + chosen_points[6] + '","jump_id":"resultFail","chance":"' + chance[6]/100 + '"}]}'
        // alert(msg);
        $.ajax({
            type:"post",
            url: points_req_url,
            // dataType: "jsonp",
            data: {
                msg:msg
            },
            success: function (data) {
                // alert(data);
                var json = eval('(' + data + ')');
                if(json['code'] == 0) {
                    alert("埋点写入成功");
                } else {
                    alert("埋点写入失败");
                }
            },
            error: function(){
                alert("无法连接");
            }
        });
    return false;
    });
});