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
	$user_guid = get_input('code');
	$todo = get_input('todo');
	if ($user_guid && $todo) {
		elgg_load_library('galliProUser');
		if ($todo == 'feat'){
			gpu_make_user_featured($user_guid);
		} elseif ($todo == 'unfeat'){
			gpu_remove_user_featured($user_guid);
		}
	}
?>	

