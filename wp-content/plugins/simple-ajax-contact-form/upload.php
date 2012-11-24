<?php
/*
 * upload.php
 * 
 * Description: upload font files
 *	Last Updated: February 11, 2012
 */
 require('../../../wp-blog-header.php');
 
      $allow = array('.ttf'); // These will be the types of file that will pass the validation.
      $max_filesize = 524288; // Maximum filesize in BYTES (currently 0.5MB).
      $upload_path = WP_PLUGIN_DIR ."/simple-ajax-contact-form/fonts/"; // The place the files will be uploaded to (currently a 'files' directory).
	  
	$r_error = 0;
	$r_msg ='';
	
   $filename = $_FILES['userfile']['name']; // Get the name of the file (including file extension).
   $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
 
   // Check if the filetype is allowed, if not DIE and inform the user.
   if(!in_array($ext,$allow))
   {
      
	  $r_error =1;
	  $r_msg = 'Only font files (.ttf) are allowed.';
	}
 
   // Now check the filesize, if it is too large then DIE and inform the user.
   if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
      {
	  $r_error =1;
	  $r_msg='The file you attempted to upload is too large.';
	  }
 
   // Check if we can upload to the specified path, if not DIE and inform the user.
   if(!is_writable($upload_path))
      {
		$r_error =1;
		$r_msg='You cannot upload to the specified directory, please CHMOD it to 777.';
		}	
 
	if(!$r_error)
	{
   // Upload the file to your specified path.
	if(move_uploaded_file($_FILES['userfile']['tmp_name'],$upload_path . $filename))
        $r_msg='Your file upload was successful'; // It worked.
      else
         $r_msg='There was an error during the file upload.  Please try again.'; 
	}
?>

<script language="javascript" type="text/javascript">

window.top.window.returnMsg("<?php echo $r_msg; ?>","<?php echo $r_error; ?>");

</script>  