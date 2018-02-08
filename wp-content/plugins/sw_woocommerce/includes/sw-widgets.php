<?php 
/**
 * SW WooCommerce Widget Functions
 *
 * Widget related functions and widget registration
 *
 * @author 		flytheme
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* function getCategoryChildsFull( $parent_id, $pos, $array, $level, &$dropdown ) {
	for ( $i = $pos; $i < count( $array ); $i ++ ) {
		if ( $array[ $i ]->parent == $parent_id ) {
			$name = str_repeat( '- ', $level ) . $array[ $i ]->name;
			$value = $array[ $i ]->slug;
			$dropdown[] = array(
				'label' => $name,
				'value' => $value,
			);
			getCategoryChildsFull( $array[ $i ]->term_id, $i, $array, $level + 1, $dropdown );
		}
	}
} */

include_once( 'sw-widgets/sw-brand.php' );
include_once( 'sw-widgets/sw-slider-widget.php' );
include_once( 'sw-widgets/sw-slider-countdown-widget.php' );
include_once( 'sw-widgets/sw-woo-tab-category-slider-widget.php' );
include_once( 'sw-widgets/sw-woo-tab-slider-widget.php' );
include_once( 'sw-widgets/sw-category-slider-widget.php' );
include_once( 'sw-widgets/sw-related-upsell-widget.php' );
include_once( 'sw-woocommerce-shortcodes.php' );

/**
 * Register Widgets
**/
function sw_register_widgets() {
	register_widget( 'sw_brand_slider_widget' );
	register_widget( 'sw_woo_slider_widget' );	
	register_widget( 'sw_woo_slider_countdown_widget' );	
	register_widget( 'sw_woo_tab_cat_slider_widget' );
	register_widget( 'sw_woo_tab_slider_widget' );
	register_widget( 'sw_woo_cat_slider_widget' );
	register_widget( 'sw_related_upsell_widget' );
}
add_action( 'widgets_init', 'sw_register_widgets' );

/*
** Get timezone offset for countdown
*/
function sw_timezone_offset( $countdowntime ){
	$timeOffset = 0;	
	if( get_option( 'timezone_string' ) != '' ) :
		$timezone = get_option( 'timezone_string' );
		$dateTimeZone = new DateTimeZone( $timezone );
		$dateTime = new DateTime( "now", $dateTimeZone );
		$timeOffset = $dateTimeZone->getOffset( $dateTime );
	else :
		$dateTime = get_option( 'gmt_offset' );
		$dateTime = intval( $dateTime );
		$timeOffset = $dateTime * 3600;
	endif;
	$offset =  ( $timeOffset < 0 ) ? '-' . gmdate( "H:i", abs( $timeOffset ) ) : '+' . gmdate( "H:i", $timeOffset );
	
	$date = date( 'Y/m/d H:i:s', $countdowntime );
	$date1 = new DateTime( $date );
	$cd_date =  $date1->format('Y-m-d H:i:s') . $offset;
	
	return strtotime( $cd_date ); 
}

/*
** Sales label
*/
if( !function_exists( 'sw_label_sales' ) ){
	function sw_label_sales(){
		global $product, $post;
		$product_type = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_type() : $product->product_type;
		if( $product_type != 'variable' ) {
			$forginal_price 	= get_post_meta( $post->ID, '_regular_price', true );	
			$fsale_price 		= get_post_meta( $post->ID, '_sale_price', true );
			if( $fsale_price > 0 && $product->is_on_sale() ){ 
			$sale_off = 100 - ( ( $fsale_price/$forginal_price ) * 100 ); 
		?>
				<div class="sale-off">
					<?php echo '-' . round( $sale_off ).'%';?>
				</div>

				<?php } 
		}else{
			wc_get_template( 'single-product/sale-flash.php' );
		}
	}	
}

/*
** Check quickview
*/
function sw_quickview(){
	global $post;
	$quickview = 1;
	if( function_exists( 'revo_options' ) ){
		$quickview = revo_options()->getCpanelValue( 'product_quickview' );
	}
	$nonce = wp_create_nonce("revo_quickviewproduct_nonce");
	$link = admin_url('admin-ajax.php?ajax=true&amp;action=revo_quickviewproduct&amp;post_id='. esc_attr( $post->ID ).'&amp;nonce='.esc_attr( $nonce ) );
	$html = '<a href="'. esc_url( $link ) .'" data-fancybox-type="ajax" class="group fancybox fancybox.ajax">'.apply_filters( 'out_of_stock_add_to_cart_text', esc_html__( 'Quick View ', 'sw_woocommerce' ) ).'</a>';	
	return $html;
}

/*
** Trim Words
*/
function sw_trim_words( $title, $title_length = 0 ){
	$html = '';
	if( $title_length > 0 ){
		$html .= wp_trim_words( $title, $title_length, '...' );
	}else{
		$html .= $title;
	}
	echo esc_html( $html );
}

/*
** Sw Ajax URL
*/
function sw_ajax_url(){
	$ajaxurl = version_compare( WC()->version, '2.4', '>=' ) ? WC_AJAX::get_endpoint( "%%endpoint%%" ) : admin_url( 'admin-ajax.php', 'relative' );
	return $ajaxurl;
}

/*
** Check override template
*/
function sw_override_check( $path, $file ){
	$paths = '';
	if( locate_template( 'sw_woocommerce/'.$path . '/' . $file ) ){
		$paths = get_template_directory() . '/sw_woocommerce/' . $path . '/' . $file . '.php';
	}else{
		$paths = WCTHEME . '/' . $path . '/' . $file . '.php';
	}
	return $paths;
}

/*
** WooCommerce Compare Version
*/
if( !function_exists( 'sw_woocommerce_version_check' ) ) :
	function sw_woocommerce_version_check( $version = '3.0' ) {
		global $woocommerce;
		if( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}else{
			return false;
		}
	}
endif;
