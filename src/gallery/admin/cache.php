<?php

// if user logged in
if (isset($_SESSION['username'])) {



$deletecache = null;
if (isset($_GET['deletecache'])) {
  $deletecache = (get_magic_quotes_gpc()) ? $_GET['deletecache'] : addslashes($_GET['deletecache']);
}



if ($deletecache) {


foreach (glob("../thumbcache/*.jpg") as $filename) {
   unlink($filename);
}

foreach (glob("../thumbcache/*.db") as $filename) {
   unlink($filename);
}

}











 function DirStat($directory) {  
    global $FolderCount, $FileCount, $FolderSize;  
  
    chdir($directory);  
    $directory = getcwd();  
    if($open = opendir($directory)) {  
        while($file = readdir($open)) {  
            if($file == '..' || $file == '.') continue;  
            if(is_file($file)) {  
                $FileCount++;  
                $FolderSize += filesize($file);  
            } elseif(is_dir($file)) {  
                $FolderCount++;  
            }  
        }  
        if($FolderCount > 0) {  
            $open2 = opendir($directory);  
            while($folders = readdir($open2)) {  
                $folder = $directory.'/'.$folders;  
                if($folders == '..' || $folders == '.') continue;  
                if(is_dir($folder)) {  
                    DirStat($folder);  
                }  
            }  
            closedir($open2);  
        }  
        closedir($open);  
    }  
 }  
  
 function ByteSize($bytes) {  
    $size = $bytes / 1024;  
    if($size < 1024){  
        $size = number_format($size, 2);  
        $size .= 'kb';  
    } else {  
        if($size / 1024 < 1024) {  
            $size = number_format($size / 1024, 2);  
            $size .= 'mb';  
        } elseif($size / 1024 / 1024 < 1024) {  
            $size = number_format($size / 1024 / 1024, 2);  
            $size .= 'gb';  
        } else {  
            $size = number_format($size / 1024 / 1024 / 1024, 2);  
            $size .= 'tb';  
        }  
    }  
    return $size;  
 }  
 
 $FileCount = 0;
 
 $folder = '../thumbcache/';  
 $dir = getcwd();  
 DirStat($folder, 0);  
 chdir($dir);  
 $FolderSize = ByteSize($FolderSize);  
  
  $html .= '<b>Folder Name:</b> '.str_replace("../","",$folder).'<br />';  
  $html .= '<b>File Count:</b> '.$FileCount.'<br />';  
  $html .= '<b>Folder Size:</b> '.$FolderSize.'<br />';  




if($FileCount > 0) {
$html .= '<br><br><a href="?galleryadmin=cache&deletecache=true">Clear Cache</a>';
} else {
$html .= '<br><br>CACHE is Empty';
}

$html .= '<br><br><a href="?galleryadmin=generate_th">Generate All Cache Files</a>';










} else { // if not logged in
$html .=('Sorry, you are not logged in, this area is restricted to admin.');
$html .= "<br><input type=button class='button' value='Back' onClick='history.go(-1)'>";
}

?>