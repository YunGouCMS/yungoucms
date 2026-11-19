var locationhref = $(location).attr("href")
var lohref = locationhref.split('/')
var toid = parseInt(lohref[lohref.length-1])
var tableid = parseInt(lohref[lohref.length-2])
var forstatus = parseInt(lohref[lohref.length-3])
var myname = '',youname = ''

let port = 'ws://'+Pathpath+':9504/chat?myId=' + wcuid,heightt = 0,ismouse = 0,mess_id = 0;
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
		enterRoom();
	} else if (arr.code == 1300) {
		toJson1300(arr,'three');
	} else if (arr.code == 1001) {
		arr.data = [arr.data]
		toJson1300(arr,'one');
	} else if (arr.code == 1301) {
		$("#"+mess_id).parent().html('您撤回了一条消息').removeClass().addClass('textcon')
	} else if (arr.code == 1303) {
		$("#"+mess_id).parent().remove()
	} else if (arr.code == 1304) {
		var tarar =  JSON.parse(arr.data)
		$("#"+tarar.id).parent().html('对方撤回了一条消息').removeClass().addClass('textcon')
	} else if (arr.code == 200) {
		$.PageDialog.fail(arr.msg);
	} else if (arr.code == 1102) {
		var t1102 = JSON.parse(arr.data)
		if (wcuid == t1102.b_uid) {
			$('.fixed_header>span').html(t1102.a_name)
			myname = t1102.b_img;youname = t1102.a_img
		} else {
			$('.fixed_header>span').html(t1102.b_name)
			youname = t1102.b_img;myname = t1102.a_img
		}
	} else {
		console.log(arr) //监听接受来自服务端的信息
	}
}
//显示消息
function showmesscon(data) {
	for (var t in data){
		if ( data[t].delect_data != null) {
			for (var ti of data[t].delect_data){
				if (wcuid == ti.uid) return
			}
		}
		var tval = ""
		if (t == 0 || data[t-1].send_time!=data[t].send_time) {
			tval += "<div class='timecon'>"+data[t].send_time+"</div>";
			heightt += 24
		}
		var yiweidu = data[t].status==0?'未读':'已读'
		if (toid == data[t].from_id) {
			if (data[t].status == 2) {
				tval += "<div class='textcon'>对方撤回了一条消息</div>";
			} else {
				tval += "<div class='newscon newsright'><img src='"+youname+"'><div id='"+data[t].id+"'>"+data[t].content+"</div><span>"+yiweidu+"</span></div>";
			}
		} else {
			if (data[t].status == 2) {
				tval += "<div class='textcon'>您撤回了一条消息</div>";
			} else {
				tval += "<div class='newscon newsleft'><span>"+yiweidu+"</span><div id='"+data[t].id+"'>"+data[t].content+"</div><img src='"+myname+"'></div>";
			}
		}
		heightt += 50
		$('.messcon').append(tval)
	}
	$('.messcon').scrollTop($('.messcon').height()+heightt)
	mousedown();
}
// json
function toJson1300(data,num) {
	var arra = [];
	for (var i in data.data) {
		var thisi = {};
		if (num == 'three') {
			thisi = JSON.parse(data.data[i])
		} else {
			thisi = data.data[i]
		}
		thisi.content = JSON.parse(thisi.content)
		if (thisi.delect_data != null) {
			if (thisi.delect_data != '') {
				thisi.delect_data = JSON.parse(thisi.delect_data)
			}
		}
		arra.push(thisi)
	}
	console.log(data.code,data.code==1300?'获取聊天消息':data.code==1001?'追加单条消息':'',arra)
	showmesscon(arra);
}

$('.sangdian').click(function(){
	window.location.href=PathWebpath+"/mobile/yunchat/usersetup/"+forstatus+"/"+tableid;
})
$('.keyboard input').bind('input propertychange', function() { 
	var val = $(this).val(),lastdiv = $('.keyboard>div:last-child')
	if (val != '') {
		lastdiv.addClass("jianbian");
	} else {
		lastdiv.removeClass("jianbian");
	}
});

//发送消息
$('.intext input').bind('keypress',function(event){
	if (event.keyCode == 13) {
		chatMess();
	}
})
function chatMess(){
	var val = $('.keyboard input').val()
	if (val == '') return
	$('.keyboard input').val('')
	pushMess = `{
		"code": 1000,
		"data": {
			"from_id": ${wcuid},
			"to_id": ${toid},
			"table_id": ${tableid},
			"msg_type": 1,
			"content": "${val}"
		}
	}`
	var newtime = toDate(new Date()).split(' ')
	ws.send(pushMess)
}
//进房间
function enterRoom(){
	pushMess = `
		{
		  "code": 1200,
		  "data": {
			"from_id": ${wcuid},
			"table_id": ${tableid}
		  }
		}
		`
	ws.send(pushMess)
}
//移除回撤
$('body').click(function(){
	if (ismouse!=0) {
		ismouse = 0
		$('.dousedow').remove()
	}
})
//显示回撤
function mousedown(){
	$('.newsleft>div').click(function(){
		var thiss = $(this)
		mess_id = thiss.attr("id")
		// var mou = "<div class='dousedow sedowleft'><div><span onclick='chatMessRetreat()'>撤回</span><span onclick='chatMessRetreat()'>删除</span></div><div></div></div>"
		var mou = "<div class='dousedow' onclick='chatMessRetreat()'><div>撤回</div><div></div></div>"
		thiss.before(mou)
		setTimeout(function() {
		    ismouse = 1
		}, 500);
	})
	// $('.newsright>div').click(function(){
	// 	var thiss = $(this)
	// 	mess_id = thiss.attr("id")
	// 	var mou = "<div class='dousedow' onclick='chatMessRemove()'><div>删除</div><div></div></div>"
	// 	thiss.before(mou)
	// 	setTimeout(function() {
	// 	    ismouse = 1
	// 	}, 500);
	// })
}
//回撤
function chatMessRetreat(){
	pushMess = `{ "code": 1301, "data": { "uid": ${wcuid}, "table_id": ${mess_id}, "room_id": ${tableid} } }`
	ws.send(pushMess)
}
//删除
function chatMessRemove(){
	pushMess = `{ "code": 1303, "data": { "uid": ${wcuid}, "table_id": ${mess_id}, "room_id": ${tableid} } }`
	ws.send(pushMess)
}