<?php


function getImageName($folder,$image) {
	
	$path_parts = pathinfo($image);
	$imagename = substr($path_parts['basename'], 0, -(strlen($path_parts['extension']) + ($path_parts['extension'] == '' ? 0 : 1)));
									
	$langfile = $folder."/".$imagename."_name_".$_SESSION['lang'].".txt";
	if( file_exists($langfile) ) {
			$fp = fopen($langfile, "r");
			$imagename = fread($fp, filesize($langfile));
			fclose($fp);
	}	
	
	$imagename = str_replace("_"," ", RemoveOrderNumber($imagename) );	
	return $imagename;
}



function getCommentsFileName($getimage) {
	$path_parts = pathinfo($getimage);
	$path_parts['basename_we'] = substr($path_parts['basename'], 0, -(strlen($path_parts['extension']) + ($path_parts['extension'] == '' ? 0 : 1)));
	$commentsfile = $path_parts['dirname']."/".$path_parts['basename_we'].".txt";

	$langfile = $path_parts['dirname']."/".$path_parts['basename_we']."_".$_SESSION['lang'].".txt";
	if( file_exists($langfile) ) {
		$commentsfile = $langfile;
	}
	return $commentsfile;
}

function getCommentsText($commentsfile,$removeNL) {
	$fp = fopen($commentsfile, "r");
	$comments = fread($fp, filesize($commentsfile));
	fclose($fp);
	
	if($removeNL) {
		$comments = str_replace("\n", " ", $comments);
		$comments = str_replace("\r", " ", $comments);
	}
	
	$comments = htmlentities($comments);
	
	return $comments;
}






function buildgallerymenu() {



Global $session_menu_save;
Global $foldername;
Global $folder_array;
Global $getfolder;
Global $get_folder;
Global $galleryfolder;
Global $currenturl;
Global $galleryfilesdir;
Global $use_modrewrite;
Global $rewrite_base;
$menuprint = "";


if ($_SESSION['menu']!==null && $session_menu_save && $_SESSION['menu_lang'] == $_SESSION['lang']) {
return $_SESSION['menu'];
} else {


$gallery_dir = dir($galleryfolder);

$folder_array = array();
while(($folder = $gallery_dir->read()) !== false) { 
	array_push($folder_array, $folder); 
}
sort($folder_array);


$menuprint .= "<div id='menu'>";
$menuprint .= "<ul>";
$foldernumber = -1;
foreach($folder_array as $foldername) {
	if ($foldernumber == 1 && !isset($_GET[$get_folder] ) && isset($_GET[$get_image] )) {
		$getfolder = $foldername;
	}
	if (is_dir($galleryfolder."/".$foldername)) {
	if ($foldername=="." || $foldername=="..") {} else {
		$displayfoldername = RemoveOrderNumber($foldername);
		$displayfoldername = str_replace("_"," ", $displayfoldername);
		
		$langfile = $galleryfolder."/".$foldername."/".$_SESSION['lang'].".txt";
		if( file_exists($langfile) ) {
				$fp = fopen($langfile, "r");
				$displayfoldername = fread($fp, filesize($langfile));
				fclose($fp);
		}		
		
			if($use_modrewrite=="0") {
			$menuprint .= '<li><a href="'.$currenturl.$get_folder.'='.$foldername.'">'.$displayfoldername.'</a>';
			} else {
			$menuprint .= '<li><a href="'.$rewrite_base.$get_folder.'/'.$foldername.'">'.$displayfoldername.'</a>';
			}
			

			// build subMenu
			$subfolder = $galleryfolder.'/'.$foldername;
			if (is_dir($subfolder)) {
				$menuprint .= subMenu($subfolder);
			}

		$menuprint .= "</li>";
	}
	}
$foldernumber++;
}  
$menuprint .= "</ul>";	
$menuprint .= "</div>";

if ($session_menu_save) {
$_SESSION['menu'] = $menuprint;
$_SESSION['menu_lang'] = $_SESSION['lang'];
}

return $menuprint;
}

} // end function buildgallerymenu






/////////////////////////////////////////////////////////////////////////////////







