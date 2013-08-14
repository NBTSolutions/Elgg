<?php
/**
 * Get a listing of person types to use as a select box for filtering.
 */

// this is kind of a brute force approach but I can't find any easier way
// to get the name/value pairs for profile types! Stupid.
$metas = elgg_get_metadata(array(
	'type' => 'object',
	'subtype' => CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE
));

$names = array();
$values = array();
foreach ($metas as $meta) {
	$guid = $meta->__get('entity_guid');
	$name = $meta->__get('name');
	$value = $meta->__get('value');
	if ($name == 'metadata_name') {
		$names[$value] = $guid;
	}
	if ($name == 'metadata_label') {
		$values[$value] = $guid;
	}
}

$types = array();
$keys = array_keys($names);
foreach ($names as $name=>$guid) {
	foreach ($values as $label=>$v_guid) {
		if ($guid == $v_guid) {
			$types[$name] = $label;
		}
	}
}

krsort($types);
$types['all'] = 'Show All Members';
$types = array_reverse($types, true);

echo elgg_view('input/dropdown', array('options_values' => $types, 'value' => get_input('type', 'all')));

