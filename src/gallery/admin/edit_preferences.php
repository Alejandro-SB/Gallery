<?php

$html .= "<div class='gallerymenu'>";


// if user logged in
if (isset($_SESSION['username'])) {

$filename = "";
if (isset($_POST['file'])) {
  $filename = "";
  $filename .= (get_magic_quotes_gpc()) ? $_POST['file'] : addslashes($_POST['file']);
}
$comments = "";
if (isset($_POST['comments'])) {
  $comments = (get_magic_quotes_gpc()) ? $_POST['comments'] : addslashes($_POST['comments']);
}


// if setting a default image for a folder
if (isset($_GET['folder']) && isset($_GET['defaultimage'])) {

$folder = "";
if (isset($_GET['folder'])) {
  $folder = (get_magic_quotes_gpc()) ? $_GET['folder'] : addslashes($_GET['folder']);
}

$defaultimage = "";
if (isset($_GET['defaultimage'])) {
  $defaultimage = (get_magic_quotes_gpc()) ? $_GET['defaultimage'] : addslashes($_GET['defaultimage']);
}


$folder = "../images/". $folder;

$filecontent = $defaultimage;
$filename = $folder."/defaultimg.txt";

if (!is_writable($folder)) {
	// Check for safe mode
	if( ini_get('safe_mode') ) {
	   $html .= 'Your webserver has PHP safe mode restrictions in effect<br>';
	   $html .= 'You must set chmod 777 on the file manually with FTP<br><br>';
	   $html .= 'Attempting to set write permissions via FTP...<br>';
	   chmodftp($galleryrootdir,$folder,777);
	   $html .= '<br>If no errors, then Chmod successfull via FTP...<br><br>';
	   $html .= 'Please click RETRY and then OK to resend data<br>';
	   $html .= "<input type=button class='button' value='RETRY' onClick='window.location.reload( false )'>";
	   die();
	}else{
	   chmod($folder, 0777);
	}

}

if (file_exists($filename)) {
	if (!is_writable($filename)) {
		// Check for safe mode
		if( ini_get('safe_mode') ) {
		   $html .= 'Attempting to set write permissions to defaultimg.txt file via FTP...<br>';
		   chmodftp($galleryrootdir.$folder,"defaultimg.txt",777);
		   $html .= '<br>If no errors, then Chmod successfull via FTP...<br><br>';
		   $html .= 'Please click RETRY and then OK to resend data<br>';
		   $html .= "<input type=button class='button' value='RETRY' onClick='window.location.reload( false )'>";
		   die();
		}else{
	  	   chmod($filename, 0777);
	}

	}
}

if (!file_exists($filename)) {
$handle = fopen($filename, 'x+');
fclose($handle);
}

if (is_writable($filename)) {

   // open file in W+ mode to empty it and write from beginning
   if (!$handle = fopen($filename, 'w+')) {
         $html .= "Cannot open file ($filename)";
         exit;
   }

   // Write content to file.
   if (fwrite($handle, $filecontent) === FALSE) {
       $html .= "Cannot write to file ($filename)";
       exit;
   }

   $html .= "Default image '".$defaultimage."' for '".$folder."' <b>saved</b> in '".$filename."'.";

   fclose($handle);
   
   
   	$backlink = "?galleryadmin=filelist&folder=".str_replace("../images/","",$folder);
 	header("Location: ".$backlink);

} else {
   $html .= "The file $filename is not writable";
}


die();

// IF editing image comments
} else if (isset($_GET['setcomment']) || isset($_POST['comments']) )    {

if (isset($_POST['comments'])) {  // if posting edited text

if (!is_writable($folder)) {
	// Check for safe mode
	if( ini_get('safe_mode') ) {
	   $html .= 'Your webserver has PHP safe mode restrictions in effect<br>';
	   $html .= 'You must set chmod 777 on the file manually with FTP<br><br>';
	   $html .= 'Attempting to set write permissions via FTP...<br>';
	   chmodftp($galleryrootdir,$folder,777);
	   $html .= '<br>If no errors, then Chmod successfull via FTP...<br><br>';
	   $html .= 'Please click RETRY and then OK to resend data<br>';
	   $html .= "<input type=button class='button' value='RETRY' onClick='window.location.reload( false )'>";
	   die();
	}else{
	   chmod($folder, 0777);
	}

}

if (file_exists($filename)) {
	if (!is_writable($filename)) {
		// Check for safe mode
		if( ini_get('safe_mode') ) {
		   $html .= 'Attempting to set write permissions to defaultimg.txt file via FTP...<br>';
		   chmodftp($galleryrootdir.$folder,"defaultimg.txt",777);
		   $html .= '<br>If no errors, then Chmod successfull via FTP...<br><br>';
		   $html .= 'Please click RETRY and then OK to resend data<br>';
		   $html .= "<input type=button class='button' value='RETRY' onClick='window.location.reload( false )'>";
		   die();
		}else{
	  	   chmod($filename, 0777);
	}

	}
}

if (!file_exists($filename)) {
$handle = fopen($filename, 'x+');
fclose($handle);
}

if (is_writable($filename)) {

   // open file in W+ mode to empty it and write from beginning
   if (!$handle = fopen($filename, 'w+')) {
         $html .= "Cannot open file ($filename)";
         exit;
   }

   // Write content to file.
   if (fwrite($handle, $comments) === FALSE) {
       $html .= "Cannot write to file ($filename)";
       exit;
   }

   $html .= "Comments <b>saved</b> in '".$filename."'.";

   fclose($handle);

} else {
   $html .= "The file $filename is not writable";
}


die();



} else {   // if editing or inserting new comment


$path_parts = pathinfo($getimage);
$path_parts['basename_we'] = substr($path_parts['basename'], 0, -(strlen($path_parts['extension']) + ($path_parts['extension'] == '' ? 0 : 1)));
$commentsfile = $path_parts['dirname']."/".$path_parts['basename_we'].".txt";
$comments = "";
if (file_exists($commentsfile)) {
    $fp = fopen($commentsfile, "r");
    $comments = fread($fp, filesize($commentsfile));
    fclose($fp);
}
    $html .= '<form name="comments" method="post" action="?galleryadmin=preferences">';
    $html .= '<input type="hidden" name="file" value="'.$commentsfile.'">';
    $html .= "<textarea name='comments' cols='50' rows='6'>";
    $html .= $comments;
    $html .= "</textarea><br />";
    $html .= '<input type="submit" name="Submit" value="SAVE CHANGES" class="button" >';
    $html .= "<input type=button class='button' value='CANCEL' onClick='history.go(-1)'>";



}






} else {  // editing preferences


if (isset($_POST['rows'])) { // if form has been submitted

if (!is_writable($filename)) {
	// Check for safe mode
	if( ini_get('safe_mode') ) {
	   $html .= 'Your webserver has PHP safe mode restrictions in effect<br>';
	   $html .= 'You must set chmod 777 on the file manually with FTP<br><br>';
	   $html .= 'Attempting to set write permissions via FTP...<br>';
	   chmodftp($galleryrootdir,$filename,777);
	   $html .= '<br>If no errors, then Chmod successfull via FTP...<br><br>';
	   $html .= 'Please click RETRY and then OK to resend data<br>';
	   $html .= "<input type=button class='button' value='RETRY' onClick='window.location.reload( false )'>";
	   die();
	}else{
	   chmod($filename, 0777);
	}

}
}


$html .= '

<script type = "text/javascript">
//<![CDATA[
function showTip( e ) {
	// if client does not support object scripting get out of function
	if ( !document.getElementById && !document.createElement ) {
		return;
	} //-- ends if statement

	if ( !e ) {
			var tipobj = window.event.srcElement;
		} else {	// if one has been sent to this function
			var tipobj = e.target;
	} //-- ends if...else statement
	// if the mouse over was not been placed on a link, get out
	if ( tipobj.tagName != "A" ) return;
	// obtain the tip from the specific link
	var tip = null;
	tip = tipobj.getAttribute( "tooltip" );

	// if link does not have a tooltip element, get out..
	if (  tip == null || tip == "undefined" || tip == "")
		return;
/////////////////////////
	//tip += "<br><a href=\'obj.href\'>" + obj.href+"</a>";
////////////////
	var tipx = 0;	// x coordinate
	var tipy = 0;	// y coordinate
	// reference to the link
	var element = document.getElementById( \'tips\' );
	if ( document.all ) {
		tipx =  event.clientX;
		tipy =  event.clientY;

		element.style.display = "block";
		element.style.left = tipx -210;
		element.style.top = tipy -10;
		element.innerHTML = tip;
	} else {

		tipx = e.pageX -220;
		tipy = e.pageY -10;
		element.style.display = "block";
		element.style.left = tipx + \'px\';
		element.style.top = tipy + \'px\';
		element.innerHTML = tip;
		return;
	} //-- ends if...else statement
} //-- ends function showTip

function hideTip() {
	document.getElementById( \'tips\' ).style.display = "none";
} //--ends function hideTip

document.onmouseover = showTip;		// call this function on hover
document.onmouseout = hideTip;		// call this function on mouseout
//]]>





// this is an extra function that I thought you should have 
// so that it works on IE to highlight the fields as well
function highLight( element ) {
	// change background color
	element.style.backgroundColor = "lightyellow";
	element.style.color = "black";
} //-- ends function hightlit

// this function trims the input from left and right..
// so user input is cleaned before submission
function trimIt( element ) {
	// change background color to white
	element.style.backgroundColor = "white";
	// obtain input field value
	element = element.value;
	// remove any spaces
	while( element.charAt( 0 ) == \' \' ) {
		element = element.substring( 1 );
	} //-- ends while
	while( element.charAt( element.length - 1 ) == \' \' ) {
		element = element.substring( 0, element.length - 1 );
	} //-- ends while
	return element;
} //-- ends function trimit
</script>


';


$html .= '<div id = "tips"></div>';




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////






if (isset($_POST['rows'])) { // if form has been submitted

$filecontent = "<?php \n";

$rows = $_POST['rows'];

for ($i=1;$i<=$rows;$i++) {

$filecontent .= '$'.$_POST['variable'.$i].' = "'.$_POST['value'.$i]."\";  // ".trim(stripslashes($_POST['comment'.$i]),"\n")."\n";

}
$filecontent .= "?>";


if (is_writable($filename)) {

   // open file in W+ mode to empty it and wrtie from beginning
   if (!$handle = fopen($filename, 'w+')) {
         $html .= "Cannot open file ($filename)";
         exit;
   }

   // Write content to file.
   if (fwrite($handle, $filecontent) === FALSE) {
       $html .= "Cannot write to file ($filename)";
       exit;
   }

   $html .= "Data <b>saved</b> in '".$filename."'.<br><br>";

   fclose($handle);

} else {
   $html .= "The file $filename is not writable";
}



















} else  {	// if form hasn't been submitted










$copyconfig = "false";
if (isset($_GET['copyconfig'])) {
  $copyconfig = (get_magic_quotes_gpc()) ? $_GET['copyconfig'] : addslashes($_GET['copyconfig']);
}
$resetconfig = "false";
if (isset($_GET['resetconfig'])) {
  $resetconfig = (get_magic_quotes_gpc()) ? $_GET['resetconfig'] : addslashes($_GET['resetconfig']);
}

$filename = "";
if (isset($_GET['file'])) {
  $filename = "../";
  $filename .= (get_magic_quotes_gpc()) ? $_GET['file'] : addslashes($_GET['file']);
}

$gallerydir_array = explode("/",str_replace("../images/","",$filename));
$gallerydir = "";
for ($x=0;$x < sizeof($gallerydir_array);$x++) {
$gallerydir .= "/".$gallerydir_array[$x];
}


$html .= "<p style='font-family:verdana,arial; font-size:14px; font-weight:bold; text-transform:uppercase;'>".$gallerydir."</p>";

$filelink = str_replace("../","",$filename);
$filename .= ".php";

if ($copyconfig=="true" || $resetconfig=="true") {





if (!is_writable($gallerydir)) {
	// Check for safe mode
	if( ini_get('safe_mode') ){
	   $html .= 'Your webserver has PHP safe mode restrictions in effect<br>';
	   $html .= 'You must set chmod 777 on the file manually with FTP<br><br>';
	   $html .= 'Attempting to set write permissions via FTP...<br>';
	   chmodftp($galleryrootdir,$gallerydir,777);
	   $html .= '<br>If no errors, then Chmod successfull via FTP...<br><br>';
	   $html .= 'Please click RETRY to finish process<br>';
	   $html .= "<input type=button class='button' value='RETRY' onClick='window.location.reload( false )'>";
	   die();
	}else{
	   chmod(str_replace("/galleryconfig.php","",$filename), 0777);
	}
	
}

if ($copyconfig=="true") {
	$configfile = '../config/galleryconfig.php';
	$themeconfig = '../theme/'.$gallerytheme.'/config.php';

	if (!copy($configfile, $filename)) {
   		$html .= "<b>Failed to create $configfile...</b><br><br>Check folder permissions<br><br>";
        die();
	}


	$temp_array1 = array();
	$fc=file($themeconfig);
	foreach($fc as $line) {
	$temp_array1[] = $line;
	}
	array_shift($temp_array1);
	array_pop($temp_array1);

	$temp_array2 = array();
	$fc=file($configfile);
	foreach($fc as $line) {
	$temp_array2[] = $line;
	}
	array_shift($temp_array2);
	array_pop($temp_array2);

	$temp_array = array_merge($temp_array1, $temp_array2);



	$filecontent = "<?php \n";

	foreach ($temp_array as $line) {
	$filecontent .= $line;
	}
	$filecontent .= "?>";

	if (is_writable($filename)) {

	   // open file in W+ mode to empty it and wrtie from beginning
	   if (!$handle = fopen($filename, 'w+')) {
		 $html .= "Cannot open file ($filename)";
		 exit;
	   }

	   // Write content to file.
	   if (fwrite($handle, $filecontent) === FALSE) {
	       $html .= "Cannot write to file ($filename)";
	       exit;
	   }

	}
	

	
	
	
	

}


if ($resetconfig=="true") {
	$html .= $filename."<br>";
	if (!unlink($filename)) {
   		$html .= "<b>Failed to delete $filename...</b><br><br>Check folder permissions<br><br>";
        die();
	} else {
   		$html .= "<b>Gallery config DELETED</b><br><br>";
   		$html .= "<a href='index.php'>Back to Admin</a><br><br>";
   		die();
	}

}

}


if (file_exists($filename)) {

$html .= '
<div class="gallerymenu" style="padding-left:100px">
<table class="gallerymenu">
<form name="labels" method="post" action="?galleryadmin=preferences">
';


$fc=file($filename);
$i=0;
foreach($fc as $line)
{
if (strstr($line,"$")) {
$variable = substr($line, 1, strpos($line, ' ')-1);
$value = substr($line, strpos($line, '"')+1);
$value = substr($value, 0, strpos($value, '"'));
if (strpos($line, "//")) {
$comment = substr($line, strpos($line, '//')+3);
$comment = substr($comment, 0, 200);
} else {$comment="";}


$html .= "<tr><td>".$i."</td><td>" . $variable . "</td>";
$html .= "<td><input size='25' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' name='value".$i."' value='" . $value . "'>";
$html .= "<input type='hidden' name='comment".$i."' value='" . $comment . "'>";
$html .= "<input type='hidden' name='variable".$i."' value='" . $variable . "'></td>";
$html .= "<td><a  tooltip='" . $comment . "' href='' onclick='javascript:return false;'>?</a></td></tr>";
}
$i++;
}
$i--;
$i--; // take another off because of added php code block lines in file


$html .= '
<tr><td></td><td></td><td>
<br><br>
    <input type="hidden" name="file" value="'.$filename.'">
    <input type="hidden" name="rows" value="'.$i.'">
    <input type="submit" name="Submit" value="SAVE CHANGES" class="button" >
    <input type=button class="button" value="CANCEL" onClick="history.go(-1)">
<td></td></td></tr>


</form>
</table>
</div>
';


if ($filelink != "galleryconfig" && $filelink != "gallerysetup") {
   $html .= "<br><br><br><a href='?galleryadmin=preferences&file=".$filelink."&resetconfig=true'>RESET to default (delete current config for this gallery)</a><br><br>";
}

} else {
   $html .= "<b>This Gallery does not have an individual configuration file.</b><br>";
   $html .= "What do you want to do?<br><br>";

   $html .= "<a href='?galleryadmin=preferences&file=".$filelink."&copyconfig=true'>Copy default config to this gallery and edit</a><br><br>";
   $html .= "<a href='#' onClick='history.go(-1)'>CANCEL</a>";
}




} // end if form not submitted

} // end if editing preferences

} else { // if not logged in
$html .=('Sorry, you are not logged in, this area is restricted to admin.');
$html .= "<br><input type=button class='button' value='Back' onClick='history.go(-1)'>";
}
$html .= "</div>";
//include ("galleryfooter.php"); 
?>


