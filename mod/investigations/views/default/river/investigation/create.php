<?php
/**
 * Investigation creation river view.
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);

$summary = elgg_echo("river:create:object:investigation", array($subject_link, "observation"));

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $excerpt,
));
