<?php  

header('Content-type: text/html; charset=utf-8'); 

session_start();

//error_reporting(0);

include_once("database.php");
$con = $link;

require_once("twitteroauth-master/twitteroauth/twitteroauth.php"); //Path to twitteroauth library

$page_id=$_GET['id'];

function getConnectionWithAccessToken1($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {

  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);

  return $connection;

}



if(strlen($page_id)>5)

{

$len=strlen($page_id)-10;

$page_id=substr($page_id,0,$len);

}

if($page_id == "")

{

 echo "NO page Found";

 die();   

}

if($page_id!= "") {

	 	$sql_main_cam = "select * from pages where id='".$page_id."'";

	$exe_main_cam = mysqli_query($con,$sql_main_cam) or die(mysqli_error($con));

     if(mysqli_num_rows($exe_main_cam) == 0)

    {

       echo "No Page Found"; 

        die();

    }

	$row= mysqli_fetch_assoc($exe_main_cam);

  ///////////////set time zone

  $userID = $row['created_user'];

/*
$TimeZoneValue=get_timezone($userID);

$TimeZoneValue = preg_replace('/\-/', '/', $TimeZoneValue, 1);

if($TimeZoneValue != "none")
*/

//date_default_timezone_set($TimeZoneValue);  //US CST

   $json=$row['json'];

   $obj=json_decode($json,true); 

   if($obj === NULL){

   $str=str_replace('"{','{',parse(DBoutf($row['json']))); 

$json=str_replace('}"','}',$str);

$obj=json_decode($json,true);

   } 

   /* $modified_date=strtotime($row['modified_date']);

 $update_date=strtotime("2013-03-07 01:11:43");

 if($modified_date<$update_date){

 $str=str_replace('"{','{',parse(DBoutf($row['json']))); 

$json=str_replace('}"','}',$str);

$obj=json_decode($json,true);

 }

 else{

  $obj=json_decode($json,true);   

 }

 */

//echo "Json error===".json_last_error();

$page_key=$row['page_key'];

$page_url=$row['short_url'];

$refresh_rate=$row['refresh_rate'];

$user_id=$row['created_user'];

$ip=$_SERVER['REMOTE_ADDR'];

/*include_once('ip2locationlite.class.php');

 $ipLite = new ip2location_lite;

 $ipLite->setKey('e3adfd70a4d2b97f5e03d32cdaf01a3be5fdd9114e45d486e26b8ba4102368bc');

 $get_location = $ipLite->getCity($ip);*/
 $location = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));

$user_city=$location['geoplugin_city'];

      if(!isset($_COOKIE['visitor_id']))

{  

$visitor_id=session_id();

$hits=1;

$coupon="yes";

 //   setcookie("page_id_$page_id",$page_id,time()+2222222000);

$rrr = time()+2222222000;

$eee = settype($rrr, "integer");

    setcookie("visitor_id",$visitor_id,$eee);

   // setcookie("hits_$page_id",$hits,time()+2222222000);

   // setcookie("redeem_$page_id",$redeem,time()+2222222000);

    $sql_vis="insert into visitors(visitor_id,page_id,ip,city,hits,time) values('$visitor_id','$page_id','$ip','$user_city','$hits',Now())";

}

else if(isset($_COOKIE['visitor_id'])){

  

     ///////////////////////if visitor is registered get vissitor data

  

                 ///////

                 $visitor_id=$_COOKIE["visitor_id"];

                 $sql="select * from visitors where visitor_id='$visitor_id' and page_id='$page_id'";

                 $res=mysqli_query($con,$sql);

                 if(mysqli_num_rows($res)==0){

                 $sql_vis="insert into visitors(visitor_id,page_id,ip,city,hits,time) values('$visitor_id','$page_id','$ip','$user_city','1',Now())";    

                 }else{

                     $sql_vis="update visitors set hits=hits+1,time=Now() where visitor_id='$visitor_id' and page_id='$page_id'";

                 }

  /*               $sql_vis="select * from visitors where visitor_id='$visitor_id' and page_id='$page_id'";

                 $res_vis=mysqli_query($con,$sql_vis);

                 if(mysqli_num_rows($res_vis)>0){

                     //////////////////visitor has already visited that page(how many time he has redeemed page)

                     $sql_red="select * from redeems where visitor_id='$visitor_id' and page_id='$page_id'";

                     $res_red=mysqli_query($con,$sql_red);

                     if(mysqli_num_rows($res_red)>0){

                        

                       //////////////////////visitor has already redeemed(get last redeem time)

                         $row_red=mysqli_fetch_assoc($res_red);

                         $now_stamp=strtotime($row_red['time']);

                         $final_stamp=strtotime($row_red['time']."+ $row[refresh_rate] days");

                         

                         if($now_stamp > $final_stamp){

                         /////////////user can get new coupon    

                         $coupon="yes";

                         }

                         else{

                             ////////////user can not get new coupon

                            $coupon="no"; 

                         }

                     }

                 }*/

        

  

}

if(isset($sql_vis))

mysqli_query($con,$sql_vis) or die(mysqli_error($con));

}



  function stamp_date($date)

{ if($date) 

  {

    $date_time=explode(" ",$date);  

      $date=explode("/",$date_time[0]);

  $time=explode(":",$date_time[1]);

//echo "strtotime ".strtotime($date[2]."-".$date[1]."-".$date[0]." ".$time[0].":".$time[1].":"."00")."<br />"; 

 return strtotime($date[2]."-".$date[1]."-".$date[0]." ".$time[0].":".$time[1].":"."00");

  }

else

return "";

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



//$url = $applicationPath.'uploads/';



return $applicationPath;

}

function DBoutf($string)

{

$string = stripslashes(trim($string));

    return html_entity_decode($string,ENT_QUOTES);

}

    function parse($text) {

    // Damn pesky carriage returns...

    $text = str_replace("\r\n", "\n", $text);

    $text = str_replace("\r", "\n", $text);



    // JSON requires new line characters be escaped

    $text = str_replace("\n", " ", $text);

    return $text;

}

function get_timezone($userID){

global $con;
      $TimeZoneValue="";

    $sql = "select time_zone_coupon from client_information where id='".$userID."'";

    $res = mysqli_query($con,$sql);

    $row = mysqli_fetch_assoc($res);

    if($row['time_zone_coupon'] != '')

    {

         $zoneVal = $row['time_zone_coupon'];

        $sql1="select time_zone from time_zones where id='$zoneVal'";

       $res1=mysqli_query($con,$sql1);

       $row1=mysqli_fetch_assoc($res1);

       $TimeZoneValue = $row1['time_zone'];



      }

      if($TimeZoneValue != "")

      return $TimeZoneValue;

      else return 'none';

}

?>



<!DOCTYPE html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--<meta name="viewport" content="width=device-with, user-scalable=yes">-->

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<meta name="HandheldFriendly" content="true"/>

<meta name="MobileOptimized" content="width" />

<!----link rel="image_src" href="/images/fb_icon.png" /---->

<!---meta property="og:title" content="open graph title" />

<meta property="og:type" content="open graph type" />

<meta property="og:url" content="open graph content url" /------>

<meta property="og:image" content="images/fb_icon.png" />

<meta property="fb:admins" content="100003230135592" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

   <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

 <script src="js/jquery-1.8.js"></script>

<!--<script src='http://connect.facebook.net/en_US/all.js'></script>--> 


  <script src="js/video.min.js"></script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCc9MOpE2wArUTUcA67RHFfpI-BfIHrDCs&sensor=false" ></script>


  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



    <script type="text/javascript">

 /////////////////////////////////////google map

        var directionsDisplay;

   var  directionsService = new google.maps.DirectionsService();

   

      var map;  

       function initialize(div,lat,lon,infowin,zoom) {

           directionsDisplay = new google.maps.DirectionsRenderer();

          if(zoom == "" || zoom ==0){

           zoom=8;   

          }

        var mapOptions = {

          center: new google.maps.LatLng(lat,lon),

          zoom: zoom,

          mapTypeId: google.maps.MapTypeId.ROADMAP

        };

          

      var infowindow = new google.maps.InfoWindow({ content: infowin});

        var map = new google.maps.Map(document.getElementById('map_canvas_'+div),

            mapOptions);

              directionsDisplay.setPanel(document.getElementById("turn_panel_"+div));

            directionsDisplay.setMap(map);

            var marker = new google.maps.Marker({

          position: map.getCenter(),

          map: map,

          title: 'Click to zoom'

        });

       

        google.maps.event.addListener(marker, 'click', function() {

     infowindow.open(map,marker);

  });

      }

      function get_direction(obj){

$(obj).next("span").html("<img src='images/load9.gif'>");

 var id=$(obj).attr('id').split("_")[1];

 $("#map_canvas_"+id).parent("div").attr({'id': 'getdirmap_'+id});

 $(".pc_widget").not("#getdirmap_"+id).hide(1000);

 $("#get_address_"+id).show('slow');

 

navigator.geolocation.getCurrentPosition(GetLocation,function(error){

////error.PERMISSION_DENIED error.POSITION_UNAVAILABLE   case error.TIMEOUT:

      $(obj).next("span").html("");

      

});

function GetLocation(location) {

    lat=location.coords.latitude;

    lon=location.coords.longitude;

  //  console.log("Lat==="+lat);

  //  console.log("Lon==="+lon);

$(obj).next("span").html("");

 //   console.log(location.coords.accuracy);

    get_address(obj,lat,lon);

$("#closegetdir_"+id).show();

}

      }

	  function close_getdir(obj){

	  $(".pc_widget").show(500);

	  var id=$(obj).attr('id').split("_")[1];

	  $("#turn_panel_"+id).hide(500);

       $("#get_address_"+id).hide('slow');

	  $(obj).hide();

	  }

      function get_address(obj,lat,lon){

       var geocoder = new google.maps.Geocoder();

   var latLng = new google.maps.LatLng(lat,lon);

   if (geocoder) {

      geocoder.geocode({ 'latLng': latLng}, function (results, status) {

         if (status == google.maps.GeocoderStatus.OK) {

       //   $(obj).next("span").append(results[0].formatted_address);

         var id=$(obj).attr('id').split("_")[1];

         $("input[name=location_"+id+"]").val(results[0].formatted_address);

         }

         else {

        $(obj).next("span").append('error');

         }

      });

   }

   }  

  

   function show_route(obj,address){

        var id=$(obj).attr('id').split("_")[1];

   $("#get_address_"+id).hide();

   $("#turn_panel_"+id).show();

   $("#getdir_"+id).next("span").html("");

   $("#closegetdir_"+id).show();

        var start=$("input[name=location_"+id+"]").val();

       // console.log("Start==="+start+"----end==="+address);

               var request = {

            origin: start,

            destination: address,

            travelMode: google.maps.DirectionsTravelMode.DRIVING

        };

        directionsService.route(request, function(response, status) {

          if (status == google.maps.DirectionsStatus.OK) {

            directionsDisplay.setDirections(response);

          }

        });

   }

