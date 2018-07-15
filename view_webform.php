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
								Webforms
								<input type="button" class="btn btn-primary" value="Add New" style="float:right !important" onclick="window.location='add_webform.php'" />
							</h4>
							<p class="category">Already saved list of webforms.</p>
						</div>
						<div class="content table-responsive table-full-width">
							<table id="webformTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>WebFrom</th>
										<th>Group</th>
										<th>Created Date</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sql = "select * from webforms where user_id='".$_SESSION['user_id']."'";
										if(is_numeric($_GET['page']))
											$pageNum = $_GET['page'];
										else
											$pageNum = 1;
										$max_records_per_page = 20;
										$pagelink 	= "view_webform.php?";
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
												<td style="text-align:left"><?php echo $row['webform_name']?></td>
												<td>
												<?php
													$exe = mysqli_query($link,"select title from campaigns where id='".$row['campaign_id']."'");
													$r = mysqli_fetch_assoc($exe);
													echo $r['title'];
												?>
												</td>
												<td><?php echo $row['created_date']?></td>
												<td style="text-align:center">
													<a href="#custom-modal" data-toggle="modal" title="Embed Code" onClick="getEmbedCode('<?php echo $row['id']?>')"><i class="fa fa-code" style="font-size:17px; font-weight:bold"></i></a>&nbsp;&nbsp;
													<a href="edit_webform.php?id=<?php echo $row['id']?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<i onclick="deleteWebform('<?php echo $row['id']?>')" style="color:red; cursor:pointer" class="fa fa-remove"></i>
												</td>
											</tr>	
									<?php
											}
										}
									?>
										<tr>
											<td colspan="5" style="padding-left:0px !important;padding-right:0px !important"><?php echo $pages['pagingString'];?></td>
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
<div id="custom-modal" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Embed Code</h6>
			</div>
			<div class="modal-body embedBody"></div>

		</div>
	</div>
</div>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script src="scripts/js/custombox.min.js"></script>
<script src="scripts/js/legacy.min.js"></script>
<script>
	$('#webformTable').cardtable();
	function getEmbedCode(webFormID){
		$('.embedBody').html('<img src="images/busy.gif">');
		var Qry = 'cmd=generate_embed_code&wbf_id='+webFormID;
		$.post('server.php',Qry,function(r){
			$('.embedBody').html('<textarea class="form-control" rows="8" onClick="this.select()">'+r+'</textarea>');
		});
	}
	function deleteWebform(id){
		if(confirm("Are you sure you want to delete this webform?")){
			window.location = 'server.php?cmd=delete_webform&id='+id;
		}
	}
</script>