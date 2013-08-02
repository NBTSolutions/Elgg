<?php
/**
 * Show a block with a face and a link to the user's profile.
 * User guid passed in as part of $vars
 */

$title = ($vars['title']) ? $vars['title'] : '';
$person = $vars['person'];

?>
<div class="elgg-head">
	<h4><?php echo $title; ?></h4>
</div>
<div class="elgg-body">
	<div><?php echo $person->get('name'); ?></div>
	<a href="<?php echo $person->getURL(); ?>" title="<?php echo $person->get('name'); ?>">
		<img src="<?php echo $person->getIconURL('medium'); ?>"/>
	</a>
</div>
