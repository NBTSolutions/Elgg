<?php
/**
 * Layout of the groups profile page
 *
 * @uses $vars['entity']
 */

echo elgg_view('investigations/profile/summary', $vars);
if (group_gatekeeper(false)) {
	echo elgg_view('investigations/profile/widgets', $vars);
} else {
	echo elgg_view('investigations/profile/closed_membership');
}
