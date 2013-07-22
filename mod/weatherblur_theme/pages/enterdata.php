<?php
	// Load Elgg engine
	include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";
	
	$title = elgg_echo('Enter Data');

	$area2 = elgg_view_title($title); 
	
	//TODO Add EnterData stuff

	$body = elgg_view_layout("one_column", array('content' => $area2));

	echo elgg_view_page($title, $body, $canvas_area);	
	
	$content = '<div class="wb-body">
	<h2 style="text-align:center;padding: 20px">Enter Data</h2>';
    if(elgg_is_logged_in()) {
        $content .= '<iframe src="http://weatherblur-staging.herokuapp.com/collect/"></iframe>';
     }
    else {
        $content .= '<h2><a href="http://localhost:9999/elgg/login">Please Login</a></h2>';
    }
	
	$canvas_area = elgg_view_layout('default', array('content' => $content));
	echo elgg_view_page($title, $canvas_area);
?>

<!-- From a tutorial:
<iframe src="<?php echo $vars['value']; ?>">
	<p>iframe goes here</p>
</iframe>
-->


