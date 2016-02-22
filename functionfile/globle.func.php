<?php
function _runtime(){
	$_mtime=explode(' ', microtime());
	return $_mtime[1]+$_mtime[0];
}
function _alert_back($info){
	echo '<script type="text/javascript">alert("'.$info.'");history.back();</script>';
	exit();
}
function _location($_info,$_url){
    echo "<script language='JavaScript'>window.alert('".$_info."')</script>";
    echo "<script language='JavaScript'>window.location='".$_url."'</script>";
	exit();
}
function _login_state(){
	if(isset($_COOKIE['username'])){
		_alert_back('登录状态无法进行本操作！');
	}
}
function _session_destroy(){
	session_destroy();
}
function _unsetcookies(){
	setcookie('username','',time()-1);
	_session_destroy();
	_location(null, 'index.php');
}
function _sha1_uniqid(){
	return _mysql_string(sha1(uniqid(rand(),true)));
}
function _mysql_string($_string){
/*
	if(!GPC){
		return mysql_real_escape_string($_string);
	}
	*/
	return $_string;
}
function _delete_file($filename){
    unlink($filename);
}
function _get_file_suffix($filename){
    $arrayTmp=explode(".", $filename);
    $fileSuffix=$arrayTmp[count($arrayTmp)-1];
    return $fileSuffix;
}
function strLength($str,$charset='utf-8'){
    if($charset=='utf-8') $str = iconv('utf-8','gb2312',$str);
    $num = strlen($str);
    $cnNum = 0;
    for($i=0;$i<$num;$i++){
        if(ord(substr($str,$i+1,1))>127){
            $cnNum++;
            $i++;
        }
    }
    $enNum = $num-($cnNum*2);
    $number = ($enNum/2)+$cnNum;
    return ceil($number);
}
//截取utf8字符串
function utf8Substr($str, $from, $len)
{
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
            '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
            '$1',$str);
}

function cutString($str, $from, $len){
    $length=mb_strlen($str,'UTF8');
    $substr=utf8Substr($str, $from, $len);
    if($length>$len){
        return $substr.'...';
    }
    return $substr;
}
?>