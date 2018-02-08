<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * @class    CED_CWSM_CSV_Import_Export
 * @version  2.0.8
 * @package  addons/wholesale-market-csv-import-export
 * @category Class
 * @author   CedCommerce
 */
new CED_CWSM_CSV_Import_Export();
class CED_CWSM_CSV_Import_Export
{
	public $wm_csv_import_export_txt_domain = 'wholesale-market';
	/**
	 * This is construct of class
	 *
	 * @author CedCommmerce
	 * @link plugins@cedcommerce.com
	 */
	public function __construct()
	{
		$this->ced_cwsm_csv_import_export_hooks_and_filters_function();
	}

	/**
	 * This function uses necessary hooks and filter for csv module to work
	 * @name ced_cwsm_csv_import_export_hooks_and_filters_function()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	function ced_cwsm_csv_import_export_hooks_and_filters_function()
	{
		add_action('init',array($this,'ced_cwsm_csv_import_export_module_export_csv_format'));
		add_action('init',array($this,'ced_cwsm_csv_import_export_module_download_error_log'));

		add_action('admin_enqueue_scripts',array($this,'ced_cwsm_csv_import_export_module_admin_enqueue_scripts'));

		add_filter( 'woocommerce_get_sections_ced_cwsm_plugin',array($this,'ced_cwsm_csv_import_export_module_add_section'),10,1);
		add_filter( 'woocommerce_get_settings_ced_cwsm_plugin', array($this,'ced_cwsm_csv_import_export_module_add_setting'),10,2);


		add_filter( 'ced_cwsm_append_basic_sections',array($this,'ced_cwsm_csv_import_export_module_add_section'),10,1);
		add_filter( 'ced_cwsm_append_basic_settings', array($this,'ced_cwsm_csv_import_export_module_add_setting'),10,2);
		

		add_action( 'wp_ajax_nopriv_ced_cwsm_csv_import_export_module_read_csv', array($this,'ced_cwsm_csv_import_export_module_read_csv' ));
		add_action( 'wp_ajax_ced_cwsm_csv_import_export_module_read_csv', array($this,'ced_cwsm_csv_import_export_module_read_csv' ));

		add_action( 'wp_ajax_nopriv_ced_cwsm_csv_import_export_module_download_error_log', array($this,'ced_cwsm_csv_import_export_module_download_error_log' ));
		add_action( 'wp_ajax_ced_cwsm_csv_import_export_module_download_error_log', array($this,'ced_cwsm_csv_import_export_module_download_error_log' ));
	}
	
	/**
	 * This function downloads error log during csv import
	 * @name ced_cwsm_csv_import_export_module_download_error_log()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	function ced_cwsm_csv_import_export_module_download_error_log()
	{
		if(strpos($_SERVER['REQUEST_URI'], "&tab=ced_cwsm_plugin&section=ced_cwsm_csv_import_export_module&ced_cwsm_log_download="))
		{	
			$fileName = sanitize_text_field($_GET['ced_cwsm_log_download']);
			$upload_dir = wp_upload_dir();
			if(isset($upload_dir["basedir"]) && !empty($upload_dir["basedir"]))
			{	
				$uploadDirPath = $upload_dir["basedir"].'/';
				$errorLogFolder = 'wholesale_market_import_error/';
				$pathOfFileToDownload = $uploadDirPath.$errorLogFolder.$fileName;
				if (file_exists($pathOfFileToDownload)) 
				{
					header('Content-Description: File Transfer');
					header('Content-Type: application/log');
					header('Content-Disposition: attachment; filename="'.basename($pathOfFileToDownload).'"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate');
					header('Pragma: public');
					header('Content-Length: ' . filesize($pathOfFileToDownload));
					
					do_action('wm_csv_import_export_error_log');
					
					readfile($pathOfFileToDownload);
					exit;
				}
			}	
			die();
		}
	}

	/**
	 * This function exports the format of wholelsale-market-csv
	 * @name ced_cwsm_csv_import_export_module_export_csv_format()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	function ced_cwsm_csv_import_export_module_export_csv_format()
	{
		if(strpos($_SERVER['REQUEST_URI'], "&tab=ced_cwsm_plugin&section=ced_cwsm_csv_import_export_module&ced_cwsm_export=true"))
		{	
			$full_product_list = array();
			$loop = new WP_Query( array( 'post_type' => array('product', 'product_variation'), 'posts_per_page' => -1 , 'post_status' => 'publish') );				
				/*Filter to fetch roles from the user addon if any created*/			
			$count = 0;
			while ( $loop->have_posts() )
			{ 
				$loop->the_post();
				
				$theid = get_the_ID();

				if(WC()->version<'3.0.0')
				{
				$product = get_product($theid);
				}
				else
				{	
					$product = wc_get_product($theid);
				}
				// add product to array but don't add the parent of product variations :: executing the check
				$children = $product->get_children();
				if(!empty($children))
				{
					continue;
				}
				//end of check
            	$full_product_list[$count][] = $theid;            	
				if( get_post_type() == 'product_variation' ) // its a variable product
				{
					$full_product_list[$count][] = get_post_meta($theid, '_sku', true );
					$full_product_list[$count][] = get_the_title();
					if(WC()->version<'3.0.0')
				{
					$full_product_list[$count][] = $product->product_type;
 					}else{
 						$full_product_list[$count][] = $product->get_type();

 					}
 					$full_product_list[$count][] = get_post_meta( $theid, '_regular_price', true);
    				$full_product_list[$count][] = get_post_meta( $theid, '_sale_price', true);
    				$full_product_list[$count][] = get_post_meta( $theid, 'ced_cwsm_wholesale_price', true );
    				$full_product_list[$count][] = get_post_meta( $theid, 'ced_cwsm_min_qty_to_buy', true );
    				$full_product_list = apply_filters( 'ced_cwsm_get_meta_key_roles_created', $full_product_list , $count , $theid );    				
				} 
        		else // its a simple product
         		{
            		$full_product_list[$count][] = get_post_meta($theid, '_sku', true );
					$full_product_list[$count][] = get_the_title();


						if(WC()->version<'3.0.0')
				{
					$full_product_list[$count][] = $product->product_type;
 					}else{
 						$full_product_list[$count][] = $product->get_type();

 					}

            		$full_product_list[$count][] = get_post_meta( $theid, '_regular_price', true);
    				$full_product_list[$count][] = get_post_meta( $theid, '_sale_price', true);
    				$full_product_list[$count][] = get_post_meta( $theid, 'ced_cwsm_wholesale_price', true);
    				$full_product_list[$count][] = get_post_meta( $theid, 'ced_cwsm_min_qty_to_buy', true);
    				$full_product_list = apply_filters( 'ced_cwsm_get_meta_key_roles_created', $full_product_list , $count , $theid );            		
            	}            	
            	$count++;
        	}         	
    		wp_reset_query();   		

