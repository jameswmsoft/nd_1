<?php
	include_once("header.php");
	include_once("left_menu.php");
	if($_REQUEST['id']!=''){
		$sql = "select * from subscribers where id='".$_REQUEST['id']."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			$sel = "select id,group_id from subscribers_group_assignment where subscriber_id='".$row['id']."'";
			$exe = mysqli_query($link,$sel);
			$rec = mysqli_fetch_assoc($exe);
			$groupID = $rec['group_id'];
			$assignID= $rec['id'];
			$cmd = 'update_subscriber';
			$buttonText = 'Update';
		}else
			$row = array();
	}else{
		$row = array();	
		$cmd = 'add_subscriber';
		$buttonText = 'Save';
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
								Add New Customer
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location='view_subscribers.php'" />
							</h4>
							<p class="category">Assign customers to your location here.</p>
						</div>
						<div class="content table-responsive">
							<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="padding-right:0px !important">
								<a href="server.php?cmd=download_sample_csv" class="btn btn-primary" style="float:right !important">Sample CSV</a>&nbsp;
								<a href="#importSubs" data-toggle="modal" class="btn btn-primary" style="float:right !important; margin-right:5px !important;">Upload CSV</a>
								<a href="#exportSubs" data-toggle="modal" class="btn btn-primary" style="float:right !important; margin-right:5px !important;">Export Subscribers</a>
							</div>
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
							<div class="form-group">
								<label>Name</label>
								<input type="text" name="first_name" placeholder="Enter name..." class="form-control" value="<?php echo DBout($row['first_name'])?>">
							</div>
							<div class="form-group">
								<label>Phone Number</label>
							
								<label><u>(Add +1 in front of the phone number)</u></label>
								<input type="text" name="phone_number" placeholder="Enter phone number..." class="form-control phoneOnly" value="<?php echo $row['phone_number']?>" required maxlength="13">
							</div>
                            <div class="form-group">
								<label>Email Address</label>
								<input type="email" name="email" placeholder="Enter Email Address..." class="form-control" value="<?php echo $row['email']?>">
							</div>
							<div class="form-group">
								<label>Location</label>
								<select name="group_id" class="form-control" parsley-trigger="change" required>
								<?php
									$sqlg = "select id, title from campaigns where user_id='".$_SESSION['user_id']."'";
									$resg = mysqli_query($link,$sqlg);
									if(mysqli_num_rows($resg)){
										while($rowg = mysqli_fetch_assoc($resg)){
											if($rowg['id']==$groupID)
												$sele = 'selected="selected"';
											else
												$sele = '';
											echo '<option '.$sele.' value="'.$rowg['id'].'">'.$rowg['title'].'</option>';
										}	
									}
								?>	
								</select>
							</div>
							<div class="form-group">
								<label>City</label>
								<input type="text" name="city" placeholder="Enter city..." class="form-control" value="<?php echo DBout($row['city'])?>">
							</div>
							<div class="form-group">
								<label>State</label>
								<input type="text" name="state" placeholder="Enter state ..." class="form-control" value="<?php echo DBout($row['state'])?>">
							</div>
							<div class="form-group text-right m-b-0">
								<button class="btn btn-primary waves-effect waves-light" type="submit"> <?php echo $buttonText?> </button>
								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
								<input type="hidden" name="cmd" value="<?php echo $cmd?>" />
								<input type="hidden" name="subscriber_id" value="<?php echo $row['id']?>" />
								<input type="hidden" name="assignment_id" value="<?php echo $assignID?>" />				
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
<div id="exportSubs" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="custom-modal-title">Export Subscribers</h6>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data" action="server.php">
			<div class="form-group">
				<label style="float:left !important">Select Campaign</label>
				<select name="export_campaign_id" class="form-control">
					<option value="all">ALL Subscribers</option>
				<?php
					$lists = mysqli_query($link,"select id, title from campaigns where user_id='".$_SESSION['user_id']."'");
					if(mysqli_num_rows($lists)){
						while($list = mysqli_fetch_assoc($lists)){
							echo '<option value="'.$list['id'].'">'.DBout($list['title']).'</option>';
						}	
					}
					else{
						echo '<option value="">No campaign found.</option>';	
					}
				?>
				</select>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="cmd" value="export_subs" />
				<input type="submit" value="Export CSV" class="btn btn-primary" />
			</div>
		</form>
      </div>
    </div>
  </div>
</div>

<div id="importSubs" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="custom-modal-title">Import Subscribers</h6>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data" action="server.php">
			<div class="form-group">
				<label style="float:left !important">Select Campaign</label>
				<select name="imported_campaign_id" class="form-control">
				<?php
					$lists = mysqli_query($link,"select id, title from campaigns where user_id='".$_SESSION['user_id']."'");
					if(mysqli_num_rows($lists)){
						while($list = mysqli_fetch_assoc($lists)){
							echo '<option value="'.$list['id'].'">'.DBout($list['title']).'</option>';
						}	
					}
					else{
						echo '<option value="">No campaign found.</option>';	
					}
				?>
				</select>
			</div>
			<div class="form-group">
				<label style="float:left !important">Select CSV file</label>
				<input type="file" name="imported_csv" style="display:inline !important" required/><br>
				<span style="color:red">Note: Please check csv format in sample file before upload.</span>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="cmd" value="import_subs" />
				<input type="submit" value="Import" class="btn btn-primary" />
			</div>
		</form>
      </div>
    </div>
  </div>
</div>
<script>
function checkPhoneNumber(number){
	$(".numeric").numeric();
}
</script>