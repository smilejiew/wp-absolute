<?php 
//Gets values from database
$options = get_option('sacf_settings');
$admin_error['msg'] ='';
$sacf_error = false;
$sacf_font_error = false;
$sacf_font_msg ='';

if( isset($_POST['sacf_font_submit'] )) 
{	 $allow = array('.ttf'); // These will be the types of file that will pass the validation.
      $max_filesize = 524288; // Maximum filesize in BYTES (currently 0.5MB).
      $upload_path = WP_PLUGIN_DIR ."/simple-ajax-contact-form/fonts/"; // The place the files will be uploaded to (currently a 'files' directory).
	  

	
   $filename = $_FILES['userfile']['name']; // Get the name of the file (including file extension).
   $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
 
   // Check if the filetype is allowed, if not DIE and inform the user.
   if(!in_array($ext,$allow))
   {
      
	  $sacf_font_error =true;
	  $sacf_font_msg = __('Only font files (.ttf) are allowed', 'sacf');
	}
 
   // Now check the filesize, if it is too large then DIE and inform the user.
   if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
      {
	  $sacf_font_error =true;
	  $sacf_font_msg=__('The file you attempted to upload is too large', 'sacf');
	  }
 
   // Check if we can upload to the specified path, if not DIE and inform the user.
   if(!is_writable($upload_path))
      {
		$sacf_font_error =true;
		$sacf_font_msg=__('You cannot upload to the specified directory, please CHMOD it to 777', 'sacf');
		}	
 
	if(!$sacf_font_error)
	{
   // Upload the file to your specified path.
	if(move_uploaded_file($_FILES['userfile']['tmp_name'],$upload_path . $filename))
        $sacf_font_msg=__('Your file upload was successful','sacf'); // It worked.
      else
         $sacf_font_msg=__('There was an error during the file upload.  Please try again', 'sacf'); 
	}
	
} /*End of upload font*/


if( isset($_POST['sacf_form_submit'] )) 
{				
		$options['b_name'] = $_POST['sacf_bname'];	
		$options['b_email'] = $_POST['sacf_bemail'];
		$options['c_color'] = $_POST['sacf_cColor'];
		$options['c_font'] = $_POST['sacf_fontGroup'];
		$options['c_size'] = $_POST['sacf_cSize'];
		$options['form_size'] = $_POST['sacf_fsize'];
		$options['subject_req'] = $_POST['sacf_subjectReqGroup'];
		$options['phone_req'] = $_POST['sacf_phoneGroup'];
		$options['phone_mask'] = $_POST['sacf_pMask'];
		$options['captcha'] = $_POST['sacf_captchaGroup'];
		$options['subject'] = $_POST['sacf_subjectGroup'];		
		
		//validates email format
		if(!is_email($_POST['sacf_bemail']))
		{
			$sacf_error = true;
			$admin_error['msg'] = '<span>'.__('Enter a valid email', 'sacf').'</span><br/>';
		}	
		else
		{
			$email = sanitize_email($_POST['sacf_bemail']);
			$options['b_email'] = $email;
		}	
		
		//if form size is empty, display error
		if(empty($_POST['sacf_fsize']))
		{
			$sacf_error = true;
			$admin_error['msg'] .= '<span>'.__('Enter form size', 'sacf').'</span><br/>';
		}
		
		//if business name is empty, display error
		if(empty($_POST['sacf_bname']))
		{
			$sacf_error = true;
			$admin_error['msg'] .= '<span>'.__('Enter your business name', 'sacf').'</span><br/>';
		}	
		
		//if captcha font size is empty, display error
		if(empty($_POST['sacf_cSize']))
		{
			$sacf_error = true;
			$admin_error['msg'] .= '<span>'.__('Enter captcha size', 'sacf').'</span><br/>';
		}	
		
		//if no error, update settings
		if(!$sacf_error)
		update_option('sacf_settings', $options);
}
?>

