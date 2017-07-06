<?php   

//All curl related functions

//Create curl params from array of keys, array of values, curl url, numbers of params, method
  
function hpro_create_curl_params($key_list, $value_list, $curl_url, $num_param, $method){
        
        //set a string variable temporary to build curl postfireld string and curl url in case of GET request
        $curl_postfields_str = ""; $curl_url = $curl_url.'?'; $i = 0; $amp = '&';
        
       while ($i<$num_param){
            $key = $key_list[$i];
            $value = $value_list[$i];
            $curl_postfields_str .= '------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"'.$key.'\"\r\n\r\n'.$value.'\r\n';
            $curl_url .= $key.'='.$value.$amp;
            $i++;
            if ($i == ($num_param-1)){$amp = '';} 
       }
           $curl_postfields_str = $curl_postfields_str.'------WebKitFormBoundary7MA4YWxkTrZu0gW--';     
           
           if ($method == 'GET'){$curl_postfields_str = "";}
      
           return array($curl_url, $method, $curl_postfields_str);
                  
    }

// Call curl with curl-params and get remote data
function hpro_get_remote_data_by_curl($curl_params){
        
       $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $curl_params[0], //SAMPLE: 'http://maps.googleapis.com/maps/api/geocode/json?address='.$eventLocation.'&sensor=false',
  CURLOPT_SSL_VERIFYHOST => is_ssl(),
  CURLOPT_SSL_VERIFYPEER => is_ssl(),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
 CURLOPT_CUSTOMREQUEST => $curl_params[1],
 CURLOPT_POSTFIELDS => $curl_params[2],//SAMPLE:  "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"address\"\r\n\r\n$eventLocation\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"sensor\"\r\n\r\nfalse\r\n",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
   // "postman-token: 45f884fe-daba-bfc7-aa0d-3f523f6fa95a",
   // "x-api-key: $custommix_api_key",
  //  "x-auth-token: $custommix_auth_token"
  ),
));

 $response = curl_exec($curl);  if(empty($response)){echo 'I am response of CURL request and I have nothing'; exit();}
 $err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err; 
} 
else {
 
   $response_array = json_decode($response, True); 
   }
    
 return $response_array;  
                
    }
    
 //Curl to google api and get coordinates by cityname
function hpro_get_coord_from_google($eventLocation){
    
    $eventLocation=urlencode($eventLocation) ;
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://maps.googleapis.com/maps/api/geocode/json?address='.$eventLocation.'&sensor=false',
  CURLOPT_SSL_VERIFYHOST => is_ssl(),
  CURLOPT_SSL_VERIFYPEER => is_ssl(),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"address\"\r\n\r\n$eventLocation\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"sensor\"\r\n\r\nfalse\r\n",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
   // "postman-token: 45f884fe-daba-bfc7-aa0d-3f523f6fa95a",
   // "x-api-key: $custommix_api_key",
  //  "x-auth-token: $custommix_auth_token"
  ),
));

 $response = curl_exec($curl);  
 $err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
   $response_array = json_decode($response, True); 
   $coordinates = array('Lat'=> $response_array['results'][0]['geometry']['location']['lat'], 'Long'=>$response_array['results'][0]['geometry']['location']['lng']);  
   }
    
 return $coordinates;  
   
} 