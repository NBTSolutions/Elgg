<?php
/**
 * All files
 *
 * @package ElggFile
 */

elgg_push_breadcrumb(elgg_echo('file'));

elgg_register_title_button();

$limit = get_input("limit", 10);

$title = elgg_echo('file:all');

// this is probably not the most elgg-efficient way to get these IDs:
$name_id = '';
$value_id = '';
$name_meta = elgg_get_metadata(array(
	'metadata_name' => 'file category'
));
if (count($name_meta) > 0) {
	$name_id = $name_meta[0]->__get('name_id');
}
$value_meta = elgg_get_metadata(array(
	'metadata_value' => 'proposal'
));
if (count($value_meta) > 0) {
	$value_id = $value_meta[0]->__get('value_id');
}

$dbprefix = elgg_get_config('dbprefix');

$content = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'file',
	'limit' => $limit,
	'full_view' => FALSE,
	'wheres' => array(
		"NOT EXISTS ( SELECT 1 FROM {$dbprefix}metadata md WHERE md.entity_guid = e.guid AND md.name_id = $name_id AND md.value_id = $value_id)"
	)
));
if (!$content) {
	$content = elgg_echo('file:none');
}

$sidebar = file_get_type_cloud();
$sidebar = elgg_view('file/sidebar');

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
