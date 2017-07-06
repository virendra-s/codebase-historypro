<?php

/*===============================================
 VCP-FUNCTIONS : USER FUNCTIONALITY FOR FRONT-END 
================================================= */

// Virendra : function to create product display on front end
function vcp_getproducts($product_cnt=1) {
    
    //Connect to the OSCommerce database
     $vcpdb = new wpdb(get_option('vcp_dbuser'),get_option('vcp_dbpwd'), get_option('vcp_dbname'), get_option('vcp_dbhost'));
     
    
    $retval = '';
    for ($i=0; $i<$product_cnt; $i++) {
        //Get a random product
        $product_count = 0;
        while ($product_count == 0) {
            $product_id = rand(0,3);
            $product_count = $vcpdb->get_var("SELECT COUNT(*) FROM vcp_products WHERE product_id=$product_id");  
        }
         
        //Get product image, name and URL
        $product_image = $vcpdb->get_var("SELECT product_image FROM vcp_products WHERE product_id=$product_id");
        $product_name = $vcpdb->get_var("SELECT product_name FROM vcp_products WHERE product_id=$product_id");
        $store_url = get_option('vcp_store_url');
        $image_folder = get_option('vcp_prod_img_folder');
 
        //Build the HTML code
        $retval .= '<div class="margin50">';
        $retval .= '<a href="'. get_site_url(). '/wp-content/plugins/viren-code-practice/pages/single-product-info.php?product_id=' . $product_id . '"><img src="' . $product_image . '" /></a><br />';
        $retval .= '<a href="'. get_site_url(). '/wp-content/plugins/viren-code-practice/pages/single-product-info.php?product_id=' . $product_id . '">' . $product_name . '</a>';
        $retval .= '</div>';
 
    }
    return $retval;  
     
}
