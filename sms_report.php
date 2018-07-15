<?php
	include_once("header.php");
	include_once("left_menu.php");
?>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								SMS Report
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location=history.go(-1)" />
							</h4>
							<p class="category">Your prevoisly sent messages. </p>
						</div>
						<div class="content table-responsive table-full-width">
							<table id="smsReportTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>From</th>
										<th>To</th>
										<th>Text</th>
										<th>Media</th>
										<th>Direction</th>
										<th>Sent Date</th>
										<th>Info</th>
									</tr>
								</thead>
								<tbody>
			<?php
				$sql = "select * from sms_history where user_id='".$_SESSION['user_id']."' order by id desc";
				if(is_numeric($_GET['page']))
					$pageNum = $_GET['page'];
				else
					$pageNum = 1;
				$max_records_per_page = 20;
				$pagelink 	= "sms_report.php?";
				$pages 		= generatePaging($sql,$pagelink,$pageNum,$max_records_per_page);
				$limit 		= $pages['limit'];
				$sql 	   .= $limit;
				if($pageNum==1)
					$countPaging=1;
				else
					$countPaging=(($pageNum*$max_records_per_page)-$max_records_per_page)+1;
							
				if($_SESSION['TOTAL_RECORDS'] <= $max_records_per_page){
					$maxLimit = $_SESSION['TOTAL_RECORDS'];	
				}else{
					$maxLimit = (((int)$countPaging+(int)$max_records_per_page)-1);
				}
				if($maxLimit >= $_SESSION['TOTAL_RECORDS']){
					$maxLimit = $_SESSION['TOTAL_RECORDS'];	
				}
				$res = mysqli_query($link,$sql);
				if(mysqli_num_rows($res)){
					$index = $countPaging;
					while($row = mysqli_fetch_assoc($res)){
			?>
						<tr>
							<td><?php echo $index++?></td>
							<td>
								<?php 
									if($row['from_number']=='mobile_sim')
										echo 'Mobile Device';
									else
										echo $row['from_number'];
								?>
							</td>
							<td>
								<?php 
									if($row['to_number']=='mobile_sim')
										echo 'Mobile Device';
									else
										echo $row['to_number'];
								?>
							</td>
							<td style="text-align:left"><?php echo $row['text']?></td>
							<td>
								<?php 
								if($row['direction']=='out-bound'){
									if(trim($row['media'])!=''){
										if(strpos(isMediaExists($row['media']),'.')==false){
											//echo isMediaExists($row['media']);
										}else{
											echo isMediaExists($row['media']);	
										}
									}
								}
								?>
							</td>
							<td><?php echo $row['direction']?></td>
							<td><?php echo date($appSettings['app_date_format'].' H:i:s',strtotime($row['created_date']))?></td>
							<td>
								<?php
									if($row['is_sent']=='false'){
								?>
								<a href="#smsInfoModel" data-toggle="modal" onclick="getMessageDetails('<?php echo $row['id']?>')"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:22px; color:orange"></i></a>
								<?php
									}else{
								?>
								<a href="#smsInfoModel" data-toggle="modal" onclick="getMessageDetails('<?php echo $row['id']?>')"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:22px; color:green"></i></a>
								<?php		
									}
								?>
							</td>
						</tr>
			<?php			
					}
				}
			?>
				<tr>
					<td colspan="8" style="padding-left:0px !important;padding-right:0px !important"><?php echo $pages['pagingString'];?></td>
				</tr>
		</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<div id="smsInfoModel" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title" style="color:red">Message Details <span id="loading" style="display:none"><img src="images/busy.gif"></span></h6>
			</div>
			<div class="modal-body loadMsgDetails" style="overflow:auto"></div>
		</div>
	</div>
</div>
<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script>
	$('#smsReportTable').cardtable();
	function getMessageDetails(msgID){
		$('#loading').show();
		$.post('server.php',{"cmd":"get_message_details","msg_id":msgID},function(r){
			$('#loading').hide();
			$('.loadMsgDetails').html(r);
		});
	}
</script>