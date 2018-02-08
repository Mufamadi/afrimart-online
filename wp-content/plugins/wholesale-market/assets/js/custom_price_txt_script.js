

var ced_cwsm_allset = false;

jQuery(document.body).on('click','input:submit[name=save]',function(event){

	if(!ced_cwsm_allset)
	{
		event.stopPropagation(); // Stop stuff happening
	    event.preventDefault(); // Totally stop stuff happening	
		
		var valueToCheck = jQuery('#ced_cwsm_default_wm_price_txt').val();

		if(valueToCheck.indexOf('{*wm_price}') > -1)
		{
			ced_cwsm_allset = true;
			jQuery('input:submit[name=save]').trigger('click');
		}
		else
		{
			jQuery('span.cwsm_sorry_msg').remove();
			jQuery('#ced_cwsm_default_wm_price_txt').addClass('cwsm_sorry_msg_red');
			jQuery('#ced_cwsm_default_wm_price_txt').after('<span class="cwsm_sorry_msg">Sorry! you can\'t remove {*wm_price} from this textarea. </span>');
			jQuery('html, body').animate({
		        scrollTop: jQuery('#ced_cwsm_default_wm_price_txt').offset().top-140
		    }, 1000);
		}
	}		

});
