<?php
/**
 * WooCommerce Admin Settings Class
 *
 * @author   CedCommerce <plugins@cedcommerce.com>
 * @category Class
 * @package  WooCommerce/Admin
 * @version  2.0.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(!class_exists('WC_Admin_Settings')){
	require_once( ABSPATH . 'wp-content/plugins/woocommerce/includes/admin/class-wc-admin-settings.php' );
}

add_action( 'admin_menu', 'my_plugin_menu' );
/**
 * This function is used to add Wholesale Market menu in the plugin.
 * @name my_plugin_menu()
 * @author CedCommerce <plugins@cedcommerce.com>
 * @link  http://www.cedcommerce.com/
 */
function my_plugin_menu() {
	add_menu_page( __( 'Wholesale Market', 'wholesale-market' ), __( 'Wholesale Market', 'wholesale-market' ), 'manage_options', 'wholesale_market', 'my_plugin_options', null, '55.5' );
}
/**
 * This function is used to add instance of class.
 * @name my_plugin_options()
 * @author CedCommerce <plugins@cedcommerce.com>
 * @link  http://www.cedcommerce.com/
 */
function my_plugin_options() {
	CED_CWSM_Admin_Settings::output();
}

class CED_CWSM_Admin_Settings {

	/**
	 * Setting pages.
	 *
	 * @var array
	 */
	private static $settings = array();

	/**
	 * Error messages.
	 *
	 * @var array
	 */
	private static $errors   = array();

	/**
	 * Update messages.
	 *
	 * @var array
	 */
	private static $messages = array();

	/**
	 * Settings page.
	 *
	 * Handles the display of the main woocommerce settings page in admin.
	 */
	public static function output() {
		global $ced_cwsm_current_section, $ced_cwsm_current_tab, $ced_cwsm_page;
		$ced_cwsm_page = 'wholesale_market';
		// Send Suggestion Sticky Header
		do_action( 'ced_cwsm_send_suggetion_sticky_form' );
		// Get current tab/section
		$ced_cwsm_current_tab     = empty( $_GET['tab'] ) ? 'ced_cwsm_basic' : sanitize_title( $_GET['tab'] );
		$ced_cwsm_current_section = empty( $_REQUEST['section'] ) ? '' : sanitize_title( $_REQUEST['section'] );

		// Get tabs for the settings page
		$tabs = array();
		$tabs['ced_cwsm_basic'] = __( 'Basic Features', 'wholesale-market' );
		$tabs = apply_filters( 'ced_cwsm_settings_tabs_array', $tabs );
		
		do_action( 'after_ced_cwsm_admin_settings_initiated' );

		include 'html-setting-tabbed-header.php';
	}

	/**
	 * This function is used to display the settings tab-Wholesale Market of the plugin.
	 * @name get_sections()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	public static function get_sections() {
		global $ced_cwsm_current_tab;
		return apply_filters( 'ced_cwsm_sections_' . $ced_cwsm_current_tab, array() );
	}

	/**
	 * This function is used to display the settings on the settings tab of the plugin.
	 * @name output_sections()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	public static function output_sections() {
		global $ced_cwsm_current_section, $ced_cwsm_current_tab, $ced_cwsm_page;

		$sections = self::get_sections();
		
		if ( empty( $sections ) || 0 === sizeof( $sections ) ) {
			return;
		}

		if( $ced_cwsm_current_section == '' ) {
			foreach ($sections as $key => $value) {
				$ced_cwsm_current_section = $key;
				break;
			}
		}
		
		echo '<ul class="subsubsub">';

		$array_keys = array_keys( $sections );

		foreach ( $sections as $id => $label ) {
			echo '<li><a href="' . admin_url( 'admin.php?page='.$ced_cwsm_page.'&tab=' . $ced_cwsm_current_tab . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $ced_cwsm_current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
		}
		echo '</ul><br class="clear" />';
	}

	/**
	 * This function is used to record the settings made on the settings tab of the plugin.
	 * @name get_sttings()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	public static function get_settings() {
		global $ced_cwsm_current_tab;
		return apply_filters( 'ced_cwsm_settings_' . $ced_cwsm_current_tab, array() );
	}

	/**
	 * This function is used to call the changes made on the settings tab of the plugin.
	 * @name output_settings()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	public static function output_settings() {
		$settings = self::get_settings();
		WC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * This function is used to save the settings made on the settings tab of the plugin.
	 * @name save()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	public static function save() {
		$allow = true;
		$allow = apply_filters( "ced_cwsm_allow_submit", $allow );
		if($allow)
		{
			$settings = self::get_settings();
			WC_Admin_Settings::save_fields( $settings );
			self::add_message( __( 'Your settings have been saved.', 'wholesale-market' ) );
		}
		if($allow==false){
			self::add_message( __( 'Your settings have been saved.', 'wholesale-market' ) );
		}
	}

	/**
	 * Add a message.
	 * @param string $text
	 */
	public static function add_message( $text ) {
		self::$messages[] = $text;
	}

	/**
	 * Add an error.
	 * @param string $text
	 */
	public static function add_error( $text ) {
		self::$errors[] = $text;
	}

	/**
	 * Output messages + errors.
	 * @return string
	 */
	public static function show_messages() {
		if ( sizeof( self::$errors ) > 0 ) {
			foreach ( self::$errors as $error ) {
				echo '<div id="message" class="error inline"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
			}
		} elseif ( sizeof( self::$messages ) > 0 ) {
			foreach ( self::$messages as $message ) {
				echo '<div id="message" class="updated inline"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
			}
		}
	}
}
?>