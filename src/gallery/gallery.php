<?php
//////////////////////////////////////////////////////////////////////////////////////
////////  Folder GALLERY Script - Joonas Viljanen 2006 - jv2 at jv2 dot net  /////////
////////                                       http://foldergallery.jv2.net  /////////
//////////////////////////////////////////////////////////////////////////////////////
/*
This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike License. 

You are free:
    * to copy, distribute, display, and perform the work
    * to make derivative works

Under the following conditions:
    * Attribution. You must give the original author credit.
    * Noncommercial. You may not use this work for commercial purposes.
    * Share Alike. If you alter, transform, or build upon this work, you may distribute the 
      resulting work only under a license identical to this one.

    * For any reuse or distribution, you must make clear to others the license terms of this work.
    * Any of these conditions can be waived if you get permission from the author.

*/
//////////////////////////////////////////////////////////////////////////////////////
// Please read the FAQ for info...
// Galleries must be stored as subdirectories in the images subdirectory
// The gallery is able to handle multiple levels of dirs
// The name of that subdirectory can be set by variable "$galleryfolder" in gallerysetup
// - change this only if you want to change the name of the folder where you hold the files.
// You can set all the config in "gallerysetup.php" and "galleryconfig.php"
//////////////////////////////////////////////////////////////////////////////////////

$gallery_version = "v 3.1.1";

//////////////////////////////////////////////////////////////////////////////////////
// Do NOT edit below unless you know what you are doing...

Global $galleryfilesdir;
Global $use_modrewrite;
Global $getimage;
Global $gallerytheme;
$gallery_cellspacing = "2"; // can be overidden by theme config

$includemode = false;
define('_VALID_gallery_INCLUDE', TRUE);

// CLEAN GET VARS
foreach($_GET as $var => $val) {
	$_GET[$var] = htmlspecialchars(strip_tags($val));
}


////////  MAIN CONFIG  ///////////////////////////////////////////////////////////////
require ("config/gallerysetup.php");
require ("config/galleryconfig.php");
require ("config/file_handling.php");
//////////////////////////////////////////////////////////////////////////////////////

////////  THEME CONFIG  ///////////////////////////////////////////////////////////////
require ("theme/".$gallerytheme."/config.php");
//////////////////////////////////////////////////////////////////////////////////////


// Turn off all error reporting according to setting
error_reporting($debug_mode);


///////////////////////////////////////////////////////////////////////


if ($includemode == false) { session_start(); }


require ("include/galleryfunctions.php");
// By default all gallery files are in gallery subdir
$galleryfilesdir = "gallery";
$galleryfolder = $galleryfilesdir."/".$galleryfolder;
$gallery_code = "";


// keep track of current url... and fix it for IIS
if($use_modrewrite=="0") {
	require ("include/url_fix.php");
} 


// GET variables

$gallerypage = 1;  // get current page number
if (isset($_GET[$get_page])) {	  $gallerypage = (get_magic_quotes_gpc()) ? $_GET[$get_page] : addslashes($_GET[$get_page]);	}

$getfolder = ""; // set folder to be opened
if (isset($_GET[$get_folder])) {  $getfolder = (get_magic_quotes_gpc()) ? $_GET[$get_folder] : addslashes($_GET[$get_folder]);	}

$getimage = ""; // get current image
if (isset($_GET[$get_image])) {	  $getimage = (get_magic_quotes_gpc()) ? $_GET[$get_image] : addslashes($_GET[$get_image]);	}

$getslide = ""; // get folder for slideshow
if (isset($_GET[$get_slideshow])) { $getslide = (get_magic_quotes_gpc()) ? $_GET[$get_slideshow] : addslashes($_GET[$get_slideshow]); }

if ($_SESSION['lang']==null) { $_SESSION['lang'] = $language ; }
if (isset($_POST['lang'])) { $_SESSION['lang'] = (get_magic_quotes_gpc()) ? $_POST['lang'] : addslashes($_POST['lang']); }


// Check passed location - refuse attempt to open parent folders
$folder_array_check = explode("/",$getfolder);
if( in_array("..",$folder_array_check) ) { die("\"..\" is not allowed in the url."); }
$folder_array_check = explode("/",$getimage);
if( in_array("..",$folder_array_check) ) { die("\"..\" is not allowed in the url."); }

///////////////////////////////////////////////////////////////////////





////////  LANGUAGE  ///////////////////////////////////////////////////
$lang_file = $galleryfilesdir."/config/lang/".$_SESSION['lang'].".php";
if (file_exists($lang_file)) {
include ($lang_file);
} else {
include ($galleryfilesdir."/config/lang/english.php");
}
///////////////////////////////////////////////////////////////////////







// if individual gallery config exists use that
if ($getimage != "") {
$galleryconfig = dirname($getimage)."/galleryconfig.php";
} else {
$galleryconfig = $galleryfolder."/".$getfolder."/galleryconfig.php";
}
if (file_exists($galleryconfig)) {
include ($galleryconfig);
}





