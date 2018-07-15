<?php	
	include_once('AuthnetARB.class.php');
	include_once('functions.php');
	
	$api_login_id = $appSettings['auth_net_api_login_id'];
	$transaction_key = $appSettings['auth_net_trans_key'];
	global $link;
	$sel = "select * from web_user_info where id='".$webUserID."'";
	$res = mysqli_query($link,$sel);
	if($res and mysqli_num_rows($res)){
		$row = mysqli_fetch_assoc($res);	
	}
	
	$isFreeDays = $pkgInfo['is_free_days'];
	if($isFreeDays=='1'){
		$freeDays = $pkgInfo['free_days'];
		$paymentDate = date("Y-m-d", strtotime("+ ".$freeDays." days"));
	}else{
		$paymentDate = date("Y-m-d", strtotime("+ 1 day"));	
	}
	$year = $row['year'];
	$month = $row['month'];
	//$card_date = $year."-".$month;
	$card_date = $month."-".$year;
	$subscription = new AuthnetARB($api_login_id, $transaction_key,AuthnetARB::USE_DEVELOPMENT_SERVER);
    $subscription->setParameter('amount', str_replace('$','',$pkgPrice));
    /*$subscription->setParameter('refId',$data_package['id']);*/
    $subscription->setParameter('subscrName',$pkgName);
    $subscription->setParameter('cardNumber', $row['card_number']);
    $subscription->setParameter('cardCode', $row['cvv']);
    $subscription->setParameter('expirationDate',$card_date);
    $subscription->setParameter('firstName', $row['first_name']);
    $subscription->setParameter('lastName', $row['last_name']);
    $subscription->setParameter('address', $row['address']);
    $subscription->setParameter('city', $row['city']);
    $subscription->setParameter('state', $row['state']);
    $subscription->setParameter('zip', $row['zip']);
    $subscription->setParameter('email', $row['email']);
    $subscription->setParameter('interval_length', 1);
    //$subscription->setParameter('startDate', date("Y-m-d"));
    $subscription->setParameter('startDate', $paymentDate);
	
	//$subscription->setParameter('trialOccurrences', 1);
    //$subscription->setParameter('trialAmount', 0.00);
	
        
    if($_REQUEST['suspendemail']== "1"){
        $select_subscription  = "select * from users where email = '".$_REQUEST['email']."'";
        $result_subscription  = mysql_query($select_subscription) or die (mysql_error()); 
            if (mysql_num_rows($result_subscription) > 0){
                $subscriptionRow =  mysql_fetch_assoc($result_subscription);
                $suspendsubscription    =   $subscriptionRow['subscription_id'];
            }
            $subscription->setParameter('subscrId',$suspendsubscription);
            $subscription->updateAccount();
            if ($subscription->isSuccessful())
            {
              $subscription_id    =   $subscriptionRow['subscription_id'];  
            }
    }else{
        $subscription->createAccount();
        if($subscription->isSuccessful()){
            // Get the subscription ID
            $subscription_id = $subscription->getSubscriberID();
        }
    }
    
    if($subscription->isSuccessful()){
        $response = $subscription->getResponse();
        $response_code = $subscription->getResponseCode();
        $sels = "update web_user_info set response = '".$response."', response_code = '".$response_code."', subscription_id = '".$subscription_id."' where id='".$webUserID."'";
        $res = mysqli_query($link,$sels);
        $_SESSION['authnet_response'] = 1;
        $_SESSION['authnet_msg'] = "Your Payment is under process, You will get an email wihtin 24 hours with your account login information"; 

		$_REQUEST['response'] = $response;
		$_REQUEST['response_code'] = $response_code;
		$_REQUEST['subscription_id'] = $subscription_id;
		$appSettings = getAppSettings($row['parent_user_id']);
		$appUrl		 = getServerUrl();
		if($isFreeDays=='1'){ // Package with free days
			$password	 = $row['password'];
			$encryptedPassword = encodePassword($row['password']);
			$ins = "insert into users
						(
							first_name,
							last_name,
							email,
							password,
							type,
							parent_user_id,
							business_name,
							tcap_ctia,
							msg_and_data_rate,
							city,
							state,
							zip,
							address,
							card_number,
							cvv,
							year,
							month,
							response,
							response_code,
							subscription_id,
							paypal_subscriber_id
						)
					values
						(
							'".$row['first_name']."',
							'".$row['last_name']."',
							'".$row['email']."',
							'".$encryptedPassword."',
							'2',
							'".$row['parent_user_id']."',
							'".$row['business_name']."',
							'".$row['tcap_ctia']."',
							'".$row['msg_and_data_rate']."',
							'".$row['city']."',
							'".$row['state']."',
							'".$row['zip']."',
							'".$row['address']."',
							'".$row['card_number']."',
							'".$row['cvv']."',
							'".$row['year']."',
							'".$row['month']."',
							'".$row['response']."',
							'".$row['response_code']."',
							'".$row['subscription_id']."',
							'".$_REQUEST['subscr_id']."'
						)";
			$exe = mysqli_query($link,$ins);
			if($exe){
				$userID	= mysqli_insert_id($link);
				mysqli_query($link,"delete from web_user_info where id='".$row['id']."'");
				try{
					$client = getTwilioConnection($row['parent_user_id']);
				}catch(Services_Twilio_RestException $e){
				}
				
				try{
					$account= $client->accounts->create(array(
						"FriendlyName" => $row['email']
					));
					$subAccountSid 	 = $account->sid;
					$subAccountToken = $account->auth_token;
				}catch(Services_Twilio_RestException $e){
				}
				mysqli_query($link,
				"insert into application_settings(twilio_sid,twilio_token,user_id,user_type)values
				('".$subAccountSid."','".$subAccountToken."','".$userID."','2')");
				$pkgInfo= getPackageInfo($row['pkg_id']);
                $_REQUEST['item_name'] = $pkgInfo['title']." SMS Plan";
                
				$today	= date('Y-m-d H').':00:00';
				$endDate= date('Y-m-d H:i',strtotime('+1 month'.$today));
				$insPkg = "insert into user_package_assignment
								(
									user_id,
									pkg_id,
									start_date,
									end_date,
									sms_credits,
									phone_number_limit,
									locations,
									reminders,
									iso_country,
									pkg_country
								)
							values
								(
									'".$userID."',
									'".$row['pkg_id']."',
									'".$today."',
									'".$endDate."',
									'".$pkgInfo['sms_credits']."',
									'".$pkgInfo['phone_number_limit']."',
									'".$pkgInfo['locations']."',
									'".$pkgInfo['reminders']."',
									'".$pkgInfo['iso_country']."',
									'".$pkgInfo['country']."'
								)";
				mysqli_query($link,$insPkg);
				
				$subject = $appSettings['email_subject'];
				$to		 = $row['email'];
				$from	 = 'admin@'.str_replace('www.','',$_SERVER['SERVER_NAME']);
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
				$from	 = 'admin@'.str_replace('www.','',$_SERVER['SERVER_NAME']);
				$msg	 = str_replace('%email%',$row['email'],$appSettings['new_app_user_email_for_admin']);
				$FullName= 'Admin';
				sendEmail($subject,$to,$from,$msg,$FullName);
			}
		}else{ // No free days in package
			$password	 = $row['password'];
			$encryptedPassword = encodePassword($row['password']);
			$ins = "insert into users
						(
							first_name,
							last_name,
							email,
							password,
							type,
							parent_user_id,
							business_name,
							tcap_ctia,
							msg_and_data_rate,
							city,
							state,
							zip,
							address,
							card_number,
							cvv,
							year,
							month,
							response,
							response_code,
							subscription_id,
							paypal_subscriber_id
						)
					values
						(
							'".$row['first_name']."',
							'".$row['last_name']."',
							'".$row['email']."',
							'".$encryptedPassword."',
							'2',
							'".$row['parent_user_id']."',
							'".$row['business_name']."',
							'".$row['tcap_ctia']."',
							'".$row['msg_and_data_rate']."',
							'".$row['city']."',
							'".$row['state']."',
							'".$row['zip']."',
							'".$row['address']."',
							'".$row['card_number']."',
							'".$row['cvv']."',
							'".$row['year']."',
							'".$row['month']."',
							'".$row['response']."',
							'".$row['response_code']."',
							'".$row['subscription_id']."',
							'".$_REQUEST['subscr_id']."'
						)";
			$exe = mysqli_query($link,$ins)or die(mysqli_error($link));
			if($exe){
				$userID	= mysqli_insert_id($link);
				mysqli_query($link,"delete from web_user_info where id='".$row['id']."'");
				try{
					$client = getTwilioConnection($row['parent_user_id']);
				}catch(Services_Twilio_RestException $e){
				}
				
				try{
					$account= $client->accounts->create(array(
						"FriendlyName" => $row['email']
					));
					$subAccountSid 	 = $account->sid;
					$subAccountToken = $account->auth_token;
				}catch(Services_Twilio_RestException $e){
				}
				mysqli_query($link,
				"insert into application_settings(twilio_sid,twilio_token,user_id,user_type)values
				('".$subAccountSid."','".$subAccountToken."','".$userID."','2')");
				$pkgInfo= getPackageInfo($row['pkg_id']);
                $_REQUEST['item_name'] = $pkgInfo['title']." SMS Plan";
                
				$today	= date('Y-m-d H').':00:00';
				$endDate= date('Y-m-d H:i',strtotime('+1 month'.$today));
				$insPkg = "insert into user_package_assignment
								(
									user_id,
									pkg_id,
									start_date,
									end_date,
									sms_credits,
									phone_number_limit,
									locations,
									reminders,
									iso_country,
									pkg_country
								)
							values
								(
									'".$userID."',
									'".$row['pkg_id']."',
									'".$today."',
									'".$endDate."',
									'".$pkgInfo['sms_credits']."',
									'".$pkgInfo['phone_number_limit']."',
									'".$pkgInfo['locations']."',
									'".$pkgInfo['reminders']."',
									'".$pkgInfo['iso_country']."',
									'".$pkgInfo['country']."'
								)";
				mysqli_query($link,$insPkg);
				
				$subject = $appSettings['email_subject'];
				$to		 = $row['email'];
				$from	 = 'admin@'.str_replace('www.','',$_SERVER['SERVER_NAME']);
				$msg	 = $appSettings['new_app_user_email'];
				$msg	 = str_replace('%first_name%',$row['first_name'],$msg);
				$msg	 = str_replace('%last_name%',$row['last_name'],$msg);
				$msg	 = str_replace('%login_email%',$row['email'],$msg);
				$msg	 = str_replace('%login_pass%',$password,$msg);
				$msg	 = str_replace('%login_url%',$appUrl,$msg);
				$FullName= 'Admin';
				$eu = sendEmail($subject,$to,$from,$msg,$FullName);
				
				// Admin notification
				$subject = $appSettings['email_subject_for_admin_notification'];
				$to		 = $appSettings['admin_email'];
				$from	 = 'admin@'.str_replace('www.','',$_SERVER['SERVER_NAME']);
				$msg	 = str_replace('%email%',$row['email'],$appSettings['new_app_user_email_for_admin']);
				$FullName= 'Admin';
				$ea = sendEmail($subject,$to,$from,$msg,$FullName);
			}	
		}  
    }else{
        //if($subscription->getResponseCode()=='E00012'){
           // echo 2;
        //}else{
            
        $response = $subscription->getResponse();
        //$transdetail = $subscription->transactionDetail();
        $response_code = $subscription->getResponseCode();
        
        $sels = "update web_user_info set response = '".$response."', response_code = '".$response_code."', subscription_id = '".@$subscription_id."' where id='".$webUserID."'";
        $res = mysqli_query($link,$sels);    
            
        $_SESSION['authnet_response'] = 0;
        $_SESSION['authnet_msg'] = $response_code." : ".$response;
        //}
        // The subscription was not created!
    }
?>
<script> window.location = "add_user.php?pid=<?php echo encode($row['pkg_id'])?>"; </script>