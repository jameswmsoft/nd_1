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
								Add New Reminder
								<input type="button" class="btn btn-primary" value="Add New" style="float:right !important" onclick="window.location='add_apts.php'" />
							</h4>
							<p class="category">To add a new reminder please click on "Add New"</p>
						</div>
						<div class="content table-responsive table-full-width">
                            <?php
                                include 'calendar.php'
                            ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="loadAptAlerOrFollowUp" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title typeTitle">Alerts/FollowUps</h6>
			</div>
			<div class="modal-body loadAptAlerts">Loading...</div>
			<div class="modal-footer">
				<span id="duplicateCampaignloading" style="display:none">Loading...</span>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<link rel="stylesheet" type="text/css" href="assets/css/calendar.css">
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script>
	function loadAptAlerOrFollowUp(dataType,aptID){
		$('.loadAptAlerts').html('Loading...');
		$('.typeTitle').html('Loading...');
		if(dataType=='alerts')
			var cmd = 'load_apt_alerts';
		else
			var cmd = 'load_apt_followUp';
			
		$.post('server.php',{cmd:cmd,aptID:aptID},function(r){
			$('.loadAptAlerts').html(r);
			if(cmd=='load_apt_alerts')
				$('.typeTitle').html('Alerts');
			else
				$('.typeTitle').html('Follow Up');
		});
	}
	$('#aptTable').cardtable();
	function deleteApt(aptID){
		if(confirm("Are you sure you want to delete this appointment?")){
			$.post('server.php',{aptID:aptID,"cmd":"delete_apt"},function(r){
				window.location = 'view_apts.php';	
			});	
		}
	}
</script>