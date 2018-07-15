<?php
	include_once("header.php");
	include_once("left_menu.php");
	$groupID = decode($_REQUEST['group_id']);
	if($_REQUEST['searchType']=='subscribers'){
		$status = '1';
		$heading = 'Subscribers stats';
	}else{
		$status = '2';
		$heading = 'UnSubscribers stats';
	}
?>
<style>
.chatBadge{
    display: inline-block;
    min-width: 10px;
    padding: 3px 5px;
    font-size: 10px;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    background-color:red;
    border-radius: 10px;
    margin: le;
    margin-right: -6px;
	color:#FFF;
}
</style>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								<?php echo $heading?>
								<input type="button" class="btn btn-success" value="Back" style="float:right !important" onclick='goBack("-1")' />
							</h4>
							<p class="category">Will show you subscribers stats group wise.</p>
						</div>
						<div class="content table-responsive table-full-width">
							<div class="col-md-6"></div>
							<div class="col-md-3"></div>
							<div class="col-md-3">
								<input type="text" name="search_subs" id="searchkeyword" class="form-control" placeholder="Search here..." onkeypress="OnKeyPress(event)" value="<?php echo $_REQUEST['searchkeyword']?>" />
							</div>
							<table id="subscribersTable" class="table table-hover table-striped">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Phone</th>
										<th>Status</th>
										<th>Subscribed Date</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
			<?php
				if(isset($_REQUEST['searchkeyword']) && $_REQUEST['searchkeyword']!=''){
					$sql = "select 
								s.* 
							from 
								subscribers s, 
								subscribers_group_assignment sga 
							where 
								sga.group_id='".$groupID."' and
								sga.subscriber_id=s.id and
								s.user_id='".$_SESSION['user_id']."' and
								s.status='".$status."' and
								(s.phone_number like '%".$_REQUEST['searchkeyword']."%') or
								(s.first_name like '%".$_REQUEST['searchkeyword']."%')
							order by 
								id desc";
				}else{
					$sql = "select 
								s.* 
							from 
								subscribers s,
								subscribers_group_assignment sga
							where 
								sga.group_id='".$groupID."' and
								sga.subscriber_id=s.id and
								s.user_id='".$_SESSION['user_id']."' and
								s.status='".$status."'
							order by 
								id desc";
				}
				if(is_numeric($_GET['page']))
					$pageNum = $_GET['page'];
				else
					$pageNum = 1;
				$max_records_per_page = 20;
				$pagelink 	= "subscribers_stats.php?";
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
				//echo $sql;
				$res = mysqli_query($link,$sql);
				if(mysqli_num_rows($res)){
					$index = $countPaging;
					while($row = mysqli_fetch_assoc($res)){
						$sel = "select id as unReadMsgs from chat_history where phone_id='".$row['id']."' and is_read='0'";
						$exe = mysqli_query($link,$sel);
						if(mysqli_num_rows($exe)){
							$unReadMsgs = mysqli_num_rows($exe);
						}else{
							$unReadMsgs = 0;
						}
			?>
						<tr>
							<td><?php echo $index++?></td>
							<td><?php echo $callerName?></td>
                            <td><?php echo $row['phone_number'];?></td>
							<td>
								<?php 
									if($row['status']=='1')
										echo '<span class="badge badge-success">Active</span>';
									else if($row['status']=='2')
										echo '<span class="badge badge-warning">Blocked</span>';
									else if($row['status']=='3')
										echo '<span class="badge badge-danger">Deleted</span>';
								?>
							</td>
							<td><?php echo date($appSettings['app_date_format'].' H:i:s',strtotime($row['created_date']));?></td>
							<td style="text-align:center">
								<?php
									if(trim($row['custom_info'])!=''){
								?>
									<a href="#customInfoBox" title="View additional Information" onclick="getSubsCustomInfo('<?php echo $row['id']?>')" data-toggle="modal"><i class="fa fa-info"></i></a>
								<?php
									}
								?>
								
								<a href="chat.php?phoneid=<?php echo encode($row['id']).'&ph='.urlencode($row['phone_number']);?>" title="Chat">
									<?php
										if($unReadMsgs>0){
											echo '<span class="chatBadge">'.$unReadMsgs.'</span>';
										}
									?>
									<i class="fa fa-comments" aria-hidden="true" style="color:#0C0"></i></a>
									<a href="add_subscribers.php?id=<?php echo $row['id']?>"><i class="fa fa-edit"></i></a>&nbsp;<i class="fa fa-remove" style="color:red; cursor:pointer" onclick="deleteSubscriber('<?php echo $row['id']?>')"></i>
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
<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />

<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script>
	function getSubsCustomInfo(subsID){
		$('.loadCustomInfo').html('Loading...');
		$.post('server.php',{"cmd":"load_subs_custom_info","subs_id":subsID},function(r){
			$('.loadCustomInfo').html(r);
		});
	}
	$('#subscribersTable').cardtable();
	/*
	function showSubscriberDetails(obj,subsID){
		if($(obj).attr('class')=='fa fa-arrow-down'){
			$(obj).attr('class','fa fa-arrow-up')
		}else{
			$(obj).attr('class','fa fa-arrow-down')
		}
		$('.showSubsDetials_'+subsID).slideToggle();
	}
	*/
	function OnKeyPress(e){
		if(window.event){ e = window.event; }
		if(e.keyCode == 13){
			var searchkeyword = document.getElementById('searchkeyword').value;
			window.location = 'subscribers_stats.php?group_id=<?php echo $_REQUEST['group_id']?>&searchType=subscribers&searchkeyword='+encodeURIComponent(searchkeyword);
			
		}	
	}
	function deleteSubscriber(id){
		if(confirm("Are you sure you want to delete this subscriber?")){
			window.location = 'server.php?cmd=delete_subscriber&id='+id;
		}
	}
</script>
<div id="customInfoBox" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Additional Information of the Subscriber</h6>
			</div>
			<div class="modal-body loadCustomInfo"></div>
			<!--
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			-->
		</div>
	</div>
</div>