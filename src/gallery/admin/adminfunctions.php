<?php



function buildadminmenu() {

Global $menulevel;
Global $foldername;
Global $folder_array;
Global $galleryfolder;



$menuprint = "";

$menuprint .= "<table border ='0' id='editlist'><tr>";
$menuprint .= "<td><b>FOLDER</b> (click for files)</td>";
$menuprint .= "<td class='td_p'><b>RENAME</b></td>";
$menuprint .= "<td class='td_p'><b>DELETE</b></td>";
$menuprint .= "<td class='td_p'><b>PREF</b></td>";
$menuprint .= "<td class='td_p'><b>SUB</b></td>";
$menuprint .= "</tr>";
$foldernumber = -1;
foreach($folder_array as $foldername) {
	if ($foldernumber == 1 && !isset($_GET[$get_folder] ) && isset($_GET[$get_image] )) {
		$getfolder = $foldername;
	}
	if (is_dir($galleryfolder."/".$foldername)) {
	if ($foldername=="." || $foldername=="..") {} else {
		$displayfoldername = RemoveOrderNumber($foldername);
		$displayfoldername = str_replace("_"," ", $displayfoldername);
			$menuprint .= '<tr><td>';
			$menuprint .= '<a href="?galleryadmin=filelist&folder='.$foldername.'">'.$displayfoldername.'</a><br>';
			$menuprint .= '</td>';
			$menuprint .= '<td class="td_p"><a href="?galleryadmin=rename&folder='.$foldername.'">Rename</a></td>';
			$menuprint .= '<td class="td_p"><a href="?galleryadmin=delete&folder='.$foldername.'">Delete</a></td>';
			
			$menuprint .= '<td class="td_p"><a href="?galleryadmin=preferences&file=images/'.$foldername.'/galleryconfig">';
			$configfile = '../images/'.$foldername.'/galleryconfig.php';
			if(file_exists($configfile)) {$menuprint .= 'EDIT';} else {$menuprint .= 'Set';}
			$menuprint .= '</a></td>';
			$menuprint .= '<td class="td_p"><a href="?galleryadmin=createfolder&folder='.$foldername.'">new</a></td>';
			
			
			$menuprint .= '</tr>';
$menulevel = 0;
			// build subMenu
			$subfolder = $galleryfolder.'/'.$foldername;
			if (is_dir($subfolder)) {
				$menuprint .= subMenu($subfolder);
			}

		$menuprint .= "";
	}
	}
$foldernumber++;
}  
$menuprint .= "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
//$menuprint .= "</table>";	




return $menuprint;

} // end function buildgallerymenu



function subMenu($path) {

Global $folder_array;
Global $getfolder;
Global $get_folder;
Global $galleryfolder;
Global $currenturl;
Global $menulevel;
$i=0;
$submenu_print = ""; 
$menulevel++;
				$gallerysub_dir = dir($path);
				$subfolder_array = array();
					while(($subfoldername = $gallerysub_dir->read()) !== false) { 
						if ($subfoldername=="." || $subfoldername=="..") {} else {
							if (is_dir($path."/".$subfoldername)) {	
							array_push($subfolder_array, $subfoldername); 
							}
						}
					}
					if (count($subfolder_array)>0) {
						$submenu_print .= "";
						foreach($subfolder_array as $subsubfolder) {
							$subfolderdisplayname = RemoveOrderNumber($subsubfolder);
							$subfolderdisplayname = str_replace("_"," ", $subfolderdisplayname);
							$submenu_print .= "<tr><td>";
							for($i=1;$i<=$menulevel;$i++) {
							$submenu_print .= "&nbsp;&nbsp;&nbsp;";
							}
							$submenu_print .= '&#187; <a href="?galleryadmin=filelist&folder='.str_replace($galleryfolder."/","",$path).'/'.$subsubfolder.'">'.$subfolderdisplayname.'</a><br>';
							$submenu_print .= '</td>';
							$submenu_print .= '<td class="td_p"><a href="?galleryadmin=rename&folder='.str_replace($galleryfolder."/","",$path).'/'.$subsubfolder.'">Rename</a></td>';
							$submenu_print .= '<td class="td_p"><a href="?galleryadmin=delete&folder='.str_replace($galleryfolder."/","",$path).'/'.$subsubfolder.'">Delete</a></td>';

			$submenu_print .= '<td class="td_p"><a href="?galleryadmin=preferences&file='.str_replace("../","",$path).'/'.$subsubfolder.'/galleryconfig">';
			$configfile = str_replace("../","",$path).'/'.$subsubfolder.'/galleryconfig.php';
			if(file_exists($configfile)) {$submenu_print .= 'EDIT';} else {$submenu_print .= 'Set';}
			$submenu_print .= '</a></td>';
			
			$submenu_print .= '<td class="td_p"><a href="?galleryadmin=createfolder&folder='.str_replace($galleryfolder."/","",$path).'/'.$subsubfolder.'">new</a></td>';
			

							$submenu_print .= '</tr>';
								// build subMenu
								$newpath="";
								$path_folders = explode('/',$path);
								for ($i=0;$i<sizeof($path_folders);$i++) {
								$newpath .= $path_folders[$i]."/";
								}
								$newpath .= $subsubfolder;
								if (is_dir($newpath)) {
									$submenu_print .= subMenu($newpath);
									$menulevel--;
								}
							
							$submenu_print .= '';
					
						}
			
					$submenu_print .= "";					
					
					
					
					}

return $submenu_print;

} // end function subMenu









function RemoveOrderNumber($foldername) {
Global $ordernumber_separator;

if(strpos($foldername,$ordernumber_separator)) {
	$displayfoldername = substr($foldername, (strpos($foldername,$ordernumber_separator)+2));
} else {
	$displayfoldername = $foldername;
}
return $displayfoldername;
}






?>