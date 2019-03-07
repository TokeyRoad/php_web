var mail_req_url = "/dragon/gm/getoperate";

$(function(){
    
    function get_url(){
        var url_current = window.location.href;
        var href_current="";
        for(var i = url_current.indexOf("http:");i < url_current.indexOf("/dragon");i++){
            href_current += url_current[i];
        }
        return href_current;
    }
    
    $('#post_button').click(function() {
        
        var operate=$('#operate').val();
        var user=$('#user').val();
        $.ajax({
            type:"post",
            url: mail_req_url,
            //dataType: "jsonp",
            data: {
                operate:operate,
                user:user
            },
            success: function (data) {
                //alert(data);
                var json = eval('(' + data + ')');
                $('#query_info').html("");
                if(json['value'] == null) {
                        return;
                }
                for(var i = 0; i <json['value'].length; ++i) {
                    var date = new Date(json['value'][i]['time']*1000);
                    Y = date.getFullYear() + '-';
                    M = (date.getMonth()+1 < 10 ? +(date.getMonth()+1) : date.getMonth()+1) + '-';
                    D = date.getDate() + ' ';
                    h = date.getHours() + ':';
                    m = date.getMinutes() + ':';
                    s = date.getSeconds();
                    
                    var operate_s=json['value'][i]['content']
                    if(json['value'][i]['operate']=="上传cdkey" || json['value'][i]['operate']=="群发邮件"){
                        operate_s='<a href="' + get_url() + json['value'][i]['content'].slice(json['value'][i]['content'].indexOf("/dragon")) + '" target="_Blank">下载</a>';
                    }
                    if(json['value'][i]['operate']=="数据埋点"){
                        operate_s='<a href="' + get_url() + '/dragon/tpl/static/points/' + json['value'][i]['content'] + '" target="_Blank">查看</a>';
                    }
                    if(json['value'][i]['operate']=="渠道号"){
                        operate_s='<a href="' + get_url() + '/dragon/tpl/static/channel/' + json['value'][i]['content'] + '" target="_Blank">查看</a>';
                    }
                    if(json['value'][i]['operate']=="游戏版本"){
                        operate_s='<a href="' + get_url() + '/dragon/tpl/static/version/' + json['value'][i]['content'] + '" target="_Blank">查看</a>';
                    }
                    if(json['value'][i]['operate']=="资源版本"){
                        operate_s='<a href="' + get_url() + '/dragon/tpl/static/resource_version/' + json['value'][i]['content'] + '" target="_Blank">查看</a>';
                    }
                    if(json['value'][i]['operate']=="游戏公告"){
                        operate_s='<a href="' + get_url() + '/dragon/tpl/static/notice/' + json['value'][i]['content'] + '" target="_Blank">查看</a>';
                    }
                    $('#query_info').append("<tr><th>" + Y+M+D+h+m+s + "</th><td>" + json['value'][i]['user'] + "</td><td>" + json['value'][i]['operate'] + "</td><td>" + operate_s + "</td></tr>");
                }
            },
            error: function(){
                alert("无法连接");
            }
        });
    return false;
    });
});
