<?php
$data = array();
$data['name']="NM Platinum Plan";
$data['id']="nm-platinum-plan";
$data['interval']="month";
$data['currency']="usd";
$data['amount']="15";
//$url = "https://sk_test_e2NIgh0TRBl5gHvaByx5cYZp:api.stripe.com/v1/plans";


require_once('stripe-php/init.php');


\Stripe\Stripe::setApiKey("sk_test_e2NIgh0TRBl5gHvaByx5cYZp");

$plan = \Stripe\Plan::create($data);

echo "<pre>";
print_r($plan);

$resp = getProtectedValues($plan,"_lastResponse");

print_r($resp);

echo "<hr>";

echo $resp->code;


function getProtectedValues($obj,$name) {
    
  $array = (array)$obj;
  
  $prefix = chr(0).'*'.chr(0);
  
  return $array[$prefix.$name];
  
}

/***************


$res = create_stripe_plan($url,"post",$data);
echo "<pre>";
print_r($res);


function create_stripe_plan($url,$method,$body=""){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if($method == "post"){
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    else{
        curl_setopt($ch, CURLOPT_HTTPGET, true );   
    }   
    return curl_exec($ch);
}
************/

?>