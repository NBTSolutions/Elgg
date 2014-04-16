<?php
header('Location: http://www.weatherblur.com',true,301);
exit;
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

			<div id="top-nav">
				<!--WEATHER WIDGET-->
				<div id="wxWrap">
					<span id="wxIntro"></span>
					<span id="wxIcon2"></span>
					<span id="wxTemp"></span>
				</div>
				<a href="http://www.weatherblur.com/">Home</a>
				<a href="http://islandinstitute.org/weatherblur.php" target="_blank">About Us</a>
				<!--<li id="avatar"></li>-->
				<!--<li><a href="#">My Account</a><i class="icon-chevron-down"></i></li>-->


<script type="text/javascript">
// javascript will go here
$(function(){

	var url = "http://www.geoplugin.net/json.gp?jsoncallback=?";

	// get the location by ip
	$.getJSON(url, function(data){
		if(data['geoplugin_status'] == '200'){
			// Do something with the data
			var ip = (data['geoplugin_request']);
			var lat = (data['geoplugin_latitude']);
			var lon = (data['geoplugin_longitude']);
			var place = (data['geoplugin_city']);

			url2 = 'https://api.metwit.com/v2/weather/?location_lat='+lat+'&location_lng='+lon ;


			//get the forecast
			$.getJSON(url2, function(forecast)
			{

					var tempf = Math.floor(((forecast.objects[0].weather.measured.temperature - 273) * 9/5) + 32);
					var img = forecast.objects[0].icon;
					$('#wxIcon').css({
						backgroundPosition: '-' + (61 * place) + 'px 0'
					}).attr({
						title: place
					});
					$('#wxIntro').append(place);
					$('#wxIcon2').append('<img src="'+img+'" width="34" height="34" title="' + place + '" />');
					$('#wxTemp').html(tempf + '&deg;F' );
			});

		}
	});
});
</script>

			</div><!--End top nav-->

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
				<li><a href="<?php echo $CONFIG->url; ?>wbsystem/enterdata"><span id="clipboard" class="nav_icons"></span>Enter Data</a></li>
				<li><a href="<?php echo $CONFIG->url; ?>investigate"><span id="magnifying_glass" class="nav_icons"></span>Investigate</a></li>
				<li><a href="<?php echo $CONFIG->url; ?>wbsystem/exploredata"><span id="map_icon" class="nav_icons"></span>Explore data</a></li>
				<li><a href="<?php echo $CONFIG->url; ?>wbsystem/people"><span id="people" class="nav_icons"></span>People</a></li>
				<li><?php echo elgg_view('output/url', array('text'=>'<span id="book" class="nav_icons"></span>Resources',href=>'file?list_type=gallery',isTrusted=>true)); ?></li>
			</ul>

		</div><!--End header-content-->

	</div><!-- End elgg-page-inner-->
</div><!-- End elgg-page-header-->
