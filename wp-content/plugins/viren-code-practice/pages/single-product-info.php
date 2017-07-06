<?php

include $_SERVER['DOCUMENT_ROOT'].'/wp-config.php';

get_header();
vcp_build_product_page();
vcp_get_related_items();
get_footer();

// Virendra : Build product page by getting Product info coming from GET Request

function vcp_build_product_page (){

global $wpdb;
$wpdb->show_errors();

if(isset($_GET)){$product_id = $_GET['product_id'];}

if(!$product_id){$product_id = 1;}

  // Do something in Database
  $product_details = $wpdb->get_results("SELECT product_name, product_image, product_info FROM vcp_products WHERE product_id = $product_id LIMIT 1");
  
  // print_r($product_details); 
  foreach ($product_details as $product_detail) 
            {
             $product_name = $product_detail->product_name;
             $product_info = $product_detail->product_info;
             $product_image = $product_detail->product_image;
            }
             
echo $html = <<<EOF

    <div style = "margin-top:50px">
    <br>
    <h2> $product_name </h2>
    <img src=$product_image  width="400" height="auto" alt=" ' . $product_name . ' "/>
        <br>
    <p style = "margin-top:20px"> $product_info </p>
        </div>

EOF
        ;

return;

}


// Function to insert related items 

function vcp_get_related_items (){

if(isset($_GET)){$product_id = $_GET['product_id'];}

if(!$product_id){$product_id = 1;}
    
$product_data = array('attribute_name' => $product_id);
echo vcp_related_shortcode_func($product_data);

return;
}

