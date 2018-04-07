<?php 


$gallery_code .= '<div class="gallerypagelinks">';
// if first link is needed
if($gallerypage > 1) {
	$previous = $gallerypage -1;
	$gallery_code .= '|< <a href="'.$currenturl;
		if (isset($_GET[$get_folder])) {$gallery_code .= $get_folder.'='.$getfolder.'&amp;';}
	$gallery_code .= $get_page.'=1">'.$lbl_first.'</a> ';
}
// if previous link is needed
if($gallerypage > 2) {
	$previous = $gallerypage -1;
	$gallery_code .= '<< <a href="'.$currenturl;
		if (isset($_GET[$get_folder])) {$gallery_code .= $get_folder.'='.$getfolder.'&amp;';}
	$gallery_code .= $get_page.'='.$previous.'">'.$lbl_previous.'</a> ';
}
// print page numbers

$gallerypages = ceil($total / $gallerypageimages);
if ($gallerypages>=2) {
$gallery_code .= "| ".$lbl_page.": ";
$p=1;
$minpage = $gallerypage - ($showpages+1);
$maxpage = $gallerypage + ($showpages+1);
if ($gallerypage > ($showpages+1) && $showpages > 0) {$gallery_code .= " ... ";}
while ($p <= $gallerypages) {
	if ($p > $minpage && $p < $maxpage) {
		if ($gallerypage == $p) {
			$gallery_code .= " <b>".$p."</b>";
		} else {
			$gallery_code .= ' <a href="'.$currenturl;
				if (isset($_GET[$get_folder])) {$gallery_code .= $get_folder.'='.$getfolder.'&amp;';}
			$gallery_code .= $get_page.'='.$p.'">'.$p.'</a>';
		}
	}
$p++;
}
if ($maxpage < $gallerypages && $showpages > 0) {$gallery_code .= " ... ";}
}
// if next link is needed
if($end < $total) {
	$next = $gallerypage +1;
	if ($next != ($p-1)) {
		$gallery_code .= ' | <a href="'.$currenturl;
			if (isset($_GET[$get_folder])) {$gallery_code .= $get_folder.'='.$getfolder.'&amp;';}
		$gallery_code .= $get_page.'='.$next.'">'.$lbl_next.'</a> >>';
	} else {$gallery_code .= ' | ';}
}
// if last link is needed
if($end < $total) {
	$last = $p -1;
	$gallery_code .= ' <a href="'.$currenturl;
		if (isset($_GET[$get_folder])) {$gallery_code .= $get_folder.'='.$getfolder.'&amp;';}
	$gallery_code .= $get_page.'='.$last.'">'.$lbl_last.'</a> >|';
}
$gallery_code .= '</div>';


?>