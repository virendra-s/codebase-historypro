<?php
/**
 * Plugin Name: Web Talkies One Time Pay 
 * Plugin URI: http://webtalkies.in
 * Description: This plugin allows user to pay onetime via PayTM
 * Version: 1.0.0
 * Author: Virendra
 * Author URI: http://www.webtalkies.in
 * License: GPL2
 */

//ini_set('display_errors','On');
if ( !function_exists( 'pmpro_changeMembershipLevel' ) ) { 
        require_once plugin_dir_path( __FILE__).'../paid-memberships-pro/includes/functions.php'; 
}
require_once(dirname(__FILE__) . '/encdec_paytm.php');
//register_activation_hook(__FILE__, 'wtvs_paytm_activation');
//register_deactivation_hook(__FILE__, 'wtvs_paytm_deactivation');

add_action('init', 'wtvs_paytm_donation_response');

if($_GET['donation_msg']!=''){
    add_action('the_content', 'wtvs_paytmDonationShowMessage');
}

 function wtvs_paytmDonationShowMessage($content){
        return '<div class="box">'.htmlentities(urldecode($_GET['donation_msg'])).'</div>'.$content;
}
		
function wtvs_paytm_activation() {
	global $wpdb, $wp_rewrite;
	$settings = wtvs_paytm_settings_list();
	foreach ($settings as $setting) {
		add_option($setting['name'], $setting['value']);
	}
	add_option( 'paytm_donation_details_url', '', '', 'yes' );
	$post_date = date( "Y-m-d H:i:s" );
	$post_date_gmt = gmdate( "Y-m-d H:i:s" );

	$paytm_pages = array(
		'paytm-page' => array(
			'name' => 'Paytm Transaction Details page',
			'title' => 'Paytm Transaction Details page',
			'tag' => '[paytm_donation_details]',
			'option' => 'paytm_donation_details_url'
		),
	);
	
	$newpages = false;
	log_me('...About to query...');
	$paytm_page_id = $wpdb->get_var("SELECT id FROM `" . $wpdb->posts . "` WHERE `post_content` LIKE '%" . $paytm_pages['paytm-page']['tag'] . "%'	AND `post_type` != 'revision'");
	log_me($wpdb->last_query);
	log_me('This is page_id:' . $paytm_page_id);
	log_me('...Done...');

	if(empty($paytm_page_id)){
		$paytm_page_id = wp_insert_post( array(
			'post_title' 	=>	$paytm_pages['paytm-page']['title'],
			'post_type' 	=>	'page',
			'post_name'		=>	$paytm_pages['paytm-page']['name'],
			'comment_status'=>	'closed',
			'ping_status' 	=>	'closed',
			'post_content' 	=>	$paytm_pages['paytm-page']['tag'],
			'post_status' 	=>	'publish',
			'post_author' 	=>	1,
			'menu_order'	=>	0
		));
		$newpages = true;
	}
	update_option( $paytm_pages['paytm-page']['option'], _get_page_link($paytm_page_id) );
	
	unset($paytm_pages['paytm-page']);	

	
	
	$table_name = $wpdb->prefix . "paytm_donation";
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) CHARACTER SET utf8 NOT NULL,
        `phone` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
				`address` varchar(255) CHARACTER SET utf8 NOT NULL,
        `city` varchar(255) CHARACTER SET utf8 NOT NULL,
        `country` varchar(255) CHARACTER SET utf8 NOT NULL,
        `state` varchar(255) CHARACTER SET utf8 NOT NULL,
        `zip` varchar(255) CHARACTER SET utf8 NOT NULL,
        `amount` varchar(255) NOT NULL,
        `comment` text NOT NULL,
        `payment_status` varchar(255) NOT NULL,
        `payment_method` varchar(255) NOT NULL,
        `date` datetime NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `id` (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql);
	if($newpages){
		wp_cache_delete( 'all_page_ids', 'pages' );
		$wp_rewrite->flush_rules();
	}
}

/*
function wtvs_paytm_deactivation() {
	$settings = wtvs_paytm_settings_list();
	foreach ($settings as $setting) {
		delete_option($setting['name']);
	}
}
*/

