<?php
// Load Elgg engine
include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";

$title = elgg_echo('People');

$content = elgg_view_title($title);

//elgg site
$site = elgg_get_site_entity();

$selected_type = get_input('type', 'all');
$people = array();
// TODO: will need to add pagination someday.
if ($selected_type == 'all') {
	$people = elgg_get_entities(array(
		'types' => 'user',
		'limit' => false,
		'joins' => array('left join elgg_users_entity u on e.guid = u.guid'), // good grief this is ugly!
		'order_by' => 'u.username asc' // all to sort by username!
	));
} else {
	$types = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => CUSTOM_PROFILE_FIELDS_PROFILE_TYPE_SUBTYPE,
		'limit' => false
	));
	foreach ($types as $type) {
		if ($type->getTitle() == $selected_type) {
			$people = elgg_get_entities_from_metadata(array(
				'metadata_value' => $type->guid
			));
		}
	}
}

$content .= elgg_view('components/person_types');

// TODO: this should be replaced by a proper view.
$content .= '<ul class="people">';

foreach($people AS $person) {
	$content .= elgg_view('components/person_list_item', array('person' => $person));
	//$content .= "<a href='" . $site->url . "profile/" . $person->username . "'><img src='".$person->getIconUrl('tiny')."'></a>";
}

$content .= '</ul>';
$content .= <<<JS
<script>
$(".people-page select").change(function(e) {
	var sel = $(this);
	if (sel.val()) {
		window.location = '?type=' + sel.val();
	}
});
</script>
JS;

//TODO Add People stuff
$body = elgg_view_layout("one_column", array('content' => $content, 'class' => 'people-page'));

echo elgg_view_page($title, $body);

?>
