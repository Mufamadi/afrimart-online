<?php get_header(); ?>
<?php 
	$revo_sidebar_template	= get_post_meta( get_the_ID(), 'page_sidebar_layout', true );
	$revo_sidebar 					= get_post_meta( get_the_ID(), 'page_sidebar_template', true );
?>

	<div class="revo_breadcrumbs">
		<div class="container">
			<div class="listing-title">			
				<h1><span><?php revo_title(); ?></span></h1>				
			</div>
			<?php
				if (!is_front_page() ) {
					if (function_exists('revo_breadcrumb')){
						revo_breadcrumb('<div class="breadcrumbs custom-font theme-clearfix">', '</div>');
					} 
				} 
			?>
		</div>
	</div>	
	<div class="container">
		<div class="row">
		<?php 
			if ( is_active_sidebar( $revo_sidebar ) && $revo_sidebar_template != 'right' && $revo_sidebar_template !='full' ):
			$revo_left_span_class = 'col-lg-'.revo_options()->getCpanelValue('sidebar_left_expand');
			$revo_left_span_class .= ' col-md-'.revo_options()->getCpanelValue('sidebar_left_expand_md');
			$revo_left_span_class .= ' col-sm-'.revo_options()->getCpanelValue('sidebar_left_expand_sm');
		?>
			<aside id="left" class="sidebar <?php echo esc_attr( $revo_left_span_class ); ?>">
				<?php dynamic_sidebar( $revo_sidebar ); ?>
			</aside>
		<?php endif; ?>
		
			<div id="contents" role="main" class="main-page <?php revo_content_page(); ?>">
				<?php
				get_template_part('templates/content', 'page')
				?>
			</div>
			<?php 
			if ( is_active_sidebar( $revo_sidebar ) && $revo_sidebar_template != 'left' && $revo_sidebar_template !='full' ):
				$revo_left_span_class = 'col-lg-'.revo_options()->getCpanelValue('sidebar_left_expand');
				$revo_left_span_class .= ' col-md-'.revo_options()->getCpanelValue('sidebar_left_expand_md');
				$revo_left_span_class .= ' col-sm-'.revo_options()->getCpanelValue('sidebar_left_expand_sm');
			?>
				<aside id="right" class="sidebar <?php echo esc_attr($revo_left_span_class); ?>">
					<?php dynamic_sidebar( $revo_sidebar ); ?>
				</aside>
			<?php endif; ?>
		</div>		
	</div>
<?php get_footer(); ?>