/*
function wtvs_paytm_settings_list(){
	$settings = array(
		array(
			'display' => 'Merchant ID',
			'name'    => 'paytm_merchant_id',
			'value'   => '',
			'type'    => 'textbox',
      'hint'    => 'Merchant ID'
		),
		array(
			'display' => 'Merchant Key',
			'name'    => 'paytm_merchant_key',
			'value'   => '',
			'type'    => 'textbox',
			'hint'    => 'Merchant key'
		),
		array(
			'display' => 'Website',
			'name'    => 'paytm_website',
			'value'   => '',
			'type'    => 'textbox',
      'hint'    => 'Website'
		),
		array(
			'display' => 'Industry Type ID',
			'name'    => 'paytm_industry_type_id',
			'value'   => '',
			'type'    => 'textbox',
      'hint'    => 'Industry Type ID'
		),
		array(
			'display' => 'Channel ID',
			'name'    => 'paytm_channel_id',
			'value'   => '',
			'type'    => 'textbox',
      'hint'    => 'Channel ID e.g. WEB/WAP'
		),
		array(
			'display' => 'Mode',
			'name'    => 'paytm_mode',
			'value'   => 'TEST',
			'values'  => array('TEST'=>'TEST','LIVE'=>'LIVE'),
			'type'    => 'select',
      'hint'    => 'Change the mode of the payments'
		),
		array(
			'display' => 'Default Amount',
			'name'    => 'paytm_amount',
			'value'   => '100',
			'type'    => 'textbox',
      'hint'    => 'the default donation amount, WITHOUT currency signs -- ie. 100'
		),
		array(
			'display' => 'Default Button/Link Text',
			'name'    => 'paytm_content',
			'value'   => 'Paytm',
			'type'    => 'textbox',
      'hint'    => 'the default text to be used for buttons or links if none is provided'
		),
		array(
			'display' => 'Set CallBack URL',	
			'name'    => 'paytm_callback',
			'value'   => 'YES',
			'values'  => array('YES'=>'YES','NO'=>'NO'),
			'type'    => 'select',
			'hint'    => 'Select No to disable CallBack URL'
		)
		
	);
	return $settings;
}
*/


/*
if (is_admin()) {
	add_action( 'admin_menu', 'wtvs_paytm_admin_menu' );
	add_action( 'admin_init', 'wtvs_paytm_register_settings' );
}


function wtvs_paytm_admin_menu() {
	add_menu_page('Paytm Settings', 'Paytm Settings', 'manage_options', 'wtvs_paytm_options_page', 'wtvs_paytm_options_page');
	add_menu_page('Paytm Paymet Details', 'Paytm Paymet Details', 'manage_options', 'wp_paytm_donation', 'wp_paytm_donation_listings_page');
	require_once(dirname(__FILE__) . '/paytm-donation-listings.php');
}
*/

/*
function wtvs_paytm_options_page() {
	echo'
	<div class="wrap" style="width:950px;">
		<h2>Paytm Configurations</h2>
			<form method="post" action="options.php" style="width:738px; float:left; clear:none;">';
				wp_nonce_field('update-options');
				echo '<table class="form-table">';
				$settings = wtvs_paytm_settings_list();
				foreach ($settings as $setting) {
					echo '<tr><th scope="row">'.$setting['display'].'</th><td>';
					if ($setting['type']=='radio') {
						echo $setting['yes'].' <input type="'.$setting['type'].'" name="'.$setting['name'].'" value="1" ';
						if (get_option($setting['name'])==1) { echo 'checked="checked" />'; } else { echo ' />'; }
						echo $setting['no'].' <input type="'.$setting['type'].'" name="'.$setting['name'].'" value="0" ';
						if (get_option($setting['name'])==0) { echo 'checked="checked" />'; } else { echo ' />'; }
					} elseif ($setting['type']=='select') {
						$values=$setting['values'];
						echo '<select name="'.$setting['name'].'">';
						foreach ($values as $value=>$name) {
							echo '<option value="'.$value.'" ';
							if (get_option($setting['name'])==$value) { echo ' selected="selected" ';}
							echo '>'.$name.'</option>';
						}
						echo '</select>';
					} else { echo '<input type="'.$setting['type'].'" name="'.$setting['name'].'" value="'.get_option($setting['name']).'" />'; }
					echo ' (<em>'.$setting['hint'].'</em>)</td></tr>';
				}
				echo '<tr><th style="text-align:center;"><input type="submit" class="button-primary" value="Save Changes" />';
				echo '<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="';
				foreach ($settings as $setting) {
					echo $setting['name'].',';
				}
				echo '" /></th><td></td></tr></table></form>';
		
	echo '</div>';
}
*/
/*
function wtvs_paytm_register_settings() {
	$settings = wtvs_paytm_settings_list();
	foreach ($settings as $setting) {
		register_setting($setting['name'], $setting['value']);
	}
}
*/

