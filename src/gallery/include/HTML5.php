<?php

//Everything related to HTML5 video & audio is here
$gestor = fopen($video_file, "rb"); //This lines check if file is Vorbis or Theora **
$contenido = fread($gestor, 28);
$contenido2 = fread($gestor, 7);
fclose($gestor);
$hex = bin2hex($contenido2);
if($hex == "01766f72626973") //** If its Vorbis...
{
	if($audio_player == "1") //And audio player is enabled, load it!
	{
		$nombrecorrecto = str_replace("_", " ", $imagename);
		$gallery_code .= '<br/><br/>
		<div id="jquery_jplayer"></div>

		<div class="jp-single-player">
			<div id="jplayer_playlist" class="jp-playlist">
				<ul>
					<li>'.$nombrecorrecto.'</li>
				</ul>
			</div>
				<div class="jp-interface">
				<ul class="jp-controls">
					<li><a href="#" id="jplayer_play" class="jp-play" tabindex="1">play</a></li>
					<li><a href="#" id="jplayer_pause" class="jp-pause" tabindex="1">pause</a></li>
					<li><a href="#" id="jplayer_stop" class="jp-stop" tabindex="1">stop</a></li>
					<li><a href="#" id="jplayer_volume_min" class="jp-volume-min" tabindex="1">min volume</a></li>
					<li><a href="#" id="jplayer_volume_max" class="jp-volume-max" tabindex="1">max volume</a></li>
					</ul>
				<div class="jp-progress">
					<div id="jplayer_load_bar" class="jp-load-bar">
						<div id="jplayer_play_bar" class="jp-play-bar"></div>
					</div>
				</div>
				<div id="jplayer_volume_bar" class="jp-volume-bar">
					<div id="jplayer_volume_bar_value" class="jp-volume-bar-value"></div>
				</div>
				<div id="jplayer_play_time" class="jp-play-time"></div>
				<div id="jplayer_total_time" class="jp-total-time"></div>
			</div>
		</div>

		';
	}
	else // If vorbis but audio player not enabled, load default player.
	{
		$gallery_code .= '<br/><br/>
		<audio src="'.$video_file.'" preload controls="controls">
		';
	}
}
elseif($hex == "807468656f7261") //If Theora
{
	if($video_player == "1") //And Video player is enabled, load player
	{
		$nombrecorrecto = str_replace("_", " ", $imagename);
		$gallery_code .= '<br/><br/>
		<link rel="stylesheet" href="projekktor/maccaco/style.css" type="text/css" media="screen" />
   		<script type="text/javascript" src="projekktor/jquery.min.js"></script> <!-- Load jquery -->
   		<script type="text/javascript" src="projekktor/projekktor.min.js"></script>  <!-- load projekktor -->
		<video id="player_a" class="projekktor" class="projekktor" title="'.$nombrecorrecto.'" width="550" height="412" controls preload>
		<source src="'.$video_file.'" type="video/ogg"/>
		</video>

		<script type="text/javascript">
		$(document).ready(function() {
	   	 projekktor("#player_a"); // instantiation
		});
    		</script>
		';
	}	
	else //If not enabled, load default player
	{
		$gallery_code .= '<br/><br/>
		<video id="video" src="'.$video_file.'" height="412" width="550" controls preload />
		';

	}
}
elseif($file_ext == "webm") //If its a webm file
{
		if($video_player == "1") //And video player is enabled, load it!
		{
			$nombrecorrecto = str_replace("_", " ", $imagename);
			$gallery_code .= '<br/><br/>
			<link rel="stylesheet" href="projekktor/maccaco/style.css" type="text/css" media="screen" />
   			<script type="text/javascript" src="projekktor/jquery.min.js"></script> <!-- Load jquery -->
	   		<script type="text/javascript" src="projekktor/projekktor.min.js"></script>  <!-- load projekktor -->
			<video id="player_a" class="projekktor" class="projekktor" title="'.$nombrecorrecto.'" width="550" height="412" controls preload>
			<source src="'.$video_file.'" type="video/webm"/>
			</video>

			<script type="text/javascript">
			$(document).ready(function() {
		   	 projekktor("#player_a"); // instantiation
			});
	    		</script>
			';
		}	
		else //If not enabled, load default player
		{
			$gallery_code .= '<br/><br/>
		<video id="video" src="'.$video_file.'" height="412" width="550" controls preload />
		';

		}
}
else //If file is not Vorbis, Theora or Webm, display a error message
{
	$gallery_code .= '<br/><br/>
	There was a problem loading file. Make sure file is not corrupted (download and test it) and its extension is right (.ogg, .ogv, .ogm, .webm)
	';
}

?>