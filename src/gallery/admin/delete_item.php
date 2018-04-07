<?php

function rmdirr($dir) {
   if($objs = glob($dir."/*")){
       foreach($objs as $obj) {
           is_dir($obj)? rmdirr($obj) : unlink($obj);
       }
   }
   rmdir($dir);
}



// if user logged in
if (isset($_SESSION['username'])) {


$folder = null;
if (isset($_POST['folder'])) {
  $folder = (get_magic_quotes_gpc()) ? $_POST['folder'] : addslashes($_POST['folder']);
}
$image = null;
if (isset($_POST['image'])) {
  $image = (get_magic_quotes_gpc()) ? $_POST['image'] : addslashes($_POST['image']);
}


if (isset($_GET['folder'])) {
  $folder = (get_magic_quotes_gpc()) ? $_GET['folder'] : addslashes($_GET['folder']);
}

if (isset($_GET['image'])) {
  $image = (get_magic_quotes_gpc()) ? $_GET['image'] : addslashes($_GET['image']);
}



// deleting a folder
if ($_POST['image']==null && isset($_POST['folder'])) {



$target_path = "../images/".$folder;


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
rmdirr($target_path);


if(isset($_SESSION['menu'])) {
$_SESSION['menu']=null;
}

header("Location: index.php");


}




// deleting an image
if ($_POST['image']!==null && isset($_POST['folder'])) {

$target_path = "../images/".$folder."/".$image;

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

unlink($target_path) or die("Can not delete");


header("Location: index.php");

}





if (isset($_GET['image']) || isset($_GET['folder'])) {


$html.='
Are you Sure You want to DELETE: <b>'.$folder.'/'.$image.'</b> ???<br><br>
<form name="confirm" method="post" action="index.php?galleryadmin=delete">
    <input type="hidden" name="folder" value="'.$folder.'">
    <input type="hidden" name="image" value="'.$image.'">

    <input type="submit" name="Submit" value="YES" >
    <input type=button value="NO" onClick="history.go(-1)">
</form>
';



}















} else { // if not logged in
$html .=('Sorry, you are not logged in, this area is restricted to admin.');
$html .= "<br><input type=button class='button' value='Back' onClick='history.go(-1)'>";
}


?>
