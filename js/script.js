 var count=100;
$(document).ready(function(){
     
    var prev_id="";
    var sender="";
            $( "#column2" ).sortable({
            revert: 300,
            delay: 100,
            opacity: 0.8,
            placeholder: "placeholder",
            forceHelperSize: true,
              handle: ".widget-head",
             forcePlaceholderSize: true,
                receive: function(e,ui){
                    count++;
             prev_id=$(ui.item).attr('id');
             sender=$(ui.item).parent().attr('id');
             //console.log("Previous id="+prev_id);
            },
            stop:function(e,ui){
                if(prev_id != "")
          {  $(ui.item).attr({id: prev_id+"_"+count});
//console.log("stop ran========="+$(ui.sender).attr('id'));
 
             $("#"+prev_id+"_"+count+" .widget_hover").show();
       $("#"+prev_id+"_"+count).append(get_template(prev_id));
   //  console.log("Prev---"+prev_id);

          }
         if(sender == "column1" ){ 
     if(prev_id == "content")
      {
  $("#"+prev_id+"_"+count+" .text_area_cam").css({height: '100px',width:'575px'});
   new nicEditor({fullPanel : true,iconsPath : 'images/nicEditIcons-latest.gif'}).panelInstance('page_content_'+count);
 // //console.log("page_editor activated");
      }
      if(prev_id == "redeem"){
        $.each(pages_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=redeem_page_url]").append('<option value='+key+'>'+val+'</option>');  
 });
    ///////////////
            $.each(buttons_types_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=choose_button_type_"+count+"]").append('<option value='+key+'>'+val+'</option>');  
 });
 /////////////////////
         $.each(buttons_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=redeem_button_template]").append('<option value="'+key+'++'+val.is_empty+'">'+val.title+'</option>');  
 });
  /////////////////////
         $.each(force_email_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=redeem_force_email]").append('<option value="'+key+'">'+val+'</option>');  
 });
      }
            if(prev_id == "icons"){
        $.each(pages_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=after_share_url]").append('<option value='+key+'>'+val+'</option>');  
 });
 $.each(arr_align_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=text_alignment]").append('<option value='+val+'>'+val+'</option>');  
 });   
      }      
       if(prev_id == "button"){
        $.each(simple_button_options_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=simple_button_type]").append('<option value='+key+'>'+val+'</option>');  
 });
     $.each(arr_align_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=text_alignment]").append('<option value='+val+'>'+val+'</option>');  
 });   
      }
      if(prev_id == "cart"){
        $.each(buttons_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=cart_button_template]").append('<option value="'+key+'++'+val.is_empty+'">'+val.title+'</option>');  
 });
 /////////////////
            $.each(buttons_types_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=choose_button_type_"+count+"]").append('<option value='+key+'>'+val+'</option>');  
 });
   
      }
      if(prev_id == "scarcity"){
              $.each(arr_align_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=text_alignment]").append('<option value='+val+'>'+val+'</option>');  
 }); 
      }
  if(prev_id == "redeem" || prev_id == "button" || prev_id == "call" || prev_id == "timer" || prev_id == "scarcity"|| prev_id == "twitter" || prev_id == "webform" || prev_id == "fonts" || prev_id=="loyalty"|| prev_id=="cart" || prev_id=="scratch")    
{ 
    $("#"+prev_id+"_"+count+" .color_picker").prop("type","color");
    
    
/*
          $("#"+prev_id+"_"+count+" .color_picker").ColorPicker({
onSubmit: function(hsb, hex, rgb, el) {
$(el).val(hex);
$(el).ColorPickerHide();},
 onBeforeShow: function () {
$(this).ColorPickerSetColor(this.value);
}
});
*/

}
         if(prev_id == "scratch"){
 $.each(arr_align_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=text_alignment]").append('<option value='+val+'>'+val+'</option>');  
 });  
        $.each(pages_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=scratch_page]").append('<option value='+key+'>'+val+'</option>');  
 });  
 } 
  if(prev_id == "timer")
{
        var myCalendar;
    myCalendar = new dhtmlXCalendarObject(["end_time_"+count+""]);
   myCalendar= myCalendar.setDateFormat("%d/%m/%Y %H:%i");
}  
if(prev_id == "fonts")
{
 $.each(fonts_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=google_fonts]").append('<option value='+val+'>'+val+'</option>');  
 }) 
 $.each(fonts_sort_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=get_updated_fonts]").append('<option value='+key+'>'+val+'</option>');  
 })   
 ///////////////////////
  $.each(arr_align_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=text_alignment]").append('<option value='+val+'>'+val+'</option>');  
 }) 
}
if(prev_id == "loyalty")
{   
 ///////////////////////
  $.each(arr_align_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=text_alignment]").append('<option value='+val+'>'+val+'</option>');  
 }) ;
