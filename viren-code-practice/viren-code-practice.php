<?php
/*
Plugin Name: VIRENDRA CODE PRACTICE
Version: 1.0-alpha
Description: Plugin for stufy and coding practice purpose only
Author: VIRENDRA SHAHANEY
Author URI:  
Plugin URI:  
Text Domain: vcp_text_domain
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
function vcp_load_plugin_textdomain() {
    load_plugin_textdomain( 'vcp_text_domain', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
 
add_action( 'plugins_loaded', 'vcp_load_plugin_textdomain' );

//define plugin directory path 
define( 'viren_code_practice', plugin_dir_path( __FILE__ ));

/* ========================================
INCLUDE REQUIRED FILES LIST
=========================================== */
//include $_SERVER['DOCUMENT_ROOT'].'/wp-config.php';

require_once('admin/vcp-admin.php' );  // Wordpress admin section settings
require_once('includes/vcp-functions.php' ); //main plugin functions
require_once('includes/vcp-addon-functions.php' ); //main plugin functions
require_once('shortcodes/vcp-shortcodes.php' ); //plugin shortcode functions
require_once('widgets/vcp-widgets.php' ); //plugin widget functions
require_once ('classes/vcp_class_lib.php'); //plugin classes library
require_once ('api/test-api-end-point.php'); //api end points
require_once ('pages/init-test.php'); //init hook test file
require_once ('db/db-functions.php'); //init hook test file


/* ========================================
CREATE DATABASE TABLES FOR THIS PLUGIN
=========================================== */

