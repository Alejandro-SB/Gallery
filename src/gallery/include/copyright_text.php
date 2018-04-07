<?php

	$text_font = $_GET['copyright_font'];
	$text_angle = $_GET['copyright_angle'];	
	$text = $_GET['copyright_text'];
	$font_size = $_GET['copyright_size'];
	$margin = $_GET['copyright_margin'];
	$position =  $_GET['copyright_position'];
	$text_color =  $_GET['copyright_color'];
	$shadow_distance =  $_GET['copyright_shadow_distance'];
	$shadow_color =  $_GET['copyright_shadow_color'];


	$textarea = ttfbbox($font_size,$text_angle,$text_font,$text); // size of text area

	$height_text = $textarea['height'];
	$width_text = $textarea['width'];



	// set position of textarea

	if($text_angle >= 0 && $text_angle <= 90) {
		if ($position == "TL") {
			$pos_x = $margin; 
			$pos_y = $margin + $height_text;
		} else if ($position == "TR") {
			$pos_x = $thumb_width - $margin - $width_text;
			$pos_y = $margin + $height_text;
		} else if ($position == "BL") {
			$pos_x = $margin; 
			$pos_y = $thumb_height - $margin;
		} else if ($position == "BR") {
			$pos_x = $thumb_width - $margin - $width_text;
			$pos_y = $thumb_height - $margin;
		}	
		$pos_x += ($font_size * $text_angle / 90);
	}
	
	if($text_angle >= 270 && $text_angle <= 360) {
		if ($position == "TL") {
			$pos_x = $margin; 
			$pos_y = $margin + ($font_size * $text_angle / 360);
		} else if ($position == "TR") {
			$pos_x = $thumb_width - $margin - $width_text;
			$pos_y = $margin + ($font_size * $text_angle / 360);
		} else if ($position == "BL") {
			$pos_x = $margin; 
			$pos_y = $thumb_height - $margin - $height_text + ($font_size * $text_angle / 360);
		} else if ($position == "BR") {
			$pos_x = $thumb_width - $margin - $width_text;
			$pos_y = $thumb_height - $margin - $height_text + ($font_size * $text_angle / 360);
		}		

	}








if(($text_angle >= 0 && $text_angle <= 90) || ($text_angle >= 270 && $text_angle <= 360)) {

if($text_color == "" || $text_color == null ) {


	$red = array();	
	$green = array();
	$blue = array();

	$x2 = $pos_x + $width_text;
	$y1 = $pos_y;
	$y2 = $pos_y + $height_text;

	for ($x=$pos_x;$x<$x2;$x++) {
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

	// invert color
	//$red = 255 - $avg_red;	
	//$green = 255 - $avg_green;
	//$blue = 255 - $avg_blue;

	$average = ($avg_red + $avg_green + $avg_blue) / 3;
	if ($average < $darkness_threshold) {
		$red = min($avg_red + $text_auto_color, 255);	
		$green = min($avg_green + $text_auto_color, 255);
		$blue = min($avg_blue + $text_auto_color, 255);
	} else {
		$red = max($avg_red - $text_auto_color, 0);	
		$green = max($avg_green - $text_auto_color, 0);
		$blue = max($avg_blue - $text_auto_color, 0);
	}





} else {
	$rgb = hex2rgb($text_color,false);
	$red = $rgb[0];	
	$green = $rgb[1];
	$blue = $rgb[2];
}
	$color = ImageColorAllocate($thumb,$red,$green,$blue);



	if($shadow_distance > 0) {
		$shadow_x = $pos_x + $shadow_distance;
		$shadow_y = $pos_y + $shadow_distance;

		if($shadow_color == "" || $shadow_color == null ) {
			if ($average < $darkness_threshold) {
				$red = min($red + $shadow_auto_color, 255);	
				$green = min($green + $shadow_auto_color, 255);
				$blue = min($blue + $shadow_auto_color, 255);
			} else {
				$red = max($red - $shadow_auto_color, 0);	
				$green = max($green - $shadow_auto_color, 0);
				$blue = max($blue - $shadow_auto_color, 0);
			}	
		} else {
			$rgb = hex2rgb($shadow_color,false);
			$red = $rgb[0];	
			$green = $rgb[1];
			$blue = $rgb[2];
		}
	$shadow_color = ImageColorAllocate($thumb,$red,$green,$blue);	
	imagettftext($thumb,$font_size,$text_angle,$shadow_x,$shadow_y,$shadow_color,$text_font,$text);

	}

	
	imagettftext($thumb,$font_size,$text_angle,$pos_x,$pos_y,$color,$text_font,$text);

}




function ttfbbox($size, $angle, $font, $text)
   {
         // Get the boundingbox from imagettfbbox(), which is correct when angle is 0
         $bbox = imagettfbbox($size, 0, $font, $text);

         // Rotate the boundingbox
         $angle = $angle/ 180 * pi();
         for ($i=0; $i<4; $i++)
         {
                 $x = $bbox[$i * 2];
                 $y = $bbox[$i * 2 + 1];
                 $bbox[$i * 2] = cos($angle) * $x - sin($angle) * $y;  // X
                 $bbox[$i * 2 + 1] = sin($angle) * $x + cos($angle) * $y; // Y
         }
         // Variables which tells the correct width and height
       $bbox["left"] = 0- min($bbox[0],$bbox[2],$bbox[4],$bbox[6]);
       $bbox["top"] = 0- min($bbox[1],$bbox[3],$bbox[5],$bbox[7]);
       $bbox["width"] = max($bbox[0],$bbox[2],$bbox[4],$bbox[6]) -  min($bbox[0],$bbox[2],$bbox[4],$bbox[6]);
       $bbox["height"] = max($bbox[1],$bbox[3],$bbox[5],$bbox[7]) - min($bbox[1],$bbox[3],$bbox[5],$bbox[7]);
      
       return $bbox;
   }










function hex2rgb($hex, $asString = true)
   {
       // strip off any leading #
       if (0 === strpos($hex, '#')) {
           $hex = substr($hex, 1);
       } else if (0 === strpos($hex, '&H')) {
           $hex = substr($hex, 2);
       }     

       // break into hex 3-tuple
       $cutpoint = ceil(strlen($hex) / 2)-1;
       $rgb = explode(':', wordwrap($hex, $cutpoint, ':', $cutpoint), 3);

       // convert each tuple to decimal
       $rgb[0] = (isset($rgb[0]) ? hexdec($rgb[0]) : 0);
       $rgb[1] = (isset($rgb[1]) ? hexdec($rgb[1]) : 0);
       $rgb[2] = (isset($rgb[2]) ? hexdec($rgb[2]) : 0);

       return ($asString ? "{$rgb[0]} {$rgb[1]} {$rgb[2]}" : $rgb);
   }



	
?>