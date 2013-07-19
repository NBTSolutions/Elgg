<?php


$element_limit      = $vars['entity']->element_limit;
if (!is_numeric($element_limit) || $element_limit < 3) {
    $element_limit = 10;
}
?>
<?php
echo elgg_view_title(elgg_echo('news:settings:layout:title'));


echo elgg_view('input/dropdown', array(
    'name'  => 'params[enable_masonry]',
	'options_values' => array(
	    'yes' => elgg_echo('news:settings:layout:yes'),
		'no' => elgg_echo('news:settings:layout:no'),
		),
		'value' => $vars['entity']->enable_masonry,
));
?>
<?php echo elgg_echo('news:settings:layout:description'); ?>
<div style="clear: both;"></div>


<div>
<?php echo elgg_echo('news:settings:layout:element_limit:label'); ?>

<?php
echo elgg_view('input/dropdown', array(
	'name' => 'params[element_limit]',
	'value' => $element_limit,
	'options_values' => array(
		 4 => 4,
	 	 6 => 6,
		 8 => 8,
		10 => 10,
		12 => 12,
		14 => 14,
		20 => 20,
		25 => 25,
		30 => 30,
	),
));
?>

</div>

<?php
