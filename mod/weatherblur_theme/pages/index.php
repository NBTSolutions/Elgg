<?php
	// Load Elgg engine
	include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";

    $news = news_homepage();

	$body = elgg_view('index.php');
	$content = '<div class="wb-body">

	<!-----------------------------------------------
				DATA COLLECTION
	------------------------------------------------->
	<div class="green-bkgd">
		<div class="map_dct_container">
			<div id="data_collection">
				<h2>Enter Data</h2>
                    <a href="wbsystem/enterdata"><img src="mod/weatherblur_theme/graphics/dct.png" width="328" height="519"></a>
            </div><!--End data collection-->

			<div id="homepage_map">
				<h2>Explore Data</h2>
				<iframe src="http://nbt-static.s3-website-us-east-1.amazonaws.com/weatherblur/map/unstable/" id="home_map"></iframe>
			</div><!--End homepage map-->
		</div><!--End map_dct_container-->
	</div><!--End green-bkgd-->

	<!-----------------------------------------------
				FEATURED 3 COLUMNS
	------------------------------------------------->
	<div class="featured-three-columns">
		<div id="three-columns-content">

			<div id="latest-news">
				<h2>Latest News</h2>

				<div id="latest-news-photo"></div>
				<h3><a class="featured-title" href="news/view/'.$news->guid.'/'.str_replace(' ', '-', $news->title).'">'.$news->title.'</a></h3>
				<p class="description">'.$news->excerpt.'</p>';
                if($news->guid) {
                    $content .= '<a class="orange-links" href="news">View more news</a>';
                }
                else {
                    $content .= $news;
                }
	$content .= '</div><!-- End lastest news-->';

	$featured_inv = elgg_get_featured('group', 'investigation', 1);
if ($featured_inv[0]) {
	$content .= '
			<div id="featured-investigation">
			<h2>Featured Investigation</h2>
				<div id="notebook-background">
					<img src="' . $featured_inv[0]->getIconURL('large') . '" alt="' . $featured_inv[0]->get('name') . '">
				</div><!--End notebook background-->
				<h3><a class="featured-title" href="investigate/profile/' . $featured_inv[0]->guid . '">' . $featured_inv[0]->name . '</a></h3>
				' . $featured_inv[0]->description . '
				<a class="orange-links" href="investigate">View more investigations</a>
				</div><!-- End featured investigation-->';
}

$content .= '<div id="activity">
				<h2>Activity</h2>'.elgg_list_river(array('limit' => 3), "page/components/homepage-activity-list").'

						<a class="orange-links" href="activity">View more activity</a>
						</div><!-- End activity-->';

$content .= elgg_view('homepage/featured_member');

$content .= '
		</div><!-- End three columns content-->
	</div><!-- End featured three columns-->
</div><!--End wb body-->';

			$canvas_area = elgg_view_layout('default', array('content' => $content));
			echo elgg_view_page($title, $canvas_area, 'default', array('bodyclass' => 'weatherblur-front'));
		?>
