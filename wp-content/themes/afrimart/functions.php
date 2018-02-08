<?php
if ( !defined('REVO_THEME') ){
	define( 'REVO_THEME', 'revo_theme' );
}

/**
 * Variables
 */
require_once ( get_template_directory().'/lib/plugin-requirement.php' );			// Custom functions
require_once ( get_template_directory().'/lib/activation.php' );
require_once ( get_template_directory().'/lib/defines.php' );
require_once ( get_template_directory().'/lib/mobile-layout.php' );
require_once ( get_template_directory().'/lib/classes.php' );		// Utility functions
require_once ( get_template_directory().'/lib/utils.php' );			// Utility functions
require_once ( get_template_directory().'/lib/init.php' );			// Initial theme setup and constants
require_once ( get_template_directory().'/lib/cleanup.php' );		// Cleanup
require_once ( get_template_directory().'/lib/nav.php' );			// Custom nav modifications
require_once ( get_template_directory().'/lib/widgets.php' );		// Sidebars and widgets
require_once ( get_template_directory().'/lib/scripts.php' );		// Scripts and stylesheets
require_once ( get_template_directory().'/lib/metabox.php' );	// Custom functions
if( class_exists( 'WooCommerce' ) ){
	require_once ( get_template_directory().'/lib/plugins/currency-converter/currency-converter.php' ); // currency converter
	require_once ( get_template_directory().'/lib/woocommerce-hook.php' );	// Utility functions
	
	if( class_exists( 'WC_Vendors' ) ) :
		require_once ( get_template_directory().'/lib/wc-vendor-hook.php' );			/** WC Vendor **/
	endif;
	
	if( class_exists( 'WeDevs_Dokan' ) ) :
		require_once ( get_template_directory().'/lib/dokan-vendor-hook.php' );			/** Dokan Vendor **/
	endif;
}

function revo_template_load( $template ){ 
	if( !is_user_logged_in() && revo_options()->getCpanelValue('maintaince_enable') ){
		$template = get_template_part( 'maintaince' );
	}else{
		if( class_exists( 'WooCommerce' ) ){
			if ( is_tax( 'product_cat' ) || is_post_type_archive( 'product' ) ) {				
				$template = get_template_part( 'archive', 'product' );
			}			
		}
	}
	return $template;
}
add_filter( 'template_include', 'revo_template_load' );

add_filter( 'revo_widget_register', 'revo_add_custom_widgets' );
function revo_add_custom_widgets( $revo_widget_areas ){
	if( class_exists( 'sw_woo_search_widget' ) ){
		$revo_widget_areas[] = array(
			'name' => esc_html__('Widget Search', 'revo'),
			'id'   => 'search',
			'before_widget' => '<div class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
		);
	}
	return $revo_widget_areas;
}