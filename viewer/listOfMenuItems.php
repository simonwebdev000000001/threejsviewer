<?php
function load_Menu_Items(){ 
    global $wpdb; 
    $table_name = $wpdb->prefix . "viewer_menu";

    $querystr = "
    SELECT * 
    FROM $table_name ";

    return $wpdb->get_results($querystr, OBJECT); 
}

