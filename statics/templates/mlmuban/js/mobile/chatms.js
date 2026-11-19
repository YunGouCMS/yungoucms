var wcuid = $('#wcuid').val(),Pathpath = PathWebpath.split('/')[2];

var Interval50 =  setInterval(function() {
	pushMess = `{ "code": 1600, "data": { "uid": ${wcuid} }}`
	ws.send(pushMess)// 每50秒请求一次，维持心跳
}, 50000);

function toDate(number){
  var date = new Date(number);
  var y = date.getFullYear();
  var m = date.getMonth() + 1;
  m = m < 10 ? ('0' + m) : m;
  var d = date.getDate();
  d = d < 10 ? ('0' + d) : d;
  var h = date.getHours();
  h = h < 10 ? ('0' + h) : h;
  var minute = date.getMinutes();
  var second = date.getSeconds();
  minute = minute < 10 ? ('0' + minute) : minute;
  second = second < 10 ? ('0' + second) : second;
  // return y + '-' + m + '-' + d + ' ' + h + ':' + minute + ':' + second;//年月日时分秒
  // return y + '-' + m + '-' + d + ' ' + h + ':' + minute;//年月日时分
  return m + '-' + d + ' ' + h + ':' + minute;
}