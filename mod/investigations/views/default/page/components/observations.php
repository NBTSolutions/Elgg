<?php
    $ch = curl_init();

    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "http://wb-aggregator.unstable.nbt.io/api/json/observation/?id=".$vars['observation_guid']
    ));

    $ok = curl_exec($ch);
    curl_close($ch);

    $observation = json_decode($ok);
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

    foreach($observation->measurements as $measurement) {
        if($measurement->value == "video") {
            $video = $measurement->meta; 
        }
        else if($measurement->value === "image") {
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

    //$video = false;
    //$picture = false;

?>
<!-- start html -->
<h1 id="obs_measurements_heading">Observation Measurements</h1>
<p>By <a href="<?php echo $site->url.'profile/'.$observation->observer->label; ?>"><?php echo $observation->observer->label; ?></a> on <?php echo date('F nS, Y', strtotime($observation->timestamp)); ?></p>

<div id="observation_left_col">
    <!-- if there is a picture display else show video-->
    <?php if($picture) { ?>
    <h2>Image</h2>
    <div id="obs_image">
        <img src="<?php echo $picture->url; ?>" id="obs_thumbnail_image">
    </div>
    <?php } elseif($video) { ?>
    <div id="obs_video">
        <h2>Video</h2>
        <video id="obs_video_js" class="video-js vjs-default-skin" controls preload="auto" width="400" height="400" poster="<?php echo $video->thumbnailUrl; ?>" data-setup='{"example_option":true}'>  
             <source src="<?php echo $video->url; ?>" type='video/mp4' />  
             <source src="<?php echo str_replace('.mp4', '', $video->url); ?>.webm" type='video/webm' />  
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
         <source src="<?php echo $video->url; ?>" type='video/mp4' />  
         <source src="<?php echo str_replace('.mp4', '', $video->url); ?>.webm" type='video/webm' />  
    </video>
</div>
<?php } ?>
<h2>Comments</h2>
<ul class="elgg-list">
    <li class="elgg-item">
        <div class="elgg-image-block clearfix">
            <div class="elgg-image">
                <div class="elgg-avatar elgg-avatar-tiny">
                    <span class="elgg-icon elgg-icon-hover-menu "></span>
                    <ul class="elgg-menu elgg-menu-hover">
                        <li><a href="http://demo.nbtsolutions.com/elgg/profile/admin"><span class="elgg-heading-basic">admin</span>@admin</a></li>
                    </ul>
                    <a href="http://demo.nbtsolutions.com/elgg/profile/admin" class="">
                        <img src="http://demo.nbtsolutions.com/elgg/_graphics/spacer.gif" alt="admin" title="admin" class="" style="background: url(http://demo.nbtsolutions.com/elgg/mod/profile/icondirect.php?lastcache=1375362698&amp;joindate=1375362461&amp;guid=38&amp;size=tiny) no-repeat;">
                    </a>
                </div>
            </div>
            <div class="elgg-body">
                <div class="elgg-subtext">
                    <a href="http://demo.nbtsolutions.com/elgg/profile/admin">Joe</a> <acronym title="2 August 2013 @ 8:32am">6 days ago</acronym>
                </div>		
                <div class="clearfix"></div>
                <div class="elgg-content">
                    This is such a great observation! I want to tell all my friends. I wonder if they will like this as much as I do.
                </div>
            </div>
        </div>
    </li>
     <li class="elgg-item">
        <div class="elgg-image-block clearfix">
            <div class="elgg-image">
                <div class="elgg-avatar elgg-avatar-tiny">
                    <span class="elgg-icon elgg-icon-hover-menu "></span>
                    <ul class="elgg-menu elgg-menu-hover">
                        <li><a href="http://demo.nbtsolutions.com/elgg/profile/admin"><span class="elgg-heading-basic">admin</span>@admin</a></li>
                    </ul>
                    <a href="http://demo.nbtsolutions.com/elgg/profile/admin" class="">
                        <img src="http://demo.nbtsolutions.com/elgg/_graphics/spacer.gif" alt="admin" title="admin" class="" style="background: url(http://demo.nbtsolutions.com/elgg/mod/profile/icondirect.php?lastcache=1375362698&amp;joindate=1375362461&amp;guid=38&amp;size=tiny) no-repeat;">
                    </a>
                </div>
            </div>
            <div class="elgg-body">
                <div class="elgg-subtext">
                    <a href="http://demo.nbtsolutions.com/elgg/profile/admin">Bob</a> <acronym title="2 August 2013 @ 8:32am">2 days ago</acronym>
                </div>		
                <div class="clearfix"></div>
                <div class="elgg-content">
                    This is such a great observation! I want to tell all my friends.
                </div>
            </div>
        </div>
    </li>   
</ul>
<textarea></textarea>
<a href="http://localhost:9999/elgg/investigate/add/49" class="elgg-button elgg-button-action">
    Submit
</a>
</div>
