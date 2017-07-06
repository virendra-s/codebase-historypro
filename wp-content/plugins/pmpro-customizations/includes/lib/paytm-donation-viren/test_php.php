<?php


echo "<center>Database Output</center>";

$htm=<<<EOF

<form action="test_php.php" method="post">     
Value1: <input type="text" name="field1-name" />
Value2: <input type="text" name="field2-name" />

<input type="Submit" /></form>

EOF;


function change_dbO()
{
	global R$wpdb;
	
 	$desired_level_id = $wpdb->get_results($wpdb->prepare("SELECT desired_level_id FROM " . $wpdb->prefix . "paytm_donation WHERE id = %d", $_POST['ORDERID']));
$user_name = $wpdb->get_results($wpdb->prepare("SELECT name FROM " . $wpdb->prefix . "paytm_donation WHERE id = %d", $_POST['ORDERID']));
				
				
		echo	$msg= "Thank you for your order $current_user->user_firstname. Your transaction has been successful.";
		echo 	$desired_level_id;
		echo 	$user_name;
}





?>