<?php	
	$title1 = ( !isset( $widget_id ) ) ? $title : '';
	$id_widget = $this->generateID();
	$terms =	get_terms( 'product_cat', 
	array(  
			'hide_empty' => false,	
			'parent' 		 => 0	
	));	
	$category_id  = ( isset($_GET['category'] ) && $_GET['category'] ) ? $_GET['category'] : '';
	$width_image  = ( $width_image && is_numeric($width_image ) ) 		 ? $width_image  : 50;
	$height_image = ( $height_image && is_numeric($height_image ) )		 ? $height_image  : 50;
	$ajaxurl 			= version_compare( WC()->version, '2.4', '>=' ) ? WC_AJAX::get_endpoint( "%%endpoint%%" ) : admin_url( 'admin-ajax.php', 'relative' );
?>
<div class="revo_top swsearch-wrapper clearfix">
<div class="top-form top-search <?php echo ( !$show_category ) ? 'non-category' : ''; ?>">
	<div class="topsearch-entry">
		<?php if( $show_title ) : ?>
			<h3><?php echo $title1; ?></h3>
		<?php endif; ?>
		
		<form method="GET" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
		<div id="<?php echo esc_attr( $id_widget ); ?>" class="search input-group" 
			data-height_image="<?php echo esc_attr( $height_image ); ?>" 
			data-width_image="<?php echo esc_attr( $width_image ); ?>" 
			data-show_image="<?php echo esc_attr( $show_image ) ; ?>" 
			data-show_price="<?php echo esc_attr( $show_price ); ?>" 
			data-character="<?php echo esc_attr( $character ); ?>" 
			data-limit="<?php echo esc_attr( $limit ); ?>"
			data-admin_url="<?php echo esc_url( $ajaxurl ); ?>"
			data-ajax_search="<?php echo esc_attr( $ajax_search ); ?>"
			data-no_result="<?php echo esc_html__( 'No Result', 'sw_ajax_woocommerce_search' ) ; ?>"	
			data-text_price="<?php echo esc_html__( 'Price', 'sw_ajax_woocommerce_search' ) ; ?>"	
			>
			<?php if( $terms && $show_category ){ ?>
			<div class="cat-wrapper">
				<label class="label-search">
					<select name="category" class="s1_option">
						<option value=""><?php echo esc_html__( 'All Category','sw_ajax_woocommerce_search' ) ; ?></option>
						<?php foreach($terms as $term) { ?>
							<?php if ($term->slug == $category_id) { ?>
							<option value="<?php echo esc_attr( $term->slug ); ?>" selected="selected"><?php echo $term->name; ?></option>
							<?php } else { ?>
							<option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo $term->name; ?></option>
							<?php } ?>
							<?php
								$terms_vl1 =	get_terms( 'product_cat', 
								array( 
										'parent' => '', 
										'hide_empty' => false,
										'parent' 		=> $term->term_id, 
								));						
							?>	
							
							<?php foreach ( $terms_vl1 as $term_vl1 ) { ?>
								<?php if ( $term_vl1->slug == $category_id ) { ?>
								<option value="<?php echo esc_attr( $term_vl1->slug ); ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $term_vl1->name; ?></option>
								<?php } else { ?>
								<option value="<?php echo esc_attr( $term_vl1->slug ); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $term_vl1->name; ?></option>
								<?php } ?>
									<?php
										$terms_vl2 =	get_terms( 'product_cat', 
										array( 
												'parent' 		 => '', 
												'hide_empty' => false,
												'parent' 		 => $term_vl1->term_id, 
										));	
									?>					
									<?php foreach ( $terms_vl2 as $term_vl2 ) { ?>
									
									<?php if ( $term_vl2->term_id == $category_id ) { ?>
									<option value="<?php echo esc_attr( $term_vl2->slug ); ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $term_vl2->name; ?></option>
									<?php } else { ?>
									<option value="<?php echo  esc_attr( $term_vl2->slug ); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $term_vl2->name; ?></option>
									<?php } ?>						
								<?php } ?>
							<?php } ?>					
						<?php } ?>
					</select>
				</label>
			</div>
			<?php } ?>
			<div class="content-search">
				<input class="autosearch-input" type="text" value="<?php echo ( ( isset( $_GET['s'] ) && $_GET['s'] ) ? ( $_GET['s'] ) : "" ); ?>" size="50" autocomplete="off" placeholder="<?php echo esc_html__( 'Search','sw_ajax_woocommerce_search' ) ; ?>" name="s">	
				<div class="icon-loading"></div>
			</div>
				<span class="input-group-btn">
				<button type="submit" class="fa fa-search button-search-pro form-button"></button>
			</span>
			<input name="search_posttype" value="product" type="hidden">
		</div>
		</form>
	</div>
</div>
</div>