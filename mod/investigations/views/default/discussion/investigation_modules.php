<?php
// look at group_modules for original

/*
if ($vars['entity']->forum_enable == 'no') {
	return true;
}

$group = $vars['entity'];


$all_link = elgg_view('output/url', array(
	'href' => "investigation_discussion/owner/$group->guid",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
));

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'investigationforumtopic',
	'container_guid' => $group->getGUID(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('investigation_discussion:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "investigation_discussion/add/" . $group->getGUID(),
	'text' => elgg_echo('investigations:addtopic'),
	'is_trusted' => true,
));

echo elgg_view('investigations/profile/module', array(
	'title' => elgg_echo('investigation_discussion:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
*/
$group = $vars['entity'];
$site = elgg_get_site_entity();

$options = array(
	'type' => 'object',
	'subtype' => 'investigationforumtopic',
	'container_guid' => $group->getGUID()
);

?>
<h1>Create Discussions</h1>
<form method="GET" action="<?php echo $site->url."investigation_discussion/add/".$group->getGUID(); ?>">
    <select name="discussion_subtype">
      <option value="text">Text</option>
      <option value="image">Image</option>
      <option value="video">Video</option>
      <option value="graph">Graph</option>
      <option value="map">Map</option>
    </select>
    <button type="submit" value="Create Discussion"></button>
</form>

<h2>See Discussions:</h2>
<a href="<?php echo $site->url."/investigation_discussion/owner/".$group->getGUID(); ?>?discussion_subtype=text">text</a>
<a href="<?php echo $site->url."/investigation_discussion/owner/".$group->getGUID(); ?>?discussion_subtype=image">image</a>
<a href="<?php echo $site->url."/investigation_discussion/owner/".$group->getGUID(); ?>?discussion_subtype=video">video</a>
<a href="<?php echo $site->url."/investigation_discussion/owner/".$group->getGUID(); ?>?discussion_subtype=graph">graph</a>
<a href="<?php echo $site->url."/investigation_discussion/owner/".$group->getGUID(); ?>?discussion_subtype=map">map</a>

