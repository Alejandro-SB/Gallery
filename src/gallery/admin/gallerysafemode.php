<?php
////////////////////////////////////////////////////////
// you will need these setting and functions if your 
// gallery is on a webserver with PHP safe mode = on
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
// CONFIG
////////////////////////////////////////////////////////

Global $galleryserveraddress;
Global $gallery_ftp_username;
Global $gallery_ftp_password;

$galleryserveraddress = ""; // Address of the ftp server
$gallery_ftp_username = ""; // your username for ftp
$gallery_ftp_password = ""; // your password for ftp
$galleryrootdir = "public_html/"; // the root directory on ftp where gallery is installed

////////////////////////////////////////////////////////
// functions
////////////////////////////////////////////////////////



function chmodftp($root,$file,$chmod=755) {

Global $galleryserveraddress;
Global $gallery_ftp_username;
Global $gallery_ftp_password;

$conn_id = 0;
$conn_id = ftp_connect($galleryserveraddress);

$login_result = ftp_login($conn_id,$gallery_ftp_username,$gallery_ftp_password);
if ((!$conn_id) || (!$login_result)) {
echo "<b>FTP Login incorrect</b><br>";
die;
}


if ($root == "") $root == "/";
echo $root."<br>";
ftp_chdir($conn_id,$root);

if ($chmod == "") $chmod = 777;
echo "chmod ".$root.$file."<br>";
ftp_site ($conn_id,"chmod $chmod $file");

ftp_quit($conn_id);
}


function makedir($root,$newdir,$chmod=755)
{

Global $galleryserveraddress;
Global $gallery_ftp_username;
Global $gallery_ftp_password;

$conn_id = 0;
$conn_id = ftp_connect($galleryserveraddress);

$login_result = ftp_login($conn_id,$gallery_ftp_username,$gallery_ftp_password);
if ((!$conn_id) || (!$login_result)) {
echo "<b>FTP Login incorrect</b><br>";
die;
}

if ($root == "") $root == "/";

//echo $root."<br>".$newdir."<br>";
ftp_chdir($conn_id,$root);
ftp_mkdir($conn_id,$newdir);

if ($chmod == "") $chmod = 777;

ftp_site ($conn_id,"chmod $chmod $newdir");

ftp_quit($conn_id);
}




?>
