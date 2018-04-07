<?php

$gestor = fopen($video_file, "rb");
$contenido = fread($gestor, 28);
$contenido2 = fread($gestor, 7);
fclose($gestor);
$hex = bin2hex($contenido2);
if($hex == "01766f72626973")
{
if($audio_player == "1") //AUDIO PLAYER
{
echo '<link href="jplayer/skin/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="jplayer/js/jquery.jplayer.min.js"></script>
<script type="text/javascript">
<!--
$(document).ready(function(){

	// Local copy of jQuery selectors, for performance.
	var jpPlayTime = $("#jplayer_play_time");
	var jpTotalTime = $("#jplayer_total_time");

	$("#jquery_jplayer").jPlayer({
		ready: function () {
			this.element.jPlayer("setFile", "'.$videofile.'", "'.$video_file.'").jPlayer("stop");
		},
		oggSupport: true
	})
	.jPlayer("onProgressChange", function(loadPercent, playedPercentRelative, playedPercentAbsolute, playedTime, totalTime) {
		jpPlayTime.text($.jPlayer.convertTime(playedTime));
		jpTotalTime.text($.jPlayer.convertTime(totalTime));
	})
	.jPlayer("onSoundComplete", function() {
		this.element.jPlayer("play");
	});
});
-->
</script>
';
}

}
?>