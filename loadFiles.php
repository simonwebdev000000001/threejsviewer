<?php
require_once(ABSPATH . 'wp-content/plugins/threejsviewer/config.php');
function load_files($extension = null)
{
    $uploads = wp_upload_dir();

    $files = new \FilesystemIterator(
        $uploads['path'],
        \FilesystemIterator::SKIP_DOTS
        | \FilesystemIterator::FOLLOW_SYMLINKS
    );

    $filesUpload = [];
    if (empty($extension)) {
        $extensions = ['mp3'];
    } else {
        $extensions = ['jpg', 'jpeg', 'png'];
    }

    foreach ($files as $file) {
        $ext = strtolower($file->getExtension());
        if (
            !$file->isDir()
            && is_numeric(array_search($ext, $extensions))
        ) {
            $_filePath = "wp-content" . explode("wp-content", $file->getRealPath())[1];
            $filesUpload[] = $_filePath;
        }


    }
    return $filesUpload;
}

function load_settings()
{
    global $wpdb;
    $table_name = $wpdb->prefix . TABLES[1];

    $querystr = "SELECT *   FROM $table_name ";

    return $wpdb->get_results($querystr, OBJECT);
}

