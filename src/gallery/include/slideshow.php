<?php

	$folder = $galleryfolder."/".$getslide;
	$galleryimg_dir = dir($folder);
	

	// read images in selected sub folder
	$img_array = array();
	$folder_array = array();
	while(($file = $galleryimg_dir->read()) !== false) { 

		$file_ext = explode(".",$file);
		$file_ext = strtolower($file_ext[1]);
		
		if (is_dir($folder."/".$file) && $file!="." && $file!="..") {
		} else {
			if( in_array( $file_ext , $accepted_img )  && $file != $defaultfolderimage ) { 
			$img_array[] = $file; // else include
			}
		}
	}



if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	// sort the files
	if ($filesort == "desc") { 
		krsort($img_array); 
	} else { 
		ksort($img_array); 
	}
} else {
	// sort the files
	if ($filesort == "desc") { 
		rsort($img_array); 
	} else { 
		sort($img_array); 
	}
}








if($page > 1) { 
	if(!$file_list_view) {
		$pageimages = $columns * $rows;
	} else {
		$pageimages = $file_list_files_per_page;
	}

	$start_image = ($page * $pageimages) - $pageimages;

	$img_array = array_merge(array_slice($img_array,$start_image), array_slice($img_array,0,$start_image));
}



$gallery_code .= '
<script>
<!--
/* 
SlideShow. Written by PerlScriptsJavaScripts.com
Copyright http://www.perlscriptsjavascripts.com 
Free and commercial Perl and JavaScripts    

Modified by Joonas Viljanen
*/

// seconds to diaply each image?
display = '.$slideshow_display_time.';


// path to image/name of image in slide show. this will also preload all images
// each element in the array must be in sequential order starting with zero (0)
SLIDES = new Array();

// SLIDES[0]  = ["/images/js/04b.jpg", "Office"];

';

$i = 0;
foreach($img_array as $image) {
	
	$displayimage = "";

	$size = getimagesize ($folder."/".$image);
	$path = str_replace($galleryfolder."/","",$folder)."/";

	$xratio = $maxlargewidth/$size[0];
	$yratio = $maxlargeheight/$size[1];
	if($xratio < $yratio) {
		$thumbwidth = $maxlargewidth;
		$thumbheight = floor($size[1]*$xratio);
	} else {
		$thumbheight = $maxlargeheight;
		$thumbwidth = floor($size[0]*$yratio);
	}

	$modifed = filemtime($folder."/".$image);
	$filesize = filesize($folder."/".$image);
	$imgpath = str_replace("//","/",substr($folder."/".$image,(strlen($galleryfilesdir)+1))  );
	$hash = md5($imgpath.$size[0].$size[1].$modifed.$filesize);
	$cacheimagename = $galleryfilesdir."/".$cachefolder."/large_".$hash.".jpg";

	if (file_exists($cacheimagename)) {
		if($use_modrewrite) {$displayimage .= $rewrite_base; }
		$displayimage .= $cacheimagename;

		if($i == 0) {
		$first_image = $displayimage;
		}
		
		
		
	} else {


		if($use_modrewrite) {$displayimage .= $rewrite_base; }
		$displayimage .= $galleryfilesdir."/thumb.php?source=".str_replace($galleryfilesdir."/","",$folder)."/".$image."&height=".$maxlargeheight."&width=".$maxlargewidth."&large=true";
	if($watermark_thumb > "") {
	$displayimage .= "&watermark_img=".$watermark_large."&watermark_opacity=".$watermark_opacity."&watermark_margin_large=".$watermark_margin_large."&watermark_mode=".$watermark_mode."&watermark_position=".$watermark_position;
	}
	if($copyright_text_thumb) {
	$displayimage .= "&copyright_text=".$copyright_text."&copyright_size=".$copyright_size_large."&copyright_font=".$copyright_font."&copyright_angle=".$copyright_angle."&copyright_margin=".$copyright_margin_large."&copyright_position=".$copyright_position."&copyright_color=".$copyright_color."&copyright_shadow_distance=".$copyright_shadow_distance."&copyright_shadow_color=".$copyright_shadow_color;
	}
			if($i == 0) {		
			$first_image = $displayimage;
			}

	}



	$gallery_code .=  'SLIDES['.$i.']  = ["'.$displayimage.'", ""];
	';
	$i++;
}



