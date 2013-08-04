<?php

 function countem ($obj)
 {
	if ($obj)
	{
		return sizeof($obj);
	}
	else
	{
		return 0;
	}
 }
 
 
 //count investigations
$user_guid = get_loggedin_userid(); //user's id
$relations = get_users_membership($user_guid); //get all the groups the user is belonging

//count obs
$obs = elgg_get_entities( array(
  'owner_guid' => $user_guid,
  'type' => 'object',
  'subtype' => 'observations',
  'limit' => false
));

//count maps
$maps = elgg_get_entities( array(
  'owner_guid' => $user_guid,
  'type' => 'object',
  'subtype' => 'investigationforumtopic_map',
  'limit' => false
)); 

//count graphs
$graphs = elgg_get_entities( array(
  'owner_guid' => $user_guid,
  'type' => 'object',
  'subtype' => 'investigationforumtopic_graph',
  'limit' => false
));

//count imgs
$imgs = elgg_get_entities( array(
  'owner_guid' => $user_guid,
  'type' => 'object',
  'subtype' => 'investigationforumtopic_image',
  'limit' => false
));

//count video
$video = elgg_get_entities( array(
  'owner_guid' => $user_guid,
  'type' => 'object',
  'subtype' => 'investigationforumtopic_video',
  'limit' => false
));

//count discussions
$disc = elgg_get_entities( array(
  'owner_guid' => $user_guid,
  'type' => 'object',
  'subtype' => 'investigationforumtopic_text',
  'limit' => false
));



?>
<div id="wb-user-stats">
	<ul>
		<li><span id="user-investigations" class="user-stat-icons"></span> Investigations: <?php echo countem($relations); ?></li>
		<li><span id="user-observations" class="user-stat-icons"></span> Observations: <?php echo countem($obs); ?></li>
		<li><span id="user-images-posted" class="user-stat-icons"></span> Images Posted: <?php echo countem($imgs); ?></li>
		<li><span id="user-maps-posted" class="user-stat-icons"></span> Maps Posted: <?php echo countem($maps); ?></li>
		<li><span id="user-graphs-posted" class="user-stat-icons"></span> Graphs Posted: <?php echo countem($graphs); ?></li>
		<li><span id="user-videos" class="user-stat-icons"></span> Videos Posted: <?php echo countem($video); ?></li>
		<li><span id="user-discussions-posted" class="user-stat-icons"></span> Questions & Ideas Posted: <?php echo countem($disc); ?></li>
	</ul>
</div>


