<?php
	// Load Elgg engine
	include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";

	$title = elgg_echo('Explore Data');

	$area2 = elgg_view_title($title);

	//TODO Add Explore Data stuff

	$body = elgg_view_layout("one_column", array('content' => $area2));

	elgg_load_css('jq-smooth');
	elgg_load_css('jq-tabs');
	elgg_load_css('enyo-css');
	elgg_load_css('graph-css');
	elgg_load_css('font-awesome');

	elgg_load_js('enyo-js');
	elgg_load_js('d3');
	elgg_load_js('moment');
	elgg_load_js('jq-widget');
	elgg_load_js('jq-tabs');
	elgg_load_js('require');
	elgg_load_js('graph');
	elgg_load_js('exploredata');

	//echo elgg_view_page($title, $body);

	echo elgg_view_page($title, $body, $canvas_area);

    $content = '
        <script>
          $(function() {
                $( "#tabs" ).tabs();
					});

					var require = {
						config: {
							"wb/main": {
								apiPath: "http://wb-aggregator.unstable.nbt.io/api"
							}
						},
						paths: {
							"wb/api/main": [
								"//s3.amazonaws.com/nbt-static/weatherblur/lib/wb.api"
							]
						}
					};

					uid = ' . elgg_get_logged_in_user_guid() . '

        </script>

        <div class="wb-body">
        <h2 style="text-align:center;padding: 20px">Explore Data</h2>
        <div id="tabs">
            <ul>
                <li><a href="#tab_explore">Explore Observations</a></li>
                <li><a href="#tab_graphing">Graphing</a></li>
                <li><a href="#tab_mapping">Mapping</a></li>
            </ul>
            <div id="tab_explore">
                <div><img src="http://localhost:9999/elgg/mod/weatherblur_theme/graphics/observation_explorer.png"></div>
            </div>
            <div id="tab_graphing">
							<div id="graph_container"></div>
							<div id="graph_people">
								<h3>Whose Observations Do You Want To See?</h3>
								<div id="use-mine" class="elgg-button elgg-button-action">Use My Own</div>
								' . elgg_view('explore/graph/personpicker') . '</div>
            </div>
            <div id="tab_mapping">
				<div><iframe src="http://nbt-static.s3-website-us-east-1.amazonaws.com/weatherblur/map/unstable/" id="home_map"></iframe></div>
            </div>

        </div>
        </div>';
	$canvas_area = elgg_view_layout('default', array('content' => $content));
	echo elgg_view_page($title, $canvas_area);

?>
