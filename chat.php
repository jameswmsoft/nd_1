<?php
	include_once("header.php");
	include_once("left_menu.php");
	$phoneID = decode($_REQUEST['phoneid']);
	$sql = "select ch.*, s.phone_number, s.first_name from chat_history ch, subscribers s where ch.phone_id='".$phoneID."' and s.id=ch.phone_id order by id asc";
	$res = mysqli_query($link,$sql);
	$messages = mysqli_num_rows($res);
	$currenttime = strtotime(date('Y-m-d H:i:s'));
	

	
?>
<link type="text/css" rel="stylesheet" href="css/chat.css" />
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								Chat with <?php echo $_REQUEST['ph'];?> from &nbsp;
								<select name="from_number" id="from_number" class="form-control" style="width:20%; display:inline">
								<?php
									if($adminSettings['sms_gateway']=='twilio'){
										$sel = "select id,phone_number from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='1'";
									}else if($adminSettings['sms_gateway']=='plivo'){
										$sel = "select id,phone_number from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='2'";
									}else if($adminSettings['sms_gateway']=='nexmo'){
										$sel = "select id,phone_number from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='3'";
									}
									$exe = mysqli_query($link,$sel);
									if(mysqli_num_rows($exe)){
										while($rec = mysqli_fetch_assoc($exe)){
											echo '<option value="'.urlencode($rec['phone_number']).'">'.$rec['phone_number'].'</option>';
										}
									}else{
										echo '<option value="">No from phone available.</option>';
									}
								?>
								</select>
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location='view_subscribers.php'" />
							</h4>
							<p class="category">One by one chat history.</p>
						</div>
						<div class="content table-responsive">
<div class="panel panel-primary">
	<div class="panel-heading">
		<span class="fa fa-comment"></span> Chat
	</div>
	<div class="panel-body" id="chat_container">
		<ul class="chat">
		<?php
			if($messages>0){
				while($row = mysqli_fetch_assoc($res)){
					$ago = timeAgo($row['created_date']);

					if($row['direction']=='in'){
		?>
				<li class="left clearfix"><span class="chat-img pull-left">
					<img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />
				</span>
					<div class="chat-body clearfix">
						<div class="header chat_header">
							<strong class="primary-font"><?php echo $row['first_name']?></strong> <small class="pull-right text-muted">
								<span class="fa fa-clock-o"></span><?php echo $ago?></small>
						</div>
						<p><?php echo DBout($row['message'])?></p>
					</div>
				</li>
		<?php				
					}else{
		?>
				<li class="right clearfix"><span class="chat-img pull-right">
					<img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" />
				</span>
					<div class="chat-body clearfix">
						<div class="header chat_header">
							<small class=" text-muted"><span class="fa fa-clock-o"></span><?php echo $ago?></small>
							<strong class="pull-right primary-font"><?php echo $_SESSION['first_name']?></strong>
						</div>
						<p><?php echo DBout($row['message'])?></p>
					</div>
				</li>
		<?php				
					}
				}
			}else{
		?>
				<li class="right clearfix">
					<div class="chat-body clearfix">
						<p>
							No chat history to display.								
						</p>
					</div>
				</li>
		<?php			
			}
		?>
		</ul>
	</div>
	<div class="panel-footer">
		<div class="input-group">
			<input id="chat_message" type="text" class="form-control input-sm" placeholder="Type your message here..." onkeypress="checkKey(event);" />
			<span class="input-group-btn">
				<button class="btn btn-warning btn-sm" onclick="sendChatMessage()">Send</button>
			</span>
		</div>
	</div>
</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>
<script>
$(document).ready(function(){
	updateScroll();
});
function updateScroll(){
    var element = document.getElementById("chat_container");
    element.scrollTop = element.scrollHeight;
}
setInterval(function loadChat(){
	$.post('server.php',{"cmd":"load_chat",phoneID:"<?php echo $phoneID?>"},function(r){
		$('.chat').html(r);
		updateScroll();
	});
}, 3000);
function checkKey(e){
	if(window.event){e = window.event;}
	if(e.keyCode == 13){
		sendChatMessage();
	}
}
function sendChatMessage(){
	var chatMessage = document.getElementById('chat_message').value;
	var appendElement = '<li class="right clearfix"><span class="chat-img pull-right">';
	appendElement += '<img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" /></span>';
	appendElement += '<div class="chat-body clearfix"><div class="header chat_header"><small class=" text-muted"><span class="fa fa-clock-o"></span>Just now</small><strong class="pull-right primary-font"><?php echo $_SESSION['first_name']?></strong></div><p>'+chatMessage+'</p></div></li>';
	$('.chat').append(appendElement);
	document.getElementById('chat_message').value='';
	updateScroll();
	$.post('server.php',{chatMessage:encodeURIComponent(chatMessage),"cmd":"save_chat_message","phone_id":"<?php echo $phoneID?>","To":"<?php echo urlencode($_REQUEST['ph'])?>","From":$('#from_number option:selected').val()},function(r){
		if(r!='1'){
			alert(r);
		}
	});	
}
</script>