<?php
/**
 * Group profile summary
 *
 * Icon and profile fields
 *
 * @uses $vars['group']
 */

if (!isset($vars['investigation']) || !$vars['investigation']) {
	echo elgg_echo('investigations:notfound');
	return true;
}

$inv = $vars['investigation'];
$owner = $inv->getOwnerEntity();

if (!$owner) {
	// not having an owner is very bad so we throw an exception
	$msg = "Sorry, 'group owner' does not exist for guid:" . $inv->guid;
	throw new InvalidParameterException($msg);
}

?>
<div class="groups-profile clearfix elgg-image-block">
	<div class="elgg-image">
		<div class="groups-profile-icon">
			<?php
				// we don't force icons to be square so don't set width/height
				echo elgg_view_entity_icon($inv, 'large', array(
					'href' => '',
					'width' => '',
					'height' => '',
				));
			?>
		</div>
	</div>

	<div class="summary-body">
		<h3>Summary:</h3>
		<?php echo $inv->get('description'); ?>
	</div>
</div>
<?php
?>

