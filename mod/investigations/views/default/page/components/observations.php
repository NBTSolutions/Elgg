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
        "notes" => 'comment_icon.png', 
        "tags" => 'tags_icon.png'
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

?>

<!-- start html -->
<h1 id="obs_measurements_heading">Observation Measurements</h1>
<?php if($video OR $picture) { ?>
    <!-- video and/or pictures -->
    <div id="obs_media">
        <div id="obs_image">
            <h2>Image</h2>
            <?php if($picture) { ?>
                <img src="<?php echo $picture->url; ?>" id="obs_thumbnail_image">
            <?php } else { ?>
                <h3>No Image Available</h3>
            <?php } ?>
        </div>
        <div id="obs_video">
            <h2>Video</h2>
        <?php if($video) { ?>
        <video id="example_video_1" class="video-js vjs-default-skin" controls preload="auto" width="400" height="400" poster="<?php echo $video->thumbnailUrl; ?>" data-setup='{"example_option":true}'>  
             <source src="<?php echo $video->url; ?>" type='video/mp4' />  
             <source src="<?php echo str_replace('.mp4', '', $video->url); ?>.webm" type='video/webm' />  
        </video>
        <?php } else { ?>
            <h3>No Video Available</h3> 
        <?php } ?>
        </div>
    </div>
<?php } ?>

<?php
$category_count = 0;
foreach($obs_categories as $category => $category_image) {
    if($obs_by_category[$category]) {
        if($category_count % 2 == 0) {
?>
    <div class="measurement_category_row">
        <?php } ?>
    <div class="measurement_category<?php echo " measurement_category_".$category?>">
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
            <tr <?php echo ($measurement_count % 2 == 0) ? 'class="even"' : 'class="odd"'; ?>>
                <td width="125">
                    <span class="measurement_cat_label"><?php echo $measurement->phenomenon->description; ?></span>
                </td>
                <td>
                    <? echo $measurement->value; ?>
                    <? 
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
    <?php if($category_count % 2 == 1) { ?>
    <!-- close the measurement_category_row -->
    </div>
    <?php } ?>
<?php
        $category_count++;
    }
}
?>

<h2>Comments</h2>