<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<?php    echo '<h2>' . __( 'Simple AJAX Contact Form', 'sacf') . '</h2>'; ?> <br/>
	
		<div class="updated fade" 
		<?php 
		if( !isset( $_REQUEST['sacf_form_submit'] )) 
		echo 'style="display:none"'; ?>><p><strong><?php 
		
		if(!$sacf_error)
			_e('Settings saved.', 'sacf');
			
			else
			echo $admin_error['msg'];
					
		 ?></strong></p></div>

	<form id="sacf_settings_form" method="POST" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			
			<p class="sacf-form-sub"><?php _e('Personal Information', 'sacf') ?></p>
			<label for="sacf_bname"><?php _e('Business Name:', 'sacf') ?></label>
			<input type="text" id="sacf_bname" class="inputType" name="sacf_bname" value="<?php echo $options['b_name']; ?>" />	
			
			<label for="sacf_bemail"><?php _e('Business Email:', 'sacf')?></label>
			<input type="text" id="sacf_bemail" class="inputType" name="sacf_bemail" value="<?php echo $options['b_email']; ?>" />
			
			<p class="sacf-form-sub"><?php _e('Form Settings', 'sacf') ?></p>
			<label for="sacf_fsize"><?php _e('Form Width:','sacf')?></label>
			<input type="text" id="sacf_fsize" class="smallField" maxlength="3" name="sacf_fsize" value="<?php echo $options['form_size']; ?>" />
			<span class="sacf_msg">pixels</span><div class="sacf_clear"></div>		
			
			<label for="sacf_captcha"><?php _e('Display Captcha:', 'sacf')?></label>
			<div class="sacf_radioContainer">
				<ul class="sacf_radioList">
					<li><input type="radio" name="sacf_captchaGroup" id="sacf_captcha_yes" class="radioType" value="1" 
					<?php
						if(sacf_getVal('captcha') == '1')
						echo 'checked="checked"';
					?>/><?php _e('Yes','sacf'); ?></li>
					<li><input type="radio" name="sacf_captchaGroup" id="sacf_captcha_no" class="radioType" value="0" 
					<?php
						if(sacf_getVal('captcha') == '0')
						echo 'checked="checked"';
					?>
					/><?php _e('No','sacf'); ?></li>
				</ul>
			</div>	<div class="sacf_clear"></div>		
			
			<label for="sacf_cColor"><?php _e('Captcha color:', 'sacf')?></label>
			<input type="text" id="sacf_cColor" class="smallField" name="sacf_cColor" value="<?php echo $options['c_color']; ?>" />			
			<div id="colorContainer"><div id="ilctabscolorpicker"></div></div>		<div class="sacf_clear"></div>			
			
			<label for="sacf_cFont"><?php _e('Captcha Fonts:', 'sacf')?></label>
			<div class="sacf_radioContainer">
				<?php sacf_selectFont();  ?>				
			</div><div class="sacf_clear"></div>		
			
			<label for="sacf_cSize"><?php _e('Captcha Font Size:', 'sacf')?></label>
			<input type="text" id="sacf_cSize" class="smallField" maxlength="2" name="sacf_cSize" value="<?php echo $options['c_size']; ?>" />
			<span class="sacf_msg">pixels</span>	<div class="sacf_clear"></div>				
			
			<label for="sacf_subject"><?php _e('Display Subject:', 'sacf')?></label>
			<div class="sacf_radioContainer">
				<ul class="sacf_radioList">
					<li><input type="radio" name="sacf_subjectGroup" id="sacf_subject_yes" class="radioType" value="1" 
					<?php
						if(sacf_getVal('subject') == '1')
						echo 'checked="checked"';
					?>/><?php _e('Yes','sacf'); ?></li>
					<li><input type="radio" name="sacf_subjectGroup" id="sacf_subject_no" class="radioType" value="0" 
					<?php
						if(sacf_getVal('subject') == '0')
						echo 'checked="checked"';
					?>
					/><?php _e('No','sacf'); ?></li>
				</ul>
			</div><div class="sacf_clear"></div>		
								
			<label for="sacf_subject"><?php _e('Subject Requiered:', 'sacf')?></label>
			<div class="sacf_radioContainer">
				<ul class="sacf_radioList">
					<li><input type="radio" name="sacf_subjectReqGroup" id="sacf_subjectReq_yes" class="radioType" value="1" 
					<?php
						if(sacf_getVal('subject_req') == '1')
						echo 'checked="checked"';
					?>/><?php _e('Yes','sacf'); ?></li>
					<li><input type="radio" name="sacf_subjectReqGroup" id="sacf_subjectReq_no" class="radioType" value="0" 
					<?php
						if(sacf_getVal('subject_req') == '0')
						echo 'checked="checked"';
					?>
					/><?php _e('No','sacf'); ?></li>
				</ul>
			</div><div class="sacf_clear"></div>		
			
			<label for="sacf_subject"><?php _e('Phone Requiered:', 'sacf')?></label>
			<div class="sacf_radioContainer">
				<ul class="sacf_radioList">
					<li><input type="radio" name="sacf_phoneGroup" id="sacf_phone_yes" class="radioType" value="1" 
					<?php
						if(sacf_getVal('phone_req') == '1')
						echo 'checked="checked"';
					?>/><?php _e('Yes','sacf'); ?></li>
					<li><input type="radio" name="sacf_phoneGroup" id="sacf_phone_no" class="radioType" value="0" 
					<?php
						if(sacf_getVal('phone_req') == '0')
						echo 'checked="checked"';
					?>/><?php _e('No','sacf'); ?></li>
				</ul>
			</div><div class="sacf_clear"></div>		
			
			<label for="sacf_pMask"><?php _e('Phone Format (Mask):', 'sacf')?></label>
			<input type="text" id="sacf_pMask" class="smallField" name="sacf_pMask" value="<?php echo $options['phone_mask']; ?>" />
			<span class="sacf_msg"><?php _e('Enter only nines Ex. (999) 999-9999','sacf')?></span>
			
			<input type="hidden" name="sacf_form_submit" value="submit" />			
			<input class="button-primary" type="submit" name="Save" value="<?php _e('Update options', 'sacf'); ?>" id="submitbutton" />
			
			
	</form>
	
	<form id="sacf_font" method="POST" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			
		<p class="sacf-form-sub"><?php _e('Upload', 'sacf') ?></p>
		
		<label for="fontfile"><?php _e('Select a Font', 'sacf') ?></label> 
		<input type="file" name="userfile" id="fontfile">
		<div class="sacf_clear"></div>
			
		<div
		<?php 
		if( !isset( $_REQUEST['sacf_font_submit'] )) 
		echo 'style="display:none"'; ?>><p><?php 
			if(!$sacf_font_error)
			echo '<strong style="color:green">'.$sacf_font_msg;
			
			else
			echo '<strong style="color:red">'.$sacf_font_msg;
		?></strong></p></div>

		
		<input type="hidden" name="sacf_font_submit" value="submit" />			
		<input class="button-primary" type="submit" name="uploadFont" value="<?php _e('Upload Font','sacf') ?>" />
		
	</form>
</div>
