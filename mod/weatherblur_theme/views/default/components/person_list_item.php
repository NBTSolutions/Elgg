<?php
// this comes from page/people.php

$person = $vars['person'];

?>
<li class="person">
	<?php echo elgg_view('output/url', array(
		'href' => $person->getURL(),
		'text' => elgg_view('output/img', array(
			'src' => $person->getIconURL('large')
		)),
		'title' => $person->get('name')
	)); ?>
	<?php echo elgg_view('output/url', array(
		'href' => $person->getURL(),
		'text' => $person->get('name')
	)); ?>
</li>
