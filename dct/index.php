<?php

include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";

elgg_load_library('elgg:investigations');

header('Location: '.elgg_get_site_url().'dct/index.html');
