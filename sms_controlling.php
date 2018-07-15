<?php
	mail('ahsan@nimblewebsolutions.com','NM Controlling',print_r($_REQUEST,true));
	include_once("database.php");
	include_once("functions.php");

	if($_REQUEST['is_mobile']==true){
		mail('ahsan@nimblewebsolutions.com','Received On Mobile',print_r($_REQUEST,true));
		$deviceName = trim($_REQUEST['To']);
		$to 	= 'mobile_sim';
		$from   = $_REQUEST['From'];
		$body	= $_REQUEST['Body'];
		$sel = "select id,user_id from campaigns where lower(keyword)='".strtolower($body)."'";
		$exe = mysqli_query($link,$sel);
		if(mysqli_num_rows($exe)==0){
			die("No keyword found.");
		}else{
			$d = mysqli_fetch_assoc($exe);
			$userID = $d['user_id'];
			$appSettings = getAppSettings($userID);
		}
	}else{
		// Checking to number
		$to = $_REQUEST['To'];
		if(trim($to)==''){
			$to = $_REQUEST['to'];
		}
		// end
		$toNumberInfo = getPhoneNumberDetails($to);
		$userID 	  = $toNumberInfo['user_id'];
		$appSettings  = getAppSettings($userID);
		if($toNumberInfo['type']=='1'){ // Twilio
			$from   = $_REQUEST['From'];
			$body	= $_REQUEST['Body'];
			$smsSid = $_REQUEST['SmsSid'];
		}else if($toNumberInfo['type']=='2'){ // Plivo
			$from   = '+'.$_REQUEST['From'];
			$body	= $_REQUEST['Text'];
			$smsSid = $_REQUEST['MessageUUID'];
		}else if($toNumberInfo['type']=='3'){ // Nexmo
			$from   = '+'.$_REQUEST['msisdn'];
			$body	= $_REQUEST['text'];
			$smsSid = $_REQUEST['messageId'];
		}else{
			die('Invalid to number.');	
		}
		if(trim($to)==trim($from)){die();}
	}
	$state	= $_REQUEST['FromState'];
	$city	= $_REQUEST['FromCity'];
	$country= $_REQUEST['FromCountry'];
	$subsType = $_REQUEST['subscriber_type'];
	$subsEmail= $_REQUEST['subs_email'];
	$subsName = $_REQUEST['name'];
	$platform = $_REQUEST['platform'];
	if($subsType=='webform'){
		$subsType = 'webform';
		$customSubsInfo = $_REQUEST['customSubsInfo'];
	}else{
		$subsType = 'campaign';
		$customSubsInfo = '';
	}
	
	creditCount($userID,'sms','in');
	$timeZone = $appSettings['time_zone'];
	if(trim($timeZone)!=''){
		date_default_timezone_set($timeZone);
	}
	$appendText	   = DBout($appSettings['append_text']);
	$userPkgStatus = checkUserPackageStatus($userID);
	if($userPkgStatus['go']==false){
		$remainingCredits = 0;
		die($userPkgStatus['message']);
	}else{
		$remainingCredits = $userPkgStatus['remaining_credits'];	
	}
	$optOutkeywords = checkOptOutKeywords();
	if(in_array($body,$optOutkeywords)){
		$sqlHistory = "insert into sms_history
					(
						to_number,
						from_number,
						text,media,
						sms_sid,
						direction,
						group_id,
						user_id,
						created_date,
						is_sent
					)
				values
					(
						'".$to."',
						'".$from."',
						'".DBin($body)."',
						'".$media."',
						'".$smsSid."',
						'in-bound',
						'".$groupID."',
						'".$userID."',
						'".$sentDate."',
						'true'
					)";
		mysqli_query($link,$sqlHistory);
		makeSubscriberBlocked($to,$from,$body,$smsSid,$userID);
		die();	
	}
	if(strtolower($body)=='start'){
		$sqlHistory = "insert into sms_history
					(
						to_number,
						from_number,
						text,media,
						sms_sid,
						direction,
						group_id,
						user_id,
						created_date,
						is_sent
					)
				values
					(
						'".$to."',
						'".$from."',
						'".DBin($body)."',
						'".$media."',
						'".$smsSid."',
						'in-bound',
						'".$groupID."',
						'".$userID."',
						'".$sentDate."',
						'true'
					)";
		mysqli_query($link,$sqlHistory);
		handleStartKeyword($userID,$from,$to,$smsSid);
	}
	if(strtolower($body)=='yes'){
		handleYesKeyword($userID,$from,$to,$smsSid);
	}
	if(strtolower($body)=='gdpr'){
		$sqlHistory = "insert into sms_history
					(
						to_number,
						from_number,
						text,media,
						sms_sid,
						direction,
						group_id,
						user_id,
						created_date,
						is_sent
					)
				values
					(
						'".$to."',
						'".$from."',
						'".DBin($body)."',
						'".$media."',
						'".$smsSid."',
						'in-bound',
						'".$groupID."',
						'".$userID."',
						'".$sentDate."',
						'true'
					)";
		mysqli_query($link,$sqlHistory);
		handleGDPRKeyword($userID,$from,$to,$smsSid,$appSettings);
		die();
	}
	
    
    
    // query for viral campaign's code here
    $is_viral_code = 0;
    $viral_code = "";  
    $couponCodeData = check_viral_coupon_code($body);
    if($couponCodeData !== false)
	{
	    $is_viral_code=1;
        $viral_code = $body;
      
        $groupID_temp = $couponCodeData['group_id']; 
        $camp_data = getGroupData($groupID_temp);
        $body = $camp_data['keyword'];
         
	}
    
    
    
    
	$sql = "select * from campaigns where lower(keyword)='".strtolower($body)."' and user_id='".$userID."'";
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){
		$row = mysqli_fetch_assoc($res);
		$userID = $row['user_id'];
		$groupID= $row['id'];        
		$sentDate = date('Y-m-d H:i:s');
		
        if($is_viral_code==1){
            $body = $viral_code;
        }
        
        $sqlHistory = "insert into sms_history
					(
						to_number,
						from_number,
						text,media,
						sms_sid,
						direction,
						group_id,
						user_id,
						created_date,
						is_sent
					)
				values
					(
						'".$to."',
						'".$from."',
						'".DBin($body)."',
						'".$media."',
						'".$smsSid."',
						'in-bound',
						'".$groupID."',
						'".$userID."',
						'".$sentDate."',
						'true'
					)";
		mysqli_query($link,$sqlHistory);
		if($row['type']=='1' || $row['type']=='3' || $row['type']=='4' || $row['type']=='0'){ // Campaign
			if($row['campaign_expiry_check']=='1'){
				if(trim($row['start_date']!="") && trim($row['end_date'])!=""){
					$start_date = date("Y-m-d",strtotime($row['start_date']));
					$end_date = date("Y-m-d",strtotime($row['end_date']));
					$current_date = date("Y-m-d H:i");
					if(($current_date<$start_date) || ($current_date>$end_date)){
						sendMessage($to,$from,$row['expire_message'],array(),$userID,$groupID);
					}
				}
				die();
			}
			if($row['double_optin_check']=='1'){ // Double optin
				$sel = "select id from subscribers where phone_number='".$from."' and user_id='".$userID."'";
				$exe = mysqli_query($link,$sel);
				if(mysqli_num_rows($exe)==0){
					$subID = addSubscriber($subsName,$from,$subsEmail,$subsType,$city,$state,$userID,'2',$customSubsInfo);
					assignGroup($subID,$groupID,$userID,'2');
					sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);
					sendMessage($to,$from,$row['double_optin'],array(),$userID,$groupID);
				}else{
					$rec   = mysqli_fetch_assoc($exe);
					$subID = $rec['id'];
					$sqlc  = "select id,status from subscribers_group_assignment where subscriber_id='".$subID."' and group_id='".$groupID."' and user_id='".$userID."'";
					$resc = mysqli_query($link,$sqlc);
					if(mysqli_num_rows($resc)==0){
						assignGroup($subID,$groupID,$userID,'2');
						sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);
						sendMessage($to,$from,$row['double_optin'],array(),$userID,$groupID);
					}else{
						$rowc = mysqli_fetch_assoc($resc);
						if($rowc['status']=='2'){
							sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);
							sendMessage($to,$from,$row['double_optin'],array(),$userID,$groupID);
						}else if($rowc['status']=='3'){
							// deleted from group assignment.
						}else if($rowc['status']=='1'){
							sendMessage($to,$from,$row['already_member_msg'],array(),$userID,$groupID);
						}
					}
				}
			}else{ // Single optin
				$sel = "select id from subscribers where phone_number='".$from."' and user_id='".$userID."'";
				$exe = mysqli_query($link,$sel);
				if(mysqli_num_rows($exe)==0){
					$subID = addSubscriber($subsName,$from,$subsEmail,$subsType,$city,$state,$userID,'1',$customSubsInfo);
					assignGroup($subID,$groupID,$userID,'1');
					
                    if($row['type']=='0'){
					    $isGift = addGiftTracking($subID,$groupID,$userID,$row['winning_number']);
            			echo "isGift == ".$isGift; 
                        if($isGift == '1'){
            			    sendMessage($to,$from,$row['winner_msg'],array(),$userID,$groupID);
            			}else{
            			    sendMessage($to,$from,$row['looser_msg'],array(),$userID,$groupID);
            			}
					}else{
					   sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);
					}
                    
                    
                    addFollowUpMessages($groupID,$userID,$subID);
    					
                    if($row['type']=='3' && ($row['get_subs_name_check']=='0' && $row['get_email']=='0'))
                    {
                        $firstQuestionID = getFirstQuestionID($groupID);
            			if($firstQuestionID !== false)
            			{
            				$firstQuestion = getNextImmediateQuestion($firstQuestionID);
                            if(trim($firstQuestion) != '') 
            				{
                                sendMessage($to,$from,DBout($firstQuestion),array(),$row['user_id'],$row['id']);
                                boundNumber($to,$from,$userID,$groupID,'trivia',$firstQuestionID);
        				    }
                        }
                    }else
                    if($row['type']=='4' && ($row['get_subs_name_check']=='0' && $row['get_email']=='0'))
                    {
                        $couponCode = createCouponCode();
                        $code_message = str_replace("%code%",$couponCode,$row['code_message']);
                        sendMessage($to,$from,DBout($code_message),array(),$row['user_id'],$row['id']);
                        addUserCoupon($subID,$groupID,$couponCode);
                        
                        
                        if($is_viral_code==1){
                            $parentPhoneID = $couponCodeData['phone_number_id'];
                            addViralFriend($subID,$parentPhoneID,$groupID,"1");
                            
                            $rowparentPhone = getSubscribersDetail($parentPhoneID);
                            $parentPhoneNumber = $rowparentPhone['phone_number'];
                            
                            $totalFrnd = count_friend($groupID,$parentPhoneID);
                            
                            if($totalFrnd == $row['winning_number']) 
				            {
				                sendMessage($to,$parentPhoneNumber,DBout($row['winner_msg']),array(),$row['user_id'],$row['id'],false,"1");
                                $update = "update viral_friends set status='0' where group_id='".$groupID."' and parent_phone_id='".$parentPhoneID."'";
					            mysqli_query($link,$update) or die(mysqli_error($link));
                            }
                            else
                            {
                                $remainingFrnd      = $row['winning_number']-$totalFrnd;
						        $notification_msg  	= str_replace("%togo%",$remainingFrnd,$row['notification_msg']);
                                sendMessage($to,$parentPhoneNumber,DBout($notification_msg),array(),$row['user_id'],$row['id']);
                            }                          
                        } 
                    }
                    else
                    {
                        // sending name/email message
    					if($row['get_subs_name_check']=='1'){
    						sendMessage($to,$from,DBout($row['msg_to_get_subscriber_name']),array(),$row['user_id'],$row['id']);
    						boundNumber($to,$from,$userID,$groupID,'sms',"0",$is_viral_code,$viral_code);
    						die();
    					}
    					if($row['get_email']=='1'){
    						sendMessage($to,$from,DBout($row['reply_email']),array(),$row['user_id'],$row['id']);
    						boundNumber($to,$from,$userID,$groupID,'email',"0",$is_viral_code,$viral_code);
    						die();
    					}
					    // end
                    }
				}else{
					$rec   = mysqli_fetch_assoc($exe);
					$subID = $rec['id'];
					$sqlc = "select id,status from subscribers_group_assignment where subscriber_id='".$subID."' and group_id='".$groupID."' and user_id='".$userID."'";
					$resc = mysqli_query($link,$sqlc);
					if(mysqli_num_rows($resc)==0){
						assignGroup($subID,$groupID,$userID,'1');
						
                        
                        if($row['type']=='0'){
    					    $isGift = addGiftTracking($subID,$groupID,$userID,$row['winning_number']);
                			echo "isGift == ".$isGift; 
                            if($isGift == '1'){
                			    sendMessage($to,$from,$row['winner_msg'],array(),$userID,$groupID);
                			}else{
                			    sendMessage($to,$from,$row['looser_msg'],array(),$userID,$groupID);
                			}
    					}else{
    					   sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);
    					}
                        
                        addFollowUpMessages($groupID,$userID,$subID);
						
                        
                        if($row['type']=='3' && ($row['get_subs_name_check']=='0' && $row['get_email']=='0'))
                        {
                            $firstQuestionID = getFirstQuestionID($groupID);
                			if($firstQuestionID !== false)
                			{
                				$firstQuestion = getNextImmediateQuestion($firstQuestionID);
                                if(trim($firstQuestion) != '') 
                				{
                                    sendMessage($to,$from,DBout($firstQuestion),array(),$row['user_id'],$row['id']);
                                    boundNumber($to,$from,$userID,$groupID,'trivia',$firstQuestionID);
            				    }
                            }
                        }else
                        if($row['type']=='4' && ($row['get_subs_name_check']=='0' && $row['get_email']=='0'))
                        {
                            $couponCode = createCouponCode();
                            $code_message = str_replace("%code%",$couponCode,$row['code_message']);
                            sendMessage($to,$from,DBout($code_message),array(),$row['user_id'],$row['id']);
                            addUserCoupon($subID,$groupID,$couponCode);
                            
                            
                            if($is_viral_code==1){
                                $parentPhoneID = $couponCodeData['phone_number_id'];
                                addViralFriend($subID,$parentPhoneID,$groupID,"1");
                                
                                $rowparentPhone = getSubscribersDetail($parentPhoneID);
                                $parentPhoneNumber = $rowparentPhone['phone_number'];
                                
                                $totalFrnd = count_friend($groupID,$parentPhoneID);
                                
                                if($totalFrnd == $row['winning_number']) 
    				            {
    				                sendMessage($to,$parentPhoneNumber,DBout($row['winner_msg']),array(),$row['user_id'],$row['id'],false,"1");
                                    $update = "update viral_friends set status='0' where group_id='".$groupID."' and parent_phone_id='".$parentPhoneID."'";
    					            mysqli_query($link,$update) or die(mysqli_error($link));
                                }
                                else
                                {
                                    $remainingFrnd      = $row['winning_number']-$totalFrnd;
    						        $notification_msg  	= str_replace("%togo%",$remainingFrnd,$row['notification_msg']);
                                    sendMessage($to,$parentPhoneNumber,DBout($notification_msg),array(),$row['user_id'],$row['id']);
                                }                          
                            } 
                        }
                        else
                        {
                            // sending name/email message
    						if($row['get_subs_name_check']=='1'){
    							sendMessage($to,$from,DBout($row['msg_to_get_subscriber_name']),array(),$row['user_id'],$row['id']);
    							boundNumber($to,$from,$userID,$groupID,'sms');
    							die();
    						}
    						if($row['get_email']=='1'){
    							sendMessage($to,$from,DBout($row['reply_email']),array(),$row['user_id'],$row['id']);
    							boundNumber($to,$from,$userID,$groupID,'email');
    							die();
    						}
						    // end
                          }
					}else{
						$rowc = mysqli_fetch_assoc($resc);
						assignGroup($subID,$groupID,$userID,'1');
						sendMessage($to,$from,$row['already_member_msg'],array(),$userID,$groupID);
					}
				}
			}
		}else if($row['type']=='2'){ // Autoresponder
			$sel = "select id from subscribers where phone_number='".$from."' and user_id='".$userID."'";
			$exe = mysqli_query($link,$sel);
			if(mysqli_num_rows($exe)==0){
				$subID = addSubscriber($subsName,$from,$subsEmail,$subsType,$city,$state,$userID,'1',$customSubsInfo);
				assignGroup($subID,$groupID,$userID,'1');
				sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);
			}else{
				$rec = mysqli_fetch_assoc($exe);
				$subID = $rec['id'];
				assignGroup($subID,$groupID,$userID,'1');
				sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);	
			}
			sendMessage($to,$appSettings['admin_phone'],$row['already_member_msg'],$row['media'],$userID,$groupID);	
		}
		if(trim($platform)=='nmapi'){
			echo '{"id":"'.$subID.'","message":"success"}';
		}
	}else{ // Bound Phone Handling
    
    
        $sel_sub = "select id from subscribers where phone_number='".$from."' and user_id='".$userID."'";
		$exe_sub = mysqli_query($link,$sel_sub);
		if(mysqli_num_rows($exe_sub)>0){
            $rec_sub   = mysqli_fetch_assoc($exe_sub);
            $subID = $rec_sub['id']; 
        }

    
		$sql = "insert into sms_history
				(to_number,from_number,text,sms_sid,direction,group_id,user_id,created_date)values
				('".$to."','".$from."','".DBin($body)."','".$smsSid."','in-bound','".$groupID."','".$userID."','".$sentDate."')";
		mysqli_query($link,$sql);
        $current_date = date("Y-m-d H:i");
        $sel = "select * from bound_phones where to_number='".$to."' and from_number='".$from."' and user_id='".$userID."' and lease_date >= '".$current_date."' order by id desc limit 1";
    	$exe = mysqli_query($link,$sel);
    	if(mysqli_num_rows($exe)>0){
			$row = mysqli_fetch_assoc($exe);
			$groupID = $row['group_id'];
			$whatIsSent = $row['what_is_sent'];
            $lastQuestionID = $row['question_id'];
            $is_viral_code = $row['is_viral_code'];
            $viral_code = $row['viral_code']; 
			$sentDate = date('Y-m-d H:i:s');
			$rowqq = getGroupData($groupID);
			if($rowqq!=false){
				if($whatIsSent=='email'){ // email received
					$sel34 = "update subscribers set email='".$_REQUEST['Body']."' where phone_number='".$from."' and user_id='".$userID."'";
					$exe34 = mysqli_query($link,$sel34);
					sendMessage($to,$from,$rowqq['email_updated'],array(),$userID,$groupID);
					mysqli_query($link,"delete from bound_phones where id = '".$row['id']."'");
					
					/*
                    Commented by M Qadeer Sheikh to shop wrong loop of same sms again and again.
                    It should not send "Reply with name sms" from here, you already asked above
                    https://www.screencast.com/t/EncQhv6tjj7c 
                    if($rowqq['get_subs_name_check']=='1'){
						sendMessage($to,$from,DBout($rowqq['msg_to_get_subscriber_name']),array(),$rowqq['user_id'],$rowqq['id']);
						boundNumber($to,$from,$rowqq['user_id'],$rowqq['id'],'sms');
					}
                    */
                    
                    
                    if($rowqq['type']=='3'){
                        $firstQuestionID = getFirstQuestionID($groupID);
            			if($firstQuestionID !== false)
            			{
            				$firstQuestion = getNextImmediateQuestion($firstQuestionID);
                            if(trim($firstQuestion) != '') 
            				{
                                sendMessage($to,$from,DBout($firstQuestion),array(),$row['user_id'],$groupID);
                                boundNumber($to,$from,$userID,$groupID,'trivia',$firstQuestionID);
        				    }
                        }
                    }
                    else if($rowqq['type']=='4'){
                        $couponCode = createCouponCode();
                        $code_message = str_replace("%code%",$couponCode,$rowqq['code_message']);
                        sendMessage($to,$from,DBout($code_message),array(),$row['user_id'],$groupID);
                        addUserCoupon($subID,$groupID,$couponCode);
                        
                        if($is_viral_code==1){
                            
                            $couponCodeData = check_viral_coupon_code($viral_code);
                            if($couponCodeData !== false){  
                                $camp = getGroupData($groupID); 
                        	}
                            
                            $parentPhoneID = $couponCodeData['phone_number_id'];
                            addViralFriend($subID,$parentPhoneID,$groupID,"1");
                            
                            $rowparentPhone = getSubscribersDetail($parentPhoneID);
                            $parentPhoneNumber = $rowparentPhone['phone_number'];
                            
                            $totalFrnd = count_friend($groupID,$parentPhoneID);
                            
                            if($totalFrnd == $camp['winning_number']) {
				                sendMessage($to,$parentPhoneNumber,DBout($camp['winner_msg']),array(),$camp['user_id'],$camp['id'],false,"1");
                                $update = "update viral_friends set status='0' where group_id='".$groupID."' and parent_phone_id='".$parentPhoneID."'";
					            mysqli_query($link,$update) or die(mysqli_error($link));
                            }
                            else{
                                $remainingFrnd      = $camp['winning_number']-$totalFrnd;
						        $notification_msg  	= str_replace("%togo%",$remainingFrnd,$camp['notification_msg']);
                                sendMessage($to,$parentPhoneNumber,DBout($notification_msg),array(),$camp['user_id'],$camp['id']);
                            }                          
                        } 
                        
                                              
                    }
                    
                    
                    
				}else if($whatIsSent=='sms'){ // name received
					$sel34 = "update subscribers set first_name='".$_REQUEST['Body']."' where phone_number='".$from."' and user_id='".$userID."'";
					$exe34 = mysqli_query($link,$sel34);
					sendMessage($to,$from,DBout($rowqq['name_received_confirmation_msg']),array(),$userID,$groupID);
					mysqli_query($link,"delete from bound_phones where id = '".$row['id']."'");
					
					if($rowqq['get_email']=='1'){
						sendMessage($to,$from,DBout($rowqq['reply_email']),array(),$rowqq['user_id'],$rowqq['id']);
						boundNumber($to,$from,$rowqq['user_id'],$rowqq['id'],'email',"0",$is_viral_code,$viral_code);
					}
                    else{
                        if($rowqq['type']=='3'){
                            $firstQuestionID = getFirstQuestionID($groupID);
                            if($firstQuestionID !== false)
                			{
                				$firstQuestion = getNextImmediateQuestion($firstQuestionID);
                			    if(trim($firstQuestion) != '') 
                				{
                			        sendMessage($to,$from,DBout($firstQuestion),array(),$row['user_id'],$groupID);
                                    boundNumber($to,$from,$userID,$groupID,'trivia',$firstQuestionID);
            				    }
                            }
                        }else if($rowqq['type']=='4'){
                            $couponCode = createCouponCode();
                            $code_message = str_replace("%code%",$couponCode,$rowqq['code_message']);
                            sendMessage($to,$from,DBout($code_message),array(),$row['user_id'],$groupID);
                            addUserCoupon($subID,$groupID,$couponCode);
                            
                            if($is_viral_code==1){
                                
                                $couponCodeData = check_viral_coupon_code($viral_code);
                                if($couponCodeData !== false)
                            	{  
                                    $camp = getGroupData($groupID); 
                            	}
                                
                                $parentPhoneID = $couponCodeData['phone_number_id'];
                                addViralFriend($subID,$parentPhoneID,$groupID,"1");
                                
                                $rowparentPhone = getSubscribersDetail($parentPhoneID);
                                $parentPhoneNumber = $rowparentPhone['phone_number'];
                                
                                $totalFrnd = count_friend($groupID,$parentPhoneID);
                                
                                if($totalFrnd == $camp['winning_number']) 
    				            {
    				                sendMessage($to,$parentPhoneNumber,DBout($camp['winner_msg']),array(),$camp['user_id'],$camp['id'],false,"1");
                                    $update = "update viral_friends set status='0' where group_id='".$groupID."' and parent_phone_id='".$parentPhoneID."'";
    					            mysqli_query($link,$update) or die(mysqli_error($link));
                                }
                                else
                                {
                                    $remainingFrnd      = $camp['winning_number']-$totalFrnd;
    						        $notification_msg  	= str_replace("%togo%",$remainingFrnd,$camp['notification_msg']);
                                    sendMessage($to,$parentPhoneNumber,DBout($notification_msg),array(),$camp['user_id'],$camp['id']);
                                }                          
                            }   
                        }
                    }
				}else{
				    
                    mysqli_query($link,"delete from bound_phones where id = '".$row['id']."'");
                    
                    
                    $ansCheck = checkAnsArr($lastQuestionID, strtoupper($body));
					if($ansCheck !== false)
					{
						if($ansCheck['correct'] == '1'){
                            sendMessage($to,$from,DBout($rowqq['correct_sms']),array(),$row['user_id'],$row['id']);
					    }
                        else{
                            sendMessage($to,$from,DBout($rowqq['wrong_sms']),array(),$row['user_id'],$row['id']);
                        }
                    }
                    
                    
                    $nextQuestionID = getNextImmediateQuestionID($groupID,$lastQuestionID);
                    if($nextQuestionID !== false)
        			{
        				$nextQuestion = getNextImmediateQuestion($nextQuestionID);
        				if(trim($nextQuestion) != '') 
        				{
        				    sendMessage($to,$from,DBout($nextQuestion),array(),$row['user_id'],$row['id']);
                            boundNumber($to,$from,$userID,$groupID,'trivia',$nextQuestionID);
    				    }
                    }else{
                        
                        sendMessage($to,$from,DBout($rowqq['complete_sms']),array(),$row['user_id'],$row['id']);
                        
                    }
                    
				}
			}
			die();
    	}
        
        
		// Sending autoresponder without keyword
		$sel = "select id,welcome_sms,already_member_msg,direct_subscription from campaigns where phone_number='".$to."' and user_id='".$userID."' and type='2' and direct_subscription='1' limit 1";
		$exe = mysqli_query($link,$sel);
		if(mysqli_num_rows($exe)){
			$rec = mysqli_fetch_assoc($exe);
			if($rec['direct_subscription']=='1'){
				$groupID = $rec['id'];
				sendMessage($to,$from,$rec['welcome_sms'],$rec['media'],$userID,$groupID);
				sendMessage($to,$appSettings['admin_phone'],$rec['already_member_msg'],$rec['media'],$userID,$groupID);
			}
			die();
		}
		// End
		
		$selc = "select id from subscribers where phone_number='".$from."' and user_id='".$userID."'";
		$exec = mysqli_query($link,$selc);
		if(mysqli_num_rows($exec)==0){
			$sql = "insert into subscribers
						(phone_number,status,user_id)
					values
						('".$from."','1','".$userID."')";
			$res = mysqli_query($link,$sql);
			$subsID = mysqli_insert_id($link);
			$sql = "insert into chat_history
						(
							phone_id,
							message,
							direction,
							user_id,
							message_sid,
							created_date
						)
					values
						(
							'".$subsID."',
							'".DBin($body)."',
							'in',
							'".$userID."',
							'".DBin($smsSid)."',
							'".date('Y-m-d H:i:s')."'
						)";
			mysqli_query($link,$sql);
		}else{
			$rec = mysqli_fetch_assoc($exec);
			$sql = "insert into chat_history
						(
							phone_id,
							message,
							direction,
							user_id,
							message_sid,
							created_date
						)
					values
						(
							'".$rec['id']."',
							'".DBin($body)."',
							'in',
							'".$userID."',
							'".DBin($smsSid)."',
							'".date('Y-m-d H:i:s')."'
						)";
			mysqli_query($link,$sql);	
		}
	}
?>