<?php
   
    $obs_guid = (int) get_input('obs_guid');
    $comment = get_input('comment');
    $user = elgg_get_logged_in_user_guid();
    $site = elgg_get_site_entity();

    // need to ignore access to set owner_id
    $ignore = elgg_set_ignore_access(true);
    $observation = get_entity($obs_guid);
    $id = $observation->annotate('observation_comments', $comment, 2, $user->guid, 'text');
    $observation->save();
    elgg_set_ignore_access($ignore);

    // forward("http://www.google.com");
    //forward($site->url . "observation/" . $obs_guid);

?>
