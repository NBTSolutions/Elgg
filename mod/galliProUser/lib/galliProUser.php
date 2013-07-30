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
	function gpu_is_premium_user($userguid){	
		$user = get_user($userguid);
		if ($user && $user->webgalli_user == "yes" ){
			return true;
		}
		return false;
	}
	
	function gpu_get_featured_members($options, $list = false){
		$default = array(
			'metadata_name' => 'webgalli_user',
			'metadata_value' => 'yes',
			'types' => 'user',
			'limit' => 10,
			'full_view' => false,
			'pagination' => false,
			'list_type' => 'gallery',
			'gallery_class' => 'elgg-gallery-users',
			'size' => 'small',
		);
		$final = array_merge($default, $options);
		if($list){
			return elgg_list_entities_from_metadata($final);
		} else {
			return elgg_get_entities_from_metadata($final);
		}	
	}
	
	function gpu_make_user_featured($user_guid){
		$user = get_user($user_guid);
		if ($user){
			$user->webgalli_user = "yes";
			system_message(elgg_echo('galliProUser:featured'));
			forward(REFERER);
		}
		return false;
	}
	
	function gpu_remove_user_featured($user_guid){
		$user = get_user($user_guid);
		if ($user){
			$user->webgalli_user = "no";
			system_message(elgg_echo('galliProUser:unfeatured'));
			forward(REFERER);
		}
		return false;
	}
?>