<?php
	if($pageName!='edit_app_user.php'){
		if($_SESSION['user_type']=='1'){ // Admin related notifications
			if(trim($appSettings['sms_gateway'])==''){
				echo '<div class="alert alert-danger"><span><b> Warning - </b> Application settings are not configured, please configure sms gateway settings <a href="settings.php" style="color:#FFFFFF;"><b>here</b></a>.</span></div>';
			}
			if(trim($appSettings['product_purchase_code'])==''){
				echo '<div class="alert alert-danger"><span><b> Warning - </b> your install is not verified, please click <a href="#verificationSection" style="color:#FFFFFF;" data-toggle="modal"><b>here</b></a> to verify install.</span></div>';
			}else{
				if($appSettings['product_purchase_code_status']!='verified'){
					echo '<div class="alert alert-danger"><span><b> Warning - </b> your product purchase code is invalid or fake, please click <a href="#verificationSection" style="color:#FFFFFF;" data-toggle="modal"><b>here</b></a> to verify install.</span></div>';
				}
			}
		}else if($_SESSION['user_type']=='2'){ // User related notification
			if(trim($appSettings['sms_gateway'])==''){
				echo '<div class="alert alert-danger"><span><b> Warning - </b> Application is not configured properly! please contact to your administrator.</div>';
			}
		}
	}
