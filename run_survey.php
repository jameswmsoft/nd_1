<?php
$surveyID = $_REQUEST['survey_id'];
$userID = $_REQUEST['uid'];
$sql = "select * from surveys where id='".$surveyID."'";
$res = mysqli_query($link,$sql);
if(mysqli_num_rows($res)){
	$surveyData = mysqli_fetch_assoc($res);
	// Making survey response
	$ins = "insert into survey_responses
				(
					survey_id,
					user_id
				)
			values
				(
					'".$surveyData['id']."',
					'".$userID."'
				)";
	$rr = mysqli_query($link,$ins);
	$attemptID = mysqli_insert_id($link);
	// End
?>	
<style>
.surveyEmoticons{
	width:100px;
}
</style>
	<div class="main_container" id="mainQuestionContainer" style="text-align:center; margin:0 auto; width:auto">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<div class="survey_title_container">
		<h3><?php echo $surveyData['survey_name']?><span id="loadingQuestion" style="display:none; margin-left:20px;font-size:normal;color:red">Loading...</span></h3>
		</div>
		<div class="question_container">
		<?php
			$sel = "select * from survey_questions where survey_id='".$surveyID."' order by id asc limit 1";
			$exe = mysqli_query($link,$sel);
			if(mysqli_num_rows($exe)){
				$questionData = mysqli_fetch_assoc($exe);
				$questionID = $questionData['id'];
				$questionType = $questionData['question_type'];
				// Options
				if($questionType=='comment_box'){
					echo '<p>';
						echo $questionData['question'];
						
					echo '</p>';
					echo '<p>';
						echo '<img src="'.getServerUrl().'/uploads/'.$questionData['media'].'" width="100%" />';
					echo '</p>';
				}
				else if($questionType=='star_rating_question'){
					echo '<p>';
						echo $questionData['question'];
					echo '</p>';
					echo '<p>';
						echo '<img src="'.getServerUrl().'/uploads/'.$questionData['media'].'" width="100%" />';
					echo '</p>';
					echo '<p>';
						echo '<img src="'.getServerUrl().'/images/star-silver.png" style="margin-right:10px; cursor:pointer" alt="1" onclick="getUserResponse(this)" onmouseover="getMouseOver(this)" onmouseout="getMouseOut(this)" title="1" class="surveyEmoticons" />';
						echo '<img src="'.getServerUrl().'/images/star-silver.png" style="margin-right:10px; cursor:pointer" alt="2" onclick="getUserResponse(this)" onmouseover="getMouseOver(this)" onmouseout="getMouseOut(this)" title="2" class="surveyEmoticons" />';
						echo '<img src="'.getServerUrl().'/images/star-silver.png" style="margin-right:10px; cursor:pointer" alt="3" onclick="getUserResponse(this)" onmouseover="getMouseOver(this)" onmouseout="getMouseOut(this)" title="3" class="surveyEmoticons" />';
						echo '<img src="'.getServerUrl().'/images/star-silver.png" style="margin-right:10px; cursor:pointer" alt="4" onclick="getUserResponse(this)" onmouseover="getMouseOver(this)" onmouseout="getMouseOut(this)" title="4" class="surveyEmoticons" />';
						echo '<img src="'.getServerUrl().'/images/star-silver.png" style="margin-right:10px; cursor:pointer" alt="5" onclick="getUserResponse(this)" onmouseover="getMouseOver(this)" onmouseout="getMouseOut(this)" title="5" class="surveyEmoticons" />';
					echo '</p>';
				}
				else if($questionType=='vote_question'){
					echo '<p>';
						echo $questionData['question'];
					echo '</p>';
					echo '<p>';
						echo '<img src="'.getServerUrl().'/uploads/'.$questionData['media'].'" width="100%" />';
					echo '</p>';
					echo '<p>';
						echo '<img src="'.getServerUrl().'/images/like-green.png" style="margin-right:10px; cursor:pointer" alt="like-green.png" onclick="getUserResponse(this)" class="surveyEmoticons" />';
						echo '<img src="'.getServerUrl().'/images/dislike-red.png" style="margin-right:10px; cursor:pointer" alt="dislike-red.png" onclick="getUserResponse(this)" class="surveyEmoticons" />';
					echo '</p>';
				}
				else if($questionType=='emoticon_question'){
					echo '<p>';
						echo $questionData['question'];
					echo '</p>';
					echo '<p>';
						echo '<img src="'.getServerUrl().'/uploads/'.$questionData['media'].'" width="100%" />';
					echo '</p>';
					echo '<p>';
						echo '<img src="'.getServerUrl().'/images/1-ico.png" style="margin-right:10px; cursor:pointer" alt="1-ico.png" onclick="getUserResponse(this)" class="surveyEmoticons" />';
						echo '<img src="'.getServerUrl().'/images/2-ico.png" style="margin-right:10px; cursor:pointer" alt="2-ico.png" onclick="getUserResponse(this)" class="surveyEmoticons" />';
						echo '<img src="'.getServerUrl().'/images/3-ico.png" style="margin-right:10px; cursor:pointer" alt="3-ico.png" onclick="getUserResponse(this)" class="surveyEmoticons" />';
						echo '<img src="'.getServerUrl().'/images/4-ico.png" style="margin-right:10px; cursor:pointer" alt="4-ico.png" onclick="getUserResponse(this)" class="surveyEmoticons" />';
						echo '<img src="'.getServerUrl().'/images/5-ico.png" style="margin-right:10px; cursor:pointer" alt="5-ico.png" onclick="getUserResponse(this)" class="surveyEmoticons" />';
					echo '</p>';
				}
				else if($questionType=='multiple_choice_question'){
					echo '<p>';
						echo $questionData['question'];
					echo '</p>';
					echo '<ul style="list-style-type:none; text-align:left">';
						$questionOptions = @explode(',',$questionData['answers']);
						for($i=0;$i<count($questionOptions);$i++){
							echo '<li><label class="radio"><input type="radio" name="multiple_choice" value="'.$questionOptions[$i].'" onclick="getUserResponse(this)">'.$questionOptions[$i].'</label></li>';
						}
					echo '</ul>';
				}
				// end options
			}else{
				echo 'No question found relating this survey.';
			}
		echo '<input type="hidden" id="nmAttemptID" value="'.$attemptID.'" />';	
		echo '<input type="hidden" id="nmSurveyID" value="'.$surveyID.'" />';
		echo '<input type="hidden" id="nmQuestionType" value="'.$questionType.'" />';
		echo '<input type="hidden" id="nmQuestionID" value="'.$questionID.'" />';
		echo '</div>';
	echo '</div>';
}else{
	echo 'Wrong survey url.';
}
?>
<script>
	function getMouseOut(obj){
		$(obj).attr('src','<?php echo getServerUrl()?>/images/star-silver.png')
	}
	function getMouseOver(obj){
		$(obj).attr('src','<?php echo getServerUrl()?>/images/star-gold.png')
	}
	function getUserResponse(obj){
		$('#loadingQuestion').show();
		var questionType = $('#nmQuestionType').val();
		var questionID   = $('#nmQuestionID').val();
		var surveyID = $('#nmSurveyID').val();
		var nmAttemptID = $('#nmAttemptID').val();
		if(questionType=='multiple_choice_question'){
			var rating = $(obj).val();
			$.post('<?php echo getServerUrl().'/server.php'?>',{"cmd":"get_survey_response",rating:rating,questionType:questionType,questionID:questionID,surveyID:surveyID,nmAttemptID:nmAttemptID},function(r){
				$('.question_container').html(r);
				$('#loadingQuestion').hide();
			});
		}else{
			var rating = $(obj).attr('alt');
			$.post('<?php echo getServerUrl().'/server.php'?>',{"cmd":"get_survey_response",rating:rating,questionType:questionType,questionID:questionID,surveyID:surveyID,nmAttemptID:nmAttemptID},function(r){
				$('.question_container').html(r);
				$('#loadingQuestion').hide();
			});
		}
	}
	window.onload = function(){
		if(window.jQuery){
			// jQuery is loaded  
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