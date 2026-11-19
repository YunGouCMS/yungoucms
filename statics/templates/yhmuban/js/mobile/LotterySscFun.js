$(function() {

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

            return "/" + j.FIdx + "/" + j.EIdx + "/" + j.isCount

        };

		 

        var p = function() {

            d.show();

            GetJPData(Gobal.Webpath, "ajax", "getLotteryListSsc"+o(),

            function(s) {			 

                if (s.code == 0) {

                    if (j.isCount == 1) {

                        a = s.count						

                    }

                    var r = s.listItems;

                    var t = r.length;

                    for (var q = 0; q < t; q++) {
														if(r[q].userphoto!='photo/member.jpg') {								var v='<ul id="' + r[q].id + '"><li class="revConS"><div class="revConS-name"><img src="'+ r[q].userphoto + '"><h2><em>幸运者</em>' + r[q].q_user + '</h2></div><div class="revConS-time">获得时间：' + r[q].q_end_time + '</div></li><li class="revConL">' + (r[q].codeType == 1 ? '<span class="z-limit-tips">限时获得</span>': "") +(r[q].ssc == 1 ?'<i class="zimgs"></i>':'')+ '<img   src="'+Gobal.imgpath+'/uploads/' + r[q].thumb + '"></li><li class="revConR"><h2>[第' + r[q].qishu + '期]&nbsp;' + r[q].title + '</h2><div>幸运号码：<em class="orange arial">' + r[q].q_user_code + '</em></div><div>参与人次：<em class="arial">' + r[q].gonumber + '</em></div></li></ul>';                            }else if(r[q].userphotow!=''){								var v='<ul id="' + r[q].id + '"><li class="revConS"><div class="revConS-name"><img src="'+r[q].userphotow+'"><h2><em>幸运者</em>' + r[q].q_user + '</h2></div><div class="revConS-time">获得时间：' + r[q].q_end_time + '</div></li><li class="revConL">' + (r[q].codeType == 1 ? '<span class="z-limit-tips">限时获得</span>': "")+(r[q].ssc == 1 ?'<i class="zimgs"></i>':'')+ '<img  src="'+Gobal.imgpath+'/uploads/' + r[q].thumb + '"></li><li class="revConR"><h2>[第' + r[q].qishu + '期]&nbsp;' + r[q].title + '</h2><div>幸运号码：<em class="orange arial">' + r[q].q_user_code + '</em></div><div>参与人次：<em class="arial">' + r[q].gonumber + '</em></div></li></ul>';                            }else{								var v='<ul id="' + r[q].id + '"><li class="revConS"><div class="revConS-name"><img src="' + r[q].userphoto + '"><h2><em>幸运者</em>' + r[q].q_user + '</h2></div><div class="revConS-time">获得时间：' + r[q].q_end_time + '</div></li><li class="revConL">' + (r[q].codeType == 1 ? '<span class="z-limit-tips">限时获得</span>': "") +(r[q].ssc == 1 ?'<i class="zimgs"></i>':'')+ (r[q].thumb ==''?'<img src="' + Gobal.LoadPic + '">':'<img src="'+Gobal.imgpath+'/uploads/' + r[q].thumb + '"')+'</li><li class="revConR"><h2>[第' + r[q].qishu + '期]&nbsp;' + r[q].title + '</h2><div>幸运号码：<em class="orange arial">' + r[q].q_user_code + '</em></div><div>参与人次：<em class="arial">' + r[q].gonumber + '</em></div></li></ul>';								                            }																																										
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

                    if (j.EIdx < a) {

                        m = false;

                        l.show()

                    }

                    loadImgFun()

                }

                d.hide()

            })

        };

        this.getInitPage = function() {

            p()

        };

        this.getNextPage = function() {

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

        GetJPData(Gobal.Webpath, "ajax", "GetStartRaffleAllListSsc/" + g,

        function(p) {

            if (p.errorCode == 0) {

                o(p)

            }

            setTimeout(k, 5000)

        });

        var o = function(q) {

           // g = q.maxSeconds;
            var p = function(t) {

                for (var r = t.length - 1; r > -1; r--) {

                    var s = t[r];

                    if (e.indexOf("," + s.id + ",") < 0) {

                        e += s.id + ",";
						var u=$('<ul class="rNow rFirst" id="' + s.id + '"><li class="revConL"><i class="zimgs"></i><img src="'+Path.path+'/statics/uploads/'+ s.thumb + '"></li><li class="revConR"><p name="pTime"><s></s>获得倒计时 <strong><em>00</em> : <em>00</em> : <em>0</em><em>0</em></strong></p><h4>[第' + s.qishu + "期]" + s.title + "</h4><h5>总需人次：" + s.zongrenshu + '</h5></li><div class="rNowTitle">正在揭晓</div></ul>');
                        u.click(function() {

                            location.href = "/mobile/mobile/item/" + $(this).attr("id");

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

    Base.getScript(Gobal.Skin + "/js/mobile/comm.js", k)

            $(window).scroll(function () {         
            if ($(document).height() - $(this).scrollTop() - $(this).height() < 1
                    && $('#btnLoadMore').css('display')!='none' ){
                if (!m) {
                    m = true;
                    l.hide();
                    h.getNextPage();
                }
            }
        });
});