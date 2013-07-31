<?php
/**
 * Pick an advisor.
 *
 */
?>
<div>
	<label>Choose an Advisor</label>
<?php
// get list of custom profile types:
$types = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE,
	'limit' => false
));
$type_guids = array();
foreach ($types as $type)  {
	// make sure the title matches the user type we're interested in:
	if (in_array($type->getTitle(), $GLOBALS['WB_ADVISOR_TYPES'])) {
		$type_guids[] = $type->guid;
	}
}

$possibles = elgg_get_entities_from_metadata(array(
	'metadata_values' => $type_guids
));


if ($possibles) {
	echo elgg_view('input/friendspicker', array('entities' => $possibles, 'name' => 'advisor_guid', 'highlight' => 'all', 'radio_buttons' => true));
} else {
	echo elgg_echo('investigations:nofriendsatall');
}
?>
</div>
