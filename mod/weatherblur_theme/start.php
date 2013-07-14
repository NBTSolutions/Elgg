<?php
	// register for the init, system event when our plugin start.php is loaded
	elgg_register_event_handler('init', 'system', 'weatherblur_theme_init');

	function weatherblur_theme_init() {
		global $CONFIG;
	
		// Replace the default index page
		elgg_register_plugin_hook_handler('index', 'system', 'new_index');
		
		// Register system page handlers
		elgg_register_page_handler('wbsystem', 'wbsystem_pg_handler');
    
		//HEADER.CSS
		elgg_register_css('header', $CONFIG->url.'mod/weatherblur_theme/css/header.css'); 
		elgg_load_css('header');

		//INDEX_BODY.CSS
		elgg_register_css('index_body', $CONFIG->url.'mod/weatherblur_theme/css/index_body.css'); 
		elgg_load_css('index_body');
   
		//FOOTER.CSS
		elgg_register_css('footer',$CONFIG->url.'mod/weatherblur_theme/css/footer.css'); 
		elgg_load_css('footer'); 
		
		//PROFILE.CSS
		elgg_register_css('profile', $CONFIG->url.'mod/weatherblur_theme/css/profile.css'); 
		elgg_load_css('profile');
		
		//MODULES.CSS
		elgg_register_css('module', $CONFIG->url.'mod/weatherblur_theme/css/modules.css'); 
		elgg_load_css('module');
		
		//BUTTONS.CSS
		elgg_register_css('buttons', $CONFIG->url.'mod/weatherblur_theme/css/buttons.css'); 
		elgg_load_css('buttons');
		
		//INNER_PAGE.CSS
		elgg_register_css('inner_page', $CONFIG->url.'mod/weatherblur_theme/css/inner_page.css'); 
		elgg_load_css('inner_page');
		
		//ADMIN.CSS
		elgg_register_css('admin', $CONFIG->url.'mod/weatherblur_theme/css/admin.css'); 
		elgg_load_css('admin');
		
		//TYPOGRAPHY.CSS
		elgg_register_css('typography', $CONFIG->url.'mod/weatherblur_theme/css/typography.css'); 
		elgg_load_css('typography');



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