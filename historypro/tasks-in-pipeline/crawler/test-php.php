<?php
if(!isset($wpdb)){include $_SERVER['DOCUMENT_ROOT'].'/wp-config.php';}
require_once plugin_dir_path(__FILE__)."../hpro-functions.php";
include_once('simple_html_dom.php');


$target_url = 'http://www.webtalkies.in';
$html = new simple_html_dom();
$html->load_file($target_url);
foreach($html->find('h2') as $text){
echo $text.'<br />';
}