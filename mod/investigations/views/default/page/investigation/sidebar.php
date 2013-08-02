<?php
/**
 * Elgg sidebar contents
 *
 * @uses $vars['sidebar'] Optional content that is displayed at the bottom of sidebar
 */

//echo elgg_view_menu('extras', array(
	//'sort_by' => 'priority',
	//'class' => 'elgg-menu-hz',
//));

//echo elgg_view('investigations/sidebar/owner_block', $vars);

echo elgg_view_menu('page', array('sort_by' => 'name'));
echo elgg_view_menu('title', array('sort_by' => 'priority'));

//if (elgg_is_active_plugin('search')) {
	//echo elgg_view('investigations/sidebar/search', array('entity' => $vars['investigation']));
//}
//
echo elgg_view('investigations/sidebar/new_discussion', $vars);

echo elgg_view('investigations/sidebar/people', $vars);

echo elgg_view('investigations/sidebar/members', array('entity' => $vars['investigation']));

echo elgg_view('investigations/sidebar/proposal', array('entity' => $vars['investigation']));

// optional 'sidebar' parameter
if (isset($vars['sidebar'])) {
	echo $vars['sidebar'];
}

// @todo deprecated so remove in Elgg 2.0
// optional second parameter of elgg_view_layout
if (isset($vars['area2'])) {
	echo $vars['area2'];
}

// @todo deprecated so remove in Elgg 2.0
// optional third parameter of elgg_view_layout
if (isset($vars['area3'])) {
	echo $vars['area3'];
}
