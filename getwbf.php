<?php
	header('Content-Type: application/javascript');
	include_once('functions.php');
	$wbfID = decode($_REQUEST['wbfid']);
	$wbType = decode($_REQUEST['wbtype']);
	//mail('ahsan@nimblewebsolutions.com','WB',$wbfID.'__'.$wbType);
	$url   = getServerUrl().'/get_web_form.php?id='.$wbfID.'&wbt='.$wbType;
	if($wbType=='2'){
?>
jQuery(document).ready(function(){
	jQuery("#nmModalData").load("<?php echo $url?>",function(res,statusText){
		if(statusText == "success"){}
		else{}
	});
});		
<?php		
	}else{
?>
jQuery(document).ready(function(){
	jQuery("#mynm_id").click(function(){		
		var x = '<div style="background: rgba(0,0,0,.7); padding-top: 22vh; top: 0px; left: 0px; position: fixed; width: 100%; height: 100%; z-index: 111111; display: none;" class="nmBackground">';
		x +='<div id="nmModalData"></div></div>';
		jQuery('body').append(x);
		jQuery(".nmBackground").fadeIn('slow');
		jQuery("#nmModalData").load("<?php echo $url?>",function(res,statusText){
			if(statusText == "success"){
				
			}
			else{
				
			}
		});
	});
});
<?php
	}
?>