<?php
	// register for the init, system event when our plugin start.php is loaded
	elgg_register_event_handler('init', 'system', 'weatherblur_theme_init');

	function weatherblur_theme_init() {
		global $CONFIG;

		// Replace the default index page
		elgg_register_plugin_hook_handler('index', 'system', 'new_index');

		// Register system page handlers
		elgg_register_page_handler('wbsystem', 'wbsystem_pg_handler');

		$css_dir = $CONFIG->url . 'mod/weatherblur_theme/css/';
		$js_dir = $CONFIG->url . 'mod/weatherblur_theme/js/';

		//HEADER.CSS
		elgg_register_css('header', $css_dir.'header.css');
		elgg_load_css('header');

		//INDEX_BODY.CSS
		elgg_register_css('index_body', $css_dir,'index_body.css');
		elgg_load_css('index_body');

		//FOOTER.CSS
		elgg_register_css('footer', $css_dir.'footer.css');
		elgg_load_css('footer');

		//PROFILE.CSS
		elgg_register_css('profile', $css_dir.'profile.css');
		elgg_load_css('profile');

		//MODULES.CSS
		elgg_register_css('module', $css_dir.'modules.css');
		elgg_load_css('module');

		//BUTTONS.CSS
		elgg_register_css('buttons', $css_dir.'buttons.css');
		elgg_load_css('buttons');

		//INNER_PAGE.CSS
		elgg_register_css('inner_page', $css_dir.'inner_page.css');
		elgg_load_css('inner_page');

		//ADMIN.CSS
		elgg_register_css('admin', $css_dir.'admin.css');
		elgg_load_css('admin');

		//TYPOGRAPHY.CSS
		elgg_register_css('typography', $css_dir.'typography.css');
		elgg_load_css('typography');

		// smoothness UI from jquery, this css needs to be removed but see tabs in
		// exploredata page, those will have to be replaced.
		elgg_register_css('jq-smooth',
			'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
		elgg_register_css('jq-tabs', $css_dir.'tabs.css');
		elgg_register_css('enyo-css',
			'//d3pch6bcnsao4c.cloudfront.net/lib/enyo/enyo-onyx-2.2.0.css');
		elgg_register_css('graph-css', $css_dir.'graph.css');
		elgg_register_css('font-awesome',
			'//s3.amazonaws.com/nbt-assets/lib/fontawesome/css/font-awesome.css');

		elgg_register_js('enyo-js',
			'//d3pch6bcnsao4c.cloudfront.net/lib/enyo/enyo-onyx-2.2.0.js');
		elgg_register_js('d3', 'http://d3js.org/d3.v3.min.js');
		elgg_register_js('moment',
			'//d3pch6bcnsao4c.cloudfront.net/lib/moment.min.js');
		elgg_register_js('underscore',
			'//d3pch6bcnsao4c.cloudfront.net/lib/underscore-1.4.4.js');

		elgg_register_js('jq-widget', $js_dir . 'jquery.ui.widget.min.js');
		elgg_register_js('jq-tabs', $js_dir . 'jquery.ui.tabs.min.js');

		elgg_register_js('graph', $js_dir . 'graph.js');

	}

	function new_index() {
    	if (!include_once(dirname(dirname(__FILE__)) . "/weatherblur_theme/pages/index.php"))
        	return false;

        	return true;
     }

	function wbsystem_pg_handler($segments)
	{
		if ($segments[0] == 'enterdata')
		{
			include elgg_get_plugins_path() . 'weatherblur_theme/pages/enterdata.php';
			return true;
		}
		if ($segments[0] == 'investigate')
		{
			include elgg_get_plugins_path() . 'weatherblur_theme/pages/investigate.php';
			return true;
		}
		if ($segments[0] == 'exploredata')
		{
			include elgg_get_plugins_path() . 'weatherblur_theme/pages/exploredata.php';
			return true;
		}
		if ($segments[0] == 'resources')
		{
			include elgg_get_plugins_path() . 'weatherblur_theme/pages/resources.php';
			return true;
		}
		if ($segments[0] == 'people')
		{
			include elgg_get_plugins_path() . 'weatherblur_theme/pages/people.php';
			return true;
		}

		return false;
	}
?>
