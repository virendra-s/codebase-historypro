<?php
    
function vcp_send_thank_you_mail_to_user ($user_email)

{
    				$body = '<html><body><img src="https://www.webtalkies.in/wp-content/uploads/2016/10/Thank-you.jpg" /></body></html>';
				$subject = 'Successful Transaction at webtalkies.in';
				$to = $user_email;
				$headers = array('Content-Type: text/html; charset=UTF-8');
				log_me('Body of email is: '. $body);
				log_me('Subject of email is: '. $subject);
				$mail_sent = wp_mail( $to, $subject, $body, $headers );
                                
     return $mail_sent;   
                                

            }


//List of All errors that can be met during post payment process.

function vcp_report_error ($error_code)

{
   //Set return variable for error 
    $return = array();
    
    switch ($error_code) {
    case "01":
        $return = array('status' => 'Empty Webhook Response', 'message' => 'Please contact Web Talkies.');
   	break;
    case "02":
        $return = array('status' => 'Database Error', 'message' => 'Please Try Again.');
        break;
    case "03":
        $return = array('status' => 'Security Error', 'message' => 'Mac/Hash/Key Mismatch');
        break;
    case "04":
        $return = array('status' => 'Operation Error', 'message' => 'User already has desired Membership.');
        break;
     default:
        $return = array('status' => 'Unknown Error', 'message' => 'Please Try Again.');
    }
    
    return $return;
         
}


//Function to check instamojos mac/secret salt.  NOT AN ENDPOINT - IT IS CALLED FROM ENDPOINT FUNCTIONS.      
function vcp_check_mac($data){
            
$mac_provided = $data['mac'];  // Get the MAC from the POST data
unset($data['mac']);  // Remove the MAC key from the data.

$ver = explode('.', phpversion());
$major = (int) $ver[0];
$minor = (int) $ver[1];
if($major >= 5 and $minor >= 4){ksort($data, SORT_STRING | SORT_FLAG_CASE);}
else{uksort($data, 'strcasecmp');}
// You can get the 'salt' from Instamojo's developers page(make sure to log in first): https://www.instamojo.com/developers
// Pass the 'salt' without <>
$mac_calculated_live = hash_hmac("sha1", implode("|", $data), "92e09180be7248e5bc823603ab6a95e6");
$mac_calculated_test = hash_hmac("sha1", implode("|", $data), "76f25113f9f849b5a2614c8ffb66e0ba");

// echo $mac_provided; echo 'gap'.$mac_calculated_live; exit();

 if($mac_provided == $mac_calculated_live){return True;}
 elseif ($mac_provided == $mac_calculated_test){return True;}
 else { return False; }
   
   }
   
   
   
    