<?php
//不需要登录的路由
return [
    'user'  =>[
        'userCustody','loginAuth','authBindMobile','login','sendCod','registered','anewsendmobile','sendlogincode','sendlogin','send_logincode',
        'flashLogin','aggregationLogin','aggreagtionNotify','aggregationInfo','getAggLoginType','get_login_type',
        'emailLogin',"send_emailcode","emailcode_login","get_yiwei_login_type",'yiwei_login','yiweiNotify',"getPhoneCode"
    ],
    'userinfo'=>[
        'backpasswordsend','backpassword'
    ],
    'index'=>[
        'init','index','new_shop','search','searchjf','index_soon','integral_index','collection','bannerDetail','copyrightInfo','jifen_navigation','jifen_banner',
        'get_group_status','get_preferential_status','bottom_bar','get_sound_effects','test',
    ],
    'nav'=>[
        'specialOffer','integral'
    ],
    'ptgoodslist'=>[
        'listgoodscalss','catalog','getLotteryList','getUndrawnList'
    ],
    'goodsdetails'=>[
        'details','goodscode','jfgoodsdetails','trendChart','getCalResult',"goodsdesc","goods_participant"
    ],
    'integral'=>[
        'jifen_glist','integral_glist','integral_glist_catalog','announce_details','buyrecords','GetStartRaffleAllList','BarcodernoInfo'
    ],
    'myhome' =>[
        'gonggaoxq','getUserBuyList','getUserInfo','index'
    ],
    'ptcart' =>[
        'recommended'
    ],
    'language' =>[
        'nowUserLanguage', 'languagSelection','getLgInfo'
    ],
    'activity' =>[
        'turntable'
    ],
//    'pay' =>[
//        'go_pay'
//    ],
    'ajax'  =>  [

    ],
    'zp'=>[
        'getPrize','winningRecord'
    ],
    'ks'=>[
        'login','getToken','register'
    ],
    'auto'=>[
        'revealed'
    ],
    'magic'=>[
        'magicLogin'
    ],
];