$gallery_code .= '
// end required modifications

S = new Array();
for(a = 0; a < SLIDES.length; a++){
	S[a] = new Image(); S[a].src  = SLIDES[a][0];
}
// -->
</script>
';





$gallery_code .= '
<form name="_slideShow">
<input type="Hidden" name="currSlide" value="0">
<input type="Hidden"name="delay">

<a href="javascript:;" onclick="startSS()"><img src="';
if($use_modrewrite) {$gallery_code .= $rewrite_base; }
$gallery_code .= 'gallery/theme/';
if( file_exists("gallery/theme/".$gallerytheme."/icons/slide_play.gif") ) {$gallery_code .= $gallerytheme.'/icons/';} else { $gallery_code .= 'DEFAULT_ICONS/'; }
$gallery_code .= 'slide_play.gif" width="30" height="32" alt="" border="0"></a>
<a href="javascript:;" onclick="stopSS()"><img src="';
if($use_modrewrite) {$gallery_code .= $rewrite_base; }
$gallery_code .= 'gallery/theme/';
if( file_exists("gallery/theme/".$gallerytheme."/icons/slide_stop.gif") ) {$gallery_code .= $gallerytheme.'/icons/';} else { $gallery_code .= 'DEFAULT_ICONS/'; }
$gallery_code .= 'slide_stop.gif" width="30" height="32" alt="" border="0"></a>

<a href="javascript:;" onclick="prevSS()"><img src="';
if($use_modrewrite) {$gallery_code .= $rewrite_base; }
$gallery_code .= 'gallery/theme/';
if( file_exists("gallery/theme/".$gallerytheme."/icons/slide_prev.gif") ) {$gallery_code .= $gallerytheme.'/icons/';} else { $gallery_code .= 'DEFAULT_ICONS/'; }
$gallery_code .= 'slide_prev.gif" width="30" height="32" alt="" border="0"></a>
<a href="javascript:;" onclick="nextSS()"><img src="';
if($use_modrewrite) {$gallery_code .= $rewrite_base; }
$gallery_code .= 'gallery/theme/';
if( file_exists("gallery/theme/".$gallerytheme."/icons/slide_next.gif") ) {$gallery_code .= $gallerytheme.'/icons/';} else { $gallery_code .= 'DEFAULT_ICONS/'; }
$gallery_code .= 'slide_next.gif" width="30" height="32" alt="" border="0"></a>


<br />

<img name="stage" border="0" src="';
if($use_modrewrite) {$gallery_code .= $rewrite_base; }
$gallery_code .= $first_image.'" style="filter: revealtrans();">
</form>
';


$gallery_code .= '
<script>
<!--

f = document._slideShow;
n = 0;
t = 0;

f.delay.value = display;


function startSS(){
	t = setTimeout("runSS(" + f.currSlide.value + ")", 1 * 1);
}

function runSS(n){
	n++;
	if(n >= SLIDES.length){
		n = 0;
	}
	document.images["stage"].src = S[n].src;

	f.currSlide.value = n;
	t = setTimeout("runSS(" + f.currSlide.value + ")", f.delay.value * 1000);
}

function stopSS(){
	if(t){
		t = clearTimeout(t);
	}
}

function nextSS(){
	stopSS();
	n = f.currSlide.value;
	n++;
	if(n >= SLIDES.length){
		n = 0;
	}
	if(n < 0){
		n = SLIDES.length - 1;
	}
	document.images["stage"].src = S[n].src;
	f.currSlide.value = n;

}

function prevSS(){
	stopSS();
	n = f.currSlide.value;
	n--;
	if(n >= SLIDES.length){
		n = 0;
	}
	if(n < 0){
		n = SLIDES.length - 1;
	}
	document.images["stage"].src = S[n].src;
	f.currSlide.value = n;
	
}

function selected(n){
	stopSS();
	document.images["stage"].src = S[n].src;
	f.currSlide.value = n;
	
}


// -->
</script>
';



?>