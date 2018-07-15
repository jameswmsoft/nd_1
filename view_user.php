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
								Application Sub-Accounts
								<input type="button" class="btn btn-primary" value="Add New" style="float:right !important" onclick="window.location='add_app_user.php'" />
							</h4>
							<p class="category">Application sub-accounts.</p>
						</div>
						<div class="content table-responsive table-full-width">
							<table id="accountsTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Email</th>
										<th>Phone Number</th>
										<th>Plan</th>
										<th>Status</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
			<?php
				$sql = "select * from users where parent_user_id='".$_SESSION['user_id']."' and type='2' order by id desc";
				if(is_numeric($_GET['page']))
					$pageNum = $_GET['page'];
				else
					$pageNum = 1;
				$max_records_per_page = 20;
				$pagelink 	= "view_user.php?";
				$pages 		= generatePaging($sql,$pagelink,$pageNum,$max_records_per_page);
				$limit 		= $pages['limit'];
				$sql 	   .= $limit;
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
				$res = mysqli_query($link,$sql);
				if(mysqli_num_rows($res)){
					$index = $countPaging;
					while($row = mysqli_fetch_assoc($res)){
			?>
						<tr>
							<td><?php echo $index++?></td>
							<td style="text-align:left"><?php echo $row['first_name'].' '.$row['last_name'];?></td>
							<td style="text-align:left"><?php echo $row['email'];?></td>
							<td>
							<?php 
								$n = mysqli_query($link,"select phone_number from users_phone_numbers where user_id='".$row['id']."'");
								if(mysqli_num_rows($n)){
									while($r = mysqli_fetch_assoc($n)){
										echo $r['phone_number'];
										echo '<br>';
									}	
								}
							?>
							</td>
							<td>
								<?php
									$sel = "select pp.title from package_plans pp, user_package_assignment upa where upa.pkg_id=pp.id and upa.user_id='".$row['id']."'";
									$exe = mysqli_query($link,$sel);
									if(mysqli_num_rows($exe)==0)
										echo 'N/A';	
									else{
										$rec = mysqli_fetch_assoc($exe);
										echo $rec['title'];
									}
									?>
									
								</td>
							<td>
								<?php 
									if($row['status']=='1'){
										echo '<span class="badge badge-success">Active</span>';
									}
									else if($row['status']=='2'){
										echo '<span class="badge badge-warning">Blocked</span>';
											
									}
									else if($row['status']=='3'){
										echo '<span class="badge badge-danger">Deleted</span>';
											
									}
										
								?>
							</td>
							<td style="text-align:center">
								<a href="edit_app_user.php?id=<?php echo encode($row['id'])?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
								<i class="fa fa-remove" style="color:red; cursor:pointer" onclick="deleteAppUser('<?php echo encode($row['id'])?>')"></i>
							</td>
						</tr>
			<?php			
					}	
				}
			?>
				<tr>
					<td colspan="8" style="padding-left:0px !important;padding-right:0px !important"><?php echo $pages['pagingString'];?></td>
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
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script>
	$('#accountsTable').cardtable();
	function deleteAppUser(userID){
		if(confirm("Are you sure you want to delete this sub-account?")){
			if(confirm("It will delete all related data included twilio sub account and its phone numbers?")){
				window.location = 'server.php?id='+userID+'&cmd=delete_app_user';
			}
		}
	}
</script>