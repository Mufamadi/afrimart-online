<?php

/**
 * Add Theme Options page.
 */
function revo_theme_admin_page(){
	add_theme_page(
		esc_html__('Theme Options', 'revo'),
		esc_html__('Theme Options', 'revo'),
		'manage_options',
		'revo_theme_options',
		'revo_theme_admin_page_content'
	);
}
add_action('admin_menu', 'revo_theme_admin_page', 49);

function revo_theme_admin_page_content(){ ?>
	<div class="wrap">
		<h2><?php esc_html_e( 'Revo Advanced Options Page', 'revo' ); ?></h2>
		<?php do_action( 'revo_theme_admin_content' ); ?>
	</div>
<?php
}