<?php
/**
 * Layout for main column with one sidebar
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content HTML for the main column
 * @uses $vars['sidebar'] Optional content that is displayed in the sidebar
 * @uses $vars['title']   Optional title for main content area
 * @uses $vars['class']   Additional class to apply to layout
 * @uses $vars['nav']     HTML of the page nav (override) (default: breadcrumbs)
 */

?>
<div class="elgg-layout elgg-layout-one-sidebar clearfix inv-detail">
	<div class="elgg-sidebar">
		<?php
			echo elgg_view('page/investigation/sidebar', $vars);
		?>
	</div>

	<div class="elgg-main elgg-body">
		<?php
			if (isset($vars['title'])) {
				echo elgg_view_title($vars['title']);
			}

			if (isset($vars['header'])) {
				$vars['header_override'] = $vars['header'];
			}
			echo elgg_view('page/layouts/content/header', $vars);

			if (isset($vars['content'])) {
				echo $vars['content'];
			}
		?>
	</div>
</div>
