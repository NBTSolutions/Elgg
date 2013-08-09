<?php
   
    $annotation_id = (int)get_input('annotation_id');
    $annotation = elgg_delete_annotation_by_id($annotation_id);

?>
