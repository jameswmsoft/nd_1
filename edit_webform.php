<?php
	include_once("header.php");
	include_once("left_menu.php");
	$id = $_REQUEST['id'];
	$sel= "select * from webforms where id='".$id."'";
	$exe= mysqli_query($link,$sel);
	if(mysqli_num_rows($exe))
		$wb = mysqli_fetch_assoc($exe);
	else
		$wb = array();
?>
<link rel="stylesheet" href="css/pick-a-color-1.2.3.min.css" />
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								Edit WebForm
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location='view_webform.php'" />
							</h4>
							<p class="category">Edit webform here.</p>
						</div>
						<div class="content table-responsive">
							<form method="post" enctype="multipart/form-data" action="server.php">
		<div class="form-group">
			<label>WebForm Name</label>
			<input type="text" name="webform_name" class="form-control" value="<?php echo $wb['webform_name']?>" required>
		</div>
		<div class="form-group">
			<label>Select Campaign</label>
			<select name="campaign_id" class="form-control">
				<?php
					$sql = "select id,title from campaigns";
					$res = mysqli_query($link,$sql);
					if(mysqli_num_rows($res)){
						while($row = mysqli_fetch_assoc($res)){
							if($wb['campaign_id']==$row['id'])
								$sele = 'selected="selected"';
							else
								$sele = '';
							echo '<option '.$sele.' value="'.$row['id'].'">'.$row['title'].'</option>';
						}
					}
					else{
						echo '<option value="">No campaign added yet.</option>';
					}
				?>
			</select>
		</div>
		<div class="form-group">
			<label>Label for Name Field</label>
			<input type="text" name="label_for_name_field" class="form-control" value="<?php echo $wb['label_for_name_field']?>" required>
		</div>
		<div class="form-group">
			<label>Label for Phone Field</label>
			<input type="text" name="label_for_phone_field" class="form-control" value="<?php echo $wb['label_for_phone_field']?>" required>
		</div>
		<div class="form-group">
			<label>Label for Email Field</label>
			<input type="text" name="label_for_email_field" class="form-control" value="<?php echo $wb['label_for_email_field']?>" required>
		</div>
		<div class="form-group">
			<label>Label for Disclaimer Text Field</label>
			<input type="text" name="label_for_disclaimer_text" value="<?php echo DBout($wb['label_for_disclaimer_text'])?>" class="form-control">
		</div>
		<div class="form-group">
			<label>Heading for Custom Information Panel</label>
			<input type="text" name="heading_for_custom_info_panel" value="<?php echo DBout($wb['heading_for_custom_info_panel'])?>" class="form-control">
		</div>
		<div id="fieldSectionContainer">
			<?php
			$customFields = json_decode($wb['custom_fields'],true);
			if(count($customFields)>0){	
				for($i=0; $i<count($customFields); $i++){
					$fieldType = $customFields[$i]['field_type'];
					$fieldLable = $customFields[$i]['field_label'];
					$filedOptions = $customFields[$i]['filed_options'];
					if($customFields[$i]['is_required']=='true')
						$isRequired = 'required';
					else
						$isRequired = '';
					
					if($fieldType=='text'){
					?>
						<div class="fieldSection">
							<div class="col-md-12" style="padding:0px">
								<div class="col-md-4" style="padding-left:0px">
								<input type="text" id="field_label" value="<?php echo $fieldLable?>" class="form-control" placeholder="Enter field label">
								</div>
								
								<div class="col-md-4" style="padding-left:0px">
								<select id="field_type" class="form-control" onchange="checkFieldType(this)">
									<option value="text" selected="selected">Text Field</option>
									<option value="textarea">Text Box</option>
									<option value="dropdown">Drop down</option>
									<option value="radio">Radio box</option>
									<option value="checkbox">Check Box</option>
								</select>
								</div>
								
								<div class="col-md-4" style="padding-left:0px; padding-top:15px">
								<label><input type="checkbox" <?php if($isRequired!='')echo 'checked="checked"';?> id="is_required" /> Required</label>
								<label style="float:right; cursor:pointer"><img src="images/cross.png" alt="Remove" title="Remove" onclick="removeField(this)"></label>
								</div>
							</div>
							<div class="col-md-12 fieldOptionContainer" style="padding:0px"></div>
						</div>
					<?php	
					}else if($fieldType=='textarea'){
					?>
						<div class="fieldSection">
							<div class="col-md-12" style="padding:0px">
								<div class="col-md-4" style="padding-left:0px">
								<input type="text" id="field_label" value="<?php echo $fieldLable?>" class="form-control" placeholder="Enter field label">
								</div>
								
								<div class="col-md-4" style="padding-left:0px">
								<select id="field_type" class="form-control" onchange="checkFieldType(this)">
									<option value="text">Text Field</option>
									<option value="textarea" selected="selected">Text Box</option>
									<option value="dropdown">Drop down</option>
									<option value="radio">Radio box</option>
									<option value="checkbox">Check Box</option>
								</select>
								</div>
								
								<div class="col-md-4" style="padding-left:0px; padding-top:15px">
								<label><input type="checkbox" <?php if($isRequired!='')echo 'checked="checked"';?> id="is_required" /> Required</label>
								<label style="float:right; cursor:pointer"><img src="images/cross.png" alt="Remove" title="Remove" onclick="removeField(this)"></label>
								</div>
							</div>
							<div class="col-md-12 fieldOptionContainer" style="padding:0px"></div>
						</div>
					<?php	
					}else if($fieldType=='dropdown'){
					?>
						<div class="fieldSection">
							<div class="col-md-12" style="padding:0px">
								<div class="col-md-4" style="padding-left:0px">
								<input type="text" id="field_label" value="<?php echo $fieldLable?>" class="form-control" placeholder="Enter field label">
								</div>
								
								<div class="col-md-4" style="padding-left:0px">
								<select id="field_type" class="form-control" onchange="checkFieldType(this)">
									<option value="text">Text Field</option>
									<option value="textarea">Text Box</option>
									<option value="dropdown" selected="selected">Drop down</option>
									<option value="radio">Radio box</option>
									<option value="checkbox">Check Box</option>
								</select>
								</div>
								
								<div class="col-md-4" style="padding-left:0px; padding-top:15px">
								<label><input type="checkbox" <?php if($isRequired!='')echo 'checked="checked"';?> id="is_required" /> Required</label>
								<label style="float:right; cursor:pointer"><img src="images/cross.png" alt="Remove" title="Remove" onclick="removeField(this)"></label>
								</div>
							</div>
							<div class="col-md-12 fieldOptionContainer" style="padding:0px">
								<div class="col-md-4 addMoreOption" style="padding-left:0px">
								<?php
									$options = @explode(',',trim($filedOptions,','));
									for($j=0; $j<count($options); $j++){
								?>
										<p class="optionHolder">
											<input type="text" value="<?php echo $options[$j]?>" class="form-control fieldOptions" placeholder="Enter option label">
										</p>
								<?php			
									}
								?>
								</div>
								<div class="col-md-4">
									<a href="javascript:void(0)" style="font-size:13px; margin-right:15px;" onclick="addNewOption(this)" title="Add new dropdown option">Add Option</a>
								</div>
								<div class="col-md-4">&nbsp;</div>
							</div>
						</div>
					<?php
					}else if($fieldType=='checkbox'){
					?>
						<div class="fieldSection">
							<div class="col-md-12" style="padding:0px">
								<div class="col-md-4" style="padding-left:0px">
								<input type="text" id="field_label" value="<?php echo $fieldLable?>" class="form-control" placeholder="Enter field label">
								</div>
								
								<div class="col-md-4" style="padding-left:0px">
								<select id="field_type" class="form-control" onchange="checkFieldType(this)">
									<option value="text">Text Field</option>
									<option value="textarea">Text Box</option>
									<option value="dropdown">Drop down</option>
									<option value="radio">Radio box</option>
									<option value="checkbox" selected="selected">Check Box</option>
								</select>
								</div>
								
								<div class="col-md-4" style="padding-left:0px; padding-top:15px">
								<label><input type="checkbox" <?php if($isRequired!='')echo 'checked="checked"';?> id="is_required" /> Required</label>
								<label style="float:right; cursor:pointer"><img src="images/cross.png" alt="Remove" title="Remove" onclick="removeField(this)"></label>
								</div>
							</div>
							<div class="col-md-12 fieldOptionContainer" style="padding:0px">
								<div class="col-md-4 addMoreOption" style="padding-left:0px">
								<?php
									$options = @explode(',',trim($filedOptions,','));
									for($j=0; $j<count($options); $j++){
								?>
										<p class="optionHolder">
											<input type="text" value="<?php echo $options[$j]?>" class="form-control fieldOptions" placeholder="Enter option label">
										</p>
								<?php			
									}
								?>
								</div>
								<div class="col-md-4">
									<a href="javascript:void(0)" style="font-size:13px; margin-right:15px;" onclick="addNewOption(this)" title="Add new dropdown option">Add Option</a>
								</div>
								<div class="col-md-4">&nbsp;</div>
							</div>
						</div>
					<?php
					}else if($fieldType=='radio'){
					?>
						<div class="fieldSection">
							<div class="col-md-12" style="padding:0px">
								<div class="col-md-4" style="padding-left:0px">
								<input type="text" id="field_label" value="<?php echo $fieldLable?>" class="form-control" placeholder="Enter field label">
								</div>
								
								<div class="col-md-4" style="padding-left:0px">
								<select id="field_type" class="form-control" onchange="checkFieldType(this)">
									<option value="text">Text Field</option>
									<option value="textarea">Text Box</option>
									<option value="dropdown">Drop down</option>
									<option value="radio" selected="selected">Radio box</option>
									<option value="checkbox">Check Box</option>
								</select>
								</div>
								
								<div class="col-md-4" style="padding-left:0px; padding-top:15px">
								<label><input type="checkbox" <?php if($isRequired!='')echo 'checked="checked"';?> id="is_required" /> Required</label>
								<label style="float:right; cursor:pointer"><img src="images/cross.png" alt="Remove" title="Remove" onclick="removeField(this)"></label>
								</div>
							</div>
							<div class="col-md-12 fieldOptionContainer" style="padding:0px">
								<div class="col-md-4 addMoreOption" style="padding-left:0px">
								<?php
									$options = @explode(',',trim($filedOptions,','));
									for($j=0; $j<count($options); $j++){
								?>
										<p class="optionHolder">
											<input type="text" value="<?php echo $options[$j]?>" class="form-control fieldOptions" placeholder="Enter option label">
										</p>
								<?php			
									}
								?>
								</div>
								<div class="col-md-4">
									<a href="javascript:void(0)" style="font-size:13px; margin-right:15px;" onclick="addNewOption(this)" title="Add new dropdown option">Add Option</a>
								</div>
								<div class="col-md-4">&nbsp;</div>
							</div>
						</div>
					<?php
					}
				}
			}
			?>
		</div>
		<div class="form-group">
			<a href="javascript:void(0)" style="font-size:15px; margin-right:15px;" onclick="createCustomField()">Add new field</a>
		</div>
		<div class="form-group">
			<label>Disclaimer Text</label>
			<textarea name="disclaimer_text" class="form-control"><?php echo DBout($wb['disclaimer_text'])?></textarea>
		</div>
		<div class="form-group">
			<label class="radio-inline"><input type="radio" name="showing_method" class="showing_method" value="1" <?php if(($wb['showing_method']=='1') || ($wb['showing_method']=='0'))echo 'checked="checked"';?> />Show in PopUp</label>
			<label class="radio-inline"><input type="radio" name="showing_method" class="showing_method" value="2" <?php if($wb['showing_method']=='2')echo 'checked="checked"';?> />Show on Page</label>
		</div>
		<div class="form-group">
			<?php 
				if($wb['webform_type']=='1'){
					$responsive = 'checked="checked"';
					$fixed	= '';
					$box = 'none';
				}
				else{
					$responsive = '';
					$fixed	= 'checked="checked"';
					$box = 'block';
				}
			?>
			<label class="radio-inline"><input type="radio" name="webform_type" class="webform_type" value="1" <?php echo $responsive?> >Responsive Widget</label>
			<label class="radio-inline"><input type="radio" name="webform_type" class="webform_type" value="2" <?php echo $fixed?> >Fixed Width Widget</label>
		</div>
		<div class="customize_section" style="display:<?php echo $box?>">
			<div class="form-group">
			<label style="color:#7E57C2; font-size:18px">Field Customization</label>
		</div>
		<div class="form-group">
			<label>Field Width</label>
			<input type="text" name="field_width" value="<?php echo $wb['field_width']?>" class="form-control numericOnly">
		</div>
		<div class="form-group">
			<label>Field Height</label>
			<input type="text" name="field_height" value="<?php echo $wb['field_height']?>" class="form-control numericOnly">
		</div>
		<div class="form-group" style="width:50% !important">
			<label>Color for Field Labels</label>
			<input type="text" name="color_for_label" value="<?php echo $wb['color_for_label']?>" class="color form-control">
		</div>
		<div class="form-group">
			<label style="color:#7E57C2; font-size:18px">Widget Customization</label>
		</div>
		<div class="form-group">
			<label>Frame Width</label>
			<input type="text" name="frame_width" value="<?php echo $wb['frame_width']?>" class="form-control numericOnly">
		</div>
		<div class="form-group">
			<label>Frame Height</label>
			<input type="text" class="form-control" value="Height will auto according to width." readonly>
		</div>
		<div class="form-group" style="width:50% !important">
			<label>Background Color</label>
			<input type="text" name="frame_bg_color" value="<?php echo $wb['frame_bg_color']?>" class="color">
		</div>
		<div class="form-group" style="width:50% !important">
			<label>Subscribe Button Color</label>
			<input type="text" name="subs_btn_bg_color" class="color form-control" value="<?php echo $wb['subs_btn_bg_color']?>">
		</div>
		<div class="form-group" style="width:50% !important">
			<label>Close Button Color</label>
			<input type="text" name="close_btn_bg_color" class="color form-control" value="<?php echo $wb['close_btn_bg_color']?>">
		</div>
		</div>
		
		<div class="form-group text-right m-b-0">
			<input type="hidden" name="id" id="id" value="<?php echo $wb['id']?>">
			<button id="webFormSaveButton" class="btn btn-primary waves-effect waves-light" type="button" onclick="saveWebForm()"> Save </button>
			<!--
			<button class="btn btn-primary waves-effect waves-light" type="submit"> Save </button>
			-->
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
<script src="scripts/js/tinycolor-0.9.15.min.js"></script>
<script src="scripts/js/pick-a-color-1.2.3.min.js"></script>
<script>
	function saveWebForm(){
		var customFields = prepareCustomFields();
		var webform_name = $('input[name="webform_name"]').val();
		var campaign_id = $('select[name="campaign_id"]').val();
		var label_for_name_field = $('input[name="label_for_name_field"]').val();
		var label_for_phone_field = $('input[name="label_for_phone_field"]').val();
		var label_for_email_field = $('input[name="label_for_email_field"]').val();
		var disclaimer_text = $('textarea[name="disclaimer_text"]').val();
		var label_for_disclaimer_text = $('input[name="label_for_disclaimer_text"]').val();
		var heading_for_custom_info_panel = $('input[name="heading_for_custom_info_panel"]').val();
		var wbID = $('#id').val();
		if($(".showing_method:checked").val()=='1'){
			var showing_method = '1';
		}else{
			var showing_method = '2';
		}
		if($(".webform_type:checked").val()=='2'){
			var webform_type = '2';
			var field_width = $('input[name="field_width"]').val();
			var field_height = $('input[name="field_height"]').val();
			var color_for_label = $('input[name="color_for_label"]').val();
			var frame_width = $('input[name="frame_width"]').val();
			var frame_bg_color = $('input[name="frame_bg_color"]').val();
			var subs_btn_bg_color = $('input[name="subs_btn_bg_color"]').val();
			var close_btn_bg_color = $('input[name="close_btn_bg_color"]').val();
		}else{
			var webform_type = '1';	
		}
		$("#webFormSaveButton").prop("disabled",true);
		$.post("server.php",{"cmd":"update_webform","customFields":customFields,webform_name:webform_name,campaign_id:campaign_id,label_for_name_field:label_for_name_field,label_for_phone_field:label_for_phone_field,label_for_email_field:label_for_email_field,disclaimer_text:disclaimer_text,webform_type:webform_type,field_width:field_width,field_height:field_height,color_for_label:color_for_label,frame_width:frame_width,frame_bg_color:frame_bg_color,subs_btn_bg_color:subs_btn_bg_color,close_btn_bg_color:close_btn_bg_color,label_for_disclaimer_text:label_for_disclaimer_text,heading_for_custom_info_panel:heading_for_custom_info_panel,wbID:wbID,showing_method:showing_method},function(r){
			$("#webFormSaveButton").prop("disabled",false);
			window.location = 'view_webform.php';
		});
	}
	function prepareCustomFields(){
		var obj = [];
		$('.fieldSection').each(function(index){
			var fieldLabel = $(this).find('#field_label').val();
			var fieldType  = $(this).find('#field_type').val();
			var isRequired = $(this).find('#is_required').prop('checked');
			obj[index] = {
				'field_label' : fieldLabel,
				'field_type'  : fieldType,
				'is_required' : isRequired
			};
			if($(this).find('.fieldOptions').length){
				var fieldOptions = '';
				$(this).find('.fieldOptions').each(function(i){
					fieldOptions += $(this).val()+',';
				});
				obj[index]['filed_options'] = fieldOptions;
			}else{
				obj[index]['filed_options'] = '';
			}
		});
		return obj;
	}
	function addNewOption(obj){
		var html = '<p class="optionHolder"><input type="text" class="form-control fieldOptions" style="width:85%;display:inline" placeholder="Enter option label"><img src="images/minus.png" style="cursor:pointer;display:inline;float:right;margin-top:5px;" title="Delete option" alt="Delete option" onclick="deleteFieldOption(this)"></p>';
		$(obj).closest('.fieldSection').find('.fieldOptionContainer > .addMoreOption').append(html);
	}
	function checkFieldType(obj){
		var elemType = $(obj).val();
		if(elemType=='text'){
			$(obj).closest('.fieldSection').find('.fieldOptionContainer').html('');
		}else if(elemType=='textarea'){
			$(obj).closest('.fieldSection').find('.fieldOptionContainer').html('');
		}else if(elemType=='dropdown'){
			var	html = '<div class="col-md-4 addMoreOption" style="padding-left:0px"><p class="optionHolder"><input type="text" class="form-control fieldOptions" placeholder="Enter option label"></p><p class="optionHolder"><input type="text" class="form-control fieldOptions" placeholder="Enter option label"></p></div>';
				html +=	'<div class="col-md-4"><a href="javascript:void(0)" style="font-size:13px; margin-right:15px;" onclick="addNewOption(this)" title="Add new '+elemType+' option">Add Option</a></div>';
				html +=	'<div class="col-md-4">&nbsp;</div>';
			$(obj).closest('.fieldSection').find('.fieldOptionContainer').html(html);
		}else if(elemType=='radio'){
			var	html = '<div class="col-md-4 addMoreOption" style="padding-left:0px"><p class="optionHolder"><input type="text" class="form-control fieldOptions" placeholder="Enter option label"></p><p class="optionHolder"><input type="text" class="form-control fieldOptions" placeholder="Enter option label"></p></div>';
				html +=	'<div class="col-md-4"><a href="javascript:void(0)" style="font-size:13px; margin-right:15px;" onclick="addNewOption(this)" title="Add new '+elemType+' option">Add Option</a></div>';
				html +=	'<div class="col-md-4">&nbsp;</div>';
			$(obj).closest('.fieldSection').find('.fieldOptionContainer').html(html);
		}else if(elemType=='checkbox'){
			var	html = '<div class="col-md-4 addMoreOption" style="padding-left:0px"><p class="optionHolder"><input type="text" class="form-control fieldOptions" placeholder="Enter option label"></p><p class="optionHolder"><input type="text" class="form-control fieldOptions" placeholder="Enter option label"></p></div>';
				html +=	'<div class="col-md-4"><a href="javascript:void(0)" style="font-size:13px; margin-right:15px;" onclick="addNewOption(this)" title="Add new '+elemType+' option">Add Option</a></div>';
				html +=	'<div class="col-md-4">&nbsp;</div>';
			$(obj).closest('.fieldSection').find('.fieldOptionContainer').html(html);
		}
	}
	function createCustomField(){
		var html  = '<div class="fieldSection">';
				html  += '<div class="col-md-12" style="padding:0px">';
					html += '<div class="col-md-4" style="padding-left:0px">';
					html += '<input type="text" id="field_label" class="form-control" placeholder="Enter field label">';
					html += '</div>';
					
					html += '<div class="col-md-4" style="padding-left:0px">';
					html += '<select id="field_type" class="form-control" onchange="checkFieldType(this)"><option value="text">Text Field</option><option value="textarea">Text Box</option><option value="dropdown">Drop down</option><option value="radio">Radio box</option><option value="checkbox">Check Box</option></select>';
					html += '</div>';
					
					html += '<div class="col-md-4" style="padding-left:0px; padding-top:15px">';
					html += '<label><input type="checkbox" id="is_required" /> Required</label>';
					html += '<label style="float:right; cursor:pointer"><img src="images/cross.png" alt="Remove" title="Remove" onclick="removeField(this)"></label>';
					html += '</div>';
				html += '</div>';
				html  += '<div class="col-md-12 fieldOptionContainer" style="padding:0px"></div>';
			html += '</div>';
		$('#fieldSectionContainer').append(html);
	}
	function deleteFieldOption(obj){
		if(confirm("Are you sure you want to delete this option?")){
			$(obj).closest('.optionHolder').remove();
		}
	}
	function removeField(obj){
		if(confirm("Are you sure you want to delete this field?")){
			$(obj).closest('.fieldSection').remove();
		}
	}
	$(document).ready(function(){
		$(".color").pickAColor({
		showSpectrum            : true,
		showSavedColors         : true,
		saveColorsPerElement    : true,
		fadeMenuToggle          : true,
		showAdvanced			: true,
		showBasicColors         : true,
		showHexInput            : true,
		allowBlank				: true,
		inlineDropdown			: true
		});
	});
</script>
<script type="text/javascript">
	$('.webform_type').on('click',function(r){
		if($(this).val()=='1'){
			$('.customize_section').slideUp('slow');
		}
		else{
			$('.customize_section').slideDown('slow');	
		}
	});
   // $('.color').colorPicker(); // that's it
</script>