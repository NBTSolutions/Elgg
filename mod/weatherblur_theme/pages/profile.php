<?php
	// Load Elgg engine
	include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";
	
	$title = elgg_echo('Profile');

	$area2 = elgg_view_title($title); 
	
	//TODO Add Profile stuff
	
	$body = elgg_view_layout("two_column_left_sidebar", array('content' => $area2));
	
	//$body = elgg_view_layout('one_sidebar', '', $area2);


	echo elgg_view_page($title, $body);

?>