//removing paytm_pay_now process of bhushan totally

/*
add_shortcode( 'paytm_pay_now', 'wtvs_paytm_donate_button' );
add_action('admin_post_paytm_donation_request','wtvs_paytm_donate_button');

function wtvs_paytm_donate_button() {
	if(!pmpro_hasMembershipLevel('Premium')){
		log_me('****I dont have premium****');
	
		if( ! isset($_POST['ORDERID']) && ! isset($_GET['donation_msg'])){
			global $wpdb;
			extract(
						array(
							'paytm_merchant_id' => trim(get_option('paytm_merchant_id')),
							'paytm_merchant_key' => trim(get_option('paytm_merchant_key')),
							'paytm_website' => trim(get_option('paytm_website')),
							'paytm_industry_type_id' => trim(get_option('paytm_industry_type_id')),
							'paytm_channel_id' => trim(get_option('paytm_channel_id')),
							'paytm_mode' => trim(get_option('paytm_mode')),
							'paytm_callback' => trim(get_option('paytm_callback')),
							'paytm_amount' => trim(get_option('paytm_amount')),		
							'paytm_content' => trim(get_option('paytm_content'))
						)
					);
			if(isset($_POST['paytmcheckout'])){		
				$valid = true;
				$html='';
				$msg='';
			
				
				$current_user = wp_get_current_user();
				 
				$donor_name = $current_user->user_lastname ;
				 
				$donor_amount = trim(get_option('paytm_amount'));
				$donor_email = $current_user->user_email;
				$donor_phone = '8888888888';
				$donor_city = 'Mum';
				$donor_address = 'Mum';
				$donor_country = 'Mum';
				$donor_state = 'Mum';
				$donor_postal_code = 'Mum';
				if($valid){
					$table_name = $wpdb->prefix . "paytm_donation";
					$data = array(
						'name' => sanitize_text_field($donor_name),
						'email' => sanitize_text_field($donor_email),
						'phone' => sanitize_text_field($donor_phone),
						'address' => sanitize_text_field($donor_address),
						'city' => sanitize_text_field($donor_city),
						'country' => sanitize_text_field($donor_country),
						'state' => sanitize_text_field($donor_state),
						'zip' => sanitize_text_field($donor_postal_code),
						'amount' => sanitize_text_field($donor_amount),
						'payment_status' => 'Pending Payment',
						'date' =>date('Y-m-d H:i:s'),
					);
				
					$wpdb->insert($table_name, $data);
					$order_id = $wpdb->insert_id;
					
					$post_params = array(
						'MID' => $paytm_merchant_id,
						'ORDER_ID' => "$order_id",
						'WEBSITE' => $paytm_website,
						'CHANNEL_ID' => $paytm_channel_id,
						'INDUSTRY_TYPE_ID' => $paytm_industry_type_id,
						'TXN_AMOUNT' => $donor_amount,
						'CUST_ID' => $donor_email,
						'EMAIL' => $donor_email,
					);
					
					if($paytm_callback=='YES')
					{
						$post_params["CALLBACK_URL"] = get_permalink();
					}

					$checkSum = getChecksumFromArray ($post_params,$paytm_merchant_key);
					$call = get_permalink();
					$action_url="https://pguat.paytm.com/oltp-web/processTransaction?orderid=$order_id";
					
					if($paytm_mode == 'LIVE'){
						$action_url="https://secure.paytm.in/oltp-web/processTransaction?orderid=$order_id";
					}

					if($paytm_callback=='YES')
					{
						$html= <<<EOF
					
						<center><h1>Please do not refresh this page...</h1></center>
							<form method="post" action="$action_url" name="f1">
							<table border="1">
								<tbody>
									<input type="hidden" name="MID" value="$paytm_merchant_id">
									<input type="hidden" name="WEBSITE" value="$paytm_website">
									<input type="hidden" name="CHANNEL_ID" value="$paytm_channel_id">
									<input type="hidden" name="ORDER_ID" value="$order_id">
									<input type="hidden" name="INDUSTRY_TYPE_ID" value="$paytm_industry_type_id">									
									<input type="hidden" name="TXN_AMOUNT" value="$donor_amount">
									<input type="hidden" name="CUST_ID" value="$donor_email">
									<input type="hidden" name="EMAIL" value="$donor_email">
									<input type="hidden" name="CALLBACK_URL" value="$call">
									<input type="hidden" name="CHECKSUMHASH" value="$checkSum">
								</tbody>
							</table>
							<script type="text/javascript">document.f1.submit();</script> 
						</form>
							
EOF;
					}
					else{
						$html= <<<EOF
					
						<center><h1>Please do not refresh this page...</h1></center>
							<form method="post" action="$action_url" name="f1">
							<table border="1">
								<tbody>
									<input type="hidden" name="MID" value="$paytm_merchant_id">
									<input type="hidden" name="WEBSITE" value="$paytm_website">
									<input type="hidden" name="CHANNEL_ID" value="$paytm_channel_id">
									<input type="hidden" name="ORDER_ID" value="$order_id">
									<input type="hidden" name="INDUSTRY_TYPE_ID" value="$paytm_industry_type_id">									
									<input type="hidden" name="TXN_AMOUNT" value="$donor_amount">
									<input type="hidden" name="CUST_ID" value="$donor_email">
									<input type="hidden" name="EMAIL" value="$donor_email">
									<input type="hidden" name="CHECKSUMHASH" value="$checkSum">
									
								</tbody>
							</table>
							<script type="text/javascript">document.f1.submit();</script> 
						</form>
							
EOF;
					}
					return $html;
			}else{
				return $msg;
			}
		}else{
			$html =""; 
			$html='<form name="frmTransaction" method="post" >';
				 
			$html .= '<input name="paytmcheckout" type="submit" value="' . $paytm_content .'"/>';
			return $html;
		}
		}  
	}else{ // User has premium membership 
		log_me('****I have premium****');
	}	
}
*/

