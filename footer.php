	</div>
</body>
<!--   Core JS Files -->
<?php
if(basename($_SERVER['PHP_SELF'])!="create_pages.php"){
    ?>
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <?php
}
?>

<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<!--  Checkbox, Radio & Switch Plugins -->
<script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>
<!--  Charts Plugin -->
<script src="assets/js/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="assets/js/bootstrap-notify.js"></script>

<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="assets/js/light-bootstrap-dashboard.js"></script>
<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
<script type="text/javascript" src="scripts/js/parsley.min.js"></script> 
<script src="assets/js/demo.js"></script>
<script src="assets/js/jquery-ui.js"></script>
<script type="text/javascript">
	function verifyEnvatoPurchaseCode(){
		var purchaseCode = $('input[name="product_purchase_code"]').val();
		if($.trim(purchaseCode)!=''){
			$('#verify').html('Verifying...');
			$('#verify').show();
			$.post('http://apps.ranksol.com/nm_license/check_code.php',{purchaseCode:purchaseCode,"server_url":'<?php echo getServerUrl()?>'},function(r){
				var res = $.parseJSON(r);
				if(res.error=='no'){
					$('#verify').html(res.message);
					var status = 'verified';
				}else{
					$('#verify').html(res.message);
					var status = 'invalid';
				}
				$.post('server.php',{"cmd":"update_purchase_code","status":status,purchaseCode:purchaseCode,user_id:'<?php echo $_SESSION['user_id']?>'},function(rr){
					window.location = 'dashboard.php';
				});
			});
		}else{
			alert('Enter purchase code.');	
		}
	}
	/*
	$(function(){
		$('form').parsley().on('field:validated', function(){
			var ok = $('.parsley-error').length === 0;
			$('.bs-callout-info').toggleClass('hidden', !ok);
			$('.bs-callout-warning').toggleClass('hidden', ok);
		}).on('form:submit', function(){
			return false;
		});
	});
	*/
	$( ".addDatePicker" ).datepicker({
		inline: true,
		dateFormat: 'yy-mm-dd'
	});
	$(document).ready(function(){
		<?php
			if(trim(@$_SESSION['message'])!=''){
				$check = strpos($_SESSION['message'],'alert-danger');
				if($check==false)
					$notiType = 'success';
				else
					$notiType = 'danger';
		?>
		var noti = "<?php echo strip_tags($_SESSION['message'])?>";
		$.notify({
			icon: 'pe-7s-attention',
			message: noti
		},{
			type: '<?php echo $notiType?>',
			timer: 1000
		});
		<?php unset($_SESSION['message']); }?>
		// Text counter
		$('.showCounter').hide();
		// also open counter for follow up messages when need.
		/*
		$('body').on('keyup','.textCounter',function(){
		var len = $(this).val().length;
		if(len>=maxLength){
		var chars = $(this).val().substring(0,maxLength);
		$(this).val(chars);
		$(this).closest('div').find('.showCount').text(maxLength-chars.length);
		}
		else{
		$(this).closest('div').find('.showCount').text(maxLength-len);
		}
		});
		*/
		//$('.showCount').text(maxLength);
		// text counter end
		
		// Decimal only
		$('body').on('keyup','.decimalOnly',function(){
			var val = $(this).val();
			var filterdVal = val.replace(/[^.\d]/g,'');
			$(this).val(filterdVal);
		});
		// end
		
		// Phone number only
		$('body').on('keyup','.phoneOnly',function(){
			var val = $(this).val();
			var filterdVal = val.replace(/[^+\d]/g,'');
			$(this).val(filterdVal);
		});
		// end
		
		// checking numeric only
		$('body').on('keyup','.numericOnly',function(){
			var val = $(this).val();
			var filterdVal = val.replace(/[^\d]/g,'');
			$(this).val(filterdVal);
		});
		// end numeric only
	});
</script>
<div id="verificationSection" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Verify your application</h6>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Enter your envato product purchase code</label>
					<input type="text" name="product_purchase_code" class="form-control" />
				</div>
			</div>
			<div class="modal-footer">
				<span style="display:none" id="verify">Verifying...</span>
				<input type="button" value="Verify" class="btn btn-success" onClick="verifyEnvatoPurchaseCode()" />
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
</html>