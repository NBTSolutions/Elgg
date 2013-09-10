<?php
    // get all of our data from the aggregator
    $ch = curl_init();

    $app_env = getenv("APP_ENV");
    $app_env = $app_env ? $app_env : "unstable";

    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "http://wb-aggregator.".$app_env.".nbt.io/api/observation/" . $vars['observation_agg_id'] . "/measurements"
    ));

    $obs_measurement = curl_exec($ch);

    //convert to utf8
    //$utf8_data = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode(stripslashes($obs_measurement)));
    $observation = json_decode(stripslashes($obs_measurement));

    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "http://wb-aggregator.".$app_env.".nbt.io/api/observation/" . $vars['observation_agg_id']
    ));

    $obs_user_local_response = curl_exec($ch);
    curl_close($ch);

    $obs_user_local = json_decode($obs_user_local_response);
    
    date_default_timezone_set("EST");

    // get the obs_guid
    $results = elgg_get_entities_from_metadata(array(
        "type_subtype_pair"	=>	array('object' => 'observation'),
        "metadata_name_value_pairs" => array('agg_id' => $vars["observation_agg_id"])
    ));
    $obs_guid = $results[0]->guid;

    // get the comments for this obsevation
    $obs = get_entity($obs_guid);
    $obs_user = get_user($obs->owner_guid);

    $comments = $obs->getAnnotations("observation_comments");

    $obs_by_category = array();

    //using this to control the order and assign icons
    $obs_categories = array(
        "sky" => 'sky_icon.png', 
        "precipitation" => 'precipitation_icon.png',
        "ocean" => 'ocean_icon.png', 
        "media" => 'media_icon.png', 
        "tags" => 'tag_icon.png',
        "notes" => 'comment_icon.png'
    );

    foreach($observation as $measurement) {
        if($measurement->value == "video") {
            $video = $measurement->meta; 
        }
        else if($measurement->value == "image") {
            $picture = $measurement->meta;
        }
        else {
            //is in array add to it
            $cat_name = strtolower($measurement->phenomenon->category->name);
            
            if($obs_by_category[$cat_name]) {
                $obs_by_category[$cat_name][] = $measurement;
            }
            //if not in array do not add to it
            else {
                $obs_by_category[$cat_name] = array($measurement);
            }

        }
    }
    
    //elgg site
    $site = elgg_get_site_entity();

    $ts = time();
    $token = generate_action_token($ts);

    //$video = false;
    //$picture = false;

    $likes = get_likes_by_agg_id($vars['observation_agg_id']);
    if(elgg_is_logged_in()) {
        $my_obs_like = get_my_obs_like_by_agg_id($vars['observation_agg_id']);
    }
?>
<!-- start html -->
<h1 id="obs_measurements_heading">Observation Details</h1>
<p>
    <a href="<?php echo $site->url.'profile/'.$obs_user->username; ?>">
        <img src='<?php echo $obs_user->getIcon("tiny") ?>'><?php echo $obs_user->name; ?>
    </a>
    <span style="font-weight: bold">on <?php echo date('F jS, Y g:i:s A', strtotime($obs_user_local->properties->timestamp) + (3600 * (1 - date('I', $comment->time_created)))); ?></span>
    <br>
    <span id="all_likes"><?php echo $likes["all_likes"]; ?> like<?php echo $likes[all_likes] != 1 ? 's' : ''; ?></span>
    <?php if(elgg_is_logged_in()) { ?>
        <span id="like_obs" class="obs_link">
            <?php echo $my_obs_like == 1 ? "Unlike" : "Like"; ?>
        </span>
        <?php if(elgg_is_admin_logged_in() || $obs_user->guid == elgg_get_logged_in_user_guid()) { ?>
        <span id="delete_obs" class="obs_link">
            Delete
        </span>
        <?php } ?>
    <?php } ?>
</p>

<div id="observation_left_col">
    <!-- if there is a picture display else show video -->
    <?php if($picture) { ?>
    <h2>Image</h2>
    <div id="obs_image">
        <img src="<?php echo $picture->url; ?>" id="obs_thumbnail_image">
    </div>
    <?php } elseif($video) { ?>
    <div id="obs_video">
        <h2>Video</h2>
        <video id="obs_video_js" class="video-js vjs-default-skin" controls preload="auto" width="400" height="400" poster="<?php echo $video->thumbnailUrl; ?>" data-setup='{"example_option":true}'>  
             <source src="<?php echo str_replace('.$ext', '', $video->url); ?>.mp4" type='video/mp4' />  
             <source src="<?php echo str_replace('.$ext', '', $video->url); ?>.webm" type='video/webm' />  
        </video>
    </div>
    <?php } ?>
