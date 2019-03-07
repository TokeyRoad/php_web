var switch_file_url = "/app/dragon/tpl/static/version/switch.json";
var switch_req_url = "/dragon/gm/switch_save";
//119.23.175.202
var charge_switch;
$(function(){
    $.ajax({
        type:"get",
        url: switch_file_url,
        //dataType: "jsonp",
        success: function (data) {
            var obj = eval(data);
            if(obj.charge_switch == true){
                $('#update_on').prop("checked",true);
            }else{
                $('#update_off').prop("checked",true);
            }
        },
        error: function(){
            alert("无法连接");
        }
    });
    $('#switchbtn').click(function() {
        if($('#update_on').is(':checked')) {
            charge_switch="true";
        }else if($('#update_off').is(':checked')) {
            charge_switch="false";
        }
        alert("charge_switch:" + charge_switch);
        var msg = '{"charge_switch":' + charge_switch + '}';
        $.ajax({
            type:"post",
            url: switch_req_url,
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
