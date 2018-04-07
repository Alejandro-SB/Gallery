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

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
     $html .= "The file <b>". basename( $_FILES['uploadedfile']['name']). "</b> has been uploaded<br /><br />";


$html .= $target_path."<br>";

$absolute_path  = str_replace("admin","",getcwd() );
$absolute_path .= str_replace("../","",$folder."/".basename($_FILES['uploadedfile']['name']) );

$zip = zip_open($absolute_path);
//$zip = zip_open($target_path);

if ($zip) {

$html .= "The Following files have been extracted: <br>";

$html .= "<table border='0' cellspacing='5'>";
$html .= "<tr>";
$html .= "<th>Filename:</th>";
$html .= "<th>Size:</th>";
$html .= "<th>Written:</th>";
$html .= "</tr>";

	while ($zip_entry = zip_read($zip)) {
      $html .= "<tr>";

		$html .= "<td>" . zip_entry_name($zip_entry) . "</td>";
		$html .= "<td>" . zip_entry_filesize($zip_entry) . "</td>";

		if (zip_entry_open($zip, $zip_entry, "r")) {
			
			$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

// write file
			
		$fp=fopen($folder."/".zip_entry_name($zip_entry),"w");
        fwrite($fp,$buf);

		$html .= "<td>OK</td>";

			zip_entry_close($zip_entry);
		$html .= "</tr>";
		}


	}


$html .= "</table>";
	
	zip_close($zip);

} else $html .=('<br>Cannot open zip file / file not found or unzip command not available<br>');


unlink($target_path);
$html .= "<br>ZIP file deleted<br><br>";

$html .= "<br /><br /><a href='?galleryadmin=uploadzip&folder=".str_replace("../images/","",$folder)."'>Upload another ZIP to this folder</a><br />";

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
 <form enctype="multipart/form-data" method="post" action="?galleryadmin=uploadzip">

Select ZIP file to upload all gallery images at once<br><br>

<input type="hidden" name="target_path" value="'.$target_path.'">
 
   <input type="file" size="32" name="uploadedfile" value="">
   <input type="submit" name="Submit" value="upload">
 </form>
';


}

 } else {
$html .=('Sorry, you are not logged in, this area is restricted to admin.');
$html .= "<br><input type=button class='button' value='Back' onClick='history.go(-1)'>";
}



?>