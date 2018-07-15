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
								Add Application User
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location='view_user.php'" />
							</h4>
							<p class="category">Add new application user sub-account.</p>
						</div>
						<div class="content table-responsive">
						<form method="post" enctype="multipart/form-data" action="server.php">
							<div class="form-group">
								<label>First Name</label>
								<input type="text" name="first_name" class="form-control" required>
							</div>
							<div class="form-group">
								<label>Last Name</label>
								<input type="text" name="last_name" class="form-control" required>
							</div>
							<div class="form-group">
								<label>Login Email</label>
								<input type="email" name="email" class="form-control" required>
							</div>
							<div class="form-group">
								<label>Select Time Zone</label>
								<select name="time_zone" class="form-control">
									<?php
										$sql = "select time_zone, time_zone_value from time_zones";
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											while($row = mysqli_fetch_assoc($res)){
												echo '<option value="'.$row['time_zone'].'">'.$row['time_zone_value'].'</option>';
											}
										}
										else{
											echo '<option value="">No time zone added yet.</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label>Select Package Plan</label>
								<select name="pkg_id" class="form-control" required>
									<?php
										$sql = "select id, title from package_plans where user_id='".$_SESSION['user_id']."'";
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											while($row = mysqli_fetch_assoc($res)){
												echo '<option value="'.$row['id'].'">'.$row['title'].'</option>';
											}
										}
										else{
											echo '<option value="">No plan created yet.</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label>Business Name</label>
								<input type="text" name="business_name" class="form-control" required>
							</div>
							<div class="form-group">
								<label>Login Password</label>
								<input type="password" name="password" class="form-control" required>
							</div>
							<div class="form-group">
								<label>Re-type Password</label>
								<input type="password" name="retype_password" class="form-control" required>
							</div>
							<div class="form-group text-right m-b-0">
								<input type="hidden" name="cmd" value="add_app_user_by_admin" />
								<button class="btn btn-primary waves-effect waves-light" type="submit"> Register Account </button>
								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
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