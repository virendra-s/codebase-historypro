<?php 
 // This is where we’re going to set up our data tables to populate our map:

if(!isset($wpdb)){include $_SERVER['DOCUMENT_ROOT'].'/wp-config.php';}
require_once "../hpro-functions.php";

//Connect to DB
$con = hpro_connect_to_db();

$target = 'eventregistry';

    if ($target == 'eventregistry'){
// Create data to send to curl function for event registry.com  
    $event_registry_api_key = '201605ee-2f82-4037-94f2-80d2641994ae';
    $dateStart='2017-06-13';
    $eventsSortBy='date';
    $eventsCount=1;
    $action = 'getEvent';
    $eventUri = 'eng-3239700';
    $resultType = 'info';
    $infoIncludeEventSummary = 'true';
    $infoIncludeEventConcepts = 'false';
    $infoIncludeEventArticleCounts = 'false';
    $callback = 'JSON_CALLBACK';
   $key = array('action', 'dateStart', 'eventUri',  'resultType','infoIncludeEventConcepts', 'eventsSortBy', 'eventsCount', 'apiKey', 'callback');
   $value = array($action, $dateStart, $eventUri, $resultType, $infoIncludeEventConcepts, $eventsSortBy, $eventsCount, $event_registry_api_key, $callback); 
   $url = 'http://eventregistry.org/json/event';
   $num_param = 8;
   $method = 'GET';
    }
    elseif ($target == 'newsapi'){
   // Create data to query thorugh newsapi.org
   $url = 'https://newsapi.org/v1/articles';
   $newsapi_api_key = '529f12c7ebdc41c09af9456f4517708a'; 
   $source = 'google-news';
   $sortBy = 'top';
   $key = array('source', 'sortBy', 'apiKey');
   $value = array($source, $sortBy, $newsapi_api_key); 
   $num_param = 3;
   $method = 'GET';
    }
    
  $curl_params = hpro_create_curl_params($key, $value, $url, $num_param, $method); // print_r($curl_params);  exit();
    
  $remote_data = hpro_get_remote_data_by_curl($curl_params);  print_r($remote_data);  // exit();
  
  $eventDate = $remote_data [$eventUri]['info']['eventDate']; echo $eventDate.'<br/>';  
  $eventName = $remote_data [$eventUri]['info']['title']['eng'] ; echo $eventName.'<br/>';
  $eventDescription = $remote_data [$eventUri]['info']['summary']['eng']; echo $eventDescription.'<br/>';
  $eventLocation = $remote_data [$eventUri]['info']['location']['label']['eng'] ; echo $eventLocation.'<br/>';
  $eventCountry = $remote_data [$eventUri]['info']['location']['country']['label']['eng'] ; echo $eventCountry.'<br/>';
  $eventCategory = $remote_data [$eventUri]['info']['categories'][0]['label'] ; echo $eventCategory.'<br/>';
 
  exit();
      


//-----------------FUNCTIONS FOR THE FILE-----------------
// create date database table if does not exist. 
hpro_create_wiki_date_table();

//Get wiki page name from get request
$titles = $_GET['wiki_monthname'].'_'.$_GET['wiki_datenum']; 


//Fixed Wiki Params
$format = 'json'; $prop = 'revisions'; $rvprop = 'content'; $action = 'query'; $uselang = 'text';


// Create data to send to curl function for creating curl params for wiki request
   $key = array('action', 'format', 'titles', 'prop', 'rvprop');
   $value = array($action, $format, $titles, $prop, $rvprop); 
   $url = 'https://en.wikipedia.org/w/api.php';
   $num_param = 5;
   $method = 'GET';
 
//Send data to set-curl-details function to create curl params for wiki
$curl_params = hpro_set_curl_url($key, $value, $url , $num_param, $method);

//Curl wiki and get response
$response = hpro_get_wiki_by_curl($curl_params);
// print_r($response); exit();

//Send response array to filering fucntion and get seperate arrays of events, births and deaths.
$data_lists = hpro_clean_wiki_data($response);
// print_r($data_lists); exit();

