<?php 
    

//Decide what data to show in the admin options page
        if($_POST['vcp_hidden'] == 'Y') {
        // show $_POST data if update options button has been clicked and update database
        $product_name = $_POST['vcp_product'];
        vcp_update_option('vcp_product', $product_name);
         
        $product_info = $_POST['vcp_product_info'];
        vcp_update_option('vcp_product_info', $product_info);
         
        $product_code = $_POST['vcp_product_code'];
        vcp_update_option('vcp_product_code', $product_code);
         
        $product_size = $_POST['vcp_product_size'];
        vcp_update_option('vcp_product_size', $product_size);
 
        $product_img = $_POST['vcp_product_img'];
        vcp_update_option('vcp_product_img', $product_img);
 
        $stock = $_POST['vcp_product_stock'];
        vcp_update_option('vcp_product_stock', $stock);
        ?>
        <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
        <?php
    } else {
        //get options from database and display
        $product_name = vcp_get_option('vcp_product');
        $product_info = vcp_get_option('vcp_product_info');
        $product_code = vcp_get_option('vcp_product_code');
        $product_size = vcp_get_option('vcp_product_size');
        $product_img = vcp_get_option('vcp_product_img');
        $stock = vcp_get_option('vcp_product_stock');
    }
?>

        
<!-- The form to get setting options in your plugin settings page -->
<div class="wrap">

<?php    echo "<h2>" . __( 'VCP Product sub menu 1 Options', 'vcp_text_domain' ) . "</h2>"; ?>

<form name="vcp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="vcp_hidden" value="Y">
        <?php    echo "<h4>" . __( 'VCP Products Settings', 'vcp' ) . "</h4>"; ?>
        <p><?php _e("Product Name: " ); ?><input type="text" name="vcp_product" value="<?php echo $product_name; ?>" size="20"><?php _e(" ex: localhost" ); ?></p>
        <p><?php _e("Product Info: " ); ?><input type="text" name="vcp_product_info" value="<?php echo $product_info; ?>" size="20"><?php _e(" ex: vcp_shop" ); ?></p>
        <p><?php _e("Product Code: " ); ?><input type="text" name="vcp_product_code" value="<?php echo $product_code; ?>" size="20"><?php _e(" ex: root" ); ?></p>
        <p><?php _e("Product Size: " ); ?><input type="text" name="vcp_product_size" value="<?php echo $product_size; ?>" size="20"><?php _e(" ex: secretpassword" ); ?></p>
        <hr />
        <?php    echo "<h4>" . __( 'VCP Stock Settings', 'vcp' ) . "</h4>"; ?>
        <p><?php _e("Stock Number: " ); ?><input type="text" name="vcp_product_stock" value="<?php echo $stock; ?>" size="20"><?php _e(" ex: http://www.yourstore.com/" ); ?></p>
        <p><?php _e("Product image: " ); ?><input type="text" name="vcp_product_img" value="<?php echo $product_img; ?>" size="20"><?php _e(" ex: http://www.yourstore.com/images/" ); ?></p>
         
     
        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Update Options', 'vcp_text_domain' ) ?>" />
        </p>
    </form>
</div>

