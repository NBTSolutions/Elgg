<?php
function wbuserobs_init() 
{ 
	add_widget_type('wbuserobs', 'My Observations', 'Display a list of user observations');
	
}

function wbuserobs_get_my_inv($agg_id) {
    $results = elgg_get_entities_from_metadata(array(
        "type_subtype_pair"	=>	array('object' => 'observation'),
        "metadata_name_value_pairs" => array('agg_id' => $agg_id)
    ));

    if($results) {
       
		$obs = get_entity($results[0]->guid);
		$inv_guid = $obs->parent_guid;
		$inv = get_entity($inv_guid);
		return $inv->name;
    }
    else {
        return 0;
    }
}

register_elgg_event_handler('init','system','wbuserobs_init'); 
?>
