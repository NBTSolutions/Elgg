<?php
// look at group_modules for original

$group = $vars['entity'];
$guid = $group->getGUID();
$site = elgg_get_site_entity();

elgg_load_library('elgg:investigation_discussion');

$subtype_get = get_input('discussion_subtype', 'all');

switch ($subtype_get) {
    case 'map':
        $subtype = array("investigationforumtopic_map");
        break;
    case 'graph':
        $subtype = array("investigationforumtopic_graph");
        break;
    case 'image':
        $subtype = array("investigationforumtopic_image");
        break;
    case 'text':
        $subtype = array("investigationforumtopic_text");
        break;
    case 'video':
        $subtype = array("investigationforumtopic_video");
        break;
    default:
        $subtype = array('investigationforumtopic_map', 'investigationforumtopic_graph', 'investigationforumtopic_image', 'investigationforumtopic_video', 'investigationforumtopic_text');
}
$options = array(
    'type' => 'object',
    'subtypes' => $subtype,
    'limit' => 10,
    'order_by' => 'e.last_action desc',
    'container_guid' => $guid,
    'full_view' => false,
	);
?>

<div class="head">
	<div class="hint">Click icons below to filter discussions.</div>
	<h1>Current Discussions:</h1>
<?php

if (!$group->isMember(elgg_get_logged_in_user_guid()))  {
	echo '<p>' . elgg_echo('investigation_discussion:not_a_member') . '</p>';
	echo '<p>' . elgg_echo('investigations:closedgroup:request') . '</p>';
}

$dis_types = array('text'=>'', 'image'=>'', 'video'=>'', 'graph'=>'', 'map'=>'');
if (isset($dis_types[$subtype_get])) {
	$dis_types[$subtype_get] = 'selected';
	$dis_types['all'] = '';
} else {
	$dis_types['all'] = 'selected';
}
?>
<ul class="filter-select">
<li class="<?php echo $dis_types['text']; ?>"><a href="?discussion_subtype=text"><div class="icon" id="user-discussions-posted"></div>Questions/Ideas</a></li>
	<li class="<?php echo $dis_types['image']; ?>"><a href="?discussion_subtype=image"><div class="icon" id="user-images-posted"></div>Pictures</a></li>
	<li class="<?php echo $dis_types['video']; ?>"><a href="?discussion_subtype=video"><div class="icon" id="user-videos"></div>Videos</a></li>
	<li class="<?php echo $dis_types['graph']; ?>"><a href="?discussion_subtype=graph"><div class="icon" id="user-graphs-posted"></div>Graphs</a></li>
	<li class="<?php echo $dis_types['map']; ?>"><a href="?discussion_subtype=map"><div class="icon" id="user-maps-posted"></div>Maps</a></li>
	<li class="<?php echo $dis_types['all']; ?>"><a href="?discussion_subtype=all"><div class="icon" id="user-all-posted"></div>All</a></li>
</ul>

</div>
<?php

$discussions = elgg_list_entities($options, 'elgg_get_entities', 'elgg_view_investigation_discussion_list');
if (!$discussions) {
	$discussions = '<p>' . elgg_echo('investigation_discussion:none_yet') . '</p>';
}

echo $discussions;
