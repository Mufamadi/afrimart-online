<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Manage add to cart button on shop page and product single page when regular price of product is not available.
 *
 * @class    CED_CWSM_Manage_Add_To_Cart_Button
 * @version  2.0.8
 * @package  wholesale-market/core/frontEnd
 * @category Class
 * @author   CedCommerce
 */
class CED_CWSM_Manage_Add_To_Cart_Button
{
	/**
	 * CED_CWSM_Manage_Add_To_Cart_Button Constructor.
	 */
	public function __construct() {
		$this->add_hooks_and_filters();
	}

	/**
	 * Hook into actions and filters.
	 * @since  1.0.8
	 */
	private function add_hooks_and_filters() {	
		/* for simple product */
		add_filter( 'woocommerce_is_purchasable', array( $this, 'woocommerce_is_purchasable' ), 30, 2 );

		/* for variable product */
		add_filter( 'woocommerce_variation_is_visible', array( $this, 'woocommerce_variation_is_visible' ), 30, 4 );
		add_filter( 'woocommerce_variation_is_purchasable', array( $this, 'woocommerce_variation_is_purchasable' ), 10, 2 );
	}

	/**
	 * This function manage add-to-cart link for variable product.
	 * @name woocommerce_variation_is_visible()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	function woocommerce_variation_is_visible( $visible, $variation_id, $id, $thisRef ) {
		if( $visible == true ) {
			return $visible;
		}

		// Published == enabled checkbox
		if ( get_post_status( $thisRef->get_id() ) != 'publish' ) {
			$visible = false;
		}

		// Price not set
		elseif ( $thisRef->get_price() === "" ) {
			global $globalCWSM;
			$wholesalePrice = $globalCWSM->getWholesalePrice( $variation_id );
			if( !empty($wholesalePrice) && $wholesalePrice != '' && $wholesalePrice != '0') {
				$visible = true;
			}
			else {
				$visible = false;
			}
		}
		return $visible;
	}

	/**
	 * This function manage add-to-cart link for variable product.
	 * @name woocommerce_variation_is_purchasable()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	function woocommerce_variation_is_purchasable( $purchasable, $thisRef ) {
		if( $purchasable == true ) {
			return $purchasable;
		}

		if ( get_post_status( $thisRef->get_id() ) != 'publish' ) {
			$purchasable = false;
		} else {
			global $globalCWSM;
			$wholesalePrice = $globalCWSM->getWholesalePrice( $thisRef->get_id() );
			if( !empty($wholesalePrice) && $wholesalePrice != '' && $wholesalePrice != '0') {
				$purchasable = true;
			}
			else {
				$purchasable = false;
			}
		}
		return $purchasable;
		//var_dump($purchasable); die();
	}

	/**
	 * This function manage add-to-cart link for simple product.
	 * @name woocommerce_is_purchasable()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	function woocommerce_is_purchasable( $purchasable, $thisRef ) {
		if( $purchasable == true ) {
			return $purchasable;
		}
		
		// Products must exist of course
		if ( ! $thisRef->exists() ) {
			$purchasable = false;
		// Other products types need a price to be set
		}
		elseif ( $thisRef->get_price() === '' ) {
			global $globalCWSM;
			$wholesalePrice = $globalCWSM->getWholesalePrice( $thisRef->get_id() );
			if( !empty($wholesalePrice) && $wholesalePrice != '' && $wholesalePrice != '0') {
				$purchasable = true;
			}
			else {
				$purchasable = false;
			}
		// Check the product is published
		} elseif ( $thisRef->post->post_status !== 'publish' && ! current_user_can( 'edit_post', $thisRef->get_id() ) ) {
			$purchasable = false;
		}
		return $purchasable;
	}
}
//Create an instance of class
new CED_CWSM_Manage_Add_To_Cart_Button();
?>