<?php 
	include_once("database.php");
	include_once("functions.php");
	if($_REQUEST['txn_type']=='subscr_payment'){
		$custom = @explode('_',$_REQUEST['custom']);
		$pkgID  = $custom[0];
		$userID = $custom[1];
		$sel = "select * from package_plans where id='".$pkgID."'";
		$exe = mysqli_query($link,$sel);
		if(mysqli_num_rows($exe)){
			$row = mysqli_fetch_assoc($exe);
			$startDate= date('Y-m-d H').':00:00';
			$endDate= date('Y-m-d H:i',strtotime('+1 month'.$today));
			$up = "update user_package_assignment set start_date='".$startDate."', end_date='".$endDate."', pkg_id='".$pkgID."', sms_credits=sms_credits+'".$row['sms_credits']."', phone_number_limit='".$row['phone_number_limit']."',locations='".$row['locations']."',reminders='".$row['reminders']."', pkg_country='".$row['country']."' where user_id='".$userID."'";
			mysqli_query($link,$up);
			
			mysqli_query($link,"insert into payment_history	(business_email,payer_status,payer_email,txn_id,payment_status,gross_payment,product_name,user_id,payment_processor)values('".$_REQUEST['business']."','".$_REQUEST['payer_status']."','".$_REQUEST['payer_email']."','".$_REQUEST['txn_id']."','".$_REQUEST['payment_status']."','".$_REQUEST['payment_gross']."','".$_REQUEST['item_name']."','".$row['parent_user_id']."','".$payment_processor."')");
		}
	}
?>