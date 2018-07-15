<?php
set_time_limit(900000);
ini_set('max_execution_time', 900000);
include_once("../database.php");

function LogErrors($data)
{
	$myFile = "thanku.txt";
	$fh = fopen($myFile, 'a') or die("can't open file");
	fwrite($fh, $data);
	fclose($fh);
}

if($_REQUEST['x_response_code'] == '1' and $_REQUEST['x_response_reason_code'] == '1')
{
    
    $_REQUEST['custom'] = $_REQUEST['x_cust_id'];
    $_REQUEST['payer_status'] = $_REQUEST['x_response_code'];
    $_REQUEST['payer_email'] = $_REQUEST['x_email'];
    $_REQUEST['txn_id'] = $_REQUEST['x_trans_id'];
    $_REQUEST['payment_status'] = $_REQUEST['x_response_code'];
    $_REQUEST['payment_gross'] = $_REQUEST['x_amount'];
    $_REQUEST['item_name'] = $_REQUEST['x_description'];
    $_REQUEST['txn_type'] = 'subscr_payment';
    $_REQUEST['payment_processor'] = "2";
    
    include_once("../credits_notify.php");
}
?>