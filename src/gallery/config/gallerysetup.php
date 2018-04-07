<?php 
$username = "admin";  // Your username for logging in to change gallery properties
$password = "password";  // Your password for logging in to change gallery properties
$gallerytheme = "dark_vertical_menu";  // Gallery Theme to use - use the foldername of theme <br> (if theme config has different image sizes to your last theme you should empty your cache)
$menutype = "1";  // Menu type: <br> 1 = css dropdown <br> 2 = normal UL list
$displaybreadcrumbs = "1";  // Display the currently viewing menu... <br> 1 = show <br> 0 = dont show
$gallerytitle = "JV2 Folder Gallery";  // Gallery title for standalone mode
$title_display = "1";  // 1= also print on page as title <br> 0= use title only for browser window  
$galleryheaderimg = "none";  // If headerimg is used, title is ignored for page display - "none" to use title
$galleryfolder = "images";  // Name of base folder where galleries are stored
$defaultfolderimage = "folder.gif";  // default image to be displayed for folder with no images
$folderthumbnail = "1";  // 1= use images inside folder as the folder image <br> 0= always display the folder icon
$language = "espanol";  // language to use on links (basename of file in lang dir)
$get_page = "pagina";  // Name for GET page in URL
$get_folder = "categoria";  // Name for GET folder in URL
$get_image = "imagen";  // Name for GET image in URL
$cachethumbs = "1";  // Set to 1 to cache images <br>- faster pages <br>- less load for server <br>(0 to turn off)
$cachefolder = "thumbcache";  // The location of cached images
$cachequality = "75";  // The jpg quality of the cached thumbnails <br>0 = worst - small file <br>100 = best - large file
$text_auto_color = "100";  // this sets the amount of auto color change for text  0-255
$shadow_auto_color = "70";  // this sets the amount of auto color change for shadow 0-255
$darkness_threshold = "100";  // sets the average color for image bg to be treated as dark or light 0-255
$display_img_count = "1";  // 1= display number of images in folder <br>0=do not show
$display_sub_count = "1";  // 1= display number of subfolders in folder <br>0=do not show
$ordernumber_separator = "__";  // set the characters to separate order number from folder title. - anything before this will be ignored for display.
$debug_mode = "1";  // php error_reporting() -- 0= hide errors  1= display errors
$session_menu_save = "1";  // set to 1 to save menu into session var. - speeds up menu generation for large sites <br> 0=regenerate menu everytime - good for testing and while developing
$use_modrewrite = "0";  // 0= normal url variable passing with ? <br> 1= use modrewrite and htaccess to generate better urls
$rewrite_base = "";  // mod_rewrite RewriteBase - see apache mod_rewrite docs.
$get_slideshow = "presentacion";  // Name for GET slideshow in URL
$display_slideshow_link = "1";  // Display the slideshow link... <br> 1 = show <br> 0 = dont show
$slideshow_display_time = "4";  // Time to display each slideshow item in seconds
$pagetitle_separator = ">>";  // Characters used to separate names in page title
$video_player = "1";  // Use HTML5 extended video player
$audio_player = "1";  // Use HTML5 extended audio player 
?>