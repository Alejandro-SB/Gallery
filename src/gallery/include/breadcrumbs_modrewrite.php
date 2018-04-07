<?php 

$rooturl = substr($currenturl, 0, (strlen($currenturl)-1));

    $breadcrumbs_code .= '<span class="gallerymenu">'.$lbl_currentlyviewing;
    $breadcrumbs_code .= '<a href="'.$rewrite_base.'">'.stripslashes($gallerytitle).'</a>';
    
    if ($getfolder!=="" || $getslide!=="") {
        
        	if ($getfolder!=="") { $current_path = $getfolder; } else { $current_path = $getslide; }
        	
        $displayfolders = explode("/",$current_path);
    
        if ($displayfolders[0]!="images"){
		
			$bc_name = $displayfolders[0];
			$bcnamefile = $galleryfolder."/".$bc_name."/".$_SESSION['lang'].".txt";
			if( file_exists($bcnamefile) ) {
				$fp = fopen($bcnamefile, "r");
				$bc_name = fread($fp, filesize($bcnamefile));
				fclose($fp);
			}
		
            		$breadcrumbs_code .= ' - <a href="'.$rewrite_base.$get_folder.'/'.$displayfolders[0].'">'.str_replace("_"," ", RemoveOrderNumber($bc_name) ).'</a>';
        }

	for ($i=1; $i <= sizeof($displayfolders); $i++) {

		if ($displayfolders[$i]!=null) { 
		$breadcrumbs_code .= ' - <a href="'.$rewrite_base.$get_folder.'/'.$displayfolders[0];
		$bc_path = "";
		for ($x=1; $x <= $i; $x++) {
		$breadcrumbs_code .= '/'.$displayfolders[$x];
		$bc_path .= '/'.$displayfolders[$x];
		}
		
		$bcnamefile = $galleryfolder."/".$displayfolders[0].$bc_path."/".$_SESSION['lang'].".txt";

		if( file_exists($bcnamefile) ) {
			$fp = fopen($bcnamefile, "r");
			$bc_name = fread($fp, filesize($bcnamefile));
			fclose($fp);
		} else {
			$bc_name = $displayfolders[$i];
		}		
		
		
		$breadcrumbs_code .= '">'.str_replace("_"," ", RemoveOrderNumber($bc_name) ).'</a>';
		}


	}


    }
    if ($getimage!=="") {
        $displayfolders = explode("/",$getimage);
        $x=0;
        $breadcrumbs = $displayfolders[$x];
        while ($x <= sizeof($displayfolders)){
        
        	$path_parts = pathinfo($displayfolders[$x]);
		$file_extension = strtolower($path_parts['extension']);
        
            if( in_array( $file_extension , $accepted_img ) || in_array( $file_extension , $accepted_vid ) ) {
            
                if ($displayfolders[$x]!=null) { 
                	$filename = substr($path_parts['basename'], 0, -(strlen($path_parts['extension']) + ($path_parts['extension'] == '' ? 0 : 1)));
			for ($i=1; $i < $x; $i++) {
				$bc_path .= '/'.$displayfolders[$i];
			}
			$imagename = $galleryfolder."/".$displayfolders[0].$bc_path."/".$filename."_name_".$_SESSION['lang'].".txt";
			if( file_exists($imagename) ) {
				$fp = fopen($imagename, "r");
				$imagename = fread($fp, filesize($imagename));
				fclose($fp);
			} else {
				$imagename = $filename;
			}
                	$breadcrumbs_code .= ' - '.str_replace("_"," ", $imagename ); 
                }
                $x = sizeof($displayfolders);
            } else {
                if ($displayfolders[$x]!=null) { 
			
			$breadcrumbs_code .= ' - <a href="'.$rewrite_base.$get_folder.'/'.$breadcrumbs.'">';
			$bcnamefile = $galleryfolder."/".$breadcrumbs."/".$_SESSION['lang'].".txt";
			if( file_exists($bcnamefile) ) {
				$fp = fopen($bcnamefile, "r");
				$bc_name = fread($fp, filesize($bcnamefile));
				fclose($fp);
			} else {	
				$bc_name = $displayfolders[$x];
			}
			
			$breadcrumbs_code .= str_replace("_"," ", RemoveOrderNumber($bc_name) ).'</a>';
		}		

                $x++;
            }
            $breadcrumbs .= "/".$displayfolders[$x];
        }
    }
    $breadcrumbs_code .= '</span>';

?>