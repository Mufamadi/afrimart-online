<?php
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists( 'Wholesale_request_process' ) ) {
	/**
	 * This is class for add feature of register user as wholesale user .
	 *
	 * @name    Wholesale_user_register_notification
	 * @category Class
	 * @author   cedcommerce <plugins@cedcommerce.com>
	 */
	
	class Wholesale_request_process {
		/**
		 * This is construct of class
		 *
		 * @author   cedcommerce <plugins@cedcommerce.com>
		 * @link http://cedcommerce.com/
		 */
		function __construct() {
			add_action( 'admin_enqueue_scripts', array($this, 'ced_wura_admin_script'));
			add_action( 'wp_ajax_nopriv_ced_wholesale_process_request', array($this,'ced_wholesale_process_request_callback'));
			add_action( 'wp_ajax_ced_wholesale_process_request', array($this,'ced_wholesale_process_request_callback'));
		}
		
		/**
		 *
		 * Enqueue admin script for addon
		 *
		 * @author   cedcommerce <plugins@cedcommerce.com>
		 * @link http://cedcommerce.com/
		 */
		
		function ced_wura_admin_script() {
			wp_register_script('ced_wura_wholesale_admin_script', CED_WURA_DIR_URL.'/assets/js/wholesale-user-register-admin.js', array('jquery'));
		
			// Localize the script with new data
			$ced_wura_script = array(
					'ajax_url' => admin_url('admin-ajax.php'),
					'plugin_url' => CED_WURA_DIR_URL,
			);
		
			wp_localize_script( 'ced_wura_wholesale_admin_script', 'ced_wura_admin', $ced_wura_script );
		
			// Enqueued script with localized data.
			wp_enqueue_script( 'ced_wura_wholesale_admin_script' );
		}
		
		/**
		* Function to manage the role request 
		*
		* @author cedcommerce <plugins@cedcommerce.com>
		* @link http://www.cedcommerce.com/
		*/
		function ced_wholesale_process_request_callback() {
			if(isset($_POST['id'])) {
				if(isset($_POST['data'])) {
					$user_id = $_POST['id'];
					$user_data = new WP_User( $user_id );
						
					$user_email = $user_data->data->user_email;
					global $globalCWSM;	
					$useraddon_roles = $globalCWSM->getWholesaleRoleKeys();

					$admin_name = get_option('blogname');
					$admin_email = get_option('admin_email');
					$notification_setting = get_option('ced_wura_notification', false);
					if(isset($notification_setting['name']) && !empty($notification_setting['name'])) {
						$admin_name = $notification_setting['name'];
					}
					if(isset($notification_setting['mail']) && !empty($notification_setting['mail'])) {
						$admin_email = $notification_setting['mail'];
					}
						
					if($_POST['data'] == 'accept') {
						$accept_subject = "";
						if(isset($notification_setting['accept_subject'])) {
							$accept_subject = $notification_setting['accept_subject'];
						}
							
						$accept_message = "";
						if(isset($notification_setting['accept_message'])) {
							$accept_message = $notification_setting['accept_message'];
						}
		
						//send mail to admin

						$headers = array('Content-Type: text/html; charset=UTF-8',"From: $admin_name <$admin_email>");;
						$to = $user_email;
						$subject = $accept_subject;
						$message = $accept_message;
						wp_mail($to, $subject, stripslashes($message), $headers);
						$prev_roles =$user_data->roles;						
						if (is_array($prev_roles)) {							
							foreach ($prev_roles as $key => $value) {
								if (in_array($value, $useraddon_roles) || $value == 'customer' || $value == 'ced_cwsm_wholesale_user') {
									$user_data->remove_role($value);
								}
							}						
						}
						// Add role
						$user_data->add_role( $_POST[ 'req_role' ] );
						update_user_meta($user_id, 'ced_wholesale_request', 'accept');
						do_action('ced_cwsm_assign_extra_information_on_accept',$user_id);
					}
					if($_POST['data'] == 'cancel') {
						$cancel_subject = "";
						if(isset($notification_setting['cancel_subject'])) {
							$cancel_subject = $notification_setting['cancel_subject'];
						}
							
						$cancel_message = "";
						if(isset($notification_setting['cancel_message'])) {
							$cancel_message = $notification_setting['cancel_message'];
						}
		
						//send mail to admin	
						$headers = array('Content-Type: text/html; charset=UTF-8',"From: $admin_name <$admin_email>");
						$to = $user_email;
						$subject = $cancel_subject;
						$message = $cancel_message;
						wp_mail($to, $subject, stripslashes($message), $headers);
						update_user_meta($user_id, 'ced_wholesale_request', 'cancel');
					}
				}
			}
		}
	}
	new Wholesale_request_process();
}		
?>