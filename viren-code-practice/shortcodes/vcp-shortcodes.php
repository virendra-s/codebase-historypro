<?php

/* VCP - SHORTCODES */

// Virendra : my template shortcode - Copy to edit
function vcp_shortcode_func( $atts ) {
    
    $text_from_attributes = "Attribute-Name = {$atts[attribute_name]} ";
    $content_from_function = vcp_getproducts(2);
    
    $output = $text_from_attributes.$content_from_function;
    
    return $output;
    
}
add_shortcode( 'vcp_test_shortcode', 'vcp_shortcode_func' );


// Virendra : simple Related Item shortcode
function vcp_related_shortcode_func( $product_details ) {
    
    $text_from_attributes = "Items similar to {$product_details[attribute_name]} ";
    $content_from_function = vcp_getproducts(2);
    
    $output = $text_from_attributes.$content_from_function;
    
    return $output;
    
}
add_shortcode( 'vcp_related_shortcode', 'vcp_related_shocrtcode_func' );
 
 