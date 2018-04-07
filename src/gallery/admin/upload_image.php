<?php



// if user logged in
if (isset($_SESSION['username'])) {

if (isset($_POST['Submit'])) {    // if the form has been submitted

$target_path = "../images/";
if (isset($_POST['target_path'])) {
  $target_path .= (get_magic_quotes_gpc()) ? $_POST['target_path'] : addslashes($_POST['target_path']);
}

if (!is_writable($target_path)) {
	// Check for safe mode
	if( ini_get('safe_mode') ) {
	   $html .= 'Your webserver has PHP safe mode restrictions in effect<br>';
	   $html .= 'You must set chmod 777 on the file manually with FTP<br><br>';
	   $html .= 'Attempting to set write permissions via FTP...<br>';
	   chmodftp($galleryrootdir,$target_path,777);
	   $html .= '<br>If no errors, then Chmod successfull via FTP...<br><br>';
	   $html .= 'Please click RETRY and then OK to resend data<br>';
	   $html .= "<input type=button class='button' value='RETRY' onClick='window.location.reload( false )'>";
	   die();
	}else{
	   chmod($target_path, 0777);
	}
}

$folder = $target_path;
$target_path = $target_path ."/". basename( $_FILES['uploadedfile']['name']);

//$html .= $target_path . "<br><br>";

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
     $html .= "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded<br /><br />";



$html .= "<br /><br /><a href='?galleryadmin=uploadimage&folder=".str_replace("../images/","",$folder)."'>Upload another image to this folder</a><br />";

$backlink = "?galleryadmin=filelist&folder=".str_replace("../images/","",$folder);
$html .= "<br /><br /><a href='".$backlink."'>Done</a><br />";


} else{
     $html .= "<br><br>There was an error uploading the file, please try again!";
}




} else {  // if the form has not yet been submitted

$target_path = null;
if (isset($_GET['folder'])) {
  $target_path = (get_magic_quotes_gpc()) ? $_GET['folder'] : addslashes($_GET['folder']);
}



$html .= '
<br><br>
 <form enctype="multipart/form-data" method="post" action="?galleryadmin=uploadimage">

<input type="hidden" name="target_path" value="'.$target_path.'">
 
   <input type="file" size="32" name="uploadedfile" value="">
   <input type="submit" name="Submit" value="upload">
 </form>';

}



} else { // if not logged in
$html .=('Sorry, you are not logged in, this area is restricted to admin.');
$html .= "<br><input type=button class='button' value='Back' onClick='history.go(-1)'>";
}


?>