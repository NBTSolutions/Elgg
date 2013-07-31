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
	elgg_load_library('galliProUser');	
	$num = (int) $vars['entity']->num_display;
	if (!$num){  $num = 8;   } 
	echo gpu_get_featured_members(array('limit' => $num), true);
?>