//Get lists of all events
$db_result['Event'] = hpro_seperate_wiki_data($data_lists[0], 'Uncategorized');
//Insert events into database
foreach ($db_result['Event'] as $db_event){$i++; echo '<br/>'.'Database Connection Status = '.$con->has_connected; echo $final_result = '<br/>'.$i.':'.hpro_insert_event_into_wiki_table($db_event);}

//Get lists of all births
$db_result['Birth'] = hpro_seperate_wiki_data($data_lists[1], 'Birth'); 
//Insert births into database
foreach ($db_result['Birth'] as $db_birth){$j++; echo '<br/>'.'Database Connection Status = '.$con->has_connected; echo $final_result = '<br/>'.$j.':'.hpro_insert_event_into_wiki_table($db_birth);}

//Get lists of all deaths
$db_result['Death'] = hpro_seperate_wiki_data($data_lists[2], 'Death');
//Insert births into database
foreach ($db_result['Death'] as $db_death){$k++; echo '<br/>'.'Database Connection Status = '.$con->has_connected; echo $final_result = '<br/>'.$k.':'.hpro_insert_event_into_wiki_table($db_death);}

echo '<br> ALL PROCESS DONE!';
exit();


//THE WIKI DATA JASON IS COMPLEX. WE BREAK IT INTO USEFUL DATA BY FLOWING FUNCTION
//This function takes the json respnse from wiki and iterates through the json to identify 
//the page id, the events lists, the births, the deaths from the json.


function hpro_clean_wiki_data($response){

// Iterate through wiki json to identify the page id. 
$page_id = current(current($response['query']['pages'])); 

$data = $response['query']['pages'][$page_id]['revisions'][0]['*'];  //$data is a string ... text of wiki page
//print_r($response); exit();

//Explode the data into an array of events, births and deaths
$new_data = explode ('==', $data);

$junk_1 = $new_data[0];
$events_heading = $new_data[1];
$events_list = $new_data[2]; //string of the events on a given date
$births_heading = $new_data[3]; 
$births_list = $new_data[4]; //string of the births on a given date
$deaths_heading = $new_data[5];
$deaths_list = $new_data[6]; //string of the deaths on a given date
$junk_2 = $new_data[7];

// echo $events_list; exit();
// echo $births_list; exit();
// echo $deaths_list; exit();

return array($events_list, $births_list, $deaths_list);

}

//Function to sepearate event, birth, death data from wiki data.
function hpro_seperate_wiki_data($data_list, $category){

$event_array = explode ('*', $data_list);

//Loop through event array to extract individual event, birth item or death item.
$i = 1;

foreach($event_array as $single){
       
   $event_str = explode('dash;', $single);
   
$my_year = hpro_get_year_from_wiki_string ($event_str[0]);  
$my_event = hpro_get_event_from_wiki_string ($event_str[1]);  

$event['EventName'] = $my_event[0];
$event['EventCategory'] = $category;
$event['EventDescription'] = $my_event[1];
if (strlen($_GET['wiki_datenum'])<2){$event['EventStartDate'] = '0'.$_GET['wiki_datenum'];} else{$event['EventStartDate'] = $_GET['wiki_datenum'];}  
if (strlen($_GET['wiki_monthnum'])<2){$event['EventStartMonth'] = '0'.$_GET['wiki_monthnum'];} else{$event['EventStartMonth'] = $_GET['wiki_monthnum'];}  
$event['EventStartYear'] = $my_year;

//INsert Dummy Location and Lat and Long for database functioning
$event['EventLocation'] = 'Dummy';

$array_of_hpro_events_array [$i-1] = hpro_verify_temp_wiki_data ($event);

$i++;
 
}
return $array_of_hpro_events_array;
}

//Function to extract year number from the wiki event string
function hpro_get_year_from_wiki_string ($str){
 
$year = preg_split('/[\[\]\s\n]+/', $str);

$my_year = $year[1];

return $my_year;

}

//Function to extract Evenet, Person name from wiki string.
function hpro_get_event_from_wiki_string ($str){
    
   $event_long_string = preg_split('/[\[\]\,\(\)\.\'\n]+/', $str);
   $event_short_string = preg_split('/[\[\]\n]+/', $str);
  foreach ($event_long_string as $eventtext) {$eventDescription .= $eventtext;}

   $eventDescription = utf8_decode($eventDescription);
   $eventName = utf8_decode($event_short_string[1]);
   
   return array($eventName, $eventDescription);
}

