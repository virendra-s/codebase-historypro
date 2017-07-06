<?php 
    

//Decide what data to show in the admin options page
        if($_POST['vcp_hidden'] == 'Y') {
        // show $_POST data if update options button has been clicked and update database
        $dbhost = $_POST['vcp_dbhost'];
        update_option('vcp_dbhost', $dbhost);
         
        $dbname = $_POST['vcp_dbname'];
        update_option('vcp_dbname', $dbname);
         
        $dbuser = $_POST['vcp_dbuser'];
        update_option('vcp_dbuser', $dbuser);
         
        $dbpwd = $_POST['vcp_dbpwd'];
        update_option('vcp_dbpwd', $dbpwd);
 
        $prod_img_folder = $_POST['vcp_prod_img_folder'];
        update_option('vcp_prod_img_folder', $prod_img_folder);
 
        $store_url = $_POST['vcp_store_url'];
        update_option('vcp_store_url', $store_url);
        ?>
        <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
        <?php
    } else {
        //get options from database and display
        $dbhost = get_option('vcp_dbhost');
        $dbname = get_option('vcp_dbname');
        $dbuser = get_option('vcp_dbuser');
        $dbpwd = get_option('vcp_dbpwd');
        $prod_img_folder = get_option('vcp_prod_img_folder');
        $store_url = get_option('vcp_store_url');
    }
?>

        
<!-- The form to get setting options in your plugin settings page -->
<div class="wrap">

<?php    echo "<h2>" . __( 'VCP Product Display Options', 'vcp_text_domain' ) . "</h2>"; ?>

<form name="vcp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="vcp_hidden" value="Y">
        <?php    echo "<h4>" . __( 'VCP Database Settings', 'vcp' ) . "</h4>"; ?>
        <p><?php _e("Database host: " ); ?><input type="text" name="vcp_dbhost" value="<?php echo $dbhost; ?>" size="20"><?php _e(" ex: localhost" ); ?></p>
        <p><?php _e("Database name: " ); ?><input type="text" name="vcp_dbname" value="<?php echo $dbname; ?>" size="20"><?php _e(" ex: vcp_shop" ); ?></p>
        <p><?php _e("Database user: " ); ?><input type="text" name="vcp_dbuser" value="<?php echo $dbuser; ?>" size="20"><?php _e(" ex: root" ); ?></p>
        <p><?php _e("Database password: " ); ?><input type="text" name="vcp_dbpwd" value="<?php echo $dbpwd; ?>" size="20"><?php _e(" ex: secretpassword" ); ?></p>
        <hr />
        <?php    echo "<h4>" . __( 'VCP Store Settings', 'vcp' ) . "</h4>"; ?>
        <p><?php _e("Store URL: " ); ?><input type="text" name="vcp_store_url" value="<?php echo $store_url; ?>" size="20"><?php _e(" ex: http://www.yourstore.com/" ); ?></p>
        <p><?php _e("Product image folder: " ); ?><input type="text" name="vcp_prod_img_folder" value="<?php echo $prod_img_folder; ?>" size="20"><?php _e(" ex: http://www.yourstore.com/images/" ); ?></p>
         
     
        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Update Options', 'vcp_text_domain' ) ?>" />
        </p>
    </form>
</div>

