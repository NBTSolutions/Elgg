<?php
// look at group_modules for original

$group = $vars['entity'];
$guid = $group->getGUID();
$site = elgg_get_site_entity();

elgg_load_library('elgg:investigation_discussion');

$subtype_get = get_input('discussion_subtype');

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
    'limit' => 2,
    'order_by' => 'e.last_action desc',
    'container_guid' => $guid,
    'full_view' => false,
);
$discussions = elgg_list_entities($options, 'elgg_get_entities', 'elgg_view_investigation_discussion_list');
if (!$discussions) {
		$discussions = elgg_echo('investigation_discussion:none_yet');
}

?>
<h1>Current Discussions:</h1>
<div class="filter-select">
	<label>Show only:</label>
	<a href="?discussion_subtype=text"><div class="icon" id="user-discussions-posted"></div>Questions/Ideas</a>
	<a href="?discussion_subtype=image"><div class="icon" id="user-images-posted"></div>Pictures</a>
	<a href="?discussion_subtype=video"><div class="icon" id="user-videos"></div>Videos</a>
	<a href="?discussion_subtype=graph"><div class="icon" id="user-graphs-posted"></div>Graphs</a>
	<a href="?discussion_subtype=map"><div class="icon" id="user-maps-posted"></div>Maps</a>
	<a href="?discussion_subtype=all" class="all">All</a>
</div>

<?php echo $discussions; ?>
