<?php

// if user logged in
if (isset($_SESSION['username'])) {




if (isset($_POST['Submit'])) {    // if the form has been submitted


$generate_small=($_POST['thumb'])?"Yes":"No";
$generate_large=($_POST['large'])?"Yes":"No";

$html .= "<b>Selected CACHE images generated in the following folders:</b><br /><br />";
$html .= generate_th($galleryfolder);

} else {  // if the form has not yet been submitted


$html .= '
This operation may take a long time depending on the amount of images you have and the speed of your server - <b>please be patient and wait for the page to load</b>...<br><br>

<br />
<form method="post" action="?galleryadmin=generate_th">
<input type="checkbox" name="thumb" checked="checked"> Generate Small Thumbnails<br />
<input type="checkbox" name="large" checked="checked"> Generate Large images<br />
<br />
<input type="submit" name="Submit" value="Generate CACHE files">
</form>
<br /> Thumbnails will be displayed as they are generated. All cache generated when page is loaded.
<br /> Large images will not display. If you are generating only large images, please wait for the page to load completely.
';


}





 } else {
$html .=('Sorry, you are not logged in, this area is restricted to admin.');
$html .= "<br><input type=button class='button' value='Back' onClick='history.go(-1)'>";
}












function generate_th($path) {

Global $folder_array;
Global $getfolder;
Global $get_folder;
Global $galleryfolder;
Global $galleryfilesdir;
Global $generate_small;
Global $generate_large;
Global $gallerytheme;

$print = ""; 
				$gallerysub_dir = dir($path);
				$subfolder_array = array();
					while(($subfoldername = $gallerysub_dir->read()) !== false) { 
						if ($subfoldername=="." || $subfoldername=="..") {} else {
							if (is_dir($path."/".$subfoldername)) {	
							array_push($subfolder_array, $subfoldername); 
							} else {
								$image = $path."/".$subfoldername;
								if ($image=="." || $image==".." || strpos($image,"Thumbs.db") || strpos($image,".php") || strpos($image,".txt") || strpos($image,".DS_Store")) {} else {
								
									// find out image height and width for folder or use default
									
									include("../theme/".$gallerytheme."/config.php");
									
									if(file_exists($path."/galleryconfig.php")) {
									include($path."/galleryconfig.php");
									} else {
									include("../config/galleryconfig.php");
									}

									
																	
								
								
									// generate Thumbnails


									if($generate_large=="Yes") {
	
									$print .= "<img style='display:none;' border='0' src='";
									$print .= "../thumb.php?source=".str_replace("../","",$image)."&height=".$maxlargeheight."&width=".$maxlargewidth."&large=true";
									if($watermark_large > "") {
									$print .= "&watermark_img=".$watermark_large."&watermark_opacity=".$watermark_opacity."&watermark_margin_large=".$watermark_margin_large."&watermark_mode=".$watermark_mode."&watermark_position=".$watermark_position;
									}
									if($copyright_text_large) {
									$print .= "&copyright_text=".$copyright_text."&copyright_size=".$copyright_size_large."&copyright_font=".$copyright_font."&copyright_angle=".$copyright_angle."&copyright_margin=".$copyright_margin_large."&copyright_position=".$copyright_position."&copyright_color=".$copyright_color."&copyright_shadow_distance=".$copyright_shadow_distance."&copyright_shadow_color=".$copyright_shadow_color;
									}
									$print .="' alt='' />";


									}
									
									
									if($generate_small=="Yes") {

									$print .= "<img class='galleryimage' border='0' src='";
									$print .= "../thumb.php?source=".str_replace("../","",$image)."&height=".$maxthumbheight."&width=".$maxthumbwidth;
									if($watermark_thumb > "") {
									$print .= "&watermark_img=".$watermark_thumb."&watermark_opacity=".$watermark_opacity."&watermark_margin_thumb=".$watermark_margin_thumb."&watermark_mode=".$watermark_mode."&watermark_position=".$watermark_position;
									}
									if($copyright_text_thumb) {
									$print .= "&copyright_text=".$copyright_text."&copyright_size=".$copyright_size_thumb."&copyright_font=".$copyright_font."&copyright_angle=".$copyright_angle."&copyright_margin=".$copyright_margin_thumb."&copyright_position=".$copyright_position."&copyright_color=".$copyright_color;
									}
									$print .= "' alt='' height='".$thumbheight."' width='".$thumbwidth."' /></a>";


									}
									
									
									

								}
							}
						}
					}
					if (count($subfolder_array)>0) {
						$print .= "";
						foreach($subfolder_array as $subsubfolder) {

							$print .= "<br /><br /><b>".$subsubfolder."</b><br />";

							
								$newpath="";
								$path_folders = explode('/',$path);
								for ($i=0;$i<sizeof($path_folders);$i++) {
								$newpath .= $path_folders[$i]."/";
								}
								$newpath .= $subsubfolder;
								if (is_dir($newpath)) {
									$print .= generate_th($newpath);
								}
							
					
						}
							
					
					
					
					}

return $print;

} // end function





?>