///////////////////////////////vedio player//////////////////

    _V_.options.flash.swf = "images/video-js.swf";

  </script>

  



<script>

/////////////////////////////redeeem

  var redeem_limit='0';

   var visitor_id=getCookie('visitor_id');

     var page_id='<?php echo $page_id?>';

     var email_temp='<table border=0  id="suscribe_table" style="min-width: 290px"> <tr><td>Enter&nbsp;email</td><td><input type="text" name="email" class="text_feild_cam" style="width: 90%"></td></tr> <tr><td colspan="2"><input type="button" value="Subscribe" name="suscribe" ><span id="result"></span><input type="button" value="Cancel" onclick="close_lightbox()" style="float: right; margin-right: 5px;"></td></tr></table>';

       var name_email_temp='<table border=0  id="suscribe_table" style="min-width: 290px"><tr><td>Enter&nbsp;name</td><td><input type="text" name="name" class="text_feild_cam" style="width: 90%;" ></td></tr><tr><td>Enter&nbsp;email</td><td><input type="text" name="email" class="text_feild_cam" style="width: 90%" ></td></tr> <tr><td colspan="2"><input type="button" value="Subscribe" name="suscribe" ><span id="result"></span><input type="button" value="Cancel" onclick="close_lightbox()" style="float: right; margin-right: 5px;"></td></tr></table>';

           var sms_temp='<table border=0  id="suscribe_table" style="min-width: 290px"><tr><td>Enter&nbsp;name</td><td><input type="text" name="name" class="text_feild_cam" style="width: 90%" ></td></tr><tr><td>Enter&nbsp;Phone</td><td><input type="text" title="Please Add your phone number with country code like, +183230XXXXX (USA), +4483230XXXXX (UK)." alt="Please Add your phone number with country code like, +183230XXXXX (USA), +4483230XXXXX (UK)." name="phone" class="text_feild_cam" style="width: 90%" ></td></tr> <tr><td colspan="2" style="color: #FF0000;font-size: x-small;padding-left: 10px;">Phone format +183230xxxxx (USA), +4483230xxxxx (UK).</td></tr><tr><td colspan="2"><input type="button" value="Subscribe" name="suscribe" ><span id="result"></span><input type="button" value="Cancel" onclick="close_lightbox()" style="float: right; margin-right: 5px;"></td></tr></table>';

  

       var user_email="";

       var user_name="";

       

$(document).ready(function(){

  $("#lightbox").css({height: $(document).height()});

 $(".redeem_link").click(function(e){

     e.preventDefault();

  var id=this.id;



   var url=$("#"+id).attr('href');

  

   var coupon='<?php echo $coupon;?>';

   var redeem_obj=$.parseJSON($(this).next("span").text());

   console.log(redeem_obj);



    // var promt="Are you sure to redeem?";

        var promt=redeem_obj.redeem_prompt;

      var confirm_temp='<table border=0  id="suscribe_table" style="min-width: 290px"><tr><td align="center">'+promt+'</td></tr><tr><td align="center"><input type="button" value="OK" name="ok" style="padding: 0px 20px;" ><span id="result"></span><input type="button" name="cencel" value="Cancel" onclick="close_lightbox()" ></td></tr></table>';

    show_lightbox();

     $("#get_user_data").html(confirm_temp);

    var promt=redeem_obj.redeem_prompt;

  $("input[name=ok]").click(function() 

  {   

         if(redeem_obj.force_optin_check == "true")

  {

     // alert(redeem_obj['force_optin']['force_optin_email_case']);

     $("#lightbox").css({display: 'block',height: $(document).height(),width: $(document).width()});

     var margin_left=(($(window).width()-$("#get_user_data").outerWidth())/2)-5;

     var margin_top=(($(window).height()-$("#get_user_data").outerHeight())/2)-50;

     if(margin_left>0)

    $("#get_user_data").css({'margin-left': margin_left});

        if(margin_top>0)

    $("#get_user_data").css({'margin-top': margin_top});

     $("#get_user_data").show();

  // console.log(margin_left+"------top="+margin_top);

   switch(redeem_obj.force_optin_case){

       case "email":

       {  

           switch(redeem_obj.force_optin_email_case){

               case "aweber":

                  $("#get_user_data").html(name_email_temp);  

               $("input[name=suscribe]").click(function(){

       var name=$("#get_user_data input[name=name]").val();

       var email=$("#get_user_data input[name=email]").val();

       if(email != "" && name!= "")

  { 

   // $("#lightbox").hide();

  // $("#get_user_data").hide(); 

  $("#result").html("<img src='load9.gif'>");

    var form_action={};

   form_action['widget_no']=redeem_obj.widget_no; 

   form_action['name']=name; 

   form_action['email']=email; 

   //console.log(form_action);

   get_coupon(form_action,'aweber');

  }  

  else{

   $("#result").html('Provide name and email');   

  }

});     

        break;
		////////////////////////////////
		case "sendreach":

                  $("#get_user_data").html(name_email_temp);  

               $("input[name=suscribe]").click(function(){

       var name=$("#get_user_data input[name=name]").val();

       var email=$("#get_user_data input[name=email]").val();

       if(email != "" && name!= "")

  { 

   // $("#lightbox").hide();

  // $("#get_user_data").hide(); 

  $("#result").html("<img src='load9.gif'>");

    var form_action={};

   form_action['widget_no']=redeem_obj.widget_no; 

   form_action['name']=name; 

   form_action['email']=email; 

   //console.log(form_action);

   get_coupon(form_action,'sendreach');

  }  

  else{

   $("#result").html('Provide name and email');   

  }

});     

        break;


        ////////////////////////////////

        case "mailchimp":

      $("#get_user_data").html(email_temp);  

    $("input[name=suscribe]").click(function(){

       var email=$("#get_user_data input[name=email]").val();

       if(email != "")

  { 

  //  $("#lightbox").hide();

 //  $("#get_user_data").hide(); 

   $("#result").html("<img src='load9.gif'>");

       var form_action={};

   form_action['widget_no']=redeem_obj.widget_no; 

   form_action['email']=email; 

   //console.log(form_action);

   get_coupon(form_action,'mailchimp');

  }

    else{

   $("#result").html('Provide  email');   

  }  

});     

        break;
		 ///////////////////////////////////

		

 case "getresponse":

 $("#get_user_data").html(name_email_temp); 



 $("input[name=suscribe]").click(function(){

	 

       var name=$("#get_user_data input[name=name]").val();

       var email=$("#get_user_data input[name=email]").val();

	   if(email != "" && name!= "")

  { 

   

 //   $("#lightbox").hide();

  // $("#get_user_data").hide(); 

    $("#result").html("<img src='load9.gif'>");

        var form_action={};

   form_action['widget_no']=redeem_obj.widget_no; 

   form_action['name']=name; 

   form_action['email']=email;

   console.log(form_action);

   

   get_coupon(form_action,'getresponse');

  }

    else{

   $("#result").html('Provide name and email');   

  }  

});     

        break;

        


        

        ///////////////////////////////////

 case "icontact":

 $("#get_user_data").html(name_email_temp); 



 $("input[name=suscribe]").click(function(){

       var name=$("#get_user_data input[name=name]").val();

       var email=$("#get_user_data input[name=email]").val();

       if(email != "" && name!= "")

  { 

 //   $("#lightbox").hide();

  // $("#get_user_data").hide(); 

    $("#result").html("<img src='load9.gif'>");

        var form_action={};

   form_action['widget_no']=redeem_obj.widget_no; 

   form_action['name']=name; 

   form_action['email']=email; 

   //console.log(form_action);

   get_coupon(form_action,'icontact');

  }

    else{

   $("#result").html('Provide name and email');   

  }  

});     

        break;

        

                

        ///////////////////////////////////

 case "const_contact":

 $("#get_user_data").html(name_email_temp);  

 $("input[name=suscribe]").click(function(){

       var name=$("#get_user_data input[name=name]").val();

       var email=$("#get_user_data input[name=email]").val();

       if(email != "" && name!= "")

  { 

  //  $("#lightbox").hide();

  // $("#get_user_data").hide(); 

    $("#result").html("<img src='load9.gif'>");

        var form_action={};

   form_action['widget_no']=redeem_obj.widget_no; 

   form_action['name']=name; 

   form_action['email']=email; 

   //console.log(form_action);

   get_coupon(form_action,'const_contact');

  }

    else{

   $("#result").html('Provide name and email');   

  }  

});     

        break;



               

           }

       }

       break;

  case "sms":{

      $("#get_user_data").html(sms_temp);  

 $("input[name=suscribe]").click(function(){

       var name=$("#get_user_data input[name=name]").val();

       var phone=$("#get_user_data input[name=phone]").val();

       if(phone != "")

  { 

   // $("#lightbox").hide();

 //  $("#get_user_data").hide(); 

   $("#result").html("<img src='load9.gif'>");

        var form_action={};

   form_action['widget_no']=redeem_obj.widget_no; 

   form_action['name']=name; 

   form_action['phone']=phone; 

   //console.log(form_action);

   get_coupon(form_action,'sms');

  } 

    else{

   $("#result").html('Provide Phone no');   

  } 

});  

  } break;    

       

   } 

   

  }

  else

  {

      $("#get_user_data").hide();

    get_coupon('none','none');   

  }

   

 });

//alert(id+"--------"+url);

 });

 $(".share_link").click(function(e){

     e.preventDefault();

      var url=$(this).attr('href');

      var icon_id=this.id.split("_");

      icon=icon_id[0];

      var after_share_url=icon_id[2];

      $.post("handle_ajax.php",{cmd: 'note_stats',icon: icon,visitor_id: visitor_id,page_id:page_id});

  // //console.log(url); 

  window.location.href="<?php echo getServerURL()?>view_page.php?id="+after_share_url;



window.open(url, '_blank');

  window.focus();



///document.location.href=url; 

 })   

})

