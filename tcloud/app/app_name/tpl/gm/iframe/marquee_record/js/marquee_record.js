var mail_req_url = "/dragon/gm/marquee_record";
var mail_all_url = "/dragon/gm/marquee_all";

$(function(){
    
    $.ajax({
        type:"post",
        url: mail_all_url,
        //dataType: "jsonp",
        data: {
        },
        success: function (data) {
            var json = eval('(' + data + ')');
            $('#query_info').html("");
            if(json['value'] == null) {
                return;
            }
            for(var i = 0; i <json['value'].length; ++i) {
                $('#query_info').append("<tr><th>" + json['value'][i]['time'] + "</th><td>" + json['value'][i]['content'] + "</td><td>" + json['value'][i]['type'] + "</td><td>世界服</td><td>" + json['value'][i]['platform'] + "</td><td>" + json['value'][i]['starttime'] + "</td><td>" + json['value'][i]['delay'] + "</td><td>" + json['value'][i]['repeat'] + "</td></tr>");
            }
        },
        error: function(){
            alert("无法连接");
        }
    });
    
    $('#marquee_query').click(function() {
        var keyword=$('#keyword').val();
        if(keyword.length < 1){
            alert("请输入内容");
            return;
        }
        
        $.ajax({
            type:"post",
            url: mail_req_url,
            //dataType: "jsonp",
            data: {
                keyword:keyword
            },
            success: function (data) {
                var json = eval('(' + data + ')');
                $('#query_info').html("");
                if(json['value'] == null) {
                    return;
                }
                for(var i = 0; i <json['value'].length; ++i) {
                    $('#query_info').append("<tr><th>" + json['value'][i]['time'] + "</th><td>" + json['value'][i]['content'] + "</td><td>" + json['value'][i]['type'] + "</td><td>世界服</td><td>" + json['value'][i]['platform'] + "</td><td>" + json['value'][i]['starttime'] + "</td><td>" + json['value'][i]['delay'] + "</td><td>" + json['value'][i]['repeat'] + "</td></tr>");
                }
            },
            error: function(){
                alert("无法连接");
            }
        });
    return false;
    });
});