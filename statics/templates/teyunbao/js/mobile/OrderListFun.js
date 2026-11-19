$(function() {

    var a = function() {

        var b = $("#divOrderLoading");

        var h = $("#btnLoadMore");
		
		var L=$('.symbol_left').val();
		
		var R=$('.symbol_right').val();
		
        var f = 0;

        var i = 10;

        var c = {

            FIdx: 0,

            EIdx: i,

            isCount: 1

        };

        var g = null;

        var e = false;

        var d = function() {

            var j = function() {

                return "/" + c.FIdx + "/" + c.EIdx + "/" + c.isCount

            };

            var k = function() {

                h.hide();

                b.show();                

                GetJPData(Gobal.Webpath, "shopajax", "getUserOrderList"+j(),

                function(p) {                
                    if (p.code == 0) {

                        if (c.isCount == 1) {

                            c.isCount = 0;

                            f = p.count

                        }

                        var o = p.listItems;

                        var n = o.length;

                        var m = "";

                        

                        for (var l = 0; l < n; l++) {                        
                            m += '<li ><a class="fl z-Limg" href="'+Gobal.Webpath+'/mobile/mobile/item/' + o[l].shopid +'">'+ (o[l].ssc == 1 ?'<i class="zimgs"></i>':"")+'<img src="' + Gobal.LoadPic + '" src2="'+Gobal.imgpath+'/uploads/' + o[l].thumb + '" border=0 alt=""/></a><div class="u-gds-r gray9"><p class="z-gds-tt"><a href="'+Gobal.Webpath+'/mobile/mobile/item/' + o[l].shopid +'" class="gray6" id="title'+ o[l].aid+'">(第' + o[l].qishu + "期)" + o[l].shopname + '</a></p><p>幸运码：<em class="orange">' + o[l].q_user_code + "</em>&nbsp;&nbsp;&nbsp;&nbsp;<em>总价值："+ L +o[l].money+ R +"</em></p><p>获得时间：" + o[l].q_end_time + "</p>";
                            var q = parseInt(o[l].orderState);
                           
                            //m += '<a href="'+Gobal.Webpath+'/mobile/home/address"  class="z-gds-btn">收货地址</a>'
                           

                        if (o[l].adr) {
                                 m += '<a href="'+Gobal.Webpath+'/mobile/home/orderlogistics/'+o[l].aid+'" class="z-gds-btn z-gds-btnDis">已转赠他人</a>' 
                           }else{ 
                             if(o[l].status.indexOf("已回收")>-1){
                                    m += '<a href="javascript:void(0);" class="z-gds-btn z-gds-btnDis">已完成</a>'
                                }else{
                                    if (o[l].status.indexOf("未发货")>-1 && o[l].status.indexOf("已付款")>-1 && o[l].status.indexOf("已作废")<0) {
                                       
                                        if(!o[l].addreinfo){
											m += '<a href="'+Gobal.Webpath+'/mobile/home/address/'+o[l].code+'"  class="z-gds-btn">确认地址</a>'
                                         if(o[l].proportion){
                                            m += '<a href="javascript:;"  class="z-gds-btn recover"  ids="'+o[l].aid+'" proportion="'+o[l].proportion+'" moneycount="'+o[l].money+'" shopid="'+o[l].shopid+'">在线兑换</a>' 
                                        }    
                                        }else{

                                        m += '<a href="'+Gobal.Webpath+'/mobile/home/orderlogistics/'+o[l].aid+'" class="z-gds-btn z-gds-btnDis">正在备货</a>' 
                                        }
                                        
                                    } else {
                                        if (o[l].status.indexOf("已发货")>-1 && o[l].status.indexOf("待收货")>-1 && o[l].status.indexOf("已付款")>-1) {
                                            m += '<a href="'+Gobal.Webpath+'/mobile/home/orderlogistics/'+o[l].aid+'" class="z-gds-btn z-gds-btnDis">已发货</a>'
                                            if(!o[l].addreinfo){
                                             m +='<a  class="z-gds-btn wuliubtn" oid="'+o[l].aid+'" uid="'+o[l].uid+'">确认收货</a>'   
                                            }
                                            
                                        } else {
                                            if (o[l].status.indexOf("已发货")>-1 && o[l].status.indexOf("已完成")>-1 && o[l].status.indexOf("已付款")>-1) {
                                                if (parseInt(o[l].IsPostSingle) == 0) {
                                                    m += '<a href="javascript:void(0);" class="z-gds-btn z-gds-btnDis">订单已完成</a><a href="javascript:void(0);" class="z-gds-btn z-gds-btnDis">已晒单</a>'
                                                } else {
                                                   // m+='<a href="'+Gobal.Webpath+'/mobile/home/postsingle/'+o[l].shopid+'" class="z-gds-btn">去晒单</a>'
                                                    m += '<a href="'+Gobal.Webpath+'/mobile/home/orderlogistics/'+o[l].aid+'" class="z-gds-btn z-gds-btnDis">订单已完成</a>'
                                                }
                                            } else {
                                                if (o[l].status.indexOf("已作废")>-1) {
                                                    
                                                    m += '<a href="javascript:void(0);" class="z-gds-btn z-gds-btnDis">订单已取消</a>'
                                                }
                                            }
                                        }
                                    }
                            }

                            m += '</div><b class="z-arrow" data-url="'+Gobal.Webpath+'/mobile/user/buyDetail/' + o[l].shopid +'"></b></li>'

                          }

                        }

                        if (c.FIdx > 0) {

                            b.prev().removeClass("bornone")

                        }

                        b.before(m).prev().addClass("bornone");
                        
                        $(".wuliubtn").click(function(){
                            var uid = $(this).attr("uid");  
                            var oid = $(this).attr("oid");  
                            $.post("/index.php/api/dingdan/set",{"uid":uid,"oid":oid},function(sdata){
                                if(sdata=='1'){
                                    alert("更新成功");
                                }else{
                                    alert("更新失败");
                                }                                              
                            });                   
                        }); 
                        $(".z-arrow").click(function(){
                            var uid = $(this).attr("data-url"); 
                            window.location.href=uid;
                        }); 
                        $(".recover").click(function(){
                            var id=$(this).attr('ids'),proportion=$(this).attr('proportion'),moneycount=$(this).attr('moneycount'),shopid=$(this).attr('shopid');
                            var money=Math.floor(moneycount * proportion *0.01);
                            var href=Gobal.Webpath+'/mobile/mobile/item/'+shopid;
                            var title=$('#title'+id).html();
                            var ock="recovers("+id+","+money+");"; 
                            $('.recover_tc').show();
                            $("#showtitle").attr('href',href);
                            $("#showtitle").html(title);
                            $(".gray").html(moneycount);
                            $("#obut").attr('onclick',ock);
                            $(".dmoney").html(money+".00");
                            
                        })
                        if (c.EIdx < f) {

                            e = false;

                            h.show()

                        }

                        loadImgFun()

                    } else {

                        if (p.code == 10) {

                            location.reload()

                        } else {

                            if (c.FIdx == 0) {

                                b.before(Gobal.NoneHtml)

                            }

                        }

                    }

                    b.hide()

                })

            };

            this.getInitPage = function() {

                k()

            };

            this.getNextPage = function() {

                c.FIdx += i;

                c.EIdx += i;

                k()

            }

        };

        h.click(function() {

            if (!e) {

                e = true;

                g.getNextPage()

            }

        }).show();

        g = new d();

        g.getInitPage()

    };

    Base.getScript(Gobal.Skin + "/js/mobile/comm.js", a)

});