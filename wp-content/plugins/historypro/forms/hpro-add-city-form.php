<?php 

add_shortcode ('hpro_add_new_city', 'hpro_choose_city_action');


function hpro_choose_city_action(){
   
    if (empty($_GET)) {hpro_show_new_city_form ();}
else {hpro_get_new_city_data();}
}


function hpro_show_new_city_form (){
   ?>
        
<!-- The form to feed city details in databse -->
 
<div class="wrap">
<form name="hpro_query_form" method="GET" action=" " id="cityform">
         
        <?php    echo "<h5>" . __( 'Please enter the city Name you want to feed.', 'hpro' ) . "</h4>"; ?>
    <p>(use commma for multiple city names at once.)</p>
    
        <p><?php _e("City Name: " ); ?><input type="text" name="cityName" size="20" required></p>
        
        <hr />
        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Submit', 'hpro_text_domain' ) ?>" />
        </p>
    </form>
</div>
<?php
}


function hpro_get_new_city_data(){
   
    // go for extracting city name or names.
    
    $cityName_array = explode(',', $_GET['cityName']);
    
    $i = 0;
    
   foreach ($cityName_array as $cityName){
         
    $cityName = sanitize_text_field($cityName);
    $temp[$i] = $cityName.': '.hpro_add_new_city_to_db ($cityName);
    $i++;
    
}

if ($temp) {foreach ($temp as $result){echo '*'.$result."<br>";}}
else {echo 'FAILURE NOTICE! IT SEEMS NOTHING GOOD HAPPENED.';}

}
 
 