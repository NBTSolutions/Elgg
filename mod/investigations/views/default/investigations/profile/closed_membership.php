<?php
/**
 * Display message about closed membership
 * 
 * @package ElggGroups
 */

?>
<p class="mtm">
<?php 
echo elgg_echo('investigations:closedgroup');
if (elgg_is_logged_in()) {
	echo ' ' . elgg_echo('investigations:closedgroup:request');
}
?>
</p>
