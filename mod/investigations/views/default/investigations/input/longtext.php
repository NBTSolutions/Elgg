<?php
/**
 * Mod to original allowing for embed_type to be passed down to the embed menu.
 */
$embed_type = ($vars['embed_type']) ? $vars['embed_type'] : 'none';

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-longtext {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-longtext";
}

$defaults = array(
	'value' => '',
	'rows' => '10',
	'cols' => '50',
	'id' => 'elgg-input-' . rand(), //@todo make this more robust
);

$vars = array_merge($defaults, $vars);

$value = $vars['value'];
unset($vars['value']);

echo elgg_view_menu('longtext', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
	'id' => $vars['id'],
	'embed_type' => $embed_type
));
?>

<textarea <?php echo elgg_format_attributes($vars); ?>>
<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false); ?>
</textarea>
