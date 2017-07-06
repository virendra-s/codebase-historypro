<?php 
// All hpro data preparattion and database interaction functions

//Create Database connection
function hpro_connect_to_db(){
 $con = new wpdb(get_option('hpro_dbuser'),get_option('hpro_dbpwd'), get_option('hpro_dbname'), get_option('hpro_dbhost'));
    if($con->connect_errno > 0){die('Unable to connect to database [' . $con->connect_error . ']');}
return $con;
}

//Verify Feeder Form Data
function hpro_prepare_data_for_db ($event){

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
function hpro_insert_event_into_db($hpro_events_array){
    
    global $wpdb, $con;
    
    if(empty($hpro_events_array)){return $result = "Improper event array received! Data could not be inserted.";}
    
     $con = new wpdb(get_option('hpro_dbuser'),get_option('hpro_dbpwd'), get_option('hpro_dbname'), get_option('hpro_dbhost'));
    if($con->connect_errno > 0){die('Unable to connect to database [' . $con->connect_error . ']');}
    

  $createeventstable = 'CREATE TABLE IF NOT EXISTS hpro_events (
  ID int AUTO_INCREMENT,
  PRIMARY KEY (ID),
      eventLat decimal (10,7),
      eventLong decimal (10,7),
      eventName text,
      eventCategory text,
      eventLocation text,
      eventStartDateString text,
      eventEndDateString text,
      eventADBC text(2),
      eventTimeZone text(20),
      eventDescription text,
      eventUrl text,
      eventAudioUrl text,
      eventVideoUrl text,
      eventImageUrl text  
      
  );';
 
  if(!$result = $con->query($createeventstable)){
    $result = 'There was an error running the create table query [' . $con->error . ']';
    return $result;
  }
    
  
 // Start inserting data into tables 
   
  $newline = "INSERT INTO hpro_events 
    (eventLat, eventLong, eventName, eventCategory, eventLocation, eventStartDateString, eventEndDateString, eventADBC, eventTimeZone, eventDescription, eventUrl, eventAudioUrl, eventVideoUrl, eventImageUrl) 
    VALUES ($hpro_events_array[0], $hpro_events_array[1], '$hpro_events_array[2]', '$hpro_events_array[3]', '$hpro_events_array[4]', '$hpro_events_array[5]', '$hpro_events_array[6]', '$hpro_events_array[7]', '$hpro_events_array[8]', '$hpro_events_array[9]', '$hpro_events_array[10]', '$hpro_events_array[11]', '$hpro_events_array[12]', '$hpro_events_array[13]')";
 
 // echo $newline; exit();
  
  if(!$insertmap = $con->query($newline)){
     $result = 'There was an error running the data insert query [' . $con->error . '] The query : '.$newline;
    return $result;
  }
  
  $result = 'The event details were successfully stored in historypro database.';
  return $result;
 

} // End of function hpro_insert_event_into_db


