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
							<h4 class="title">
								Bulk SMS
								<!--<input type="button" class="btn btn-primary" value="Add New" style="float:right !important" onclick="window.location='test.php'" />-->
							</h4>
							<p class="category">Create bulk sms here.</p>
						</div>
						<div class="content table-responsive">
							<form method="post" action="server.php" enctype="multipart/form-data">
							<div class="form-group">
								<label>Message</label>
								<span style="clear: both !important;color:#451e89;display: block;font-size: 12px;">Merge tags: Name = %name%</span>
								<textarea class="form-control textCounter" name="bulk_sms" required></textarea>
								<span class="showCounter">
									<span class="showCount"><?php echo $maxLength?></span> Characters left
								</span>
							</div>
                            <div class="form-group">
								<label>Select Media</label>
								<input type="file" name="bulk_media" style="display:inline !important" />
							</div>
							<div class="form-group">
								<input type="hidden" name="cmd" value="save_bulk_sms">
								<input type="submit" value="Save" class="btn btn-primary">
							</div>
						</form>
						</div>
						
						<div class="content table-responsive table-full-width">
							<table id="bulkSMSTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th width="60%">Message</th>
										<th>Send</th>
										<th>Media</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
							<?php
								$sel = "select * from bulk_sms where user_id='".$_SESSION['user_id']."' order by id desc";
								if(is_numeric($_GET['page']))
									$pageNum = $_GET['page'];
								else
									$pageNum = 1;
								$max_records_per_page = 20;
								$pagelink 	= "bulk_sms.php?";
								$pages 		= generatePaging($sel,$pagelink,$pageNum,$max_records_per_page);
								$limit 		= $pages['limit'];
								$sel 	   .= $limit;
								if($pageNum==1)
									$countPaging=1;
								else
									$countPaging=(($pageNum*$max_records_per_page)-$max_records_per_page)+1;
											
								if($_SESSION['TOTAL_RECORDS'] <= $max_records_per_page){
									$maxLimit = $_SESSION['TOTAL_RECORDS'];	
								}else{
									$maxLimit = (((int)$countPaging+(int)$max_records_per_page)-1);
								}
								if($maxLimit >= $_SESSION['TOTAL_RECORDS']){
									$maxLimit = $_SESSION['TOTAL_RECORDS'];	
								}

								$exe = mysqli_query($link,$sel);
								if(mysqli_num_rows($exe)){
									$index = $countPaging;
									while($row = mysqli_fetch_assoc($exe)){
							?>
										<tr>
											<td><?php echo $index++?></td>
											<td style="text-align:left"><?php echo DBout($row['message'])?></td>
											<td>
												<a data-target="#custom-modal" data-toggle="modal" title="Send bulk sms" onClick="getSMSID('<?php echo $row['id']?>')"><i class="btn btn-warning btn-custom btn-rounded">Send</i></a>
											</td>
                                            <td>
												<?php 
													echo isMediaExists($row['bulk_media']);
												?>
                                            </td>
											<td style="text-align:center">
												<a href="edit_bulk_sms.php?id=<?php echo $row['id']?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
												<i onclick="deleteBulkSMS('<?php echo $row['id']?>')" style="color:red; cursor:pointer" class="fa fa-remove"></i>
											</td>
										</tr>
							<?php			
									}	
								}
							?>	
								<tr>
									<td colspan="5" style="padding-left:0px !important;padding-right:0px !important"><?php echo $pages['pagingString'];?></td>
				</tr>
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
<?php include_once("footer.php");?>
<div id="custom-modal" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Send Bulk SMS</h6>
			</div>
			
			<div class="modal-body buklSMSBody">
				<div class="form-group">
					<form action="server.php" method="post">
					<table width="100%" id="bulksmstable">
						<tr>
							<td align="left" width="25%"><label>Select Type</label></td>
							<td align="left">
							<label class="radio-inline">
								<input type="radio" name="bulk_type" class="bulk_type" value="1" checked>Single number/Group
							</label>
							<label class="radio-inline">
								<input type="radio" name="bulk_type" class="bulk_type" value="2">Date range
							</label>
							</td>
						</tr>
						<?php
						if($_SESSION['user_type']==1){
							$display="";
						}else{
							$display="none";
						}
						?>
						<tr style="display:<?php echo $display; ?>;">
							<td align="left"><label>Choose Account</label></td>
							<td>
								<select name="client_id" id="client_id" class="form-control" onchange="getAccountGroups(this.value)">
									<option value="all">All Accounts</option>
								<?php
									$seln = "select id, first_name, last_name, business_name from users where status='1'";
									$resn = mysqli_query($link,$seln);
									if(mysqli_num_rows($resn)){
										while($rown = mysqli_fetch_assoc($resn)){
											if($rown['id']==$_SESSION['user_id']){ $sel = "selected='selected'"; } else { $sel = ""; }
											echo '<option '.$sel.' value="'.$rown['id'].'">'.$rown['first_name'].' '.$rown['last_name'].' ('.$rown['business_name'].')</option>';
										}	
									}
									else{
										echo '<option value="">No phone number found.</option>';	
									}
								?>
								</select>
							</td>
						</tr>
						
						<tr>
							<td align="left"><label>From Number</label></td>
							<td>
							<?php
								echo '<select name="from_number" id="twilio_numbers" class="form-control" required>';
									if($appSettings['sms_gateway']=='twilio'){
										$seln = "select id, phone_number from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='1'";
									}else if($appSettings['sms_gateway']=='plivo'){
										$seln = "select id, phone_number from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='2'";
									}else if($appSettings['sms_gateway']=='nexmo'){
										$seln = "select id, phone_number from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='3'";
									}
									$resn = mysqli_query($link,$seln);
									if(mysqli_num_rows($resn)){
										while($rown = mysqli_fetch_assoc($resn)){
											echo '<option value="'.$rown['phone_number'].'">'.$rown['phone_number'].'</option>';
										}	
									}
									if( ($appSettings['device_id']!='') && ($appSettings['device_id']!='0') ){
										echo '<option value="mobile_sim">From Mobile Device</option>';
									}
								echo '</select>';
							?>
							</td>
						</tr>
						<!-- Single Number -->
						<tr class="single_group">
							<td align="left"><label>Select Group</label></td>
							<td>
								<select name="group_id" id="group_list" class="form-control" onChange="getGroupNumbers(this.value)" required>
									<option value="">Select One</option>
									<option value="all">All Groups</option>
									<?php
									
										$sql = "select id,title from campaigns where user_id='".$_SESSION['user_id']."'";
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											while($row = mysqli_fetch_assoc($res)){
												echo '<option value="'.$row['id'].'">'.DBout($row['title']).'</option>';
											}	
										}
									
									?>	
								</select>
							</td>
						</tr>
						<tr class="single_group phoneListRow">
							<td align="left"><label>Select Number</label></td>
							<td>
								<select name="phone_number_id" id="phoneid" class="form-control" required>
									<option value="">Select One</option>
								</select>
							</td>
						</tr>
						<!-- Single Number End -->
						
						<tr class="daterange" style="display:none">
							<td align="left"><label>Date range</label></td>
							<td>
								<input type="text" class="form-control addDatePicker" style="width:48% !important; display:inline !important" name="start_date" placeholder="Start date.">
								<input type="text" class="form-control addDatePicker" style="width:48% !important; display:inline !important" name="end_date" placeholder="End date.">
							</td>
						</tr>
						<tr class="daterange" style="display:none">
							<td align="left"><label>Select Group</label></td>
							<td>
								<select name="daterange_group_id" id="daterange_group_id" class="form-control">
									<option value="">Select One</option>
									<option value="all">All Groups</option>
									<?php
										$sql = "select id,title from campaigns where user_id='".$_SESSION['user_id']."'";
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											while($row = mysqli_fetch_assoc($res)){
												echo '<option value="'.$row['id'].'">'.DBout($row['title']).'</option>';
											}	
										}
									?>	
								</select>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td align="left">
								<!-- <input type="button" value="Send Now" class="btn btn-danger" onclick="sendBulkSMS()" />-->
								<input type="submit" value="Send Now" class="btn btn-success" />
								&nbsp;<img src="images/busy.gif" id="loading" style="display:none">&nbsp;<span id="showresponse"></span>
								<input type="hidden" name="hidden_sms_id" id="hidden_sms_id" value="">
								<input type="hidden" name="cmd" value="process_bulk_sms" />
							</td>
						</tr>
					</table>
					</form>
				</div>
			</div>
			<!--
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			-->
		</div>
	</div>