// include breadcrumbs code generation
if ($displaybreadcrumbs == "1") {
	if($use_modrewrite=="0") {
	include ("include/breadcrumbs.php");
	} else {
	include ("include/breadcrumbs_modrewrite.php");
	}
}




// if requesting display page
if ($getimage != "") {

	$path_parts = pathinfo($getimage);
	$file_ext = strtolower($path_parts['extension']);

	// viewing image file
	if( in_array( $file_ext , $accepted_img ) ) { // is image 
		$gallery_code .= navlinks();
		include ("include/view_large_img.php");
	}






	// viewing video file
	if( in_array( $file_ext , $accepted_vid ) )  // is video file
	{
		$gallery_code .= navlinks();
		include ("include/view_video.php");
		if( in_array( $file_ext , $html5_vid ) ) 
		{
		include ("include/HTML5.php");
		} 
	}






	// displaying comments from text file

	$commentsfile = getCommentsFileName($getimage);

	if (file_exists($commentsfile)) {
		$comments = getCommentsText($commentsfile);
		$gallery_code .= "<div class='comments'>";
		$gallery_code .= nl2br($comments);
		$gallery_code .= "</div>";
	}



	// if printing EXIF information
	if ($print_exif_info == 1  && in_array( $file_ext , $accepted_img ) ) {
	$gallery_code .= print_exif($getimage);
	} // end EXIF printing


///////////////////////////////////////////////////////////////////////

// if requesting slideshow
} else if ($getslide != "") {

include ("include/slideshow.php");

///////////////////////////////////////////////////////////////////////

} else { // displaying gallery

///////////////////////////////////////////////////////////////////////




	// include javascript for folder description tooltip
	if ($tooltip_folder_descr || $tooltip_image_descr) {
	include ("include/js_tooltip.php");
	}


	// build gallery table
	$gallery_code .= '<table id= "gallery" border="0" cellspacing="'.$gallery_cellspacing.'" ';
	if ($gallerywidth !== "default") {
	$gallery_code .= 'width="'.$gallerywidth.'"';}
	$gallery_code .= '>';


			// print header row for file list table
			if($file_list_view && $file_list_headings) {
				$gallery_code .= '<tr>';
				$gallery_code .= "<td class='filetitle' style='padding-right:20px;'>&nbsp;</td>";
				$gallery_code .= "<td class='filetitle' style='padding-right:20px;'>Name</td>";
				$gallery_code .= "<td class='filetitle' style='padding-right:20px;'>Links</td>";
				if($file_list_file_size) { 
					$gallery_code .= "<td class='filetitle' style='padding-right:20px;'>Size</td>";
				}
				if($file_list_file_type) { 
					$gallery_code .= "<td class='filetitle' style='padding-right:10px;'>Type</td>";
				}
				if($file_list_file_date) { 
					$gallery_code .= "<td class='filetitle'>Date Modified</td>";
				}
				$gallery_code .= '</tr>';
			}



	$gallery_code .= '<tr>';


	$folder = $galleryfolder."/".$getfolder;
	$galleryimg_dir = dir($folder);

	
	$images_displayed = 0;

	// read images in selected sub folder
	$img_array = array();
	$folder_array = array();
	while(($file = $galleryimg_dir->read()) !== false) { 

		$file_ext = explode(".",$file);
		$file_ext = strtolower($file_ext[1]);
		
		if (is_dir($folder."/".$file) && $file!="." && $file!="..") {
			$folder_array[] = $file;
		} else {
			if( in_array( $file_ext , $ignore_files )  || $file=="." || $file==".."  || $file==$defaultfolderimage ) { // ignore files
			} else {
			$img_array[] = $file; // else include
			}
		}
	}


if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	// sort the files
	if ($filesort == "desc") { 
		krsort($folder_array); 
		krsort($img_array); 
	} else { 
		ksort($folder_array); 
		ksort($img_array); 
	}
} else {
	// sort the files
	if ($filesort == "desc") { 
		rsort($folder_array); 
		rsort($img_array); 
	} else { 
		sort($folder_array); 
		sort($img_array); 
	}
}

//$img_array = array_reverse($img_array);

	$img_array = array_merge ($folder_array, $img_array);


	// figure out which images to display
	if(!$file_list_view) {
		$gallerypageimages = $columns * $rows;
	} else {
		$gallerypageimages = $file_list_files_per_page;
	}
	
	
	$start = ($gallerypage * $gallerypageimages) - $gallerypageimages;
	$end = $start + $gallerypageimages;
	$i=1;
	$x=1;

	if($file_list_view) {
	$displayimgname = "1";
	}




