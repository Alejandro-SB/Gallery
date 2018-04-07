<?php
include ("config/gallerysetup.php");
include ("admin/gallerysafemode.php");

// CLEAN GET VARS
foreach($_GET as $var => $val) {
	$_GET[$var] = htmlspecialchars(strip_tags($val));
}

$maxthumbwidth = stripslashes($_GET['width']);
$maxthumbheight = stripslashes($_GET['height']);

$source = stripslashes($_GET['source']);
$path = pathinfo($source);


switch(strtolower($path["extension"])){
	case "jpeg":
	case "jpg":
			$original=imagecreatefromjpeg($source);
			break;
	case "gif":
			$original=imagecreatefromgif($source);
			break;
	case "png":
			$original=imagecreatefrompng($source);
			break;
	default:
			break;			
}
$xratio = $maxthumbwidth/(imagesx($original));
$yratio = $maxthumbheight/(imagesy($original));

if($xratio < $yratio) {
		$thumb = imagecreatetruecolor($maxthumbwidth,floor(imagesy($original)*$xratio));
		$thumb_width = $maxthumbwidth;
		$thumb_height = floor(imagesy($original)*$xratio);
} else {
		$thumb = imagecreatetruecolor(floor(imagesx($original)*$yratio), $maxthumbheight);
		$thumb_width = floor(imagesx($original)*$yratio);
		$thumb_height = $maxthumbheight;
}



imagecopyresampled($thumb, $original, 0, 0, 0, 0, imagesx($thumb)+1,imagesy($thumb)+1,imagesx($original),imagesy($original));



// set copyright on image	
if ( isset($_GET['watermark_img']) ) {
include("include/copyright_img.php");
}
if ( isset($_GET['copyright_text']) ) {
include("include/copyright_text.php");
}

imagedestroy($original);

//CACHE IMAGE
if ($cachethumbs == "1") {


if (!is_writable($cachefolder)) {
	// Check for safe mode
	if( ini_get('safe_mode') ) {
	   chmodftp($galleryrootdir,$cachefolder,777);
	   die();
	}else{
	   chmod($cachefolder, 0777);
	}
}	

if (is_writable($cachefolder)) {	
$size = getimagesize($source);
$modifed = filemtime($source);
$filesize = filesize($source);
$hash = md5($source.$size[0].$size[1].$modifed.$filesize);

if (stripslashes($_GET['large']) == "true") {
$cacheimagename = $cachefolder."/large_".$hash.".jpg";	
} else {
$cacheimagename = $cachefolder."/thumb_".$hash.".jpg";
}

imagejpeg($thumb,$cacheimagename,$cachequality);
}
}


//RETURN A JPG TO THE BROWSER 
imagejpeg($thumb);
imagedestroy($thumb);

?>
