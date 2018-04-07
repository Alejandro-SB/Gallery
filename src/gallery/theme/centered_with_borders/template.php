<?php defined('_VALID_gallery_INCLUDE') or die('Direct access not allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?php echo gallery_page_title(); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="imagetoolbar" content="false" />
<link href="gallery/theme/<?php echo $gallerytheme; ?>/style.css" rel="stylesheet" type="text/css">

<?php include $galleryfilesdir."/include/css_hover_htc_behaviour.php"; ?>
<?php include $galleryfilesdir."/include/js_script_include.php"; ?>

</head><body>
<!-- JV2 Folder Gallery -->
<div align="center">
<div style="width: 740px; margin:0px auto 5px auto; background-color:#f9f9f9; padding: 5px; border:1px solid #686868;">

<?php include $galleryfilesdir."/include/js_highslide_div.php"; ?>
<?php include $galleryfilesdir."/include/gallery_header.php"; ?>

</div>


<div style="width: 740px; margin:0px auto; background-color:#f9f9f9; padding: 5px; border:1px solid #686868;">

<div style="margin:0px 0px 10px 0px; padding:3px; border-bottom:1px dashed #686868;text-align:left;">
<?php echo $breadcrumbs_code; ?>
</div>

<table width="100%"><tr><td valign="top" width="180">
<?php echo buildgallerymenu(); ?>
</td><td valign="top">
<?php echo $gallery_code; ?>
</td></tr></table>

</div>
<div style="width: 740px; margin:0px auto;">
<?php include $galleryfilesdir."/include/gallery_footer.php"; ?>

</div>
</div>
<!-- JV2 Folder Gallery -->
</body>
</html>