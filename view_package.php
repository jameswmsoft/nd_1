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
								Pricing Plans
								<input type="button" class="btn btn-primary" value="Add New" style="float:right !important" onclick="window.location='add_package.php'" />
							</h4>
							<p class="category">Your Already saved sms pricing plans.</p>
						</div>
						<div class="content table-responsive table-full-width">
							<table id="packagesTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>Title</th>
										<th>Gateway</th>
										<th>SMS Credits</th>
										<th>Allowed Number</th>
                                        <th>Locations</th>
                                        <th>Reminders</th>
										<th>Country</th>
										<th>Price</th>
										<!--<th>Created Date</th>-->
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sql = "select * from package_plans where user_id='".$_SESSION['user_id']."'";
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											$index = 1;
											while($row = mysqli_fetch_assoc($res)){
									?>
												<tr>
													<td><?php echo $index++?></td>
													<td style="text-align:left"><?php echo $row['title']?></td>
													<td><?php echo ucfirst($row['sms_gateway'])?></td>
													<td align="center"><?php echo $row['sms_credits']?></td>
													<td align="center"><?php echo $row['phone_number_limit']?></td>
                                                    <td align="center"><?php echo $row['locations']?></td>
                                                    <td align="center"><?php echo $row['reminders']?></td>
													<td align="center"><?php echo $row['country']?></td>
													<td><?php echo '$/'.$row['price'];?></td>
													<!--<td><?php echo $row['created_date']?></td>-->
													<td style="text-align:center">
														<a href="edit_pkg.php?id=<?php echo $row['id']?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
														<i style="color:red; cursor:pointer" class="fa fa-remove" onclick="deletePlan('<?php echo $row['id']?>')"></i>
													</td>
												</tr>
									<?php				
											}
										}
										else{
											echo '<tr><td colspan="7">No plan created yet.</td></tr>';
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
	$('#packagesTable').cardtable();
	function deletePlan(id){
		if(confirm("Are you sure you want to delete this pricing plan?")){
			window.location = 'server.php?cmd=delete_plan&id='+id;
		}
	}
</script>