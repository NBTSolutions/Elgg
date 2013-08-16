<?php
/**
 * Override the default header view.
 */

$buttons = '';
// consider adding list/gallery toggle here.
if (elgg_get_logged_in_user_entity()->isAdmin()) {
	$buttons = elgg_view_menu('title', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}
?>
<div class="elgg-head resources-head">
	<?php echo elgg_view_title('Resources', array('class' => 'elgg-heading-main')); ?>
	<?php echo $buttons; ?>
</div>

