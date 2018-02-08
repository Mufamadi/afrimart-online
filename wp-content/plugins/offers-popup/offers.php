<?php  
/* 
Plugin Name: Offers Widget
Plugin URI: http://dotsquares.com
Description: Plugin for managing offers Banners at front end 
Author: dotsquares 
Version: 1.0 
Author URI: http://dotsquares.com/
*/ 
ob_start();  
global $offer_db_version,$wpdb;
$offers_db_version = "1.0";

function offers_install(){
 	
	global $wpdb;
    $table = $wpdb->prefix . "offers";
    $structure = "CREATE TABLE IF NOT EXISTS $table (
	`offer_id` bigint(5) NOT NULL AUTO_INCREMENT,
	`offer_name` varchar(255) NOT NULL,
	`offer_start` date NOT NULL,
	`offer_end` date NOT NULL,
	`offer_desc` text NOT NULL,
	`offer_url` varchar(255) NOT NULL,
	`offer_custom_css` text NOT NULL,
	`status` int(1) NOT NULL,
	PRIMARY KEY (`offer_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";	
	
    $wpdb->query($structure);	
}

function offers_uninstall(){
    global $wpdb;
    $table = $wpdb->prefix . "offers";
    $structure = "drop table if exists $table";
    $wpdb->query($structure);  
}

register_activation_hook( __FILE__, 'offers_install' );
register_deactivation_hook(__FILE__ , 'offers_uninstall' );

function offers_admin(){
	global $wpdb;
	$sql = "SELECT * FROM ".$wpdb->prefix."offers";
	if($_GET['orderby']) $sql .=(" order by " . $_GET['orderby']);
	if($_GET['order']) $sql .=(" " . $_GET['order']);
	$offers = $wpdb->get_results($sql);	
	include('offers_admin.php');  
}


function offers_admin_actions(){  
	add_menu_page(__("Offers Widget"),"Offers Widget",1,'offers_admin.php',"offers_admin"); 
	add_submenu_page('offers_admin.php', 'Add New Offer', 'Add New Offer', 1, 'offers_add_offer', 'offers_add_offer');		
	wp_enqueue_style( 'twentyfourteen-ie', plugins_url('css/style.css',__FILE__));
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-style', plugins_url('css/offers.css', __FILE__ ));
	wp_enqueue_script('offersjs', plugins_url('js/offers.js', __FILE__ ));
} 

function checkValidOffer($offer_id){
	global $wpdb;
	$offer_sql = "SELECT * FROM ".$wpdb->prefix."offers where offer_id='".$offer_id."'";
	$offer = $wpdb->get_results($offer_sql);	
	if(count($offer)>0){
		return $offer;
	}
	else{
		return false;
	}
}

function offers_add_offer(){

	 if($offer_id = $_REQUEST['id']){
	 
	 	if(checkValidOffer($offer_id)){
			$offer_detail = checkValidOffer($offer_id);
		}else{
			return false;
		}
	 }
	 include('manage_offer.php');
}

if($_GET['action']== "deleteOffer"){
	deleteOffer($_GET['id']);
}elseif($_GET['action']== "statusChangeOffer"){
	statusChangeOffer($_GET['value'], $_GET['id']);
}

function deleteOffer($offer_id){
	global $wpdb;
	$delete_sql = " Delete from ".$wpdb->prefix."offers where offer_id='".$offer_id."'"; 
	$wpdb->query($delete_sql);
	$delMsg = "3";
	echo '<meta http-equiv="refresh" content="0;url=admin.php?page=offers_admin.php&info='.$delMsg.'">';
	exit;
} 

function statusChangeOffer($status_val, $offer_id){
	global $wpdb;
	$update_sql = "Update ".$wpdb->prefix."offers SET `status`='".$status_val."' where `offer_id`='".$offer_id."'";
	$wpdb->query($update_sql);
	$statusMsg = "4";
	echo '<meta http-equiv="refresh" content="0;url=admin.php?page=offers_admin.php&info='.$statusMsg.'">';
	exit;
} 
function offers_showPic(){  
global $wpdb;
	 
	$cuDate = date("Y-m-d");
	$offer_sql = "SELECT * FROM ".$wpdb->prefix."offers where ('".$cuDate."' between `offer_start` and `offer_end`) and status='1' ORDER BY RAND()";
	$offers = $wpdb->get_results($offer_sql);	
	if(count($offers)>0){
		$offer_data = $offers[0];
		$offer_name = $offer_data->offer_name;
		$offer_start = $offer_data->offer_start;
		$offer_end = $offer_data->offer_end;
		$offer_url = $offer_data->offer_url;
		$offer_custom_css = $offer_data->offer_custom_css;
		$status = $offer_data->status;
		$offer_desc = $offer_data->offer_desc;
		$offer_id = $offer_data->offer_id;
		if(!$_COOKIE["offers_cookie"]){ 
			 //--send a cookie that expires in 2 hours
			setcookie("offers_cookie", true, time() + (3600 * 2));
			include('new_offers.php'); 
		}
		
	}
} 

add_action( 'wp_footer', 'offers_showPic' );

function displaydate($datetime,$format = 'd-M-Y'){
	$newdateFormate = date($format,strtotime($datetime));
	return $newdateFormate;	
	include('offers_admin.php'); 
}

add_action('admin_menu', 'offers_admin_actions');
function addoffer_scripts(){	
	wp_enqueue_script('jquery');
?>
<?php }
add_action('init', 'addoffer_scripts',10,2);
?>