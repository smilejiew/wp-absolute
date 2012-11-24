<?php  
/*
Plugin Name: Simple Ajax Contact Form (SACF)
Plugin URI: http://www.h3fuzion.com/lab/simple-ajax-contact-form.php
Description: Simple Ajax Contact Form is designed for Wordpress plugin. This multilingual contact form is easy to configure, you can create custom error messages, disable input fileds and create a custom captcha.
Author: H3 Fuzion
Version: 1.0.4
Author URI: http://www.h3fuzion.com
License: GPL2
Text Domain: sacf

Copyright 2012  H3 Fuzion (email : support@h3fuzion.com)

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

/********************** Setup, Install, Unistall*********************/
//Adds a shortcut action link in the plugin page, next to the activate and edit links 
add_filter('plugin_action_links', 'sacf_action_link', 10, 2);

/*Activation and deactivation hooks*/
register_activation_hook( __FILE__, 'sacf_db_install' );
register_deactivation_hook( __FILE__, 'sacf_db_uninstall');

//Creates a settings shortcut link
function sacf_action_link($link, $file) 
{
    static $sacf_plugin;

    if (!$sacf_plugin) {
        $sacf_plugin = plugin_basename(__FILE__);
    }

    if ($file == $sacf_plugin) 
    {       
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=sacf-settings">'.__('Settings','sacf').'</a>';
        array_unshift($link, $settings_link);
    }

    return $link;
}

//Creates option array when plugin is activated
function sacf_db_install() 
{
        $sacf_options = array(
            'b_name' => 'Your business name',
            'b_email' => '',
            'captcha'=>'1',
            'c_color'=>'#0b83c2',
            'c_font'=>'arial.ttf',
            'c_size'=>'12',
            'subject'=>'1',
            's_name'=>'',
            's_address'=>'',
            'form_size'=>'500',
            'subject_req'=>'1',
            'phone_req'=>'1',
            'phone_mask'=>'(999) 999-9999'
        );
        
        $sacf_msg_options = array(
            'empty' => 'Please fill the values',
            'email_nv' => 'Please enter a valid e-mail',
            'w_captcha'=>'Wrong captcha',
            'e_captcha'=>'Captcha is empty',
            'e_phone'=>'Enter phone number',
            'sub_req'=>'Enter a subject',
            'success'=>'Thank you, we will contact you soon.'
        );
        
        if( !get_option( 'sacf_msg_settings' ))
        add_option( 'sacf_msg_settings', $sacf_msg_options );
        
        if( !get_option( 'sacf_settings' ))
        add_option( 'sacf_settings', $sacf_options );
}

//Delete option array when plugin is deactivated
function sacf_db_uninstall()
{
     delete_option('sacf_settings');
     delete_option('sacf_msg_settings');
     unset($_SESSION["secureWord"]);
}



/**************** Initializes plugin on Admin Page*************************/
//Initialize external files and creates a menu
add_action( 'admin_init', 'sacf_admin_init' );
add_action( 'admin_menu', 'sacf_admin_menu');                    // Displays a option menu under the main OPTIONS menu
add_action( 'init', 'sacf_init_session', 1);

//Initializes external admin CSS and JS files
function sacf_admin_init() 
{
    //Loads CSS and JS files to Admin menu
    wp_enqueue_style( 'sacfAdminStyle', plugins_url( 'css/sacf-admin-style.css', __FILE__ ),array('farbtastic'));
    wp_enqueue_script( 'sacfAdminScript', plugins_url( 'js/sacf-admin-script.js', __FILE__ ),array('jquery','farbtastic'),'1.0',true);        
}

 //Sets up the admin menu
function sacf_admin_menu()
{
add_menu_page( __('Simple Ajax Contact Form - Options Page','sacf'), //Menu title
                            __('SACF Settings','sacf'),  //Page Title
                            'manage_options',  // The capability required for access to this item
                            'sacf-settings',         // the slug to use for the page in the URL
                            'sacf_form_settings');    // The function to call to render the page
                            
add_submenu_page('sacf-settings', __('Settings','sacf'), __('Settings','sacf'), 'manage_options', 'sacf-settings','sacf_form_settings');
add_submenu_page('sacf-settings', __('Edit Messages','sacf'), __('Edit Messages','sacf'), 'manage_options', 'sacf_test','sacf_error_settings');
//add_submenu_page('sacf', 'General', 'General', 'manage_options', 2,'sacf_form_settings'); 
}

 //Adds info links to Admin footer - Disabled by default
