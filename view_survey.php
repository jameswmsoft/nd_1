<?php
	include_once("header.php");
	include_once("left_menu.php");
?>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">My Surveys
								<input type="button" class="btn btn-primary" value="Add New" style="float:right !important" onclick="window.location='add_survey.php'" />
							</h4>
							<p class="category">Your already saved list of surveys.</p>
							<div id="alertArea"></div>
						</div>
						<div class="content table-responsive table-full-width">
							<table class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>Survey</th>
										<th>Questions</th>
										<th>Response</th>
										<th>Share Link</th>
										<th>Created</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$sql = "select * from surveys where user_id='".$_SESSION['user_id']."'";
									$res = mysqli_query($link,$sql);
									if(mysqli_num_rows($res)){
										$index = 1;
										while($row=mysqli_fetch_assoc($res)){
								?>
										<tr>
											<td><?php echo $index++?></td>
											<td style="text-align:left"><?php echo $row['survey_name']?></td>
											<td>
												<?php 
													$sel = "select * from survey_questions where survey_id='".$row['id']."'";
													$exe = mysqli_query($link,$sel);
													echo mysqli_num_rows($exe);
												?>
											</td>
											<td>
												<?php 
													$sel = "select id from survey_responses where survey_id='".$row['id']."'";
													$exe = mysqli_query($link,$sel);
													echo mysqli_num_rows($exe);
												?>
											</td>
											<td><?php echo "<a href='".$row['survey_link']."' target='_blank'>".$row['survey_link']."</a>";?></td>
											<td><?php echo $row['create_date']?></td>
											<td>
												<a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="getSurveyLink('<?php echo $row['survey_link']?>')"><i class="fa fa-share-alt"></i></a>&nbsp;&nbsp;
												<i class="fa fa-trash-o" style="cursor:pointer; color:red" onclick="deleteSurvey('<?php echo $row['id']?>')"></i>
											</td>
										</tr>
								<?php
										}
									}else{
										
									}
								?>
									
								</tbody>								
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<input type="hidden" id="survey_url" value="" />
<?php include_once("footer.php");?>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Share survey.</h4>
      </div>
      <div class="modal-body">
        <p style="text-align:center">
			<img src="images/twitter-ico.png" width="30" onclick="postOnTwitter()" style="cursor:pointer" />&nbsp;
			<img src="images/fb-ico.png" width="30" onclick="postOnFacebook()" style="cursor:pointer" />&nbsp;
			<!--
			<img src="images/gplus-ico.png" width="30" />&nbsp;
			<img src="images/linkedin-ico.png" width="30" />&nbsp;
			-->
		</p>
      </div>
      <div class="modal-footer">
		  <span id="loading" style="display:none">Saving...</span>
    	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
	function postOnTwitter(){
		$('#loading').show();
		var surveyUrl = $('#survey_url').val();
		if(surveyUrl!=''){
			$.post('server.php',{"cmd":"post_survey_twitter",surveyUrl:surveyUrl},function(r){
				$('#loading').hide();
			});
		}else{
			$('#loading').hide();
			alert('Survey url is empty.');
		}
    }
	function getSurveyLink(surveyUrl){
		$('#survey_url').val(surveyUrl);
	}
	function postOnFacebook(){
		$('#loading').show();
		var surveyUrl = $('#survey_url').val();
		if(surveyUrl!=''){
			$.post('server.php',{"cmd":"post_survey_facebook",surveyUrl:surveyUrl},function(r){
				$('#loading').hide();
			});
		}else{
			$('#loading').hide();
			alert('Survey url is empty.');	
		}
	}
	function deleteSurvey(surveyID){
		if(confirm("Are you sure you want to delete this survey?")){
			$.post('server.php',{surveyID:surveyID,"cmd":"delete_survey"},function(r){
				window.location = 'view_survey.php';
			});	
		}
	}
</script>