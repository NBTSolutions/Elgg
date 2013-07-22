<?php
/**
 * Register the Elggnews class for the object/news subtype
 */

if (get_subtype_id('object', 'news')) {
	update_subtype('object', 'news', 'Elggnews');
} else {
	add_subtype('object', 'news', 'Elggnews');
}
