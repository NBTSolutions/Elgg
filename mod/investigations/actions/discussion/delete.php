<?php
/**
 * Delete topic action
 *
 */

$topic_guid = (int) get_input('guid');

$topic = get_entity($topic_guid);
if (!$topic || !$topic->getSubtype() == "investigationforumtopic") {
	register_error(elgg_echo('investigation_discussion:error:notdeleted'));
	forward(REFERER);
}

if (!$topic->canEdit()) {
	register_error(elgg_echo('investigation_discussion:error:permissions'));
	forward(REFERER);
}

$container = $topic->getContainerEntity();

$result = $topic->delete();
if ($result) {
	system_message(elgg_echo('investigation_discussion:topic:deleted'));
} else {
	register_error(elgg_echo('investigation_discussion:error:notdeleted'));
}

forward("discussion/owner/$container->guid");
