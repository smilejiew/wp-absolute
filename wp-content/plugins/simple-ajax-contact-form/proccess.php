<?php    
	header('Cache-Control: no-cache, must-revalidate');
	header('Content-type: application/json');

//Gets values from form
$name = esc_html($_POST['scf-name']);
$email = $_POST['scf-email'];
$phone = $_POST['scf-phone'];
$comment =  esc_textarea($_POST['scf-comment']);
$captcha =  strtoupper($_POST['scf-captcha']);
$subject  ='';

//Gets values from database
$options = get_option('sacf_settings');
$return['subject'] = $options['subject'];
$return['subject_req'] = $options['subject_req'];
$return['phone_req'] = $options['phone_req'];
$return['captcha'] = $options['captcha'];

$return['error'] = '';
$return['msg'] = '';

/******************  Validates input fields****************/
//if Name, email and comment field are empty, display error message
if(empty($name) || empty($email) || empty($comment))
{
	$return['error'] = 1;
	$return['msg'] .= sacf_displayError('empty');
}

//if email IS NOT empty proceed with validation
if(!empty($email))
{
	//if email format is not valid display error
	if(!is_email($email))
	{
		$return['error'] = 1;
		$return['msg'] .= sacf_displayError('email_nv');		
	}
}

//if phone input field is required
if($return['phone_req'] == '1')
{
	//if phone input field is empty, display error
	if (empty($phone))
	{
		$return['error'] = 1;
		$return['msg'] .= sacf_displayError('e_phone');
	}	
}

//if subject input field is visible
if($return['subject'] == '1')
{
	//if subject input field is required and empty, display error
	if($return['subject_req'] == '1' && empty($_POST['scf-subject']))
	{
		$return['error'] = 1;
		$return['msg'] .= sacf_displayError('sub_req');
	}
	else
	$subject = esc_html($_POST['scf-subject']);
}

//if captcha is visible, proceed with validation 
if($return['captcha'] == '1')
{
		
		if(empty($captcha))
		{
			$return['error'] = 1;
			$return['msg'] .= sacf_displayError('e_captcha');
		}

		else
		{
			//if value entered by user is not equal to our captcha, display error
			if($captcha != $_SESSION['secureWord']) 
			{ 
				$return['error'] = 1;
				$return['msg'] .= sacf_displayError('w_captcha');
			}			
		}
}

/*if no errors, send email and display success message*/
if(!$return['error'])
{
	$return['error'] = 0;
	$return['msg'] = sacf_displayMsg('success');	
	
	sacf_send_email($name,$email,$phone,$subject,$comment);
}

echo json_encode($return);
die();
?>