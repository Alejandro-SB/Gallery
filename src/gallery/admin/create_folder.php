<?php


// if user logged in
if (isset($_SESSION['username'])) {


if (isset($_POST['newfolder'])) { // if form has been submitted

$folder = null;
if (isset($_POST['folder'])) {
  $folder = (get_magic_quotes_gpc()) ? $_POST['folder'] : addslashes($_POST['folder']);
}
$newfolder = null;
if (isset($_POST['newfolder'])) {
  $newfolder = (get_magic_quotes_gpc()) ? $_POST['newfolder'] : addslashes($_POST['newfolder']);
}


if (!is_writable($folder)) {
	// Check for safe mode
	if( ini_get('safe_mode') ) {
	} else {
	   chmod($folder, 0777);
	}

}


if( ini_get('safe_mode') ) {

	makedir($galleryrootdir."/".str_replace("../","",$folder),$newfolder,777);

} else {

	$ok = @mkdir($folder."/".$newfolder, 0777);

}

if($ok) {
$html .= "<br /><br />Folder <b>".$folder."/".$newfolder."</b> created";

if(isset($_SESSION['menu'])) {
$_SESSION['menu']=null;
}

header("Location: index.php");

} else {
$html .= "Folder can't be created... check write permissions and that name does not exist";
}




} else  {	// if form hasn't been submitted

$folder = "../images/";
if (isset($_GET['folder'])) {
  $folder .= (get_magic_quotes_gpc()) ? $_GET['folder'] : addslashes($_GET['folder']);
}

$html.='

<form name="createfolder" method="post" action="index.php?galleryadmin=createfolder">
    <input type="hidden" name="folder" value="'.$folder.'">
    Name for folder: <input type="text" name="newfolder" size="20">
    <input type="submit" name="Submit" value="Create Folder" >
    <input type=button class="button" value="CANCEL" onClick="history.go(-1)">
</form>
';

} // end if not submitted

} else { // if not logged in
$html .=('Sorry, you are not logged in, this area is restricted to admin.');
$html .= "<br><input type=button class='button' value='Back' onClick='history.go(-1)'>";
}


?>
