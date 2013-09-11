<?php
/**
 * Get a listing of person types to use as a select box for filtering.
 */

// borrowed from profile_manager code:
$p_types = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE,
	'limit' => 0,
	'owner_guid' => elgg_get_site_entity()->getGUID(),
	'full_view' => false,
	'view_type_toggle' => false,
	'pagination' => false
));

$types = array();
foreach($p_types as $p_type) {
	$types[ $p_type->getTitle() ] = $p_type->getMetadata('metadata_label');
}

krsort($types);
$types['all'] = 'Show All Members';
$types = array_reverse($types, true);

echo elgg_view('input/dropdown', array('options_values' => $types, 'value' => get_input('type', 'all')));

