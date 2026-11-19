(function(){

    var web_name=window.location+'?';

    var path_name=window.location;

    var div = $('#divLottery');

    var update = function(info, key, listLen){
                   var html = '<li class="m-lott-item" id="lott-' + info.id + '">'+
                '<a href="'+web_name+'/mobile/mobile/dataserver/' + info.id + '" class="u-lott-pic">'+
                            '<img src="'+path_name+'/statics/uploads/' +info.thumb + '" alt="' + info.title + '" border="0">'+
                               
               ' </a>'+
                '<p class="pnmae"><em>倒计时</em></p>'+
               ' <span class="orange u-time"><em class="minute">99</em>:<em class="second">99</em>:<em class="millisecond">99</em></span>'+
            '</li>';


       // var liWidth = div.find("li").outerWidth();

       var liWidth = $("#published").width()/4;

        var marginLen = -1 * (parseInt(key) + 1) * liWidth;//获取li的宽度

        var tmpWidth = (parseInt(key) + 5) * liWidth;

         var elmExistsss = $('#lott-' + info.id).length; // 重新选择元素，不设变量缓存

         if(elmExistsss){

            return;

         }else{

            div.prepend(html);//在divLottery 的头部插入新揭晓的li  

         }

        

        div.find("li").css("width", liWidth);

       

        div.css({

            "marginLeft" : marginLen,

            "width" : tmpWidth

        });



        if (key >= (listLen - 4)) { // 只创建4个可见元素的setTimeout

            var mydiv = div.find('#lott-' + info.id);

            var minute = mydiv.find('em.minute');

            var second = mydiv.find('em.second');

            var millisecond = mydiv.find('em.millisecond');

            var times = (new Date().getTime()) + info.times * 1000;

            var timer = setInterval(function () {

            var elmExist = $('#divLottery').find('#lott-' + info.id).length; // 重新选择元素，不设变量缓存

                if (!elmExist) { // 元素被移除则释放倒计时

                    clearInterval(timer);

                    return;

                }



                var time = times - (new Date().getTime());



                if (!info.times || time < 1) {



                    clearInterval(timer);
                   
                    if(info.extend_model==14){

                        minute.parent().html('正在获取');

                    }else{

                        minute.parent().html('正在计算');

                    }

                    var checker = setInterval(function () {

                        var elmExist = $('#divLottery').find('#lott-' + info.id).length;

                        if (!elmExist) {

                            clearInterval(checker);

                            return;

                        }

 

                        $.getJSON(web_name+"/mobile/ajax/lottery_huode_shop/"+info.id

                        , function (user) {

                            if(!user.error) {

                                clearInterval(checker);

                                mydiv.removeClass();

                                //mydiv.find(".m-lott-text > p").remove();

                                mydiv.children(".pnmae").text("获奖者");

                                mydiv.find("p:last-child").html('<span class="u-user">' +

                                    '<a href='+web_name+'/mobile/mobile/dataserver/' + info.id + ' class="z-user">' +

                                    user.username + '</a></span>');

									send(info);

                                mydiv.find("p:last-child").removeClass('orange u-time')

                            }

                        });

                    }, 5000);

                    return;

                }



                i = parseInt((time / 1000) / 60);

                s = parseInt((time / 1000) % 60);

                ms = String(Math.floor(time % 1000));

                ms = parseInt(ms.substr(0, 2));

                if (i < 10)i = '0' + i;

                if (s < 10)s = '0' + s;

                if (ms < 10)ms = '0' + ms;

                minute.html(i);

                second.html(s);

                millisecond.html(ms);

            }, 41);

        }



        if (key == (listLen - 1)) {

            div.animate({marginLeft:"0px"},1000,function(){//动画效果 并在回调函数中将第五个起的li移除

                div.css("width", liWidth *4);

                div.children("li:gt(4)").remove();

                setTimeout(thread, 5000);

            });

        }

    };



    var oid = '';

    var thread = function() {

        $.getJSON(web_name+"/mobile/ajax/lottery_going_shoplist",function(infos){

            if(!infos.error) {

                var list =  infos.listItems;

                var infoLen = list.length;

                if(infos.listItems){

                     for (var key in list) {

                        var info = list[key];

                        update(info, key, infoLen);

                    }                   

                }

			

            } else {

                setTimeout(thread, 3000);

            }



        });

    };

	var send = function(info){

		$.getJSON(web_name+"/mobile/ajax/send_lottery_info/"+info.id,function(data){						

		});	

		

	}

   setInterval(thread, 1000);



    

})();



 

 