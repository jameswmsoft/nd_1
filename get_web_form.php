<?php
	header("Access-Control-Allow-Origin: *");
	include_once("database.php");
	include_once("functions.php");
	$id = $_REQUEST['id'];
	$sql = "select * from webforms where id='".$id."'";
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){
		$row = mysqli_fetch_assoc($res);
		$invalidChars = array('\'','"','@',',','-','_','!','`','~','#','%','&','*','(',')','+','=','/','{','}','[',']',':',';','?','.');
	}else{
		die('Webform already deleted.');
	}
?>
<link href="<?php echo getServerUrl()?>/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<style>
.nmBackground{
	padding-top:0px !important;	
}
.modal-content{
	min-height:425px;
	overflow:auto;
}
<?php
	if($row['showing_method']=='2'){
?>
.modal-dialog{
	width:100%
}
<?php }?>
</style>
<div class="modal-dialog">
	<?php if($row['webform_type']=='2'){?>
			<div class="modal-content" style="background-color:#<?php echo $row['frame_bg_color']?> !important; width:<?php echo $row['frame_width']?>px; margin:0 auto !important">
				<div class="modal-header">
					<button type="button" data-dismiss="modal" class="close">&times;</button>
					<h4 class="modal-title" style="color:<?php echo $row['color_for_label']?> !important"><?php echo $row['webform_name']?></h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label style="color:#<?php echo $row['color_for_label']?> !important"><?php echo $row['label_for_name_field']?></label>
						<input type="text" class="form-control" style="width:<?php echo $row['field_width']?>px; height:<?php echo $row['field_height']?>px;" name="subs_name" required>
					</div>
					<div class="form-group">
						<label style="color:#<?php echo $row['color_for_label']?> !important"><?php echo $row['label_for_phone_field']?></label>
						<input type="text" class="form-control" style="width:<?php echo $row['field_width']?>px; height:<?php echo $row['field_height']?>px;" name="subs_phone" required>
					</div>
					<div class="form-group">
						<label style="color:#<?php echo $row['color_for_label']?> !important"><?php echo $row['label_for_email_field']?></label>
						<input type="email" class="form-control" style="width:<?php echo $row['field_width']?>px; height:<?php echo $row['field_height']?>px;" name="subs_email" required>
					</div>
					<?php
						$customFields = json_decode($row['custom_fields'],true);
						if(count($customFields)>0){
					?>
					<div class="panel panel-default">
						<div class="panel-heading"><?php echo DBout($row['heading_for_custom_info_panel'])?></div>
						<div class="panel-body">
							<?php 
								for($i=0; $i<count($customFields); $i++){
									$fieldType = $customFields[$i]['field_type'];
									$fieldLable = $customFields[$i]['field_label'];
									$filedOptions = $customFields[$i]['filed_options'];
									if($customFields[$i]['is_required']=='true')
										$isRequired = 'required';
									else
										$isRequired = '';
									$eleName = str_replace($invalidChars,'',$fieldLable);
									$eleName = str_replace(' ','_',$eleName);
									$eleName = strtolower($eleName);
									
									if($fieldType=='text'){
										echo '<div class="form-group">';
										echo '<label>'.$fieldLable.'</label>';
										echo '<input type="text" name="'.$eleName.'" class="form-control" '.$isRequired.'>';
										echo '</div>';
									}else if($fieldType=='textarea'){
										echo '<div class="form-group">';
										echo '<label>'.$fieldLable.'</label>';
										echo '<textarea name="'.$eleName.'" class="form-control" '.$isRequired.'></textarea>';
										echo '</div>';
									}else if($fieldType=='dropdown'){
										echo '<div class="form-group">';
										echo '<label>'.$fieldLable.'</label>';
										echo '<select name="'.$eleName.'" class="form-control" '.$isRequired.'>';
										$options = @explode(',',trim($filedOptions,','));
										for($j=0; $j<count($options); $j++){
											echo '<option value="'.$options[$j].'">'.$options[$j].'</option>';
										}
										echo  '</select>';
										echo '</div>';
									}else if($fieldType=='checkbox'){
										echo '<div class="form-group">';
										echo '<label>'.$fieldLable.'</label><br>';
										$options = @explode(',',trim($filedOptions,','));
										for($j=0; $j<count($options); $j++){
											echo '<label class="checkbox-inline"><input type="checkbox" '.$isRequired.' name="'.$eleName.'"> '.$options[$j].'</label>';
										}
										echo '</div>';
									}else if($fieldType=='radio'){
										echo '<div class="form-group">';
										echo '<label>'.$fieldLable.'</label><br>';
										$options = @explode(',',trim($filedOptions,','));
										for($j=0; $j<count($options); $j++){
											echo '<label class="radio-inline"><input type="radio" '.$isRequired.' name="'.$eleName.'"> '.$options[$j].'</label>';
										}
										echo '</div>';
									}
								}
							?>
						</div>
					</div>
					<?php
						}
					?>
					<div class="form-group">
						<label style="color:#<?php echo $row['color_for_label']?> !important"><?php echo DBout($row['label_for_disclaimer_text'])?></label><br>
						<p style="color:#<?php echo $row['color_for_label']?> !important"><?php echo $row['disclaimer_text']?></p>
					</div>
				</div>
				<div class="modal-footer">
					<img src="<?php echo getServerUrl().'/images/busy.gif'?>" style="display:none" class="nmLoading">
					<span class="nmStatus"></span>
					<input type="button" class="btn btn-primary" style="color:#<?php echo $row['color_for_label']?> !important; background-color:#<?php echo $row['subs_btn_bg_color']?> !important; border-color:#<?php echo $row['subs_btn_bg_color']?> !important" value="Subscribe" onClick="saveWebFormUser('<?php echo $row['id']?>','<?php echo $row['campaign_id']?>','<?php echo $row['user_id']?>')">
					<button type="button" class="btn btn-default closePop" style="color:#<?php echo $row['color_for_label']?> !important; background-color:#<?php echo $row['close_btn_bg_color']?> !important; border-color:#<?php echo $row['subs_btn_bg_color']?> !important">Close</button>
				</div>
			</div>
	<?php }else{?>
			<div class="modal-content">
				<div class="modal-header">
				<button type="button" data-dismiss="modal" class="close">&times;</button>
				<h4 class="modal-title"><?php echo $row['webform_name']?></h4>
			</div>
				<div class="modal-body">
				<div class="form-group">
					<label><?php echo $row['label_for_name_field']?></label>
					<input type="text" class="form-control" name="subs_name" required>
				</div>
				<div class="form-group">
					<label><?php echo $row['label_for_phone_field']?></label>
					<input type="text" class="form-control" name="subs_phone" required>
				</div>
				<div class="form-group">
					<label><?php echo $row['label_for_email_field']?></label>
					<input type="email" class="form-control" name="subs_email" required>
				</div>
				<?php
					$customFields = json_decode($row['custom_fields'],true);
					if(count($customFields)>0){
				?>
				<div class="panel panel-default">
					<div class="panel-heading"><?php echo DBout($row['heading_for_custom_info_panel'])?></div>
					<div class="panel-body customSubsInfo">
						<?php 
							for($i=0; $i<count($customFields); $i++){
								$fieldType = $customFields[$i]['field_type'];
								$fieldLable = $customFields[$i]['field_label'];
								$filedOptions = $customFields[$i]['filed_options'];
								if($customFields[$i]['is_required']=='true')
									$isRequired = 'required';
								else
									$isRequired = '';
								$eleName = str_replace($invalidChars,'',$fieldLable);
								$eleName = str_replace(' ','_',$eleName);
								$eleName = strtolower($eleName);
								
								if($fieldType=='text'){
									echo '<div class="form-group">';
									echo '<input type="hidden" id="fieldType" value="'.$fieldType.'">';
									echo '<input type="hidden" id="fieldTitle" value="'.$fieldLable.'">';
									echo '<label>'.$fieldLable.'</label>';
									echo '<input type="text" class="form-control" '.$isRequired.'>';
									echo '</div>';
								}else if($fieldType=='textarea'){
									echo '<div class="form-group">';
									echo '<input type="hidden" id="fieldType" value="'.$fieldType.'">';
									echo '<input type="hidden" id="fieldTitle" value="'.$fieldLable.'">';
									echo '<label>'.$fieldLable.'</label>';
									echo '<textarea class="form-control" '.$isRequired.'></textarea>';
									echo '</div>';
								}else if($fieldType=='dropdown'){
									echo '<div class="form-group">';
									echo '<input type="hidden" id="fieldType" value="'.$fieldType.'">';
									echo '<input type="hidden" id="fieldTitle" value="'.$fieldLable.'">';
									echo '<label>'.$fieldLable.'</label>';
									echo '<select name="'.$eleName.'" class="form-control" '.$isRequired.'>';
									$options = @explode(',',trim($filedOptions,','));
									for($j=0; $j<count($options); $j++){
										echo '<option value="'.$options[$j].'">'.$options[$j].'</option>';
									}
									echo  '</select>';
									echo '</div>';
								}else if($fieldType=='checkbox'){
									echo '<div class="form-group">';
									echo '<input type="hidden" id="fieldType" value="'.$fieldType.'">';
									echo '<input type="hidden" id="fieldTitle" value="'.$fieldLable.'">';
									echo '<label>'.$fieldLable.'</label><br>';
									$options = @explode(',',trim($filedOptions,','));
									for($j=0; $j<count($options); $j++){
										echo '<label class="checkbox-inline"><input type="checkbox" '.$isRequired.' name="'.$eleName.'" value="'.$options[$j].'"> '.$options[$j].'</label>';
									}
									echo '</div>';
								}else if($fieldType=='radio'){
									echo '<div class="form-group">';
									echo '<input type="hidden" id="fieldType" value="'.$fieldType.'">';
									echo '<input type="hidden" id="fieldTitle" value="'.$fieldLable.'">';
									echo '<label>'.$fieldLable.'</label><br>';
									$options = @explode(',',trim($filedOptions,','));
									for($j=0; $j<count($options); $j++){
										echo '<label class="radio-inline"><input type="radio" '.$isRequired.' name="'.$eleName.'" value="'.$options[$j].'"> '.$options[$j].'</label>';
									}
									echo '</div>';
								}
							}
						?>
					</div>
				</div>
				<?php
					}
				?>
				<div class="form-group">
					<label><?php echo DBout($row['label_for_disclaimer_text'])?></label><br>
					<p style="font-size:11px"><i><?php echo $row['disclaimer_text']?></i></p>
				</div>
			</div>
				<div class="modal-footer">
					<img src="<?php echo getServerUrl().'/images/busy.gif'?>" style="display:none" class="nmLoading">
					<span class="nmStatus"></span>
					<input type="button" class="btn btn-primary" value="Subscribe" onClick="saveWebFormUser('<?php echo $row['id']?>','<?php echo $row['campaign_id']?>','<?php echo $row['user_id']?>')" />
					<button type="button" class="btn btn-default closePop">Close</button>
				</div>
			</div>
	<?php }?>
