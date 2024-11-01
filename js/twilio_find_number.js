jQuery(document).ready(function($) {

$(".country_flag_dropdown").intlTelInput();
$('.twilio_find_number').click(function(){
    $(this).find('.phone_number_search_area_code').focus();
});


$('.twilio_find_number').on('click','.phone_number_drop_down li',function(){

    $('.actual_number').val($(this).attr('phone_number'));
    $('.pretty_number').val($(this).attr('pretty_number'));
   var country_code_int=$('.country_flag_dropdown').val(); 
   var area_code=$('.phone_number_search_area_code').val();
   var number=$(this).attr('phone_number');
    var last_digits_count = number.length - country_code_int.length - area_code.length +1;  
     var new_value=$(this).attr('phone_number').substr(-last_digits_count);
    $('.phone_number_search_phone').val(new_value);
    $('.phone_number_drop_down').remove();
    $('.phone_number_search .rate_center').val($(this).attr('rate_center'));
    $('.phone_number_search .region').val($(this).attr('region'));
});


var ajax_request=false;
$('.twilio_find_number').on('input','.phone_number_search_area_code',function(){
        $('.phone_number_drop_down').remove();
           if($(this).val().length >= '1'){
               if(ajax_request){
                    ajax_request.abort();
               }
                $('.phone_image_loader').show();
                 ajax_request= $.ajax({
                      type: "POST",
                      url: "http://www.ivrdesigner.com/rest/phonenumber/number_search.php",
                      dataType: "JSON",
                      data:{area_code:$(this).val(),cc:$('.country_flag_dropdown').val()},
                      success:function(data){
                            var html='<ul class="phone_number_drop_down">';
                           $.each(data.available_phone_numbers,function(index,value){
                                html+='<li region="'+value.region+'" rate_center="'+value.rate_center+'" phone_number="'+value.phone_number+'" pretty_number="'+value.friendly_name+'">';
                                html+='<div class="phone_number">'+value.friendly_name+'</div>';
                                html+='<div class="phone_number_city">&nbsp;'+value.rate_center+'</div>';
                                html+='<div class="phone_number_state"> ,'+value.region+'</div>';
                                html+='</li>';
 
                           });
                            if(data.available_phone_numbers.length < 1){
                               html+='<li>No Results try different area code</li>';
                            }

                             html+='</ul>';
                            $('.twilio_find_number').append(html); 
                            $('.phone_image_loader').hide();
                      }
                });       
           }

    });

    var html=''+
    '<div class="phone_number_search">'+
        '<input type="hidden" class="rate_center"  name="rate_center">'+
        '<input type="hidden" class="region" name="region">'+
        '<input type="hidden" class="pretty_number" name="pretty_number">'+
        '<input type="hidden" class="actual_number" name="actual_number">'+
        '&nbsp;(<input style="display:inline-block"type="text" class="phone_number_search_area_code">)'+
        '<input style="display:inline-block" readonly="true" type="text" class="phone_number_search_phone">'+
    '<div class="phone_image_loader"></div>'+
    '</div>';

    $('.twilio_find_number').html(html);

});
