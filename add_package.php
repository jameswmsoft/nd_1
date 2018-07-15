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
								Create New Plan
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location='view_package.php'" />
							</h4>
							<p class="category">Create your attractive sms package plans here.</p>
						</div>
						<div class="content table-responsive">
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post" onsubmit="getPkgCountry()">
							<div class="form-group">
								<label>Title*</label>
								<input type="text" name="title" parsley-trigger="change" required placeholder="Enter title..." class="form-control">
							</div>
                            <div class="form-group">
                                <label>Locations*</label>
                                <input type="number" name="locations" parsley-trigger="change" required placeholder="Enter title..." class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Reminders*</label>
                                <input type="number" name="reminders" parsley-trigger="change" required placeholder="Enter title..." class="form-control">
                            </div>
							<div class="form-group">
								<label>SMS Credits*</label>
								<input type="text" name="sms_credits" parsley-trigger="change" required placeholder="Enter sms credits..." class="form-control">
							</div>
							<div class="form-group">
								<label>Phone Number Limit*</label>
								<input type="text" name="phone_number_limit" parsley-trigger="change" required placeholder="Enter phone number limit..." class="form-control">
							</div>
							<div class="form-group">
								<label>SMS Gateway*</label><span class="loading"></span>
								<p class="category" style="color:#F00">Package will use this sms gateway to send messages.</p>
								<select name="sms_gateway" class="form-control" parsley-trigger="change" required onchange="getGatewayCountries(this.value)">
									<option value="">- Select One -</option>
									<option value="twilio">Twilio</option>
									<option value="plivo">Plivo</option>
									<option value="nexmo">Nexmo</option>
								</select>
							</div>
							<div class="form-group">
								<label>Country*</label>
								<select name="country" id="country" class="form-control" parsley-trigger="change" required>
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
							</div>
							<div class="form-group">
								<label>Price*</label>
								<input type="text" name="price" parsley-trigger="change" required placeholder="Enter package price..." class="form-control numericOnly">
							</div>
							<div class="form-group">
								<label class="checkbox-inline">
									<input type="checkbox" name="is_free_days" value="1" class="is_free_days" onclick="showFreeDays(this)" /> Free Days
								</label>
								<input type="text" name="free_days" id="free_days" class="form-control" style="display:none" />
							</div>
							<div class="form-group text-right m-b-0">
								<input type="hidden" name="pkg_country" id="pkg_country" value="" />
								<input type="hidden" name="cmd" value="save_pkg" />
								<button class="btn btn-primary waves-effect waves-light" type="submit"> Save </button>
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
<script type="text/javascript" src="scripts/js/parsley.min.js"></script>
<script type="text/javascript">
	function getGatewayCountries(gateway){
	   
       return false;
       // By MQS, For Nexmo and Plivo you have hardcoded only USA as country that is why we are showing all countries
       
		$('#country').html('');
		$.post('server.php',{"cmd":"get_gateway_countries",gateway:gateway},function(r){
			$('#country').html(r);
		});
	}
	function showFreeDays(obj){
		if($(obj).is(":checked")==true){
			$('#free_days').show('slow');
		}else{
			$('#free_days').hide('slow');
		}
	}
	function getPkgCountry(){
		var country = $('#country option:selected').text();
		$('#pkg_country').val(country);
	}
	$(document).ready(function(){
		$('form').parsley();
	});
</script>