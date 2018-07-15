<?php
	include_once("header.php");
	include_once("left_menu.php");
	if($_REQUEST['id']!=''){
		$sql = "select * from schedulers where id='".$_REQUEST['id']."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
		}else{
			$row = array();
		}
	}
	if($row['send_immediate']=='1'){
		$immediate = 'checked="checked"';
		$dateSection = 'none';
	}else{
		$immediate = '';
		$dateSection = 'block';
	}
?>
<link href="assets/css/timepicki.css" rel="stylesheet" />
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								Edit SMS Scheduler
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location='view_scheduler.php'" />
							</h4>
							<p class="category" style="color:#F00"><?php echo date('g:iA \o\n l jS F Y')?></p>
						</div>
						<div class="content table-responsive">
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
							<div class="form-group">
								<label>Title*</label>
								<input type="text" name="title" parsley-trigger="change" required placeholder="Enter title..." class="form-control" value="<?php echo DBout($row['title'])?>">
							</div>
							<div class="dateTimeSection" style="display:<?php echo $dateSection?>">
								<div class="form-group">
									<label>Date*</label>
									<input type="text" class="form-control addDatePicker" name="date" parsley-trigger="change" required value="<?php echo date('Y-m-d',strtotime($row['scheduled_time']));?>">
								</div>
								<div class="form-group">
                                    <label>Time*</label>
                                    <input type="text" name="time" id="time" class="form-control" required  value="<?php echo date('h:i A',strtotime($row['scheduled_time']));?>"/>
<!--									--><?php
//										$chtime = date('H:i',strtotime($row['scheduled_time']));
//									?>
<!--									<label>Time*</label>-->
<!--									<select name="time" class="form-control" parsley-trigger="change" required>-->
<!--									--><?php
//										$time = getTimeArray();
//										foreach($time as $key => $value){
//											if($chtime==$key){
//												$selected = 'selected="selected"';
//											}else{
//												$selected = '';
//											}
//											echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
//										}
//									?>
<!--									</select>-->
								</div>
							</div>
							<div class="form-group">
								<label><input <?php echo $immediate?> name="send_immediate" value="1" type="checkbox" /> Send Immediate</label>
							</div>
							<div class="form-group">
								<label><input name="attach_mobile_device" <?php if($row['attach_mobile_device']=='1')echo 'checked="checked"';else echo '';?> value="1" type="checkbox" /> Attach mobile device</label>
							</div>
							<div class="form-group">
								<label>Select Group</label>
								<select class="form-control" name="group_id" onChange="getGroupNumbers(this.value,'<?php echo $row['phone_number']?>')" parsley-trigger="change" required>
									<option value="">- Select One -</option>
								<?php
									$sqlg = "select id, title from campaigns where user_id='".$_SESSION['user_id']."'";
									$resg = mysqli_query($link,$sqlg);
									if(mysqli_num_rows($resg)){
										while($rowg = mysqli_fetch_assoc($resg)){
											if($row['group_id']==$rowg['id'])
												$sel = 'selected="selected"';
											else
												$sel = '';
											echo '<option '.$sel.' value="'.$rowg['id'].'">'.$rowg['title'].'</option>';
										}	
									}else{
										echo '<option value="">No group found</option>';						
									}
								?>		
								</select>
							</div>
							<div class="form-group">
								<label>Select Number</label>
								<select name="phone_number" class="form-control" id="list_group_number"></select>
							</div>
							<div class="form-group">
								<label>Message</label>
								<textarea name="message" class="form-control textCounter" parsley-trigger="change" required><?php echo DBout($row['message'])?></textarea>
								<span class="showCounter">
									<span class="showCount"><?php echo $maxLength-strlen(DBout($row['message']))?></span> Characters left
								</span>
							</div>
							<div class="form-group">
								<label>Select Survey (Optional)</label>
								<select class="form-control" name="survey_url">
									<option value="">- Select One -</option>
								<?php
									$sqlg = "select survey_name,survey_link from surveys where user_id='".$_SESSION['user_id']."'";
									$resg = mysqli_query($link,$sqlg);
									if(mysqli_num_rows($resg)){
										while($rowg = mysqli_fetch_assoc($resg)){
											if($row['survey_url']==$rowg['survey_link'])
												$sel = 'selected="selected"';
											else
												$sel = '';
											echo '<option '.$sel.' value="'.$rowg['survey_link'].'">'.$rowg['survey_name'].'</option>';
										}	
									}else{
										echo '<option value="">No survey found</option>';						
									}
								?>		
								</select>
							</div>
							<div class="form-group">
								<label>Media</label>
								<input type="file" name="media" style="display:inline !important">
								<input type="hidden" name="hidden_media" value="<?php echo $row['media']?>">
								<?php 
									echo isMediaExists($row['media']);
								?>
							</div>
							<div class="form-group text-right m-b-0">
								<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
								<input type="hidden" name="cmd" value="update_scheduler" />
								<input type="hidden" name="scheduler_id" value="<?php echo $row['id']?>">
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
<script src="scripts/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="scripts/js/parsley.min.js"></script>
<script src="assets/js/timepicki.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#time').timepicki();
    });
</script>
<script>
	jQuery('#datepicker-autoclose').datepicker({
		autoclose: true,
		todayHighlight: true
	});
	function getGroupNumbers(groupID,numberID){
		var Qry = 'cmd=get_group_numbers&group_id='+groupID+'&numberID='+numberID;
		$.post('server.php',Qry,function(r){
			$('#list_group_number').html(r);
		});
	}
	$(document).ready(function(){
		$('input[name=send_immediate]').on('change',function(){
			if($(this).is(":checked")==true){
				$('.dateTimeSection').hide('slow');
			}else{
				$('.dateTimeSection').show('slow');
			}
		});
		$('form').parsley();
	});
</script>
<?php if(trim($_REQUEST['id'])!=''){?>
	<script>getGroupNumbers('<?php echo $row['group_id']?>','<?php echo $row['phone_number']?>')</script>
<?php }?>