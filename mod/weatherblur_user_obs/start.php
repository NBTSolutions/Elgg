<?php
function wbuserobs_init() 
{ 
	add_widget_type('wbuserobs', 'My Observations', 'Display a list of user observations');
	
}
register_elgg_event_handler('init','system','wbuserobs_init'); 
?>