function wtvs_paytm_donation_meta_box()
{
   $screens = array( 'paytmcheckout' );
	
   foreach ( $screens as $screen ) {
      add_meta_box(  'myplugin_sectionid', __( 'Paytm', 'myplugin_textdomain' ),'wtvs_paytm_donation_meta_box_callback', $screen, 'normal','high' );
   }
}

add_action( 'add_meta_boxes', 'wtvs_paytm_donation_meta_box' );

function wtvs_paytm_donation_meta_box_callback($post)
{

echo "admin";
}

/*
add_action( 'init', 'paytmcheckout_menu_type', 0 );

function paytmcheckout_menu_type() {

	$labels = array(
		'name'                => _x( 'Donations', 'Post Type General Name', 'paytmcheckout_menu' ),
		'singular_name'       => _x( 'Paytm Donation', 'Post Type Singular Name', 'paytmcheckout_menu' ),
		'menu_name'           => __( 'Paytm Donation ', 'paytmcheckout_menu' ),
		'parent_item_colon'   => __( 'Parent Donation', 'paytmcheckout_menu' ),
		'all_items'           => __( 'All Donation', 'paytmcheckout_menu' ),
		'view_item'           => __( 'View Donation', 'paytmcheckout_menu' ),
		'edit_item'           => __( 'Edit Donation', 'paytmcheckout_menu' ),
		'update_item'         => __( 'Update Donation', 'paytmcheckout_menu' ),
		'search_items'        => __( 'Search Donation', 'paytmcheckout_menu' ),
		'not_found'           => __( 'Not found', 'paytmcheckout_menu' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'paytmcheckout_menu' ),
	);
	$args = array(
		'label'               => __( 'paytmcheckout_menu', 'paytmcheckout_menu' ),
		'description'         => __( 'list of donations', 'paytmcheckout_menu' ),
		'labels'              => $labels,
		'supports'            => array('title', 'custom-fields' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => false,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'paytmcheckout_menu', $args );

}

*/

