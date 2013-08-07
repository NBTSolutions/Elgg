<?php
/**
 * Layout of the groups profile page
 *
 * @uses $vars['entity']
 */

// we need everyone to be able to see the investigation, just not participate,
// so we don't need the gatekeeper check here:
echo elgg_view('investigations/profile/widgets', $vars);

//echo elgg_view('investigations/profile/summary', $vars);
//if (group_gatekeeper(false)) {
	//echo elgg_view('investigations/profile/widgets', $vars);
//} else {
	//echo elgg_view('investigations/profile/closed_membership');
//}
