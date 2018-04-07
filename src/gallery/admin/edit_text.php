<?php


// if user logged in
if (isset($_SESSION['username'])) {




if ($_POST['Submit']=="SAVE" || $_POST['Submit']=="DELETE") { 

$filename = "";
if (isset($_POST['file'])) {
  $filename = (get_magic_quotes_gpc()) ? $_POST['file'] : addslashes($_POST['file']);
}

$filecontent = $_POST['content'];


$backlink_array = explode("/",str_replace("../images/","",$filename));
$backlink = "";
for ($x=0;$x < (sizeof($backlink_array)-1);$x++) {
$backlink .= $backlink_array[$x]."/";
}
$backlink = substr("?galleryadmin=filelist&folder=".$backlink,0,-1);

}







if ($_POST['Submit']=="SAVE") { 

   // open file in W+ mode to empty it and write from beginning
   if (!$filehandle = fopen($filename, 'w+')) {
         $html .=  "Cannot open file ($filename)";
         exit;
   }

   // Write content to file.
   if (fwrite($filehandle, $filecontent) === FALSE) {
       $html .=  "Cannot write to file ($filename)";
       exit;
   }

   $html .=  "Data saved in '".$filename."'.";

   fclose($filehandle);

   header("Location: ".$backlink);








} else if ($_POST['Submit']=="DELETE") { 

   unlink($filename);
   header("Location: ".$backlink);









} else  {	// if form hasn't been submitted

$commentsfile = "../images/";
if (isset($_GET['file'])) {
  $commentsfile .= (get_magic_quotes_gpc()) ? $_GET['file'] : addslashes($_GET['file']);
}

if (file_exists($commentsfile)) {
    $fp = fopen($commentsfile, "r");
    $comments = fread($fp, filesize($commentsfile));
    fclose($fp);
}


$html.='
Editing: '.str_replace("../images/","",$commentsfile).'
<br><br>
<form name="rename" method="post" action="index.php?galleryadmin=edittext">
    <input type="hidden" name="file" value="'.$commentsfile.'">
    <textarea name="content" cols="40" rows="5">'.$comments.'</textarea><br>
    <input type="submit" name="Submit" value="SAVE" >
    <input type=button class="button" value="CANCEL" onClick="history.go(-1)">
    &nbsp;&nbsp;&nbsp;
    <input type="submit" name="Submit" value="DELETE" >
</form>
';


} // end if not submitted

} else { // if not logged in
$html .=('Sorry, you are not logged in, this area is restricted to admin.');
$html .= "<br><input type=button class='button' value='Back' onClick='history.go(-1)'>";
}



?>