<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
* Render setting to send suggestion to plugin author.
*
* @class    CED_CWSM_Admin_Suggestions
* @version  2.0.8
* @category Class
* @author   CedCommerce
*/
class CED_CWSM_Admin_Suggestions
{
	/**
	 * This is construct of class
	 *
	 * @author CedCommmerce
	 * @link plugins@cedcommerce.com
	 */
	public function __construct()
	{
		$this->ced_cwsm_admin_suggestions_module_hooks_and_filters_function();
	}

	/**
	 * This function uses necessary hooks and filter for module to work
	 * @name ced_cwsm_admin_suggestions_module_hooks_and_filters_function()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	function ced_cwsm_admin_suggestions_module_hooks_and_filters_function()
	{
		add_action('admin_enqueue_scripts',array($this,'ced_cwsm_admin_suggestions_module_admin_enqueue_scripts'));

		add_filter( 'woocommerce_get_sections_ced_cwsm_plugin',array($this,'ced_cwsm_admin_suggestions_module_add_section'),20,1);
		add_filter( 'woocommerce_get_settings_ced_cwsm_plugin', array($this,'ced_cwsm_admin_suggestions_module_add_setting'),10,2);

		add_filter( 'ced_cwsm_append_basic_sections',array($this,'ced_cwsm_admin_suggestions_module_add_section'),20,1);
		add_filter( 'ced_cwsm_append_basic_settings', array($this,'ced_cwsm_admin_suggestions_module_add_setting'),10,2);


		add_action( 'wp_ajax_nopriv_ced_cwsm_admin_suggestions_module_send_mail', array($this,'ced_cwsm_admin_suggestions_module_send_mail' ));
		add_action( 'wp_ajax_ced_cwsm_admin_suggestions_module_send_mail', array($this,'ced_cwsm_admin_suggestions_module_send_mail' ));
	}

	/**
	 * This function is used to send mail through admin for suggestions
	 * @name ced_cwsm_admin_suggestions_module_send_mail()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	function ced_cwsm_admin_suggestions_module_send_mail()
	{
        $admin_email = get_option('admin_email');
		$suggestionTitle = __('Wholsale-Market Suggestion : ','wholesale-market').sanitize_text_field($_POST['suggestionTitle']);
		$suggestionDetail = __('Suggestion : ','wholesale-market').'<br/>'.sanitize_text_field($_POST['suggestionDetail']).'<br/>'.__('Suggestion From : ','wholesale-market').'<br/>'.$_SERVER['SERVER_NAME'].'<br/>('.$admin_email.')'; 
        
		$our_email = 'plugins@cedcommerce.com';
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$isMailSend = wp_mail( $our_email, $suggestionTitle, $suggestionDetail,$headers);
		
		if($isMailSend)
		{
			echo "success";
		}
		else
		{
			echo "failure";
		}	
		wp_die();
	}

	/**
	 * This function includes custom js needed by module.
	 * @name ced_cwsm_admin_suggestions_module_admin_enqueue_scripts()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	public function ced_cwsm_admin_suggestions_module_admin_enqueue_scripts()
	{
		if(strpos($_SERVER['REQUEST_URI'], "page=wholesale_market&tab=ced_cwsm_basic&section=ced_cwsm_admin_suggestions_module"))
		{
			wp_enqueue_script( 'ced_cwsm_send_mail', plugins_url('js/ced_cwsm_send_mail.js',__FILE__), array('jquery'), '1.0', true );
			wp_localize_script( 'ced_cwsm_send_mail', 'ced_cwsm_send_mail_js_ajax', array(
				'ajax_url' => admin_url( 'admin-ajax.php' )
			));

			wp_enqueue_style('ced_cwsm_send_mail_css', plugins_url('css/ced_cwsm_send_mail.css',__FILE__));
		}
	}

	/**
	 * This function adds section on wholesale market tab.
	 * @name ced_cwsm_admin_suggestions_module_add_section()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	public function ced_cwsm_admin_suggestions_module_add_section($sections)
	{
		$sections['ced_cwsm_admin_suggestions_module'] = __('Send Suggestion','wholesale-market');
		return $sections;
	}

	/**
	 * This function adds settings on wholesale market tab.
	 * @name ced_cwsm_admin_suggestions_module_add_setting()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	public function ced_cwsm_admin_suggestions_module_add_setting($settingReceived,$current_section)
	{
		if($current_section == 'ced_cwsm_admin_suggestions_module')
		{
			$GLOBALS['hide_save_button'] = true;
			?>
			<div class="ced_cwsm_suggestion_wrapper">
				<div class="updated" id="ced_cwsm_mail_success">
					<p>
						<strong>
							<?php _e('Your suggestion has been successfully send.','wholesale-market'); ?>
						</strong>
					</p>
				</div>
				<div class="error" id="ced_cwsm_mail_failure">
					<p>
						<strong>
							<?php _e('Some error occured. Please try again.','wholesale-market'); ?>
						</strong>
					</p>
				</div>
				<div class="error" id="ced_cwsm_mail_empty">
					<p>
						<strong>
							<?php _e('Both fields are required. Please try again. ','wholesale-market'); ?>
						</strong>
					</p>
				</div>

				<div class="ced_cwsm_mail_msg">
					<p>
						<?php _e('You can send your suggestion and query regarding Wholesale-Market here.','wholesale-market'); ?>
					</p>
				</div>
				<div>
					<p>
						<label><?php _e('Enter suggestion title here','wholesale-market','wholesale-market'); ?></label>
						<input type="text" id="ced_cwsm_suggestion_title">
					</p>
					<p>
						<label><?php _e('Enter suggestion detail here','wholesale-market'); ?></label>
						<textarea id="ced_cwsm_suggestion_detail" rows="10" cols="90"></textarea>
					</p>
					<p>	
					<label></label>
					<button class="button-primary" id="ced_cwsm_send_mail"><?php _e('Send Suggestion','wholesale-market'); ?></button><img id="ced_cwsm_send_loading" src="<?php echo CED_CWSM_PLUGIN_DIR_URL.'assets/images/ajax-loader.gif';?>">
					</p>
				</div>
			</div>
			<?php
			$settings = array();
			return $settings;
		}
		return $settingReceived;
	}
}
//Create instance of class
new CED_CWSM_Admin_Suggestions();
?>