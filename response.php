<?php
//$body = print_r($_REQUEST,true);
$date = date('Y-m-d H:i:s');

$x_cust_id = explode("_",$_REQUEST['x_cust_id']);
if(count($x_cust_id)==3)
{


    
    /////// Code Starts Here ////
    
    if($_REQUEST['x_response_code'] == '1' and $_REQUEST['x_response_reason_code'] == '1')
    {
        
        $_REQUEST['custom'] = $_REQUEST['x_cust_id'];
        $_REQUEST['payer_status'] = $_REQUEST['x_response_code'];
        $_REQUEST['payer_email'] = $_REQUEST['x_email'];
        $_REQUEST['txn_id'] = $_REQUEST['x_trans_id'];
        $_REQUEST['payment_status'] = "Completed";
        $_REQUEST['mc_gross'] = $_REQUEST['x_amount'];
        $_REQUEST['item_name'] = $_REQUEST['x_description'];
        $_REQUEST['payment_processor'] = "2";
        
        include_once("credits_notify.php");
    }
}
else
{
    $subscription_id = $_REQUEST['x_subscription_id'];
   
    include_once('database.php');
    include_once("functions.php");
    
    if(trim($subscription_id)==""){
        die();
    }
    
    $selects = "select * from web_user_info where subscription_id = '".$subscription_id."'";
    $q_run = mysqli_query($link,$selects);
	if(mysqli_num_rows($q_run)){
		$web_user_info =  mysqli_fetch_assoc($q_run);
		$parent_user_id = $web_user_info['parent_user_id'];
		
	}else{
		$sql = "select * from usres where subscription_id = '".$subscription_id."'";
		$res= mysqli_query($link,$sql);
		$web_user_info = mysqli_fetch_assoc($res);
		
	}
    
    $appSettings = getAppSettings($parent_user_id,true);
    
    $api_login_id = $appSettings['auth_net_api_login_id'];
    $transaction_key = $appSettings['auth_net_trans_key'];
    include_once('AuthnetARB.class.php');
    
    $subscription = new AuthnetARB($api_login_id, $transaction_key,AuthnetARB::USE_DEVELOPMENT_SERVER);
    $subscription->setParameter('subscrId',$subscription_id);
                $subscription->SubscriptionStatus();
                if ($subscription->isSuccessful())
                {
                    $substat    =   $subscription->getSubscrStatus().$subscription_id; 
                    //@mail("irfan@ranksol.com","Authorize.net live Email subscription status ".$date,$substat); 
                    if($subscription->getSubscrStatus() == "suspended"){
                      if(isset($subscription_id) && $subscription_id!= ""){
                            mysqli_query($link,"update users set status='4', authorize_status = 'suspended' where subscription_id = '".$subscription_id."'");    
                        }   
                    }else if($subscription->getSubscrStatus() == "terminated" || $subscription->getSubscrStatus() == "cancelled"){
                      if(isset($subscription_id) && $subscription_id!= ""){
                            mysqli_query($link,"update users set status='3', authorize_status = 'cancelled' where subscription_id = '".$subscription_id."'");    
                        }   
                    }
                    //@mail("irfan@ranksol.com","Authorize.net live subscriber status Email ".$date,$substat);
                }else{
                    $subgetresp =   $subscription->getResponse().$subscription_id;    
                    if(isset($subscription_id) && $subscription_id!= ""){
                            mysqli_query($link,"update users set status='4', authorize_status = '".$subscription->getResponse()."' where subscription_id = '".$subscription_id."'");    
                        }  
                    //@mail("irfan@ranksol.com","Authorize.net live subscriber getresponse Email ".$date,$subgetresp);
                }
    if(isset($_REQUEST['x_response_code']) && $_REQUEST['x_response_code']==1){
    $jason = json_encode($_REQUEST);
        
        $_REQUEST['custom'] = $web_user_info['id'];
        $_REQUEST['payer_status'] = $_REQUEST['x_response_code'];
        $_REQUEST['payer_email'] = $web_user_info['email'];
        $_REQUEST['txn_id'] = $_REQUEST['x_trans_id'];
        $_REQUEST['payment_status'] = $_REQUEST['x_response_code'];
        $_REQUEST['payment_gross'] = $_REQUEST['x_amount'];
        $_REQUEST['item_name'] = $_REQUEST['x_description'];
        $_REQUEST['txn_type'] = 'subscr_payment';
        $_REQUEST['payment_processor'] = "2";
        
        include_once("notify.php");
        
    }
    else if((isset($_REQUEST['x_response_code']) && $_REQUEST['x_response_code']==2) && (isset($_REQUEST['x_response_reason_code']) && $_REQUEST['x_response_reason_code']==3)){
        if(isset($_REQUEST['x_subscription_id']) && $_REQUEST['x_subscription_id']!= ""){
           // @mail("irfan@ranksol.com","Authorize.net live else Email ".$date,$body);
            mysqli_query($link,"update users set status='4' , authorize_status = 'suspended'  where subscription_id = '".$_REQUEST['x_subscription_id']."'");    
			
			$subject = $appSettings['failed_payment_email_subject'];
			$to		 = $web_user_info['email'];
			$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
			$msg	 = $appSettings['failed_payment_email'];
			$FullName= 'Admin';
			sendEmail($subject,$to,$from,$msg,$FullName);
			
			// Admin notification
			$appSettings = getAppSettings($userID,true);
			$subject = $appSettings['payment_noti_subject'];
			$to		 = $appSettings['admin_email'];
			$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
			$msg	 = str_replace('%email%',$web_user_info['email'],$appSettings['payment_noti_email'].'. Payment status is '.$_REQUEST['x_response_reason_text']);
			$FullName= 'Admin';
			sendEmail($subject,$to,$from,$msg,$FullName);
			
        }
    }
}

function LogErrors($data)
{
	$myFile = "thanku.txt";
	$fh = fopen($myFile, 'a') or die("can't open file");
	fwrite($fh, $data);
	fclose($fh);
}


?>
