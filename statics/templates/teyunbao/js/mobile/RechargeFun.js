$(function() {
    var d = level ? 500 : 50;

    var g = false;

    var a = null;

    var f = null;

    var b = null;

    var c = 1;
	
  	var U=$(".unit").val();
  
	var banktype='wxpay_wap';
	
	var RechMoney = {}
	var RechMoneyIndex =0;

    var e = function() {

        var k = function(p, o, n, m) {

            $.PageDialog.fail(p, o, n, m)

        };

        function l(m) {

            m = Math.round(m * 1000) / 1000;

            m = Math.round(m * 100) / 100;

            if (/^\d+$/.test(m)) {

                return m + ".00"

            }

            if (/^\d+\.\d$/.test(m)) {

                return m + "0"

            }

            return m

        }

        var h = /^[1-9]{1}\d*$/;

        var j = "";

        var i = function() {

            var m = a.val();

            if (m != "") {

                if (j != m) {

                    if (!h.test(m)) {

                        a.val(j).focus()

                    } else {

                        j = m;

                        f.html('选择平台充值<em class="orange"> ' + l(m) + "</em> " + U)

                    }

                }

            } else {

                j = "";

                a.focus();

                f.html('选择平台充值<em class="orange"> 0.00 </em> ' + U)

            }

        };
		
		// 获取充值范围
		 $.get('/mobile/home/rechargedata',function (res) {
		    var result = JSON.parse(res)
		     if (result.code ==1){
		         RechMoney = result.data
				 ulBankList();
				 // ulOption_li(RechMoney.paylist[RechMoneyIndex].pay_monerange.ranges.length);
		     }
		 })

		function ulBankList(){
			$("#ulBankList > li").each(function(m) {
				
			    var n = $(this);
				if (m == 0) {
				
				   f = n
	
			    } else {
					n.click(function() {
						c = m;
				
						banktype=n.attr('urm');
				
						n.children("i").attr("class", "z-bank-Roundsel");
				
						n.siblings().children("i").attr("class", "z-bank-Round");
						
						var contents = "";
						var data = RechMoney.paylist[m-1].pay_monerange.ranges
						$("#ulOption").empty()
						for (var i=0;i<data.length;i++){
							var clas = i==0?'class="z-sel"':''
							var html = `
								<li money="${data[i]}" ${clas}><a href="javascript:;">${data[i]+RechMoney.currency.unit}<s></s></a></li>
							`
							$("#ulOption").append(html);
							if (data.length-1==i&&RechMoney.paylist[m-1].pay_monerange.isinput=='1'){
								var htmli = `
									<li> <b><input type="text" class="z-init" placeholder="输入金额" maxlength="8" /><s></s> </b> </li>
								`
								$("#ulOption").append(htmli)
							}
						}
						ulOption_li(data.length);
					})
				}
			
			});
			
			$("#ulBankList>li:eq(1)").click();
		}
		
		function ulOption_li(len){
			$("#ulOption > li").each(function(m) {
				
			    var n = $(this),t_sel = level ? 4 : len;
				
			    if (m < t_sel) {
			
			        n.click(function() {
			
			            g = false;
			
			            d = n.attr("money");
			
			            n.children("a").addClass("z-sel");
			
			            n.siblings().children().removeClass("z-sel").removeClass("z-initsel");
			
			            f.html('选择平台充值<em class="orange"> ' + n.attr("money") + ".00</em> " + U)
			
			        })
			
			    } else {
			
			        a = n.find("input");
			
			        a.focus(function() {
			
			            g = true;
			
			            if (a.val() == "输入金额") {
			
			                a.val("")
			
			            }
			
			            a.parent().addClass("z-initsel").parent().siblings().children().removeClass("z-sel");
			
			            if (b == null) {
			
			                b = setInterval(i, 200)
			
			            }
			
			        }).blur(function() {
			
			            clearInterval(b);
			
			            b = null
			
			        })
			
			    }
			
			});
			
			$("#ulOption > li:eq(0)").click();
		}

        $("#btnSubmit").click(function() {
			var range = RechMoney.paylist[c-1].pay_monerange.range
            d = g ? a.val() : d;
            if (d == "" || parseInt(d) == 0) {

                k("请输入充值金额")

            } else if(parseInt(d)<range.min){
				k("充值金额必须大于或等于 "+ range.min +' ' + U);
			} else if(parseInt(d)>range.max){
				k("充值金额必须小于或等于 "+ range.max +' ' + U);
			} else {

                var m = /^[1-9]\d*\.?\d{0,2}$/;

                if (m.test(d)) {

                        location.href = Gobal.Webpath+"/mobile/cart/addmoney/" + d+"/"+banktype

                } else {

                    k("充值金额输入有误")

                }

            }

        });

    };

    Base.getScript(Gobal.Skin + "/js/mobile/pageDialog.js", e)

});