</div>
<script>
function getUserCustomInfo(){
	var obj = [];
	var fieldOptions = '';
	$('.customSubsInfo > .form-group').each(function(index){
		var fieldLabel = $(this).find('#fieldTitle').val();
		var fieldType  = $(this).find('#fieldType').val();
		if(fieldType == 'text'){
			var fieldValue = $(this).find('input[type="text"]').val();
			obj[index] = {
				'field_label' : fieldLabel,
				'field_type'  : fieldType,
				'field_value' : fieldValue
			};
		}else if(fieldType == 'textarea'){
			var fieldValue = $(this).find('textarea').val();
			obj[index] = {
				'field_label' : fieldLabel,
				'field_type'  : fieldType,
				'field_value' : fieldValue
			};
		}else if(fieldType=='dropdown'){
			var selectedOption = $(this).find('select option:selected').text();
			obj[index] = {
				'field_label' : fieldLabel,
				'field_type'  : fieldType,
				'field_value' : selectedOption
			};
		}else if(fieldType=='radio'){
			var selectedOption = $(this).find('input[type=radio]:checked').val();
			obj[index] = {
				'field_label' : fieldLabel,
				'field_type'  : fieldType,
				'field_value' : selectedOption
			};
		}else if(fieldType=='checkbox'){
			$(this).find('input[type=checkbox]:checked').each(function(i){
				fieldOptions += $(this).val()+',';
			});
			obj[index] = {
				'field_label' : fieldLabel,
				'field_type'  : fieldType,
				'field_value' : fieldOptions
			};
		}else{
			obj[index]['field_value'] = '';
		}
		fieldOptions = '';
	});
	var jsonString = JSON.stringify(obj);
	return jsonString;
}
function saveWebFormUser(webFormID,campaignID,userID){
	var name  = $('input[name="subs_name"]').val();
	var phone = $('input[name="subs_phone"]').val();
	var email = $('input[name="subs_email"]').val();
	if(($.trim(name)=="") || ($.trim(phone)=="") || ($.trim(email)=="")){
		alert('All fields are required.');
		return false;
	}
	if(!isValidEmailAddress(email)){
		alert('Enter valid email address.');
		return false;
	}
	var customInfo = getUserCustomInfo();
	$('.nmLoading').show();
	$('.nmStatus').html('');
	var Qry = 'cmd=save_webform_subscriber&name='+name+'&phone='+encodeURIComponent(phone)+'&email='+email+'&campaign_id='+campaignID+'&user_id='+userID+'&customSubsInfo='+customInfo;
	$.post('<?php echo getServerUrl().'/server.php'?>',Qry,function(r){
		$('.nmLoading').hide();
		if(r=='success'){
			$('.nmStatus').html('<span style="color:green">Success.</span>');
			window.setTimeout(hideAll,2000);
		}
		else if(r=='exists'){
			$('.nmStatus').html('<span style="color:red">Phone number is already subscribed.</span>');
			window.setTimeout(hideAll,2000);
		}
		else{
			$('.nmStatus').html('<span style="color:red">Unknown Error! Attempt failed.</span>');
			window.setTimeout(hideAll,2000);
		}
	});
}
function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
};
function hideAll(){
	$('.nmLoading').hide();
	$(".nmBackground").remove();
	$("#nmModalData").remove();		
}
$(".close, .closePop").on('click',function(r){
	$(".nmBackground").remove();
	$("#nmModalData").remove();
});
</script>