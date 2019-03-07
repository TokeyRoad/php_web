var mail_req_url = "/dragon/gm/mailgm";

$(function(){
    $('.item-input').hide();
    $('#chosen').change(function(){
        if($('#chosen').val() == 3 || $('#chosen').val() == 4) {
            $('.item-input').show();
        }else{
            $('.item-input').hide();
        }
    });
    
//  $('#player_id').hide();
    var apm = document.getElementsByName("mail_type");
    
    $(function () {
            $("input[id='mail_personal']").click(function () {
                $('#player_id').show();
            });
            $("input[id='mail_allserver']").click(function () {
                $('#player_id').hide();
            });
    }) 
    $('#postmail_button').click(function() {
        //alert(1);
        var mail_type=-1;       
        if($('#mail_personal').is(':checked')) {
            mail_type=0;
        }
        if($('#mail_allserver').is(':checked')) {
            mail_type=1;
        }
        
        var targets  ="";
        if(mail_type == 0){
            targets = $('#post-targets').val();
        }
        var title = $('#post-title').val();
        var content = $('#post-content').val();
        var type = $('#chosen').val();
        var amount = $('#post-amount').val();
        
        content=content.replace(/\n/g, " ");
        
        if (content.length > 512 || title.length > 128) {
            alert("邮件内容过长");
            return;
        }
        
        if(mail_type == 0 && targets == ""){
            alert("input err");
            return;
        }
        
        if(title == "" || content == ""){
            alert("input err");
            return;
        }
        
        var itemid = 0;
        if(type == 3 || type == 4) {
            itemid = $('#post-itemid').val();
            if(itemid == ""){
                alert("itemid err");
                return;
            }
        }
        
        var attachment = type + ',' + itemid + ',' + amount;
        
        if(amount==""||amount==0){
            attachment="";
        }
        //alert(2);
        $.ajax({
            type:"post",
            url: mail_req_url,
            //dataType: "jsonp",
            data: {
            //  server:server_check,
                type:mail_type,
                targets:targets,
                title:title,
                content:content,
                attachment:attachment
            },
            success: function (data) {
                //alert(data);
                var json = eval('(' + data + ')');
                if(json['code'] == 0) {
                    alert("邮件发送成功");
                } else {
                    alert("邮件发送失败");
                }
            },
            error: function(){
                alert("无法连接");
            }
        });
        return false;
    });
});
