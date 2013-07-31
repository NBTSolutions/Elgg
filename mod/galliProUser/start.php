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
	define('GALLIPROVERSION', false);
	
    function galliProUser_init() {
		$base = elgg_get_plugins_path() . 'galliProUser';
		elgg_register_library('galliProUser', "$base/lib/galliProUser.php");
    	elgg_extend_view('index/righthandside', 'galliProUser/featuredusers');
		elgg_register_widget_type('galliProUser',elgg_echo("galliProUser:widget"),elgg_echo("galliProUser:widget:description"));
		elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'galliProUser_admin_user_hover_menu');		
		elgg_register_action('galliProUser/prouser', $base . "/actions/prouser.php", 'admin');
    }
	
	function galliProUser_admin_user_hover_menu($hook, $type, $return, $params) {
		$user = $params['entity'];
		if (elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != $user->guid) {
			elgg_load_library('galliProUser');
			if(gpu_is_premium_user($user->guid)){
				$url = "action/galliProUser/prouser?code=" . $user->guid . "&todo=unfeat";
				$action = 'removefeatured';
				$echo = elgg_echo("galliProUser:unfeature");
			} else {
				$url = "action/galliProUser/prouser?code=" . $user->guid . "&todo=feat";
				$action = 'makefeatured';
				$echo = elgg_echo("galliProUser:feature");
			}
			$url = elgg_add_action_tokens_to_url($url);
			$item = new ElggMenuItem($action, elgg_echo($echo), $url);
			$item->setSection('admin');
			$item->setLinkClass('elgg-requires-confirmation');
			$return[] = $item;
		}
		return $return;
	}
	
    elgg_register_event_handler('init','system','galliProUser_init');
?>