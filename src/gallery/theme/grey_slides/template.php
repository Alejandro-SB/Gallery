<?php defined('_VALID_gallery_INCLUDE') or die('Direct access not allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?php echo gallery_page_title(); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="imagetoolbar" content="false" />
<?php if ($use_modrewrite) {
echo '<link href="'.$rewrite_base.'gallery/theme/'.$gallerytheme.'/style.css" rel="stylesheet" type="text/css" />';
} else {
echo '<link href="gallery/theme/'.$gallerytheme.'/style.css" rel="stylesheet" type="text/css" />';
} ?>

<?php include $galleryfilesdir."/include/css_hover_htc_behaviour.php"; ?>
<?php include $galleryfilesdir."/include/js_script_include.php"; ?>

</head><body>
<!-- JV2 Folder Gallery -->

<?php include $galleryfilesdir."/include/js_highslide_div.php"; ?>
<?php include $galleryfilesdir."/include/gallery_header.php"; ?>

<?php echo $breadcrumbs_code; ?>

<br /><br />
<table><tr><td valign="top">
<?php echo buildgallerymenu(); ?>
</td><td valign="top">
<?php echo $gallery_code; ?>
</td></tr></table>

<?php include $galleryfilesdir."/include/gallery_footer.php"; ?>

<!-- JV2 Folder Gallery -->
</body>
</html>