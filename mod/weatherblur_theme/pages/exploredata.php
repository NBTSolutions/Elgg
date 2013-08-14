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
	elgg_load_css('tables-css');
	elgg_load_css('font-awesome');
	elgg_load_css('tabletools-css');

	elgg_load_js('enyo-js');
	elgg_load_js('d3');
	elgg_load_js('moment');
	elgg_load_js('underscore');
	elgg_load_js('jq-widget');
	elgg_load_js('jq-tabs');
	elgg_load_js('graph');
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

				 show: function(ui, event)
				 {
					ttInstances = TableTools.fnGetMasters();
					for (i in ttInstances) {
						if (ttInstances[i].fnResizeRequired()) ttInstances[i].fnResizeButtons();
					}
				}
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
                <div><img src="'.$site_url.'mod/weatherblur_theme/graphics/observation_explorer.png"></div>
            </div>
            <div id="tab_graphing">
                <div id="graph_container"></div>
            </div>
            <div id="tab_mapping">
				<div><iframe src="http://nbt-static.s3-website-us-east-1.amazonaws.com/weatherblur/map/unstable/" id="explore_page_map"></iframe></div>
            </div>
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
	$canvas_area = elgg_view_layout('default', array('content' => $content));
	echo elgg_view_page($title, $canvas_area);

?>
