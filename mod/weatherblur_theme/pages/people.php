<?php
	// Load Elgg engine
	include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";
	
	$title = elgg_echo('People');

    $content = elgg_view_title($title);

    //elgg site
    $site = elgg_get_site_entity();

    $people = elgg_get_entities(array(
        'types' => 'user',
        'limit' => false,
    ));

    foreach($people AS $person) {
        $content .= "<a href='" . $site->url . "profile/" . $person->username . "'><img src='".$person->getIconUrl('tiny')."'></a>";
    }

	//TODO Add People stuff

	$body = elgg_view_layout("one_column", array('content' => $content));

	echo elgg_view_page($title, $body);

?>
