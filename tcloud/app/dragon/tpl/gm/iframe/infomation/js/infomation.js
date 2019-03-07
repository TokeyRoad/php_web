var mail_req_url = "/dragon/gm/getplayerbyid";
var readmail_req_url = "/dragon/gm/readmail";

$(function(){
    $('#post_button').click(function() {
        var accountid=$('#accountid').val();
        $.ajax({
            type:"post",
            url: mail_req_url,
            //dataType: "jsonp",
            data: {
                account:accountid
            },
            success: function (data) {
                // alert(data);
                var json = eval('(' + data + ')');  
                $('#query_info').html("<th>" + json['value']['account'] + "</th><td>" + json['value']['name'] + "</td><td>" + json['value']['guid'] + "</td><td>" + json['value']['platform'] + "</td><td>" + json['value']['level'] + "</td><td>" + json['value']['diamond'] + "</td><td>" + json['value']['gold'] + "</td><td>" + json['value']['createtime'] + "</td><td>" + json['value']['endtime'] + "</td>");
                alert("查询成功");
            },
            error: function(){
                alert("无法连接");
            }
        });
    return false;
    });
    $('#mail_button').click(function() {
        var accountid=$('#accountid').val();
        $.ajax({
            type:"post",
            url: readmail_req_url,
            //dataType: "jsonp",
            data: {
                account:accountid
            },
            success: function (data) {
                var json = eval('(' + data + ')');
                $('#mail_info').html("");
                if(json['value'] == null) {
                    alert("信息为空！");
                    return;
                }
                for(var i = 0; i <json['value'].length; ++i) {
                    $('#mail_info').append("<tr><th>" + json['value'][i]['mailid'] + "</th><td>" + json['value'][i]['title'] + "</td><td>" + json['value'][i]['content'] + "</td><td>" + json['value'][i]['accessory'] + "</td><td>" + json['value'][i]['authortime'] + "</td></tr>");
                }
            },
            error: function(){
                alert("无法连接");
            }
        });
    return false;
    });
});