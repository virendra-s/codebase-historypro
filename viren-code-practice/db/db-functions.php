<?php



$vcp_products = $wpdb->prefix . 'vcp_products';

// function to create the DB / Options / Defaults					
function vcp_create_db_table() {
   	global $wpdb;
  	global $vcp_products;
        
        // create the ECPT metabox database table
	if($wpdb->get_var("show tables like '$vcp_products'") != $vcp_products) 
	{
		$sql = "CREATE TABLE " . $vcp_products . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`product_name` mediumtext NOT NULL,
		`product_code` tinytext NOT NULL,
		`product_info` tinytext NOT NULL,
		`product-size` tinytext NOT NULL,
                `product-stock` tinytext NOT NULL,
                `product-img` tinytext NOT NULL,
		UNIQUE KEY id (id)
		);";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$wpdb->query($sql);

	}

}

add_action('init', 'vcp_create_db_table', 1);