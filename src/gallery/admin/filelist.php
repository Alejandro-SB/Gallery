<?php

$filefolder = "";
if (isset($_GET["folder"])) {
  $filefolder = (get_magic_quotes_gpc()) ? $_GET["folder"] : addslashes($_GET["folder"]);
}



	$path = "../images/".$filefolder;
	$gallery_dir = dir($path);

	$file_array = array();
	while(($file = $gallery_dir->read()) !== false) { 
		if ( is_dir($path.'/'.$file) || $file=="." || $file==".." || $file=="Thumbs.db" || $file=="galleryconfig.php" || strpos($file,".txt") || $file==".DS_Store") {} else {
		array_push($file_array, $file); 
		}
	}
	sort($file_array);




if($filefolder == "") { } else {
	$html .= "<p><span style='font-family:verdana,arial; font-size:14px; font-weight:bold; text-transform:uppercase;'>".$filefolder."</span><br>";

	$imagefile = $path."/defaultimg.txt";

	if(file_exists($imagefile)) {
	$imagefile = file($path."/defaultimg.txt");
	$displayimg = $imagefile[0];
	$html .= "Current default img for this folder is: ".$displayimg."";
	} else {
	$html .= "No default image set for this folder...";
	}
	if(file_exists($path."/galleryconfig.php")) {
	$html .= "<br><b>This Folder uses individual configuration options</b> - ";
	$html .= '<a href="?galleryadmin=preferences&file='.str_replace("../","",$path).'/galleryconfig">EDIT</a>';
	} else {

	}
	$html .= "</p>";



$html .= "<table border='0' id='editlist'>";
}

$html .= "<tr>";
$html .= "<td><b>FILENAME</b></td>";
$html .= "<td class='td_p'><b>RENAME</b></td>";
$html .= "<td class='td_p'><b>DELETE</b></td>";
$html .= "<td class='td_p'><b>TEXT</b></td>";
$html .= "<td class='td_p'>";
if($path == "../images/") {$html.="&nbsp;";} else {$html.="<b>DEFAULT</b>";}
$html .= "</td>";

$html .= "</tr>";


foreach($file_array as $file) {

$path_parts = pathinfo($path."/".$file);
$path_parts['basename_we'] = substr($path_parts['basename'], 0, -(strlen($path_parts['extension']) + ($path_parts['extension'] == '' ? 0 : 1)));
$commentsfile = $path_parts['dirname']."/".$path_parts['basename_we'].".txt";

$filelink = str_replace("../images/","",$commentsfile);
$folderpath =  str_replace("../images/","",$path_parts['dirname']);

$html .= "<tr>";
$html .= "<td><a class='thumbnail' href='#'>".$file;
$html .= '<span><img src="thumb.php?source=../images/'.$folderpath.'/'.$file.'"></span>';
$html .= "</a></td>";

$html .= '<td class="td_p"><a href="?galleryadmin=rename&image='.$folderpath.'/'.$file.'">Rename</a></td>';
$html .= '<td class="td_p"><a href="?galleryadmin=delete&folder='.$folderpath.'&image='.$file.'">Delete</a></td>';
$html .= "<td class='td_p'>";
if (file_exists($commentsfile)) {
$html .= "<a href='?galleryadmin=edittext&file=".$filelink."'>EDIT</a>";
} else {
$html .= "<a href='?galleryadmin=settext&file=".$filelink."'>Set</a>";
}
$html .= "</td>";
$html .= '<td class="td_p">';
if($displayimg==$file) {
$html .= '<i>DEFAULT</i>';
} else {
if($path == "../images/") {$html.="&nbsp;";} else {
$html .= '<a href="?galleryadmin=preferences&folder='.$folderpath.'&defaultimage='.$file.'">Set</a>';
}
}
$html .= '</td>';


$html .= "</tr>";

}

$html .= "</table>";

$html .= '<br><hr>';
$html .= '<a href="?galleryadmin=createfolder&folder='.$filefolder.'">Create New SubFolder Here</a> | ';
$html .= '<a href="?galleryadmin=uploadimage&folder='.$filefolder.'">Upload Image to this Folder</a> | ';
$html .= '<a href="?galleryadmin=uploadzip&folder='.$filefolder.'">Upload ZIP to this Folder</a>  ';
?>