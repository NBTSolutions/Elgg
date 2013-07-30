<?php
elgg_load_library('galliProUser');
$featured_users = gpu_get_featured_members(array('limit' => 1), false);
if (count($featured_users) == 0) {
	return;
}
$fuser = $featured_users[0];
$title = 'Member';
if ($fuser->custom_profile_type) {
	$title = get_entity($fuser->custom_profile_type)->getTitle();
}

?>
<!-----------------------------------------------
				FEATURED MEMBER
------------------------------------------------->
<div id="featured-member">
	<h2>Featured <?php print $title; ?></h2>
	<a class="icon" href="<?php print $fuser->getURL(); ?>"><img src="<?php print $fuser->getIconURL('large'); ?>" /></a>
	<a class="name" href="<?php print $fuser->getURL(); ?>"><?php print $fuser->get('name'); ?></a>
	<?php print $fuser->get('description'); ?>

</div><!--End featured member bkgd-->

