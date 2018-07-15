<?php
@session_start();
include_once("database.php");

$sql = "select * from users where id = '".$_SESSION['user_id']."'";
$exe = @mysqli_query($link,$sql);
$row = @mysqli_fetch_assoc($exe);
$app_id = @$row['app_id'];
$fb_secret = @$row['app_secret'];
$access_token = @$row['access_token'];

$body = array();
$url="https://graph.facebook.com/v2.8/me/feed?access_token=$access_token"; 
$body['message'] = $_REQUEST['post_message'];
$json_res=post_fb($url,"post",$body);
$response = json_decode($json_res,true);
if(is_array($response) && isset($response['id'])){
    $post_message = mysqli_real_escape_string($link,$_REQUEST['post_message']);
    $sql_camp = "update campaigns set post_message = '".$post_message."' where id = '".$_REQUEST['camp_id']."'";
    mysqli_query($link,$sql_camp);
    echo '<div class="alert alert-success">Posted On Facebook Successfully.</div>';
}else{
	echo '<div class="alert alert-danger">'.$response['error']['message'].'</div>';
}




function post_fb($url,$method,$body=""){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if($method == "post"){
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    else{
        curl_setopt($ch, CURLOPT_HTTPGET, true );   
    }   
    return curl_exec($ch);
}


function post_fb2($url,$method,$body=""){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if($method == "post"){
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    }
    else{
        curl_setopt($ch, CURLOPT_HTTPGET, true );   
    }   
    return curl_exec($ch);
}
    
function getServerURL()
{
    $serverName = $_SERVER['SERVER_NAME'];
    $filePath = $_SERVER['REQUEST_URI'];
    $withInstall = substr($filePath,0,strrpos($filePath,'/')+1);
    $serverPath = $serverName.$withInstall;
    $applicationPath = $serverPath;
    
    if(strpos($applicationPath,'http://www.')===false)
    {
        if(strpos($applicationPath,'www.')===false)
            $applicationPath = 'www.'.$applicationPath;
        if(strpos($applicationPath,'http://')===false)
            $applicationPath = 'http://'.$applicationPath;
    }
    $applicationPath = str_replace("www.","",$applicationPath);
    return $applicationPath;
}
?>