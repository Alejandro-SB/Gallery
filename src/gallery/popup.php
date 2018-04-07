<?php 
include ("config/gallerysetup.php");
include ("config/galleryconfig.php");

// CLEAN GET VARS
foreach($_GET as $var => $val) {
	$_GET[$var] = htmlspecialchars(strip_tags($val));
}

$img = $_GET['img'];
$w = $_GET['w'];
$h = $_GET['h'];
$t = $_GET['t'];
print "<html>";
print "<head>";
print "<title>$t</title>";
print "</head>";
print "<body bgcolor=\"Black\" leftmargin=0 topmargin=0  marginwidth=0 marginheight=0 onblur=\"self.close();\" onload=\"self.focus();\">";
print "<img src=\"$img\" width=$w height=$h border=0>";
print "</body>";
?>