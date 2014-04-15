<?php
	// Load Elgg engine
	include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";

    $app_env = getenv("APP_ENV");
    $app_env = $app_env ? $app_env : "unstable";

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
				<iframe src="http://nbt-static.s3-website-us-east-1.amazonaws.com/weatherblur/map/'.$app_env.'/" id="home_map"></iframe>
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

			//$canvas_area = elgg_view_layout('default', array('content' => $content));
			//echo elgg_view_page($title, $canvas_area, 'default', array('bodyclass' => 'weatherblur-front'));

            

		?>

<!DOCTYPE html>
<html lang="en" ng-app="weatherblur">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WeatherBlur</title>
    <link rel="stylesheet" href="styles/app.css">
    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
</head>
<body>
    <div class="container-fuild">
        <div id="header" class="row" ng-controller="header">
            <accountheader></accountheader>
            <div class="pageWidth">
                <div id="header-middle" class="col-md-12">
                    <div id="header-logo" class="col-md-6">
                        <a href="{{appPath}}{{appFilename}}/"><img src='media/images/wb_logo.png'></a>
                    </div>

                    <div id="search" class="col-md-6 pull-right">
                        <div class="col-md-8">
                            <input type="text" ng-model="search" class="form-control" placeholder="Search">
                        </div>
                        <div class="col-md-4">
                            <button class="form-control">Search</button>
                        </div>
                    </div>
                </div>

                <div id="nav" class="col-md-12">
                    <a href="{{appPath}}{{appFilename}}/enterdata"><span class="observationIcon"></span>Enter Data</a>
                    <a href="{{appPath}}{{appFilename}}/investigations"><span class="investigationIcon"></span>Investigations</a>
                    <a href="{{appPath}}{{appFilename}}/observations"><span class="exploreDataIcon"></span>Explore Data</a>
                    <a href="{{appPath}}{{appFilename}}/members"><span class="membersIcon"></span>Members</a>
                    <a href="{{appPath}}{{appFilename}}/resources"><span class="resourcesIcon"></span>Resources</a>
                </div>
            </div>
        </div>
        <div id="content" class="row">
            <div ng-view></div>
        </div>
        <div id="footer" class="row" ng-controller="footer">
            <div class="pageWidth">
                <div class="col-md-12">
                    <ul class="links col-centered">
                        <li><a class="footer-link" href="/#/enterdata">Enter Data</a></li>
                        <li><a class="footer-link" href="/#/investigations">Investigate</a></li>
                        <li><a class="footer-link" href="/#/exploredata">Explore Data</a></li>
                        <li><a class="footer-link" href="/#/members">Members</a></li>
                        <li><a class="footer-link" href="/#/resources">Resources</a></li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <ul class="links col-centered">
                        <li>WeatherBlur</li>
                        <li>Island Institute</li>
                        <li>386 Main Street Rockland, ME 04841</li>
                        <li>207.594.9209</li>
                        <li><a href="#">Email: weatherblur@islandinstitute.org</a></li>
                    </ul>
                </div>

                <div class="col-md-12">
                    <div class="col-centered">
                        <div class="mini-wb-logo"></div>
                        <div class="mini-island-institute-logo"></div>
                        <div class="mini-nsf-logo"></div>
                    </div>
                </div>

                <span class="copyright">&copy; Copyright 2014 WeatherBlur</span>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="scripts/app.js"></script>
</body>
</html>
