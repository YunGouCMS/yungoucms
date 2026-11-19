let port = 'ws://'+Pathpath+':9504/chat?myId=' + wcuid
ws = new WebSocket(port)
ws.onopen=()=>{
	console.log('服务器已连接')
}
ws.onclose=()=>{
	console.log('服务器关闭')
	clearInterval(Interval50)
}
ws.onmessage=(msg)=>{
	var arr = JSON.parse(msg.data)
	if (arr.code == 1100) {
		applyList();
		console.log(arr)
	} else if (arr.code == 1502) {
		showmesscon(arr.data)
		console.log(1502,'申请列表',arr)
	} else if (arr.code == 200) {
		$.PageDialog.fail(arr.msg);
		console.log(arr)
		changebot();
	} else {
		console.log(arr) //监听接受来自服务端的信息
	}
}
//显示消息
function showmesscon(data) {
	$('.maillist ul').html('')
	if(data.length == 0) {
		$('.maillist ul').append("<li style='text-align: center;background: #f5f5f5;'>暂无好友申请</li>")
		return
	}
	for (var ac of data) {
		tval = "<li class='diflex jube alitc'><a href='javascript:;' class='diflex alitc'><img src='http://upos.yungoucms.com/statics/uploads/banner/20221013/24564165643523.png' class='wid100'><div class=''>"+ac.a_name+"</div></a><div class='newfrspan' id='"+ac.id+"'><span class='jianbian' onclick='botFrends("+'1,'+ac.id+")'>同意</span><span onclick='botFrends("+'5,'+ac.id+")'>拒绝</span></div></li>"
		$('.maillist ul').append(tval)
	}
}
var thisstatu = 0,thisid = 0;
function applyList(fid){
	pushMess = `
		{
		  "code": 1502,
		  "data": {
			"uid": ${wcuid}
		  }
		}
		`
	ws.send(pushMess)
}
function botFrends(statu,id){
	pushMess = `
		{
		  "code": 1503,
		  "data": {
			"uid": ${wcuid},
			"id": ${id},
			"status": ${statu}
		  }
		}
		`
	thisstatu = statu
	thisid = id
	ws.send(pushMess)
}
function changebot(){
	if (thisstatu == 1) {
		$("#"+thisid).html("<span>已同意</span>")
	} else {
		$("#"+thisid).html("<span>已拒绝</span>")
	}
	thisstatu = 0
	thisid = 0
}