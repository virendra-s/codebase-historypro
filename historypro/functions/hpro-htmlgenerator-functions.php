<?php 

function hpro_get_html_for_info ($eventParam){
        
        if(!empty($eventParam->eventDescription)){$Description = $eventParam->eventDescription;} else {$Description = "Event Description Not Available.";}
        if(!empty($eventParam->eventUrl)){$LinkWebsite = "Visit Event Website";}
        if(!empty($eventParam->eventVideoUrl)){$LinkVideo = "Watch Related Video";}
        if(!empty($eventParam->eventAudioUrl)){$LinkAudio = "Listen to Related Audio";}
        if(!empty($eventParam->eventImageUrl)){$LinkImage = '<a href="'.$eventParam->eventImageUrl.'"><img src="'.$eventParam->eventImageUrl.'" alt="Event Pic Alt" style="width:300px;height:100px;"></a>';}
       
       
    $html = <<<EOF
        
      '<div id="content">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h1 id="firstHeading" class="firstHeading">$eventParam->eventName</h1>'+
      '<div id="bodyContent">'+
      '$LinkImage'+
      '<p><b>About the event: </b>$Description</p>'+
      '<p><a href="$eventParam->eventUrl">$LinkWebsite</a> '+
      '<p><a href="$eventParam->eventVideoUrl">$LinkVideo</a> '+
      '<p><a href="$eventParam->eventAudioUrl">$LinkAudio</a> '+
      '</div>'+
      '</div>'
        
EOF;
    
    return $html;
    
} 


function hpro_date_drop_down_generator($parameterName, $formId){

    ?>  
<p><?php _e("Date: " ); ?><select name="<?php echo $parameterName ?>" form="<?php echo $formId ?>" required>
        <?php $counter = 1; while ($counter <32){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>"><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select> </p>
         <?php
     }
   
     
function hpro_month_drop_down_generator($parameterName, $formId){

    ?>  
     <p><?php _e("Month: " ); ?><select name="<?php echo $parameterName ?>" form="<?php echo $formId ?>" required>
        <?php $counter = 1; while ($counter <13){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>"><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select> </p>
        
    <?php
}
     
    
    function hpro_hour_drop_down_generator($parameterName, $formId){

    ?>  
     <p><?php _e("Hour: " ); ?><select name="<?php echo $parameterName ?>" form="<?php echo $formId ?>" required>
         <?php $counter = 00; while ($counter <24){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>"><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select> </p>
        
    <?php
     }
      
   function hpro_minute_drop_down_generator($parameterName, $formId){

    ?>   
     <p><?php _e("Minute: " ); ?><select name="<?php echo $parameterName ?>" form="<?php echo $formId ?>" required>
         <?php $counter = 00; while ($counter <60){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>"><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select></p>
        
        <?php
     }
     
    function hpro_seconds_drop_down_generator($parameterName, $formId){

    ?>  
     <p><?php _e("Second: " ); ?><select name="<?php echo $parameterName ?>" form="<?php echo $formId ?>" required>
        <?php $counter = 00; while ($counter <60){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>"><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select></p>
        
             <?php
     }
     
      function hpro_category_drop_down_generator($parameterName, $formId){
          
          global$wpdb;
          
          $newQuery = "SELECT * FROM hpro_categories ORDER BY eventCategory ASC";
          $results = $wpdb->get_results($newQuery);
          

    ?> 
     <p><?php _e("Select Event Category: " ); ?><select name="<?php echo $parameterName ?>" form="<?php echo $formId ?>" required> 
              <option value="allEvents">allEvents</option>
              <?php foreach($results as $result){
              $eventCategory = $result->eventCategory;?>
             <option value="<?php echo $eventCategory ?>"><?php echo $eventCategory ?></option>
             <?php } ?>
        
        </select> </p>
        <?php
     }
     
     function hpro_location_drop_down_generator($parameterName, $formId){
          
          global$wpdb;
          
          $newQuery = "SELECT * FROM hpro_locations ORDER BY eventLocation ASC";
          $results = $wpdb->get_results($newQuery);
          

    ?> 
     <p><?php _e("Event Location: " ); ?><select name="<?php echo $parameterName ?>" form="<?php echo $formId ?>" required> 
              <?php foreach($results as $result){
              $eventLocation = $result->eventLocation;?>
             <option value="<?php echo $eventLocation ?>"><?php echo $eventLocation ?></option>
             <?php } ?>
        
        </select> </p>
        <?php
     }
 
    
