<?php
	include_once("database.php");
	include_once("functions.php");
	if($_REQUEST['txn_type']=='subscr_payment'){
		$webUserID = $_REQUEST['custom'];
		$sql = "select * from web_user_info where id='".$webUserID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			$sel = "select * from users where email='".$row['email']."'";
			$exe = mysqli_query($link,$sel);
			if(mysqli_num_rows($exe)==0){
				$appSettings = getAppSettings($row['parent_user_id'],true);
				$appUrl		 = getServerUrl();
				$password	 = $row['password'];
				$encryptedPassword = encodePassword($row['password']);
				$ins = "insert into users
(first_name,last_name,email,password,type,parent_user_id,business_name,tcap_ctia,msg_and_data_rate,city,state,zip,address,card_number,cvv,year,month,response,response_code,subscription_id,paypal_subscriber_id)values('".$row['first_name']."','".$row['last_name']."','".$row['email']."','".$encryptedPassword."','2','".$row['parent_user_id']."','".$row['business_name']."','".$row['tcap_ctia']."','".$row['msg_and_data_rate']."','".$row['city']."','".$row['state']."','".$row['zip']."','".$row['address']."','".$row['card_number']."','".$row['cvv']."','".$row['year']."','".$row['month']."','".$row['response']."','".$row['response_code']."','".$row['subscription_id']."','".$_REQUEST['subscr_id']."')";
				$exe = mysqli_query($link,$ins);
				if($exe){
					$userID	= mysqli_insert_id($link);
					mysqli_query($link,"delete from web_user_info where id='".$row['id']."'");
					$client = getTwilioConnection($row['parent_user_id']);
					$account= $client->accounts->create(array(
						"FriendlyName" => $row['email']
					));
					$subAccountSid 	 = $account->sid;
					$subAccountToken = $account->auth_token;
					mysqli_query($link,
					"insert into application_settings(twilio_sid,twilio_token,user_id,user_type)values
					('".$subAccountSid."','".$subAccountToken."','".$userID."','2')");
					$pkgInfo= getPackageInfo($row['pkg_id']);
					$_REQUEST['item_name'] = $pkgInfo['title']." SMS Plan";
					
					$today	= date('Y-m-d H').':00:00';
					$endDate= date('Y-m-d H:i',strtotime('+1 month'.$today));
					$insPkg = "insert into user_package_assignment
								(user_id,pkg_id,start_date,end_date,sms_credits,phone_number_limit,locations,reminders,iso_country,pkg_country)values
								('".$userID."','".$row['pkg_id']."','".$today."','".$endDate."','".$pkgInfo['sms_credits']."','".$pkgInfo['phone_number_limit']."','".$pkgInfo['locations']."','".$pkgInfo['reminders']."','".$pkgInfo['iso_country']."','".$pkgInfo['country']."')";
					mysqli_query($link,$insPkg);
					
					// User notification
					$subject = $appSettings['email_subject'];
					$to		 = $row['email'];
					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
					$msg	 = $appSettings['new_app_user_email'];
					$msg	 = str_replace('%first_name%',$row['first_name'],$msg);
					$msg	 = str_replace('%last_name%',$row['last_name'],$msg);
					$msg	 = str_replace('%login_email%',$row['email'],$msg);
					$msg	 = str_replace('%login_pass%',$password,$msg);
					$msg	 = str_replace('%login_url%',$appUrl,$msg);
					$FullName= 'Admin';
					sendEmail($subject,$to,$from,$msg,$FullName);
					
					// Admin notification
					$subject = $appSettings['email_subject_for_admin_notification'];
					$to		 = $appSettings['admin_email'];
					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
					$msg	 = str_replace('%email%',$row['email'],$appSettings['new_app_user_email_for_admin']);
					$FullName= 'Admin';
					sendEmail($subject,$to,$from,$msg,$FullName);
				}
			}
		}else{ // existing user
			if(isset($_REQUEST['payment_processor']) && $_REQUEST['payment_processor']==2){
				$payment_processor = $_REQUEST['payment_processor'];
				$sql = "select id,email from users where subscription_id='".$_REQUEST['subscription_id']."'";	
			}else{
				$payment_processor = 1;
				$sql = "select id,email from users where paypal_subscriber_id='".$_REQUEST['subscr_id']."'";
			}
			
			$res = mysqli_query($link,$sql);
			if(mysqli_num_rows($res)){
				$row = mysqli_fetch_assoc($res);
				$userID = $row['id'];
				$appSettings = getAppSettings($userID);
				if(($_REQUEST['payment_status']=='Completed') ||  ($_REQUEST['payment_status']=='1')){
					$today	= date('Y-m-d H').':00:00';
					$endDate= date('Y-m-d H:i',strtotime('+1 month'.$today));
					$sqlUp = "update user_package_assignment set end_date='".$endDate."' where user_id='".$userID."'";
					$resUp = mysqli_query($link,$sqlUp);
					$subject = $appSettings['success_payment_email_subject'];
					$to		 = $row['email'];
					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
					$msg	 = $appSettings['success_payment_email'];
					$FullName= 'Admin';
					sendEmail($subject,$to,$from,$msg,$FullName);
					
					// Admin notification
					$appSettings = getAppSettings($userID,true);
					$subject = $appSettings['payment_noti_subject'];
					$to		 = $appSettings['admin_email'];
					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
					$msg	 = str_replace('%email%',$row['email'],$appSettings['payment_noti_email']);
					$FullName= 'Admin';
					sendEmail($subject,$to,$from,$msg,$FullName);
					
				}else{ // Payment failed
					$subject = $appSettings['failed_payment_email_subject'];
					$to		 = $row['email'];
					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
					$msg	 = $appSettings['failed_payment_email'];
					$FullName= 'Admin';
					sendEmail($subject,$to,$from,$msg,$FullName);
					
					// Admin notification
					$appSettings = getAppSettings($userID,true);
					$subject = $appSettings['payment_noti_subject'];
					$to		 = $appSettings['admin_email'];
					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
					$msg	 = str_replace('%email%',$row['email'],$appSettings['payment_noti_email']);
					$FullName= 'Admin';
					sendEmail($subject,$to,$from,$msg.'. Payment status is '.$_REQUEST['payment_status'],$FullName);
				}
			}
		}
        
		mysqli_query($link,
		"insert into payment_history	(business_email,payer_status,payer_email,txn_id,payment_status,gross_payment,product_name,user_id,payment_processor)values('".$_REQUEST['business']."','".$_REQUEST['payer_status']."','".$_REQUEST['payer_email']."','".$_REQUEST['txn_id']."','".$_REQUEST['payment_status']."','".$_REQUEST['payment_gross']."','".$_REQUEST['item_name']."','".$row['parent_user_id']."','".$payment_processor."')");
	}
?>