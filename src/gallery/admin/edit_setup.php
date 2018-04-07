<?php
// if user logged in
if (isset($_SESSION['username'])) {

$filename = "../config/gallerysetup.php";


if (isset($_POST['Submit'])) { // if form has been submitted



if (!is_writable($filename)) {
	// Check for safe mode
	if( ini_get('safe_mode') ) {
	   $html .= 'Your webserver has PHP safe mode restrictions in effect<br>';
	   $html .= 'You must set chmod 777 on the file manually with FTP<br><br>';
	   $html .= 'Attempting to set write permissions via FTP...<br>';
	   chmodftp($galleryrootdir,$filename,777);
	   $html .= '<br>If no errors, then Chmod successfull via FTP...<br><br>';
	   $html .= 'Please click RETRY and then OK to resend data<br>';
	   $html .= "<input type=button class='button' value='RETRY' onClick='window.location.reload( false )'>";
	   die();
	}else{
	   chmod($filename, 0777);
	}
	
}
}



$html .= '

<script type = "text/javascript">
//<![CDATA[
function showTip( e ) {
	// if client does not support object scripting get out of function
	if ( !document.getElementById && !document.createElement ) {
		return;
	} //-- ends if statement

	if ( !e ) {
			var tipobj = window.event.srcElement;
		} else {	// if one has been sent to this function
			var tipobj = e.target;
	} //-- ends if...else statement
	// if the mouse over was not been placed on a link, get out
	if ( tipobj.tagName != "A" ) return;
	// obtain the tip from the specific link
	var tip = null;
	tip = tipobj.getAttribute( "tooltip" );

	// if link does not have a tooltip element, get out..
	if (  tip == null || tip == "undefined" || tip == "")
		return;
/////////////////////////
	//tip += "<br><a href=\'obj.href\'>" + obj.href+"</a>";
////////////////
	var tipx = 0;	// x coordinate
	var tipy = 0;	// y coordinate
	// reference to the link
	var element = document.getElementById( \'tips\' );
	if ( document.all ) {
		tipx =  event.clientX;
		tipy =  event.clientY;

		element.style.display = "block";
		element.style.left = tipx -210;
		element.style.top = tipy -10;
		element.innerHTML = tip;
	} else {

		tipx = e.pageX -220;
		tipy = e.pageY -10;
		element.style.display = "block";
		element.style.left = tipx + \'px\';
		element.style.top = tipy + \'px\';
		element.innerHTML = tip;
		return;
	} //-- ends if...else statement
} //-- ends function showTip

function hideTip() {
	document.getElementById( \'tips\' ).style.display = "none";
} //--ends function hideTip

document.onmouseover = showTip;		// call this function on hover
document.onmouseout = hideTip;		// call this function on mouseout
//]]>





// this is an extra function that I thought you should have 
// so that it works on IE to highlight the fields as well
function highLight( element ) {
	// change background color
	element.style.backgroundColor = "lightyellow";
	element.style.color = "black";
} //-- ends function hightlit

// this function trims the input from left and right..
// so user input is cleaned before submission
function trimIt( element ) {
	// change background color to white
	element.style.backgroundColor = "white";
	// obtain input field value
	element = element.value;
	// remove any spaces
	while( element.charAt( 0 ) == \' \' ) {
		element = element.substring( 1 );
	} //-- ends while
	while( element.charAt( element.length - 1 ) == \' \' ) {
		element = element.substring( 0, element.length - 1 );
	} //-- ends while
	return element;
} //-- ends function trimit
</script>


';


$html .= '<div id = "tips"></div>';




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$filecontent = "<?php \n";

if (isset($_POST['Submit'])) { // if form has been submitted

$rows = $_POST['rows'] -1;

for ($i=0;$i<=$rows;$i++) {

$filecontent .= '$'.$_POST['variable'.$i].' = "'.$_POST['value'.$i]."\";  // ".trim(stripslashes($_POST['comment'.$i]),"\n")."\n";

}
$filecontent .= "?>";


if (is_writable($filename)) {

   // open file in W+ mode to empty it and wrtie from beginning
   if (!$handle = fopen($filename, 'w+')) {
         $html .= "Cannot open file ($filename)";
         exit;
   }

   // Write content to file.
   if (fwrite($handle, $filecontent) === FALSE) {
       $html .= "Cannot write to file ($filename)";
       exit;
   }

   $html .= "Data <b>saved</b> in '".$filename."'";

   fclose($handle);

} else {
   $html .= "The file $filename is not writable";
}


} else  {	// if form hasn't been submitted


$html .= "<p class='gallerymenu' style='font-size:16px;font-weight:bold;'>Gallery Setup</p>";

$filelink = $filename;


if (file_exists($filename)) {




$html .= '<form name="labels" method="post" action="?galleryadmin=setup" onSubmit="return checkLabelsForm(this);">';




if (file_exists("../theme")) {
$style_dir = dir("../theme");
$theme_array = array();
while(($style = $style_dir->read()) !== false) {
      if($style=="." || $style==".." || $style=="DEFAULT_ICONS") { } else {
      array_push($theme_array, $style);
      }
}
sort($theme_array);
}

if (file_exists("../config/lang")) {
$lang_dir = dir("../config/lang");
$lang_array = array();
while(($lang = $lang_dir->read()) !== false) {
      if($lang=="." || $lang==".." || $lang=="language instructions.txt") { } else {
      array_push($lang_array, $lang);
      }
}
sort($lang_array);
}


$setup_array = array();
$fc=file($filename);
$i=0;
foreach($fc as $line)
{
if (strstr($line,"$")) {
$variable = substr($line, 1, strpos($line, ' ')-1);
$value = substr($line, strpos($line, '"')+1);
$value = substr($value, 0, strpos($value, '"'));
if (strpos($line, "//")) {
$comment = substr($line, strpos($line, '//')+3);
$comment = substr($comment, 0, 200);
} else {$comment="";}

array_push($setup_array,array($variable,$value,$comment));


}
$i++;
}
$i--;
$i--; // take another off because of added php code block lines in file

$html .= "<div class='gallerymenu' style='padding-left:100px'>";
$html .= "<b>Admin account:</b>";
$html .= "<table class='gallerymenu'>";

// Username
$html .= "<tr><td>Username:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable0' value='". $setup_array[0][0] ."' type='hidden'>";
$html .= "<input name='value0' value='". $setup_array[0][1] ."' size='15' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment0' value='". $setup_array[0][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[0][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// Password
$html .= "<tr><td>Password:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable1' value='". $setup_array[1][0] ."' type='hidden'>";
$html .= "<input name='value1' value='". $setup_array[1][1] ."' size='15' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment1' value='". $setup_array[1][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[1][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

$html .= "</table><br><br>";

$html .= "<b>General config:</b>";
$html .= "<table class='gallerymenu'>";


// Gallery Title
$html .= "<tr><td>Gallery Title:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable5' value='". $setup_array[5][0] ."' type='hidden'>";
$html .= '<input name="value5" value="'. stripslashes($setup_array[5][1]) .'" size="30" type="text" onFocus = "javascript: void( highLight(this) );" onBlur = "javascript: this.value = trimIt( this ) ;" >';
$html .= "<input name='comment5' value='". $setup_array[5][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[5][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// pagetitle_separator
$html .= "<tr><td>Page Title separator:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable31' value='". $setup_array[31][0] ."' type='hidden'>";
$html .= '<input name="value31" value="'. $setup_array[31][1] .'" size="3" type="text" onFocus = "javascript: void( highLight(this) );" onBlur = "javascript: this.value = trimIt( this ) ;" >';
$html .= "<input name='comment31' value='". $setup_array[31][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[31][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// Gallery Title DISPLAY
$html .= "<tr><td>Display Title:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable6' value='". $setup_array[6][0] ."' type='hidden'>";
$html .= "<select name='value6' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[6][1] == "1"){$html .= " selected";} $html .= " >Display</option>";
$html .= "<option value='0'"; if($setup_array[6][1] == "0"){$html .= " selected";} $html .= " >Do Not display</option>";
$html .= "</select>";
$html .= "<input name='comment6' value='". $setup_array[6][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[6][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";


// Language
$html .= "<tr><td>Language:&nbsp;&nbsp;&nbsp;</td><td>";

$html .= "<input name='variable11' value='". $setup_array[11][0] ."' type='hidden'>";
$html .= "<select name='value11' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
foreach($lang_array as $lang) {
$lang = str_replace(".php","",$lang);
$html .= "<option value='".$lang."'"; if($setup_array[11][1] == $lang){$html .= " selected";} $html .= " >".$lang."</option>";
}
$html .= "</select>";
$html .= "<input name='comment11' value='". $setup_array[11][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[11][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";


// Gallery Header Image Path
$html .= "<tr><td>Header Image:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable7' value='". $setup_array[7][0] ."' type='hidden'>";
$html .= "<input name='value7' value='". $setup_array[7][1] ."' size='30' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment7' value='". $setup_array[7][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[7][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";


// Gallery Folder
$html .= "<tr><td>Root Image Folder:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable8' value='". $setup_array[8][0] ."' type='hidden'>";
$html .= "<input name='value8' value='". $setup_array[8][1] ."' size='30' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment8' value='". $setup_array[8][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[8][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";


// Default Folder Image
$html .= "<tr><td>Default Folder Image:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable9' value='". $setup_array[9][0] ."' type='hidden'>";
$html .= "<input name='value9' value='". $setup_array[9][1] ."' size='30' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment9' value='". $setup_array[9][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[9][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";


// folder thumbnail
$html .= "<tr><td>Folder Thumbnail:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable10' value='". $setup_array[10][0] ."' type='hidden'>";
$html .= "<select name='value10' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[10][1] == "1"){$html .= " selected";} $html .= " >Generate thumbnail</option>";
$html .= "<option value='0'"; if($setup_array[10][1] == "0"){$html .= " selected";} $html .= " >Always use icon</option>";
$html .= "</select>";
$html .= "<input name='comment10' value='". $setup_array[10][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[10][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";


// Order number separator
$html .= "<tr><td>Order number separator:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable23' value='". $setup_array[23][0] ."' type='hidden'>";
$html .= "<input name='value23' value='". $setup_array[23][1] ."' size='3' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment23' value='". $setup_array[23][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[23][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";



// Display IMG count
$html .= "<tr><td>Display IMG count:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable21' value='". $setup_array[21][0] ."' type='hidden'>";
$html .= "<select name='value21' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[21][1] == "1"){$html .= " selected";} $html .= " >Display</option>";
$html .= "<option value='0'"; if($setup_array[21][1] == "0"){$html .= " selected";} $html .= " >Do Not display</option>";
$html .= "</select>";
$html .= "<input name='comment21' value='". $setup_array[21][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[21][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// Display SUB count
$html .= "<tr><td>Display SUB count:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable22' value='". $setup_array[22][0] ."' type='hidden'>";
$html .= "<select name='value22' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[22][1] == "1"){$html .= " selected";} $html .= " >Display</option>";
$html .= "<option value='0'"; if($setup_array[22][1] == "0"){$html .= " selected";} $html .= " >Do Not display</option>";
$html .= "</select>";
$html .= "<input name='comment22' value='". $setup_array[22][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[22][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// debug_mode
$html .= "<tr><td>Debug Mode:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable24' value='". $setup_array[24][0] ."' type='hidden'>";
$html .= "<select name='value24' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[24][1] == "1"){$html .= " selected";} $html .= " >ON - display errors</option>";
$html .= "<option value='0'"; if($setup_array[24][1] == "0"){$html .= " selected";} $html .= " >OFF - hide errors</option>";
$html .= "</select>";
$html .= "<input name='comment24' value='". $setup_array[24][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[24][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// session_menu_save
$html .= "<tr><td>Session Menu Save:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable25' value='". $setup_array[25][0] ."' type='hidden'>";
$html .= "<select name='value25' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[25][1] == "1"){$html .= " selected";} $html .= " >ON - SAVE FOR SESSION</option>";
$html .= "<option value='0'"; if($setup_array[25][1] == "0"){$html .= " selected";} $html .= " >OFF - Regenerate on every request</option>";
$html .= "</select>";
$html .= "<input name='comment25' value='". $setup_array[25][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[25][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

$html .= "</table><br><br>";



$html .= "<b>mod_rewrite (apache):</b>";
$html .= "<table class='gallerymenu'>";

// use_modrewrite
$html .= "<tr><td>Use mod_rewrite:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable26' value='". $setup_array[26][0] ."' type='hidden'>";
$html .= "<select name='value26' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[26][1] == "1"){$html .= " selected";} $html .= " >USE mod_rewrite and htaccess</option>";
$html .= "<option value='0'"; if($setup_array[26][1] == "0"){$html .= " selected";} $html .= " >USE normal URLs with ?</option>";
$html .= "</select>";
$html .= "<input name='comment26' value='". $setup_array[26][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[26][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// rewritebase
$html .= "<tr><td>RewriteBase:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable27' value='". $setup_array[27][0] ."' type='hidden'>";
$html .= "<input name='value27' value='". $setup_array[27][1] ."' size='15' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment27' value='". $setup_array[27][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[27][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";



$html .= "</table><br><br>";





$html .= "<b>THEME settings:</b>";
$html .= "<br><i>If the theme has different size thumb or large cache images, you should regenerate the cache.</i>";
$html .= "<table class='gallerymenu'>";

// Gallery Style
$html .= "<tr><td>Theme:&nbsp;&nbsp;&nbsp;</td><td>";

$html .= "<input name='variable2' value='". $setup_array[2][0] ."' type='hidden'>";
$html .= "<select name='value2' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
foreach($theme_array as $theme) {
$html .= "<option value='".$theme."'"; if($setup_array[2][1] == $theme){$html .= " selected";} $html .= " >".str_replace("_"," ",$theme)."</option>";
}
$html .= "</select>";
$html .= "<input name='comment2' value='". $setup_array[2][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[2][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";



// Menu Type
$html .= "<tr><td>Menu Type:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable3' value='". $setup_array[3][0] ."' type='hidden'>";
$html .= "<select name='value3' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[3][1] == "1"){$html .= " selected";} $html .= " >CSS Dropdown</option>";
$html .= "<option value='0'"; if($setup_array[3][1] == "0"){$html .= " selected";} $html .= " >UL list</option>";
$html .= "</select>";
$html .= "<input name='comment3' value='". $setup_array[3][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[3][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// Breadcrumbs
$html .= "<tr><td>Breadcrumbs menu:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable4' value='". $setup_array[4][0] ."' type='hidden'>";
$html .= "<select name='value4' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[4][1] == "1"){$html .= " selected";} $html .= " >Display</option>";
$html .= "<option value='0'"; if($setup_array[4][1] == "0"){$html .= " selected";} $html .= " >Do Not display</option>";
$html .= "</select>";
$html .= "<input name='comment4' value='". $setup_array[4][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[4][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

$html .= "</table><br><br>";









$html .= "<b>Image Caching:</b>";
$html .= "<table class='gallerymenu'>";

// Cache Thumbs
$html .= "<tr><td>CACHE:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable15' value='". $setup_array[15][0] ."' type='hidden'>";
$html .= "<select name='value15' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[15][1] == "1"){$html .= " selected";} $html .= " >Yes</option>";
$html .= "<option value='0'"; if($setup_array[15][1] == "0"){$html .= " selected";} $html .= " >No</option>";
$html .= "</select>";
$html .= "<input name='comment15' value='". $setup_array[15][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[15][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";


// Thumb Folder
$html .= "<tr><td>Folder:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable16' value='". $setup_array[16][0] ."' type='hidden'>";
$html .= "<input name='value16' value='". $setup_array[16][1] ."' size='30' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment16' value='". $setup_array[16][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[16][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// Cache Quality
$html .= "<tr><td>Quality:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable17' value='". $setup_array[17][0] ."' type='hidden'>";
$html .= "<input name='value17' value='". $setup_array[17][1] ."' size='3' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment17' value='". $setup_array[17][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[17][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

$html .= "</table><br><br>";







$html .= "<b>Copyright basic settings:</b>";
$html .= "<table class='gallerymenu'>";

// text_auto_color
$html .= "<tr><td>TEXT color variance:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable18' value='". $setup_array[18][0] ."' type='hidden'>";
$html .= "<input name='value18' value='". $setup_array[18][1] ."' size='3' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment18' value='". $setup_array[18][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[18][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";


// shadow_auto_color
$html .= "<tr><td>Shadow variance:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable19' value='". $setup_array[19][0] ."' type='hidden'>";
$html .= "<input name='value19' value='". $setup_array[19][1] ."' size='3' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment19' value='". $setup_array[19][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[19][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// darkness_threshold
$html .= "<tr><td>Darkness threshold:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable20' value='". $setup_array[20][0] ."' type='hidden'>";
$html .= "<input name='value20' value='". $setup_array[20][1] ."' size='3' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment20' value='". $setup_array[20][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[20][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

$html .= "</table><br><br>";






$html .= "<b>SLIDESHOW:</b>";
$html .= "<table class='gallerymenu'>";

// display_slideshow_link
$html .= "<tr><td>Display slideshow link:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable29' value='". $setup_array[29][0] ."' type='hidden'>";
$html .= "<select name='value29' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[29][1] == "1"){$html .= " selected";} $html .= " >Yes</option>";
$html .= "<option value='0'"; if($setup_array[29][1] == "0"){$html .= " selected";} $html .= " >No</option>";
$html .= "</select>";
$html .= "<input name='comment29' value='". $setup_array[29][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[29][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";


// slideshow_display_time
$html .= "<tr><td>Slideshow display time:&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable30' value='". $setup_array[30][0] ."' type='hidden'>";
$html .= "<input name='value30' value='". $setup_array[30][1] ."' size='3' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment30' value='". $setup_array[30][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[30][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

$html .= "</table><br><br>";









$html .= "<table class='gallerymenu'>";

$html .= "<tr><td><BR><b>URL Settings:</b></td><td></td></tr>";


// Page url
$html .= "<tr><td>Page</td><td>";
$html .= "<input name='variable12' value='". $setup_array[12][0] ."' type='hidden'>";
$html .= "<input name='value12' value='". $setup_array[12][1] ."' size='30' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment12' value='". $setup_array[12][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[12][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// folder url
$html .= "<tr><td>Folder</td><td>";
$html .= "<input name='variable13' value='". $setup_array[13][0] ."' type='hidden'>";
$html .= "<input name='value13' value='". $setup_array[13][1] ."' size='30' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment13' value='". $setup_array[13][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[13][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// image url
$html .= "<tr><td>Image</td><td>";
$html .= "<input name='variable14' value='". $setup_array[14][0] ."' type='hidden'>";
$html .= "<input name='value14' value='". $setup_array[14][1] ."' size='30' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment14' value='". $setup_array[14][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[14][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

// slideshow url
$html .= "<tr><td>Slideshow</td><td>";
$html .= "<input name='variable28' value='". $setup_array[28][0] ."' type='hidden'>";
$html .= "<input name='value28' value='". $setup_array[28][1] ."' size='30' type='text' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;' >";
$html .= "<input name='comment28' value='". $setup_array[28][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[28][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";




$html .= "<tr><td><BR><b>HTML5 Settings:</b></td><td></td></tr>";

// Video player
// Breadcrumbs
$html .= "<tr><td>Video Player&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable32' value='". $setup_array[32][0] ."' type='hidden'>";
$html .= "<select name='value32' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[32][1] == "1"){$html .= " selected";} $html .= " >Use Projekktor</option>";
$html .= "<option value='0'"; if($setup_array[32][1] == "0"){$html .= " selected";} $html .= " >Use default</option>";
$html .= "</select>";
$html .= "<input name='comment32' value='". $setup_array[32][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[32][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

$html .= "<tr><td>Audio Player&nbsp;&nbsp;&nbsp;</td><td>";
$html .= "<input name='variable33' value='". $setup_array[33][0] ."' type='hidden'>";
$html .= "<select name='value33' onFocus = 'javascript: void( highLight(this) );' onBlur = 'javascript: this.value = trimIt( this ) ;'>";
$html .= "<option value='1'"; if($setup_array[33][1] == "1"){$html .= " selected";} $html .= " >Use jPlayer</option>";
$html .= "<option value='0'"; if($setup_array[33][1] == "0"){$html .= " selected";} $html .= " >Use default</option>";
$html .= "</select>";
$html .= "<input name='comment33' value='". $setup_array[33][2] ."' type='hidden'>";
$html .= "<a  tooltip='". $setup_array[33][2] ."' href='' onclick='javascript:return false;'>?</a>";
$html .= "</td></tr>";

$html .= "</table><br><br>";
$html .= "</div>";






$html .= '
<br><br>
    <input type="hidden" name="rows" value="'.$i.'>">
    <input type="submit" name="Submit" value="SAVE CHANGES" class="button" >
    <input type=button class="button" value="CANCEL" onClick="history.go(-1)">
<td></td></td></tr>
</form>

';

} // end if file exists

} // end if form not submitted

} else { // if not logged in
$html .=('Sorry, you are not logged in, this area is restricted to admin.');
$html .= "<br><input type=button class='button' value='Back' onClick='history.go(-1)'>";
}



?>