$.each(pages_obj,function(key,val){
   $("#"+prev_id+"_"+count+" select[name=reward_page]").append('<option value='+key+'>'+val+'</option>');  
 });
    var myCalendar;
    myCalendar = new dhtmlXCalendarObject(["keyword_date_"+count+""]);
     myCalendar= myCalendar.setDateFormat("%Y-%m-%d"); 
}
if(prev_id=="webform"){
    $.each(campaigns_obj,function(key,val){
       $("#"+prev_id+"_"+count+" select[name=group_id]").append('<option value='+key+'>'+val+'</option>');  
     });
}


 sender="";
 prev_id="";
         }      
            }
        });
        $( "#column1 .widget" ).draggable({
            connectToSortable: "#column2",
            helper: "clone",
            revert: "invalid",
          
            start: function(e,ui){
  
            },
            stop: function(e,ui){
   
            },
         
        
        });  
   
  $("#save_page").click(function(){
      
      if($("input[name=page_title]").val()=="")
     { alert("Enter page title");
       //  return;
     }
      else{
     $("#light_box").css({width: $(window).width(), height: $(window).height(),display: 'block'});    
      var refresh_rate="";
      var redeem_limit="";
      var red_page_id="";
      var redeem_one="";
     //var data='{"json" : [{"person" : [{"name": "touseef"}],[{"person2" : [{"name" : "ahmad"}]}]}';
     var data={};
           var widget_json={};
     
     /////////////save settings
     var page_title=$("#slider_settings_0 input[name=page_title]").val();
     var radio=$("#slider_settings_0 input:radio[name=slider_settings_0]:checked").val();
                 //console.log(radio);
 
  var header_image=$("#slider_settings_0 #uploaded_thumb_0").attr('src');  
 
  var header_color=$("#slider_settings_0 input[name=page_bg_color]").val();    
       var address=$("#slider_settings_0 textarea[name=address]").val();
    //   if(address != "")
  {         var lat=$("#slider_settings_0 input[name=lat]").val();
           var lon=$("#slider_settings_0 input[name=lon]").val();           }  
            var border_style=$("#slider_settings_0 select[name=border_style]").val();
             var border_thickness=$("#slider_settings_0 input[name=border_thickness]").val();
              var border_color=$("#slider_settings_0 input[name=border_color]").val();        
           
           // else           {           var lat="";           var lon="";           }
           var header_type="none";
           header_image=header_image.substring(1,header_image.indexOf("?"));
    img_arr=header_image.split("/");
    header_image=img_arr[img_arr.length-1];  
  if(radio == "header_image")
   {            
  /// widget_json='{"page_title" : "'+page_title+'","header_image" : "'+header_image+'","address" : "'+address+'","lat" : "'+lat+'","lon" : "'+lon+'"}';
   header_type="image";
   }
   else if(radio == "header_video")
   {
     //  widget_json='{"page_title" : "'+page_title+'","header_color" : "'+header_color+'","address" : "'+address+'","lat" : "'+lat+'","lon" : "'+lon+'"}';
   header_type="color";
   }
       var js_code=$("#slider_settings_0 textarea[name=js_code]").val();
 ///  else
 ///widget_json='{"page_title" : "'+page_title+'","border_style" : "'+border_style+'","border_thickness" : "'+border_thickness+'","border_color" : "'+border_color+'","address" : "'+address+'","lat" : "'+lat+'","lon" : "'+lon+'","header_type" : "'+header_type+'","header_color" : "'+header_color+'","header_image" : "'+header_image+'"}';
widget_json['page_title']=page_title;
widget_json['border_style']=border_style;
widget_json['border_thickness']=border_thickness;
widget_json['border_color']=border_color;
widget_json['address']=address;
widget_json['lat']=lat;
widget_json['lon']=lon;
widget_json['header_type']=header_type;
widget_json['header_color']=header_color;
widget_json['header_image']=header_image;
widget_json['js_code']=js_code;
console.log(widget_json);
   data['settings_0']=widget_json;
   ////////////////
     $("#column2 li").each(function(i){
         //////////////////////////////setting
         var li_id=this.id;
        
         var widget=li_id.split("_");
          var widget_name=widget[0];
          var widget_no=widget[1];
          var widget_json={};
          
        switch(widget_name){
       
            case "header":{
                 var check=$("#"+li_id+" .check_widget").attr('src');
                 var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
                 var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
                  /* if(radio == "header_image")
  {
  var header_image=$("#"+li_id+" #uploaded_thumb_"+widget_no).attr('src'); 
      header_image=header_image.substring(1,header_image.indexOf("?"));
    img_arr=header_image.split("/");
    header_image=img_arr[img_arr.length-1];          
            widget_json='{"header_image" : "'+header_image+'","check": "'+check+'"}'; 
  }
   else if(radio == "header_video")
  {
  var header_image=$("#"+li_id+" #uploaded_video_"+widget_no).attr('src');  
     header_image=header_image.substring(1,header_image.indexOf("?"));
    img_arr=header_image.split("/");
    header_image=img_arr[img_arr.length-1];        
            widget_json='{"header_video" : "'+header_image+'","check": "'+check+'"}';  
  }
  */
                   var radio=$("#"+li_id+" input:radio[name=slider_header_"+widget_no+"]:checked").val();
  if(radio == "header_image")
  {
    var radio_c=$("#"+li_id+" input:radio[name=image_choice_"+widget_no+"]:checked").val();
    if(radio_c == "image_upload")   
  {  
  var header_image=$("#"+li_id+" #uploaded_thumb_"+widget_no).attr('src');
      header_image=header_image.substring(1,header_image.indexOf("?"));
    img_arr=header_image.split("/");
    header_image=img_arr[img_arr.length-1];          
   /// widget_json='{"header_type": "image","header_image_name" : "'+header_image+'","check": "'+check+'","no_border": "'+no_border+'","transparent_bg": "'+transparent_bg+'"}'; 
 widget_json['header_type']= "image";  
 widget_json['header_image_name']= header_image;  
 widget_json['check']= check;  
 widget_json['no_border']= no_border;  
 widget_json['transparent_bg']= transparent_bg;  
  }
  else  if(radio_c == "image_url") {
  var header_image=$("#"+li_id+" input[name=image_url]").val();          
  ///  widget_json='{"header_type": "image","header_image_url" : "'+header_image+'","check": "'+check+'","no_border": "'+no_border+'","transparent_bg": "'+transparent_bg+'"}';    
 widget_json['header_type']= "image";  
 widget_json['header_image_url']= header_image;  
 widget_json['check']= check;  
 widget_json['no_border']= no_border;  
 widget_json['transparent_bg']= transparent_bg; 
  }
  }
   else if(radio == "header_video")
  {
        var autoplay=$("#"+li_id+" input[name=autoplay]").is(":checked");
        var loop=$("#"+li_id+" input[name=loop]").is(":checked");
        var video_width=$("#"+li_id+" input[name=video_width]").val();
        var video_height=$("#"+li_id+" input[name=video_height]").val();
        var radio_c=$("#"+li_id+" input:radio[name=video_choice_"+widget_no+"]:checked").val();
    if(radio_c == "video_upload") 
{  var header_image=$("#"+li_id+" input[name=hidden_video_name]").val();  
/* var header_image=$("#"+li_id+" #uploaded_video_"+widget_no).attr('src');  
   var header_arr=header_image.split("/");
     header_arr=header_arr.reverse();
     header_image=header_arr[0];  */       
  /// widget_json='{"header_type": "video","header_video_name" : "'+header_image+'","autoplay" : "'+autoplay+'","loop" : "'+loop+'","check": "'+check+'","no_border": "'+no_border+'","transparent_bg": "'+transparent_bg+'"}';  
    widget_json['header_type']= "video";  
 widget_json['header_video_name']= header_image;  
 widget_json['check']= check;  
 widget_json['no_border']= no_border;  
 widget_json['transparent_bg']= transparent_bg; 
 widget_json['autoplay']= autoplay; 
 widget_json['video_width']= video_width; 
 widget_json['video_height']= video_height; 
 widget_json['loop']= loop; 
}
else  if(radio_c == "video_url"){
     var header_image=$("#"+li_id+" input[name=video_url]").val();          
  ///  widget_json='{"header_type": "video","header_video_url" : "'+header_image+'","autoplay" : "'+autoplay+'","loop" : "'+loop+'","check": "'+check+'","no_border": "'+no_border+'","transparent_bg": "'+transparent_bg+'"}';  
       widget_json['header_type']= "video";  
 widget_json['header_video_url']= header_image;  
 widget_json['check']= check;  
 widget_json['no_border']= no_border;  
 widget_json['transparent_bg']= transparent_bg; 
 widget_json['autoplay']= autoplay; 
  widget_json['video_width']= video_width; 
 widget_json['video_height']= video_height; 
 widget_json['loop']= loop;    
} 

  }
  console.log(widget_json);
  console.log(radio+"--------"+radio_c);
   
            };break;
   case "content":{
                   var check=$("#"+li_id+" .check_widget").attr('src');
                    var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
                 var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
                 var page_content=$("#"+li_id+" textarea[name=page_content]").prev("div").children(".nicEdit-main").html();
            ///   page_content=page_content.replace(/"/g,'\'');
               var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
               var no_padding=$("#"+li_id+" input[name=no_padding]").is(":checked");
             //   var pc_opacity=$("#"+li_id+" input[name=pc_div_opacity]").val();
              //  var pc_font_family=$("#"+li_id+" select[name=pc_font_family]").val();
            //    var pc_font_size=$("#"+li_id+" input[name=pc_font_size]").val();
            //    var pc_font_color=$("#"+li_id+" input[name=pc_font_color]").val();
          //  widget_json='{"page_content" : "'+page_content+'","page_opacity" : "'+pc_opacity+'","pc_font_family" : "'+pc_font_family+'","pc_font_size" : "'+pc_font_size+'","pc_font_color" : "'+pc_font_color+'","check": "'+check+'"}';
          ///  widget_json='{"page_content" : "'+page_content+'","check": "'+check+'","no_border": "'+no_border+'","transparent_bg": "'+transparent_bg+'","no_padding": "'+no_padding+'"}';
      widget_json['page_content']=page_content;      
      widget_json['check']=check;      
      widget_json['no_border']=no_border;      
      widget_json['transparent_bg']=transparent_bg;      
      widget_json['no_padding']=no_padding;      
            };break;
  case "redeem":{
                var check=$("#"+li_id+" .check_widget").attr('src');
                 var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
                 var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
                var redeem_label=$("#"+li_id+" input[name=redeem_label]").val();
                var redeem_color=$("#"+li_id+" input[name=redeem_color]").val();
                var font_size=$("#"+li_id+" input[name=font_size]").val();
                var redeem_bg_color=$("#"+li_id+" input[name=redeem_bg_color]").val();
                var redeem_hover_color=$("#"+li_id+" input[name=redeem_hover_color]").val();
                var redeem_page_url=$("#"+li_id+" select[name=redeem_page_url]").val();
               if($("#"+li_id+" input:checkbox[name=redeem_once]").is(":checked"))
               var redeem_once="yes";
               else
             var  redeem_once="no";
              var refresh_period=$("#"+li_id+" input[name=refresh_period]").val();
              var redeem_text =$("#"+li_id+" textarea[name=redeem_text]").val();
              var redeem_prompt =$("#"+li_id+" textarea[name=redeem_prompt]").val();
             var redeem_radio=$("#"+li_id+" input:radio[name=slider_redeem_"+widget_no+"]:checked").val();
             var redeem_img_type="none";
             var redeem_image_val="";
               if(redeem_radio == "redeem_image")
               { redeem_img_type="image";
    
         }
               else   if(redeem_radio == "redeem_img_url")
               {
                   redeem_img_type="url";
  
               }
////////////////////////////////image name               
var redeem_image=$("#"+li_id+" #redeem_thumb_"+widget_no).attr('src');
redeem_image=redeem_image.substring(1,redeem_image.indexOf("?"));
    img_arr=redeem_image.split("/");
   redeem_img_name=img_arr[img_arr.length-1]; 
   var redeem_img_url=$("#"+li_id+" input[name=redeem_img_url]").val();
var redeem_button_type=$("#"+li_id+" select[name=choose_button_type_"+widget_no+"]").val();
/////////////////////////
var redeem_button_image=$("#"+li_id+" #redeem_button_thumb_"+widget_no).attr('src');
      redeem_button_image=redeem_button_image.substring(1,redeem_button_image.indexOf("?"));
      redeem_button_image=redeem_button_image.substring(redeem_button_image.lastIndexOf("/")+1);
 
      //////////////////////
/*if(redeem_button_radio == "button_image")
 {
 redeem_button_type="image";    
 }
 else if(redeem_button_radio == "button_template")
 {
 redeem_button_type="template"       
 }*/
 var redeem_button_template=$("#"+li_id+" select[name=redeem_button_template]").val();    
 var is_empty=$("#"+li_id+" input[name=is_empty]").val();  
 
 ///////////////////////////////////////force optin logic///////////////////////  
 var force_optin_check=$("#"+li_id+" #force_optin_"+widget_no).is(":checked");
 var radio_email_sms=$("#"+li_id+" input:radio[name=redeem_email_"+widget_no+"]:checked").val();
var force_optin_case="none";
  var  arr_force_optin={};
 if(radio_email_sms == "redeem_email_"+widget_no)
 {
 var selected_email=$("#"+li_id+" select[name=redeem_force_email]").val();
//console.log(selected_email);
   arr_force_optin['force_optin_email_case']=selected_email;
  switch(selected_email){
 
      case "aweber":
      var form_action=$("#"+li_id+" #cc_form_dom form").attr('action');
        if(typeof form_action == 'undefined')
arr_force_optin['form_action']=$.parseJSON($("#"+li_id+" #cc_form_json").html()).form_action;
else
    {  arr_force_optin['form_action']=form_action+"?meta_web_form_id="+$("#"+li_id+" #cc_form_dom form input[name=meta_web_form_id]").val()+"&listname="+$("#"+li_id+" #cc_form_dom form input[name=listname]").val();
    }
          //console.log("awebere");
       break;
	   case "sendreach":

      var form_action=$("#"+li_id+" #cc_form_dom form").attr('action');
	  

        if(typeof form_action == 'undefined')

arr_force_optin['form_action']=$.parseJSON($("#"+li_id+" #cc_form_json").html()).form_action;

else

    {  /*arr_force_optin['form_action']=form_action+"&name="+$("#"+li_id+" #cc_form_dom form input[name=name]").val()+"&email="+$("#"+li_id+" #cc_form_dom form input[name=email]").val();*/
arr_force_optin['form_action']=form_action;

    }
	
	
	  

          //console.log("sendreach");

       break;
	   
 case "icontact":
 var form_action=$("#"+li_id+" #cc_form_dom form").attr('action');
  if(typeof form_action == 'undefined')
 arr_force_optin['form_action']=$.parseJSON($("#"+li_id+" #cc_form_json").html()).form_action;
 else{
 var list_id=$("#"+li_id+" #cc_form_dom form input[name=listid]").val();
 var special_id=$("#"+li_id+" #cc_form_dom form input[name='specialid:"+list_id+"']").val();
 var client_id=$("#"+li_id+" #cc_form_dom form input[name=clientid]").val();
 var form_id=$("#"+li_id+" #cc_form_dom form input[name=formid]").val();
 var reallist_id=$("#"+li_id+" #cc_form_dom form input[name=reallistid]").val();
 var doubleopt=$("#"+li_id+" #cc_form_dom form input[name=doubleopt]").val();
 arr_force_optin['form_action']=form_action+"?listid="+list_id+"&specialid:"+list_id+"="+special_id+"&clientid="+client_id+"&formid="+form_id+"&reallistid="+reallist_id+"&doubleopt="+doubleopt;
 }
          //console.log("const contact");
       break;
        case "mailchimp":
        var form_action=$("#"+li_id+" #cc_form_dom form").attr('action');
        if(typeof form_action == 'undefined')
        form_action=$.parseJSON($("#"+li_id+" #cc_form_json").html()).form_action;
        arr_force_optin['form_action']=form_action;
          //console.log("mail chimp");
       break;
	   
	   case "getresponse":

        var form_action=$("#"+li_id+" #cc_form_dom form").attr('action');

        if(typeof form_action == 'undefined')

		{

        	arr_force_optin['form_action']=$.parseJSON($("#"+li_id+" #cc_form_json").html()).form_action;

		}

		else

		{

        	arr_force_optin['form_action']=form_action+"?webform_id="+$("#"+li_id+" #cc_form_dom form input[name=webform_id]").val();

		}

		//arr_force_optin['form_action']=form_action;

          //console.log("mail chimp");

       break;

        case "const_contact":
        arr_force_optin['email_const_contact']=$("#"+li_id+" input[name=email_const_contact]").val();
        arr_force_optin['pass_const_contact']=$("#"+li_id+" input[name=pass_const_contact]").val();
        arr_force_optin['apikey_const_contact']=$("#"+li_id+" input[name=apikey_const_contact]").val();
        arr_force_optin['list_const_contact_id']=$("#"+li_id+" select[name=cc_list]").val();
        arr_force_optin['list_const_contact_name']=$("#"+li_id+" select[name=cc_list] option:selected").text();
          //console.log("i conmtact");
       break;      
  } 
  force_optin_case="email";
  //console.log(JSON.stringify(arr_force_optin));
 } 
 else if(radio_email_sms == "redeem_sms_"+widget_no){
   force_optin_case="sms";  
   arr_force_optin['user_campaign_id']=$("#"+li_id+" select[name=user_campeigns]").val();  
   arr_force_optin['user_campaign_name']=$("#"+li_id+" select[name=user_campeigns]").val();  
   arr_force_optin['user_campaign_phone']=$("#"+li_id+" select[name=user_campeigns] option:selected").attr('title');  
 //console.log("SMS checked")     
 }
var add_close=$("#add_close_"+widget_no).is(":checked");
//widget_json='{"redeem_label" : "'+redeem_label+'","redeem_img_type" : "'+redeem_img_type+'","redeem_img_name" : "'+redeem_img_name+'","redeem_img_url" : "'+redeem_img_url+'","redeem_button_type" : "'+redeem_button_type+'","redeem_button_image" : "'+redeem_button_image+'","redeem_button_template" : "'+redeem_button_template+'","redeem_color" : "'+redeem_color+'","redeem_bg_color" : "'+redeem_bg_color+'","redeem_hover_color" : "'+redeem_hover_color+'","redeem_page_url" : "'+redeem_page_url+'","check": "'+check+'","redeem_once": "'+redeem_once+'","redeem_text": "'+redeem_text+'","redeem_prompt": "'+redeem_prompt+'","refresh_period": "'+refresh_period+'","force_optin_case": "'+force_optin_case+'","force_optin":'+JSON.stringify(arr_force_optin)+',"is_empty": "'+is_empty+'","add_close": "'+add_close+'","force_optin_check": "'+force_optin_check+'","no_border": "'+no_border+'","transparent_bg": "'+transparent_bg+'","font_size": "'+font_size+'"}'; 
widget_json['redeem_label']=redeem_label;   
widget_json['redeem_img_type']=redeem_img_type;   
widget_json['redeem_img_name']=redeem_img_name;   
widget_json['redeem_img_url']=redeem_img_url;   
widget_json['redeem_button_type']=redeem_button_type;   
widget_json['redeem_button_image']=redeem_button_image;   
widget_json['redeem_button_template']=redeem_button_template;   
widget_json['redeem_color']=redeem_color;   
widget_json['redeem_bg_color']=redeem_bg_color;   
widget_json['redeem_hover_color']=redeem_hover_color;   
widget_json['redeem_page_url']=redeem_page_url;   
widget_json['check']=check;   
widget_json['redeem_once']=redeem_once;   
widget_json['redeem_text']=redeem_text;   
widget_json['redeem_prompt']=redeem_prompt;   
widget_json['refresh_period']=refresh_period;   
widget_json['force_optin_case']=force_optin_case;   
widget_json['force_optin']=arr_force_optin;   
widget_json['is_empty']=is_empty;   
widget_json['add_close']=add_close;   
widget_json['force_optin_check']=force_optin_check;   
widget_json['no_border']=no_border;   
widget_json['transparent_bg']=transparent_bg;   
widget_json['font_size']=font_size;   
               ////////////////////////refresh rate for mysql table
               if(refresh_rate == "")
      refresh_rate=refresh_period;
      if(red_page_id == "")
          red_page_id=redeem_page_url;
          if(redeem_one == "")
          redeem_one=redeem_once;
            };break;
            case "button":{
                 var check=$("#"+li_id+" .check_widget").attr('src');
                        var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
                 var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
                var button_bg_color=$("#"+li_id+" input[name=simple_button_bg_color]").val();
                var button_hover_color=$("#"+li_id+" input[name=simple_button_hover_color]").val();
                var font_color=$("#"+li_id+" input[name=font_color]").val();
                var button_label=$("#"+li_id+" input[name=simple_button_label_"+widget_no+"]").val();
               var button_url=$("#"+li_id+" input[name=simple_button_url_"+widget_no+"]").val();
               var simple_button_image=$("#"+li_id+" #button_thumb_"+widget_no).attr('src');
      simple_button_image=simple_button_image.substring(1,simple_button_image.indexOf("?"));
      simple_button_image=simple_button_image.substring(simple_button_image.lastIndexOf("/")+1);
       var simple_button_type=$("#"+li_id+" select[name=simple_button_type]").val();
       var text_alignment=$("#"+li_id+" select[name=text_alignment]").val();
                var buttons=new Array();
            $("#"+li_id+" #buttons table").each(function(i){
                 var table={};
           var table_id=this.id;
            // console.log("table id==="+table_id);
           var button_label=$("#"+li_id+" #"+table_id+" input[name=simple_button_label]").val(); 
           var button_url=$("#"+li_id+" #"+table_id+" input[name=simple_button_url]").val();
           table['button_label']=button_label;
           table['button_url']=button_url;
           buttons[i]=table;
          // console.log("table id==="+button_label+"butt"+button_url);
         ///  table+=',{"button_label" : "'+button_label+'","button_url" : "'+button_url+'"}'; 
 
            });
             // console.log(buttons);
          //  table=table.substr(1);
              //    widget_json='{"button_bg_color" : "'+button_bg_color+'","simple_button_type" : "'+simple_button_type+'","font_color" : "'+font_color+'","simple_button_image" : "'+simple_button_image+'","check": "'+check+'","no_border": "'+no_border+'","transparent_bg": "'+transparent_bg+'","button_hover_color" : "'+button_hover_color+'","text_alignment" : "'+text_alignment+'","buttons": ['+table+'"]}';
                  widget_json['button_bg_color']=button_bg_color;
                  widget_json['simple_button_type']=simple_button_type;
                  widget_json['font_color']=font_color;
                  widget_json['simple_button_image']=simple_button_image;
                  widget_json['check']=check;
                  widget_json['no_border']=no_border;
                  widget_json['transparent_bg']=transparent_bg;
                  widget_json['button_hover_color']=button_hover_color;
                  widget_json['text_alignment']=text_alignment;
                  widget_json['buttons']=buttons;
            };break;
                  case "icons":{
                       var check=$("#"+li_id+" .check_widget").attr('src');
                    var fb=$("#"+li_id+" input[name=facebook]").is(":checked");
                    var fb_app_id=$("#"+li_id+" input[name=fb_app_id]").val();
                    var fb_title=$("#"+li_id+" input[name=title]").val();
                    var fb_caption=$("#"+li_id+" input[name=caption]").val();
                    var fb_desc=$("#"+li_id+" textarea[name=description]").val();
					var fb_button_template=$("#"+li_id+" select[name=fb_button_template]").val();
               ////////////////////////////icons image/////////////////////////
                   var fb_radio=$("#"+li_id+" input:radio[name=slider_icons_"+widget_no+"]:checked").val();
             var icons_img_type="none";
               if(fb_radio == "icons_image")
               { icons_img_type="image";
                 }
               else   if(fb_radio == "icons_img_url")
               {
                   icons_img_type="url";
               }
////////////////////////////////image name               
var icons_image=$("#"+li_id+" #icons_thumb_"+widget_no).attr('src');
icons_image=icons_image.substring(1,icons_image.indexOf("?"));
    img_arr=icons_image.split("/");
   icons_img_name=img_arr[img_arr.length-1]; 
   var icons_img_url=$("#"+li_id+" input[name=icons_img_url]").val();
   /////////////////////////////////////////////////////////////////////////////////////     
            var twitter=$("#"+li_id+" input[name=twitter]").is(":checked")  
           var email=$("#"+li_id+" input[name=email]").is(":checked") 
                var email_subject=$("#"+li_id+" input[name=email_link_url]").val();
                var sms=$("#"+li_id+" input[name=send_sms]").is(":checked");  
              var share_text=$("#"+li_id+" textarea[name=share_text]").val();
              var after_share_url=$("#"+li_id+" select[name=after_share_url]").val();
              var text_alignment=$("#"+li_id+" select[name=text_alignment]").val();
              var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
              var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
               //   widget_json='{"fb" : "'+fb+'","twitter" : "'+twitter+'","email": "'+email+'","email_subject": "'+email_subject+'","sms" : "'+sms+'","share_text" : "'+share_text+'","after_share_url" : "'+after_share_url+'","check": "'+check+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'","text_alignment" : "'+text_alignment+'"}';
          widget_json['fb']=fb;
          widget_json['fb_app_id']=fb_app_id;
          widget_json['fb_title']=fb_title;
          widget_json['fb_caption']=fb_caption;
		  widget_json['fb_button_template']=fb_button_template;
          widget_json['fb_desc']=fb_desc;
          widget_json['icons_img_type']=icons_img_type;
          widget_json['icons_img_name']=icons_img_name;
          widget_json['icons_img_url']=icons_img_url;
          widget_json['twitter']=twitter;
          widget_json['email']=email;
          widget_json['email_subject']=email_subject;
          widget_json['sms']=sms;
          widget_json['share_text']=share_text;
          widget_json['after_share_url']=after_share_url;
          widget_json['check']=check;
          widget_json['transparent_bg']=transparent_bg;
          widget_json['no_border']=no_border;
          widget_json['text_alignment']=text_alignment;
                  };break;
                        case "call":{
                             var check=$("#"+li_id+" .check_widget").attr('src');
                var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
                 var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
                var call_lable=$("#"+li_id+" input[name=call_button_lable]").val();
                var call_number=$("#"+li_id+" input[name=call_button_number]").val();
            var call_bg_color=$("#"+li_id+" input[name=call_button_bg_color]").val();             
              var call_hover_color=$("#"+li_id+" input[name=call_hover_color]").val();             
              var fonts_size=$("#"+li_id+" input[name=fonts_size]").val();             
              var fonts_color=$("#"+li_id+" input[name=fonts_color]").val();             
                          
                ///  widget_json='{"call_lable" : "'+call_lable+'","call_number" : "'+call_number+'","call_bg_color": "'+call_bg_color+'","check": "'+check+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'","call_hover_color" : "'+call_hover_color+'","fonts_size" : "'+fonts_size+'","fonts_color" : "'+fonts_color+'"}';
                  widget_json['call_lable']=call_lable;
                  widget_json['call_number']=call_number;
                  widget_json['call_bg_color']=call_bg_color;
                  widget_json['check']=check;
                  widget_json['transparent_bg']=transparent_bg;
                  widget_json['no_border']=no_border;
                  widget_json['call_hover_color']=call_hover_color;
                  widget_json['fonts_size']=fonts_size;
                  widget_json['fonts_color']=fonts_color;
            };break;
                        case "timer":{
                             var check=$("#"+li_id+" .check_widget").attr('src');
                      var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
                 var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
           var start_time=$("#"+li_id+" input[name=start_time]").val();
           var end_time=$("#"+li_id+" input[name=end_time]").val();
           var timer_text=$("#"+li_id+" textarea[name=timer_text]").val();
                var page_opacity=$("#"+li_id+" input[name=tdiv_opacity]").val();
                var tfont_family=$("#"+li_id+" select[name=tfont_family]").val();
                var tfont_size=$("#"+li_id+" input[name=tfont_size]").val();
                var tfont_color=$("#"+li_id+" input[name=tfont_color]").val();
           /// widget_json='{"start_time" : "'+start_time+'","end_time" : "'+end_time+'","timer_text" : "'+timer_text+'","page_opacity" : "'+tdiv_opacity+'","tfont_family" : "'+tfont_family+'","tfont_size" : "'+tfont_size+'","tfont_color" : "'+tfont_color+'","check": "'+check+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'"}';
            widget_json['start_time']=start_time;
            widget_json['end_time']=end_time;
            widget_json['timer_text']=timer_text;
            widget_json['page_opacity']=page_opacity;
            widget_json['tfont_family']=tfont_family;
            widget_json['tfont_size']=tfont_size;
            widget_json['tfont_color']=tfont_color;
            widget_json['check']=check;
            widget_json['transparent_bg']=transparent_bg;
            widget_json['no_border']=no_border;
            };break;
                        case "cart":{
            var check=$("#"+li_id+" .check_widget").attr('src');
            var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
            var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
           var cart_lable=$("#"+li_id+" input[name=cart_lable]").val();
           var font_color=$("#"+li_id+" input[name=font_color]").val();
           var font_size=$("#"+li_id+" input[name=font_size]").val();
           var cart_url=$("#"+li_id+" input[name=cart_url]").val();
          var button_cart_template=$("#"+li_id+" select[name=cart_button_template]").val(); 
           button_cart_template=button_cart_template.split("++");
           button_cart_template=button_cart_template[0];
           var cart_button_type=$("#"+li_id+" select[name=choose_button_type_"+widget_no+"]").val(); 
           var is_empty=$("#"+li_id+" input[name=is_empty]").val(); 
      var cart_image=$("#"+li_id+" #cart_button_thumb_"+widget_no).attr('src');
cart_image=cart_image.substring(1,cart_image.indexOf("?"));
    img_arr=cart_image.split("/");
   cart_image=img_arr[img_arr.length-1]; 
      //   widget_json='{"cart_lable" : "'+cart_lable+'","cart_button_type" : "'+cart_button_type+'","cart_button_image" : "'+cart_image+'","cart_button_template" : "'+button_cart_template+'","cart_url" : "'+cart_url+'","is_empty": "'+is_empty+'","check": "'+check+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'","font_color" : "'+font_color+'","font_size" : "'+font_size+'"}';
       widget_json['cart_lable']=cart_lable;   
       widget_json['cart_button_type']=cart_button_type;   
       widget_json['cart_button_image']=cart_image;   
       widget_json['cart_button_template']=button_cart_template;   
       widget_json['cart_url']=cart_url;   
       widget_json['is_empty']=is_empty;   
       widget_json['check']=check;   
       widget_json['transparent_bg']=transparent_bg;   
       widget_json['no_border']=no_border;   
       widget_json['font_color']=font_color;   
       widget_json['font_size']=font_size;   
                        
                        };break;
                         case "scarcity":{
                              var check=$("#"+li_id+" .check_widget").attr('src');
                var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
                 var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
           var red_limit=$("#"+li_id+" input[name=red_limit]").val();
           var red_text=$("#"+li_id+" textarea[name=red_text]").val();
           var red_font_size=$("#"+li_id+" input[name=red_font_size]").val();
           var red_font_color=$("#"+li_id+" input[name=red_font_color]").val();
         
           var text_alignment=$("#"+li_id+" select[name=text_alignment]").val();
           var radio=$("#"+li_id+" input:radio[name=slider_scarcity_"+widget_no+"]:checked").val();
         var red_image=$("#"+li_id+" #red_thumb_"+widget_no).attr('src');
      red_image=red_image.substring(1,red_image.indexOf("?"));
    img_arr=red_image.split("/");
    red_image=img_arr[img_arr.length-1]; 
     var red_bg_color=$("#"+li_id+" input[name=red_bg_color]").val();
    
    /// widget_json='{"red_limit" : "'+red_limit+'","check": "'+check+'","red_text": "'+red_text+'","red_font_size": "'+red_font_size+'","red_font_color": "'+red_font_color+'","red_bg_image": "'+red_image+'","red_bg_color": "'+red_bg_color+'","text_alignment": "'+red_alignment+'","red_bg_type": "'+radio+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'"}';  
  widget_json['red_limit']=red_limit; 
  widget_json['check']=check; 
  widget_json['red_text']=red_text; 
  widget_json['red_font_size']=red_font_size; 
  widget_json['red_font_color']=red_font_color; 
  widget_json['red_bg_image']=red_image; 
  widget_json['red_bg_color']=red_bg_color; 
  widget_json['text_alignment']=text_alignment; 
  widget_json['red_bg_type']=radio; 
  widget_json['transparent_bg']=transparent_bg; 
  widget_json['no_border']=no_border; 
  //////////////redeem limit for mysql table
  if(redeem_limit == "")
  redeem_limit=red_limit;
            };break;
                  case "facebook":{
                       var check=$("#"+li_id+" .check_widget").attr('src');
                  var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
                 var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
        // var page_url=$("#"+li_id+" input[name=page_url]").val();
           var posts=$("#"+li_id+" input[name=posts]").val();
        //  widget_json='{"posts" : "'+posts+'","check": "'+check+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'"}';
          widget_json['posts']=posts; 
          widget_json['check']=check; 
          widget_json['transparent_bg']=transparent_bg; 
          widget_json['no_border']=no_border; 
                  };break;
          case "map":{  
           var check=$("#"+li_id+" .check_widget").attr('src'); 
                 var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
                 var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
				 var get_direction=$("#"+li_id+" input[name=get_direction]").is(":checked");
         var address=$("#"+li_id+" textarea[name=address]").val();
           var lat=$("#"+li_id+" input[name=lat]").val();
           var lon=$("#"+li_id+" input[name=lon]").val();
           var zoom=$("#"+li_id+" input[name=zoom]").val();
         ///   widget_json='{"address" : "'+address+'","lat" : "'+lat+'","lon" : "'+lon+'","check": "'+check+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'","zoom" : "'+zoom+'","get_direction" : "'+get_direction+'"}';
       widget_json['address']=address;     
       widget_json['lat']=lat;     
       widget_json['lon']=lon;     
       widget_json['check']=check;     
       widget_json['transparent_bg']=transparent_bg;     
       widget_json['no_border']=no_border;     
       widget_json['zoom']=zoom;     
       widget_json['get_direction']=get_direction;     
          };break;
        case "twitter":{
            var check=$("#"+li_id+" .check_widget").attr('src');
            var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
            var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
            var username=$("#"+li_id+" input[name=username]").val();
            var tweets=$("#"+li_id+" input[name=tweets]").val();
            var t_fcolor=$("#"+li_id+" input[name=t_fcolor]").val();
            var t_fsize=$("#"+li_id+" input[name=t_fsize]").val();
            var t_bcolor=$("#"+li_id+" input[name=t_bcolor]").val();
            // widget_json='{"username" : "'+username+'","tweets" : "'+tweets+'","check": "'+check+'","t_fcolor": "'+t_fcolor+'","t_fsize": "'+t_fsize+'","t_bcolor": "'+t_bcolor+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'"}';
            widget_json['username']=username;  
            widget_json['tweets']=tweets;  
            widget_json['check']=check;  
            widget_json['t_fcolor']=t_fcolor;  
            widget_json['t_fsize']=t_fsize;  
            widget_json['t_bcolor']=t_bcolor;  
            widget_json['transparent_bg']=transparent_bg;  
            widget_json['no_border']=no_border;  
        };break;
        
        case "webform":{
            var check=$("#"+li_id+" .check_widget").attr('src');
            var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
            var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
            var name=$("#"+li_id+" input[name=name]").val();
            var email=$("#"+li_id+" input[name=email]").val();
            var number=$("#"+li_id+" input[name=number]").val();
            var birthday=$("#"+li_id+" input[name=birthday]").val();
            var anniversary=$("#"+li_id+" input[name=anniversary]").val();
            var heading=$("#"+li_id+" textarea[name=heading]").val();
            var group_id=$("#"+li_id+" select[name=group_id]").val();
            
            
            // widget_json='{"username" : "'+username+'","tweets" : "'+tweets+'","check": "'+check+'","t_fcolor": "'+t_fcolor+'","t_fsize": "'+t_fsize+'","t_bcolor": "'+t_bcolor+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'"}';
            widget_json['name']=name;  
            widget_json['email']=email;  
            widget_json['number']=number;
            widget_json['check']=check;
            widget_json['birthday']=birthday;
            widget_json['anniversary']=anniversary;
            widget_json['heading']=heading; 
            widget_json['group_id'] = group_id;
            widget_json['transparent_bg']=transparent_bg;  
            widget_json['no_border']=no_border;  
        };break;
        
                   case "fonts":{
                        var check=$("#"+li_id+" .check_widget").attr('src');
            var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
                 var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
              var font_color=$("#"+li_id+" input[name=font_color]").val();     
              var font_size=$("#"+li_id+" input[name=font_size]").val();     
              var font_text=$("#"+li_id+" textarea[name=font_text]").val();  
              var google_font=$("#"+li_id+" select[name=google_fonts]").val();
              var text_alignment=$("#"+li_id+" select[name=text_alignment]").val();
                var font_bg_color=$("#"+li_id+" input[name=font_bg_color]").val();
                   var font_image=$("#"+li_id+" #font_thumb_"+widget_no).attr('src');
      font_image=font_image.substring(1,font_image.indexOf("?"));
    img_arr=font_image.split("/");
    font_image=img_arr[img_arr.length-1]; 
                  var radio=$("#"+li_id+" input:radio[name=slider_fonts_"+widget_no+"]:checked").val();
  ///widget_json='{"google_font" : "'+google_font+'","font_color" : "'+font_color+'","font_text" : "'+font_text+'","text_alignment" : "'+text_alignment+'","font_size" : "'+font_size+'","font_bg_image" : "'+font_image+'","font_bg_color" : "'+font_bg_color+'","font_bg_type" : "'+radio+'","check": "'+check+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'"}';
                     widget_json['google_font']=google_font;                        
                     widget_json['font_color']=font_color;                        
                     widget_json['font_text']=font_text;                        
                     widget_json['text_alignment']=text_alignment;                        
                     widget_json['font_size']=font_size;                        
                     widget_json['font_bg_image']=font_image;                        
                     widget_json['font_bg_color']=font_bg_color;                        
                     widget_json['font_bg_type']=radio;                        
                     widget_json['check']=check;                        
                     widget_json['transparent_bg']=transparent_bg;                        
                     widget_json['no_border']=no_border;  					  
            };break;
	case "loyalty":{
          var check=$("#"+li_id+" .check_widget").attr('src');
              var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
                 var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
        var loyalty_bg_type= $("#"+li_id+" input[name=loyalty_bg_"+widget_no+"]:checked").val(); 
 var loyalty_image=$("#"+li_id+" #loyalty_thumb_"+widget_no).attr('src');		  
      loyalty_image=loyalty_image.substring(loyalty_image.lastIndexOf("/"),loyalty_image.indexOf("?"));
var loyalty_bgcolor=$("#"+li_id+" input[name=loyalty_bgcolor]").val();
var loyalty_font_color=$("#"+li_id+" input[name=loyalty_font_color]").val();
var loyalty_font_size=$("#"+li_id+" input[name=loyalty_font_size]").val();
var text_alignment=$("#"+li_id+" select[name=text_alignment]").val();
var text_above_code=$("#"+li_id+" textarea[name=text_above_code]").val();
var codes_required=$("#"+li_id+" input[name=codes_required]").val();
var invalid_prompt=$("#"+li_id+" textarea[name=invalid_prompt]").val();
var looser_prompt=$("#"+li_id+" textarea[name=looser_prompt]").val();
var reward_page=$("#"+li_id+" select[name=reward_page]").val();
var keywords_data=new Array();
$.each($("#"+li_id+" .keywords_data"),function(k,v){
    var keywords_temp={};
  //  keywords_temp['date']=$(v).children("td:first-child").text().trim();    
  //  keywords_temp['keyword']=$(v).children("td:nth-child(2)").text().trim();    
/////console.log(k+"----------"+$(v).children("td:first-child").html()); 
keywords_temp[$(v).children("td:first-child").text().trim()]=$(v).children("td:nth-child(2)").text().trim();
keywords_data[k]=keywords_temp;  
});
//keywords_data=JSON.stringify(keywords_data);
//widget_json='{"loyalty_bg_type": "'+loyalty_bg_type+'","loyalty_bgcolor": "'+loyalty_bgcolor+'","loyalty_font_color": "'+loyalty_font_color+'","loyalty_font_size": "'+loyalty_font_size+'","text_alignment": "'+text_alignment+'","text_above_code": "'+text_above_code+'","invalid_prompt": "'+invalid_prompt+'","looser_prompt": "'+looser_prompt+'","reward_page": "'+reward_page+'","loyalty_image": "'+loyalty_image+'","codes_required": "'+codes_required+'","keywords_data": '+keywords_data+',"loyalty_bgcolor": "'+loyalty_bgcolor+'","check": "'+check+'","transparent_bg" : "'+transparent_bg+'","no_border" : "'+no_border+'"}';
//widget_json='{"check": "'+check+'"}';
widget_json['loyalty_bg_type']=loyalty_bg_type;  
widget_json['loyalty_bgcolor']=loyalty_bgcolor;  
widget_json['loyalty_font_color']=loyalty_font_color;  
widget_json['loyalty_font_size']=loyalty_font_size;  
widget_json['text_alignment']=text_alignment;  
widget_json['text_above_code']=text_above_code;  
widget_json['invalid_prompt']=invalid_prompt;  
widget_json['looser_prompt']=looser_prompt;  
widget_json['reward_page']=reward_page;  
widget_json['loyalty_image']=loyalty_image;  
widget_json['codes_required']=codes_required;  
widget_json['keywords_data']=keywords_data;  
widget_json['loyalty_bgcolor']=loyalty_bgcolor;  
widget_json['check']=check;  
widget_json['transparent_bg']=transparent_bg;  
widget_json['no_border']=no_border;  
            };break;
             case "scratch":{
                   var check=$("#"+li_id+" .check_widget").attr('src');
                    var no_border=$("#"+li_id+" input[name=no_border]").is(":checked");
                 var transparent_bg=$("#"+li_id+" input[name=transparent_bg]").is(":checked");
				 var orig_dimensions=$("#"+li_id+" input[name=orig_dimensions]").is(":checked");
                   var text_alignment=$("#"+li_id+" select[name=text_alignment]").val();
                   var scratch_page=$("#"+li_id+" select[name=scratch_page]").val();
                 var scratch_bg_radio=$("#"+li_id+" input:radio[name=slider_scratch_"+widget_no+"]:checked").val();
                 //console.log(scratch_bg_radio);
             var scratch_bg_type="none";
             var scratch_bg_image="";
               if(scratch_bg_radio == "scratch_bg_image")
               { scratch_bg_type="image";
    
         }
               else   if(scratch_bg_radio == "scratch_bg_url")
               {
                   scratch_bg_type="url";
  
               }
////////////////////////////////image name               
var scratch_bg_image=$("#"+li_id+" #scratch_bg_thumb_"+widget_no).attr('src');
//console.log(scratch_bg_image);
scratch_bg_image=scratch_bg_image.substring(1,scratch_bg_image.indexOf("?"));
    img_arr=scratch_bg_image.split("/");
   scratch_bg_image=img_arr[img_arr.length-1]; 
   var scratch_bg_url=$("#"+li_id+" input[name=scratch_bg_url]").val();
   //////////////////////////////////scratch fg
   var scratch_fg_radio=$("#"+li_id+" input:radio[name=scratch_fg_"+widget_no+"]:checked").val();
                 //console.log(scratch_fg_radio);
             var scratch_fg_type="none";
             var scratch_fg_image="";
               if(scratch_fg_radio == "scratch_fg_image")
               { scratch_fg_type="image";
    
         }
               else   if(scratch_fg_radio == "scratch_fg_url")
               {
                   scratch_fg_type="url";
  
               }
////////////////////////////////image name               
var scratch_fg_image=$("#"+li_id+" #scratch_fg_thumb_"+widget_no).attr('src');
//console.log(scratch_fg_image);
scratch_fg_image=scratch_fg_image.substring(1,scratch_fg_image.indexOf("?"));
    img_arr=scratch_fg_image.split("/");
   scratch_fg_image=img_arr[img_arr.length-1]; 
   var scratch_fg_url=$("#"+li_id+" input[name=scratch_fg_url]").val();
   var reveal_radius=$("#"+li_id+" input[name=reveal_radius]").val();
   var auto_show_after=$("#"+li_id+" input[name=auto_show_after]").val();
   //////////////////////////////widget bg


   
          var widget_bg_type= $("#"+li_id+" input[name=scratch_bg_"+widget_no+"]:checked").val(); 
var scratch_image=$("#"+li_id+" #scratch_thumb_"+widget_no).attr('src');
scratch_image=scratch_image.substring(1,scratch_image.indexOf("?"));
    img_arr=scratch_image.split("/");
   scratch_image=img_arr[img_arr.length-1]; 
    var scratch_bgcolor=$("#"+li_id+" input[name=scratch_bgcolor]").val();
              ///widget_json='{"scratch_bg_type" : "'+scratch_bg_type+'","check": "'+check+'","no_border": "'+no_border+'","transparent_bg": "'+transparent_bg+'","scratch_bg_image": "'+scratch_bg_image+'","scratch_bg_url": "'+scratch_bg_url+'","scratch_fg_image": "'+scratch_fg_image+'","scratch_fg_url": "'+scratch_fg_url+'","scratch_fg_type" : "'+scratch_fg_type+'","text_alignment" : "'+text_alignment+'"}';
                 //console.log(widget_json);
                 widget_json['scratch_bg_type']=scratch_bg_type; 
                 widget_json['check']=check; 
                 widget_json['no_border']=no_border; 
                 widget_json['transparent_bg']=transparent_bg;
				 widget_json['orig_dimensions']=orig_dimensions; 
                 widget_json['scratch_bg_image']=scratch_bg_image; 
                 widget_json['scratch_bg_url']=scratch_bg_url; 
                 widget_json['scratch_fg_image']=scratch_fg_image; 
                 widget_json['scratch_fg_url']=scratch_fg_url; 
                 widget_json['scratch_fg_type']=scratch_fg_type; 
                 widget_json['scratch_page']=scratch_page; 
                 widget_json['text_alignment']=text_alignment; 
                 widget_json['reveal_radius']=reveal_radius; 
                 widget_json['auto_show_after']=auto_show_after; 
                 widget_json['widget_bg_type']=widget_bg_type; 
                 widget_json['scratch_image']=scratch_image; 
                 widget_json['scratch_bgcolor']=scratch_bgcolor; 
           ///  console.log(widget_json);
            };break;
        } 
      data[li_id]=widget_json;  
     });
   //  //console.log(data);
  ///  var testt=JSON.stringify(data);
 $.post("save_page.php",{json: data,page_id: page_id,page_key: page_key,redeem_limit: redeem_limit,refresh_rate: refresh_rate,page_title:page_title,redeem_page_id: red_page_id,redeem_once: redeem_one},function(res){
    var obj=JSON.parse(res);
    if(obj.id!=""){
         window.location.href="?id="+obj.id;
    }
    $("#light_box").css({'display' : 'none'});
    alert(obj.msg);
    
});
  }
 }
 );
});
          
     function toggle_main(id){
  $("#"+id).slideToggle();  
}       
 function make_doggle(obj) {
$(obj).parent("div").next("div").slideToggle('slow');
}
function make_doggle_main(id) {
$("#"+id).slideToggle('slow');
} 
function fb_url(div_id,obj) 
{ 
    var name=  $(obj).attr('name');
        if($(obj).is(":checked") == true)
      {$("#"+div_id+" #"+name).show(); 

      } 
            else
     { $("#"+div_id+" #"+name).hide();
     }
}
function show_hide_header_img_video(slider_area,obj) {
     var radio=obj.value; 
 // var radio=$("#"+slider_area+" input:radio[name="+slider_area+"]:checked").val();
  switch(radio){
      case "header_image":{
              $("#"+slider_area+" #header_video").hide();
      $("#"+slider_area+" #header_image").show();
      };break;
      case "header_video":{
           $("#"+slider_area+" #header_video").show();
      $("#"+slider_area+" #header_image").hide();
      };break;
         case "image_upload":{
           $("#"+slider_area+" #image_upload").show();
      $("#"+slider_area+" #image_url").hide();
      };break;
         case "image_url":{
           $("#"+slider_area+" #image_url").show();
      $("#"+slider_area+" #image_upload").hide();
      };break;
             case "video_upload":{
           $("#"+slider_area+" #video_upload").show();
      $("#"+slider_area+" #video_url").hide();
      };break;
         case "video_url":{
           $("#"+slider_area+" #video_url").show();
      $("#"+slider_area+" #video_upload").hide();
      };break;
      case "red_image":{
                  $("#"+slider_area+" #red_image").show();
      $("#"+slider_area+" #red_color").hide();
      };break;
      case "red_color": {
                 $("#"+slider_area+" #red_color").show();
      $("#"+slider_area+" #red_image").hide(); 
      };break;
         case "redeem_image":{
                  $("#"+slider_area+" #redeem_image").show();
      $("#"+slider_area+" #redeem_img_url").hide();
      };break;
      case "redeem_img_url": {
                 $("#"+slider_area+" #redeem_img_url").show();
      $("#"+slider_area+" #redeem_image").hide(); 
      };break;
               case "icons_image":{
                  $("#"+slider_area+" #icons_image").show();
      $("#"+slider_area+" #icons_img_url").hide();
      };break;
      case "icons_img_url": {
                 $("#"+slider_area+" #icons_img_url").show();
      $("#"+slider_area+" #icons_image").hide(); 
      };break;
             case "font_image":{
                  $("#"+slider_area+" #font_image").show();
      $("#"+slider_area+" #font_color").hide();
      };break;
      case "font_color": {
                 $("#"+slider_area+" #font_color").show();
      $("#"+slider_area+" #font_image").hide(); 
      };break;
               case "button_template":{
                  $("#"+slider_area+" #button_template").show();
      $("#"+slider_area+" #button_image").hide();
      };break;
      case "button_image": {
                 $("#"+slider_area+" #button_image").show();
      $("#"+slider_area+" #button_template").hide(); 
      };break;
	          case "loyalty_image":{
                  $("#"+slider_area+" #loyalty_image").show();
      $("#"+slider_area+" #loyalty_bgcolor").hide();
      };break;
      case "loyalty_bgcolor": {
                 $("#"+slider_area+" #loyalty_bgcolor").show();
      $("#"+slider_area+" #loyalty_image").hide(); 
      };break;    
        case "scratch_image":{
                  $("#"+slider_area+" #scratch_image").show();
      $("#"+slider_area+" #scratch_bgcolor").hide();
      };break;
      case "scratch_bgcolor": {
                 $("#"+slider_area+" #scratch_bgcolor").show();
      $("#"+slider_area+" #scratch_image").hide(); 
      };break;
	           case "scratch_bg_image":{
                  $("#"+slider_area+" #scratch_bg_image").show();
      $("#"+slider_area+" #scratch_bg_url").hide();
      };break;
      case "scratch_bg_url": {
                 $("#"+slider_area+" #scratch_bg_url").show();
      $("#"+slider_area+" #scratch_bg_image").hide(); 
      };break;         
        case "scratch_fg_image":{
        
                  $("#"+slider_area+" #scratch_fg_image").show();
      $("#"+slider_area+" #scratch_fg_url").hide();
      };break;
      case "scratch_fg_url": {
                 $("#"+slider_area+" #scratch_fg_url").show();
      $("#"+slider_area+" #scratch_fg_image").hide(); 
      };break;
      default:{
      alert(radio);    
      };break;
  }
} 
function show_hide_option(li_id,hide_class,obj)
{
var option=obj.value;
$("#"+li_id+" ."+hide_class).hide();    
$("#"+li_id+" #"+option).show();    
}
function get_template(widget){
    var ret="";
     select="test";
switch(widget){
    case"header":
ret='<div id="slider_header_'+count+'" class="slider_area" align="center"> <table width="100%" border="0"> <tr> <td align="center"> BG Image : <input  name="slider_header_'+count+'" onclick="show_hide_header_img_video(\'slider_header_'+count+'\',this)" type="radio" value="header_image" /> BG Video : <input  name="slider_header_'+count+'" onclick="show_hide_header_img_video(\'slider_header_'+count+'\',this)" type="radio" value="header_video"/> </td> </tr> <tr id="header_image" style="display: none"> <td> <table ><tr><td>Upload Image:<input type="radio" name="image_choice_'+count+'" value="image_upload" onclick="show_hide_header_img_video(\'slider_header_'+count+'\',this)" /> Image URL: <input type="radio" name="image_choice_'+count+'" value="image_url" onclick="show_hide_header_img_video(\'slider_header_'+count+'\',this)" /></td></tr> <tr id="image_upload" style="display: none;"><td> Upload Image : <form  method="POST" enctype="multipart/form-data">  <input id="slider_image_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'slider_image_'+count+'\',\'loading_'+count+'\',\'uploaded_thumb_'+count+'\',\'image\');" style="margin-top: 5px;">Upload</button> <img id="loading_'+count+'" src="load9.gif" style="display: none;"> <img id="uploaded_thumb_'+count+'" style="display: none;"/> </form> </td></tr> <tr id="image_url"  style="display: none;"><td>Enter url: <input type="text" name="image_url" /><td></td></tr> </table> </td> </tr> <tr id="header_video" style="display:none"> <td> <table><tr><td>Upload Video:<input type="radio" name="video_choice_'+count+'" value="video_upload" onclick="show_hide_header_img_video(\'slider_header_'+count+'\',this)" /> Video URL: <input type="radio" name="video_choice_'+count+'" value="video_url" onclick="show_hide_header_img_video(\'slider_header_'+count+'\',this)" /></td></tr><tr> <tr id="video_upload" style="display: none;"><td> Upload Video <form  method="POST" enctype="multipart/form-data">  <input id="slider_video_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'slider_video_'+count+'\',\'vloading_'+count+'\',\'uploaded_video_'+count+'\',\'video\');" style="margin-top: 5px;">Upload</button> <img id="vloading_'+count+'" src="load9.gif" style="display: none;"> <input type="hidden" name="hidden_video_name"  /> <img id="uploaded_video_'+count+'" style="display: none;"/> </form> </td></tr> <tr id="video_url"  style="display: none;"><td>Enter url: <input type="text" name="video_url" /><td></td></tr><tr> <td>Enter Width: <input type="text" name="video_width" class="text_feild_cam" style="width: 100px; height: 20px" /></td></tr> <tr> <td>Enter Height: <input type="text" name="video_height"  class="text_feild_cam" style="width: 100px; height: 20px"/> <div class="hint">Give height in pixels(suggested 250).Width is also in pixels(leave empty for 100%)</div> </td></tr> <tr><td>Auto Play:<input type="checkbox" name="autoplay"/>  Loop video: <input type="checkbox" name="loop"/></td></tr></table> </td> </tr><tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG: <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr> </table> </div>';
break;
     //////////////////////////////////////////////////////
  case "content":   
  //ret='<div id="slider_content_'+count+'" class="slider_area">  <table width="100%" border="0" align="center"> <tr> <td> <label for="page_content">Content<a href="javascript:void(0);" onclick="larger_view('+count+')" class="larger_view">Larger View</a><textarea name="page_content" id="page_content_'+count+'" class="text_area_cam" style="widtd:100%;"></textarea></label> </td> </tr> <tr><td>No Padding: <span id="checkbox_pos_soc"><input type="checkbox" name="no_padding"/></span></td></tr> <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG: <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr></table>   </div></li>';
  ret='<div id="slider_content_'+count+'" class="slider_area">  <table width="100%" border="0" align="center"> <tr> <td> <label for="page_content">Content<textarea name="page_content" id="page_content_'+count+'" class="text_area_cam" style="widtd:100%;"></textarea></label> </td> </tr> <tr><td>No Padding: <span id="checkbox_pos_soc"><input type="checkbox" name="no_padding"/></span></td></tr> <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr></table>   </div></li>';
  ;break;
  /////////////////////////////////////////////////////////////
case "redeem":
ret='<div id="slider_redeem_'+count+'" class="slider_area"> <table width="100%" border="0" align="center"> <tr> <td> <label for="redeem_label">Button Label <input type="text" name="redeem_label" id="redeem_label" class="text_feild_cam"  /> </label> </td> </tr><tr> <td>Font Color <input type="text" name="redeem_color" id="redeem_color" class="text_feild_cam color_picker"/> </td> </tr><tr> <td> Font Size:  <small>(PX is auto included)</small> <input type="text" name="font_size" class="text_feild_cam"  /> </td> </tr><tr> <td> <label for="redeem_bg_color">Button BG Color <input type="text" name="redeem_bg_color"  class="color_picker text_feild_cam" /></label> </td> </tr> <tr> <td> <label for="redeem_hover_color">Button HOVER Color <input type="text" name="redeem_hover_color"  class="color_picker text_feild_cam" /></label> </td> </tr> <tr><td>Refresh Period<input type="text" name="refresh_period" class="text_feild_cam"/></tr> <tr> <td> Select Page <select name="redeem_page_url"  class="text_feild_cam_select"> <option value="">Select Any</option></select> </td> <tr><td>Thank You page text<textarea class="text_area_cam" name="redeem_text"></textarea><span class="hint">You may use %refresh% to display after how many days users can get another coupon and %UPC% to display the barcode of coupon number.</span></td></tr> <tr><td>Redeem Prompt<textarea class="text_area_cam" name="redeem_prompt"></textarea></td></tr> <tr><td>Redeem Once<input type="checkbox" value="yes" name="redeem_once" /></td></tr> <tr> <td align="center"> Upload Image : <input  name="slider_redeem_'+count+'" onclick="show_hide_header_img_video(\'slider_redeem_'+count+'\',this)" type="radio" value="redeem_image" /> Image URL : <input  name="slider_redeem_'+count+'" onclick="show_hide_header_img_video(\'slider_redeem_'+count+'\',this)" type="radio" value="redeem_img_url" /> </td> </tr>  <tr id="redeem_img_url" style="display: none;"> <td> Image URL <input type="text" name="redeem_img_url" class="text_feild_cam"  /> </td> </tr> <tr id="redeem_image" style="display: none;" ><td>           Upload Image : <form  method="POST" enctype="multipart/form-data">  <input id="redeem_image_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'redeem_image_'+count+'\',\'redeem_loading_'+count+'\',\'redeem_thumb_'+count+'\',\'image\');" style="margin-top: 5px;">Upload</button> <img id="redeem_loading_'+count+'" src="load9.gif" style="display: none;"> <img id="redeem_thumb_'+count+'" src=""/> </form></td></tr> <tr><td>Redeem Button </td></tr> <tr> <td align="center"> <select name="choose_button_type_'+count+'" onchange="show_hide_option(\'slider_redeem_'+count+'\',\'hide_options\',this)" class="text_feild_cam_select">'+select+'</select> </td> </tr>  <tr id="button_template" class="hide_options" style="display: none;" > <td> Select button template <select name="redeem_button_template" onchange="show_button_template(this,\'button_redeem_'+count+'\')" class="text_feild_cam_select">'+select+'</select>   <div id="button_redeem_'+count+'" class="button_template"><img src=""/> <input type="hidden" name="is_empty"/> </div> </td> </tr> <tr id="button_image" class="hide_options" style="display: none;" ><td>   Upload Image : <form  method="POST" enctype="multipart/form-data"> <input id="redeem_button_image_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'redeem_button_image_'+count+'\',\'redeem_button_loading_'+count+'\',\'redeem_button_thumb_'+count+',\'image\');" style="margin-top: 5px;">Upload</button> <img id="redeem_button_loading_'+count+'" src="load9.gif" style="display: none;"> <img id="redeem_button_thumb_'+count+'" src=""/></form></td></tr> <tr><td>Add close button for clerk: <input type="checkbox" id="add_close_'+count+'" /></td></tr> <tr><td>Force Optin: <span id="checkbox_pos_soc"><input type="checkbox" id="force_optin_'+count+'" onclick="show_next_option(this);" /></span></td></tr> <tr style="display: none;"><td> &nbsp;  &nbsp;  &nbsp;  &nbsp;  Email:  <input type="radio" name="redeem_email_'+count+'"  onclick="show_hide_option(\'slider_redeem_'+count+'\',\'hide_force_options\',this)" value="redeem_email_'+count+'" /> &nbsp;  &nbsp;  &nbsp;  &nbsp; SMS: <input type="radio" name="redeem_email_'+count+'" value="redeem_sms_'+count+'" onclick="show_hide_option(\'slider_redeem_'+count+'\',\'hide_force_options\',this)" /> <table> <tr id="redeem_email_'+count+'" class="hide_force_options" style="display: none;"><td><select name="redeem_force_email" onchange="show_const_contact(this,\'slider_redeem_'+count+'\')" class="text_feild_cam_select"></select> <textarea id="email_code" class="text_area_cam" onblur="force_optin_form_dom(this,\'slider_redeem_'+count+'\')"></textarea> <div id="cc_form_json" style="display: none;"></div> <div id="cc_form_dom" style="display: none;"></div> <div id="get_const_contact" style="display: none;"> <table><tr><td>Email:<input type="text" name="email_const_contact" class="text_feild_cam" /></td></tr> <tr><td>password:<input type="text" name="pass_const_contact" class="text_feild_cam" /></td></tr> <tr><td>API Key:<input type="text" name="apikey_const_contact" class="text_feild_cam"  /></td></tr> <tr><td><input type="button" name="get_list_const_contact" value="Get List" onclick="get_list_const_contact(this,\'slider_redeem_'+count+'\')" /></td></tr> <tr><td id="const_contact_list"></td></tr> </table> </div> </td></tr> <tr id="redeem_sms_'+count+'" class="hide_force_options" style="display: none;" ><td>  <input type="button" value="Get Campaigns" onclick="redeem_get_campaigns(this,\'slider_redeem_'+count+'\');" style="margin:5px 0px;"/> <div id="redeem_user_campaigns"></div> </td></tr></table></td></tr> <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG: <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr> </table> </div>';
break; 
////////////////////////////////////////////
case"button":
ret='<div id="slider_button_'+count+'" class="slider_area"> <table width="100%" border="0" align="center"> <tr> <td> Button BG Color <input type="text" name="simple_button_bg_color" class="color_picker text_feild_cam"  /> </td> </tr> <tr> <td> Button HOVER Color <input type="text" name="simple_button_hover_color"  class="color_picker text_feild_cam"  /> </td> </tr> <tr> <td> Font Color: <input type="text" name="font_color" class="color_picker text_feild_cam" /> </td> </tr> <tr><td>  Upload Image : <form  method="POST" enctype="multipart/form-data"> <input id="button_image_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'button_image_'+count+'\',\'button_loading_'+count+'\',\'button_thumb_'+count+'\',\'image\');" style="margin-top: 5px;">Upload</button> <img id="button_loading_'+count+'" src="load9.gif" style="display: none;"> <img id="button_thumb_'+count+'" src="" /> </form></td></tr> <tr><td><select name="simple_button_type" class="text_feild_cam_select"> </select></td></tr> <tr> <td> <select name="text_alignment" class="text_feild_cam_select"> </select> </td> </tr><tr> <td> <div id="buttons"> <table id="inner_table_1"> <tr> <td align="center"> <strong>Simple Button 1</strong> </td> </tr> <tr> <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr><td>  Button Label <input type="text" name="simple_button_label"  class="text_feild_cam" placeholder="Button Label" /> </td></tr> <tr> <td> Button URL <input type="text" name="simple_button_url"  class="text_feild_cam"  placeholder="Button URL"/> </td> </tr></table> </div> </td> </tr>  <tr> <td align="center"> <input type="button" onclick="create_simple_button(\'button_'+count+'\')" value="Create New Button" /> </td> </tr>  </table> </div>';
break; 
////////////////////////////////////////////
case "icons":
ret='<div id="slider_icons_'+count+'" class="slider_area"> <table width="100%" border="0"> <tr> <td>FACEBOOK :<span id="checkbox_pos_soc"><input type="checkbox" name="facebook" onclick="fb_url(\'icons_'+count+'\',this)"/></span></td> </tr><tr id="facebook" style="display: none;"><td > <table width="=100%" border="0"><tr><td> Facebook App ID: <input name="fb_app_id" type="text" class="text_feild_cam" style="width: 212px;"></td></tr><tr><td> Share Title: <input name="title" type="text"  class="text_feild_cam" style="width: 212px;"></td></tr> <tr><td> Share Caption: <input name="caption" type="text"  class="text_feild_cam" style="width: 212px;"></td></tr> <tr><td> Share Description: <textarea class="text_area_cam" name="description" style="width: 212px;"></textarea></td></tr><tr id="fb_button_template"><td>Select button template <select name="fb_button_template" onchange="show_button_template(this,\'button_fb_'+count+'\')" class="text_feild_cam_select"><option value="fb_icon.png" selected="selected">Button Small 1</option><option value="small_btn2.png">Button Small 2</option><option value="small_btn3.png">Button Small 3</option><option value="medium_btn1.png">Button Medium 1</option><option value="medium_btn2.png">Button Medium 2</option><option value="medium_btn3.png">Button Medium 3</option><option value="horizontal_btn1.jpg">Horizontal Button 1</option><option value="horizontal_btn2.png">Horizontal Button 2</option><option value="horizontal_btn3.png">Horizontal Button 3</option></select> <div id="button_fb_'+count+'" class="fb_button_template" style="text-align:center"><img src="images/buttons/fb_icon.png"/> <input type="hidden" name="is_empty"/></div></td></tr> <tr><td > Upload Image :<input  name="slider_icons_'+count+'" onclick="show_hide_header_img_video(\'slider_icons_'+count+'\',this)" type="radio" value="icons_image" /> Image URL : <input  name="slider_icons_'+count+'" onclick="show_hide_header_img_video(\'slider_icons_'+count+'\',this)" type="radio" value="icons_img_url" /> </td> </tr>  <tr id="icons_img_url" style="display: none;"> <td> Image URL <input type="text" name="icons_img_url" class="text_feild_cam"  style="width: 212px;"/> </td> </tr> <tr id="icons_image" style="display: none;"><td>           Upload Image : <form  method="POST" enctype="multipart/form-data"> <input id="icons_image_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'icons_image_'+count+'\',\'icons_loading_'+count+'\',\'icons_thumb_'+count+'\',\'image\');" style="margin-top: 5px;">Upload</button> <img id="icons_loading_'+count+'" src="load9.gif" style="display: none;"> <img id="icons_thumb_'+count+'" src=""/> </form></td></tr></table></td></tr>  <tr> <td>TWITTER :<span id="checkbox_pos_soc"><input type="checkbox" name="twitter" /></span></td> </tr>  <tr> <td>EMAIL :<span id="checkbox_pos_soc"><input type="checkbox" name="email" onclick="fb_url(\'icons_'+count+'\',this)"  /></span></td> </tr> <tr id="email" style="display:none;"> <td> EMAIL Subject <input type="text" name="email_link_url"  class="text_feild_cam"  />    </td>  </tr> <tr> <td>Send SMS :<span id="checkbox_pos_soc"><input  type="checkbox"  name="send_sms" /></span></td> </tr> <tr><td>Share Text: <small>(This will share on above enabled channels)</small><textarea class="text_area_cam" name="share_text"></textarea></td></tr> <tr><td>After Share URL:  <small>(will redirect to below page)</small>    <select name="after_share_url" class="text_feild_cam_select"> <option value="">Select Any</option</select></td></tr> <tr><td>Icons alignment:<select name="text_alignment" class="text_feild_cam_select"> </select></td></tr> <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG: <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr> </table>  </div>';
break;
case "call":
ret='<div id="slider_call_'+count+'" class="slider_area"> <table width="100%" border="0" align="center"> <tr> <td> Button Label <input type="text" name="call_button_lable" class="text_feild_cam"  /> </td> </tr> <tr> <td> Calling Number <input type="text" name="call_button_number" class="text_feild_cam"  /> </td> </tr> <tr> <td> Button BG Color <input type="text" name="call_button_bg_color"  class="color_picker text_feild_cam"  /> </td> </tr><tr> <td> Button Hover Color: <input type="text" name="call_hover_color"  class="color_picker text_feild_cam"/> </td> </tr> <tr> <td> Fonts Color: <input type="text" name="fonts_color"  class="color_picker text_feild_cam" /> </td> </tr> <tr> <td> Fonts size: <input type="text" name="fonts_size"  class="text_feild_cam" /> <div class="hint">Suggetsed size is 10-22</div> </td> </tr>   <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr></table> </div>';
break;
case "timer":
ret='<div id="slider_timer_'+count+'" class="slider_area">  <table width="100%" border="0" align="center"> <tr style="display: none;"> <td> Start Time <input type="text" name="start_time"  class="text_feild_cam start_time"id="start_time_'+count+'"/> </td> </tr> <tr> <td> End Time <input type="text" name="end_time"  class="text_feild_cam end_time" id="end_time_'+count+'" /> </td> </tr> <tr> <td> Timer Text <textarea class="text_area_cam" name="timer_text"></textarea><span class="hint">You may use %timer% to display count down time</span> </td> </tr> <tr> <td> Div Opacity&nbsp;&nbsp;&nbsp;Limit : 0.1 - 1 <input type="text" name="tdiv_opacity"  class="text_feild_cam"  /> </td> </tr> <tr> <td> Font Size&nbsp;&nbsp;&nbsp;<small>PX is auto included </small><input type="text" name="tfont_size"  class="text_feild_cam"  /> </td> </tr> <tr> <td> Font Color <input type="text" name="tfont_color"  class="color_picker text_feild_cam"  /> </td> </tr>  <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr></table>   </div>';
break;
case "cart":
ret='<div id="slider_cart_'+count+'" class="slider_area"> <table width="100%" border="0" align="center"> <tr> <td> Button Label <input type="text" name="cart_lable" class="text_feild_cam"  /> </td> </tr><tr> <td> Font Color: <input type="text" name="font_color" class="color_picker text_feild_cam" /> </td> </tr> <tr> <td> Font Size:  <small>(PX is auto included)</small> <input type="text" name="font_size" class="text_feild_cam"  /> </td> </tr> <tr> <td> Landing Page Url <input type="text" name="cart_url" class="text_feild_cam"  /> </td> </tr> <tr><td>Selecct Button </td></tr> <tr> <td align="center"> <select name="choose_button_type_'+count+'" onchange="show_hide_option(\'slider_cart_'+count+'\',\'hide_options_cart\',this)" class="text_feild_cam_select"></select> </td> </tr> <tr id="button_template" style="display: none;" class="hide_options_cart"> <td> Select button template <select name="cart_button_template" onchange="show_button_template(this,\'button_cart_'+count+'\')" class="text_feild_cam_select"> <option value="">---Select Any---</option></select>   <div id="button_cart_'+count+'" class="button_template"><img src=""/> <input type="hidden" name="is_empty"/> </div> </td> </tr> <tr id="button_image" style="display: none;" class="hide_options_cart"><td>           Upload Image : <form  method="POST" enctype="multipart/form-data">  <input id="cart_button_image_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'cart_button_image_'+count+'\',\'cart_button_loading_'+count+'\',\'cart_button_thumb_'+count+'\',\'image\');" style="margin-top: 5px;">Upload</button> <img id="cart_button_loading_'+count+'" src="load9.gif" style="display: none;"> <img id="cart_button_thumb_'+count+'" src=""/> </form></td></tr> <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr></table> </div>';
break;
case "scarcity":
ret='<div id="slider_scarcity_'+count+'" class="slider_area"> <table width="100%" border="0" align="center"> <tr> <td> Redemption Limit &nbsp;&nbsp;&nbsp; i.e : 10 <input type="text" name="red_limit"  class="text_feild_cam"  /> </td> </tr> <tr> <td> Redemption text <textarea name="red_text"  class="text_area_cam"></textarea><span class="hint">You may use %redeem% to display how many redemptions are left</span> </td> </tr> <tr> <td> Font Size&nbsp;&nbsp;&nbsp;<small>PX is auto included</small> <input type="text" name="red_font_size" class="text_feild_cam"  /> </td> </tr>  <tr> <td> Font Color <input type="text" name="red_font_color" class="color_picker  text_feild_cam"   /> </td> </tr><tr><td> <select name="text_alignment" class="text_feild_cam_select"></select></td></tr><tr> <td align="center"> BG Image : <input  name="slider_scarcity_'+count+'" onclick="show_hide_header_img_video(\'slider_scarcity_'+count+'\',this)" type="radio" value="red_image"/> BG color : <input  name="slider_scarcity_'+count+'" onclick="show_hide_header_img_video(\'slider_scarcity_'+count+'\',this)" type="radio" value="red_color"/> </td> </tr> <tr id="red_color" style="display: none;"> <td> BG Color <input type="text" name="red_bg_color" class="color_picker text_feild_cam"   /> </td> </tr> <tr id="red_image" style="display: none;"><td>           Upload Image : <form  method="POST" enctype="multipart/form-data">  <input id="red_image_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'red_image_'+count+'\',\'red_loading_'+count+'\',\'red_thumb_'+count+'\',\'image\');" style="margin-top: 5px;">Upload</button> <img id="red_loading_'+count+'" src="load9.gif" style="display: none;"> <img id="red_thumb_'+count+'" src="" /> </form></td></tr> <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr> </table> </div>';
break;
case "facebook":
ret='<div id="slider_facebook_'+count+'" class="slider_area"> <table width="100%" border="0" align="center"> <tr><td><small> System will add comments plugin that lets people comment on your landing page using their Facebook account.</small></td></tr> <tr> <td> No of Comments: <input type="text" name="posts"  class="text_feild_cam"  /> </td> </tr> <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr> </table> </div>';
break;
case "map":
//ret='<div id="slider_map_'+count+'" class="slider_area"> <table width="100%" border="0" align="center"> <tr> <td> Enter Address: <textarea name="address"  maxlength="0" id="map_address_'+count+'" onfocus="load_map('+count+')" class="map_address_textarea map_address"></textarea> <div id="wrapper_'+count+'" class="google_map_wrapper"> <div id="map_div_'+count+'" calss="google_map_div"></div> <img src="images/close_button.png" id="close_map_'+count+'" class="close_map"/></div> <input type="hidden" name="lat" /> <input  type="hidden" name="lon"/> </td> </tr> </table> </div>';
ret='<div id="slider_map_'+count+'" class="slider_area"> <table width="100%" border="0" align="center"> <tr> <td> Enter Address: <small>(Enter address, then drag red marker on location to get exact address)</small> <textarea name="address"  class="text_feild_cam map_address" id="map_address_'+count+'" onfocus="load_map('+count+')" style="width: 100%; height: 45px;"></textarea><div id="wrapper_'+count+'" style="position: absolute; display: none; z-index: 1; margin-left: 0px; margin-top: 5px;"><div id="map_div_'+count+'" style="border: 5px solid #ccc; width: 550px; height: 400px; background-color: #e2e2e2; overflow: hidden;"></div><img src="images/close_button.png" style=" cursor: pointer; margin: -437px 0px 0px 540px; z-index: 2; position: absolute;" id="close_map_'+count+'" class="close_map"/></div> <input type="hidden" name="lat" /> <input  type="hidden" name="lon"/> </td> </tr><tr><td> Zoom level: <input type="text" name="zoom"  class="text_feild_cam" /><div class="hint">Zoom level range 1-16 suggested 8-14</div>  <tr><td> Add get direction: <small>(Enable to add Get Directions button on map)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="get_direction"/></span></td></tr> <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr> </table> </div>';
break;
case "twitter":
ret='<div id="slider_twitter_'+count+'" class="slider_area"> <table width="100%" border="0" align="center"> <tr> <td> Enter Username: <input name="username" type="text"  class="text_feild_cam" /> </td> </tr> <tr> <td> No of tweets: <input name="tweets"  class="text_feild_cam" type="text"   /></td> </tr> <tr><td>Select font color: <input type="text" name="t_fcolor"  class="color_picker text_feild_cam" /></td></tr> <tr><td>Select font size:  <small>(PX is auto included)</small> <input type="text" name="t_fsize"   class="text_feild_cam" /></td></tr> <tr><td>Select background color: <input type="text" name="t_bcolor"  class="color_picker text_feild_cam"/></td></tr> <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr> </table> </div>';
break;
case "webform":
ret='<div id="slider_webform_'+count+'" class="slider_area"> <table width="100%" border="0" align="center"><tr><td>Text above input fields<textarea name="heading"  class="text_feild_cam form-control" ></textarea></td></tr><tr> <td> Enter Name Label: <input name="name" type="text"  class="text_feild_cam" /> </td> </tr> <tr> <td> Enter Email Label: <input name="email" class="text_feild_cam" type="email"   /></td> </tr> <tr><td>Enter Number Label: <input type="text" name="number" class="text_feild_cam" /></td></tr> <tr><td>Enter Birthday Label:<input name="birthday"  class="text_feild_cam" type="text" /></td></tr><tr><td>Enter Anniversary Label:<input name="anniversary"  class="text_feild_cam" type="text"/></td></tr> <tr><tr><td>Campaign <select name="group_id" class="text_feild_cam" ></select></td></tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr></table> </div>';
break;
case "fonts":
ret='<div id="slider_fonts_'+count+'" class="slider_area"> <table border=0> <tr><td>Headline Text: <textarea class="text_area_cam" name="font_text"></textarea></td></tr> <tr><td>Font size:  <small>(PX is auto included)</small> <input type="text" name="font_size" class="text_feild_cam"/></td></tr> <tr><td>Font color: <input type="text" name="font_color" class="text_feild_cam color_picker"/></td></tr><tr><td>Head Text ALignment:</td></tr> <tr><td><select name="text_alignment" class="text_feild_cam_select"></select></td></tr> <tr> <td align="center"> BG Image : <input  name="slider_fonts_'+count+'" onclick="show_hide_header_img_video(\'slider_fonts_'+count+'\',this)" type="radio" value="font_image"/> BG color : <input  name="slider_fonts_'+count+'" onclick="show_hide_header_img_video(\'slider_fonts_'+count+'\',this)" type="radio" value="font_color"/> </td> </tr> <tr id="font_color" style="display: none;"> <td> BG Color <input type="text" name="font_bg_color" class="color_picker  text_feild_cam"   /> </td> </tr> <tr id="font_image" style="display: none;"><td>           Upload Image : <form  method="POST" enctype="multipart/form-data">  <input id="font_image_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'font_image_'+count+'\',\'font_loading_'+count+'\',\'font_thumb_'+count+'\',\'image\');" style="margin-top: 5px;">Upload</button> <img id="font_loading_'+count+'" src="load9.gif" style="display: none;"> <img id="font_thumb_'+count+'" src=""/> </form></td></tr>   <tr><td>Fonts Sorting:<select class="text_feild_cam_select" name="get_updated_fonts" onchange="get_updated_fonts(\'slider_fonts_'+count+'\',this);"> </td></tr><tr><td> <select class="text_feild_cam_select" name="google_fonts" onchange="show_google_font('+count+',this)"> <option value="">-----Select Any-----</option></select> <div id="font_sample_'+count+'" style="font-size: 30px; border: 1px solid #ccc; padding: 4px; border-radius: 5px; margin-top: 5px;">Sample Text</div> </td></tr> <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG: <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr></table></div>';
break;
case "loyalty":
ret='<div id="slider_loyalty_'+count+'" class="slider_area"> <table border=0> <tr> <td align="center"> BG Image : <input  name="loyalty_bg_'+count+'" onclick="show_hide_header_img_video(\'slider_loyalty_'+count+'\',this)" type="radio" value="loyalty_image" /> BG Color : <input  name="loyalty_bg_'+count+'" onclick="show_hide_header_img_video(\'slider_loyalty_'+count+'\',this)" type="radio" value="loyalty_bgcolor" /> </td> </tr>  <tr id="loyalty_bgcolor" style="display: none;"> <td>  <input type="text" name="loyalty_bgcolor" class="text_feild_cam color_picker"/> </td> </tr> <tr id="loyalty_image" style="display:none;"><td> Upload Image : <form  method="POST" enctype="multipart/form-data">  <input id="loyalty_image_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'loyalty_image_'+count+'\',\'loyalty_loading_'+count+'\',\'loyalty_thumb_'+count+'\',\'image\');" style="margin-top: 5px;">Upload</button> <img id="loyalty_loading_'+count+'" src="load9.gif" style="display: none;"> <img id="loyalty_thumb_'+count+'"  src=""/> </form></td></tr> <tr><td>Font Color: <input name="loyalty_font_color" type="text" class="text_feild_cam color_picker"  ></td></tr> <tr><td>Font Size:  <small>(PX is auto included)</small> <input name="loyalty_font_size" type="text" class="text_feild_cam"  ></td></tr> <tr><td>Text Alignment: <select name="text_alignment" class="text_feild_cam_select"> </select></td></tr> <tr><td>Text above CodeBox: <textarea name="text_above_code"  class="text_area_cam"  ></textarea></td></tr> <tr><td>Codes Required: <input type="text" name="codes_required"  class="text_feild_cam"  /></td></tr> <tr><td>Invalid Code Prompt: <textarea name="invalid_prompt"  class="text_area_cam"  ></textarea></td></tr> <tr><td>Looser Prompt: <textarea name="looser_prompt"  class="text_area_cam"  ></textarea></td></tr> <tr><td>       Winner Page: <select name="reward_page" class="text_feild_cam_select">  </select></td></tr> <tr><td><fieldset><legend>Keywords</legend><table border="0"> <tr><td>Date: </td></tr><tr><td><input type="text" id="keyword_date_'+count+'" name="keyword_date" class="text_feild_small"></td></tr> <tr><td>Keywords: </td></tr><tr><td><input type="text" name="keyword" class="text_feild_small"></td></tr> <tr><td>Keyword CSV: </td></tr><tr><td><input type="file" id="keyword_csv_'+count+'" name="keyword_csv" size="10" style="width: 100%" onchange="ajaxupload_csv(\'slider_loyalty_'+count+'\',this)"></td></tr> <tr><td><input type="button" name="add_keyword" value="Add" onclick="add_keywords(\'slider_loyalty_'+count+'\',this);"><a href="images/sample.csv" class="link">Download sample CSV</a></td></tr></table> </fieldset> <div id="keywords_list" style="max-height: 100px; overflow: auto; margin-top: 5px;"><table border="1"  cellpadding="0" cellspacing="0" style="width: 100%"> <tr><td><b>Date</b></td><td colspan="2"><b>KeyWord</b></td></tr>  </table></div> </td></tr>  <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG: <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr> </table> </div>';
break;
case "scratch":
ret='<div id="slider_scratch_'+count+'" class="slider_area" align="center"> <table width="100%" border="0"> <tr><td><strong>Background Image</strong> <small>(result image, will display after scratch)</small></td></tr> <tr> <td align="center"> Upload Image : <input  name="slider_scratch_'+count+'" onclick="show_hide_header_img_video(\'slider_scratch_'+count+'\',this)" type="radio" value="scratch_bg_image"/> Image URL : <input  name="slider_scratch_'+count+'" onclick="show_hide_header_img_video(\'slider_scratch_'+count+'\',this)" type="radio" value="scratch_bg_url" /> </td> </tr>  <tr id="scratch_bg_url" style="display: none;"> <td> Image URL <input type="text" name="scratch_bg_url" class="text_feild_cam" /> </td> </tr> <tr id="scratch_bg_image" style="display: none;"><td>Upload Image : <form  method="POST" enctype="multipart/form-data">  <input id="scratch_bg_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'scratch_bg_'+count+'\',\'scratch_bg_loading_'+count+'\',\'scratch_bg_thumb_'+count+'\',\'image\');" style="margin-top: 5px;">Upload</button> <img id="scratch_bg_loading_'+count+'" src="load9.gif" style="display: none;"> <img id="scratch_bg_thumb_'+count+'" src=""/> </form></td></tr>  <tr><td><strong>Foreground Image</strong><small>(Image to scratch)</small></td></tr> <tr> <td align="center"> Upload Image : <input  name="scratch_fg_'+count+'" onclick="show_hide_header_img_video(\'slider_scratch_'+count+'\',this)" type="radio" value="scratch_fg_image"/> Image URL : <input  name="scratch_fg_'+count+'" onclick="show_hide_header_img_video(\'slider_scratch_'+count+'\',this)" type="radio" value="scratch_fg_url"/> </td> </tr><tr id="scratch_fg_url" style="display: none;"> <td> Image URL <input type="text" name="scratch_fg_url" class="text_feild_cam" /> </td> </tr><tr id="scratch_fg_image" style="display: none;"><td> Upload Image : <form  method="POST" enctype="multipart/form-data">  <input id="scratch_fg_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'scratch_fg_'+count+'\',\'scratch_fg_loading_'+count+'\',\'scratch_fg_thumb_'+count+'\',\'image\');" style="margin-top: 5px;">Upload</button> <img id="scratch_fg_loading_'+count+'" src="load9.gif" style="display: none;"> <img id="scratch_fg_thumb_'+count+'" src=""/> </form></td></tr><tr><td><strong>Widget Backgorund</strong></td></tr><tr> <td align="center"> BG Image : <input  name="scratch_bg_'+count+'" onclick="show_hide_header_img_video(\'slider_scratch_'+count+'\',this)" type="radio" value="scratch_image" /> BG Color : <input  name="scratch_bg_'+count+'" onclick="show_hide_header_img_video(\'slider_scratch_'+count+'\',this)" type="radio" value="scratch_bgcolor" /> </td> </tr>  <tr id="scratch_bgcolor" style="display: none;"> <td>  <input type="text" name="scratch_bgcolor" class="text_feild_cam color_picker"  /> </td> </tr> <tr id="scratch_image" style="display: none;"><td>Upload Image : <form  method="POST" enctype="multipart/form-data">  <input id="scratch_image_'+count+'" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload(\'scratch_image_'+count+'\',\'scratch_loading_'+count+'\',\'scratch_thumb_'+count+'\',\'image\');" style="margin-top: 5px;">Upload</button> <img id="scratch_loading_'+count+'" src="load9.gif" style="display: none;"> <img id="scratch_thumb_'+count+'" src=""/> </form></td></tr><tr><td><b>Reveal Radius:</b></td></tr><tr><td><input type="text" name="reveal_radius" class="text_feild_cam"><div class="hint">Suggested value(15-30)</div></td></tr> <tr><td><b>Auto show after:</b> <small>(background image will immediately display when scratch reaches selected percentage)</small> </td></tr><tr><td><input type="text" name="auto_show_after" class="text_feild_cam"><div class="hint">In percentage- suggested value(80-95)</div></td></tr> <tr><td>      <b>After scratch Page:</b> <small>(after scratch on background image click, page will redirect to selected page)</small> <select name="scratch_page" class="text_feild_cam_select"> <option value="">Select Any</option> </select></td></tr> <tr><td><strong>Image Alignment:</strong><select name="text_alignment" class="text_feild_cam_select"> </select></td></tr><tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"/></span></td></tr> <tr><td>Transparent BG: <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"/></span></td></tr><tr><td>Use Original Dimensions: <span id="checkbox_pos_soc"><input type="checkbox" name="orig_dimensions"/></span></td></tr></table> </div>';break;
}
return ret;
}
<!--------ajax file upload----------->
    function ajaxFileUpload(image,loading,uploaded,select)
    {
        
        ////////////check image source
       var image_name=$("#"+uploaded).attr('src');
       if(typeof image_name !='undefined')
{ image_name=image_name.substring(0,image_name.indexOf(".jpg"));
    arr_name=image_name.split("/");
   image_name=arr_name[arr_name.length-1];
   }
   else
   image_name="";
        $("#"+loading)
        .ajaxStart(function(){
           // $(this).show();
          //  alert(loading);
        })
        .ajaxComplete(function(){
          //  $(this).hide();
          //  alert(loading);
        });
$("#"+loading).show();
        $.ajaxFileUpload
        (
            {
                url:'doajaxfileupload.php',
                secureuri:false,
                fileElementId:image,
                dataType: 'json',
                data:{select :select,page_key: page_key,page_id: page_id,image: image,image_name: image_name},
                success: function (data, status)
                {
                    if(typeof(data.error) != 'undefined')
                    {
                        if(data.error != '')
                        {
                            alert(data.error);
                        }else
                        {
                            
                      if(select == "image")     
                   {      $("#"+uploaded).attr({  
          src: "uploaded_images/thumbs/"+data.msg+".jpg?"+new Date().getTime()
          });
                   }
                   else
                   {
                     $("#"+uploaded).attr({ 
                      
          src: "images/video.png"
          });
         $("#"+loading).next("input[name=hidden_video_name]").val(data.msg);    
                   }
          $("#"+uploaded).show(); 
          $("#"+image).val("");  
                      /// alert(data.msg);
                         //   $("#slidee").slideUp(500);
                        //   refresh_image(); 
                           //refresh_image_logged();
                        }
                    }
                    $("#"+loading).hide();
                },
                error: function (data, status, e)
                {
                    alert("Error="+e);
                }
            }
        )
        
        return false;
    } 
    /////////////////////////////////
      function ajaxupload_csv(li_id,obj)
    {
        var id=$(obj).attr('id');
$("#"+id).after("<img src='images/load9.gif'>");
        $.ajaxFileUpload
        (
            {
                url:'handle_ajax.php',
                secureuri:false,
                fileElementId:id,
                dataType: 'json',
                data:{cmd: 'keywords_csvtojson'},
                success: function (data, status)
                {
                   if(typeof(data.error) != 'undefined')
                    {
                       if(data.error != '')
                        {
                            alert(data.error);
                        }else
                        {
                          
                $.each(data.res,function(k,v){
                   /// //console.log(k+"----------"+v.date);
                    if(k>0)
                $("#"+li_id+" #keywords_list table").append("<tr class='keywords_data'><td>"+v.date+" <td>"+v.keyword+"</td><td align='right'><img src='images/close.png'  onclick='delete_keyword(this);' id='add'></td></tr>");  
                })
                        }
                   }
                    $("#"+id).next("img").remove();
                     $("#"+id).val('');
                },
                error: function (data, status, e)
                {
                    alert("Error="+e);
                }
            }
        )
        
        return false;
    } 
    ////////////////////////////////
    
 function create_simple_button(div_id) {
   var i=$("#"+div_id+" #buttons table").size();
   i++;
   var uniq_id=Math.floor( Math.random()*99999 );
    $("#"+div_id+" #buttons").append('<table id="inner_table_'+uniq_id+'" width="100%" align="center"> <tr> <td id="headline_td_'+i+'" align="center"> <strong>Simple Button '+i+'</strong><img src="images/bin.png" style="margin:0px 0px -5px 50px" onclick="delete_button('+"'inner_table_"+uniq_id+"'"+')"/></td> </tr> <tr> <td> <label> <input class="text_feild_cam" name="simple_button_label" placeholder="Button Label"> </label> </td> </tr> <tr> <td> <label> <input class="text_feild_cam" name="simple_button_url" placeholder="Button URL"> </label> </td> </tr> </table>');
    
}
function delete_button(id){
    if(confirm("Are you sure to delete?"))
   $("#"+id).remove();
}  
function check_widget(obj){
    
   var img_src=$(obj).attr('src');
 
   if(img_src=="images/enable.png")
   {
       $(obj).attr({src:'images/disable.png'});
   }
    else  if(img_src=="images/disable.png")
   {
       $(obj).attr({src:'images/enable.png'});
   }
} 
function delete_widget(obj){
    var widget_id=$(obj).parents("li").attr('id');
           $("#"+widget_id).remove();
}        
/*
$(".drop_arrow_settings").on("click",function(){
$(this).parent().next("div").slideToggle(1500);   
});
})
function show_options(obj){
$(obj).parent().next("div").slideToggle();
}
function show_radio(obj,hide_class){
    var parent_id=$(obj).parents(".widget").attr('id');
  $("#"+parent_id+" ."+hide_class).hide(); 
  $("#"+parent_id+" ."+obj.value).show(); 
 // $(obj).parents("tr").next("."+obj.value).show(); 
}
*/            
        function show_google_font(id,obj){
              //  alert( $("#testttr").html());
                
           loadcss('http://fonts.googleapis.com/css?family='+obj.value);
            $("#font_sample_"+id).css({'font-family':obj.value,'font-size': '30px'});
             
            }
             function loadcss(url) {
   var head = document.getElementsByTagName('head')[0],
   link = document.createElement('link');
   link.type = 'text/css';
   link.rel = 'stylesheet';
   link.href = url;
   head.appendChild(link);
   return link;
 }
 function show_button_template(obj,temp){
 var image=obj.value;
 var name = obj.name;
 if(name == 'fb_button_template')
 { 
 	var img=image.split("++");
	$("#"+temp+" img").attr({
		src: "images/buttons/"+img[0],
		width: 'auto',
		height: 'auto'
	 });

 }
 else
 {
	 var img=image.split("++");
	 $("#"+temp+" img").attr({
			src: "images/buttons/"+img[0],
			width: 200,
			height: 60
	 });
 }
// //console.log("ddddd"+image);
 $("#"+temp+" input[name=is_empty]").val(img[1]);
 //alert(image);    
 }
 function delete_page(id,nav_page_id) {
     if(nav_page_id != "")
     nav_page='page='+nav_page_id
     else
     nav_page='';
if(confirm("Are You Sure to Delete This Page?")){
      $("#light_box").css({width: $(window).width(), height: $(window).height(),display: 'block'});  
    $.post("handle_ajax.php",{cmd:'delete_page',id: id},function(res){
     $("#light_box").hide();   
  if(res)
   {alert("Page deleted");
    window.location.href="create_pages.php?"+nav_page;
   }
    })
}
}
function duplicate_page(pageid,nav_page_id){
      if(nav_page_id != "")
     nav_page='page='+nav_page_id
    var page_title=$("#page_title_"+pageid).html();
    var title=prompt("Please confirm page title:",page_title);
    if(title)
   { if(page_title!=title)
    var action="yes";
    else
    var action="no";
     $("#light_box").css({width: $(window).width(), height: $(window).height(),display: 'block'});  
$.post("handle_ajax.php",{cmd: 'copy_page',page_id: pageid,page_title: title,action: action},function(res){
// var obj=JSON.parse(res);
 $("#light_box").hide();  
  if(res)
   {alert("Page Copied");
   if(page_id!="")
    {//alert(page_id);
        window.location.href="create_pages.php?id="+page_id+'&'+nav_page;
    }
    else
    {//alert('empty');
        window.location.href="create_pages.php?"+nav_page; 
    }
   }
    
});  
   }  
}
function show_next_option(obj){
    if($(obj).is(":checked")){
    $(obj).parents("tr").next("tr").css('display','');
    }
    else 
 $(obj).parents("tr").next("tr").css('display','none');
}
function show_const_contact(obj,li_id){
  var select_mail=obj.value;
  //console.log(select_mail);
  if(select_mail == "const_contact")
  {
   $("#"+li_id+" #email_code").hide();
   $("#"+li_id+" #get_const_contact").show();
    //console.log("case ran");  
  } 
  else {
    $("#"+li_id+" #get_const_contact").hide();
     $("#"+li_id+" #email_code").show();
      
  }
}
function get_list_const_contact(obj,li_id){
 var email_const_contact=$("#"+li_id+" input[name=email_const_contact]").val();   
 var pass_const_contact=$("#"+li_id+" input[name=pass_const_contact]").val();   
 var apikey_const_contact=$("#"+li_id+" input[name=apikey_const_contact]").val();
 $("#"+li_id+" #const_contact_list").html('<img src="images/load9.gif" />');
 $.post("handle_ajax.php",{cmd: "get_cc_list",email: email_const_contact ,pass: pass_const_contact,api_key: apikey_const_contact},function(data){
     if(data == "The requested URL returned error: 401error")
      $("#"+li_id+" #const_contact_list").html('<span class="error">Invalid credentials</span>');
      else
   $("#"+li_id+" #const_contact_list").html(data);   
 });   
}
function force_optin_form_dom(obj,li_id){
var force_optin_form=obj.value;
$("#"+li_id+" #cc_form_dom").html(force_optin_form);    
}
function redeem_get_campaigns(obj,li_id){
$("#"+li_id+" #redeem_user_campaigns").html('<img src="images/load9.gif" />');
var redeem_email=wbsms_obj.wbsms_user;
var redeem_pass=wbsms_obj.wbsms_pass;
var redeem_url=wbsms_obj.wbsms_url;
$.post("handle_ajax.php",{cmd: "get_campaigns",email: redeem_email,pass: redeem_pass,url: redeem_url},function(data){
   $("#"+li_id+" #redeem_user_campaigns").html(data); 
})    
}
function get_updated_fonts(li_id,obj){
$("#"+li_id+" select[name=get_updated_fonts]").after("<img src='images/load9.gif'>");
var sort=obj.value;
$.post("handle_ajax.php",{cmd: "get_updated_fonts_list",sort: sort},function(res){
    var fonts=$.parseJSON(res);
    var fonts_option="<option value=''>"+$("#"+li_id+" select[name=get_updated_fonts] option:selected").text()+" fonts</option>";
    $.each(fonts,function(key,val){
     fonts_option+="<option value='"+val+"'>"+val+"</option>";   
    })
    //console.log(fonts);
    $("#"+li_id+" select[name=get_updated_fonts]").next("img").remove();
    $("#"+li_id+" select[name=google_fonts]").html(fonts_option);
})   
}
function add_keywords(li_id,obj){
var keyword_date=$("#"+li_id+" input[name=keyword_date]").val();
var keyword=$("#"+li_id+" input[name=keyword]").val();
$("#"+li_id+" input[name=keyword_date]").val("");
$("#"+li_id+" input[name=keyword]").val("");
if(keyword_date != "" && keyword !="")
{
$("#"+li_id+" #keywords_list table").append("<tr class='keywords_data'><td>"+keyword_date+" <td>"+keyword+"</td><td align='right'><img src='images/close.png'  onclick='delete_keyword(this);' id='add'></td></tr>");
}
}
function delete_keyword(obj){
if(confirm("Are you sure to delete?"))
$(obj).parent("td").parent().remove();
}
function reset_redeems(obj){
if(confirm("Are you sure to reset redeems?"))
$.post("handle_ajax.php",{cmd:"reset_redeems",page_id: page_id},function(res){
    //console.log(res);
})    
}
///////////////google maps
var lat="";
var lon="";
$(document).ready(function(){
   function success(position){
      lat=position.coords.latitude;
      lon=position.coords.longitude;
}
   if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(success);
 // alert(lat+"ddddd");
} else {
 // lat='34.4545';
 /// lon='73.4545';
}
})
    
