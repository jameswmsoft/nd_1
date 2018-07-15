<?php
	header('Content-Type: application/javascript');
	include_once('functions.php');
	$wbfID = decode($_REQUEST['wbfid']);
	$url   = getServerUrl().'/get_web_form.php?id='.$wbfID;
?>
jQuery(document).ready(function(){
	jQuery("#mynm_id").click(function(){		
		var x = '<div style="background: rgba(0,0,0,.7); padding-top: 22vh; top: 0px; left: 0px; position: fixed; width: 100%; height: 100%; z-index: 111111; display: none;" class="nmBackground">';
		x +='<div id="nmModalData"></div></div>';
		jQuery('body').append(x);
		jQuery(".nmBackground").fadeIn('slow');
		jQuery("#nmModalData").load("<?php echo $url?>",function(res,statusText){
			if(statusText == "success"){
			}else{
			}
		});
	});
});