<?php
	include_once("database.php");
	include_once("functions.php");
	
	if($_REQUEST['payment_status']=="Completed" || $_REQUEST['payment_status']=="1"){
		$custom = explode('_',$_REQUEST['custom']);
		$quantity = $custom[0];
		$userID	  = $custom[1];
		
        if(isset($_REQUEST['payment_processor']) && $_REQUEST['payment_processor']==2){
            $payment_processor = $_REQUEST['payment_processor'];
        }else{
            $payment_processor = 1;
        }
        
        $sql_chk = "select * from payment_history where txn_id = '".$_REQUEST['txn_id']."'";
        $exe_chk = mysqli_query($link,$sql_chk);
        if(mysqli_num_rows($exe_chk)==0)
        {
            $sql = "insert into payment_history
					(
						business_email,
						payer_status,
						payer_email,
						txn_id,
						payment_status,
						gross_payment,
						product_name,
						user_id,
                        payment_processor
					)
				values
					(
						'".$_REQUEST['business']."',
						'".$_REQUEST['payer_status']."',
						'".$_REQUEST['payer_email']."',
						'".$_REQUEST['txn_id']."',
						'".$_REQUEST['payment_status']."',
						'".$_REQUEST['mc_gross']."',
						'".$_REQUEST['item_name']."',
						'".$userID."',
                        '".$payment_processor."'
					)";
    		mysqli_query($link,$sql);
    		
    		// Adding rollover
    		$sqlrollover = "insert into rollover_credits
    							(
    								user_id,
    								credits
    							)
    						values
    							(
    								'".$userID."',
    								'".$quantity."'
    							)";
    		$resRollover = mysqli_query($link,$sqlrollover);
    		if($resRollover){
    			$sqlUpdate = "update user_package_assignment set sms_credits=sms_credits+'".$quantity."' where user_id='".$userID."'";
    			$resUpdate = mysqli_query($link,$sqlUpdate);
    		}
        }
        
        
        
		
		// end	
	}
?>