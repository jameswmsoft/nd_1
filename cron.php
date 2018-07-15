<?php
	ini_set("display_errors","0");
	include_once("database.php");
	include_once("functions.php");
	
	$sql = "select * from schedulers where status='0'";
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){
		$adminSettings = getAppSettings('',true);
		while($row = mysqli_fetch_assoc($res)){
			$userID = $row['user_id'];
			$userInfo = getUserInfo($userID);
			$appInfo  = getAppSettings($userID);
			$timeZone = $appInfo['time_zone'];
			date_default_timezone_set($timeZone);
			$date = date('Y-m-d H:i');
			
			// checking cron start/stop time
			if((trim($appInfo['cron_stop_time_from'])!='')&&(trim($appInfo['cron_stop_time_to'])!='')){
				$fromTime = preg_replace('/\s+/', '', $appInfo['cron_stop_time_from']);
				$toTime   = preg_replace('/\s+/', '', $appInfo['cron_stop_time_to']);
				$fromTime = explode(":", $fromTime); 
				$toTime   = explode(":",$toTime);
				$fromT = $fromTime[0].":".$fromTime[1].$fromTime[2];
				$toT   =  $toTime[0].":".$toTime[1].$toTime[2]; 
				$cronfromTime = date("Gi", strtotime($fromT));
				$crontoTime   = date("Gi", strtotime($toT));
				if(($fromTime < date("Gi")) && ($toTime > date("Gi"))){
					die("Cron stop time has been started.");
				}
			}
			// end
			$userPkgStatus = checkUserPackageStatus($userID);
			if($userPkgStatus['go']==false){
				$remainingCredits = 0;
				die($userPkgStatus['message']);
			}else{
				$remainingCredits = $userPkgStatus['remaining_credits'];	
			}
			$sel = "select * from schedulers where status='0' and date_format(scheduled_time,'%Y-%m-%d %H:%i')<='".$date."'";
			$exe = mysqli_query($link,$sel);
			if(mysqli_num_rows($exe)){
				if($row['attach_mobile_device']=='1'){
					$from = 'mobile_sim';
				}else{
					$nn = "select phone_number from campaigns where id='".$row['group_id']."' and phone_number!=''";
					$n = mysqli_query($link,$nn);
					if(mysqli_num_rows($n)){
						$fromNumber = mysqli_fetch_assoc($n);
						$from = $fromNumber['phone_number'];
					}else{
						die('No from phone number found.');	
					}
				}
				while($scheduler = mysqli_fetch_assoc($exe)){
					if($scheduler['phone_number']=='all'){
						$groupID = $scheduler['group_id'];
						$sqlpnga = "select s.phone_number from subscribers s, subscribers_group_assignment sga where sga.group_id='".$groupID."' and sga.subscriber_id=s.id and s.status='1'";
						$respnga = mysqli_query($link,$sqlpnga);
						if(mysqli_num_rows($respnga)){
							while($number = mysqli_fetch_assoc($respnga)){
								sendMessage($from,$number['phone_number'],$scheduler['message'],$scheduler['media'],$userID,$groupID);
							}
						}
					}else{
						$phoneID = $scheduler['phone_number'];
						$r = mysqli_query($link,"select phone_number from subscribers where id='".$phoneID."' and status='1'");
						$number = mysqli_fetch_assoc($r);
						$toPhone= $number['phone_number'];
						sendMessage($from,$toPhone,$scheduler['message'],$scheduler['media'],$userID,$scheduler['group_id']);
					}
					mysqli_query($link,"update schedulers set status='1' where id='".$scheduler['id']."'");
				}
			}
		}	
	}else{
		echo 'No pending scheduler found.';	
	}
		
	// To process bulk messages.
	$sql = "select 
				* 
			from 
				queued_msgs
			where
				status='0'";
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){
		while($row = mysqli_fetch_assoc($res)){
			$userPkgStatus = checkUserPackageStatus($row['user_id']);
			$appInfo  = getAppSettings($row['user_id']);
			$timeZone = $appInfo['time_zone'];
			date_default_timezone_set($timeZone);
			$date = date('Y-m-d H:i');
			if($userPkgStatus['go']==false){
				$remainingCredits = 0;
				die($userPkgStatus['message']);
			}else{
				$remainingCredits = $userPkgStatus['remaining_credits'];
				// Gettings subscriber name
				$sel = "select id,first_name,last_name from subscribers where phone_number='".$row['to_number']."'";
				$exe = mysqli_query($link,$sel);
				if(mysqli_num_rows($exe)){
					$rec = mysqli_fetch_assoc($exe);
					$name= $rec['first_name'].' '.$rec['last_name'];
				}
				// end
			}
			$fromTime = preg_replace('/\s+/', '', $appInfo['cron_stop_time_from']);
			$toTime   = preg_replace('/\s+/', '', $appInfo['cron_stop_time_to']);
			$fromTime = explode(":", $fromTime); 
			$toTime   = explode(":",$toTime);
			$fromT = $fromTime[0].":".$fromTime[1].$fromTime[2];
			$toT   =  $toTime[0].":".$toTime[1].$toTime[2]; 
			$cronfromTime = date("Gi", strtotime($fromT));
			$crontoTime   = date("Gi", strtotime($toT));
			if(($fromTime < date("Gi")) && ($toTime > date("Gi"))){
				die("<br>Cron stop time has been started.");
			}	
			if($row['type']=='2'){ // Sending appointment alert/followup
				if($row['message_time'] <= $date){
					$from	= $row['from_number'];
					$to		= $row['to_number'];
					$body	= DBout($row['message']);
					//$body   = str_replace('%name%',$name,$body);
					$media[0] = $row['media'];
					$userID	= $row['user_id'];
					$groupID= $row['group_id'];
					sendMessage($from,$to,$body,$media,$userID,$groupID,false);
					$up = "update 
								queued_msgs
							set
								status='1'
							where
								id='".$row['id']."'";
					mysqli_query($link,$up);
				}
			}else{ // Sending bulk
				$from	= $row['from_number'];
				$to		= $row['to_number'];
				$body	= DBout($row['message']);
				$body   = str_replace('%name%',$name,$body);
				$media[0] = $row['media'];
				$userID	= $row['user_id'];
				$groupID= $row['group_id'];
				sendMessage($from,$to,$body,$media,$userID,$groupID,false);
				$up = "update 
							queued_msgs
						set
							status='1'
						where
							id='".$row['id']."'";
				mysqli_query($link,$up);
			}
		}
	}else{
		echo 'No pending queued message found.';	
	}
	// end
?>