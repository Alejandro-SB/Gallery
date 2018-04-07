<?php

$src = stripslashes($_GET['watermark_img']);
$margin = stripslashes($_GET['watermark_margin_large']);
$mode =  stripslashes($_GET['watermark_mode']);
$position =  stripslashes($_GET['watermark_position']);
$pct = stripslashes($_GET['watermark_opacity']);
$path = pathinfo($src);

switch(strtolower($path["extension"])){
	case "jpeg":
	case "jpg":
			$src=imagecreatefromjpeg($src);
			break;
	case "gif":
			$src=imagecreatefromgif($src);
			break;
	case "png":
			$src=imagecreatefrompng($src);
			break;
	default:
			break;			
}

   	$w = imagesx($src);
   	$h = imagesy($src);


	// set position of watermark

	if ($position == "TL") {
		$dstx = $margin;
		$dsty = $margin;
	} else if ($position == "TR") {
		$dstx = $thumb_width - $margin - $w;
		$dsty = $margin;
	} else if ($position == "BL") {
		$dstx = $margin;
		$dsty = $thumb_height - $margin - $h;
	} else { // $position == "BR"
		$dstx = $thumb_width - $margin - $w;
		$dsty = $thumb_height - $margin - $h;
	}



		if($mode=="0") { // if mode "auto" - work out average color
		
		$red = array();	
		$green = array();
		$blue = array();

		$x2 = $dstx + $w;
		$y1 = $dsty;
		$y2 = $dsty + $h;

		for ($x=$dstx;$x<$x2;$x++) {
			for ($y=$y1;$y<$y2;$y++) {
				$colorindex = imagecolorat($thumb,$x,$y);
				// echo $x." ".$y."<br>";
				$colorrgb = imagecolorsforindex($thumb,$colorindex);	
				$red[] = $colorrgb['red'];
				$green[] = $colorrgb['green'];
				$blue[] = $colorrgb['blue'];
			}
		}

		// work out average
		$avg_red = ceil( array_sum($red) / count($red) );
		$avg_green = ceil( array_sum($green) / count($green) );
		$avg_blue = ceil( array_sum($blue) / count($blue) );
		
		$average = ($avg_red + $avg_green + $avg_blue) / 3;
			if ($average < $darkness_threshold) {
				$mode = "1"; // image is light - use DODGE
			} else {
				$mode = "2"; // image is dark - use BURN
			}
			
		}




   $i = 0; $j = 0; $k = 0; $rgb = 0;
   $d = array(); $s = array();
  
   for ($i=0; $i<$h; $i++) {
       for ($j=0; $j<$w; $j++) {
           $rgb = imagecolorat($thumb,$dstx+$j,$dsty+$i);
           $d[0] = ($rgb >> 16) & 0xFF;
           $d[1] = ($rgb >> 8) & 0xFF;
           $d[2] = $rgb & 0xFF;
          
           $rgb = imagecolorat($src,$j,$i);
           $s[0] = ($rgb >> 16) & 0xFF;
           $s[1] = ($rgb >> 8) & 0xFF;
           $s[2] = $rgb & 0xFF;

		if($average != null) { // invert logo if image dark
			if ($average >= $darkness_threshold) {
			} else {
			   $s[0] = 255 - $s[0];
			   $s[1] = 255 - $s[1];
			   $s[2] = 255 - $s[2];
			}		
		}


	      if ($mode=="1") { // Image DODGE
 		   $d[0] += min($s[0],0xFF-$d[0])*$pct/100;
		   $d[1] += min($s[1],0xFF-$d[1])*$pct/100;
		   $d[2] += min($s[2],0xFF-$d[2])*$pct/100;           
              }
	      if($mode=="2") { // Image BURN	      
		   $d[0] -= max($d[0]-$s[0],0)*$pct/100;
		   $d[1] -= max($d[1]-$s[1],0)*$pct/100;
		   $d[2] -= max($d[2]-$s[2],0)*$pct/100;  	      
              } 
	      if($mode=="3") { // Image Paste
 		   $d[0] = $s[0];
		   $d[1] = $s[1];
		   $d[2] = $s[2];  	      
              }  
	      if($mode=="4") { // AVERAGE
 		   $d[0] = ($s[0] + $d[0])/2;
		   $d[1] = ($s[1] + $d[1])/2;
		   $d[2] = ($s[2] + $d[2])/2;  	      
              }  
	      if($mode=="5") { // Average Fade (opacity logo)
 		   $d[0] = ($s[0]*$pct/100 + $d[0])/2;
		   $d[1] = ($s[1]*$pct/100 + $d[1])/2;
		   $d[2] = ($s[2]*$pct/100 + $d[2])/2;  	      
              }  
	      if($mode=="6") { // Average Fade (opacity background)
 		   $d[0] = ($s[0] + $d[0]*$pct/100)/2;
		   $d[1] = ($s[1] + $d[1]*$pct/100)/2;
		   $d[2] = ($s[2] + $d[2]*$pct/100)/2;  	      
              }               
	      if($mode=="7") { // Lighten
 		   $d[0] += min($s[0]*$pct/100,0xFF-$d[0]);
		   $d[1] += min($s[1]*$pct/100,0xFF-$d[1]);
		   $d[2] += min($s[2]*$pct/100,0xFF-$d[2]);  	      
              }                

             
           imagesetpixel(
               $thumb, $dstx+$j, $dsty+$i,
               imagecolorallocate($thumb,$d[0],$d[1],$d[2])
           );
       }
   }


	
?>