<?php 

add_shortcode ('hpro_add_new_category', 'hpro_choose_category_action');


function hpro_choose_category_action(){ 
    
    if (empty($_GET)) {hpro_show_new_category_form();}
else {hpro_get_new_category_data();}
}


function hpro_show_new_category_form (){
   ?>
        
<!-- The form to feed category details in databse -->
 
<div class="wrap">
<form name="hpro_query_form" method="GET" action=" " id="addcategoryform">
         
        <?php    echo "<h5>" . __( 'Please enter the category Name you want to feed.', 'hpro' ) . "</h4>"; ?>
    <p>(use commma for multiple categories at once.)</p>
    
        <p><?php _e("Category Name: " ); ?><input type="text" name="categoryName" size="20" required></p>
        
        <p><?php _e("Icon URL: " ); ?><input type="text" name="iconUrl" size="100" required></p>
        
        <hr />
        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Submit', 'hpro_text_domain' ) ?>" />
        </p>
    </form>
</div>
<?php
}

function hpro_get_new_category_data(){
    
    // go for extracting city name or names.
    
    $categoryName_array = explode(',', $_GET['categoryName']);
    $iconUrl_array = explode(',', $_GET['iconUrl']);
    
    $i = 0;
    
   foreach ($categoryName_array as $categoryName){
         
    $categoryName = sanitize_text_field($categoryName);
    $iconUrl = sanitize_text_field($iconUrl_array[$i]);
    $temp[$i] = $categoryName.': '.hpro_add_new_category_to_db ($categoryName, $iconUrl);
    $i++;
    
}

if ($temp) {foreach ($temp as $result){echo '*'.$result."<br>";}}
else {echo 'FAILURE NOTICE! IT SEEMS NOTHING GOOD HAPPENED.';}

}
 