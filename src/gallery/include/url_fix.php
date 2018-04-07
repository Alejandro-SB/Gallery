<?php


// IIS request_uri fix
if(!isset($_SERVER['REQUEST_URI'])) {
    $uri_subfolder = dirname($_SERVER['PHP_SELF']);
    $arr = explode($uri_subfolder, $_SERVER['PHP_SELF']);
    $_SERVER['REQUEST_URI'] = $uri_subfolder . $arr[count($arr)-1];
    if ($_SERVER['argv'][0]!="") {
        $_SERVER['REQUEST_URI'] .= "?" . $_SERVER['argv'][0];
    }
}
	
	
// keep track of current url...

$currenturl = $_SERVER["REQUEST_URI"];	

if (strpos($currenturl,$get_folder."=") ) {$currenturl = substr($currenturl, 0, (strpos($currenturl,$get_folder."=")-1));}
if (strpos($currenturl,$get_image."=") )  {$currenturl = substr($currenturl, 0, (strpos($currenturl,$get_image."=")-1));}
if (strpos($currenturl,$get_page."=") )  {$currenturl = substr($currenturl, 0, (strpos($currenturl,$get_page."=")-1));}
if (strpos($currenturl,$get_slideshow."=") )  {$currenturl = substr($currenturl, 0, (strpos($currenturl,$get_slideshow."=")-1));}

if ($includemode == true) {
      if (strpos($currenturl,"?")) {
	    $currenturl = $currenturl."&amp;";
	} else {
	      $currenturl = $currenturl."?";
	}
} else {
	$currenturl = $currenturl."?";
}



?>