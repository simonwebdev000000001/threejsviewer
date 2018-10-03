<?php
require_once(ABSPATH . 'wp-content/plugins/threejsviewer/config.php');
function load_Menu_Items()
{
    global $wpdb;
    $table_name = $wpdb->prefix . TABLES[0];

    $querystr = "SELECT *   FROM $table_name ";

    return $wpdb->get_results($querystr, OBJECT);
}

