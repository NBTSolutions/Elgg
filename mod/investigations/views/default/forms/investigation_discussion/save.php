<?php
/**
 * Discussion topic add/edit form body
 *
 */

$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$status = elgg_extract('status', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);
$guid = elgg_extract('guid', $vars, null);

$discussion_subtype = get_input('discussion_subtype', 'text');
$embed_type = ($discussion_subtype == 'text') ? 'none' : $discussion_subtype;

?>
<div>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
</div>
<div>
	<label><?php echo elgg_echo('investigations:topicmessage'); ?></label>
	<?php echo elgg_view('investigations/input/longtext', array('name' => 'description', 'value' => $desc, 'embed_type' => $embed_type)); ?>
</div>
<div>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>
<?php
    if($discussion_subtype) {
        echo elgg_view('input/hidden', array('name' => 'discussion_subtype', 'value' => $discussion_subtype));
    }
?>
<div>

<?php echo elgg_view('input/hidden', array('name' => 'status', 'value' => 'open')); ?>

<div class="elgg-foot">
<?php

echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'topic_guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('value' => elgg_echo("investigations:savetopic")));

?>
</div>
<?php
// XXX since there's lightbox loading, I can't find the right way to inject
// this 'change of text' bit. Something to look forward to, not time now.
if ($embed_type != 'none') {
	//elgg_load_js('inv:embed');
}
?>