//////////////////////////////////////////////////////////

	
	// loop through the image array
	foreach($img_array as $image) {
		
		if ($i > $start && $i <= $end) {


			
			// FOLDERS
			// if list item is a subdirectory - displaying folder link
			if (is_dir($folder."/".$image)) {
			

				if(!$file_list_view) {
					$gallery_code .= "<td height='100%' align='center' valign='".$table_valign."' class='table_cell_folder'>";
				} else {
					$gallery_code .= "<td>";
				}			
			
			
			
				$subfolder = $folder."/".$image;


				// calculate image and subfolder counts for display underneath the folder
				if(!$file_list_view) {
				if ($display_img_count=="1" || $display_sub_count=="1") {
					$gallerysub_dir = dir($subfolder);
					$num_imgs=0;
					$num_subs=0;
					while (($count=$gallerysub_dir->read() ) !== false ) {
						$count_ext = pathinfo($count);
						$count_ext = strtolower($count_ext['extension']);

						if( in_array($count_ext,$ignore_files)  || $count=="." || $count==".." || $count==$defaultfolderimage || is_dir($folder."/".$image."/".$count)   ) { //ignore files
						} else { $num_imgs++; // else count
						}				
						if(is_dir($folder."/".$image."/".$count) && $count!=="." && $count!=="..") {
						$num_subs++;
						}						
					}					
				}
				}


				// get description from text file if it exists
				$description="";
				$langfile = $folder."/".$image."/folder_".$_SESSION['lang'].".txt";
				if( file_exists($langfile) ) {
					$descr_file = $langfile;
				} else {
					$descr_file = $folder."/".$image."/folder.txt";
				}		

				if (file_exists($descr_file)) {
					$description = getCommentsText($descr_file,true);
				}			
			
			
				




				$gallery_code .= "<a ";
				
				if ($tooltip_folder_descr && $description > "") {
				$gallery_code .=  "onmousemove=\"javascript:toolTip( this, '".str_replace("_"," ", RemoveOrderNumber($image) )."', '".$description."' );\"	onmouseout=\"javascript: removeTip( this );\" ";
				}

				$gallery_code .= "href='";
				if($use_modrewrite=="0") {
					$gallery_code .= $currenturl.$get_folder."=";
				} else {
					$gallery_code .= $rewrite_base.$get_folder."/";
				}

				if (isset($_GET[$get_folder])) {$gallery_code .= $getfolder.'/';}
				$gallery_code .= $image."'>";				
				
				
				// if there is defaultfolder icon image in the folder use that
				if (file_exists($subfolder."/".$defaultfolderimage)) {
					$displayimg = $subfolder."/".$defaultfolderimage;
				
					$gallery_code .= "<img class='galleryfolder' border='0' src='";
					if($use_modrewrite=="0") {} else { $gallery_code .= "/"; }
					$gallery_code .= $subfolder."/".$defaultfolderimage;
					$gallery_code .= "' alt=''  />";
				
				
				} else { // defaultfolder icon not set
				
					// if option set to to display image thumbnail for folders
					if (!$file_list_view) { // if using file list view
						if ($folderthumbnail) { // if generating thumbnails

							// if default image for folder is set
							if (file_exists($subfolder."/defaultimg.txt")) {
								$folderimage = ".jpg";
								$imagefile = file($subfolder."/defaultimg.txt");
								$displayimg = $subfolder."/".rtrim($imagefile[0]);
							} else {
								// get first image in folder to use as thumbnail for folder if possible
								$gallerysub_dir = dir($subfolder);
								while ($folderimage = $gallerysub_dir->read()) {
									if(  preg_match( "/^\w+[\+\.\w-\s+]*\.(gif|jpg|png)$/i", trim( $folderimage ) )    ) {break;}					
								}
								$displayimg=$folder."/".$image."/".$folderimage;	
							}

							// default icon
							if(!eregi(".*(\.jpg|\.gif|\.png|\.jpeg)", $folderimage)) {
								$gallery_code .= "<img class='galleryfolder' border='0' src='";
								if($use_modrewrite=="0") {} else { $gallery_code .= "/"; }

								// use folder image from theme/icons if exists - else use one in DEFAULT_ICONS
								if ( file_exists($galleryfilesdir."/theme/".$gallerytheme."/icons/".$defaultfolderimage) ) {
								$gallery_code .= $galleryfilesdir."/theme/".$gallerytheme."/icons/".$defaultfolderimage;
								} else {
								$gallery_code .= $galleryfilesdir."/theme/DEFAULT_ICONS/".$defaultfolderimage;
								}
								$gallery_code .= "' alt=''  />";


							} else { // generate thumbnail

								$size = getimagesize ($displayimg);
								$xratio = $maxthumbwidth/$size[0];
								$yratio = $maxthumbheight/$size[1];
									if($xratio < $yratio) {
										$thumbwidth = $maxthumbwidth;
										$thumbheight = floor($size[1]*$xratio);
									} else {
										$thumbheight = $maxthumbheight;
										$thumbwidth = floor($size[0]*$yratio);
									}


								$modifed = filemtime($displayimg);
								$filesize = filesize($displayimg);
								$imgpath = str_replace("//","/",substr($displayimg,(strlen($galleryfilesdir)+1))  );
								$hash = md5($imgpath.$size[0].$size[1].$modifed.$filesize);
								$cacheimagename = $galleryfilesdir."/".$cachefolder."/thumb_".$hash.".jpg";

								if (file_exists($cacheimagename)) {
									$gallery_code .= "<img class='galleryimage' border='0' src='";
									if($use_modrewrite=="0") {} else { $gallery_code .= "/"; }
									$gallery_code .= $cacheimagename."' alt='' height='".$thumbheight."' width='".$thumbwidth."' />";
								} else {
									$gallery_code .= "<img class='galleryimage' border='0' src='";
									if($use_modrewrite=="0") {} else { $gallery_code .= "/"; }
									$gallery_code .= $galleryfilesdir."/thumb.php?source=".str_replace("//","/",str_replace($galleryfilesdir."/","",$displayimg))."&amp;height=".$maxthumbheight."&amp;width=".$maxthumbwidth;
									if($watermark_thumb > "") {
									$gallery_code .= "&watermark_img=".$watermark_thumb."&watermark_opacity=".$watermark_opacity."&watermark_margin_thumb=".$watermark_margin_thumb."&watermark_mode=".$watermark_mode."&watermark_position=".$watermark_position;
									}
									if($copyright_text_thumb) {
									$gallery_code .= "&copyright_text=".$copyright_text."&copyright_size=".$copyright_size_thumb."&copyright_font=".$copyright_font."&copyright_angle=".$copyright_angle."&copyright_margin=".$copyright_margin_thumb."&copyright_position=".$copyright_position."&copyright_color=".$copyright_color;
									}
									$gallery_code .= "' alt='' height='".$thumbheight."' width='".$thumbwidth."' />";
								}	


							} // end if else for default icon or generated thumbnail



							// always displaying folder icon instead of thumbnail image on folders
						} else {
							$gallery_code .= "<img class='galleryfolder' border='0' src='";
							if($use_modrewrite=="0") {} else { $gallery_code .= "/"; }

							// use folder image from theme/icons if exists - else use one in DEFAULT_ICONS
							if ( file_exists($galleryfilesdir."/theme/".$gallerytheme."/icons/".$defaultfolderimage) ) {
							$gallery_code .= $galleryfilesdir."/theme/".$gallerytheme."/icons/".$defaultfolderimage;
							} else {
							$gallery_code .= $galleryfilesdir."/theme/DEFAULT_ICONS/".$defaultfolderimage;
							}
							$gallery_code .= "' alt=''  />";
							
						} // end if else block for generating thumbnail


					// display folder icons in file list view				
					} else {

						$gallery_code .= "<img border='0' src='";
						if($use_modrewrite=="0") {} else { $gallery_code .= "/"; }

						// use folder image from theme/icons if exists - else use one in DEFAULT_ICONS
						if ( file_exists($galleryfilesdir."/theme/".$gallerytheme."/icons/".$defaultfolderimage) ) {
						$gallery_code .= $galleryfilesdir."/theme/".$gallerytheme."/icons/".$defaultfolderimage;
						} else {
						$gallery_code .= $galleryfilesdir."/theme/DEFAULT_ICONS/".$defaultfolderimage;
						}
						$gallery_code .= "' alt='' width='".$file_list_file_icon_height."' />";

					} // end if else block for file list view
				
				
				} // end if else block for defaultfolder icon set
				
				
				$gallery_code .= "</a>";
				
				if ($file_list_view) {
				$gallery_code .= "</td>";
				}

				
				
				// display the folder name

				$displayfoldername = RemoveOrderNumber($image);
				
				$langfile = $folder."/".$image."/".$_SESSION['lang'].".txt";
				if( file_exists($langfile) ) {
						$fp = fopen($langfile, "r");
						$displayfoldername = fread($fp, filesize($langfile));
						fclose($fp);
				}
				
				
				$displayfoldername = str_replace("_"," ", $displayfoldername);
				
				// print folder name
				if(!$file_list_view) { 
					$gallery_code .= "<br /><span class='imagetitle'>".$displayfoldername;
				
					// show image and subfolder counts underneath folder
				
					if ($display_img_count=="1" && $num_imgs>0) {
					$gallery_code .= " (".$num_imgs.")";
					}
					if ($display_sub_count=="1" && $num_subs>0) {
					$gallery_code .= "<br />(".$num_subs." ".$lbl_album.")";
					}
								
					$gallery_code .= "</span>";


					// displaying description for folder from text file

					if ($description > "" && !$tooltip_folder_descr) {
						$gallery_code .= "<div style='width:".$maxthumbwidth."px;' class='folder_description'>";
						$gallery_code .= nl2br($description);
						$gallery_code .= "</div>";
					}




					$gallery_code .= "";
				
				} else {
				$gallery_code .= "<td class='filelist' style='padding-right:20px;'>".$displayfoldername."</td>";
				}				
				
				
				// additional table cells for info in list view
				if ($file_list_view) {
					$gallery_code .= "<td style='padding-right:20px;'><span class='filelist'><a href='";
					if($use_modrewrite=="0") {
						$gallery_code .= $currenturl.$get_folder."=";
					} else {
						$gallery_code .= $rewrite_base.$get_folder."/";
					}
					if (isset($_GET[$get_folder])) {$gallery_code .= $getfolder.'/';}
					$gallery_code .= $image."'>".$lbl_view."</a></span>";	

					$gallery_code .= "</td>";
				
					if($file_list_file_size) { 
						$gallery_code .= "<td class='filelist'>&nbsp;</td>";
					}
					if($file_list_file_type) { 
						$gallery_code .= "<td class='filelist' style='padding-right:10px;'>&nbsp;</td>";
					}
					if($file_list_file_date) { 
						$gallery_code .= "<td class='filelist' style='padding-right:20px; text-align:right;'>";							
						$gallery_code .= date ("d-m-Y H:i:s", filemtime($folder."/".$image) );
						$gallery_code .= "</td>";
					}
					
					
					
				}
				


///////////////////////////////////////////////////////////////////////////////////////////////





			// IMAGE
			// else displaying a normal image
			} else if(eregi(".*(\.jpg|\.gif|\.png|\.jpeg)", $image)){ 
			
		

				if(!$file_list_view) {
					$gallery_code .= "<td height='100%'  align='center' valign='".$table_valign."' class='table_cell_img'>";
				} else {
					$gallery_code .= "<td>";
				}



				$image_descr = "";
				
				if ($tooltip_image_descr) {
					$imagename = getImageName($folder,$image);
					$commentsfile = getCommentsFileName($folder."/".$image);
					if (file_exists($commentsfile)) {
						$comments = getCommentsText($commentsfile,true);
						$image_descr .= $comments;
					}			
			
			
				}
				
				
				if ($tooltip_image_descr && $image_descr > "") {
					$image_descr =  " onmousemove=\"javascript:toolTip( this, '".$imagename."', '".$image_descr."' );\" onmouseout=\"javascript: removeTip( this );\" ";
				}


			
				$size = getimagesize ($folder."/".$image);
				$path = str_replace($galleryfolder."/","",$folder)."/";
				

				if ($popupimage == "2") {
					$gallery_code .= "<a ".$image_descr." href='#' onclick=\"window.open('";
					if($use_modrewrite=="0") {} else { $gallery_code .= $rewrite_base; }
					$gallery_code .= $galleryfilesdir."/popup.php?img=".str_replace($galleryfilesdir."/","",$folder)."/".$image."&amp;w=$size[0]&amp;h=$size[1]&amp;t=$image','$x','width=$size[0],height=$size[1],directories=no,location=no,menubar=no,scrollbars=";
                    			if ($popupscrollbars==1) { $gallery_code .= "yes"; } else { $gallery_code .= "no"; }
                    			$gallery_code .= ",status=no,toolbar=no,resizable=no');return false\" target=\"_blank\">";
				} else if ($popupimage == "6") {
					$gallery_code .= "<a ".$image_descr." href='#' onclick=\"window.open('";
					if($use_modrewrite=="0") {} else { $gallery_code .= $rewrite_base; }
					$gallery_code .= $galleryfilesdir."/popup_slideshow.php?img=".str_replace($galleryfilesdir."/","",$folder)."/".$image."&amp;w=$size[0]&amp;h=$size[1]&amp;t=$image&amp;large=true','$x','width=50,height=50,directories=no,location=no,menubar=no,scrollbars=";
                    			if ($popupscrollbars==1) { $gallery_code .= "yes"; } else { $gallery_code .= "no"; }
                    			$gallery_code .= ",status=no,toolbar=no,resizable=yes');return false\" target=\"_blank\">"; 			

				} else if ($popupimage == "5") {
					$gallery_code .= "<a ".$image_descr." href='#' onclick=\"window.open('";
					if($use_modrewrite=="0") {} else { $gallery_code .= $rewrite_base; }
					$gallery_code .= $galleryfilesdir."/popup_slideshow.php?img=".str_replace($galleryfilesdir."/","",$folder)."/".$image."&amp;w=$size[0]&amp;h=$size[1]&amp;t=$image','$x','width=50,height=50,directories=no,location=no,menubar=no,scrollbars=";
                    			if ($popupscrollbars==1) { $gallery_code .= "yes"; } else { $gallery_code .= "no"; }
                    			$gallery_code .= ",status=no,toolbar=no,resizable=yes');return false\" target=\"_blank\">"; 			
				
				} else if ($popupimage == "4") { // Highslide
				
					$gallery_code .=  '<a '.$image_descr.' href="';
					if($use_modrewrite=="1") { $gallery_code .=  "/"; }
					$gallery_code .=  $folder.'/'.$image.'" class="highslide" onclick="return hs.expand(this)">';
				
				} else if ($popupimage == "7") { // Lightbox
					$imagetitle = "";
					if ($displayimgname == true) {
						$imagetitle .= getImageName($folder,$image);
					}
					$commentsfile = getCommentsFileName($folder."/".$image);
					if (file_exists($commentsfile)) {
						if (!empty($imagetitle)) {
							$imagetitle .= " - ";
						}
						$comments = getCommentsText($commentsfile,true);
						$imagetitle .= $comments;
					}
					
					$gallery_code .=  '<a '.$image_descr.' href="';
					if($use_modrewrite=="1") { $gallery_code .=  "/"; }
					$gallery_code .=  $folder.'/'.$image.'" rel="lightbox[jv2gallery]" title="'.$imagetitle.'">';
				
				} else {
					
					if($use_modrewrite=="0") {
					$imageurl = $currenturl.$get_image;
					$gallery_code .= "<a ".$image_descr." href='".$imageurl."=".$path.$image."'>";
					} else {
						if($folder==$galleryfolder."/") {$path="";} 
					$gallery_code .= "<a ".$image_descr." href='".$rewrite_base.$get_image."/".$path.$image."'>";
					}	
				}



				if(!$file_list_view) {


					$xratio = $maxthumbwidth/$size[0];
					$yratio = $maxthumbheight/$size[1];
					if($xratio < $yratio) {
						$thumbwidth = $maxthumbwidth;
						$thumbheight = floor($size[1]*$xratio);
					} else {
						$thumbheight = $maxthumbheight;
						$thumbwidth = floor($size[0]*$yratio);
					}

					$modifed = filemtime($folder."/".$image);
					$filesize = filesize($folder."/".$image);
					$imgpath = str_replace("//","/",substr($folder."/".$image,(strlen($galleryfilesdir)+1))  );
					$hash = md5($imgpath.$size[0].$size[1].$modifed.$filesize);
					$cacheimagename = $galleryfilesdir."/".$cachefolder."/thumb_".$hash.".jpg";

					if (file_exists($cacheimagename)) {
					$gallery_code .= "<img class='galleryimage' border='0' src='";
						if($use_modrewrite=="0") {} else { $gallery_code .= "/"; }
						$gallery_code .= $cacheimagename."' alt='' height='".$thumbheight."' width='".$thumbwidth."' />";
					} else {

					$gallery_code .= "<img class='galleryimage' border='0' src='";
						if($use_modrewrite=="0") {} else { $gallery_code .= "/"; }
						$gallery_code .= $galleryfilesdir."/thumb.php?source=".str_replace($galleryfilesdir."/","",$folder)."/".$image."&amp;height=".$maxthumbheight."&amp;width=".$maxthumbwidth;
					if($watermark_thumb > "") {
					$gallery_code .= "&watermark_img=".$watermark_thumb."&watermark_opacity=".$watermark_opacity."&watermark_margin_thumb=".$watermark_margin_thumb."&watermark_mode=".$watermark_mode."&watermark_position=".$watermark_position;
					}
					if($copyright_text_thumb) {
					$gallery_code .= "&copyright_text=".$copyright_text."&copyright_size=".$copyright_size_thumb."&copyright_font=".$copyright_font."&copyright_angle=".$copyright_angle."&copyright_margin=".$copyright_margin_thumb."&copyright_position=".$copyright_position."&copyright_color=".$copyright_color;
					}
					$gallery_code .= "' alt='' height='".$thumbheight."' width='".$thumbwidth."' />";

					}



				} else {
					
						$gallery_code .= "<img border='0' src='";
						if($use_modrewrite=="1") { $gallery_code .=  "/"; }
						$gallery_code .= GetFileIcon($image);
						if(!$file_list_view) { 
						$gallery_code .= "'>";
						} else {
						$gallery_code .= "' height='".$file_list_file_icon_height."'>";
						}			
					
				}

				$gallery_code .= "</a>";
				
				// display image title
				if ($displayimgname == true) {
					
					$imagename = getImageName($folder,$image);
					
					if(!$file_list_view) { 
					$gallery_code .= "<br /><span class='imagetitle'>".$imagename."</span>";
					} else {
					$gallery_code .= "<td class='filelist' style='padding-right:20px;'>".$imagename."</td>";
					}
				}



				// display table cells for file list view
				if($file_list_view) { 

						$gallery_code .= "<td style='padding-right:20px;'><span class='filelist'>";

						$path = str_replace($galleryfolder."/","",$folder)."/";
						if ($popupimage == "2") {
							$gallery_code .= "<a href='#' onclick=\"window.open('";
							if($use_modrewrite=="0") {} else { $gallery_code .= $rewrite_base; }
							$gallery_code .= $galleryfilesdir."/popup.php?img=".str_replace($galleryfilesdir."/","",$folder)."/".$image."&amp;w=$size[0]&amp;h=$size[1]&amp;t=$image','$x','width=$size[0],height=$size[1],directories=no,location=no,menubar=no,scrollbars=";
							if ($popupscrollbars==1) { $gallery_code .= "yes"; } else { $gallery_code .= "no"; }
							$gallery_code .= ",status=no,toolbar=no,resizable=no');return false\" target=\"_blank\">";
						} else if ($popupimage == "4") {

						$gallery_code .=  '<a href="';
						if($use_modrewrite=="1") { $gallery_code .=  $rewrite_base; }
						$gallery_code .=  $folder.'/'.$image.'" class="highslide" onclick="return hs.expand(this)">';

						} else {

							if($use_modrewrite=="0") {
							$imageurl = $currenturl.$get_image;
							$gallery_code .= "<a href='".$imageurl."=".$path.$image."'>";
							} else {
								if($folder==$galleryfolder."/") {$path="";} 
							$gallery_code .= "<a href='".$rewrite_base.$get_image."/".$path.$image."'>";
							}	
						}

						$gallery_code .= "".$lbl_view."</a></span> - ";	

						$gallery_code .=  '<span class="filelist"><a href="';
						if($use_modrewrite=="1") { $gallery_code .=  $rewrite_base; }
						$gallery_code .=  $galleryfilesdir.'/download.php?file='.str_replace($galleryfilesdir."/","",$folder).'/'.$image.'" >';
						$gallery_code .= "".$lbl_download."</a></span>";					
						
						$gallery_code .= "</td>";
						
						
						if($file_list_file_size) { 
							$gallery_code .= "<td class='filelist' style='padding-right:20px; text-align:right;'>";							
							$gallery_code .= number_format(filesize($folder."/".$image)/1024,0)." KB";
							$gallery_code .= "</td>";
						}
						if($file_list_file_type) { 
							$gallery_code .= "<td class='filelist' style='padding-right:10px;'>".strtolower($path_parts['extension'])."</td>";
						}
						if($file_list_file_date) { 
							$gallery_code .= "<td class='filelist' style='padding-right:20px; text-align:right;'>";							
							$gallery_code .= date ("d-m-Y H:i:s", filemtime($folder."/".$image) );
							$gallery_code .= "</td>";
						}
						
				} // end download link


			$images_displayed++;	

////////////////////////////////////////////////////////////////////////////////
		
			// FILE
			// displaying link to another type of file

			} else {	
			
				if(!$file_list_view) {
					$gallery_code .= "<td height='100%'  align='center' valign='".$table_valign."' class='table_cell_img'><div style='width:".$maxthumbwidth."px;padding:10px 0px;'>";
				} else {
					$gallery_code .= "<td>";
				}


				$path_parts = pathinfo($image);
				$file_ext = strtolower($path_parts['extension']);
				
				
				// VIDEO
				if( in_array( $file_ext , $accepted_vid ) ) { // is video file

					
					$path = str_replace($galleryfolder."/","",$folder)."/";
					
					if($use_modrewrite=="0") {
						$imageurl = $currenturl.$get_image;
						$gallery_code .= "<a href='".$imageurl."=".$path.$image."'>";
					} else {
						if($folder==$galleryfolder."/") {$path="";}
						$gallery_code .= "<a href='".$rewrite_base.$get_image."/".$path.$image."'>";
					}	

					$gallery_code .= "<img class='galleryfile' border='0' src='";
						if($use_modrewrite=="1") { $gallery_code .=  "/"; }
					$gallery_code .= GetFileIcon($image);
					if(!$file_list_view) { 
						$gallery_code .= "' alt='' /></a></div>";
					} else {
						$gallery_code .= "' height='".$file_list_file_icon_height."' alt='' /></a>";
					}
					
					
					$path_parts = pathinfo($image);
					$imagename = substr($path_parts['basename'], 0, -(strlen($path_parts['extension']) + ($path_parts['extension'] == '' ? 0 : 1)));
					$imagename = str_replace("_"," ", $imagename);	
					
					if(!$file_list_view) { 
						$gallery_code .= "<span class='imagetitle'>".$imagename."</span><br />";
					} else {
						$gallery_code .= "<td class='filelist' style='padding-right:20px;'>".$imagename."</td>";	
					}
					
					
					
						if(!$file_list_view) { 
							$gallery_code .= "<span class='view_download'>";
						} else {
							$gallery_code .= "<td style='padding-right:20px;'><span class='filelist'>";
						}					
					
						// display additional view and download link

						$path = str_replace($galleryfolder."/","",$folder)."/";
						if($use_modrewrite=="0") {
						$imageurl = $currenturl.$get_image;
						$gallery_code .= "<a href='".$imageurl."=".$path.$image."'>";
						} else {
							if($folder==$galleryfolder."/") {$path="";} 
						$gallery_code .= "<a href='".$rewrite_base.$get_image."/".$path.$image."'>";
						}	

						$gallery_code .= "".$lbl_view."</a> - ";	

						$gallery_code .=  '<a href="';
						if($use_modrewrite=="1") { $gallery_code .=  "/"; }
						$gallery_code .=  $galleryfilesdir.'/download.php?file='.str_replace($galleryfilesdir."/","",$folder).'/'.$image.'" >';
						$gallery_code .= "".$lbl_download."</a></span>";					
						
						// end download link
						
						
						if($file_list_view) { 
							$gallery_code .= "</td>";
							
							if($file_list_file_size) { 
								$gallery_code .= "<td class='filelist' style='padding-right:20px; text-align:right;'>";							
								$gallery_code .= number_format(filesize($folder."/".$image)/1024,0)." KB";
								$gallery_code .= "</td>";
							}
							if($file_list_file_type) { 
								$gallery_code .= "<td class='filelist' style='padding-right:10px;'>".strtolower($path_parts['extension'])."</td>";
							}
							if($file_list_file_date) { 
								$gallery_code .= "<td class='filelist' style='padding-right:20px; text-align:right;'>";							
								$gallery_code .= date ("d-m-Y H:i:s", filemtime($folder."/".$image) );
								$gallery_code .= "</td>";
							}	
							
						} else {
							$gallery_code .= "";
						}

						


				
				
				// OTHER FILES
				} else { //download
				
					
					$gallery_code .=  '<a href="';
					if($use_modrewrite=="1") { $gallery_code .=  $rewrite_base; }
					$gallery_code .=  $galleryfilesdir.'/download.php?file='.str_replace($galleryfilesdir."/","",$folder).'/'.$image.'" >';

					$gallery_code .= "<img class='galleryfile' border='0' src='";
					if($use_modrewrite=="1") { $gallery_code .=  "/"; }
					$gallery_code .= GetFileIcon($image);
					if(!$file_list_view) { 
					$gallery_code .= "' alt='' />";
					} else {
					$gallery_code .= "' height='".$file_list_file_icon_height."' alt='' />";
					}

					$gallery_code .= "</a>";
					if(!$file_list_view) { 
						$gallery_code .= "</div><span class='imagetitle'>".$image."</span>";
					} else {
						$path_parts = pathinfo($image);
						$imagename = substr($path_parts['basename'], 0, -(strlen($path_parts['extension']) + ($path_parts['extension'] == '' ? 0 : 1)));
						$imagename = str_replace("_"," ", $imagename);	
						
						$gallery_code .= "<td class='filelist' style='padding-right:20px;'>".$imagename."</td>";	
					}



				// display additional view and download link for file list view
				if($file_list_view) { 

						$gallery_code .= "<td style='padding-right:20px;'>";
						$gallery_code .= "<span class='filelist' style='text-decoration: line-through;'>".$lbl_view."</span> - ";	

						$gallery_code .=  '<span class="filelist"><a href="';
						if($use_modrewrite=="1") { $gallery_code .=  $rewrite_base; }
						$gallery_code .=  $galleryfilesdir.'/download.php?file='.str_replace($galleryfilesdir."/","",$folder).'/'.$image.'" >';
						$gallery_code .= $lbl_download."</a></span>";					
						
						$gallery_code .= "</td>";
						
						
						if($file_list_file_size) { 
							$gallery_code .= "<td class='filelist' style='padding-right:20px; text-align:right;'>";							
							$gallery_code .= number_format(filesize($folder."/".$image)/1024,0)." KB";
							$gallery_code .= "</td>";
						}
						if($file_list_file_type) { 
							$gallery_code .= "<td class='filelist' style='padding-right:10px;'>".strtolower($path_parts['extension'])."</td>";
						}
						if($file_list_file_date) { 
							$gallery_code .= "<td class='filelist' style='padding-right:20px; text-align:right;'>";							
							$gallery_code .= date ("d-m-Y H:i:s", filemtime($folder."/".$image) );
							$gallery_code .= "</td>";
						}						
						
						
				} else { // end extra table cells for info
					$gallery_code .= "";
				}
					
				
				}
				
				
			}



			$gallery_code .= "</td>";




			
			if(!$file_list_view) {
				// start new table row
 				if ($x % $columns == 0 && $x != $gallerypageimages) { $gallery_code .= '</tr><tr>'; }
    				$x++;
    			} else {
    				$gallery_code .= '</tr><tr>';
    			}






		}
	$i++;
          
}  



