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
 ?>
 <p>
	<?php echo elgg_echo("galliProUser:num_display"); ?>:
	<?php echo elgg_view('input/dropdown', array('options_values' => range(1,10), 'name'=>'params[num_display]', 'value' => $vars['entity']->num_display));?>
</p>