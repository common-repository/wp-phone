<?php
   /*
   Plugin Name: WP Phone 
   Plugin URI: http://wordpress.org/plugins/wp-phone/
   Description: WP Phone lets you get a unique phone number for your Wordpress website. You can then setup a custom phone system and do things like forward calls, transfer calls, place outbound calls, set up interactive voice response menus and more. Wp Phone leverages <a target="_blank" href="http://www.ivrdesigner.com"> www.ivrdesigner.com</a> for the phone system.  
   Version: 1.1
   Author:Taylor Hawkes 
   Author URI: http://taylor.woodstitch.com
   License: GPL2
   */
    

/*  Copyright 2013  Taylor Hawkes  (email : thawkes@woodstitch.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/* stuff to do when we create plugin */

//register_activation_hook( __FILE__, 'wp_phone_init_plugin' );

//register_deactivation_hook( __FILE__, 'wp_phone_remove_plugin' );
add_action( 'admin_init', 'my_plugin_admin_init' );


/** Step 2 (from text above). */
add_action( 'admin_menu', 'wp_phone_my_plugin_menu' );
//add_action( 'edit_form_after_title', 'add_admin_cache_button' );

/*these are for updting the cache automaticly */
//add_action( 'edit_post', 'wp_phone_update_page_cache' );

add_action( 'admin_footer', 'wp_phone_add_javascript_to_admin' );

function wp_phone_add_javascript_to_admin() {
?>
<script src="<?php echo  plugins_url('js/intlTelInput.min.js',__FILE__);?>"></script>
<script src="<?php echo  plugins_url('js/signup.js',__FILE__);?>"></script>
<script src="<?php echo  plugins_url('js/twilio_find_number.js',__FILE__);?>"></script>
    
<script type="text/javascript" >
//put all js stuff here
jQuery(document).ready(function($) {

});   
</script>
<?php
}

/** Step 1. */
function wp_phone_my_plugin_menu() {
    add_menu_page( 'Wp Phone', 'WP phone', 'publish_posts', 'wp-phone', 'wp_phone_my_plugin_options' );
}

/** Step 3. */

function wp_phone_my_plugin_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

?>

 <?php wp_enqueue_style( 'intlTelInput' );?>
 <?php wp_enqueue_style( 'twilio_find_number' );?>
<div class="wrap">

  <div id="icon-options-general" class="icon32"><br></div>
   <h2 > WP Phone  </h2> 
    <p class="description">A Business Phone System For Wordpress </p>

