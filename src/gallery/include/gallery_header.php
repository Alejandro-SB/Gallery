<?php
if ($galleryheaderimg=="none") {
	if ($title_display != "0") {
	echo '<span class="gallerytitle">'.stripslashes($gallerytitle).'</span><br />';
	}
} else {
	echo '<img src="'.$galleryheaderimg.'"><br />';
}

?>