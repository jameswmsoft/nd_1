<?php
@session_start();
include_once('database.php');
include_once('functions.php');
require_once('stripe-php/init.php');
$appSettings= getAppSettings(1,true);
// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey($appSettings['stripe_secret_key']);

// Token is created using Stripe.js or Checkout!
// Get the payment token submitted by the form:
$token = $_POST['stripeToken'];

try
{
    // Charge the user's card:
    $charge = \Stripe\Charge::create(array(
      "amount" => $_REQUEST['amt'],
      "currency" => "usd",
      "description" => "Add Credits",
      "source" => $token,
    ));
    
    //echo "<pre>";
    //print_r($charge);
    
    $chargeData = getProtectedValues($charge,"_values");
    //print_r($chargeData);
    
    $user = getUserInfo($_REQUEST['user_id']);
    
    if(isset($_REQUEST['stripeEmail']) && $_REQUEST['stripeEmail']!=""){
        $email = $_REQUEST['stripeEmail'];
    }else{
        $email = $user['email'];   
    }
    $amount = $_REQUEST['amt']/100;
	//$amount = $_REQUEST['amt'];
    $userID = $user['id'];
    $quantity = $_REQUEST['q'];
    
    $sql = "insert into payment_history
			(
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
                '1',
				'".$email."',
				'".$chargeData['id']."',
				'Completed',
				'".$amount."',
				'Add Credits',
				'".$userID."',
                '3'
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
    
    $_SESSION['message'] = "<div class='alert alert-success'>Credits updated successfully</div>";
    header("location: payment_history.php");
    
}
catch(Exception $e)
{
    //header('Location:oops.html');
    $_SESSION['message'] = "<div class='alert alert-danger'>Unable to Charge Card... <br> error:" . $e->getMessage().'</div>';
    header("location: settings.php");
}
//echo $_SESSION['message'];

?>