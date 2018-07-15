<?php
	@session_start();
	include_once("database.php");
	include_once("functions.php");
	$subsID = decode($_REQUEST['subid']);
	
	$sel = "select * from subscribers where id='".$subsID."'";
	$exe = mysqli_query($link,$sel);
	if(mysqli_num_rows($exe)){
		$row = mysqli_fetch_assoc($exe);
		$appSettings = getAppSettings($row['user_id']);
		date_default_timezone_set($appSettings['time_zone']);
		$today = date('Y-m-d H:i');
		$sql = "select id from bound_phones where to_number='".$row['phone_number']."' and user_id='".$row['user_id']."' and date_format(lease_date, '%Y-%m-%d %H:%i') >= '".$today."'";
		mail("ahsan@nimblewebsolutions.com","query",$sql);
		$exe = mysqli_query($link,$sql);
		if(mysqli_num_rows($exe)==0){
			die('Session expired! please send keyword again.');	
		}
		
	}else{
		echo $_SESSION['message'];
		die();
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Nimble Profile</title>
<link href="css/pricing_style.css" rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic' rel='stylesheet' type='text/css'>
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<meta name="viewport" content="width=device-width" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
	<div class="container-fluid">
		<img src="images/<?php echo $appSettings['app_logo']?>">
		<h3>Update/Delete Profile <a href="javascript:void(0)" onClick="deleteGDPRProfile()" class="btn btn-danger" style="float:right">Delete Profile</a></h3>
		<?php
			if($_SESSION['message']!=''){
				echo $_SESSION['message'];unset($_SESSION['message']);	
			}
		?>
		<form action="server.php" method="post">
			<div class="form-group">
				<label>Name</label>
				<input type="text" name="gdpr_name" class="form-control" value="<?php echo $row['first_name']?>">
			</div>
			<div class="form-group">
				<label>Last Name</label>
				<input type="text" name="gdpr_last_name" class="form-control" value="<?php echo $row['last_name']?>">
			</div>
			<div class="form-group">
				<label>Email</label>
				<input type="text" name="gdpr_email" class="form-control" value="<?php echo $row['email']?>">
			</div>
			<div class="form-group">
				<label>Phone</label>
				<input type="text" name="gdpr_phone" class="form-control" value="<?php echo $row['phone_number']?>">
			</div>
			<div class="form-group">
				<input type="hidden" name="subs_id" value="<?php echo $subsID?>">
				<input type="hidden" name="cmd" value="update_gdpr_profile">
				<input type="submit" value="Update" class="btn btn-primary">
			</div>
		</form>
	</div>
<div class="footer">
	<div class="wrap">
		<p>&copy; 2016  All rights  Reserved | Powered by &nbsp;<a href="http://ranksol.com">Ranksol</a></p>
	</div>
</div>
</body>
</html>
<script>
	function deleteGDPRProfile(){
		if(confirm("Are you sure you want to delete profile?")){
			window.location = 'server.php?cmd=delete_gdpr_profile&subsid=<?php echo $subsID?>';
		}
	}
</script>