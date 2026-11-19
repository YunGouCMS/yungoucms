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
		toJson1100(arr);
		systemnumber();
	} else if (arr.code == 1701) {
		console.log(arr)
		$('.newnavicon>span').css('display','inline-block')
	} else {
		console.log(msg) //监听接受来自服务端的信息
	}
}

$('.showjia').click(function(){
	$('.popjia').fadeToggle(100);
})
$('.popjia>div').click(function(){
	return false
})
$('.showkefu').click(function(){
	$('.messapar').fadeToggle(100);
})
$('.messapar>div').click(function(){
	return false
})
$('.giotnu').click(function(){
	$('.messapar').fadeOut();
	$('.numbpar').fadeToggle(100);
})
$('.touserwfr').click(function(){
	window.location.href=PathWebpath+"/mobile/yunchat/usernewfr";
})

function showmessage(data){
	$('.message ul').html('')
	for (var item of data){
		var netime = '',bname = ''
		var lasttime = toDate(item.mes_sending_time*1000).split(' ')
		var newtime = toDate(new Date()).split(' ')
		if(lasttime[0] == newtime[0]) {
			netime = lasttime[1]
		} else {
			netime = lasttime[0]+' '+lasttime[1]
		}
		var toid = item.b_uid == wcuid ? item.a_uid : item.b_uid
		var toname = item.b_uid == wcuid ? item.a_name : item.b_name
		var toimg = item.b_uid == wcuid ? item.a_img : item.b_img
		if (toname == null) {
			bname = 'USER.'+toid
		} else { bname = toname }
		var unreadnum = ''
		var todnum = item.b_uid == wcuid ? item.a_unread_num : item.b_unread_num
		if (todnum !=0) {
			unreadnum = "<span class='anum'>"+todnum+"</span>"
		}
		var val = "<li class='pa015'><a href='"+PathWebpath+"/mobile/yunchat/usernews/"+item.status+"/"+item.id+"/"+toid+"' class='diflex jube'><div class='sageicon'><img src='"+toimg+"' class='wid100'>"+unreadnum+"</div><div class='sagefont diflex jube c333 size13'><div class='diflex jube'>"+bname+"<span class='c999'>"+netime+"</span></div><span style='display:none;' class='c999 size14'>哇库哇库</span></div></a></li>"
		$('.message ul').append(val)
	}
}

function toJson1100(data) {
	var arra = [];
	for (let i in data.data) {
		var thisi = JSON.parse(data.data[i])
		if (thisi.shielding_uid != null && thisi.shielding_uid != '') {
			thisi.shielding_uid = JSON.parse(thisi.shielding_uid)
		}
		arra.push(thisi)
	}
	console.log(1100,'渲染好友列表',arra)
	showmessage(arra);
}

function systemnumber(fid){
	pushMess = `
		{
		  "code": 1702,
		  "data": {
			"uid": ${wcuid},
			"number": 1
		  }
		}
		`
	ws.send(pushMess)
}