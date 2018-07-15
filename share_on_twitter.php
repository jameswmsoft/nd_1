<?php
@session_start();
include_once("database.php");
include_once("functions.php");
require_once('twitter/TwitterAPIExchange.php');
$userInfo = getUserInfo($_SESSION['user_id']);

$twitter = new TwitterAPIExchange(array(
    'oauth_access_token' => $userInfo['tw_access_token'],
    'oauth_access_token_secret' => $userInfo['tw_access_token_secret'],
    'consumer_key' => $userInfo['tw_consumer_key'],
    'consumer_secret' => $userInfo['tw_consumer_secret']
));

$url = 'https://api.twitter.com/1.1/statuses/update.json';
$requestMethod = 'POST';
$postData = array('status' => $_REQUEST['post_message']);
$json_res = $twitter->buildOauth($url, $requestMethod)
             ->setPostfields($postData)
             ->performRequest();
$response = json_decode($json_res,true);

if(is_array($response) && isset($response['id'])){
    
    $post_message = mysqli_real_escape_string($link,$_REQUEST['post_message']);
    $sql_camp = "update campaigns set post_message = '".$post_message."' where id = '".$_REQUEST['camp_id']."'";
    mysqli_query($link,$sql_camp);
    echo '<div class="alert alert-success">Posted On Twitter Successfully.</div>';
}else{
    echo '<div class="alert alert-danger">Error while posting on Twitter.</div>';
}

?>