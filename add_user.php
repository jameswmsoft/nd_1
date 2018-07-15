<?php
@session_start();
include_once("database.php");
include_once("functions.php");
$id  = decode($_REQUEST['pid']);
$uid = $_REQUEST['uid'];
$appSettings = getAppSettings("",true);
$sql = "select * from package_plans where id='".$id."'";
$res = mysqli_query($link,$sql);
if(mysqli_num_rows($res)){
	$row = mysqli_fetch_assoc($res);
}
if(isset($uid) && trim($uid)!=''){
?>
	<form method="post" action="server.php" enctype="multipart/form-data" id="upgradeuserpackage">
		<input type="hidden" name="pkg_id" value="<?php echo $id?>">
		<input type="hidden" name="pkg_price" value="<?php echo $row['price']?>">
		<input type="hidden" name="pkg_title" value="<?php echo $row['title']?>">
		<input type="hidden" name="user_id" value="<?php echo decode($uid)?>">
		<input type="hidden" name="cmd" value="upgrade_user_package">
	</form>
<?php	
	echo '<script>document.forms["upgradeuserpackage"].submit();</script>';
	die();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="images/favi.png">
<title>Ranksol - Get Started</title>

<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
<link href="assets/css/animate.min.css" rel="stylesheet"/>
</head>
<style>
.footer{
	left:0px !important;
	text-align:center !important
}
input[type="checkbox"]{
	opacity:1 !important;
}
.alert-success {
    background-color: #dff0d8;
    border-color: #d0e9c6;
    color: #3c763d;
}
.alert-danger {
    background-color: #f2dede;
    border-color: #ebcccc;
    color: #a94442;
}
.alert {
    border: 1px solid transparent;
    border-radius: 0.25rem;
    padding: 0.75rem 1.25rem;
    margin-bottom: 0;
    margin-top: 25px;
}
</style>
<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">
<div class="topbar" style="margin-top:20px;">
	<div class="topbar-left">
		<div class="text-center"><span><img src="images/installer_logo.png"></span></div>
	</div>
</div>

<div class="content-page" style="margin:0 auto !important"> 
	<div class="content">
		<div class="container"> 
			<div class="row">
				<div class="col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-lg-12">
								<h4 class="m-t-0 header-title"><b>Please provide information below</b></h4>
	<p class="text-muted font-13">
		You're just one step away.
	</p>
	<?php
		if(trim($_REQUEST['message'])!=''){
			echo $_REQUEST['message']; $_REQUEST['message']='';
		}
        
        if(isset($_SESSION['message']) && $_SESSION['message']!=""){
            echo $_SESSION['message'];
        }
        unset($_SESSION['message']);
        
        if(isset($_SESSION['authnet_response']) && $_SESSION['authnet_response']==1){
            echo '<div class="alert alert-success">'.$_SESSION['authnet_msg'].'</div>';
        }else if(isset($_SESSION['authnet_response']) && $_SESSION['authnet_response']==0){
            echo '<div class="alert alert-danger">'.$_SESSION['authnet_msg'].'</div>';
        }
        unset($_SESSION['authnet_msg']);
        unset($_SESSION['authnet_response']);
        
	?>
	<div class="p-20">
		<h4>Your are singing up for the <span style="color:#FF8700"><?php echo $row['title']?></span> @ <span style="color:red"><?php echo '$'.$row['price']?> per month.</span></h4>
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
            
            <?php
            if(($row['price']>1) && ($appSettings['payment_processor']!=1)){
            ?>
            
            <div class="form-group">
				<label>Address</label>
				<input type="text" name="address" class="form-control" required>
			</div>
            <div class="form-group">
				<label>City</label>
				<input type="text" name="city" class="form-control" required>
			</div>
            <div class="form-group">
				<label>State</label>
				<input type="text" name="state" class="form-control" required>
			</div>
            <div class="form-group">
				<label>Zip</label>
				<input type="text" name="zip" class="form-control" required>
			</div>
            
            <div class="form-group">
				<label>Credit Card Number</label>
				<input type="text" name="card_number" class="form-control" required>
			</div>
            <div class="form-group">
				<label>CVC Security Code (Located on back of card)</label>
				<input type="text" name="cvv" class="form-control" required>
			</div>
            <div class="form-group">
				<label>Expiration Month (MM)</label>
				<input type="text" name="month" class="form-control" placeholder="05" required>
			</div>
            <div class="form-group">
				<label>Expiration Year (YY)</label>
				<input type="text" name="year" class="form-control" placeholder="20" required>
			</div>
            <?php
            }
            ?>
            
			<div class="form-group">
				<label class="checkbox"><input type="checkbox" name="tcap_ctia" value="1" required style="margin-left:0px !important; margin-right:5px !important; position:relative !important">100% TPCA & CTIA Compliant</label>
				<label class="checkbox"><input type="checkbox" name="msg_and_data_rate" value="1" required style="margin-left:0px !important; margin-right:5px !important; position:relative !important">Msg & Data Rates May Apply</label>
				<label class="checkbox"><input type="checkbox" name="privacy_policy" value="1" required style="margin-left:0px !important; margin-right:5px !important; position:relative !important">T&C/Privacy Policy <a href="tandc.php">Read here</a></label>
                <label class="checkbox"><input type="checkbox" name="statement" value="1" required style="margin-left:0px !important; margin-right:5px !important; position:relative !important">Your billing statement will show a charge from Swyft Media Group, LLC</label>
			</div>
			
			<div class="form-group text-right m-b-0">
                <?php
                if($row['price']<1){ $cmd = "add_app_user_by_admin"; }else{ $cmd = "add_web_user"; }
                ?>
                <input type="hidden" name="cmd" value="<?php echo $cmd; ?>" />
				<!-- package fields -->
				<input type="hidden" name="pkg_id" value="<?php echo $id?>">
				<input type="hidden" name="pkg_price" value="<?php echo $row['price']?>">
				<input type="hidden" name="pkg_title" value="<?php echo $row['title']?>">
				<!-- package fields end -->
				<input type="hidden" name="parent_user_id" value="<?php echo $row['user_id']?>">
				<button class="btn btn-primary waves-effect waves-light" type="submit"> Sign up Now </button>
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
	<footer class="footer">
		Powered by <a href="http://ranksol.com" target="_blank" style="text-decoration:none">Ranksol</a><br>Weaving a better web
	</footer>
</div>