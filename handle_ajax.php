<?php @session_start();

error_reporting(0);
include("database.php");
$con = $link;
$cmd=$_POST['cmd'];
if(isset($_REQUEST['page_id']) && $_REQUEST['page_id'] != ""){
    $page_id=$_REQUEST['page_id'];
    $sql="select * from pages where id='$page_id'";
$res=mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($res);
    $userID=$row['created_user'];
$TimeZoneValue=get_timezone($userID);
$TimeZoneValue = preg_replace('/\-/', '/', $TimeZoneValue, 1);
if($TimeZoneValue != "none")
date_default_timezone_set($TimeZoneValue);  //US CST
}

switch($cmd){
    
    case"copy_page":{
   $page_id=$_POST['page_id'];
   $page_title=$_POST['page_title'];     
        
      if(isset($page_id))
{  
$sql="select * from pages where id='$page_id'";
$res=mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($res);
$page_key_old=$row['page_key'];
$json=$row['json'];
//$_SESSION['Ar_js']="<textarea>".$json."</textarea>";
$obj=json_decode($json,true);
if($obj === NULL)
{$str=str_replace('"{','{',parse(DBoutf($json))); 
$json=str_replace('}"','}',$str);
$obj=json_decode($json,true);
}
$page_key=uniqid();
///////////////////////
$_SESSION['Arr']=$_SESSION['Ar_js']="<textarea>".print_r($obj,true)."</textarea>";
if(isset($obj)){
         $header=1;
$redeem=1;
$scarcity=1;
$fonts=1;
$cart=1;
$loyalty=1;
$button=1;
$icons=1;
$scratch=1;
$images="";
  foreach($obj as $key => $val)
{
   
    $li=substr($key,0,strpos($key,"_"));
  
    switch($li){
       
        case "header":{
                if(isset($obj[$key]['header_image_name']))
           {
               rename_copy($obj[$key]['header_image_name'],"uploaded_images/thumbs");
   $obj[$key]['header_image_name']=rename_copy($obj[$key]['header_image_name'],"uploaded_images");
   $images[$obj[$key]['header_image_name']]='image';
   
           }
              else  if(isset($obj[$key]['header_video_name']))
             {$obj[$key]['header_video_name']=rename_copy($obj[$key]['header_video_name'].".mp4","uploaded_videos");
            $images[$obj[$key]['header_video_name']]='video'; 
             }
    
       $header++;
        };break;  
              case "settings":{
                if(isset($obj[$key]['header_image']))
        {
            rename_copy($obj[$key]['header_image'],"uploaded_images/thumbs");
        $obj[$key]['header_image']=rename_copy($obj[$key]['header_image'],"uploaded_images");
        $images[$obj[$key]['header_image']]='image';
        
        }
    
        };break;
              case "redeem":{
                if(isset($obj[$key]['redeem_image']))
        {  rename_copy($obj[$key]['redeem_image'],"uploaded_images/thumbs"); 
            $obj[$key]['redeem_image']=rename_copy($obj[$key]['redeem_image'],"uploaded_images"); 
        $images[$obj[$key]['redeem_image']]='image';
      
        }         else    if(isset($obj[$key]['redeem_button_image']))
        {  rename_copy($obj[$key]['redeem_button_image'],"uploaded_images/thumbs"); 
            $obj[$key]['redeem_button_image']=rename_copy($obj[$key]['redeem_button_image'],"uploaded_images"); 
        $images[$obj[$key]['redeem_button_image']]='image';
      
        }   
       $redeem++;
        };break;
              case "scarcity":{
                if(isset($obj[$key]['red_bg_image']))
             { rename_copy($obj[$key]['red_bg_image'],"uploaded_images/thumbs"); 
                 $obj[$key]['red_bg_image']=rename_copy($obj[$key]['red_bg_image'],"uploaded_images");  
             $images[$obj[$key]['red_bg_image']]='image';
             
             }  
       $scarcity++;
        };break;
        case "cart":{
        if(isset($obj[$key]['cart_button_image']))
             { rename_copy($obj[$key]['cart_button_image'],"uploaded_images/thumbs"); 
                 $obj[$key]['cart_button_image']=rename_copy($obj[$key]['cart_button_image'],"uploaded_images");  
             $images[$obj[$key]['cart_button_image']]='image';
             
             }  
       $cart++;
        };break;
               case "button":{
        if(isset($obj[$key]['simple_button_image']))
             { rename_copy($obj[$key]['simple_button_image'],"uploaded_images/thumbs"); 
                 $obj[$key]['simple_button_image']=rename_copy($obj[$key]['simple_button_image'],"uploaded_images");  
             $images[$obj[$key]['simple_button_image']]='image';
             
             }  
       $button++;
        };break;     
               case "loyalty":{
        if(isset($obj[$key]['loyalty_image']))
             { rename_copy($obj[$key]['loyalty_image'],"uploaded_images/thumbs"); 
                 $obj[$key]['loyalty_image']=rename_copy($obj[$key]['loyalty_image'],"uploaded_images");  
             $images[$obj[$key]['loyalty_image']]='image';
             
             }  
       $loyalty++;
        };break;
                  case "fonts":{
                if(isset($obj[$key]['font_bg_image']))
             { rename_copy($obj[$key]['font_bg_image'],"uploaded_images/thumbs"); 
                 $obj[$key]['font_bg_image']=rename_copy($obj[$key]['font_bg_image'],"uploaded_images");  
             $images[$obj[$key]['font_bg_image']]='image';
             
             }  
       $fonts++;
        };break;
		case "icons":{
                if(isset($obj[$key]['icons_img_name']))
             { rename_copy($obj[$key]['icons_img_name'],"uploaded_images/thumbs"); 
                 $obj[$key]['icons_img_name']=rename_copy($obj[$key]['icons_img_name'],"uploaded_images");  
             $images[$obj[$key]['icons_img_name']]='image';
             
             }  
       $icons++;
        };break;
            case "scratch":{
                if(isset($obj[$key]['scratch_bg_image']))
             { rename_copy($obj[$key]['scratch_bg_image'],"uploaded_images/thumbs"); 
                 $obj[$key]['scratch_bg_image']=rename_copy($obj[$key]['scratch_bg_image'],"uploaded_images");  
             $images[$obj[$key]['scratch_bg_image']]='image';
             }
               if(isset($obj[$key]['scratch_fg_image']))
             { rename_copy($obj[$key]['scratch_fg_image'],"uploaded_images/thumbs"); 
                 $obj[$key]['scratch_fg_image']=rename_copy($obj[$key]['scratch_fg_image'],"uploaded_images");  
             $images[$obj[$key]['scratch_fg_image']]='image';
             }  
       $scratch++;
        };break;
    }
}
}
  ///////////////////////////
if($_POST['action']== "yes")
{
$obj['settings_0']['page_title']=$page_title;    
}

if(count($images)>0){
$test_sql="";    
    foreach($images as $key_img => $val_img)
{ $sql_img="insert into pages_data (status,page_key,name,type) values('used','$page_key','$key_img','$val_img')"; 
mysqli_query($con,$sql_img);
$test_sql.=$sql_img;
}  
}

$json=json_encode($obj);
    $sql="insert into pages(json,page_key,created_user,lat,lng,redeem_limit,page_title,refresh_rate,redeem_once,redeem_page_id,created_date,modified_date) values('".mysqli_real_escape_string($con,$json)."','$page_key','$_SESSION[admin_id]','$row[lat]','$row[lng]','$row[redeem_limit]','$page_title','$row[refresh_rate]','$row[redeem_once]','$row[redeem_page_id]',Now(),Now())";
  ///  $_SESSION['JSON-textrea']="<textarea>".$sql."</textarea>";
  $res=mysqli_query($con,$sql) or die(mysqli_error($con));
  if($res)
  {$insert_id=mysqli_insert_id($con);
  $url_temp = getServerURL()."view_page.php?id=$insert_id";
$token = get_user_token();
$short_url=get_short_url($url_temp,$token);
$sql_up="update pages set short_url='$short_url' where id='$insert_id'";
mysqli_query($con,$sql_up);
////////////////////////////////////////
/////////////////////////////dupliocate any twitter data
$twitter_data="";
$sqlt="select * from twitter_data where page_id='$page_id'";
$rest=mysqli_query($con,$sqlt);
if(mysqli_num_rows($rest)>0){
    while($rowt=mysqli_fetch_assoc($rest))
{ $sqli="insert into twitter_data(page_id,user_name,json,time) values('$insert_id','$rowt[user_name]','".mysqli_real_escape_string($con,$rowt['json'])."','$rowt[time]')";
mysqli_query($con,$sqli);   
}
}
      echo 'Page  created';
  }  else
  echo 'Error occured while copying';
}
        
    };break;
    ////////////////////////end copy page
    case "coupon_redeemed":{
        $ip=$_SERVER['REMOTE_ADDR'];
        $camp_id=$_REQUEST['camp_id'];
		/*include_once('ip2locationlite.class.php');
 $ipLite = new ip2location_lite;
 $ipLite->setKey('e3adfd70a4d2b97f5e03d32cdaf01a3be5fdd9114e45d486e26b8ba4102368bc');
  $get_location = $ipLite->getCity($ip);*/
  $location = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
  $city=$location['geoplugin_city'];
        $page_id=$_POST['page_id'];
        $visitor_id=$_POST['visitor_id'];
        if(isset($page_id) && isset($visitor_id))
       {
  ////////////get no of coupons set in the page
  $sql="select * from pages where id='$page_id'";
$res=mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($res);
$page_json=$row['json'];
$page_json_arr=json_decode($page_json,true);
  if(isset($_POST['suscribe']) && $_POST['suscribe'] !="none")
     suscribe($_POST['suscribe'],$_POST['casee']);
/*$str=str_replace('"{','{',parse(DBoutf($row['json']))); 
$json=str_replace('}"','}',$str);
$obj=json_decode($json,true);
 */
 $total_coupons=$row['redeem_limit'];
 $refresh_rate=$row['refresh_rate'];
 /*$scar_check="11";
 while($scar_i<=$scar_check)
{
    if(isset($obj['scar_'.$scar_i]['red_limit']) && $obj['scar_'.$scar_i]['check']=="images/enable.png")
{
if($obj['scar_'.$scar_i]['red_limit']>0)
{$total_coupons=$obj['scar_'.$scar_i]['red_limit'];
    $scar_i=$scar_check;}
}
$scar_i++;
}*/
       /////////////////////check availabale coupons
       $sql_coup="select count(id) as total_redeems from redeems where page_id='$page_id'"; 
       $res_coup=mysqli_query($con,$sql_coup);
       $row_coup=mysqli_fetch_assoc($res_coup); 
      ////  $_SESSION['coupon___check']=$total_coupons."----".$row_coup['total_redeems'];
       if($row_coup['total_redeems'] <  $total_coupons) 
    { 
///////////////////coupons are available
if(isset($_COOKIE['visitor_id']))
{  ///////////////////get users coupon
$sql_vis_check="select * from redeems where page_id='$page_id' and visitor_id='$visitor_id' order by time DESC limit 1";
$res_vis_check=mysqli_query($con,$sql_vis_check);
if(mysqli_num_rows($res_vis_check) > 0)
{
//////////////user has redeemed coupon
//if($refresh_rate > 0)
{  //////////////if refresh rate is set
    $row_vis_check=mysqli_fetch_assoc($res_vis_check);
    /////////$now_stamp=old coupon time
     $now_stamp=time();
	 $refresh_rate=(int)$refresh_rate;
	 
                         $final_stamp=strtotime($row_vis_check['time']." +$refresh_rate days");
                         if($now_stamp > $final_stamp){
                         /////////////user can  get new coupon (refresh days passed)
                         if($row['redeem_once'] == "no")
    { $coupon=uniqid();
           $sql="insert into redeems (visitor_id,ip,city,page_id,coupon) values('$visitor_id','$ip','$city','$page_id','$coupon')"; 
      if(mysqli_query($con,$sql))
      echo '{"msg": "updated","url": "coupon.php?page_id='.$page_id.'&coupon_id='.$coupon.'&camp_id='.$camp_id.'"}';
      else
      echo '{"msg":"error","msg_txt":"refresh days passerd user can get token(refresh period set)--insert error"}';
    }
      ////////////////////////////////////////////end user coupon    
                         }
                         else{
                             /////////////////////refresh days not passed (can not get coupon)
                             echo '{"msg":"error","visitor_id" : "'.$visitor_id."-----".$now_stamp.$row_vis_check['time']."-------".$final_stamp.'","msg_text":"refresh days not passed (can not get coupon)"}';
                         }  
}  
}
else{
 /////////////////////////already visited  but not  redeemed coupon
     $coupon=uniqid();
           $sql="insert into redeems (visitor_id,ip,city,page_id,coupon) values('$visitor_id','$ip','$city','$page_id','$coupon')"; 
      if(mysqli_query($con,$sql))
      echo '{"msg": "updated","url": "coupon.php?page_id='.$page_id.'&coupon_id='.$coupon.'&camp_id='.$camp_id.'"}';
      else
      echo '{"msg":"error" ,"msg_text":"already visited but not redeemed-error in insert"}';
      ////////////////////////////////////////////end user coupon 
}    
}
else{
   ////////////////////////new user couopon 
     $coupon=uniqid();
           $sql="insert into redeems (visitor_id,ip,city,page_id,coupon) values('$visitor_id','$ip','$city','$page_id','$coupon')"; 
      if(mysqli_query($con,$sql))
      echo '{"msg": "updated","url": "coupon.php?page_id='.$page_id.'&coupon_id='.$coupon.'&camp_id='.$camp_id.'"}';
      else
      echo '{"msg":"error" ,"new user error in insert"}';
      ////////////////////////////////////////////end new user coupon
} 
    }
    else
    {
      //////////////////////////no coupons avalilabe (limit reached)  
       echo '{"msg":"error","msg_text":"no coupon available'.$row_coup['total_redeems'].'--------'.$total_coupons.'"}';
    }
       }
    };break;
    /////////////////////end coupon redeemed///////////////////
    case 'delete_page':{
        if(isset($_POST['id'])) {
    $id=$_POST['id'];
$sql="select page_key from pages where id='$id'";
$res=mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($res);
$page_key=$row['page_key'];
$sql_img="select * from pages_data where page_key='$page_key'";
$res_img=mysqli_query($con,$sql_img);
if(mysqli_num_rows($res_img)>0)
{
 while($row_img=mysqli_fetch_assoc($res_img)){
  if($row_img['type'] == "image")
  {
   if(file_exists("uploaded_images/$row_img[name]"))
   unlink("uploaded_images/$row_img[name]");
     if(file_exists("uploaded_images/thumbs/$row_img[name]"))
   unlink("uploaded_images/thumbs/$row_img[name]");   
  }
  else if($row['type'] == "video"){
        if(file_exists("uploaded_videos/$row_img[name]"))
   unlink("uploaded_videos/$row_img[name]");
  }   
 }   
$sql_del="delete from pages_data where page_key='$page_key'";
mysqli_query($con,$sql_del);   
}
/////////////////delete page
$sql_p="delete from pages where id='$id'";
if(mysqli_query($con,$sql_p))

//////////////////////delete twitter data
$sqldt="delete from twitter_data where page_id='$id'";
mysqli_query($con,$sqldt);
echo 'deleted';
        }
    };break;
//////////////////////end delete page/////////////////////
      case 'visitor_data':{
          $page_id=$_POST['page_id'];
          $tab_id=$_POST['tab_id'];
          if($page_id != "")
         { 
          if($tab_id =='first_tab')   
         {    $sql="select ip,city,hits,time from visitors where page_id='$page_id'";
          $res=mysqli_query($con,$sql) or die(mysqli_error($con));
          $ar="";
          while($row=mysqli_fetch_assoc($res)){
            $arr['ip']=$row['ip'];  
            $arr['city']=$row['city'];  
            $arr['time']=$row['time'];  
            $arr['hits']=$row['hits'];  
            
            $ar[]=$arr;  
          
          }
         }
         else if($tab_id='sec_tab'){
             $sql="select  coupon,ip,city,time from redeems where page_id='$page_id'";
          $res=mysqli_query($con,$sql);
          $ar="";
          while($row=mysqli_fetch_assoc($res)){
            $arr['ip']=$row['ip'];  
            $arr['city']=$row['city'];  
            $arr['coupon']=$row['coupon'];  
            $arr['time']=$row['time'];  
            
            $ar[]=$arr;  
          
          }
         }
          echo json_encode($ar);
         }  
      };break;
      //////////////////////end viisitor data(reports tab)
	  
	  //////////////////////download data(reports tab)
	  case "download_report";{
		  
		  $page_id=$_POST['page_id'];
		  $camp_name = $_POST['camp_id'];
		  
		  //$sql="select pg.page_title, re.coupon,re.ip,re.city,re.time from pages pd, redeems re where pg.id='$page_id' and re.page_id='$page_id'";
		  $sql="select  coupon,ip,city,time from redeems where page_id='$page_id'";
		  $sql2 = "select page_title from pages where id='$page_id'";
		  $qry2 =  mysqli_query($con,$sql2) or die(mysqli_error($con));
		  $row2 = mysqli_fetch_assoc($qry2);
		  $qry = mysqli_query($con,$sql) or die(mysqli_error($con));
		  if($qry and mysqli_num_rows($qry) > 0)
		  {
		 	$filename = 'Redeem_Report.csv';
			$fp = fopen($filename, "w+");
			$line = "";
			$comma = "";
			$line .= $comma . '"Campain","Page Title","IP","City","Coupon","Time"';
			$comma = ",";
			$line .= "\n";
			fputs($fp, $line);	
			$line = "";
			$comma = "";
			
			while($row = mysqli_fetch_assoc($qry))
			{
				$line = "";
				$comma = "";
				$line .= $comma . '"' . $camp_name . '","'. $row2['page_title'] . '","'. $row['ip'] . '","'.$row['city'].'","'.$row['coupon'].'","'.$row['time'].'"';
				$comma = ",";
				$line .= "\n";
				fputs($fp, $line);
							}
				echo $filename;
		  }
		  else
			{
				echo '0';
			}
		 
		  };break;
	  
	  
	  
	  
	  
	  
	 //////////////////////End download data(reports tab)
      case "update_dashboard":{
          $li_json=json_encode($_POST['li_json']);
    $sql="update app_settings set dashboard='$li_json' where created_user='$_SESSION[admin_id]'";
    if(mysqli_query($con,$sql) or die(mysqli_error($con)))
    echo 'updated';
    else 
    echo 'error';
      };break;
      case "chart_redeems":{
               $pages=$_POST['pages'];
         $st_date=$_POST['st_date'];
         $ed_date=$_POST['ed_date'];
		   $arr_redeems[]=ARRAY("Page", "Redeems", "Out of");
		  if(is_array($pages))
{$page_ids=join(",",$pages);
       include_once("bc_date.php");   
    ////////////////////////redeems////////
  $sql="SELECT id,page_key,short_url,lng,lat,redeem_limit,page_title,refresh_rate,redeem_page_id,redeem_limit,(select count(id) from redeems where page_id=pages.id and date(time) >='$st_date' and date(time)<= '$ed_date')as total_coupons FROM pages where created_user='$_SESSION[admin_id]' and id in($page_ids) having redeem_limit>0";
  $res=mysqli_query($con,$sql) or die(mysqli_error($con));

  while($row=mysqli_fetch_assoc($res))
  { 
   $arr_data=""; 
       $arr_data[]=$row['page_title'];
       $arr_data[]= (int)$row['total_coupons'];
       $arr_data[]=(int)$row['redeem_limit'];
       $arr_redeems[]=$arr_data; 

  }  
  }
echo json_encode($arr_redeems);
          
      };break;
      case "test":{
    
echo json_encode($_REQUEST);
      };break;
      case "chart_visitor":{
            $pages=$_POST['pages'];
         $st_date=$_POST['st_date'];
         $ed_date=$_POST['ed_date'];
		   $arr_vis[]=ARRAY('Page', 'Hits', 'Visitors');
		 if(is_array($pages))
{$page_ids=join(",",$pages);
       include_once("bc_date.php"); 
            ////////////////////////visitors(pages)///////////
    $sql="SELECT visitors.id,page_id,page_title,count(visitors.id) as visits,sum(hits) as viewss FROM visitors left join pages on(pages.id=visitors.page_id) where pages.created_user='$_SESSION[admin_id]' and pages.id in($page_ids) and date(visitors.time) >= '$st_date' and date(visitors.time) <='$ed_date' group by page_id";
  $res=mysqli_query($con,$sql) or die(mysqli_error($con));


  if(mysqli_num_rows($res)>0)
{while($row=mysqli_fetch_assoc($res)){
    $arr_data="";
$arr_data[]=$row['page_title'];
$arr_data[]=(int)$row['viewss'];
$arr_data[]=(int) $row['visits']; 
$arr_vis[]=$arr_data;    
}}
}
echo json_encode($arr_vis);
      };break;
      case "chart_camps":{
                  $pages=$_POST['pages'];
         $st_date=$_POST['st_date'];
         $ed_date=$_POST['ed_date'];
		  if(is_array($pages))
{$page_ids=join(",",$pages);
       include_once("bc_date.php");
            //////////////////campign stats
$arr_camps[]=ARRAY('Page','Hits','Visitors','Facebook','Twitter','Email','SMS');
   $sql="SELECT * from campaign  WHERE created_user ='$_SESSION[admin_id]' and id in ($page_ids)";
  $res=mysqli_query($con,$sql) or die(mysqli_error($con));
  $sno=1;
  $arr_campsall="";
  if(mysqli_num_rows($res)>0)
{while($row=mysqli_fetch_assoc($res)){
  $statData = getHistsandVisitors($row['id'],$st_date,$ed_date);
  $socialstats=getsocialstats($row['id']);
 $arr_camps[]=ARRAY($row['title'],(int)$statData['hits'],(int)$statData['visitors'],(int)$socialstats['facebook'],(int)$socialstats['twitter'],(int)$socialstats['email'],(int)$socialstats['sms']); 
    
}}
echo json_encode($arr_camps);
}
else {
echo '{"response": "no"}';
}
      };break;
      case "note_stats":{
      $icon=$_POST['icon'];
      $visitor_id=$_POST['visitor_id'];
      $page_id=$_POST['page_id'];
$sql="update visitors set $icon=$icon+1 where page_id='$page_id' and visitor_id='$visitor_id'";
mysqli_query($con,$sql) or die(mysqli_error($con));
      };break;	
       case "note_page_stats":{
	  $visitor_id=trim($_POST['visitor_id']);
	  $page_id=trim($_POST['page_id']);
      if($visitor_id == "" || $page_id == "")
      die('error');
       $sql_vis="update visitors set hits=hits+1,time=Now() where visitor_id='$visitor_id' and page_id='$page_id'";
$res=mysqli_query($con,$sql_vis) or die(mysqli_error($con));
if(mysql_affected_rows() ==0 )
{
$ip=$_SERVER['REMOTE_ADDR'];
/*include_once('ip2locationlite.class.php');
 $ipLite = new ip2location_lite;
 $ipLite->setKey('e3adfd70a4d2b97f5e03d32cdaf01a3be5fdd9114e45d486e26b8ba4102368bc');
 
 $get_location = $ipLite->getCity($ip);
$user_city=$get_location['cityName'];*/
$location = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
$user_city=$location['geoplugin_city'];

 $sql_vis="insert into visitors(visitor_id,page_id,ip,city,hits,time) values('$visitor_id','$page_id','$ip','$user_city','1',Now())";
 $res=mysqli_query($con,$sql_vis);     
}
echo 'ok';
	  };break;
	  case "search_coupon":{
	  if($_POST['coupon'] !=""){
$coupon=$_POST['coupon'];
$result_found="no";
$sql="select ip,city,time,page_id,visitor_id from redeems where coupon='$coupon'";
$res=mysqli_query($con,$sql);
if(mysqli_num_rows($res)>0)
{
$row=mysqli_fetch_assoc($res);
$ip=$row['ip'];
$time=$row['time'];
$city=$row['city'];
$page_id=$row['page_id'];
$visitor_id=$row['visitor_id'];
/////////////no of hits by user
$sql_hits="select id from visitors where visitor_id='$visitor_id' and page_id='$page_id'";
$res_hits=mysqli_query($con,$sql_hits);
$hits=mysqli_num_rows($res_hits);
//////////////////find title of page
$sql_page="select page_title from pages where id='$page_id'";
$res_page=mysqli_query($con,$sql_page) or die(mysqli_error($con));
$row_page=mysqli_fetch_assoc($res_page);
///print_r($row_page);
$page_title=$row_page['page_title'];
//////////////////////find campaign
$sql1="select group_id from camp_group_pages where page_id='$page_id' limit 1";
$res1=mysqli_query($con,$sql1) or die (mysqli_error($con));
$row1=mysqli_fetch_assoc($res1);
$camp_group_id=$row1['group_id']; //////////////group id
$sql2="select camp_id from camp_groups where id='$camp_group_id'";
$res2=mysqli_query($con,$sql2) or die(mysqli_error($con));
$row2=mysqli_fetch_assoc($res2);
$camp_id=$row2['camp_id']; //////////////campaign id
$sql3="select title from campaign where id='$camp_id'";
$res3=mysqli_query($con,$sql3) or die(mysqli_error($con));
$row3=mysqli_fetch_assoc($res3);
$camp_title=$row3['title'];
$result_found="yes";
}
else{
$result_found="no";
}

}else{
$result_found="no";
}
$arr=ARRAY("result" =>$result_found,"camp_title"=>$camp_title,"page_title"=>$page_title,"ip"=>$ip,"city"=>$city,"hits"=>$hits,"time"=>$time);
echo json_encode($arr);

	  };break;
      
  case"get_cc_list":{
      include_once("cc_class.php");
       $user = $_POST['user'];
    $pass = $_POST['pass'];
     $api_key = $_POST['api_key'];
    //echo $user.' - '.$pass.' - '.$api_key;
    //die();
    $ccListOBJ = new CC_List($user,$pass,$api_key);
    $allLists = $ccListOBJ->getLists();
    if(count($allLists)>0)
   { echo '<select name="cc_list" class="text_feild_cam_select">';
    foreach ($allLists as $k=>$item) 
    {
        echo '<option value="'.$item['id'].'">'.$item['title'].'</option>';
    }
  echo "</select>";  
  }
  else echo "error";
  } break; /*
  case"get_campaigns":{
$email=$_POST['email'];
$pass=$_POST['pass'];
$url=$_POST['url'];
 $xml=wbr_get_campaign_curl($email,$pass,$url);
 if(count($xml)>0)
{echo "<select name='user_campeigns' class='text_feild_cam_select'>";
foreach($xml as $key=> $val){
echo "<option value='$val->Keyword' label='$val->ClientPhoneNumber'>$val->Keyword</option>";
}
echo "</select>";

}else
echo "error";
  }  break;*/
  case"get_campaigns":{
       $sql_campaign = "select cg.id,cg.group_type, cg.title, cg.keywords,cpn.phone_number from client_groups cg, client_phone_numbers cpn where cg.client_id = '".$_SESSION['admin_id']."' and cg.phone_number_id=cpn.id";
        $exc_campaign = mysqli_query($con,$sql_campaign) or die(mysqli_error($con));
        $camps=array();
         while($row_campaign = mysqli_fetch_assoc($exc_campaign))
        {
			$temp=array();
			$temp['id']=$row_campaign['id'];    
			$temp['title']=$row_campaign['title'];
			$group_type=$row_campaign['group_type']; 
			$temp['phone_number']=$row_campaign['phone_number'];
			$keyword = trim($row_campaign['keywords'],',');
				//$keyword = explode(',',$keyword);
			if($group_type == '3')
			{
				$sql_pk = "SELECT `prospector_kw` FROM `prospector_keywords` WHERE `group_id` ='".$row_campaign['id']."' limit 1";
				$exe_pk = mysqli_query($con,$sql_pk);
				$row_pk = mysqli_fetch_assoc($exe_pk);
				$keyword = $row_pk['prospector_kw'];
			}
			elseif($group_type == '4')
			{
				$date = date("Y-m-d");
				$sql_pk = "SELECT `keyword` FROM `day_keywords` WHERE `group_id` ='".$row_campaign['id']."' and (day <= '".$date ."' and end_day >= '".$date."') limit 1";
				$exe_pk = mysqli_query($con,$sql_pk);
				$row_pk = mysqli_fetch_assoc($exe_pk);
				$keyword = $row_pk['keyword'];
			}
			
			$temp['keyword']=$keyword;
			$camps[]=$temp;    
        }   
/////////////
 if(count($camps)>0)
{echo "<select name='user_campeigns' class='text_feild_cam_select'>";
foreach($camps as $key=> $val){
echo "<option value='$val[keyword]' title='$val[phone_number]'>$val[title]</option>";
}
echo "</select>";

}else
echo "error";   
  };break;
   case"check_wbsms_connection":{
   $email=$_POST['email'];
$pass=$_POST['pass'];
$url=$_POST['url'];
    $xml=wbr_get_campaign_curl($email,$pass,$url);
 if(is_object($xml))
{
echo "Connection to WBSMS is ok";
}else
echo "Could not connect to WBSMS";   
  };break;
  case"suscribe":{
     $url=$_POST['suscribe'];
 // echo $url;
       suscribe($_POST['suscribe'],$_POST['casee']);
//  echo "touseeeffeffefe";
  }   break;
  case "get_updated_fonts_list":{
      $sort=$_POST['sort'];
       $fonts_json=read_file("https://www.googleapis.com/webfonts/v1/webfonts?sort=$sort&key=AIzaSyBcuUSqWQdJNliJNiMRqwdzsC09yMwTk6U");
    $obj=json_decode($fonts_json,true);
   $arr=array();
foreach($obj['items'] as $key => $val){
 $arr["family_".$key]=$val['family'];
}
echo json_encode($arr);
  };break;
  case "keywords_csvtojson": {
      sleep(2);
  $arr="";
  $ext=pathinfo($_FILES['keyword_csv']['name']);
  if($ext['extension'] == "csv")
{if (($handle = fopen($_FILES['keyword_csv']['tmp_name'], "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
$temp="";
$temp['date']=$data[0];
$temp['keyword']=$data[1];
$arr[]=$temp;
    }
    fclose($handle);
}
$ress['error']="";
$ress['res']=$arr;
echo json_encode($ress);
///$_SESSION['json']=json_encode($arr);
}
else
{
echo '{"error": "Invalid file."}';    
}     
  };break;
  case "check_loyalty":{
        $visitor_id=$_POST['visitor_id'];
      $page_id=$_POST['page_id'];
      $keyword=$_POST['keyword'];
      $sql="select * from pages where id='$page_id'";
      $res=mysqli_query($con,$sql);
      $row=mysqli_fetch_assoc($res);
      $json=$row['json'];
      $obj=json_decode($json,true); 
   if($obj === NULL){
   $str=str_replace('"{','{',parse(DBoutf($json))); 
$json=str_replace('}"','}',$str);
$obj=json_decode($json,true);
   }
 
      $codes_required=0;
      $arr_keywords="";
       foreach($obj as $key =>$val)
{
$widget_arr=explode("_",$key);
$widget=$widget_arr[0];
if($widget == "loyalty" && $obj[$key]['check']=="images/enable.png")
{ 
       
 $codes_required=$obj[$key]['codes_required'];   
 $arr_keywords=$obj[$key]['keywords_data'];   
}
}

////////todays keyword
   $time=date('Y-m-d');  
  
$todays_keyword=array();
foreach($arr_keywords as $kk => $vv){
 $todays_keyword[]=$vv[$time];   
}

if(count($todays_keyword) == 0 || $keyword =="")
die('{"msg":"invalid","todays_keyword":"'.$todays_keyword."----".$time.'","arr": '.json_encode($arr_keywords).'}');  
if(in_array($keyword,$todays_keyword)===false)
die('{"msg":"invalid","todays_keywordii":"'.$todays_keyword.'"}');
//////////if key word is valid then proceed
         $sql1="select time,keyword from loyalty where visitor_id='$visitor_id' and page_id='$page_id' and time='$time'";
          $res1=mysqli_query($con,$sql1) or die(mysqli_error($con));
         //user has not entered same keyword
      if(mysqli_num_rows($res1)==0)
     { $sql2="insert into loyalty (visitor_id,page_id,keyword,winner,time) values('$visitor_id','$page_id','$keyword',0,'$time')";
       mysqli_query($con,$sql2) or die(mysqli_error($con)); 
     }
	 $sqlrfind = "select id from loyalty where winner=1 and visitor_id='$visitor_id' and page_id='$page_id'";
	$resrfind = mysqli_query($con,$sqlrfind);
	if(mysqli_num_rows($resrfind)==0)
     $sql="select count(id) as codes_entered,time from loyalty where visitor_id='$visitor_id' and page_id='$page_id'";
	  else
	 $sql = "select count(id) as codes_entered,time from loyalty where visitor_id='$visitor_id' and page_id='$page_id' and id > (select id from loyalty where visitor_id='$visitor_id' and page_id='$page_id' and winner=1 order by id desc limit 1)";
      $res=mysqli_query($con,$sql);
      $row=mysqli_fetch_assoc($res);
      $codes_entered=$row['codes_entered'];
     ///////////////////////////
     if($codes_entered >0)
      $limit_check=$codes_entered%$codes_required;
      else
      $limit_check=1;
   //   if($codes_required == 1)
    //  $limit_check=0;
      /////////////
      if($limit_check ==0)
      {
    $sql="select id,winner from loyalty where visitor_id='$visitor_id' and page_id='$page_id' order by id desc limit 1";
      $res=mysqli_query($con,$sql);
      $row=mysqli_fetch_assoc($res);
     $winner=$row['winner'];
      $last_id=$row['id'];
          if($winner == 0)
       {   $sql="update loyalty set winner=1 where id='$last_id'";
          mysqli_query($con,$sql) or die(mysqli_error($con));
          die('{"msg":"win"}');
       }
       else
       die('{"msg":"invalid","time":"'.$time.'"}');
      } 
      die('{"msg":"looser","check": "'.$limit_check.'","codes_required": "'.$codes_required.'","codes_entered":"'.$codes_entered.'"}');
            
  };break;
  case "reset_redeems":{
  $page_id=$_POST['page_id'];
  $sql="select * from redeems where page_id='$page_id'";
  $res=mysqli_query($con,$sql);
  while($row=mysqli_fetch_assoc($res)){
  $sql1="insert into redeems_archive(visitor_id,ip,city,page_id,coupon,time) values('$row[visitor_id]','$row[ip]','$row[city]','$row[page_id]','$row[coupon]','$row[time]')";
  mysqli_query($con,$sql1);    
  }
  $sql="delete from redeems where page_id='$page_id'";
  $res=mysqli_query($con,$sql);
  if($res)
  echo '{"msg":"ok"}';
  else echo '{"msg":"error"}';
  };break;
  case"save_settings":{
      $address=DBin($_REQUEST['address']);
      $lat=$_REQUEST['lat'];
      $lon=$_REQUEST['lon'];
      $time_zone=DBin($_REQUEST['time_zone']);
  $sql="update client_information set address_coupon='$address',lat='$lat',lon='$lon',time_zone_coupon='$time_zone' where id='$_SESSION[admin_id]'";
  $res=mysqli_query($con,$sql) or die(mysqli_error($con));
  if($res)
  echo "updated";
  else echo "error";    
  };break;
}

/////////////////////////////functions
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
    function parse($text) {
    // Damn pesky carriage returns...
    $text = str_replace("\r\n", "\n", $text);
    $text = str_replace("\r", "\n", $text);
    $text = str_replace("\\", "", $text);
  $text=preg_replace('/[^(\x20-\x7F)]*/','', $text);
    // JSON requires new line characters be escaped
    $text = str_replace("\n", " ", $text);
    return $text;
}

function get_user_token()
{
	$sql_noti = mysqli_query($con,"select bitly_token from client_information where id = '".$_SESSION['admin_id']."'")or die(mysqli_error($con));
	$get_noti = mysqli_fetch_assoc($sql_noti);
	$bitly_token = $get_noti['bitly_token'];
	if($bitly_token == '')
	{
		$sql_noti1 = mysqli_query($con,"select bitly_token from client_information where type = '1'")or die(mysqli_error($con));
		$get_noti1 = mysqli_fetch_assoc($sql_noti1);
		$bitly_token = $get_noti1['bitly_token'];
		if($bitly_token == '')
		{
			$bitly_token = '4da82d8a8c46585f1eae3c36be7ca310532a466c';
		}
	}
	return $bitly_token;
}
function get_short_url($url,$token){
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
  //$token="c4886973c677031cd35676b85381b1e7fd766b45";
$url=read_file($bitly.$token."&longUrl=".urlencode($url));
$url=json_decode($url,true);
return $url['data']['url'];
 
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
function rename_copy($file,$path){
    global $page_key;
   $name=$page_key;
if(file_exists("$path/$file"))
{
    
     $img_name=substr($file,strpos($file,"_"));
     $orig=$name.$img_name;
        copy("$path/$file","$path/$orig"); 
return $name.$img_name; 
 
}
else
{
    return $file;


}
}
function getHistsandVisitors($campaignID,$st_date,$ed_date)
{
    $pageArray = '';
    $sql="SELECT distinct cgp.page_id as pageID FROM camp_groups cg, camp_group_pages cgp where cgp.group_id=cg.id and cg.camp_id='$campaignID'";
    $res = mysqli_query($con,$sql);
    while($row = mysqli_fetch_assoc($res))
    {
        $pageArray[] = $row['pageID'];
    }
    $padIds = join(',',$pageArray);
   $sqs="SELECT count(v.id) as visitors, sum(v.hits) as hits, v.page_id FROM `visitors` v where page_id in(".$padIds.") and time>='$st_date' and time<= '$ed_date'";
    $ress = mysqli_query($con,$sqs) or die(mysqli_error($con));
    
    if(mysqli_num_rows($ress)>0)
   {
   $row = mysqli_fetch_assoc($ress);
    $returnVal['hits'] = $row['hits'];
    $returnVal['visitors'] = $row['visitors'];
    return $returnVal;
   }
}
function getsocialstats($campaignID)
{
	$pageArray = '';
	$sql="SELECT distinct cgp.page_id as pageID FROM camp_groups cg, camp_group_pages cgp where cgp.group_id=cg.id and cg.camp_id='$campaignID'";
	$res = mysqli_query($con,$sql);
	while($row = mysqli_fetch_assoc($res))
	{
		$pageArray[] = $row['pageID'];
	}
	$padIds = join(',',$pageArray);
	$sqs="SELECT sum(v.facebook) as facebook, sum(v.twitter) as twitter, sum(v.email) as email, sum(v.sms) as sms FROM `visitors` v where page_id in(".$padIds.");";
	$ress = mysqli_query($con,$sqs);
	$row = mysqli_fetch_assoc($ress);
	$returnVal['facebook'] = $row['facebook'];
	$returnVal['twitter'] = $row['twitter'];
	$returnVal['email'] = $row['email'];
	$returnVal['sms'] = $row['sms'];
	return $returnVal;
}
      function wbr_get_campaign_curl($email, $password, $url)
{   
    if(strpos($url,".php")!=false)
    $url=substr($url,0,strrpos($url,"/"));
     $url=trim($url,"/");
    $url = $url."/handle_curl_request.php";
    $data = array('email_address'=>$email,'password'=>$password,'action'=>'get_campaigns');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');
    $request = curl_exec($ch);
   $xml = @simplexml_load_string($request);
    return $xml;
    curl_close($ch);
}
function suscribe($data,$case){
   global $page_id;
   global $page_json_arr;
   global $page_json;
    $_SESSION['data']=json_encode($data);
    $_SESSION['json']=$page_json_arr[$data['widget_no']];
    $_SESSION['case']=$case;
    $_SESSION['page_id']=$page_id;
   // $_SESSION['case']=$case;
   

  switch($case){
      case "aweber":{
          $form_action=$page_json_arr[$data['widget_no']]['force_optin']['form_action'];
          $url=$form_action."&name=".$data['name']."&email=".$data['email'];
          read_file($url);
      };break;     
       case "mailchimp":{
          $form_action=$page_json_arr[$data['widget_no']]['force_optin']['form_action'];
          $url=$form_action."&EMAIL=".$data['email'];
          $_SESSION['url']=$url;
          read_file($url);
      };break;  
	  
	  case "sendreach":{

          $form_action=$page_json_arr[$data['widget_no']]['force_optin']['form_action'];

          $url=$form_action."&name=".$data['name']."&email=".$data['email'];
		  

          read_file($url);

      };break;     
   
    
      case "icontact":{
          $form_action=$page_json_arr[$data['widget_no']]['force_optin']['form_action'];
          $url=$form_action."&fields_fname=".$data['name']."&fields_email=".$data['email'];
          read_file($url);
      };break; 
	  
	  case "getresponse":

	  	$form_action=$page_json_arr[$data['widget_no']]['force_optin']['form_action'];

			if($name == '')

			{

				$name = 'loyalty';

			}

			$url = $form_action."&email=".$data['email']."&name=".$data['name'];

			read_file($url);

		break;
     
case "const_contact":
{
    include_once("cc_class.php");
    $user=$page_json_arr[$data['widget_no']]['force_optin']['email_const_contact'];
    $pass=$page_json_arr[$data['widget_no']]['force_optin']['pass_const_contact'];
    $api_key=$page_json_arr[$data['widget_no']]['force_optin']['apikey_const_contact'];
    $list=$page_json_arr[$data['widget_no']]['force_optin']['list_const_contact_id'];
            $ccContactOBJ = new CC_Contact($user,$pass,$api_key);
                            
                            $postFields = array();
                            $postFields["email_address"] = $data['email'];
                            $postFields["first_name"] = $data['name'];
                            $postFields["last_name"] = '';
                            $postFields["middle_name"] = '';
                            $postFields["home_number"] = '';
                            $postFields["address_line_1"] = '';
                            $postFields["address_line_2"] = '';
                            $postFields["address_line_3"] = '';
                            $postFields["city_name"] = '';
                            $postFields["state_code"] = '';
                            $postFields["state_name"] = '';
                            $postFields["country_code"] = '';
                            $postFields["zip_code"] = '';
                            $postFields["sub_zip_code"] = '';
                            
                            $postFields["mail_type"] = "HTML";
                            $postFields["lists"] = array($list);
                        
                            $check_email = $ccContactOBJ->subscriberExists($data['email']);
                            if($check_email)
                            {
                                $postFields["status"] = 'Active';
                                $contact_id = $check_email;
                                $contactXML = $ccContactOBJ->createContactXML($contact_id,$postFields);
                                $updateContact = $ccContactOBJ->editSubscriber($contact_id, $contactXML);
                                if($updateContact)
                                {
                                  //  echo 'udpated'.$contactXML."-----".$updateContact;
                                }
                                else
                                {
                                ///    echo 'not updated';
                                }
                            }
                            else
                            {
                                $contactXML = $ccContactOBJ->createContactXML(null,$postFields);
                                if (!$ccContactOBJ->addSubscriber($contactXML))
                                {
                                   /// echo 'not created'.$contactXML;
                                }
                                else
                                {
                                  //  echo 'created';
                                }
                            }    
};break;
case "sms":{
  if(get_magic_quotes_gpc())
  $url=stripcslashes($url);
   $sms_json=json_decode($url,true);
   
   
   $sqlURL = "select sms_url from twilio_settings";
    $resURL = mysqli_query($con,$sqlURL);
    $rowURL = mysqli_fetch_assoc($resURL);
    $url = substr($rowURL['sms_url'],0,strripos($rowURL['sms_url'],'/'));
 
   $url_sms=$url."/sms_controlling.php?Body=".$page_json_arr[$data['widget_no']]['force_optin']['user_campaign_name']."&SmsSid=1234567890&SmsMessageSid=1234567890&From=".urlencode($data['phone'])."&To=".urlencode($page_json_arr[$data['widget_no']]['force_optin']['user_campaign_phone'])."&coupon=".$data['name'];
   mail("qadeer@nimblewebsolutions.com","Handle",$url_sms);
   read_file($url_sms);
};break;
  }
}
function read_file($url){
    set_time_limit(0);
$ch = curl_init(str_replace(" ","%20",$url));//Here is the file we are downloading, replace spaces with %20
curl_setopt($ch, CURLOPT_TIMEOUT, 0);
//curl_setopt($ch, CURLOPT_FILE, $fp); // here it sais to curl to just save it
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');
$data = curl_exec($ch);//get curl response
curl_close($ch);
return $data;
}
function get_timezone($userID){
      $TimeZoneValue="";
    $sql = "select time_zone from client_information where id='".$userID."'";
    $res = mysqli_query($con,$sql);
    $row = mysqli_fetch_assoc($res);
    if($row['time_zone'] != '')
    {
         $zoneVal = $row['time_zone'];
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
