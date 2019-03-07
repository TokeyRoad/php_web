var mail_req_url = "/dragon/gm/broadcast";

$(function(){
    var myData=new Date();
    $('#begin_year').val(myData.getFullYear());
    $('#begin_month').val(myData.getMonth()+1);
    $('#begin_day').val(myData.getDate());
    if(myData.getMinutes() + 10 > 60) {
        $('#begin_hour').val(myData.getHours() + 1);
    } else {
        $('#begin_hour').val(myData.getHours());
    }
    $('#begin_min').val((myData.getMinutes() + 10) % 60);
    $('#begin_sec').val(myData.getSeconds());
    
    $('#broadtime_min').val(0);
    $('#broadtime_sec').val(10);
    
    $('#post_button').click(function() {
        var content = $('#verbal_content').val();   //内容
        content=content.replace(/\n/g, " ");
        if (content.length > 512) {
            alert("广播内容过长");
            return;
        }
        
        var notice_type = 0;    //公告类型
        if($('#marquee').is(':checked')) {
            notice_type = 0;
        } else if($('#window').is(':checked')) {
            notice_type = 1;
        }
        
        var brobegin_time;  //开始时间
        if($('#begin_year').val()<0||$('#begin_month').val()<0||$('#begin_day').val()<0||$('#begin_hour').val()<0||$('#begin_min').val()<0||$('#begin_sec').val()<0){
            alert("时间输入错误");
            return;
        }
        brobegin_time=$('#begin_year').val()+"."+$('#begin_month').val()+"."+$('#begin_day').val()+"."+$('#begin_hour').val()+"."+$('#begin_min').val()+"."+$('#begin_sec').val();
/*      brobegin_time[0]=$('#begin_year').val();
        brobegin_time[1]=$('#begin_month').val();
        brobegin_time[2]=$('#begin_day').val();
        brobegin_time[3]=$('#begin_hour').val();
        brobegin_time[4]=$('#begin_min').val();
        brobegin_time[5]=$('#begin_sec').val();
*/
        var broadcast_time = $('#broadtime_min').val()* 60 + $('#broadtime_sec').val() * 1;     //间隔时间
        if($('#broadtime_min').val() < 0 || $('#broadtime_sec').val() < 0){
            alert("时间输入错误!");
            return;
        }
        
        var content_repete=$('#repete_time').val();     //重复次数
        if(content_repete<0){
            alert("次数不能为负!");
            return;
        }
        
        
        if (content.length < 1) {
            alert("内容不能为空");
            return;
        }
        if($('#broadtime_min').val()==""||$('#broadtime_sec').val()==""){
            alert("间隔时间不能为空");
            return;
        }
        if($('#begin_year').val()==""||$('#begin_month').val()==""||$('#begin_day').val()==""||$('#begin_hour').val()==""||$('#begin_min').val()==""||$('#begin_sec').val()==""){
            alert("时间输入错误");
            return;
        }
        if(content_repete==""){
            alert("重复次数不能为空");
            return;
        }
        $.ajax({
            type:"post",
            url: mail_req_url,
            //dataType: "jsonp",
            data: {
                content:content,
                type:notice_type,
                time:brobegin_time,
                delay:broadcast_time,
                repeat:content_repete
            },
            success: function (data) {
                // alert(data);
                var json = eval('(' + data + ')');
                if(json['code'] == 0) {
                    alert("广播成功");
                } else {
                    alert("广播失败");
                }
            },
            error: function(){
                alert("无法连接");
            }
        });
    return false;
    });
});