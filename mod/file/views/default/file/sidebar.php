<?php
/**
 * File sidebar
 */

// WB: hiding all thise so i don't have to find all instances and comment out.
//echo elgg_view('page/elements/comments_block', array(
	//'subtypes' => 'file',
	//'owner_guid' => elgg_get_page_owner_guid(),
//));

//echo elgg_view('page/elements/tagcloud_block', array(
	//'subtypes' => 'file',
	//'owner_guid' => elgg_get_page_owner_guid(),
//));
//

if (! isset($vars['skip_filter']) || !$vars['skip_filter']) {
	echo elgg_view('resources_page/filter', $vars);
}

