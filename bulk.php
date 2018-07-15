<?php
	echo '<pre>';
	print_r($_REQUEST);
	die();
	set_time_limit(900000);
	ini_set('max_execution_time', 900000);	
	@session_start();
	include_once("database.php");
	include_once("functions.php");
?>
<script src="assets/js/jquery-1.10.2.js"></script>

<input id="hidden_sms_id" name="hidden_sms_id" value="<?php echo $_REQUEST["hidden_sms_id"]; ?>" type="hidden"/>
<input id="bulk_type" name="bulk_type" value="<?php echo $_REQUEST["bulk_type"]; ?>" type="hidden"/>
<input id="client_id" name="client_id" value="<?php echo $_REQUEST["client_id"]; ?>" type="hidden"/>
<input id="from_number" name="from_number" value="<?php echo $_REQUEST["from_number"]; ?>" type="hidden"/>
<input id="group_id" name="group_id" value="<?php echo $_REQUEST["group_id"]; ?>" type="hidden"/>
<input id="phone_number_id" name="phone_number_id" value="<?php echo $_REQUEST["phone_number_id"]; ?>" type="hidden"/>
<input id="start_date" name="start_date" value="<?php echo $_REQUEST["start_date"]; ?>" type="hidden"/>
<input id="end_date" name="end_date" value="<?php echo $_REQUEST["end_date"]; ?>" type="hidden"/>
<input id="daterange_group_id" name="daterange_group_id" value="<?php echo $_REQUEST["daterange_group_id"]; ?>" type="hidden"/>
<div style="width:80%; margin:0 auto;" align="center">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 id="json_msg_response">Please wait! Sending Bulk Messages... <img src="images/ajax-loader-black-bar.gif" /> </h3>
			<div style="float:right; margin-top:-30px; cursor: pointer;" onclick="window.close()" title="Close the window"><span class="glyphicon glyphicon-remove"></span></div>
		</div>
		<div class="panel-body" id="response_wait_image">
			<div class="alert alert-danger" id="warning_msg"> Please don't close this tab untill system sends the messages to the selected recipients. </div>
		</div>
	</div>
</div>
<?php

if(trim($_REQUEST['hidden_sms_id']) == "")
{
    die("<center><h2 style='color:red;'>Not Allowed</h2></center>");
}
?>
<script type="text/javascript">
var sent=-30;
var req=30;
var QryStr='';
var total=100000000000;

function sendSMS(){	
    var hidden_sms_id = $('#hidden_sms_id').val();
	var bulk_type = $('#bulk_type').val();
	var client_id = $('#client_id').val();
	var from_number = $('#from_number').val();
    var group_id = $('#group_id').val();
	var phone_number_id = $('#phone_number_id').val();
	var start_date = $('#start_date').val();
	var end_date = $('#end_date').val();
    var daterange_group_id = $('#daterange_group_id').val();
    
	QryStr = 'smsid='+hidden_sms_id+'&bulk_type='+bulk_type+'&client_id='+client_id+'&from_number='+from_number+'&group_id='+group_id+'&phone_number_id='+phone_number_id+'&start_date='+start_date+'&end_date='+end_date+'&daterange_group_id='+daterange_group_id;
	
    sendSMS1();
    if(total>sent)
    sendSMS2();
    if(total>sent)
    sendSMS3();
    if(total>sent)
    sendSMS4();
    if(total>sent)
    sendSMS5();
    if(total>sent)
    sendSMS6();
}

var ajax_res_check={};

function sendSMS1()
{
    ajax_res_check['sms1']="start";
    sent+=req;
    var qr=QryStr+"&start="+(sent-req);
    //console.log(qr);
    $.post('send_bulk_sms.php',qr, function(res){
        ajax_res_check['sms1']="completed";
        list_response(res);
        if(total>sent)
            sendSMS1();
    });
}
function sendSMS2()
{ 
    ajax_res_check['sms2']="start";
   // var to_numbers = tonumbers.slice(sent,sent+req);
          sent+=req;
           var qr=QryStr+"&start="+(sent-req);
    $.post('send_bulk_sms.php',qr, function(res){
              ajax_res_check['sms2']="completed";
list_response(res);
                if(total>sent)
            sendSMS2();
        });
   
}
function sendSMS3()
{
    ajax_res_check['sms3']="start";
   /// var to_numbers=tonumbers.slice(sent,sent+req);
            sent+=req;
          var qr=QryStr+"&start="+(sent-req);
    $.post('send_bulk_sms.php',qr, function(res){
              ajax_res_check['sms3']="completed";
list_response(res);
                if(total>sent)
            sendSMS3();
        });
 
}
function sendSMS4()
{
    ajax_res_check['sms4']="start";
   /// var to_numbers=tonumbers.slice(sent,sent+req);
        sent+=req;
         var qr=QryStr+"&start="+(sent-req);
    $.post('send_bulk_sms.php',qr ,function(res){
              ajax_res_check['sms4']="completed";
list_response(res);
                if(total>sent)
            sendSMS4();
        });
     
}function sendSMS5()
{
 ajax_res_check['sms5']="start";   
   /// var to_numbers=tonumbers.slice(sent,sent+req);
        sent+=req;
         var qr=QryStr+"&start="+(sent-req);
    $.post('send_bulk_sms.php',qr ,function(res){
              ajax_res_check['sms5']="completed";
list_response(res);
                if(total>sent)
            sendSMS5();
        });
     
}function sendSMS6()
{
    ajax_res_check['sms6']="start";
   /// var to_numbers=tonumbers.slice(sent,sent+req);
        sent+=req;
         var qr=QryStr+"&start="+(sent-req);
    $.post('send_bulk_sms.php',qr ,function(res){
              ajax_res_check['sms6']="completed";
list_response(res);
                if(total>sent)
            sendSMS6();
        });
     
}

function show_completed(){
    
  if((ajax_res_check['sms1']=="completed")&&(ajax_res_check['sms2']=="completed")&&(ajax_res_check['sms3']=="completed")&&(ajax_res_check['sms4']=="completed")&&(ajax_res_check['sms5']=="completed")&&(ajax_res_check['sms6']=="completed"))  
 {   
    $("#json_msg_response").html("Message Sending Completed");
    $('#warning_msg').hide();
 }

}

function list_response(res){
    //console.log(res);
            var result=$.parseJSON(res);
              show_completed();
        if(result.error == "yes" || sent === total)
        {
            //console.log(result.error);
            total=0;
        }
        var res2='';
     if(result.result.length>0)
     {  $.each(result.result,function(k,v){
        //console.log(v['Phone']);
           if(v['response'] == 'sent')
        res2+='Message Sent to '+v['Phone']+"<br/>";
        else
        res2+='Message status pending '+v['Phone']+"<br/>";
        } );
     }
document.getElementById('response_wait_image').innerHTML +=res2;

}

$(document).ready(function(){
     sendSMS();
});

</script>