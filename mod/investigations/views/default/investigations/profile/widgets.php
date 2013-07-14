<?php
/**
* Profile widgets/tools
* 
* @package ElggGroups
*/ 
	
// tools widget area
echo '<ul id="groups-tools" class="elgg-gallery elgg-gallery-fluid mtl clearfix">';

// enable tools to extend this area
// we could use elgg_view_extend to make use of this tool.
echo elgg_view("investigations/tool_latest", $vars);

// backward compatibility
$right = elgg_view('groups/right_column', $vars);
$left = elgg_view('groups/left_column', $vars);
if ($right || $left) {
	elgg_deprecated_notice('The views investigations/right_column and investigations/left_column have been replaced by investigations/tool_latest', 1.8);
	echo $left;
	echo $right;
}

echo "</ul>";

