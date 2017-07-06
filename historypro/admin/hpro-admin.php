<?php

/* ===============================
ADMIN SECTION - ADD MENU ITEM TO WORDPRESS SETTINGS MENU
================================= */

//Virendra : my template add_action usage 

function hpro_admin_actions ()
{
    add_options_page("HPRO Menu Title", "HPRO Settings", 1, "historypro/hpro-admin.php", "hpro_admin_settings");
    
   
}
 
add_action ('admin_menu', 'hpro_admin_actions');

// Virendra Function to create settings page for plugin and to include the file that has all settings form options.
function hpro_admin_settings() {
        

//Decide what data to show in the admin options page
        if($_POST['hpro_hidden'] == 'Y') {
        // show $_POST data if update options button has been clicked and update database
        $hpro_dbhost = $_POST['hpro_dbhost'];
        update_option('hpro_dbhost', $hpro_dbhost);
         
        $hpro_dbname = $_POST['hpro_dbname'];
        update_option('hpro_dbname', $hpro_dbname);
         
        $hpro_dbuser = $_POST['hpro_dbuser'];
        update_option('hpro_dbuser', $hpro_dbuser);
         
        $hpro_dbpwd = $_POST['hpro_dbpwd'];
        update_option('hpro_dbpwd', $hpro_dbpwd);
        
        $hprogak = $_POST['hpro_google_api_key'];
        update_option('hpro_google_api_key', $hprogak);

        ?>
        <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
        <?php
    } else {
        //get options from database and display
        $hpro_dbhost = get_option('hpro_dbhost');
        $hpro_dbname = get_option('hpro_dbname');
        $hpro_dbuser = get_option('hpro_dbuser');
        $hpro_dbpwd = get_option('hpro_dbpwd');
        $hprogak = get_option('hpro_google_api_key');
         }
?>

        
<!-- The form to get setting options in your plugin settings page -->
<div class="wrap">

<?php    echo "<h2>" . __( 'HPRO settings Options', 'hpro_text_domain' ) . "</h2>"; ?>

<form name="hpro_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="hpro_hidden" value="Y">
        <?php    echo "<h4>" . __( 'HPRO Database Settings', 'hpro' ) . "</h4>"; ?>
        <p><?php _e("Database host: " ); ?><input type="text" name="hpro_dbhost" value="<?php echo $hpro_dbhost; ?>" size="20"><?php _e(" ex: localhost" ); ?></p>
        <p><?php _e("Database name: " ); ?><input type="text" name="hpro_dbname" value="<?php echo $hpro_dbname; ?>" size="20"><?php _e(" ex: hpro_shop" ); ?></p>
        <p><?php _e("Database user: " ); ?><input type="text" name="hpro_dbuser" value="<?php echo $hpro_dbuser; ?>" size="20"><?php _e(" ex: root" ); ?></p>
        <p><?php _e("Database password: " ); ?><input type="text" name="hpro_dbpwd" value="<?php echo $hpro_dbpwd; ?>" size="20"><?php _e(" ex: secretpassword" ); ?></p>
        <hr />
        <?php    echo "<h4>" . __( 'Google Maps settingss', 'hpro' ) . "</h4>"; ?>
        <p><?php _e("API KEy from Google: " ); ?><input type="text" name="hpro_google_api_key" value="<?php echo $hprogak; ?>" size="100"><?php _e(" ex: google api key" ); ?></p>
        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Update Options', 'hpro_text_domain' ) ?>" />
        </p>
    </form>
</div>

<?php
}

