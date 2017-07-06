<?php

// Template for an object class 
// 
class vcp_test_class {
    
    var $var_name; // This is a class variable. In classes variables are called properties and functions are called methods.
    
             // function used to construct a new object
             function __construct($var_from_construct) {		
			$this->var_name = $var_from_construct;		
		}	
              
            // function to set variable/property values
            function vcp_set_var_name ($var_in_setname){
                $this->var_name = $var_in_setname;
            }
            
             // function to retrieve variable/property values
             function vcp_get_var_name (){
                return $this->var_name;
             }
                
    }

// The following comment block tells you how to store values and get values to objects. 

/*$vcp_any_object = new vcp_test_class('I am variable passes to construct method') ;
        
$vcp_any_object->vcp_set_var_name('I am variable passed to set_name method');       
 
//access the varble by following
echo $vcp_any_object->vcp_get_var_name();
 
 OR

echo $vcp_object->var_name; // This is not a recommended way of accessing a variable data.
  */
    
    
    class vcp_stdClass {
        
        //Function to send Thank you Card VCP stands for virendra-code-practice this plugin name

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
    }