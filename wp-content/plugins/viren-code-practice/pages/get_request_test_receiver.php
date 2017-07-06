<?php
 
 include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );
 
 wt_fortumo_request();
 
 function wt_fortumo_request() {
    
    global $wpdb; 
    
    //set return variable for json reporting
        $return = array();
        
   // check that the request comes from Fortumo server
 if (!in_array($_SERVER['REMOTE_ADDR'],
 array('127.0.0.1', '54.72.6.126', '54.72.6.27', '54.72.6.17', '54.72.6.23', '79.125.125.1', '79.125.5.95', '79.125.5.205'))) { 
 wp_mail('virendrashahaney2@gmail.com', 'unkown ip', 'body');
 header("HTTP/1.0 403 Forbidden");
 $return = wtvs_report_error(05);
 return wp_send_json ($return);
 die("Error: Unknown IP");
 }
 
 /*
  // check the signature
  
  $secret = ''; // insert your secret between ''
  if(empty($secret)||!check_signature($_GET, $secret)) {
    header("HTTP/1.0 404 Not Found");
    die("Error: Invalid signature");
  }
  
  */
 
              
  // Retrieve Data received from Webhook
  if(isset($_GET))    
  {
      
  $customer_phone = $_GET['sender'];//phone num.
  $amount = $_GET['amount'];//credit
  $cuid = $_GET['cuid'];//resource i.e. user
  $payment_id = $_GET['payment_id'];//unique id
  $test = $_GET['test'];
  $status = $_GET['status'];
  }
 
  //explode CUID to seperate user_id and order_id. CUID that we send to fortumo is user_id@order_id.
        $cuid_array = explode('@', $cuid);
        $user_id = $cuid_array[0];
        $order_id = $cuid_array[1];
        
  // Check if Data is proper otherwise rrport error and stop
         if(empty($user_id) || empty($order_id) || empty($status))
         {
             $return = wtvs_report_error(01);
             wp_mail('virendrashahaney2@gmail.com', 'empty response', 'body');
             return wp_send_json ($return);
         }
        
  // If data is proper go ahead and do action according to data and payment status.
      
      //get order status parameters by get request
          
	             
            if(preg_match("/completed/i", $status))
            {$return = wtvs_action_after_successful_payment($user_id, $order_id);}
            else 
            {$return = wtvs_action_after_failed_payment($user_id, $order_id);}
           
   
   //Send jason response         
     return wp_send_json ($return);   
}

function check_signature($params_array, $secret) {
    ksort($params_array);

    $str = '';
    foreach ($params_array as $k=>$v) {
      if($k != 'sig') {
        $str .= "$k=$v";
      }
    }
    $str .= $secret;
    $signature = md5($str);

    return ($params_array['sig'] == $signature);
  }