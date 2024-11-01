jQuery(document).ready(function($) {

$("#wp_register_form").on('submit',function(event){
var form_data=$(this).serialize();
//var user_personal_number=$("#area_code").val()+$("#phone1").val()+$("#phone2").val();
 //   user_personal_number=user_personal_number.replace(/\s+/g,"");
  //  form_data+="&user_personal_number="+user_personal_number;
    var form_stuff=form_data;

    
    $.ajax({
      type: "POST",
      url: 'http://www.ivrdesigner.com/rest/signup.php',
      dataType:'JSON',
      data:form_data,
      success:function(data,form_data){
        if(data.success == "1"){
                            add_wp_phone_wordpress_options(form_stuff,'new_account',data);            

              }else{
                     var errors_holder="<ul class='errors'>";
                            errors_holder+="<h5>Oops, there was a problem. Please fix the below errors.</h5>";
                        $.each(data.errors,function(key,error){
                            errors_holder+="<li>"+error+"</li>";
                        });     

                        errors_holder+="</ul>";

                        $('.error_holder').html(errors_holder);
            
                  
          }
      }
    });
    event.preventDefault();
    return false;
    
});

$("#wp_phone_sync_form").on('submit',function(event){
var form_data=$(this).serialize();
//this is for syncing wordpress
  $.ajax({
      type: "POST",
      url: 'http://www.ivrdesigner.com/rest/sync_wp.php',
      dataType:'JSON',
      data:form_data,
      success:function(data,form_data,return_data){
        if(data.success == "1"){
            var account_data='';
             account_data+="&name="+data.data.name;
             account_data+="&email="+data.data.email;
             //account_data+="&user_personal_number="+data.data.user_personal_number;
             //account_data+="&phone_cc="+data.data.phone_cc;
                    add_wp_phone_wordpress_options(account_data,'account_sync',return_data);            
              }else{

                     var errors_holder="<ul class='errors'>";
                            errors_holder+="<h5>Oops, there was a problem. Please fix the below errors.</h5>";
                        $.each(data.errors,function(key,error){
                            errors_holder+="<li>"+error+"</li>";
                        });     

                        errors_holder+="</ul>";
                        $('.error_holder_sync').html(errors_holder);
            
                  
          }
      }
    });
    event.preventDefault();
    return false;
    
});

$('#wp_phone_sync').on('click',function(){
   $('#wp_phone_sync_form').show(); 
   $('#wp_register_form').hide(); 
});
$('#wp_phone_register').on('click',function(){
   $('#wp_phone_sync_form').hide(); 
   $('#wp_register_form').show(); 
});

    //saves data to worpdress
    function add_wp_phone_wordpress_options(form_data,signup_source,return_data){
        var data=form_data;
        data+="&signup_source="+signup_source;
        data+="&action=wp_phone_save_user_data";
        data+="&default_phone="+return_data.friendly_name;
        $.post(ajaxurl, data, function(response) {
            var reload_page=window.location.href;
                reload_page+="&first_time=true"
            window.location=reload_page;
        });
     }

});