//Insert New City into database
function hpro_add_new_city_to_db($cityName){
    
    global $wpdb, $con;
    
    if(empty($cityName)){return $result = "Improper City Data received! Data could not be inserted.";}
    
    $con = new wpdb(get_option('hpro_dbuser'),get_option('hpro_dbpwd'), get_option('hpro_dbname'), get_option('hpro_dbhost'));
    if($con->connect_errno > 0){return $result = 'Error connecting with Database';}
    
    //First check if the cityName already exists in DB tables. If yes, return the information and exit.
    $newQuery = "SELECT locationId FROM hpro_locations WHERE eventLocation = '$cityName'"; 
         $id = $con->get_var($newQuery);
      if($con->error){$result = 'There was error searching database for duplicate entry [' . $con->error . ']'; return $result;}
      elseif ($id){$result = 'City Name already exists. Please check the list.'; return $result;}
      
     //Proceed if Cityname does not already exist.
      
      //First create database table if not already there.
       
  $createcitytable = 'CREATE TABLE IF NOT EXISTS hpro_locations (
  ID int AUTO_INCREMENT,
  PRIMARY KEY (ID),
      locationId int NOT NULL,
      eventLocation text NOT NULL,
      locationLattitude decimal(10,7),
      locationLongitude decimal(10,7)
      
  );';
 
  if(!$result = $con->query($createcitytable)){
    $result = 'There was an error running the create table query [' . $con->error . ']';
    return $result;
  } 
  
  //Get coordinates of the city from Google Maps
  $coordinates = hpro_get_coord_from_google($cityName);
  $lat = $coordinates['Lat']; $long = $coordinates['Long'];
      
    
    //Get the last location Id set so the next Id can be created. 
         $newQuery = "SELECT id FROM hpro_locations ORDER BY locationId ASC";
         $ids = $con->get_results($newQuery);
      if(!$ids && $con->error){$result = 'There was an error running the data insert query [' . $con->error . '] The query : '.$newline; return $result;}
      elseif (!ids && !$con->error){$location_id_new = 1;}
      else {foreach ($ids as $locationId){$location_id_last = $locationId->id;}}     
      $location_id_new = $location_id_last + 1;
       
      
 // Start inserting data into tables 
     $newline = "INSERT INTO hpro_locations 
    (id, locationId, eventLocation, locationLattitude, locationLongitude) 
    VALUES ($location_id_new, $location_id_new, '$cityName', $lat, $long)";
 
  // echo $newline; exit();
  
  if(!$insertmap = $con->query($newline)){
     $result = 'There was an error running the data insert query [' . $con->error . '] The query : '.$newline;
    return $result;
  }
  
  $result = 'The city details were successfully stored in historypro database.';
  return $result;
 

} // End of function hpro_insert_city into db

//Insert New City into database
function hpro_add_new_category_to_db($categoryName, $iconUrl){
    
    global $wpdb, $con;
    
    if(empty($categoryName) || empty($iconUrl)){return $result = "Improper Category Data received! Data could not be inserted.";}
    
    //make database connection  
    $con = new wpdb(get_option('hpro_dbuser'),get_option('hpro_dbpwd'), get_option('hpro_dbname'), get_option('hpro_dbhost'));
    if($con->connect_errno > 0){return $result = 'Error connecting with Database';}
    
    //First check if the cityName already exists in DB tables. If yes, return the information and exit.
    $newQuery = "SELECT categoryId FROM hpro_categories WHERE eventCategory = '$categoryName'";
         $id = $con->get_var($newQuery);
      if($con->error){$result = 'There was error searching database for duplicate entry [' . $con->error . ']'; return $result;}
      elseif ($id){$result = 'Category Name already exists. Please check the list.'; return $result;}
      
     //Proceed if Cityname does not already exist.
      
      //First create database table if not already there.
       
  $createcategorytable = 'CREATE TABLE IF NOT EXISTS hpro_categories (
  ID int AUTO_INCREMENT,
  PRIMARY KEY (ID),
      categoryId int NOT NULL,
      eventCategory text NOT NULL,
      eventCategoryIcon text NOT NULL
            
  );';
 
  if(!$result = $con->query($createcategorytable)){
    $result = 'There was an error running the create table query [' . $con->error . ']';
    return $result;
  } 
  
  //Set Icon Url
  $iconUrl = sanitize_text_field($iconUrl);
  
  
    //Get the last location Id set so the next Id can be created. 
         $newQuery = "SELECT id FROM hpro_categories ORDER BY categoryId ASC";
         $ids = $con->get_results($newQuery);
      if(!$ids && $con->error){$result = 'There was an error running the data insert query [' . $con->error . '] The query : '.$newline; return $result;}
      elseif (!ids && !$con->error){$category_id_new = 1;}
      else {foreach ($ids as $categoryId){$category_id_last = $categoryId->id;}}     
      $category_id_new = $category_id_last + 1;
      
          
 // Start inserting data into tables 
     $newline = "INSERT INTO hpro_categories 
    (id, categoryId, eventCategory, eventCategoryIcon) 
    VALUES ($category_id_new, $category_id_new, '$categoryName', '$iconUrl')";
 
  // echo $newline; exit();
  
  if(!$insertmap = $con->query($newline)){
     $result = 'There was an error running the data insert query [' . $con->error . '] The query : '.$newline;
    return $result;
  }
  
  $result = 'The category details were successfully stored in historypro database.';
  return $result;
 

} // End of function hpro_insert_category into db


