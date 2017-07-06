<?php
/*
Plugin Name: HISTORY-PRO
Version: .1-alpha
Description: Plugin for history data project
Author: VIRENDRA SHAHANEY
Author URI:  
Plugin URI:  
Text Domain: hpro_text_domain
Domain Path: /languages/
*/
 
/*===========================================================
 * Do important initialization codes  
 * =========================================================*/
 // Stop direct access to the plugin file for security reason
 
if ( ! defined( 'ABSPATH' ) ) {  
    exit; // Exit if accessed directly
}

// Set text_domain  
function hpro_load_plugin_textdomain() {
    load_plugin_textdomain( 'hpro_text_domain', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
 
add_action( 'plugins_loaded', 'hpro_load_plugin_textdomain' );

//define plugin directory path 
define( 'historypro', plugin_dir_path( __FILE__ ));

/* ========================================
INCLUDE REQUIRED FILES LIST
=========================================== */
global $wpdb;

require_once(historypro.'hpro-functions.php' );
require_once(historypro.'/functions/hpro-curl-functions.php' ); //Seek remote data from curl and related functions
require_once(historypro.'/functions/hpro-db-functions.php' ); //Database interaction functions
require_once(historypro.'/functions/hpro-htmlgenerator-functions.php' ); //Database interaction functions
require_once(historypro.'/admin/hpro-admin.php' );  // Wordpress admin section settings
require_once(historypro.'/forms/hpro-event-seeker-form.php' ); //event seeker form shortcode
require_once(historypro.'/forms/hpro-event-feeder-form.php' ); //event feeder form shortcode
require_once(historypro.'/forms/hpro-add-category-form.php' ); //event add category form shortcode
require_once(historypro.'/forms/hpro-add-city-form.php' ); //event add city form shortcode
 

//Crwling functionality pages
// require_once(historypro.'/crawler/test-page-to-crawl.php' ); //event feeder form shortcode
// require_once(historypro.'/crawler/test-crawler.php' ); //event feeder form shortcode
// require_once(historypro.'/crawler/test-php.php' ); //event feeder form shortcode
//Curl and remote get test pages
// require_once(historypro.'curl-test.php' ); //event feeder form shortcode

/* ========================================
SET UP DATABASE TABLE
=========================================== */

/*
 * $hpro_events = $wpdb->prefix . 'hpro_events';
  

// function to create the DB / Options / Defaults					
function hpro_create_db_table() {
   	global $wpdb;
  	global $hpro_events;
        
        // create the ECPT metabox database table
	if($wpdb->get_var("show tables like '$hpro_events'") != $hpro_events) 
	{
		$sql = "CREATE TABLE " . $hpro_events . " (
		`ID` int AUTO_INCREMENT,
  PRIMARY KEY (ID),
      `eventLat` decimal (5,3),
      `eventLong` decimal (6,3),
      `eventName` text,
      `eventCategory` text,
      `eventLocation` text,
      `eventStartDateString` text,
      `eventEndDateString` text,
      `eventADBC` text(2),
      `eventTimeZone` text(20),
      `eventDescription` text,
      `eventUrl` text,
      `eventAudioUrl` text,
      `eventVideoUrl` text,
      `eventImageUrl` text  
      );";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$wpdb->query($sql);

	}

}

add_action('init', 'hpro_create_db_table', 1);
 * 
 * */

 
