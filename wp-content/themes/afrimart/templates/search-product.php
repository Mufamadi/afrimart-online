<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
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
				<li class="item col-lg-3 col-md-4 col-sm-4 col-xs-6">
					<div class="item-wrap">
						<div class="item-detail">										
							<div class="item-img products-thumb">											
								<!-- quickview & thumbnail  -->
								<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
							</div>										
							<div class="item-content">
								<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a></h4>								
																			
								<!-- rating  -->
								<?php 
									$rating_count = $product->get_rating_count();
									$review_count = $product->get_review_count();
									$average      = $product->get_average_rating();
								?>
								<div class="reviews-content">
									<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*13 ).'px"></span>' : ''; ?></div>
								</div>	
								<!-- end rating  -->
								<?php if ( $price_html = $product->get_price_html() ){?>
								<div class="item-price">
									<span>
										<?php echo $price_html; ?>
									</span>
								</div>
								<?php } ?>
								<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
							</div>
						</div>
					</div>
				</li>
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