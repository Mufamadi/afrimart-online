<?php
/**
 * Single Product Thumbnails
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version    3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $woocommerce;
$revo_featured_video = get_post_meta( $post->ID, 'featured_video_product', true );
$attachment_ids = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_gallery_image_ids() : $product->get_gallery_attachment_ids();
if ( $attachment_ids ) {
	if( has_post_thumbnail() ) :
		$image_id = get_post_thumbnail_id();
		array_unshift( $attachment_ids, $image_id );
	endif;
?>
	<div class="slider product-responsive-thumbnail" id="product_thumbnail_<?php echo esc_attr( $post->ID ); ?>">
		<?php if( $revo_featured_video != '' ) { ?>
		<div class="item-thumbnail-product">
			<div class="thumbnail-wrapper item-video-thumb">
				<img src="http://img.youtube.com/vi/<?php echo esc_attr( $revo_featured_video ); ?>/2.jpg" alt="<?php echo esc_attr( $post->post_title ); ?>"/>
			</div>
		</div>
	<?php }
		foreach ( $attachment_ids as $attachment_id ) { 
	?>
		<div class="item-thumbnail-product">
			<div class="thumbnail-wrapper">
			<?php	echo wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), false, array( 'class' => 'slick-current' ) );	?>
			</div>
		</div>
		<?php
		}
	?>
	</div>
<?php
}
?>
	
