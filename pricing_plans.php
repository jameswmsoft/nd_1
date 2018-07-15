<?php
@session_start();
include_once("database.php");
include_once("functions.php");
$uid = $_REQUEST['uid'];
$sel = "select business_name from users where type='1'";
$exe = mysqli_query($link,$sel);
if(mysqli_num_rows($exe)){
	$adminData = mysqli_fetch_assoc($exe);
	$businessName  = $adminData['business_name'];
}else{
	$businessName  = 'Nimble Messaging';
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>SMS Reminder</title>
<link href="css/pricing_style.css" rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic' rel='stylesheet' type='text/css'>
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<meta name="viewport" content="width=device-width" />

<style>
.alert-success {
	background-color: #dff0d8;
	border-color: #d0e9c6;
	color: #3c763d;
}
.alert {
	border: 1px solid transparent;
	border-radius: 0.25rem;
	padding: 0.75rem 1.25rem;
	margin-bottom: 0;
	margin-top: 25px;
}
.price-head >h1{
	color:#666;
}
@media (max-width:450px){
	.pricing-grid1, .pricing-grid2, .pricing-grid3 {
		margin:0 auto !important;	
	}
	.pricing-grids{
		text-align:center !important
	}
}
</style>
</head>
<body>
<div class="pricing-plans">
	<div class="wrap">
		<div class="price-head">
			<h1>SELECT A PACKAGE</h1>
		</div>
		<?php        
        if(isset($_SESSION['authnet_response']) && $_SESSION['authnet_response']==1){
            echo '<div class="alert alert-success">'.$_SESSION['authnet_msg'].'</div>';
        }else if(isset($_SESSION['authnet_response']) && $_SESSION['authnet_response']==0){
            echo '<div class="alert alert-danger">'.$_SESSION['authnet_msg'].'</div>';
        }else if(trim($_SESSION['message'])!=''){
			echo $_SESSION['message'];
		}
		unset($_SESSION['message']);
        unset($_SESSION['authnet_msg']);
        unset($_SESSION['authnet_response']);
        ?>
		<div class="pricing-grids">
			<?php
				$id = decode($_REQUEST['id']);
				if(trim($id)==""){
					$sel = "select id from users where type='1'";
					$exe = mysqli_query($link,$sel);
					$r   = mysqli_fetch_assoc($exe);
					$id	 = $r['id'];
				}
                
                $AppSettings = getAppSettings($id);
                if($AppSettings['payment_processor'] == "3"){
                    $sql = "select * from package_plans where user_id='".$id."'";
                }else{
                    $sql = "select * from package_plans where user_id='".$id."'";
                }
                
				$res = mysqli_query($link,$sql);
				$totalRecords = mysqli_num_rows($res);
				if($totalRecords>0){
					$records = 0;
					$styles = array('pricing-grid1','pricing-grid2','pricing-grid3');
					while($row = mysqli_fetch_assoc($res)){
						$styleKey = array_rand($styles,1);
						$mainClass = $styles[$styleKey];
						if($mainClass=='pricing-grid1'){
							$index = '';
							$saleBox = 'sale-box';
							$cart = 'cart1';
						}else if($mainClass=='pricing-grid2'){
							$index = 'two';
							$saleBox = 'sale-box two';
							$cart = 'cart2';
						}else if($mainClass=='pricing-grid3'){
							$index = 'three';
							$saleBox = 'sale-box three';
							$cart = 'cart3';
							
						}
						if(($records+1)==$totalRecords){
							$margin = ' style="margin-right:0px;"';
						}else{
							$margin = ' style="margin-right:16px;"';
						}
						$records++;
			?>
			<div class="<?php echo $mainClass?>"<?php echo $margin?>>
				<div class="price-value <?php echo $index?>">
					<h2><a href="#"> <?php echo strtoupper($row['title'])?> </a></h2>
					<h5><span>$ <?php echo $row['price'];?></span>
						<lable> /Month</lable>
					</h5>
					<div class="<?php echo $saleBox?>"> <span class="on_sale title_shop">NEW</span> </div>
				</div>
				<div class="price-bg">
					<ul>
						<!--<li class="whyt"><a href="#">Available SMS Credits <b><?php echo $row['sms_credits']?></b></a></li>-->
                        <li class="whyt"><a href="#">Available Locations <b><?php echo $row['locations']?></b></a></li>
                        <li class="whyt"><a href="#">Available Reminders <b><?php echo $row['reminders']?></b></a></li>
						<li><a href="#">Allowed Phone Numbers <b><?php echo $row['phone_number_limit']?></b></a></li>
						<li class="whyt"><a href="#">Released Date <b><?php echo date('F/d/Y',strtotime($row['created_date']))?></b></a></li>
						<!--<li><a href="#">Secure Payment via Paypal</b></a></li>-->
						<li class="whyt"><a href="#">24/7 Support</a></li>
					</ul>
					<?php
                    if($AppSettings['payment_processor'] == "3"){
                        ?>
                        <form action="create_subscription_form.php?id=<?php echo $row['id']; ?>" method="POST">
                          <script
                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="<?php echo $AppSettings['stripe_publishable_key'] ?>"
                            data-image=""
                            data-name="<?php echo $businessName?>"
                            data-description="<?php echo $row['title'] ?>"
                            data-amount="<?php echo $row['price']*100 ?>"
                            data-label="Purchase">
                          </script>
                        </form>
                        <?php  
                    }else{
                        ?>
                        <div class="<?php echo $cart?>"> <a class="popup-with-zoom-anim" href="add_user.php?pid=<?php echo encode($row['id'])?>&uid=<?php echo $uid?>">Purchase</a> </div><br>
                        <?php
                    }
                    ?>
				</div>
			</div>
			<?php
					}
				}else{
					echo '<h1 style="color:red">No plans created by admin.</h1>';
				}
			?>
			<div class="clear"></div>
		</div>
		<div class="clear"> </div>
	</div>
</div>
<div class="footer">
	<div class="wrap">
		
	</div>
</div>
</body>
</html>