/*
function copy2_hpro_get_year_from_wiki_string ($str){
    
    $my_year = '';
    $str_array = str_split($str);
    
    
 foreach ($str_array as $cha){
     
     if ($cha === '[' || $cha === ']' || $cha === ' ' || $cha === 'n' || $cha === '&' ) {} else {$my_year .= $cha;} 
 }
    
    return $my_year;
}


 function copy_hpro_get_year_from_wiki_string ($str){
 
    
$my_year_minus_ob = explode('[[', $str); 
$my_year_minus_cb = explode(']]', $my_year_minus_ob[1]);
    $my_year = $my_year_minus_cb[0];
return $my_year;
}

function copy_hpro_get_event_from_wiki_string ($str){
    
    $my_event_minus_ob = explode('[[', $str);  
$my_event_minus_cb = explode(']]', $my_event_minus_ob[1]);
    $my_event = $my_event_minus_cb[0];      
            
            
   return $my_event;
}

*/

/*========================================================================================
 * DATA INSERT INTO WIKI TEMPORARY TABLE==================================================
 * =======================================================================================
 */

//Verify Feeder Form Data
function hpro_verify_temp_wiki_data ($event){

 //Extract event variables from the data
$eventName = $event['EventName'];
$eventCategory = $event['EventCategory'];
$eventLocation = $event['EventLocation'];
$eventDescription = $event['EventDescription'];
$eventUrl = $event['EventUrl'];
$eventAudioUrl = $event['EventAudioUrl'];
$eventVideoUrl = $event['EventVideoUrl'];
$eventImageUrl = $event['EventImageUrl'];
$eventADBC = $event['EventADBC']; 
$eventTimeZone = $event['EventTimeZone'];

//Get event Location Lattitude and Longitude by google api
 $coordinates = hpro_get_coord_from_db($eventLocation);
$eventLongitude = $coordinates[Lat];
 $eventLattitude = $coordinates[Long]; 

if ($eventLattitude == 'Error' || $evenLongitude == 'Error' )
{$hpro_events_array = array(); return $hpro_events_array;}


//Set category to Uncategorised in case of allEvents selected
if ($eventCategory == "allEvents"){$eventCategory = 'Uncategorized';}

//Extract Event Starts date time details

 $dds = $event['EventStartDate'];  
 $mms = $event['EventStartMonth'];  
 $yys = $event['EventStartYear'];  
 $hrs = $event['EventStartHour'];  
 $mns = $event['EventStartMinute'];  
 $ses = $event['EventStartSecond'];
 
  
 //Extract event End date time details
 $dde = $event['EventEndDate'];  
 $mme = $event['EventEndMonth'];  
 $yye = $event['EventEndYear'];  
 $hre = $event['EventEndHour'];  
 $mne = $event['EventEndMinute'];  
 $see = $event['EventEndSecond'];  
 

//Create date time string of Event Start Time OPTIONS OF LONG AND SHORT FORMAT
// $event_start_date_string = "$yys"."-"."$mms"."-"."$dds"." "."$hrs".":"."$mns".":"."$ses"; //(Format YYYY-MM-DD HH:MM:SS)
 $event_start_date_string = "$yys"."-"."$mms"."-"."$dds"; //(Format YYYY-MM-DD)

 //Create date time string of Event End Time OPTIONS OF LONG AND SHORT FORMAT
// $event_end_date_string = "$yye"."-"."$mme"."-"."$dde"." "."$hre".":"."$mne".":"."$see"; //(Format YYYY-MM-DD HH:MM:SS)
$event_end_date_string = "$yye"."-"."$mme"."-"."$dde"; //(Format YYYY-MM-DD)
 
/* 
//Verify if end date is later than the start date
if($eventADBC == 'AD' && $event_end_date_string > $event_start_date_string ) //if time period is AD
    {$date_verification = 'DATES-OK';}
elseif ($eventADBC == 'BC' && $event_end_date_string < $event_start_date_string)
    {$date_verification = 'DATES-OK';}
else {$date_verification = 'DATES-NOT-OK';}
*/
 
 
 // Sanitize text fields for database insertion
 $event_start_date_string = sanitize_text_field ($event_start_date_string);
 $event_end_date_string = sanitize_text_field ($event_end_date_string);
 $eventName = sanitize_text_field ($eventName);
$eventCategory = sanitize_text_field ($eventCategory);
$eventLocation = sanitize_text_field ($eventLocation);
$eventDescription = sanitize_text_field ($eventDescription);
$eventUrl = sanitize_text_field ($eventUrl);
$eventAudioUrl = sanitize_text_field ($eventAudioUrl);
$eventVideoUrl = sanitize_text_field ($eventVideoUrl);
$eventImageUrl = sanitize_text_field ($eventImageUrl);
$eventADBC = sanitize_text_field ($eventADBC);
$eventTimeZone = sanitize_text_field ($eventTimeZone);
 
//Store this event details in an array to return
 
 $hpro_events_array = array(    
    $eventLongitude, $eventLattitude, $eventName, $eventCategory, $eventLocation,
    $event_start_date_string, $event_end_date_string, $eventADBC , $eventTimeZone , 
    $eventDescription,  $eventUrl , $eventAudioUrl , $eventVideoUrl , $eventImageUrl ,
   );
 
 return $hpro_events_array;
 
 }

