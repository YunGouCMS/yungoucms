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
	if (arr.code == 1500) {
		showmesscon(arr.data)
		console.log(arr.code,arr)
	} else if (arr.code == 200) {
		$.PageDialog.fail(arr.msg);
	} else {
		console.log(arr) //监听接受来自服务端的信息
	}
}
//显示消息
function showmesscon(data) {
	$('.maillist ul').html('')
	tval = "<li class='diflex jube alitc'><a href='javascript:;' class='diflex alitc'><img src='http://upos.yungoucms.com/statics/uploads/banner/20221013/24564165643523.png' class='wid100'><div class='size14'>"+data.username+"</div></a><div class='jianbian size14 cfff' onclick='addFrends("+data.uid+")'>添加</div></li>"
	$('.maillist ul').append(tval)
}
function addFrends(fid){
	pushMess = `
		{
		  "code": 1501,
		  "data": {
			"uid": ${wcuid},
			"fid": ${fid},
			"rename": ""
		  }
		}
		`
		console.log(pushMess)
	ws.send(pushMess)
}
$('.sousuos input').bind('keypress',function(event){
	if (event.keyCode == 13) {
		var val = $('.sousuos input').val()
		if (val == '') return
		$('.sousuos input').val('')
		pushMess = `
			{
			  "code": 1500,
			  "data": {
				"uid": ${wcuid},
				"mobile": ${val}
			  }
			}
			`
		ws.send(pushMess)
	}
})