function get_coupon(suscribe ,casee){

    ////coupon_redeemed

        $("#light_box").css({width: $(window).width(), height: $(window).height(),display: 'block'});    

      $.post("handle_ajax.php",{cmd: 'coupon_redeemed',page_id: page_id,visitor_id: visitor_id,suscribe: suscribe,casee: casee },function(res){

        $("#light_box").css({'display' : 'none'});    

  var obj=$.parseJSON(res);

  if(obj){

      if(obj.msg =="updated"){

         // setCookie('redeem_'+page_id,'yes','121212154');

         var url=obj.url;

         window.location.href=url;

     }

     else

     {

         if( $("#lightbox").is(":visible"))

      {   $("#lightbox").hide();

  $("#get_user_data").hide();

      }    alert('Already redeemed!');    

     } }

     else

    {

      if( $("#lightbox").is(":visible"))

      {   $("#lightbox").hide();

  $("#get_user_data").hide();

   

      }

      alert('Already redeemed!');

    }    

  });

}

function fb_share(fb_app_id,obj){

    ///////////////////////////////////////////

       var url=$(obj).attr('href');

      var icon_id=$(obj).attr('id').split("_");

      icon=icon_id[0];

      var after_share_url=icon_id[2];

        $.post("handle_ajax.php",{cmd: 'note_stats',icon: icon,visitor_id: visitor_id,page_id:page_id});

    if(fb_app_id.length>4){

        window.location.href=url;

    }else{

  window.location.href="<?php echo getServerURL()?>view_page.php?id="+after_share_url;

window.open(url, '_blank');

  window.focus();   

  }

}

function note_stats(icon){

//alert(icon);

$.post("handle_ajax.php",{cmd: 'note_stats',icon: icon,visitor_id: visitor_id,page_id:page_id});

//var link=$

}



function getCookie(c_name)

{

var i,x,y,ARRcookies=document.cookie.split(";");

for (i=0;i<ARRcookies.length;i++)

  {

  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));

  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);

  x=x.replace(/^\s+|\s+$/g,"");

  if (x==c_name)

    {

    return unescape(y);

    }

  }

}



function setCookie(c_name,value,exdays)

{

var exdate=new Date();

exdate.setDate(exdate.getDate() + exdays);

var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());

document.cookie=c_name + "=" + c_value;

}

      function loadjs(url) {

   var head = document.getElementsByTagName('head')[0],

   link = document.createElement('script');

   link.type = 'text/javascript';

   link.src = url;

   head.appendChild(link);

   return link;

 }

              function loadcss(url) {

   var head = document.getElementsByTagName('head')[0],

   link = document.createElement('link');

   link.type = 'text/css';

   link.rel = 'stylesheet';

   link.href = url;

   head.appendChild(link);

   return link;

 }

 function loyalty_check(obj){

     if(typeof $(obj).next("img").attr('src') == 'undefined')

   {  $(obj).after("<img src='images/load9.gif'>");

var keyword=$(obj).prev("input").val();

 $.post("handle_ajax.php",{cmd: "check_loyalty",visitor_id: visitor_id,page_id:page_id,keyword: keyword},function(data){

 $(obj).next("img").remove();

 var loyalty_obj=$(obj).next("div").text(); 

 var res_arr=$.parseJSON(data);

 //console.log(data);    

 //console.log(loyalty_obj);

var loyalty_arr=$.parseJSON(loyalty_obj);

switch(res_arr.msg) {

    case "invalid":

 alert(loyalty_arr.invalid_prompt);break; 

  case "looser":

 alert(loyalty_arr.looser_prompt.replace('%togo%',res_arr.codes_required-res_arr.codes_entered));break;

  case "win":

window.location.href='view_page.php?id='+loyalty_arr.reward_page; 

} 

 });

   }

   else{

       var test=$(obj).next("img").attr('src');

       //console.log(test);

   }

 }

  function show_lightbox(){

          $("#lightbox").show();

     var margin_left=(($(window).width()-$("#get_user_data").outerWidth())/2)-5;

     var margin_top=(($(window).height()-$("#get_user_data").outerHeight())/2)-50;

     if(margin_left>0)

    $("#get_user_data").css({'margin-left': margin_left});

        if(margin_top>0)

    $("#get_user_data").css({'margin-top': margin_top});

     $("#get_user_data").show();

 }

function close_lightbox(){

$("#lightbox").hide();    

$("#get_user_data").hide();

}



    </script>

<title><?php echo $obj['settings_0']['page_title']?></title>

<style>

body{

margin: 0px;

padding: 0px;

}

#main {

    max-width:1000px;

    margin:0 auto;

    padding:10px;

 <?php if(isset($obj['settings_0']['border_thickness']) && strlen(trim($obj['settings_0']['border_thickness']))>0)

       $border_thickness= $obj['settings_0']['border_thickness'];

     else

    $border_thickness="7";

     if(isset($obj['settings_0']['border_color']) && strlen(trim($obj['settings_0']['border_color']))>2)

         $border_color= $obj['settings_0']['border_color'];

     else

    $border_color="ccc";

   echo 'border: '.$border_thickness.'px '.$obj['settings_0']['border_style'].' '.$border_color.";";   

    ?>

    <?php if($obj['settings_0']['header_type'] == "image")

    { echo 'background-image: url("uploaded_images/'.$obj['settings_0']['header_image'].'");';

    ?>

      background-position: center center;

    background-repeat: no-repeat;

    background-size: cover;

    <?php

    }

     else if($obj['settings_0']['header_type'] == "color")

     echo 'background-color: '.$obj['settings_0']['header_color'].';';

     ?>

}

.pc_widget

{

  overflow: hidden;

    background:#FFF;

    box-shadow:0 0 3px 1px #666;

    border-radius:5px;

    word-wrap:normal;

    margin: 7px 0px 7px 0px;

}

.header

{

width: 100%;

height: 300px;

}

.content

{

padding: 0px;    

}

.redeem

{

 border: 2px #666 solid;

 text-align: center;

}

.cart

{

  border: 2px #666 solid;

 padding: 15px; 

background-color: #e2e2e2;

 display:block;

 text-decoration: none;

 color: #000;

}

.cart:hover{

    

}

.icons{

    padding: 15px;

}

.icons img{

    width: 40px;

    height: 40px;

    margin: 0px 4px;

}

.button

{

    border: 2px #666 solid;

 padding: 15px; 

 background-color: #ccc; 

 display:block;

 text-decoration: none;

 color: #000;

}

.button:hover{

 background-color: #666;     

}

.call{

padding: 15px; 

font-size: 20px;   

}

.call a{

text-decoration: none;

color: #000;   

}

.call{

 padding: 15px; 

 display:block;

 text-decoration: none;

 color: #000;

}

.twitter a{

 text-decoration: none; 

 color: inherit;  

}

.twitter a:hover{

 text-decoration: underline;   

}

.simple_button

