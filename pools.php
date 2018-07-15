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
								Number Pools
								<input type="button" class="btn btn-primary" value="Add New" style="float:right !important" onclick="window.location='add_pool.php'" />
							</h4>
							<p class="category">Your already saved list of number pools.</p>
						</div>
						<div class="content table-responsive table-full-width">
							
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