function sacf_admin_footer() 
{
    $plugin_data = get_plugin_data( __FILE__ );
    printf('%1$s plugin | Version %2$s | by %3$s | %4$s<br />', $plugin_data['Title'], $plugin_data['Version'], $plugin_data['Author'],'<a href="http://www.h3fuzion.com/lab/simple-contact-form.php">Doc</a>');    
}

//Gets data from get_option('sacf_settings'); - Thisi is used by radio inputs
function sacf_getVal($type)
{
    $options = get_option('sacf_settings');
    return ( $options[$type] );
}

//Gets error messages from get_option('sacf_msg_settings');
function sacf_displayError($type)
{
    $eoptions = get_option('sacf_msg_settings');    
    $errorType = '<span class="errorMsg">'.$eoptions[$type].'</span><br/>';    
    return $errorType;
}

//Displays success message
function sacf_displayMsg($type)
{
    $eoptions = get_option('sacf_msg_settings');    
    $msgType = '<span id="successMsg">'.$eoptions[$type].'</span><br/>';    
    return $msgType;
}

//Displays SACF settings form in Admin's page
function sacf_form_settings()
{
     if (!current_user_can('manage_options')) 
     wp_die('You do not have sufficient permissions to access this page.');
    
    else
    include_once('sacf_form.php');
}

//Displays SACF error messages form in Admin's page
 function sacf_error_settings()
 {
    if (!current_user_can('manage_options')) 
     wp_die('You do not have sufficient permissions to access this page.');
    
    else
    include_once('sacf_error_settings.php'); 
 }

//Adds a submenu to Settings menu - disabled by default
function sacf_admin_actions() 
{
    add_options_page("Simple Ajax Contact Form - Options Page", "SACF Options", 1, "SACF", "sacf_admin");
}


/********************** Displays Contact Form *******************************/
add_action( 'wp_enqueue_scripts', 'sacf_load_client_script');
add_shortcode( 'sacf_contact_form', 'sacf_display_form');   //enter this in your post or page [sacf_contact_form] to display the contact form
add_action('wp_head', 'sacf_load_into_head' ); 
 // AJAX add actions
add_action( 'wp_ajax_the_ajax_hook', 'sacf_process_form' );
add_action( 'wp_ajax_nopriv_the_ajax_hook', 'sacf_process_form' ); // need this to serve non logged in users

//Process our contact form using AJAX    
function sacf_process_form()
{
    include_once('proccess.php');
}

