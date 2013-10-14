<?php
/**
 * WeatherBlur User Obs Widget
 */
 
if(!function_exists('elgg_view_observation_widget_list')) {
     function elgg_view_observation_widget_list($entities, $vars = array(), $offset = 0, $limit = 10, $full_view = true, $list_type_toggle = true, $pagination = true) {

        if (!is_int($offset)) {
            $offset = (int)get_input('offset', 0);
        }

        // list type can be passed as request parameter
        $list_type = get_input('list_type', 'list');
        if (get_input('listtype')) {
            elgg_deprecated_notice("'listtype' has been deprecated by 'list_type' for lists", 1.8);
            $list_type = get_input('listtype');
        }

        $defaults = array(
            'items' => $entities,
            'full_view' => false,
            'pagination' => true,
            'list_type' => $list_type,
            'list_type_toggle' => false,
            'offset' => $offset,
        );

        $vars = array_merge($defaults, $vars);

        if ($vars['list_type'] != 'list') {
            return elgg_view('page/components/gallery', $vars);
        } else {
            return elgg_view('page/components/observation_list_widget', $vars);
        }
    }
} 
 
$num = $vars['entity']->num_display;


 
$options = array(
	'type' => 'object',
	'subtype' => 'observation',
	'container_guid' => $vars['entity']->owner_guid,
	'limit' => $num,
	'full_view' => FALSE,
	'pagination' => TRUE,
);

$content = elgg_list_entities($options, 'elgg_get_entities', 'elgg_view_observation_widget_list');

echo $content;


if ($content) 
{
   //we have obs	
} 
else 
{
	echo "No Observations";
}
