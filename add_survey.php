<?php
	include_once("header.php");
	include_once("left_menu.php");
?>
<style>
#sortable li {
    margin: 0 3px 3px 3px;
    padding: 0.4em;
    /*padding-left: 1.5em;*/
    font-size: 1 em;
}
</style>
<link rel="stylesheet" href="css/bread_curms.css">
<link rel="stylesheet" href="css/bread_curms-1.css">
<script src="assets/js/modernizr.js"></script>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title"> Surveys &nbsp;<span id="loading" style="display:none">Saving...</span>
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location='view_survey.php'" />
							</h4>
							<p class="category">Saved surveys</p>
						</div>
						<div class="content table-responsive">
							<ol class="cd-breadcrumb triangle custom-icons" style="margin:0px;">
								<li class="current"><a href="javascript:void(0)" class="name_survey" onclick="showTab(this,'1')">1. Name Survey</a></li>
								<li><a href="javascript:void(0)" class="add_question" onclick="showTab(this,'2')">2. Add Questions</a></li>
								<li><a href="javascript:void(0)" class="save_share" onclick="showTab(this,'3')">3. Save & Share</a></li>
							</ol>
							
							<div class="row newSurveySection">
								<div class="col-md-12">
									<div class="form-group">
										<label>Name</label>
										<input type="text" name="survey_name" class="form-control" required value="<?php echo DBout($row['survey_name'])?>" />
									</div>
									<div class="form-group">
										<label>Description</label>
										<textarea name="survey_description" class="form-control" required><?php echo DBout($row['survey_desc'])?></textarea>
									</div>
									<div class="form-group">
										<input type="hidden" name="current_tab" value="name_survey" />
										<input type="button" value="Add Questions" class="btn btn-primary" onclick="saveWizard('name_survey')" />
										<input type="button" class="btn btn-default" value="Back" onclick="window.location='view_survey.php'" />
									</div>
								</div>
							</div>
							
							<div class="row addQuestionSection" style="display:none">
								<div class="col-md-3" style="min-height:400px;">
									<div class="dropdown" style="text-align:center">
										<button class="btn btn-primary dropdown-toggle" id="menu1" type="button" data-toggle="dropdown" style="margin-top:13px;">Add Questions<span class="caret"></span></button>
										<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
											<!--
											<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Comment Box</a></li>
											-->
											<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Multiple Choice</a></li>
											<!--
											<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Dropdown Menu</a></li>
											-->
											<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Star Rating</a></li>
											<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Vote</a></li>
											<!--
											<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Comparison</a></li>
											-->
											<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Emoticon</a></li>
										</ul>
									</div>
									<ul class="dragNdrop" style="list-style:none; margin-top:5px; padding:0px;" id="sortable"></ul>
								</div>
								<div class="col-md-5" style="min-height:400px; background-color:#fbfbfb; border-left:1px solid #f2f3f4; border-right:1px solid #f2f3f4;">
									<h4 style="margin-top:10px; font-weight:400">Build Question</h4>
									<div class="question_container"></div>
								</div>
								
								<div class="col-md-4" style="min-height:400px;">
									<h4 style="margin-top:10px; font-weight:400">Preview Question</h4>
									<div class="previewQuestion" style="border:1px solid #999; min-height:300px; padding:1px;">
										<input type="text" class="form-control preview" readonly="readonly" value="Your question will be displayed here." id="showEmoticonMsgPreivew" />
										<div class="previewImage" style="min-height:250px; padding:2px;">
											<img src="images/image_icon.png" width="270" height="240" id="showImagePreview" />
										</div>
										<div class="questionType" style="padding:2px; text-align:center"></div>
									</div>
								</div>
							</div>
							
							<div class="row saveShareSection" style="display:none">
								<h4 style="padding-left:15px; padding-right:15px; font-weight:400;">Woo Hoo! Your survey is saved and ready to share.</h4>
								<div class="col-md-4">
									<div class="" style="min-height:400px; border:1px solid #CCC"></div>
								</div>
								<div class="col-md-8" style="min-height:400px; background-color:#fbfbfb; border-left:1px solid #f2f3f4; border-right:1px solid #f2f3f4">
									<ul class="nav nav-tabs">
										<li class="active"><a data-toggle="tab" href="#share">Share</a></li>
										<li><a data-toggle="tab" href="#embed">Embed</a></li>
										<li><a data-toggle="tab" href="#survey_tab">Survey Tab</a></li>
									</ul>
									
									<div class="tab-content">
										<div id="share" class="tab-pane fade in active">
											<h3>Share</h3>
											<p>
												<h3>Survey URL:</h3>
												<input type="text" id="survey_url" value="" class="form-control" />
											</p>
											<p>
												<img src="images/twitter-ico.png" width="30" />&nbsp;
												<img src="images/fb-ico.png" width="30" onclick="postOnFacebook()" style="cursor:pointer" />&nbsp;
												<!--
												<img src="images/gplus-ico.png" width="30" />&nbsp;
												<img src="images/linkedin-ico.png" width="30" />&nbsp;
												-->
											</p>
										</div>
										<div id="embed" class="tab-pane fade">
											<h3>Embed</h3>
											<!--
											<textarea id="loadSuveryUrl" readonly="readonly" class="form-control" onclick="this.select()"></textarea>
											-->
											<div id="loadSuveryUrl"></div>
										</div>
										<div id="survey_tab" class="tab-pane fade">
											<h3>Survey tab.</h3>
											<p>Some content in survey tab.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<input type="hidden" name="current_survey_id" value="" />
