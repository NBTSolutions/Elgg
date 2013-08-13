<?php
function wbsna_init() 
{ 
	add_widget_type('wbsna', 'WeatherBlur SNA Report', 'Export Social Network Anlalysis');
	
}
register_elgg_event_handler('init','system','wbsna_init'); 
?>