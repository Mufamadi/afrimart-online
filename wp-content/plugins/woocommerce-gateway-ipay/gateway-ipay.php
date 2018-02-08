<?php
/**
 * @wordpress-plugin
 * @Plugin Name:		WooCommerce i-Pay Gateway
 * @Plugin URI:			https://instanteft.i-pay.co.za
 * @Description:		Receive instant EFT payments from customers
 * @Version:			1.0.0.0
 * @Author:			    <a href="https://instanteft.i-pay.co.za" />i-Pay</a>
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

load_plugin_textdomain( 'woocommerce-gateway-ipay', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) );

add_action('plugins_loaded', 'woocommerce_ipay_init', 0);

function woocommerce_ipay_init() {
    if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;
    
    require_once( plugin_basename( 'classes/class-wc-gateway-ipay.php' ) );

    add_filter('woocommerce_payment_gateways', 'add_wc_ipay_class');
}

function add_wc_ipay_class($methods) {
    
    $methods[] = 'WC_Gateway_iPay';
    return $methods;
}

?>