{

    width:100px; 

    height:25px; 

    background-color:#069;

    border-radius:5px;

    color:#fff;

}

.suscribe{

        border:5px solid #999;

    border-radius:10px;

    border-radius:10px;

    font-family: 'Arial,Helvetica';

    background: #ffffff; /* Old browsers */

background: -moz-linear-gradient(top, #ffffff 0%, #e5e5e5 100%); /* FF3.6+ */

background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#e5e5e5)); /* Chrome,Safari4+ */

background: -webkit-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* Chrome10+,Safari5.1+ */

background: -o-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* Opera 11.10+ */

background: -ms-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* IE10+ */

background: linear-gradient(to bottom, #ffffff 0%,#e5e5e5 100%); /* W3C */

filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */   

}

.text_feild_cam{

    width:220px;

    height:25px;

    border:1px solid #CCC;

    border-radius:3px;

    background:#F7F7F7;

    padding-left:3px;

}

.text_feild_cam:focus{

    background:#FFF;

}

#suscribe_table{

    margin: 5px auto; 

    width: 80%; 

    height: 120px;

    margin-top: 20px;

}

#result{

    font-size: 14px;

    color: red;

    margin-left: 20px;

}

.fixed{

    position: fixed;

}

canvas { vertical-align:bottom; }

.scratchCanvas
{
	background-size: contain;
	width:100%;
}

</style>

<?php 

if(isset($obj['settings_0']['js_code']) && $obj['settings_0']['js_code'] != "" ){

    echo html_entity_decode($obj['settings_0']['js_code'],ENT_QUOTES);

}

?>



</head>

<body>

<?php

///print_r($obj['settings_0']);

     $fb_check="11";

 while(@$fb_i<=$fb_check)

{

    if(isset($obj['facebook_'.@$fb_i]['posts']) && $obj['facebook_'.@$fb_i]['check']=="images/enable.png")

{

?>



<?php

@$fb_i=$fb_check;

}

@$fb_i++;

}

?>

<div  id="get_user_data" class="suscribe" style=" position: fixed; z-index: 9999;  display: none; max-width: 500px;"><table border=0  id="suscribe_table" style="width: 300px"> <tr><td>Enter&nbsp;email</td><td><input type="text" name="email" class="text_feild_cam" style="width: 90%" ></td></tr> <tr><td colspan="2"><input type="button" value="Subscribe" name="suscribe" ><span id="result"></span></td></tr></table>

</div><div id="lightbox" style="background-color: #000; opacity: .6; top: 0px; left: 0px; display: none; position : absolute; width: 100%; height: 100%; z-index: 999;"></div>

<div id="main">

<?php

     $header=1;

     $content=1;

     $redeem=1;

     $button=1;

     $icons=1;

     $call=1;

     $timer=1;

     $cart=1;

     $scarcity=1;

     $facebook=1;
     
     $webform=1;

     $twitter=1;

     $map=1;

     $fonts=1;

     $loyalty=1;

     $scratch=1;

                foreach($obj as $key => $val)

