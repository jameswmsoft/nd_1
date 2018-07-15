<?php
	include_once("header.php");
	include_once("left_menu.php");
	$timeArray   = getTimeArray();
	$timeOptions = '';
	foreach($timeArray as $key => $value){
		$timeOptions .= '<option value="'.$key.'">'.$value.'</option>';		
	}
	$options = '';
	for($i=1; $i<=23; $i++){
		if($i > 1)
			$hour = 'hours';
		else
			$hour = 'hour';
		$options .= '<option value="+'.$i.' '.$hour.'">After '.$i.' '.ucfirst($hour).'</option>';
	}
?>
<style>
.delay_table tr td{
	padding:5px !important;
} 
</style>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card" style="margin: 0px;padding-bottom: 10px;">
						<div class="header">
							<h4 class="title">
								Create Location
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location='view_campaigns.php'" />
							</h4>
							<p class="category">Here you can add a new location.</p>
						</div>
						<div class="content table-responsive">
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
							<div class="form-group">
								<label>Location Name*</label>
								<input type="text" name="title" parsley-trigger="change" required placeholder="Enter title..." class="form-control">
							</div>
							<div class="form-group">
								<label>Keyword*</label>
								<input type="text" name="keyword" parsley-trigger="change" required placeholder="Enter keyword..." class="form-control">
							</div>
							<div class="form-group">
								<label>Assign Number*</label>
								<select name="phone_number" class="form-control">
									<option value="">- Select One -</option>
								<?php
									if($appSettings['sms_gateway']=='twilio'){
										$sel = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='1'";
									}else if($appSettings['sms_gateway']=='plivo'){
										$sel = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='2'";
									}else if($appSettings['sms_gateway']=='nexmo'){
										$sel = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='3'";
									}else{
										$sel = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."'";
									}
									$rec = mysqli_query($link,$sel);
									if(mysqli_num_rows($rec)){
										while($numbers = mysqli_fetch_assoc($rec)){
											echo '<option '.$selected.' value="'.$numbers['phone_number'].'">'.$numbers['phone_number'].'</option>';
										}
									}
								?>
								</select>
							</div>
                                <!--
                            <div class="form-group">
                                <label>Sender ID*</label>
                                <input type="text" name="sender_id" maxlength="11" parsley-trigger="change" required placeholder="Enter sender_Id..." class="form-control">
                            </div>
                                -->
