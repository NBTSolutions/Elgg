<?php
/**
 * Group entity view
 *
 * @package ElggGroups
 */

$inv = $vars['entity'];
$advisor = 'None Yet';
$e = $inv->getEntitiesFromRelationship('advisor', true);
if (count($e) > 0) {
	$advisor = $e[0]->get('name');
}

// this is not ideal, since here we are again adding in more external bits,
// but it's pretty basic and I just need to get thru it....
$metadata = elgg_view_menu('entity', array(
	'entity' => $inv,
	'handler' => 'investigate',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));


// weird css issue if there's not description.
$desc = $inv->get('description');
if (strlen(trim($desc)) == 0) { $desc = '<p>This investigation has yet to be described</p>'; }

?>
<li class="inv">
	<div class="notebook">
		<img src="<?php print $inv->getIconURL('large'); ?>" alt="<?php print $inv->get('name'); ?>">
	</div>

	<h3><a href="<?php print $inv->getURL(); ?>"><?php print $inv->get('name'); ?></a></h3>
	<?php print $metadata; ?>

	<div class="desc"><?php print $desc; ?></div>

	<div class="people">
		<div>Investigation Owner:</div>
		<div class="owner"><?php print $inv->getOwnerEntity()->get('name'); ?></div>
	</div>
	<div class="people">
		<div>Investigation Advisor:</div>
		<div class="advisor"><?php print $advisor; ?></div>
	</div>
</li>

