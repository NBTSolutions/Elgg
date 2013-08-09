<?php
/**
 * WeatherBlur User Obs Widget
 */


$num = 4;

/*$options = array(
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => $vars['entity']->owner_guid,
	'limit' => $num,
	'full_view' => FALSE,
	'pagination' => FALSE,
);
$content = elgg_list_entities_from_relationship($options);*/

$content = elgg_list_entities(array(
        'type_subtype_pair'	=>	array('object' => 'observation'),
		'limit' => $num,
        'owner_guid' => $vars['entity']->owner_guid
    ));

echo $content;

if ($content) {

} else {
	echo "No Observations";
}