<!--							<div class="form-group">-->
<!--								<label><input name="attach_mobile_device" value="1" type="checkbox" /> Attach mobile device</label>-->
<!--							</div>-->
<!--							<div class="col-lg-12" style="padding:0px !important;">-->
<!--								<div class="portlet">-->
<!--									<div class="portlet-heading bg-custom" style="background-color:#9350e9 !important; padding:2px 5px 2px 10px !important; border-radius:5px;">-->
<!--										<h5 style="color:#FFF !important">-->
<!--											SMS/MMS-->
<!--											<a onclick="slideToggleMainSection(this,'sms_texts_section','');" href="javascript:;"><i class="fa fa-plus" title="Open" style="color:#FFF !important; float:right !important"></i></a>-->
<!--										</h5>-->
<!--										<div class="portlet-widgets">-->
<!--											<span class="divider"></span>-->
<!--											<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>-->
<!--										</div>-->
<!--										<div class="clearfix"></div>-->
<!--									</div>-->
<!--									<div class="panel-collapse collapse in sms_texts_section" style="display:none" aria-expanded="true">-->
<!--										<div class="portlet-body" style="padding:10px;">-->
<!--											<!---->
<!--											<div class="form-group">-->
<!--												<label><input type="checkbox" name="double_optin_check" onClick="slideToggleInnerSection(this,'smsTextsSection')" /> Enable Double Opt-in</label>-->
<!--											</div>-->
<!--											-->
<!--											<div class="form-group smsTextsSection">-->
<!--												<label>Welcome SMS*</label>-->
<!--												<textarea name="welcome_sms" parsley-trigger="change" required placeholder="Enter welcome sms text..." class="form-control textCounter"></textarea>-->
<!--												<span class="showCounter">-->
<!--													<span class="showCount">--><?php //echo @$maxLength?><!--</span> Characters left-->
<!--												</span>-->
<!--											</div>-->
<!--											<div class="form-group smsTextsSection">-->
<!--												<label>Already Member SMS*</label>-->
<!--												<textarea name="already_member_sms" parsley-trigger="change" required placeholder="Enter sms text for existing member..." class="form-control textCounter"></textarea>-->
<!--												<span class="showCounter">-->
<!--													<span class="showCount">--><?php //echo @$maxLength?><!--</span> Characters left-->
<!--												</span>-->
<!--											</div>-->
<!--											<div class="form-group smsTextsSection">-->
<!--												<label>Select Media</label>-->
<!--												<input type="file" name="campaign_media" style="display:inline !important" />-->
<!--											</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</div>-->
<!--							</div>-->
<!--							<div style="height:8px; clear:both"></div>-->
<!--							<div class="col-lg-12" style="padding:0px !important;">-->
<!--								<div class="portlet">-->
<!--									<div class="portlet-heading bg-custom" style="background-color:#9350e9 !important; padding:2px 5px 2px 10px !important; border-radius:5px;">-->
<!--										<h5 style="color:#FFF !important">-->
<!--											Make the campaign double opt-in-->
<!--											<a onclick="slideToggleMainSection(this,'double_optin_section','doubleOptInCheck');" href="javascript:;"><i class="fa fa-plus" title="Open" style="color:#FFF !important; float:right !important"></i></a>-->
<!--										</h5>-->
<!--										<div class="portlet-widgets">-->
<!--											<span class="divider"></span>-->
<!--											<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>-->
<!--										</div>-->
<!--										<div class="clearfix"></div>-->
<!--									</div>-->
<!--									<div class="panel-collapse collapse in double_optin_section" style="display:none" aria-expanded="true">-->
<!--										<div class="portlet-body" style="padding:10px;">-->
<!--											<div class="form-group">-->
<!--												<label><input type="checkbox" name="double_optin_check" onClick="slideToggleInnerSection(this,'doubleOptInSection')" /> Enable Double Opt-in</label>-->
<!--											</div>-->
<!--											<div class="form-group doubleOptInSection" style="display:none">-->
<!--												<label>Double Opt-in SMS</label>-->
<!--												<textarea name="double_optin" placeholder="Enter double opt-in text..." class="form-control textCounter"></textarea>-->
<!--												<span class="showCounter">-->
<!--													<span class="showCount">--><?php //echo @$maxLength?><!--</span> Characters left-->
<!--												</span>-->
<!--											</div>-->
<!--											<div class="form-group doubleOptInSection" style="display:none">-->
<!--												<label>Double Opt-in Confirm Message</label>-->
<!--												<textarea name="double_optin_confirm_message" placeholder="Enter double opt-in text..." class="form-control textCounter"></textarea>-->
<!--												<span class="showCounter">-->
<!--													<span class="showCount">--><?php //echo @$maxLength?><!--</span> Characters left-->
<!--												</span>-->
<!--											</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</div>-->
<!--							</div>-->
<!--							<div style="height:8px; clear:both"></div>-->
<!--							<div class="col-lg-12" style="padding:0px !important;">-->
<!--								<div class="portlet">-->
<!--									<div class="portlet-heading bg-custom" style="background-color:#9350e9 !important; padding:2px 5px 2px 10px !important; border-radius:5px;">-->
<!--										<h5 style="color:#FFF !important">-->
<!--											Get subscriber name/email-->
<!--											<a onclick="slideToggleMainSection(this,'get_email_section','get_email');" href="javascript:;"><i class="fa fa-plus" title="Open" style="color:#FFF !important; float:right !important"></i></a>-->
<!--										</h5>-->
<!--										<div class="portlet-widgets">-->
<!--											<span class="divider"></span>-->
<!--											<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>-->
<!--										</div>-->
<!--										<div class="clearfix"></div>-->
<!--									</div>-->
<!--									<div class="panel-collapse collapse in get_email_section" style="display:none" aria-expanded="true">-->
<!--										<div class="portlet-body" style="padding:10px;">-->
<!--											<div class="form-group">-->
<!--												<label class="checkbox-inline"><input type="checkbox" name="get_subs_email" value="1" onClick="slideToggleInnerSection(this,'subsEmailSection')" /> Get subscriber email</label>-->
<!--											</div>-->
<!--											<div class="form-group subsEmailSection" style="display:none">-->
<!--												<label>Message to get subscriber Email</label>-->
<!--												<textarea name="reply_email" parsley-trigger="change" placeholder="Enter sms to ask for email..." class="form-control textCounter"></textarea>-->
<!--												<span class="showCounter">-->
<!--													<span class="showCount">--><?php //echo @$maxLength?><!--</span> Characters left-->
<!--												</span>-->
<!--											</div>-->
<!--											<div class="form-group subsEmailSection" style="display:none">-->
<!--												<label>Email Received Confirmation Message</label>-->
<!--												<textarea name="email_updated" parsley-trigger="change" placeholder="Confirmation sms text for receiving email..." class="form-control textCounter"></textarea>-->
<!--												<span class="showCounter">-->
<!--													<span class="showCount">--><?php //echo @$maxLength?><!--</span> Characters left-->
<!--												</span>-->
<!--											</div>-->
<!--											-->
<!--											<div class="form-group">-->
<!--												<label class="checkbox-inline"><input type="checkbox" name="get_subs_name_check" onClick="slideToggleInnerSection(this,'subsNameSection')" value="1" /> Get subscriber name</label>-->
<!--											</div>-->
<!--											<div class="subsNameSection" style="display:none">-->
<!--												<div class="form-group">-->
<!--													<label>Message to get subscriber name</label>-->
<!--													<textarea name="msg_to_get_subscriber_name" parsley-trigger="change" placeholder="Message to get subscriber name..." class="form-control textCounter"></textarea>-->
<!--													<span class="showCounter">-->
<!--														<span class="showCount">--><?php //echo @$maxLength?><!--</span> Characters left-->
<!--													</span>-->
<!--												</div>-->
<!--												<div class="form-group">-->
<!--													<label>Name Received Confirmation Message</label>-->
<!--													<textarea name="name_received_confirmation_msg" parsley-trigger="change" placeholder="Name received confirmation message..." class="form-control textCounter"></textarea>-->
<!--													<span class="showCounter">-->
<!--														<span class="showCount">--><?php //echo @$maxLength?><!--</span> Characters left-->
<!--													</span>-->
<!--												</div>-->
<!--											</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</div>-->
<!--							</div>-->
<!--							<div style="height:8px; clear:both"></div>-->
<!--							<div class="col-lg-12" style="padding:0px !important;">-->
<!--								<div class="portlet">-->
<!--									<div class="portlet-heading bg-custom" style="background-color:#9350e9 !important; padding:2px 5px 2px 10px !important; border-radius:5px;">-->
<!--										<h5 style="color:#FFF !important">-->
<!--											Activate campaign for limited time-->
<!--											<a onclick="slideToggleMainSection(this,'campaign_expity_section','check_campaign_expiry');" href="javascript:;"><i class="fa fa-plus" title="Open" style="color:#FFF !important; float:right !important"></i></a>-->
<!--										</h5>-->
<!--										<div class="portlet-widgets">-->
<!--											<span class="divider"></span>-->
<!--											<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>-->
<!--										</div>-->
<!--										<div class="clearfix"></div>-->
<!--									</div>-->
<!--									<div class="panel-collapse collapse in campaign_expity_section" style="display:none" aria-expanded="true">-->
<!--										<div class="portlet-body" style="padding:10px;">-->
<!--											<div class="form-group">-->
<!--												<label><input type="checkbox" name="campaign_expiry_check" value="1" onClick="slideToggleInnerSection(this,'campaignExpirySection')" /> Enable/Disable</label>-->
<!--											</div>-->
<!--											<div class="campaignExpirySection" style="display:none">-->
<!--												<div class="col-md-6" style="padding-left:0px;">-->
<!--													<div class="form-group">-->
<!--													<label>Start Date</label>	-->
<!--													<input type="text" class="form-control addDatePicker" name="start_date" placeholder="Start date.">-->
<!--													</div>-->
<!--												</div>-->
<!--												<div class="col-md-6" style="padding-right:0px">-->
<!--													<div class="form-group">	-->
<!--														<label>End Date</label>-->
<!--														<input type="text" class="form-control addDatePicker" name="end_date" placeholder="End date.">-->
<!--													</div>-->
<!--												</div>-->
<!--												<div class="form-group">-->
<!--													<label>Expire Message</label>-->
<!--													<textarea name="expire_message" parsley-trigger="change" placeholder="Expire Message" class="form-control textCounter"></textarea>-->
<!--													<span class="showCounter">-->
<!--														<span class="showCount">--><?php //echo @$maxLength?><!--</span> Characters left-->
<!--													</span>-->
<!--												</div>-->
<!--											</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</div>-->
<!--							</div>-->
<!--							<div style="height:8px; clear:both"></div>-->
<!--							<div class="col-lg-12" style="padding:0px !important;">-->
<!--								<div class="portlet">-->
<!--									<div class="portlet-heading bg-custom" style="background-color:#9350e9 !important; padding:2px 5px 2px 10px !important; border-radius:5px;">-->
<!--										<h5 style="color:#FFF !important">-->
<!--											Add Delay Messages for this campaign.-->
<!--											<a onclick="slideToggleMainSection(this,'follow_up_msg_section','');" href="javascript:;"><i class="fa fa-plus" title="Add More" style="color:#FFF !important; float:right !important"></i></a>-->
<!--										</h5>-->
<!--										<div class="portlet-widgets">-->
<!--											<span class="divider"></span>-->
<!--											<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>-->
<!--										</div>-->
<!--										<div class="clearfix"></div>-->
<!--									</div>-->
<!--									<div class="panel-collapse collapse in follow_up_msg_section" id="bg-primary" style="display:none" aria-expanded="true">-->
<!--										<div class="form-group" style="padding:10px">-->
<!--											<label><input type="checkbox" name="followup_msg_check" onClick="slideToggleInnerSection(this,'followUpContainer')" value="1" /> Enable/Disable</label>-->
<!--										</div>-->
<!--										<div class="portlet-body followUpContainer" id="followUpContainer" style="padding:10px; display:none">-->
<!--											<div>-->
<!--												<table width="100%" class="delay_table">-->
<!--												<tr>-->
<!--													<td width="25%">Select Days/Time</td>-->
<!--													<td>-->
<!--														<input type="text" class="form-control numericOnly" style="width:auto !important; display:inline !important" placeholder="Days delay..." name="delay_day[]" value="0" onblur="switchTimeDropDown(this)">&nbsp;-->
<!--														<select class="form-control timeDropDown" style="width:48% !important; display:none" name="delay_time[]">-->
<!--															--><?php
//															$timeArray = getTimeArray();
//															foreach($timeArray as $key => $value){
//																echo '<option value="'.$key.'">'.$value.'</option>';
//
//															}
//															?>
<!--														</select>-->
<!--														<select class="form-control hoursDropDown" style="width:48% !important; display:inline" name="delay_time_hours[]">-->
<!--															--><?php
//																echo $options;
//															?>
<!--														</select>-->
<!--														<span style="cursor:pointer; margin-left:30px;" onClick="addMoreFollowUpMsg()"><i class="fa fa-plus" title="Add More" style="color:green !important; font-size:35px; vertical-align:middle"></i></span>-->
<!--													</td>-->
<!--												</tr>-->
<!--												<tr>-->
<!--													<td>Message</td>-->
<!--													<td>-->
<!--														<textarea name="delay_message[]" class="form-control textCounter"></textarea>-->
<!--														<span class="showCounter">-->
<!--															<span class="showCount">--><?php //echo @$maxLength?><!--</span> Characters left-->
<!--														</span>-->
<!--													</td>-->
<!--												</tr>-->
<!--												<tr>-->
<!--													<td>Attach Media</td>-->
<!--													<td>-->
<!--														<input type="file" name="delay_media[]">-->
<!--													</td>-->
<!--												</tr>-->
<!--											</table>-->
<!--											</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</div>-->
<!--							</div>-->
<!--                            <div style="height:8px; clear:both"></div>-->
<!--							<div class="col-lg-12" style="padding:0px !important;">-->
<!--								--><?php
//									if(@$row['campaign_beacon_check']=='1'){
//										$campBeaconIcon = 'fa-minus';
//										$campaignBeaconCheck = 'checked=checked';
//										$campaignBeaconSection = '';
//										$campaignBeaconInnerSection = '';
//									}else{
//										$campBeaconIcon = 'fa-plus';
//										$campaignBeaconCheck = '';
//										$campaignBeaconSection = 'none';
//										$campaignBeaconInnerSection = 'none';
//									}
//
//
//                                    if(@$row['beacon_url_type']=='1'){
//                                        $campaignBeaconURLSection = 'none';
//                                        $campaignBeaconCouponSection = '';
//                                        $campaignBeaconCouponCheck = 'checked';
//                                        $campaignBeaconURLCheck = '';
//                                    }else{
//                                        $campaignBeaconURLSection = '';
//                                        $campaignBeaconCouponSection = 'none';
//                                        $campaignBeaconCouponCheck = '';
//                                        $campaignBeaconURLCheck = 'checked';
//                                    }
//
//
//
//								?>
<!--								<div class="portlet">-->
<!--									<div class="portlet-heading bg-custom" style="background-color:#9350e9 !important; padding:2px 5px 2px 10px !important; border-radius:5px;">-->
<!--										<h5 style="color:#FFF !important">-->
<!--											Activate beacon-->
<!--											<a onclick="slideToggleMainSection(this,'campaign_beacon_section','check_campaign_beacon');" href="javascript:;"><i class="fa --><?php //echo $campBeaconIcon?><!--" title="Open" style="color:#FFF !important; float:right !important"></i></a>-->
<!--										</h5>-->
<!--										<div class="portlet-widgets">-->
<!--											<span class="divider"></span>-->
<!--											<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>-->
<!--										</div>-->
<!--										<div class="clearfix"></div>-->
<!--									</div>-->
<!--									<div class="panel-collapse collapse in campaign_beacon_section" style="display:--><?php //echo $campaignBeaconSection?><!--" aria-expanded="true">-->
<!--										<div class="portlet-body" style="padding:10px;">-->
<!---->
<!---->
<!--                                            --><?php
//                                            $sql_as = "select * from application_settings where user_id='".$_SESSION['user_id']."'";
//                                        	$res_as = mysqli_query($link,$sql_as);
//                                        	$row_as = mysqli_fetch_assoc($res_as);
//                                            $AppID = $row_as['estimote_app_id'];
//                                            $AppToken = $row_as['estimote_app_token'];
//
//                                            if($AppID=="" || $AppToken==""){
//                                                echo "<span style='color:red;'><br /> Please add Estimote API credentials in settings tab to use beacon </span>";
//                                            }
//                                            else
//                                            {
//                                            ?>
<!---->
<!--                                            <div class="form-group">-->
<!--												<label><input type="checkbox" name="campaign_beacon_check" --><?php //echo $campaignBeaconCheck?><!-- onClick="slideToggleInnerSection(this,'campaignBeaconSection')" value="1" /> Enable/Disable</label>-->
<!--											</div>-->
<!--											<div class="campaignBeaconSection" style="display:--><?php //echo $campaignBeaconInnerSection?><!--">-->
<!---->
<!--                                                --><?php
//                                                $url = "https://$AppID:$AppToken@cloud.estimote.com/v2/devices";
//                                                $res = curl_process($url);
//                                                $beacons = json_decode($res,true);
//
//                                                if(isset($beacons['error'])){
//                                                    echo "<span style='color:red;'><br />".$beacons['error']."</span>";
//                                                }
//                                                ?>
<!--                                                <div class="col-md-12" style="padding-left:0px;">-->
<!--													<div class="form-group">-->
<!--													<label>Available Beacons</label>-->
<!--													<select class="form-control" name="beacon">-->
<!--                                                        <option value="">Choose Beacon</option>-->
<!--                                                        --><?php
//                                                        if(is_array($beacons) && count($beacons)>0 && !isset($beacons['error'])){
//                                                            foreach($beacons as $beacon){
//                                                                ?>
<!--                                                                <option --><?php //if($row['beacon']==$beacon['identifier']){ echo "selected"; } ?><!-- value="--><?php //echo $beacon['identifier']; ?><!--">--><?php //echo $beacon['shadow']['name']; ?><!--</option>-->
<!--                                                                --><?php
//                                                            }
//                                                        }
//                                                        ?>
<!--                                                    </select>-->
<!--													</div>-->
<!--												</div>-->
<!---->
<!---->
<!--                                                <div class="form-group">-->
<!--    												<label><input type="radio" name="beacon_url_type" --><?php //echo $campaignBeaconCouponCheck; ?><!-- onClick="slideToggleBeaconSection(this,'campaignBeaconCouponSection')" value="1" /> Coupon Landing Page</label>-->
<!--                                                    <label><input type="radio" name="beacon_url_type" --><?php //echo $campaignBeaconURLCheck; ?><!-- onClick="slideToggleBeaconSection(this,'campaignBeaconURLSection')" value="2" /> Custom URL</label>-->
<!--    											</div>-->
<!---->
<!---->
<!--                                                <div class="campaignBeaconCouponSection" style="display:--><?php //echo $campaignBeaconCouponSection?><!--">-->
<!--    												<div class="col-md-12" style="padding-left:0px;">-->
<!--    													<div class="form-group">-->
<!--    													<label>Coupons Page</label>-->
<!--    													<select class="form-control" name="coupon">-->
<!--                                                            <option value="">Choose Coupon</option>-->
<!--                                                            --><?php
//                                                            $sql_pages = "select * from pages where created_user='".$_SESSION['user_id']."'";
//                                                        	$res_pages = mysqli_query($link,$sql_pages);
//                                                        	while($row_pages = mysqli_fetch_assoc($res_pages)){
//                                                            ?>
<!--                                                            <option value="--><?php //echo $row_pages['id']; ?><!--">--><?php //echo $row_pages['page_title']; ?><!--</option>-->
<!--                                                            --><?php
//                                                            }
//                                                            ?>
<!--                                                        </select>-->
<!--    													</div>-->
<!--    												</div>-->
<!--                                                </div>-->
<!---->
<!--                                                <div class="campaignBeaconURLSection" style="display:--><?php //echo $campaignBeaconURLSection?><!--">-->
<!--    												<div class="col-md-12" style="padding-left:0px;">-->
<!--    													<div class="form-group">-->
<!--        													<label>Custom URL</label>-->
<!--        													<input type="text" class="form-control" name="custom_url" value="--><?php //echo @$row['custom_url'];?><!--">-->
<!--    													</div>-->
<!--    												</div>-->
<!--                                                </div>-->
<!---->
<!---->
<!---->
<!--											</div>-->
<!---->
<!---->
<!--                                            --><?php
//                                            }
//                                            ?>
<!---->
<!---->
<!--										</div>-->
<!--									</div>-->
<!--								</div>-->
							</div>
							<div style="height:8px; clear:both"></div>
							<div class="form-group text-right m-b-0" style="padding-right: 15px;">
								<button class="btn btn-primary waves-effect waves-light" type="submit"> Save </button>
								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
								<input type="hidden" name="cmd" value="create_campaign" />
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
<!--<script type="text/javascript">-->
<!--	function switchTimeDropDown(obj){-->
<!--		if($(obj).val()=='0'){-->
<!--			$(obj).parent().find('.timeDropDown').css('display','none');-->
<!--			$(obj).parent().find('.hoursDropDown').css('display','inline');-->
<!--		}else{-->
<!--			$(obj).parent().find('.timeDropDown').css('display','inline');-->
<!--			$(obj).parent().find('.hoursDropDown').css('display','none');-->
<!--		}-->
<!--	}-->
<!--	function slideToggleInnerSection(obj,eleMent){-->
<!--		if($(obj).is(":checked")==true){-->
<!--			$('.'+eleMent+'').show('slow');-->
<!--		}else{-->
<!--			$('.'+eleMent+'').hide('slow');-->
<!--		}-->
<!--	}-->
<!---->
<!--	function slideToggleMainSection(obj,section,chkBox){-->
<!--		var html = $(obj).html();-->
<!--		var check = html.indexOf("fa-plus");-->
<!--		if(check=="-1"){-->
<!--			$(obj).html('<i class="fa fa-plus" title="Close" style="color:#FFF !important; float:right !important"></i>');-->
<!--			$('.'+section).hide('slow');-->
<!--			//if(chkBox!=''){-->
<!--				//$('.'+chkBox+'').prop("checked",false);-->
<!--			//}-->
<!--		}else{-->
<!--			$(obj).html('<i class="fa fa-minus" title="Open" style="color:#FFF !important; float:right !important"></i>');-->
<!--			$('.'+section).show('slow');-->
<!--			//if(chkBox!=''){-->
<!--				//$('.'+chkBox+'').prop("checked",true);-->
<!--			//}-->
<!--		}-->
<!--	}-->
<!--    -->
<!--    function slideToggleBeaconSection(obj,eleMent){-->
<!--		if(eleMent=='campaignBeaconCouponSection'){-->
<!--			$('.campaignBeaconCouponSection').show('slow');-->
<!--            $('.campaignBeaconURLSection').hide('slow');-->
<!--		}else{-->
<!--			$('.campaignBeaconCouponSection').hide('slow');-->
<!--            $('.campaignBeaconURLSection').show('slow');-->
<!--		}-->
<!--	}-->
<!--    -->
<!--	function removeFollowUp(obj){-->
<!--		if(confirm("Are you sure you want to remove this follow up?")){-->
<!--			obj.closest('.delay_table').remove('slow');-->
<!--		}-->
<!--	}-->
<!--<!--	function followUpHtml(){-->-->
<!--<!--		var timeOption = '-->--><?php ////echo $timeOptions?><!--//';-->
<!--//		var html = '<table width="100%" class="delay_table">';-->
<!--//		html += '<tr><td colspan="2"><hr style="background-color: #7e57c2 !important;height:1px;margin: 15px;"></td></tr>';-->
<!--//		html += '<tr><td width="25%">Select Days/Time</td><td><input type="text" class="form-control numericOnly" style="width:auto !important; display:inline !important" placeholder="Days delay..." name="delay_day[]" value="0" onblur="switchTimeDropDown(this)">&nbsp;<select class="form-control timeDropDown" style="width:48% !important; display:none" name="delay_time[]">'+timeOption+'</select><select class="form-control hoursDropDown" style="width:48% !important; display:inline" name="delay_time_hours[]">--><?php ////echo $options?><!--//</select></td></tr>';-->
<!--//		html += '<tr><td>Message</td><td><textarea name="delay_message[]" class="form-control textCounter"></textarea><span class="showCounter"><span class="showCount">--><?php ////echo @$maxLength; ?><!--//</span> Characters left</span></td></tr>';-->
<!--//		html += '<tr><td>Attach Media</td><td><input type="file" name="delay_media[]" style="display:inline !important"><span class="fa fa-trash" style="color:red;float:right;margin:10px;cursor:pointer" title="Remove Message" onclick="removeFollowUp(this)"></span></td></tr></table>';-->
<!--//		return html;-->
<!--//	}-->
<!--//	function addMoreFollowUpMsg(){-->
<!--//		var html = followUpHtml();-->
<!--//		$('#followUpContainer').append('<div>'+html+'</div>');-->
<!--//		$('.showCounter').hide();-->
<!--//	}-->
<!--//</script>-->