<?php
@error_reporting(0);
@session_start();
if(@$_SESSION['rndir']=='true'){
	unset($_SESSION['rndir']);
	$_SESSION['rndir'] = '';
	@rename('installer','_installer');
}
if(file_exists("installer/index.php")){
	header("location: installer/index.php");
	die();
}
if(@$_SESSION['user_id']!=''){
	header("location:view_apts.php");	
}
include_once("database.php");
include_once("functions.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link rel="shortcut icon" href="images/favi.png">
<title>N1D.ca SMS Scheduling</title>
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<meta name="viewport" content="width=device-width" />

<!-- Bootstrap core CSS     -->
<link href="assets/css/bootstrap.min.css" rel="stylesheet" />

<!-- Animation library for notifications   -->
<link href="assets/css/animate.min.css" rel="stylesheet"/>

<!--  Light Bootstrap Table core CSS    -->
<link href="assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>

<!--  CSS for Demo Purpose, don't include it in your project     -->
<link href="assets/css/demo.css" rel="stylesheet" />

<!--     Fonts and icons     -->
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
</head>
<style>
body{
	background-color:#ebeff2;
}
.btn-purple, .btn-purple:hover, .btn-purple:focus, .btn-purple:active {
    background-color: #7266ba !important;
    border: 1px solid #7266ba !important;
    color: #ffffff;
}
.card-box{
	width:50%; 
	margin:10% auto;
	background-color:#FFF;
	border-radius:4px;
	
}

</style>

<style>
.text-custom{
		
	   color: #7266ba;
	}
@media (max-width:650px){
	   body{
		background-color:#FFFFFF;
			background-image:url(images/img.jpg);
	    background-repeat: no-repeat;
	}
	.card-box{
		width:100%;	
		margin:20% auto;
	    background: transparent;
	}

	.text-custom{
		
	   color: #1C7CA2;
	}

	.text-center{
		
	   color: #fff;
	}

   .apniclass{
		
	   background-color:#1C7CA2 !important;
        border: none;
        color: #fff;
        border:none;
		border-color:#1C7CA2 !important;
	}
	.text-dark{
		color:#FFFFFF !important;
	}
    .btn:hover, .btn:focus, .btn:active, .btn.active, .open > .btn.dropdown-toggle{
		background-color:#1C7CA2 !important;
		border-color:#1C7CA2 !important;
	}

}
</style>
<body>
<?php
$adminSettings= getAppSettings("",true);
$sidebarColor = $adminSettings['sidebar_color'];
$colors = array("purple"=>"#9368E9",
"blue"=>"#1F77D0",
"azure"=>"#1DC7EA",
"green"=>"#87CB16",
"orange"=>"#FFA534",
"red"=>"#FB404B"
);

$sidebarColor = $colors[$adminSettings['sidebar_color']];

?>
<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">
	<div class="card-box">
		<div class="panel-heading">
			
			<?php
				if((isset($_SESSION['message'])) && (trim($_SESSION['message'])!='')){
					echo $_SESSION['message']; unset($_SESSION['message']);
				}
			?>
		</div>
		<div class="panel-body">
			<form class="form-horizontal m-t-20" action="server.php?cmd=login" method="post">
				<div class="form-group ">
					<div class="col-xs-12">
						<input class="form-control" type="text" required placeholder="Username" name="username">
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12">
						<input class="form-control" type="password" required placeholder="Password" name="password">
					</div>
				</div>
				<!--<div class="form-group ">
					<div class="col-xs-12">
						<div class="checkbox checkbox-primary">
							<input id="checkbox-signup" type="checkbox">
							<label for="checkbox-signup"> Remember me </label>
						</div>
					</div>
				</div>-->
				<div class="form-group text-center m-t-40">
					<div class="col-xs-12">
						<button class="btn btn-block text-uppercase waves-effect waves-light apniclass" type="submit" style="background:<?php echo $sidebarColor; ?>; color:#FFF;">Log In</button>
					</div>
				</div>
				<div class="form-group m-t-30 m-b-0">
					<div class="col-sm-12">
						<a href="forgot_password.php" class="text-dark" style="color:#7266BA"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
						<a href="pricing_plans.php" class="text-dark" style="float:right; color:#7266BA">SignUp?</a>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!--<div class="row">
		<div class="col-sm-12 text-center">
			<p>Don't have an account? <a href="page-register.html" class="text-primary m-l-5"><b>Sign Up</b></a></p>
		</div>
	</div>-->
</div>
</body>
</html>