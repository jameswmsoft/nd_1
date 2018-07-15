<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="images/favi.png">
<title>Nimble Messaging</title>

<!--Morris Chart CSS -->
<!--<link rel="stylesheet" href="assets/plugins/morris/morris.css">-->
<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

</head>
<style>
.footer{
	left:0px !important;
	text-align:center !important
}
input[type="checkbox"]{
	opacity:1 !important;
}
</style>
<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper" style="padding-top:5% !important">
<div class="topbar">
	<!-- LOGO <img src="images/favi.png" class="icon-magnet icon-c-logo">-->
	<div class="topbar-left">
		<div class="text-center"><span><img src="../images/installer_logo.png"></span></div>
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
								<h4 class="m-t-0 header-title"><b>Please provide database information below</b></h4>
	<p class="text-muted font-13">
		Provide your already created database information below.
	</p>
	<?php
		if(trim($_REQUEST['message'])!=''){
			echo $_REQUEST['message']; $_REQUEST['message']='';
		}
	?>
	<div class="p-20">
		<!--<h4>SignUp here for <span style="color:#FF8700">Application Admin</span> account.<span style="color:red"></span></h4>-->
		<form method="post" enctype="multipart/form-data" action="../server.php">
			<div class="form-group">
				<label>Host Name</label>
				<input type="text" name="hostname" class="form-control" required>
			</div>
			<div class="form-group">
				<label>Database Name</label>
				<input type="text" name="dbname" class="form-control" required>
			</div>
			<div class="form-group">
				<label>User Name</label>
				<input type="text" name="username" class="form-control" required>
			</div>
			<div class="form-group">
				<label>Password</label>
				<input type="text" name="password" class="form-control" required>
			</div>
			<div class="form-group text-right m-b-0">
				<img src="../images/busy.gif" id="loading" style="display:none">
				<span id="showResMsg"></span>
				<input type="hidden" name="cmd" value="save_installer_db_info" />
				<button class="btn btn-success waves-effect waves-light" type="submit"> Save & Next </button>
				<button type="button" class="btn btn-info waves-effect waves-light m-l-5" id="checkDBConnection"> Check Connection </button>
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
		Powered by <a href="http://ranksol.com" target="_blank" style="text-decoration:none">ranksol.com</a> copyright@2017
	</footer>
</div>
<script src="../assets/js/jquery-1.10.2.js"></script>
<script>
	$(document).ready(function(){
		$('#checkDBConnection').click(function(e){
			$('#showResMsg').html('');
			$('#loading').show();
			var hostname = $('input[name="hostname"]').val();
			var dbname   = $('input[name="dbname"]').val();
			var username = $('input[name="username"]').val();
			var password = $('input[name="password"]').val();
			$.post('../server.php',{'cmd':'check_db_conn',hostname:hostname,dbname:dbname,username:username,password:password},function(r){
				$('#loading').hide();
				if(r=='success'){
					$('#showResMsg').html('<span style="color:green">Connected successfully.</span>');
				}
				else{
					$('#showResMsg').html('<span style="color:red">Error: Invalid database information.</span>');
				}
			});
		});
	});
</script>