<h2>Data</h2>
<?php
// go over each category type
foreach($obs_categories as $category => $category_image) {
    // if we have data for this category show it
    if($obs_by_category[$category]) {
?>
    <div class="measurement_cat">

        <!-- data table for observation category -->
        <table class="obs_measurements">
            <tr>
                <td class="measurement_category_heading" colspan='2'>
                    <img src='<?php echo $site->url; ?>mod/weatherblur_theme/graphics/<?php echo $category_image; ?>'>
                    <h2><?php echo ucwords($category); ?></h2>
                </td>
            <tr>

            <?php
            $measurement_count = 0;
            foreach($obs_by_category[$category] AS $measurement) {
            ?>
            <!-- even odd logic -->
            <tr <?php echo ($measurement_count % 2 == 0) ? 'class="even"' : 'class="odd"'; ?>>
                <td width="125">
                    <span class="measurement_cat_label"><?php echo $measurement->phenomenon->description; ?></span>
                </td>
                <td>
                    <?php echo $measurement->value; ?>
                    <?php 
                    if($measurement->phenomenon->unit) {
                        echo $measurement->phenomenon->unit->abbrev;
                    }
                    ?>
                </td>
            </tr>
            <?php
                $measurement_count++;
            }
            ?>
        </table>

    </div>
<?php
    }
}
?>
</div>

<div id="observation_right_col">
<!-- video -->
<?php if($picture AND $video) { ?>
<h2>Video</h2>
<div id="obs_video">
    <video id="example_video_1" class="video-js vjs-default-skin" controls preload="auto" width="400" height="400" poster="<?php echo $video->thumbnailUrl; ?>" data-setup='{"example_option":true}'>  
         <source src="<?php echo str_replace('.$ext', '', $video->url); ?>.mp4" type='video/mp4' />  
         <source src="<?php echo str_replace('.$ext', '', $video->url); ?>.webm" type='video/webm' />  
    </video>
</div>
<?php } ?>
<h2>Comments</h2>
<ul class="elgg-list">
    <?php foreach($comments AS $comment) { ?>
    <?php $ignore = elgg_set_ignore_access(true); ?>
    <?php $user = get_user($comment->owner_guid); ?>
    <li class="elgg-item">
        <div class="elgg-image-block clearfix">
            <div class="elgg-image">
                <div class="elgg-avatar elgg-avatar-tiny">
                    <a href="<?php echo $site->url; ?>profile/<?php echo $user->username; ?>" class="">
                        <img src="<?php echo $site->url; ?>_graphics/spacer.gif" alt="admin" title="admin" class="" style="background: url(<?php echo $user->getIconUrl('tiny'); ?>) no-repeat;">
                    </a>
                </div>
            </div>
            <div class="elgg-body">
                <div class="elgg-subtext">
                    <a href="<?php echo $site->url; ?>profile/<?php echo $user->username; ?>"><?php echo $user->name; ?></a>
                    <acronym>on <?php echo date('F jS, Y g:i:s A', $comment->time_created + (3600 * (1 - date('I', $comment->time_created)))); ?></acronym>
                </div>		
                <div class="clearfix"></div>
                <div class="elgg-content">
                    <?php echo $comment->value; ?>
                    <?php if(elgg_is_admin_logged_in()) { ?>
                    <a href="<?php echo $site->url; ?>action/observation/delete_comment?__elgg_token=<?php echo $token; ?>&__elgg_ts=<?php echo $ts; ?>&annotation_id=<?php echo $comment->id; ?>">Delete</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </li>
    <?php elgg_set_ignore_access($ignore); ?> 
    <?php } ?>
</ul>
<?php if(elgg_is_logged_in()) { ?>

<form method="get" action='<?php echo $site->url; ?>action/observation/create_comment' class="elgg-form">
    <input type="hidden" name="__elgg_token" value="<?php echo $token; ?>" />
    <input type="hidden" name="__elgg_ts" value="<?php echo $ts; ?>" />
    <input type="hidden" name="obs_guid" value="<?php echo $obs_guid; ?>">
    <textarea name="comment"></textarea>
    <input type="submit" value="Add Comment" class="elgg-button elgg-button-submit" />
</form>
</div>
<?php } ?>
<script>
    var all_likes = <?php echo $likes['all_likes']; ?>,
        my_likes = <?php echo $my_obs_like; ?>;

    // take into account whether or not I have liked this
    all_likes = all_likes - my_likes;

    $(function() {
        $('#like_obs').click(function() {
            $.get('<?php echo elgg_get_site_url(); ?>services/api/rest/json/', 
                {
                    method : 'wb.toggle_like_obs_by_agg_id',
                    agg_id : "<?php echo $vars['observation_agg_id'] ?>"
                })
                .done(function(data) {
                    var total_likes = all_likes + data.result;
                    var like_label = total_likes != 1 ? " likes" : " like";

                    $('#all_likes').html(total_likes + like_label);
                    $('#like_obs').html(data.result ? 'Unlike' : 'like');
                });
        });
        $('#delete_obs').click(function() {
            $.get('<?php echo elgg_get_site_url(); ?>services/api/rest/json/',
                {
                    method : 'wb.delete_obs_by_agg_id',
                    agg_id : '<?php echo $vars['observation_agg_id'] ?>'
                })
                .done(function(data) {
                    history.back(); 
                });
        });
    });
</script>