function subMenu($path) {

Global $folder_array;
Global $getfolder;
Global $get_folder;
Global $galleryfolder;
Global $currenturl;
Global $galleryfilesdir;
Global $use_modrewrite;
Global $rewrite_base;
$submenu_print = ""; 
				$gallerysub_dir = dir($path);
				$subfolder_array = array();
					while(($subfoldername = $gallerysub_dir->read()) !== false) { 
						if ($subfoldername=="." || $subfoldername=="..") {} else {
							if (is_dir($path."/".$subfoldername)) {	
							array_push($subfolder_array, $subfoldername); 
							}
						}
					}
					
					sort($subfolder_array);
					
					if (count($subfolder_array)>0) {
						$submenu_print .= "<ul>";
						foreach($subfolder_array as $subsubfolder) {
							$subfolderdisplayname = RemoveOrderNumber($subsubfolder);
							$subfolderdisplayname = str_replace("_"," ", $subfolderdisplayname);

							$langfile = $path."/".$subsubfolder."/".$_SESSION['lang'].".txt";
							if( file_exists($langfile) ) {
									$fp = fopen($langfile, "r");
									$subfolderdisplayname = fread($fp, filesize($langfile));
									fclose($fp);
							}		

							
							if($use_modrewrite=="0") {
							$submenu_print .= '<li><a href="'.$currenturl.$get_folder.'='.str_replace($galleryfolder."/","",$path).'/'.$subsubfolder.'">'.$subfolderdisplayname.'</a>';
							} else {
							$submenu_print .= '<li><a href="'.$rewrite_base.$get_folder.'/'.str_replace($galleryfolder."/","",$path).'/'.$subsubfolder.'">'.$subfolderdisplayname.'</a>';
							}
							
							
								// build subMenu
								$newpath="";
								$path_folders = explode('/',$path);
								for ($i=0;$i<sizeof($path_folders);$i++) {
								$newpath .= $path_folders[$i]."/";
								}
								$newpath .= $subsubfolder;
								if (is_dir($newpath)) {
									$submenu_print .= subMenu($newpath);
								}
							
							$submenu_print .= '</li>';
					
						}
			
					$submenu_print .= "</ul>";					
					
					
					
					}

return $submenu_print;

} // end function subMenu




/////////////////////////////////////////////////////////////////////////////////



function  gallery_page_title() {

Global $gallerytitle;
Global $pagetitle_separator;
Global $getfolder;
Global $getimage;
Global $getslide;
Global $galleryfolder;
Global $lbl_slideshow;

	$separator = " ".$pagetitle_separator." ";

	$title =  stripslashes($gallerytitle);

	if ($getfolder > "") { 
	$title .=  $separator.str_replace("/",$separator,$getfolder);
	} else if ($getimage > "") { 
	$title .=  $separator.str_replace("/",$separator,  str_replace($galleryfolder."/","",$getimage)  );
	} else if ($getslide > "") { 
	$title .=  $separator.$lbl_slideshow.$separator.str_replace("/",$separator,$getslide);
	}

return $title;

} // end function gallery_page_title





/////////////////////////////////////////////////////////////////////////////////




function navlinks() {

Global $standalonemode;
Global $getimage;
Global $get_image;
Global $get_folder;
Global $galleryfolder;
Global $lbl_back;
Global $lbl_next;
Global $lbl_previous;
Global $currenturl;
Global $filesort;
Global $use_modrewrite;
Global $rewrite_base;
Global $columns;
Global $rows;
Global $accepted_img;
Global $accepted_vid;
Global $file_list_view;
Global $file_list_files_per_page;
Global $next_img_link;

// read files in the directory
$subfolder = dirname($galleryfolder."/".$getimage);
$gallery_dir = dir($subfolder);
$files = array();
while(($filename = $gallery_dir->read()) !== false) {
    if ($filename=="." || $filename=="..") {} else {
            if (is_dir($subfolder."/".$filename)) { } else {
            array_push($files, $filename);
            }
    }
}

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	// sort the files
	if ($filesort == "desc") { 
		krsort($files);  
	} else { 
		ksort($files); 
	}
} else {
	// sort the files
	if ($filesort == "desc") { 
		rsort($files); 
	} else { 
		sort($files); 
	}
}
/*
// sort the files
if ($filesort == "desc") {
krsort($files);
} else {
ksort($files);
}
*/

// filter files
foreach($files as $img) {
	$path_parts = pathinfo($img);
	$file_ext = strtolower($path_parts['extension']);

	if( in_array( $file_ext , $accepted_img ) || in_array( $file_ext , $accepted_vid ) ) {
		$imgfiles[] = $img;
	}
}