<?php include_once("footer.php");?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
function postOnFacebook(){
	$('#loading').show();
	var surveyUrl = $('#survey_url').val();
	$.post('server.php',{"cmd":"post_survey_facebook",surveyUrl:surveyUrl},function(r){
		$('#loading').hide();
	});
}
function saveWizard(currentTab){
	//alert(currentTab);
	var surveyID = $('input[name="current_survey_id"]').val();
	if(currentTab=='name_survey'){
		var surveyName = $('input[name="survey_name"]').val();
		var surveyDesc = $('textarea[name="survey_description"]').val();
		if(surveyName!=""){
			$('#loading').show();
			$.post('server.php',{"cmd":"save_survey",surveyName:surveyName,surveyDesc:surveyDesc,surveyID:surveyID,"tab":currentTab},function(r){
				var json = $.parseJSON(r);
				if(json.error=='no'){
					$('input[name="current_survey_id"]').val(json.id);
					$('#survey_url').val(json.survey_url);
					$('#loading').html("<i style='font-size:14px; color:green'>"+json.message+"</i>");
					$('.add_question').attr("href",'javascript:void(0)');
					$('.add_question').attr("onclick","showTab(this,'2')");
					$('.save_share').attr("href",'javascript:void(0)');
					$('.save_share').attr("onclick","showTab(this,'3')");
					
					$(".name_survey").parent('li').removeClass('current');
					$(".add_question").parent('li').addClass('current');
					$('.newSurveySection').hide('slow');
					$('.addQuestionSection').show('slow');
					$('#loading').html('');
					$('#loading').hide();
				}else{
					$('#loading').html("<i style='font-size:14px; color:red'>"+json.message+"</i>");
				}
			});
		}
	}else if(currentTab=='add_question'){		
		var listItems = $(".dragNdrop li");
		listItems.each(function(idx,li){
			$(this).find('span').each(function(){
				if($(this).hasClass("preview")){
					$(this).removeClass("preview");
				}
			});
		});
		// Posting data
		var formID = $('.question_container').find('form').attr('id');
		var formData = new FormData(document.getElementById(formID));
		$.ajax({
			type:'POST',
			url: 'server.php?cmd=save_survey&tab='+currentTab+'&surveyID='+surveyID,
			data:formData,
			contentType: false,
			processData: false,
			success:function(data){
				$('#loading').html('');
				$('#loading').hide();
				$('.question_container').html('Question saved.');
			},error: function(data){
				$('#loading').html(data);
			}
		});
		// End
	}
	else if(currentTab=='save_share'){
		
	}
}
function showTab(obj,tabNumber){
	if(tabNumber=='1'){
		$(".name_survey").parent('li').addClass('current');
		$(".add_question").parent('li').removeClass('current');
		$(".save_share").parent('li').removeClass('current');
		
		$('.newSurveySection').show('slow');
		$('.addQuestionSection').hide('slow');
		$('.saveShareSection').hide('slow');
	}else if(tabNumber=='2'){
		$(".add_question").parent('li').addClass('current');
		$(".name_survey").parent('li').removeClass('current');
		$(".save_share").parent('li').removeClass('current');
		
		$('.newSurveySection').hide('slow');
		$('.addQuestionSection').show('slow');
		$('.saveShareSection').hide('slow');
	}else if(tabNumber=='3'){
		$(".save_share").parent('li').addClass('current');
		$(".name_survey").parent('li').removeClass('current');
		$(".add_question").parent('li').removeClass('current');
		
		$('.newSurveySection').hide('slow');
		$('.addQuestionSection').hide('slow');
		$('.saveShareSection').show('slow');
		var surveyID = $('input[name="current_survey_id"]').val();
	}
}
$(function(){
	var currentSurveyID = $('input[name="current_survey_id"]').val();
	if(currentSurveyID==''){
		$('.add_question').removeAttr("href");
		$('.add_question').removeAttr("onclick");
		$('.save_share').removeAttr("href");
		$('.save_share').removeAttr("onclick");
	}
	$("#sortable").sortable({placeholder: "ui-state-highlight"});
	$( "#sortable" ).disableSelection();
});
function showemoticonMSGPreview(e){
	$('.preview').val($('.QuestionText').val());
	$('.preview').text($('.QuestionText').val());
}
function readURL(input){
	if(input.files && input.files[0]){
		var reader = new FileReader();
		reader.onload = function(e){
			$('#showImagePreview').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}
$(document).on('change', '.question_image', function(){
	readURL(this);});

$('.dropdown li > a').click(function(e){
    if($(this).text()=='Comment Box'){
		$('.question_container').html(createCommentbox());
		$('.dragNdrop').append('<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="display:inline-block"></span><span class="preview" style="display:inline-block;width:100px;text-overflow:ellipsis;overflow:hidden;"></span><span class="fa fa-trash-o removeButton" style="float:right; margin:3px; cursor:pointer""></span></li>');
	}
	else if($(this).text()=='Multiple Choice'){
		$('#showImagePreview').attr('src', "images/image_icon.png");
		$('#showEmoticonMsgPreivew').val("");
		$('.question_container').html(createMultipleChoice());
		$('.dragNdrop').append('<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="display:inline-block"></span><span class="preview" style="display:inline-block;width:100px;text-overflow:ellipsis;overflow:hidden;"></span><span class="fa fa-trash-o removeButton" style="float:right; margin:3px; cursor:pointer""></span></li>');
		//$('.previewImage').html('<input type="text" class="form-control multiple_choices_preview"><br><input type="text" class="form-control multiple_choices_preview">');
	}
	else if($(this).text()=='Dropdown Menu'){
			
	}
	else if($(this).text()=='Star Rating'){
		$('#showImagePreview').attr('src', "images/image_icon.png");
		$('#showEmoticonMsgPreivew').val("");
		$('.question_container').html(createStarRating());
		$('.dragNdrop').append('<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="display:inline-block"></span><span class="preview" style="display:inline-block;width:100px;text-overflow:ellipsis;overflow:hidden;"></span><span class="fa fa-trash-o removeButton" style="float:right; margin:3px; cursor:pointer""></span></li>');
		//$('.questionType').html(starRatingPreview());
	}
	else if($(this).text()=='Vote'){
		$('#showImagePreview').attr('src', "images/image_icon.png");
		$('#showEmoticonMsgPreivew').val("");
		$('.question_container').html(createVoteQuestion());
		$('.dragNdrop').append('<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="display:inline-block"></span><span class="preview" style="display:inline-block;width:100px;text-overflow:ellipsis;overflow:hidden;"></span><span class="fa fa-trash-o removeButton" style="float:right; margin:3px; cursor:pointer""></span></li>');
		//$('.questionType').html(votePreview());	
	}
	else if($(this).text()=='Comparison'){
			
	}
	else if($(this).text()=='Emoticon'){
		$('#showImagePreview').attr('src', "images/image_icon.png");
		$('#showEmoticonMsgPreivew').val("");
		$('.question_container').html(createEmoticon());
		$('.dragNdrop').append('<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="display:inline-block"></span><span class="preview" style="display:inline-block;width:100px;text-overflow:ellipsis;overflow:hidden;"></span><span class="fa fa-trash-o removeButton" style="float:right; margin:3px; cursor:pointer""></span></li>');
		//$('.questionType').html(emoticonPreview());
	}
});

function createVoteQuestion(){
	var commentBox = '<form enctype="multipart/form-data" method="post" id="commentbox_form">';
	commentBox += '<textarea name="survey_question" class="form-control QuestionText" onkeyup="showemoticonMSGPreview(event)"></textarea><br>';
	commentBox += '<input type="file" name="question_media" class="question_image"><br>';
	commentBox += '<input type="hidden" name="question_type" value="vote_question"><br>';
	commentBox += '<input type="button" name="button" value="Save" class="btn btn-primary" onclick="saveWizard(&quot;add_question&quot;)" />';
	commentBox += '</form>';
	return commentBox;
}
function createMultipleChoice(){
	var commentBox = '<form enctype="multipart/form-data" method="post" id="commentbox_form">';
	commentBox += '<textarea name="survey_question" class="form-control QuestionText" onkeyup="showemoticonMSGPreview(event)"></textarea><br>';
	commentBox += '<div class="optionContainder">';
	commentBox += '<div style="margin-bottom:5px"><input type="text" name="multiple_choices[]" class="form-control" style="width:80%; display:inline" onkeyup="myKeyuUp(this)"></div>';
	commentBox += '<div style="margin-bottom:5px"><input type="text" onkeyup="myKeyuUp(this)" name="multiple_choices[]" class="form-control" style="width:80%; display:inline"></div>';
	commentBox += '</div>';
	commentBox += '<input type="hidden" name="question_type" value="multiple_choice_question">';
	commentBox += '<a href="javascript:void(0)" onclick="addMoreOption()" class="addMoreOptions">Add Option</a><br>';
	commentBox += '<input type="button" name="button" value="Save" class="btn btn-primary" onclick="saveWizard(&quot;add_question&quot;)" />';
	commentBox += '</form>';
	return commentBox;
}
function createStarRating(){
	var commentBox = '<form enctype="multipart/form-data" method="post" id="commentbox_form">';
	commentBox += '<textarea name="survey_question" class="form-control QuestionText" onkeyup="showemoticonMSGPreview(event)"></textarea><br>';
	commentBox += '<input type="file" name="question_media" class="question_image"><br>';
	commentBox += '<input type="hidden" name="question_type" value="star_rating_question"><br>';
	commentBox += '<input type="button" name="button" value="Save" class="btn btn-primary" onclick="saveWizard(&quot;add_question&quot;)" />';
	commentBox += '</form>';
	return commentBox;
}
function createEmoticon(){
	var commentBox = '<form enctype="multipart/form-data" method="post" id="commentbox_form">';
	commentBox += '<textarea name="survey_question" class="form-control QuestionText" onkeyup="showemoticonMSGPreview(event)"></textarea><br>';
	commentBox += '<input type="file" name="question_media" class="question_image"><br>';
	commentBox += '<input type="hidden" name="question_type" value="emoticon_question"><br>';
	commentBox += '<input type="button" name="button" value="Save" class="btn btn-primary" onclick="saveWizard(&quot;add_question&quot;)" />';
	commentBox += '</form>';
	return commentBox;
}
function createCommentbox(){
	var commentBox = '<form enctype="multipart/form-data" method="post" id="commentbox_form">';
	commentBox += '<textarea name="survey_question" class="form-control QuestionText" onkeyup="showemoticonMSGPreview(event)"></textarea><br>';
	commentBox += '<input type="file" name="question_media" class="question_image"><br>';
	commentBox += '<input type="hidden" name="question_type" value="comment_box"><br>';
	commentBox += '<input type="button" name="button" value="Save" class="btn btn-primary" onclick="saveWizard(&quot;add_question&quot;)" />';
	commentBox += '</form>';
	return commentBox;
}
$(document).on('click','.removeButton',function(){
	if(confirm("Are you sure you want to remove this question?")){
		$(this).closest("li").remove();
	}
});
function votePreview(){
	var emoticons = '<img src="images/like-green.png"/>&nbsp;&nbsp;&nbsp;<img src="images/dislike-red.png"/>';
	return emoticons;
}
function emoticonPreview(){
	var emoticons = '<img src="images/1-ico.png"/>&nbsp;<img src="images/2-ico.png"/>&nbsp;<img src="images/3-ico.png"/>&nbsp;<img src="images/4-ico.png"/>&nbsp;<img src="images/5-ico.png"/>';
	return emoticons;
}
function starRatingPreview(){
	var starRating = '<img src="images/star-silver.png"/>&nbsp;<img src="images/star-silver.png"/>&nbsp;<img src="images/star-silver.png"/>&nbsp;<img src="images/star-silver.png"/>&nbsp;<img src="images/star-silver.png"/>';
	return starRating;
}
var optionCount = 2;
function addMoreOption(){
	var qOption = '<div style="margin-bottom:5px"><input type="text" name="multiple_choices[]" class="form-control" style="width:80%; display:inline" onkeyup="myKeyuUp(this)">&nbsp;<span class="fa fa-trash removeChoice" style="cursor:pointer;color:red"></span></div>';
	if(optionCount<5){
		$('.optionContainder').append(qOption);
	}
	optionCount++;
	if(optionCount==5){
		$('.addMoreOptions').hide();
	}
}
function myKeyuUp(obj){
	//alert($(obj).val());
}
$(document).ready(function(){	
	$(document).on('click','.removeChoice',function(){
		if(confirm("Are you sure you want to remove this option?")){
			$(this).closest('div').remove();
			optionCount--;
			$('.addMoreOptions').show();
		}
	});
});
</script>