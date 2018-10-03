<?php
function load_files()
{
    $uploads = wp_upload_dir();

    $files = new \FilesystemIterator(
        $uploads['path'],
        \FilesystemIterator::SKIP_DOTS
        | \FilesystemIterator::FOLLOW_SYMLINKS
    );

    $filesUpload = [];
    $extensions = ['mp3'];
    foreach ($files as $file) {
        if (
            !$file->isDir()
            && is_numeric(array_search($file->getExtension(), $extensions))
        ) {
            $_filePath = "wp-content" . explode("wp-content", $file->getRealPath())[1];
            $filesUpload[] = $_filePath;
        }


    }
    return $filesUpload;
}

