<?php
session_start();
require('../../../wp-blog-header.php');

//Gets values from database
$options = get_option('sacf_settings');
$new_font = $options['c_font'];
$new_color = $options['c_color'];
$new_size = $options['c_size'];

header("Content-type: image/png");

//Change color from HEX to RGB
$color = sacf_HexToRGB($options['c_color']);

//Creates a empty session
$_SESSION['secureWord']='';

//select a font type from the font folder
$font = 'fonts/'.$new_font;  

//Dimension of the image captcha 90 width, 23 height
$img = imagecreate(90, 23); 

//selects background color
imagecolorallocate($img, 255, 255, 255);  

//select font color      
$blue = imagecolorallocate($img, $color['r'], $color['g'], $color['b']);   

//loop 6 times, one for each character of the captcha
for($i=0;$i<=5;$i++) 
{
		//characters to be used
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
		
		//Gets length of our chraracter
		$size = strlen($chars);
		
		//Pick a random character
		$str = $chars[rand(0, $size - 1)];		
	    
	//stores character in our session. Will be used to compare with user's input
    $_SESSION['secureWord'].=$str;
	
	//gets a random angle
    $angle=rand(-25, 25);
	
	//Compiles everything
    imagettftext($img, $new_size, $angle, 11+12*$i, 15, $blue, $font, $str);        
}


imagepng($img);
imagedestroy($img);
?>