<?php
require_once('stripe-php/init.php');

/// Be sure to replace this with your actual test API key
// (switch to the live key later)
\Stripe\Stripe::setApiKey("sk_test_e2NIgh0TRBl5gHvaByx5cYZp");

try
{
  $customer = \Stripe\Customer::create(array(
    'email' => $_POST['stripeEmail'],
    'source'  => $_POST['stripeToken'],
    'plan' => 'nm-platinum-plan'
  ));

    echo "<pre>";
    print_r($customer);
    
    $customerData = getProtectedValues($customer,"_values");
    $customerID = $customerData['id'];
    
    $resp = getProtectedValues($customer,"_lastResponse");
    if($resp->code==200){
        // Plan Created Successfully. 
    }
    

    

  //header('Location: thankyou.html');
  exit;
}
catch(Exception $e)
{
  header('Location:oops.html');
  error_log("unable to sign up customer:" . $_POST['stripeEmail'].
    ", error:" . $e->getMessage());
}


function getProtectedValues($obj,$name) {
    
  $array = (array)$obj;
  
  $prefix = chr(0).'*'.chr(0);
  
  return $array[$prefix.$name];
  
}

?>