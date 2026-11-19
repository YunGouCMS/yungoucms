$.fn.StartTimeOut = function(t, h) {

    var s = $(this);

    var a = new Date();

    a.setSeconds(a.getSeconds() + h);

    var m = 0;

    var p = 0;

    var o = 0;
	

	
    var l = function() {

        var v = new Date();

        if (a > v) {

            var w = parseInt((a.getTime() - v.getTime()) / 1000);

            var u = w % 60;

            m = parseInt(w / 60);

            p = parseInt(u);

            if (u >= p) {

                o = parseInt((u - p) * 10)

            } else {

                o = 0

            } 

            setTimeout(l, 3000)

        }

    };

    var g = s.find("em");

    var b = g.eq(0);

    var k = g.eq(1);

    var d = g.eq(2);

    var r = g.eq(3);

    var f = 9;

    var n = function() {

        f--;

        if (f < 0) {

            f = 9

        }

        r.html(f);

        setTimeout(n, 10)

    };

    var c = function() {
        r.html("0");

        s.find("p[name='pTime']").html("点击查看详情3");

        var v = null;
		
        var u = function() {

            GetJPData(Path.Webpath, "ajax/BarcodernoInfo", t,

            function(w) {
                if (w.code == 0) {
                    var x = "";

                    if (w.codeType == 1) {

                        x = '<span class="z-limit-tips">限时获得</span>'

                    }
                    if (w.img!='photo/member.jpg') {
						s.removeClass("rNow").removeClass("rFirst").html('<a href="/mobile/mobile/dataserver/'+s.id+'"></a><li class="revConS"><div class="revConS-name"><img src="'+w.img +'"><h2><em>幸运者</em>' + w.user + '</h2></div><div class="revConS-time">获得时间：' + w.codeRTime + '</div></li><li class="revConL">' + x + (w.ssc == 1 ?'<i class="zimgs"></i>':'')+'<img src="'+Path.path+'/statics/uploads/'+ w.thumb + '"></li><li class="revConR"><h2>[第' + w.qishu + '期]&nbsp;' + w.title + '</h2><div>幸运号码：<em class="orange arial">' + w.codeRNO + '</em></div><div>参与人次：<em class="arial">' + w.buyCount + '</em></div></li>');
                    } else if(w.headimg!=''){
					   s.removeClass("rNow").removeClass("rFirst").html('<a href="/mobile/mobile/dataserver/'+s.id+'"></a><li class="revConS"><div class="revConS-name"><img src="'+w.headimg+'"><h2><em>幸运者</em>' + w.user + '</h2></div><div class="revConS-time">获得时间：' + w.codeRTime + '</div></li><li class="revConL">' + x + (w.ssc == 1 ?'<i class="zimgs"></i>':'')+'<img src="'+Path.path+'/statics/uploads/'+ w.thumb + '"></li><li class="revConR"><h2>[第' + w.qishu + '期]&nbsp;' + w.title + '</h2><div>幸运号码：<em class="orange arial">' + w.codeRNO + '</em></div><div>参与人次：<em class="arial">' + w.buyCount + '</em></div></li>');

                    }else{ 
					   s.removeClass("rNow").removeClass("rFirst").html('<a href="/mobile/mobile/dataserver/'+s.id+'"></a><li class="revConS"><div class="revConS-name"><img src="'+Path.path+'/statics/uploads/'+ w.img +'"><h2><em>幸运者</em>' + w.user + '</h2></div><div class="revConS-time">获得时间：' + w.codeRTime + '</div></li><li class="revConL">' + x + (w.ssc == 1 ?'<i class="zimgs"></i>':'')+'<img src="'+Path.path+'/statics/uploads/'+ w.thumb + '"></li><li class="revConR"><h2>[第' + w.qishu + '期]&nbsp;' + w.title + '</h2><div>幸运号码：<em class="orange arial">' + w.codeRNO + '</em></div><div>参与人次：<em class="arial">' + w.buyCount + '</em></div></li>');
				
                    }

                    if (v != null) {

                        clearInterval(v);

                        v = null

                    }
					send(w);

                }

            })

        };

        v = setInterval(u, 2000)

    };
	
	var send = function(w){
	    GetJPData(Path.Webpath, "ajax/send_lottery_info", w.id,

            function(w) {
				
				  })
		
	}
    var j = function() {

        o--;

        if (o < 1) {

            if (p < 1) {

                if (m < 1) {

                    c();

                    return

                } else {

                    m--

                }

                p = 59

            } else {

                p--

            }

            o = 9

        }

        setTimeout(j, 100)

    };

    var e = 0,

    q = 0;

    var i = function() {

        d.html(o);

        if (e != p) {

            if (p < 10) {

                k.html("0" + p)
            } else {
                k.html(p)

            }

            e = p

        }
        b.html("12");

            if (m > 9) {
                b.html(m)            
            } else {
                b.html("0" + m)

            }
            q = m
        setTimeout(i, 100)

    };

    l();

    j();

    n();

    i()

};