//Get city data from database 
function hpro_get_coord_from_db($cityName){
    
    global $wpdb;
    
    $coordinates = array();
    
    $wpdb = new wpdb(get_option('hpro_dbuser'),get_option('hpro_dbpwd'), get_option('hpro_dbname'), get_option('hpro_dbhost'));
    if($wpdb->connect_errno > 0){return $error = array('Lat' => 'Error', 'Long' => 'Error');}
    
    //First check if the cityName already exists in DB tables. If yes, return the information and exit.
    $newQuery = "SELECT locationLattitude, locationLongitude FROM hpro_locations WHERE eventLocation = '$cityName'"; 
    $results = $wpdb->get_results($newQuery); 
      if($wpdb->error){return $error = array('Lat' => '1.11111', 'Long' => '1.11111');}
      
      foreach ($results as $results){$coordinates['Lat'] = $results->locationLattitude; $coordinates['Long'] = $results->locationLongitude;}
      return $coordinates;
    
    }

//Get user's coordinates by his device location
function hpro_get_mapcenter_by_ip (){
   return; 
}

  
 // Get array of all events based on Seeker Form Data
   
function hpro_get_events($seeker_form_data){
    
    global $wpdb;
   
$seekCategory = $seeker_form_data['seekCategory'];
$seekLocation = $seeker_form_data['seekLocation'];
$seekADBC = $seeker_form_data['seekADBC']; 
$seekTimeZone = $seeker_form_data['seekTimeZone'];

//Create DateString before Query database

//Extract start time from form data
$dds = $seeker_form_data['seekStartDate'];  
 $mms = $seeker_form_data['seekStartMonth'];  
 $yys = $seeker_form_data['seekStartYear'];  
 $hrs = $seeker_form_data['seekStartHour'];  
 $mns = $seeker_form_data['seekStartMinute'];  
 $ses = $seeker_form_data['seekStartSecond'];
 
  
 //Extract event End time details
 $dde = $seeker_form_data['seekEndDate'];  
 $mme = $seeker_form_data['seekEndMonth'];  
 $yye = $seeker_form_data['seekEndYear'];  
 $hre = $seeker_form_data['seekEndHour'];  
 $mne = $seeker_form_data['seekEndMinute'];  
 $see = $seeker_form_data['seekEndSecond'];  
 
//Create date time string of Seek Start Time OPTIONS OF LONG AND SHORT FORMAT
// $seeker_start_date_string = "$yys"."-"."$mms"."-"."$dds"." "."$hrs".":"."$mns".":"."$ses"; //(Format YYYY-MM-DD HH:MM:SS)
 $seeker_start_date_string = "$yys"."-"."$mms"."-"."$dds"; //(Format YYYY-MM-DD)

 //Create date time string of Seek End Time OPTIONS OF LONG AND SHORT FORMAT
// $seeker_end_date_string = "$yye"."-"."$mme"."-"."$dde"." "."$hre".":"."$mne".":"."$see"; //(Format YYYY-MM-DD HH:MM:SS)
$seeker_end_date_string = "$yye"."-"."$mme"."-"."$dde"; //(Format YYYY-MM-DD)
 
 //Build query to send to database    and get results filtered as per users desired date.
 if ($seekCategory === 'allEvents'){
 $newQuery =  "SELECT * FROM `hpro_events` WHERE eventStartDateString = %s";
 }
 else {
     $newQuery =  "SELECT * FROM `hpro_events` WHERE eventStartDateString = %s AND eventCategory = %s";
 }
 

$eventsList = $wpdb->get_results($wpdb->prepare($newQuery, $seeker_start_date_string, $seekCategory));
if($wpdb->connect_errno > 0){die('Unable to run query [' . $wpdb->connect_error . ']');}

return $eventsList;

}    
     
    function hpro_get_category_iconurl($eventCategory){
        global $wpdb;
        
        $newQuery = "SELECT eventCategoryIcon FROM hpro_categories WHERE eventCategory = %s";
        $iconUrl = $wpdb->get_var($wpdb->prepare($newQuery,$eventCategory ));
        $iconUrl = sanitize_text_field($iconUrl);
        return $iconUrl;
        
    }
    
    