{

   

    $li=substr($key,0,strpos($key,"_"));

  if(@$val['check'] == "images/enable.png")

  {  switch($li){

        case "fonts" :{

           //print_r($obj[$key]); 

            if(@$obj[$key]['font_bg_type'] == "font_color")

            $font_bg="background-color: ".$obj[$key]['font_bg_color'].";"; 

            else if(@$obj[$key]['font_bg_type'] == "font_image")

            $font_bg="background-image: url('uploaded_images/".$obj[$key]['font_bg_image']."');";

        if($obj[$key]['google_font'] != ""){

        ?>

        <script>

        loadcss("http://fonts.googleapis.com/css?family=<?php echo $obj[$key]['google_font']?>");

        $(document).ready(function(){

             $("#fonts_<?php echo $fonts?>").css({'font-family': '<?php echo $obj[$key]['google_font'] ?>'});

        });

        </script>

        <?php }?>

        <div class="pc_widget" style=" <?php echo $font_bg?> font-size: <?php echo $obj[$key]['font_size']."px"?>; color: <?php echo $obj[$key]['font_color']?>; padding: 15px; text-align: <?php echo $obj[$key]['text_alignment']?>; font-weight: bold; <?php if($obj[$key]['no_border'] == "true") echo "box-shadow: none;"; if($obj[$key]['transparent_bg'] == "true") echo "background: transparent;";?>" id="fonts_<?php echo $fonts; ?>">

        <?php echo $obj[$key]['font_text']?>

        </div>

        <?php

        $fonts++;

        };break;

        case "header":{

     //  print_r($obj[$key]);         

          ?>

                  <div class="pc_widget" style="<?php if($obj[$key]['no_border'] == "true") echo "box-shadow: none;"; if($obj[$key]['transparent_bg'] == "true") echo "background: transparent;";?>">

             <?php 

           

             if($obj[$key]['header_type']=="image")

             {

             if(isset($obj[$key]['header_image_name']))

        $image_src='uploaded_images/'.$obj[$key]['header_image_name']."?".time();

        else if(isset($obj[$key]['header_image_url']))

        $image_src=$obj[$key]['header_image_url'];

        ?><img  src="<?php echo $image_src?>" style="width: 100%; margin-bottom: -5px;"><?php

             }

              else  if($obj[$key]['header_type']=="video")

             {

                 $youtube="false";

                 if(strlen($obj[$key]['header_video_url'])>1){

                    if (strpos($obj[$key]['header_video_url'],"youtube") !== false)

                    {

                        $youtube="true";

              $v_url=substr($obj[$key]['header_video_url'],strrpos($obj[$key]['header_video_url'],"=")+1); 

              $autoplay=$obj[$key]['autoplay'] == "true"? "1": "0";

              $loop=$obj[$key]['loop'] == "true"? "1": "0";

              $v_url="http://www.youtube.com/embed/".$v_url."?autoplay=".$autoplay."&loop=".$loop."&rel=0"; 

               $vedio_src=$v_url;

                    }

                    else

                     $vedio_src=$v_url;

                 } 

                 else if(strlen($obj[$key]['header_video_name'])>1){

                     $vedio_src="uploaded_videos/".$obj[$key]['header_video_name'].".mp4";

                 }

                $video_width=trim($obj[$key]['video_width']) == ""? "100%": trim($obj[$key]['video_width']); 

                $video_height=trim($obj[$key]['video_height']) == ""? "250px": trim($obj[$key]['video_height']); 

                 if($youtube == "false"){

              ?>

              <video id="example_video_1" class="video-js vjs-default-skin" style="margin-bottom: -5px;" controls preload="none" width="<?php echo $video_width?>" height="<?php echo $video_height?>"

       <?php if($obj[$key]['autoplay'] == "true") echo  'autoplay '; if($obj[$key]['loop'] == "true") echo' loop';?>

      data-setup="{}">

    <source src="<?php echo $vedio_src?>" type='video/mp4' />

    <track kind="captions" src="js/captions.vtt" srclang="en" label="English" />

  </video>

              <?php

                 }

                 else{?>

                 <iframe style="margin-bottom: -5px;" width="<?php echo $video_width;?>" height="<?php echo $video_height?>" src="<?php echo $vedio_src?>" frameborder="0" allowfullscreen></iframe>

                 <?php } 

             }

             ?>     



</div>               

 <?php         

       $header++;

        };break;

        case "content":{

            $contents=str_replace("http//","http://",$obj[$key]['page_content']);

$contents=($contents);

            ?>

 <div class="pc_widget content" style=" font-size: <?php echo $obj[$key]['pc_font_size']."px"?>; color: <?php echo $obj[$key]['pc_font_color']?>; <?php if($obj[$key]['no_border'] == "true") echo "box-shadow: none;"; if($obj[$key]['transparent_bg'] == "true") echo "background: transparent;"; if($obj[$key]['no_padding'] == "false") echo 'padding: 15px;'?>">

<span style="opacity:1;"><?php echo $contents?></span>

</div>

            <?php

           $content++; 

        };break;

        case "redeem":{

       ///  print_r($obj[$key]);

            ?>

             <div class="pc_widget redeem" style="<?php if($obj[$key]['no_border'] == "true") echo "box-shadow: none; border: none;";?>">

             <?php if($obj[$key]['redeem_button_type'] == "button_image"){?>

             

               <a href="<?php echo $obj[$key]['redeem_page_url']?>" class="redeem_link" id="redeem_<?php echo $redeem?>"><img src="uploaded_images/<?php echo $obj[$key]['redeem_button_image']; ?>" style="width: 100%"/></a>

             

             

             <?php } else if($obj[$key]['redeem_button_type'] == "button_template"){      

           if($obj[$key]['is_empty'] == 0 || $obj[$key]['is_empty'] == 1){

          //  echo '<a href="'.$obj[$key]['redeem_page_url'].'"><img src="images/buttons/'.$obj[$key]['redeem_button_template'].'" style="width: 90%;"></a>';

          //  }else{         

///////////////////////get image image (provide support to old pages which had image++0 name)

$redeem_bg_img="";

if(strpos($obj[$key]['redeem_button_template'],"++")>0)

$redeem_bg_img=substr($obj[$key]['redeem_button_template'],0,strpos($obj[$key]['redeem_button_template'],"++"));

else

$redeem_bg_img=$obj[$key]['redeem_button_template'];

            ?>

             <style>

 .button_templater_<?php echo $redeem?> {

    width: 90%;

    height: 75px;

    line-height: 75px;

    border: 0px solid red;

    background-repeat: no-repeat;

  /*  background-size: 100%;*/

  background-size: 290px 75px;

    background-position: center;

    background-image: url("images/buttons/<?php echo $redeem_bg_img;?>");

    text-align: center;

    font-size: <?php echo $obj[$key]['font_size']?>px;



color: <?php echo $obj[$key]['font_color']?>;

font-weight: bolder;

border: 0px solid #ccc;

margin: 0px auto;

 }

 .button_templater_<?php echo $redeem?> span{

  /*   margin-top: 21px;

 display: inline-block;

*/

 }

</style>

<a class="redeem_link" href="<?php echo $obj[$key]['redeem_page_url']?>" style="text-decoration: none;"><div class="button_templater_<?php echo $redeem?>"><span><?php if($obj[$key]['is_empty'] == 1){echo $obj[$key]['redeem_label'];}?></span></div></a>

            <?php

            } 

       }

          else{?>

             <style>

             .redeem_link

             {

              text-decoration: none;

              background: <?php echo $obj[$key]['transparent_bg'] == "true" ? "transparent": "#".$obj[$key]['redeem_bg_color'];?>;

              font-size: <?php echo $obj[$key]['font_size']?>px; 

              padding: 15px;

              font-weight: bold;

              display: block;

            <?php if(isset($obj[$key]['redeem_color']) && strlen($obj[$key]['redeem_color'])>0) 

                   echo 'color: '.$obj[$key]['redeem_color'].";";

                   else

                   echo 'color: #000';

            ?>

                }

            .redeem_link:hover

             {

            background-color: <?php echo $obj[$key]['redeem_hover_color'];?>; 

                }

             </style>

  <a href="<?php echo $obj[$key]['redeem_page_url']?>" class="redeem_link" id="redeem_<?php echo $redeem?>"><?php echo $obj[$key]['redeem_label']; //print_r($obj[$key]);?></a><?php }?>

  <span style="display: none"><?php $redeem_temp=array("force_optin_case"=>$obj[$key]['force_optin_case'],"force_optin_email_case"=>$obj[$key]['force_optin']['force_optin_email_case'],"redeem_prompt"=>$obj[$key]['redeem_prompt'],"force_optin_check"=>$obj[$key]['force_optin_check'],"widget_no"=>$key); echo json_encode($redeem_temp);?></span>

  </div>

            <?php



            $redeem++;

        };break;  

        case "button":{

         //   print_r($obj[$key]);

             if($obj[$key]['simple_button_type'] != "image") 

             { $simple_button_class="class='button_$button'";

             } 

             else

             $simple_button_class="";           

             ?>





            <style>

.button_<?php echo $button ?>

{

    <?php 

if($obj[$key]['simple_button_type'] == "bg_color" && $obj[$key]['transparent_bg'] == "false")

echo 'background-color: '.$obj[$key]['button_bg_color'].';';

else if($obj[$key]['simple_button_type'] == "bg_image")

echo 'background-image: url("uploaded_images/'.$obj[$key]['simple_button_image'].'");';

else echo 'background: transparent;';

?>    



display: block;

padding: 15px;

color: <?php echo $obj[$key]['font_color']?>;

text-decoration: none;

font-size: 20px;

text-align: <?php echo $obj[$key]['text_alignment']?>;

}

.button_<?php echo $button ?>:hover

{

    <?php if($obj[$key]['simple_button_type'] == "bg_color"){?>

   background-color:  <?php echo $obj[$key]['button_hover_color']?>;

   <?php }?>

}



</style>

<?php



foreach($obj[$key]['buttons'] as $kk =>$vall)

{ if($vall['button_url'] != "")

  {  ?>

  <div class="pc_widget" style="<?php if($obj[$key]['no_border'] == "true") echo "box-shadow: none;"; ?>">

  <?php

  if($obj[$key]['simple_button_type'] != "image")

   $simple_button_src="<strong>".$vall['button_label']."</strong><img src='images/arrow-right.png' style='float: right;'>";

   else 

   $simple_button_src="<img src='uploaded_images/".$obj[$key]['simple_button_image']."'>";

    echo "<a href=".$vall['button_url']." $simple_button_class >$simple_button_src</a>";

?></div>

<?php

  }

}?>



            <?php

            $button++;

        };break;

        case "icons":{

if($obj[$key]['icons_img_type'] == "image")

$icons_img=getServerURL()."uploaded_images/".$obj[$key]['icons_img_name'];

else if($obj[$key]['icons_img_type'] == "url")

$icons_img=$obj[$key]['icons_img_url'];

else

$icons_img="http://wreckingballmarketing.com/coupon_updates/fb_app/coupon.jpg";

$icons_img.="?".time();

$fb_title=$obj[$key]['fb_title']=="" ? "Coupon Builder Page" : $obj[$key]['fb_title'];

$fb_app_id=$obj[$key]['fb_app_id']=="" ? "458069077598226" : $obj[$key]['fb_app_id'];

$fb_caption=$obj[$key]['fb_caption']=="" ? "Coupon Builder Page" : $obj[$key]['fb_caption'];

$fb_desc=$obj[$key]['fb_desc']=="" ? "Coupon Builder Page" : $obj[$key]['fb_desc'];

$fb_button_template=$obj[$key]['fb_button_template']=="" ? "fb_icon.png" : $obj[$key]['fb_button_template'];

$redirect_page_id=$obj[$key]['after_share_url']=="" ? $page_id : $obj[$key]['after_share_url'];

$redirect_url=$obj[$key]['fb_app_id']==""? "http://wreckingballmarketing.com/coupon_updates/fb_app/redirect.php" : getServerURL()."view_page.php?id=".$redirect_page_id;

  $fb_link='https://www.facebook.com/dialog/feed?app_id='.$fb_app_id.'&link='.$page_url.'&picture='.$icons_img.'&name='.$fb_title."&caption=".$fb_caption.'&description='.$fb_desc.'&redirect_uri='.$redirect_url;            

            ?>

            <div class="pc_widget icons" style="<?php if($obj[$key]['transparent_bg'] == "true") echo "background: transparent;"; if($obj[$key]['no_border'] == "true") {echo "box-shadow: none; ";} echo "text-align: ".$obj[$key]['text_alignment'].";"; ?>">

            <?php if(isset($obj[$key]['fb']) && $obj[$key]['fb'] == "true"){?>

<!--<a href="<?php echo $fb_link?>" onclick="fb_share('<?php echo $obj[$key]['fb_app_id']?>',this); return false;" id="facebook_<?php echo $icons?>_<?php echo  $obj[$key]['after_share_url']?>"><img src="images/fb_icon.png"></a>-->

<a href="#" onClick="postToFeed('<?php echo $fb_app_id ?>','<?php echo $page_url ?>','<?php echo $icons_img ?>','<?php echo $fb_title ?>','<?php echo $fb_caption ?>','<?php echo $fb_desc ?>','<?php echo $redirect_url ?>',this); return false;" id="facebook_<?php echo $icons?>_<?php echo  $obj[$key]['after_share_url']?>"><img src="images/buttons/<?php echo $fb_button_template;?>"  style="width:auto;height:auto;"></a><?php } if(isset($obj[$key]['twitter']) && $obj[$key]['twitter'] == "true"){?>

<a href="http://twitter.com/share?url=<?php echo $page_url?>" class="share_link" id="twitter_<?php echo $icons?>_<?php echo  $obj[$key]['after_share_url']?>"><img src="images/twitter_icon.png" /></a><?php } if(isset($obj[$key]['email']) && $obj[$key]['email'] == "true"){?>

<a href="mailto:<?php echo "&subject=".$obj[$key]['email_subject']."&body=".str_replace(array('%link%'),array($page_url),$obj[$key]['share_text']) ?>" class="share_link" id="email_<?php echo $icons?>_<?php echo  $obj[$key]['after_share_url']?>"><img src="images/email_icon.png"/></a><?php } if(isset($obj[$key]['sms']) && $obj[$key]['sms'] == "true"){?>

<!--<a href="sms:/**/;/*body=<?php //echo str_replace('%link%',$page_url,$obj[$key]['share_text']) ?>*/" class="share_link" id="sms_<?php //echo $icons?>_<?php //echo  $obj[$key]['after_share_url']?>"><img src="images/sms_icon.png" style="margin-bottom: -2px;"/></a><?php //}?>-->
<a href="sms:" class="share_link" id="sms_<?php echo $icons?>_<?php echo  $obj[$key]['after_share_url']?>"><img src="images/sms_icon.png" style="margin-bottom: -2px;"/></a><?php }?>

</div>

            <?php

            $icons++;

        };break;

              case "call":{

          //  echo $li."<hr>";

            ?>

     <style>

     .call_button_<?php echo $call?> a{

         background-color: <?php echo $obj[$key]['transparent_bg'] == "true" ? "transparent": $obj[$key]['call_bg_color']?>;

          text-align: center;

         font-size: <?php  echo strlen($obj[$key]['fonts_size'])>0 ? $obj[$key]['fonts_size']."px" : "18px";?>;  

         color: <?php  echo strlen($obj[$key]['fonts_color'])>0 ? $obj[$key]['fonts_color'] : ""; ?>;  

         

     }

     .call_button_<?php echo $call?> a:hover{

      background-color: <?php echo $obj[$key]['call_hover_color'];?>;   

     }

     </style>       

<div class="pc_widget call_button_<?php echo $call?>" style="<?php if($obj[$key]['no_border'] == "true") echo "box-shadow: none;";?>">

<a href="tel:<?php echo $obj[$key]['call_number']?>" class="call"><strong><?php echo $obj[$key]['call_lable']?></strong></a>

</div>

            <?php

            $call++;

        };break;     

            case "timer":{

                ////////////////////time zones for count doe=wn timer

/*
$sql_time_zone="SELECT client_information.time_zone_id,time_zones.time_zone,real_value FROM client_information inner join time_zones on(client_information.time_zone_id = time_zones.id) where client_information.id='$user_id'"; 

$res_time_zone=mysqli_query($con,$sql_time_zone);

if(mysqli_num_rows($res_time_zone) > 0)
{
    $row_time_zone=mysqli_fetch_assoc($res_time_zone);
    $st_stamp=time()+($row_time_zone['real_value'] * 3600);
}
 else 
*/
 $st_stamp=time();              

//// echo "<hr>".time()."----------".$st_stamp;               

       //   $st_date=$obj[$key]['start_time'];

  $end_date=$obj[$key]['end_time'];
 
 //$st_stamp=stamp_date($st_date);

 $end_stamp=stamp_date($end_date);
 
  $stamp=($end_stamp-$st_stamp);
  
  

 /// echo "start($st_date)==".$st_stamp."---end date($end_date)--".$end_stamp."====stamp---".$stamp;

/*$days=($stamp/86400);

$day=intval($days);

$hours=($stamp-($day*86400))/3600;

//$secs=$stamp-()/60;

$hour=intval($hours);

//echo "<hr>";

$mins=($stamp-(($day*86400)+($hour*3600)))/60;

$min=intval($mins);

$sec='00'; 

 */       

$arr_time=duration($stamp); 

$day=(int)$arr_time['d'];

$hour=$arr_time['h'];

$min=$arr_time['m'];

$sec=$arr_time['s'];

     ?>

            <script>

    $(document).ready(function(){   

    var day='<?php echo $day?>';

    var hour='<?php echo $hour?>';

    var min='<?php echo $min?>';

    var sec='<?php echo $sec?>';

  $("#sec_<?php echo $timer?>").html(pad(sec,2));

  $("#min_<?php echo $timer?>").html(pad(min,2));

  $("#hour_<?php echo $timer?>").html(pad(hour,2));

  $("#day_<?php echo $timer?>").html(pad(day,2));

var interval=setInterval(function(){

if(day <= 0 && hour <= 0 && min <= 0 && sec <= 0)

{

     $("#sec_<?php echo $timer?>").html('00');

  $("#min_<?php echo $timer?>").html('00');

  $("#hour_<?php echo $timer?>").html('00');

  $("#day_<?php echo $timer?>").html('00');

   clearInterval(interval);

}

else

{  

--sec;

  if(sec<0)

  {

  min=--min;

  sec=59;

  }

  if(min < 0)

  {

   hour=--hour;

   min=59;   

  }

  if(hour <  0)

  {

   day=--day;

 if(day>0){

   sec=59;

   min=59;

   hour=23;    

   }

   else

  { clearInterval(interval);

  $("#timer_<?php echo $timer?>").html("Ended");

  }

  }

  $("#sec_<?php echo $timer?>").html(pad(sec,2));

  $("#min_<?php echo $timer?>").html(pad(min,2));

  $("#hour_<?php echo $timer?>").html(pad(hour,2));

  $("#day_<?php echo $timer?>").html(pad(day,2));

 }

  },1000);

  });

            function pad(number, length) {

   

    var str = '' + number;

    while (str.length < length) {

        str = '0' + str;

    }

   

    return str;



}

                     

            </script>

            <style>

.timer li

{

      border: 0px solid red;

  height: 70px;

    list-style-type: none;

    font-size: 30px;

    font-weight: bolder;

float: left;

    padding: 2px;

    margin-left: 5px;

    font-family:  "Times New Roman",Georgia,Serif;

}

.hour,.min,.sec,.day{

    margin-right: -7px;

     border: 1px solid #000;

      background-color: #666;

     color: #fff;

     border-radius: 4px;

   width: 33px;

      display: inline-block;

}

#ddots,#hdots,#mdots{

   

    margin-top: -2px;

    margin-left: 11px;

    border: 0px solid green;

   display: inline-block;

   

}

.hint{

    border: 0px solid red;

    font-size: 14px;

vertical-align: bottom;

background-color: #ccc;

border-radius: 2px;

font-weight: 100;

width: 33px;

position: absolute;

display: block;

}

.timer{

 display: inline-block;



 padding: 0px;

 margin: 0px 8px 0px 0px;   

}

.timer_text{

   color : <?php echo "#".$obj[$key]['tfont_color']?>; 

   font-size:  <?php echo $obj[$key]['tfont_size']."px"?>;

vertical-align: top;

}

</style>

<?php 

$timer_text=explode("%timer%",$obj[$key]['timer_text']);

?>

             <div class="pc_widget" style="opacity: <?php echo $obj[$key]['page_opacity']?>; text-align: center; padding-top: 15px;<?php if($obj[$key]['transparent_bg'] == "true") echo "background: transparent;";  if($obj[$key]['no_border'] == "true") echo "box-shadow: none;";?>">

             <?php  echo "<span class='timer_text'>".$timer_text[0]."</span>";?>

             <ul id="timer_<?php echo $timer?>" class="timer" >

<li>

<span id="day_<?php echo $timer?>" class="day">2</span>

<span id="ddots">:</span><span class="hint">Day</span>

</li>

<li>

<span id="hour_<?php echo $timer?>" class="hour">1</span>

<span id="hdots">:</span><span class="hint">Hour</span>

</li>

<li><span id="min_<?php echo $timer?>" class="min">3</span>

<span id="mdots">:</span><span class="hint">Min</span>

</li>

<li><span id="sec_<?php echo $timer?>" class="sec" style="width: 33px;">4</span><span class="hint">Sec</span></li>

</ul>   <?php echo "<span class='timer_text'>".@$timer_text[1]."</span>";?>

             </div>

            <?php

            $timer++;

        };break;                   

          case "cart":{

      ///  print_r($obj[$key]);

            ?>

        <div class="pc_widget cart" style="text-align: center; <?php if($obj[$key]['transparent_bg'] == "true") echo "background: transparent;"; if($obj[$key]['no_border'] == "true") echo "box-shadow: none; border: none;";?>">

        <?php if($obj[$key]['cart_button_type'] =="button_image") 

        { ///////////////button image

		?> 

            <a href="<?php echo $obj[$key]['cart_url']?>"><img src="uploaded_images/<?php echo $obj[$key]['cart_button_image']?>" style="width: 90%"></a> 

      

<?php             } 

        else if($obj[$key]['cart_button_type'] =="button_template"){

		///////////////////button template

            if($obj[$key]['is_empty'] == 0 || $obj[$key]['is_empty'] == 1){

          //  echo '<a href="'.$obj[$key]['cart_url'].'"><img src="images/buttons/'.$obj[$key]['cart_button_template'].'" style="width: 90%"></a>';

         //   }else{

            ?>

             <style>

 .button_template_<?php echo $cart?> {

    width: 90%;

    height: 75px;

    line-height: 75px;

    background-repeat: no-repeat;

  background-size: 290px 75px;

    background-position: center;

    background-image: url("images/buttons/<?php echo $obj[$key]['cart_button_template']?>");

    text-align: center;

  font-size: <?php echo $obj[$key]['font_size']?>px;

color: <?php echo $obj[$key]['font_color']?>;

font-weight: bolder;

border: 0px solid #ccc;

margin: 0px auto;

 }

 .button_template_<?php echo $cart?> span{

 /*    margin-top: 21px;

 display: inline-block;

 */

 }

</style>

<a href="<?php echo $obj[$key]['cart_url']?>" style="text-decoration: none;"><div class="button_template_<?php echo $cart?>"><span><?php if($obj[$key]['is_empty'] == 1){echo  ucfirst($obj[$key]['cart_lable']); } ?></span></div></a>

            <?php

            }     

        }

        else if($obj[$key]['cart_button_type'] =="label"){

		////////////////////button lable

            ?>

			             <style>

 .button_template_<?php echo $cart?> {

    text-align: center;

  font-size: <?php echo $obj[$key]['font_size']?>px;

color: <?php echo $obj[$key]['font_color']?>;

font-weight: bolder;

border: 0px solid red;

margin: 0px auto;

text-decoration: none;

 }

 .button_template_<?php echo $cart?>:hover{

 /*    margin-top: 21px;

 display: inline-block;

 */

 }

</style>

            <a href="<?php echo $obj[$key]['cart_url']?>" class="button_template_<?php echo $cart?>"><strong><?php echo ucfirst($obj[$key]['cart_lable'])?></strong></a>

            <?php

        }

         ?>

        </div>    

            <?php

            $cart++;

        };break;         

         case "scarcity":{

          //   print_r($obj[$key]);

       if(isset($obj[$key]['red_limit'])){

$sql_count="select count(id) from redeems where page_id='$page_id'";

  $res_count=mysqli_query($con,$sql_count);

  $redeemed=mysqli_fetch_assoc($res_count); 

    $redeem_limit= $obj[$key]['red_limit']-@$redeemed[0];

    if($redeem_limit<0)

    $redeem_limit=0;

       } 

      // echo $redeem_limit."-------".$obj[$key]['red_limit'];

            ?>

      <script>

      redeem_limit='<?php echo $redeem_limit?>';

      </script>      

     <style>

     .red_<?php echo $scarcity?>{

         padding: 15px;

         <?php if($obj[$key]['red_bg_type'] == "red_color"){

             ?>

         background-color: <?php echo $obj[$key]['red_bg_color']?>;

         <?php

         } else if($obj[$key]['red_bg_type'] == "red_image"){

         ?>

          background-image: url('uploaded_images/<?php echo $obj[$key]['red_bg_image']?>');

          <?php }?>

         color: <?php echo $obj[$key]['red_font_color']?>;

         font-size: <?php echo $obj[$key]['red_font_size']."px"?>;

         text-align: <?php echo $obj[$key]['text_alignment']?>;

         font-weight: bold;

     }

       

     </style>       

     <div class="pc_widget red_<?php echo $scarcity?>" style="<?php if($obj[$key]['transparent_bg'] == "true") echo "background: transparent;"; if($obj[$key]['no_border'] == "true") echo "box-shadow: none; border: none;";?>">

     <?php echo str_replace('%redeem%',"$redeem_limit",$obj[$key]['red_text']);?>

     </div>

            <?php

            $scarcity++;

        };break;   

                 case "facebook":{

        

            ?>

            <div class="pc_widget" style="<?php if($obj[$key]['transparent_bg'] == "true") echo "background: transparent;"; if($obj[$key]['no_border'] == "true") echo "box-shadow: none; border: none;";?> height:auto;">

         <div class="fb-comments" data-href="<?php echo 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]?>" data-num-posts="4" ></div></div>

            <?php

            $facebook++;

        };break;
        
        
        case "webform":{

        

            ?>
            
            <style>
            .form-group {
                margin-bottom: 15px;
            }

            .form-control {
                background-color: #FFFFFF;
                border: 1px solid #E3E3E3;
                border-radius: 4px;
                color: #565656;
                padding: 8px 12px;
                height: 21px;
                -webkit-box-shadow: none;
                box-shadow: none;
                width: 100%;
                font-size: 14px;
                line-height: 1.42857143;
            }
            </style>

            <div class="pc_widget" style="<?php if($obj[$key]['transparent_bg'] == "true") echo "background: transparent;"; if($obj[$key]['no_border'] == "true") echo "box-shadow: none; border: none;";?>">
                
                <form action="server.php" enctype="multipart/form-data" method="post" style="padding: 3%;width: 92%;" id="form213" onsubmit="return save_pagebuilder_subscriber()">
					
                    <?php 
                    if($obj[$key]['heading']!=""){
                    ?>
                        <div class="form-group">
    						<label><?php echo $obj[$key]['heading']; ?></label>
    					</div>
                    <?php
                    }
                    ?>
                    
                    <?php 
                    if($obj[$key]['name']!=""){
                    ?>
                        <div class="form-group">
    						<label><?php echo $obj[$key]['name']; ?></label>
    						<input type="text" name="first_name" class="form-control" required="">
    					</div>
                    <?php
                    }
                    ?>
                    
                    <?php 
                    if($obj[$key]['email']!=""){
                    ?>
                        <div class="form-group">
    						<label><?php echo $obj[$key]['email']; ?></label>
    						<input type="email" name="email" class="form-control" required="">
    					</div>
                    <?php
                    }
                    ?>
                    
                    <?php 
                    if($obj[$key]['number']!=""){
                    ?>
                        <div class="form-group">
    						<label><?php echo $obj[$key]['number']; ?></label>
    						<input type="text" name="phone_number" class="form-control" required="">
    					</div>
                    <?php
                    }
                    ?>
                    
                    <?php 
                    if($obj[$key]['birthday']!=""){
                    ?>
                        <div class="form-group">
    						<label><?php echo $obj[$key]['birthday']; ?></label>
    						<input type="text" name="birthday" class="form-control" id="birthday" required="">
    					</div>
                    <?php
                    }
                    ?>
                    
                    <?php 
                    if($obj[$key]['anniversary']!=""){
                    ?>
                        <div class="form-group">
    						<label><?php echo $obj[$key]['anniversary']; ?></label>
    						<input type="text" name="anniversary" class="form-control" id="anniversary">
    					</div>
                    <?php
                    }
                    ?>
                    
                    <div style="height:8px; clear:both"></div>
					<div class="form-group m-b-0">
						<button class="btn btn-success waves-effect waves-light" type="submit"> Save </button>
						<input type="hidden" name="cmd" id="pagebuilder_subscriber_cmd" value="pagebuilder_subscriber" />
                        <input type="hidden" name="user_id" value="<?php echo $userID; ?>" />
                        <input type="hidden" name="group_id" value="<?php echo @$obj[$key]['group_id']; ?>" />
                        
					</div>
                    
                </form>
                
            </div>
            
            <script type="text/javascript">

            function save_pagebuilder_subscriber(){
                
                var data = $("#form213").serialize();
                $.post("server.php",{data,cmd:$("#pagebuilder_subscriber_cmd").val()},function(res){
                    if(res==1){
                        $('#form213').trigger("reset");
                        alert("You are subscribed, Thanks!");
                    }
                })
                
                return false;
            }
            </script>
              <script>
              $( function() {
                $( "#birthday , #anniversary" ).datepicker();
                
              } );
              </script>
            
            <style>
            
            .btn {
                display: inline-block;
                font-weight: 400;
                text-align: center;
                white-space: nowrap;
                vertical-align: middle;
                padding: 8px 16px;
                border-width: 1px;
                border-radius: 5px;
                border: solid;
                cursor:pointer;
            }
            .btn-success {
                color: #fff;
                background-color: #28a745;
                border-color: #28a745;
            }
            .btn-success:hover {
                color: #fff;
                background-color: #218838;
                border-color: #1e7e34;
            }
            
            </style>

            <?php

            $webform++;

        };break;   

             case "twitter":{

       //echo 'twitter case ran'.print_r($obj[$key])."<hr>";

              if($obj[$key]['username']!="")

           { 

               $user_name=trim($obj[$key]['username'],"@");

        ///   echo "Username not empty";  

            if($obj[$key]['tweets']<5)

             $total=5;

 else

  $total=$obj[$key]['tweets']*3;



$consumerkey = "yBd3eAocVYjyu1K63tdvJg";

$consumersecret = "MmLGi9xNHmg65kMm4TvwjOn3TJyIVFAEuEE3VCuhp0";

$accesstoken = "391977413-0CgFKbeW8MygNmtCXzhwpmUYybbg6jDVBdjnlgcI";

$accesstokensecret = "1NVXSPKyzeSFD013U3JdiWtWE9oM5fPacA4kV1nHHQ";

 

  

$connection = getConnectionWithAccessToken1($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);



//echo "http://api.twitter.com/1/statuses/user_timeline.json?screen_name=".$obj[$key]['username']."&count=$total".$obj[$key]['tweets'];

//echo "<hr>";

 $jsont= $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$user_name."&count=".$total);

  $objt=json_encode($jsont);

 $objt=json_decode($objt,true);

  /*echo "<pre>";

  print_r($objt);

  echo "</pre><hr>";*/

/*if(isset($objt)){

    $user_name=$obj[$key]['username'];

 $sqlt="select * from twitter_data where user_name='$user_name' and page_id='$page_id' limit 1"; 

 $rest=mysqli_query($con,$sqlt) or die(mysqli_error($con));

 if(mysqli_num_rows($rest)>0){

  $rowt=mysqli_fetch_assoc($rest);

  $objt=json_decode($rowt['json'],true);  

 }  

}*/

 if(isset($objt))

 { ?>

   <div class="pc_widget twitter" style="background-color: <?php echo $obj[$key]['t_bcolor']?>; color: <?php echo $obj[$key]['t_fcolor']?>; font-size: <?php echo $obj[$key]['t_fsize']."px"?>; padding: 3px;  <?php if($obj[$key]['transparent_bg'] == "true") echo " background: transparent;"; if($obj[$key]['no_border'] == "true") echo " box-shadow: none; border: none;";?>">

 

 <?php

     for($k=0;$k<$total;$k++){

        if($objt[$k]['text'] != "")

   {

        echo '<img src="'.$objt[$k]['user']['profile_image_url'].'" align="left" style="margin-right: 5px; border: 1px solid #ccc; border-radius: 4px;"/>';

     echo '<span style=""><a href="http://twitter.com/'.$objt[$k]['user']['screen_name'].'" target="_blank"><b>'.$objt[$k]['user']['name'].'</b></a> @'.$objt[$k]['user']['screen_name']."</span><br/>";

     echo '<span style="">'.$objt[$k]['text'].'</span>';

     echo "<hr style='clear: both; margin-top: 12px;'>";

     if($obj[$key]['tweets']==$k)

        break; 

    }

	else{

      // echo "<h2>Empty text</h2>";

    }

    

 }

 ?>

 </div>

 <?php

 }

           }

            $twitter++;

        };break;

            case "map":{        

            ?>

<div class="pc_widget map" style="<?php if($obj[$key]['transparent_bg'] == "true") echo "background: transparent;"; if($obj[$key]['no_border'] == "true") echo "box-shadow: none; border: none;";?> height: 300px;"><div id="get_address_<?php echo $map?>" style="border: 0px solid red; margin-top: 230px; opacity: .8; position: absolute; z-index: 99999; width: 60%; background: #ccc; margin-left: 0px; padding: 20px; display: none;"><input type="text" name="location_<?php echo $map?>" style="width: 75%; height: 25px; border: 1px solid #ccc;"><input type="button" id="showroute_<?php echo $map?>" value="Go" style="width: 20%; height: 30px;" onclick="show_route(this,'<?php  echo str_replace(array("\n"),array(" "),$obj[$key]['address']);?>')"></div>

  <script>

  $(document).ready(function(){

      //console.log(typeof initialize);   

    //  google.maps.event.addDomListener(window, 'load', initialize);

  initialize('<?php echo $map?>','<?php echo $obj[$key]['lat'];?>','<?php echo $obj[$key]['lon'];?>','<?php  echo str_replace(array("\n"),array(" "),$obj[$key]['address']);?>',<?php echo $obj[$key]['zoom']=="" ? 8: $obj[$key]['zoom']?>);      

  })

  </script>

<div id="map_canvas_<?php echo $map;?>" style="width:100%; height:100%"></div>



</div><div <?php if($obj[$key]['get_direction'] != "true") echo "style='display: none;'"?>><button id="getdir_<?php echo $map?>" onclick="get_direction(this)">Get Directions</button><span></span><button id="closegetdir_<?php echo $map;?>" onclick="close_getdir(this)" style="display: inline-block; margin-left: 35%; width: 10%; display: none;">Close</button></div>

<div style="border: 1px solid #ccc; max-height: 100%; overflow: auto; width: 100%; display: none;" id="turn_panel_<?php echo $map?>"></div>

            <?php

            $map++;

        };break;

case "loyalty":{

    ?>

     <div class="pc_widget loyalty" style="padding: 10px; <?php if($obj[$key]['loyalty_bg_type'] == "loyalty_image") echo "background-image: url('uploaded_images".$obj[$key]['loyalty_image']."');"; else if($obj[$key]['loyalty_bg_type'] == "loyalty_bgcolor") echo "background-color: ".$obj[$key]['loyalty_bgcolor'].";"; if(isset($obj[$key]['loyalty_font_color'])) echo "color: ".$obj[$key]['loyalty_font_color'].";";  if(isset($obj[$key]['loyalty_font_size'])) echo "font-size: ".$obj[$key]['loyalty_font_size']."px;";  if(isset($obj[$key]['text_alignment'])) echo "text-align: ".$obj[$key]['text_alignment'].";"; if($obj[$key]['transparent_bg'] == "true") echo " background: transparent;"; if($obj[$key]['no_border'] == "true") echo " box-shadow: none; border: none;";?>"  ><?php echo $obj[$key]['text_above_code']?><div style="border: 0px solid red; width: 100%;padding: 10px;">

     <input type="text" name="keyword" style="border: 1px solid #ccc; margin-right: 5px; height: 22px;"><input type="button" value="Submit" onclick="loyalty_check(this);"><div style="display: none"><?php echo json_encode($obj[$key]);?></div>

     </div></div>

    <?php 

    $loyalty++;

};break;

case "scratch":{

  //  echo "<pre>";

  // print_r($obj[$key]);

  // echo "</pre>";

    if($obj[$key]['scratch_bg_type'] == "image")

    $scratch_bg_image="uploaded_images/".$obj[$key]['scratch_bg_image'];

    else

    $scratch_bg_image=$obj[$key]['scratch_bg_url'];

    if($obj[$key]['scratch_fg_type'] == "image")

   $scratch_fg_image="uploaded_images/".$obj[$key]['scratch_fg_image'];

    else

    $scratch_fg_image=$obj[$key]['scratch_fg_url'];

   // print_r($obj[$key]);

   $widget_bg_image=$obj[$key]['scratch_image'];

   $widget_bg_color=$obj[$key]['scratch_bgcolor'];

    ?>

<div class="pc_widget" style="<?php if($obj[$key]['transparent_bg'] == "true") echo "background: transparent;"; if($obj[$key]['no_border'] == "true") echo "box-shadow: none; border: none;"; if($obj[$key]['widget_bg_type'] == "scratch_image") echo "background:url(uploaded_images/$widget_bg_image); background-repeat: no-repeat; background-position: center; background-size: 100%"; else if($obj[$key]['widget_bg_type'] == "scratch_bgcolor") echo "background-color: $widget_bg_color"?>" >

<script type="text/javascript" src="js/scratch.js?ver=1.6.18"></script>

<script type="text/javascript" charset="utf-8">

var scratch_c={};

jQuery(window).load(function() {

    jQuery('#1234567890_<?php echo $scratch?>').rabidScratchCard({

                revealRadius:parseInt('<?php echo $obj[$key]['reveal_radius'] != "" ? $obj[$key]['reveal_radius']: 35 ?>'),

        percentComplete:parseInt('<?php echo $obj[$key]['auto_show_after']!= "" ? $obj[$key]['auto_show_after'] : 85 ?>'),

        updateOnFingerMove:false

      });

$('#1234567890_<?php echo $scratch?>').mouseup(function(){

    if(scratch_m['<?php echo $scratch?>'] == "completed")

{

scratch_c['<?php echo $scratch?>']="completed";

} 

if(scratch_c['<?php echo $scratch?>']=="completed")

$('#1234567890_<?php echo $scratch?> canvas').css({cursor: 'pointer'});  

});

$('#1234567890_<?php echo $scratch?>').mousedown(function(e){

    if(scratch_m['<?php echo $scratch?>'] == "completed" && scratch_c['<?php echo $scratch?>'] == "completed" && $(e.target).is("canvas"))

    {

   var scratch_page='<?php echo $obj[$key]['scratch_page']?>';

   if(scratch_page != "")

   window.location.href='view_page.php?id='+scratch_page; 

    }  

});      

  });  

</script>
<?php
if($obj[$key]['orig_dimensions']=="true")
   {
	?>
    <style>
		.scratchcard .scratchCanvas
		{
			width:auto !important;
		}
    </style>   
    <?php
   }
   else
   {
	   ?>
       <style>
		.scratchcard .scratchCanvas
		{
			width:100% !important;
		}
    </style>   
       <?php
   }

    ?>
<div style="text-align: <?php echo $obj[$key]['text_alignment']?>;" id="1234567890_<?php echo $scratch?>" class="scratchcard" data-backGroundImage="<?php echo $scratch_bg_image?>" data-foreGroundImage="<?php echo $scratch_fg_image?>"></div>

     </div>

    <?php 

    $scratch++;

};break;

    }

  }

}   

  

