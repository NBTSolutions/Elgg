<?php
/**
 * Forum topic entity view
 *
 * @package ElggGroups
 */

$full = elgg_extract('full_view', $vars, FALSE);
$topic = elgg_extract('entity', $vars, FALSE);

if (!$topic) {
	return true;
}

$poster = $topic->getOwnerEntity();
$group = $topic->getContainerEntity();
$excerpt = elgg_get_excerpt($topic->description);

$poster_link = elgg_view('output/url', array(
	'href' => $poster->getURL(),
	'text' => $poster->getIconURL(),
	'is_trusted' => true,
	'class' => 'poster'
));
$poster_text = elgg_echo('investigations:started', array($poster->name));

$tags = elgg_view('output/tags', array('tags' => $topic->tags));
$date = elgg_view_friendly_time($topic->time_created);

$replies_link = '';
$reply_text = '';
$num_replies = elgg_get_annotations(array(
	'annotation_name' => 'group_topic_post',
	'guid' => $topic->getGUID(),
	'count' => true,
));
if ($num_replies != 0) {
	$last_reply = $topic->getAnnotations('group_topic_post', 1, 0, 'desc');
	$poster = $last_reply[0]->getOwnerEntity();
	$reply_time = elgg_view_friendly_time($last_reply[0]->time_created);
	$reply_text = elgg_echo('investigations:updated', array($poster->name, $reply_time));

	$replies_link = elgg_view('output/url', array(
		'href' => $topic->getURL() . '#group-replies',
		'text' => elgg_echo('investigation:replies') . " ($num_replies)",
		'is_trusted' => true,
	));
}

// see engine/lib/navigation - this could just be dumped.
// YOU'D THINK THIS WOULD WORK: elgg_unregister_menu_item('entity', 'likes');
$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'discussion',
	'sort_by' => 'priority',
	'class' => 'editing',
));

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

$subtitle = "$poster_text $date $replies_link";

/*params = array(*/
	//'entity' => $topic,
	//'metadata' => $metadata,
	//'subtitle' => $subtitle,
	//'tags' => $tags,
//);
	//$params = $params + $vars;
	//$list_body = elgg_view('object/elements/summary', $params);

 //info = elgg_view_image_block($poster_icon, $list_body);

$body = elgg_view('output/longtext', array('value' => $topic->description));

$likes = 1;
$comments = 1;
// $likes = likes_count($topic->getGUID());
// $comments = $topic->countComments();

?>
<li class="discussion">
	<a class="poster" href="<?php echo $poster->getURL(); ?>"><img src="<?php echo $poster->getIconURL('tiny'); ?>" title="<?php echo $poster->get('name'); ?>"/></a>
<?php if ($topic->canEdit(elgg_get_logged_in_user_guid())) { ?>
	<?php echo $metadata; ?>
<?php } ?>
	<h3><?php echo $topic->title; ?></h3>
	<ul class="social">
<?php if ($likes) { ?>
		<li><span class="elgg-icon thumb"></span><?php echo $likes; ?> likes</li>
<?php }
if ($comments) { ?>
		<li><span class="elgg-icon bubble"></span><?php echo $comments; ?> comments</li>
<?php } ?>
	</ul>
	<div class="subtext"><?php echo $subtitle; ?></div>
	<?php echo $body; ?>
<?php if ($tags) { ?>
	<div class="tags"><?php echo $tags; ?></div>
<?php } ?>
</li>

<?php
	/*v*/
	//echo <<<HTML
//$info
//$body
//HTML;

//} else {
	//// brief view
	//$subtitle = "$poster_text $date $replies_link <span class=\"groups-latest-reply\">$reply_text</span>";

	//$params = array(
		//'entity' => $topic,
		//'metadata' => $metadata,
		//'subtitle' => $subtitle,
		//'tags' => $tags,
		//'content' => $excerpt,
	//);
	//$params = $params + $vars;
	//$list_body = elgg_view('object/elements/summary', $params);

	//echo elgg_view_image_block($poster_icon, $list_body);
/*}*/
