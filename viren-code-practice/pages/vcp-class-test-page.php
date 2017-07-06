<?php

// include wordpress (Find a way to avoid this include, does not look right)
include $_SERVER['DOCUMENT_ROOT'].'/wp-config.php';
// Require the class library file
require_once ('../classes/vcp_class_lib.php');




$vcp_object = new vcp_test_class('I am variable passes to construct method') ;
        
$vcp_object->vcp_set_var_name('virendrashahaney2@gmail.com');       
 
echo $vcp_object->vcp_get_var_name();

echo $vcp_object->var_name;

$vcp_object_1 = new vcp_stdClass();

echo $vcp_object_1->vcp_send_thank_you_mail_to_user($vcp_object->var_name);

 