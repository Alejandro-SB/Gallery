<!-- JV2 Folder Gallery -->
<?php defined('_VALID_gallery_INCLUDE') or die('Direct access not allowed.'); ?>
<?php include $galleryfilesdir."/include/js_highslide_div.php"; ?>

<?php echo $breadcrumbs_code; ?>
<hr>
<table><tr><td valign="top">
<?php echo buildgallerymenu(); ?>
</td><td valign="top">
<?php echo $gallery_code; ?>
</td></tr></table>

<?php include $galleryfilesdir."/gallery_footer.php"; ?>

<!-- JV2 Folder Gallery -->
