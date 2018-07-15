<?php
	@session_start();
	include_once("database.php");
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Nimble Pricing</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic' rel='stylesheet' type='text/css'>
<style>
.alert-success {
	background-color: #dff0d8;
	border-color: #d0e9c6;
	color: #3c763d;
}
.alert {
	border: 1px solid transparent;
	border-radius: 0.25rem;
	padding: 0.75rem 1.25rem;
	margin-bottom: 0;
	margin-top: 25px;
}
.price-head >h1{
	color:#666;
}
</style>
</head>
<body>


<div class="content-page"> 
	<div class="content">
		<div class="container"> 
			<div class="row">
				<div class="col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-lg-12"><br /><br /><br /><br /><br />
								<h4 class="m-t-0 header-title"><b>Please Add Basic Information</b></h4>
								<p class="text-muted font-13">
									
								</p>
	<?php
		if(trim($_SESSION['message'])!=''){
			echo $_SESSION['message']; unset($_SESSION['message']);
		}
	?>
	<div class="p-20">
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
			<input type="email" name="email" value="<?php echo $_REQUEST['stripeEmail'] ?>" class="form-control" required readonly="" />
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
					}else{
						echo '<option value="">No time zone added yet.</option>';
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
        
            
            <input name="stripeToken" type="hidden" value="<?php echo $_REQUEST['stripeToken']; ?>" />
            <input name="pkg_id" type="hidden" value="<?php echo $_REQUEST['id']; ?>" />
        
			<input type="hidden" name="cmd" value="add_app_user_by_stripe" />
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
		</div>
	</div>
    </div>
    
    </body>
 </html>