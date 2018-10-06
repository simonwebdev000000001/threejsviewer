<?php
require_once(ABSPATH . 'wp-content/plugins/threejsviewer/config.php');
function load_Menu_Items($onlyWordPressMenu = false)
{
    global $wpdb;
    $table_name = $wpdb->prefix . TABLES[0];

    if ($onlyWordPressMenu) {
        $querystr = "SELECT *   FROM $table_name where `menuId` IS NOT NULL";
    } else {
        $querystr = "SELECT *   FROM $table_name where `menuId` IS NULL";
    }


    return $wpdb->get_results($querystr, OBJECT);
}

