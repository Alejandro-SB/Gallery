<?php
echo '<div class="credit">Powered by: <a target="_blank" href="http://foldergallery.jv2.net">JV2 Folder Gallery</a> '.$gallery_version.'&nbsp;<a href="';
if($use_modrewrite=="0") {} else {$gallery_code .= '/';}
echo 'gallery/admin/">Login</a></div>
';
?>