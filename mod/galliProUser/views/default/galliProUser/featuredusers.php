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
	$num = elgg_get_plugin_setting('featured_noinindex', 'galliProUser'); 
	$newest_members = gpu_get_featured_members(array('limit' => $num), true);
	echo elgg_view_module('featured',  elgg_echo("galliProUser:widget"), $newest_members);	
	?>