			$ced_cwsm_csv_header = array(
				'product_id' => "Product-Id", 
				'product_sku' => "SKU", 
				'product_name' => "Product-Name", 
				'product_type' => "Product_Type", 
				'regular_price' => "Regular-Price", 
				'special_price' => "Special-Price", 
				'ced_cwsm_wholesale_price' => "Wholesale-Price", 
				'ced_cwsm_min_qty_to_buy' => "Min Qty To Buy"
			);
			$ced_cwsm_csv_header = apply_filters( 'ced_cwsm_get_roles_for_csv_title' , $ced_cwsm_csv_header );
			update_option( 'ced_cwsm_latest_csv_header', $ced_cwsm_csv_header );
			$title = array_values($ced_cwsm_csv_header);			
			$filename = "wholesale_market_format.csv";
        
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=$filename");
			header("Pragma: no-cache");
			header("Expires: 0");

			$content = array();
			
 			foreach ($full_product_list as $pro) {
    			$row = array();
 				
 				foreach ($pro as $key => $value) {
 					$row[] = stripslashes($value);
 				}
    			$content[] = $row;
    		}

			$output = fopen('php://output', 'w');
			fputcsv($output, $title);
			foreach ($content as $con) 
			{
    			fputcsv($output, $con);
			}

			die();
		}	
	}

	/**
	 * This function to read data from csv and prepare response
	 * @name ced_cwsm_csv_import_export_module_read_csv()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	function ced_cwsm_csv_import_export_module_read_csv()
	{
		$uploadDirPath = wp_upload_dir()["basedir"].'/';
		$errorLogFolder = 'wholesale_market_import_error/';

		$ced_cwsm_csv_header = get_option( 'ced_cwsm_latest_csv_header' , array() );
		$ced_cwsm_csv_keys = array_keys( $ced_cwsm_csv_header );			
		$title = array_values($ced_cwsm_csv_header);
		$importErrorDir = $uploadDirPath.$errorLogFolder;
		if (!is_dir($importErrorDir)) 
		{
			mkdir($importErrorDir, $permissions = 0777);
		}
		$logFileName = 'csvImportError.log';
		$errorLogReport = "";
		
		if(isset($_FILES))
		{
			$file = $_FILES[0]['tmp_name'];
			if(file_exists($file)) 
			{
				$handle = fopen($file, 'r');
				if($handle) 
				{	
					$forTheFirstTime = true;

					$successfulUpdate_wholesale_price = 0;
					$failedUpdate_wholesale_price = 0;
					
					$successfulUpdate_min_qty_to_buy = 0;
					$failedUpdate_min_qty_to_buy = 0;

					$successfulUpdate_regular_price = 0;
					$failedUpdate_regular_price= 0;

					$successfulUpdate_special_price = 0;
					$failedUpdate_special_price = 0;

					while (($data = fgetcsv($handle))!== false) 
					{
						$anyIssueFound = false;

						if($forTheFirstTime)
						{	
							if( $data == $title )
							{	
								$forTheFirstTime = false;
								continue;
							}
							else
							{
								$resultArray = array(
									'status' => false,
									'reason' => 'CSV not found in correct format. Please try again.'
								);

								$resultArray = json_encode($resultArray);
								echo $resultArray;
								wp_die();

							}	
						}
						
						$response = $this->ced_cwsm_write_wholesale_csv_content_to_DB($data[0],$data[6],$data[7]);
						if ( count( $data ) > 8 ) {							
							$errorLogReport = apply_filters( 'ced_cwsm_write_user_addon_users_price_csv_to_DB', $errorLogReport, $data, $ced_cwsm_csv_keys  );							
						}
						$response = explode("*",$response);
						
						if($response[0] == "true")
						{
							$successfulUpdate_wholesale_price++;
						}
						else
						{
							$failedUpdate_wholesale_price++;

							$reason = end(explode("#",$response[0]));
							if(!$anyIssueFound)
							{	
								$anyIssueFound = true;
								$errorLogReport .= "Product-Name : ".$data[2].trim()."\n";
								$errorLogReport .= "Update Failure Reason(s) :\n";
							}
							if($reason == "empty")
							{
								$errorLogReport .= '- Wholesale-Price was blank.'."\n";
							}
							else
							{
								$errorLogReport .= '- Wholesale-Price was not found in correct format. It must be numeric.'."\n";
							}
						}

						if($response[1] == "true")
						{
							$successfulUpdate_min_qty_to_buy++;
						}
						else
						{
							$failedUpdate_min_qty_to_buy++;
							
							$reason = end(explode("#",$response[1]));
							if(!$anyIssueFound)
							{	
								$anyIssueFound = true;
								$errorLogReport .= __('Product-Name : ','wholesale-market').$data[2].trim()."\n";
								$errorLogReport .= __('Update Failure Reason(s) :','wholesale-market')."\n";
							}
							if($reason == "empty")
							{
								$errorLogReport .= __('- Minimum Quantity To Buy was blank.','wholesale-market')."\n";
							}
							else
							{
								$errorLogReport .= __('- Minimum Quantity To Buy was not found in correct format. It must be numeric.','wholesale-market')."\n";
							}
						}

						/* if user wants to update WooCommerce price as well */
						if($_POST["modify_woocommerce_price"] == "true")
						{
							$response = $this->ced_cwsm_write_woocommerce_csv_content_to_DB($data[0],$data[4],$data[5]);
						
							$response = explode("*",$response);
						
							if($response[0] == "true")
							{
								$successfulUpdate_regular_price++;
							}
							else
							{
								$failedUpdate_regular_price++;

								$reason = end(explode("#",$response[0]));
								if(!$anyIssueFound)
								{	
									$anyIssueFound = true;
									$errorLogReport .= __('Product-Name : ','wholesale-market').$data[2].trim()."\n";
									$errorLogReport .= __('Update Failure Reason(s) :','wholesale-market').'\n';
								}
								if($reason == "empty")
								{
									$errorLogReport .= __('- Regular-Price was blank.','wholesale-market').'\n';
								}
								else
								{
									$errorLogReport .= __('- Regular-Price To Buy was not found in correct format. It must be numeric.','wholesale-market').'\n';
								}
							}

							if($response[1] == "true")
							{
								$successfulUpdate_special_price++;
							}
							else
							{
								$failedUpdate_special_price++;

								$reason = end(explode("#",$response[1]));
								if(!$anyIssueFound)
								{	
									$anyIssueFound = true;
									$errorLogReport .= __('Product-Name : ','wholesale-market').$data[2].trim().'\n';
									$errorLogReport .= __('Update Failure Reason(s) :','wholesale-market').'\n';
								}
								if($reason == "empty")
								{
									$errorLogReport .= __('- Sale-Price was blank.','wholesale-market').'\n';
								}
								else
								{
									$errorLogReport .= __('- Sale-Price To Buy was not found in correct format. It must be numeric.','wholesale-market').'\n';
								}
							}
						}

						if($anyIssueFound)
						{
							$errorLogReport .= "\n\n";
						}	
					}
					fclose($handle);
				}
			}	
		}

		$resultArray = array(
				'status' => true,
				'wholesale_price' => array(
						'successfulUpdate' => $successfulUpdate_wholesale_price,
						'failedUpdate' => $failedUpdate_wholesale_price
					),
				'min_qty_to_buy' => array(
						'successfulUpdate' => $successfulUpdate_min_qty_to_buy,
						'failedUpdate' => $failedUpdate_min_qty_to_buy
					)
			);

		/* if user wants to update WooCommerce price as well */
		if($_POST["modify_woocommerce_price"] == "true")
		{
			$resultArray['regular_price'] = array(
						'successfulUpdate' => $successfulUpdate_regular_price,
						'failedUpdate' => $failedUpdate_regular_price
					);

			$resultArray['special_price'] = array(
						'successfulUpdate' => $successfulUpdate_special_price,
						'failedUpdate' => $failedUpdate_special_price
					);
		}	

		$oldLogReport = "";
		if(file_exists($importErrorDir.$logFileName))
		{	
			$oldLogReport = file_get_contents($importErrorDir.$logFileName);
		}

		$errorLogReport = "Date : ".date("Y-m-d")."\n".$errorLogReport."\n\n\n";

		$errorLogReport = $oldLogReport.$errorLogReport;

		file_put_contents($importErrorDir.$logFileName, $errorLogReport);
		
		$resultArray['error_log_link'] = $logFileName;

		$resultArray = json_encode($resultArray);
		echo $resultArray;
		wp_die();
	}

	/**
	 * This function to read data from csv and update to database
	 * @name ced_cwsm_write_woocommerce_csv_content_to_DB()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	function ced_cwsm_write_woocommerce_csv_content_to_DB($proId,$regular_price,$special_price)
	{
		$regular_price = sanitize_text_field($regular_price);
		$regular_price = $regular_price.trim();

		$special_price = sanitize_text_field($special_price);
		$special_price = $special_price.trim();

		$flagVar_regular_price = "false";
		$flagVar_special_price = "false";

		if($regular_price < $special_price)
		{
			return $flagVar_regular_price."*".$flagVar_special_price;
		}

		if(isset($regular_price) && !empty($regular_price) && $regular_price != "" && $regular_price != null)
		{
			if(is_numeric($regular_price))
			{
				update_post_meta( $proId, '_regular_price', wc_format_decimal($regular_price));
				$flagVar_regular_price = "true";
			}
			else
			{
				$flagVar_regular_price = "false";
			}	
			
		}

		if(isset($special_price) && !empty($special_price) && $special_price != "" && $special_price != null)
		{
			if(is_numeric($special_price))
			{
				update_post_meta( $proId, '_sale_price', wc_format_decimal($special_price));
				$flagVar_special_price = "true";
			}
			else
			{
				$flagVar_special_price = "false";
			}	
			
		}

		return $flagVar_regular_price."*".$flagVar_special_price;
	}

	/**
	 * This function is used to write data from csv and update to database
	 * @name ced_cwsm_write_wholesale_csv_content_to_DB()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	function ced_cwsm_write_wholesale_csv_content_to_DB($proId,$wholesale_price,$min_qty_to_buy)
	{
		$wholesale_price = sanitize_text_field($wholesale_price);
		$wholesale_price = $wholesale_price.trim();

		$min_qty_to_buy = sanitize_text_field($min_qty_to_buy);
		$min_qty_to_buy = $min_qty_to_buy.trim();

		$flagVar_wholesale_price = "false#empty";
		$flagVar_min_qty_to_buy = "false#empty";

		if(isset($wholesale_price) && !empty($wholesale_price) && $wholesale_price != "" && $wholesale_price != null)
		{
			if(is_numeric($wholesale_price))
			{
				update_post_meta( $proId, 'ced_cwsm_wholesale_price', wc_format_decimal($wholesale_price));
				$flagVar_wholesale_price = "true";
			}
			else
			{
				$flagVar_wholesale_price = "false#notNumeric";
			}	
			
		}

		if(isset($min_qty_to_buy) && !empty($min_qty_to_buy) && $min_qty_to_buy != "" && $min_qty_to_buy != null)
		{
			if(is_numeric($min_qty_to_buy))
			{
				update_post_meta( $proId, 'ced_cwsm_min_qty_to_buy', wc_format_decimal($min_qty_to_buy));
				$flagVar_min_qty_to_buy = "true";
			}
			else
			{
				$flagVar_min_qty_to_buy = "false#notNumeric";
			}	
			
		}

		return $flagVar_wholesale_price."*".$flagVar_min_qty_to_buy;

	}

	/**
	 * This function includes custom js needed by module.
	 * @name ced_cwsm_csv_import_export_module_admin_enqueue_scripts()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	public function ced_cwsm_csv_import_export_module_admin_enqueue_scripts()
	{
		if(strpos($_SERVER['REQUEST_URI'], "page=wholesale_market&tab=ced_cwsm_basic&section=ced_cwsm_csv_import_export_module"))
		{
			wp_enqueue_script( 'ced_cwsm_csv_script_js', plugins_url('js/ced_cwsm_csv_script.js',__FILE__), array('jquery'), '1.0', true );
			wp_localize_script( 'ced_cwsm_csv_script_js', 'ced_cwsm_csv_script_js_ajax', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'loading_image' => CED_CWSM_PLUGIN_DIR_URL.'assets/images/clock-loading.gif'
			));

			wp_enqueue_style('ced_cwsm_csv_module_css', plugins_url('css/ced_cwsm_csv_module_style.css',__FILE__));
		}
	}

	/**
	 * This function adds section on wholesale market tab for CSV import & export.
	 * @name ced_cwsm_csv_import_export_module_add_section()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	public function ced_cwsm_csv_import_export_module_add_section($sections)
	{
		$sections['ced_cwsm_csv_import_export_module'] = __('CSV Import/Export','wholesale-market');
		apply_filters('wm_csv_import_export_add_section',$sections);
		return $sections;
	}

	/**
	 * This function adds settings on wholesale market tab for CSV module.
	 * @name ced_cwsm_csv_import_export_module_add_setting()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link  http://www.cedcommerce.com/
	 */
	public function ced_cwsm_csv_import_export_module_add_setting($settingReceived,$current_section)
	{
		if($current_section == 'ced_cwsm_csv_import_export_module')
		{
			$GLOBALS['hide_save_button'] = true;
			do_action('wm_csv_import_export_setting_start');
			?>

			<h3 id="ced_cwsm_csv_module_instruction_heading">
				<span>+</span>
				<?php _e('Instructions To Use CSV Module of Wholesale-Market',$this->wm_csv_import_export_txt_domain);?>
			</h3>

			<div id="ced_cwsm_csv_module_instruction">
				<p><?php _e('1. Export the format of CSV by clicking the ',$this->wm_csv_import_export_txt_domain); ?><b> <?php _e('Export CSV Format ',$this->wm_csv_import_export_txt_domain); ?></b><?php _e('below.',$this->wm_csv_import_export_txt_domain); ?></p>
				<p><?php _e('2. Use the exported CSV to fill-in the values of ',$this->wm_csv_import_export_txt_domain); ?><b><?php _e('Wholesale Price ',$this->wm_csv_import_export_txt_domain); ?></b><?php _e('and/or ',$this->wm_csv_import_export_txt_domain); ?><b><?php  _e('Minimum Product To Buy ',$this->wm_csv_import_export_txt_domain); ?></b><?php _e('columns for each product you want.',$this->wm_csv_import_export_txt_domain); ?></p>
				<p><?php _e('3. By default this module only updates ',$this->wm_csv_import_export_txt_domain); ?><b><?php _e('Wholesale-Market ',$this->wm_csv_import_export_txt_domain); ?></b><?php _e('meta-fields. If you want the module to change WooCommerce-Price (regular-price & sale-price) of product, then please make the checkbox checked.',$this->wm_csv_import_export_txt_domain);?></p>
				<p><?php _e('4. Finally, click the upload button and let the magic begin.',$this->wm_csv_import_export_txt_domain);?></p>
			</div>

			<div id="ced_cwsm_csv_module_main">
			<p>
				<label class="ced_cwsm_label_class">
				<?php _e('Get CSV Format Here',$this->wm_csv_import_export_txt_domain);?>
				</label> 
				<a class="button-primary" href="admin.php?page=wc-settings&tab=ced_cwsm_plugin&section=ced_cwsm_csv_import_export_module&ced_cwsm_export=true" target="_blank"><?php _e('Export CSV Format','wholesale-market'); ?></a>
			</p>
			
			<p>
				<label class="ced_cwsm_label_class">
				<?php _e('Select CSV To Upload',$this->wm_csv_import_export_txt_domain); ?>
				</label>
    			<input type="file" name="ced_cwsm_csvToUpload" id="ced_cwsm_csvToUpload">
    			<label class="browse_label" for="ced_cwsm_csvToUpload"><?php _e('Browse',$this->wm_csv_import_export_txt_domain); ?></label>
    			<label id="ced_cwsm_csv_file_name"><?php _e('No File Selected',$this->wm_csv_import_export_txt_domain); ?></label>
    		</p>

    		<p>
    			<input type="checkbox" id="ced_cwsm_update_woocommerce_price"> 
    			<label for="ced_cwsm_update_woocommerce_price" class="ced_cwsm_label_class_copy">
    				<?php _e('Update WooCommerce Price Also',$this->wm_csv_import_export_txt_domain); ?>
    			</label>
    		</p>

    		<button id="ced_cwsm_csv_submit_button" class="button-primary"><?php _e('Upload',$this->wm_csv_import_export_txt_domain); ?></button>
			</div>

    		<div id="ced_cwsm_csv_processing_div">
    			<img src="<?php echo CED_CWSM_PLUGIN_DIR_URL.'assets/images/clock-loading.gif';?>">
    		</div>

			<?php
			do_action('wm_csv_import_export_setting_end');
			$settings = array();
			return $settings;
		}
		return $settingReceived;
	}
}

?>