<?php
/**
 * All files
 *
 * @package ElggFile
 */

//elgg_push_breadcrumb(elgg_echo('file'));

if (elgg_is_logged_in() && elgg_get_logged_in_user_entity()->isAdmin()) {
	elgg_register_title_button();
}

$list_type = 'list';
if (get_input('list_type', 'gallery') != 'list') {
	elgg_set_context('gallery');
	$list_type = 'gallery';
}

$limit = get_input("limit", 10);

$title = elgg_echo('file:all');

$type = get_input('type', false);
$nvp = array(array( // show only global files.
	'name' => 'file category',
	'value' => 'global'
));
$order = array();
if ($type == 'plans') {
	$nvp[] = array(
		'name' => 'lesson plan',
		'value' => 'yes'
	);
	$order = array(
		'name' => 'weight',
		'direction' => 'ASC',
		'as' => 'integer'
	);
}

$content = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'file',
	'limit' => $limit,
	'full_view' => FALSE,
	'metadata_name_value_pairs' => $nvp,
	'list_type' => $list_type,
	'order_by_metadata' => $order
));
if (!$content) {
	$content = elgg_echo('file:none');
}

$fcontext = ($type == 'plans') ? 'plans' : 'all';
//$sidebar = file_get_type_cloud();
$sidebar = elgg_view('file/sidebar', array('filter_context' => $fcontext) );


$body = elgg_view_layout('content', array(
	'filter_context' => $fcontext,
	'content' => $content,
	'title' => $title,
	'class' => 'resources-page',
	'sidebar' => $sidebar,
	'header_override' => elgg_view('resources_page/header'),
	'filter_override' => ' '
));

echo elgg_view_page($title, $body);
