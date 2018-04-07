<?php

$maxthumbwidth = 100;
$maxthumbheight = 100;

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


imagedestroy($original);

//RETURN A JPG TO THE BROWSER 
imagejpeg($thumb);
imagedestroy($thumb);

?>