//Insert Feeder form data into database
function hpro_insert_event_into_wiki_table($hpro_events_array){
    
    global $wpdb, $con;
    
    if(empty($hpro_events_array) || empty($hpro_events_array[2])){return $result = "Improper event array received! Data could not be inserted.";}
    
   // Start inserting data into tables 
   
  $newline = "INSERT INTO hpro_events_wiki_".$_GET['wiki_monthname'].'_'.$_GET['wiki_datenum']."  
    (eventLat, eventLong, eventName, eventCategory, eventLocation, eventStartDateString, eventEndDateString, eventADBC, eventTimeZone, eventDescription, eventUrl, eventAudioUrl, eventVideoUrl, eventImageUrl) 
    VALUES ($hpro_events_array[0], $hpro_events_array[1], '$hpro_events_array[2]', '$hpro_events_array[3]', '$hpro_events_array[4]', '$hpro_events_array[5]', '$hpro_events_array[6]', '$hpro_events_array[7]', '$hpro_events_array[8]', '$hpro_events_array[9]', '$hpro_events_array[10]', '$hpro_events_array[11]', '$hpro_events_array[12]', '$hpro_events_array[13]')";
 
 // echo $newline; exit();
  
  if(!$insertmap = $con->query($newline)){
     $result = 'There was an error running the data insert query. insertmap varibale = '.$insertmap.' The query : <br/>'.$newline.'<br/>';
    return $result;
  }
  
  $result = 'Insertmap Variable = '.$insertmap.' The event details were successfully stored in historypro database.';
  return $result;
 

} // End of function hpro_insert_event_into_db


//Create Table for wiki date data

function hpro_create_wiki_date_table (){
    
global $wpdb, $wpdb;

$createwikitable = 'CREATE TABLE IF NOT EXISTS hpro_events_wiki_'.$_GET['wiki_monthname'].'_'.$_GET['wiki_datenum'].' (
  ID int AUTO_INCREMENT,
  PRIMARY KEY (ID),
      eventLat decimal (10,7) NULL,
      eventLong decimal (10,7) NULL,
      eventName text,
      eventCategory text,
      eventLocation text NULL,
      eventStartDateString text,
      eventEndDateString text NULL,
      eventADBC text(2) NULL,
      eventTimeZone text(20) NULL,
      eventDescription text NULL,
      eventUrl text NULL,
      eventAudioUrl text NULL,
      eventVideoUrl text NULL,
      eventImageUrl text NULL  
      
  );';
 
  if(!$result = $wpdb->query($createwikitable)){$result = 'There was an error running the create table query.'; echo $result; exit();}
  else {$result = True;}
  
    return $result;
  }
  