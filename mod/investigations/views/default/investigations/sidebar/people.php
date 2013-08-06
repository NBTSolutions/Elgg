<?php
/**
 * People involved in this investigation.
 */

$inv = $vars['investigation'];
$owner = $inv->getOwnerEntity();
$advisor = elgg_get_entities_from_relationship(array(
	'type' => 'user',
	'relationship' => 'advisor',
	'relationship_guid' => $inv->getGUID(),
	'inverse_relationship' => true
));
if (count($advisor) > 0) {
	$advisor = $advisor[0];
}

?>
<div class="elgg-module elgg-module-aside people-block">
	<div class="person">
		<?php echo elgg_view('investigations/sidebar/person', array('person' => $owner, 'title' => 'Investigation Coordinator')); ?>
	</div>
<?php if ($advisor) { ?>
	<div class="person">
		<?php echo elgg_view('investigations/sidebar/person', array('person' => $advisor, 'title' => 'Investigation Advisor')); ?>
	</div>
<?php } ?>

</div>
