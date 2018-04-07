<?php

// these file extensions will not be shown in the gallery
// txt is also ignored because it used for the comment and language files.
$ignore_files = array( 'php', 'exe', 'txt', 'db','DS_Store');

// these are accepted images
$accepted_img = array( 'jpg', 'jpeg', 'gif', 'png' );



// these are accepted video extensions
// configure video player to use with each in include/view_video.php
$accepted_vid = array( 'avi', 'mpg', 'mpeg', 'divx', 'wmv', 'mov', 'qt', 'mp3', 'mp4', 'swf', 'flv', 'ogg', 'webm','ogv','ogm' );

// select which players to use for each file extension.
// if your divx files are with extension avi, you can choose to use the divx_vid array to be sure that it will work :)
$mediaplayer_vid = array( 'avi', 'wmv', 'mpg', 'mpeg', 'mp3', 'mp4');
$quicktime_vid = array('mov', 'qt' );
$flash_vid = array('swf', 'flv' );
$divx_vid = array( 'divx');
$html5_vid = array('ogg','webm','ogv','ogm');

?>