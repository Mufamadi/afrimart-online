jQuery(document).ready(function(){
	
	
	if(jQuery('#last_tab').val() == ''){

		jQuery('.revo-opts-group-tab:first').slideDown('fast');
		jQuery('#revo-opts-group-menu li:first').addClass('active');
	
	}else{
		
		tabid = jQuery('#last_tab').val();
		jQuery('#'+tabid+'_section_group').slideDown('fast');
		jQuery('#'+tabid+'_section_group_li').addClass('active');
		
	}
	
	
	jQuery('input[name="'+revo_opts.opt_name+'[defaults]"]').click(function(){
		if(!confirm(revo_opts.reset_confirm)){
			return false;
		}
	});
	
	jQuery('.revo-opts-group-tab-link-a').click(function(){
		relid = jQuery(this).attr('data-rel');
		
		jQuery('#last_tab').val(relid);
		
		jQuery('.revo-opts-group-tab').each(function(){
			if(jQuery(this).attr('id') == relid+'_section_group'){
				jQuery(this).show();
			}else{
				jQuery(this).hide();
			}
			
		});
		
		jQuery('.revo-opts-group-tab-link-li').each(function(){
				if(jQuery(this).attr('id') != relid+'_section_group_li' && jQuery(this).hasClass('active')){
					jQuery(this).removeClass('active');
				}
				if(jQuery(this).attr('id') == relid+'_section_group_li'){
					jQuery(this).addClass('active');
				}
		});
	});
	
	
	
	
	if(jQuery('#revo-opts-save').is(':visible')){
		jQuery('#revo-opts-save').delay(4000).slideUp('slow');
	}
	
	if(jQuery('#revo-opts-imported').is(':visible')){
		jQuery('#revo-opts-imported').delay(4000).slideUp('slow');
	}	
	
	jQuery('input, textarea, select').change(function(){
		jQuery('#revo-opts-save-warn').slideDown('slow');
	});
	
	
	jQuery('#revo-opts-import-code-button').click(function(){
		if(jQuery('#revo-opts-import-link-wrapper').is(':visible')){
			jQuery('#revo-opts-import-link-wrapper').fadeOut('fast');
			jQuery('#import-link-value').val('');
		}
		jQuery('#revo-opts-import-code-wrapper').fadeIn('slow');
	});
	
	jQuery('#revo-opts-import-link-button').click(function(){
		if(jQuery('#revo-opts-import-code-wrapper').is(':visible')){
			jQuery('#revo-opts-import-code-wrapper').fadeOut('fast');
			jQuery('#import-code-value').val('');
		}
		jQuery('#revo-opts-import-link-wrapper').fadeIn('slow');
	});
	
	
	
	
	jQuery('#revo-opts-export-code-copy').click(function(){
		if(jQuery('#revo-opts-export-link-value').is(':visible')){jQuery('#revo-opts-export-link-value').fadeOut('slow');}
		jQuery('#revo-opts-export-code').toggle('fade');
	});
	
	jQuery('#revo-opts-export-link').click(function(){
		if(jQuery('#revo-opts-export-code').is(':visible')){jQuery('#revo-opts-export-code').fadeOut('slow');}
		jQuery('#revo-opts-export-link-value').toggle('fade');
	});
	
	

	
	
	
});