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
								Ranksol help
								<!--
								<input type="button" class="btn btn-primary" value="Add New" style="float:right !important" onclick="window.location='test.php'" />
								-->
							</h4>
							<p class="category">Create ticket here if you any issue.</p>
						</div>
						<div class="content table-responsive table-full-width">
							<iframe src="http://ranksol.com/help" style="width:100%; height:100%; border:none"></iframe>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>