function wtvs_paytm_donation_response(){
	// log_me('In paytm donation response before print');
	// log_me($_POST);
	// log_me('In paytm donation response after print');
	
	if(! empty($_POST) && isset($_POST['ORDERID']))
	
	{
		global $wpdb;
		extract(
						array(
							'paytm_merchant_id' => pmpro_getOption('paytm_merchant_id'),
							'paytm_merchant_key' => pmpro_getOption('paytm_merchant_key'),
							'paytm_website' => pmpro_getOption('paytm_website'),
							'paytm_industry_type_id' => pmpro_getOption('paytm_industry_type_id'),
							'paytm_channel_id' => pmpro_getOption('paytm_channel_id'),
							'paytm_mode' => pmpro_getOption('paytm_mode'),
							'paytm_callback' => pmpro_getOption('paytm_callback'),
							'paytm_amount' => $order->InitialPayment,		
							'paytm_content' => pmpro_getOption('paytm_content')
						)
					);
					
			$current_user = wp_get_current_user();
			//$level_id = 2; // HARDCODE ALERT !!
					
		if(verifychecksum_e($_POST,$paytm_merchant_key,$_POST['CHECKSUMHASH']) === "TRUE"){
			
			if($_POST['RESPCODE'] =="01"){
			
			$level_id = $wpdb->get_var("SELECT membership_id FROM $wpdb->pmpro_membership_orders WHERE code = '" . $_POST['ORDERID'] . "' LIMIT 1" );					
		
			
			$msg= "Dear $current_user->user_firstname, your transaction has been successful. Thank you for your order. Your level id is now $level_id.";
			$msg.= 'Your order id is : '.$_POST['ORDERID'];
			
		    //change membership level
			$result = pmpro_changeMembershipLevel($level_id, $current_user->ID);
			
			//Update order status in database
			$wpdb->query($wpdb->prepare("UPDATE $wpdb->pmpro_membership_orders SET status = 'Success' WHERE code = '" . $_POST['ORDERID'] . "' LIMIT 1" ));					
			

			// Send email to user with image
				$body = '<html><body><img src="https://www.webtalkies.in/wp-content/uploads/2016/10/Thank-you.jpg" /></body></html>';
				$subject = 'Successful Transaction at webtalkies.in';
				$to = $current_user->user_email;
				$headers = array('Content-Type: text/html; charset=UTF-8');
				log_me('Body of email is: '. $body);
				log_me('Subject of email is: '. $subject);
				wp_mail( $to, $subject, $body, $headers );
			}
			
			else {
					$msg= "Dear $current_user->user_firstname, your PayTm transaction has Failed For Reason  : "  . sanitize_text_field($_POST['RESPMSG']);
					$msg.= '[paytm_pay_now]';
					$wpdb->query($wpdb->prepare("UPDATE $wpdb->pmpro_membership_orders SET status = 'Failed' WHERE code = '" . $_POST['ORDERID'] . "' LIMIT 1" ));
				}
			
		}
		
		else {
				$msg= "Dear $current_user->user_firstname, a technical error aborted the order. Please try again after some time."; 
				$wpdb->query($wpdb->prepare("UPDATE $wpdb->pmpro_membership_orders SET status = 'Error' WHERE code = '" . $_POST['ORDERID'] . "' LIMIT 1" ));
			}
		
		$redirect_url = get_site_url() . '/' . get_permalink(get_the_ID());//echo $redirect_url ."<br />";
		$redirect_url = add_query_arg( array('donation_msg'=> urlencode($msg)));
		wp_redirect( $redirect_url,301 );
		
		exit;
	}
	
	
}



// add_action( 'wp', 'wtvs_process_payment_status' );
// function wtvs_process_payment_status()
// {
//     if (!is_page( 'payment-status' ))
//         return print "++++++++++Yo World!++++++++++";

//     return printf('<p>++++++++++I am payment status!++++++++++<br /></p>');
// }

// add_filter( 'query_vars', 'add_query_vars_filter' );
// function wtvs_add_query_vars_filter( $vars ){
//   $vars[] = "pmt_id";
//   $vars[] = "pmt_status";
//   return $vars;
// }

// function wtvs_add_rewrite_rules($aRules) {
// 	$aNewRules = array('payment-status/([^/]+)/([^/]+)/?$' => 'index.php?pagename=payment-status&pmt_id=$matches[1]&pmt_status=$matches[2]');
// 	$aRules = $aNewRules + $aRules;
// 	return $aRules;
// } 
// // hook wtvs_add_rewrite_rules function into rewrite_rules_array
// add_filter('rewrite_rules_array', 'wtvs_add_rewrite_rules');

add_filter( 'the_content', 'wtvs_process_payment_status_content' );
function wtvs_process_payment_status_content($content)
{
	$status = 'Hi';
    if (is_page( 'payment-status' ))
	{
		$status = get_query_var( 'pmt_status' );
		// if($status == '1'){
		$content = 'On Payment status' . $status;
	}
	else if(is_page('payment-status-error')){
		$content = 'Payment status failed';
	}
	return $content;
}