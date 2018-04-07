<?php

//For HTML5 video & audio playing, see HTML5.php

$video_file = $galleryfolder."/".$getimage;

// Play quicktime video file
if( in_array( $file_ext , $quicktime_vid ) ) { 
$gallery_code .= "<br/><br/>
<object classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B' width='".$video_width."'
height='".$video_height."' codebase='http://www.apple.com/qtactivex/qtplugin.cab'>
<param name='src' value='".$video_file."'>
<param name='autoplay' value='".$video_autoplay."'>
<param name='controller' value='true'>
<param name='loop' value='false'>
<EMBED src='".$video_file."' width='".$video_width."' height='".$video_height."' autoplay='".$video_autoplay."' 
controller='true' loop='false' pluginspage='http://www.apple.com/quicktime/download/'>
</EMBED></OBJECT>
";
}


// Play flash file
if( in_array( $file_ext , $flash_vid ) ) { 
$gallery_code .= '<br/><br/>
<object width="'.$video_width.'" height="'.$video_height.'" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
<param value="'.$video_file.'" name="movie" />
<param value="high" name="quality" />
<embed width="'.$video_width.'" height="'.$video_height.'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="'.$video_file.'" />
</object>
';
}




// Play DivX file
if( in_array( $file_ext , $divx_vid ) ) {
$gallery_code .= '<br/><br/>
<object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616"
width="'.$video_width.'" height="'.$video_height.'"
codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">
<param name="src" value="'.$video_file.'"/>
<embed type="video/divx" src="'.$video_file.'"
width="'.$video_width.'" height="'.$video_height.'"
pluginspage="http://go.divx.com/plugin/download/">
</embed>
</object>
';
}

?>
