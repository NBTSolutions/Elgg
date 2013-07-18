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
	elgg_load_js('underscore');
	elgg_load_js('jq-widget');
	elgg_load_js('jq-tabs');
	elgg_load_js('graph');

	//echo elgg_view_page($title, $body);

	echo elgg_view_page($title, $body, $canvas_area);
	
    $content = '
        <script>
          $(function() {
                $( "#tabs" ).tabs();
          });
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
                <div>Observations grid will go here.</div>
            </div>
            <div id="tab_graphing">
                <div id="graph_container"></div>
            </div>
            <div id="tab_mapping">
                <div><iframe src="http://weatherblur-staging.herokuapp.com/map/"></iframe></div>
            </div>

        </div>
        </div>';    
	$canvas_area = elgg_view_layout('default', array('content' => $content));
	echo elgg_view_page($title, $canvas_area);
	
?>
