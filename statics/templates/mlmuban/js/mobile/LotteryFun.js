$(function() {
    var isbuttom = false;
    
    var init = true;

    var h = null;

    var f = 10;

    var a = 0;

    var j = {

        FIdx: 0,

        EIdx: f,

        isCount: 1
    };
    var d = $("#divLotteryLoading");

    var l = $("#btnLoadMore");
    
    var n = $("#divLotteryNothing");

    var m = false;

    var c = function(o) {

        if (o && o.stopPropagation) {

            o.stopPropagation()

        } else {

            window.event.cancelBubble = true

        }

    };

    var b = function() {

        var o = function() {

       //     return "/" + j.FIdx + "/" + j.EIdx + "/" + j.isCount + "/" + Date.parse(new Date($('em.c9:last').text()))/1000;

      //        return "/" + j.FIdx + "/" + j.EIdx + "/" + j.isCount + "/" + Date.parse(new Date($('em.c9:last').text().replace(/-/g, "\/").slice(0,-4)))/1000;
                var timestring = $('em.orange:last').text()
                if(!timestring) return 0;
                var ts = timestring ? Date.parse(new Date(timestring.replace(/-/g, "\/").slice(0)))/1000 : Date.parse(new Date())/1000;
                return "/" + j.FIdx + "/" + j.EIdx + "/" + j.isCount + "/" + ts;
        };

		 

        var p = function() {

            d.show();
			var param = o();
            if (init) param = "/" + j.FIdx + "/" + j.EIdx + "/" + j.isCount + "/" + Date.parse(new Date())/1000;
            if (!param ) return;
            GetJPData(Gobal.Webpath, "ajax", "getLotteryList"+param,
       //     GetJPData(Gobal.Webpath, "ajax", "getLotteryList"+o(),

            function(s) {			 

                if (s.code == 0) {

                /*    if (j.isCount == 1) {

                        a = s.count						

                    }*/

                    var r = s.listItems;

                    var t = r.length;

                    for (var q = 0; q < t; q++) {
					//	alert(r[q].q_user)
                        var v = '<ul id="' + r[q].id + '"><li class="revConL">' + (r[q].codeType == 1 ? '<span class="z-limit-tips">限时揭晓</span>': "") + '<g>第' + r[q].qishu + '期</g><img src="' + Gobal.LoadPic + '" src2="'+Gobal.imgpath+'/uploads/' + r[q].thumb + '"></li><li class="revConR"><dl><dd><img name="uImg" uweb="' + r[q].q_uid + '" src="' + r[q].userphoto + '?v=1"></dd><dd><span>获得者<strong>：</strong><a name="uName" uweb="' + r[q].q_uid + '" class="rUserName blue">' + r[q].q_user + '</a></span>本期购买<strong>：</strong><em class="orange arial">' + r[q].gonumber + '</em>人次</dd></dl><dt>幸运码：<em class="orange arial">' + r[q].q_user_code + '</em><br/>获得时间：<em class="c9 arial">' + r[q].q_end_time + '</em></dt><b class="fr z-arrow"></b></li></ul>';

                        var u = $(v);

                        u.click(function() {

                            location.href = Gobal.Webpath+"/mobile/mobile/item/" + $(this).attr("id")

                        }).find('img[name="uImg"]').click(function(w) {

                            location.href = Gobal.Webpath+"/mobile/mobile/userindex/" + $(this).attr("uweb");

                            c(w)

                        });

                        u.find('a[name="uName"]').click(function(w) {

                            location.href = Gobal.Webpath+"/mobile/mobile/userindex/" + $(this).attr("uweb");

                            c(w)

                        });

                        d.before(u)

                    }

                 //   if (j.EIdx < 10000) {

                        m = false;
                        isbuttom = false;

                        l.show()

                //    }

                    loadImgFun()

                } else {
                    isbuttom = true;
                    m = true
                    n.show();
                }

                d.hide()

            })

        };

        this.getInitPage = function() {

            p()

        };

        this.getNextPage = function() {
            init = false;
            j.FIdx += f;

            j.EIdx += f;

            p()

        }

    };

    l.click(function() {

        if (!m) {

            m = true;

            l.hide();

            h.getNextPage()

        }

    });

    h = new b();

    h.getInitPage();

    var e = ",";

    var n = false;

    var g = 0;

    var i = $("#divLottery");

	

    var k = function() {

        GetJPData(Gobal.Webpath, "ajax", "GetStartRaffleAllList/0",// + g,

        function(p) {

            if (p.errorCode == 0) {

                o(p)

            }

            setTimeout(k, 5000)

        });

        var o = function(q) {

            g = q.maxSeconds;

            var p = function(t) {

                for (var r = t.length - 1; r > -1; r--) {

                    var s = t[r];

                    if (e.indexOf("," + s.id + ",") < 0) {

                        e += s.id + ",";

                        var u = $('<ul class="rNow rFirst" id="' + s.id + '"><li class="revConL"><img src="'+Path.path+'/statics/uploads/'+ s.thumb + '"><span>第'+ s.qishu + '期</span></li><li class="revConR"><h4>(第' + s.qishu + "期)" + s.title + "</h4><h5>价值：￥" + CastMoney(s.money) + '</h5><p name="pTime">获得倒计时 <strong><em>00</em> : <em>00</em> : <em>0</em><em>0</em></strong></p><b class="fr z-arrow"></b></li><div class="rNowTitle">正在揭晓</div></ul>');

                        u.click(function() {

                            location.href = "/index.php/mobile/mobile/item/" + $(this).attr("id");

                        });

                        i.prepend(u);

                        u.next().removeClass("rFirst");

                        u.StartTimeOut(s.id, parseInt(s.seconds))

                    }

                }

            };

            if (n) {

                p(q.listItems)

            } else {
                Base.getScript(Gobal.Skin + "/js/mobile/LotteryTimeFun.js",
                function() {
                    n = true;
                    p(q.listItems)

                })

            }

        }

    };

     $(window).scroll(function () {
             if ($(window).scrollTop() >= $(document).height() - $(window).height() - 100) {
                        
                         l.click()
             }
     });
    Base.getScript(Gobal.Skin + "/js/mobile/comm.js", k)

});