// figure out next and previous links
$current_index = array_search(basename($getimage),$imgfiles);

$prev_index = $current_index-1;
    if($prev_index == -1)
    $prev_index = count($imgfiles)-1;

    $next_index = $current_index+1;
    if($next_index == count($imgfiles))
        $next_index = 0;


// figure out current page in the gallery view
	if(!$file_list_view) {
		$img_per_page = $columns * $rows;
	} else {
		$img_per_page = $file_list_files_per_page;
	}



$current_page = ceil(($current_index+1) / $img_per_page);


// create links
if($use_modrewrite=="0") { // normal links

if ($prev_index < $current_index){
    $prev_link = "<a class='navlink' href='" . $currenturl.$get_image. "=" . dirname($getimage) . "/" . $imgfiles[$prev_index] . "'>&laquo; ".$lbl_previous."</a>";
}
if ($next_index > current_index){
    $next_link = "<a class='navlink' href='" . $currenturl.$get_image. "=" . dirname($getimage) . "/" . $imgfiles[$next_index] . "'>".$lbl_next." &raquo;</a>";
}
if (dirname($getimage)!="gallery/images"){
    $back_link = "<a class='navlink' href='" . $currenturl.$get_folder."=" . str_replace($galleryfolder."/","",dirname($getimage));
    if ($current_page > 1) {$back_link .= "&amp;page=".$current_page; }
    $back_link .= "'>".$lbl_back."</a>";
}else{
    $trimmed_url = rtrim($currenturl,"&amp;");
    $back_link = "<a class='navlink' href='" . $trimmed_url."</a>";
}

} else { //mod_rewrite links

if ($prev_index < $current_index){
    $prev_link = "<a class='navlink' href='".$rewrite_base . $get_image. "/" . str_replace($galleryfolder."/","",dirname($getimage)) . "/" . $imgfiles[$prev_index] . "'>&laquo; ".$lbl_previous."</a>";
}
if ($next_index > current_index){
    $next_link = "<a class='navlink' href='".$rewrite_base . $get_image. "/" . str_replace($galleryfolder."/","",dirname($getimage)) . "/" . $imgfiles[$next_index] . "'>".$lbl_next." &raquo;</a>";
}
if (dirname($getimage)!="gallery/images"){
    $back_link = "<a class='navlink' href='".$rewrite_base . $get_folder."/" . str_replace($galleryfolder."/","",dirname($getimage));
    
    $back_link .= "'>".$lbl_back."</a>";
}else{
    $back_link = "<a class='navlink' href='".$rewrite_base."'>".$lbl_back."</a>";
}

}        
        

$next_img_link = str_replace($lbl_next." &raquo;</a>","", str_replace("class='navlink' ","",$next_link ) );


$html = $prev_link . " " . $next_link . " " . $back_link ."";

return $html;

} // end function navlinks







/////////////////////////////////////////////////////////////////////////////////








function GetFileIcon($file) {

	$iconfolder="gallery/theme/DEFAULT_ICONS";
	Global $gallerytheme;
	$themeiconfolder="gallery/theme/".$gallerytheme."/icons";

	if ($_SESSION['defaulticons']==null) { 
		ScanIcons($iconfolder,"default"); 
	}
	if ($_SESSION['themeicons']==null) { 
		if ( file_exists($themeiconfolder) ) {
		ScanIcons($themeiconfolder,"theme"); 
		}
	}	
	
	$default_icon_array = $_SESSION['defaulticons'];
	$theme_icon_array = array();
	$theme_icon_array = $_SESSION['themeicons'];

	$found = false;
	$filetype = explode(".",$file);
	$filetype = strtolower($filetype[1]);

	if (sizeof($theme_icon_array) > 0) {
		foreach($theme_icon_array as $icon) {
			$icontype = explode(".",$icon);
			if($icontype[0] == $filetype) {
				$usethis = $themeiconfolder."/".$icon;
				$found = true;
			}
		}
	}

	if (!$found) {
		foreach($default_icon_array as $icon) {
			$icontype = explode(".",$icon);
			if($icontype[0] == $filetype) {
				$usethis = $iconfolder."/".$icon;
				$found = true;
			}
		}
	}
	
	if (!$found) {
		$usethis = $iconfolder."/default.gif";
	}




return $usethis;
}


