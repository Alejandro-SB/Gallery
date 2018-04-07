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

$admin_version = "v 1.2";

//////////////////////////////////////////////////////////////////////////////////////
session_start();

include ("../config/gallerysetup.php");
include ("../config/galleryconfig.php");
include ("gallerysafemode.php");

$galleryfilesdir = "gallery";
$galleryfolder = "../".$galleryfolder;

include ("adminfunctions.php");

$html = "";

$setdefault = "";
if (isset($_GET["setdefault"])) {
  $setdefault = (get_magic_quotes_gpc()) ? $_GET["setdefault"] : addslashes($_GET["setdefault"]);
}
$deleteimage = "";
if (isset($_GET["deleteimage"])) {
  $deleteimage = (get_magic_quotes_gpc()) ? $_GET["deleteimage"] : addslashes($_GET["deleteimage"]);
}
$deletefolder = "";
if (isset($_GET["deletefolder"])) {
  $deletefolder = (get_magic_quotes_gpc()) ? $_GET["deletefolder"] : addslashes($_GET["deletefolder"]);
}
$galleryadmin = "";
if (isset($_GET["galleryadmin"])) {
  $galleryadmin = (get_magic_quotes_gpc()) ? $_GET["galleryadmin"] : addslashes($_GET["galleryadmin"]);
}

$login = 0;
if (isset($_GET["login"])) {
  $login = (get_magic_quotes_gpc()) ? $_GET["login"] : addslashes($_GET["login"]);
}
$logout = 0;
if (isset($_GET["logout"])) {
  $logout = (get_magic_quotes_gpc()) ? $_GET["logout"] : addslashes($_GET["logout"]);
}

$username2 = "";
if (isset($_POST["username"])) {
  $username2 = (get_magic_quotes_gpc()) ? $_POST["username"] : addslashes($_POST["username"]);
}
$password2 = "";
if (isset($_POST["password"])) {
  $password2 = (get_magic_quotes_gpc()) ? $_POST["password"] : addslashes($_POST["password"]);
}
$wronglogin = 0;
if (isset($_POST["username"]) && isset($_POST["password"])) {
if ($username==$username2 && $password==$password2) {
	$_SESSION['username']=$username2;
	} else {
	$wronglogin = 1;
	}
}
if ($logout==1) {
	unset($_SESSION['username']);
	}

unset($_POST["username"]);
unset($_POST["password"]);

//////////////////////////////////////////////////////////////////////

include ("adminheader.php");

//////////////////////////////////////////////////////////////////////


if (isset($_SESSION['username'])) {



if ($galleryadmin=="setup") {
    include ("edit_setup.php");
} else if($galleryadmin=="preferences") {
    include ("edit_preferences.php");
} else if($galleryadmin=="uploadimage") {
    include ("upload_image.php");
} else if($galleryadmin=="uploadzip") {
    include ("upload_galleryzip.php");
} else if($galleryadmin=="createfolder") {
    include ("create_folder.php");
} else if($galleryadmin=="delete") {
    include ("delete_item.php");
} else if($galleryadmin=="filelist") {
    include ("filelist.php");
} else if($galleryadmin=="cache") {
    include ("cache.php");
} else if($galleryadmin=="generate_th") {
    include ("generate_th.php");
} else if($galleryadmin=="rename") {
    include ("rename.php");
} else if($galleryadmin=="edittext") {
    include ("edit_text.php");
} else if($galleryadmin=="settext") {
    include ("edit_text.php");
} else {   // Do the Normal Gallery Routine






$gallery_dir = dir($galleryfolder);

$folder_array = array();
while(($folder = $gallery_dir->read()) !== false) { 
	array_push($folder_array, $folder); 
}
sort($folder_array);

$html .=  buildadminmenu();

    include ("filelist.php");

//$html .= '<br><hr>';
//$html .= '<a href="?galleryadmin=createfolder">New Main Level Folder</a> | ';
//$html .= '<a href="?galleryadmin=uploadimage">Upload Image to Main Folder</a> | ';
//$html .= '<a href="?galleryadmin=uploadzip">Upload ZIP to Main Folder</a>  ';
}



} else { // if not logged in

if ($wronglogin==1) {
	$html .= '<div style="text-align:center; color:#FF0000;">Wrong username or password...</div>';
	$html .= '<form id="login" name="login" action="index.php" method="POST">';
	$html .= 'Username: <input name="username" type="text" size="8"> ';
	$html .= 'Password:<input name="password" type="password" size="8"> ';
	$html .= '<input name="Submit" type="Submit" value="Login">';
	$html .= '</form>';
	} else {
	$html .= '<div style="text-align:center;">Login to Admin:</div>';
	$html .= '<form id="login" name="login" action="index.php" method="POST">';
	$html .= 'Username: <input name="username" type="text" size="8"> ';
	$html .= 'Password:<input name="password" type="password" size="8"> ';
	$html .= '<input name="Submit" type="Submit" value="Login">';
	$html .= '</form>';	
	}

}








//////////////////////////////////////////////////////////////////////

include ("adminfooter.php");

//////////////////////////////////////////////////////////////////////


echo $html;

?>