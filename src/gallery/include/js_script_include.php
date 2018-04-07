<?php
if ($popupimage == "4") { // if using js_highslide
	echo '<script type="text/javascript" src="';
	if($use_modrewrite=="1") { echo  '/'; }
	echo 'highslide/highslide/highslide.js"></script>';
	echo '<script type="text/javascript">    ';
	echo "    hs.graphicsDir = '";
	if($use_modrewrite=="1") { echo  '/'; }
	echo "highslide/highslide/graphics/';";
	echo "    hs.outlineType = 'outer-glow';";
	echo '    window.onload = function() {';
	echo '        hs.preloadImages();';
	echo '    }';
	echo '</script>';
} // end if using js_highslide

if ($popupimage == "7") { // if using Lightbox JS v2x

	if($use_modrewrite=="1") { 
		$lightboxpath = '/lightbox/';
	} else {
		$lightboxpath = 'lightbox/';
	}
	
	echo '<script type="text/javascript" src="'.$lightboxpath.'js/prototype.js"></script>';
	echo '<script type="text/javascript" src="'.$lightboxpath.'js/scriptaculous.js?load=effects,builder"></script>';
	echo '<script type="text/javascript" src="'.$lightboxpath.'js/lightbox.js"></script>';
	echo '<link rel="stylesheet" href="'.$lightboxpath.'css/lightbox.css" type="text/css" media="screen" />';

} // end if using Lightbox JS

?>