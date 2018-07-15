<?php
	include_once("header.php");
	include_once("left_menu.php");
	$sql = "select * from application_settings where user_id='".$_SESSION['user_id']."'";
	$res = mysqli_query($link,$sql);
	$row = mysqli_fetch_assoc($res);
	$sid = $row['twilio_sid'];
	$token = $row['twilio_token'];
	if($_SESSION['user_type']=='1'){
		echo '<script src="scripts/js/ckeditor/ckeditor.js"></script>';	
	}
?>
<link href="assets/css/timepicki.css" rel="stylesheet" />
<link rel="stylesheet" href="assets/css/bootstrap-select.min.css">
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title"> Settings
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location=history.go(-1)" />
							</h4>
							<p class="category">Add your application configuration here. <span id="loading" style="display:none"><img src="images/busy.gif"></span></p>
						</div>
<div class="content table-responsive">
	<ul class="nav nav-tabs tabs">
		<li class="active tab">
			<a href="#general_settings" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-home"></i></span> <span class="hidden-xs">General Settings</span> </a>
		</li>
		<li class="tab">
			<a href="#buy_numbers" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Buy Numbers</span> </a>
		</li>
		<?php if($_SESSION['user_type']=='1'){?>
		<li class="tab">
			<a href="#sms_gateways" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">SMS Gateways</span> </a>
		</li>
		<li class="tab">
			<a href="#mobile_devices" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Mobile Devices</span> </a>
		</li>
		<li class="tab">
			<a href="#payment_processors" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Payment Processors</span> </a>
		</li>
		<li class="tab">
			<a href="#pricing_details" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Pricing</span> </a>
		</li>
		<li class="tab">
			<a href="#email_templates" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Email Templates</span> </a>
		</li>
		<?php }?>
		<li class="tab">
			<a href="#propend_messages" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Messages</span> </a>
		</li>
        <li class="tab">
			<a href="#estimote_beacons" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Estimote Beacons</span> </a>
		</li>
		<li class="tab">
			<a href="#cron_jobs_section" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Cron Job</span> </a>
		</li>
		<li class="tab">
			<a href="#bitly_section" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Bitly Keys</span> </a>
		</li>
		<li class="tab">
			<a href="#footer_section" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Footer Customization</span> </a>
		</li>
	</ul>
	
	<div class="tab-content" style="padding:30px 0px;">
		<div class="tab-pane active" id="general_settings">
			<form method="post" action="server.php" enctype="multipart/form-data">
				<div class="form-group">
				<?php
					$colors = array('purple'=>'Purple','blue'=>'Blue','azure'=>'Azure','green'=>'Green','orange'=>'Orange','red'=>'Red','#1A4180'=>'Navy Blue');
				?>
					<label>Sidebar Color</label>
					<select name="sidebar_color" class="form-control" onchange="applySidebarColor(this.value)">
					<?php
						foreach($colors as $k => $v){
							if($row['sidebar_color']==$k)
								$selColor = 'selected="selected"';
							else
								$selColor = '';
							echo '<option '.$selColor.' value="'.$k.'">'.$v.'</option>';
						}
					?>	
					</select>
				</div>
				<div class="form-group">
					<label>Admin Phone Number</label>
					<input type="text" name="admin_phone" class="form-control" value="<?php echo $row['admin_phone']?>"/>
				</div>
				<div class="form-group">
					<label>Time Zone</label>
					<select name="time_zone" class="form-control">
						<?php
							$sqlt = "select * from time_zones";
							$rest = mysqli_query($link,$sqlt)or die(mysqli_error($link));
							if(mysqli_num_rows($rest)){
								while($rowt = mysqli_fetch_assoc($rest)){
								if($row['time_zone']==$rowt['time_zone']){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
									echo '<option '.$selected.' value="'.$rowt['time_zone'].'">'.$rowt['time_zone_value'].'</option>';
								}
							}else{
								echo '<option value="">No time zone added.</option>';	
							}
						?>
					</select>
				</div>
				<div class="form-group">
					<label>Date Format</label>
					<select name="app_date_format" class="form-control">
						<option <?php if($row['app_date_format']=='d-m-Y')echo 'selected="selected"';?> value="d-m-Y">d-m-Y</option>
						<option <?php if($row['app_date_format']=='m-d-Y')echo 'selected="selected"';?> value="m-d-Y">m-d-Y</option>
						<option <?php if($row['app_date_format']=='Y-m-d')echo 'selected="selected"';?> value="Y-m-d">Y-m-d</option>
						<option <?php if($row['app_date_format']=='M-d-y')echo 'selected="selected"';?> value="M-d-y">M-d-y</option>
					</select>
				</div>
				
				<?php if($_SESSION['user_type']=='1'){?>
				<div class="form-group">
					<label>Admin Email</label>
					<input type="email" name="admin_email" class="form-control" value="<?php echo $row['admin_email']?>" />
				</div>
				<div class="form-group">
					<label>API Key</label><br />
					<input type="text" name="api_key" class="form-control" value="<?php echo $row['api_key']?>" style="width:50%; display:inline" readonly/>
					<input type="button" id="generate_key" value="Generate Key" style="display:inline" class="btn btn-btn-success" onclick="generateApiKey(this)" />
				</div>
				<div class="form-group">
					<label>API Base URL</label>
					<p><?php echo getServerUrl().'/nmapi/phpapi.php?cmd=desired_resource'?></p>
				</div>
				<?php }?>
				<!--
				<div class="form-group">
					<label class="">
						<input type="checkbox" name="is_double_optin" <?php if($row['is_double_optin']=='1')echo 'checked="checked"';?> value="1">
						&nbsp;Double Opt-in</label>
				</div>
				-->
				<div class="form-group">
					<?php if($_SESSION['user_type']!='1'){?>
					<label style="color:#F00">Admin's banned words</label>
					<textarea class="form-control" readonly style="text-align:left !important"><?php echo DBout($adminSettings['banned_words'])?></textarea>
					<?php }?>
					<label>Banned Words</label>
					<span style="clear: both !important;color:#451e89;display: block;font-size: 12px;">Enter banned words comma separated.</span>
					<textarea name="banned_words" class="form-control"><?php echo DBout($row['banned_words'])?></textarea>
					<span class="showCounter"> <span class="showCount"><?php echo $maxLength-strlen(DBout($row['banned_words']))?></span> Characters left </span>
				</div>
				<div class="form-group">
					<label>Upload Logo</label>
					<span style="color:red; margin-left:10px;">Recomended dimensions are 170x50</span>
					<input type="file" name="app_logo" />
					<input type="hidden" name="hidden_app_logo" value="<?php echo $row['app_logo']?>" />
				</div>
				<div class="form-group">
					<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
					<input type="hidden" name="cmd" value="update_general_settings" />
				</div>
			</form>
		</div>
		
		<div class="tab-pane" id="buy_numbers">
			<?php
				if($appSettings['sms_gateway']=='twilio'){
					if($_SESSION['user_type']=='1'){ // admin
			?>
						<table width="100%" align="center" style="margin-top:15px !important">
							<tr>
								<td width="30%">Select One</td>
								<td style="text-align:left !important"><label>Buy Number:
										<input type="radio" name="number_type" value="1" onclick="showSections(this)" checked="checked" style="margin:0px !important" />
									</label>
									&nbsp;&nbsp;
									<label>Existing Number:
										<input type="radio" name="number_type" value="3" onclick="showSections(this)" style="margin:0px !important" />
									</label>
								</td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr id="purchase_number">
								<td style="vertical-align:top !important">Select Country</td>
								<td align="left" style="text-align:left !important">
									<select name="phone_type" id="phone_type" onchange="searchNumbers(this.value);" class="form-control" style="width:100% !important">
				<?php
										$countries = getTwilioCountries($sid,$token);
										for($i=0;$i<count($countries->Countries->Country);$i++){
											if($countries->Countries->Country[$i]->CountryCode=="US")
												$sele = 'selected="selected"';
											else
												$sele = '';
											echo '<option '.$sele.' value="'.$countries->Countries->Country[$i]->CountryCode.'">'.$countries->Countries->Country[$i]->Country.'</option>';
											
										}
				?>
									</select>
									<br>
									<div id="usa_section" class="form-group" style="text-align:left">
										<label>State&nbsp;&nbsp;
										<input type="radio" name="us_number_type" value="state" onclick="showSection(this);" />
										</label>
										&nbsp;&nbsp;
										<label>Area Code&nbsp;&nbsp;
										<input type="radio" name="us_number_type" value="areacode" onclick="showSection(this);" />
										</label>
										<div id="showStateSection" style="display:none">
											<select name="state" id="state" class="form-control" onchange="getareacodes(this);" style="width:35% !important; display:inline !important">
				<?php
											$sqlState = "select * from states";
											$resStats = mysqli_query($link,$sqlState);
											if(mysqli_num_rows($resStats)){
												while($rowStats = mysqli_fetch_assoc($resStats)){
													echo '<option value="'.$rowStats['Code'].'">'.$rowStats['State'].'</option>';
												
												}	
											}
				?>
											</select>
											<select name="areacode" id="areacode" class="form-control" onchange="getnumbers(this);" style="width:35% !important; display:inline !important; margin-top:5px;"></select>
										</div>
										<div id="showAraaCodeSection" style="display:none">
											<label>Enter Code: </label>
											<input type="text" name="areacode" id="selected_areacode" class="form-control" style="width:50% !important; display: inline !important" onkeypress="OnKeyPress(event);" />
											<img src="images/search.png" style="display:inline !important; cursor:pointer; margin-left:5px; height:36px; vertical-align:top" title="Search" alt="Search" onclick="getNumberByAreaCode();" />
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td id="existing_number" colspan="2"></td>
							</tr>
						</table>
						<div id="showNumbers" style="display:none"></div>
			<?php			
					}
					else if($_SESSION['user_type']=='2'){ // Sub account
						$getNumberOnload = false;
						$pkgInfo = getAssingnedPackageInfo($_SESSION['user_id']);
			?>
						<table width="100%" align="center" style="margin-top:15px !important">
							<tr>
								<td colspan="2">
									<!--
									Using <?php echo '<span style="color:#F00"><b>'.ucfirst($pkgInfo['sms_gateway']).'</b></span> in <span style="color:#F00"><b>'.$pkgInfo['pkg_country'].'</b></span>'?>
									-->
									Using application in <?php echo '<span style="color:#F00"><b>'.$pkgInfo['pkg_country'].'</b></span>'?>
								</td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td width="30%">Select One</td>
								<td style="text-align:left !important"><label>Buy Number:
										<input type="radio" name="number_type" value="1" onclick="showSections(this)" checked="checked" style="margin:0px !important" />
									</label>
									&nbsp;&nbsp;
									<label>Buy Credits:
										<input type="radio" name="number_type" value="2" onclick="showSections(this)" style="margin:0px !important" />
									</label>
									&nbsp;&nbsp;
									<label>Existing Number:
										<input type="radio" name="number_type" value="3" onclick="showSections(this)" style="margin:0px !important" />
									</label>
								</td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr id="purchase_number">
								<td style="vertical-align:top !important; padding:5px 0px 10px 0px" width="30%">&nbsp;</td>
								<td align="left" style="text-align:left !important">
									<select name="phone_type" id="phone_type" style="display:none; visibility:hidden">
										<option value="<?php echo $pkgInfo['iso_country']?>" selected="selected"><?php echo $pkgInfo['pkg_country']?></option>
									</select>
			<?php 
									if($pkgInfo['iso_country']=='US'){
			?>
									<div id="usa_section" class="form-group" style="text-align:left">
										<label>State&nbsp;&nbsp;
										<input type="radio" name="us_number_type" value="state" onclick="showSection(this);" />
										</label>
										&nbsp;&nbsp;
										<label>Area Code&nbsp;&nbsp;
										<input type="radio" name="us_number_type" value="areacode" onclick="showSection(this);" />
										</label>
										<div id="showStateSection" style="display:none">
											<select name="state" id="state" class="form-control" onchange="getareacodes(this);" style="width:35% !important; display:inline !important">
											<?php
											$sqlState = "select * from states";
											$resStats = mysqli_query($link,$sqlState);
											if(mysqli_num_rows($resStats)){
												while($rowStats = mysqli_fetch_assoc($resStats)){
													echo '<option value="'.$rowStats['Code'].'">'.$rowStats['State'].'</option>';
												}	
											}
											?>
											</select>
											<select name="areacode" id="areacode" class="form-control" onchange="getnumbers(this);" style="width:35% !important; display:inline !important; margin-top:5px;"></select>
										</div>
										<div id="showAraaCodeSection" style="display:none">
											<label>Enter Code: </label>
											<input type="text" name="areacode" id="selected_areacode" class="form-control" style="width:50% !important; display: inline !important" onkeypress="OnKeyPress(event);" />
											<img src="images/search.png" style="display:inline !important; cursor:pointer; margin-left:5px; height:36px; vertical-align:top" title="Search" alt="Search" onclick="getNumberByAreaCode();" />
										</div>
									</div>
			<?php 
									}else{
										$getNumberOnload = true;
									}
			?>
								</td>
							</tr>
							<tr style="display:none" id="buy_credits_section">
								<td>Buy credits</td>
								<td>
			<?php
									if($adminSettings['payment_processor']=="3"){
										$action="add_stripe_credits_form.php";
									}else{
										$action="server.php";
									}
			?>
									<form action="<?php echo $action; ?>" method="post">
										<input type="text" name="credit_quantity" class="form-control" style="width:80% !important; display:inline !important" placeholder="Amount of credits..." required>
										<input type="submit" class="btn btn-danger" value="Buy" style="display:inline !important; vertical-align:top !important">
										<input type="hidden" name="cmd" value="buy_credits">
									</form>
								</td>
							</tr>
							<tr>
								<td id="existing_number" colspan="2"></td>
							</tr>
						</table>
						<div id="showNumbers" style="display:none"></div>
			<?php			
					}
				}
				else if($appSettings['sms_gateway']=='plivo'){
					if($_SESSION['user_type']=='1'){ // admin
			?>
						<table width="100%" align="center" style="margin-top:15px !important">
							<tr>
								<td colspan="1" width="25%">Select One</td>
								<td style="text-align:left !important" colspan="3">
									<label>Buy Number:&nbsp;
										<input type="radio" name="plivo_number_type" value="1" style="margin:0px !important" onclick="showPlivoSections(this)" checked="checked" />
									</label>
									&nbsp;&nbsp;
									<label>Existing Number:&nbsp;
									<input type="radio" name="plivo_number_type" value="3" style="margin:0px !important" onclick="showPlivoSections(this)" />
									</label>
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr id="search_pattren">
								<td width="10%">State:</td>
								<td width="40%">
									<select name="state" class="form-control">
			<?php
										$sels = "select Code,State from states";
										$ress = mysqli_query($link,$sels);
										if(mysqli_num_rows($ress)){
											while($rows = mysqli_fetch_assoc($ress)){
												echo '<option value="'.$rows['Code'].'">'.$rows['State'].'</option>';
											}
										}
			?>
									</select>
								</td>
								<td width="10%" style="padding-left:5px;">Pattern:</td>
								<td width="40%">
									<input maxlength="3" name="pattern" class="form-control" style="width:100px; text-align:center; display:inline">
								&nbsp;<img src="images/search.png" style="cursor:pointer; width:25px; display:inline" title="Search" alt="Search" onclick="searchPlivoNumbers()" />
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr>
								<td id="existing_number" colspan="4"></td>
							</tr>
						</table>
						<div id="showNumbers" style="display:none"></div>
			<?php			
					}
					else if($_SESSION['user_type']=='2'){ // Sub account
						$getNumberOnload = false;
						$pkgInfo = getAssingnedPackageInfo($_SESSION['user_id']);
			?>
						<table width="100%" align="center" style="margin-top:15px !important">
							<tr>
								<td colspan="4">
									<!--
									Using <?php echo '<span style="color:#F00"><b>'.ucfirst($pkgInfo['sms_gateway']).'</b></span> in <span style="color:#F00"><b>'.$pkgInfo['pkg_country'].'</b></span>'?>
									-->
									Using application in <?php echo '<span style="color:#F00"><b>'.$pkgInfo['pkg_country'].'</b></span>'?>
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="1" width="25%">Select One</td>
								<td style="text-align:left !important" colspan="3">
									<label>Buy Number:&nbsp;
										<input type="radio" name="plivo_number_type" value="1" style="margin:0px !important" onclick="showPlivoSections(this)" checked="checked" />
									</label>
									&nbsp;&nbsp;
									<label>Existing Number:&nbsp;
									<input type="radio" name="plivo_number_type" value="3" style="margin:0px !important" onclick="showPlivoSections(this)" />
									</label>
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr id="search_pattren">
								<td width="10%">State:</td>
								<td width="40%">
									<select name="state" class="form-control">
			<?php
										$sels = "select Code,State from states";
										$ress = mysqli_query($link,$sels);
										if(mysqli_num_rows($ress)){
											while($rows = mysqli_fetch_assoc($ress)){
												echo '<option value="'.$rows['Code'].'">'.$rows['State'].'</option>';
											}
										}
			?>
									</select>
								</td>
								<td width="10%" style="padding-left:5px;">Pattern:</td>
								<td width="40%">
									<input maxlength="3" name="pattern" class="form-control" style="width:100px; text-align:center; display:inline">
								&nbsp;<img src="images/search.png" style="cursor:pointer; width:25px; display:inline" title="Search" alt="Search" onclick="searchPlivoNumbers()" />
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr>
								<td id="existing_number" colspan="4"></td>
							</tr>
						</table>
						<div id="showNumbers" style="display:none"></div>
			<?php
						if($pkgInfo['iso_country']!='US'){
							$getNumberOnload = true;
						}
					}
				}
				else if($appSettings['sms_gateway']=='nexmo'){
					if($_SESSION['user_type']=='1'){ // admin
			?>
						<table width="100%" align="center" style="margin-top:15px !important">
							<tr>
								<td colspan="1" width="25%">Select One</td>
								<td style="text-align:left !important" colspan="3">
									<label>Buy Number:&nbsp;
										<input type="radio" name="nexmo_number_type" value="1" style="margin:0px !important" onclick="showNexmoSections(this)" checked="checked" />
									</label>
									&nbsp;&nbsp;
									<label>Existing Number:&nbsp;
										<input type="radio" name="nexmo_number_type" value="3" style="margin:0px !important" onclick="showNexmoSections(this)" />
									</label>
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr id="purchase_nexmo_number">
								<td width="10%">Select Country:</td>
								<td width="40%">
									<select name="nexmo_country" class="form-control" onchange="searchNexmoNumbers(this.value)">
			<?php
										$isoCountries = countries();
										foreach($isoCountries as $key => $value){
											if($key=="US")
												$sele = 'selected="selected"';
											else
												$sele = '';
											echo '<option '.$sele.' value="'.$key.'">'.$value.'</option>';
										}
			?>	
									</select>
								</td>
								<td width="10%" style="padding-left:5px;">&nbsp;</td>
								<td width="40%">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr>
								<td id="existing_number" colspan="4"></td>
							</tr>
						</table>
						<div id="showNumbers" style="display:none"></div>
			<?php
					}
					else if($_SESSION['user_type']=='2'){ // Sub account
						$getNumberOnload = false;
						$pkgInfo = getAssingnedPackageInfo($_SESSION['user_id']);
			?>
						<table width="100%" align="center" style="margin-top:15px !important">
							<tr>
								<td colspan="4">
									<!--
									Using <?php echo '<span style="color:#F00"><b>'.ucfirst($pkgInfo['sms_gateway']).'</b></span> in <span style="color:#F00"><b>'.$pkgInfo['pkg_country'].'</b></span>'?>
									-->
									Using application in <?php echo '<span style="color:#F00"><b>'.$pkgInfo['pkg_country'].'</b></span>'?>
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="1" width="25%">Select One</td>
								<td style="text-align:left !important" colspan="3">
									<label>Buy Number:&nbsp;
										<input type="radio" name="nexmo_number_type" value="1" style="margin:0px !important" onclick="showNexmoSections(this)" checked="checked" />
									</label>
									&nbsp;&nbsp;
									<label>Existing Number:&nbsp;
										<input type="radio" name="nexmo_number_type" value="3" style="margin:0px !important" onclick="showNexmoSections(this)" />
									</label>
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr id="purchase_nexmo_number">
								<td width="10%">Select Country:</td>
								<td width="40%">
									<select name="nexmo_country" class="form-control" onchange="searchNexmoNumbers(this.value)">
			<?php
										$isoCountries = countries();
										foreach($isoCountries as $key => $value){
											if($key=="US")
												$sele = 'selected="selected"';
											else
												$sele = '';
											echo '<option '.$sele.' value="'.$key.'">'.$value.'</option>';
										}
			?>	
									</select>
								</td>
								<td width="10%" style="padding-left:5px;">&nbsp;</td>
								<td width="40%">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr>
								<td id="existing_number" colspan="4"></td>
							</tr>
						</table>
						<div id="showNumbers" style="display:none"></div>
			<?php
					}
				}
				else if($appSettings['sms_gateway']=='mobile_sim'){
					echo '<div style="color:red;border-left-color:red; text-align:left" class="updated"><p>Your mobile number will use to send messages.</p></div>';	
				}else{
					echo '<div style="color:red;border-left-color:red; text-align:left" class="updated"><p>You are not able to buy numbers for now.</p></div>';		
				}
			?>	
		</div>
		
		<div class="tab-pane" id="mobile_devices">
			<div class="alert alert-warning">
				<h4>Instructions</h4>
				<strong>
					You can get Nimble Messaging android app from <a href="https://codecanyon.net/item/nimble-messaging-business-mobile-sms-marketing-application-for-android/20956083" target="_blank">here</a>.<br />
					After installing Nimble Android App follow the steps listed below:<br />
					1. Enter the url of your nimble messaging web app and hit "GO".<br />
					2. After verifying your appUrl enter any device name of your choice and hit "Go".<br />
					3. Sign in to your nimble messaging app with the credentials you use on Nimble Messaging web app.<br />
					4. Device Id with user info will be saved in the app.<br />
					6. Now you can enable device messaging feature from top menu in the app<br />
					7. you have to allow following permissions on runtime in order to enable this feature.<br />
						 <span style="margin-left:15px;">&gt; send and view sms messages</span><br />
						 <span style="margin-left:15px;">&gt; make and manage phone calls</span><br />
					8. By allowing second permission the app only will get sim status and info in order to enable dual sim feature.<br />
					9. You can enable/disable sms response as well any time.<br />
					10. You can also select sim for messaging if device has dual sim feature otherwise it will use default sim.<br />
				</strong>
			</div>
			<form method="post" action="server.php" enctype="multipart/form-data">
			<?php
				$selDevices = "select * from mobile_devices where device_token!='' order by id desc";
				$exeDevices = mysqli_query($link,$selDevices);
				if(mysqli_num_rows($exeDevices)==0){
					echo '<div class="alert alert-danger">No active device found.</div>';
				}else{
					echo '<ul class="list-group">';
					while($device = mysqli_fetch_assoc($exeDevices)){
						if($device['id']==$row['device_id'])
							$seld = 'checked="checked"';
						else
							$seld = '';
						echo '<li class="list-group-item"><label>';
							echo '<input '.$seld.' type="radio" name="mobile_device" style="margin-top:-2px; vertical-align:middle" value="'.$device['id'].'"> '.$device['device_name'].'</label>';
							echo '<label class="label label-success" style="float:right; color:#ffffff; font-weight:bold; padding:5px;">'.$device['created_date'].'</label>';
						echo '</li>';
					}
					echo '</ul>';
				}
			?>	
			<div class="form-group">
				<div class="col-md-4">
					<input type="hidden" name="cmd" value="update_mobile_device" />
					<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
				</div>
				<div class="col-md-8" style="text-align:right; padding-right:0px;">
					<a target="_blank" href="<?php echo getServerUrl().'/tandc.php'?>">Terms and Conditions</a>&nbsp;
					<a href="https://codecanyon.net/item/nimble-messaging-business-mobile-sms-marketing-application-for-android/20956083" target="_blank">Get Mobile App</a>
				</div>
			</div>
			</form>
		</div>
		<?php if($_SESSION['user_type']=='1'){?>
		<div class="tab-pane" id="sms_gateways">
			<form method="post" action="server.php" enctype="multipart/form-data">
			<?php
				$twilio = 'none';
				$plivo  = 'none';
				$nexmo  = 'none';
				$mobileSim  = 'none';
				if(($row['sms_gateway']=='twilio') || (trim($row['sms_gateway'])=='')){
					$twilio = 'block';
				}else if($row['sms_gateway']=='plivo'){
					$plivo  = 'block';
				}else if($row['sms_gateway']=='nexmo'){
					$nexmo  = 'block';
				}else if($row['sms_gateway']=='mobile_sim'){
					$mobileSim  = 'block';
				}
			?>
				<div class="form-group">
					<label>SMS Gateway</label>
					<select name="sms_gateway" class="form-control smsGateWay">
						<option <?php if($row['sms_gateway']=='twilio')echo 'selected="selected"';?> value="twilio">Twilio</option>
						<option <?php if($row['sms_gateway']=='plivo')echo 'selected="selected"';?> value="plivo">Plivo</option>
						<option <?php if($row['sms_gateway']=='nexmo')echo 'selected="selected"';?> value="nexmo">Nexmo</option>
						<!--
						<option <?php if($row['sms_gateway']=='mobile_sim')echo 'selected="selected"';?> value="mobile_sim">Mobile Sim</option>
						-->
					</select>
				</div>
				
				<!-- Nexmo -->
				<div class="nexmoInfo" style="display:<?php echo $nexmo?>">
					<div class="alert alert-danger"><span><b> Warning - </b> Nexmo does not support MMS messages.<br /><b> Info - </b> Please set this <?php echo getServerUrl().'/sms_controlling.php'?> as a webhook url by editing your desired number from nexmo dashboard.</span></div>
					<div class="form-group">
						<label>Nexmo API Key</label>
						<input type="text" name="nexmo_api_key" class="form-control" value="<?php echo $row['nexmo_api_key']?>" />
					</div>
					<div class="form-group">
						<label>Nexmo API Secret</label>
						<input type="text" name="nexmo_api_secret" class="form-control" value="<?php echo $row['nexmo_api_secret']?>" />
					</div>
				</div>
				<!-- Nexmo end -->
				
				<!-- Plivo -->
				<div class="plivoInfo" style="display:<?php echo $plivo?>">
					<div class="form-group">
						<label>Plivo Auth ID</label>
						<input type="text" name="plivo_auth_id" class="form-control" value="<?php echo $row['plivo_auth_id']?>" />
					</div>
					<div class="form-group">
						<label>Plivo Auth Token</label>
						<input type="text" name="plivo_auth_token" class="form-control" value="<?php echo $row['plivo_auth_token']?>" />
					</div>
				</div>
				<!-- Plivo end --> 
				
				<!-- Twilio -->
				<div class="twilioInfo" style="display:<?php echo $twilio?>">
					<div class="form-group">
						<label>Twilio Account sid</label>
						<input type="text" name="twilio_sid" class="form-control" value="<?php echo $row['twilio_sid']?>" />
					</div>
					<div class="form-group">
						<label>Twilio Account Token</label>
						<input type="text" name="twilio_token" class="form-control" value="<?php echo $row['twilio_token']?>" />
					</div>
					<div class="form-group">
						<?php
							$disSenderID = 'none';
							$senderIDCheck = '';
							if($row['enable_sender_id']=='1'){
								$senderIDCheck = 'checked="checked"';
								$disSenderID = 'block';
							}
						?>
						<label>Enable Sender ID:
							<input <?php echo $senderIDCheck?> type="checkbox" name="enable_sender_id" class="enableSenderID" value="1" />
						</label>
					</div>
					<div class="form-group senderID" style="display:<?php echo $disSenderID?>">
						<p style="font-size:12px; color:red">
							Before using Must enable in your twilio account.<br />
							Must configure your phone number with a twilio messaging service.<br />
							<i>For branded one-way messaging, many countries allow an alphanumeric string as the sender ID. Alpha Sender ID allows you to add your company name or brand to your Messaging Service. When sending messages to a country where an alphanumeric sender ID is accepted, Twilio will use your Alpha Sender ID as the From parameter to deliver your message. A phone number from your Messaging Service will be selected if your recipient is in a country where alphanumeric sender IDs are not supported.</i></p>
						<label>Twilio Sender ID</label>
						<input type="text" name="twilio_sender_id" class="form-control" value="<?php echo $row['twilio_sender_id']?>" />
					</div>
				</div>
				<!-- twilio end -->
				
				<!-- Mobile Sim -->
				<div class="form-group mobileSimSection" style="display:<?php echo $mobileSim?>">
				<?php
					/*
					$selDevices = "select * from mobile_devices where device_token!='' order by id desc";
					$exeDevices = mysqli_query($link,$selDevices);
					if(mysqli_num_rows($exeDevices)==0){
						echo '<div class="alert alert-danger">No active device found.</div>';
					}else{
						echo '<ul class="list-group">';
						while($device = mysqli_fetch_assoc($exeDevices)){
							if($device['id']==$row['device_id'])
								$seld = 'checked="checked"';
							else
								$seld = '';
							echo '<li class="list-group-item"><label>';
								echo '<input '.$seld.' type="radio" name="mobile_device" style="margin-top:-2px; vertical-align:middle" value="'.$device['id'].'"> '.$device['device_name'].'</label>';
								echo '<label class="label label-success" style="float:right; color:#ffffff; font-weight:bold; padding:5px;">'.$device['created_date'].'</label>';
							echo '</li>';
						}
						echo '</ul>';
					}
					*/
				?>
				</div>
				<!-- Mobile sim end -->
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_sms_gateways" />
					<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
				</div>
			</form>
		</div>
		<div class="tab-pane" id="payment_processors">
			<form action="server.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label class="radio-inline">
						<input type="radio" name="payment_processor" value="1" <?php if(($row['payment_processor']==1) || ($row['payment_processor']=='0')){ echo "checked"; } ?> >
						Paypal</label>
					&nbsp;&nbsp;
					<label class="radio-inline">
						<input type="radio" name="payment_processor" value="2" <?php if($row['payment_processor']==2){ echo "checked"; } ?>>
						Auth.Net</label>
					&nbsp;&nbsp;
					<label class="radio-inline">
						<input type="radio" name="payment_processor" value="3" <?php if($row['payment_processor']==3){ echo "checked"; } ?>>
						Stripe</label>
				</div>
				<?php 
					if(($row['payment_processor']==1) || $row['payment_processor']==0){
	
	$paypal="block";
	
	$auth="none";
	
	$stripe="none";
	
					}else if($row['payment_processor']==2){
	
	$paypal="none";
	
	$auth="block";
	
	$stripe="none";
	
					}else if($row['payment_processor']==3){
	
	$paypal="none";
	
	$auth="none";
	
	$stripe="block";
	
					}
				?>
				<div id="stripe_area" style="display:<?php echo $stripe; ?>;">
					<div class="form-group" id="stripe_secret_key">
						<label>Stripe</label>
						<br />
						<label>Stripe Secret Key</label>
						<input type="text" name="stripe_secret_key" class="form-control" value="<?php echo $row['stripe_secret_key']?>"/>
					</div>
					<div class="form-group" id="stripe_publishable_key">
						<label>Stripe Publishable Key</label>
						<input type="text" name="stripe_publishable_key" class="form-control" value="<?php echo $row['stripe_publishable_key']?>"/>
					</div>
				</div>
				<div id="authnet_area" style="display:<?php echo $auth; ?>;">
					<div class="form-group" id="auth_net_trans_key">
						<label>Authorize.Net</label>
						<br />
						<label>Transaction ID</label>
						<input type="text" name="auth_net_trans_key" class="form-control" value="<?php echo $row['auth_net_trans_key']?>"/>
					</div>
					<div class="form-group" id="auth_net_api_login_id">
						<label>API Login ID</label>
						<input type="text" name="auth_net_api_login_id" class="form-control" value="<?php echo $row['auth_net_api_login_id']?>"/>
					</div>
				</div>
				<div id="paypal_area" style="display:<?php echo $paypal; ?>;">
					<div class="form-group">
						<label>Paypal</label><br />
						<?php 
						if($row['paypal_switch']=='0'){
							$sandbox = 'checked="checked"';
							$sandboxSection = 'block';
							$live = '';
							$liveSection = 'none';
						}else{
							$live = 'checked="checked"';
							$liveSection = 'block';
							$sandbox = '';
							$sandboxSection = 'none';
						}
						?>
						<label class="radio-inline">
							<input type="radio" name="paypal_switch" value="0" <?php echo $sandbox?>>
							Sandbox</label>
						&nbsp;&nbsp;
						<label class="radio-inline">
							<input type="radio" name="paypal_switch" value="1" <?php echo $live?>>
							Live</label>
					</div>
					<div class="form-group" id="paypal_sandbox_email" style="display:<?php echo $sandboxSection?>;">
						<label>Paypal Sandbox Email</label>
						<input type="email" name="paypal_sandbox_email" class="form-control" value="<?php echo $row['paypal_sandbox_email']?>"/>
					</div>
					<div class="form-group" id="paypal_live_email" style="display:<?php echo $liveSection?>;">
						<label>Paypal Live Email</label>
						<input type="email" name="paypal_email" class="form-control" value="<?php echo $row['paypal_email']?>"/>
					</div>
				</div>
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_payment_processor" />
					<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
				</div>
			</form>
		</div>
		<div class="tab-pane" id="pricing_details">
			<form action="server.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label>InComing SMS Credits Charges</label>
					<input type="text" name="incoming_sms_charge" class="form-control decimalOnly" value="<?php echo $row['incoming_sms_charge']?>" required="required"/>
				</div>
				<div class="form-group">
					<label>Outgoing SMS Credits Charges</label>
					<input type="text" name="outgoing_sms_charge" class="form-control decimalOnly" value="<?php echo $row['outgoing_sms_charge']?>" required="required"/>
				</div>
				<div class="form-group">
					<label>MMS Credits Charges</label>
					<input type="text" name="mms_credit_charges" class="form-control decimalOnly" value="<?php echo $row['mms_credit_charges']?>" required="required"/>
				</div>
				<div class="form-group">
					<label>Per Credit Charges</label>
					<input type="text" name="per_credit_charges" class="form-control decimalOnly" value="<?php echo $row['per_credit_charges']?>" required="required"/>
				</div>
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_pricing_details" />
					<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
				</div>
			</form>
		</div>
		<div class="tab-pane" id="email_templates">
			<form action="server.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label>New User Email Subject </label>
					<input type="text" name="email_subject" class="form-control" value="<?php echo DBout($row['email_subject'])?>" />
				</div>
				<div class="form-group">
					<label>New User Email Message</label>
					<span style="clear: both !important;color:#451e89;display: block;font-size: 12px;">Merge tags: First name = %first_name%, Last name = %last_name% Login Email = %login_email%, Login password = %login_pass%, Login URL = %login_url%</span>
					<textarea name="new_app_user_email" id="new_user_email" class="form-control"><?php echo $row['new_app_user_email']?></textarea>
				</div>
				<div class="form-group">
					<label>New User Email Notification Subject for Admin </label>
					<input type="text" name="email_subject_for_admin_notification" class="form-control" value="<?php echo DBout($row['email_subject_for_admin_notification'])?>" />
				</div>
				<div class="form-group">
					<label>New User Email Notification Message for Admin</label>
					<span style="clear: both !important;color:#451e89;display: block;font-size: 12px;">Merge tags: Email = %email%</span>
					<textarea name="new_app_user_email_for_admin" id="new_app_user_email_for_admin" class="form-control"><?php echo $row['new_app_user_email_for_admin']?></textarea>
				</div>
				<div class="form-group">
					<label>Successful Payment Email Subject </label>
					<input type="text" name="success_payment_email_subject" class="form-control" value="<?php echo DBout($row['success_payment_email_subject'])?>" />
				</div>
				<div class="form-group">
					<label>Successful Payment Email Message</label>
					<textarea name="success_payment_email" id="success_payment_email" class="form-control"><?php echo $row['success_payment_email']?></textarea>
				</div>
				<div class="form-group">
					<label>Failed Payment Email Subject </label>
					<input type="text" name="failed_payment_email_subject" class="form-control" value="<?php echo DBout($row['failed_payment_email_subject'])?>" />
				</div>
				<div class="form-group">
					<label>Failed Payment Email Message</label>
					<textarea name="failed_payment_email" id="failed_payment_email" class="form-control"><?php echo $row['failed_payment_email']?></textarea>
				</div>
				<div class="form-group">
					<label>Payment Notification Email Subject </label>
					<input type="text" name="payment_noti_subject" class="form-control" value="<?php echo DBout($row['payment_noti_subject'])?>" />
				</div>
				<div class="form-group">
					<label>Payment Notification Email Message</label>
					<span style="clear: both !important;color:#451e89;display: block;font-size: 12px;">Merge tags: Email = %email%</span>
					<textarea name="payment_noti_email" id="payment_noti_email" class="form-control"><?php echo $row['payment_noti_email']?></textarea>
				</div>			
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_email_templates" />
					<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
				</div>
			</form>
		</div>
		<?php }?>
		<div class="tab-pane" id="propend_messages">
			<form action="server.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label>Append Text</label>
					<span style="clear: both !important;color:#451e89;display: block;font-size: 12px;">Will be appended with each outbound message. </span>
					<textarea name="append_text" class="form-control textCounter"><?php echo DBout($row['append_text'])?></textarea>
					<span class="showCounter"> <span class="showCount"><?php echo $maxLength-strlen(DBout($row['append_text']))?></span> Characters left </span>
				</div>
				<!--
				<div class="form-group">
					<label>Typo Message</label>
					<span style="clear: both !important;color:#451e89;display: block;font-size: 12px;">Will respond on wrong or invalid keyword. </span>
					<textarea name="typo_message" class="form-control textCounter"><?php echo DBout($row['typo_message'])?></textarea>
					<span class="showCounter"> <span class="showCount"><?php echo $maxLength-strlen(DBout($row['typo_message']))?></span> Characters left </span>
				</div>
				-->
				<div class="form-group">
					<label>Unsubscribe Message</label>
					<span style="clear: both !important;color:#451e89;display: block;font-size: 12px;">Will respond on unsubscription. </span>
					<textarea name="unsub_message" class="form-control textCounter"><?php echo DBout($row['unsub_message'])?></textarea>
					<span class="showCounter"> <span class="showCount"><?php echo $maxLength-strlen(DBout($row['unsub_message']))?></span> Characters left </span>
				</div>
				<div class="form-group">
					<label>GDPR Message</label>
					<span style="clear:both !important;color:#451e89;display: block;font-size: 12px;">Will respond on receiving GDPR Keyword, use %gdpr_link% where you want to add profile link in message.</span>
					<textarea name="gdpr_message" class="form-control textCounter"><?php echo DBout($row['gdpr_message'])?></textarea>
					<span class="showCounter"> <span class="showCount"><?php echo $maxLength-strlen(DBout($row['gdpr_message']))?></span> Characters left </span>
				</div>
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_propend_msgs" />
					<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
				</div>
			</form>
		</div>
        <div class="tab-pane" id="estimote_beacons">
			<form action="server.php" method="post" enctype="multipart/form-data">
				
                <div class="alert alert-info">You can purchase beacons from <a target="_blank" style="text-decoration:underline; color:#FFF; font-weight:bold;" href="https://estimote.com/">here</a></div>
                
                
                <a href="https://www.youtube.com/user/Estimote/videos" target="_blank"> <i class="fa fa-youtube-play" style="color:red;"></i> Youtube Channel Link </a>
                                
                <br /><br />
                                
                <div class="form-group">
					<label>App ID</label>
					<input name="estimote_app_id" class="form-control" value="<?php echo DBout($row['estimote_app_id'])?>" />
				</div>
				<div class="form-group">
					<label>App Token</label>
					<input name="estimote_app_token" class="form-control" value="<?php echo DBout($row['estimote_app_token'])?>" />
				</div>
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_beacon_credentials" />
					<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
				</div>
			</form>
            
            <strong>To get App credentials, please create app on estimote from <a href="https://cloud.estimote.com/#/apps" target="_blank">here</a></strong>
            
		</div>
		<?php
			if($_SESSION['user_type']=='1'){
		?>
		<div class="tab-pane" id="cron_jobs_section">
			<form method="post" enctype="multipart/form-data" action="server.php">
				<div class="form-group">
					<label>Cron Stop Time From</label>
					<input type="text" name="cron_stop_time_from" id="cron_stop_time_from" class="form-control" value="<?php echo $row['cron_stop_time_from']?>" />
				</div>
				<div class="form-group">
					<label>Cron Stop Time To</label>
					<input type="text" name="cron_stop_time_to" id="cron_stop_time_to" class="form-control" value="<?php echo $row['cron_stop_time_to']?>" />
				</div>
				<div class="form-group">
					<label style="color:red">Cron URL for every 15 minutes: </label><br />
					<p><?php echo 'curl '.getServerURL().'/cron.php'?></p>
					Or
					<p>wget -q -O - <?php echo getServerURL().'/cron.php'?> >/dev/null 2>&1</p>
					<img src="images/cron_guide.png" width="100%" />
					<img src="images/cron_guide_2.png" width="100%" />
				</div>
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_cronjob_settings" />
					<input type="submit" value="Update" class="btn btn-primary" />
				</div>
			</form>
		</div>
		
		<div class="tab-pane" id="bitly_section">
			<form method="post" action="server.php">
				<div class="form-group">
					<label>Bit.ly API Key</label>
					<input type="text" name="bitly_key" class="form-control" value="<?php echo $row['bitly_key']?>" />
				</div>
				<div class="form-group">
					<label>Bit.ly Token</label>
					<input type="text" name="bitly_token" class="form-control" value="<?php echo $row['bitly_token']?>" />
				</div>
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_bitly_api_keys" />
					<input type="submit" value="Save" class="btn btn-primary" />
				</div>
			</form>
		</div>
		<?php }
		?>
		<div class="tab-pane" id="footer_section">
			<form method="post" enctype="multipart/form-data" action="server.php">
				<div class="form-group">
					<label>Footer Customization</label>
					<textarea name="footer_customization" id="footer_customization" class="form-control"><?php echo DBout($row['footer_customization'])?></textarea>
				</div>
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_footer_customization" />
					<input type="submit" value="Update" class="btn btn-primary" />
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
	<?php include_once("footer_info.php");?>
</div>
<div id="nexmoInfoModel" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title" style="color:red">Nexmo Information</h6>
			</div>
			<div class="modal-body" style="overflow:auto">
				<p>1- You have to set webhook url for this number from your nexmo dashboard.</p>
				<p><img src="images/nexmo_webhook_guide.png" style="width:100%" /></p>
				<p>2- Enter webhook url in mentioned field.</p>
				<p><img src="images/webhook_section.png" style="width:100%" /></p>
			</div>
		</div>
	</div>
</div>
<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script src="assets/js/timepicki.js"></script> 
<script src="assets/js/bootstrap-select.min.js"></script> 
<script type="text/javascript">
	$(document).ready(function(){
		$('#cron_stop_time_from').timepicki();
		$('#cron_stop_time_to').timepicki();
	});
</script>
<script type="text/javascript">
$('#plivoTable').cardtable();
function addNexmoToInstall(phoneNumber){
	$("#loading").show();
	if(confirm("Are you sure you want to assign this number to application?")){
		$.post("server.php",{"cmd":"add_nexmo_to_install","phone_number":phoneNumber},function(r){
			$("#loading").hide();
			if(r!='1'){
				alert(r);	
			}
			window.location='settings.php';
		});
	}
}
function removeNexmoFromInstall(phoneNumber){
	$("#loading").show();
	if(confirm("Are you sure you want to remove this number from application?")){
		if(confirm("All campaigns configured with this number will be soppped working.")){
			$.post("server.php",{"cmd":"remove_nexmo_from_install","phone_number":phoneNumber},function(r){
				$("#loading").hide();
				if(r!='1'){
					alert(r);	
				}
				window.location='settings.php';
			});
		}
	}
}
function generateApiKey(obj){
	var apikey = '<?php echo $row['api_key']?>';
	if($.trim(apikey)==''){
		$(obj).val('Generating...');
		$.post('server.php',{"cmd":"generate_apikey"},function(r){
			$('input[name="api_key"]').val(r);
			$(obj).val('Generate Key');
		});
	}else{
		if(confirm("Are you sure? Previous key will be stopped working?")){
			$(obj).val('Generating...');
			$.post('server.php',{"cmd":"generate_apikey"},function(r){
				$('input[name="api_key"]').val(r);
				$(obj).val('Generate Key');
			});
		}
	}
}
function applySidebarColor(color){
    if(color=="#1A4180"){
        $('.sidebar-wrapper').css('background',color);
        $('.sidebar').attr('data-color',"");
    }else{
        $('.sidebar').attr('data-color',color);
        $('.sidebar-wrapper').css('background',"");
    }
	
    
    
}
function showNexmoSections(obj){
	var numType = $(obj).val();
	if(numType=='1'){
		$("#buy_credits_section").hide('slow');
		//searchPlivoNumbers();
		$("#purchase_number").hide('slow');
		$('#search_pattren').hide('slow');
		$("#showNumbers").hide('slow');
		$("#existing_number").hide('slow');
		$("#purchase_nexmo_number").show('slow');
	}else if(numType=='2'){
		$("#buy_credits_section").show('slow');
		$('#search_pattren').hide('slow');
		$("#purchase_number").hide('slow');
		//$("#existing_number").html(res);
		$("#showNumbers").hide('slow');
		$("#existing_number").hide('slow');
		$("#existing_number").hide('slow');
		$("#purchase_nexmo_number").hide('slow');
		$("#loading").hide();
	}else{
		$("#loading").show();
		<?php if($_SESSION['user_type']=='1'){?>
		var Qry = 'cmd=get_nexmo_existing_numbers';
		<?php }else{?>
		var Qry = 'cmd=get_nexmo_existing_numbers_in_subaccount&user_id=<?php echo $_SESSION['user_id']?>';
		<?php }?>
		$.post("server.php",Qry,function(res){
			$("#buy_credits_section").hide('slow');
			$('#search_pattren').hide('slow');
			$("#purchase_number").hide('slow');
			$("#existing_number").html(res);
			$("#showNumbers").hide('slow');
			$("#purchase_nexmo_number").hide('slow');
			$("#existing_number").show('slow');
			$("#loading").hide();
		});
	}
}
function buyNexmoNumber(){
	var phoneNumber = $('input[class="nexmo_buy_number"]:checked').val();
	var isoCountry	= $('select[name="nexmo_country"] option:selected').val();
	if(phoneNumber=="undefined"){
		alert("Select at least one number.");
		return false;
	}else{
		if(confirm("Are you sure you want to buy this number?")){
			$("#loading").show();
			var Qry = 'cmd=buy_nexmo_number&phoneNumber='+encodeURIComponent(phoneNumber)+'&isoCountry='+isoCountry;
			$.post('server.php',Qry,function(r){
				window.location = 'settings.php';
			});
		}
	}
}
function searchNexmoNumbers(ISOCountry){
	$("#loading").show();
	$("#showNumbers").html('');
	var Qry = 'cmd=search_nexmo_numbers&ISOCountry='+ISOCountry;
	$.post("server.php",Qry,function(res){
		$("#showNumbers").html(res);
		$("#showNumbers").show();
		$("#loading").hide();
	});
}

function removePlivoFromInstall(phoneNumber){

	if(confirm("Are you sure you want to remove number from this install?")){

		$("#loading").show();

		var Qry = 'cmd=remove_plivo_from_install&phoneNumber='+encodeURIComponent(phoneNumber);

		$.post("server.php",Qry,function(res){

			if(res=="1"){

				$("#loading").html('<span style="color:green">Number released from your install.</span>');	

				window.location = 'settings.php';

			}else{

				$("#loading").html('<span style="color:red">'+res+'</span>');

			}

		});

	}}

function addPlivoToInstall(phoneNumber){

	if(confirm("Are you sure you want to assign number to this install?")){

		$("#loading").show();

		var Qry = 'cmd=add_plivo_number_to_install&phoneNumber='+encodeURIComponent(phoneNumber);

		$.post("server.php",Qry,function(res){
			if(res=="1"){
				$("#loading").html('<span style="color:green">Number assigned to your install.</span>');	
				window.location = 'settings.php';
			}else{
				$("#loading").html('<span style="color:red">'+res+'</span>');
			}

		});

	}}

function buyPlivoNumber(){

	var phoneNumber = $('input[class="plivo_buy_number"]:checked').val();

	if((phoneNumber=="") || (phoneNumber=="undefined")){

		alert("Select at least one number.");

	}else{

		if(confirm("Are you sure you want to buy this number?")){

			$("#loading").show();

			var Qry = 'cmd=buy_plivo_number&phoneNumber='+encodeURIComponent(phoneNumber);

			$.post('server.php',Qry,function(r){

				window.location = 'settings.php';

			});

		}

	}}

function searchPlivoNumbers(){
	$("#loading").show();
	$("#showNumbers").html('');
	var state = $('select[name="state"]').val();
	var pattern = $('input[name="pattern"]').val();
	var Qry = 'cmd=search_plivo_numbers&state='+state+'&pattern='+pattern;
	$.post("server.php",Qry,function(res){
		$("#showNumbers").html(res);
		$("#showNumbers").show();
		$("#loading").hide();
	});}

function showPlivoSections(obj){
	var numType = $(obj).val();
	if(numType=='1'){
		$("#buy_credits_section").hide('slow');
		//searchPlivoNumbers();
		$("#purchase_number").show('slow');
		$('#search_pattren').show('slow');
		$("#showNumbers").show('slow');
		$("#existing_number").hide('slow');
	}else if(numType=='2'){
		$("#buy_credits_section").show('slow');
		$('#search_pattren').hide('slow');
		$("#purchase_number").hide('slow');
		//$("#existing_number").html(res);
		$("#showNumbers").hide('slow');
		$("#existing_number").hide('slow');
		$("#loading").hide();
	}else{
		$("#loading").show();
		<?php if($_SESSION['user_type']=='1'){?>
		var Qry = 'cmd=get_plivo_existing_numbers';
		<?php }else{?>
		var Qry = 'cmd=get_plivo_existing_numbers_for_subaccount&user_id=<?php echo $_SESSION['user_id']?>';
		<?php }?>
		$.post("server.php",Qry,function(res){
			$("#buy_credits_section").hide('slow');
			$('#search_pattren').hide('slow');
			$("#purchase_number").hide('slow');
			$("#existing_number").html(res);
			$("#showNumbers").hide('slow');
			$("#existing_number").show('slow');
			$("#loading").hide();
		});
	}
}



$('.smsGateWay').on('change',function(r){
	$('.nexmoInfo').hide('slow');
	$('.plivoInfo').hide('slow');
	$('.twilioInfo').hide('slow');
	$('.mobileSimSection').hide('slow');
	
	if($(this).val()=='twilio'){
		$('.twilioInfo').show('slow');
	}else if($(this).val()=='plivo'){
		$('.plivoInfo').show('slow');
	}else if($(this).val()=='nexmo'){
		$('.nexmoInfo').show('slow');
	}else if($(this).val()=='mobile_sim'){
		$('.mobileSimSection').show('slow');
	}
});



	window.onload = function(){
		<?php if($_SESSION['user_type']=='1'){?>
		CKEDITOR.config.autoParagraph = false;
		CKEDITOR.replace('footer_customization');
		CKEDITOR.config.autoParagraph = false;
		CKEDITOR.replace('new_user_email');
		CKEDITOR.config.autoParagraph = false;
		CKEDITOR.replace('success_payment_email');
		CKEDITOR.config.autoParagraph = false;
		CKEDITOR.replace('failed_payment_email');
		CKEDITOR.config.autoParagraph = false;
		CKEDITOR.replace('payment_noti_email');
		CKEDITOR.config.autoParagraph = false;
		CKEDITOR.replace('new_app_user_email_for_admin');
		<?php 
			}
			if(@$getNumberOnload){
		?>
			searchNumbers('<?php echo $pkgInfo['iso_country']?>');
		<?php
			}
		?>
	};

</script> 
<!--<script src="scripts/js/bootstrap-datetimepicker.min.js"></script> -->
<script>

$('.enableSenderID').on('click',function(){

	if($(this).is(":checked")==true){

		$('.senderID').show('slow');

	}else{

		$('.senderID').hide('slow');

	}

});



$('input[name="payment_processor"]').on('click',function(r){

	if($(this).val()=='1'){

		$('#authnet_area').hide('slow');

        $('#paypal_area').show('slow');

        $('#stripe_area').hide('slow');

	}

    else if($(this).val()=='2'){

        $('#paypal_area').hide('slow');

		$('#authnet_area').show('slow');

        $('#stripe_area').hide('slow');

    }

	else{

		$('#paypal_area').hide('slow');

		$('#authnet_area').hide('slow');

        $('#stripe_area').show('slow');

        	

	}

});



$('input[name="paypal_switch"]').on('click',function(r){

	if($(this).val()=='0'){

		$('#paypal_sandbox_email').show('slow');

		$('#paypal_live_email').hide('slow');

	}

	else{

		$('#paypal_sandbox_email').hide('slow');

		$('#paypal_live_email').show('slow');	

	}

});

function buyNumber(){

	var arr = new Array();

	var checked = $('[name=buy_num]:checked');

	var country = $('#phone_type option:selected').text();

	var ISOcountry = $('#phone_type option:selected').val();

	checked.each(function(){

		arr.push($(this).val());

	});

	if(arr==""){

		alert("Select at least one number.");

	}

	else{

		if(confirm("Are you sure you want to buy number(s)?")){

			$("#loading").show();

			var Qry = 'cmd=buy_number&numbers='+encodeURIComponent(arr)+'&country='+country+'&ISOcountry='+ISOcountry;

			$.post('server.php',Qry,function(r){				

				window.location = 'settings.php';

			});

		}

	}

}

function removeFromInstall(numberSid,number){

	if(confirm("Are you sure you want to remove number from this install?")){

		$("#loading").show();

		var Qry = 'cmd=remove_from_install&phoneSid='+numberSid+'&number='+encodeURIComponent(number);

		$.post("server.php",Qry,function(res){

			if(res=="1"){

				$("#loading").html('<span style="color:green">Number released from your install.</span>');	

				window.location = 'settings.php';

			}

			else{

				$("#loading").html('<span style="color:red">'+res+'</span>');

			}

		});

	}}

function addToInstall(numberSid,number,country,isoCountry){

	if(confirm("Are you sure you want to add this number?")){

		$("#loading").show();

		var Qry = 'cmd=assign_to_install&phoneSid='+numberSid+'&phone_number='+encodeURIComponent(number)+'&country='+country+'&isoCountry='+isoCountry;

		$.post("server.php",Qry,function(res){

			if(res=="1"){

				$("#loading").html('<span style="color:green">Number assigned to your install.</span>');	

				window.location = 'settings.php';

			}

			else{

				$("#loading").html('<span style="color:red">'+res+'</span>');

			}

		});

	}

}



function searchNumbers(country){
	if(country=="US"){
		$("#showNumbers").html('');
		$("#usa_section").show();
	}else{
		$("#loading").show();
		$("#showNumbers").html('');
		$("#usa_section").hide();
		var Qry = 'cmd=get_numbers&country='+country;
		$.post("server.php",Qry,function(res){
			$("#showNumbers").html(res);
			$("#showNumbers").show();
			$("#loading").hide();
		});	
	}}

function getnumbers(obj){

	$("#loading").show();

	var state = $("#state").val();

	var areacode = $("#areacode").val();

	var country = $('#phone_type').val();

	var Qry = 'state='+state+'&areacode='+areacode+'&cmd=get_numbers&country='+country;

	$.post("server.php",Qry,function(res){

		$("#showNumbers").html(res);

		$("#showNumbers").show();

		$("#loading").hide();

	});

}

function getareacodes(obj){

	$("#loading").show();

	$("#showNumbers").html('');

	var state = obj.value;

	var Qry = 'state_code='+state+'&cmd=get_area_codes';

	$.post("server.php",Qry,function(res){

		$("#areacode").html(res);

		$("#loading").hide();

	});

}

function showSections(obj){
	if(obj.value=="1"){
		<?php 
			if($_SESSION['user_type']=='1'){
		?>
				$("#purchase_number").show('slow');
				$("#existing_number").hide('slow');
		<?php 
			}else{
				echo '$("#purchase_number").show("slow");';
				echo '$("#existing_number").hide("slow");';
				echo 'searchNumbers("'.$pkgInfo['iso_country'].'");';
				/*
				if($rec['pkg_country']=='US'){
					echo '$("#purchase_number").show("slow");';
					echo '$("#existing_number").hide("slow");';
				}
				if($rec['pkg_country']=='GB'){
					echo '$("#purchase_number").show("slow");';
					echo '$("#existing_number").hide("slow");';
					echo 'searchNumbers("GB");';
				}
				if($rec['pkg_country']=='AU'){
					echo '$("#purchase_number").show("slow");';
					echo '$("#existing_number").hide("slow");';
					echo 'searchNumbers("AU");';
				}
				if($rec['pkg_country']=='CA'){
					echo '$("#purchase_number").show("slow");';
					echo '$("#existing_number").hide("slow");';
					echo 'searchNumbers("CA");';
				}
				*/
			}
		?>
		$("#buy_credits_section").hide('slow');
	}
	else if(obj.value=="2"){
		$("#purchase_number").hide('slow');
		$("#existing_number").html('');
		$("#showNumbers").hide('slow');
		$("#existing_number").hide('slow');
		$("#buy_credits_section").show('slow');
	}else{

		$("#loading").show();

		var Qry = 'cmd=get_existing_numbers';

		$.post("server.php",Qry,function(res){

			$("#buy_credits_section").hide('slow');

			$("#purchase_number").hide('slow');

			$("#existing_number").html(res);

			$("#showNumbers").hide('slow');

			$("#existing_number").show('slow');

			$("#loading").hide();

		});

	}

}

function OnKeyPress(e){

	if(window.event){e=window.event;}

	if(e.keyCode==13){

		getNumberByAreaCode();

	}	

}

function getNumberByAreaCode(){

	$("#loading").show();

	$("#showNumbers").html('');

	var areaCode = $("#selected_areacode").val();

	var country = $('#phone_type').val();

	var Qry = 'areacode='+areaCode+'&country='+country+'&cmd=get_numbers_areacode';

	$.post("server.php",Qry,function(res){

		$("#showNumbers").html(res);

		$("#showNumbers").show();

		$("#loading").hide();

	});

}

function showSection(obj){

	if(obj.value=="state"){

		$("#showAraaCodeSection").hide();

		$("#showStateSection").show();

		$("#phone_number").html('');

		$("#showNumbers").hide();

	}

	else{

		$("#showStateSection").hide();

		$("#showAraaCodeSection").show();

		$("#phone_number").html('');

		$("#showNumbers").hide();

	}

}
</script>