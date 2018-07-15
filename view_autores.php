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
								Autoresponders
								<input type="button" class="btn btn-primary" value="Add New" style="float:right !important" onclick="window.location='add_autores.php'" />
							</h4>
							<p class="category">Your already saved list of autoresponders.</p>
						</div>
						<div class="content table-responsive table-full-width">
							<table id="autoResTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>Title</th>
										<th>Keyword</th>
										<th>Phone Number</th>
										<th>Direct Subscription</th>
										<th>Subscribers / Unsubscribers</th>
										<th>Media</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sql = "select * from campaigns where user_id='".$_SESSION['user_id']."' and type='2'";
										if(is_numeric($_GET['page']))
											$pageNum = $_GET['page'];
										else
											$pageNum = 1;
										$max_records_per_page = 20;
										$pagelink 	= "view_autores.php?";
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
													<td style="text-align:left !important"><?php echo $row['title'];?></td>
													<td><?php echo $row['keyword'];?></td>
													<td><?php echo $row['phone_number'];?></td>
													<td>
													<?php 
														if($row['direct_subscription']=='1'){
															echo 'On';
														}else{
															echo 'Off';	
														}
													?>
													</td>
													<td align="center">
														<?php
															$exe = mysqli_query($link,"select s.id from subscribers_group_assignment sga, subscribers s where sga.group_id='".$row['id']."' and sga.subscriber_id=s.id and s.status='1'");
															if(mysqli_num_rows($exe)=='0'){
																echo mysqli_num_rows($exe);
															}else{
																echo '<a href="subscribers_stats.php?group_id='.encode($row["id"]).'&searchType=subscribers" title="View details" target="_blank">'.mysqli_num_rows($exe).'</a>';
															}
															echo ' / ';
															$exe = mysqli_query($link,"select s.id from subscribers_group_assignment sga, subscribers s where sga.group_id='".$row['id']."' and sga.subscriber_id=s.id and s.status='2'");
															if(mysqli_num_rows($exe)=='0'){
																echo mysqli_num_rows($exe);
															}else{
																echo '<a href="subscribers_stats.php?group_id='.encode($row["id"]).'&searchType=unsubscribers" title="View details" target="_blank">'.mysqli_num_rows($exe).'</a>';
															}
														?>
													</td>
													<td>
														<?php 
															echo isMediaExists($row['media']);
														?>
													</td>
													<td style="text-align:center">
														<a href="add_autores.php?id=<?php echo $row['id']?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
														<i class="fa fa-remove" style="color:red; cursor:pointer" onclick="deleteCampaign('<?php echo $row['id']?>','<?php echo $row['media']?>')"></i>
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
	$('#autoResTable').cardtable();
	function deleteCampaign(id,img){
		if(confirm("Are you sure you want to delete this autoresponder?")){
			window.location = 'server.php?cmd=delete_autores&id='+id+'&media='+img;
		}
	}
</script>