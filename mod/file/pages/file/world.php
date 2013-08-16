<?php
/**
 * All files
 *
 * @package ElggFile
 */

//elgg_push_breadcrumb(elgg_echo('file'));

if (elgg_get_logged_in_user_entity()->isAdmin()) {
	elgg_register_title_button();
}

if (get_input('list_type', 'gallery') != 'list') {
	elgg_set_context('gallery');
}

$limit = get_input("limit", 10);

$title = elgg_echo('file:all');

$content = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'file',
	'limit' => $limit,
	'full_view' => FALSE,
	'metadata_name_value_pairs' => array( // show only global files.
		'name' => 'file category',
		'value' => 'global'
	)
));
if (!$content) {
	$content = elgg_echo('file:none');
}

//$sidebar = file_get_type_cloud();
$sidebar = elgg_view('file/sidebar');

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'class' => 'resources-page',
	'sidebar' => $sidebar,
	'header_override' => elgg_view('resources_page/header'),
	'filter_override' => ' '
));

echo elgg_view_page($title, $body);
