<?php

$getimage = $galleryfolder."/".$getimage;


$size = getimagesize ($getimage);
$gallery_code .= '<br /><br />';
if ($popupimage == "1") {
	$gallery_code .= "<a href='#' onclick=\"window.open('";
	if($use_modrewrite=="0") {} else { $gallery_code .= $rewrite_base; }
	$gallery_code .= $galleryfilesdir."/popup.php?img=".substr($getimage, (strlen($galleryfilesdir)+1))."&amp;w=$size[0]&amp;h=$size[1]&amp;t=$getimage','$x','width=$size[0],height=$size[1],directories=no,location=no,menubar=no,scrollbars=";
	if ($popupscrollbars==1) { $gallery_code .= "yes"; } else { $gallery_code .= "no"; }
	$gallery_code .= ",status=no,toolbar=no,resizable=no');return false\" target=\"_blank\">";

} else if ($popupimage == "3") {
	if($use_modrewrite=="0") {
	$gallery_code .= "<a href='".$getimage."' target=\"_blank\">";
	} else {
	$gallery_code .= "<a href='".$rewrite_base.$getimage."' target=\"_blank\">";
	}

} else if ($popupimage == "0") {
	$gallery_code .= $next_img_link;
}

if (($maxlargewidth !== "none" || $maxlargeheight !== "none") && ($maxlargewidth < $size[0] || $maxlargeheight < $size[1]) ) {
	// restricting image size of the large image displayed
	// generates cached image if option set
	$cacheimagename = "xxxxx";

		$modifed = filemtime($getimage);
		$filesize = filesize($getimage);
		$imgpath = substr($getimage, (strlen($galleryfilesdir)+1));
		$hash = md5($imgpath.$size[0].$size[1].$modifed.$filesize);
		$cacheimagename = "";
		if($use_modrewrite=="0") {} else { $cacheimagename = "/"; }
		$cacheimagename .= "gallery/".$cachefolder."/large_".$hash.".jpg";
		if (file_exists($cacheimagename)) {
		$gallery_code .= "<img class='large_img_view' border='0' src='".$cacheimagename."' alt='' />";					
		} else {

		$gallery_code .= "<img class='large_img_view' border='0' src='";
		if($use_modrewrite=="0") {} else { $gallery_code .= "/"; }
		$gallery_code .= $galleryfilesdir."/thumb.php?source=".substr($getimage, (strlen($galleryfilesdir)+1))."&height=".$maxlargeheight."&width=".$maxlargewidth."&large=true";
		if($watermark_large > "") {
		$gallery_code .= "&watermark_img=".$watermark_large."&watermark_opacity=".$watermark_opacity."&watermark_margin_large=".$watermark_margin_large."&watermark_mode=".$watermark_mode."&watermark_position=".$watermark_position;
		}
		if($copyright_text_large) {
		$gallery_code .= "&copyright_text=".$copyright_text."&copyright_size=".$copyright_size_large."&copyright_font=".$copyright_font."&copyright_angle=".$copyright_angle."&copyright_margin=".$copyright_margin_large."&copyright_position=".$copyright_position."&copyright_color=".$copyright_color."&copyright_shadow_distance=".$copyright_shadow_distance."&copyright_shadow_color=".$copyright_shadow_color;
		}
		$gallery_code .="' alt='' />";
		}



} else {
	// display image in full size
	if($use_modrewrite=="0") {
		$gallery_code .= '<img class="large_img_view" border="0" src="'.$getimage.'" alt="" />';
	} else {
		$gallery_code .= '<img class="large_img_view" border="0" src="/'.$getimage.'" alt="" />';
	}


}

$gallery_code .= '</a><br />';












?>