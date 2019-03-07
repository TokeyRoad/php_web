var money_req_url = "/dragon/gm/addmoney";
$(function(){
	$("#addbtn").click(function(){
		userId = $('#userId').val();
		money = $('#money_number').val();
		diamond = $('#diamond_number').val();
		if((diamond == "" && money == "" )|| userId == ""){
			alert("用户名或金币,钻石不能为空！");
			return;
		}
		if(diamond == "")
			diamond = 0;
		else if(money == "")
			money=0;
		// alert("diamond" + diamond);
		// alert("moeny" + money);
		$.ajax({
			type:"post",
			url:money_req_url,
			data:{
				account:userId,
				gold:money,
				diamond:diamond
			},
			success:function(data){
				// alert(data);
				var json = eval("(" + data + ")");
				if(json['code'] == 0) {
                    alert("增加成功");
                } else {
                    alert("增加失败");
                }
			},
			error:function(){
				alert("无法连接");
			}
		});
		return false;
	});
});