<?php if(get_option("wp_phone_is_registered_1")){ ?>

<?php 
    $wp_phone_options=get_option("wp_phone_registration_data");
    $wp_phone_options=unserialize($wp_phone_options);
?>
    <?php get_first_time_notice($wp_phone_options)?>


    <div id="wp_phone_holder">
    <h3>WP Phone Account Details</h3>
    <hr/>
    
    <table class="form-table wp_phone_form_table" style="width:600px;" >
<tr>
     <th>
        <label class=""> WP Phone Number </label>
        <p  class="description">Phone number to be used on your website.</p>
            </th> </th>
            <td>
    <span style="font-size:34px;font-weight:bold;"><?php echo $wp_phone_options['pretty_default_number']?> </span>
        <br/>

        <p  class="description">To display your WP Phone number copy and paste this shortcode in your pages and posts.</p>
        <textarea class="wp_phone_default_number_shortcode">[wp_phone_number]</textarea>
<br/>
        <p  class="description">If you would like to display the number in a template use this PHP code</p>
        <textarea class="wp_phone_default_number_shortcode">&lt;?php echo get_option("wp_phone_number");?&gt; </textarea>
    
             </td>
        <tr>
        <tr>
            <th> Manage Phone number
            <p class="description"> Setup call flow for your phone number </p>
             </th>
             <td> <a href="http://www.ivrdesigner.com/login.php?email=<?php echo $wp_phone_options['email']?>" target="_blank" class="button button-primary"> Login and Manage Number </a></td>
        </tr>
        <tr>
            <th> Help videos
            <p class="description">Having trouble setting up your call system check out these help videos. </p>
             </th>
             <td><a href="http://www.ivrdesigner.com/help-videos.php" target="_blank"> http://www.ivrdesigner.com/help-videos.php </div> </td> 
        </tr>


    </table>
    </div>
   <br/> 
   <br/> 
   <br/> 

    <div id="wp_phone_holder" class="" >
    <h3>WP Phone User Profile</h3>
    <hr/>

    <table class="form-table wp_phone_form_table" style="width:600px;" >
        <tr>
            <th> <label for="name">Username: </label> </th>
            <td> <?php echo $wp_phone_options['name']?></td>
        </tr>
        <tr>
           <th> <label for="email">Email: </label> </th>
            <td><?php echo $wp_phone_options['email']?> </td>
        </tr>
        <tr>   
        </tr>

        <tr>
        <th>
        <label > Your Phone Number </label>
        <p  class="description">Your personal phone number.</p>
            </th> </th>
            <td><?php echo $wp_phone_options['default_phone']?> </td>
        <tr>
        <tr>
       
        </table>

    </div>

<?php }else{ ?>

    <!-- start of form-->
    <div style="margin-top:0px;">
    <div id="wp_phone_holder" class="" >

<form  style="display:none" id="wp_phone_sync_form">
<h3> Sync IVR Designer account with Wordpresss</h3>
    <p class="description"> 
    If you already have registered with www.ivrdesigner.com you may sync your account with wordpress so you can easily display your IVR Designer phone number.
    </p> 
<hr/>

    <div class="error_holder_sync"></div>

    <table class="form-table wp_phone_form_table" style="width:600px;" >
    <tr>
       <th> <label for="email">IVR Designer Email: </label> </th>
        <td><input class="regular-text" type="text" name="email" id="email"> </td>

    </tr>
    <tr>   
        <th><label for="password">IVR Designer Password : </label> </th>
        <td><input class="regular-text" type="password" name="password" id="password"> </td>
    </tr>
    <tr>
        <th></th>
        <td> <input class="button button-primary" style="width:100%;" type="submit" value="Sync IVR Designer Account."> </td>
    </tr>
    </table>
<hr/>
Don't have  a www.ivrdesigner.com account? <a  style="cursor:pointer;"id="wp_phone_register">Register One Now!</a>
</form>


    <form  id="wp_register_form">

  <h3> Register Your WP Phone Account</h3>
    <p class="description"> In order to receive a WP Phone number to be used on your website you need to complete the below form to register a new account with www.ivrdesigner.com. Once you have a WP Phone number you will be able to setup a call flow system that allows you to forward your WP Phone number to your personal phone, setup a automated voice response, setup call filters and more.
</p>
 
<hr/>


    <div class="error_holder"></div>


    <table class="form-table wp_phone_form_table" style="width:600px;" >
    <tr>
        <th> <label for="name">Username: </label> </th>
        <td> <input class="regular-text" type="text" name="name" id="name"> </td>
    </tr>
    <tr>
       <th> <label for="email">Email: </label> </th>
        <td><input class="regular-text" type="text" name="email" id="email"> </td>

    </tr>
    <tr>   
        <th><label for="password">Select Password: </label> </th>
        <td><input class="regular-text" type="password" name="password" id="password"> </td>
    </tr>
    <input type="hidden" name="new_signup_form" value="1">
<!--
    <tr>
    <th>
        <label > Your Phone Number </label>
    <p  class="description">Your personal phone number.</p>
    </th>
    
        <td class="no-pad">
            <table id="wp_phone_form_phone" style="width:316px;" class="a-top"><tr><td>
                <input type="tel" name="phone_cc" class="country_flag_dropdown">
            </td>
            <td>
                <input style="width:60px;"class="phone_part1_signup" name="area_code" id="area_code" maxlength="3" size="3" type="text">
                <input style="width:60px;"class="phone_part2_signup" name="phone1" id="phone1" maxlength="3" size="3" type="text">
                <input style="width:90px;" class="phone_part3_signup" name="phone2" id="phone2" maxlength="6" size="6" type="text">
            </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th>
            <label style="margin-top:0px;" >Get New Phone Number </label>
            <p class="description">Input an area code then select a number from dropdown.</p>
        </th>
        <td class="no-pad">
            <table style="width:316px;" class="a-top"><tr>
            <td>
                <input type="tel" class="country_flag_dropdown">
            </td>
            <td>
                <div class="twilio_find_number"></div>
            </td>
            </tr>
            </table>
        </td>
    </tr>
-->
    <tr><th></th><td>
<input class="button button-primary" style="width:100%;" type="submit" value="Register WP Phone Account">

<hr/>
 By clicking the "Submit" button, your agree to www.ivrdesigner.com's <a target="_blank" href="http://www.ivrdesigner.com/terms.php">Terms of Use</a>, <a target="_blank" href="http://www.ivrdesigner.com/aup.php">Acceptable Use Policy</a> and <a target="_blank" href="http://www.ivrdesigner.com/privacy.php">Privacy Policy</a>

 </td> </tr>
    </table>
<hr/>
Already have a www.ivrdesigner.com account? <a style="cursor:pointer;" id="wp_phone_sync">Sync it with Wordpress.</a>
         
    </form>
    
    </div>
    </div>
<!--end the form -->
   
<?php } ?>
</div>

    <?php
}
  

/*this removes the codes form .htaccess*/
function wp_phone_remove_plugin(){
    //remove plugin her
}

/* this inits the plugin */
function my_plugin_admin_init(){

      wp_register_style( 'intlTelInput', plugins_url('css/intlTelInput.css', __FILE__) );
      wp_register_style( 'twilio_find_number', plugins_url('css/twilio_find_number.css', __FILE__) );
}
    
function  get_first_time_notice($wp_phone_options){
    if(isset($_GET['first_time'])){ ?>
        <div class="first_time_notice">
    Registration Success! Your WP Phone number is <?php echo $wp_phone_options['pretty_default_number']?>
. Give it a call to test it out then <a href="http://www.ivrdesigner.com/login.php?email=<?php echo $wp_phone_options['email']?>" target="_blank">customize your call flow system however you want here </a>.    
      </div>
                
    <?php  }
}

  
###############################################################################
# AJAX REGISTERS FUNCTION FOR CRUD 
###############################################################################

  
add_action('wp_ajax_wp_phone_save_user_data', 'wp_phone_save_user_data'); 
function wp_phone_save_user_data(){
    $data=array();
    $data['name']=$_POST['name'];
    $data['email']=$_POST['email'];
  //  $data['user_personal_number'] =$_POST['user_personal_number'];
   // $data['user_personal_number_cc'] =$_POST['phone_cc'];
    $data['default_phone']=$_POST['default_phone'];
    $data['pretty_default_number']=$_POST['default_phone'];
    $data['signup_source']=$_POST['signup_source'];
    $data=serialize($data);
    update_option("wp_phone_registration_data",$data);
    update_option("wp_phone_number",$_POST['pretty_number']);
    update_option("wp_phone_is_registered_1","1");

    //save the user data
    return true;
}
###############################################################################
#  filters
###############################################################################


function wp_phone_replace_phone_number($content){
$number=get_option("wp_phone_number");
if(!$number){  $number='';}
return str_replace("[wp_phone_number]",$number,$content);

}

add_filter( 'the_content', 'wp_phone_replace_phone_number');


?>
