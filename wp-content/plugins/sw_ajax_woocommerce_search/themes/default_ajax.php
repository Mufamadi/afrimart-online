<?php
/**
 * Layout Default ajax
 * @version     1.0.0
 **/
	$id_category = (isset($_GET['filter_category_id']) && $_GET['filter_category_id'] ) ? $_GET['filter_category_id'] : 0;
	$filter_name = (isset($_GET['filter_name']) && $_GET['filter_name'] ) ? $_GET['filter_name'] : '';
	$limit = (isset($_GET['limit']) && $_GET['limit'] ) ? $_GET['limit'] : 5;
	if($id_category == 'all')
		$tax_query = array();
	else
		$tax_query = array(
			array(
				'taxonomy' => 'product_cat',
				'field' 	 => 'slug', //This is optional, as it defaults to 'term_id'
				'terms'    => $id_category,
				'operator' => 'AND' // Possible values are 'IN', 'NOT IN', 'AND'.
			)
		);
	$filter_name = str_replace( "%20"," ",$filter_name );
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts' => 1,	  
		's' => $filter_name,
		'tax_query' => $tax_query,
		'posts_per_page'=> $limit
	);	
	
	$list = new WP_Query( $args );

	$json = array();
	if ( $list->have_posts() ) {
		while( $list->have_posts() ): $list->the_post();
		global $product, $post;
		$product_id = ( version_compare( WC()->version, '3.0', '>=' ) ) ? $product->get_id() : $product->id;
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
		$json[] = array(
			'product_id' => $product_id,
			'name'       => $product->get_title(),		
			'image'		 	 => $image[0],
			'link'		   => get_permalink( $product_id ),
			'price'      => $product->get_price_html(),
		);			
		endwhile;
	}
	die ( json_encode( $json ) );
