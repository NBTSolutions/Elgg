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
    $discussions = elgg_echo('investigation_discussion:none');
}

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
    <input type="submit" value="Create Discussion"></input>
</form>

<h2>See Discussions:</h2>
<a href="?discussion_subtype=text">Text</a>
<a href="?discussion_subtype=image">Image</a>
<a href="?discussion_subtype=video">Video</a>
<a href="?discussion_subtype=graph">Graph</a>
<a href="?discussion_subtype=map">Map</a>
<a href="?discussion_subtype=all">All</a>

<?php echo $discussions; ?>
