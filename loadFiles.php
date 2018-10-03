<?php
function load_files(){
    $uploads = wp_upload_dir();

$files = new \FilesystemIterator( 
    $uploads['path'],
    \FilesystemIterator::SKIP_DOTS 
    | \FilesystemIterator::FOLLOW_SYMLINKS
);

$filesUpload = [];
$extensions = ['mp3'];
foreach ( $files as $pdf )
{
    /** @noinspection PhpIncludeInspection */
    if ( 
        ! $files->isDir() 
        && is_numeric(array_search($files->getExtension(),$extensions))
    ){
        $_filePath = "wp-content".explode("wp-content",$files->getRealPath())[1];
        $filesUpload[]=$_filePath;
    }
        
       
}
return $filesUpload ;
}

