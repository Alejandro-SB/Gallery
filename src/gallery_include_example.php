<?php
/*

This file demonstrates how you should use the gallery when you want to include it in your existing pages.
You will need to rename the index.php to something else or delete it, so you dont copy over your existing one...
Then copy the gallery structure to your root dir of your website.

Copy the php include statement to your website where you want the gallery displayed...

===============================================
FOR THE GALLERY TO WORK IN INCLUDE MODE, YOU NEED TO SELECT ONE OF THE "include_mode" THEMES.
THE THEME CONFIG DEFINES THAT THE GALLERY IS WORKING IN INCLUDE MODE, 
AND THOSE THEMES DO NOT INCUDE HTML PAGE DEFINITIONS ETC.
If you want to use a different theme in your include...
please have a look at the theme files, and figure out what you need to do.
i.e. remove html headers etc from the template, and set the include mode to true.
===============================================

You will then need to include the code between the HEAD tags below to your own header
include the gallery css - if you want to style the gallery

You need to include the csshover.htc file in the head also... 
if you want to display the dropdown menu and have it display correctly in Internet Explorer.

If you decide to use the theme "include_mode_no_menu" as your theme,
You should point the css in the header to that folder instead of the "include_mode" in this example.

To make use of the Lightbox or Highslide Javascript effects... see read me file... and download the packages.
Then include the javascripts in your own header according to their instructions.

*/
?>

<html>
<head>


<!-- JV2 Folder Gallery (header) -->
<link href="gallery/theme/include_mode/style.css" rel="stylesheet" type="text/css">
<!--[if IE]>
<style type="text/css" media="screen">
/* csshover.htc file V1.21.041022 available for download from: http://www.xs4all.nl/~peterned/csshover.html */
body{behavior:url(gallery/include/csshover.htc);}
#menu ul li{float:left;height:1%;}
#menu ul li a{height:1%;}
</style>
<![endif]-->
<!-- JV2 Folder Gallery (header) -->


</head>


<body>


<?php include("gallery/gallery.php"); ?>


</body>
</html>
