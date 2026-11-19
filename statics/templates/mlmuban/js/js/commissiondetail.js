$(function() {

    var a = function() {	 

        var l = $(".van-list");//$("#ulRechage");

        var i = $("#ulConsumption");

        var d = $("#divLoad");

        var c = $("#btnLoadMore");

        var b = $("#btnRecharge");

        var f = $("#btnConsumption");

        var u = false;

        var o = false;

        var g = 10;

        var h = null;

        var e = null;

        var m = 0;

        var p = false;

        var n = false;

        var k = false;

        var j = $("#loadDataType").val() === "0"; 
		
		var list = $('.van-list');

        var r = function() {

            var x = 0;

            var v = {

                FIdx: 0,

                EIdx: g,

                isCount: 1,
				
				page: 1

            };

            var w = function() {

                return "/" + v.FIdx + "/" + v.EIdx + "/" + v.isCount

            };

            var y = function() {

                d.show();				 

				GetJPData(Gobal.Webpath, "shopajax", "getCommissionDetail"+w(),

                function(D) {

                    if (D.code == 0) {

                        var B = "";

                        if (v.isCount == 1) {

                            x = D.count;

                            v.isCount = 0;

                        //    B = '<li class="m-userMoneylst-tt"><span>充值时间</span><span>充值金额</span><span>充值渠道</span></li>'

                        }

                        var C = D.listItems;

                        var A = C.length;

                        for (var z = 0; z < A; z++) {
							/*
							if(C[z].content.indexOf('转账') > -1) {
                                B += '<li class="' + (z + 1 == A ? "bornone": "") + '"><span>' + C[z].time + "</span><span>￥" + C[z].money + "</span><span>系统充值</span></li>"
                            } else {
                                B += '<li class="' + (z + 1 == A ? "bornone": "") + '"><span>' + C[z].time + "</span><span>￥" + C[z].money + "</span><span>" + C[z].content + "</span></li>"
                            }*/
							B += '<div class="flex content">';
							B += '<div class="flex-item">' + C[z].time + '</div>' 
							B += '<div class="flex-item">' + C[z].username + '</div>'
							B += '<div class="flex-item">' + C[z].ygmoney + '</div>'
							B += '<div class="flex-item" style="flex: 0.4 1 0%;">消费</div>'
							B += '<div class="flex-item" style="flex: 0.8 1 0%; color:#08a600">+' + C[z].money + '</div>'
							B += '</div>'

                        }

                        if (v.FIdx > 0) {

                            l.children("li").last().removeClass("bornone")

                        }

                        l.append(B);

                        if (v.EIdx < x) {

                            c.show()
							p = false

                        } else {

                            n = true;
							p = true

                            c.hide()
                            l.append('<div style="text-align: center;margin-top:10px">没有更多了</div>');

                        }

                    } else {

                        if (D.code == 10) {

                            location.reload()

                        } else {

                            if (v.FIdx == 0) {

                                n = true;

                            //    l.append(Gobal.NoneHtml);
								l.append('<div style="text-align: center;margin-top:10px">暂无记录</div>');
                                c.hide()

                            }

                        }

                    }

                    d.hide();

                    u = true;

                //    p = false

                })

            };

            this.initPage = function() {

                y()

            };

            this.getNextPage = function() {

                v.FIdx += g;

                v.EIdx += g;

                y()

            }

        };

        var t = function() {

            var x = 0;

            var v = {

                FIdx: 0,

                EIdx: g,

                isCount: 1

            };

            var w = function() {

                return "/" + v.FIdx + "/" + v.EIdx + "/" + v.isCount

            };

            var y = function() {

                d.show();				

                GetJPData(Gobal.Webpath, "shopajax", "getUserConsumption"+w(),

                function(D) {

                    if (D.code == 0) {

                        var B = "";

                        if (v.isCount == 1) {

                            v.isCount = 0;

                            x = D.count;							 

                            B = '<li class="m-userMoneylst-tt"><span>消费时间</span><span>消费数额</span></li>'

                        }

                        var C = D.listItems;

                        var A = C.length;

                        for (var z = 0; z < A; z++) {

				 if(C[z].content.indexOf('转账')>-1){
                                B += '<li class="' + (z + 1 == A ? "bornone": "") + '"><span>' + C[z].time + "</span><span>" + C[z].content.split('|')[0] + "</span></li>"
                            } else {
                                B += '<li class="' + (z + 1 == A ? "bornone": "") + '"><span>' + C[z].time + "</span><span>花费 " + C[z].money + " 个参与了抽奖</span></li>"
                            }

                        }

                        if (v.FIdx > 0) {

                            i.children("li").last().removeClass("bornone")

                        }

                        i.append(B);						 

                        if (v.EIdx < x) {

                            c.show()
							
							p = false

                        } else {

                            k = true;
							p = true

                            c.hide()
			    
                            i.append('<div style="text-align: center;color: #ccc;">只显示最近一个月</div>');

                        }

                    } else {

                        if (D.code == 10) {

                            location.reload()

                        } else {

                            if (v.FIdx == 0) {

                                i.append(Gobal.NoneHtml);

                                k = true;

                                c.hide()

                            }

                        }

                    }

                    d.hide();

                    o = true;

                //    p = false

                })

            };

            this.initPage = function() {

                y()

            };

            this.getNextPage = function() {

			  

                v.FIdx += g;

                v.EIdx += g;				 

                y()

            }

        };

        h = new r();

        e = new t();

        var q = function() {

            m = 0;
			/*
            b.removeClass("z-MoneyNav-crt02");

            f.addClass("z-MoneyNav-crt01");

            l.hide();

            i.show();*/

            if (!k) {

                c.show()

            } else {

                c.hide()

            }

            if (!o) {

                e.initPage()

            }

        };

        var s = function() {

            m = 1;
		/*
            b.addClass("z-MoneyNav-crt02");

            f.removeClass("z-MoneyNav-crt01");

            l.show();

            i.hide();*/

            if (!n) {

                c.show()

            } else {

                c.hide()

            }

            if (!u) {

                h.initPage()

            }

        };

        if (j) {

            s()

        } else {

            q()

        }

        f.click(q);

        b.click(s);

        c.click(function() {

            if (!p) {

                p = true;

                c.hide();

                if (m == 1) {

                    h.getNextPage()

                } else {

                    e.getNextPage()

                }

            }

        })
		
		$(window).scroll(function () {
			if (($(window).scrollTop() > (list.height() - $(window).height() + 100))) {
				c.click()
			}
		});

    };

    Base.getScript(Gobal.Skin + "/js/mobile/comm.js", a)

});
