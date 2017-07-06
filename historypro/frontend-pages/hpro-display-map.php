<?php 
 // This is where we’re going to take out data for event asked in event seeker form

if(!isset($wpdb)){include $_SERVER['DOCUMENT_ROOT'].'/wp-config.php';}
require_once plugin_dir_path(__FILE__)."../hpro-functions.php";

global $wpdb;

//Receive data from event Seeker form.
$seeker_form_data = $_GET;
   
//Get events by the data received
$eventsList = hpro_get_events($seeker_form_data);


//Get the centre lattitude and longitude for display map and set it in the array called $outputMapCenter
$outputMapCenter = hpro_get_mapcenter_by_ip ();
if (empty($outputMapCenter)){$outputMapCenter = array( 'Lat' => 48.8566, 'Long' => 2.3522);}

//Extract Lat and Long for map center
 $lat = $outputMapCenter[Lat];
 $long = $outputMapCenter[Long];  

//Set zoom
   $zoom = 2;
    
 //Create String for map js code.
  
      $str = ""; $counter=1;
      foreach ($eventsList as $eventParam){
      $pointLat = $eventParam->eventLat;
      $pointLong = $eventParam->eventLong;
      $placeName = $eventParam->eventLocation;
      $eventDescription = $eventParam->eventDescription;
      $eventName = $eventParam->eventName;
      $eventUrl = $eventParam->eventUrl;
      $eventAudioUrl = $eventParam->eventAudioUrl;
      $eventVideoUrl = $eventParam->eventVideoUrl;
      $eventImageUrl = $eventParam->eventImageUrl;
      
      // get iconUrl to show unique icon for category
      $iconUrl = hpro_get_category_iconurl($eventParam->eventCategory);
       
       //get content for popup infowindow
       $html = hpro_get_html_for_info($eventParam);
       
       //Build string of map-code query to insert in jquery code below.
       
       $str .= 'var myLatlng'. $counter .' = new google.maps.LatLng('.$pointLat.', '.$pointLong.');';    
       $str .= 'var image'. $counter .' = `'.$iconUrl. '`;';
       $str .= 'var contentString'. $counter .' = '.$html.';';
       $str .= 'var infowindow'.$counter.' = new google.maps.InfoWindow({content: contentString'. $counter .'});';
       $str .= 'var marker'.$counter. ' = new google.maps.Marker({position: myLatlng'.$counter.', map: map, icon: image'. $counter .', title:"Location: '.$placeName.', Title: '.$eventName.', Description: '.$eventDescription.', Website: '.$eventUrl.', Audio: '.$eventAudioUrl.', Video: '.$eventVideoUrl.', Images: '.$eventImageUrl.'"});';
       $str .= 'marker'.$counter.'.addListener('.'`click`'.', function() {infowindow'.$counter.'.open(map, marker'.$counter.');});';
              
       $counter++;
      } 
      ?>
    
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport"
        content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map-canvas { height: 100% }
    </style>
 
  <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=
          <?php echo get_option('hpro_google_api_key'); ?>&sensor=false">
    </script>
    <script type="text/javascript">
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(<?php echo $lat.', '.$long; ?>),
          zoom: <?php echo $zoom; ?>
        };
        
      var map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);
            
            <?php echo $str;?>
            
            }
      google.maps.event.addDomListener(window, 'load', initialize);
      
     </script>
  </head>
  <body>
    <div id="map-canvas"/>
  </body>
</html>

<?php 
        