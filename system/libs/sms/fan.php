<?php

function balance($mobile){
    $clientId = $mobile['cid'];
    $clientSecret = $mobile['mpass'];
    $url = "https://www.sms.fan/check-balance?clientId={$clientId}&clientSecret={$clientSecret}";
    $res = getCurl($url);
    return json_decode($res,true);
}

?>