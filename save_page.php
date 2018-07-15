<?php
@session_start();
require_once("twitteroauth-master/twitteroauth/twitteroauth.php"); //Path to twitteroauth library
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashe($k)] = $v;
                $process[] = &$process[$key][stripslashe($k)];
            } else {
                $process[$key][stripslashe($k)] = stripslashe($v);
            }
        }
    }
    unset($process);
function stripslashe($str){
if (get_magic_quotes_gpc()) 
$str=stripcslashes($str); 
return $str;
}
function getConnectionWithAccessToken_new($cons_key, $cons_secret, $oauth_token, $oauth_token_secret)
				   {
                      $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
                      return $connection;
                   }
error_reporting(1);
include("database.php");
///mysqli_query($con,"SET NAMES utf8");
$con = $link;
  $json=json_encode($_POST['json']);

$obj=json_decode($json,true);
///$_SESSION['obj']=json_encode($obj);
$lat=$obj['settings_0']['lat'];
$lng=$obj['settings_0']['lon'];
$red_limit=$_POST['redeem_limit'];
$title=mysqli_real_escape_string($con,$_POST['page_title']);
if($_POST['refresh_rate']==""){
    $refresh_rate=0;
}else{
    $refresh_rate=$_POST['refresh_rate'];
}

$redeem_page_id=$_POST['redeem_page_id'];
$redeem_once=$_POST['redeem_once'];
/////////////////////////////////////
  if($_POST['page_id']=="")
 { $json=mysqli_real_escape_string($con,$json);
    $sql="insert into pages(json,page_key,created_user,lat,lng,redeem_limit,page_title,refresh_rate,redeem_once,redeem_page_id,created_date,modified_date) values('$json','$_POST[page_key]','".$_SESSION['user_id']."','$lat','$lng','$red_limit','$title','$refresh_rate','$redeem_once','$redeem_page_id',Now(),Now())";
  $res=mysqli_query($con,$sql) or die(mysqli_error($con)."<hr>".$sql);
 $id=mysqli_insert_id($con);
 $short_url=get_short_url(getServerURL().'view_page.php?id='.$id,$_SESSION['user_id']);
 $sql_update="update pages set short_url='$short_url' where id='$id'";
 mysqli_query($con,$sql_update);
   if($res)
 echo '{"msg": "Page created","id": "'.$id.'"}';
  else
  echo '{"msg": "Error","id": ""}';
  $update_images="yes";
 }
  else if($_POST['page_id'] != "")
 {
     
     
     
 ///////////////get last modified time
 $sql_get_time="select modified_date,short_url from pages where id='$_POST[page_id]'";
 $res_time=mysqli_query($con,$sql_get_time) or die(mysqli_error($con));
 if(mysqli_num_rows($res_time)>0){
     $row_time=mysqli_fetch_assoc($res_time);
     $modified_time=$row_time['modified_date'];
      $now_stamp=time();
       $final_stamp=strtotime($modified_time." +7 days");
       if($now_stamp> $final_stamp)
     $update_images="yes";
     
     if($row_time['short_url']==""){
         $id=$_POST['page_id'];
         getServerURL().'view_page.php?id='.$id;
         $short_url=get_short_url(getServerURL().'view_page.php?id='.$id,$_SESSION['user_id']);
         $sql_update="update pages set short_url='$short_url' where id='$id'";
         mysqli_query($con,$sql_update);
     }
     
     
     
 } 
  $update_images="yes";
     
  $json=mysqli_real_escape_string($con,$json);
  $sql="update pages set json='$json',lat='$lat',lng='$lng',redeem_limit='$red_limit',page_title='$title',refresh_rate='$refresh_rate',redeem_page_id='$redeem_page_id',redeem_once='$redeem_once',modified_date=Now() where id='$_POST[page_id]'";

  $res=mysqli_query($con,$sql) or die(mysqli_error($con));
  if($res)
  echo '{"msg": "Updated","id": ""}';
else
echo '{"msg": "Error","id": ""}';

 }
 //////////////////////////////////////////////////////
 if(isset($update_images) && $update_images=="yes"){
$header=1;
$redeem=1;
$scarcity=1;
$fonts=1;
$button=1;
$loyalty=1;
$icons=1;
$scratch=1;
$images;
$db_images;
//////////////////get images from table
 $sql_del="select * from pages_data where page_key='$_POST[page_key]'";
 $res_del=mysqli_query($con,$sql_del);
 if(mysqli_num_rows($res_del)>0)   
{
    while($row_del=mysqli_fetch_assoc($res_del)){
    $db_images[$row_del['name']]=$row_del['type'];    
    }
}
////////////////////////get images in json/////////////
if(isset($obj)){
  foreach($obj as $key => $val)
{
   
    $li=substr($key,0,strpos($key,"_"));
  
    switch($li){
       
        case "header":{
                if(isset($obj[$key]['header_image_name']))
             $images[]=$obj[$key]['header_image_name'];
              else  if(isset($obj[$key]['header_video_name']))
             $images[]=$obj[$key]['header_video_name'].".mp4";
    
       $header++;
        };break;  
              case "settings":{
                if(isset($obj[$key]['header_image']))
             $images[]=$obj[$key]['header_image'];
    
        };break;
              case "redeem":{
                if(isset($obj[$key]['redeem_img_name']))
             $images[]=$obj[$key]['redeem_img_name']; 
              if(isset($obj[$key]['redeem_button_image']))
             $images[]=$obj[$key]['redeem_button_image'];    
       $redeem++;
        };break;
 case "cart":{
                if(isset($obj[$key]['cart_button_image']))
             $images[]=$obj[$key]['cart_button_image'];    
       $redeem++;
        };break;
              case "scarcity":{
                if(isset($obj[$key]['red_bg_image']))
             $images[]=$obj[$key]['red_bg_image'];    
       $scarcity++;
        };break;
         case "fonts":{
                if(isset($obj[$key]['font_bg_image']))
             $images[]=$obj[$key]['font_bg_image'];    
       $fonts++;
        };break;
   case "button":{
      if(isset($obj[$key]['simple_button_image']))
       $images[]=$obj[$key]['simple_button_image'];    
       $button++;
        };break;
         case "loyalty":{
      if(isset($obj[$key]['loyalty_image']))
       $images[]=$obj[$key]['loyalty_image'];    
       $loyalty++;
        };break;
               case "icons":{
      if(isset($obj[$key]['icons_img_name']))
       $images[]=$obj[$key]['icons_img_name'];    
       $icons++;
        };break;
                 case "scratch":{
      if(isset($obj[$key]['scratch_bg_image']))
       $images[]=$obj[$key]['scratch_bg_image']; 
        if(isset($obj[$key]['scratch_fg_image']))
       $images[]=$obj[$key]['scratch_fg_image']; 
         if(isset($obj[$key]['scratch_image']))
       $images[]=$obj[$key]['scratch_image'];   
       $scratch++;
        };break;
        case "twitter":{
               if(isset($obj[$key]['username'])){
                   if(!isset($obj[$key]['tweets']) || $obj[$key]['tweets']<1)
                   $tweets=5;
                   else
                   $tweets=$obj[$key]['tweets'];
				   $consumerkey = "yBd3eAocVYjyu1K63tdvJg";
                   $consumersecret ="MmLGi9xNHmg65kMm4TvwjOn3TJyIVFAEuEE3VCuhp0";
                   $accesstoken = "391977413-0CgFKbeW8MygNmtCXzhwpmUYybbg6jDVBdjnlgcI";
                   $accesstokensecret ="1NVXSPKyzeSFD013U3JdiWtWE9oM5fPacA4kV1nHHQ";
				   
				   
				   
				   $connection = getConnectionWithAccessToken_new($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
                   $jsont = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$obj[$key]['username']."&count=".$tweets);
                   $jsont = json_encode($jsont);
				   if(json_decode($jsont))
                   {
                     //  $jsont=str_replace("'","",$jsont);
                     $jsont=mysqli_real_escape_string($con,$jsont);
                    ///////////////////check username data in db
                    $username=$obj[$key]['username'];
                    $sql_t="select * from twitter_data where user_name='$username' and page_id = '".$id."'";
					$res_t=mysqli_query($con,$sql_t) or die(mysqli_error($con));
					if(mysqli_num_rows($res_t)>0){
                      $twitter_update="update twitter_data set json='$jsont',time=Now() where user_name='$username'"; 
                      mysqli_query($con,$twitter_update) or die(mysqli_error($con));
                      $object="update ------object";
                    }
                    else
                    {
                       
                    $ins_t="insert into twitter_data (page_id,user_name,json,time) values('$id','$username','$jsont',Now())";
                    mysqli_query($con,$ins_t) or die(mysqli_error($con));    
                    }
                    $object="insert object----";   
                   } 
               } 
        };break;
    }
}
}
if(isset($db_images)){
 foreach($db_images as $key_i => $val_i){
 if(!in_array($key_i,$images))
 {
     ///////////////////images to delete 
     $sqld="delete from pages_data where name='$key_i'";
     if(mysqli_query($con,$sqld)){
         if($val_i == "image"){
             if(file_exists("uploaded_images/$key_i"))
             unlink("uploaded_images/$key_i");
               if(file_exists("uploaded_images/thumbs/$key_i"))
             unlink("uploaded_images/thumbs/$key_i");
         }
            else if($val_i == "video"){
             if(file_exists("uploaded_videos/$key_i"))
             unlink("uploaded_videos/$key_i");
             
         }
     }
 }
 else if(in_array($key_i,$images)){
  /////////////update images ids
  $sql_up="update pages_data set status='used' where name='$key_i'";
  mysqli_query($con,$sql_up);   
 }
 }
   
}
 }
 function DBin($string)
{
    $a = html_entity_decode($string);
    return trim(htmlspecialchars($a,ENT_QUOTES));
}
function DBoutf($string)
{
$string = stripslashes(trim($string));
    return html_entity_decode($string,ENT_QUOTES);
}
function DBout($string)
{
  //  $string = stripslashes(trim($string));
    return html_entity_decode($string,ENT_QUOTES);
}
function get_short_url($url,$userID){
 /*   $header=ARRAY("Content-Type: application/json");

$body= '{"longUrl": "'.$url.'"}';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/urlshortener/v1/url" );
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//curl_setopt($ch, CURLOPT_PUT, true );
curl_setopt($ch, CURLOPT_POST, true );
//curl_setopt($ch, CURLOPT_HTTPGET, true );
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
$filecontents=curl_exec($ch);

$json_output = json_decode($filecontents);
//print_r($json_output);
 return $json_output->id;
 */
    $bitly="https://api-ssl.bitly.com/v3/shorten?access_token=";
    
    global $link;
    
    $sql = "select * from application_settings where user_id='".$userID."'";
    //echo "<br>";
	$res = mysqli_query($link,$sql);
    $appSettings = mysqli_fetch_assoc($res);
    
  $token=$appSettings['bitly_token'];
  
  
  $bitly.$token."&longUrl=".urlencode($url);
  //echo "<br>";
  
$url=read_file($bitly.$token."&longUrl=".urlencode($url));
//echo $url;
//echo "<br>";
 
$url=json_decode($url,true);


return $url['data']['url'];
}
function read_file($url){
    set_time_limit(0);
$ch = curl_init(str_replace(" ","%20",$url));//Here is the file we are downloading, replace spaces with %20
curl_setopt($ch, CURLOPT_TIMEOUT, 0);
//curl_setopt($ch, CURLOPT_FILE, $fp); // here it sais to curl to just save it
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);//get curl response
curl_close($ch);
return $data;

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
return $applicationPath;
}



?>
