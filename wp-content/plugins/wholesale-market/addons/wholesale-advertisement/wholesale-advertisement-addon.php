<?php
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists( 'Wholesale_advertisement' ) )
{
	/**
	 * This is class for add feature of register user as wholesale user .
	 *
	 * @name    Wholesale_user_register_notification
	 * @category Class
	 * @author   CedCommmerce <plugins@cedcommerce.com>
	 */
	class Wholesale_advertisement
	{
		/**
		 * This is construct of class
		 *
		 * @author CedCommmerce
		 * @link plugins@cedcommerce.com
		 */
		function __construct()
		{
			add_action( 'ced_cwsm_settings_tabs_array', array( $this, 'ced_cwsm_wholesale_advertisement_tab' ) );
			add_filter( 'ced_cwsm_settings_ced_cwsm_wholesale_advertisement', array($this,'ced_cwsm_wholesale_advertisement_setting'));
		}
		
		/**
		 * This function is used to display the settings page
		 * @name ced_cwsm_wholesale_advertisement_setting()
		 * @author CedCommmerce
		 * @link plugins@cedcommerce.com
		 */
		function ced_cwsm_wholesale_advertisement_setting()
		{
			global $ced_cwsm_current_tab;
			
			if( $ced_cwsm_current_tab == 'ced_cwsm_wholesale_advertisement' )
			{				
				$GLOBALS['hide_save_button'] = true;
				?>
				<div class="wrap woocommerce wc_addons_wrap ced_cwsm_wc_addons_wrap">
					<ul class="products">
						<li class="product addon_ready">
							<div class="ced_cwsm_anchor_main" href="http://demo.cedcommerce.com/woocommerce/wholesale/" target="_blank">
								<h2><?php _e('Wholesale User Add-On','wholesale-market'); ?></h2> 
								<div class="ced_cwsm_temp_class">
									<p><?php _e('Allow Merchant to add multiple level wholesale roles as well as manage price for each roles. User wholesale role request handling features and many more....','wholesale-market'); ?></p>
									<a href="http://demo.cedcommerce.com/woocommerce/wholesale/wp-admin" class="button-secondary ced_cwsm_buy_now_anchor" target="_blank"><?php _e('View Demo','wholesale-market'); ?></a>
									<a href="http://cedcommerce.com/woocommerce-extensions/wholesale-market-user-addon" class="button-secondary buy_now ced_cwsm_buy_now_anchor" target="_blank"><?php _e('Buy Now','wholesale-market'); ?></a>
								</div>
							</div>
						</li>
				
						<li class="product addon_ready">
							<div class="ced_cwsm_anchor_main" href="http://demo.cedcommerce.com/woocommerce/wholesale/" target="_blank">
								<h2><?php _e('Wholesale Product Add-On','wholesale-market'); ?></h2> 
								<div class="ced_cwsm_temp_class">
									<p><?php _e('List wholesale product on seperate section. Product avaliable for wholesale or other role. Global discount to product and many more...','wholesale-market'); ?></p>
									<a href="http://demo.cedcommerce.com/woocommerce/wholesale/wp-admin" class="button-secondary ced_cwsm_buy_now_anchor" target="_blank"><?php _e('View Demo','wholesale-market'); ?></a>
									<a href="http://cedcommerce.com/woocommerce-extensions/wholesale-market-product-addon" class="button-secondary buy_now ced_cwsm_buy_now_anchor" target="_blank"><?php _e('Buy Now','wholesale-market'); ?></a>
								</div>
							</div>
						</li>
						
						<li class="product addon_ready">
							<div class="ced_cwsm_anchor_main" href="http://demo.cedcommerce.com/woocommerce/wholesale/" target="_blank">
								<h2><?php _e('Wholesale Subscription Add-On','wholesale-market'); ?></h2> 
								<div class="ced_cwsm_temp_class">
									<p><?php _e('User can subscribe himself for wholesale roles for specific time as monthly, half-yearly, yearly.','wholesale-market') ?></p>
									<a href="http://demo.cedcommerce.com/woocommerce/wholesale/wp-admin" class="button-secondary ced_cwsm_buy_now_anchor" target="_blank"><?php _e('View Demo','wholesale-market'); ?></a>
									<a href="http://cedcommerce.com/woocommerce-extensions/wholesale-market-subscription-addon" class="button-secondary buy_now ced_cwsm_buy_now_anchor" target="_blank"><?php _e('Buy Now','wholesale-market'); ?></a>
								</div>
							</div>
						</li>
						
						<li class="product addon_ready">
							<div>
								<h2><?php _e('Wholesale Reporting Add-On','wholesale-market');?></h2> 
								<div class="ced_cwsm_temp_class">
									<p><?php _e('There is a reporting section where user can view all wholesale orders date, user, product wise. View all wholesale request reports and many more...','wholesale-market');?></p>
									<a href="http://demo.cedcommerce.com/woocommerce/wholesale/wp-admin" class="button-secondary ced_cwsm_buy_now_anchor" target="_blank"><?php _e('View Demo','wholesale-market'); ?></a>
									<a href="http://cedcommerce.com/woocommerce-extensions/wholesale-market-reporting-addon" class="button-secondary buy_now ced_cwsm_buy_now_anchor" target="_blank"><?php _e('Buy Now','wholesale-market'); ?></a>
								</div>
							</div>
						</li>
						
						<li class="product">
							<div>
								<h2><?php _e('Wholesale Taxation Add-On','wholesale-market');?></h2> 
								<div class="ced_cwsm_temp_class">
									<p><?php _e('Allow merchant to manage taxes for wholesale users and many more...','wholesale-market'); ?></p>
									<a href="#ced_cwsm_suggestion_div" class="suggest_uus button-secondary" data-num="<?php _e('Wholesale Taxation Add-On','wholesale-market'); ?>"><?php _e('Suggest Features','wholesale-market'); ?></a>
								</div>
							</div>
						</li>
						
						<li class="product">
							<div>
								<h2><?php _e('Wholesale Shipping Add-On','wholesale-market');?></h2> 
								<div class="ced_cwsm_temp_class">
									<p><?php _e('Manage shipping method for wholesale order and many more...','wholesale-market'); ?></p>
									<a href="#ced_cwsm_suggestion_div" class="suggest_uus button-secondary" data-num="<?php _e('Wholesale Shipping Add-On','wholesale-market'); ?>"><?php _e('Suggest Features','wholesale-market'); ?></a>
								</div>
							</div>
						</li>
				
						<li class="product">
							<div>
								<h2><?php _e('Wholesale Payment Add-On','wholesale-market');?></h2>
								<div class="ced_cwsm_temp_class">
									<p><?php _e('Manage payment method for wholesale order and many more...','wholesale-market'); ?></p>
									<a href="#ced_cwsm_suggestion_div" class="suggest_uus button-secondary" data-num="<?php _e('Wholesale Payment Add-On','wholesale-market'); ?>"><?php _e('Suggest Features','wholesale-market'); ?></a>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<?php
				$settings = array ();
				return $settings;
			}
			return $settingReceived;
		}

		/**
		 * This function is used to display the tab for settings page in the plugin.
		 * @name ced_cwsm_wholesale_advertisement_tab()
		 * @author CedCommmerce
		 * @link plugins@cedcommerce.com
		 */
		function ced_cwsm_wholesale_advertisement_tab($tab) {
			$tab ['ced_cwsm_wholesale_advertisement'] = __ ( 'Get Addons', 'wholesale-market' );
			return $tab;
		}
	}
	//Create instance of class
	new Wholesale_advertisement();
}	
?>