//loads client JS file
function sacf_load_client_script()
{
    wp_enqueue_style( 'sacfStyle', plugins_url( 'css/sacf-contact-form-style.css', __FILE__ ));    
    wp_enqueue_script( 'sacfPhoneMask', plugins_url( 'js/jquery.maskedinput-1.3.min.js', __FILE__ ),array('jquery'));
        
    //Ajax
    wp_enqueue_script( 'my-ajax-handle', plugin_dir_url( __FILE__ ) . 'js/sacf-script.js', array( 'jquery' ) );    
    wp_localize_script( 'my-ajax-handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );    
}

//adds js to the head of your template
function sacf_load_into_head() 
{ 
$sacf_options = get_option('sacf_settings');
$cap = '
<script type="text/javascript"> 
    jQuery(document).ready(function(){    
        jQuery("#scf-phone").mask("'.$sacf_options['phone_mask'].'");
    });    
    
    function reloadCaptcha()
    {
        var time = new Date();
        var current = time.getTime();
        jQuery("#scf-captcha-img").attr("src","'.plugins_url( 'captcha.php', __FILE__ ).'?x="+ current);
        
    }
</script>'; 
echo $cap;
} 

//Displays contact form
function sacf_display_form()
{
    global $sacf_options;
    $sacf_options = get_option('sacf_settings');
    $sacf_test ='';
    $sacf_test = '<div style="width:'.$sacf_options['form_size'].'px; float:left">
                                <form id="scf-form" action="" method="POST">
                                    <div id="scf-msg" class="allCorners"></div>

                                    <input type="text" value="" name="scf-name" class="txt" id="scf-name" placeholder="YOUR NAME" />
                                    <div class="sacf_clear"></div>

                                    <input type="text" name="scf-email" class="txt" id="scf-email" placeholder="YOUR E-MAIL" />
                                    <div class="sacf_clear"></div>';

                                    if($sacf_options['subject'] == '1')
                                    {
                                        $sacf_test .= '<label>';
                                        if($sacf_options['subject_req'] == '1')
                                        {
                                            $require = '<b>*</b>';
                                        }
                                        $sacf_test .= __('Subject','sacf') .$require. '</label>
                                                                    <input type="text" name="scf-subject" class="txt" id="scf-subject" /><div class="sacf_clear"></div>';
                                    }
                                    
                                    $sacf_test .= '<textarea id="scf-comment" name="scf-comment" class="txtarea" ></textarea><div class="sacf_clear"></div>';
                                    
                                    if($sacf_options['captcha'] == '1')
                                    {
                                        $sacf_test .= '<label><img border="0" id="scf-captcha-img" src="'.plugins_url( 'captcha.php', __FILE__ ).'" alt="Captcha"><a href="JavaScript: reloadCaptcha();" /><img border="0" alt="Click here" src="'.plugins_url( 'images/refresh.png', __FILE__ ).'" /></a></label>
                                        <div class="sacf_txt_container">
                                            <input type="text" maxlength="6" name="scf-captcha" id="scf-captcha" />
                                        </div>';
                                    }                                    
                                $theme_url = get_theme_root_uri($stylesheet_or_template = false).'/'.get_current_theme().'/';
                                $sacf_test .=  '<div id="submit_cont">
                                    <div id="loader"></div>
                                    <input type="submit" name="scf-submit" id="scf-submit" value="'. __('Send','sacf') .'" />
                                </div>
                                <input name="action" type="hidden" value="the_ajax_hook" />
                                    
                                </form></div><div class="sacf_clear"></div>';
    return $sacf_test;
    
}


/**************************** Extra functions *******************************/
//start session and loads languages
function sacf_init_session()
{    
    if (!session_id())
    session_start();
    
    load_plugin_textdomain('sacf', false, basename(dirname(__FILE__)) . '/language/');
}

//Converts HEX to RGB colors
function sacf_HexToRGB($hex) 
{
    $hex = ereg_replace("#", "", $hex);
    $color = array();        
        $color['r'] = hexdec(substr($hex, 0, 2));
        $color['g'] = hexdec(substr($hex, 2, 2));
        $color['b'] = hexdec(substr($hex, 4, 2));        
    return $color;
}

//Displays fonts type from the font directory
function sacf_selectFont()
{            
            $fileCount=1;
            $filePath= WP_PLUGIN_DIR ."/simple-ajax-contact-form/fonts/";
            $dir = opendir($filePath); 
            
            
            while ($file = readdir($dir)) 
            { 
                 if (preg_match("/\.ttf/i",$file))// || eregi("\.TTF",$file)) 
                 {              
                    $displayFont = strtoupper(substr($file, 0, strlen($file)-4));
                     $options = get_option('sacf_settings');
                    if ( $file == $options['c_font'] )
                    {
                        echo '<input type="radio" name="sacf_fontGroup" id="font_'.$fileCount.'" class="radioType" value="'.$file.'" checked="checked" />'. $displayFont.'<div class="sacf_clear"></div>';
                    }
                    else
                    {
                        echo '<input type="radio" name="sacf_fontGroup" id="font_'.$fileCount.'" class="radioType" value="'.$file.'" />'. $displayFont.'<div class="sacf_clear"></div>';
                    }                    
                    $fileCount++;
                  }
            }    
            closedir($dir);
}

//sends email 
function sacf_send_email($sender,$s_email,$phone,$sub,$comment)
{
    $sacf_options = get_option('sacf_settings');
    
    $display_subject = $sacf_options['subject_req'];
    $sendTo = $sacf_options['b_email'];
    
    if(empty($sub))
    $emailSubject = $sacf_options['b_name'].' contact form';
    
    else
    $emailSubject = $sub;
    
    
    $dateSent = date("l dS \of F Y h:i a"); 
    // Send email in HTML format, the Content-type header must be set
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";        
    $headers .= "From: " .$sender." <" .$s_email. ">" ."\r\n";
    
    $message = '
                                                                                    
                            <table>                                
                                <tr><td><b>'.__('Name', 'sacf').'</b></td><td>'.$sender.'</td></tr>
                                
                                <tr><td><b>'.__('Phone', 'sacf').'</b></td><td>'.$phone.'</td></tr>';
                                
                                $message .= '<tr><td><b>'.__('Email', 'sacf').'</b></td><td>'.$s_email.'</td></tr>';
                                if ($display_subject)
                                $message .= '<tr><td><b>'.__('Subject','sacf').'</b></td><td>'.$emailSubject.'</td></tr>';    
                                
                                $message .= '<tr><td><b>'.__('Date', 'sacf').'</b></td><td>'.$dateSent.'</td></tr>
                            </table><br/>
                            
                            <div>
                                
                                '.$comment.'
                                
                            </div>';                                                        
                            
    mail($sendTo, $emailSubject, $message, $headers);
}

?>