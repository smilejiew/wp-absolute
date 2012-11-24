<?php 
//Gets values from database
$options = get_option('sacf_msg_settings');

if( isset($_POST['sacf_msg_form_submit'] )) 
{		
		$options['empty'] = $_POST['sacf_efield'];		
		$options['email_nv'] = $_POST['sacf_emailnv'];
		$options['w_captcha'] = $_POST['sacf_wcaptcha'];
		$options['e_captcha'] = $_POST['sacf_ecaptcha'];
		$options['e_phone'] = $_POST['sacf_phone'];
		$options['sub_req'] = $_POST['sacf_esub'];
		$options['success'] = $_POST['sacf_success'];		
		
		update_option('sacf_msg_settings', $options);
}

?>

<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<?php    echo '<h2>' . __( 'Simple AJAX Contact Form', 'sacf' ) . '</h2>'; ?> <br/>
	
		<div class="updated fade" <?php if( !isset( $_REQUEST['sacf_msg_form_submit'] )) echo 'style="display:none"'; ?>><p><strong><?php echo __('Settings saved.') ?></strong></p></div>

	<form id="sacf_settings_form" method="POST" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			
			<p class="sacf-form-sub"><?php _e('Error Messages','sacf') ?></p>
			<label for="sacf_efield"><?php _e('Empty field:','sacf') ?></label>
			<input type="text" id="sacf_efield" class="inputType" name="sacf_efield" value="<?php echo $options['empty']; ?>" />	<div class="sacf_clear"></div>
			
			<label for="sacf_emailnv"><?php _e('Email no valid:','sacf') ?></label>
			<input type="text" id="sacf_emailnv" class="inputType" name="sacf_emailnv" value="<?php echo $options['email_nv']; ?>" /><div class="sacf_clear"></div>
			
			<label for="sacf_wcaptcha"><?php _e('Wrong Captcha:','sacf') ?></label>
			<input type="text" id="sacf_wcaptcha" class="inputType" name="sacf_wcaptcha" value="<?php echo $options['w_captcha']; ?>" /><div class="sacf_clear"></div>
			
			
			<label for="sacf_ecaptcha"><?php _e('Empty Capcha:','sacf') ?></label>
			<input type="text" id="sacf_ecaptcha" class="inputType" name="sacf_ecaptcha" value="<?php echo $options['e_captcha']; ?>" /><div class="sacf_clear"></div>
			
						
			<label for="sacf_phone"><?php _e('Empty Phone:','sacf') ?></label>
			<input type="text" id="sacf_phone" class="inputType" name="sacf_phone" value="<?php echo $options['e_phone']; ?>" /><div class="sacf_clear"></div>
						
			<label for="sacf_esub"><?php _e('Empty Subject:','sacf') ?></label>
			<input type="text" id="sacf_esub" class="inputType" name="sacf_esub" value="<?php echo $options['sub_req']; ?>" /><div class="sacf_clear"></div>
			
			<p class="sacf-form-sub"><?php _e('Success Message','sacf') ?></p>
			<label for="sacf_success"><?php _e('Success Message:','sacf') ?></label>
			<input type="text" id="sacf_success" class="inputType" name="sacf_success" value="<?php echo $options['success']; ?>" />	<div class="sacf_clear"></div>
			
			<input type="hidden" name="sacf_msg_form_submit" value="submit" />			<div class="sacf_clear"></div>
			
			<input class="button-primary" type="submit" name="Save" value="<?php _e('Update options', 'sacf'); ?>" id="submitbutton" />
	</form>
</div>
