<?php
/**
 * Discussion function library
 */

/**
 * List all discussion topics
 */
function investigation_discussion_handle_all_page() {

	elgg_pop_breadcrumb();
	elgg_push_breadcrumb(elgg_echo('discussion'));

	$content = elgg_list_entities(array(
		'type' => 'object',
		'subtype' => 'investigationforumtopic',
		'order_by' => 'e.last_action desc',
		'limit' => 20,
		'full_view' => false,
	));

	$params = array(
		'content' => $content,
		'title' => elgg_echo('investigation_discussion:latest'),
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * List discussion topics in a group
 *
 * @param int $guid Group entity GUID
 */
function investigation_discussion_handle_list_page($guid) {

	elgg_set_page_owner_guid($guid);

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

	$group = get_entity($guid);
	if (!$group) {
		register_error(elgg_echo('investigation:notfound'));
		forward();
	}
	elgg_push_breadcrumb($group->name);

	elgg_register_title_button();

	group_gatekeeper();

	$title = elgg_echo('item:object:investigationforumtopic');
	
	$options = array(
        'type' => 'object',
		'subtypes' => $subtype,
		'limit' => 20,
		'order_by' => 'e.last_action desc',
		'container_guid' => $guid,
		'full_view' => false,
	);
	$content = elgg_list_entities($options);
	if (!$content) {
		$content = elgg_echo('investigation_discussion:none');
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Edit or add a discussion topic
 *
 * @param string $type 'add' or 'edit'
 * @param int    $guid GUID of group or topic
 */
function investigation_discussion_handle_edit_page($type, $guid) {
	gatekeeper();

	if ($type == 'add') {
		$investigation = get_entity($guid);
		if (!$investigation) {
			register_error(elgg_echo('investigation:notfound'));
			forward();
		}
        
        $subtype = get_input("discussion_subtype");
        if($subtype == "image" OR $subtype == "graph" OR $subtype == "map") {
            forward('file/add/'.$investigation->getGUID()."?discussion_subtype=".$subtype);
        }

		// make sure user has permissions to add a topic to container
		if (!$investigation->canWriteToContainer(0, 'object', 'investigationforumtopic')) {
			register_error(elgg_echo('investigations:permissions:error'));
			forward($investigation->getURL());
		}

		$title = elgg_echo('investigations:addtopic');

		elgg_push_breadcrumb($investigation->name, "investigation_discussion/owner/$investigation->guid");
		elgg_push_breadcrumb($title);

		$body_vars = investigation_discussion_prepare_form_vars();
		$content = elgg_view_form('investigation_discussion/save', array(), $body_vars);
	} else {
		$topic = get_entity($guid);
		if (!$topic || !$topic->canEdit()) {
			register_error(elgg_echo('investigation_discussion:topic:notfound'));
			forward();
		}
		$investigation = $topic->getContainerEntity();
		if (!$investigation) {
			register_error(elgg_echo('investigation:notfound'));
			forward();
		}

		$title = elgg_echo('investigations:edittopic');

		elgg_push_breadcrumb($investigation->name, "investigation_discussion/owner/$investigation->guid");
		elgg_push_breadcrumb($topic->title, $topic->getURL());
		elgg_push_breadcrumb($title);

		$body_vars = investigation_discussion_prepare_form_vars($topic);
		$content = elgg_view_form('investigation_discussion/save', array(), $body_vars);
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * View a discussion topic
 *
 * @param int $guid GUID of topic
 */
function investigation_discussion_handle_view_page($guid) {
	// We now have RSS on topics
	global $autofeed;
	$autofeed = true;

	$topic = get_entity($guid);
	if (!$topic) {
		register_error(elgg_echo('noaccess'));
		$_SESSION['last_forward_from'] = current_page_url();
		//elgg_get_session()->set('last_forward_from', current_page_url());
		forward('');
	}

	$group = $topic->getContainerEntity();
	if (!$group) {
		register_error(elgg_echo('investigation:notfound'));
		forward();
	}

	elgg_set_page_owner_guid($group->getGUID());

	group_gatekeeper();

	elgg_push_breadcrumb($group->name, "investigation_discussion/owner/$group->guid");
	elgg_push_breadcrumb($topic->title);

	$content = elgg_view_entity($topic, array('full_view' => true));
	if ($topic->status == 'closed') {
		$content .= elgg_view('discussion/replies', array(
			'entity' => $topic,
			'show_add_form' => false,
		));
		$content .= elgg_view('discussion/closed');
	} elseif ($group->canWriteToContainer(0, 'object', 'investigationforumtopic') || elgg_is_admin_logged_in()) {
		$content .= elgg_view('discussion/replies', array(
			'entity' => $topic,
			'show_add_form' => true,
		));
	} else {
		$content .= elgg_view('discussion/replies', array(
			'entity' => $topic,
			'show_add_form' => false,
		));
	}

	$params = array(
		'content' => $content,
		'title' => $topic->title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($topic->title, $body);
}

/**
 * Prepare discussion topic form variables
 *
 * @param ElggObject $topic Topic object if editing
 * @return array
 */
function investigation_discussion_prepare_form_vars($topic = NULL) {
	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'status' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $topic,
	);

	if ($topic) {
		foreach (array_keys($values) as $field) {
			if (isset($topic->$field)) {
				$values[$field] = $topic->$field;
			}
		}
	}

	if (elgg_is_sticky_form('topic')) {
		$sticky_values = elgg_get_sticky_values('topic');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('topic');

	return $values;
}
