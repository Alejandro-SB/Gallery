<?php


// if user logged in
if (isset($_SESSION['username'])) {


if (isset($_POST['newname'])) { // if form has been submitted

$oldname = null;
if (isset($_POST['oldname'])) {
  $oldname = (get_magic_quotes_gpc()) ? $_POST['oldname'] : addslashes($_POST['oldname']);
}
$newname = null;
if (isset($_POST['newname'])) {
  $newname = (get_magic_quotes_gpc()) ? $_POST['newname'] : addslashes($_POST['newname']);
}
$isfolder = null;
if (isset($_POST['isfolder'])) {
  $isfolder = (get_magic_quotes_gpc()) ? $_POST['isfolder'] : addslashes($_POST['isfolder']);
}


if(  strpos($oldname,".jpg") ||  strpos($oldname,".JPG") ) {
if( !strpos($newname,".jpg") || !strpos($newname,".JPG") ) {
$newname.=".jpg";
}}
if(  strpos($oldname,".gif") ||  strpos($oldname,".GIF") ) {
if( !strpos($newname,".gif") || !strpos($newname,".GIF") ) {
$newname.=".gif";
}}
if(  strpos($oldname,".png") ||  strpos($oldname,".PNG") ) {
if( !strpos($newname,".png") || !strpos($newname,".PNG") ) {
$newname.=".png";
}}



$path_array = explode("/",$oldname);
$path = "";
for ($x=0;$x < (sizeof($path_array)-1);$x++) {
$path .= $path_array[$x]."/";
}
$ok = rename($oldname,$path.$newname);




if($ok) {
$html .= "<br /><br /><i>".$oldname."</i> renamed to <b>".$path.$newname."</b>";

if(isset($_SESSION['menu'])) {
$_SESSION['menu']=null;
}

if ($isfolder == 0) {
$backlink = substr("?galleryadmin=filelist&folder=".str_replace("../images/","",$path),0,-1);
} else {
$backlink = "index.php";
}

$html .= '<br><br><a href="'.$backlink.'"><u>Done</u></a>';

} else {
$html .= "Item can't be renamed... check write permissions and that name does not exist";
}




} else  {	// if form hasn't been submitted


if (isset($_GET['folder'])) {
$folder = "";
  $folder .= (get_magic_quotes_gpc()) ? $_GET['folder'] : addslashes($_GET['folder']);
  $isfolder = 1;
}


if (isset($_GET['image'])) {
$image = "";
  $image .= (get_magic_quotes_gpc()) ? $_GET['image'] : addslashes($_GET['image']);
  $isfolder = 0;
}

$name = "../images/".$folder.$image;


$html.='
Renaming: '.str_replace("../images/","",$name).'
<br><br>
<form name="rename" method="post" action="index.php?galleryadmin=rename">
    <input type="hidden" name="oldname" value="'.$name.'">
    <input type="hidden" name="isfolder" value="'.$isfolder.'">
    New Name: <input type="text" name="newname" size="20">
    <input type="submit" name="Submit" value="RENAME" >
    <input type=button class="button" value="CANCEL" onClick="history.go(-1)">
</form>
';


} // end if not submitted

} else { // if not logged in
$html .=('Sorry, you are not logged in, this area is restricted to admin.');
$html .= "<br><input type=button class='button' value='Back' onClick='history.go(-1)'>";
}


?>