function ScanIcons($iconfolder,$type) {

	$folder = dir($iconfolder);
	$icon_array = array();
	while(($file = $folder->read()) !== false) { 
		array_push($icon_array, $file); 
	}

	$_SESSION[$type.'icons'] = $icon_array;
}








/////////////////////////////////////////////////////////////////////////////////








function language_select() {

Global $galleryfilesdir;
$form = "";

$form .='<form name="lang_form" id="lang_form" action="'.$_SERVER["REQUEST_URI"].'" method="POST" style="display: inline; margin: 0;">';
$form .='<select name="lang"  onChange="this.form.submit();">';

$lang_dir = dir($galleryfilesdir."/config/lang");
$lang_array = array();
while(($lang_file = $lang_dir->read()) !== false) { 
	if (strpos($lang_file,".php")) {
		array_push($lang_array, $lang_file); 
	}
}
sort($lang_array);

foreach($lang_array as $lang_option) {
	$lang_option = str_replace(".php","",$lang_option);
	$form .= '<option value="'.$lang_option.'" ';
	if ($_SESSION['lang']==$lang_option) { $form .="selected";}
	$form .= '>'.$lang_option.'</option>';
}


$form .='</select>';
$form .='</form>';

return $form;

} // end language_select




/////////////////////////////////////////////////////////////////////////////////






function RemoveOrderNumber($foldername) {
Global $ordernumber_separator;

if(strpos($foldername,$ordernumber_separator)) {
	$displayfoldername = substr($foldername, (strpos($foldername,$ordernumber_separator)+2));
} else {
	$displayfoldername = $foldername;
}
return $displayfoldername;
}



/////////////////////////////////////////////////////////////////////////////////



function ReplaceScandinavian($string) {

$chars = array();

$chars[] = array("ä","&auml;");
$chars[] = array("Ä","&Auml;");
$chars[] = array("ö","&ouml;");
$chars[] = array("Ö","&Ouml;");
$chars[] = array("å","&aring");
$chars[] = array("Å","&Aring");


return $string;
}


/////////////////////////////////////////////////////////////////////////////////




function print_exif($image) {
	$data = exif_read_data($image, 0, true);
	
	// this prints out the EXIF info table
	// comment out lines you do not want to display by using // in front
		
	$info = "<table><tr><td valign='top'>";
	$info .= "<table class='exif'>";
	$info .= "<tr><td>DateTimeOriginal</td><td>"	.$data[EXIF][DateTimeOriginal]."</td></tr>";	
	$info .= "<tr><td>Make</td><td>"		.$data[IFD0][Make]."</td></tr>";
	$info .= "<tr><td>Model</td><td>"		.$data[IFD0][Model]."</td></tr>";	
	$info .= "<tr><td>FileName</td><td>"		.$data[FILE][FileName]."</td></tr>";
	$info .= "<tr><td>MimeType</td><td>"		.$data[FILE][MimeType]."</td></tr>";	
	$info .= "<tr><td>Flash</td><td>"		.exif_flash($data[EXIF][Flash])."</td></tr>";
	$info .= "<tr><td>LightSource</td><td>"		.exif_LightSource($data[EXIF][LightSource])."</td></tr>";
	$info .= "<tr><td>MeteringMode</td><td>"	.exif_MeteringMode($data[EXIF][MeteringMode])."</td></tr>";
	$info .= "<tr><td>ExposureProgram</td><td>"	.exif_ExposureProgram($data[EXIF][ExposureProgram])."</td></tr>";
	$info .= "</table>";
	$info .= "</td><td valign='top'>";
	$info .= "<table class='exif'>";
	$info .= "<tr><td>ExposureTime</td><td>"	.$data[EXIF][ExposureTime]."</td></tr>";
	$info .= "<tr><td>ApertureFNumber</td><td>"	.$data[COMPUTED][ApertureFNumber]."</td></tr>";
	$info .= "<tr><td>FNumber</td><td>"		.$data[EXIF][FNumber]."</td></tr>";
	$info .= "<tr><td>MaxApertureValue</td><td>"	.$data[EXIF][MaxApertureValue]."</td></tr>";
	$info .= "<tr><td>ExposureBiasValue</td><td>"	.$data[EXIF][ExposureBiasValue]."</td></tr>";
	$info .= "<tr><td>FocalLength</td><td>"		.$data[EXIF][FocalLength]."</td></tr>";
	$info .= "<tr><td>ISOSpeedRatings</td><td>"	.$data[EXIF][ISOSpeedRatings]."</td></tr>";
	$info .= "<tr><td>ShutterSpeedValue</td><td>"	.$data[EXIF][ShutterSpeedValue]."</td></tr>";
	$info .= "<tr><td>ApertureValue</td><td>"	.$data[EXIF][ApertureValue]."</td></tr>";
	$info .= "<tr><td>BrightnessValue</td><td>"	.$data[EXIF][BrightnessValue]."</td></tr>";
	$info .= "</table>";
	$info .= "</td></tr></table>";

	return $info;
}



