<?php
/**
 * news sidebar
 *
 * @package news
 */


// only users can have archives at present
if ($vars['page'] == 'owner' || $vars['page'] == 'group') {
	echo elgg_view('news/sidebar/archives', $vars);
}

