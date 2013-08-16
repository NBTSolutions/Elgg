<?php
/**
 * Pick someone.
 *
 */
$title = 'Select a person';
if ($vars['title']) {
	$title = $vars['title'];
}
$include_self = false;
// todo: include support for self?

$peeps = elgg_get_entities(array(
	'type' => 'user',
	'limit' => false,
	'wheres' => array(
		'guid != ' . elgg_get_logged_in_user_guid()
	)
));
?>
<div>
	<label><?php echo $title; ?></label>
	<?php echo elgg_view('input/friendspicker', array('entities' => $peeps,
		'name' => 'user_guid', 'highlight' => 'all', 'radio_buttons' => true));
	?>
</div>
