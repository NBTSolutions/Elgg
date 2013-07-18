<?php
	// Load Elgg engine
	include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";
	
	$title = elgg_echo('Explore Data');

	$area2 = elgg_view_title($title); 
	
	//TODO Add Explore Data stuff
	
	$body = elgg_view_layout("one_column", array('content' => $area2));

	echo elgg_view_page($title, $body, $canvas_area);
	
	$content = '<div class="wb-body">
	<h2 style="text-align:center;padding: 20px">Explore Data</h2>
	<iframe id="explore" src="/elgg/mod/weatherblur_theme/pages/explore.html"></iframe>';
	
	$canvas_area = elgg_view_layout('default', array('content' => $content));
	echo elgg_view_page($title, $canvas_area);
	

?>