function exif_flash($tag) {
	if($tag==0) $tag 	= "No Flash";
	else if($tag==1) $tag 	= "Flash";
	else if($tag==5) $tag 	= "Flash, strobe return light not detected";
	else if($tag==7) $tag 	= "Flash, strob return light detected";
	else if($tag==9) $tag 	= "Compulsory Flash";
	else if($tag==13) $tag 	= "Compulsory Flash, Return light not detected";
	else if($tag==15) $tag 	= "Compulsory Flash, Return light detected";
	else if($tag==16) $tag 	= "No Flash";
	else if($tag==24) $tag 	= "No Flash";
	else if($tag==25) $tag 	= "Flash, Auto-Mode";
	else if($tag==29) $tag 	= "Flash, Auto-Mode, Return light not detected";
	else if($tag==31) $tag 	= "Flash, Auto-Mode, Return light detected";
	else if($tag==32) $tag 	= "No Flash";
	else if($tag==65) $tag 	= "Red Eye";
	else if($tag==69) $tag 	= "Red Eye, Return light not detected";
	else if($tag==71) $tag 	= "Red Eye, Return light detected";
	else if($tag==73) $tag 	= "Red Eye, Compulsory Flash";
	else if($tag==77) $tag 	= "Red Eye, Compulsory Flash, Return light not detected";
	else if($tag==79) $tag 	= "Red Eye, Compulsory Flash, Return light detected";
	else if($tag==89) $tag 	= "Red Eye, Auto-Mode";
	else if($tag==93) $tag 	= "Red Eye, Auto-Mode, Return light not detected";
	else if($tag==95) $tag 	= "Red Eye, Auto-Mode, Return light detected";
	else $tag 		= "Unknown: ".$tag;

	return $tag;
}

function exif_LightSource($tag) {
	if($tag==0) $tag 	= "Unknown or Auto";
	else if($tag==1) $tag 	= "Daylight";
	else if($tag==2) $tag 	= "Flourescent";
	else if($tag==3) $tag 	= "Tungsten";
	else if($tag==10) $tag 	= "Flash";
	else if($tag==17) $tag 	= "Standard Light A";
	else if($tag==18) $tag 	= "Standard Light B";
	else if($tag==19) $tag 	= "Standard Light C";
	else if($tag==20) $tag 	= "D55";
	else if($tag==21) $tag 	= "D65";
	else if($tag==22) $tag 	= "D75";
	else if($tag==255) $tag = "Other";
	else $tag 		= "Unknown: ".$tag;

	return $tag;
}


function exif_MeteringMode($tag) {
	if($tag==0) $tag 	= "Unknown";
	else if($tag==1) $tag 	= "Average";
	else if($tag==2) $tag 	= "Center Weighted Average";
	else if($tag==3) $tag 	= "Spot";
	else if($tag==4) $tag 	= "Multi-Spot";
	else if($tag==5) $tag 	= "Multi-Segment";
	else if($tag==6) $tag 	= "Partial";
	else if($tag==255) $tag = "Other";
	else $tag 		= "Unknown: ".$tag;

	return $tag;
}


function exif_ExposureProgram($tag) {
	if($tag==1) $tag 	= "Manual";
	else if($tag==2) $tag 	= "Program";
	else if($tag==3) $tag 	= "Aperature Priority";
	else if($tag==4) $tag 	= "Shutter Priority";
	else if($tag==5) $tag 	= "Program Creative";
	else if($tag==6) $tag 	= "Program Action";
	else if($tag==7) $tag 	= "Portrat";
	else if($tag==8) $tag 	= "Landscape";
	else $tag 		= "Unknown: ".$tag;

	return $tag;
}


/////////////////////////////////////////////////////////////////////////////////

?>