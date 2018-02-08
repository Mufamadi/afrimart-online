<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Adds wholesale-Customer role to available user role in wordpress and also handles its deletion.
 *
 * @class    CED_CWSM_Manage_Wholesale_Role
 * @version  2.0.8
 * @package  wholesale-market/wholesale-customer-role
 * @category Class
 * @author   CedCommerce
 */
class CED_CWSM_Manage_Wholesale_Role
{
	//store the single instance
	private static $_instance;
	/*
     * Get an instance of the database
     * @return database
	 */
	public static function getInstance() {
		if( !self::$_instance instanceof self )
			self::$_instance = new self;
	
			return self::$_instance;
	}
	
	/**
	 * This function adds a new role to wordpress site as "Wholesale Customer".
	 *
	 * @name ced_cwsm_addWholesaleRole()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	public function ced_cwsm_addWholesaleRole( $role , $display_name ) 
	{
		global $wp_roles;
		if ( ! isset( $wp_roles ) )
			$wp_roles = new WP_Roles();
	
		$customerRole = $wp_roles->get_role( 'customer' ); // Copy customer role capabilities
	
		add_role( $role , $display_name , $customerRole->capabilities );
	}
	
	/**
	 * This function adds a capability to the specified role.
	 *
	 * @name ced_cwsm_addWholesaleCapability()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @param string $role
	 * @param string $cap
	 * @return string $role
	 * @return string $cap
	 * @link  http://www.cedcommerce.com/
	 */
	public function ced_cwsm_addWholesaleCapability( $role , $cap ) 
	{
		$role = get_role( $role );
		$role->add_cap( $cap );
	}
	
	/**
	 * This function removes "Wholesale Customer" role on plugin deactivation.
	 * @name ced_cwsm_removeWholesaleRole()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @param string $role
	 * @return string $role
	 * @link  http://www.cedcommerce.com/
	 */
	public function ced_cwsm_removeWholesaleRole( $role ) 
	{
		remove_role( $role );
	}
	
	/**
	 * This function removes capability of the specified role on plugin deactivation.
	 *
	 * @name ced_cwsm_removeWholesaleCapability()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @param string $role
	 * @param string $cap
	 * @return string $cap
	 * @link  http://www.cedcommerce.com/
	 */
	public function ced_cwsm_removeWholesaleCapability ( $role , $cap ) 
	{
		$role = get_role( $role );
	
		if ( $role instanceof WP_Role )
			$role->remove_cap( $cap );
	}
}
?>