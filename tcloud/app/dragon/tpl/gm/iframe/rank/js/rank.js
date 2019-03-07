var mail_req_url = "/dragon/gm/query_history_ladder";

$(function(){
    
    $('#rank_post').click(function() {
        var count=100;
        $.ajax({
            type:"post",
            url: mail_req_url,
            data: {
                count:count
            },
            success: function (data) {
                var json = eval('(' + data + ')');
                if(json['value'] == null) {
                    alert("排行榜为空");
                    return;
                }
                for(var i= 0 ; i < json['value'].length ; ++i){
                    $('#query_info').append(
                    "<tr><th>" + json['value'][i]['rank'] + "</th><td>" + json['value'][i]['account'] + "</td><td>" + json['value'][i]['name'] + "</td><td>" + json['value'][i]['score'] + "</td></tr>"
                    ); 
                };
            },
            error: function(){
                alert("无法连接");
            }
        });
        return false;
    });
});