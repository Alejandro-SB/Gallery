<?php

// CLEAN GET VARS
foreach($_GET as $var => $val) {
	$_GET[$var] = htmlspecialchars(strip_tags($val));
}

$filename = null;
if (isset($_GET['file'])) {
  $filename = (get_magic_quotes_gpc()) ? $_GET['file'] : addslashes($_GET['file']);
}


$valid = strpos($filename,"images/");
if($valid === false) { die(); }
$notvalid = strpos($filename,"../");
if($notvalid === true) { die(); }

$clientfilename = explode("/",$filename);
$i = sizeof($clientfilename)-1;
$clientfilename = $clientfilename[$i];



		@set_time_limit(0);
	
		$chunksize = 1*(1024*1024); // how many bytes per chunk 
		$buffer = ''; 
		$handle = fopen($filename, 'rb');
		if ($handle === false) {
			header("Content-Type: text/html");
			die('Could not open '.$filename.'!');
		} 

		header("Content-Type: application/octet-stream");
		header('Content-Disposition: attachment; filename="' . $clientfilename . '"');
		header("Content-Description: ".trim(htmlentities($clientfilename)));
	    	header("Transfer-Encoding: binary");
	    	header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("Connection: close");
		header("Content-Length: ".filesize($filename));

		while (!feof($handle)) { 
			$buffer = fread($handle, $chunksize); 
			print $buffer; 
			if (connection_aborted()) break;
		}


		@fclose($handle); 

?>