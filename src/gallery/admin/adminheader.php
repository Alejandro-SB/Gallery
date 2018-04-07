<?php

$html .='
<html><head>
<title>GALLERY ADMIN</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="imagetoolbar" content="false" />
<link href="admin.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function goToURL(url,check) { if (check == "delete") { if (confirmSubmit() == true) { window.location = url; }} else { window.location = url;} }
function confirmSubmit() { var agree=confirm("Are You sure you want to delete this?"); if (agree) { return true ; } else { return false;}  }
</script>
</head><body>

<div id="page">
<div id="header">
Folder Gallery Admin
</div>
<div id="topmenu">
<a href="../../">My Gallery</a> | 
<a target="_blank" href="http://foldergallery.jv2.net">Folder Gallery Website</a> | 
<a target="_blank" href="http://foldergallery.jv2.net/forum/">Forum</a> | 
<a target="_blank" href="http://foldergallery.jv2.net/forum/viewforum.php?id=1">FAQ</a> | 
<a target="_blank" href="http://foldergallery.jv2.net/?id=8">Donate</a>
</div>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
';

if (isset($_SESSION['username'])) {

$html .= '<td valign="top" id="menubg">';
$html .= '<div id="adminmenu">';
$html .= '<a href="index.php">File Manager</a><br><br>';
$html .= '<a href="?galleryadmin=preferences&file=config/galleryconfig">Default Preferences</a><br>';
$html .= '<a href="?galleryadmin=setup">Main Setup</a><br><br>';
$html .= '<a href="?galleryadmin=cache">Cache</a><br><br>';
$html .= '<a href="?logout=1">Logout</a>';
$html .= '</div>';
$html .= '</td>';
}

$html .= '
<td valign="top">
<div id="pagecontent">
';
?>