<?php 
/**
 *	Elgg Premium members
 *	Author : Mohammed Aqeel | Team Webgalli
 *	Team Webgalli | Elgg developers and consultants
 *	Mail : info@webgalli.com
 *	Web	: http://webgalli.com | http://plugingalaxy.com
 *	Skype : 'team.webgalli'
 *	@package galliProUser
 * 	Plugin info : Allow elgg site admin to promote some users as featured
 *	Licence : GNU2
 *	Copyright : Team Webgalli 2011-2015
 */
	if(GALLIPROVERSION){
		$key = $vars['entity']->featured_key; 
		echo "<p>"; 
		echo elgg_echo('featured:index:licence');  
		echo elgg_view('input/text', array('name' => "params[featured_key]", 'value' => $vars['entity']->featured_key)); 
		echo elgg_echo('featured:index:licence:link');  
		echo "</p>"; 
	}

	$num = $vars['entity']->featured_noinindex; 
	if (!elgg_get_plugin_setting('featured_noinindex', 'galliProUser')) {   
		elgg_set_plugin_setting('featured_noinindex', 10, 'galliProUser'); 
	}  
	echo "<p>";    echo elgg_echo('galliProUser:noinindex');   
	echo elgg_view('input/dropdown', array('name' => 'params[featured_noinindex]', 'options_values' => range(1,16), 'value' => $num)); 
	echo "</p>";
	?>