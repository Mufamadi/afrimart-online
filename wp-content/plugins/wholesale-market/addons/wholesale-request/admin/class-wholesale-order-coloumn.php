<?php
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists( 'Wholesale_order_coloumn' ) ) {
	/**
	 * This is class for add feature of register user as wholesale user .
	 *
	 * @name    Wholesale_user_register_notification
	 * @category Class
	 * @author   cedcommerce <plugins@cedcommerce.com>
	 */
	
	class Wholesale_order_coloumn {
		/**
		 * This is construct of class
		 *
		 * @author   cedcommerce <plugins@cedcommerce.com>
		 * @link http://cedcommerce.com/
		 */
		function __construct() {
			add_filter( 'manage_edit-shop_order_columns',array($this,'ced_wura_wholesale_shop_order_column'),10,1);
			add_filter( 'manage_shop_order_posts_custom_column', array($this,'ced_wura_wholesale_shop_order_column_value'),10,2);
			add_filter( 'request',  array($this, 'ced_wura_wholesale_order_column_orderby' ));
			add_filter( 'manage_edit-shop_order_sortable_columns', array( $this, 'ced_wura_wholesale_shop_order_sortable_columns' ) );
			add_action( 'woocommerce_checkout_order_processed', array( $this, 'ced_wura_wholesale_order' ), 10, 2);
		}
		
		function ced_wura_wholesale_order($order_id, $posted) {
			$order = new WC_Order( $order_id );
			$items = $order->get_items();
			$check='';
			foreach ( $items as $item ) {
			    $product_id = $item['product_id'];
			    $product_variation_id = $item['variation_id'];
			    if(!empty($product_variation_id)) {
			    	$p_id=$product_variation_id;
			    }
			    else {
			    	$p_id=$product_id;
			    }
			    $wholesale_price=get_post_meta($p_id, 'ced_cwsm_wholesale_price', true);
			    if(!empty($wholesale_price)) {
			    	$check='yes';
			    }
			}
			
			$current_user_id = get_current_user_id();
			if($current_user_id > 0) {
				$current_user_info = get_userdata($current_user_id);
				$current_user_role = $current_user_info->roles;
				if(in_array('ced_cwsm_wholesale_user', $current_user_role) && $check=='yes') {
					update_post_meta($order_id, 'ced_wholesale_order', 1);
				}
				else {
					update_post_meta($order_id, 'ced_wholesale_order', 0);
				}	
			}	
			else {
				update_post_meta($order_id, 'ced_wholesale_order', 0);
			}	
		} 
		
		/**
		 * This function is used to order by columns of product orders.
		 * @name ced_wura_wholesale_order_column_orderby()
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  http://www.cedcommerce.com/
		 */

		function ced_wura_wholesale_order_column_orderby($vars) {
			if ( isset( $vars['orderby'] ) && 'ced_wholesale_order' == $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
						'meta_key' => 'ced_wholesale_order',
						'orderby' => 'meta_value'
				) );
			}
			return $vars;
		}

		/**
		 * This function is used to sort wholesale shop orders.
		 * @name ced_wura_wholesale_shop_order_sortable_columns()
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  http://www.cedcommerce.com/
		 */
		
		function ced_wura_wholesale_shop_order_sortable_columns( $columns ) {
			$custom = array(
					'ced_wholesale_order'    => 'ced_wholesale_order',
			);
			return wp_parse_args($custom, $columns);
		}

		/**
		 * This function is add Wholesale order column
		 * @name ced_wura_wholesale_shop_order_column()
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  http://www.cedcommerce.com/
		 */
		
		function ced_wura_wholesale_shop_order_column($columns) {
			$columns['ced_wholesale_order'] = __( 'Wholesale Order','wholesale-market');
			return $columns;
		}

		/**
		 * This function is to add value toWholesale order column
		 * @name ced_wura_wholesale_shop_order_column_value()
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  http://www.cedcommerce.com/
		 */
		
		function ced_wura_wholesale_shop_order_column_value($column) {
			global $post, $woocommerce, $the_order;
			if(WC()->version<'3.0.0') {
					$order_id = $the_order->id;
				}
				else {
					$order_id = $the_order->get_id();	
				}
			switch ( $column ) {
				case 'ced_wholesale_order' :
				$wholesale_order = get_post_meta($order_id, 'ced_wholesale_order', true);
				if($wholesale_order == 1) {
					echo __( 'Wholesale Order','wholesale-market');
				}
				else {
					echo __( 'Not a Wholesale Order','wholesale-market');
				}			
				break;
			}
		}
	}
	new Wholesale_order_coloumn();
}	