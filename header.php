<?php
	@error_reporting(0);
	@session_start();
	if($_SESSION['user_id']==''){
		header("location:index.php");
	}
	include_once("database.php");
	include_once("functions.php");
	if(file_exists("update_script.php")){
		include_once("update_script.php");
		@unlink("update_script.php");
	}
?>
<?php
	$appSettings  = getAppSettings($_SESSION['user_id']);
	$adminSettings= getAppSettings("",true);
	$timeZone = $appSettings['time_zone'];
	date_default_timezone_set($timeZone);
	
	
	$pageName  = getCurrentPageName();
	$pkgStatus = checkUserPackageStatus($_SESSION['user_id']);
	if($pkgStatus['go']==false)
		$notification = true;
	else
		$notification = false;

	if($appSettings!=false){
		$appVersion	  = $appSettings['version'];
		if(trim($appVersion)=='')
			$appVersion = '1.1.0';
			
		$updateResult = getUpdateDetails($appVersion);
		$upResult = json_decode($updateResult);
		
		$latestVersion = $upResult->version;
		$updateError   = $upResult->error;
		
		if(($updateError=="invalid") || ($updateError=="")){
			$displayUpdate  = "none";
		}else{
			$displayUpdate  = "";
		}
		$Latestupdates = $upResult->updates;	
	}else{
		$displayUpdate  = "none";	
	}
?>
<!doctype html>
<html lang="en">
<head>
<style>
.notification{
	background-color:red; 
	color:#ffffff; 
	padding:10px;
	border-radius:7px;
}
.notification-list{
	max-height:none !important
}
.showCount{
	color:#7E57C2 !important
}
.btn-grey{
	-webkit-border-radius: 5;
	-moz-border-radius: 5;
	-o-border-radius: 5;
	border-radius: 5px;
	color: #999999;
	font-size: 16px;
	background: #fff;
	padding: 5px 10px 5px 10px;
	border: solid #eeeeee 1px;
	text-decoration: none;
}
.btn-grey:hover{
	background: #7E57C2;
	text-decoration: none;
	color:#fff;
}
.btn-pages-active{
	font-family: Arial;
	color: #ffffff;
	font-size: 16px;
	background: #7E57C2 !important;
	padding: 8px 10px 8px 10px;
	text-decoration: none;
	border: solid #7E57C2 1px;
}
.btn-pages-active:hover{
	background: #1B53B7;
	text-decoration: none;
	border: solid #7E57C2 1px;
}
.btn-pages-inactive{
	font-family: Arial;
	color: #999999;
	font-size: 16px;
	background: #FFFFFF;
	padding: 8px 10px 8px 10px;
	text-decoration: none;
	border: solid #eeeeee 1px;
}
.btn-pages-inactive:hover{
	background: #EEEEEE;
	text-decoration: none;
	color:#2A6496;
}
#google_translate_element{
	margin-top:13% !important
}
.goog-te-gadget-icon{
	display:none !important
}
.goog-te-gadget-simple{
	border:none !important
}
.listTable > thead > tr > th{
	text-align:center
}
.listTable > tbody > tr > td{
	text-align:center
}
</style>
<meta charset="utf-8" />
<link rel="icon" type="image/png" href="images/favi.png">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>SMS Reminder Dashboard</title>
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<meta name="viewport" content="width=device-width" />




<!-- Bootstrap core CSS -->
<link href="assets/css/bootstrap.min.css" rel="stylesheet" />

<!-- Animation library for notifications -->
<link href="assets/css/animate.min.css" rel="stylesheet"/>

<!--  Light Bootstrap Table core CSS -->
<link href="assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>

<!--  CSS for Demo Purpose, don't include it in your project     -->
<link href="assets/css/demo.css" rel="stylesheet" />

<!--     Fonts and icons     -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<!--
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
-->
<link href='//fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
<link href="assets/css/jquery-ui.css" rel="stylesheet">
<!--Slim scroll --> 
<!--
<link href="css/prettify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="assets/js/prettify.js"></script>
<script type="text/javascript" src="assets/js/jquery.slimscroll.js"></script>
-->
<!--<link href="css/slim_scroll_style.css" type="text/css" rel="stylesheet" />-->
<!--Slim scroll end --> 

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script>
	function goBack(step='-1'){
		window.history.go(""+step+"");
	}
	function googleTranslateElementInit(){
		new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
	}
	var maxLength = '<?php echo @$maxLength?>';
</script>
</head>
<body>
<div class="wrapper">
