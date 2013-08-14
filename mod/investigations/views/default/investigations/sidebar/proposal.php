<?php

$inv = $vars['entity'];
$prop = elgg_get_entities_from_relationship(array(
	'relationship' => 'proposal',
	'relationship_guid' => $inv->getGUID(),
	'inverse_relationship' => true
));

$regex = '/[^a-zA-Z0-9_-]/';

// assuming all props are pdfs
$filename = $inv->name . '-' . $prop[0]->getGUID(); 
$filename = preg_replace($regex, '-', $filename) . '.pdf';

$val = preg_replace($regex, '', $val);
if (count($prop) > 0) {
?>
<div class="elgg-module elgg-module-aside proposal-block">
	<div class="proposal">
		<?php echo elgg_view('output/url', array(
			'text' => 'View Original Proposal',
			'value' => 'file/download/' . $prop[0]->getGUID() . '/' . $filename,
			'is_trusted' => true
		)); ?>
	</div>
</div>
<?php
}
