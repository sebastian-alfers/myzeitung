<?php
    //return only json encoded strings; makes it usable for JS processing
    header('Content-Type: application/json');
    echo $content_for_layout;

?>