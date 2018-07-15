<?php
@session_start();
include_once("database.php");

$sql = "select * from users where id = '".$_SESSION['user_id']."'";
$exe = @mysqli_query($link,$sql);
$row = @mysqli_fetch_assoc($exe);
$app_id = @$row['app_id'];
$fb_secret = @$row['app_secret'];


if(isset($_REQUEST['app_id']) && isset($_REQUEST['app_secret'])){    
    $sql = "update users set app_id = '".$_REQUEST['app_id']."', app_secret = '".$_REQUEST['app_secret']."', access_token='".$_REQUEST['fb_user_access_token']."' where id = '".$_SESSION['user_id']."'";
    mysqli_query($link,$sql)or die(mysqli_error($link));
    
    $app_id = $_REQUEST['app_id'];
    $fb_secret = $_REQUEST['app_secret'];
}

    
$redirect_url = getServerURL().'api.php';
$redirect_url= urlencode($redirect_url); 

//url which you entered in fb app settings and this is current page's url 
//req_perms
//$login_url="https://www.facebook.com/dialog/oauth?client_id=$app_id&redirect_uri=$redirect_url&response_type=code&scope=manage_pages,publish_pages";
//$login_url="https://www.facebook.com/dialog/oauth?client_id=$app_id&redirect_uri=$redirect_url&response_type=code&scope=email,user_hometown,user_location,user_birthday,public_profile,user_friends,user_activities,user_likes,manage_pages";
    //$login_url="https://www.facebook.com/dialog/oauth?client_id=$app_id&redirect_uri=$redirect_url&response_type=code";
$login_url="https://www.facebook.com/dialog/oauth?client_id=$app_id&redirect_uri=$redirect_url&response_type=code&scope=email,public_profile,publish_actions";
    

if(isset($_GET['code']) && $_GET['code']!="")
{
    $code = $_GET['code'];
    $redirect_url = getServerURL().'api.php';
    
    $redirect_url= urlencode($redirect_url); 
    $token_url="https://graph.facebook.com/oauth/access_token?client_id=$app_id&redirect_uri=$redirect_url&type=token&client_secret=$fb_secret&code=$code";
    $access_token=post_fb($token_url,"get");
    //$fb_success=json_decode($access_token,true) == NULL ? "token" : "error";
    $fb_success=json_decode($access_token,true);
    /*
    echo "<pre>";
    print_r($access_token);
    print_r($fb_success);
    echo "</pre>";
    die();
    */
    
    
    if(is_array($fb_success) && @$fb_success['access_token'] != "")
    {
       // $sql = "update users set access_token = 'access_token=".$fb_success['access_token']."' where id = '".$_SESSION['user_id']."'";
	    $sql = "update users set access_token = '".$fb_success['access_token']."' where id = '".$_SESSION['user_id']."'";
        mysqli_query($link,$sql);  
    }
    else{
        $_SESSION['message'] = '<div class="alert alert-danger">Error While Connected to Facebook API, Please verify your app credentials and try again</div>';
        ?>
        <script> window.location = 'profile.php'; </script>
        <?php
        die();
        exit; 
        
    }
    
    $_SESSION['message'] = '<div class="alert alert-success">Application Connected Successfully.</div>';
    ?>
    <script> window.location = 'profile.php'; </script>
    <?php
    die();
    exit;
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

header("location:".$login_url);
?>