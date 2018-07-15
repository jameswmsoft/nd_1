<?php
	//phpinfo();
	/*
	include_once("../db.php");
	$sql = "select * from settings where id='1'";
	$res = mysql_query($sql);
	$data1 = mysql_fetch_assoc($res);
	$plivoAuthID = $data1['auth_id'];
	$plivoAuthToken = $data1['auth_token'];
	$plivoAppID = $data1['plivo_app_id'];
	*/
	require"vendor/autoload.php";
	require("vendor/plivo/plivo-php/plivo.php");
	$p = new RestAPI('MAMGRHNDM5NDZMZJRIZD', 'ODQ5MDAyYjE2ZjVlZDQ5ZWI2MTNjMDExMzBlYTgx');
	$params = array(
		'src' => '1111111111',
		'dst' => '2222222222',
		'text' => 'Hello, how are you?'
	);
	try{
		$response = $p->send_message($params);
		echo '<pre>';
		print_r($response);
	}catch(Exception $e){
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        exit(0);
    }


	/*
	$index = 1;
	for($i = 0; $i <= 100; $i += 20){
		echo $i;
		echo '<br>';
		$response = $p->get_numbers(array('limit' => '0','offset' => $i));
		if($response['response']['objects'][0]!=''){
			foreach($response['response']['objects'] as $number){
				echo $index.' -> '.$number['number'];
				echo '<br>';
				$index++;
			}
		}else{
			break;	
		}
		
	}
	*/
?>