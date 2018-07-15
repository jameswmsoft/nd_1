<?php
	include_once("header.php");
	include_once("left_menu.php");
	if($_REQUEST['id']!=''){
		$sql = "select * from campaigns where id='".$_REQUEST['id']."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			$cmd = 'update_autores';
			$buttonText = 'Update';
			$heading = 'Edit Autoresponder';
		}else
			$row = array();
	}else{
		$row = array();	
		$cmd = 'create_autores';
		$buttonText = 'Save';
		$heading = 'Create Autoresponder';
	}
?>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								<?php echo $heading?>
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location='view_autores.php'" />
							</h4>
							<p class="category">Create your awesome autoresponders here.</p>
						</div>
						<div class="content table-responsive">
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
							<div class="form-group">
								<label>Title*</label>
								<input type="text" name="title" parsley-trigger="change" required placeholder="Enter title..." class="form-control" value="<?php echo $row['title']?>">
							</div>
							<div class="form-group">
								<label>Keyword*</label>
								<input type="text" name="keyword" parsley-trigger="change" required placeholder="Enter keyword..." class="form-control" value="<?php echo $row['keyword']?>">
							</div>
							<div class="form-group">
								<label><input name="direct_subscription" <?php if($row['direct_subscription']=='1')echo 'checked="checked"';else echo '';?> value="1" type="checkbox" /> Direct subscription</label><br />
								<span class="category">Check this box to enable subscription without receiving a keyword, any message received on its number will be considered as a keyword and sender will receive messages of this autoresponder.</span>
							</div>
							<div class="form-group">
								<label>Phone Number*</label>
								<select name="phone_number" class="form-control">
									<option value="">- Select One -</option>
								<?php
									if($appSettings['sms_gateway']=='twilio'){
										$sel = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='1'";
									}else if($appSettings['sms_gateway']=='plivo'){
										$sel = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='2'";
									}
									else if($appSettings['sms_gateway']=='nexmo'){
										$sel = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='3'";
									}else{
										$sel = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."'";
									}
									$rec = mysqli_query($link,$sel);
									if(mysqli_num_rows($rec)){
										while($numbers = mysqli_fetch_assoc($rec)){
											if($row['phone_number']==$numbers['phone_number']){
												$selected = 'selected="selected"';	
											}
											echo '<option '.$selected.' value="'.$numbers['phone_number'].'">'.$numbers['phone_number'].'</option>';
										}	
									}
								?>	
								</select>
							</div>
							<div class="form-group">
								<label><input name="attach_mobile_device" <?php if($row['attach_mobile_device']=='1')echo 'checked="checked"';else echo '';?> value="1" type="checkbox" /> Attach mobile device</label>
							</div>
							<div class="form-group">
								<label>Welcome SMS*</label>
								<textarea name="welcome_sms" parsley-trigger="change" required placeholder="Enter welcome sms text..." class="form-control"><?php echo $row['welcome_sms']?></textarea>
							</div>
							<div class="form-group">
								<label>Office SMS*</label>
								<textarea name="already_member_sms" parsley-trigger="change" required placeholder="Enter sms text for office..." class="form-control"><?php echo $row['already_member_msg']?></textarea>
							</div>
							<div class="form-group">
								<label>Select Media</label>
								<input type="file" name="campaign_media" style="display:inline !important" />
								<input type="hidden" name="hidden_campaign_media" value="<?php echo $row['media']?>" />
								<?php 
									echo isMediaExists($row['media']);
								?>
							</div>
							<div class="form-group text-right m-b-0">
								<button class="btn btn-primary waves-effect waves-light" type="submit"> <?php echo $buttonText?> </button>
								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
								<input type="hidden" name="cmd" value="<?php echo $cmd?>" />
								<input type="hidden" name="campaign_id" value="<?php echo $row['id']?>" />
							</div>
						</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>