var geocoder = new google.maps.Geocoder();
function geocodePosition(pos,div) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
   if (responses && responses.length > 0) {
      updateMarkerAddress(responses[0].formatted_address,div);
    } else {
      updateMarkerAddress('',div);
    }
  });
}
function updateMarkerStatus(str) {
  document.getElementById('markerStatus').innerHTML = str;
}
function updateMarkerPosition(latLng,div) {
 $("#map_"+div+" input[name=lat]").val(latLng.lat());
    $("#map_"+div+" input[name=lon]").val(latLng.lng());
}
function updateMarkerAddress(str,div) {
    if(str!="")
$("#map_address_"+div).val(str);
}
function initialize(div,lat,lon) {
  var latLng = new google.maps.LatLng(lat, lon);
  var map = new google.maps.Map(document.getElementById("map_div_"+div), {
    zoom: 8,
    center: latLng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });
        var image = new google.maps.MarkerImage('images/add.png');
 
        marker = new google.maps.Marker({
    position: latLng,
    title: 'Point A',
    map: map,
    ///icon: image,
    
    draggable: true
  });
  
 /* $("#map_address_0").blur(function(){
      ///Update current position info. 
     // var latLng = new google.maps.LatLng('33.256', '73.025'); 
    //  updateMarkerPosition(latLng,div);
 // geocodePosition(latLng,div);  
  //console.log("Focus out called");
  })
  */
  // Add dragging event listeners.
  google.maps.event.addListener(marker, 'dragstart', function() {
    updateMarkerAddress('Dragging...',div);
  });
  
  google.maps.event.addListener(marker, 'drag', function() {
   // updateMarkerStatus('Dragging...',div);
    updateMarkerPosition(marker.getPosition(),div);
  });
  
  google.maps.event.addListener(marker, 'dragend', function() {
    //updateMarkerStatus('Drag ended');
    geocodePosition(marker.getPosition(),div);
  });
}
function load_map(div){
    $("#close_map_"+div).click(function(){
        if($("#wrapper_"+div).is(":visible"))
        $("#wrapper_"+div).hide();
      //  $("#close_map_"+div).hide();
    });
   
//$("#close_map_"+div).css("display","inline");
        // Onload handler to fire off the app.
         if($("#map_"+div+" input[name=lat]").val().length>1 && $("#map_"+div+" input[name=lon]").val().length>1)
        {
          ///  alert("ffffffff");
         lat=$("#map_"+div+" input[name=lat]").val();   
         lon=$("#map_"+div+" input[name=lon]").val();  
            //console.log("map div location"); 
        }
        else if(lat != "" && lon !="")
      {
    //////////check if user shred location
      //  lat_inner=lat;
      //  lon_inner=lon;
          //console.log("shared location="+lat+"=="+lon);
      }else if (latitude != "" && longitude != "")
      {
      ///////////// latitude and longitude(global) coming from database set settings page
        lat=latitude;
        lon=longitude;     
      }    
       else
      {
      lat="44.03862300239222";
        lon="-91.64188628076317";  
  //console.log("default location");
      }
      if(lat !="" && lon !="")
      { $("#wrapper_"+div).show();
          initialize(div,lat,lon);
      }
///google.maps.event.addDomListener(window, 'load', initialize(div,lat,lon));
$("#map_address_"+div).blur(function(){
    var geocoder = new google.maps.Geocoder();
var address = $("#map_address_"+div).val();
geocoder.geocode( { 'address': address}, function(results, status) {
  if (status == google.maps.GeocoderStatus.OK) {
    var latitude = results[0].geometry.location.lat();
    var longitude = results[0].geometry.location.lng();
   $("#map_"+div+" input[name=lat]").val(latitude);
    $("#map_"+div+" input[name=lon]").val(longitude);
  }
});
});
}