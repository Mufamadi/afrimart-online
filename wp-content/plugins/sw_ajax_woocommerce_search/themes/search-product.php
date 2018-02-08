<?php get_header(); ?>

<?php
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$product_cat = isset( $_GET['category'] ) ? $_GET['category'] : '';
	$s = isset( $_GET['s'] ) ? $_GET['s'] : '';	
	$args_product = array(
		's' => $s,
		'post_type'	=> 'product',
		'posts_per_page' => 12,
		'paged' => $paged
	);
	if( $product_cat != '' ){
		$args_product['tax_query'] = array(
			array(
				'taxonomy'	=> 'product_cat',
				'field'		=> 'slug',
				'terms'	=> $product_cat				
			)
		);
	}
?>
<div class="content-list-category container">
	<div class="content_list_product">
		<div class="products-wrapper">		
		<?php
			$product_query = new wp_query( $args_product );
			if( $product_query -> have_posts() ){
		?>
			<ul id="loop-products" class="products-loop row clearfix grid-view grid">
			<?php 
				while( $product_query -> have_posts() ) : $product_query -> the_post(); 
				global $product, $post;
				$product_id = $post->ID;
			?>
				<?php wc_get_template_part( 'content', 'product' ); ?>
				<?php	endwhile;
				
			?>
			</ul>
			<!--Pagination-->
			<?php if ($product_query->max_num_pages > 1) : ?>
			<div class="pag-search ">
				<div class="pagination nav-pag pull-right">
					<?php 
						echo paginate_links( array(
							'base' => esc_url_raw( str_replace( 999999999, '%#%', get_pagenum_link( 999999999, false ) ) ),
							'format' => '?paged=%#%',
							'current' => max( 1, get_query_var('paged') ),
							'total' => $product_query->max_num_pages,
							'end_size' => 1,
							'mid_size' => 1,
							'prev_text' => '<i class="fa fa-angle-left"></i>',
							'next_text' => '<i class="fa fa-angle-right"></i>',
							'type' => 'list',
							
						) );
					?>
				</div>
			</div>
	<?php endif;wp_reset_postdata(); ?>
	<!--End Pagination-->
	<?php 
		}else{
			get_template_part( 'templates/no-results' );
		}
	?>
		</div>
	</div>
</div>

<?php get_footer(); ?>