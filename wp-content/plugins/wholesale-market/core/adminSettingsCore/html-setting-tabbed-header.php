<?php
/**
 * Admin View: Settings
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wrap woocommerce">
	<!-- Display Wholesale Market Settings Tab -->
	<form method="<?php echo esc_attr( apply_filters( 'woocommerce_settings_form_method_tab_' . $ced_cwsm_current_tab, 'post' ) ); ?>" id="mainform" action="" enctype="multipart/form-data">
		<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
			<?php
				foreach ( $tabs as $name => $label ) {
					echo '<a href="' . admin_url( 'admin.php?page='.$ced_cwsm_page.'&tab=' . $name ) . '" class="nav-tab ' . ( $ced_cwsm_current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
				}
				do_action( 'woocommerce_settings_tabs' );
			?>
		</nav>
		<h1 class="screen-reader-text"><?php echo esc_html( $tabs[ $ced_cwsm_current_tab ] ); ?></h1>
		<?php
			self::output_sections();

			// Save settings if data has been posted
			if ( ! empty( $_POST ) ) {
				self::save();
			}

			// Add any posted errors
			if ( ! empty( $_GET['wc_error'] ) ) {
				self::add_error( stripslashes( $_GET['wc_error'] ) );
			}
			// Add any posted messages
			if ( ! empty( $_GET['wc_message'] ) ) {
				self::add_message( stripslashes( $_GET['wc_message'] ) );
			}

			self::show_messages();

			self::output_settings();
		?>
		<p class="submit">
			<?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
				<!-- Save settings -->
				<input name="save" class="button-primary woocommerce-save-button" type="submit" value="<?php _e( 'Save changes', 'wholesale-market' ); ?>" />
			<?php endif; ?>
			<?php wp_nonce_field( 'woocommerce-settings' ); ?>
		</p>
	</form>
</div>
