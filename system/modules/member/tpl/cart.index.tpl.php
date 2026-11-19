<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>聊天</title>
    <!-- 高级骨架屏系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <!-- 统一后台页面美化样式（参考webcfg标准） -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <!-- 后台通用页面增强样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <!-- 通知消息系统样式 -->
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/cartindex.css">
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-3.4.1.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/vue/vue.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
</head>
<style>
    .chatBox{height:654px!important;}
	.cover {width: 40px;height: 40px;top: -20px;border-radius: 50%;}
	.frnd_message p {right: -65px;}
	.my_message p {right: 65px;}
	.my_message img {right: 0;left: auto;}
</style>
<body>
<div id="app" class="container">
    <input type="hidden" id="user_id" value="<?php echo $user_id;?>">
    <div class="leftSide">
        <!--header-->
        <div class="header" style="display: flex;justify-content: start;">
            <div class="userimg">
                <img src="<?php echo $userinfo['img'];?>" class="cover" style="top: 0;">

            </div>
            <ul class="nav_icons">
                <li>
                    <h4><?php echo $userinfo['username'] ?: "USERID:".$userinfo['uid'];?></h4>
                </li>
<!--                <li><img src="消息.png"></li>-->
<!--                <li><img src="更多.png"></li>-->
            </ul>
        </div>
        <!--search-->
        <!--ChatList-->
        <div class="chatlist" v-for="(v,k) of frinds" :key="k">
            <div class="block" :class="currentFrind.uid==v.uid?'active':''" @click="Toviewfrind(v)">
                <div class="imgbx">
                    <img :src="v.img" class="cover" style="top: 0;">
                </div>
                <div class="details">
                    <div class="listHead">
                        <h4>{{v.username||v.mobile}}</h4>
<!--                        <p class="time">14:25</p>-->
                    </div>
<!--                    <div class="message_p">-->
<!--                        <p>什么时候出去玩？</p>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
    </div>
    <div class="rightSide">
        <div class="header">
            <div class="imgText">
                <div class="userimg">
                    <img :src="currentFrind.img" class="cover" style="top: 0;">
                </div>
                <h4>{{currentFrind.username|| currentFrind.mobile}}<br><span></span></h4>
            </div>
<!--            <ul class="nav_icons">-->
<!--                <li><img src="消息.png"></li>-->
<!--                <li><img src="更多.png"></li>-->
<!--            </ul>-->
        </div>
        <!--chatbox-->
        <div class="chatBox">
            <div v-if="msglist.length>0">
                <div class="message" :class="v.from_id==user_id?'my_message':'frnd_message'" v-for="(v,k) of msglist">
                    <img :src="v.from_id==user_id?'<?php echo $userinfo[img];?>':currentFrind.img" class="cover" alt="">
                    <p>{{JSON.parse(v.content)}}<br><span>{{formatDate(v.send_time)}}</span> <span v-if="v.status==1">已读</span> <span v-if="v.status==0">未读</span><span v-if="v.status==2">撤回</span></p>
                </div>
            </div>
            <div v-else>
                <h3 style="text-align: center">暂无聊天记录</h3>
            </div>
<!--            <div class="message my_message">-->
<!--                <p>你在干嘛呢？<br><span>10:15</span> </p>-->
<!--            </div>-->
<!--            <div class="message frnd_message">-->
<!--                <p>在写作业<br><span>10:15</span> </p>-->
<!--            </div>-->
<!--            <div class="message frnd_message">-->
<!--                <p>天气太热了天气太热了天气太热了天气太热了天气太热了天气太热了天气太热了天气太热了天气太热了天气太热了天气太热了天气太热了<br><span>10:15 </span> </p>-->
<!--            </div>-->
<!--            <div class="message my_message">-->
<!--                <p>对啊 也不下雨<br><span>10:15</span> </p>-->
<!--            </div>-->

        </div>

        <!--chat input-->
<!--        <div class="chatbox_input">-->
<!--            <div class="instrument">-->
<!--                <img src="表情.png" >-->
<!--                <img src="语音.png">-->
<!--            </div>-->
<!--            <div>-->
<!--                <input type="text">-->
<!--            </div>-->
<!--        </div>-->
    </div>

</div>
</body>
<script>
    let vm = new Vue({
        el:'#app',
        data:{
            frinds:{},
            user_id:0,
            msglist:{},
            currentFrind:{}
        },
        created() {
            this.user_id = $("#user_id").val()
            this.initIm();
        },
        destroyed() {

        },
        methods:{
            initIm(){
                var _this = this
                $.post('/member/member/getcartFrind',{user_id: this.user_id},function (res) {
                    var rest = JSON.parse(res)
                    if (rest.code !=1){
                        layer.msg('没有记录')
                        return
                    }
                    if(rest.data.length<1){
                        layer.msg('没有记录')
                        return
                    }
                    _this.frinds =rest.data
                    _this.currentFrind = rest.data[0]
                    console.log(_this.currentFrind)
                    _this.getFrendMsg()
                })
            },
            Toviewfrind(item){
                this.currentFrind = item
                this.getFrendMsg()
            },
            getFrendMsg(){
                var data = {
                    from_id:this.user_id,
                    to_uid:this.currentFrind.uid
                }
                var _this =this
                $.post('/member/member/getchatmsg',data,function (res) {
                    var rest = JSON.parse(res)
                    _this.msglist = rest.data
                    // if (rest.code !=1){
                    //     layer.msg('查询错误')
                    //     return
                    // }
                    if(rest.data.length<1){
                        layer.msg('没有查询到记录')
                        return
                    }
                    // _this.frinds =rest.data
                    // _this.currentFrind = rest.data[0]
                })
            },
            formatDate(value, type = 1){
                var time = new Date(value * 1000);
                var year = time.getFullYear();
                var month = time.getMonth() + 1;
                var date = time.getDate();
                var hour = time.getHours();
                var minute = time.getMinutes();
                var second = time.getSeconds();
                month = month < 10 ? "0" + month : month;
                date = date < 10 ? "0" + date : date;
                hour = hour < 10 ? "0" + hour : hour;
                minute = minute < 10 ? "0" + minute : minute;
                second = second < 10 ? "0" + second : second;
                var arr = [
                    year + "-" + month + "-" + date,
                    year + "-" + month + "-" + date + " " + hour + ":" + minute + ":" + second,
                    year + "年" + month + "月" + date,
                    year + "年" + month + "月" + date + " " + hour + ":" + minute + ":" + second,
                    hour + ":" + minute + ":" + second
                ]
                return arr[type];
            }
        }
    })
</script>
</html>