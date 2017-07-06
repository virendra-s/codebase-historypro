<?php 
 // This is where we’re going to set up our data tables to populate our map:

if(!isset($wpdb)){include $_SERVER['DOCUMENT_ROOT'].'/wp-config.php';}
require_once plugin_dir_path(__FILE__)."../hpro-functions.php";
?>

<html>
    
    <script src='http://www.histroypro.dev/wp-content/plugins/historypro/jquery-lib.js'>
   
    </script>
    
    <div class="input_fields_wrap">
    <button class="add_field_button">Add More Fields</button>
    <div><input type="text" name="mytext[]"></div>
</div>
    
</html>

