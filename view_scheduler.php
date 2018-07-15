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
								SMS Schedulers
								<!--<input type="button" class="btn btn-primary" value="<?php echo date('g:ia \o\n l jS F Y')?>" style="float:right !important"/>-->
								<input type="button" class="btn btn-primary" value="Add New" style="float:right !important" onclick="window.location='scheduler.php'"/>
							</h4>
							<p class="category" style="color:#F00"><?php echo date('g:iA \o\n l jS F Y')?></p>
						</div>
						<div class="content table-responsive table-full-width">
							<table id="schedulerTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>Title</th>
										<th>Send Time</th>
										<th>Group</th>
										<th>Recipient</th>
										<th>Status</th>
										<th>Media</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sql = "select * from schedulers where user_id='".$_SESSION['user_id']."' and scheduler_type='1' order by id desc";
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											$index = 1;
											while($row = mysqli_fetch_assoc($res)){
									?>
												<tr>
													<td><?php echo $index++?></td>
													<td style="text-align:left"><?php echo $row['title'];?></td>
													<td><?php echo date($appSettings['app_date_format'].' H:i:s',strtotime($row['scheduled_time']));?></td>
													<td>
														<?php
															$sqlg = "select title from campaigns where id='".$row['group_id']."'";
															$resg = mysqli_query($link,$sqlg);
															if(mysqli_num_rows($resg)){
																$rowg = mysqli_fetch_assoc($resg);	
																echo $rowg['title'];
															}
															else{
																echo 'N/A';	
															}
														?>
													</td>
													<td>
														<?php
															if($row['phone_number']=='all'){
																echo 'Whole Group';	
															}
															else{
																$sqln = "select phone_number from subscribers where id='".$row['phone_number']."'";
																$resn = mysqli_query($link,$sqln);	
																if(mysqli_num_rows($resn)){
																	$rown = mysqli_fetch_assoc($resn);	
																	echo $rown['phone_number'];
																}
															}
														?>
													</td>
													<td>
														<?php
															if($row['status']=='1')
																echo '<i class="badge badge-success">Sent</i>';
															else
																echo '<i class="badge badge-warning">Waiting</i>';
														?>
													</td>
													<td>
													<?php
														if(trim($row['media'])!=''){
													?>
														<img src="<?php echo $row['media'];?>" width="30" height="30" />
													<?php
														}
													?>
													</td>
													<td style="text-align:center">
														<a href="edit_scheduler.php?id=<?php echo $row['id']?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
														<i class="fa fa-remove" style="color:red; cursor:pointer" onclick="deleteScheduler('<?php echo $row['id']?>')"></i>
													</td>
												</tr>
									<?php			
											}	
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
<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script>
	$('#schedulerTable').cardtable();
	function deleteScheduler(id,img){
		if(confirm("Are you sure you want to delete this schduler?")){
			window.location = 'server.php?cmd=delete_scheduler&id='+id;
		}
	}
</script>