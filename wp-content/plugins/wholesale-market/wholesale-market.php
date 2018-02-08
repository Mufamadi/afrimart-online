<?php
/**
 * Plugin Name: Wholesale Market
 * Plugin URI: http://cedcommerce.com
 * Description: WooCommerce Extension that empowers your shop with the capability to create wholesale-users and give special privilege to them by setting product's wholesale-price for them.
 * Version: 2.0.12
 * Author: CedCommerce <plugins@cedcommerce.com>
 * Author URI: http://cedcommerce.com
 * Requires at least: 4.0
 * Tested up to: 4.8.3
 * Text Domain: wholesale-market
 * Domain Path: /language
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Check for multisite support
 **/
$activated = true;
if (function_exists('is_multisite') && is_multisite())
{
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) )
	{
		$activated = false;
	}
}
else
{
	/**
	 * Check if WooCommerce is active
	 **/
	if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
	{
		$activated = false;
	}
}

if($activated)
{
	define('CED_CWSM_PREFIX','ced_cwsm_');
	define( 'CED_CWSM_PLUGIN_BASE_FILE', plugin_basename( __FILE__ ) );
	
	// woocommerce is actiated
	require_once 'core/wholesale-customer-role/class-cwsm-mange-wholesale-role.php';
	require_once 'global/class-cwsm-common-functions.php';
	
	//code to give setting option on Plugins Section begins...
	$plugin = plugin_basename(__FILE__);
	add_filter( "plugin_action_links_$plugin", 'ced_cwsm_plugin_show_settings_link' );
	
	/**
	 * This function is used to show the settings tab.
	 * 
	 * @name ced_cwsm_plugin_show_settings_link()
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @param array $links
	 * @return array $links
	 * @link http://cedcommerce.com/
	 */
	function ced_cwsm_plugin_show_settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=wholesale_market">Go To Settings</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}
	//code to give setting option on Plugins Section ends...
	
	register_activation_hook( __FILE__ , 'ced_cwsm_activateFunc');
	register_deactivation_hook( __FILE__, 'ced_cwsm_deactivateFunc');
	register_uninstall_hook(__FILE__, 'ced_cwsm_uninstallFunc');
	
	/**
	 * This function is used to activate the plugin.
	 * 
	 * @name ced_cwsm_activateFunc()
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function ced_cwsm_activateFunc()
	{
		$cwsm_manage_wholesale_role_OBJ = CED_CWSM_Manage_Wholesale_Role::getInstance();
		$cwsm_manage_wholesale_role_OBJ->ced_cwsm_addWholesaleRole( 'ced_cwsm_wholesale_user' , __( 'Wholesale-Customer' , 'wholesale-market' ) );
		$cwsm_manage_wholesale_role_OBJ->ced_cwsm_addWholesaleCapability( 'ced_cwsm_wholesale_user' , 'have_wholesale_price' );
	
		$optionsArray = array(
			'ced_cwsm_enable_wholesale_market',
			'ced_cwsm_radio_whoCanSee',
		);
	
		if(!get_option(CED_CWSM_PREFIX.'enable_wholesale_market'))
		{
			update_option(CED_CWSM_PREFIX.'enable_wholesale_market','yes');
		}
		if(!get_option(CED_CWSM_PREFIX.'radio_whoCanSee'))
		{
			update_option(CED_CWSM_PREFIX.'radio_whoCanSee','_cwsm_radio_showWSuser');
		}

		if(!get_option(CED_CWSM_PREFIX.'default_wm_price_txt'))
		{
			update_option(CED_CWSM_PREFIX.'default_wm_price_txt','Wholesale Price : {*wm_price}');
		}
		if(!get_option(CED_CWSM_PREFIX.'custm_shop_txt'))
		{
			update_option(CED_CWSM_PREFIX.'custm_shop_txt','Wholesale Price : {*wm_price}');
		}
		if(!get_option(CED_CWSM_PREFIX.'custm_product_txt'))
		{
			update_option(CED_CWSM_PREFIX.'custm_product_txt','Wholesale Price : {*wm_price}');
		}

		/**
		* @since  1.0.8
		*/
		if(!get_option(CED_CWSM_PREFIX.'default_wsp_applied_txt'))
		{
			update_option(CED_CWSM_PREFIX.'default_wsp_applied_txt','Wholesale price is applied successfully on "{*wm_product_name}".');
		}
		if(!get_option(CED_CWSM_PREFIX.'wsp_applied_success_txt'))
		{
			update_option(CED_CWSM_PREFIX.'wsp_applied_success_txt','Wholesale price is applied successfully on "{*wm_product_name}" as {*wm_min_qty} or more units are in cart.');
		}
		if(!get_option(CED_CWSM_PREFIX.'wsp_applied_failure_txt'))
		{
			update_option(CED_CWSM_PREFIX.'wsp_applied_failure_txt','Wholesale price will be applicable on "{*wm_product_name}" after buying {*wm_qty_diff} more units.');
		}
	
	}
	
	function ced_cwsm_deactivateFunc()
	{
		//assigning customer role to wholesale-users begins...
		$ws_users = get_users( 'role=cwsm_wholesale_user' );
		foreach ($ws_users as $user)
		{
			$u = new WP_User( $user->ID );
			// Remove role
			$u->remove_role( 'ced_cwsm_wholesale_user' );
			// Add role
			$u->add_role( 'customer' );
		}
		//assigning customer role to wholesale-users ends...
	
		$cwsm_manage_wholesale_role_OBJ = CED_CWSM_Manage_Wholesale_Role::getInstance();
		$cwsm_manage_wholesale_role_OBJ->ced_cwsm_removeWholesaleCapability( 'ced_cwsm_wholesale_user' , 'have_wholesale_price' );
		$cwsm_manage_wholesale_role_OBJ->ced_cwsm_removeWholesaleRole( 'ced_cwsm_wholesale_user' );
	
		/*
		 * code to deal with options and meta-fields
		 */
		$keepPluginSetting = get_option(CED_CWSM_PREFIX.'keep_plugin_setting');
		if(empty($keepPluginSetting) || $keepPluginSetting == "no")
		{
			$optionsToDelArray = array(
					CED_CWSM_PREFIX.'enable_wholesale_market',
					CED_CWSM_PREFIX.'radio_whoCanSee',
					CED_CWSM_PREFIX.'show_in_price_column',
					CED_CWSM_PREFIX.'keep_plugin_setting',
					CED_CWSM_PREFIX.'keep_products_meta_fields'
			);
				
			$optionsToDelArray = apply_filters('ced_cwsm_add_options_to_delete_filter_dec', $optionsToDelArray);
				
			foreach ($optionsToDelArray as $optionDel)
			{
				delete_option($optionDel);
			}
		}
	
		$keepProMetaValues = get_option(CED_CWSM_PREFIX.'keep_products_meta_fields');
		if(empty($keepProMetaValues) || $keepProMetaValues == "no")
		{
			$metaFieldsToBeDeleted = array('ced_cwsm_wholesale_price');
			$metaFieldsToBeDeleted = apply_filters('ced_cwsm_add_meta_keys_to_be_deleted_dec', $metaFieldsToBeDeleted);
				
			foreach ($metaFieldsToBeDeleted as $metaFieldToBeDeleted)
			{
				delete_post_meta_by_key( $metaFieldToBeDeleted );
			}
		}
	}
	
	/**
	 * This function is used to manage the options and meta-fields.
	 * 
	 * @name ced_cwsm_uninstallFunc()
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function ced_cwsm_uninstallFunc()
	{
		$optionsToDelArray = array(
				CED_CWSM_PREFIX.'enable_wholesale_market',
				CED_CWSM_PREFIX.'radio_whoCanSee',
				CED_CWSM_PREFIX.'show_in_price_column',
				CED_CWSM_PREFIX.'keep_plugin_setting',
				CED_CWSM_PREFIX.'keep_products_meta_fields'
		);
	
		$optionsToDelArray = apply_filters('ced_cwsm_add_options_to_delete_filter', $optionsToDelArray);
	
		foreach ($optionsToDelArray as $optionDel)
		{
			delete_option($optionDel);
		}
	
		$metaFieldsToBeDeleted = array('ced_cwsm_wholesale_price');
	
		$metaFieldsToBeDeleted = apply_filters('ced_cwsm_add_meta_keys_to_be_deleted', $metaFieldsToBeDeleted);
	
		foreach ($metaFieldsToBeDeleted as $metaFieldToBeDeleted)
		{
			delete_post_meta_by_key( $metaFieldToBeDeleted );
		}
	}
	require_once 'class-cwsm-core-class.php';
}
else
{
	//Woocommerece is not activated
	//uninstall your plugin
	add_action( 'admin_init', 'ced_cwsm_basic_plugin_activation_failure' );
	function ced_cwsm_basic_plugin_activation_failure() {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	add_action( 'admin_notices', 'ced_cwsm_basic_plugin_activation_failure_admin_notice' );

	/**
	 * This function is used to display failure message if WooCommerce is deactivated.
	 * 
	 * @name ced_cwsm_basic_plugin_activation_failure_admin_notice()
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function ced_cwsm_basic_plugin_activation_failure_admin_notice() {
	    ?>
	    <div class="notice notice-error is-dismissible">
	        <p><?php _e( 'Please Install WooCommerece First.', 'wholesale-market' ); ?></p>
	    </div>
	    <style>div#message.updated{ display: none; }</style>
	    <?php
	}
}
?>