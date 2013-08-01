<?php

$inv = $vars['entity'];
$prop = elgg_get_entities_from_relationship(array(
	'relationship' => 'proposal',
	'relationship_guid' => $inv->getGUID(),
	'inverse_relationship' => true
));

if (count($prop) > 0) {
?>
<div class="elgg-module elgg-module-aside proposal-block">
	<div class="proposal">
		<?php echo elgg_view('output/url', array(
			'text' => 'View Original Proposal',
			'value' => 'file/download/' . $prop[0]->getGUID(),
			'is_trusted' => true
		)); ?>
	</div>
</div>
<?php
}
