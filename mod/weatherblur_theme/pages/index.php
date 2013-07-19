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
				<iframe src="http://weatherblur-staging.herokuapp.com/collect/" id="home_dct"></iframe>
			</div><!--End data collection-->	
					
			<div id="homepage_map">
				<h2>Explore Data</h2>
				<iframe src="http://weatherblur-staging.herokuapp.com/map/" id="home_map"></iframe>
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
				<p class="description">'.$news->description.'</p>
				';
                if($news->guid) {
                    $content .= '<a class="orange-links" href="news">View more news</a>';
                }
                else {
                    $content .= $news;
                }
            $content .= '
			</div><!-- End lastest news-->
			
			<div id="featured-investigation">
				<h2>Featured Investigation</h2>
				<div id="notebook-background">
					<div id="featured-investigation-photo"></div>
				</div><!--End notebook background-->
				<h3><a class="featured-title" href="#">Title goes here</a></h3>
				<p class="description">Description goes here. Description goes here. Description goes here. Description goes here. Description goes here. Description goes here. Description goes here. Description goes here.</p>
				<a class="orange-links" href="#">View more investigations</a>
			</div><!-- End featured investigation-->
			
			<div id="activity">
				<h2>Activity</h2>'.elgg_list_river(array('limit' => 3), "page/components/homepage-activity-list").'

						<a class="orange-links" href="activity">View more activity</a>
			</div><!-- End activity-->
			
			<!-----------------------------------------------
							FEATURED MEMBER
			------------------------------------------------->
			<div id="featured-member-bkgd">
			
				<h2>Featured Member</h2>						
				<a class="featured-pic" href="#"></a>
				<a class="featured-name" href="#">Owen Stiles</a>
				<a class="featured-teaser" href="#">For an instant in the flashing eyes of the mate, and his fiery cheeks, you would have almost thought that he had really received the blaze of the levelled tube. But, mastering his emotion, he half calmly rose, and as he quitted the cabin, paused for an instant and said: "Thou hast outraged, not insulted me, sir; but for that I ask thee not to beware of Starbuck; thou wouldst but laugh; but let Ahab beware of Ahab; beware of thyself, old man."</a>
						
			</div><!--End featured member bkgd-->

		</div><!-- End three columns content-->
	</div><!-- End featured three columns-->
</div><!--End wb body-->';
		
			$canvas_area = elgg_view_layout('default', array('content' => $content));
			echo elgg_view_page($title, $canvas_area);
		?>
