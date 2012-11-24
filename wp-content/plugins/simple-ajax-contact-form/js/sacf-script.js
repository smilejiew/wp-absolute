jQuery(document).ready(function(){
    
	  //jQuery("#scf-phone").mask("(999) 999-9999");
    observeContactFrom();
});

function observeContactFrom(){
    jQuery('#scf-form').off('submit');
		jQuery('#scf-form').on('submit',function() 
		{
			jQuery('#loader').show();
			var values = jQuery(this).serialize();
			
			jQuery.post(the_ajax_script.ajaxurl, values, 
			function(data)
			{			
				if (data.error == 0)
				{
					if(jQuery('#scf-msg').is(':visible'))
					{
						jQuery('#loader').hide();
						jQuery('#scf-msg').removeClass('scf-msg-error').html(data.msg).delay(3000).slideToggle('fast',function()
						{
							clearForm(data.subject,data.captcha);							
						});					
					}
					else
					{
						jQuery('#loader').hide();
						jQuery('#scf-msg').slideToggle('fast',function()
						{
							jQuery(this).html(data.msg).delay(3000).slideToggle('fast',function()
							{
								clearForm(data.subject,data.captcha);
							});					
						});
					}
				}			
				else
				{
					jQuery('#loader').hide();
					if(jQuery('#scf-msg').is(':visible'))
					{
						jQuery('#scf-msg').html(data.msg);
					}
					else
					{
						jQuery('#scf-msg').slideToggle('fast',function()
						{
							jQuery(this).addClass('scf-msg-error').html(data.msg);
						});
					}
				}			
			},'json');	
			
			//alert(values);
			
			return false;
		});
}

/*Clear form fileds*/
 function clearForm(subject,captcha)
 {
	jQuery('#scf-name').val('');
	
	jQuery('#scf-phone').val('');
	
	if(subject == '1')
	jQuery('#scf-subject').val('');
	
	jQuery('#scf-email').val('');
	jQuery('#scf-comment').val('');
	
	if(captcha == '1')
	{
		jQuery('#scf-captcha').val('');
		reloadCaptcha();
	}
	
	jQuery('#successMsg').remove();
 }
