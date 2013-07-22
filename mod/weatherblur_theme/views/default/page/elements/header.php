<?php
/**
 * Elgg page header
 * In the default theme, the header lives between the topbar and main content area.
 */

// drop-down login
echo elgg_view('core/account/login_dropdown');

// insert site-wide navigation
//echo elgg_view_menu('site');

?>

<div class="elgg-page-header">		
	<div class="elgg-page-inner">
		<div class="header-content">
		
			<ul id="top-nav">
				<li id="weather-widget"></li>
				<li><a href="#">Home</a></li>
				<li><a href="#">About Us</a></li>
				<!--<li id="avatar"></li>-->					
				<!--<li><a href="#">My Account</a><i class="icon-chevron-down"></i></li>-->	
			</ul><!--End top nav-->
			
			<?php
				// Header Logo
				echo elgg_view('page/elements/header_logo', $vars);
				
				//Display search box: 
				// First, hide ".elgg-search-header" in header.css 
				echo elgg_view('search/search_box');
				
				//Site-wide navigation:
				//echo elgg_view_menu('site');			
			?>
			
			<ul class="elgg-menu-site">
				<li><span id="clipboard" class="nav_icons"></span><a href="<?php echo $CONFIG->url; ?>wbsystem/enterdata">Enter Data</a></li>
				<li><span id="magnifying_glass" class="nav_icons"></span><a href="/elgg/investigate">Investigate</a></li>					
				<li><span id="map_icon" class="nav_icons"></span><a href="<?php echo $CONFIG->url; ?>wbsystem/exploredata">Explore data</a></li>	
				<li><span id="people" class="nav_icons"></span><a href="<?php echo $CONFIG->url; ?>wbsystem/people">People</a></li>
				<li><span id="book" class="nav_icons"></span><a href="<?php echo $CONFIG->url; ?>wbsystem/resources">Resources</a></li>
			</ul>
			
		</div><!--End header-content-->	
			
	</div><!-- End elgg-page-inner-->
</div><!-- End elgg-page-header-->
