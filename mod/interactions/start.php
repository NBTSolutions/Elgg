<?php
function interactions_init() 
{ 
	$css_url = 'mod/interactions/css/interactions.css';
    elgg_register_css('wb-interactions', $css_url);
    elgg_load_css('wb-interactions');
	add_widget_type('interactions', 'My WeatherBlur Stats', 'interactions');
}
register_elgg_event_handler('init','system','interactions_init'); 
?>