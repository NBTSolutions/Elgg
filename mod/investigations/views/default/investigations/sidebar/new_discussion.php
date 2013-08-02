<?php


$url = 'investigation_discussion/add/' .
	$vars['investigation']->getGUID() . '?discussion_subtype=';

?>
<div class="elgg-module elgg-module-aside create-discussion">
	<div class="start">Start a Discussion</div>
	<ul class="types">
		<li><?php echo elgg_view('output/url', array(
			'href' => $url . 'text',
			'text' => '<span class="user-stat-icons" id="user-discussions-posted"></span>Questions or Ideas'
		)); ?></li>
		<li><?php echo elgg_view('output/url', array(
			'href' => $url . 'image',
			'text' => '<span class="user-stat-icons" id="user-images-posted"></span>A Photo or Picture'
		)); ?></li>
		<li><?php echo elgg_view('output/url', array(
			'href' => $url . 'video',
			'text' => '<span class="user-stat-icons" id="user-videos"></span>A Video'
		)); ?></li>
		<li><?php echo elgg_view('output/url', array(
			'href' => $url . 'graph',
			'text' => '<span class="user-stat-icons" id="user-graphs-posted"></span>A Graph or Chart'
		)); ?></li>
		<li><?php echo elgg_view('output/url', array(
			'href' => $url . 'map',
			'text' => '<span class="user-stat-icons" id="user-maps-posted"></span>A Map'
		)); ?></li>
	</ul>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('div.create-discussion div.start').click(function() {
		$(this).siblings('ul').toggle('blind');
	});
});

</script>