?>

</div>



<div style="background-color: #666; z-index: 1; opacity: .5; display: none; position: fixed; left: 0px; top: 0px;" id="light_box"><img src="images/ajax-loader.gif" style="position: absolute; left: 50%; top: 50%; z-index: 9999;" /></div>


<script type="text/javascript">


function postToFeed(App_id, pageUrl, icon_img, Name, caption, desc, reUrl, data)

	 {

		FB.init({appId:App_id, status: true, cookie: true});

		var icon_id=$(data).attr('id').split("_");

      	icon=icon_id[0];

      	var after_share_url=icon_id[2];

	 	$.post("handle_ajax.php",{cmd: 'note_stats',icon: icon,visitor_id: visitor_id,page_id:page_id}); 

		

             var obj = {

                      method: 'feed',

                      redirect_uri: reUrl,

                      link: pageUrl,

                      picture: icon_img,

                      name: Name,

                      caption: caption,

                      description: desc

                    };

        function callback(response) {  

               if(response=='')

			  {

				  return false;

			  }

			  else

			  {

				  if(response['post_id']!=null)

				  {

					  window.location.href="<?php echo getServerURL()?>view_page.php?id="+after_share_url;

				  }

			  }

			



       }

        FB.ui(obj, callback);

      }

</script>

<?php

    if($facebook > 1){

?>

<script>

$(document).ready(function(){

  ///  $(".fb-comments").css({'width': $(window).width(),height: $(window).height()});

     $(".fb-comments").attr({

      'data-with': $(document).width()   

     });

})

</script>

<div id="fb-root"></div>

<script>(function(d, s, id) {

  var js, fjs = d.getElementsByTagName(s)[0];

  if (d.getElementById(id)) return;

  js = d.createElement(s); js.id = id;

  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";

  fjs.parentNode.insertBefore(js, fjs);

}(document, 'script', 'facebook-jssdk'));</script>

<?php

    } 

?>


</body>



<?php

function duration($secs)

{

$vals = array(

           //   'w' => (int) ($secs / 86400 / 7),

             // 'd' => $secs / 86400 % 7,

              'd' =>(int) $secs / 86400,

              'h' => $secs / 3600 % 24,

              'm' => $secs / 60 % 60,

              's' => $secs % 60

              );

return $vals;

}
?>