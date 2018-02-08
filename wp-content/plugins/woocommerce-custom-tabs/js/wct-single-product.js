//#if IS_PREMIUM_2
jQuery( function( $ ) {
	
	var urlParam = function( pUrlHref, name ){

	    var results = new RegExp('[\?&]' + name + '=([^#&]*)').exec( pUrlHref );

	    if ( $(results).length > 0 ) {

	    	return results[1];

		} else {

			return 0;

		}

	};
	
	var urlTabAnchorByPrefix = function( pUrlHref, prefix ){

	    var results = new RegExp('[?#]' + prefix + '([^#&]*)').exec( pUrlHref );

	    if ( $(results).length > 0 ) {

	    	return results[1];

		} else {

			return 0;

		}

	};

	var triggerClickOnTab = function( pUrlHref ){

		//if #tab-...... tag exists in the url, then the appropriate product tab will be displayed
		if ( $('ul.tabs li.' + decodeURIComponent( urlTabAnchorByPrefix( pUrlHref, 'tab-' )) + '_tab a').length != 0 ) {

			$( 'ul.tabs li.' + decodeURIComponent( urlTabAnchorByPrefix( pUrlHref, 'tab-' )) + '_tab a' ).click();

		}

	};


	// wc_single_product_params is required to continue, ensure the object exists
	if ( typeof wc_single_product_params === 'undefined' ) {
		return false;
	}

	$( '.woocommerce-tabs' ).each( function() {

		triggerClickOnTab( window.location.href );
		
	});

	$(document).on('click', 'a', function (e) {

       //On descendants of .wc-tabs class should not been active this function, because it caused infinite loop.
		if($(e.target).closest('.wc-tabs').length || $(e.target).closest('.woocommerce-tabs').length)
			return;

		triggerClickOnTab( $(this).attr("href") );

	});

});
//#endif_2