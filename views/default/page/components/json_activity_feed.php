<?php
/**
 * View a list of items
 *
 * @package Elgg
 *
 * @uses $vars['items']       Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['offset']      Index of the first list item in complete list
 * @uses $vars['limit']       Number of items per page. Only used as input to pagination.
 * @uses $vars['count']       Number of items in the complete list
 * @uses $vars['base_url']    Base URL of list (optional)
 * @uses $vars['pagination']  Show pagination? (default: true)
 * @uses $vars['position']    Position of the pagination: before, after, or both
 * @uses $vars['full_view']   Show the full view of the items (default: false)
 * @uses $vars['list_class']  Additional CSS class for the <ul> element
 * @uses $vars['item_class']  Additional CSS class for the <li> elements
 */

$items = $vars['items'];
$offset = elgg_extract('offset', $vars);
$limit = elgg_extract('limit', $vars);
$count = elgg_extract('count', $vars);
$base_url = elgg_extract('base_url', $vars, '');
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');

$activities = array();

// I apologize this is really ugly

if (is_array($items) && count($items) > 0) {
    //var_dump($items);
	foreach ($items as $item) {

        $user = get_user($item->subject_guid);
        $action = $item->action_type;

        //not a great name in the activity sentence structure SVO (subject verb object) it is the object
        $action_uri = $item->subtype;
        $actionLabel = $action_uri;
        $object_guid = $item->object_guid;
        $preview = '';
        $with_users = array();

        if($action == 'reply') {
            $action = 'replied to a';
        }
        else if($action == 'join') {
            $action = 'joined the site';
        }
        else if($action == 'create') {
            $action = 'created an';
        }
        else if($action == 'post') {
            $action = 'posted on';
        }

        //subtypes
        if(strpos($item->subtype, 'investigationforumtopic_') !== false) {
            $comment = get_entity($item->object_guid);

            $action_uri = 'discussion';
            $actionLabel = 'discussion';

            if($item->annotation_id) {
                $action = "commented on a";
                $annotation = elgg_get_annotation_from_id($item->annotation_id);
                $preview = $annotation->value;
            }
            else {
                $action = "created a";
                $preview = $comment->description;
            }
        }
        else if($item->subtype == 'news') {
            $news = get_entity($item->object_guid);

            $action = 'published a news article';
            $actionLabel = $news->title;
            $preview = $news->excerpt;
            $action_uri = 'newsarticle';
        }
        else if($item->subtype == 'messageboard') {
            // get other users name
            $action_uri = 'discussion';
            $action = "asked";

            $relationship = get_entity_relationships($item->object_guid, true);

            // take first result (there will only be one relationship)
            $messageboard_user = get_user($relationship[0]->guid_one);

            $actionLabel = $messageboard_user->name.' a question';
        }
        else if($item->subtype == 'investigation') {
            $investigation = get_entity($item->object_guid);

            $action_uri = 'investigation';
            $action = 'created an investigation';
            $actionLabel = $investigation->name;

        }
        else if($item->subtype == 'observation') {

            $observation = get_entity($item->object_guid);
            $object_guid = $observation->agg_id;

            $with_users_relationship = elgg_get_entities_from_relationship(array(
                'types'	=>	'user',
                'relationship' => 'with_users',
                'relationship_guid' => $item->object_guid,
                'inverse_relationship' => false,
                'full_view' => false
            ));

            foreach($with_users_relationship as $person){
              $with_users[] = array(
                displayname => $person->name,
                username => $person->username,
                tiny_icon => $person->getIcon('tiny'),
                small_icon => $person->getIcon('small'),
                medium_icon => $person->getIcon('medium'),
                large_icon => $person->getIcon('large')
              );
            }

        }

        if($actionLabel != 'file') {
            $activities[] = array(
                'subject_guid' => $item->subject_guid,
                'objectGuid' => $object_guid,
                'annotation_id' => $item->annotation_id,
                'type' => $item->type,
                'actionUri' => $action_uri,
                'actionLabel' => $actionLabel,
                'subtype' => $item->subtype,
                'action_type' => $action,
                'date' => elgg_get_friendly_time($item->posted),
                'displayname' => $user->name,
                'user_id' => $user->guid,
                'username' => $user->username,
                'userIcon' => $user->getIcon('small'),
                'userIconTiny' => $user->getIcon('tiny'),
                'with_users' => $with_users,
                'preview' => $preview
            );
        }
	}
}

echo json_encode($activities);
