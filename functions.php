<?php
	function handleGDPRKeyword($userID,$from,$to,$smsSid,$appSettings){
		global $link;
		date_default_timezone_set($appSettings['time_zone']);
		$sql = "select id from subscribers where phone_number='".$from."' and user_id='".$userID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			$profileUrl = getServerUrl().'/gdpr.php?subid='.encode($row['id']);
			//$profileUrl = bitlyLinkShortner($profileUrl,$userID);
			$gdPrMessage = str_replace('%gdpr_link%',$profileUrl,$appSettings['gdpr_message']);
			sendMessage($to,$from,DBout($gdPrMessage),array(),$userID,"");
			
			date_default_timezone_set($appSettings['time_zone']);
			$today = date('Y-m-d H:i');
			$sql = "select id from bound_phones where to_number='".$from."' and user_id='".$row['user_id']."' and date_format(lease_date, '%Y-%m-%d %H:%i') > '".$today."'";
			$exe = mysqli_query($link,$sql);
			if(mysqli_num_rows($exe)==0){
				mysqli_query($link,"delete from bound_phones where to_number='".$from."' and user_id='".$userID."'");
				$ins = "insert into bound_phones 
					(to_number,from_number,user_id,lease_date,what_is_sent)
					values
					('".$from."','".$to."','".$userID."','".date('Y-m-d H:i:s',strtotime("+5 minutes"))."','".$profileUrl."')";
				mysqli_query($link,$ins);
			}else{
				mysqli_query($link,"delete from bound_phones where to_number='".$from."' and user_id='".$userID."'");
				$ins = "insert into bound_phones 
					(to_number,from_number,user_id,lease_date,what_is_sent)
					values
					('".$from."','".$to."','".$userID."','".date('Y-m-d H:i:s',strtotime("+5 minutes"))."','".$profileUrl."')";
				mysqli_query($link,$ins);
			}
			
		}
	}
	function setTimeZone($userID){
		global $link;
		$sql = "select time_zone from application_settings where user_id='".$userID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);	
			$timeZone = $row['time_zone'];
			if(trim($timeZone)!=''){
				date_default_timezone_set($timeZone);	
			}
		}
	}
	function isMediaExists($media){
		if(trim($media)!=''){
			$fileCheckArray = @explode('/',$media);
			$fileCheckName  = @end($fileCheckArray);
			$filePath = getServerUrl().'/uploads/'.$fileCheckName;
			//$filePath = trim($filePath,'/');
			if(file_exists('uploads/'.$fileCheckName)){
				return '<img src="'.$media.'" width="30" height="30" />';
			}
		}	
	}
	function getTotalBlockedSubscribers($userID){
		global $link;
		$sql = "select id from subscribers where user_id='".$userID."' and status='2'";
		$res = mysqli_query($link,$sql);
		return mysqli_num_rows($res);
	}
	function getTotalActiveSubscribers($userID){
		global $link;
		$sql = "select id from subscribers where user_id='".$userID."' and status='1'";
		$res = mysqli_query($link,$sql);
		return mysqli_num_rows($res);
	}
	function getTotalAutoresponders($userID){
		global $link;
		$sql = "select id from campaigns where user_id='".$userID."' and type='2'";
		$res = mysqli_query($link,$sql);
		return mysqli_num_rows($res);
	}
	function getTotalGroups($userID){
		global $link;
		$sql = "select id from campaigns where user_id='".$userID."' and type='1'";
		$res = mysqli_query($link,$sql);
		return mysqli_num_rows($res);
	}
	function getDeviceInfo($deviceID){
		global $link;
		$sql = "select * from mobile_devices where id='".$deviceID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			return mysqli_fetch_assoc($res);
		}else{
			return 'false';
		}
	}
	function bitlyLinkShortner($url,$userID){
		$appSettings = getAppSettings($userID);
		if(trim($appSettings['bitly_token'])!=''){
			$url= "https://api-ssl.bitly.com/v3/shorten?access_token=".$appSettings['bitly_token']."&longUrl=".urlencode($url);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$request = curl_exec($ch);
			$request = json_decode($request,true);
			return $request['data']['url'];
			curl_close($ch);
		}else{
			return $url;
		}
	}
	function getPhoneNumberDetails($phone){
		global $link;
		$sql = "select * from users_phone_numbers where phone_number='".$phone."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			return mysqli_fetch_assoc($res);	
		}else{
			return 'invalid To phone number.';
		}
	}
	function ResizeImage(
				$file,
				$string = null,
				$width = 0,
				$height = 0,
				$proportional = false,
				$output = 'file', 
				$delete_original = true, 
				$use_linux_commands = false,
				$quality = 100,
				$grayscale = false
  		 	){
		if($height <= 0 && $width <= 0) return false;
		if($file === null && $string === null) return false;

		# Setting defaults and meta
		$info = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
		$image = '';
		$final_width = 0;
		$final_height = 0;
		list($width_old,$height_old) = $info;
		$cropHeight = $cropWidth = 0;

		# Calculating proportionality
		if($proportional){
			if($width == 0)$factor = $height/$height_old;
			elseif($height == 0)$factor = $width/$width_old;
			else $factor = min( $width / $width_old, $height / $height_old );
			
			$final_width  = round($width_old * $factor);
			$final_height = round($height_old * $factor);
		}else{
			$final_width = ($width <= 0) ? $width_old : $width;
			$final_height= ($height <= 0 ) ? $height_old : $height;
			$widthX = $width_old / $width;
			$heightX = $height_old / $height;
			
			$x = min($widthX, $heightX);
			$cropWidth = ($width_old - $width * $x) / 2;
			$cropHeight = ($height_old - $height * $x) / 2;
		}

		# Loading image to memory according to type
		switch($info[2]){
			case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
			case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
			case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
			default: return false;
		}
    
		# Making the image grayscale, if needed
		if($grayscale){
			imagefilter($image, IMG_FILTER_GRAYSCALE);
		}    
    
		# This is the resizing/resampling/transparency-preserving magic
		$image_resized = imagecreatetruecolor($final_width, $final_height);
		if(($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)){
			$transparency = imagecolortransparent($image);
			$palletsize = imagecolorstotal($image);
			
			if ($transparency >= 0 && $transparency < $palletsize) {
			$transparent_color  = imagecolorsforindex($image, $transparency);
			$transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
			imagefill($image_resized, 0, 0, $transparency);
			imagecolortransparent($image_resized, $transparency);
			}
			elseif ($info[2] == IMAGETYPE_PNG) {
			imagealphablending($image_resized, false);
			$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
			imagefill($image_resized, 0, 0, $color);
			imagesavealpha($image_resized, true);
			}
		}
		imagecopyresampled($image_resized,$image,0,0,$cropWidth,$cropHeight,$final_width,	$final_height,$width_old - 2 * $cropWidth,$height_old - 2 * $cropHeight);
	
		# Taking care of original, if needed
		if($delete_original){
			if($use_linux_commands)
				exec('rm '.$file);
			else
				@unlink($file);
		}

		# Preparing a method of providing result
		switch(strtolower($output)){
			case 'browser':
				$mime = image_type_to_mime_type($info[2]);
				header("Content-type: $mime");
				$output = NULL;
			break;
			case 'file':
				$output = $file;
			break;
			case 'return':
				return $image_resized;
			break;
				default:
			break;
		}
    
		# Writing image according to type to the output destination and image quality
		switch($info[2]){
			case IMAGETYPE_GIF: imagegif($image_resized,$output); break;
			case IMAGETYPE_JPEG: imagejpeg($image_resized,$output,$quality); break;
			case IMAGETYPE_PNG:
			$quality = 9 - (int)((0.9*$quality)/10.0);
			imagepng($image_resized, $output, $quality);
			break;
			default: return false;
		}
		return true;
		/**
		* easy image resize function
		* @param  $file - file name to resize
		* @param  $string - The image data, as a string
		* @param  $width - new image width
		* @param  $height - new image height
		* @param  $proportional - keep image proportional, default is no
		* @param  $output - name of the new file (include path if needed)
		* @param  $delete_original - if true the original image will be deleted
		* @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
		* @param  $quality - enter 1-100 (100 is best quality) default is 100
		* @param  $grayscale - if true, image will be grayscale (default is false)
		* @return boolean|resource
		*/
	}
	function subscriberLookUp($sid,$token,$number,$numberID){
		global $link;
		$url = "https://lookups.twilio.com/v1/PhoneNumbers/".$number."?Type=carrier&Type=caller-name";
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_USERPWD,"$sid:$token");
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPGET, true );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');
		$request = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($request,true);
		$callerName = $response['caller_name']['caller_name'];
		$callerType = $response['caller_name']['caller_type'];
		$countryCode= $response['country_code'];
		$carrierName= $response['carrier']['name'];
		$carrierType= $response['carrier']['type'];
		$mobCountryCode = $response['carrier']['mobile_country_code'];
		$mobNetworkCode = $response['carrier']['mobile_network_code'];
		if(trim($carrierName)!=''){
			$sql = "update subscribers set
						first_name='".$callerName."',
						caller_type='".$callerType."',
						country_code='".$mobCountryCode."',
						carrier_name='".$carrierName."',
						carrier_type='".$carrierType."',
						mobile_country_code='".$mobCountryCode."',
						mobile_network_code='".$mobNetworkCode."'
					where
						id='".$numberID."'";
			mysqli_query($link,$sql);
		}
		return $response;
	}
	function updatePassword($userID,$password){
		global $link;
		$pass = encodePassword($password);
		$sql = "update users set password='".$pass."' where id='".$userID."'";
		mysqli_query($link,$sql);
	}
	function encodePassword($str){
		for($i=0; $i<2; $i++){
			$str=strrev(base64_encode($str)); 
		}
		return $str;
	}
	function decodePassword($str){
		for($i=0; $i<2; $i++){
			$str=base64_decode(strrev($str));
		}
		return $str;
	}
	function handleStartKeyword($userID,$from,$to,$smsSid){
		global $link;
		$up = "update subscribers set status='1' where phone_number='".$from."' and user_id='".$userID."'";
		$res = mysqli_query($link,$up);
		if(mysqli_affected_rows($link))
			$sql = "insert into sms_history
						(
							to_number,
							from_number,
							text,
							sms_sid,
							direction,
							user_id
						)
					values
						(
							'".$to."',
							'".$from."',
							'start',
							'".$smsSid."',
							'in-bound',
							'".$userID."'
						)";
			mysqli_query($link,$sql);
		die();
	}
    function getProtectedValues($obj,$name){
        $array = (array)$obj;
        $prefix = chr(0).'*'.chr(0);
        return $array[$prefix.$name];
    }
	function isValidMd5($md5=''){
		return preg_match('/^[a-f0-9]{32}$/', $md5);
	}
	function validImageExtensions(){
		return array('png','jpg','jpeg','gif','bmp');
	}
	function numberLookUp($sid,$token,$number){
		$url = "https://$sid:$token@lookups.twilio.com/v1/PhoneNumbers/".$number."?Type=carrier";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPGET, true );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');
		$request = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($request,true);
		return $response;
	}
	function getUpdateDetails($version){
		$url = "http://apps.ranksol.com/app_updates/nimble_messaging_update/update.php?ver=".$version;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPGET, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	function post_curl_mqs($url,$data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, true);
		//curl_setopt($ch, CURLOPT_HTTPGET, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	function getBulkSMS($smsID){
		global $link;
		$sql = "select * from bulk_sms where id='".$smsID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			return mysqli_fetch_assoc($res);
			//return DBout($row['message']);
		}else
			return '';
	}
    function getBulkMedia($smsID){
		global $link;
		$sql = "select bulk_media from bulk_sms where id='".$smsID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			return $row['bulk_media'];
		}else
			return '';
	}
	function handleYesKeyword($userID,$from,$to,$smsSid){
		global $link;
		$sqln = "select id from subscribers where phone_number='".$from."' and user_id='".$userID."'";
		$resn = mysqli_query($link,$sqln);
		if(mysqli_num_rows($resn)==0){
			die();
		}else{
			$rown = mysqli_fetch_assoc($resn);
			$subscriberID = $rown['id'];
			$sqla = "select id,group_id from subscribers_group_assignment where subscriber_id='".$subscriberID."' and status='2' and user_id='".$userID."' order by id desc limit 1";
			$resa = mysqli_query($link,$sqla);
			if(mysqli_num_rows($resa)==0){
				die();
			}else{
				$rowa = mysqli_fetch_assoc($resa);
				$groupID = $rowa['group_id'];
			}
			$sql = "insert into sms_history
				(to_number,from_number,text,media,sms_sid,direction,group_id,user_id)values
				('".$to."','".$from."','yes','','".$smsSid."','in-bound','".$groupID."','".$userID."')";
			mysqli_query($link,$sql);
			
			mysqli_query($link,"update subscribers set status='1' where id='".$subscriberID."' and user_id='".$userID."'");
			
			mysqli_query($link,"update subscribers_group_assignment set status='1' where group_id='".$groupID."' and subscriber_id='".$subscriberID."' and user_id='".$userID."'");
			
			mysqli_query($link,"delete from subscribers_group_assignment where subscriber_id='".$subscriberID."' and user_id='".$userID."' and status='2'");
			addFollowUpMessages($groupID,$userID,$subscriberID);
			creditCount($userID,'sms','in');
			
			$groupData = getGroupData($groupID);
			// double optin thanks
			sendMessage($to,$from,DBout($groupData['double_optin_confirm_message']),array(),$groupData['user_id'],$groupData['id']);
			// end
			// sending name/email message
			if($groupData['get_subs_name_check']=='1'){
				sendMessage($to,$from,DBout($groupData['msg_to_get_subscriber_name']),array(),$groupData['user_id'],$groupData['id']);
				boundNumber($to,$from,$userID,$groupData['id'],'sms');
				die();
			}
			if($groupData['get_email']=='1'){
				sendMessage($to,$from,DBout($groupData['reply_email']),array(),$groupData['user_id'],$groupData['id']);
				boundNumber($to,$from,$userID,$groupData['id'],'email');
				die();
			}
			// end
		}
		die();
	}
	function addFollowUpMessages($groupID,$userID,$subscriberID){
		global $link;
		$sqlf = "select * from follow_up_msgs where group_id='".$groupID."' and user_id='".$userID."'";
		$resf = mysqli_query($link,$sqlf);
		if(mysqli_num_rows($resf)){
			$appSettings = getAppSettings($userID);
			$timeZone	 = $appSettings['time_zone'];
			date_default_timezone_set($timeZone);
			$today = date('Y-m-d');
			while($rowf = mysqli_fetch_assoc($resf)){
				$delayDays = $rowf['delay_day'];
				$delayTime = $rowf['delay_time'];
				if($delayDays=='0'){
					$date	   = date('Y-m-d H:i:s',strtotime($delayTime.$today));
					$dateTime  = $date;
				}else{
					$date	   = date('Y-m-d',strtotime("+".$delayDays." days ".$today));
					$dateTime  = $date.' '.$delayTime.':00';
				}
				$message   = DBout($rowf['message']);
				$media	   = $rowf['media'];
				if(trim($message)!=''){
					$sqls = "insert into schedulers
					(scheduled_time,group_id,phone_number,message,media,scheduler_type,user_id)values
					('".$dateTime."','".$groupID."','".$subscriberID."','".$message."','".$media."','2','".$userID."')";
					mysqli_query($link,$sqls);
				}
			}	
		}
	}
	function removeMedia($filePath){
		$pathArray = @explode('/',$filePath);
		@end($pathArray);
		$key = @key($pathArray);
		$fileName = $pathArray[$key];
		@unlink('uploads/'.$fileName);
	}
	function checkUserPackageStatus($userID){
		if(isAdmin($userID)){
			$array = array();
			$array['message'] = '';
			$array['go'] = true;
			$array['remaining_credits'] = 5000;
			return $array;
		}else{
			$creditsLeft = 0;
			$userPackage = getAssingnedPackageInfo($userID);
			if($userPackage['status']=='1'){
				$today   = date('Y-m-d H:i');
				$endDate = date('Y-m-d H:i',strtotime($userPackage['end_date']));
				if($today>$endDate){ // Expires
					$message = 'Your package plan has expired, please add more credits or buy a new plan.';
					$go = false;
				}else{ // Credits end
					if($userPackage['used_sms_credits']>=$userPackage['sms_credits']){
						$message = 'Your sms credits are finished, please add more credits or buy a new plan.';
						$go = false;
					}else{
						$message = 'Success';
						$go = true;	
						$creditsLeft = ($userPackage['sms_credits']-$userPackage['used_sms_credits']);
					}
				}
			}else{ // suspended
				$message = 'Your package plan has suspended, please contact to administrator.';
				$go = false;
			}
		
			$array = array();
			$array['message'] = $message;
			$array['go'] = $go;
			$array['remaining_credits'] = $creditsLeft;
			return $array;
		}
	}
	function isAdmin($userID){
		global $link;
		$sql = "select type from users where id='".$userID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			if($row['type']=='1')
				return true;
			else
				return false;
		}
	}
	function checkUserNumberslimit($userID){
		global $link;
		$sql = "select id from users_phone_numbers where user_id='".$userID."'";
		$res = mysqli_query($link,$sql);
		return mysqli_num_rows($res);
	}
	function getAssingnedPackageInfo($userID){
		global $link;
		$sql = "select * from user_package_assignment where user_id='".$userID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			return mysqli_fetch_assoc($res);
		}
	}
    function getCampaingsLocations($userID){
        global $link;
        $sql = "select id from campaigns where user_id='".$userID."'";
        $res = mysqli_query($link,$sql);
        return mysqli_num_rows($res);
    }
    function getAppointmentReminders($userID){
        global $link;
        $sql = "select id from appointments where user_id='".$userID."'";
        $res = mysqli_query($link,$sql);
        return mysqli_num_rows($res);
    }
	function getPackageInfo($id){
		global $link;
		$sql = "select * from package_plans where id='".$id."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			return mysqli_fetch_assoc($res);
		}
	}
	function redirectToPaypal($userID,$pkgName,$pkgPrice,$webUserID,$pkgInfo=""){
		$appSettings = getAppSettings($userID);
        if($appSettings['payment_processor']==2){
            include_once("pay_with_authrize_recurring.php");       
        }else{
    		$redirectUrl = getServerUrl();
    		$notifyUrl   = getServerUrl().'/notify.php';
    		if($appSettings['paypal_switch']=='1'){ // Live
    			$endPoint	= 'https://www.paypal.com/cgi-bin/webscr';
    			$businessEmail = $appSettings['paypal_email'];
    		}else{
    			$endPoint	= 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    			$businessEmail = $appSettings['paypal_sandbox_email'];
    		}
    		echo "Redirecting to paypal...";
    		echo '<form action="'.$endPoint.'" name="" method="post" id="recurring_payment_form">
    			<input type="hidden" value="'.$businessEmail.'" name="business">
    			<input type="hidden" name="return" value="'.$redirectUrl.'" />
    			<input type="hidden" name="cancel_return" value="'.$notifyUrl.'" />
    			<input type="hidden" name="notify_url" value="'.$notifyUrl.'" />
    			<input type="hidden" name="cmd" value="_xclick-subscriptions" />
    			<input type="hidden" name="no_note" value="1" />
    			<input type="hidden" name="no_shipping" value="1">
    			<input type="hidden" name="currency_code" value="USD">
    			<input type="hidden" name="country" value="IN" />
    			<input type="hidden" value="'.$pkgName.' SMS Plan" name="item_name">
    			<input type="hidden" name="a3" value="'.$pkgPrice.'" />
    			<input type="hidden" name="p3" value="1" />
    			<input type="hidden" name="t3" value="M" />
    			<input type="hidden" name="src" value="1" />
    			<input type="hidden" name="sra" value="1" />
    			<input type="hidden" name="custom" value="'.$webUserID.'" />';
			
				if($pkgInfo['is_free_days']=='1'){
					echo '<input type="hidden" name="a1" value="0">';
					echo '<input type="hidden" name="p1" value="'.$pkgInfo['free_days'].'">';
					echo '<input type="hidden" name="t1" value="D">';
				}
			echo '</form>';
    		echo '<script>document.forms["recurring_payment_form"].submit();</script>';
        }
	}
	function getGroupData($groupID){
		global $link;
		$sql = "select * from campaigns where id='".$groupID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			return mysqli_fetch_assoc($res);
		}else{
			return false;	
		}
	}
	function getAppSettings($userID,$isAdmin=false){
		global $link;
		if($isAdmin)
			$sql = "select * from application_settings where user_type='1'";
		else
			$sql = "select * from application_settings where user_id='".$userID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			return mysqli_fetch_assoc($res);	
		}else
			return false;
	}
	function getAdminInfo(){
		global $link;
		$sql = "select * from users where type='1'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			return mysqli_fetch_assoc($res);	
		}else{
			return array();	
		}
	}
	function getUserInfo($userID){
		global $link;
		$sql = "select * from users where id='".$userID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			return mysqli_fetch_assoc($res);	
		}else{
			return array();	
		}
	}

	function getTimeObj($timeString){
        $timeStr = preg_replace('/\s+/', '', $timeString);
        $timeArr = explode(":", $timeStr);
        if (count($timeArr) == 2){
            return date("H:i:s", strtotime($timeString));
        }else {
            $timeStr = $timeArr[0] . ":" . $timeArr[1] . $timeArr[2];
            return date("G:i", strtotime($timeStr));
        }
    }

	function specialCharacters(){
		return array("'",'"',',','@','|','<','>','.');
	}
	function makeSubscriberBlocked($to,$from,$body,$smsSid,$userID){
		global $link,$appSettings;
		$sql = "insert into sms_history
				(to_number,from_number,text,media,sms_sid,direction,group_id,user_id)values
				('".$to."','".$from."','".$body."','','".$smsSid."','in-bound','','".$userID."')";
		mysqli_query($link,$sql);
		creditCount($userID,'sms','in');
		$sql = "select id,status from subscribers where phone_number='".$from."' and user_id='".$userID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			$id	 = $row['id'];
			if(isset($appSettings['time_zone']) && trim($appSettings['time_zone'])!=''){
				@date_default_timezone_set($appSettings['time_zone']);
				$unSubDateTime = date('Y-m-d H:i:s');
			}else{
				$unSubDateTime = date('Y-m-d H:i:s');
			}
			mysqli_query($link,"update subscribers set status='2', unsubscribe_date='".$unSubDateTime."' where id='".$id."'");
			mysqli_query($link,"update subscribers_group_assignment set status='2' where subscriber_id='".$id."'");
		}
	}
	function checkOptOutKeywords(){
		return array('stop','STOP','cancel','CANCEL','OPTOUT','optout','OPT-OUT','opt-out','remove','REMOVE','quit','QUIT','END','end');
	}
	function checkReserveKeywords(){
		return array('cancel','CANCEL','OPTOUT','optout','OPT-OUT','opt-out','remove','REMOVE','quit','QUIT','help','HELP','STOP','stop','START','start','END','end','RESERVE','reserve');
	}
	function addSubscriber($name="",$phoneNumber,$email="",$subsType="",$city,$state,$userID,$status,$customSubsInfo=''){
		global $link;
		$ins = "insert into subscribers
				(first_name,phone_number,city,state,user_id,status,email,subs_type,custom_info)values
				('".$name."','".$phoneNumber."','".$city."','".$state."','".$userID."','".$status."','".$email."','".$subsType."','".$customSubsInfo."')";
		mysqli_query($link,$ins);
		return mysqli_insert_id($link);
	}
	function assignGroup($subID,$groupID,$userID,$status){
		global $link;
		$sql = "select id from subscribers_group_assignment where subscriber_id='".$subID."' and group_id='".$groupID."' and user_id='".$userID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)==0){
			mysqli_query($link,"insert into subscribers_group_assignment
			(group_id,subscriber_id,user_id,status)values
			('".$groupID."','".$subID."','".$userID."','".$status."')");
		}else{
			$row = mysqli_fetch_assoc($res);
			mysqli_query($link,"update subscribers_group_assignment set
				status='".$status."'
			where
				id='".$row['id']."'");
		}
	}

	function sendMessage($from,$to,$body,$media=array(),$userID,$groupID="",$isChat=false,$win_bit=0){
		global $link,$remainingCredits;
		$settings	  = getAppSettings($userID);
		$adminSettings= getAppSettings('',true);
		$timeZone	= $settings['time_zone'];
		$appendText = $settings['append_text'];
		if(isset($timeZone)){
			@date_default_timezone_set($timeZone);
			$sentDate = date('Y-m-d H:i:s');
		}else{
			$sentDate = date('Y-m-d H:i:s');	
		}
		$usersWords = DBout($settings['banned_words']);
		$adminBannedWords = DBout($adminSettings['banned_words']);
		$bannedWords = trim($usersWords,',').','.trim($adminBannedWords,',');
		$bannedWords = @explode(',',$bannedWords);
		$bannedWords = array_map('trim',$bannedWords);
		$body = str_replace($bannedWords,'****',$body);
		if($isChat==false){
			$body .= ' '.$appendText;
		}
		if(!is_array($media)){
			$media = @explode(',',$media);
		}
		$smsSid  = '';
		$msgType = '';
		$isSent  = 'false';
		$body = DBout($body);
		if($remainingCredits>0){
			if(($from!='')&&($to!='')&&(trim($body)!='')){
				//mail('ahsan@nimblewebsolutions.com','from Send Message',$from.','.$to.','.$body.','.print_r($media,true));
				if($from=='mobile_sim'){ // Sending from mobile
					$deviceID = $settings['device_id'];
					if(trim($deviceID)!=''){
						$deviceInfo = getDeviceInfo($deviceID);
						$pathToFcmServer = 'https://fcm.googleapis.com/fcm/send';
						$fireBaseToken = $deviceInfo['device_token'];
						$androidAppServerKey = $adminSettings['android_app_server_key'];
						$headers = array(
							'Authorization:key=' .$androidAppServerKey,
							'Content-Type:application/json'
						);
						$toNumbers = array($to);
						$fields = array(
							'to' => $fireBaseToken,
							'data' => array('body'=>$body,'numbers'=>$toNumbers)
						);
						$payload = json_encode($fields);
						$curl_session = curl_init();
						curl_setopt($curl_session, CURLOPT_URL, $pathToFcmServer);
						curl_setopt($curl_session, CURLOPT_POST, true); 
						curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
						curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);
						$result = curl_exec($curl_session);
						$result = json_decode($result,true);
						mail('ahsan@nimblewebsolutions.com','from mobile sim',print_r($result,true).'___'.$body);
						if($result['success']=='1'){
							$smsSid = $result['results'][0]['message_id'];
							$isSent = 'true';
						}else{
							$smsSid = $result['failure'];
						}
					}else{
						$smsSid = 'No active device found.';
					}
				}else{
					if($settings['sms_gateway']=='twilio'){
						$client = getTwilioConnection($userID);
						$enableSenderID = $settings['enable_sender_id'];
						if($enableSenderID=='1'){
							$senderID = $settings['twilio_sender_id'];
							$from = $senderID;
						}
						try{	
							if(trim($media[0])!=''){
								$sms = $client->account->messages->sendMessage($from,$to,$body,$media);
								$msgType = 'mms';
							}else{
								$sms = $client->account->messages->sendMessage($from,$to,$body);
								$msgType = 'sms';
							}
							$smsSid = $sms->sid;
							$isSent = 'true';
						}catch(Services_Twilio_RestException $e){
							$smsSid = $e->getMessage();
						}
					}
					else if($settings['sms_gateway']=='plivo'){ // Plivo
						require_once("plivo/vendor/autoload.php");
						require_once("plivo/vendor/plivo/plivo-php/plivo.php");
						$p = new RestAPI($adminSettings['plivo_auth_id'], $adminSettings['plivo_auth_token']);
						$params = array(
							'src' => $from,
							'dst' => $to,
							'text' => $body
						);
						$msgType  = 'sms';
						$response = $p->send_message($params);
						if($response['status']=='202'){
							$smsSid = $response['response']['message_uuid'][0];
							$isSent = 'true';
						}else{
							$smsSid = $response['response']['error'];
						}
					}
					else if($settings['sms_gateway']=='nexmo'){
						$url = 'https://rest.nexmo.com/sms/json?' . http_build_query(array(
							  'api_key' =>  $adminSettings['nexmo_api_key'],
							  'api_secret' => $adminSettings['nexmo_api_secret'],
							  'to' => $to,
							  'from' => $from,
							  'text' => $body
						));
						$ch = curl_init($url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$response = curl_exec($ch);
						$response = json_decode(curl_exec($ch),true);
						if($response['messages'][0]['status']=='0'){
							$smsSid = $response['messages'][0]['message-id'];
							$isSent = 'true';
						}else{
							$smsSid = $response['messages'][0]['error-text'];	
						}
						$msgType  = 'sms';
					}
				}
				if($isChat==false){
					// Making History
					$media = @implode(',',$media);
					$smsSid = addslashes($smsSid);
					$sql = "insert into sms_history
								(
									to_number,
									from_number,
									text,
									media,
									sms_sid,
									direction,
									group_id,
									user_id,
									created_date,
									is_sent,
                                    win_bit
								)
							values
								(
									'".$to."',
									'".$from."',
									'".DBin($body)."',
									'".$media."',
									'".$smsSid."',
									'out-bound',
									'".$groupID."',
									'".$userID."',
									'".$sentDate."',
									'".$isSent."',
                                    '".$win_bit."'                                    
								)";
					mysqli_query($link,$sql) or die(mysqli_error($link));
					// end
				}
				creditCount($userID,$msgType,'out');
				return $smsSid;			
			}
		}else{
			/*
			$email = $adminSettings['admin_email'];
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
				$subject = '';
				$to		 = '';
				$from	 = '';
				$msg	 = '';
				$FullName= '';
				sendEmail($subject,$to,$from,$msg,$FullName);
			}
			*/
			return 'No sms credits.';	
		}
	}
	function creditCount($userID,$msgType,$direction){
		global $link;
		$appSettings = getAppSettings($userID,true);
		$charges 	 = 0;
		if($direction=='in'){
			if($msgType=='sms'){
				$charges = $appSettings['incoming_sms_charge'];
			}else{
				// incoming mms charge here.
			}
		}else{
			if($msgType=='sms'){
				$charges = $appSettings['outgoing_sms_charge'];
			}else{
				$charges = ($appSettings['mms_credit_charges']+$appSettings['outgoing_sms_charge']);
			}	
		}
		if(isAdmin($userID)){
			$sql = "update users set used_sms_credits=used_sms_credits+".$charges." where id='".$userID."'";
			mysqli_query($link,$sql);
		}else{
			$sql = "update user_package_assignment set used_sms_credits=used_sms_credits+".$charges." where user_id='".$userID."'";
			mysqli_query($link,$sql);
		}
	}
	function countWebforms($userID){
		global $link;
		$sql = "select id from webforms where user_id='".$userID."'";
		$res = mysqli_query($link,$sql);
		return mysqli_num_rows($res);
	}
	function countAutoresponders($userID){
		global $link;
		$sql = "select id from campaigns where user_id='".$userID."' and type='2'";
		$res = mysqli_query($link,$sql);
		return mysqli_num_rows($res);
	}
	function countCampaigns($userID){
		global $link;
		$sql = "select id from campaigns where user_id='".$userID."' and type='1'";
		$res = mysqli_query($link,$sql);
		return mysqli_num_rows($res);
	}
	function countUnSubscribers($userID){
		global $link;
		$sql = "select id from subscribers where user_id='".$userID."' and status='2'";
		$res = mysqli_query($link,$sql);
		return mysqli_num_rows($res);
	}
	function countSubscribers($userID){
		global $link;
		$sql = "select id from subscribers where user_id='".$userID."'";
		$res = mysqli_query($link,$sql);
		return mysqli_num_rows($res);
	}
	function getTwilioConnection($userID){
		global $link;
		$sql = "select * from application_settings where user_id='".$userID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			include_once("ts/Twilio.php");
			try{
				$client = new Services_Twilio($row['twilio_sid'],$row['twilio_token']);
				return $client;
			}catch (Services_Twilio_RestException $e) {
				//echo $e->getMessage();
				return false;
			}
		}else{
			return false;	
		}
	}
	function checkKeyword($userID,$keyword,$campignID=""){
		global $link;
		$reservekeywords = checkReserveKeywords();
		if(!in_array($keyword,$reservekeywords)){
			if(($campignID=='') || ($campignID=='0')){
				$sql = "select id from campaigns where lower(keyword)='".strtolower($keyword)."' and user_id='".$userID."'";
			}else{
				$sql = "select id from campaigns where lower(keyword)='".strtolower($keyword)."' and user_id='".$userID."' and id!='".$campignID."'";
			}
			$res = mysqli_query($link,$sql);
			if(mysqli_num_rows($res)==0)
				return true;
			else
				return false;
		}else{
			return false;	
		}
	}
	function getCurrentPageName(){
		$currentFile = $_SERVER["PHP_SELF"];
		$parts = explode('/', $currentFile);
		$Name = $parts[count($parts) - 1];
		return $Name;
	}
	function encode($str){
		$id=uniqid();
		$last=substr($id,strlen($id)-10);
		$start=rand(11,99);
		return $start.$str.$last;    
	}
	function decode($str){
		return substr($str,2,strlen($str)-12);    
	}
	function DBin($string){
		$a = html_entity_decode($string);
		return trim(htmlspecialchars($a,ENT_QUOTES));
	}
	function DBout($string){
		$string = stripslashes(trim($string));
		return str_replace("'","'",html_entity_decode($string,ENT_QUOTES,'UTF-8'));
	}
	function getExtension($str){
		$i = strrpos($str,".");
		if (!$i) { return ""; }
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
	}
	function getTimeArray(){
		$timeArray = array('00:00'=>'12:00 AM','00:15'=>'12:15 AM','00:30'=>'12:30 AM','00:45'=>'12:45 AM','01:00'=>'01:00 AM','01:15'=>'01:15 AM','01:30'=>'01:30 AM','01:45'=>'01:45 AM','02:00'=>'02:00 AM','02:15'=>'02:15 AM','02:30'=>'02:30 AM','02:45'=>'02:45 AM','03:00'=>'03:00 AM','03:15'=>'03:15 AM','03:30'=>'03:30 AM','03:45'=>'03:45 AM','04:00'=>'04:00 AM','04:15'=>'04:15 AM','04:30'=>'04:30 AM','04:45'=>'04:45 AM','05:00'=>'05:00 AM','05:15'=>'05:15 AM','05:30'=>'05:30 AM','05:45'=>'05:45 AM','06:00'=>'06:00 AM','06:15'=>'06:15 AM','06:30'=>'06:30 AM','06:45'=>'06:45 AM','07:00'=>'07:00 AM','07:15'=>'07:15 AM','07:30'=>'07:30 AM','07:45'=>'07:45 AM','08:00'=>'08:00 AM','08:15'=>'08:15 AM','08:30'=>'08:30 AM','08:45'=>'08:45 AM','09:00'=>'09:00 AM','09:15'=>'09:15 AM','09:30'=>'09:30 AM','09:45'=>'09:45 AM','10:00'=>'10:00 AM','10:15'=>'10:15 AM','10:30'=>'10:30 AM','10:45'=>'10:45 AM','11:00'=>'11:00 AM','11:15'=>'11:15 AM','11:30'=>'11:30 AM','11:45'=>'11:45 AM','12:00'=>'12:00 PM','12:15'=>'12:15 PM','12:30'=>'12:30 PM','12:45'=>'12:45 PM','13:00'=>'01:00 PM','13:15'=>'01:15 PM','13:30'=>'01:30 PM','13:45'=>'01:45 PM','14:00'=>'02:00 PM','14:15'=>'02:15 PM','14:30'=>'02:30 PM','14:45'=>'02:45 PM','15:00'=>'03:00 PM','15:15'=>'03:15 PM','15:30'=>'03:30 PM','15:45'=>'03:45 PM','16:00'=>'04:00 PM','16:15'=>'04:15 PM','16:30'=>'04:30 PM','16:45'=>'04:45 PM','17:00'=>'05:00 PM','17:15'=>'05:15 PM','17:30'=>'05:30 PM','17:45'=>'05:45 PM','18:00'=>'06:00 PM','18:15'=>'06:15 PM','18:30'=>'06:30 PM','18:45'=>'06:45 PM','19:00'=>'07:00 PM','19:15'=>'07:15 PM','19:30'=>'07:30 PM','19:45'=>'07:45 PM','20:00'=>'08:00 PM','20:15'=>'08:15 PM','20:30'=>'08:30 PM','20:45'=>'08:45 PM','21:00'=>'09:00 PM','21:15'=>'09:15 PM','21:30'=>'09:30 PM','21:45'=>'09:45 PM','22:00'=>'10:00 PM','22:15'=>'10:15 PM','22:30'=>'10:30 PM','22:45'=>'10:45 PM','23:00'=>'11:00 PM','23:15'=>'11:15 PM','23:30'=>'11:30 PM','23:45'=>'11:45 PM');
		return $timeArray;
	}
	function checkTwilioAccountStatus($userID){
		global $link;
		$sql = "select * from application_settings where user_id='".$userID."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			$url = 'https://'.$row['twilio_sid'].':'.$row['twilio_token'].'@api.twilio.com/2010-04-01/Accounts';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPGET,true);
			$response = curl_exec($ch);
			$response = simplexml_load_string($response);
			$accounts = $response->Accounts->Account;
			$array  = array();
			foreach($accounts as $account){
				if( ($account->Sid==$row['twilio_sid']) && ($account->AuthToken==$row['twilio_token']) ){
					$status = $account->Status;
					$type	= $account->Type;
					$accName= $account->FriendlyName;
					$array['status'] = $status;
					$array['type'] 	 = $type;
					$array['acc_name'] = $accName;
					return $array;
					break;
				}
			}
		}
	}
	function getTwilioCountries($sid,$token){
		$url = "https://$sid:$token@api.twilio.com/2010-04-01/Accounts/$sid/AvailablePhoneNumbers";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPGET,true);
		$response = curl_exec($ch);
		$response = simplexml_load_string($response);
		return $response;
	}
	function searchTwilioNumbers($client,$country,$state,$type,$areaCode,$contains){
		if(trim($country)=="")
			$country = 'US';
		if(trim($type)=="")
			$type = 'Local';
		try{
			$numbers = $client->account->available_phone_numbers->getList($country, $type,
				array(
					"AreaCode" => $areaCode,
					"Contains" => $contains,
					"InRegion" => $state
				)
			);
			return $numbers->available_phone_numbers;
		}catch(Services_Twilio_RestException $e){
			try{
				$type = 'Mobile';
				$numbers = $client->account->available_phone_numbers->getList($country, $type,
					array(
						"AreaCode" => $areaCode,
						"Contains" => $contains,
						"InRegion" => $state
					)
				);
				return $numbers->available_phone_numbers;
			}catch(Services_Twilio_RestException $e){
				//return $e->getMessage();	
				return false;
			}
		}
	}
	function getServerURL(){ // Updated version
		$protocol = ( ((!empty($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] !== 'off')) || ($_SERVER['SERVER_PORT'] == 443) ) ? "https://" : "http://";
		$domainName = $_SERVER['HTTP_HOST'];
		$filePath   = $_SERVER['REQUEST_URI'];
		$fullUrl = $protocol.$domainName.$filePath;
		$installURL = substr($fullUrl,0,strrpos($fullUrl,'/'));
		return $installURL;
	}
	function sendEmail($subject,$to,$from,$msg,$FullName){
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: <'.$to.'>'. "\r\n";
		$headers .= 'From: '.$FullName.' <'.$from.'>' . "\r\n";	
		mail($to, $subject, $msg, $headers);
	}
	function postData($url,$data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, true);
		//curl_setopt($ch, CURLOPT_HTTPGET, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	function exportSubscribers($campaignID,$userID){
		global $link;
		$filename = 'subscribers.csv';
		$fp = fopen($filename, "w");
		$line = "";
		$comma = "";
		$line .= $comma . 'SR#,First Name, Last Name, Phone Number, Email';
		$comma = ",";
		$line .= "\n";
		fputs($fp, $line);	
		$line = "";
		$comma = "";
		if($campaignID=='all'){
			$sql = "select first_name, last_name, phone_number, email, status from subscribers where user_id='".$userID."'";
		}else{
			$sql = "select s.first_name, s.last_name, s.phone_number, s.email, s.status from subscribers s, subscribers_group_assignment sga where sga.group_id='".$campaignID."' and sga.subscriber_id=s.id";
		}
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$index = 1;
			while($row=mysqli_fetch_assoc($res)){
				$line = "";
				$comma = "";
				$count++;
				$line .= $comma . '"'.$index++.'","'.$row['first_name'].'","'.$row['last_name'].'","'.$row['phone_number'].'","'.$row['email'].'"';
				$comma = ",";
				$line .= "\n";
				fputs($fp, $line);	
			}	
		}		
	}
	function importSubscribers($filename,$campaignID,$userID){
		global $link;
		$index = 0;
		$handle = fopen("uploads/$filename", "r");
		while(($data=fgetcsv($handle,1000,",")) !== FALSE){
			if($index>0){
				if($number = trim($data[0])==''){
					$_SESSION['message'] = 'No Data in it..';
				}else{
					$number    = trim($data[0]);
					$firstName = trim($data[1]);
					$lastName  = trim($data[2]);
                    $email  = trim($data[3]);
					$sql = "select id from subscribers where phone_number='".$number."'";
					$res = mysqli_query($link,$sql);
					if(mysqli_num_rows($res)==0){
						$import="INSERT into subscribers 
							(first_name, last_name, phone_number,email,user_id,subs_type) values
							('".$firstName."','".$lastName."','".$number."','".$email."','".$_SESSION['user_id']."','campaign')";
						mysqli_query($link,$import) or die(mysqli_error($link));
						$subsID = mysqli_insert_id($link);
						$sel = "select id from subscribers_group_assignment where subscriber_id='".$subsID."' and group_id='".$campaignID."'";
						$exe = mysqli_query($link,$sel) or die(mysqli_error($link));
						if(mysqli_num_rows($exe)=='0'){
							mysqli_query($link,"insert into subscribers_group_assignment (group_id,subscriber_id,user_id) values('".$campaignID."','".$subsID."','".$userID."')") or die(mysqli_error($link));
						}
					}else{
						$row = mysqli_fetch_assoc($res);
						$subsID = $row['id'];						
						$sel = "select id from subscribers_group_assignment where subscriber_id='".$subsID."' and group_id='".$campaignID."'";
						$exe = mysqli_query($link,$sel);
						if(mysqli_num_rows($exe)=='0'){
							mysqli_query($link,"insert into subscribers_group_assignment (group_id,subscriber_id,user_id) values('".$campaignID."','".$subsID."','".$userID."')");
						}	
					}
				}
			}
			$index++;
		}
	}
	function downloadFile($file){
		$mime = 'application/force-download';
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: close');
		readfile($file);
		exit();
	}
	function countries(){
		return $isoCountries = array(
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua And Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BA' => 'Bosnia And Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CG' => 'Congo',
			'CD' => 'Congo, Democratic Republic',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote D\'Ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands (Malvinas)',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island & Mcdonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran, Islamic Republic Of',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle Of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KR' => 'Korea',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Lao People\'s Democratic Republic',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia, Federated States Of',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'AN' => 'Netherlands Antilles',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory, Occupied',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts And Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre And Miquelon',
			'VC' => 'Saint Vincent And Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome And Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia And Sandwich Isl.',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard And Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad And Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks And Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Viet Nam',
			'VG' => 'Virgin Islands, British',
			'VI' => 'Virgin Islands, U.S.',
			'WF' => 'Wallis And Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);
	}
	function generatePaging($sql,$pagelink,$pageNum,$max_records_per_page){
		global $link;
		if($pageNum==1){
			$tmpRes = mysqli_query($link,$sql);
			$totalRecs = mysqli_num_rows($tmpRes);
			$_SESSION['TOTAL_RECORDS'] = $totalRecs;
		}
		$recStart = ((int)($pageNum-1) )*((int) $max_records_per_page);
		$totalRecs= $_SESSION['TOTAL_RECORDS'];
		$pagingString = '<table border="0" cellspacing="0" class="paging-string" cellpadding="0" style="float:right !important"><tr><td align="left" valign="middle" style="padding:5px 0px 5px 0px;">';
		$totalPages = ceil(((int)$totalRecs)/((int)$max_records_per_page));
		$pagingStartPage = 1;
		$pagingEndPage = $totalPages;
		if($pageNum>6)
			$pagingStartPage = $pageNum-5;
		
		if($pageNum<($totalPages-5))
			$pagingEndPage = $pageNum+5;
		
		if($pageNum>1){
			$prPage = $pageNum -1;
			$pagingString .= ' <a href="'.$pagelink.'page='. $prPage .'" ><span class="btn-grey">Previous</span></a> ';
		}
		for($i=$pagingStartPage;$i<=$pagingEndPage;$i++){
			if($pageNum==$i){
				$pagingString .= '<span class="btn-pages-active">'.$i.'</span>';
			}else{
				$pagingString .='<a href="'.$pagelink.'page='.$i.'" class="btn-pages-inactive">'.$i.'</a>';
			}
		}
		if($pageNum<$totalPages){
			$nePage = $pageNum + 1;
			$pagingString .= ' <a href="'.$pagelink.'page='. $nePage .'" ><span class="btn-grey">Next</span></a> ';
		}
		$pagingString .= '</td></tr></table>';		
		$sqlLIMIT = " LIMIT ". $recStart . " , " . $max_records_per_page;
		
		if($totalPages == 1){
			$a['pagingString'] = '';
			$a['limit'] = '';
		}else{
			$a['pagingString'] = $pagingString;
			$a['limit'] =  $sqlLIMIT;
		}
		return $a;
	}
    
    function boundNumber($to,$from,$userID,$groupID,$whatIsSent="",$questionID="0",$is_viral_code="0",$viral_code=""){
        global $link;
        $lease_date = date("Y-m-d H:i",strtotime(date("Y-m-d H:i")." + 24 hours"));
        $sel = "select id from bound_phones where to_number='".$to."' and from_number='".$from."' and user_id='".$userID."' and group_id='".$groupID."'";
    	$exe = mysqli_query($link,$sel);
    	if(mysqli_num_rows($exe)=='0'){
    		mysqli_query($link,"insert into bound_phones (to_number,from_number,user_id,group_id,lease_date,what_is_sent,question_id,is_viral_code,viral_code) values('".$to."','".$from."','".$userID."','".$groupID."','".$lease_date."','".$whatIsSent."','".$questionID."','".$is_viral_code."','".$viral_code."')");
    	}else{
            $row = mysqli_fetch_assoc($exe);
    	   	mysqli_query($link,"update bound_phones set lease_date = '".$lease_date."' where id = '".$row['id']."'");
    	}
    }
	function generateAPIKey(){
		global $link;
		$couponChars 	= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		$couponCharLen 	= (strlen($couponChars)-1);
		$couponLength 	= 20;
		$couponCode 	= '';
		$couponCheck 	= '0';
		while($couponCheck == '0'){
			for($i=0; $i<$couponLength; $i++){
				$couponCode .= $couponChars[rand(0,$couponCharLen)];
			}
			$sql = "select id from application_settings where api_key='".$couponCode."'";
			$res = mysqli_query($link,$sql);
			if(mysqli_num_rows($res)==0){
				$couponCheck = '1';
			}
		}
		return $couponCode;
	}
	function timeAgo($mysqlDateTime,$full=false){
		$now = new DateTime;
		$ago = new DateTime($mysqlDateTime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v){
			if($diff->$k){
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			}else{
				unset($string[$k]);
			}
		}
	
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
    
    
    function curl_process($url){   
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        $res = curl_exec($ch);
        $error = curl_error($ch);
        if($error!=""){
            return json_encode(array("error"=>$error));
        }
        return $res; 
    }
    
    function curl_process22($url,$data=""){   
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json',
                                                'Content-Type: application/json'
                                             ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);
        $error = curl_error($ch);
        echo $error;
        return $res; 
    }
    
    function getFirstQuestionID($groupID)
    {
        global $link;
    	$sql = "select * from trivia_questions where campaign_id='".$groupID."' order by id asc limit 1";
    	$res = mysqli_query($link,$sql);
    	if(mysqli_num_rows($res)>0)
    	{
    		$row = mysqli_fetch_assoc($res);
    		return $row['id'];
    	}
    	return false;
    }
    
    
    function getNextImmediateQuestion($nextImmediateQuestionID)
    {
        global $link;
    	$sql = "select * from trivia_questions where id='".$nextImmediateQuestionID."' limit 1";
    	$res = mysqli_query($link,$sql);
    	if(mysqli_num_rows($res)>0)
    	{
    		$row  = mysqli_fetch_assoc($res);
			$options      = 'Reply ';
			$msg1		  = $row['question'];	
			$sqlAns       = "select * from trivia_answers where question_id='".$row['id']."' order by id asc";
			$resAns		  = mysqli_query($link,$sqlAns) or die(mysqli_error($link));
			while($rowAns = mysqli_fetch_assoc($resAns))
			{
				$options .= $rowAns['value'].' for '.$rowAns['answer'].", \n\r";
			}
			$msg1 .= "\n\r".$options;
			return $msg1;
    	}
    }
    
    function getNextImmediateQuestionID($groupID,$lastQuestionID)
    {
        global $link;
    	$sql = "select * from trivia_questions where campaign_id='".$groupID."' and id >'".$lastQuestionID."' order by id asc limit 1";
    	$res = mysqli_query($link,$sql);
    	if(mysqli_num_rows($res)>0)
        {
            $row = mysqli_fetch_assoc($res);
  			return $row['id'];
    	}
    	return false;
    }
    
    
    function checkAnsArr($questionID, $key)
    {
        global $link;
    	$sqlCheckAns = "select sa.* , sq.question, sq.id as questionID from trivia_answers sa, trivia_questions sq where sq.id='".$questionID."' and sa.question_id=sq.id and sa.value='".$key."' limit 1";
    	//$sqlCheckAns = "select * from survey_answers where question_id='".$questionID."' and option_value = '".$key."' limit 1";
    	$exeCheckAns = mysqli_query($link,$sqlCheckAns) or die(__FILE__.'<br>'.mysqli_error($link));
    	if($exeCheckAns && mysqli_num_rows($exeCheckAns)>0)
    	{
    		return mysqli_fetch_assoc($exeCheckAns);
    	}
    	return false;
    }
    
    function createCouponCode()
    {
        global $link;
    	$couponChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    	$couponCharLen = (strlen($couponChars)-1);
    	$couponLength = 6;
    	$couponCode = '';
    	$couponCheck = '0';
    	while($couponCheck == '0')
    	{
    		for($i=0; $i<$couponLength; $i++)
    		{
    			$couponCode .= $couponChars[rand(0,$couponCharLen)];
    		}
    		$sql_coupon_check = "select id from viral_coupon_codes where code = '".$couponCode."' limit 1";
    		$exe_coupon_check = mysqli_query($link,$sql_coupon_check) or die(mysqli_error($link));
    		if($exe_coupon_check && mysqli_num_rows($exe_coupon_check)==0)
    		{
    			$couponCheck = '1';
    		}
    	}	
    	return strtolower($couponCode);
    }
    
    function addUserCoupon($phoneID,$groupID,$code)
    {
        global $link;
    	$ins = "insert into viral_coupon_codes 
    			(`phone_number_id`, `group_id`, `code`) values 
    			('".$phoneID."', '".$groupID."','".$code."')";	
    	mysqli_query($link,$ins) or die(mysqli_error($link));
    }
    
    function check_viral_coupon_code($code)
    {
        global $link;
    	$sql = "select * from viral_coupon_codes where code='".$code."' order by id asc limit 1";
    	$res = mysqli_query($link,$sql);
    	if(mysqli_num_rows($res)>0)
    	{
    		$row = mysqli_fetch_assoc($res);
    		return $row;
    	}
    	return false;
    }
    
    function addViralFriend($phoneNumberID,$parentPhoneNumberID,$groupID,$status=0)
    {	
        global $link;
        $sql = "select * from viral_friends where phone_number_id='".$phoneNumberID."' and parent_phone_id = '".$parentPhoneNumberID."' and group_id = '".$groupID."'";
    	$res = mysqli_query($link,$sql);
    	if(mysqli_num_rows($res)==0)
    	{
    	   $sqlfrnd = "insert into viral_friends 
        				(`phone_number_id`, `parent_phone_id`, `group_id`,`status`) values
        				('".$phoneNumberID."', '".$parentPhoneNumberID."', '".$groupID."','".$status."')";
        	mysqli_query($link,$sqlfrnd);
 	    }
    }

    function getSubscribersDetail($id){
        global $link;
        $sqlparentPhone = "select * from subscribers where id='".$id."'";
		$resparentPhone = mysqli_query($link,$sqlparentPhone) or die(mysqli_error($link));
		$rowparentPhone = mysqli_fetch_assoc($resparentPhone);
		return $rowparentPhone;
    }
    
    function count_friend($groupID,$parentPhoneID){
        global $link;
        $sqlcount = "select count(id) as count_friend from viral_friends where group_id='".$groupID."' and status ='1' and parent_phone_id='".$parentPhoneID."'";
        $rescount = mysqli_query($link,$sqlcount) or die(mysqli_error($link));
        $rowcount = mysqli_fetch_assoc($rescount);
        return $rowcount['count_friend'];
    }
    
    
    function addGiftTracking($phoneNumberID,$groupID,$userID,$winNumber)
    {
        global $link;
        if($winNumber == '1')
		{
			$isGift = '1';
			$currentCycle= getContestCycleNumber($groupID,$userID);
			
			$sqlUpdate = "insert into campaign_gift_track 
										(phone_number_id, campaign_id, client_id, counter, is_gift,gift_number,cycle_number) values 
										('".$phoneNumberID."','".$groupID."','".$userID."','0','".$isGift."','1','".$currentCycle."')";
			mysqli_query($link,$sqlUpdate);
			
			$newCycleNum = ($currentCycle+1);
			$upcycle_number = "update campaigns set 
									  contest_cycle_num='".$newCycleNum."' where 
									  id='".$groupID."' and
									  user_id='".$userID."' 
									  limit 1";
			mysqli_query($link,$upcycle_number);
			return $isGift;
		}
		else
		{
			$sql = "select id,counter,is_gift from campaign_gift_track where campaign_id='".$groupID."' and client_id='".$userID."' order by id desc limit 1";
			$res = mysqli_query($link,$sql);
			$row = mysqli_fetch_assoc($res);
			$currentMaxCounter = $row['counter'];
			$nextCounterValue  = $currentMaxCounter+1;
			
			if($nextCounterValue == $winNumber) // Winner found
			{
				$isGift = '1';
				$currentCycle = getContestCycleNumber($groupID,$userID);
				$newCycleNum 	= ($currentCycle+1);
				
				$sqlUpdate = "insert into campaign_gift_track 
							(phone_number_id, campaign_id, client_id, counter, is_gift,gift_number,cycle_number) values 
							('".$phoneNumberID."','".$groupID."','".$userID."','0','".$isGift."','".$nextCounterValue."','".$newCycleNum."')";	
				mysqli_query($link,$sqlUpdate);
				
				$upcycle_number = "update campaigns set 
										  contest_cycle_num='".$newCycleNum."' where 
										  id='".$groupID."' and
										  user_id='".$userID."' 
										  limit 1";
				mysqli_query($link,$upcycle_number);	
			}
			else
			{
				$isGift = '0';
				$currentCycle= getContestCycleNumber($groupID,$userID);
				
				$sqlUpdate = "insert into campaign_gift_track 
							(phone_number_id, campaign_id, client_id, counter, is_gift,gift_number,cycle_number) values 
							('".$phoneNumberID."','".$groupID."','".$userID."','".$nextCounterValue."','".$isGift."','".$nextCounterValue."','".$currentCycle."')";	
				mysqli_query($link,$sqlUpdate);
			}
			return $isGift;
		}	
	
        
    }
    
    function getContestCycleNumber($groupID,$userID)
    {
        global $link;
    	$selCycleNum = "select contest_cycle_num from campaigns where id='".$groupID."' and user_id='".$userID."' limit 1";
    	$resCycleNum = mysqli_query($link,$selCycleNum);
    	$rowCycleNum = mysqli_fetch_assoc($resCycleNum);
    	$currentCycle= $rowCycleNum['contest_cycle_num'];	
    	return $currentCycle;
    }
    
?>