?>
<nav class="navbar navbar-default navbar-fixed">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
			<a class="navbar-brand" href="javascript:void(0)"><?php echo $_SESSION['business_name']?></a> </div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav navbar-right">
				<?php if($notification){?>
				<li> <a href="javascript:void(0)">
					<p style="color:#FFF; background-color:red;padding:2px; margin-left:0px !important">Package Expired.</p>
					</a>
					<!--<span class="notification"><?php echo $pkgStatus['message']?></span>--> 
				</li>
				<?php }?>
				<li id="googletranslateelement">
					<div id="google_translate_element" style=""></div>
				</li>
				<!-- <li> <a href="https://codecanyon.net/item/nimble-messaging-business-mobile-sms-marketing-application-for-android/20956083" target="_blank"><i class="fa fa-android" aria-hidden="true"></i>&nbsp;Get Mobile App</a> </li>
				<li> <a href="help.php" target="_blank"><i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp;Help</a> </li>  -->
				<?php
				if($_SESSION['user_type']=='1'){
					if($displayUpdate=='none'){
				?>
				<li>
				<?php 
					if(trim($appVersion)!='')
						$appVersion = 'v'.$appVersion;
					echo '<a href="javascript:void(0)"><p>'.$appVersion.'</p></a>';
				?>
				</li>
				<?php }else{?>
				<li> <a href="update_app.php" class="btn btn-danger">Update to <?php echo $latestVersion?></a> </li>
				<?php }

				}
				?>
				<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<p><span class="pe-7s-bell" style="font-size:20px;"></span>&nbsp;<b class="caret"></b> </p>
					</a>
					<ul class="dropdown-menu">
						<?php 
						if($_SESSION['user_type']=='1'){
							$sqlcrd = "select used_sms_credits from users where id='".$_SESSION['user_id']."'";
							$rescrd = mysqli_query($link,$sqlcrd);
							$rowAdmin = mysqli_fetch_assoc($rescrd);
					?>
						<li> <a href="javascript:void(0);" class="" style="padding-left:15px;">
							<div class="media">
								<div class="pull-left p-r-10"> <b>Unlimited Plan</b></div>
								<div class="media-body">
									<h5 class="media-heading"> <span class="badge" style="background-color:#00F; color:white; float:right">Admin</span> </h5>
								</div>
							</div>
							</a> </li>
						<li> <a href="javascript:void(0);" class="" style="padding-left:10px !important; padding-right:10px !important">
							<div class="media" style="padding-left:5px !important; padding-right:5px !important">
								<div class="media-body">
									<h5 class="media-heading"> SMS Credits: <span style="float:right"><?php echo $rowAdmin['used_sms_credits'].'/Ultd'?></span> </h5>
									<p class="m-0" style="margin-left:0px"> <small>Remaining sms credits <span class="text-primary font-600" style="float:righ;">Ultd</span>.</small></p>
								</div>
							</div>
							</a> </li>
						<li> <a href="javascript:void(0);" class="" style="padding-left:10px !important; padding-right:10px !important">
							<div class="media" style="padding-left:5px !important; padding-right:5px !important">
								<div class="media-body">
									<h5 class="media-heading"> Phone Numbers: <span style="margin-left:10px"><?php echo checkUserNumberslimit($_SESSION['user_id']).'/Ultd'?></span> </h5>
									<p class="m-0" style="margin-left:0px"> <small>Remaining numbers are <span class="text-primary font-600">Ultd</span>.</small> </p>
								</div>
							</div>
							</a> </li>
						<li> <a href="javascript:void(0);" class="" style="padding-left:10px !important; padding-right:10px !important">
							<div class="media" style="padding-left:5px !important; padding-right:5px !important">
								<div class="media-body">
									<h5 class="media-heading">Unlimited Plan</h5>
									<p class="m-0" style="margin-left:0px"> <small> You can buy any number.</small> </p>
								</div>
							</div>
							</a> </li>
						<li> <a href="javascript:void(0);" class="" style="padding-left:10px !important; padding-right:10px !important">
							<div class="media" style="padding-left:5px !important; padding-right:5px !important">
								<div class="media-body">
									<h5 class="media-heading">Status Active</h5>
									<p class="m-0" style="margin-left:0px"> <small>Your plan status is currently <span class="text-primary font-600">Active</span>.</small> </p>
								</div>
							</div>
							</a> </li>
						<?php 
						}else{
							$userPackage = getAssingnedPackageInfo($_SESSION['user_id']);
							$pkgTitle 	 = getPackageInfo($userPackage['pkg_id']);	
					?>
						<li>
							<a href="javascript:void(0)" style="padding-left:10px !important; padding-right:10px !important"> <?php echo $pkgTitle['title']?><br />
							<span class="showUserPkgDtls" style="color:red; padding:5px; margin-bottom:5px;"><?php echo 'Expires: '.date('M-d-y H:i a',strtotime($userPackage['end_date']));?></span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" style="cursor:default !important;padding-left:10px !important; padding-right:10px !important">
							<div class="media">
								<div class="media-body">
									<h5 class="media-heading"> SMS Credits: &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $userPackage['used_sms_credits'].'/'.$userPackage['sms_credits']?> </h5>
									<p class="m-0" style="margin-left:0px"> <small>Remaining sms credits are <span class="text-primary font-600"><?php echo ($userPackage['sms_credits']-$userPackage['used_sms_credits'])?></span></small> </p>
								</div>
							</div>
							</a> </li>
						<li> <a href="javascript:void(0);" style="cursor:default !important;padding-left:10px !important; padding-right:10px !important">
							<div class="media"> 
								<div class="media-body">
									<h5 class="media-heading"> Phone Numbers: <span style="margin-left:20px"><?php echo checkUserNumberslimit($_SESSION['user_id']).'/'.$userPackage['phone_number_limit']?></span> </h5>
									<p class="m-0" style="margin-left:0px"> <small>Remaining numbers are <span class="text-primary font-600"><?php echo ($userPackage['phone_number_limit']-checkUserNumberslimit($_SESSION['user_id']))?></span></small> </p>
								</div>
							</div>
							</a> </li>
						<li> <a href="javascript:void(0);" style="cursor:default !important;padding-left:10px !important; padding-right:10px !important">
							<div class="media"> 
								<div class="media-body">
									<?php
										$pkgCountry = $userPackage['pkg_country'];
									?>
									<h5 class="media-heading"><?php echo $pkgCountry?> Plan</h5>
									<p class="m-0" style="margin-left:0px"> <small>Can only buy <span class="text-primary font-600"><?php echo $pkgCountry?></span> numbers.</small> </p>
								</div>
							</div>
							</a> </li>
						<li> <a href="javascript:void(0);" style="cursor:default !important;padding-left:10px !important; padding-right:10px !important">
							<div class="media"> 
								<div class="media-body">
									<?php
										if($userPackage['status']=='1')
											$status = 'Active';
										else
											$status = 'Suspended';
									?>
									<h5 class="media-heading">Status <?php echo $status?></h5>
									<p class="m-0" style="margin-left:0px"> <small>Plan status is currently <span class="text-primary font-600"><?php echo $status?></span>.</small> </p>
								</div>
							</div>
							</a> </li>
						<li><a href="pricing_plans.php?uid=<?php echo encode($_SESSION['user_id'])?>" target="_blank">
							<div class="media"> 
								<div class="media-body"> Upgrade Package </div>
							</div>
							</a> </li>
						<?php }?>
					</ul>
				</li>
				<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<p> <?php echo $_SESSION['first_name'].' '.$_SESSION['last_name'];?> - SETTINGS MENU <b class="caret"></b> </p>
					</a>
					<ul class="dropdown-menu">
						<li><a href="profile.php"><i class="ti-user m-r-5"></i> Profile</a></li>
						<li><a href="settings.php"><i class="ti-settings m-r-5"></i> Settings</a></li>
						<li><a href="sms_report.php"><i class="ti-settings m-r-5"></i> SMS Report</a></li>
						<li><a href="payment_history.php"><i class="ti-settings m-r-5"></i> Payment History</a></li>
						<li class="separator"></li>
						<li><a href="server.php?cmd=logout">Log out</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>