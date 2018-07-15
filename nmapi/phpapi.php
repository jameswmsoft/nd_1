<?php
	header("Access-Control-Allow-Origin: *");
	$cmd  = $_REQUEST['cmd'];

	if((isset($cmd)) && (trim($cmd)!='')){
		include_once('../database.php');
		include_once('../functions.php');
		// Checking API Key
		$apiKey = $_REQUEST['api_key'];
		$sql = sprintf("select id,api_key from application_settings where api_key='%s'",
			   mysqli_real_escape_string($link,$apiKey));
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row    = mysqli_fetch_assoc($res);
			$userID = $row['id'];
			$apiKey = $row['api_key'];
			$adminSettings = getAppSettings("",true);
			@date_default_timezone_set($adminSettings['time_zone']);
		}else{
			die('Error! Authentication failed.');
		}
		// End
		
		if($cmd=='make_subscriber'){
			if((trim($_REQUEST['phone'])!='') && (trim($_REQUEST['group_id']!=''))){
				$name    = $_REQUEST['name'];
				$phone   = $_REQUEST['phone'];
				$groupID = $_REQUEST['group_id'];
				$sel = "select keyword,phone_number from campaigns where id='".$groupID."'";
				$exe = mysqli_query($link,$sel);
				if(mysqli_num_rows($exe)){
					$campData = mysqli_fetch_assoc($exe);
					if((trim($campData['phone_number'])!='') && (trim($campData['keyword'])!='')){
						$url = getServerUrl().'/sms_controlling.php';
						$data= array('platform'=>'nmapi', 'To'=>$campData['phone_number'], 'From'=>$phone, 'Body'=>$campData['keyword'], 'SmsSid'=>'nmapi','name'=>$name);
						echo postData($url,$data);
					}else{
						die('Select group is not configured correctly.');
					}
				}else{
					die('Select group is not exists or deleted.');	
				}
			}else{
				die('API parameters are missing.');	
			}
		}
		else if($cmd=='subscriber'){
			if((isset($_REQUEST['group_id'])) &&(trim($_REQUEST['group_id'])!='')){
				$sql = "select s.first_name,s.last_name,s.phone_number from subscribers s, subscribers_group_assignment sga where sga.group_id='".$_REQUEST['group_id']."' and sga.subscriber_id=s.id";
			}else{
				$sql = "select first_name, last_name, phone_number from subscribers";
			}
			$res = mysqli_query($link,$sql);
			if(mysqli_num_rows($res)){
				while($row = mysqli_fetch_assoc($res)){
					echo json_encode($row);
					echo '<br>';
				}	
			}else{
				die('No subscriber found.');	
			}
		}
		else if($cmd=='make_user'){
			$email = $_REQUEST['email'];
			$password = $_REQUEST['password'];
			$planID = $_REQUEST['plan_id'];
			if((filter_var($email,FILTER_VALIDATE_EMAIL)) && (trim($password)!='')){
				if(trim($planID)!=''){
					$pkgInfo = getPackageInfo($planID);
					$userID  = $pkgInfo['user_id'];
					$check = "select id from users where email='".$_REQUEST['email']."'";
					$resep = mysqli_query($link,$check);
					if(mysqli_num_rows($resep)==0){
						try{
							// Creating twilio sub account
							$client = getTwilioConnection($userID);
							$account= $client->accounts->create(array(
								"FriendlyName" => $_REQUEST['email']
							));
							// end
							$subAccountSid = $account->sid;
							$subAccountToken = $account->auth_token;
							
							$encryptedPassword = encodePassword($password);
							$sql = "insert into users
										(
											first_name,
											last_name,
											email,
											password,
											parent_user_id,
											type
										)
									values
										(
											'".$_REQUEST['first_name']."',
											'".$_REQUEST['last_name']."',
											'".$_REQUEST['email']."',
											'".$encryptedPassword."',
											'".$userID."',
											'2'
										)";
							$res = mysqli_query($link,$sql);
							if($res){
								$newUserID = mysqli_insert_id($link);
								$today	= date('Y-m-d H').':00:00';
								$endDate= date('Y-m-d H:i',strtotime('+1 month'.$today));
								$ins = "insert into user_package_assignment
											(
												user_id,
												pkg_id,
												start_date,
												end_date,
												sms_credits,
												phone_number_limit,
												pkg_country,
												iso_country
											)
										values
											(
												'".$newUserID."',
												'".$pkgInfo['id']."',
												'".$today."',
												'".$endDate."',
												'".$pkgInfo['sms_credits']."',
												'".$pkgInfo['phone_number_limit']."',
												'".$pkgInfo['country']."',
												'".$pkgInfo['iso_country']."'
											)";
								mysqli_query($link,$ins);
								
								// Adding app setting for new user
								$sqls = "insert into application_settings 
									(
										sms_gateway,
										twilio_sid,
										twilio_token,
										version,
										user_id,
										app_logo,
										app_date_format,
										user_type,
										sidebar_color
									)
								values
									(
										'twilio',
										'".$subAccountSid."',
										'".$subAccountToken."',
										'".$adminSettings['version']."',
										'".$newUserID."',
										'nimble_messaging.png',
										'm-d-Y',
										'2',
										'purple'
									)";
								mysqli_query($link,$sqls);
								// end
								echo '{"id":"'.$newUserID.'","message":"success"}';
							}else{
								echo '{"id":"'.$newUserID.'","message":"error","error":"'.mysqli_error($link).'"}';
							}
							$appUrl		 = getServerUrl();
							$subject = DBout($adminSettings['email_subject']);
							$to		 = $_REQUEST['email'];
							$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
							$msg	 = $adminSettings['new_app_user_email'];
							$msg	 = str_replace('%first_name%',$_REQUEST['first_name'],$msg);
							$msg	 = str_replace('%last_name%',$_REQUEST['last_name'],$msg);
							$msg	 = str_replace('%login_email%',$_REQUEST['email'],$msg);
							$msg	 = str_replace('%login_pass%',$_REQUEST['password'],$msg);
							$msg	 = str_replace('%login_url%',$appUrl,$msg);
							$FullName= 'Admin';
							sendEmail($subject,$to,$from,$msg,$FullName);
							
							// Admin notification
							$subject = $adminSettings['email_subject_for_admin_notification'];
							$to		 = $adminSettings['admin_email'];
							$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
							$msg	 = str_replace('%email%',$_REQUEST['email'],$adminSettings['new_app_user_email_for_admin']);
							$FullName= 'Admin';
							sendEmail($subject,$to,$from,$msg,$FullName);
						}catch(Services_Twilio_RestException $e){
							echo $e->getMessage();
						}
					}else{
						echo '{"message":"error","error":"Duplicate account."}';
					}
				}else{
					die('Plan id parameter is missing.');
				}
			}else{
				die('Email or password not valid.');
			}
		}
		else if($cmd=='bulk_sms'){
			if(trim($_REQUEST['text'])!=''){
				if($adminSettings['sms_gateway']=='twilio')
					$numberType = '1';
				else if($adminSettings['sms_gateway']=='plivo')
					$numberType = '2';
				else if($adminSettings['sms_gateway']=='nexmo')
					$numberType = '3';
				
				$sel = "select phone_number from users_phone_numbers where user_id='".$userID."' and type='".$numberType."'";
				$exe = mysqli_query($link,$sel);
				if(mysqli_num_rows($exe)){
					$fromNumbers = array();
					while($number = mysqli_fetch_assoc($exe)){
						$fromNumbers[] = $number['phone_number'];
					}
				}else{
					die('No phone number found to send messages.');
				}
				$randKey = array_rand($fromNumbers,1);
				$fromNumber = $fromNumbers[$randKey];
				$userPkgStatus = checkUserPackageStatus($userID);
				if($userPkgStatus['go']==false){
					$remainingCredits = 0;
					die($userPkgStatus['message']);
				}else{
					$remainingCredits = $userPkgStatus['remaining_credits'];	
				}
				$client = getTwilioConnection($userID);
				if(trim($_REQUEST['group_id'])!=''){ // to group
					$sql = "select s.phone_number from subscribers s, subscribers_group_assignment sga where sga.group_id='".$_REQUEST['group_id']."' and sga.subscriber_id=s.id";
					$res = mysqli_query($link,$sql);
					if(mysqli_num_rows($res)){
						while($row = mysqli_fetch_assoc($res)){
							$toNumber = $row['phone_number'];
							$smsSid = sendMessage($client,$fromNumber,$toNumber,$_REQUEST['text'],$media=array(),$userID,$groupID="");
							echo '{"message":"success","sms_id":"'.$smsSid.'"}<br>';
						}
					}else{
						die('No subscriber found.');
					}
				}else{ // to all subs
					$sql = "select phone_number from subscribers where user_id='".$userID."'";
					$res = mysqli_query($link,$sql);
					if(mysqli_num_rows($res)){
						while($row = mysqli_fetch_assoc($res)){
							$toNumber = $row['phone_number'];
							$smsSid = sendMessage($client,$fromNumber,$toNumber,$_REQUEST['text'],$media=array(),$userID,$groupID="");
							echo '{"message":"success","sms_id":"'.$smsSid.'"}<br>';
						}
					}else{
						die('No subscriber found.');
					}
				}
			}else{
				die('Text parameter is missing.');	
			}
		}
		else if($cmd=='make_plan'){
			if(($_REQUEST['free_days']=='') or ($_REQUEST['free_days']=='0'))
				$isFreeDays = '0';
			else
				$isFreeDays = '1';

			$sql = "insert into package_plans					
						(
							title,
							sms_credits,
							phone_number_limit,
							currency,
							price,
							user_id,
							iso_country,
							country,
							is_free_days,
							free_days
						)
					values
						(
							'".DBin($_REQUEST['title'])."',
							'".$_REQUEST['sms_credits']."',
							'".$_REQUEST['phone_number_limit']."',
							'".$_REQUEST['currency']."',
							'".$_REQUEST['price']."',
							'".$adminSettings['user_id']."',
							'".$_REQUEST['iso_country']."',
							'".$_REQUEST['iso_country']."',
							'".$isFreeDays."',
							'".$_REQUEST['free_days']."'
						)";
			$res = mysqli_query($link,$sql);
			if($res){
				$planID = mysqli_insert_id($link);
				echo '{"message":"success","plan_id":"'.$planID.'"}';
			}else{
				echo '{"message":"error","error":"'.mysqli_error($link).'"}';
			}
		}
	}else{
		echo 'Resource not found.';
	}
	mysqli_close($link);
?>