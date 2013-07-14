<?php
/**
 * Add users to a group
 *
 * @package ElggGroups
 */
$logged_in_user = elgg_get_logged_in_user_entity();

$user_guid = get_input('user_guid');
if (!is_array($user_guid)) {
	$user_guid = array($user_guid);
}
$group_guid = get_input('group_guid');
$group = get_entity($group_guid);

if (sizeof($user_guid)) {
	foreach ($user_guid as $u_id) {
		$user = get_user($u_id);

		if ($user && $group && $group->canEdit()) {
			if (!$group->isMember($user)) {
				if (investigations_join_investigation($group, $user)) {

					// send welcome email to user
					notify_user($user->getGUID(), $group->owner_guid,
							elgg_echo('investigations:welcome:subject', array($group->name)),
							elgg_echo('investigations:welcome:body', array(
								$user->name,
								$group->name,
								$group->getURL())
							));

					system_message(elgg_echo('investigations:addedtogroup'));
				} else {
					// huh
				}
			}
		}
	}
}

forward(REFERER);
