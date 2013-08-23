<?php
	// Load Elgg engine
	include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";

    elgg_load_library('elgg:investigations');

	$title = elgg_echo('Explore Data');

	$area2 = elgg_view_title($title);

	//TODO Add Explore Data stuff

	$body = elgg_view_layout("one_column", array('content' => $area2));

	elgg_load_css('jq-smooth');
	elgg_load_css('enyo-css');
	elgg_load_css('graph');
	elgg_load_css('gallery');
	elgg_load_css('tables-css');
	elgg_load_css('font-awesome');
	elgg_load_css('tabletools-css');

	elgg_load_js('enyo-js');
	elgg_load_js('d3');
	elgg_load_js('moment');
	elgg_load_js('graph');
	elgg_load_js('gallery');
	elgg_load_js('exploredata');

	elgg_load_js('datatables');
	elgg_load_js('table-tools');
	elgg_load_js('table-tools-zc');

	//echo elgg_view_page($title, $body);

	echo elgg_view_page($title, $body, $canvas_area);
	$site_url = elgg_get_site_url();

	$content = '
			<script>
$(function() {
	$( "#tabs" ).tabs({

		show: function(ui, event)	{
			ttInstances = TableTools.fnGetMasters();
			for (i in ttInstances) {
				if (ttInstances[i].fnResizeRequired()) ttInstances[i].fnResizeButtons();
			}
            $("[aria-controls=\'tab_mapping\']").click(function() {
                if($("#tab_mapping").children().length < 1) {
                    $("#tab_mapping").append("<div><iframe src=\"http://nbt-static.s3-website-us-east-1.amazonaws.com/weatherblur/map/unstable/index.html\" id=\"explore_page_map\"></iframe></div>");
                }
            });
		}
	});
});

var require = {
	config: {
		"wb/main": {
			apiPath: "http://wb-aggregator.unstable.nbt.io/api"
		}
	},
	paths: {
		"wb/api/main": [
			"//s3.amazonaws.com/nbt-static/weatherblur/lib/wb.api-sans-jquery"
			//"//s3.amazonaws.com/nbt-static/weatherblur/lib/wb.api"
		]
	}
};

uid = ' . elgg_get_logged_in_user_guid() . '
	</script>
<script src="//d3pch6bcnsao4c.cloudfront.net/lib/require.js"></script>
<script>
define("jquery", [], function() { return jQuery; });
require(["wb/api/main"], function(wb) {
	require(["require"], function() {
		$.ajaxSetup({ cache: true });
		graph = new wb.Graph().renderInto(document.getElementById("graph_container"));
	});
});

require(["wb/api/main"], function(wb) {
	require(["require"], function() {
		gallery = new wb.Gallery().renderInto(document.getElementById("gallery_container"));
	});
});
		</script>
		<script type="text/javascript" charset="utf-8">
$(document).ready(function() {

				var oTable = $("#wb-data").dataTable( {
				    "sDom": \'T<"clear">lfrtip\',
					"bProcessing": true,
					"bLengthChange": false,
					"iDisplayLength" : 15,
					"oTableTools": {
						"aButtons": [ "copy", "print","csv","pdf"],
						"sSwfPath": "'.$site_url.'mod/weatherblur_theme/media/swf/copy_csv_xls_pdf.swf",
					},
					"sAjaxSource": "'.$site_url.'mod/weatherblur_theme/pages/datatable.php"
				} );

			} );
		</script>

        <div class="wb-body">
        <h2 style="text-align:center;padding: 20px">Explore Data</h2>
        <div id="tabs">
            <ul>
                <li><a href="#tab_explore">Observations</a></li>
                <li><a href="#tab_graphing">Graphs</a></li>
                <li><a href="#tab_mapping">Maps</a></li>
				 <li><a href="#tab_data">Data</a></li>
            </ul>
            <div id="tab_explore">
                <div id="gallery_container"></div>
            </div>
            <div id="tab_graphing">
							<div id="graph_container"></div>
							<div id="graph_people">
								<h3>Whose Observations Do You Want To See?</h3>
								<div id="use-mine" class="elgg-button elgg-button-action">Use My Own Observations</div>
								' . elgg_view('explore/graph/personpicker', array('title' => "Or Select Another User's:")) . '
								<div id="use-user" class="elgg-button elgg-button-action">Okay</div>
							</div>
            </div>
            <div id="tab_mapping"></div>
			<div id="tab_data">
				<div id="dynamic" width="100%">
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="wb-data">
					<thead>
						<tr>
							<th width="20%">User</th>
							<th width="20%">Investigation</th>
							<th width="20%">Measurement</th>
							<th width="5%">Value</th>
							<th width="5%">Unit</th>
							<th width="25%">Date</th>
						</tr>
					</thead>
					<tbody>

					</tbody>

				</table>

			</div>
            </div>

        </div>
        </div>';
	$canvas_area = elgg_view_layout('default', array('content' => $content, 'class' => 'frank'));
	echo elgg_view_page($title, $canvas_area);

?>
