<?php
@session_start();
include_once("database.php");
require_once 'Auth.net/anet_php_sdk/AuthorizeNet.php'; // Include the SDK you downloaded in Step 2
$api_login_id 	 = $appSettings['auth_net_api_login_id']; // '2NTu85XuhJG'; //
$transaction_key = $appSettings['auth_net_trans_key']; // '667zdR582BWa9p87'; //
//$amount 		 = $_REQUEST['pricePerCredit'];//"4.44";
//$amount		 = ($amount*$_REQUEST['auth_quantity']);

$perCreditRate  = $appSettings['per_credit_charges'];
$amount         = $perCreditRate;

$quantity = $_REQUEST['credit_quantity'];

$amount = round($amount*$quantity,2);


$packageID 		 = "1-200-".date("ymdhis");
$fp_timestamp 	 = time();
$fp_sequence 	 = "123" . time(); // Enter an invoice or other unique number.
$userID			 = $_SESSION['user_id']; 
$fingerprint = AuthorizeNetSIM_Form::getFingerprint($api_login_id,
$transaction_key, $amount, $fp_sequence, $fp_timestamp);
global $link;
$sel = "select * from users where id='".$userID."'";
$res = mysqli_query($link,$sel);
if($res and mysqli_num_rows($res))
{
	$row = mysqli_fetch_assoc($res);	
}

//$url_auth = "https://secure2.authorize.net/gateway/transact.dll";
$url_auth = "https://secure.authorize.net/gateway/transact.dll";
//$url_auth = "https://test.authorize.net/gateway/transact.dll";


$getServerUrl = getServerUrl();
$getServerUrl = str_replace("www.","",$getServerUrl);
?>

<form method='post' id="auth_form" action="<?php echo $url_auth; ?>">
<input type='hidden' name="x_login" value="<?php echo $api_login_id?>" />
<input type='hidden' name="x_fp_hash" value="<?php echo $fingerprint?>" />
<input type='hidden' name="x_amount" value="<?php echo $amount?>" />
<input type='hidden' name="x_fp_timestamp" value="<?php echo $fp_timestamp?>" />
<input type='hidden' name="x_fp_sequence" value="<?php echo $fp_sequence?>" />
<input type='hidden' name="x_version" value="3.1">
<input type='hidden' name="x_show_form" value="payment_form">
<input type='hidden' name="x_test_request" value="false" />
<input type='hidden' name="x_method" value="cc">
<input type='hidden' name="x_invoice_num" value="<?=$packageID;?>">
<input type='hidden' name="x_description" value="<?php echo $quantity; ?> SMS Credits">
<input type='hidden' name="x_cust_id" value="<?php echo $quantity.'_'.$userID."_onetime";?>">
<input type='hidden' name="x_first_name" value="<?php echo $row['first_name']?>">
<input type='hidden' name="x_last_name" value="<?php echo $row['last_name']?>">
<input type='hidden' name="x_company" value="XYZ LLC">
<input type='hidden' name="x_address" value="127P Kohinoor Town">
<input type='hidden' name="x_city" value="New York">
<input type='hidden' name="x_state" value="New York">
<!--<input type='hidden' name="x_zip" value="380000">-->
<input type='hidden' name="x_country" value="United States">
<input type='hidden' name="x_phone" value="<?php echo $row['pkg_id']?>">
<!--<input type='hidden' name="x_fax" value="+854568545">-->
<input type='hidden' name="x_email" value="<?php echo $row['email']?>">
<!--<input type='hidden' name="x_currency_code" value="USD">
-->

<input type='hidden' name="x_cancel_url" value="<?php echo $getServerUrl ?>">
<input type='hidden' name="x_receipt_link_url" value="<?php echo $getServerUrl; ?>/response.php">

<input type='hidden' name="x_relay_always" value="TRUE">
<input type='hidden' name="x_relay_response" value="TRUE">

<input type='hidden' name="x_relay_url" value="<?php echo $getServerUrl; ?>/response.php">

</form>
<script type="text/javascript">
	document.forms['auth_form'].submit();
</script>