$total = $i - 1;

// if no content in table, create empty TD for xhtml validation
if ($total==0) {$gallery_code .= '<td></td>'; }

// close gallery table
$gallery_code .= '</tr></table>';




//////////////////////////////////////////////////////////////////////////////////////

	// build navigation links below gallery

	if($use_modrewrite=="0") {
	include ("include/pagelinks.php");
	} else {
	include ("include/pagelinks_modrewrite.php");
	}







// slideshow link

if($display_slideshow_link) {
if($images_displayed > 1) {

	$gallery_code .= '<div class="slideshowlink">';

	if($use_modrewrite=="1") { 
	$gallery_code .= '<a href="'.$rewrite_base.$get_slideshow.'/';
	} else { 
	$gallery_code .= '<a href="'.$currenturl.$get_slideshow.'=';
	}

	$gallery_code .= $getfolder;
	if($gallerypage > 1) { 
		if($use_modrewrite=="1") { 
		$gallery_code .= '<a href="'.$rewrite_base.$gallerypage.'/';
		} else { 
		$gallery_code .= '&'.$get_page.'='.$gallerypage;
		}
	}
	$gallery_code .= '">'.$lbl_slideshow;
	$gallery_code .= '</a>';

	$gallery_code .= '</div>';

}
}






} // end else diplaying gallery (not image)

//////////////////////////////////////////////////////////////////////////////////////
$gallery_code .= '<div style="display:none;">JV2 Folder Gallery</div>';
//////////////////////////////////////////////////////////////////////////////////////




// INCLUDE THEME FOR STYLING AND TO OUT PUT GALLERY CODE
//////////////////////////////////////////////////////////////////////////////////////

include ("theme/".$gallerytheme."/template.php");

//////////////////////////////////////////////////////////////////////////////////////



?>