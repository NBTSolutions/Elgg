<?php
	// register for the init, system event when our plugin start.php is loaded
elgg_register_event_handler('init', 'system', 'weatherblur_theme_init');

	function weatherblur_theme_init() {
		global $CONFIG;

		$site_url = elgg_get_site_url();

		// Replace the default index page
		elgg_register_plugin_hook_handler('index', 'system', 'new_index');

		// Register system page handlers
		elgg_register_page_handler('wbsystem', 'wbsystem_pg_handler');

		$css_dir = elgg_get_site_url() . 'mod/weatherblur_theme/css/';
		$js_dir = elgg_get_site_url() . 'mod/weatherblur_theme/js/';

		// include open sans font:
		elgg_register_css('opensans', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700');
		elgg_load_css('opensans');

		// include font-awesome's css
		elgg_register_css('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css');
		elgg_load_css('font-awesome');

		//HEADER.CSS
		elgg_register_css('header', $css_dir.'header.less');
		elgg_load_css('header');

		//INDEX_BODY.CSS
		elgg_register_css('index_body', $css_dir.'index_body.less');
		elgg_load_css('index_body');

		//FOOTER.CSS
		elgg_register_css('footer', $css_dir.'footer.less');
		elgg_load_css('footer');

		//PROFILE.CSS
		elgg_register_css('profile', $css_dir.'profile.less');
		elgg_load_css('profile');

		//MODULES.CSS
		elgg_register_css('module', $css_dir.'modules.less');
		elgg_load_css('module');

		//BUTTONS.CSS
		elgg_register_css('buttons', $css_dir.'buttons.less');
		elgg_load_css('buttons');

		//INNER_PAGE.CSS
		elgg_register_css('inner_page', $css_dir.'inner_page.less');
		elgg_load_css('inner_page');

		//ADMIN.CSS
		elgg_register_css('admin', $css_dir.'admin.less');
		elgg_load_css('admin');

		//TYPOGRAPHY.CSS
		elgg_register_css('typography', $css_dir.'typography.less');
		elgg_load_css('typography');

		//GRAPH.CSS
		elgg_register_css('graph', $CONFIG->url.'mod/weatherblur_theme/css/graph.css');
		elgg_load_css('graph');

		elgg_register_css('enyo-css',
			'//d3pch6bcnsao4c.cloudfront.net/lib/enyo/enyo-onyx-2.2.0.css');
		elgg_register_css('graph-css', $css_dir.'graph.css');
		elgg_register_css('tables-css', $css_dir.'wb-table.css');
		elgg_register_css('tabletools-css', $site_url.'mod/weatherblur_theme/media/css/TableTools.css');


		elgg_register_js('datatables', '//ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.js');
        elgg_register_js('table-tools',
			$site_url.'mod/weatherblur_theme/media/js/TableTools.js');
		elgg_register_js('table-tools-zc',
			$site_url.'mod/weatherblur_theme/media/js/ZeroClipboard.js');


		elgg_register_js('less',
		'//d3pch6bcnsao4c.cloudfront.net/lib/less-1.3.3.min.js');
		elgg_load_js('less');

		elgg_register_js('enyo-js',
			'//d3pch6bcnsao4c.cloudfront.net/lib/enyo/enyo-onyx-2.2.0.js');
		elgg_register_js('d3', 'http://d3js.org/d3.v3.min.js');
		elgg_register_js('moment',
			'//d3pch6bcnsao4c.cloudfront.net/lib/moment.min.js');

		elgg_register_js('graph', $js_dir . 'graph.js', 'footer');
		elgg_register_js('exploredata', $js_dir . 'exploredata.js');

		elgg_unregister_js('jquery');
		elgg_register_js('jquery191', '//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.9.1.js');
		elgg_load_js('jquery191');
		elgg_register_js('jquery-migrate', '//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.1.1.min.js');
		elgg_load_js('jquery-migrate');

		elgg_unregister_js('jquery-ui');
		elgg_register_js('jquery191-ui', '//ajax.aspnetcdn.com/ajax/jquery.ui/1.9.1/jquery-ui.min.js');
		elgg_load_js('jquery191-ui');

		elgg_register_css('jq-smooth', '//ajax.aspnetcdn.com/ajax/jquery.ui/1.9.1/themes/smoothness/jquery-ui.css');


		elgg_register_event_handler('pagesetup', 'system', 'kill_friends_link');

		//register login count hook handler
		elgg_register_event_handler('login', 'user', 'login_count');


	}

	/**
	 * Since all users are 'friends' with all other users and cannot turn that
	 * on/off, we might as well not give them the option of viewing the list of
	 * their 'friends'.
	 */
	function kill_friends_link() {
		elgg_unregister_menu_item('topbar', 'friends');
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
		if ($segments[0] == 'profile')
		{
			include elgg_get_plugins_path() . 'weatherblur_theme/pages/profile.php';
			return true;
		}

		if ($segments[0] == 'wbsnareport')
		{
			include elgg_get_plugins_path() . 'weatherblur_theme/pages/wbsnareport.php';
			return true;
		}

		return false;
	}

	function login_count($event, $type, $user)
	{
			$login_count = $user->login_count;
			$login_count++;
			$user->login_count = $login_count;



	}

	function elgg_get_featured($type, $subtype, $count=1) {
		$ents = elgg_get_entities_from_metadata(array(
			'type' => $type,
			'subtype' => $subtype,
			// get most recent first: nice thing is elgg updates 'time_created' on save.
			'order_by' => 'time_created DESC',
			'limit' => $count,
			'metadata_name_value_pairs' => array(
				'name' => 'featured_group',
				'value' => 'yes'
			)
		));

		return $ents;
}
?>
