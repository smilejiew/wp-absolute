jQuery(document).ready(function()
{
   //jQuery('#ilctabscolorpicker').hide();
    jQuery('#ilctabscolorpicker').farbtastic("#sacf_cColor");
    jQuery("#sacf_cColor").focus(function()
	{
		jQuery('#ilctabscolorpicker').show('slow');
	}).blur(function()
	{
		jQuery('#ilctabscolorpicker').hide('slow');
	});
	
	
});