* gallerysetup.php *

This file contains all the main config for the gallery.
The setting here are "global" and they affect the whole gallery.


* galleryconfig.php *

The setting in this file contain more specific information on how to handle images.
When set in this file in this location, the setting are "global" and affect the whole gallery. If you make a copy of this file inside a image folder (a specific image folder which you want to change) you can then override the global setting and have that part of the gallery behave differently.

Some of the settings previously present in galleryconfig have been moved to the config file within the theme folder. the Setting contained in that file, are layout specific.

The settings in that "config.php" file within the theme folder can be overridden for a specific folder also (as described above)... but... do not make a copy of that config.php.
You should copy the setting from that file and insert them into the "galleryconfig.php" file that you have copied in the directory.