</div>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>

<script src="scripts/js/custombox.min.js"></script>
<script src="scripts/js/legacy.min.js"></script>

<script type="text/javascript" src="scripts/js/parsley.min.js"></script>
<script type="text/javascript">
	$('#custom-modal').on('shown.bs.modal', function() {
		$( ".addDatePicker" ).datepicker({
			inline: true,
			dateFormat: 'yy-mm-dd'
		});
	});
	
	$('#bulkSMSTable').cardtable();
	function sendBulkSMS(smsID){
		$('#showresponse').html('');
		var sendType = $('input[name=bulk_type]:checked').val();
		var fromNumber = $('select[name=from_number] option:selected').val();
		var smsID	 = $('#hidden_sms_id').val();
		if(fromNumber==''){
			alert('Select a from number.');	
			return false;
		}
		if(sendType=='1'){
			var groupID  = $('select[name="group_id"] option:selected').val();
			var numberID = $('select[name="phone_number_id"] option:selected').val();
			if(($.trim(groupID)=='') || ($.trim(numberID)=='')){
				alert('All fields are required.');
				return false;
			}
			var Qry = 'sendType='+sendType+'&smsID='+smsID+'&groupID='+groupID+'&numberID='+numberID+'&cmd=send_bulk_sms&fromNumber='+encodeURIComponent(fromNumber);
		}
		else{
			var startDate= $('#start_date').val();
			var endDate  = $('#end_date').val();
			var numberID = $('select[name="daterange_group_id"] option:selected').val();
			if(($.trim(startDate)=='') || ($.trim(endDate)=='') || ($.trim(numberID)=='')){
				alert('All fields are required.');
				return false;
			}
			var Qry = 'sendType='+sendType+'&smsID='+smsID+'&startDate='+startDate+'&endDate='+endDate+'&numberID='+numberID+'&cmd=send_bulk_sms&fromNumber='+encodeURIComponent(fromNumber);
		}
		$('#loading').show();
		$.post('server.php',Qry,function(r){
			$('#loading').hide();
			$('#showresponse').html(r);
		});
	}
	function getSMSID(smsID){
		$('#hidden_sms_id').val(smsID);
	}
	function getGroupNumbers(groupID){
		if(groupID=='all'){
			$('.phoneListRow').hide('slow');
			return false;
		}
		$('#loading').show();
        var client_id = $("#client_id").val();
		var Qry = 'cmd=get_group_numbers&group_id='+groupID+"&client_id="+client_id;
		$.post('server.php',Qry,function(r){
			$('#loading').hide();
			$('#phoneid').html(r);
			$('.phoneListRow').show('slow');
		});
	}
    function getAccountGroups(user_id){
		$('#loading').show();
		var Qry = 'cmd=get_groups&user_id='+user_id;
		$.post('server.php',Qry,function(r){
			$('#loading').hide();
			$('#group_list').html(r);
		});
        
        var Qry = 'cmd=get_twilio_numbers&user_id='+user_id;
		$.post('server.php',Qry,function(r){
			$('#loading').hide();
			$('#twilio_numbers').html(r);
		});
        
        $('#phoneid').html('<option value="">Select One</option>');
	}
    
    
	function deleteBulkSMS(smsID){
		if(confirm("Are you sure you want to delete this message?")){
			window.location = 'server.php?cmd=delete_bulk_sms&id='+smsID;
		}
	}
	$(document).ready(function(){
		$('form').parsley();
		$('.bulk_type').on('click',function(r){
			if($(this).val()=='1'){
				$('.daterange').hide('slow');
				$('.single_group').show('slow');
			}
			else if($(this).val()=='2'){
				$('.single_group').hide('slow');
				$('.daterange').show('slow');
			}
		});
		/*
		$('#start_date, #end_date').datepicker({
			autoclose: true,
			todayHighlight: true
		});
		*/
	});
</script>